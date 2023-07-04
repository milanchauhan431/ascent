<?php
class PurchaseOrders extends MY_Controller{
    private $indexPage = "purchase_order/index";
    private $form = "purchase_order/form";

    public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "Purchase Order";
		$this->data['headData']->controller = "purchaseOrders";        
        $this->data['headData']->pageUrl = "purchaseOrders";
        $this->data['entry_type'] = $this->transMainModel->getEntryType(['controller'=>'purchaseOrders'])->id;
	}

    public function index(){
        $this->data['tableHeader'] = getPurchaseDtHeader("purchaseOrders");
        $this->load->view($this->indexPage,$this->data);
    }

    public function getDTRows($status = 0){
        $data = $this->input->post();$data['status'] = $status;
        $data['entry_type'] = $this->data['entry_type'];
        $result = $this->purchaseOrder->getDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):
            $row->sr_no = $i++;
            $sendData[] = getPurchaseOrderData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function createOrder($ids){
        $ids = decodeURL($ids);
        $this->data['trans_prefix'] = $this->transMainModel->getTransPrefix($this->data['entry_type']);
        $this->data['trans_no'] = $this->transMainModel->nextTransNo($this->data['entry_type']);
        $this->data['trans_number'] = $this->data['trans_prefix'].$this->data['trans_no'];
        $this->data['partyList'] = $this->party->getPartyList(['party_category'=>"2,3"]);
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>"2,3"]);
        $this->data['hsnList'] = $this->hsnModel->getHSNList();
		$this->data['taxList'] = $this->taxMaster->getActiveTaxList(1);
        $this->data['expenseList'] = $this->expenseMaster->getActiveExpenseList(1);
        $this->data['orderItemList'] = $this->purchaseIndent->getRequestItems($ids);
        $this->data['termsList'] = $this->terms->getTermsList(['type'=>'Purchase']);
        //$this->data['companyInfo'] = $this->masterModel->getCompanyInfo();
        $this->data['unitList'] = $this->item->itemUnits();
        $this->data['transportList'] = $this->transport->getTransportList();
        $this->data['brandList'] = $this->brandMaster->getBrandList();
        $this->load->view($this->form,$this->data);
    }

    public function addOrder(){
        $this->data['trans_prefix'] = $this->transMainModel->getTransPrefix($this->data['entry_type']);
        $this->data['trans_no'] = $this->transMainModel->nextTransNo($this->data['entry_type']);
        $this->data['trans_number'] = $this->data['trans_prefix'].$this->data['trans_no'];
        $this->data['partyList'] = $this->party->getPartyList(['party_category'=>"2,3"]);
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>"2,3"]);
        $this->data['hsnList'] = $this->hsnModel->getHSNList();
		$this->data['taxList'] = $this->taxMaster->getActiveTaxList(1);
        $this->data['expenseList'] = $this->expenseMaster->getActiveExpenseList(1);
        $this->data['termsList'] = $this->terms->getTermsList(['type'=>'Purchase']);
        //$this->data['companyInfo'] = $this->masterModel->getCompanyInfo();
        $this->data['unitList'] = $this->item->itemUnits();
        $this->data['transportList'] = $this->transport->getTransportList();
        $this->data['brandList'] = $this->brandMaster->getBrandList();
        $this->load->view($this->form,$this->data);
    }

    public function save(){
        $data = $this->input->post();
        $errorMessage = array();

        if(empty($data['party_id']))
            $errorMessage['party_id'] = "Party Name is required.";
        if(empty($data['itemData'])):
            $errorMessage['itemData'] = "Item Details is required.";
        else:
            foreach($data['itemData'] as $key => $row):
                if(!empty(floatVal($row['qty'])) && !empty(floatVal($row['std_pck_qty']))):
                    $boxQty = floatval(floatVal($row['qty']) / floatVal($row['std_pck_qty']));
                    if(floor($boxQty) != $boxQty):
                        $errorMessage['qty'.$key] = "Invalid qty against packing standard.";
                    endif;
                endif;
            endforeach;
        endif;
        
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->purchaseOrder->save($data));
        endif;
    }

    public function edit($id){
        $this->data['dataRow'] = $dataRow = $this->purchaseOrder->getPurchaseOrder(['id'=>$id,'itemList'=>1]);
        $this->data['gstinList'] = $this->party->getPartyGSTDetail(['party_id' => $dataRow->party_id]);
        $this->data['partyList'] = $this->party->getPartyList(['party_category'=>"2,3"]);
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>"2,3"]);
        $this->data['hsnList'] = $this->hsnModel->getHSNList();
		$this->data['taxList'] = $this->taxMaster->getActiveTaxList(2);
        $this->data['expenseList'] = $this->expenseMaster->getActiveExpenseList(2);
        $this->data['termsList'] = $this->terms->getTermsList(['type'=>'Purchase']);
        $this->data['unitList'] = $this->item->itemUnits();
        $this->data['transportList'] = $this->transport->getTransportList();
        $this->data['brandList'] = $this->brandMaster->getBrandList();
        $this->load->view($this->form,$this->data);
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->purchaseOrder->delete($id));
        endif;
    }

    function printPO($id){
		$this->data['poData'] = $poData = $this->purchaseOrder->getPurchaseOrder(['id'=>$id,'itemList'=>1]);
		$this->data['partyData'] = $this->party->getParty(['id'=>$poData->party_id]);
        $this->data['taxList'] = $this->taxMaster->getActiveTaxList(2);
        $this->data['expenseList'] = $this->expenseMaster->getActiveExpenseList(2);
		$this->data['companyData'] = $this->masterModel->getCompanyInfo();
		$response="";
		$logo=base_url('assets/images/logo.png');
		$this->data['letter_head']=base_url('assets/images/letterhead-top.png');
				
		//print_r($pdfData);exit;
		$prepare = $this->employee->getEmployee(['id'=>$poData->created_by]);
		$this->data['prepareBy'] = $prepareBy = $prepare->emp_name.' <br>('.formatDate($poData->created_at).')'; 
		$this->data['approveBy'] = $approveBy = '';
		if(!empty($poData->is_approve)){
            $approve = $this->employee->getEmployee(['id'=>$poData->is_approve]);
			$this->data['approveBy'] = $approveBy .= $approve->emp_name.' <br>('.formatDate($poData->approve_date).')'; 
		}
		
        $pdfData = $this->load->view('purchase_order/print',$this->data,true);

		$htmlHeader = "";//'<img src="'.$this->data['letter_head'].'" class="img">';
		$htmlFooter = '
            <table class="table top-table" style="margin-top:10px;">
                <tr>
                    <td style="width:25%;">PO No. & Date : '.$poData->trans_number.' ['.formatDate($poData->trans_date).']</td>
                    <td style="width:25%;"></td>
                    <td style="width:25%;text-align:right;">Page No. {PAGENO}/{nbpg}</td>
                </tr>
            </table>';
        
            //print_r($pdfData);exit;
		$mpdf = new \Mpdf\Mpdf();
		$pdfFileName=str_replace(["/","-"],"_",$poData->trans_number).'.pdf';
		$stylesheet = file_get_contents(base_url('assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css'));
		$stylesheet = file_get_contents(base_url('assets/css/style.css?v='.time()));
		$stylesheet = file_get_contents(base_url('assets/css/pdf_style.css'));
		$mpdf->WriteHTML($stylesheet,1);
		$mpdf->SetDisplayMode('fullpage');
		$mpdf->SetWatermarkImage($logo,0.03,array(120,45));
		$mpdf->showWatermarkImage = true;
		$mpdf->SetProtection(array('print'));
		
		//$mpdf->SetHTMLHeader($htmlHeader);
		$mpdf->SetHTMLFooter($htmlFooter);
		$mpdf->AddPage('P','','','','',5,5,5,5,5,5,'','','','','','','','','','A4-P');
		$mpdf->WriteHTML($pdfData);
		$mpdf->Output($pdfFileName,'I');
	}

}
?>