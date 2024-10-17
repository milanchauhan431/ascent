<?php
class DispatchChallan extends MY_Controller{
    private $indexPage = "dispatch_challan/index";
    private $dispatchForm = "dispatch_challan/form";

    public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "Dispatch Challan";
		$this->data['headData']->controller = "dispatchChallan";        
        $this->data['headData']->pageUrl = "dispatchChallan";
        $this->data['entry_type'] = $this->transMainModel->getEntryType(['controller'=>'salesOrders'])->id;
	}

    public function index(){
        $this->data['tableHeader'] = getSalesDtHeader("salesOrders");
        $this->load->view($this->indexPage,$this->data);
    }

    public function getDTRows($status = 0){
        $data = $this->input->post();

        if($status == 0):
            $data['status'] = 4;
            $data['entry_type'] = $this->data['entry_type'];
            $result = $this->salesOrder->getDTRows($data);
        else:
            $result = $this->dispatchChallan->getDTRows($data);
        endif;

        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):
            $row->sr_no = $i++;
            if($status == 0):
                $sendData[] = getSalesOrderData($row);
            else:
                $sendData[] = getDispatchChallanData($row);
            endif;
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function dispatch(){
        $data = $this->input->post();
        $this->data['chl_prefix'] = "DC/".$this->shortYear.'/';
        $this->data['chl_no'] = $this->dispatchChallan->getNectNo();
        $this->data['itemList'] = $this->getPendingDispatchList($data);
        $this->load->view($this->dispatchForm,$this->data);
    }

    public function getPendingDispatchList($data){
        //$data = $this->input->post();
        $result = $this->dispatchChallan->getPendingDispatchItems($data);

        $i=1;$tbody = '';
        foreach($result as $row):
            $tbody .= '<tr>
                <td>'.$i.'</td>
                <td>'.$row->item_name.'</td>
                <td>'.$row->job_number.'</td>
                <td>'.floatval($row->qty).'</td>
                <td>'.floatval($row->pending_qty).'</td>
                <td>
                    <input type="hidden" name="itemData['.$i.'][id]" value="">
                    <input type="hidden" name="itemData['.$i.'][so_id]" value="'.$row->trans_main_id.'">
                    <input type="hidden" name="itemData['.$i.'][so_trans_id]" value="'.$row->id.'">
                    <input type="text" name="itemData['.$i.'][qty]" id="qty_'.$i.'" class="form-control floatOnly" value="'.floatval($row->pending_qty).'">
                    <div class="error qty_'.$i.'"></div>
                </td>
            </tr>';
            $i++;
        endforeach;

        $tbody  = (!empty($tbody))?$tbody:'<tr><td colspan="7" class="text-center">No data available in table</td></tr>';

        //$this->printJson(['status'=>1,'tbodyData'=>$tbody]);
        return $tbody;
    }

    public function saveDispatchDetails(){
        $data = $this->input->post();
        $errorMessage = [];

        if(empty($data['dispatch_date']))
            $errorMessage['dispatch_date'] = "Dispatch Date is required.";
        if(empty($data['challan_no']))
            $errorMessage['challan_no'] = "Challan No. is required.";

        if(empty($data['itemData'])):
            $errorMessage['item_error'] = "Item Details is required.";
        elseif(!empty($data['itemData']) && array_sum(array_column($data['itemData'],'qty')) == 0):
            $errorMessage['item_error'] = "Dispatch Qty. is required.";
        else:
            $i=1;
            foreach($data['itemData'] as $row):
                if(floatval($row['qty']) > 0):
                    $itemDetail = $this->salesOrder->getSalesOrderItem(['id'=>$row['so_trans_id']]);

                    $dispatchQty = 0;
                    if(!empty($data['is_edit'])):
                        $transItem = $this->dispatchChallan->getDispatchedItem(['id'=>$row['id']]);
                        $dispatchQty = $transItem->qty;
                    endif;

                    $pendingQty = ($itemDetail->qty - $itemDetail->dispatch_qty) + $dispatchQty;
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
            $this->printJson($this->dispatchChallan->saveDispatchDetails($data));
        endif;
    }

    public function editDispatch(){
        $data = $this->input->post();
        $result = $this->dispatchChallan->getDispatchedItemList($data);

        $dataRow = new stdClass();
        $dataRow->is_edit = 1;

        $i=1;$tbody = '';
        foreach($result as $row):
            $tbody .= '<tr>
                <td>'.$i.'</td>
                <td>'.$row->item_name.'</td>
                <td>'.$row->job_number.'</td>
                <td>'.floatval($row->order_qty).'</td>
                <td>'.floatval($row->pending_qty).'</td>
                <td>
                    <input type="hidden" name="itemData['.$i.'][id]" value="'.$row->id.'">
                    <input type="hidden" name="itemData['.$i.'][so_id]" value="'.$row->so_id.'">
                    <input type="hidden" name="itemData['.$i.'][so_trans_id]" value="'.$row->so_trans_id.'">
                    <input type="text" name="itemData['.$i.'][qty]" id="qty_'.$i.'" class="form-control floatOnly" value="'.floatval($row->qty).'">
                    <div class="error qty_'.$i.'"></div>
                </td>
            </tr>';
            $i++;

            $dataRow->chl_prefix = $row->chl_prefix;
            $dataRow->chl_no = $row->chl_no;
            $dataRow->challan_no = $row->challan_no;
            $dataRow->dispatch_date = $row->dispatch_date;
            $dataRow->invoice_no = $row->invoice_no;
            $dataRow->vehicle_no = $row->vehicle_no;
            $dataRow->remark = $row->remark;
        endforeach;

        $tbody  = (!empty($tbody))?$tbody:'<tr><td colspan="7" class="text-center">No data available in table</td></tr>';

        $dataRow->itemList = $tbody;

        $this->data['dataRow'] = $dataRow;
        $this->load->view($this->dispatchForm,$this->data);
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->dispatchChallan->deleteDispatchTrans($id));
        endif;
    }
}
?>