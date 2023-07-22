<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

function getProductionDtHeader($page){
    /* Parameter Master */
    $data['parameters'][] = ["name"=>"Action","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"];
	$data['parameters'][] = ["name"=>"#","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"]; 
	$data['parameters'][] = ["name"=>"Type"];
	$data['parameters'][] = ["name"=>"Name"];
	$data['parameters'][] = ["name"=>"Seq."];
	$data['parameters'][] = ["name"=>"Input Type"];
	$data['parameters'][] = ["name"=>"Remark"];

    /* Estimation & Design Header */
    $data['estimation'][] = ["name"=>"Action","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"];
	$data['estimation'][] = ["name"=>"#","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"]; 
	$data['estimation'][] = ["name"=>"Job No."];
	$data['estimation'][] = ["name"=>"Job Date"];
	$data['estimation'][] = ["name"=>"Customer Name"];
	$data['estimation'][] = ["name"=>"Item Name"];
    $data['estimation'][] = ["name"=>"Order Qty"];
    $data['estimation'][] = ["name"=>"Bom Status"];
    $data['estimation'][] = ["name"=>"Priority"];
    $data['estimation'][] = ["name"=>"FAB. PRODUCTION NOTE"];
    $data['estimation'][] = ["name"=>"POWER COATING NOTE"];
    $data['estimation'][] = ["name"=>"ASSEMBALY NOTE"];
    $data['estimation'][] = ["name"=>"GENERAL NOTE"];

    /* Mechanical Design Header */
    $data['mechanical_design'][] = ["name"=>"Action","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"];
	$data['mechanical_design'][] = ["name"=>"#","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"]; 
	$data['mechanical_design'][] = ["name"=>"Job No.","style"=>"width:12%;",];
    $data['mechanical_design'][] = ["name"=>"Priority","style"=>"width:5%;","textAlign"=>"center"];
    $data['mechanical_design'][] = ["name"=>"GA","sortable"=>"FALSE","textAlign"=>"center"];
    $data['mechanical_design'][] = ["name"=>"Bom","sortable"=>"FALSE","textAlign"=>"center"];
    $data['mechanical_design'][] = ["name"=>"FAB. PRODUCTION NOTE"];
    $data['mechanical_design'][] = ["name"=>"GENERAL NOTE"];
    $data['mechanical_design'][] = ["name"=>"Accepted BY","sortable"=>"FALSE","textAlign"=>"center"];

    return tableHeader($data[$page]);
}

/* Parameter Master Table Data */
function getParametersData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Parameter'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'modal-md', 'form_id' : 'editParameter', 'title' : 'Update Parameter'}";

    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="ti-pencil-alt" ></i></a>';

    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';
	
	$action = getActionButton($editButton.$deleteButton);
    return [$action,$data->sr_no,$data->param_type_text,$data->param_name,$data->seq,$data->input_type_text,$data->remark];
}


/* Estimation & Desing Table Data */
function getEstimationData($data){    

    $soBomParam = "{'postData':{'trans_main_id' : ".$data->trans_main_id.",'trans_child_id':".$data->trans_child_id."},'modal_id' : 'modal-xxl', 'form_id' : 'addOrderBom', 'fnedit':'orderBom', 'fnsave':'saveOrderBom','title' : 'Order Bom','res_function':'resSaveOrderBom','js_store_fn':'customStore'}";
    $soBom = '<a class="btn btn-info btn-delete permission-write" href="javascript:void(0)" onclick="edit('.$soBomParam.');" datatip="SO Bom" flow="down"><i class="fa fa-database"></i></a>';

    $viewBomParam = "{'postData':{'trans_child_id':".$data->trans_child_id."},'modal_id' : 'modal-xl','fnedit':'viewOrderBom','title' : 'View Bom [Item Name : ".$data->item_name."]','button':'close'}";
    $viewBom = '<a class="btn btn-primary permission-read" href="javascript:void(0)" onclick="edit('.$viewBomParam.');" datatip="View Item Bom" flow="down"><i class="fa fa-eye"></i></a>';

    $reqParam = "{'postData':{'trans_child_id':".$data->trans_child_id.",'trans_number':'".$data->trans_number."','item_name':'".$data->item_name."'},'modal_id' : 'modal-xl', 'form_id' : 'addOrderBom', 'fnedit':'purchaseRequest', 'fnsave':'savePurchaseRequest','title' : 'Send Purchase Request'}";
    $reqButton = '<a class="btn btn-info btn-delete permission-write" href="javascript:void(0)" onclick="edit('.$reqParam.');" datatip="Purchase Request" flow="down"><i class="fa fa-paper-plane"></i></a>';

    $estimationParam = "{'postData':{'id':'".$data->id."','trans_child_id':".$data->trans_child_id.",'trans_main_id':'".$data->trans_main_id."'},'modal_id' : 'modal-xl', 'form_id' : 'estimation', 'fnedit':'addEstimation', 'fnsave':'saveEstimation','title' : 'Estimation & Design'}";
    $estimationButton = '<a class="btn btn-success permission-write" href="javascript:void(0)" onclick="edit('.$estimationParam.');" datatip="Estimation" flow="down"><i class="fa fa-plus"></i></a>';

    if($data->priority == 1):
        $data->priority_status = '<span class="badge badge-pill badge-danger m-1">'.$data->priority_status.'</span>';
    elseif($data->priority == 2):
        $data->priority_status = '<span class="badge badge-pill badge-warning m-1">'.$data->priority_status.'</span>';
    elseif($data->priority == 3):
        $data->priority_status = '<span class="badge badge-pill badge-info m-1">'.$data->priority_status.'</span>';
    endif;

    $data->bom_status = '<span class="badge badge-pill badge-'.(($data->bom_status == "Generated")?"success":"danger").' m-1">'.$data->bom_status.'</span>';

    if(!empty($data->job_status)):
        $soBom = $estimationButton = '';
    endif;

    $action = getActionButton($soBom.$viewBom.$reqButton.$estimationButton);

    return [$action,$data->sr_no,$data->job_number,$data->trans_date,$data->party_name,$data->item_name,$data->qty,$data->bom_status,$data->priority_status,$data->fab_dept_note,$data->pc_dept_note,$data->ass_dept_note,$data->remark];
}

/* Fabrication Table Data */
function getFabricationData($data){
    if(in_array($data->entry_type,[27,30])): // Mechanical Design Table Data
        if($data->priority == 1):
            $data->priority_status = '<span class="badge badge-pill badge-danger m-1">'.$data->priority_status.'</span>';
        elseif($data->priority == 2):
            $data->priority_status = '<span class="badge badge-pill badge-warning m-1">'.$data->priority_status.'</span>';
        elseif($data->priority == 3):
            $data->priority_status = '<span class="badge badge-pill badge-info m-1">'.$data->priority_status.'</span>';
        endif;

        $data->ga_file = (!empty($data->ga_file))?'<a href="'.base_url('assets/uploads/production/'.$data->ga_file).'" class="btn btn-outline-info waves-effect waves-light" target="_blank"><i class="fa fa-eye"></i></a>':'';

        $viewBomParam = "{'postData':{'trans_child_id':".$data->trans_child_id."},'modal_id' : 'modal-xl','fnedit':'viewOrderBom','title' : 'View Bom [Item Name : ".$data->item_name."]','button':'close','controller':'production/estimation'}";
        $viewBom = '<a class="btn btn-outline-info waves-effect waves-light" href="javascript:void(0)" onclick="edit('.$viewBomParam.');" datatip="View Item Bom" flow="down"><i class="fa fa-eye"></i></a>';

        $accepted_by = ($data->entry_type == 30 && $data->accepted_by > 0)?$data->accepted_by_name."<br>".formatDate($data->accepted_at,'d-m-Y h:i:s A'):"";

        $completJob = $accptJob = '';

        if($data->entry_type == $data->from_entry_type):
            $acceptParam = "{'postData':{job_status : 1, id : ".$data->id.",'next_dept_id': ".$data->next_dept_id."},'controllerName' : 'production/fabrication','fnsave':'acceptJob','message':'Are you sure want to accept this Job?'}";
            $accptJob = '<a class="btn btn-success" href="javascript:void(0)" datatip="Accept Job" flow="down" onclick="confirmStore('.$acceptParam.');"><i class="fa fa-check"></i></a>';
        endif;

        if($data->job_status == 1 && $data->entry_type == $data->to_entry_type):
            $accptJob = '';
            $completParam = "{'postData':{ref_id : ".$data->id.", 'pm_id' : ".$data->pm_id.",'entry_type': ".$data->entry_type."},'controller' : 'production/fabrication','fnedit':'mechanicalDesign','js_store_fn':'confirmStore','modal_id':'modal-md','form_id':'mechanicalDesign','title':'Mechanical Design'}";
            $completJob = '<a class="btn btn-success" href="javascript:void(0)" datatip="Complet Job" flow="down" onclick="edit('.$completParam.');"><i class="fa fa-check"></i></a>';
        elseif($data->job_status == 2 && $data->entry_type == $data->to_entry_type):
            $completJob = $accptJob = '';
        endif;

        $action = getActionButton($accptJob.$completJob);

        return [$action,$data->sr_no,$data->job_number,$data->priority_status,$data->ga_file,$viewBom,$data->fab_dept_note,$data->remark,$accepted_by];
    endif;
}
?>