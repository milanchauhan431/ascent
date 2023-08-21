<?php
class ElectricalDesign extends MY_Controller{
    private $index = "production/electrical_design/index";
    private $electricalDesignFrom = "production/electrical_design/electrical_design_form";

    public function __construct(){
        parent::__construct();
        $this->data['headData']->pageTitle = "Electrical Design";
		$this->data['headData']->controller = "production/electricalDesign";
        $this->data['headData']->pageUrl = "production/electricalDesign";
        $this->data['entry_type'] = $this->transMainModel->getEntryType(['controller'=>'production/electricalDesign'])->id;
    }

    public function index(){
        $this->data['tableHeader'] = getProductionDtHeader("electrical_design");
        $this->load->view($this->index,$this->data);
    }

    public function getDTRows($status = 0){
        $data = $this->input->post();$data['job_status'] = $status;
        $data['entry_type'] = 27;
        $result = $this->production->getElectricalDesignDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):          
            $row->sr_no = $i++;         
            $sendData[] = getElectricalDesignData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function electricalDesign(){
        $data = $this->input->post();
        $this->data['pm_id'] = $data['pm_id'];
        
        $this->load->view($this->electricalDesignFrom,$this->data);
    }

    public function save(){
        $data = $this->input->post();
        $errorMessage = array();

        if(empty($_FILES['attachments'])):
            $errorMessage['attachments'] = "Please select design files.";
        else:
            $this->load->library('upload');

            $dataRow = array();
            foreach($_FILES['attachments']['name'] as $key => $fileName):
                $_FILES['userfile']['name']     = $fileName;
                $_FILES['userfile']['type']     = $_FILES['attachments']['type'][$key];
                $_FILES['userfile']['tmp_name'] = $_FILES['attachments']['tmp_name'][$key];
                $_FILES['userfile']['error']    = $_FILES['attachments']['error'][$key];
                $_FILES['userfile']['size']     = $_FILES['attachments']['size'][$key];

                $imagePath = realpath(APPPATH . '../assets/uploads/production/');
                /* $ext = pathinfo($fileName, PATHINFO_EXTENSION);
                $fileName = pathinfo($fileName, PATHINFO_FILENAME); */

                $fileName = preg_replace('/[^A-Za-z0-9]+/', '_', strtolower($fileName));
                $config = ['file_name' => time()."_ED_".$fileName,'allowed_types' => '*','max_size' => 10240,'overwrite' => FALSE, 'upload_path' => $imagePath];

                if(file_exists($config['upload_path'].'/'.$config['file_name'])):
                    unlink($config['upload_path'].'/'.$config['file_name']);
                endif;

                $this->upload->initialize($config);
                if (!$this->upload->do_upload()):
                    $errorMessage['attachments'] = $this->upload->display_errors();
                else:
                    $uploadData = $this->upload->data();

                    $dataRow[$key]['id'] = "";
                    $dataRow[$key]['entry_date'] = date("Y-m-d");
                    $dataRow[$key]['ref_id'] = $data['ref_id'];
                    $dataRow[$key]['main_pm_id'] = $data['main_pm_id'];
                    $dataRow[$key]['entry_type'] = $data['entry_type'];
                    $dataRow[$key]['param_value'] = $uploadData['file_name'];
                endif;
            endforeach;
        endif;

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->production->saveElectricalDesign($dataRow));
        endif;
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->production->deleteElectricalDesignFile($id));
        endif;
    }

    public function getElectricalDesignFilesHtml(){
        $data = $this->input->post();
        $result = $this->production->getProductionTransData($data);

        $imgExtension = ["jpg","jpeg","png","gif"];

        $fileExtension = [
            "pdf"=>"assets/uploads/defualt/pdf_image.png",
            "xlsx"=>"assets/uploads/defualt/excel_image.png",
            "docx"=>"assets/uploads/defualt/wordfile_image.png",
            "txt"=>"assets/uploads/defualt/textfile_image.png"
        ];

        $html = "";$extension = "";
        foreach($result as $row):
            $extension = pathinfo($row->param_value, PATHINFO_EXTENSION);

            $fileView = "";

            if(in_array($extension,$imgExtension)):
                $fileView = '<a href="'.base_url("assets/uploads/production/".$row->param_value).'" download><image src="'.base_url("assets/uploads/production/".$row->param_value).'" width="50"></a>';
            elseif(in_array($extension,["pdf","xlsx","docx","txt"])):
                $fileView = '<a href="'.base_url("assets/uploads/production/".$row->param_value).'" download><image src="'.base_url($fileExtension[$extension]).'" width="50"></a>';
            else:
                $fileView = '<a href="'.base_url("assets/uploads/production/".$row->param_value).'" download><image src="'.base_url("assets/uploads/defualt/otherfile_image.png").'" width="50"></a>';
            endif;

            $deleteParam = "{'postData':{'id' : ".$row->id."},'message' : 'File','res_function':'resDeleteFile'}";
            $removeBtn = '<a class="btn btn-outline-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="left"><i class="ti-trash"></i></a>';

            $html .= '<tr>
                <td class="text-center">'.$fileView.'</td>
                <td class="text-center">'.$removeBtn.'</td>
            </tr>';
        endforeach;

        $html  = (!empty($html))?$html:'<tr><td colspan="2" class="text-center">No data available in table</td></tr>';

        $this->printJson(['status'=>1,'tbodyData'=>$html]);
    }
}
?>