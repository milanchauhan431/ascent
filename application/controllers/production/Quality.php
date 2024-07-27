<?php
class Quality extends MY_Controller{
    private $qualityForm = "production/quality/quality_form";

    public function __construct(){
		parent::__construct();		
		$this->data['headData']->controller = "production/quality";
	}

    public function list($department = ""){
        $this->data['headData']->pageUrl = "production/quality/list/".$department;
        $this->data['headData']->pageTitle = "Quality Department";
        $this->data['tableHeader'] = getProductionDtHeader($department);
        $indexPage = "production/quality/".$department."_index";
        $this->load->view($indexPage,$this->data);
    }

    public function getDTRows($from_entry_type,$to_entry_type,$job_status){
        $data = $this->input->post();
        $data['from_entry_type'] = $from_entry_type;
        $data['to_entry_type'] = $to_entry_type;
        $data['job_status'] = $job_status;

        $result = $this->production->getProductionDTRows($data);
        $sendData = array();$i=($data['start']+1);

        foreach($result['data'] as $row):
            $row->sr_no = $i++;        
            $row->controller = $this->data['headData']->controller;
            $row->from_entry_type = $data['from_entry_type'];
            $row->to_entry_type = $data['to_entry_type'];
            $sendData[] = getQualityData($row);
        endforeach;

        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function qualityChecking(){
        $data = $this->input->post();
        $this->data['postData'] = (object) $data;
        $this->load->view($this->qualityForm,$this->data);
    }

    public function saveQualityChecking(){
        $data = $this->input->post();
        $this->printJson($this->production->saveQualityChecking($data));
    }
}
?>