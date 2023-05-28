<?php
class SalesOrders extends MY_Controller{
    private $indexPage = "sales_order/index";
    private $form = "sales_order/form";
    private $orderBom = "sales_order/order_bom";
    private $viewBom = "sales_order/view_bom";
    private $requestFrom = "sales_order/pur_request_form";

    public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "Sales Order";
		$this->data['headData']->controller = "salesOrders";        
        $this->data['headData']->pageUrl = "salesOrders";
        $this->data['entry_type'] = $this->transMainModel->getEntryType(['controller'=>'salesOrders'])->id;
	}

    public function index(){
        $this->data['tableHeader'] = getSalesDtHeader("salesOrders");
        $this->load->view($this->indexPage,$this->data);
    }

    public function getDTRows($status = 0){
        $data = $this->input->post();$data['status'] = $status;
        $data['entry_type'] = $this->data['entry_type'];
        $result = $this->salesOrder->getDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):
            $row->sr_no = $i++;
            $sendData[] = getSalesOrderData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addOrder(){
        $this->data['trans_prefix'] = $this->transMainModel->getTransPrefix($this->data['entry_type']);
        $this->data['trans_no'] = $this->transMainModel->nextTransNo($this->data['entry_type']);
        $this->data['trans_number'] = $this->data['trans_prefix'].$this->data['trans_no'];
        $this->data['partyList'] = $this->party->getPartyList(['party_category'=>1]);
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>1]);
        $this->data['hsnList'] = $this->hsnModel->getHSNList();
		$this->data['taxList'] = $this->taxMaster->getActiveTaxList(2);
        $this->data['expenseList'] = $this->expenseMaster->getActiveExpenseList(2);
        $this->data['termsList'] = $this->terms->getTermsList(['type'=>'Sales']);
        $this->load->view($this->form,$this->data);
    }

    public function save(){
        $data = $this->input->post();
        $errorMessage = array();

        if(empty($data['party_id']))
            $errorMessage['party_id'] = "Party Name is required.";
        if(empty($data['itemData']))
            $errorMessage['itemData'] = "Item Details is required.";
        
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->salesOrder->save($data));
        endif;
    }

    public function edit($id){
        $this->data['dataRow'] = $dataRow = $this->salesOrder->getSalesOrder(['id'=>$id,'itemList'=>1]);
        $this->data['gstinList'] = $this->party->getPartyGSTDetail(['party_id' => $dataRow->party_id]);
        $this->data['partyList'] = $this->party->getPartyList(['party_category' => 1]);
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>1]);
        $this->data['hsnList'] = $this->hsnModel->getHSNList();
		$this->data['taxList'] = $this->taxMaster->getActiveTaxList(2);
        $this->data['expenseList'] = $this->expenseMaster->getActiveExpenseList(2);
        $this->data['termsList'] = $this->terms->getTermsList(['type'=>'Sales']);
        $this->load->view($this->form,$this->data);
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->salesOrder->delete($id));
        endif;
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
            
            $this->printJson($this->salesOrder->saveOrderBom($postData));
        endif;
    }

    public function viewOrderBom(){
        $data = $this->input->post();
        $this->data['postData'] = (object) $data;
        $this->load->view($this->viewBom,$this->data);
    }

    public function getOrderBomHtml(){
        $data = $this->input->post();
        $dataRow = $this->salesOrder->getOrderBomItems($data);
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

    public function removeBomItem(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->salesOrder->removeBomItem($id));
        endif;
    }

    public function purchaseRequest(){
        $data = $this->input->post();
        $this->data['dataRow'] = $this->salesOrder->getOrderBomItems($data);
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
            $this->printJson($this->salesOrder->savePurchaseRequest($data));
        endif;
    }
}
?>