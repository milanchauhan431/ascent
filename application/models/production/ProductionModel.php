<?php
class ProductionModel extends MasterModel{
    private $productionMaster = "production_master";
    private $productionTrans = "production_transaction";
    private $transMain = "trans_main";
    private $transChild = "trans_child";
    private $orderBom = "order_bom";
    private $purchseReq = "purchase_request";
    private $paramMaster = "parameter_master";

    /* Parameters Start */
    public function getParametersDTRows($data){
        $data['tableName'] = $this->paramMaster;
        $data['select'] = "parameter_master.*,(CASE WHEN param_type = 1 THEN 'Production' WHEN param_type = 2 THEN 'Testing' ELSE '' END) as param_type_text,(CASE WHEN input_type = 1 THEN 'Number' WHEN input_type = 2 THEN 'Decimal' WHEN input_type = 3 THEN 'Text' ELSE '' END) as input_type_text";

        if(!isset($data['order'])):
            $data['order_by']['seq'] = "ASC";
        endif;

        $data['searchCol'][] = "";
		$data['searchCol'][] = "";
		$data['searchCol'][] = "(CASE WHEN param_type = 1 THEN 'Production' WHEN param_type = 2 THEN 'Testing' ELSE '' END)";
		$data['searchCol'][] = "param_name";
        $data['searchCol'][] = "seq";
        $data['searchCol'][] = "(CASE WHEN input_type = 1 THEN 'Number' WHEN input_type = 2 THEN 'Decimal' WHEN input_type = 3 THEN 'Text' ELSE '' END)";
        $data['searchCol'][] = "remark";

		$columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;

		if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
        return $this->pagingRows($data);
    }

    public function saveParameter($data){
        try{
            $this->db->trans_begin();

            if($this->checkParameterDuplicate($data) > 0):
                $errorMessage['param_name'] = "Parameter Name is duplicate.";
                return ['status'=>0,'message'=>$errorMessage];
            endif;

            $result = $this->store($this->paramMaster,$data,'Parameter');

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }

    public function checkParameterDuplicate($data){
        $queryData['tableName'] = $this->paramMaster;
        $queryData['where']['param_name'] = $data['param_name'];
        $queryData['where']['param_type'] = $data['param_type'];
        
        if(!empty($data['id']))
            $queryData['where']['id !='] = $data['id'];
        
        $queryData['resultType'] = "numRows";
        return $this->specificRow($queryData);
    }

    public function getParameter($data){
        if(!empty($data['id'])):
            $queryData['where']['id'] = $data['id'];
        endif;
        if(!empty($data['param_name'])):
            $queryData['where']['param_name'] = $data['param_name'];
        endif;
        $queryData['tableName'] = $this->paramMaster;
        return $this->row($queryData);
    }

    public function getParameterList($data=array()){
        $queryData['tableName'] = $this->paramMaster;

        if($data['param_type']):
            $queryData['param_type'] = $data['param_type'];
        endif;

        $queryData['order_by']['seq'] = "ASC";
        return $this->rows($queryData);
    }

    public function deleteParameter($id){
        try{
            $this->db->trans_begin();

            $checkData['columnName'] = [];
            $checkData['value'] = $id;
            $checkUsed = $this->checkUsage($checkData);

            if($checkUsed == true):
                return ['status'=>0,'message'=>'The Parameter is currently in use. you cannot delete it.'];
            endif;

            $result = $this->trash($this->paramMaster,['id'=>$id],'Parameter');

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }
    /* Parameters End */

    /* Estomation & Design Start */
    public function getEstimationDTRows($data){
        $data['tableName'] = $this->transChild;
        $data['select'] = "trans_child.id as trans_child_id,trans_child.trans_main_id,trans_child.item_name,trans_child.qty,trans_child.job_number,trans_main.trans_number,DATE_FORMAT(trans_main.trans_date,'%d-%m-%Y') as trans_date,trans_main.party_name,(CASE WHEN obc.order_bom_count > 0 THEN 'Generated' ELSE 'Pending' END) as bom_status,production_master.fab_dept_note,production_master.pc_dept_note,production_master.ass_dept_note,(CASE WHEN production_master.priority = 1 THEN 'HIGH' WHEN production_master.priority = 2 THEN 'MEDIUM' WHEN production_master.priority = 3 THEN 'LOW' ELSE '' END) as priority_status,production_master.priority,production_master.remark,production_master.id,production_master.job_status";

        $data['leftJoin']['trans_main'] = "trans_main.id = trans_child.trans_main_id";
        $data['leftJoin']['(SELECT `trans_child_id`, COUNT(`id`) as `order_bom_count` FROM `order_bom` WHERE is_delete = 0 GROUP BY `trans_child_id`) as obc'] = "obc.trans_child_id = trans_child.id";
        $data['leftJoin']['production_master'] = "production_master.trans_child_id = trans_child.id";

        $data['where']['trans_child.entry_type'] = $data['entry_type'];

        if($data['job_status'] == -1):
            $data['where']['production_master.id'] = null;
        elseif($data['job_status'] >= 0):
            $data['where']['production_master.id >'] = 0;
            $data['where']['production_master.ref_id'] = 0;
            $data['where']['production_master.job_status'] = $data['job_status'];
        endif;
        
        $data['where']['trans_main.trans_date >='] = $this->startYearDate;
        $data['where']['trans_main.trans_date <='] = $this->endYearDate;

        $data['order_by']['trans_main.trans_date'] = "DESC";
        $data['order_by']['trans_main.id'] = "DESC";

        $data['group_by'][] = "trans_child.id";

        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "trans_child.job_number";
        $data['searchCol'][] = "DATE_FORMAT(trans_main.trans_date,'%d-%m-%Y')";
        $data['searchCol'][] = "trans_main.party_name";
        $data['searchCol'][] = "trans_child.item_name";
        $data['searchCol'][] = "trans_child.qty";
        $data['searchCol'][] = "(CASE WHEN obc.order_bom_count > 0 THEN 'Generated' ELSE 'Pending' END)";
        $data['searchCol'][] = "(CASE WHEN production_master.priority = 1 THEN 'HIGH' WHEN production_master.priority = 2 THEN 'MEDIUM' WHEN production_master.priority = 3 THEN 'LOW' END)";
        $data['searchCol'][] = "production_master.fab_dept_note";
        $data['searchCol'][] = "production_master.pc_dept_note";
        $data['searchCol'][] = "production_master.ass_dept_note";
        $data['searchCol'][] = "production_master.remark";

        $columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;
        if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
        
        return $this->pagingRows($data); //$this->printQuery();
    }

    public function saveOrderBom($postData){
        try{
            $this->db->trans_begin();
            
            foreach($postData as $row):
                $row['id'] = "";
                $this->store($this->orderBom,$row);
            endforeach;

            $result = ['status'=>1,'message'=>'Order Bom saved successfully.'];

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }

    public function getOrderBomItems($data){
        $queryData['tableName'] = $this->orderBom;
        $queryData['select'] = "order_bom.*,item_master.item_name,(trans_child.qty * order_bom.qty) as reqired_qty,ifnull(SUM(stock_transaction.qty * stock_transaction.p_or_m),0) as stock_qty,trans_child.trans_status";

        $queryData['leftJoin']['trans_child'] = "order_bom.trans_child_id = trans_child.id";
        $queryData['leftJoin']['item_master'] = "order_bom.item_id = item_master.id";
        $queryData['leftJoin']['stock_transaction'] = "order_bom.item_id = stock_transaction.item_id";

        $queryData['where']['order_bom.trans_child_id'] = $data['trans_child_id'];

        $queryData['group_by'][] = "order_bom.id";
        return $this->rows($queryData);
    }

    public function removeBomItem($id){
        try{
            $this->db->trans_begin();

            $result = $this->trash($this->orderBom,['id'=>$id],'Bom Item');

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }

    public function savePurchaseRequest($data){
        try{
            $this->db->trans_begin();
            
            foreach($data['itemData'] as $row):
                if(!empty($row['req_qty'])):
                    $row['req_no'] = $this->purchaseIndent->nextIndentNo();
                    $row['id'] = "";
                    $row['req_date'] = $data['req_date'];
                    $row['order_status'] = 1;
                    $row['approved_by'] = $this->loginId;
                    $row['approved_at'] = date("Y-m-d H:i:s");
                    $this->store($this->purchseReq,$row);

                    $setData = Array();
                    $setData['tableName'] = $this->orderBom;
                    $setData['where']['id'] = $row['bom_id'];
                    $setData['set']['req_qty'] = 'req_qty, + '.$row['req_qty'];
                    $this->setValue($setData);
                endif;
            endforeach;

            $result = ['status'=>1,'message'=>'Request sent successfully.'];

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }

    public function getProductionMaster($data=array()){
        $queryData['tableName'] = $this->productionMaster;
        $queryData['select'] = "production_master.*";
        $queryData['where']['production_master.id'] = $data['id'];
        return $this->row($queryData);
    }

    public function saveEstimation($data){
        try{
            $this->db->trans_begin();

            $result = $this->store($this->productionMaster,$data,"Estimation");

            if(!empty($data['id'])):
                if(!empty($data['ga_file'])):
                    $jobData['ga_file'] = $data['ga_file'];
                endif;
                if(!empty($data['technical_specification_file'])):
                    $jobData['technical_specification_file'] = $data['technical_specification_file'];
                endif;
                if(!empty($data['sld_file'])):
                    $jobData['sld_file'] = $data['sld_file'];
                endif;
                $jobData['fab_dept_note'] = $data['fab_dept_note'];
                $jobData['pc_dept_note'] = $data['pc_dept_note'];
                $jobData['ass_dept_note'] = $data['ass_dept_note'];
                $jobData['priority'] = $data['priority'];
                $jobData['remark'] = $data['remark'];

                $this->edit($this->productionMaster,['pm_id'=>$data['id']],$jobData);
            endif;

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        } 
    }
    /* Estomation & Design End */

    /* Fabrication [Mechanical Design] Start */
    public function getFabricationDTRows($data){

        $data['tableName'] = $this->productionMaster;
        $data['select'] = "production_master.id,production_master.entry_type,production_master.ref_id,production_master.pm_id,production_master.trans_child_id,production_master.trans_main_id,trans_child.job_number,trans_child.item_name,(CASE WHEN production_master.priority = 1 THEN 'HIGH' WHEN production_master.priority = 2 THEN 'MEDIUM' WHEN production_master.priority = 3 THEN 'LOW' ELSE '' END) as priority_status,production_master.ga_file,production_master.priority,production_master.fab_dept_note,production_master.remark,production_master.accepted_by,em.emp_name as accepted_by_name,production_master.accepted_at,production_master.job_status, (CASE WHEN production_master.ref_id = 0 THEN SUBSTRING_INDEX(production_master.department_ids, REPLACE(', ', ' ', ''), 1) ELSE SUBSTRING_INDEX(SUBSTRING_INDEX(production_master.department_ids, ',', FIND_IN_SET('".$data['from_entry_type']."', production_master.department_ids) + 1), REPLACE(', ', ' ', ''), -1) END) as next_dept_id";

        $data['leftJoin']['trans_child'] = "production_master.trans_child_id = trans_child.id";
        $data['leftJoin']['employee_master as em'] = "em.id = production_master.accepted_by";

        if($data['from_entry_type'] != $data['to_entry_type'] && $data['job_status'] == 0):
            $data['where']["SUBSTRING_INDEX(production_master.department_ids,',', 1) = "] = $data['to_entry_type'];
        elseif($data['from_entry_type'] != $data['to_entry_type'] && $data['job_status'] == 1):
            $data['where']["SUBSTRING_INDEX(SUBSTRING_INDEX(production_master.department_ids, ',', FIND_IN_SET(".$data['from_entry_type'].", production_master.department_ids) + 1),',', -1) = "] = $data['to_entry_type'];
        else:
            $data['where']['production_master.entry_type'] = $data['to_entry_type'];
        endif;

        $data['where']['production_master.job_status'] = $data['job_status'];

        if(in_array($data['job_status'],[0,1])):
            $data['where']['production_master.entry_date <='] = $this->endYearDate;
        else:
            $data['where']['production_master.entry_date >='] = $this->startYearDate;
            $data['where']['production_master.entry_date <='] = $this->endYearDate;
        endif;

        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "trans_child.job_number";
        $data['searchCol'][] = "(CASE WHEN production_master.priority = 1 THEN 'HIGH' WHEN production_master.priority = 2 THEN 'MEDIUM' WHEN production_master.priority = 3 THEN 'LOW' END)";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "production_master.fab_dept_note";
        $data['searchCol'][] = "production_master.remark";
        //$data['searchCol'][] = "CONCAT(em.emp_name,' ',DATE_FORMAT(production_master.accepted_at,'%d-%m-%Y %h:%i:%s %A'))";

        $columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;
        if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
        
        return $this->pagingRows($data); $this->printQuery();
    }

    public function acceptJob($data){
        try{
            $this->db->trans_begin();

            $next_dept_id = $data['next_dept_id']; unset($data['next_dept_id']);
            $result = $this->store($this->productionMaster,$data);

            $jobData = $this->getProductionMaster(['id'=>$data['id']]);
            $jobData = (array) $jobData;

            $jobData['id'] = "";
            $jobData['entry_type'] = $next_dept_id;
            $jobData['ref_id'] = $data['id'];
            if(empty($jobData['pm_id'])):
                $jobData['pm_id'] = $data['id'];
            endif;
            $jobData['entry_date'] = date("Y-m-d");
            $jobData['accepted_by'] = $this->loginId;
            $jobData['accepted_at'] = date("Y-m-d H:i:s");
            $this->store($this->productionMaster,$jobData);

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }

    public function saveProductionTrans($data){
        try{
            $this->db->trans_begin();

            foreach($data['transData'] as $row):
                $row['entry_type'] = $data['entry_type'];
                $row['pm_id'] = $data['ref_id'];
                $row['entry_date'] = date("Y-m-d");

                if($row['param_key'] == "cutting_drawings"):
                    $row['param_value'] = $data['cutting_drawings'];
                endif;

                $result = $this->store($this->productionTrans,$row);
            endforeach;

            $result['message'] = "Job Completed Successfully.";

            //Current Job Status Update
            $setData = Array();
            $setData['tableName'] = $this->productionMaster;
            $setData['where']['id'] = $data['ref_id'];
            $setData['update']['job_status'] = "2";
            $this->setValue($setData);

            //Manage Main Job Record Status
            $setData = Array();
            $setData['tableName'] = $this->productionMaster;
            $setData['where']['id'] = $data['pm_id'];
            $setData['update']['job_status'] = "(CASE WHEN REVERSE(SUBSTRING_INDEX(REVERSE(department_ids), ',', 1)) = ".$data['entry_type']." THEN 2 ELSE job_status END)";
            $this->setValue($setData);

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }
    /* Fabrication [Mechanical Design] End */
}
?>