<?php
class MaterialIssueModel extends MasterModel{
    private $materialIssue = "material_issue";
    private $stockTrans = "stock_transaction";

    public function getNextNo(){
        $queryData['tableName'] = $this->materialIssue;
        $queryData['select'] = "IFNULL((MAX(trans_no) + 1),1) as next_no";
        $queryData['where']['trans_date >='] = $this->startYearDate;
        $queryData['where']['trans_date <='] = $this->endYearDate;
        $result = $this->row($queryData);
        return $result->next_no;
    }

    public function getDTRows($data){
        $data['tableName'] = $this->materialIssue;
        $data['select'] = "material_issue.id, material_issue.trans_date, CONCAT(material_issue.trans_prefix, material_issue.trans_no) as trans_number, material_issue.collected_by, material_issue.item_id, item_master.item_code, item_master.item_name, material_issue.req_qty, material_issue.issue_qty, material_issue.return_qty, material_issue.remark,material_issue.trans_status";

        $data['leftJoin']['item_master'] = "item_master.id = material_issue.item_id";

        $data['where']['material_issue.trans_status'] = $data['status'];

        if($data['status'] == 0):
            $data['where']['material_issue.trans_date <='] = $this->endYearDate;
        else:
            $data['where']['material_issue.trans_date >='] = $this->startYearDate;
            $data['where']['material_issue.trans_date <='] = $this->endYearDate;
        endif;

        $data['order_by']['material_issue.trans_date'] = "DESC";
        $data['order_by']['material_issue.trans_no'] = "DESC";

        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "DATE_FORMAT(material_issue.trans_date,'%d-%m-%Y')";
        $data['searchCol'][] = "CONCAT(material_issue.trans_prefix, material_issue.trans_no)";
        $data['searchCol'][] = "material_issue.collected_by";
        $data['searchCol'][] = "item_master.item_code";
        $data['searchCol'][] = "item_master.item_name";
        $data['searchCol'][] = "material_issue.req_qty";
        $data['searchCol'][] = "material_issue.issue_qty";
        $data['searchCol'][] = "material_issue.return_qty";
        $data['searchCol'][] = "material_issue.remark";

		$columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;

		if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
        return $this->pagingRows($data);
    }

    public function getItemStockBatchWise($data){
        $queryData['tableName'] = $this->stockTrans;
        $queryData['select'] = "stock_transaction.id, stock_transaction.ref_date, stock_transaction.item_id, item_master.item_code, item_master.item_name, SUM(stock_transaction.qty * stock_transaction.p_or_m) as qty, stock_transaction.unique_id, stock_transaction.batch_no,  stock_transaction.location_id, lm.location, lm.store_name, stock_transaction.stock_type, stock_transaction.remark";
        
        $queryData['leftJoin']['location_master as lm'] = "lm.id=stock_transaction.location_id";
        $queryData['leftJoin']['item_master'] = "stock_transaction.item_id = item_master.id";

        if(!empty($data['item_id'])): 
            $queryData['where']['stock_transaction.item_id'] = $data['item_id'];           
        endif;

        if(!empty($data['location_id'])):
            $queryData['where']['stock_transaction.location_id'] = $data['location_id'];
        endif;

        if(!empty($data['location_ids'])):
            $queryData['where_in']['stock_transaction.location_id'] = $data['location_ids'];
        endif;

        if(!empty($data['batch_no'])):
            $queryData['where']['stock_transaction.batch_no'] = $data['batch_no'];
        endif;
        
        if(!empty($data['p_or_m'])):
            $queryData['where']['stock_transaction.p_or_m'] = $data['p_or_m'];
        endif;

        if(!empty($data['entry_type'])):
            $queryData['where_in']['stock_transaction.entry_type'] = $data['entry_type'];
        endif;

        if(!empty($data['main_ref_id'])):
            $queryData['where']['stock_transaction.main_ref_id'] = $data['main_ref_id'];
        endif;

        if(!empty($data['child_ref_id'])):
            $queryData['where']['stock_transaction.child_ref_id'] = $data['child_ref_id'];
        endif;

        if(!empty($data['ref_no'])):
            $queryData['where']['stock_transaction.ref_no'] = $data['ref_no'];
        endif;

        if(!empty($data['remark'])):
            $queryData['where']['stock_transaction.remark'] = $data['remark'];
        endif;
        
        if(!empty($data['customWhere'])):
            $queryData['customWhere'][] = $data['customWhere'];
        endif;
        
        if(!empty($data['stock_required'])):
            if(!empty($data['customHaving'])):
                $queryData['having'][] = $data['customHaving'];
            else:
                $queryData['having'][] = 'SUM(stock_transaction.qty * stock_transaction.p_or_m) > 0';
            endif;
        endif;

        if(!empty($data['group_by'])):
            $queryData['group_by'][] = $data['group_by'];
        else:
            $queryData['group_by'][] = "stock_transaction.item_id,stock_transaction.location_id,stock_transaction.batch_no";
        endif;
    
      
        $queryData['order_by']['lm.location'] = "ASC";

        if(isset($data['single_row']) && $data['single_row'] == 1):
            $stockData = $this->row($queryData);
        else:
            $stockData = $this->rows($queryData);
        endif;
        return $stockData;
    }

    public function save($data){
        try {
			$this->db->trans_begin();

            if(empty($data['id'])):
                $data['trans_prefix'] = "MI/".$this->shortYear."/";
                $data['trans_no'] = $this->getNextNo();
            else:
                $this->remove($this->stockTrans,['main_ref_id'=>$data['id'],'entry_type'=>$this->data['entry_type']]);
            endif;

            $batchDetail = $data['batchDetail'];
            $data['batch_detail'] = json_encode($data['batchDetail']); unset($data['batchDetail']);
            $result = $this->store($this->materialIssue,$data,'Material Issue');

            foreach($batchDetail as $row):
                $row['id'] = "";
                $row['ref_no'] = $data['trans_prefix'].$data['trans_no'];
                $row['ref_date'] = $data['trans_date'];
                $row['item_id'] = $data['item_id'];
                $row['main_ref_id'] = $result['id'];
                $row['entry_type'] = $this->data['entry_type'];
                $row['p_or_m'] = -1;
                $row['qty'] = $row['batch_qty'];

                unset($row['batch_qty'],$row['batch_stock'],$row['location_name']);

                $this->store($this->stockTrans,$row);
            endforeach;

            if ($this->db->trans_status() !== FALSE) :
				$this->db->trans_commit();
				return $result;
			endif;
		} catch (\Exception $e) {
			$this->db->trans_rollback();
			return ['status' => 2, 'message' => "somthing is wrong. Error : " . $e->getMessage()];
		}
    }

    public function getMaterialIssueDetail($data){
        $queryData = [];
        $queryData['tableName'] = $this->materialIssue;
        $queryData['select'] = "material_issue.*, item_master.item_code, item_master.item_name";
        $queryData['leftJoin']['item_master'] = "item_master.id = material_issue.item_id";
        $queryData['where']['material_issue.id'] = $data['id'];
        $result = $this->row($queryData);
        return $result;
    }

    public function delete($id){
        try{
            $this->db->trans_begin();

            $this->remove($this->stockTrans,['main_ref_id'=>$id,'entry_type'=>$this->data['entry_type']]);
            $result = $this->trash($this->materialIssue,['id'=>$id],'Material Issue');        

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }

    public function saveReturnMaterial($data){
        try {
			$this->db->trans_begin();

            $this->remove($this->stockTrans,['main_ref_id'=>$data['id'],'entry_type'=>$this->data['entry_type'],'p_or_m'=>1]);

            $batchDetail = $data['batchDetail'];
            $data['batch_detail'] = json_encode($data['batchDetail']); unset($data['batchDetail']);
            $result = $this->store($this->materialIssue,$data,'Material Return');

            foreach($batchDetail as $row):
                if($row['return_qty'] > 0):
                    $row['id'] = "";
                    $row['ref_no'] = $data['trans_prefix'].$data['trans_no'];
                    $row['ref_date'] = $data['trans_date'];
                    $row['item_id'] = $data['item_id'];
                    $row['main_ref_id'] = $result['id'];
                    $row['entry_type'] = $this->data['entry_type'];
                    $row['p_or_m'] = 1;
                    $row['qty'] = $row['return_qty'];

                    unset($row['batch_qty'],$row['return_qty'],$row['batch_stock'],$row['location_name']);

                    $this->store($this->stockTrans,$row);
                endif;
            endforeach;

            if ($this->db->trans_status() !== FALSE) :
				$this->db->trans_commit();
				return $result;
			endif;
		} catch (\Exception $e) {
			$this->db->trans_rollback();
			return ['status' => 2, 'message' => "somthing is wrong. Error : " . $e->getMessage()];
		}
    }
}
?>