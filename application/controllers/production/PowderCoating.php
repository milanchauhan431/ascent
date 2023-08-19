<?php
class PowderCoating extends MY_Controller{
    private $powderCoatingFrom = "production/powder_coating/powder_coating_form";
    private $viewPowderCoating = "production/powder_coating/view_powder_coating";

    public function __construct(){
		parent::__construct();		
		$this->data['headData']->controller = "production/powderCoating";
	}

    public function list($department = ""){
        $this->data['headData']->pageUrl = "production/powderCoating/list/".$department;
        $this->data['headData']->pageTitle = ucwords(str_replace("_"," ",$department));
        $this->data['tableHeader'] = getProductionDtHeader($department);
        $indexPage = "production/powder_coating/".$department."_index";
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
            $sendData[] = getPowderCoatingData($row);
        endforeach;

        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function acceptJob(){
        $data = $this->input->post();
        $this->printJson($this->production->acceptJob($data));
    }

    public function powderCoating(){
        $data = $this->input->post();
        $this->data['parameterList'] = $this->production->getParameterList(['param_type'=>1]);
        $this->data['fabAssemblyData'] = $this->production->getProductionTransData(['pm_id'=>$data['pm_id'],'entry_type'=>$data['fab_prod_entry_type']]);
        $this->data['itemList'] = $this->item->getItemList();
        $this->data['dataRow'] = (object) $data;
        $this->load->view($this->powderCoatingFrom,$this->data);
    }

    public function save(){
        $data = $this->input->post();
        $errorMessage = array();

        if($data['entry_type'] == 34)://Powder Coating
            foreach($data['transData'] as $key=>$row):
                if(empty($row['param_value'])):
                    $errorMessage['param_value_'.$key] = ucwords(str_replace("_"," ",$row['param_key']))." is required.";
                endif;
            endforeach;
        endif;

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->production->saveProductionTrans($data));
        endif;
    }

    public function viewPowderCoating(){
        $data = $this->input->post();
        $this->data['dataRow'] = $this->production->getProductionTransData($data);
        $this->load->view($this->viewPowderCoating,$this->data);
    }
}
?>