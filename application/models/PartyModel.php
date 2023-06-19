<?php
class PartyModel extends MasterModel{
    private $partyMaster = "party_master";
    private $countries = "countries";
	private $states = "states";
	private $cities = "cities";
    private $transDetails = "trans_details";

    public function getPartyCode($category=1){
        $queryData['tableName'] = $this->partyMaster;
        $queryData['select'] = "ifnull((MAX(CAST(party_code AS UNSIGNED)) + 1),1) as code";
        $queryData['where']['party_category'] = $category;
        $result = $this->row($queryData)->code;
        return $result;
    }

    public function getDTRows($data){
        $data['tableName'] = $this->partyMaster;
        $data['where']['party_category'] = $data['party_category'];

        $data['searchCol'][] = "";
		$data['searchCol'][] = "";
        if($data['party_category'] == 1):
            $data['searchCol'][] = "party_name";
			$data['searchCol'][] = "contact_person";
			$data['searchCol'][] = "party_mobile";
			$data['searchCol'][] = "party_code";
			$data['searchCol'][] = "currency";
        elseif($data['party_category'] == 2):
            $data['searchCol'][] = "party_name";
			$data['searchCol'][] = "contact_person";
			$data['searchCol'][] = "party_mobile";
			$data['searchCol'][] = "party_code";
        else:
            $data['searchCol'][] = "party_name";
			$data['searchCol'][] = "contact_person";
			$data['searchCol'][] = "party_mobile";
			$data['searchCol'][] = "party_address";
        endif;

        $columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;
        if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
        
        return $this->pagingRows($data);
    }

    public function getPartyList($data=array()){
        $queryData = array();
        $queryData['tableName']  = $this->partyMaster;
        
        if(!empty($data['party_category'])):
            $queryData['where_in']['party_category'] = $data['party_category'];
        endif;
        return $this->rows($queryData);
    }

    public function getParty($data){
        $queryData = array();
        $queryData['tableName']  = $this->partyMaster;
        $queryData['where']['id'] = $data['id'];

        if(!empty($data['party_category'])):
            $queryData['where_in']['party_category'] = $data['party_category'];
        endif;
        return $this->row($queryData);
    }

    public function getCurrencyList(){
		$queryData['tableName'] = 'currency';
		return $this->rows($queryData);
	}

    public function getCountries(){
		$queryData['tableName'] = $this->countries;
		$queryData['order_by']['name'] = "ASC";
		return $this->rows($queryData);
	}

    public function getCountry($data){
		$queryData['tableName'] = $this->countries;
		$queryData['where']['id'] = $data['id'];
		return $this->row($queryData);
	}

    public function getStates($data=array()){
        $queryData['tableName'] = $this->states;
		$queryData['where']['country_id'] = $data['country_id'];
		$queryData['order_by']['name'] = "ASC";
		return $this->rows($queryData);
    }

    public function getState($data){
        $queryData['tableName'] = $this->states;
		$queryData['where']['id'] = $data['id'];
		return $this->row($queryData);
    }

    public function getCities($data=array()){
        $queryData['tableName'] = $this->cities;
		$queryData['where']['state_id'] = $data['state_id'];
		$queryData['order_by']['name'] = "ASC";
		return $this->rows($queryData);
    }

    public function getCity($data){
        $queryData['tableName'] = $this->cities;
		$queryData['where']['id'] = $data['id'];
		return $this->row($queryData);
    }

    public function save($data){
		try {
			$this->db->trans_begin();
			if ($this->checkDuplicate($data) > 0) :
				$errorMessage['party_name'] = "Company name is duplicate.";
				$result = ['status' => 0, 'message' => $errorMessage];
            endif;
			
            $result = $this->store($this->partyMaster, $data, 'Party');
            $data['party_id'] = $result['id'];
            $this->saveGstDetail($data);				
			
			if ($this->db->trans_status() !== FALSE) :
				$this->db->trans_commit();
				return $result;
			endif;
		} catch (\Exception $e) {
			$this->db->trans_rollback();
			return ['status' => 2, 'message' => "somthing is wrong. Error : " . $e->getMessage()];
		}
	}

    public function checkDuplicate($data){
        $queryData['tableName'] = $this->partyMaster;
        $data['where']['party_name'] = $data['party_name'];
		$data['where']['party_category'] = $data['party_category'];
        
        if(!empty($data['id']))
            $queryData['where']['id !='] = $data['id'];
        
        $queryData['resultType'] = "numRows";
        return $this->specificRow($queryData);
    }

    public function delete($id){
		try {
			$this->db->trans_begin();

            $checkData['columnName'] = ['party_id'];
            $checkData['value'] = $id;
            $checkUsed = $this->checkUsage($checkData);

            if($checkUsed == true):
                return ['status'=>0,'message'=>'The Party is currently in use. you cannot delete it.'];
            endif;

			$result = $this->trash($this->partyMaster, ['id' => $id], 'Party');

			if ($this->db->trans_status() !== FALSE) :
				$this->db->trans_commit();
				return $result;
			endif;
		} catch (\Exception $e) {
			$this->db->trans_rollback();
			return ['status' => 2, 'message' => "somthing is wrong. Error : " . $e->getMessage()];
		}
	}

    public function getPartyGSTDetail($data){
        $queryData = array();
        $queryData['tableName'] = $this->transDetails;
        $queryData['select'] = "id, main_ref_id as party_id, t_col_1 as gstin, t_col_2 as party_address, t_col_3 as party_pincode, t_col_4 as delivery_address, t_col_5 as delivery_pincode";
        $queryData['where']['main_ref_id'] = $data['party_id'];
        $queryData['where']['table_name'] = $this->partyMaster;
        $queryData['where']['description'] = "PARTY GST DETAIL";
        return $this->rows($queryData);
    }

    public function saveGstDetail($data){
        try {
			$this->db->trans_begin();

            $queryData['tableName'] = $this->transDetails;
            $queryData['where']['main_ref_id'] = $data['party_id'];
            $queryData['where']['table_name'] = $this->partyMaster;
            $queryData['where']['description'] = "PARTY GST DETAIL";
            $queryData['where']['t_col_1'] = $data['gstin'];
            $gstData = $this->row($queryData);

            $postData = [
                'id' => (!empty($gstData))?$gstData->id:"",
                'main_ref_id' =>  $data['party_id'],
                'table_name' => $this->partyMaster,
                'description' => "PARTY GST DETAIL",
                't_col_1' => $data['gstin'],
                't_col_2' => $data['party_address'],
			    't_col_3' => $data['party_pincode'],
                't_col_4' => $data['delivery_address'],
                't_col_5' => $data['delivery_pincode']
            ];

            $result = $this->store($this->transDetails,$postData);

            if ($this->db->trans_status() !== FALSE) :
				$this->db->trans_commit();
				return $result;
			endif;
		} catch (\Exception $e) {
			$this->db->trans_rollback();
			return ['status' => 2, 'message' => "somthing is wrong. Error : " . $e->getMessage()];
		}
    }

    public function deleteGstDetail($id){
		try {
			$this->db->trans_begin();

			$result = $this->trash($this->transDetails, ['id' => $id], 'Party GST Detail');

			if ($this->db->trans_status() !== FALSE) :
				$this->db->trans_commit();
				return $result;
			endif;
		} catch (\Exception $e) {
			$this->db->trans_rollback();
			return ['status' => 2, 'message' => "somthing is wrong. Error : " . $e->getMessage()];
		}
	}

    public function getPartyContactDetail($data){
        $queryData = array();
        $queryData['tableName'] = $this->transDetails;
        $queryData['select'] = "id, main_ref_id as party_id, t_col_1 as contact_person, t_col_2 as mobile_no, t_col_3 as contact_email";
        $queryData['where']['main_ref_id'] = $data['party_id'];
        $queryData['where']['table_name'] = $this->partyMaster;
        $queryData['where']['description'] = "PARTY CONTACT DETAIL";
        return $this->rows($queryData);
    }

    public function saveContactDetail($data){
        try {
			$this->db->trans_begin();

            $postData = [
                'id' => "",
                'main_ref_id' => $data['party_id'],
                'table_name' => $this->partyMaster,
                'description' => "PARTY CONTACT DETAIL",
                't_col_1' => $data['person'],
                't_col_2' => $data['mobile'],
			    't_col_3' => $data['email']
            ];

            $result = $this->store($this->transDetails,$postData,'Contact Detail');

            if ($this->db->trans_status() !== FALSE) :
				$this->db->trans_commit();
				return $result;
			endif;
		} catch (\Exception $e) {
			$this->db->trans_rollback();
			return ['status' => 2, 'message' => "somthing is wrong. Error : " . $e->getMessage()];
		}
    }

    public function deleteContactDetail($id){
		try {
			$this->db->trans_begin();

			$result = $this->trash($this->transDetails, ['id' => $id], 'Contact Detail');

			if ($this->db->trans_status() !== FALSE) :
				$this->db->trans_commit();
				return $result;
			endif;
		} catch (\Exception $e) {
			$this->db->trans_rollback();
			return ['status' => 2, 'message' => "somthing is wrong. Error : " . $e->getMessage()];
		}
	}
}
?>