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
        $data['select'] = "trans_child.id as trans_child_id,trans_child.item_name,trans_child.qty,trans_child.dispatch_qty,(trans_child.qty - trans_child.dispatch_qty) as pending_qty,trans_main.id,trans_main.trans_number,DATE_FORMAT(trans_main.trans_date,'%d-%m-%Y') as trans_date,trans_main.party_name,trans_main.sales_type";

        $data['leftJoin']['trans_main'] = "trans_main.id = trans_child.trans_main_id";

        $data['where']['trans_child.entry_type'] = $data['entry_type'];
        $data['where']['trans_child.trans_status'] = $data['status'];
        $data['where']['trans_main.trans_date >='] = $this->startYearDate;
        $data['where']['trans_main.trans_date <='] = $this->endYearDate;

        $data['order_by']['trans_main.trans_date'] = "DESC";
        $data['order_by']['trans_main.id'] = "DESC";

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
            endif;
            
            $itemData = $data['itemData'];

            $transExp = getExpArrayMap($data['expenseData']);
			$expAmount = $transExp['exp_amount'];
            $termsData = $data['termsData'];

            unset($transExp['exp_amount'],$data['itemData'],$data['expenseData'],$data['termsData']);		

            $result = $this->store($this->transMain,$data,'Sales Order');

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

            foreach($itemData as $row):
                $row['entry_type'] = $data['entry_type'];
                $row['trans_main_id'] = $result['id'];
                $row['is_delete'] = 0;
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
        $queryData['where']['id'] = $data['id'];
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
        $queryData['where']['trans_main_id'] = $data['id'];
        $result = $this->rows($queryData);
        return $result;
    }

    public function delete($id){
        try{
            $this->db->trans_begin();

            $this->trash($this->transChild,['trans_main_id'=>$id]);
            $this->trash($this->transExpense,['trans_main_id'=>$id]);
            $this->remove($this->transDetails,['main_ref_id'=>$data['id'],'table_name'=>$this->transMain,'description'=>"SO TERMS"]);
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

    public function saveOrderBom($data){
        try{
            $this->db->trans_begin();
            
            foreach($data['itemData'] as $row):
                $row['id'] = "";
                $this->store($this->orderBom,$row);
            endforeach;

            $result = ['status'=>1,'message'=>'Order Bom saved successfully.'];

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }

    public function getOrderBomItems($data){
        $queryData['tableName'] = $this->orderBom;
        $queryData['select'] = "order_bom.*,item_master.item_name,(trans_child.qty * order_bom.qty) as reqired_qty,ifnull(SUM(stock_transaction.qty),0) as stock_qty";

        $queryData['leftJoin']['trans_child'] = "order_bom.trans_child_id = trans_child.id";
        $queryData['leftJoin']['item_master'] = "order_bom.item_id = item_master.id";
        $queryData['leftJoin']['stock_transaction'] = "order_bom.item_id = stock_transaction.item_id";

        $queryData['where']['order_bom.trans_child_id'] = $data['trans_child_id'];

        $queryData['group_by'][] = "order_bom.id";
        return $this->rows($queryData);
    }

    public function savePurchaseRequest($data){
        try{
            $this->db->trans_begin();
            
            foreach($data['itemData'] as $row):
                if(!empty($row['req_qty'])):
                    $row['req_no'] = $this->purchaseIndent->nextIndentNo();
                    $row['id'] = "";
                    $row['req_date'] = $data['req_date'];
                    $this->store($this->purchseReq,$row);

                    $setData = Array();
                    $setData['tableName'] = $this->orderBom;
                    $setData['where']['id'] = $row['bom_id'];
                    $setData['set']['req_qty'] = 'req_qty, + '.$row['req_qty'];
                    $this->setValue($setData);
                endif;
            endforeach;

            $result = ['status'=>1,'message'=>'Request sent successfully.'];

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