<?php
class PurchaseIndentModel extends MasterModel{
    private $purchaseReq = "purchase_request";
    
    public function nextIndentNo() {
        $data['tableName'] = $this->purchaseReq;
        $data['select'] = "MAX(req_no) as req_no";
        $data['where']['DATE_FORMAT(purchase_request.req_date,"%Y-%m-%d") >='] = $this->startYearDate;
        $data['where']['DATE_FORMAT(purchase_request.req_date,"%Y-%m-%d") <='] = $this->endYearDate;
        $maxNo = $this->specificRow($data)->req_no;
        $nextIndentNo = (!empty($maxNo)) ? ($maxNo + 1) : 1;
        return $nextIndentNo;
    }

    public function getDTRows($data){
        $data['tableName'] = $this->purchaseReq;
        $data['select'] = "purchase_request.*,order_bom.uom,order_bom.make,item_master.item_code,item_master.item_name,trans_child.job_number";

        $data['leftJoin']['order_bom'] = "purchase_request.bom_id = order_bom.id";
        $data['leftJoin']['trans_child'] = "trans_child.id = order_bom.trans_child_id";
        $data['leftJoin']['item_master'] = "item_master.id = order_bom.item_id";       

        if(empty($data['status'])):
            $data['where_in']['purchase_request.order_status'] = [0,1];
            $data['where']['DATE_FORMAT(purchase_request.req_date,"%Y-%m-%d") <='] = $this->endYearDate;
        else:
            $data['where']['purchase_request.order_status'] = $data['status'];
            $data['where']['DATE_FORMAT(purchase_request.req_date,"%Y-%m-%d") >='] = $this->startYearDate;
            $data['where']['DATE_FORMAT(purchase_request.req_date,"%Y-%m-%d") <='] = $this->endYearDate;
        endif;

        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "DATE_FORMAT(purchase_request.req_date,'%d-%m-%Y')";
        $data['searchCol'][] = "CONCAT('IND',LPAD(purchase_request.req_no, 3, '0'))";
        $data['searchCol'][] = "trans_child.job_number";
        $data['searchCol'][] = "order_bom.make";
        $data['searchCol'][] = "item_master.item_name";
        $data['searchCol'][] = "order_bom.uom";
        $data['searchCol'][] = "purchase_request.req_qty";
        $data['searchCol'][] = "purchase_request.remark";
        $data['searchCol'][] = "";

        $columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;
        if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
        
        return $this->pagingRows($data);
    }

    public function changeRequestStatus($data){
        try{
            $this->db->trans_begin();

            $data['approved_by'] = $this->loginId;
            $data['approved_at'] = date("Y-m-d H:i:s");
            $result = $this->store($this->purchaseReq,$data);

            $msg = "";
            if($data['order_status'] == 1):
                $msg = "Approved";
            elseif($data['order_status'] == 2):
                $msg = "Closed";
            elseif($data['order_status'] == 3):
                $queryData = [];
                $queryData['tableName'] = $this->purchaseReq;
                $queryData['where']['id'] = $data['id'];
                $reqDetail = $this->row($queryData);

                $setData = Array();
                $setData['tableName'] = "order_bom";;
                $setData['where']['id'] = $reqDetail->bom_id;
                $setData['set_value']['req_qty'] = 'IF(`req_qty` - '.floatval($reqDetail->req_qty).' >= 0, `req_qty` - '.floatval($reqDetail->req_qty).', 0)';
                $this->setValue($setData);

                $msg = "Canceled";
            endif;

                

            $result['message'] = "Request ".$msg. " Successfully.";

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }

    public function getRequestItem($data){
        $queryData['tableName'] = $this->purchaseReq;
        $queryData['select'] = "purchase_request.*,order_bom.item_id,order_bom.uom,order_bom.make,item_master.item_code,item_master.item_name,item_master.hsn_code,item_master.gst_per,order_bom.price,order_bom.disc_per,item_master.unit_id,unit_master.unit_name";
        $queryData['leftJoin']['order_bom'] = "purchase_request.bom_id = order_bom.id";
        $queryData['leftJoin']['item_master'] = "item_master.id = order_bom.item_id";
        $queryData['leftJoin']['unit_master'] = "unit_master.id = item_master.unit_id";
        $queryData['where']['purchase_request.order_status'] = 1;
        $queryData['where']['purchase_request.id'] = $data['id'];
        return $this->row($queryData);
    }

    public function getRequestItems($ids){
        $queryData['tableName'] = $this->purchaseReq;
        $queryData['select'] = "purchase_request.*,order_bom.item_id,order_bom.uom,order_bom.make,item_master.item_code,item_master.item_name,item_master.hsn_code,item_master.gst_per,item_master.std_pck_qty,item_master.std_qty,item_master.sec_unit_id,order_bom.price,order_bom.disc_per,item_master.unit_id,unit_master.unit_name,trans_child.job_number";
        $queryData['leftJoin']['order_bom'] = "purchase_request.bom_id = order_bom.id";
        $queryData['leftJoin']['trans_child'] = "trans_child.id = order_bom.trans_child_id";
        $queryData['leftJoin']['item_master'] = "item_master.id = order_bom.item_id";
        $queryData['leftJoin']['unit_master'] = "unit_master.id = item_master.unit_id";
        $queryData['where']['purchase_request.order_status'] = 1;
        $queryData['where_in']['purchase_request.id'] = str_replace("~",",",$ids);
        return $this->rows($queryData);
    }

    public function getRequestItemsForPo($ids=""){
        $queryData['tableName'] = $this->purchaseReq;
        $queryData['select']  = "GROUP_CONCAT(purchase_request.id) as ref_id,order_bom.item_id,purchase_request.req_qty,SUM(purchase_request.req_qty - purchase_request.po_qty) as qty,order_bom.uom,order_bom.make,item_master.item_code,item_master.item_name,item_master.hsn_code,item_master.gst_per,item_master.std_pck_qty,item_master.std_qty,item_master.sec_unit_id,order_bom.price,order_bom.disc_per,item_master.unit_id,unit_master.unit_name,GROUP_CONCAT(DISTINCT(trans_child.job_number) SEPARATOR ',') as job_number,CONCAT('[',GROUP_CONCAT(json_object('id',purchase_request.id,'qty',(purchase_request.req_qty - purchase_request.po_qty)) ORDER BY purchase_request.id SEPARATOR ','),']') as req_ref_list";

        $queryData['leftJoin']['order_bom'] = "purchase_request.bom_id = order_bom.id";
        $queryData['leftJoin']['trans_child'] = "trans_child.id = order_bom.trans_child_id";
        $queryData['leftJoin']['item_master'] = "item_master.id = order_bom.item_id";
        $queryData['leftJoin']['unit_master'] = "unit_master.id = item_master.unit_id";

        $queryData['where']['purchase_request.order_status'] = 1;
        if(!empty($ids)):
            $queryData['where_in']['purchase_request.id'] = str_replace("~",",",$ids);
        endif;
        $queryData['having'][] = "SUM(purchase_request.req_qty - purchase_request.po_qty) > 0";

        $queryData['group_by'][] = "order_bom.item_id,order_bom.price,order_bom.disc_per"; 
        return $this->rows($queryData);
    }
}
?>