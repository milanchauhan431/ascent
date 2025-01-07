<?php
class Fabrication extends MY_Controller{
    private $indexPage = "production/fabrication/index";
    private $mechanicalDesignFrom = "production/fabrication/mechanical_design";
    private $viewMechanicalDesign = "production/fabrication/view_mechanical_design";
    private $cuttingFrom = "production/fabrication/cutting_form";
    private $viewCutting = "production/fabrication/view_cutting";
    private $fabAssembelyFrom = "production/fabrication/fab_assembely_form";
    private $viewFabAssembely = "production/fabrication/view_fab_assembely";

    public function __construct(){
		parent::__construct();		
		$this->data['headData']->controller = "production/fabrication";
	}

    public function list($department = ""){
        $this->data['headData']->pageUrl = "production/fabrication/list/".$department;
        $this->data['headData']->pageTitle = ucwords(str_replace("_"," ",$department));
        $this->data['tableHeader'] = getProductionDtHeader($department);
        $indexPage = "production/fabrication/".$department."_index";
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
            $sendData[] = getFabricationData($row);
        endforeach;

        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function acceptJob(){
        $data = $this->input->post();
        $this->printJson($this->production->acceptJob($data));
    }

    public function mechanicalDesign(){
        $data = $this->input->post();
        $this->data['parameterList'] = $this->production->getParameterList(['param_type'=>1]);
        $this->data['dataRow'] = (object) $data;
        $this->load->view($this->mechanicalDesignFrom,$this->data);
    }

    public function viewMechanicalDesign(){
        $data = $this->input->post();
        $this->data['dataRow'] = $this->production->getProductionTransData($data);
        $this->load->view($this->viewMechanicalDesign,$this->data);
    }

    public function cutting(){
        $data = $this->input->post();
        $this->data['dataRow'] = (object) $data;
        $this->data['itemList'] = $this->item->getItemList();
        $this->load->view($this->cuttingFrom,$this->data);
    }

    public function viewCutting(){
        $data = $this->input->post();
        $this->data['dataRow'] = $this->production->getProductionTransData($data);
        $this->load->view($this->viewCutting,$this->data);
    }

    public function fabAssembely(){
        $data = $this->input->post();
        $this->data['parameterList'] = $this->production->getParameterList(['param_type'=>1]);
        $this->data['dataRow'] = (object) $data;
        $this->load->view($this->fabAssembelyFrom,$this->data);
    }

    public function viewFabAssembely(){
        $data = $this->input->post();
        $this->data['dataRow'] = $this->production->getProductionTransData($data);
        $this->load->view($this->viewFabAssembely,$this->data);
    }

    public function save(){
        $data = $this->input->post();
        $errorMessage = array();

        //Get Only Required Parameters
        $parameterList = $this->production->getParameterList(['param_type'=>1,'is_required'=>1]);
        $paramIds = array_column($parameterList,'id');

        if($data['entry_type'] == 30): // Mechanical Design
            $this->load->library('upload');
            if(!empty($_FILES['cutting_drawings']['name'])):
                $_FILES['userfile']['name']     = $_FILES['cutting_drawings']['name'];
                $_FILES['userfile']['type']     = $_FILES['cutting_drawings']['type'];
                $_FILES['userfile']['tmp_name'] = $_FILES['cutting_drawings']['tmp_name'];
                $_FILES['userfile']['error']    = $_FILES['cutting_drawings']['error'];
                $_FILES['userfile']['size']     = $_FILES['cutting_drawings']['size'];

                $imagePath = realpath(APPPATH . '../assets/uploads/production/');
                $ext = pathinfo($_FILES['cutting_drawings']['name'], PATHINFO_EXTENSION);

                $_FILES['cutting_drawings']['name'] = preg_replace('/[^A-Za-z0-9.]+/', '_', strtolower($_FILES['cutting_drawings']['name']));
                $config = ['file_name' => time()."_CD_".$_FILES['cutting_drawings']['name'],'allowed_types' => '*','max_size' => 10240,'overwrite' => FALSE, 'upload_path' => $imagePath];

                if(file_exists($config['upload_path'].'/'.$config['file_name'])):
                    unlink($config['upload_path'].'/'.$config['file_name']);
                endif;

                $this->upload->initialize($config);
                if (!$this->upload->do_upload()):
                    $errorMessage['cutting_drawings'] = $this->upload->display_errors();
                else:
                    $uploadData = $this->upload->data();
                    $data['cutting_drawings'] = $uploadData['file_name'];
                endif;
            endif;

            foreach($data['transData'] as $key=>$row):
                if(in_array($row['param_id'],$paramIds) && empty($row['param_value'])):
                    $errorMessage['param_value_'.$key] = ucwords(str_replace("_"," ",$row['param_key']))." is required.";
                endif;
            endforeach;
        endif;

        if($data['entry_type'] == 31): // Cutting
            $reqParamKey = ['panel_qty','discription_of_sheet','sheet_qty'];
            foreach($data['transData'] as $key=>$row):
                if(in_array($row['param_key'],$reqParamKey)):
                    if(empty($row['param_value'])):
                        $errorMessage['param_value_'.$key] = ucwords(str_replace("_"," ",$row['param_key']))." is required.";
                    endif;
                endif;
            endforeach;
        endif;

        if($data['entry_type'] == 33)://Fab. Assembely
            foreach($data['transData'] as $key=>$row):
                if(in_array($row['param_id'],$paramIds) && empty($row['param_value'])):
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

    public function completeProductionTrans(){
        $data = $this->input->post();
        $this->printJson($this->production->completeProductionTrans($data));
    }
}
?>