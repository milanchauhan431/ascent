<?php
require_once 'vendor/autoload.php';
use Xthiago\PDFVersionConverter\Guesser\RegexGuesser;
class Estimation extends MY_Controller{
    private $index = "production/estimation/index";
    private $orderBom = "production/estimation/order_bom";
    private $viewBom = "production/estimation/view_bom";
    private $viewProdBom = "production/estimation/view_production_bom";
    private $requestFrom = "production/estimation/pur_request_form";
    private $estimationFrom = "production/estimation/estimation_form";
    private $changeJobPriorityFrom = "production/estimation/change_job_priority_form";

    private $departmentList = [
        "30"=>"MECHENICAL DESIGN",
        "31"=>"CUTTING",
        "32"=>"BENDING",
        "33"=>"FAB. ASSEMBELY",
        "34"=>"POWDER COATING",
        //"35"=>"ELETRICALS DESIGN",
        "36"=>"ASSEMBELY PRODUCTION",
        "37"=>"Contactor Assembly",
        "38"=>"QULITY CHECK",
        "40"=>"Testing",
        /* "39"=>"QULITY CHECK",
        "40"=>"TESTING",
        "41"=>"DOCUMATION" */
    ];

    public function __construct(){
        parent::__construct();
        $this->data['headData']->pageTitle = "Estimation & Design";
		$this->data['headData']->controller = "production/estimation";
        $this->data['headData']->pageUrl = "production/estimation";
        $this->data['entry_type'] = $this->transMainModel->getEntryType(['controller'=>'production/estimation'])->id;
    }

    public function index(){
        $this->data['tableHeader'] = getProductionDtHeader("estimation");
        $this->load->view($this->index,$this->data);
    }

    public function getEstimationDTRows($status = 0){
        $data = $this->input->post();$data['job_status'] = $status;
        $data['entry_type'] = $this->transMainModel->getEntryType(['controller'=>'salesOrders'])->id;
        $result = $this->production->getEstimationDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):          
            $row->sr_no = $i++;         
            $sendData[] = getEstimationData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function orderBom(){
        $data = $this->input->post();
        $this->data['dataRow'] = (object) $data;
        $this->load->view($this->orderBom,$this->data);
    }

    public function saveOrderBom(){
        $data = $this->input->post();
        $errorMessage = array();

        if(empty($data['itemData']))
            $errorMessage['itemData'] = "Item Details not found.";

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $postData = array();
            foreach($data['itemData'] as $row):
                $key = $row['item_id'].$row['make'];
                if(array_key_exists($key,$postData)):
                    $postData[$key]['qty'] += $row['qty'];
                else:
                    $postData[$key] = $row;
                endif;
            endforeach;

            $this->printJson($this->production->saveOrderBom($postData));
        endif;
    }

    public function viewOrderBom(){
        $data = $this->input->post();
        $this->data['postData'] = (object) $data;
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>"2,3"]);
        $this->data['unitList'] = $this->item->itemUnits();
        $this->data['brandList'] = $this->brandMaster->getBrandList();
        $this->load->view($this->viewBom,$this->data);
    }

    public function saveOrderBomItem(){
        $data = $this->input->post();
        $errorMessage = array();

        if(empty($data['item_id']))
            $errorMessage['item_id'] = "Item Name is required.";
        if(empty($data['qty']))
            $errorMessage['qty'] = "Qty is required.";
        if(empty($data['uom']))
            $errorMessage['uom'] = "Unit Name is required.";
        if(empty($data['price']))
            $errorMessage['price'] = "Price is required.";
        if(empty($data['make']))
            $errorMessage['make'] = "Make is required.";

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->production->saveOrderBomItem($data));
        endif;
    }

    public function getOrderBomHtml(){
        $data = $this->input->post();
        $dataRow = $this->production->getOrderBomItems($data);
        $i=1;$tbodyData = "";
        if(!empty($dataRow)):
            foreach($dataRow as $row):
                $deleteButton = "";

                if($row->req_qty <= 0 || $row->trans_status > 0):
                    $deleteParam = "{'postData':{'id' : ".$row->id."},'message' : 'Bom Item','res_function':'resDeleteBomItem','fndelete':'removeBomItem'}";
                    $deleteButton = '<a class="btn btn-outline-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="left"><i class="ti-trash"></i></a>';
                endif;

                $tbodyData .=  '<tr>
                    <td>'.$i++.'</td>
                    <td>'.$row->item_name.'</td>
                    <td>'.$row->make.'</td>
                    <td>'.$row->item_code.'</td>
                    <td>'.$row->uom.'</td>
                    <td>'.$row->qty.'</td>
                    <td>'.$row->price.'</td>
                    <td>'.$row->amount.'</td>
                    <td>'.$row->disc_per.'</td>
                    <td>'.$row->net_amount.'</td>
                    <td>'.$deleteButton.'</td>
                </tr>';
            endforeach;
        else:
            $tbodyData.= '<tr><td colspan="11" class="text-center">No data available in table</td></tr>';
        endif;

        $this->printJson(['status'=>1,"tbodyData"=>$tbodyData]);
    }

    public function viewProductionBom(){
        $data = $this->input->post();
        $this->data['postData'] = (object) $data;
        $this->load->view($this->viewProdBom,$this->data);
    }

    public function getProductionBomHtml(){
        $data = $this->input->post();
        $dataRow = $this->production->getOrderBomItems($data);
        $i=1;$tbodyData = "";
        if(!empty($dataRow)):
            foreach($dataRow as $row):

                $tbodyData .=  '<tr>
                    <td>'.$i++.'</td>
                    <td>'.$row->item_name.'</td>
                    <td>'.$row->make.'</td>
                    <td>'.$row->item_code.'</td>
                    <td>'.$row->uom.'</td>
                    <td>'.$row->qty.'</td>
                </tr>';
            endforeach;
        else:
            $tbodyData.= '<tr><td colspan="6" class="text-center">No data available in table</td></tr>';
        endif;

        $this->printJson(['status'=>1,"tbodyData"=>$tbodyData]);
    }

    public function removeBomItem(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->production->removeBomItem($id));
        endif;
    }

    public function purchaseRequest(){
        $data = $this->input->post();
        $this->data['dataRow'] = $this->production->getOrderBomItems($data);
        $this->data['postData'] = (object) $data;
        $this->load->view($this->requestFrom,$this->data);
    }

    public function savePurchaseRequest(){
        $data = $this->input->post();

        if(empty($data['itemData'])):
            $errorMessage['itemData'] = "Item Details is required.";
        else:
            if(empty(array_sum(array_column($data['itemData'],'req_qty')))):
                $errorMessage['itemData'] = "Please input request Qty.";
            endif;
        endif;

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->production->savePurchaseRequest($data));
        endif;
    }

    public function addEstimation(){
        $data = $this->input->post();
        if(!empty($data['id'])):
            $this->data['dataRow'] = $this->production->getProductionMaster(['id'=>$data['id']]);
        else:
            $this->data['dataRow'] = (object) $data;
        endif;
        $this->data['departmentList'] = $this->departmentList;
        $this->load->view($this->estimationFrom,$this->data);
    }

    public function saveEstimation(){
        $data = $this->input->post();
        $errorMessage = array();

        if(empty($_FILES['ga_file']['name']) && empty($data['ga_file_name']))
            $errorMessage['ga_file'] = "GA File is required.";

        if(empty($data['priority']))
            $errorMessage['priority'] = "Priority is required.";

        if(empty($data['department_ids']))
            $errorMessage['department_ids'] = "Departments is required.";

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->load->library('upload');

            if($_FILES['ga_file']['name'] != null && !empty($_FILES['ga_file']['name'])):
                $_FILES['userfile']['name']     = $_FILES['ga_file']['name'];
                $_FILES['userfile']['type']     = $_FILES['ga_file']['type'];
                $_FILES['userfile']['tmp_name'] = $_FILES['ga_file']['tmp_name'];
                $_FILES['userfile']['error']    = $_FILES['ga_file']['error'];
                $_FILES['userfile']['size']     = $_FILES['ga_file']['size'];

                $imagePath = realpath(APPPATH . '../assets/uploads/production/');
                $ext = pathinfo($_FILES['ga_file']['name'], PATHINFO_EXTENSION);

                $_FILES['ga_file']['name'] = preg_replace('/[^A-Za-z0-9.]+/', '_', strtolower($_FILES['ga_file']['name']));
                $config = ['file_name' => time()."_".$_FILES['ga_file']['name'],'allowed_types' => '*','max_size' => 10240,'overwrite' => FALSE, 'upload_path' => $imagePath];

                if(file_exists($config['upload_path'].'/'.$config['file_name'])):
                    unlink($config['upload_path'].'/'.$config['file_name']);
                endif;

                $this->upload->initialize($config);
                if (!$this->upload->do_upload()):
                    $errorMessage['ga_file'] = $this->upload->display_errors();
                else:
                    $uploadData = $this->upload->data();
                    $data['ga_file'] = $uploadData['file_name'];
                endif;
            endif;

            if($_FILES['technical_specification_file']['name'] != null && !empty($_FILES['technical_specification_file']['name'])):     
                $_FILES['userfile']['name']     = $_FILES['technical_specification_file']['name'];
                $_FILES['userfile']['type']     = $_FILES['technical_specification_file']['type'];
                $_FILES['userfile']['tmp_name'] = $_FILES['technical_specification_file']['tmp_name'];
                $_FILES['userfile']['error']    = $_FILES['technical_specification_file']['error'];
                $_FILES['userfile']['size']     = $_FILES['technical_specification_file']['size'];

                $imagePath = realpath(APPPATH . '../assets/uploads/production/');
                $ext = pathinfo($_FILES['technical_specification_file']['name'], PATHINFO_EXTENSION);

                $_FILES['technical_specification_file']['name'] = preg_replace('/[^A-Za-z0-9.]+/', '_', strtolower($_FILES['technical_specification_file']['name']));
                $config = ['file_name' => time()."_".$_FILES['technical_specification_file']['name'],'allowed_types' => '*','max_size' => 10240,'overwrite' => FALSE, 'upload_path' => $imagePath];

                if(file_exists($config['upload_path'].'/'.$config['file_name'])): 
                    unlink($config['upload_path'].'/'.$config['file_name']);
                endif;

                $this->upload->initialize($config);
                if (!$this->upload->do_upload()):
                    $errorMessage['technical_specification_file'] = $this->upload->display_errors();
                else:
                    $uploadData = $this->upload->data();
                    $data['technical_specification_file'] = $uploadData['file_name'];
                endif;
            endif;

            if($_FILES['sld_file']['name'] != null && !empty($_FILES['sld_file']['name'])):
                $_FILES['userfile']['name']     = $_FILES['sld_file']['name'];
                $_FILES['userfile']['type']     = $_FILES['sld_file']['type'];
                $_FILES['userfile']['tmp_name'] = $_FILES['sld_file']['tmp_name'];
                $_FILES['userfile']['error']    = $_FILES['sld_file']['error'];
                $_FILES['userfile']['size']     = $_FILES['sld_file']['size'];

                $imagePath = realpath(APPPATH . '../assets/uploads/production/');
                $ext = pathinfo($_FILES['sld_file']['name'], PATHINFO_EXTENSION);

                $_FILES['sld_file']['name'] = preg_replace('/[^A-Za-z0-9.]+/', '_', strtolower($_FILES['sld_file']['name']));
                $config = ['file_name' => time()."_".$_FILES['sld_file']['name'],'allowed_types' => '*','max_size' => 10240,'overwrite' => FALSE, 'upload_path' => $imagePath];

                if(file_exists($config['upload_path'].'/'.$config['file_name'])):
                    unlink($config['upload_path'].'/'.$config['file_name']);
                endif;

                $this->upload->initialize($config);
                if (!$this->upload->do_upload()):
                    $errorMessage['sld_file'] = $this->upload->display_errors();
                else:
                    $uploadData = $this->upload->data();
                    $data['sld_file'] = $uploadData['file_name'];
                endif;
            endif;

            unset($data['ga_file_name']);
            $this->printJson($this->production->saveEstimation($data));
        endif;
    }

    public function changeJobPriority(){
        $data = $this->input->post();
        $this->data['dataRow'] = $this->production->getProductionMaster(['id'=>$data['id']]);
        $this->load->view($this->changeJobPriorityFrom,$this->data);
    }

    public function saveJobPriority(){
        $data = $this->input->post();
        $errorMessage = array();

        if(empty($data['priority']))
            $errorMessage['priority'] = "Priority is required.";

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->production->changeJobPriority($data));
        endif;
    }

    public function startJob(){
        $data = $this->input->post();
        $this->printJson($this->production->startJob($data));
    }

    public function printProductionDetails($id){
        $productionDetail = $this->production->getProductionMaster(['id'=>$id]);
        $bomItems = $this->production->getOrderBomItems(['trans_child_id'=>$productionDetail->trans_child_id]);
        $companyData = $this->masterModel->getCompanyInfo();

        $a=1;$tbodyData = "";
        if(!empty($bomItems)):
            foreach($bomItems as $row):

                $tbodyData .=  '<tr>
                    <td>'.$a++.'</td>
                    <td>'.$row->item_name.'</td>
                    <td>'.$row->make.'</td>
                    <td>'.$row->item_code.'</td>
                    <td>'.$row->uom.'</td>
                    <td>'.$row->qty.'</td>
                </tr>';
            endforeach;
        else:
            $tbodyData.= '<tr><td colspan="6" class="text-center">No data available in table</td></tr>';
        endif;

        $thead = '<tr>
            <th colspan="6" class="text-center">BOM Detail</th>
        </tr>        
        <tr>
            <th>#</th>
            <th>Material Description</th>
            <th>Make</th>
            <th>Cat. No.</th>
            <th>UOM</th>
            <th>Total Qty.</th>
        </tr>';

        $htmlHeader = '<table class="table" style="border-bottom:1px solid #036aae;">
            <tr>
                <td class="org_title text-uppercase text-left" style="font-size:1rem;width:30%"></td>
                <td class="org_title text-uppercase text-center" style="font-size:1.3rem;width:40%">'.$companyData->company_name.'</td>
                <td class="org_title text-uppercase text-right" style="font-size:1rem;width:30%"></td>
            </tr>
        </table>
        <table class="table" style="border-bottom:1px solid #036aae;margin-bottom:2px;">
            <tr><td class="org-address text-center" style="font-size:13px;">'.$companyData->company_address.'</td></tr>
        </table>';

		$htmlFooter = '<table class="table top-table" style="margin-top:10px;border-top:1px solid #545454;">
            <tr>
                <td style="width:33%;" class="text-left">SO. No. & Date : '.$productionDetail->trans_number.' ['.formatDate($productionDetail->trans_date).']</td>
                <td style="width:34%;" class="text-center">Job. No. : '.$productionDetail->job_number.'</td>
                <td style="width:33%;text-align:right;">Page No. {PAGENO}/{nbpg}</td>
            </tr>
        </table>';

        $pdfData = '<table class="table table-bordered" style="margin-top:0px;font-size:8px !important;">
            <thead>'.$thead.'</thead>
            <tbody>'.$tbodyData.'</tbody>
        </table>';

        $this->trashFiles();

        $mpdf = new \Mpdf\Mpdf();
        $filePath = realpath(APPPATH . '../assets/uploads/removable_files/');
		$pdfFileName = $filePath."/".str_replace(["/","-"],"_",$productionDetail->job_number).'.pdf';
		$stylesheet = file_get_contents(base_url('assets/css/pdf_style.css?v='.time()));
		$mpdf->WriteHTML($stylesheet,1);
		$mpdf->SetDisplayMode('fullpage');
		//$mpdf->SetProtection(array('print'));
		$mpdf->SetHTMLHeader($htmlHeader);
        $mpdf->SetHTMLFooter($htmlFooter);
		$mpdf->AddPage('P','','','','',5,5,25,5,2,2,'','','','','','','','','','A4-P');
		$mpdf->WriteHTML($pdfData);

        ob_clean();
		$mpdf->Output($pdfFileName,'F');


        $filenames[] = $pdfFileName;

        if((!empty($productionDetail->ga_file)) && file_exists(realpath(APPPATH . '../assets/uploads/production/').'/'.$productionDetail->ga_file)):
            $filenames[] = realpath(APPPATH . '../assets/uploads/production/').'/'.$productionDetail->ga_file;
        endif;

        if((!empty($productionDetail->technical_specification_file)) && file_exists(base_url('assets/uploads/production/'.$productionDetail->ga_file))):
            $filenames[] = realpath(APPPATH . '../assets/uploads/production/').'/'.$productionDetail->technical_specification_file;
        endif;

        if((!empty($productionDetail->sld_file)) && file_exists(base_url('assets/uploads/production/'.$productionDetail->sld_file))):
            $filenames[] = realpath(APPPATH . '../assets/uploads/production/').'/'.$productionDetail->sld_file;
        endif;

        $document = new \Mpdf\Mpdf();
        $filesTotal = sizeof($filenames);
        $fileNumber = 1;

        if (!file_exists($pdfFileName)):
            $handle = fopen($pdfFileName, 'w');
            fclose($handle);
        endif;

        foreach ($filenames as $fileName):
            if (file_exists($fileName)):
                $pagesInFile = $document->setSourceFile($fileName);

                for ($i = 1; $i <= $pagesInFile; $i++) :
                    $tplId = $document->ImportPage($i); // in mPdf v8 should be 'importPage($i)'
                    //$size = $document->getTemplateSize($tplId);

                    $document->UseTemplate($tplId);
                    if (($fileNumber < $filesTotal) || ($i != $pagesInFile)) :
                        $document->AddPage();
                    endif;
                endfor;
            endif;
            $fileNumber++;
        endforeach;

        $document->Output($pdfFileName,'I');
    }
}
?>