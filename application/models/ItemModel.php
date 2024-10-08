<?php
class ItemModel extends MasterModel{
    private $itemMaster = "item_master";
    private $unitMaster = "unit_master";
    private $itemCategory = "item_category";

    public function getDTRows($data){
        $data['tableName'] = $this->itemMaster;
        $data['select'] = "item_master.*,CAST(item_master.gst_per AS FLOAT) as gst_per,item_category.category_name,unit_master.unit_name";
        $data['leftJoin']['item_category'] = "item_category.id  = item_master.category_id";
        $data['leftJoin']['unit_master'] = "unit_master.id  = item_master.unit_id";

        $data['where']['item_master.item_type'] = $data['item_type'];

        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "item_master.item_code";
        $data['searchCol'][] = "item_master.item_name";
        $data['searchCol'][] = "item_category.category_name";
        $data['searchCol'][] = "unit_master.unit_name";
        $data['searchCol'][] = "item_master.make_brand";
        $data['searchCol'][] = "item_master.price";
        $data['searchCol'][] = "item_master.defualt_disc";

		$columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;

		if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
        return $this->pagingRows($data);
    }

    public function getScrapGroupDTRows($data){
        $data['tableName'] = $this->itemMaster;
        $data['select'] = "item_master.*,item_category.category_name,unit_master.unit_name";

        $data['leftJoin']['item_category'] = "item_category.id  = item_master.category_id";
        $data['leftJoin']['unit_master'] = "unit_master.id  = item_master.unit_id";

        $data['where']['item_master.item_type'] = "10";

        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "item_master.item_name";
        $data['searchCol'][] = "unit_master.unit_name";

		$columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;

		if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
        return $this->pagingRows($data);
    }

    public function getItemList($data=array()){
        $queryData['tableName'] = $this->itemMaster;
        $queryData['select'] = "item_master.*,unit_master.unit_name,item_category.category_name,item_category.batch_stock as stock_type";

        $queryData['leftJoin']['item_category'] = "item_category.id  = item_master.category_id";
        $queryData['leftJoin']['unit_master'] = "item_master.unit_id = unit_master.id";
        
        if(!empty($data['item_type'])):
            $queryData['where_in']['item_master.item_type'] = $data['item_type'];
        endif;

        return $this->rows($queryData);
    }

    public function getItem($data){
        $queryData['tableName'] = $this->itemMaster;
        $queryData['select'] = "item_master.*,unit_master.unit_name,item_category.category_name,item_category.batch_stock as stock_type";

        $queryData['leftJoin']['unit_master'] = "item_master.unit_id = unit_master.id";
        $queryData['leftJoin']['item_category'] = "item_category.id  = item_master.category_id";
        
        if(!empty($data['id'])):
            $queryData['where']['item_master.id'] = $data['id'];
        endif;

        if(!empty($data['item_code'])):
            $queryData['where']['item_master.item_code'] = trim($data['item_code']);
        endif;

        if(!empty($data['item_types'])):
            $queryData['where_in']['item_master.item_type'] = $data['item_types'];
        endif;

        return $this->row($queryData);
    }

    public function save($data){
        try{
            $this->db->trans_begin();

            if($this->checkDuplicate(['item_code'=>$data['item_code'],'id'=>$data['id']]) > 0):
                $errorMessage['item_code'] = "CAT No. is duplicate.";
                return ['status'=>0,'message'=>$errorMessage];
            endif;

            if($this->checkDuplicate($data) > 0):
                $errorMessage['item_name'] = "Name is duplicate.";
                return ['status'=>0,'message'=>$errorMessage];
            endif;

            $data['std_pck_qty'] = (!empty($data['std_pck_qty']))?$data['std_pck_qty']:1;
            /* $data['std_qty'] = (!empty($data['std_qty']))?$data['std_qty']:1;
            $data['sec_unit_id'] = (!empty($data['sec_unit_id']))?$data['sec_unit_id']:$data['unit_id']; */
            
            $result = $this->store($this->itemMaster,$data,"Item");            

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }

    public function saveBulkItems($itemData){
        try{
            $this->db->trans_begin();

            $i=0;$itemResult = array();
            if(!empty($itemData)):
                foreach($itemData as $row):
                    $fname = Array();
                    if(!empty($row['item_code'])){$fname[] = $row['item_code'];}
                    if(!empty($row['item_name'])){$fname[] = $row['item_name'];}
                    if(!empty($row['part_no'])){$fname[] = $row['part_no'];}
                    $row['full_name'] = (!empty($fname)) ? implode(' - ',$fname) : '';			
                    
                    if(!empty($row['hsn_code'])):
                        $hsnData = $this->hsnModel->getHSNDetail(['hsn'=>$row['hsn_code']]);
                        $row['gst_per'] = $hsnData->gst_per;
                    endif;

                    $itemResult[] = $this->store($this->itemMaster,$row,"Item");         
                    $i++;
                endforeach;
            endif;
            
            $result = ['status' => 1, 'message' => $i . ' Record import successfully.','itemResult'=>$itemResult];

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
        $queryData['tableName'] = $this->itemMaster;

        if(!empty($data['item_name']))
            $queryData['where']['item_name'] = $data['item_name'];
        if(!empty($data['item_type']))
            $queryData['where']['item_type'] = $data['item_type'];
        if(!empty($data['item_code']))
            $queryData['where']['item_code'] = $data['item_code'];
        if(!empty($data['id']))
            $queryData['where']['id !='] = $data['id'];

        $queryData['resultType'] = "numRows";
        return $this->specificRow($queryData);
    }

    public function delete($id){
        try{
            $this->db->trans_begin();

            $checkData['columnName'] = ["item_id","scrap_group"];
            $checkData['value'] = $id;
            $checkUsed = $this->checkUsage($checkData);
            
            if($checkUsed == true):
                return ['status'=>0,'message'=>'The Item is currently in use. you cannot delete it.'];
            endif;

            $result = $this->trash($this->itemMaster,['id'=>$id],'Item');

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }

    public function itemUnits(){
        $queryData['tableName'] = $this->unitMaster;
		return $this->rows($queryData);
	}

    public function itemUnit($id){
        $queryData['tableName'] = $this->unitMaster;
		$queryData['where']['id'] = $id;
		return $this->row($queryData);
	}
}
?>