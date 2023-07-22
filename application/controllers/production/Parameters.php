<?php
class Parameters extends MY_Controller{
    private $indexPage = "production/parameters/index";
    private $form = "production/parameters/form";

    public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "Parameters";
		$this->data['headData']->controller = "production/parameters";
        $this->data['headData']->pageUrl = "production/parameters";
	}

    public function index(){
        $this->data['tableHeader'] = getProductionDtHeader("parameters");
        $this->load->view($this->indexPage,$this->data);
    }
	
    public function getDTRows(){
        $data = $this->input->post(); 
        $result = $this->production->getParametersDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row): 
            $row->sr_no = $i++;         
            $sendData[] = getParametersData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addParameter(){
        $this->load->view($this->form,$this->data);
    }

    public function save(){
        $data = $this->input->post();
        $errorMessage = array();

        if(empty($data['param_name']))
            $errorMessage['param_name'] = "Parameter Name is required.";

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->production->saveParameter($data));
        endif;
    }

    public function edit(){
        $data = $this->input->post();
        $this->data['dataRow'] = $this->production->getParameter($data);
        $this->load->view($this->form,$this->data);
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->production->deleteParameter($id));
        endif;
    }
}
?>