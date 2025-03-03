<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/* get Pagewise Table Header */
function getStoreDtHeader($page){
    /* Location Master header */
    $data['storeLocation'][] = ["name"=>"Action",'textAlign'=>'center'];
    $data['storeLocation'][] = ["name"=>"#",'textAlign'=>'center']; 
    $data['storeLocation'][] = ["name"=>"Store Name"];
    $data['storeLocation'][] = ["name"=>"Location"];
    $data['storeLocation'][] = ["name"=>"Remark"];

    /* Gate Entry */
    $data['gateEntry'][] = ["name" => "Action", "textAlign" => "center"];
    $data['gateEntry'][] = ["name" => "#", "textAlign" => "center"];
    $data['gateEntry'][] = ["name"=> "GE No.", "textAlign" => "center"];
    $data['gateEntry'][] = ["name" => "GE Date", "textAlign" => "center"];
    $data['gateEntry'][] = ["name" => "Party Name"];
    $data['gateEntry'][] = ["name" => "Transport"];
    $data['gateEntry'][] = ["name" => "LR No."];
    $data['gateEntry'][] = ["name" => "Vehicle Type"];
    $data['gateEntry'][] = ["name" => "Vehicle No."];
    $data['gateEntry'][] = ['name' => "Invoice No."];
    $data['gateEntry'][] = ['name' => "Invoice Date"];
    $data['gateEntry'][] = ['name' => "Challan No."];
    $data['gateEntry'][] = ['name' => "Challan Date"];

    /* Gate Inward Pending GE Tab Header */
    $data['pendingGE'][] = ["name" => "Action", "textAlign" => "center"];
    $data['pendingGE'][] = ["name" => "#", "textAlign" => "center"];
    $data['pendingGE'][] = ["name"=> "GE No.", "textAlign" => "center"];
    $data['pendingGE'][] = ["name" => "GE Date", "textAlign" => "center"];
    $data['pendingGE'][] = ["name" => "Party Name"];
    $data['pendingGE'][] = ["name" => "Inv. No."];
    $data['pendingGE'][] = ["name" => "Inv. Date"];
    $data['pendingGE'][] = ['name' => "CH. NO."];
    $data['pendingGE'][] = ['name' => "CH. Date"];

    /* Gate Inward Pending/Compeleted Tab Header */
    $data['gateInward'][] = ["name" => "Action", "textAlign" => "center"];
    $data['gateInward'][] = ["name" => "#", "textAlign" => "center"];
    $data['gateInward'][] = ["name"=> "GI No.", "textAlign" => "center"];
    $data['gateInward'][] = ["name" => "GI Date", "textAlign" => "center"];
    $data['gateInward'][] = ["name" => "GE. NO."];   
    $data['gateInward'][] = ["name" => "PO. NO."];   
    $data['gateInward'][] = ["name" => "Party Name"];
    $data['gateInward'][] = ["name" => "CAT No."];
    $data['gateInward'][] = ["name" => "Item Name"];
    $data['gateInward'][] = ["name" => "Qty"];
    $data['gateInward'][] = ["name" => "UOM"];
    $data['gateInward'][] = ["name" => "Price"];
    $data['gateInward'][] = ["name" => "Disc. (%)"];

    /* Material Issue */
    $data['materialIssue'][] = ["name" => "Action", "textAlign" => "center"];
    $data['materialIssue'][] = ["name" => "#", "textAlign" => "center"];
    $data['materialIssue'][] = ["name" => "Entry Date", "textAlign" => "center"];
    $data['materialIssue'][] = ["name" => "Entry No.", "textAlign" => "center"];
    $data['materialIssue'][] = ["name" => "Collected By"];   
    $data['materialIssue'][] = ["name" => "CAT No."];
    $data['materialIssue'][] = ["name" => "Item Name"];
    $data['materialIssue'][] = ["name" => "Req. Qty"];   
    $data['materialIssue'][] = ["name" => "Issue Qty"];
    $data['materialIssue'][] = ["name" => "Return Qty"];
    $data['materialIssue'][] = ["name" => "Remark"];

    return tableHeader($data[$page]);
}

/* Store Location Data */
function getStoreLocationData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Store Location'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'modal-md', 'form_id' : 'editStoreLocation', 'title' : 'Update Store Location'}";

    $editButton = ''; $deleteButton = '';
    if(!empty($data->ref_id) && empty($data->store_type)):
        $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="ti-pencil-alt" ></i></a>';

        $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';
    endif;

    if($data->final_location == 0):
        $locationName = '<a href="' . base_url("storeLocation/list/" . $data->id) . '">' . $data->location . '</a>';
    else:
        $locationName = $data->location;
    endif;
	
	$action = getActionButton($editButton.$deleteButton);
    return [$action,$data->sr_no,$data->store_name,$locationName,$data->remark];
}

/* Gate Entry Data  */
function getGateEntryData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Gate Entry'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'modal-lg', 'form_id' : 'editGateEntry', 'title' : 'Update Gate Entry'}";

    $editButton = "";
    $deleteButton = "";
    if($data->trans_status == 0):
        $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="ti-pencil-alt" ></i></a>';

        $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';
    endif;

    $action = getActionButton($editButton.$deleteButton);

    return [$action,$data->sr_no,$data->trans_number,formatDate($data->trans_date),$data->party_name,$data->transport_name,$data->lr,$data->vehicle_type_name,$data->vehicle_no,$data->inv_no,((!empty($data->inv_date))?formatDate($data->inv_date):""),$data->doc_no,((!empty($data->doc_date))?formatDate($data->doc_date):"")];
}

/* GateInward Data Data  */
function getGateInwardData($data){
    $action = '';$editButton='';$deleteButton="";$pallatePrint="";
    if($data->trans_type == 1): //Pending GE Data
        $createGI = "";
        $createGIParam = "{postData:{'id' : ".$data->id."}, 'modal_id' : 'modal-xl', 'form_id' : 'addGateInward', 'title' : 'Gate Inward',fnsave: 'save',fnedit: 'createGI'}";

        $createGI = '<a class="btn btn-success btn-edit permission-write" href="javascript:void(0)" datatip="Create GI" flow="down" onclick="edit('.$createGIParam.');"><i class="fa fa-plus" ></i></a>';

        $action = getActionButton($createGI);

        return [$action,$data->sr_no,$data->trans_number,formatDate($data->trans_date),$data->party_name,$data->inv_no,$data->inv_date,$data->doc_no,$data->doc_date];
    else: // Gate Inward Pending/Completed Data

        $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Gate Inward'}";
        $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'modal-xl', 'form_id' : 'editGateInward', 'title' : 'Update Gate Inward'}";

        $editButton = "";
        $deleteButton = "";
        if($data->trans_status == 0):
            $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="ti-pencil-alt" ></i></a>';

            $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';
        endif;

        $insParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'modal-xl', 'form_id' : 'materialInspection', 'title' : 'Material Inspection','fnedit':'materialInspection','fnsave':'saveInspectedMaterial'}";
        $inspection = '<a href="javscript:voide(0);" type="button" class="btn btn-warning permission-modify" datatip="Inspection" flow="down" onclick="edit('.$insParam.');"><i class="fas fa-search"></i></a>';

	    $iirPrint = '<a href="'.base_url('gateInward/ir_print/'.$data->id).'" type="button" class="btn btn-primary" datatip="IIR Print" flow="down" target="_blank"><i class="fas fa-print"></i></a>';

	    $action = getActionButton($iirPrint.$inspection.$editButton.$deleteButton);

        return [$action,$data->sr_no,$data->trans_number,formatDate($data->trans_date),$data->ge_number,$data->po_number,$data->party_name,$data->item_code,$data->item_name,$data->qty,$data->unit_name,$data->price,$data->disc_per];
    endif;
}

/* Material Issue */
function getMaterialIssueData($data){
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'modal-lg', 'form_id' : 'edit', 'title' : 'Material Issue'}";
    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="ti-pencil-alt" ></i></a>';

    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Material Issue'}";
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';

    $rejectButton = '';  
    if($data->trans_status == 0):
        $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Material Issue" flow="down" onclick="edit('.$editParam.');"><i class="fa fa-paper-plane" ></i></a>';

        $rejectParam = "{'postData':{'id':".$data->id.",'trans_status' : 2},'fnsave':'changeRequestStatus','message' : 'Are you sure want to reject this request ?'}";
        $rejectButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="confirmStore('.$rejectParam.');" datatip="Reject Request" flow="down"><i class="ti-close"></i></a>';

        $deleteButton = '';
    endif;

    $returnButton = '';
    if($data->trans_status == 1):
        $returnParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'modal-xl', 'form_id' : 'edit', 'title' : 'Material Return','fnedit':'materialReturn','fnsave':'saveReturnMaterial'}";
        $returnButton = '<a class="btn btn-primary btn-edit permission-modify" href="javascript:void(0)" datatip="Material Return" flow="down" onclick="edit('.$returnParam.');"><i class="fa fa-reply"></i></a>';
    endif;

    if(floatval($data->return_qty) > 0): $editButton = $deleteButton = ''; endif;

    $action = getActionButton($rejectButton.$returnButton.$editButton.$deleteButton);

    return [$action,$data->sr_no,formatDate($data->trans_date),$data->trans_number,$data->collected_by,$data->item_code,$data->item_name,floatval($data->req_qty),floatval($data->issue_qty),floatval($data->return_qty),$data->remark];
}
?>