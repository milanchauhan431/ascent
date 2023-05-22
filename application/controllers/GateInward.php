<?php
class GateInward extends MY_Controller{
    private $indexPage = "gate_inward/index";
    private $form = "gate_inward/form";

    public function __construct(){
        parent::__construct();
        $this->data['headData']->pageTitle = "Gate Inward Register";
		$this->data['headData']->controller = "gateInward";
        $this->data['headData']->pageUrl = "gateInward";
    }

    public function index(){
        $this->data['tableHeader'] = getStoreDtHeader("pendingGE");
		$this->load->view($this->indexPage,$this->data);
    }

    public function getDTRows($type = 1,$status = 0){
        $data = $this->input->post();
        $data['trans_type'] = $type;
        $data['trans_status'] = $status;

        $result = $this->gateInward->getDTRows($data);
        $sendData = array();$i=($data['start']+1);

        foreach($result['data'] as $row):
            $row->sr_no = $i++;        
            $row->controller = $this->data['headData']->controller;
            $sendData[] = getGateInwardData($row);
        endforeach;

        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function createGI(){
        $data = $this->input->post();
        $gateEntryData = $this->gateEntry->getGateEntry($data['id']);
        $this->data['gateEntryData'] = $gateEntryData;
        $this->data['partyList'] = $this->party->getPartyList();
        $this->data['itemList'] = $this->item->getItemList();
        $this->data['locationList'] = $this->storeLocation->getStoreLocationList(['store_type'=>'0,15','final_location'=>1]);
        $this->data['materialGradeList'] = $this->materialGrade->getMaterialGrades();
        $this->data['trans_no'] = $this->gateEntry->getNextNo(2);
        $this->data['trans_prefix'] = "GE/".n2y(getFyDate("Y"));
        $this->data['trans_number'] = $this->data['trans_prefix'].sprintf("%04d",$this->data['trans_no']);
        $this->load->view($this->form,$this->data);
    }

    public function addGateInward(){
        $this->data['partyList'] = $this->party->getPartyList();
        $this->data['itemList'] = $this->item->getItemList();
        $this->data['locationList'] = $this->storeLocation->getStoreLocationList(['store_type'=>'0,15','final_location'=>1]);
        $this->data['materialGradeList'] = $this->materialGrade->getMaterialGrades();
        $this->data['trans_no'] = $this->gateEntry->getNextNo(2);
        $this->data['trans_prefix'] = "GE/".n2y(getFyDate("Y"));
        $this->data['trans_number'] = $this->data['trans_prefix'].sprintf("%04d",$this->data['trans_no']);
        $this->load->view($this->form,$this->data);
    }

    public function getPoNumberListOnItemId(){
        $data = $this->input->post();
        $poList = $this->purchaseOrder->getItemWisePoList($data);

        $options = '<option value="">Select Purchase Order</option>';
        foreach($poList as $row):
            $options .= '<option value="'.$row->po_trans_id.'" data-po_id="'.$row->po_id.'" data-po_no="'.$row->trans_number.'" >'.$row->trans_number.' [ Pending Qty : '.$row->pending_qty.' ]</option>';
        endforeach;

        $this->printJson(['status'=>1,'poOptions'=>$options]);
    }

    public function save(){
        $data = $this->input->post(); 
        $errorMessage = array();
        
        if(empty($data['party_id']))
            $errorMessage['party_id'] = "Party Name is required.";
        if(empty($data['batchData']))
            $errorMessage['batch_details'] = "Batch Details is required.";

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->gateInward->save($data));
        endif;
    }
}
?>