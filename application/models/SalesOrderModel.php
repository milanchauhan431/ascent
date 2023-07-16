<?php
class SalesOrderModel extends MasterModel{
    private $transMain = "trans_main";
    private $transChild = "trans_child";
    private $transExpense = "trans_expense";
    private $transDetails = "trans_details";
    private $orderBom = "order_bom";
    private $purchseReq = "purchase_request";

    public function getDTRows($data){
        $data['tableName'] = $this->transChild;
        $data['select'] = "trans_child.id as trans_child_id,trans_child.item_name,trans_child.qty,trans_child.dispatch_qty,(trans_child.qty - trans_child.dispatch_qty) as pending_qty,trans_child.job_number,trans_main.id,trans_main.trans_number,DATE_FORMAT(trans_main.trans_date,'%d-%m-%Y') as trans_date,trans_main.party_name,trans_main.sales_type,COUNT(order_bom.id) as bom_items";

        $data['leftJoin']['trans_main'] = "trans_main.id = trans_child.trans_main_id";
        $data['leftJoin']['order_bom'] = "order_bom.trans_child_id = trans_child.id";

        $data['where']['trans_child.entry_type'] = $data['entry_type'];

        if($data['status'] == 0):
            $data['where']['trans_child.trans_status'] = 0;
            $data['having'][] = 'COUNT(order_bom.id) <= 0';
        elseif($data['status'] == 1):
            $data['where']['trans_child.trans_status'] = 0;
            $data['having'][] = 'COUNT(order_bom.id) > 0';
        else:
            $data['where']['trans_child.trans_status'] = 1;
        endif;
        
        $data['where']['trans_main.trans_date >='] = $this->startYearDate;
        $data['where']['trans_main.trans_date <='] = $this->endYearDate;

        $data['order_by']['trans_main.trans_date'] = "DESC";
        $data['order_by']['trans_main.id'] = "DESC";

        $data['group_by'][] = "trans_child.id";

        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "trans_main.trans_number";
        $data['searchCol'][] = "DATE_FORMAT(trans_main.trans_date,'%d-%m-%Y')";
        $data['searchCol'][] = "trans_main.party_name";
        $data['searchCol'][] = "trans_child.item_name";
        $data['searchCol'][] = "trans_child.qty";
        $data['searchCol'][] = "trans_child.dispatch_qty";
        $data['searchCol'][] = "(trans_child.qty - trans_child.dispatch_qty)";

        $columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;
        if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
        
        return $this->pagingRows($data);
    }

    public function getNextJobChar(){
        $queryData['tableName'] = $this->transChild;
        $queryData['select'] = "MAX(job_char) as job_char";
        $queryData['where']['entry_type'] = 20;
        $result =  $this->row($queryData)->job_char;

        $nextChar = (!empty($result) && $result != 'Z')? ++$result : 'A';        
        return $nextChar;
    }

    public function getNextJobNo(){
        $queryData['tableName'] = $this->transChild;
        $queryData['select'] = "ifnull((MAX(job_no) + 1),1) as job_no";
        $queryData['where']['entry_type'] = 20;
        $result =  $this->row($queryData)->job_no;
        return $result;
    }

    public function save($data){
        try{
            $this->db->trans_begin();

            if($this->checkDuplicate($data) > 0):
                $errorMessage['trans_number'] = "SO. No. is duplicate.";
                $result = ['status'=>0,'message'=>$errorMessage];
            endif;

            if(!empty($data['id'])):
                $this->trash($this->transChild,['trans_main_id'=>$data['id']]);
                $this->trash($this->transExpense,['trans_main_id'=>$data['id']]);
                $this->remove($this->transDetails,['main_ref_id'=>$data['id'],'table_name'=>$this->transMain,'description'=>"SO TERMS"]);
                $this->remove($this->transDetails,['main_ref_id'=>$data['id'],'table_name'=>$this->transMain,'description'=>"SO MASTER DETAILS"]);
            endif;
            
            $masterDetails = $data['masterDetails'];
            $itemData = $data['itemData'];

            $transExp = getExpArrayMap($data['expenseData']);
			$expAmount = $transExp['exp_amount'];
            $termsData = (!empty($data['termsData']))?$data['termsData']:array();

            unset($transExp['exp_amount'],$data['itemData'],$data['expenseData'],$data['termsData'],$data['masterDetails']);		

            $result = $this->store($this->transMain,$data,'Sales Order');

            $masterDetails['id'] = "";
            $masterDetails['main_ref_id'] = $result['id'];
            $masterDetails['table_name'] = $this->transMain;
            $masterDetails['description'] = "SO MASTER DETAILS";
            $this->store($this->transDetails,$masterDetails);

            $expenseData = array();
            if($expAmount <> 0):				
				$expenseData = $transExp;
                $expenseData['id'] = "";
				$expenseData['trans_main_id'] = $result['id'];
                $this->store($this->transExpense,$expenseData);
			endif;

            if(!empty($termsData)):
                foreach($termsData as $row):
                    $row['id'] = "";
                    $row['table_name'] = $this->transMain;
                    $row['description'] = "SO TERMS";
                    $row['main_ref_id'] = $result['id'];
                    $this->store($this->transDetails,$row);
                endforeach;
            endif;

            $partyData = $this->party->getParty(['id'=>$data['party_id']]);
            //$jobPrefix = "AE-".$data['order_type']."-".$partyData->party_code."-";
            if($data['order_type'] == "P"):
                $jobPrefix = "AE/".$data['order_type']."-";
            else:
                $jobPrefix = "AE-".$data['order_type']."-";
            endif;

            $i=1;
            foreach($itemData as $row):
                $row['entry_type'] = $data['entry_type'];
                $row['trans_main_id'] = $result['id'];
                $row['is_delete'] = 0;
                if(empty($row['id'])):
                    $row['job_no'] = $this->getNextJobNo();
                    $row['job_number'] = $jobPrefix.sprintf("%04d",$row['job_no']);
                    if($data['order_type'] == "P"):
                        $row['job_char'] = $this->getNextJobChar();
                        $row['job_number'] = $row['job_number']."-".$i++.$row['job_char'];
                    endif;
                endif;
                $this->store($this->transChild,$row);
            endforeach;
            

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }

    public function checkDuplicate($data){
        $queryData['tableName'] = $this->transMain;
        $queryData['where']['trans_number'] = $data['trans_number'];

        if(!empty($data['id']))
            $queryData['where']['id !='] = $data['id'];

        $queryData['resultType'] = "numRows";
        return $this->specificRow($queryData);
    }

    public function getSalesOrder($data){
        $queryData = array();
        $queryData['tableName'] = $this->transMain;
        $queryData['select'] = "trans_main.*,trans_details.t_col_1 as contact_person,trans_details.t_col_2 as contact_no,trans_details.t_col_3 as ship_address";
        $queryData['leftJoin']['trans_details'] = "trans_main.id = trans_details.main_ref_id AND trans_details.description = 'SO MASTER DETAILS' AND trans_details.table_name = '".$this->transMain."'";
        $queryData['where']['trans_main.id'] = $data['id'];
        $result = $this->row($queryData);

        if($data['itemList'] == 1):
            $result->itemList = $this->getSalesOrderItems($data);
        endif;

        $queryData = array();
        $queryData['tableName'] = $this->transExpense;
        $queryData['where']['trans_main_id'] = $data['id'];
        $result->expenseData = $this->row($queryData);

        $queryData = array();
        $queryData['tableName'] = $this->transDetails;
        $queryData['select'] = "i_col_1 as term_id,t_col_1 as term_title,t_col_2 as condition";
        $queryData['where']['main_ref_id'] = $data['id'];
        $queryData['where']['table_name'] = $this->transMain;
        $queryData['where']['description'] = "SO TERMS";
        $result->termsConditions = $this->rows($queryData);

        return $result;
    }

    public function getSalesOrderItems($data){
        $queryData = array();
        $queryData['tableName'] = $this->transChild;
        $queryData['select'] = "trans_child.*,COUNT(order_bom.id) as bom_items";
        $queryData['leftJoin']['order_bom'] = "order_bom.trans_child_id = trans_child.id";
        $queryData['where']['trans_child.trans_main_id'] = $data['id'];
        $result = $this->rows($queryData);
        return $result;
    }

    public function getSalesOrderItem($data){
        $queryData = array();
        $queryData['tableName'] = $this->transChild;
        $queryData['where']['id'] = $data['id'];
        $result = $this->row($queryData);
        return $result;
    }

    public function delete($id){
        try{
            $this->db->trans_begin();

            $this->trash($this->transChild,['trans_main_id'=>$id]);
            $this->trash($this->transExpense,['trans_main_id'=>$id]);
            $this->remove($this->transDetails,['main_ref_id'=>$id,'table_name'=>$this->transMain,'description'=>"SO TERMS"]);
            $this->remove($this->transDetails,['main_ref_id'=>$id,'table_name'=>$this->transMain,'description'=>"SO MASTER DETAILS"]);
            $result = $this->trash($this->transMain,['id'=>$id],'Sales Order');

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }
}
?>