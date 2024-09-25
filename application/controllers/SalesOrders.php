<?php
class SalesOrders extends MY_Controller{
    private $indexPage = "sales_order/index";
    private $form = "sales_order/form";
    private $dispatchForm = "sales_order/dispatch_form";

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
        $this->data['unitList'] = $this->item->itemUnits();
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
        if(empty($data['order_type']))
            $errorMessage['order_type'] = "Production Type is required.";
        if(empty($data['itemData']))
            $errorMessage['itemData'] = "Item Details is required.";
        
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            /* $this->load->library('upload');
            $filePath = realpath(APPPATH . '../assets/uploads/sales_order');

            $itemData = array();
            foreach($data['itemData'] as $key => $row):
                if(isset($_FILES['itemData']['name'][$key]['attachment']) && !empty($_FILES['itemData']['name'][$key]['attachment'])):
                    $_FILES['userfile']['name']     = $_FILES['itemData']['name'][$key]['attachment'];
                    $_FILES['userfile']['type']     = $_FILES['itemData']['type'][$key]['attachment'];
                    $_FILES['userfile']['tmp_name'] = $_FILES['itemData']['tmp_name'][$key]['attachment'];
                    $_FILES['userfile']['error']    = $_FILES['itemData']['error'][$key]['attachment'];
                    $_FILES['userfile']['size']     = $_FILES['itemData']['size'][$key]['attachment'];

                    $config = ['file_name' => time()."_soi_".$_FILES['userfile']['name'],'allowed_types' => '*','max_size' => 10240,'overwrite' => FALSE, 'upload_path'	=>$filePath];

                    $this->upload->initialize($config);
                    if(!$this->upload->do_upload()):
                        $errorMessage['ba_file_'.$key] = $this->upload->display_errors();
                        $this->printJson(["status"=>0,"message"=>$errorMessage]);
                    else:
                        $uploadData = $this->upload->data();
                        $row['attachment'] = $uploadData['file_name'];
                    endif;
                endif;

                if(!empty($row['id']) && $row['attachment_status'] == 2):
                    $soItem = $this->salesOrder->getSalesOrderItem(['id'=>$row['id']]);
                    if(!empty($soItem->attachment)):
                        if(file_exists($filePath."/".$soItem->attachment)):
                            unlink($filePath."/".$soItem->attachment);
                        endif;
                    endif;

                    $row['attachment'] = "";
                endif;

                unset($row['attachment_status']);

                $itemData[] = $row;
            endforeach;
            $data['itemData'] = $itemData; */

            $this->printJson($this->salesOrder->save($data));
        endif;
    }

    public function edit($id){
        $this->data['dataRow'] = $dataRow = $this->salesOrder->getSalesOrder(['id'=>$id,'itemList'=>1]);
        $this->data['gstinList'] = $this->party->getPartyGSTDetail(['party_id' => $dataRow->party_id]);
        $this->data['partyList'] = $this->party->getPartyList(['party_category' => 1]);
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>1]);
        $this->data['unitList'] = $this->item->itemUnits();
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

    public function cancelSO(){
        $data = $this->input->post();
        if(empty($data['trans_child_id'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->salesOrder->cancelSO($data));
        endif;
    }

    public function dispatch(){
        $data = $this->input->post();
        $this->data['id'] = $data['id'];
        $this->load->view($this->dispatchForm,$this->data);
    }

    public function getPendingDispatchList(){
        $data = $this->input->post();
        $result = $this->salesOrder->getPendingDispatchItems($data);

        $i=1;$tbody = '';
        foreach($result as $row):
            $tbody .= '<tr>
                <td>'.$i.'</td>
                <td>'.$row->item_name.'</td>
                <td>'.$row->job_number.'</td>
                <td>'.floatval($row->qty).'</td>
                <td>'.floatval($row->pending_qty).'</td>
                <td>
                    <input type="hidden" name="itemData['.$i.'][so_id]" value="'.$row->trans_main_id.'">
                    <input type="hidden" name="itemData['.$i.'][so_trans_id]" value="'.$row->id.'">
                    <input type="text" name="itemData['.$i.'][qty]" id="qty_'.$i.'" class="form-control floatOnly" value="'.floatval($row->pending_qty).'">
                    <div class="error qty_'.$i.'"></div>
                </td>
            </tr>';
            $i++;
        endforeach;

        $tbody  = (!empty($tbody))?$tbody:'<tr><td colspan="6" class="text-center">No data available in table</td></tr>';

        $this->printJson(['status'=>1,'tbodyData'=>$tbody]);
    }

    public function getDispatchedList(){
        $data = $this->input->post();
        $result = $this->salesOrder->getDispatchedItemList($data);

        $i=1;$tbody = '';
        foreach($result as $row):
            $deleteParam = "{'postData':{'id' : ".$row->id."},'message' : 'File','res_function':'resDeleteDispatchTrans','fndelete':'deleteDispatchTrans'}";
            $removeBtn = '<a class="btn btn-outline-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="left"><i class="ti-trash"></i></a>';

            $tbody .= '<tr>
                <td>'.$i.'</td>
                <td>'.$row->item_name.'</td>
                <td>'.$row->job_number.'</td>
                <td>'.formatDate($row->dispatch_date).'</td>
                <td>'.$row->vehicle_no.'</td>
                <td>'.$row->challan_no.'</td>
                <td>'.$row->invoice_no.'</td>
                <td>'.floatval($row->qty).'</td>
                <td>'.$row->remark.'</td>
                <td class="text-center">
                    '.$removeBtn.'
                </td>
            </tr>';
            $i++;
        endforeach;

        $tbody  = (!empty($tbody))?$tbody:'<tr><td colspan="10" class="text-center">No data available in table</td></tr>';

        $this->printJson(['status'=>1,'tbodyData'=>$tbody]);
    }

    public function saveDispatchDetails(){
        $data = $this->input->post();
        $errorMessage = [];

        if(empty($data['dispatch_date']))
            $errorMessage['dispatch_date'] = "Dispatch Date is required.";
        if(empty($data['challan_no']) && empty($data['invoice_no']))
            $errorMessage['invoice_no'] = "Challan/Invoice No. is required.";

        if(empty($data['itemData'])):
            $errorMessage['item_error'] = "Item Details is required.";
        elseif(!empty($data['itemData']) && array_sum(array_column($data['itemData'],'qty')) == 0):
            $errorMessage['item_error'] = "Dispatch Qty. is required.";
        else:
            $i=1;
            foreach($data['itemData'] as $row):
                if(floatval($row['qty']) > 0):
                    $itemDetail = $this->salesOrder->getSalesOrderItem(['id'=>$row['so_trans_id']]);
                    $pendingQty = $itemDetail->qty - $itemDetail->dispatch_qty;
                    if(floatval($row['qty']) > $pendingQty):
                        $errorMessage['qty_'.$i] = "Invalid Qty.";
                    endif;
                endif;
                $i++;
            endforeach;
        endif;

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->salesOrder->saveDispatchDetails($data));
        endif;
    }

    public function deleteDispatchTrans(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->salesOrder->deleteDispatchTrans($id));
        endif;
    }
}
?>