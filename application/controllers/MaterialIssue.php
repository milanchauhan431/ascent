<?php
class MaterialIssue extends MY_Controller{
    private $index = "material_issue/index";
    private $form = "material_issue/form";

    public function __construct(){
        parent::__construct();
        $this->data['headData']->pageTitle = "Material Issue";
		$this->data['headData']->controller = "materialIssue";
        $this->data['headData']->pageUrl = "materialIssue";
        $this->data['entry_type'] = $this->transMainModel->getEntryType(['controller'=>'materialIssue'])->id;
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

    public function getBatchWiseItemStock(){
		$data = $this->input->post();
		
		$data['batchDetail'] = (!empty($data['batchDetail']))?json_decode($data['batchDetail'],true):[];

		$postData = ["item_id" => $data['item_id'], 'stock_required'=>1, 'group_by'=>'location_id,batch_no'];		

		if(!empty($data['batchDetail']) && !empty($data['id'])):			
			$batch_no = array_unique(array_column($data['batchDetail'],'batch_no'));
			$batch_no = "'".implode("', '",$batch_no)."'";

            $location_ids = array_unique(array_column($data['batchDetail'],'location_id'));
			$location_ids = "'".implode("', '",$location_ids)."'";

			$postData['customHaving'] = "(SUM(stock_transaction.qty * stock_transaction.p_or_m) > 0 OR (stock_transaction.batch_no IN (".$batch_no.") AND stock_transaction.location_id IN (".$location_ids.")))";
		endif;

		$batchData = $this->materialIssue->getItemStockBatchWise($postData);

		$batchDetail = [];
		if(!empty($data['batchDetail'])):			
			$batchDetail = array_reduce($data['batchDetail'],function($item,$row){
				$item[$row['remark']] = $row['batch_qty'];
				return $item;
			},[]);
		endif;

        $tbody = '';$i=1;
        if(!empty($batchData)):
            foreach($batchData as $row):
                $batchId = trim(preg_replace('/[^A-Za-z0-9]/', '', $row->batch_no)).$row->location_id.$row->item_id;
                $location_name = '['.$row->store_name.'] '.$row->location;

				$qty = (isset($batchDetail[$batchId]))?$batchDetail[$batchId]:0;

				if(!empty($data['id'])):
					$row->qty = $row->qty + $qty;
				endif;

                $locationName = '['.$row->store_name.'] '.$row->location;

                $tbody .= '<tr id="'.$batchId.'" data-ind="'.$i.'">
                    <td>'.$i.'</td>
                    <td>'.$locationName.'</td>
                    <td>
                        '.floatval($row->qty).'
                    </td>
                    <td>
                        <input type="hidden" name="batchDetail['.$i.'][location_id]" id="location_id_'.$i.'" value="'.$row->location_id.'">
                        <input type="hidden" name="batchDetail['.$i.'][batch_no]" id="batch_no_'.$i.'" value="'.$row->batch_no.'">
                        <input type="hidden" name="batchDetail['.$i.'][location_name]" id="location_name_'.$i.'" value="'.$locationName.'">
                        <input type="text" name="batchDetail['.$i.'][batch_qty]" id="batch_qty_'.$i.'" class="form-control floatOnly totalQty" value="'.$qty.'">
                        <input type="hidden" name="batchDetail['.$i.'][remark]" id="batch_id_'.$i.'" value="'.$batchId.'">
                        <input type="hidden" name="batchDetail['.$i.'][batch_stock]" id="batch_stock_'.$i.'" value="'.floatVal($row->qty).'">
                        <div class="error batch_qty_'.$i.'"></div>
                    </td>
                </tr>';
                $i++;
            endforeach;
        endif;

		if(empty($tbody)):
            $tbody = '<tr>
                <td colspan="4" class="text-center">No data available in table</td>
            </tr>';
        endif;

        $this->printJson(['status'=>1,'tbodyData'=>$tbody]);
	}

    public function save(){
        $data = $this->input->post();
        $errorMessage = [];

        if(empty($data['trans_date']))
            $errorMessage['trans_date'] = "Entry Date is required.";
        if(empty($data['item_id']))
            $errorMessage['item_id'] = "Item Name is required.";
        if(empty($data['batchDetail'])):
            $errorMessage['issue_qty'] = "Qty is required.";
        else:
            $batchDetail = [];
            if(!empty($data['id'])):
                $issueDetail = $this->materialIssue->getMaterialIssueDetail(['id'=>$data['id']]);
                $issueQty = (!empty($issueDetail->batch_detail))?json_decode($issueDetail->batch_detail,true):[];

                $batchDetail = array_reduce($issueQty,function($item,$row){
                    $item[$row['remark']] = $row['batch_qty'];
                    return $item;
                },[]);
            endif;

            $bQty = array();
            foreach($data['batchDetail'] as $key=>$batch):
                $postData = [
                    'location_id' => $batch['location_id'],
                    'batch_no' => $batch['batch_no'], 
                    'item_id' => $data['item_id'],
                    'stock_required' => 1,
                    'single_row' => 1
                ];                        
                $stockData = $this->materialIssue->getItemStockBatchWise($postData);  
                $batchKey = "";
                $batchKey = $batch['remark'];

                $issueBatchQty = (isset($batchDetail[$batchKey]))?$batchDetail[$batchKey]:0;
                
                $stockQty = (!empty($stockData->qty))?floatVal($stockData->qty):0;
                if(!empty($data['id'])):                            
                    $stockQty = $stockQty + $issueBatchQty;
                endif;
                
                if(!isset($bQty[$batchKey])):
                    $bQty[$batchKey] = $batch['batch_qty'] ;
                else:
                    $bQty[$batchKey] += $row['batch_qty'];
                endif;

                if(empty($stockQty)):
                    $errorMessage['batch_qty_'.$key] = "Stock not available.".$stockQty;
                else:
                    if($bQty[$batchKey] > $stockQty):
                        $errorMessage['batch_qty_'.$key] = "Stock not available.";
                    endif;
                endif;
            endforeach;
        endif;

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->materialIssue->save($data));
        endif;
    }

    public function edit(){
        $data = $this->input->post();
        $this->data['dataRow'] = $this->materialIssue->getMaterialIssueDetail($data);
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>"2,3"]);
        $this->load->view($this->form,$this->data);
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->materialIssue->delete($id));
        endif;
    }
}
?>