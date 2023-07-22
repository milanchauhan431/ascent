<?php
class Fabrication extends MY_Controller{
    private $indexPage = "production/fabrication/index";
    private $mechanicalDesignFrom = "production/fabrication/mechanical_design";

    public function __construct(){
		parent::__construct();		
		$this->data['headData']->controller = "production/fabrication";
	}

    public function list($department = ""){
        $this->data['headData']->pageUrl = "production/fabrication/list/".$department;
        $this->data['headData']->pageTitle = ucwords(str_replace("_"," ",$department));
        $this->data['tableHeader'] = getProductionDtHeader($department);
        $this->load->view($this->indexPage,$this->data);
    }

    public function getDTRows($from_entry_type,$to_entry_type,$job_status){
        $data = $this->input->post();
        $data['from_entry_type'] = $from_entry_type;
        $data['to_entry_type'] = $to_entry_type;
        $data['job_status'] = $job_status;

        $result = $this->production->getFabricationDTRows($data);
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

    public function save(){
        $data = $this->input->post();

        $this->load->library('upload');
        if(!empty($_FILES['cutting_drawings']['name'])):
            $_FILES['userfile']['name']     = $_FILES['cutting_drawings']['name'];
            $_FILES['userfile']['type']     = $_FILES['cutting_drawings']['type'];
            $_FILES['userfile']['tmp_name'] = $_FILES['cutting_drawings']['tmp_name'];
            $_FILES['userfile']['error']    = $_FILES['cutting_drawings']['error'];
            $_FILES['userfile']['size']     = $_FILES['cutting_drawings']['size'];

            $imagePath = realpath(APPPATH . '../assets/uploads/production/');
            $ext = pathinfo($_FILES['cutting_drawings']['name'], PATHINFO_EXTENSION);

            $config = ['file_name' => time()."_"."CD_".$_FILES['cutting_drawings']['name'],'allowed_types' => '*','max_size' => 10240,'overwrite' => FALSE, 'upload_path' => $imagePath];

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

        $this->printJson($this->production->saveProductionTrans($data));
    }
}
?>