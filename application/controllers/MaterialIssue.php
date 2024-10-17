<?php
class MaterialIssue extends MY_Controller{
    private $index = "material_issue/index";
    private $form = "material_issue/form";

    public function __construct(){
        parent::__construct();
        $this->data['headData']->pageTitle = "Material Issue";
		$this->data['headData']->controller = "materialIssue";
        $this->data['headData']->pageUrl = "materialIssue";
    }

    public function index(){
        $this->data['tableHeader'] = getStoreDtHeader("materialIssue");
        $this->load->view($this->index,$this->data);
    }

    public function getDTRows($status = 0){
        $data=$this->input->post();$data['status'] = $status;
        $result = $this->materialIssue->getDTRows($data);
        $sendData = array();
        $i = ($data['start']+1);
        foreach ($result['data'] as $row) :
            $row->sr_no = $i++;
            $sendData[] = getMaterialIssueData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function materialIssue(){
        $this->data['trans_prefix'] = "MI/".$this->shortYear."/";
        $this->data['trans_no'] = $this->materialIssue->getNextNo();
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>"2,3"]);
        $this->load->view($this->form,$this->data);
    }

    public function save(){
        $data = $this->input->post();
        $errorMessage = [];

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->materialIssue->save($data));
        endif;
    }
}
?>