<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/* get Pagewise Table Header */
function getStoreDtHeader($page){
    /* Location Master header */
    $data['storeLocation'][] = ["name"=>"Action","style"=>"width:5%;",'textAlign'=>'center'];
    $data['storeLocation'][] = ["name"=>"#","style"=>"width:5%;",'textAlign'=>'center']; 
    $data['storeLocation'][] = ["name"=>"Store Name"];
    $data['storeLocation'][] = ["name"=>"Location"];
    $data['storeLocation'][] = ["name"=>"Remark"];

    /* Gate Entry */
    $data['gateEntry'][] = ["name" => "Action", "style" => "width:5%;", "textAlign" => "center"];
    $data['gateEntry'][] = ["name" => "#", "style" => "width:5%;", "textAlign" => "center"];
    $data['gateEntry'][] = ["name"=> "GE No.", "textAlign" => "center"];
    $data['gateEntry'][] = ["name" => "GE Date", "textAlign" => "center"];
    $data['gateEntry'][] = ["name" => "Driver Name"];
    $data['gateEntry'][] = ["name" => "Driver No."];
    $data['gateEntry'][] = ["name" => "Vehicle No."];
    $data['gateEntry'][] = ["name" => "Vehicle Type"];
    $data['gateEntry'][] = ["name" => "Transport"];
    $data['gateEntry'][] = ['name' => "No. of Items"];

    /* Gate Inward Pending GE Tab Header */
    $data['pendingGE'][] = ["name" => "Action", "style" => "width:5%;", "textAlign" => "center"];
    $data['pendingGE'][] = ["name" => "#", "style" => "width:5%;", "textAlign" => "center"];
    $data['pendingGE'][] = ["name"=> "GE No.", "textAlign" => "center"];
    $data['pendingGE'][] = ["name" => "GE Date", "textAlign" => "center"];
    $data['pendingGE'][] = ["name" => "Party Name"];
    $data['pendingGE'][] = ["name" => "Item Name"];
    $data['pendingGE'][] = ["name" => "Qty"];
    $data['pendingGE'][] = ["name" => "Inv. No."];
    $data['pendingGE'][] = ["name" => "Inv. Date"];
    $data['pendingGE'][] = ['name' => "CH. NO."];
    $data['pendingGE'][] = ['name' => "CH. Date"];

    /* Gate Inward Pending/Compeleted Tab Header */
    $data['gateInward'][] = ["name" => "Action", "style" => "width:5%;", "textAlign" => "center"];
    $data['gateInward'][] = ["name" => "#", "style" => "width:5%;", "textAlign" => "center"];
    $data['gateInward'][] = ["name"=> "GI No.", "textAlign" => "center"];
    $data['gateInward'][] = ["name" => "GI Date", "textAlign" => "center"];
    $data['gateInward'][] = ["name" => "Party Name"];
    $data['gateInward'][] = ["name" => "Item Name"];
    $data['gateInward'][] = ["name" => "Inward Qty"];
    $data['gateInward'][] = ["name" => "Actual Qty"];
    $data['gateInward'][] = ["name" => "Weight (Kgs)"];
    $data['gateInward'][] = ["name" => "PO. NO."];   

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

    return [$action,$data->sr_no,$data->trans_number,formatDate($data->trans_date),$data->driver_name,$data->driver_contact,$data->vehicle_no,$data->vehicle_type_name,$data->transport_name,$data->no_of_items];
}

/* GateInward Data Data  */
function getGateInwardData($data){
    $action = '';$editButton='';$deleteButton="";$pallatePrint="";
    if($data->trans_type == 1): //Pending GE Data
        $createGI = "";
        $createGIParam = "{postData:{'id' : ".$data->id."}, 'modal_id' : 'modal-xl', 'form_id' : 'addGateInward', 'title' : 'Gate Inward',fnsave: 'save',fnedit: 'createGI'}";

        $createGI = '<a class="btn btn-success btn-edit permission-write" href="javascript:void(0)" datatip="Create GI" flow="down" onclick="edit('.$createGIParam.');"><i class="fa fa-plus" ></i></a>';

        $action = getActionButton($createGI);

        return [$action,$data->sr_no,$data->trans_number,formatDate($data->trans_date),$data->party_name,$data->item_name,$data->qty,$data->inv_no,$data->inv_date,$data->doc_no,$data->doc_date];
    else: // Gate Inward Pending/Completed Data
	    $iirPrint = '<a href="'.base_url('gateInward/ir_print/'.$data->id).'" type="button" class="btn btn-primary" datatip="IIR Print" flow="down" target="_blank"><i class="fas fa-print"></i></a>';

	    $action = getActionButton($pallatePrint.$iirPrint.$editButton.$deleteButton);

        return [$action,$data->sr_no,$data->trans_number,formatDate($data->trans_date),$data->party_name,$data->item_name,$data->inward_qty,$data->qty,$data->qty_kg,$data->po_number];
    endif;
}

?>