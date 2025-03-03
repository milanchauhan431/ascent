<?php
class SalesOrderModel extends MasterModel{
    private $transMain = "trans_main";
    private $transChild = "trans_child";
    private $transExpense = "trans_expense";
    private $transDetails = "trans_details";
    private $orderBom = "order_bom";
    private $purchseReq = "purchase_request";
    private $dispatchTrans = "dispatch_trans";

    public function getDTRows($data){
        $data['tableName'] = $this->transChild;
        $data['select'] = "trans_child.id as trans_child_id,trans_child.item_name,trans_child.qty,trans_child.dispatch_qty,(trans_child.qty - trans_child.dispatch_qty) as pending_qty,trans_child.job_number,trans_child.trans_status,trans_main.id,trans_main.trans_number,DATE_FORMAT(trans_main.trans_date,'%d-%m-%Y') as trans_date,trans_main.party_name,trans_main.sales_type,production_master.job_status";
        //COUNT(order_bom.id) as bom_items,

        $data['leftJoin']['trans_main'] = "trans_main.id = trans_child.trans_main_id";
        //$data['leftJoin']['order_bom'] = "order_bom.trans_child_id = trans_child.id";
        $data['leftJoin']['production_master'] = "production_master.trans_child_id = trans_child.id AND production_master.entry_type = 27";

        $data['where']['trans_child.entry_type'] = $data['entry_type'];

        if($data['status'] == 0): // Pending Orders
            $data['where']['trans_child.trans_status'] = 0;
            $data['where']['production_master.job_status'] = null;

            $data['where']['trans_main.trans_date <='] = $this->endYearDate;
        elseif($data['status'] == 1): // Production in Process
            $data['where']['trans_child.trans_status'] = 0;
            $data['where_in']['production_master.job_status'] = [0,1,2];

            $data['where']['trans_main.trans_date <='] = $this->endYearDate;
        elseif($data['status'] == 2): // Completed Order
            $data['where']['trans_child.trans_status'] = 1;

            $data['where']['trans_main.trans_date >='] = $this->startYearDate;
            $data['where']['trans_main.trans_date <='] = $this->endYearDate;
        elseif($data['status'] == 3): // Cancled Order
            $data['where']['trans_child.trans_status'] = 3;

            $data['where']['trans_main.trans_date >='] = $this->startYearDate;
            $data['where']['trans_main.trans_date <='] = $this->endYearDate;
        else: // Pending For Dispatch
            $data['where']['trans_child.trans_status'] = 0;
            $data['where_in']['production_master.job_status'] = [3];

            $data['where']['trans_main.trans_date <='] = $this->endYearDate;
        endif;

        $data['order_by']['trans_main.trans_date'] = "DESC";
        $data['order_by']['trans_main.id'] = "DESC";

        $data['group_by'][] = "trans_child.id";

        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "trans_main.trans_number";
        $data['searchCol'][] = "DATE_FORMAT(trans_main.trans_date,'%d-%m-%Y')";
        $data['searchCol'][] = "trans_child.job_number";
        $data['searchCol'][] = "trans_main.party_name";
        $data['searchCol'][] = "trans_child.item_name";
        $data['searchCol'][] = "trans_child.qty";
        $data['searchCol'][] = "trans_child.dispatch_qty";
        $data['searchCol'][] = "(trans_child.qty - trans_child.dispatch_qty)";

        $columns =array(); 
        foreach($data['searchCol'] as $row): 
            $columns[] = $row; 
        endforeach;
        if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
        
        return $this->pagingRows($data);
    }

    public function getNextJobChar(){
        $queryData['tableName'] = $this->transChild;
        //$queryData['select'] = "MAX(job_char) as job_char";
        $queryData['select'] = "job_char";
        $queryData['where']['entry_type'] = 20;
        $queryData['where']['job_char !='] = "";
        $queryData['order_by']['id'] = "DESC";
        $result =  $this->row($queryData)->job_char;

        $nextChar = (!empty($result) && $result != 'Z')? ++$result : 'A';
        return $nextChar;
    }

    public function getNextJobNo($order_type = ""){
        $queryData['tableName'] = $this->transChild;
        $queryData['select'] = "ifnull((MAX(trans_child.job_no) + 1),1) as job_no";
        $queryData['leftJoin']['trans_main'] = "trans_main.id = trans_child.trans_main_id";
        $queryData['where']['trans_child.entry_type'] = 20;
        if(!empty($order_type)):
            $queryData['where']['trans_main.order_type'] = $order_type;
        endif;
        $result =  $this->row($queryData)->job_no;

        if(in_array($order_type,["F","S"]) && $result == 1):
            $result = 2061;
        endif;
        
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
            if($data['order_type'] == "P"):
                $jobPrefix = "AE/".$data['order_type']."-";
            else:
                $jobPrefix = "AE-".$data['order_type']."-";
            endif;

            $job_no = $this->getNextJobNo($data['order_type']);
                    
            $i=1;
            foreach($itemData as $row):
                $row['entry_type'] = $data['entry_type'];
                $row['trans_main_id'] = $result['id'];
                $row['is_delete'] = 0;
                if(empty($row['id'])):
                    $row['job_no'] = $job_no;
                    $row['job_number'] = $jobPrefix.sprintf("%04d",$row['job_no'])."-".$i;
                    if($data['order_type'] == "P"):
                        $row['job_char'] = $this->getNextJobChar();
                        $row['job_number'] = $row['job_number'].$row['job_char'];
                    endif;
                    $i++;
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
        $queryData['select'] = "trans_child.*,if(production_master.job_status IS NULL,0,production_master.job_status) as job_status";
        $queryData['leftJoin']['production_master'] = "production_master.trans_child_id = trans_child.id";
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

    public function cancelSO($data){
        try{
            $this->db->trans_begin();

            $soData = $this->getSalesOrder(['id'=>$data['id'],'itemList'=>0]);

            $this->edit($this->transChild,['id'=>$data['trans_child_id']],['trans_status'=>3]);

            $setData = Array();
            $setData['tableName'] = $this->transMain;
            $setData['where']['id'] = $data['id'];
            $setData['update']['trans_status'] = "(SELECT IF( COUNT(id) = SUM(IF(trans_status = 3, 1, 0)) ,3 , ".$soData->trans_status." ) as trans_status FROM trans_child WHERE trans_main_id = ".$data['id']." AND is_delete = 0)";
            $result = $this->setValue($setData);
            $result["message"] = "Order item canceled successfully.";

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }

    /* public function getPendingDispatchItems($data){
        $queryData = array();
        $queryData['tableName'] = $this->transChild;
        $queryData['select'] = "trans_child.*,(trans_child.qty - trans_child.dispatch_qty) as pending_qty";

        $queryData['where']['trans_child.trans_main_id'] = $data['id'];
        $queryData['where']['trans_child.trans_status'] = 0;

        $result = $this->rows($queryData);
        return $result;
    } */

    /* public function getDispatchedItemList($data){
        $queryData = array();
        $queryData['tableName'] = $this->dispatchTrans;
        $queryData['select'] = "dispatch_trans.*,trans_child.item_name,trans_child.job_number";

        $queryData['leftJoin']['trans_child'] = "dispatch_trans.so_trans_id = trans_child.id";

        $queryData['where']['dispatch_trans.so_id'] = $data['id'];

        $result = $this->rows($queryData);
        return $result;
    } */

    /* public function saveDispatchDetails($data){
        try{
            $this->db->trans_begin();

            foreach($data['itemData'] as $row):
                if(floatval($row['qty']) > 0):
                    $row['id'] = "";
                    $row['dispatch_date'] = $data['dispatch_date'];
                    $row['vehicle_no'] = $data['vehicle_no'];
                    $row['challan_no'] = $data['challan_no'];
                    $row['invoice_no'] = $data['invoice_no'];
                    $row['remark'] = $data['remark'];

                    $result = $this->store($this->dispatchTrans,$row,'Dispatch Detail');

                    $setData = Array();
                    $setData['tableName'] = $this->transChild;
                    $setData['where']['id'] = $row['so_trans_id'];
                    $setData['set']['dispatch_qty'] = 'dispatch_qty, + '.$row['qty'];
                    $setData['update']['trans_status'] = "(CASE WHEN dispatch_qty >= qty THEN 1 ELSE 0 END)";
                    $this->setValue($setData);
                endif;
            endforeach;

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    } */

    /* public function deleteDispatchTrans($id){
        try{
            $this->db->trans_begin();

            $queryData = array();
            $queryData['tableName'] = $this->dispatchTrans;
            $queryData['where']['dispatch_trans.id'] = $id;
            $transData = $this->row($queryData);

            $result = $this->trash($this->dispatchTrans,['id'=>$id],'Dispatch Item');

            $setData = Array();
            $setData['tableName'] = $this->transChild;
            $setData['where']['id'] = $transData->so_trans_id;
            $setData['set_value']['dispatch_qty'] = 'IF(`dispatch_qty` - '.floatval($transData->qty).' >= 0, `dispatch_qty` - '.floatval($transData->qty).', 0)';
            $setData['update']['trans_status'] = "(CASE WHEN dispatch_qty >= qty THEN 1 ELSE 0 END)";
            $this->setValue($setData);

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    } */
}
?>