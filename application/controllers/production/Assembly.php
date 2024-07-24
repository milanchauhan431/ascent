<?php
class Assembly extends MY_Controller{
    private $assignForm = "production/assembly_production/assign_form";
    private $assembelyProductionFrom = "production/assembly_production/assembly_production_form";
    private $viewAssemblyProduction = "production/assembly_production/view_assembly_production";
    private $receiveForm = "production/assembly_production/receive_form";

    public function __construct(){
		parent::__construct();		
		$this->data['headData']->controller = "production/assembly";
	}

    public function list($department = ""){
        $this->data['headData']->pageUrl = "production/assembly/list/".$department;
        $this->data['headData']->pageTitle = "Assembly Production";//ucwords(str_replace("_"," ",$department));
        $this->data['tableHeader'] = getProductionDtHeader($department);
        $indexPage = "production/assembly_production/".$department."_index";
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
            $sendData[] = getAssemblyAllotmentData($row);
        endforeach;

        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function assignJob(){
        $data = $this->input->post();
        $this->data['postData'] = (object) $data;
        $this->data['partyList'] = $this->employee->getEmployeeList();
        //$this->party->getPartyList(['party_category'=>3]);
        $this->load->view($this->assignForm,$this->data);
    }

    public function saveAssignJob(){
        $data = $this->input->post();
        $errorMessage = array();

        if(empty($data['vendor_id']))
            $errorMessage['vendor_id'] = "Vendor name is required.";
        if(empty($data['vendor_qty']))
            $errorMessage['vendor_qty'] = "Panel Qty is required.";

        if(!empty($data['vendor_qty'])):
            $powderCoatingData = $this->production->getProductionMaster(['id'=>$data['id']]);
            if($data['vendor_qty'] > $powderCoatingData->pending_qty):
                $errorMessage['vendor_qty'] = "Invalid Qty.";
            endif;
        endif;

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->production->assignJob($data));
        endif;
    }

    public function getAssPrdDTRows($from_entry_type,$to_entry_type,$job_status){
        $data = $this->input->post();
        $data['from_entry_type'] = $from_entry_type;
        $data['to_entry_type'] = $to_entry_type;
        $data['job_status'] = $job_status;

        $result = $this->production->getAssPrdDTRows($data);
        $sendData = array();$i=($data['start']+1);

        foreach($result['data'] as $row):
            $row->sr_no = $i++;        
            $row->controller = $this->data['headData']->controller;
            $row->from_entry_type = $data['from_entry_type'];
            $row->to_entry_type = $data['to_entry_type'];
            $sendData[] = getAssemblyProductionData($row);
        endforeach;

        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function acceptJob(){
        $data = $this->input->post();
        $this->printJson($this->production->acceptJob($data));
    }

    public function assemblyProduction(){
        $data = $this->input->post();
        $this->data['parameterList'] = $this->production->getParameterList(['param_type'=>1]);
        $this->data['fabAssemblyData'] = $this->production->getProductionTransData(['pm_id'=>$data['pm_id'],'entry_type'=>$data['fab_prod_entry_type']]);
        $this->data['dataRow'] = (object) $data;
        $this->load->view($this->assembelyProductionFrom,$this->data);
    }

    public function save(){
        $data = $this->input->post();
        $errorMessage = array();

        if($data['entry_type'] == 37)://Assembly Production
            foreach($data['transData'] as $key=>$row):
                if(empty($row['param_value'])):
                    $errorMessage['param_value_'.$key] = ucwords(str_replace("_"," ",$row['param_key']))." is required.";
                endif;
            endforeach;
        endif;

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->production->acceptContractorJob($data));
        endif;
    }

    public function completeJob(){
        $data = $this->input->post();
        $this->printJson($this->production->completeContractorJob($data));
    }

    public function viewAssemblyProduction(){
        $data = $this->input->post();
        $this->data['dataRow'] = $this->production->getProductionTransData($data);
        $this->load->view($this->viewAssemblyProduction,$this->data);
    }

    /* public function reciveJob(){
        $data = $this->input->post();
        $this->data['postData'] = (object) $data;
        $this->load->view($this->receiveForm,$this->data);
    }

    public function saveReceiveJob(){
        $data = $this->input->post();
        $errorMessage = array();

        if(empty($data['vendor_qty']))
            $errorMessage['vendor_qty'] = "Panel Qty is required.";

        if(!empty($data['vendor_qty'])):
            $assemblyData = $this->production->getProductionMaster(['id'=>$data['id']]);
            if($data['vendor_qty'] > $assemblyData->vendor_qty):
                $errorMessage['vendor_qty'] = "Invalid Qty.";
            endif;
        endif;

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->production->saveReceiveJob($data));
        endif;
    } */
}
?>