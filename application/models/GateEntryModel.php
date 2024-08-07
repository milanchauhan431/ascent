<?php
class GateEntryModel extends MasterModel{
    private $mir = "mir";

    public function getNextNo($type = 1){
        $queryData['tableName'] = $this->mir;
        $queryData['select'] = "ifnull(MAX(trans_no + 1),1) as next_no";
        $queryData['where']['trans_type'] = $type;
        $queryData['where']['trans_date >='] = $this->startYearDate;
        $queryData['where']['trans_date <='] = $this->endYearDate;
        return $this->row($queryData)->next_no;
    }

    public function getDTRows($data){
        $data['tableName'] = $this->mir;
        $data['select'] = "mir.id,mir.trans_number,DATE_FORMAT(mir.trans_date,'%d-%m-%Y') as trans_date,mir.driver_name,mir.driver_contact,mir.vehicle_no,mir.transporter,mir.vehicle_type,vehicle_types.vehicle_type as vehicle_type_name,transport_master.transport_name,mir.qty as no_of_items,mir.trans_status,mir.lr,mir.inv_no,mir.inv_date,mir.doc_no,mir.doc_date,party_master.party_name";
        
        $data['leftJoin']['vehicle_types'] = "vehicle_types.id = mir.vehicle_type";
        $data['leftJoin']['transport_master'] = "transport_master.id = mir.transporter";
        $data['leftJoin']['party_master'] = "party_master.id = mir.party_id";
        
        $data['where']['mir.trans_status'] = $data['status'];
        $data['where']['mir.trans_type'] = 1;
        
        $data['order_by']['mir.id'] = "DESC";

        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "mir.trans_number";
		$data['searchCol'][] = "DATE_FORMAT(mir.trans_date,'%d-%m-%Y')";
        $data['searchCol'][] = "party_master.party_name";
		$data['searchCol'][] = "transport_master.transport_name";
		$data['searchCol'][] = "mir.lr";
		$data['searchCol'][] = "vehicle_types.vehicle_type";
		$data['searchCol'][] = "mir.vehicle_no";
		$data['searchCol'][] = "mir.inv_no";
        $data['searchCol'][] = "IF(mir.inv_date IS NOT NULL,DATE_FORMAT(mir.inv_date,'%d-%m-%Y'),'')";
        $data['searchCol'][] = "mir.doc_no";
        $data['searchCol'][] = "IF(mir.doc_date IS NOT NULL,DATE_FORMAT(mir.doc_date,'%d-%m-%Y'),'')";

        $columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;
		if (isset($data['order'])) {
			$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];
		}

		return $this->pagingRows($data);
    }

    public function save($data){
        try{
            $this->db->trans_begin();

            $result = $this->store($this->mir,$data,"Gate Entry");

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }

    public function getGateEntry($id){
        $queryData = array();
        $queryData['tableName'] = $this->mir;
        $queryData['select'] = "mir.*,item_master.item_name,item_master.item_code,item_master.item_type";
        $queryData['leftJoin']['item_master'] = "item_master.id = mir.item_id";
        $queryData['where']['mir.id'] = $id;
        $result = $this->row($queryData);
        return $result;
    }

    public function delete($id){
        try{
            $this->db->trans_begin();

            $result = $this->trash($this->mir,['id'=>$id],'Gate Entry');

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