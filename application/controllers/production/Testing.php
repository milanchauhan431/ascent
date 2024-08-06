<?php
class Testing extends MY_Controller{
    private $testingForm = "production/testing/testing_form";
    private $indexPage = "production/testing/testing_index";

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
		$stylesheet = file_get_contents(base_url('assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css'));
		$stylesheet = file_get_contents(base_url('assets/css/style.css?v='.time()));
		$stylesheet = file_get_contents(base_url('assets/css/pdf_style.css?v='.time()));
		$mpdf->WriteHTML($stylesheet,1);
		$mpdf->SetDisplayMode('fullpage');
		$mpdf->SetWatermarkImage($logo,0.03,array(120,45));
		$mpdf->showWatermarkImage = true;
		$mpdf->SetProtection(array('print'));
		$mpdf->SetHTMLFooter($htmlFooter);
		$mpdf->AddPage('P','','','','',5,5,0,2,2,2,'','','','','','','','','','A4-P');
		$mpdf->WriteHTML($pdfData);
		$mpdf->Output($pdfFileName,'I');
    }
}
?>