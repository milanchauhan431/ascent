<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

function getProductionDtHeader($page){
    /* Parameter Master */
    $data['parameters'][] = ["name"=>"Action","sortable"=>"FALSE","textAlign"=>"center"];
	$data['parameters'][] = ["name"=>"#","sortable"=>"FALSE","textAlign"=>"center"]; 
	$data['parameters'][] = ["name"=>"Type"];
	$data['parameters'][] = ["name"=>"Name"];
	$data['parameters'][] = ["name"=>"Seq."];
	$data['parameters'][] = ["name"=>"Input Type"];
	$data['parameters'][] = ["name"=>"Is Required"];
	$data['parameters'][] = ["name"=>"Remark"];

    /* Estimation & Design Header */
    $data['estimation'][] = ["name"=>"Action","sortable"=>"FALSE","textAlign"=>"center"];
	$data['estimation'][] = ["name"=>"#","sortable"=>"FALSE","textAlign"=>"center"]; 
	$data['estimation'][] = ["name"=>"Job No."];
	$data['estimation'][] = ["name"=>"Job Date"];
	$data['estimation'][] = ["name"=>"Customer Name"];
	$data['estimation'][] = ["name"=>"Item Name"];
    $data['estimation'][] = ["name"=>"Order Qty"];
    $data['estimation'][] = ["name"=>"Bom Status","textAlign"=>"center"];
    $data['estimation'][] = ["name"=>"Priority","textAlign"=>"center"];
    $data['estimation'][] = ["name"=>"FAB. PRODUCTION NOTE"];
    $data['estimation'][] = ["name"=>"POWER COATING NOTE"];
    $data['estimation'][] = ["name"=>"ASSEMBLY NOTE"];
    $data['estimation'][] = ["name"=>"GENERAL NOTE"];

    /* Mechanical Design Header */
    $data['mechanical_design'][] = ["name"=>"Action","sortable"=>"FALSE","textAlign"=>"center"];
	$data['mechanical_design'][] = ["name"=>"#","sortable"=>"FALSE","textAlign"=>"center"]; 
	$data['mechanical_design'][] = ["name"=>"Job No."];
    $data['mechanical_design'][] = ["name"=>"Item Name"];
    $data['mechanical_design'][] = ["name"=>"Order Qty"];
    $data['mechanical_design'][] = ["name"=>"Priority","textAlign"=>"center"];
    $data['mechanical_design'][] = ["name"=>"GA","sortable"=>"FALSE","textAlign"=>"center"];
    $data['mechanical_design'][] = ["name"=>"T.S.","sortable"=>"FALSE","textAlign"=>"center"];
    $data['mechanical_design'][] = ["name"=>"SLD","sortable"=>"FALSE","textAlign"=>"center"];
    $data['mechanical_design'][] = ["name"=>"Bom","sortable"=>"FALSE","textAlign"=>"center"];
    $data['mechanical_design'][] = ["name"=>"FAB. PRODUCTION NOTE"];
    $data['mechanical_design'][] = ["name"=>"GENERAL NOTE"];
    //$data['mechanical_design'][] = ["name"=>"Accepted BY","sortable"=>"FALSE","textAlign"=>"center"];

    /* Cutting Header */
    $data['cutting'][] = ["name"=>"Action","sortable"=>"FALSE","textAlign"=>"center"];
	$data['cutting'][] = ["name"=>"#","sortable"=>"FALSE","textAlign"=>"center"]; 
	$data['cutting'][] = ["name"=>"Job No."];
    $data['cutting'][] = ["name"=>"Item Name"];
    $data['cutting'][] = ["name"=>"Order Qty"];
    $data['cutting'][] = ["name"=>"Priority","textAlign"=>"center"];
    $data['cutting'][] = ["name"=>"GA","sortable"=>"FALSE","textAlign"=>"center"];
    $data['cutting'][] = ["name"=>"T.S.","sortable"=>"FALSE","textAlign"=>"center"];
    $data['cutting'][] = ["name"=>"SLD","sortable"=>"FALSE","textAlign"=>"center"];
    $data['cutting'][] = ["name"=>"Bom","sortable"=>"FALSE","textAlign"=>"center"];
    $data['cutting'][] = ["name"=>"FAB. PRODUCTION NOTE"];
    $data['cutting'][] = ["name"=>"GENERAL NOTE"];

    /* Bending Header */
    $data['bending'][] = ["name"=>"Action","sortable"=>"FALSE","textAlign"=>"center"];
	$data['bending'][] = ["name"=>"#","sortable"=>"FALSE","textAlign"=>"center"]; 
	$data['bending'][] = ["name"=>"Job No."];
    $data['bending'][] = ["name"=>"Item Name"];
    $data['bending'][] = ["name"=>"Order Qty"];
    $data['bending'][] = ["name"=>"Priority","textAlign"=>"center"];
    $data['bending'][] = ["name"=>"GA","sortable"=>"FALSE","textAlign"=>"center"];
    $data['bending'][] = ["name"=>"T.S.","sortable"=>"FALSE","textAlign"=>"center"];
    $data['bending'][] = ["name"=>"SLD","sortable"=>"FALSE","textAlign"=>"center"];
    $data['bending'][] = ["name"=>"Bom","sortable"=>"FALSE","textAlign"=>"center"];
    $data['bending'][] = ["name"=>"FAB. PRODUCTION NOTE"];
    $data['bending'][] = ["name"=>"GENERAL NOTE"];

    /* Fab. Assembely Header */
    $data['fab_assembely'][] = ["name"=>"Action","sortable"=>"FALSE","textAlign"=>"center"];
	$data['fab_assembely'][] = ["name"=>"#","sortable"=>"FALSE","textAlign"=>"center"]; 
	$data['fab_assembely'][] = ["name"=>"Job No."];
    $data['fab_assembely'][] = ["name"=>"Item Name"];
    $data['fab_assembely'][] = ["name"=>"Order Qty"];
    $data['fab_assembely'][] = ["name"=>"Priority","textAlign"=>"center"];
    $data['fab_assembely'][] = ["name"=>"GA","sortable"=>"FALSE","textAlign"=>"center"];
    $data['fab_assembely'][] = ["name"=>"T.S.","sortable"=>"FALSE","textAlign"=>"center"];
    $data['fab_assembely'][] = ["name"=>"SLD","sortable"=>"FALSE","textAlign"=>"center"];
    $data['fab_assembely'][] = ["name"=>"Bom","sortable"=>"FALSE","textAlign"=>"center"];
    $data['fab_assembely'][] = ["name"=>"FAB. PRODUCTION NOTE"];
    $data['fab_assembely'][] = ["name"=>"GENERAL NOTE"];

    /* Powder Coating Header */
    $data['powder_coating'][] = ["name"=>"Action","sortable"=>"FALSE","textAlign"=>"center"];
	$data['powder_coating'][] = ["name"=>"#","sortable"=>"FALSE","textAlign"=>"center"]; 
	$data['powder_coating'][] = ["name"=>"Job No."];
    $data['powder_coating'][] = ["name"=>"Item Name"];
    $data['powder_coating'][] = ["name"=>"Order Qty"];
    $data['powder_coating'][] = ["name"=>"Priority","textAlign"=>"center"];
    $data['powder_coating'][] = ["name"=>"GA","sortable"=>"FALSE","textAlign"=>"center"];
    $data['powder_coating'][] = ["name"=>"T.S.","sortable"=>"FALSE","textAlign"=>"center"];
    $data['powder_coating'][] = ["name"=>"SLD","sortable"=>"FALSE","textAlign"=>"center"];
    $data['powder_coating'][] = ["name"=>"Bom","sortable"=>"FALSE","textAlign"=>"center"];
    $data['powder_coating'][] = ["name"=>"POWDER COATING NOTE"];
    $data['powder_coating'][] = ["name"=>"GENERAL NOTE"];

    /* Electrical Design Header */
    $data['electrical_design'][] = ["name"=>"Action","sortable"=>"FALSE","textAlign"=>"center"];
	$data['electrical_design'][] = ["name"=>"#","sortable"=>"FALSE","textAlign"=>"center"]; 
	$data['electrical_design'][] = ["name"=>"Job No."];
    $data['electrical_design'][] = ["name"=>"Item Name"];
    $data['electrical_design'][] = ["name"=>"Order Qty"];
    $data['electrical_design'][] = ["name"=>"Priority","textAlign"=>"center"];
    $data['electrical_design'][] = ["name"=>"GA","sortable"=>"FALSE","textAlign"=>"center"];
    $data['electrical_design'][] = ["name"=>"T.S.","sortable"=>"FALSE","textAlign"=>"center"];
    $data['electrical_design'][] = ["name"=>"SLD","sortable"=>"FALSE","textAlign"=>"center"];
    $data['electrical_design'][] = ["name"=>"Bom","sortable"=>"FALSE","textAlign"=>"center"];
    $data['electrical_design'][] = ["name"=>"FAB. PRODUCTION NOTE"];
    $data['electrical_design'][] = ["name"=>"POWDER COATING NOTE"];
    $data['electrical_design'][] = ["name"=>"GENERAL NOTE"];

    /* Pending Assembly Allotment Header */
    $data['vendor_allotment'][] = ["name"=>"Action","sortable"=>"FALSE","textAlign"=>"center"];
	$data['vendor_allotment'][] = ["name"=>"#","sortable"=>"FALSE","textAlign"=>"center"]; 
	$data['vendor_allotment'][] = ["name"=>"Job No."];
    $data['vendor_allotment'][] = ["name"=>"Item Name"];
    $data['vendor_allotment'][] = ["name"=>"Order Qty"];
    $data['vendor_allotment'][] = ["name"=>"Alloted Qty"];
    $data['vendor_allotment'][] = ["name"=>"Pending Qty"];
    $data['vendor_allotment'][] = ["name"=>"Priority","textAlign"=>"center"];
    $data['vendor_allotment'][] = ["name"=>"GA","sortable"=>"FALSE","textAlign"=>"center"];
    $data['vendor_allotment'][] = ["name"=>"T.S.","sortable"=>"FALSE","textAlign"=>"center"];
    $data['vendor_allotment'][] = ["name"=>"SLD","sortable"=>"FALSE","textAlign"=>"center"];
    $data['vendor_allotment'][] = ["name"=>"Bom","sortable"=>"FALSE","textAlign"=>"center"];
    $data['vendor_allotment'][] = ["name"=>"ASSEMBLY NOTE"];
    $data['vendor_allotment'][] = ["name"=>"GENERAL NOTE"];

    /* Alloted Assembly Header */
    $data['assembly_allotment'][] = ["name"=>"Action","sortable"=>"FALSE","textAlign"=>"center"];
	$data['assembly_allotment'][] = ["name"=>"#","sortable"=>"FALSE","textAlign"=>"center"]; 
	$data['assembly_allotment'][] = ["name"=>"Job No."];
    $data['assembly_allotment'][] = ["name"=>"Item Name"];
    $data['assembly_allotment'][] = ["name"=>"Vendor Name"];
    $data['assembly_allotment'][] = ["name"=>"Panel Qty."];
    $data['assembly_allotment'][] = ["name"=>"Priority","textAlign"=>"center"];
    $data['assembly_allotment'][] = ["name"=>"GA","sortable"=>"FALSE","textAlign"=>"center"];
    $data['assembly_allotment'][] = ["name"=>"T.S.","sortable"=>"FALSE","textAlign"=>"center"];
    $data['assembly_allotment'][] = ["name"=>"SLD","sortable"=>"FALSE","textAlign"=>"center"];
    $data['assembly_allotment'][] = ["name"=>"Bom","sortable"=>"FALSE","textAlign"=>"center"];
    $data['assembly_allotment'][] = ["name"=>"ASSEMBLY NOTE"];
    $data['assembly_allotment'][] = ["name"=>"GENERAL NOTE"];

    /* Assembly Production Part-1 Header */
    $data['contactor_assembly'][] = ["name"=>"Action","sortable"=>"FALSE","textAlign"=>"center"];
	$data['contactor_assembly'][] = ["name"=>"#","sortable"=>"FALSE","textAlign"=>"center"]; 
	$data['contactor_assembly'][] = ["name"=>"Job No."];
    $data['contactor_assembly'][] = ["name"=>"Item Name"];
    $data['contactor_assembly'][] = ["name"=>"Vendor Name"];
    $data['contactor_assembly'][] = ["name"=>"Panel Qty."];
    $data['contactor_assembly'][] = ["name"=>"Priority","textAlign"=>"center"];
    $data['contactor_assembly'][] = ["name"=>"GA","sortable"=>"FALSE","textAlign"=>"center"];
    $data['contactor_assembly'][] = ["name"=>"T.S.","sortable"=>"FALSE","textAlign"=>"center"];
    $data['contactor_assembly'][] = ["name"=>"SLD","sortable"=>"FALSE","textAlign"=>"center"];
    $data['contactor_assembly'][] = ["name"=>"Bom","sortable"=>"FALSE","textAlign"=>"center"];
    $data['contactor_assembly'][] = ["name"=>"ASSEMBLY NOTE"];
    $data['contactor_assembly'][] = ["name"=>"GENERAL NOTE"];

    /* Quality Department Header */
    $data['quality'][] = ["name"=>"Action","sortable"=>"FALSE","textAlign"=>"center"];
	$data['quality'][] = ["name"=>"#","sortable"=>"FALSE","textAlign"=>"center"]; 
	$data['quality'][] = ["name"=>"Job No."];
    $data['quality'][] = ["name"=>"Item Name"];
    $data['quality'][] = ["name"=>"Order Qty."];
    $data['quality'][] = ["name"=>"Priority","textAlign"=>"center"];
    $data['quality'][] = ["name"=>"GA","sortable"=>"FALSE","textAlign"=>"center"];
    $data['quality'][] = ["name"=>"Bom","sortable"=>"FALSE","textAlign"=>"center"];
    $data['quality'][] = ["name"=>"FAB. PRODUCTION NOTE"];
    $data['quality'][] = ["name"=>"GENERAL NOTE"];

    /* Testing Parameters Header */
    $data['testingParameters'][] = ["name"=>"Action","sortable"=>"FALSE","textAlign"=>"center"];
	$data['testingParameters'][] = ["name"=>"#","sortable"=>"FALSE","textAlign"=>"center"]; 
	$data['testingParameters'][] = ["name"=>"System Detail"];
    $data['testingParameters'][] = ["name"=>"Control Supply"];
    $data['testingParameters'][] = ["name"=>"HV Test"];
    $data['testingParameters'][] = ["name"=>"Insulation Resistance"];

    /* Testing Department Header */
    $data['pending_testing'][] = ["name"=>"Action","sortable"=>"FALSE","textAlign"=>"center"];
	$data['pending_testing'][] = ["name"=>"#","sortable"=>"FALSE","textAlign"=>"center"]; 
	$data['pending_testing'][] = ["name"=>"Job No."];
    $data['pending_testing'][] = ["name"=>"Item Name"];
    $data['pending_testing'][] = ["name"=>"Order Qty."];
    $data['pending_testing'][] = ["name"=>"Tested Qty."];
    $data['pending_testing'][] = ["name"=>"Priority","textAlign"=>"center"];
    $data['pending_testing'][] = ["name"=>"GA","sortable"=>"FALSE","textAlign"=>"center"];
    $data['pending_testing'][] = ["name"=>"Bom","sortable"=>"FALSE","textAlign"=>"center"];
    $data['pending_testing'][] = ["name"=>"FAB. PRODUCTION NOTE"];
    $data['pending_testing'][] = ["name"=>"GENERAL NOTE"];

    $data['complete_testing'][] = ["name"=>"Action","sortable"=>"FALSE","textAlign"=>"center"];
	$data['complete_testing'][] = ["name"=>"#","sortable"=>"FALSE","textAlign"=>"center"]; 
	$data['complete_testing'][] = ["name"=>"Job No."];
	$data['complete_testing'][] = ["name"=>"Customer Name"];
    $data['complete_testing'][] = ["name"=>"Item Name"];
    $data['complete_testing'][] = ["name"=>"Order Qty."];
    $data['complete_testing'][] = ["name"=>"Tested Qty."];
	$data['complete_testing'][] = ["name"=>"TC Sr. No."];
	$data['complete_testing'][] = ["name"=>"Drgs Ref."];
	$data['complete_testing'][] = ["name"=>"Switchgear Sr. No."];
	$data['complete_testing'][] = ["name"=>"Tested By"];
    $data['complete_testing'][] = ["name"=>"Priority","textAlign"=>"center"];
    $data['complete_testing'][] = ["name"=>"GA","sortable"=>"FALSE","textAlign"=>"center"];
    $data['complete_testing'][] = ["name"=>"Bom","sortable"=>"FALSE","textAlign"=>"center"];
    $data['complete_testing'][] = ["name"=>"FAB. PRODUCTION NOTE"];
    $data['complete_testing'][] = ["name"=>"GENERAL NOTE"];

    return tableHeader($data[$page]);
}

/* Parameter Master Table Data */
function getParametersData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Parameter'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'modal-md', 'form_id' : 'editParameter', 'title' : 'Update Parameter'}";

    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="ti-pencil-alt" ></i></a>';

    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';
	
	$action = getActionButton($editButton.$deleteButton);
    return [$action,$data->sr_no,$data->param_type_text,$data->param_name,$data->seq,$data->input_type_text,$data->is_required_text,$data->remark];
}

/* Estimation & Desing Table Data */
function getEstimationData($data){    

    $soBomParam = "{'postData':{'trans_main_id' : ".$data->trans_main_id.",'trans_child_id':".$data->trans_child_id."},'modal_id' : 'modal-xxl', 'form_id' : 'addOrderBom', 'fnedit':'orderBom', 'fnsave':'saveOrderBom','title' : 'Order Bom','res_function':'resSaveOrderBom','js_store_fn':'customStore'}";
    $soBom = '<a class="btn btn-info btn-delete permission-write" href="javascript:void(0)" onclick="edit('.$soBomParam.');" datatip="SO Bom" flow="down"><i class="fa fa-database"></i></a>';

    $viewBomParam = "{'postData':{'trans_child_id':".$data->trans_child_id.",'trans_main_id':'".$data->trans_main_id."'},'modal_id' : 'modal-xl','fnedit':'viewOrderBom','title' : 'View Bom [Item Name : ".$data->item_name."]','button':'close'}";
    $viewBom = '<a class="btn btn-primary permission-read" href="javascript:void(0)" onclick="edit('.$viewBomParam.');" datatip="View Item Bom" flow="down"><i class="fa fa-eye"></i></a>';

    $reqParam = "{'postData':{'trans_child_id':".$data->trans_child_id.",'trans_number':'".$data->trans_number."','item_name':'".$data->item_name."'},'modal_id' : 'modal-xl', 'form_id' : 'addOrderBom', 'fnedit':'purchaseRequest', 'fnsave':'savePurchaseRequest','title' : 'Send Purchase Request'}";
    $reqButton = '<a class="btn btn-info permission-write" href="javascript:void(0)" onclick="edit('.$reqParam.');" datatip="Purchase Request" flow="down"><i class="fa fa-paper-plane"></i></a>';

    $miReqParam = "{'postData':{'trans_child_id':".$data->trans_child_id.",'trans_number':'".$data->trans_number."','item_name':'".$data->item_name."'},'modal_id' : 'modal-xl', 'form_id' : 'addMaterialRequest', 'fnedit':'materialIssueRequest', 'fnsave':'saveMaterialIssueRequest','title' : 'Send Request for Material Issue'}";
    $miReqButton = '<a class="btn btn-warning permission-write" href="javascript:void(0)" onclick="edit('.$miReqParam.');" datatip="Material Issue Request" flow="down"><i class="fa fa-paper-plane"></i></a>';

    $estimationParam = "{'postData':{'id':'".$data->id."','trans_child_id':".$data->trans_child_id.",'trans_main_id':'".$data->trans_main_id."'},'modal_id' : 'modal-xl', 'form_id' : 'estimation', 'fnedit':'addEstimation', 'fnsave':'saveEstimation','title' : 'Estimation & Design'}";
    $estimationButton = '<a class="btn btn-success permission-write" href="javascript:void(0)" onclick="edit('.$estimationParam.');" datatip="Estimation" flow="down"><i class="fa fa-plus"></i></a>';

    $startJob = "";
    if(!empty($data->id)):
        $startJobParam = "{'postData':{'job_status' : 1, 'id' : ".$data->id."},'fnsave':'startJob','message':'Are you sure want to start this Job?'}";
        $startJob = '<a class="btn btn-success" href="javascript:void(0)" datatip="Start Job" flow="down" onclick="confirmStore('.$startJobParam.');"><i class="fa fa-play"></i></a>';
    endif;

    if($data->priority == 1):
        $data->priority_status = '<span class="badge badge-pill badge-danger m-1">'.$data->priority_status.'</span>';
    elseif($data->priority == 2):
        $data->priority_status = '<span class="badge badge-pill badge-warning m-1">'.$data->priority_status.'</span>';
    elseif($data->priority == 3):
        $data->priority_status = '<span class="badge badge-pill badge-info m-1">'.$data->priority_status.'</span>';
    endif;

    $data->bom_status = '<span class="badge badge-pill badge-'.(($data->bom_status == "Generated")?"success":"danger").' m-1">'.$data->bom_status.'</span>';

    $changePriority = $prodDetailPrintBtn = '';
    if(!empty($data->job_status)):
        $startJob = $estimationButton = '';
        if($data->job_status == 1):
            $changePriorityParam = "{'postData':{'id' : ".$data->id."},'fnsave':'saveJobPriority','title':'Change Job Priority','form_id':'changeJobPriority','js_store_fn':'confirmStore','controller':'production/estimation','fnedit':'changeJobPriority','modal_id':'modal-md'}";
            $changePriority = '<a class="btn btn-success" href="javascript:void(0)" datatip="Change Job Priority" flow="down" onclick="edit('.$changePriorityParam.');"><i class="fa fa-sync"></i></a>';
        endif;

        if($data->job_status == 3): $soBom = ''; endif;

        $prodDetailPrintBtn = '<a class="btn btn-dark" href="'.base_url('production/estimation/printProductionDetails/'.$data->id).'" target="_blank" datatip="Print GA,T.S. & SLD Document" flow="down"><i class="fas fa-print" ></i></a>';
    endif;

    $action = getActionButton($soBom.$viewBom.$reqButton.$miReqButton.$prodDetailPrintBtn.$estimationButton.$changePriority.$startJob);

    return [$action,$data->sr_no,$data->job_number,$data->trans_date,$data->party_name,$data->item_name,$data->qty,$data->bom_status,$data->priority_status,$data->fab_dept_note,$data->pc_dept_note,$data->ass_dept_note,$data->remark];
}

/* Fabrication Table Data */
function getFabricationData($data){
    if($data->priority == 1):
        $data->priority_status = '<span class="badge badge-pill badge-danger m-1">'.$data->priority_status.'</span>';
    elseif($data->priority == 2):
        $data->priority_status = '<span class="badge badge-pill badge-warning m-1">'.$data->priority_status.'</span>';
    elseif($data->priority == 3):
        $data->priority_status = '<span class="badge badge-pill badge-info m-1">'.$data->priority_status.'</span>';
    endif;

    $data->ga_file = (!empty($data->ga_file))?'<a href="'.base_url('assets/uploads/production/'.$data->ga_file).'" class="btn btn-outline-info waves-effect waves-light" target="_blank"><i class="fa fa-eye"></i></a>':'';
    $data->technical_specification_file = (!empty($data->technical_specification_file))?'<a href="'.base_url('assets/uploads/production/'.$data->technical_specification_file).'" class="btn btn-outline-info waves-effect waves-light" target="_blank"><i class="fa fa-eye"></i></a>':'';
    $data->sld_file = (!empty($data->sld_file))?'<a href="'.base_url('assets/uploads/production/'.$data->sld_file).'" class="btn btn-outline-info waves-effect waves-light" target="_blank"><i class="fa fa-eye"></i></a>':'';

    $viewBomParam = "{'postData':{'trans_child_id':".$data->trans_child_id."},'modal_id' : 'modal-xl','fnedit':'viewProductionBom','title' : 'View Bom [Item Name : ".$data->item_name."]','button':'close','controller':'production/estimation'}";
    $viewBom = '<a class="btn btn-outline-info waves-effect waves-light" href="javascript:void(0)" onclick="edit('.$viewBomParam.');" datatip="View Item Bom" flow="down"><i class="fa fa-eye"></i></a>';

    if($data->to_entry_type == 30): // Mechanical Design Table Data
        //$accepted_by = ($data->entry_type == 30 && $data->accepted_by > 0)?$data->accepted_by_name."<br>".formatDate($data->accepted_at,'d-m-Y h:i:s A'):"";

        $viewComplete = $completJob = $accptJob = '';

        if($data->entry_type == $data->from_entry_type):
            $acceptParam = "{'postData':{'job_status' : ".(($data->from_entry_type == 27)?2:3).", 'id' : ".$data->id.",'next_dept_id': ".$data->next_dept_id."},'controllerName' : 'production/fabrication','fnsave':'acceptJob','message':'Are you sure want to accept this Job?'}";
            $accptJob = '<a class="btn btn-success" href="javascript:void(0)" datatip="Accept Job" flow="down" onclick="confirmStore('.$acceptParam.');"><i class="fa fa-check"></i></a>';
        endif;

        if($data->job_status == 1 && $data->entry_type == $data->to_entry_type):
            $accptJob = '';
            $completParam = "{'postData':{'ref_id' : ".$data->id.", 'pm_id' : ".$data->pm_id.",'entry_type': ".$data->entry_type."},'controller' : 'production/fabrication','fnedit':'mechanicalDesign','js_store_fn':'confirmStore','modal_id':'modal-md','form_id':'mechanicalDesign','title':'Mechanical Design [".$data->job_number."] <br> <small>Panel Name : ".$data->item_name."</small>'}";
            $completJob = '<a class="btn btn-success" href="javascript:void(0)" datatip="Complet Job" flow="down" onclick="edit('.$completParam.');"><i class="fa fa-check"></i></a>';
        elseif(in_array($data->job_status,[2,3]) && $data->entry_type == $data->to_entry_type):
            $completJob = $accptJob = '';

            $viewParam = "{'postData':{'ref_id' : ".$data->id.",'entry_type': ".$data->entry_type."},'controller' : 'production/fabrication','fnedit':'viewMechanicalDesign','js_store_fn':'confirmStore','modal_id':'modal-md','form_id':'viewMechanicalDesign','title':'Mechanical Design [".$data->job_number."] <br> <small>Panel Name : ".$data->item_name."</small>','button':'close'}";

            $viewComplete = '<a class="btn btn-info" href="javascript:void(0)" datatip="View Complet Job" flow="down" onclick="edit('.$viewParam.');"><i class="fa fa-eye"></i></a>';
        endif;

        $action = getActionButton($accptJob.$completJob.$viewComplete);

        return [$action,$data->sr_no,$data->job_number,$data->item_name,$data->order_qty,$data->priority_status,$data->ga_file,$data->technical_specification_file,$data->sld_file,$viewBom,$data->fab_dept_note,$data->remark/* ,$accepted_by */];
    endif;

    if($data->to_entry_type == 31): // Cutting Table Data

        $viewComplete = $completJob = $accptJob = '';

        if($data->entry_type == $data->from_entry_type):
            $acceptParam = "{'postData':{'job_status' : ".(($data->from_entry_type == 27)?2:3).", 'id' : ".$data->id.",'next_dept_id': ".$data->next_dept_id."},'controllerName' : 'production/fabrication','fnsave':'acceptJob','message':'Are you sure want to accept this Job?'}";
            $accptJob = '<a class="btn btn-success" href="javascript:void(0)" datatip="Accept Job" flow="down" onclick="confirmStore('.$acceptParam.');"><i class="fa fa-check"></i></a>';
        endif;

        if($data->job_status == 1 && $data->entry_type == $data->to_entry_type):
            $accptJob = '';
            $completParam = "{'postData':{'ref_id' : ".$data->id.", 'pm_id' : ".$data->pm_id.",'entry_type': ".$data->entry_type."},'controller' : 'production/fabrication','fnedit':'cutting','js_store_fn':'confirmStore','modal_id':'modal-md','form_id':'cutting','title':'Cutting [".$data->job_number."] <br> <small>Panel Name : ".$data->item_name."</small>'}";
            $completJob = '<a class="btn btn-success" href="javascript:void(0)" datatip="Complet Job" flow="down" onclick="edit('.$completParam.');"><i class="fa fa-check"></i></a>';
        elseif(in_array($data->job_status,[2,3]) && $data->entry_type == $data->to_entry_type):
            $completJob = $accptJob = '';
            $viewParam = "{'postData':{'ref_id' : ".$data->id.",'entry_type': ".$data->entry_type."},'controller' : 'production/fabrication','fnedit':'viewCutting','js_store_fn':'confirmStore','modal_id':'modal-md','form_id':'cutting','title':'Cutting [".$data->job_number."] <br> <small>Panel Name : ".$data->item_name."</small>','button':'close'}";
            $viewComplete = '<a class="btn btn-info" href="javascript:void(0)" datatip="View Complet Job" flow="down" onclick="edit('.$viewParam.');"><i class="fa fa-eye"></i></a>';
        endif;

        $viewMacDesParam = "{'postData':{'pm_id' : ".$data->pm_id.",'entry_type': '30'},'controller' : 'production/fabrication','fnedit':'viewMechanicalDesign','js_store_fn':'confirmStore','modal_id':'modal-md','form_id':'viewMechanicalDesign','title':'Mechanical Design [".$data->job_number."] <br> <small>Panel Name : ".$data->item_name."</small>','button':'close'}";

        $viewMacDes = '<a class="btn btn-warning" href="javascript:void(0)" datatip="View Mechanical Design" flow="down" onclick="edit('.$viewMacDesParam.');"><i class="fa fa-eye"></i></a>';

        $action = getActionButton($accptJob.$viewMacDes.$completJob.$viewComplete);

        return [$action,$data->sr_no,$data->job_number,$data->item_name,$data->order_qty,$data->priority_status,$data->ga_file,$data->technical_specification_file,$data->sld_file,$viewBom,$data->fab_dept_note,$data->remark];
    endif;

    if($data->to_entry_type == 32): // Bending Table Data

        $completJob = $accptJob = '';

        if($data->entry_type == $data->from_entry_type):
            $acceptParam = "{'postData':{'job_status' : 3, id : ".$data->id.",'next_dept_id': ".$data->next_dept_id."},'controllerName' : 'production/fabrication','fnsave':'acceptJob','message':'Are you sure want to accept this Job?'}";
            $accptJob = '<a class="btn btn-success" href="javascript:void(0)" datatip="Accept Job" flow="down" onclick="confirmStore('.$acceptParam.');"><i class="fa fa-check"></i></a>';
        endif;

        if($data->job_status == 1 && $data->entry_type == $data->to_entry_type):
            $accptJob = '';
            $completParam = "{'postData':{'job_status' : 2, 'ref_id' : ".$data->id.", 'pm_id' : ".$data->pm_id.",'entry_type': ".$data->entry_type."},'controllerName' : 'production/fabrication','fnsave':'completeProductionTrans','message':'Are you sure want to complete this Job?'}";
            $completJob = '<a class="btn btn-success" href="javascript:void(0)" datatip="Complete Job" flow="down" onclick="confirmStore('.$completParam.');"><i class="fa fa-check"></i></a>';
        elseif(in_array($data->job_status,[2,3]) && $data->entry_type == $data->to_entry_type):
            $completJob = $accptJob = '';
        endif;

        $viewMacDesParam = "{'postData':{'pm_id' : ".$data->pm_id.",'entry_type': '30'},'controller' : 'production/fabrication','fnedit':'viewMechanicalDesign','js_store_fn':'confirmStore','modal_id':'modal-md','form_id':'viewMechanicalDesign','title':'Mechanical Design [".$data->job_number."] <br> <small>Panel Name : ".$data->item_name."</small>','button':'close'}";

        $viewMacDes = '<a class="btn btn-warning" href="javascript:void(0)" datatip="View Mechanical Design" flow="down" onclick="edit('.$viewMacDesParam.');"><i class="fa fa-eye"></i></a>';

        $action = getActionButton($accptJob.$viewMacDes.$completJob);

        return [$action,$data->sr_no,$data->job_number,$data->item_name,$data->order_qty,$data->priority_status,$data->ga_file,$data->technical_specification_file,$data->sld_file,$viewBom,$data->fab_dept_note,$data->remark];
    endif;

    if($data->to_entry_type == 33): // Fab. Assembly

        $viewComplete = $completJob = $accptJob = '';

        if($data->entry_type == $data->from_entry_type):
            $acceptParam = "{'postData':{'job_status' : 3, id : ".$data->id.",'next_dept_id': ".$data->next_dept_id."},'controllerName' : 'production/fabrication','fnsave':'acceptJob','message':'Are you sure want to accept this Job?'}";
            $accptJob = '<a class="btn btn-success" href="javascript:void(0)" datatip="Accept Job" flow="down" onclick="confirmStore('.$acceptParam.');"><i class="fa fa-check"></i></a>';
        endif;

        if($data->job_status == 1 && $data->entry_type == $data->to_entry_type):
            $accptJob = '';
            $completParam = "{'postData':{'ref_id' : ".$data->id.", 'pm_id' : ".$data->pm_id.",'entry_type': ".$data->entry_type."},'controller' : 'production/fabrication','fnedit':'fabAssembely','js_store_fn':'confirmStore','modal_id':'modal-md','form_id':'fabAssembely','title':'Fab. Assembly [".$data->job_number."] <br> <small>Panel Name : ".$data->item_name."</small>'}";
            $completJob = '<a class="btn btn-success" href="javascript:void(0)" datatip="Complet Job" flow="down" onclick="edit('.$completParam.');"><i class="fa fa-check"></i></a>';
        elseif(in_array($data->job_status,[2,3]) && $data->entry_type == $data->to_entry_type):
            $completJob = $accptJob = '';

            $viewParam = "{'postData':{'ref_id' : ".$data->id.",'entry_type': ".$data->entry_type."},'controller' : 'production/fabrication','fnedit':'viewFabAssembely','js_store_fn':'confirmStore','modal_id':'modal-md','form_id':'fabAssembely','title':'Fab. Assembly [".$data->job_number."] <br> <small>Panel Name : ".$data->item_name."</small>','button':'close'}";
            $viewComplete = '<a class="btn btn-info" href="javascript:void(0)" datatip="View Complet Job" flow="down" onclick="edit('.$viewParam.');"><i class="fa fa-eye"></i></a>';
        endif;

        $viewMacDesParam = "{'postData':{'pm_id' : ".$data->pm_id.",'entry_type': '30'},'controller' : 'production/fabrication','fnedit':'viewMechanicalDesign','js_store_fn':'confirmStore','modal_id':'modal-md','form_id':'viewMechanicalDesign','title':'Mechanical Design [".$data->job_number."] <br> <small>Panel Name : ".$data->item_name."</small>','button':'close'}";

        $viewMacDes = '<a class="btn btn-warning" href="javascript:void(0)" datatip="View Mechanical Design" flow="down" onclick="edit('.$viewMacDesParam.');"><i class="fa fa-eye"></i></a>';

        $action = getActionButton($accptJob.$viewMacDes.$completJob.$viewComplete);

        return [$action,$data->sr_no,$data->job_number,$data->item_name,$data->order_qty,$data->priority_status,$data->ga_file,$data->technical_specification_file,$data->sld_file,$viewBom,$data->fab_dept_note,$data->remark];
    endif;
}

/* Powder Coating Table Data */
function getPowderCoatingData($data){
    if($data->priority == 1):
        $data->priority_status = '<span class="badge badge-pill badge-danger m-1">'.$data->priority_status.'</span>';
    elseif($data->priority == 2):
        $data->priority_status = '<span class="badge badge-pill badge-warning m-1">'.$data->priority_status.'</span>';
    elseif($data->priority == 3):
        $data->priority_status = '<span class="badge badge-pill badge-info m-1">'.$data->priority_status.'</span>';
    endif;

    $data->ga_file = (!empty($data->ga_file))?'<a href="'.base_url('assets/uploads/production/'.$data->ga_file).'" class="btn btn-outline-info waves-effect waves-light" target="_blank"><i class="fa fa-eye"></i></a>':'';
    $data->technical_specification_file = (!empty($data->technical_specification_file))?'<a href="'.base_url('assets/uploads/production/'.$data->technical_specification_file).'" class="btn btn-outline-info waves-effect waves-light" target="_blank"><i class="fa fa-eye"></i></a>':'';
    $data->sld_file = (!empty($data->sld_file))?'<a href="'.base_url('assets/uploads/production/'.$data->sld_file).'" class="btn btn-outline-info waves-effect waves-light" target="_blank"><i class="fa fa-eye"></i></a>':'';

    $viewBomParam = "{'postData':{'trans_child_id':".$data->trans_child_id."},'modal_id' : 'modal-xl','fnedit':'viewProductionBom','title' : 'View Bom [Item Name : ".$data->item_name."]','button':'close','controller':'production/estimation'}";
    $viewBom = '<a class="btn btn-outline-info waves-effect waves-light" href="javascript:void(0)" onclick="edit('.$viewBomParam.');" datatip="View Item Bom" flow="down"><i class="fa fa-eye"></i></a>';

    if($data->to_entry_type == 34): // Powder Coating

        $viewComplete = $completJob = $accptJob = '';

        if($data->entry_type == $data->from_entry_type):
            $acceptParam = "{'postData':{'job_status' : 3, id : ".$data->id.",'next_dept_id': ".$data->next_dept_id."},'controllerName' : 'production/powderCoating','fnsave':'acceptJob','message':'Are you sure want to accept this Job?'}";
            $accptJob = '<a class="btn btn-success" href="javascript:void(0)" datatip="Accept Job" flow="down" onclick="confirmStore('.$acceptParam.');"><i class="fa fa-check"></i></a>';
        endif;

        if($data->job_status == 1 && $data->entry_type == $data->to_entry_type):
            $accptJob = '';
            $completParam = "{'postData':{'ref_id' : ".$data->id.", 'pm_id' : ".$data->pm_id.",'entry_type': ".$data->entry_type.",'fab_prod_entry_type':'33'},'controller' : 'production/powderCoating','fnedit':'powderCoating','js_store_fn':'confirmStore','modal_id':'modal-md','form_id':'powderCoating','title':'Powder Coating [".$data->job_number."] <br> <small>Panel Name : ".$data->item_name."</small>'}";
            $completJob = '<a class="btn btn-success" href="javascript:void(0)" datatip="Complet Job" flow="down" onclick="edit('.$completParam.');"><i class="fa fa-check"></i></a>';
        elseif(in_array($data->job_status,[2,3]) && $data->entry_type == $data->to_entry_type):
            $completJob = $accptJob = '';

            $viewParam = "{'postData':{'ref_id' : ".$data->id.",'entry_type': ".$data->entry_type."},'controller' : 'production/powderCoating','fnedit':'viewPowderCoating','js_store_fn':'confirmStore','modal_id':'modal-md','form_id':'powderCoating','title':'Powder Coating [".$data->job_number."] <br> <small>Panel Name : ".$data->item_name."</small>','button':'close'}";
            $viewComplete = '<a class="btn btn-info" href="javascript:void(0)" datatip="View Complet Job" flow="down" onclick="edit('.$viewParam.');"><i class="fa fa-eye"></i></a>';
        endif;

        $viewFabAssParam = "{'postData':{'pm_id' : ".$data->pm_id.",'entry_type': '33'},'controller' : 'production/fabrication','fnedit':'viewFabAssembely','js_store_fn':'confirmStore','modal_id':'modal-md','form_id':'viewMechanicalDesign','title':'Fab. Assembely [".$data->job_number."] <br> <small>Panel Name : ".$data->item_name."</small>','button':'close'}";

        $viewFabAss = '<a class="btn btn-warning" href="javascript:void(0)" datatip="View Fab. Assembely" flow="down" onclick="edit('.$viewFabAssParam.');"><i class="fa fa-eye"></i></a>';

        $action = getActionButton($accptJob.$viewFabAss.$completJob.$viewComplete);

        return [$action,$data->sr_no,$data->job_number,$data->item_name,$data->order_qty,$data->priority_status,$data->ga_file,$data->technical_specification_file,$data->sld_file,$viewBom,$data->pc_dept_note,$data->remark];
    endif;
}

/* Electrical Design Table Data */
function getElectricalDesignData($data){
    if($data->priority == 1):
        $data->priority_status = '<span class="badge badge-pill badge-danger m-1">'.$data->priority_status.'</span>';
    elseif($data->priority == 2):
        $data->priority_status = '<span class="badge badge-pill badge-warning m-1">'.$data->priority_status.'</span>';
    elseif($data->priority == 3):
        $data->priority_status = '<span class="badge badge-pill badge-info m-1">'.$data->priority_status.'</span>';
    endif;

    $data->ga_file = (!empty($data->ga_file))?'<a href="'.base_url('assets/uploads/production/'.$data->ga_file).'" class="btn btn-outline-info waves-effect waves-light" target="_blank"><i class="fa fa-eye"></i></a>':'';
    $data->technical_specification_file = (!empty($data->technical_specification_file))?'<a href="'.base_url('assets/uploads/production/'.$data->technical_specification_file).'" class="btn btn-outline-info waves-effect waves-light" target="_blank"><i class="fa fa-eye"></i></a>':'';
    $data->sld_file = (!empty($data->sld_file))?'<a href="'.base_url('assets/uploads/production/'.$data->sld_file).'" class="btn btn-outline-info waves-effect waves-light" target="_blank"><i class="fa fa-eye"></i></a>':'';

    $viewBomParam = "{'postData':{'trans_child_id':".$data->trans_child_id."},'modal_id' : 'modal-xl','fnedit':'viewProductionBom','title' : 'View Bom [Item Name : ".$data->item_name."]','button':'close','controller':'production/estimation'}";
    $viewBom = '<a class="btn btn-outline-info waves-effect waves-light" href="javascript:void(0)" onclick="edit('.$viewBomParam.');" datatip="View Item Bom" flow="down"><i class="fa fa-eye"></i></a>';

    $addAttachment = '';

    $attechmentParam = "{'postData':{'pm_id':".$data->id."},'modal_id' : 'modal-md', 'form_id':'electricalDesign','fnedit':'electricalDesign','title' : 'Electrical Design  [".$data->job_number."] <br> <small>Panel Name : ".$data->item_name."</small>','js_store_fn':'customStore','res_function':'resSaveElectricalDesign'}";
    $addAttachment = '<a class="btn btn-info" href="javascript:void(0)" onclick="edit('.$attechmentParam.');" datatip="Add Design" flow="down"><i class="fa fa-plus"></i></a>';

    $documentPrintBtn = '<a class="btn btn-dark" href="'.base_url('production/electricalDesign/printElectricalDesignDocuments/'.$data->id).'" target="_blank" datatip="Print Electrical Design Document" flow="down"><i class="fas fa-print" ></i></a>';

    $action = getActionButton($documentPrintBtn.$addAttachment);

    return [$action,$data->sr_no,$data->job_number,$data->item_name,$data->order_qty,$data->priority_status,$data->ga_file,$data->technical_specification_file,$data->sld_file,$viewBom,$data->fab_dept_note,$data->pc_dept_note,$data->remark];
}

/* Assembly Allotment Table Data */
function getAssemblyAllotmentData($data){
    if($data->priority == 1):
        $data->priority_status = '<span class="badge badge-pill badge-danger m-1">'.$data->priority_status.'</span>';
    elseif($data->priority == 2):
        $data->priority_status = '<span class="badge badge-pill badge-warning m-1">'.$data->priority_status.'</span>';
    elseif($data->priority == 3):
        $data->priority_status = '<span class="badge badge-pill badge-info m-1">'.$data->priority_status.'</span>';
    endif;

    $data->ga_file = (!empty($data->ga_file))?'<a href="'.base_url('assets/uploads/production/'.$data->ga_file).'" class="btn btn-outline-info waves-effect waves-light" target="_blank"><i class="fa fa-eye"></i></a>':'';
    $data->technical_specification_file = (!empty($data->technical_specification_file))?'<a href="'.base_url('assets/uploads/production/'.$data->technical_specification_file).'" class="btn btn-outline-info waves-effect waves-light" target="_blank"><i class="fa fa-eye"></i></a>':'';
    $data->sld_file = (!empty($data->sld_file))?'<a href="'.base_url('assets/uploads/production/'.$data->sld_file).'" class="btn btn-outline-info waves-effect waves-light" target="_blank"><i class="fa fa-eye"></i></a>':'';

    $viewBomParam = "{'postData':{'trans_child_id':".$data->trans_child_id."},'modal_id' : 'modal-xl','fnedit':'viewProductionBom','title' : 'View Bom [Item Name : ".$data->item_name."]','button':'close','controller':'production/estimation'}";
    $viewBom = '<a class="btn btn-outline-info waves-effect waves-light" href="javascript:void(0)" onclick="edit('.$viewBomParam.');" datatip="View Item Bom" flow="down"><i class="fa fa-eye"></i></a>';

    $viewFabAssParam = "{'postData':{'pm_id' : ".$data->pm_id.",'entry_type': '33'},'controller' : 'production/fabrication','fnedit':'viewFabAssembely','js_store_fn':'confirmStore','modal_id':'modal-md','form_id':'viewMechanicalDesign','title':'Fab. Assembely [".$data->job_number."] <br> <small>Panel Name : ".$data->item_name."</small>','button':'close'}";

    $viewFabAss = '<a class="btn btn-warning" href="javascript:void(0)" datatip="View Fab. Assembely" flow="down" onclick="edit('.$viewFabAssParam.');"><i class="fa fa-eye"></i></a>';

    if($data->from_entry_type == 34): // Powder Coating

        $accptJob = '';

        if($data->entry_type == $data->from_entry_type):
            $acceptParam = "{'postData':{'job_status' : 3, 'id' : ".$data->id.",'next_dept_id': ".$data->next_dept_id."},'form_id':'assignJob','modal_id':'modal-md','title':'Assign Job [".$data->job_number."] <br> <small>Panel Name : ".$data->item_name."</small>','controllerName' : 'production/assembly','fnedit':'assignJob','fnsave':'saveAssignJob','js_store_fn':'confirmStore'}";
            $accptJob = '<a class="btn btn-success" href="javascript:void(0)" datatip="Assign Job" flow="down" onclick="edit('.$acceptParam.');"><i class="fa fa-check"></i></a>';
        endif;



        $action = getActionButton($viewFabAss.$accptJob);

        return [$action,$data->sr_no,$data->job_number,$data->item_name,floatval($data->order_qty),floatval($data->vendor_qty),floatval($data->pending_allotment_qty),$data->priority_status,$data->ga_file,$data->technical_specification_file,$data->sld_file,$viewBom,$data->ass_dept_note,$data->remark];
    endif;

    $action = getActionButton($viewFabAss);
    return [$action,$data->sr_no,$data->job_number,$data->item_name,$data->party_name,floatval($data->vendor_qty),$data->priority_status,$data->ga_file,$data->technical_specification_file,$data->sld_file,$viewBom,$data->pc_dept_note,$data->remark];
}

/* Assembly Production Table data */
function getAssemblyProductionData($data){
    if($data->priority == 1):
        $data->priority_status = '<span class="badge badge-pill badge-danger m-1">'.$data->priority_status.'</span>';
    elseif($data->priority == 2):
        $data->priority_status = '<span class="badge badge-pill badge-warning m-1">'.$data->priority_status.'</span>';
    elseif($data->priority == 3):
        $data->priority_status = '<span class="badge badge-pill badge-info m-1">'.$data->priority_status.'</span>';
    endif;

    $data->ga_file = (!empty($data->ga_file))?'<a href="'.base_url('assets/uploads/production/'.$data->ga_file).'" class="btn btn-outline-info waves-effect waves-light" target="_blank"><i class="fa fa-eye"></i></a>':'';
    $data->technical_specification_file = (!empty($data->technical_specification_file))?'<a href="'.base_url('assets/uploads/production/'.$data->technical_specification_file).'" class="btn btn-outline-info waves-effect waves-light" target="_blank"><i class="fa fa-eye"></i></a>':'';
    $data->sld_file = (!empty($data->sld_file))?'<a href="'.base_url('assets/uploads/production/'.$data->sld_file).'" class="btn btn-outline-info waves-effect waves-light" target="_blank"><i class="fa fa-eye"></i></a>':'';

    $viewBomParam = "{'postData':{'trans_child_id':".$data->trans_child_id."},'modal_id' : 'modal-xl','fnedit':'viewProductionBom','title' : 'View Bom [Item Name : ".$data->item_name."]','button':'close','controller':'production/estimation'}";
    $viewBom = '<a class="btn btn-outline-info waves-effect waves-light" href="javascript:void(0)" onclick="edit('.$viewBomParam.');" datatip="View Item Bom" flow="down"><i class="fa fa-eye"></i></a>';

    $viewFabAssParam = "{'postData':{'pm_id' : ".$data->pm_id.",'entry_type': '33'},'controller' : 'production/fabrication','fnedit':'viewFabAssembely','js_store_fn':'confirmStore','modal_id':'modal-md','form_id':'viewMechanicalDesign','title':'Fab. Assembely [".$data->job_number."] <br> <small>Panel Name : ".$data->item_name."</small>','button':'close'}";

    $viewFabAss = '<a class="btn btn-warning" href="javascript:void(0)" datatip="View Fab. Assembely" flow="down" onclick="edit('.$viewFabAssParam.');"><i class="fa fa-eye"></i></a>';

    if($data->to_entry_type == 37): // Contactor Assembly
        $viewComplete = $completJob = $accptJob = '';

        if($data->entry_type == $data->from_entry_type):
            /* $acceptParam = "{'postData':{'job_status' : 2, 'id' : ".$data->id.",'next_dept_id': ".$data->next_dept_id."},'controllerName' : 'production/assembly','fnsave':'acceptJob','message':'Are you sure want to accept this Job?'}";
            $accptJob = '<a class="btn btn-success" href="javascript:void(0)" datatip="Accept Job" flow="down" onclick="confirmStore('.$acceptParam.');"><i class="fa fa-check"></i></a>'; */

            $acceptParam = "{'postData':{'ref_id' : ".$data->id.", 'pm_id' : ".$data->pm_id.",'entry_type': ".$data->next_dept_id.",'fab_prod_entry_type':'33'},'controller' : 'production/assembly','fnedit':'assemblyProduction','js_store_fn':'confirmStore','modal_id':'modal-md','form_id':'assemblyProduction','title':'Assembly Production [".$data->job_number."] <br> <small>Panel Name : ".$data->item_name."</small>'}";
            $accptJob = '<a class="btn btn-success" href="javascript:void(0)" datatip="Complet Job" flow="down" onclick="edit('.$acceptParam.');"><i class="fa fa-check"></i></a>';
        endif;

        if($data->job_status == 1 && $data->entry_type == $data->to_entry_type):
            $accptJob = '';
            /* $completParam = "{'postData':{'ref_id' : ".$data->id.", 'pm_id' : ".$data->pm_id.",'entry_type': ".$data->entry_type.",'fab_prod_entry_type':'33'},'controller' : 'production/assembly','fnedit':'assemblyProduction','js_store_fn':'confirmStore','modal_id':'modal-md','form_id':'assemblyProduction','title':'Assembly Production'}";
            $completJob = '<a class="btn btn-success" href="javascript:void(0)" datatip="Complet Job" flow="down" onclick="edit('.$completParam.');"><i class="fa fa-check"></i></a>'; */

            $completParam = "{'postData':{'id' : ".$data->id.", 'pm_id' : ".$data->pm_id.", 'entry_type': ".$data->entry_type."},'controllerName' : 'production/assembly','fnsave':'completeJob','message':'Are you sure want to complete this Job?'}";
            $completJob = '<a class="btn btn-success" href="javascript:void(0)" datatip="Complet Job" flow="down" onclick="confirmStore('.$completParam.');"><i class="fa fa-check"></i></a>';
        elseif(in_array($data->job_status,[2,3]) && $data->entry_type == $data->to_entry_type):
            $completJob = $accptJob = '';

            $viewParam = "{'postData':{'ref_id' : ".$data->id.",'entry_type': ".$data->entry_type."},'controller' : 'production/assembly','fnedit':'viewAssemblyProduction','js_store_fn':'confirmStore','modal_id':'modal-md','form_id':'assemblyProduction','title':'Assembly Production [".$data->job_number."] <br> <small>Panel Name : ".$data->item_name."</small>','button':'close'}";
            $viewComplete = '<a class="btn btn-info" href="javascript:void(0)" datatip="View Complet Job" flow="down" onclick="edit('.$viewParam.');"><i class="fa fa-eye"></i></a>';
        endif;

        $action = getActionButton($viewFabAss.$accptJob.$completJob.$viewComplete);
        
        return [$action,$data->sr_no,$data->job_number,$data->item_name,$data->party_name,floatval($data->vendor_qty),$data->priority_status,$data->ga_file,$data->technical_specification_file,$data->sld_file,$viewBom,$data->pc_dept_note,$data->remark];
    endif;
}

/* Quality Department Data */
function getQualityData($data){
    if($data->priority == 1):
        $data->priority_status = '<span class="badge badge-pill badge-danger m-1">'.$data->priority_status.'</span>';
    elseif($data->priority == 2):
        $data->priority_status = '<span class="badge badge-pill badge-warning m-1">'.$data->priority_status.'</span>';
    elseif($data->priority == 3):
        $data->priority_status = '<span class="badge badge-pill badge-info m-1">'.$data->priority_status.'</span>';
    endif;

    $data->ga_file = (!empty($data->ga_file))?'<a href="'.base_url('assets/uploads/production/'.$data->ga_file).'" class="btn btn-outline-info waves-effect waves-light" target="_blank"><i class="fa fa-eye"></i></a>':'';

    $viewBomParam = "{'postData':{'trans_child_id':".$data->trans_child_id."},'modal_id' : 'modal-xl','fnedit':'viewProductionBom','title' : 'View Bom [Item Name : ".$data->item_name."]','button':'close','controller':'production/estimation'}";
    $viewBom = '<a class="btn btn-outline-info waves-effect waves-light" href="javascript:void(0)" onclick="edit('.$viewBomParam.');" datatip="View Item Bom" flow="down"><i class="fa fa-eye"></i></a>';

    if($data->to_entry_type == 38): //Quality Department

        $viewMacDesParam = "{'postData':{'pm_id' : ".$data->pm_id.",'entry_type': '30'},'controller' : 'production/fabrication','fnedit':'viewMechanicalDesign','js_store_fn':'confirmStore','modal_id':'modal-md','form_id':'viewMechanicalDesign','title':'Mechanical Design [".$data->job_number."] <br> <small>Panel Name : ".$data->item_name."</small>','button':'close'}";
        $viewMacDes = '<a class="btn btn-warning" href="javascript:void(0)" datatip="View Mechanical Design" flow="down" onclick="edit('.$viewMacDesParam.');"><i class="fa fa-eye"></i></a>';

        $qcButton = "";
        if($data->from_entry_type == 37):
            $qcParam = "{'postData':{'job_status' : 3, 'id' : ".$data->id.",'next_dept_id': ".$data->next_dept_id."},'form_id':'qualityChecking','modal_id':'modal-md','title':'Quality Checking [".$data->job_number."] <br> <small>Panel Name : ".$data->item_name."</small>','controllerName' : 'production/quality','fnedit':'qualityChecking','fnsave':'saveQualityChecking','js_store_fn':'confirmStore'}";
            $qcButton = '<a class="btn btn-success" href="javascript:void(0)" datatip="QC Check" flow="down" onclick="edit('.$qcParam.');"><i class="fa fa-check"></i></a>';
        endif;

        $action = getActionButton($viewMacDes.$qcButton);

        return [$action,$data->sr_no,$data->job_number,$data->item_name,$data->order_qty,$data->priority_status,$data->ga_file,$viewBom,$data->fab_dept_note,$data->remark];
    endif;
}

/* Testing Parameters Data */
function getTestingParametersData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Testing Parameter'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'modal-md', 'form_id' : 'editTestingParameter', 'title' : 'Update Testing Parameter'}";

    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="ti-pencil-alt" ></i></a>';

    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';
	
	$action = getActionButton($editButton.$deleteButton);
    return [$action,$data->sr_no,$data->system_detail,$data->control_supply,$data->hv_test,$data->insulation_resistance];
}

/* Testing Department Data */
function getTestingData($data){
    if($data->priority == 1):
        $data->priority_status = '<span class="badge badge-pill badge-danger m-1">'.$data->priority_status.'</span>';
    elseif($data->priority == 2):
        $data->priority_status = '<span class="badge badge-pill badge-warning m-1">'.$data->priority_status.'</span>';
    elseif($data->priority == 3):
        $data->priority_status = '<span class="badge badge-pill badge-info m-1">'.$data->priority_status.'</span>';
    endif;

    $data->ga_file = (!empty($data->ga_file))?'<a href="'.base_url('assets/uploads/production/'.$data->ga_file).'" class="btn btn-outline-info waves-effect waves-light" target="_blank"><i class="fa fa-eye"></i></a>':'';

    $viewBomParam = "{'postData':{'trans_child_id':".$data->trans_child_id."},'modal_id' : 'modal-xl','fnedit':'viewProductionBom','title' : 'View Bom [Item Name : ".$data->item_name."]','button':'close','controller':'production/estimation'}";
    $viewBom = '<a class="btn btn-outline-info waves-effect waves-light" href="javascript:void(0)" onclick="edit('.$viewBomParam.');" datatip="View Item Bom" flow="down"><i class="fa fa-eye"></i></a>';

    $viewMacDesParam = "{'postData':{'pm_id' : ".$data->pm_id.",'entry_type': '30'},'controller' : 'production/fabrication','fnedit':'viewMechanicalDesign','js_store_fn':'confirmStore','modal_id':'modal-md','form_id':'viewMechanicalDesign','title':'Mechanical Design [".$data->job_number."] <br> <small>Panel Name : ".$data->item_name."</small>','button':'close'}";
    $viewMacDes = '<a class="btn btn-warning" href="javascript:void(0)" datatip="View Mechanical Design" flow="down" onclick="edit('.$viewMacDesParam.');"><i class="fa fa-eye"></i></a>';

    if($data->from_entry_type != $data->to_entry_type):
        $testingParam = "{'postData':{'job_status' : 3, 'id' : ".$data->id.",'next_dept_id': ".$data->next_dept_id.",'trans_child_id':".$data->trans_child_id.",'party_name':'".$data->party_name."','item_name':'".$data->item_name."','job_number':'".$data->job_number."'},'form_id':'testingForm','modal_id':'modal-md','title':'Testing','controllerName' : 'production/testing','fnedit':'addTestingDetail','fnsave':'save','js_store_fn':'confirmStore'}";
        $testingButton = '<a class="btn btn-success" href="javascript:void(0)" datatip="Testing" flow="down" onclick="edit('.$testingParam.');"><i class="fa fa-check"></i></a>';

        $action = getActionButton($viewMacDes.$testingButton);
        return [$action,$data->sr_no,$data->job_number,$data->item_name,$data->order_qty,$data->total_tested_qty,$data->priority_status,$data->ga_file,$viewBom,$data->fab_dept_note,$data->remark];
    else:
        $editParam = "{'postData':{'id' : ".$data->id."},'form_id':'testingForm','modal_id':'modal-md','title':'Testing','controllerName' : 'production/testing','fnedit':'editTestingDetail','fnsave':'save','js_store_fn':'confirmStore'}";
        $editButton = '<a class="btn btn-success" href="javascript:void(0)" datatip="Testing" flow="down" onclick="edit('.$editParam.');"><i class="ti-pencil-alt"></i></a>';

        $documentationParam = "{'postData':{'ref_id' : ".$data->id.", 'main_pm_id' : ".$data->pm_id."},'form_id':'documentationForm','modal_id':'modal-md','title':'Documentation [".$data->job_number."] <br> <small>Panel Name : ".$data->item_name."</small>','controllerName' : 'production/testing','fnedit':'documentation','fnsave':'saveDocumentation','js_store_fn':'customStore','res_function':'resSaveDocumentation'}";
        $documentationButton = '<a class="btn btn-primary" href="javascript:void(0)" datatip="Documentation" flow="down" onclick="edit('.$documentationParam.');"><i class="ti-book"></i></a>';

        $printBtn = '<a class="btn btn-info" href="'.base_url('production/testing/printTestingCertificate/'.$data->id).'" target="_blank" datatip="Print Test Certificate" flow="down"><i class="fas fa-print" ></i></a>';

        $prodDetailPrintBtn = '<a class="btn btn-dark" href="'.base_url('production/estimation/printProductionDetails/'.$data->id).'" target="_blank" datatip="Print GA,T.S. & SLD Document" flow="down"><i class="fas fa-print" ></i></a>';

        $documentPrintBtn = '<a class="btn btn-dark" href="'.base_url('production/testing/printDocumentationFiles/'.$data->id.'/'.$data->pm_id).'" target="_blank" datatip="Print Documentation" flow="down"><i class="fas fa-print" ></i></a>';

        $action = getActionButton($viewMacDes.$prodDetailPrintBtn.$documentationButton.$documentPrintBtn.$editButton.$printBtn);
        return [$action,$data->sr_no,$data->job_number,$data->customer_name,$data->item_name,$data->order_qty,$data->tested_qty,$data->tc_sr_number,$data->drgs_number,$data->switchgear_no,$data->accepted_by_name,$data->priority_status,$data->ga_file,$viewBom,$data->fab_dept_note,$data->remark];
    endif;
}
?>