<?php
class GateInwardModel extends masterModel{
    private $mir = "mir";
    private $mirTrans = "mir_transaction";
    private $transChild = "trans_child";
    private $stockTrans = "stock_transaction";


    public function getDTRows($data){
        $data['tableName'] = $this->mir;

        $data['select'] = "mir.id,mir.trans_number,DATE_FORMAT(mir.trans_date,'%d-%m-%Y') as trans_date,mir.qty,party_master.party_name,item_master.item_name,mir.inv_no,ifnull(DATE_FORMAT(mir.inv_date,'%d-%m-%Y'),'') as inv_date,mir.doc_no,ifnull(DATE_FORMAT(mir.doc_date,'%d-%m-%Y'),'') as doc_date,trans_main.trans_number as po_number,mir.qty_kg,mir.inward_qty,mir.trans_status,mir.trans_type";

        $data['leftJoin']['party_master'] = "party_master.id = mir.party_id";
        $data['leftJoin']['item_master'] = "item_master.id = mir.item_id";
        $data['leftJoin']['trans_main'] = "trans_main.id = mir.po_id";

        $data['where']['mir.trans_status'] = $data['trans_status'];
        $data['where']['mir.trans_type'] = $data['trans_type'];
            
        $data['order_by']['mir.id'] = "DESC";

        if($data['trans_type'] == 1):
            $data['searchCol'][] = "";
            $data['searchCol'][] = "";
            $data['searchCol'][] = "mir.trans_number";
            $data['searchCol'][] = "DATE_FORMAT(mir.trans_date,'%d-%m-%Y')";
            $data['searchCol'][] = "party_master.party_name";
            $data['searchCol'][] = "item_master.full_name";
            $data['searchCol'][] = "mir.qty";
            $data['searchCol'][] = "mir.inv_no";
            $data['searchCol'][] = "ifnull(DATE_FORMAT(mir.inv_date,'%d-%m-%Y'),'')";
            $data['searchCol'][] = "mir.doc_no";
            $data['searchCol'][] = "ifnull(DATE_FORMAT(mir.doc_date,'%d-%m-%Y'),'')";
        else:
            $data['searchCol'][] = "";
            $data['searchCol'][] = "";
            $data['searchCol'][] = "mir.trans_number";
            $data['searchCol'][] = "DATE_FORMAT(mir.trans_date,'%d-%m-%Y')";
            $data['searchCol'][] = "party_master.party_name";
            $data['searchCol'][] = "item_master.full_name";
            $data['searchCol'][] = "mir.inward_qty";
            $data['searchCol'][] = "mir.qty";
            $data['searchCol'][] = "mir.qty_kg";
            $data['searchCol'][] = "trans_main.trans_number";
        endif;

        $columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;
		if (isset($data['order'])) {
			$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];
		}

		return $this->pagingRows($data);
    }

    public function save($data){
        try{
            $this->db->trans_begin();

            if(!empty($data['id'])):
                $this->trash($this->mirTrans,['mir_id',$data['id']]);
                
                $gateInwardData = $this->getGateInward($data['id']);
                $this->store($this->mir,['id'=>$gateInwardData->ref_id,'trans_status'=>0]);
            else:
                $data['trans_no'] = $this->gateEntry->getNextNo(2);
                $data['trans_prefix'] = "GE/".n2y(getFyDate("Y"));
                $data['trans_number'] = $data['trans_prefix'].sprintf("%04d",$data['trans_no']);
            endif;

            foreach($data['batchData'] as $row):         
                $itemData = $this->item->getItem($data['item_id']);

                $masterData = [
                    'id' => $row['mir_id'],
                    'ref_id' => $data['ref_id'],
                    'trans_type' => 2,
                    'trans_prefix' => $data['trans_prefix'],
                    'trans_no' => $data['trans_no'],
                    'trans_number' => $data['trans_number'],
                    'trans_date' => $data['trans_date'],
                    'party_id' => $data['party_id'],
                    'item_id' => $row['item_id'],
                    'item_stock_type' => $itemData->stock_type,
                    'po_id' => $row['po_id'],
                    'po_trans_id' => $row['po_trans_id'],
                    'qty' => $row['batch_qty']
                ];

                $result = $this->store($this->mir,$masterData,'Gate Inward');

                $batchData = [
                    'id' => $row['id'],
                    'mir_id' => $result['id'],
                    'type' => 1,
                    'location_id' => $row['location_id'],
                    'qty' => $row['batch_qty'],
                    'item_id' => $row['item_id'],
                    'heat_no' => $row['heat_no'],
                    'mill_heat_no' => $row['mill_heat_no'],
                    'is_delete' => 0
                ];

                if($data['item_stock_type'] == 1):
                    $nextBatchNo = $this->gateReceipt->getNextBatchOrSerialNo(['trans_id'=>'','item_id'=>$row['item_id'],'heat_no'=>$row['heat_no']]);

                    $batchData['batch_no'] = $nextBatchNo['batch_no'];                    
                    $batchData['serial_no'] = $nextBatchNo['serial_no'];
                elseif($data['item_stock_type'] == 2):
                    $batchData['batch_no'] = $itemData->item_code.sprintf(n2y(date('Y'))."%03d",$data['trans_no']);
                else:
                    $batchData['batch_no'] = "GB";
                    $batchData['serial_no'] = 0;
                endif;

                $batch = $this->store($this->mirTrans,$batchData);

                if(!empty($row['po_trans_id'])):
                    $setData = array();
                    $setData['tableName'] = $this->transChild;
                    $setData['where']['id'] = $row['po_trans_id'];
                    $setData['set']['dispatch_qty'] = 'dispatch_qty, + '.$row['batch_qty'];
                    $setData['update']['trans_status'] = "(CASE WHEN (dispatch_qty + ".$row['batch_qty'].") >= qty THEN 1 ELSE 0 END)";
                    $this->setValue($setData);
                endif;
                
            endforeach;

            //Update GI Status
            $this->store($this->mir,['id'=>$data['ref_id'],'trans_status'=>1]);            

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }

    public function getNextBatchOrSerialNo($data){
		$result = array(); $code = "";

        $itemData = $this->item->getItem($data['item_id']);
        $code = (!empty($itemData->stock_type) && $itemData->stock_type == 2)?$itemData->item_code:"";
        
        $itemTypes = [5,6,7];
        
		if(!empty($data['trans_id'])):
            $queryData = array();
			$queryData['select'] = "serial_no,heat_no";
			$queryData['tableName'] = $this->mirTrans;
            $queryData['where']['type'] = 1;
			$queryData['where']['id'] = $data['trans_id'];
			$result = $this->row($queryData);

			if(!empty($result->serial_no) && $data['heat_no'] == $result->heat_no):
                if(in_array($itemData->item_type,$itemTypes)):
			        $code .= sprintf("-%03d",$result->serial_no);
			    else:
			        $code .= sprintf(n2y(date('Y'))."%03d",$result->serial_no);    
			    endif;
				return ['status'=>1,'batch_no'=>$code,'serial_no'=>$result->serial_no];
			endif;			
		endif;
		
		if(!empty($itemData->stock_type) && $itemData->stock_type == 1):
            $queryData = array();
            $queryData['select'] = "serial_no,heat_no";
			$queryData['tableName'] = $this->mirTrans;
			$queryData['where']['item_id'] = $data['item_id'];
            $queryData['where']['type'] = 1;
			$queryData['where']['heat_no'] = $data['heat_no'];
			$result = $this->row($queryData);
			
			if(!empty($result->serial_no)):
                if(in_array($itemData->item_type,$itemTypes)):
			        $code .= sprintf("-%03d",$result->serial_no);
			    else:
			        $code .= sprintf(n2y(date('Y'))."%03d",$result->serial_no);    
			    endif;
				return ['status'=>1,'batch_no'=>$code,'serial_no'=>$result->serial_no];
			endif;
		endif;

		$queryData = array();
		$queryData['select'] = "ifnull(MAX(serial_no) + 1,1) as serial_no";
		$queryData['tableName'] = $this->mirTrans;
        $queryData['where']['type'] = 1;
		$queryData['where']['item_id'] = $data['item_id'];
		$queryData['where']['is_delete'] = 0;
		$queryData['where']['YEAR(created_at)'] = date("Y");
		$serial_no = $this->specificRow($queryData)->serial_no;
		
		if(in_array($itemData->item_type,$itemTypes)):
	        $code .= sprintf("-%03d",$serial_no);
	    else:
	        $code .= sprintf(n2y(date('Y'))."%03d",$serial_no);    
	    endif;
		return ['status'=>1,'batch_no'=>$code,'serial_no'=>$serial_no];
	}


    
}
?>