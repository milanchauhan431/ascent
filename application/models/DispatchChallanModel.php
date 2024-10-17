<?php
class DispatchChallanModel extends MasterModel{
    private $transMain = "trans_main";
    private $transChild = "trans_child";
    private $dispatchTrans = "dispatch_trans";

    public function getDTRows($data){
        $data['tableName'] = $this->dispatchTrans;
        $data['select'] = "dispatch_trans.*,trans_main.trans_number,trans_main.party_name,trans_child.item_name,trans_child.job_number";

        $data['leftJoin']['trans_main'] = "dispatch_trans.so_id = trans_main.id";
        $data['leftJoin']['trans_child'] = "dispatch_trans.so_trans_id = trans_child.id";

        $data['where']['dispatch_trans.dispatch_date >='] = $this->startYearDate;
        $data['where']['dispatch_trans.dispatch_date <='] = $this->endYearDate;

        $data['order_by']['dispatch_trans.dispatch_date'] = "DESC";
        $data['order_by']['dispatch_trans.chl_no'] = "DESC";

        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "dispatch_trans.challan_no";
        $data['searchCol'][] = "DATE_FORMAT(dispatch_trans.dispatch_date,'%d-%m-%Y')";
        $data['searchCol'][] = "dispatch_trans.invoice_no";
        $data['searchCol'][] = "dispatch_trans.vehicle_no";
        $data['searchCol'][] = "trans_main.trans_number";
        $data['searchCol'][] = "trans_child.job_number";
        $data['searchCol'][] = "trans_main.party_name";
        $data['searchCol'][] = "trans_child.item_name";
        $data['searchCol'][] = "dispatch_trans.qty";
        $data['searchCol'][] = "dispatch_trans.remark";

        $columns =array(); 
        foreach($data['searchCol'] as $row): 
            $columns[] = $row; 
        endforeach;
        if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
        
        return $this->pagingRows($data);
    }

    public function getPendingDispatchItems($data){
        $queryData = array();
        $queryData['tableName'] = $this->transChild;
        $queryData['select'] = "trans_child.*,(trans_child.qty - trans_child.dispatch_qty) as pending_qty";

        $queryData['where']['trans_child.trans_main_id'] = $data['id'];
        $queryData['where']['trans_child.trans_status'] = 0;

        $result = $this->rows($queryData);
        return $result;
    }

    public function getNectNo(){
        $queryData = [];
        $queryData['tableName'] = $this->dispatchTrans;
        $queryData['select'] = "IFNULL((MAX(chl_no) + 1),1) as next_no";
        $queryData['where']['dispatch_date >='] = $this->startYearDate;
        $queryData['where']['dispatch_date <='] = $this->endYearDate;
        $result = $this->row($queryData)->next_no;
        return $result;
    }

    public function saveDispatchDetails($data){
        try{
            $this->db->trans_begin();

            if(!empty($data['is_edit'])):
                $this->trash($this->dispatchTrans,['chl_no'=>$data['chl_no']]);
            else:
                $data['chl_prefix'] = "DC/".$this->shortYear.'/';
                $data['chl_no'] = $this->dispatchChallan->getNectNo();
                $data['challan_no'] = $data['chl_prefix'].$data['chl_no'];
            endif;

            foreach($data['itemData'] as $row):
                if(floatval($row['qty']) > 0):                    
                    $row['dispatch_date'] = $data['dispatch_date'];
                    $row['vehicle_no'] = $data['vehicle_no'];
                    $row['chl_prefix'] = $data['chl_prefix'];
                    $row['chl_no'] = $data['chl_no'];
                    $row['challan_no'] = $data['challan_no'];
                    $row['invoice_no'] = $data['invoice_no'];
                    $row['remark'] = $data['remark'];
                    $row['is_delete'] = 0;

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
    }

    public function getDispatchedItemList($data){
        $queryData = array();
        $queryData['tableName'] = $this->dispatchTrans;
        $queryData['select'] = "dispatch_trans.*,trans_child.item_name,trans_child.job_number,(trans_child.qty - trans_child.dispatch_qty) as pending_qty,trans_child.qty as order_qty";

        $queryData['leftJoin']['trans_child'] = "dispatch_trans.so_trans_id = trans_child.id";

        $queryData['where']['dispatch_trans.chl_no'] = $data['chl_no'];

        $result = $this->rows($queryData);
        return $result;
    }

    public function getDispatchedItem($data){
        $queryData = array();
        $queryData['tableName'] = $this->dispatchTrans;
        $queryData['select'] = "dispatch_trans.*,trans_child.item_name,trans_child.job_number,(trans_child.qty - trans_child.dispatch_qty) as pending_qty,trans_child.qty as order_qty";

        $queryData['leftJoin']['trans_child'] = "dispatch_trans.so_trans_id = trans_child.id";

        $queryData['where']['dispatch_trans.id'] = $data['id'];

        $result = $this->row($queryData);
        return $result;
    }

    public function deleteDispatchTrans($id){
        try{
            $this->db->trans_begin();

            $transData = $this->getDispatchedItemList(['chl_no'=>$id]);

            foreach($transData as $row):
                $result = $this->trash($this->dispatchTrans,['id'=>$row->id],'Dispatch Item');

                $setData = Array();
                $setData['tableName'] = $this->transChild;
                $setData['where']['id'] = $row->so_trans_id;
                $setData['set_value']['dispatch_qty'] = 'IF(`dispatch_qty` - '.floatval($row->qty).' >= 0, `dispatch_qty` - '.floatval($row->qty).', 0)';
                $setData['update']['trans_status'] = "(CASE WHEN dispatch_qty >= qty THEN 1 ELSE 0 END)";
                $this->setValue($setData);
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
}
?>