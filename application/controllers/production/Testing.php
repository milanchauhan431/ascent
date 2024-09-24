<?php
class Testing extends MY_Controller{
    private $testingForm = "production/testing/testing_form";
    private $indexPage = "production/testing/testing_index";
    private $documentationForm = "production/testing/documentation_form";

    public function __construct(){
		parent::__construct();		
		$this->data['headData']->controller = "production/testing";
        $this->data['headData']->pageUrl = "production/testing";
        $this->data['headData']->pageTitle = "Testing Department";
	}

    public function index(){        
        $this->data['tableHeader'] = getProductionDtHeader("pending_testing");
        $this->load->view($this->indexPage,$this->data);
    }

    public function getDTRows($from_entry_type,$to_entry_type,$job_status){
        $data = $this->input->post();
        $data['from_entry_type'] = $from_entry_type;
        $data['to_entry_type'] = $to_entry_type;
        $data['job_status'] = $job_status;

        $result = $this->production->getTestingDTRows($data);
        $sendData = array();$i=($data['start']+1);

        foreach($result['data'] as $row):
            $row->sr_no = $i++;        
            $row->controller = $this->data['headData']->controller;
            $row->from_entry_type = $data['from_entry_type'];
            $row->to_entry_type = $data['to_entry_type'];
            $sendData[] = getTestingData($row);
        endforeach;

        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addTestingDetail(){
        $data = $this->input->post();
        $data['tc_prefix'] = 'AE/'.date("Y")."/";
        $data['tc_sr_no'] = $this->production->getTcNo(['tc_prefix'=>$data['tc_prefix']]);
        $data['tc_sr_number'] = $data['tc_prefix'].$data['tc_sr_no'];
        $data['drgs_no'] = $this->production->getDrgsNo(['trans_child_id'=>$data['trans_child_id']]);
        $data['drgs_number'] = $data['job_number'].'/'.$data['drgs_no'];
        $this->data['postData'] = (object) $data;
        $this->data['systemDetailList'] = $this->production->getSystemDetailList();
        $this->load->view($this->testingForm,$this->data);
    }

    public function save(){
        $data = $this->input->post();
        $errorMessage = [];

        if(empty($data['customer_name']))
            $errorMessage['customer_name'] = "Customer Name is required.";

        if(empty($data['switchgear_no']))
            $errorMessage['switchgear_no'] = "Switchgear Sr. No. is required.";

        if(empty($data['system_detail_id']))
            $errorMessage['system_detail_id'] = "System Detail is required.";

        if(empty($data['control_supply']))
            $errorMessage['control_supply'] = "Control Supply is required.";

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->production->saveTestingParameters($data));
        endif;
    }

    public function editTestingDetail(){
        $data = $this->input->post();
        $this->data['dataRow'] = $this->production->getTestingParameterData($data);
        $this->data['systemDetailList'] = $this->production->getSystemDetailList();
        $this->load->view($this->testingForm,$this->data);
    }

    public function printTestingCertificate($id){
        $this->data['dataRow'] = $dataRow = $this->production->getTestingParameterData(['id'=>$id]);
        $this->data['companyData'] = $this->masterModel->getCompanyInfo();
        $logo=base_url('assets/images/logo.png');
		$this->data['letter_head']=base_url('assets/images/letterhead-top.png');
		$this->data['qc_logo']=base_url('assets/images/quality_control_logo.png');

        $pdfData = $this->load->view('production/testing/print_test_certificate',$this->data,true);

        $htmlHeader = "";
		$htmlFooter = '<table class="table top-table" style="margin-top:10px;border-top:1px solid #545454;">
            <tr>
                <td style="width:25%;">TC Sr. No. & Date : '.$dataRow->tc_sr_number.' ['.formatDate($dataRow->entry_date).']</td>
                <td style="width:25%;"></td>
                <td style="width:25%;text-align:right;">Page No. {PAGENO}/{nbpg}</td>
            </tr>
        </table>';

        $mpdf = new \Mpdf\Mpdf();
		$pdfFileName=str_replace(["/","-"],"_",$dataRow->tc_sr_number).'.pdf';
		$stylesheet = file_get_contents(base_url('assets/css/pdf_style.css?v='.time()));
		$mpdf->WriteHTML($stylesheet,1);
		$mpdf->SetDisplayMode('fullpage');
		$mpdf->SetWatermarkImage($logo,0.03,array(120,45));
		$mpdf->showWatermarkImage = true;
		$mpdf->SetProtection(array('print'));
		$mpdf->SetHTMLFooter($htmlFooter);
		$mpdf->AddPage('P','','','','',5,5,0,5,2,2,'','','','','','','','','','A4-P');
		$mpdf->WriteHTML($pdfData);
		$mpdf->Output($pdfFileName,'I');
    }

    public function documentation(){
        $data = $this->input->post();
        $this->data['ref_id'] = $data['ref_id'];
        $this->data['main_pm_id'] = $data['main_pm_id'];
        $this->load->view($this->documentationForm,$this->data);
    }

    public function saveDocumentation(){
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
                $config = ['file_name' => time()."_DOC_".$fileName,'allowed_types' => '*','max_size' => 10240,'overwrite' => FALSE, 'upload_path' => $imagePath];

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
            $this->printJson($this->production->saveDocumentation($dataRow));
        endif;
    }

    public function getDocumentFilesHtml(){
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
            $fileName = pathinfo($row->param_value, PATHINFO_FILENAME);

            $fileView = "";

            if(in_array($extension,$imgExtension)):
                $fileView = '<a href="'.base_url("assets/uploads/production/".$row->param_value).'" target="_blank"><image src="'.base_url("assets/uploads/production/".$row->param_value).'" width="50"></a><br>'.$fileName;
            elseif(in_array($extension,["pdf"])):
                $fileView = '<a href="'.base_url("assets/uploads/production/".$row->param_value).'"  target="_blank"><image src="'.base_url($fileExtension[$extension]).'" width="50"></a><br>'.$fileName;
            endif;

            $deleteParam = "{'postData':{'id' : ".$row->id."},'message' : 'File','res_function':'resDeleteFile','fndelete':'deleteDocumentFile'}";
            $removeBtn = '<a class="btn btn-outline-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="left"><i class="ti-trash"></i></a>';

            $html .= '<tr>
                <td class="text-center">'.$fileView.'</td>
                <td class="text-center">'.$removeBtn.'</td>
            </tr>';
        endforeach;

        $html  = (!empty($html))?$html:'<tr><td colspan="2" class="text-center">No data available in table</td></tr>';

        $this->printJson(['status'=>1,'tbodyData'=>$html]);
    }

    public function deleteDocumentFile(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->production->deleteDocumentFile($id));
        endif;
    }

    public function printProductionDetails($id){
        
    }
}
?>