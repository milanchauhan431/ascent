<?php
class TestingParameters extends MY_Controller{
    private $index = "production/testing_param/index";
    private $form = "production/testing_param/form";

    public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "Testing Parameters";
		$this->data['headData']->controller = "production/testingParameters";
        $this->data['headData']->pageUrl = "production/testingParameters";
	}

    public function index(){
        $this->data['tableHeader'] = getProductionDtHeader("testingParameters");
        $this->load->view($this->index,$this->data);
    }
	
    public function getDTRows(){
        $data = $this->input->post(); 
        $result = $this->production->getTestingParametersDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row): 
            $row->sr_no = $i++;         
            $sendData[] = getTestingParametersData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addTestingParameter(){
        $this->load->view($this->form,$this->data);
    }

    public function save(){
        $data = $this->input->post();
        $errorMessage = array();

        if(empty($data['system_detail']))
            $errorMessage['system_detail'] = "System Detail is required.";

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $data['insulation_resistance_json'] = (!empty($data['insulation_resistance_json']))?json_encode($data['insulation_resistance_json']):NULL;
            $this->printJson($this->production->saveTestingParameter($data));
        endif;
    }

    public function edit(){
        $data = $this->input->post();
        $this->data['dataRow'] = $this->production->getTestingParameter($data);
        $this->load->view($this->form,$this->data);
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->production->deleteTestingParameter($id));
        endif;
    }
}
?>