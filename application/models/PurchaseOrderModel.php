<?php
class PurchaseOrderModel extends MasterModel{
    private $transMain = "trans_main";
    private $transChild = "trans_child";
    private $transExpense = "trans_expense";
    private $transDetails = "trans_details";
    private $purchseReq = "purchase_request";

    public function getDTRows($data){
        $data['tableName'] = $this->transChild;
        $data['select'] = "trans_child.id as trans_child_id,trans_child.item_code,trans_child.item_name,trans_child.make,trans_child.price,trans_child.disc_per,trans_child.qty,trans_child.dispatch_qty as received_qty,(trans_child.qty - trans_child.dispatch_qty) as pending_qty,trans_child.item_remark,trans_main.id,trans_main.trans_number,DATE_FORMAT(trans_main.trans_date,'%d-%m-%Y') as trans_date,trans_main.party_name,trans_main.sales_type,trans_child.job_number,trans_child.trans_status,employee_master.emp_name as created_by_name";

        $data['leftJoin']['trans_main'] = "trans_main.id = trans_child.trans_main_id";
        $data['leftJoin']['employee_master'] = "trans_main.created_by = employee_master.id";

        $data['where']['trans_child.entry_type'] = $data['entry_type'];
        $data['where']['trans_child.trans_status'] = $data['status'];
        if($data['status'] != 0):
            $data['where']['trans_main.trans_date >='] = $this->startYearDate;
        endif;
        $data['where']['trans_main.trans_date <='] = $this->endYearDate;

        $data['order_by']['trans_main.trans_date'] = "DESC";
        $data['order_by']['trans_main.id'] = "DESC";

        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "trans_main.trans_number";
        $data['searchCol'][] = "DATE_FORMAT(trans_main.trans_date,'%d-%m-%Y')";
        $data['searchCol'][] = "trans_main.party_name";
        if(!empty($data['list_type'])):
            $data['group_by'][] = "trans_child.trans_main_id";
            
            $data['searchCol'][] = "employee_master.emp_name";
        else:
            $data['searchCol'][] = "trans_child.job_number";
            $data['searchCol'][] = "trans_child.item_code";
            $data['searchCol'][] = "trans_child.item_name";
            $data['searchCol'][] = "trans_child.make";
            $data['searchCol'][] = "trans_child.price";
            $data['searchCol'][] = "trans_child.disc_per";
            $data['searchCol'][] = "trans_child.qty";
            $data['searchCol'][] = "trans_child.dispatch_qty";
            $data['searchCol'][] = "(trans_child.qty - trans_child.dispatch_qty)";
            $data['searchCol'][] = "trans_child.item_remark";
        endif;
        

        $columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;
        if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
        
        return $this->pagingRows($data);
    }

    public function save($data){
        try{
            $this->db->trans_begin();

            /* if($this->checkDuplicate($data) > 0):
                $errorMessage['trans_number'] = "PO. No. is duplicate.";
                return ['status'=>0,'message'=>$errorMessage];
            endif; */

            if(empty($data['id'])):
                $data['trans_prefix'] = $this->transMainModel->getTransPrefix($data['entry_type']);
                $data['trans_no'] = $this->transMainModel->nextTransNo($data['entry_type']);
                $data['trans_number'] = $data['trans_prefix'].$data['trans_no'];
            endif;

            if(!empty($data['id'])):
                $itemList = $this->getPurchaseOrderItems(['id'=>$data['id']]);
                foreach($itemList as $row):
                    if(!empty($row->ref_id)):
                        if(!empty($row->feasible_remark)):
                            $piDetails = json_decode($row->feasible_remark,false);
                            foreach($piDetails as $jrow):
                                $setData = Array();
                                $setData['tableName'] = $this->purchseReq;
                                $setData['where']['id'] = $jrow->id;
                                $setData['set']['po_qty'] = 'po_qty, - '.$jrow->qty;
                                $setData['update']['order_status'] = 1;
                                $this->setValue($setData);
                            endforeach;
                        else:                        
                            $setData = Array();
                            $setData['tableName'] = $this->purchseReq;
                            $setData['where']['id'] = $row->ref_id;
                            $setData['set']['po_qty'] = 'po_qty, - '.$row->qty;
                            $setData['update']['order_status'] = 1;
                            $this->setValue($setData);
                        endif;
                    endif;
                    $this->trash($this->transChild,['id'=>$row->id]);
                endforeach;

                $this->trash($this->transExpense,['trans_main_id'=>$data['id']]);
                $this->remove($this->transDetails,['main_ref_id'=>$data['id'],'table_name'=>$this->transMain,'description'=>"PO TERMS"]);
                $this->remove($this->transDetails,['main_ref_id'=>$data['id'],'table_name'=>$this->transMain,'description'=>"PO MASTER DETAILS"]);
            endif;
            
            $masterDetails = $data['masterDetails'];
            $itemData = $data['itemData'];

            $transExp = getExpArrayMap($data['expenseData']);
			$expAmount = $transExp['exp_amount'];
            $termsData = $data['termsData'];

            unset($transExp['exp_amount'],$data['itemData'],$data['expenseData'],$data['termsData'],$data['masterDetails']);		

            $result = $this->store($this->transMain,$data,'Purchase Order');

            $masterDetails['id'] = "";
            $masterDetails['main_ref_id'] = $result['id'];
            $masterDetails['table_name'] = $this->transMain;
            $masterDetails['description'] = "PO MASTER DETAILS";
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
                    $row['description'] = "PO TERMS";
                    $row['main_ref_id'] = $result['id'];
                    $this->store($this->transDetails,$row);
                endforeach;
            endif;

            foreach($itemData as $row):
                $row['entry_type'] = $data['entry_type'];
                $row['trans_main_id'] = $result['id'];
                $row['is_delete'] = 0;
                
                $reqItemDetails = array();
                if(!empty($row['ref_id'])):
                    if(!empty($row['feasible_remark'])):
                        $piDetails = json_decode($row['feasible_remark'],false);
                        $poQty = $row['qty'];
                        foreach($piDetails as $jrow):
                            if($poQty > 0):
                                $qty = 0;
                                $qty = ($poQty > $jrow->qty)?$jrow->qty:$poQty;
                                $poQty -= $qty;

                                $setData = Array();
                                $setData['tableName'] = $this->purchseReq;
                                $setData['where']['id'] = $jrow->id;
                                $setData['set']['po_qty'] = 'po_qty, + '.$qty;
                                $setData['update']['order_status'] = "(CASE WHEN req_qty >= po_qty THEN 2 ELSE 1 END)";
                                $this->setValue($setData);

                                $reqItemDetails[] = ['id'=>$jrow->id,'qty'=>$qty,'req_qty'=>$jrow->qty];
                            endif;
                        endforeach;
                        $row['feasible_remark'] = json_encode($reqItemDetails);
                    else:                        
                        $setData = Array();
                        $setData['tableName'] = $this->purchseReq;
                        $setData['where']['id'] = $row['ref_id'];
                        $setData['set']['po_qty'] = 'po_qty, + '.$row['qty'];
                        $setData['update']['order_status'] = "(CASE WHEN req_qty >= po_qty THEN 2 ELSE 1 END)";
                        $this->setValue($setData);
                    endif;                    
                endif;

                $row['sec_unit_id'] = (!empty($row['sec_unit_id']))?$row['sec_unit_id']:$row['unit_id'];
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

    public function getPurchaseOrder($data){
        $queryData = array();
        $queryData['tableName'] = $this->transMain;
        $queryData['select'] = "trans_main.*, trans_details.i_col_1 as transport_id, transport_master.transport_name, transport_master.transport_id as transport_gstin, trans_details.t_col_1 as contact_person, trans_details.t_col_2 as contact_no, trans_details.t_col_3 as delivery_address";
        $queryData['leftJoin']['trans_details'] = "trans_main.id = trans_details.main_ref_id AND trans_details.description = 'PO MASTER DETAILS' AND trans_details.table_name = '".$this->transMain."'";
        $queryData['leftJoin']['transport_master'] = "trans_details.i_col_1 = transport_master.id";
        $queryData['where']['trans_main.id'] = $data['id'];
        $result = $this->row($queryData);

        if($data['itemList'] == 1):
            $result->itemList = $this->getPurchaseOrderItems($data);
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
        $queryData['where']['description'] = "PO TERMS";
        $result->termsConditions = $this->rows($queryData);

        return $result;
    }

    public function getPurchaseOrderItems($data){
        $queryData = array();
        $queryData['tableName'] = $this->transChild;
        $queryData['select'] = "trans_child.*,sec_unit.unit_name as sec_unit_name";
        $queryData['leftJoin']['unit_master as sec_unit'] = "sec_unit.id = trans_child.sec_unit_id";
        $queryData['where']['trans_child.trans_main_id'] = $data['id'];
        $result = $this->rows($queryData);
        return $result;
    }

    public function delete($id){
        try{
            $this->db->trans_begin();

            $itemList = $this->getPurchaseOrderItems(['id'=>$id]);
            foreach($itemList as $row):
                if(!empty($row->feasible_remark)):
                    $piDetails = json_decode($row->feasible_remark,false);
                    foreach($piDetails as $jrow):
                        $setData = Array();
                        $setData['tableName'] = $this->purchseReq;
                        $setData['where']['id'] = $jrow->id;
                        $setData['set']['po_qty'] = 'po_qty, - '.$jrow->qty;
                        $setData['update']['order_status'] = 1;
                        $this->setValue($setData);
                    endforeach;
                else:                        
                    $setData = Array();
                    $setData['tableName'] = $this->purchseReq;
                    $setData['where']['id'] = $row->ref_id;
                    $setData['set']['po_qty'] = 'po_qty, - '.$row->qty;
                    $setData['update']['order_status'] = 1;
                    $this->setValue($setData);
                endif;
                $this->trash($this->transChild,['id'=>$row->id]);
            endforeach;

            $this->trash($this->transExpense,['trans_main_id'=>$id]);
            $this->remove($this->transDetails,['main_ref_id'=>$id,'table_name'=>$this->transMain,'description'=>"PO TERMS"]);
            $result = $this->trash($this->transMain,['id'=>$id],'Purchase Order');

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }

    public function completePoItem($data){
        try{
            $this->db->trans_begin();

            $this->edit($this->transChild,['id'=>$data['trans_child_id']],['trans_status'=>1]);

            $setData = Array();
            $setData['tableName'] = $this->transMain;
            $setData['where']['id'] = $data['id'];
            $setData['update']['trans_status'] = "(SELECT IF( COUNT(id) = SUM(IF(trans_status = 1, 1, 0)) ,1 , 0 ) as trans_status FROM trans_child WHERE trans_main_id = ".$data['id']." AND is_delete = 0)";
            $result = $this->setValue($setData);
            $result["message"] = "Order item complete successfully.";

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }

    public function cancelPO($data){
        try{
            $this->db->trans_begin();

            $this->edit($this->transChild,['id'=>$data['trans_child_id']],['trans_status'=>3]);

            $setData = Array();
            $setData['tableName'] = $this->transMain;
            $setData['where']['id'] = $data['id'];
            $setData['update']['trans_status'] = "(SELECT IF( COUNT(id) = SUM(IF(trans_status = 3, 1, 0)) ,3 , 0 ) as trans_status FROM trans_child WHERE trans_main_id = ".$data['id']." AND is_delete = 0)";
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

    public function getItemWisePoList($data){
        $queryData['tableName'] = $this->transChild;
        $queryData['select'] = "trans_main.id as po_id,trans_main.trans_number,trans_child.id as po_trans_id,trans_child.qty,trans_child.dispatch_qty as received_qty,(trans_child.qty - trans_child.dispatch_qty) as pending_qty,trans_child.price,trans_child.disc_per";

        $queryData['leftJoin']['trans_main'] = "trans_main.id = trans_child.trans_main_id";

        $queryData['where']['trans_child.entry_type'] = 21;

        $queryData['where']['trans_child.item_id'] = $data['item_id'];
        $queryData['where']['(trans_child.qty - trans_child.dispatch_qty) >'] = 0;

        return $this->rows($queryData);
    }

    public function getPartyWisePoList($data){
        $queryData['tableName'] = $this->transMain;
        $queryData['select'] = "trans_main.id as po_id,trans_main.trans_number";

        $queryData['where']['trans_main.entry_type'] = 21;
        $queryData['where']['trans_main.party_id'] = $data['party_id'];
        $queryData['where']['trans_main.trans_status'] = 0;

        return $this->rows($queryData);
    }

    public function getPendingPoItems($data){
        $queryData['tableName'] = $this->transChild;
        $queryData['select'] = "trans_child.id as po_trans_id,trans_child.item_id,item_master.item_code,item_master.item_name,trans_child.qty,trans_child.dispatch_qty as received_qty,(trans_child.qty - trans_child.dispatch_qty) as pending_qty,trans_child.price,trans_child.disc_per";

        $queryData['leftJoin']['item_master'] = "item_master.id = trans_child.item_id";

        $queryData['where']['trans_child.entry_type'] = 21;
        $queryData['where']['trans_child.trans_main_id'] = $data['po_id'];
        $queryData['where']['(trans_child.qty - trans_child.dispatch_qty) >'] = 0;

        return $this->rows($queryData);
    }
}
?>