<?php
class ProductionModel extends MasterModel{
    private $productionMaster = "production_master";
    private $transMain = "trans_main";
    private $transChild = "trans_child";
    private $orderBom = "order_bom";
    private $purchseReq = "purchase_request";

    public function getEstimationDTRows($data){
        $data['tableName'] = $this->transChild;
        $data['select'] = "trans_child.id as trans_child_id,trans_child.trans_main_id,trans_child.item_name,trans_child.qty,trans_child.job_number,trans_main.trans_number,DATE_FORMAT(trans_main.trans_date,'%d-%m-%Y') as trans_date,trans_main.party_name,(CASE WHEN obc.order_bom_count > 0 THEN 'Generated' ELSE 'Pending' END) as bom_status,production_master.fab_dept_note,production_master.pc_dept_note,production_master.ass_dept_note,(CASE WHEN production_master.priority = 1 THEN 'HIGH' WHEN production_master.priority = 2 THEN 'MEDIUM' WHEN production_master.priority = 3 THEN 'LOW' ELSE '' END) as priority_status,production_master.priority,production_master.remark,production_master.id";

        $data['leftJoin']['trans_main'] = "trans_main.id = trans_child.trans_main_id";
        $data['leftJoin']['(SELECT `trans_child_id`, COUNT(`id`) as `order_bom_count` FROM `order_bom` WHERE is_delete = 0 GROUP BY `trans_child_id`) as obc'] = "obc.trans_child_id = trans_child.id";
        $data['leftJoin']['production_master'] = "production_master.trans_child_id = trans_child.id";

        $data['where']['trans_child.entry_type'] = $data['entry_type'];

        if($data['job_status'] == 0):
            $data['where']['production_master.id'] = null;
        elseif($data['job_status'] == 1):
            $data['where']['production_master.id >'] = 0;
            $data['where']['production_master.ref_id'] = 0;
        endif;
        
        $data['where']['trans_main.trans_date >='] = $this->startYearDate;
        $data['where']['trans_main.trans_date <='] = $this->endYearDate;

        $data['order_by']['trans_main.trans_date'] = "DESC";
        $data['order_by']['trans_main.id'] = "DESC";

        $data['group_by'][] = "trans_child.id";

        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "trans_child.job_number";
        $data['searchCol'][] = "DATE_FORMAT(trans_main.trans_date,'%d-%m-%Y')";
        $data['searchCol'][] = "trans_main.party_name";
        $data['searchCol'][] = "trans_child.item_name";
        $data['searchCol'][] = "trans_child.qty";
        $data['searchCol'][] = "(CASE WHEN obc.order_bom_count > 0 THEN 'Generated' ELSE 'Pending' END)";
        $data['searchCol'][] = "(CASE WHEN production_master.priority = 1 THEN 'HIGH' WHEN production_master.priority = 2 THEN 'MEDIUM' WHEN production_master.priority = 3 THEN 'LOW' END)";
        $data['searchCol'][] = "production_master.fab_dept_note";
        $data['searchCol'][] = "production_master.pc_dept_note";
        $data['searchCol'][] = "production_master.ass_dept_note";
        $data['searchCol'][] = "production_master.remark";

        $columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;
        if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
        
        return $this->pagingRows($data); //$this->printQuery();
    }

    public function saveOrderBom($postData){
        try{
            $this->db->trans_begin();
            
            foreach($postData as $row):
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
        $queryData['select'] = "order_bom.*,item_master.item_name,(trans_child.qty * order_bom.qty) as reqired_qty,ifnull(SUM(stock_transaction.qty * stock_transaction.p_or_m),0) as stock_qty,trans_child.trans_status";

        $queryData['leftJoin']['trans_child'] = "order_bom.trans_child_id = trans_child.id";
        $queryData['leftJoin']['item_master'] = "order_bom.item_id = item_master.id";
        $queryData['leftJoin']['stock_transaction'] = "order_bom.item_id = stock_transaction.item_id";

        $queryData['where']['order_bom.trans_child_id'] = $data['trans_child_id'];

        $queryData['group_by'][] = "order_bom.id";
        return $this->rows($queryData);
    }

    public function removeBomItem($id){
        try{
            $this->db->trans_begin();

            $result = $this->trash($this->orderBom,['id'=>$id],'Bom Item');

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
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

    public function getProductionMaster($data=array()){
        $queryData['tableName'] = $this->productionMaster;
        $queryData['select'] = "production_master.*";
        $queryData['where']['production_master.id'] = $data['id'];
        return $this->row($queryData);
    }

    public function saveEstimation($data){
        try{
            $this->db->trans_begin();

            $result = $this->store($this->productionMaster,$data,"Estimation");

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