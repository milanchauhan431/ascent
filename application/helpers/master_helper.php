<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/* get Pagewise Table Header */
function getMasterDtHeader($page){
    /* Customer Header */
    $data['customer'][] = ["name"=>"Action","sortable"=>"FALSE","textAlign"=>"center"];
	$data['customer'][] = ["name"=>"#","sortable"=>"FALSE","textAlign"=>"center"]; 
	$data['customer'][] = ["name"=>"Company Name"];
	$data['customer'][] = ["name"=>"Contact Person"];
    $data['customer'][] = ["name"=>"Contact No."];
    $data['customer'][] = ["name"=>"Party Code"];
    $data['customer'][] = ["name"=>"Currency"];

    /* Supplier Header */
    $data['supplier'][] = ["name"=>"Action","sortable"=>"FALSE","textAlign"=>"center"];
	$data['supplier'][] = ["name"=>"#","sortable"=>"FALSE","textAlign"=>"center"]; 
	$data['supplier'][] = ["name"=>"Company Name"];
	$data['supplier'][] = ["name"=>"Contact Person"];
    $data['supplier'][] = ["name"=>"Contact No."];
    $data['supplier'][] = ["name"=>"Party Code"];

    /* Vendor Header */
    $data['vendor'][] = ["name"=>"Action","sortable"=>"FALSE","textAlign"=>"center"];
    $data['vendor'][] = ["name"=>"#","sortable"=>"FALSE","textAlign"=>"center"]; 
    $data['vendor'][] = ["name"=>"Company Name"];
    $data['vendor'][] = ["name"=>"Contact Person"];
    $data['vendor'][] = ["name"=>"Contact No."];
    $data['vendor'][] = ["name"=>"Address"];

    /* Item Category Header */
    $data['itemCategory'][] = ["name"=>"Action","sortable"=>"FALSE","textAlign"=>"center"];
    $data['itemCategory'][] = ["name"=>"#","sortable"=>"FALSE","textAlign"=>"center"]; 
    $data['itemCategory'][] = ["name"=>"Category Name"];
    $data['itemCategory'][] = ["name"=>"Parent Category"];
    $data['itemCategory'][] = ["name"=>"Is Final ?"];
    $data['itemCategory'][] = ["name"=>"Stock Type"];
    $data['itemCategory'][] = ["name"=>"Is Returnable ?"];
    $data['itemCategory'][] = ["name"=>"Remark"];

    /* Brand Master Header */
    $data['brandMaster'][] = ["name"=>"Action","sortable"=>"FALSE","textAlign"=>"center"];
    $data['brandMaster'][] = ["name"=>"#","sortable"=>"FALSE","textAlign"=>"center"]; 
    $data['brandMaster'][] = ["name"=>"Make"];
    $data['brandMaster'][] = ["name"=>"Remark"];

    /* Finish Goods Header */
    $data['finish_goods'][] = ["name"=>"Action","sortable"=>"FALSE","textAlign"=>"center"];
    $data['finish_goods'][] = ["name"=>"#","sortable"=>"FALSE","textAlign"=>"center"]; 
    $data['finish_goods'][] = ["name"=>"CAT No."];
    $data['finish_goods'][] = ["name"=>"Item Name"];
    $data['finish_goods'][] = ["name"=>"Category Name"];
    $data['finish_goods'][] = ["name"=>"Unit"];
    $data['finish_goods'][] = ["name"=>"Make"];
    $data['finish_goods'][] = ["name"=>"Defual Disc. (%)"];
    $data['finish_goods'][] = ["name"=>"Price"];

    /* Row Material Header */
    $data['raw_material'][] = ["name"=>"Action","sortable"=>"FALSE","textAlign"=>"center"];
    $data['raw_material'][] = ["name"=>"#","sortable"=>"FALSE","textAlign"=>"center"]; 
    $data['raw_material'][] = ["name"=>"CAT No."];
    $data['raw_material'][] = ["name"=>"Item Name"];
    $data['raw_material'][] = ["name"=>"Category Name"];
    $data['raw_material'][] = ["name"=>"Unit"];
    $data['raw_material'][] = ["name"=>"Make"];
    $data['raw_material'][] = ["name"=>"Defual Disc. (%)"];
    $data['raw_material'][] = ["name"=>"Price"];

    /* Consumable Header */
    $data['consumable'][] = ["name"=>"Action","sortable"=>"FALSE","textAlign"=>"center"];
    $data['consumable'][] = ["name"=>"#","sortable"=>"FALSE","textAlign"=>"center"]; 
    $data['consumable'][] = ["name"=>"CAT No."];
    $data['consumable'][] = ["name"=>"Item Name"];
    $data['consumable'][] = ["name"=>"Category Name"];
    $data['consumable'][] = ["name"=>"Unit"];
    $data['consumable'][] = ["name"=>"Make"];
    $data['consumable'][] = ["name"=>"Defual Disc. (%)"];
    $data['consumable'][] = ["name"=>"Price"];

    return tableHeader($data[$page]);
}

function getPartyData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : '".$data->party_category_name."'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'modal-xl', 'form_id' : 'edit".$data->party_category_name."', 'title' : 'Update ".$data->party_category_name."'}";

    /* $approvalParam = "{'id' : ".$data->id.", 'modal_id' : 'modal-md', 'form_id' : 'partyApproval', 'title' : 'Party Approval', 'fnEdit' : 'partyApproval', 'fnsave' : 'savePartyApproval'}";
    $approvalButton = '<a class="btn btn-info btn-approval permission-approve" href="javascript:void(0)" datatip="Party Approval" flow="down" onclick="edit('.$approvalParam.');"><i class="fa fa-check" ></i></a>'; */

    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="ti-pencil-alt"></i></a>';
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';

    $gstJsonBtn="";$contactBtn="";
    if($data->party_category == 1):
        $gstParam = "{'postData':{'id' : ".$data->id."}, 'modal_id' : 'modal-lg','button' : 'close', 'form_id' : 'gstDetail', 'title' : 'GST Detail', 'fnedit' : 'gstDetail', 'fnsave' : 'saveGstDetail','js_store_fn' : 'customStore'}";
        $gstJsonBtn = '<a class="btn btn-warning btn-contact permission-modify" href="javascript:void(0)" datatip="GST Detail" flow="down" onclick="edit('.$gstParam.');"><i class="fab fa-google"></i></a>';

        $contactParam = "{'postData':{'id' : ".$data->id."}, 'modal_id' : 'modal-lg','button' : 'close', 'form_id' : 'contactDetail', 'title' : 'Contact Detail', 'fnedit' : 'contactDetail', 'fnsave' : 'saveContactDetail','js_store_fn' : 'customStore'}";
        $contactBtn = '<a class="btn btn-info btn-contact permission-modify" href="javascript:void(0)" datatip="Contact Detail" flow="down" onclick="edit('.$contactParam.');"><i class="fa fa-address-book"></i></a>';
    endif;

    $action = getActionButton($contactBtn.$gstJsonBtn.$editButton.$deleteButton);

    if($data->party_category == 1):
        $responseData = [$action,$data->sr_no,$data->party_name,$data->contact_person,$data->party_mobile,$data->party_code,$data->currency];
    elseif($data->party_category == 2):
        $responseData = [$action,$data->sr_no,$data->party_name,$data->contact_person,$data->party_mobile,$data->party_code];
    else:        
        $responseData = [$action,$data->sr_no,$data->party_name,$data->contact_person,$data->party_mobile,$data->party_address];
    endif;
    return $responseData;
}

function getItemCategoryData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Item Category'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'modal-md', 'form_id' : 'editItemCategory', 'title' : 'Update Item Category'}";

    $editButton=''; $deleteButton='';
	if(!empty($data->ref_id)):
        $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="ti-pencil-alt"></i></a>';
        $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';
    endif;

    $cat_code ='';
	if($data->ref_id ==6 || $data->ref_id == 7):
        $cat_code = (!empty($data->tool_type))?'['.str_pad($data->tool_type,3,'0',STR_PAD_LEFT).'] ':'';
    endif;

    if($data->final_category == 0):
        $data->category_name = $cat_code.'<a href="' . base_url("itemCategory/list/" . $data->id) . '">' . $data->category_name . '</a>';
    else:
        $data->category_name = $cat_code.$data->category_name;
    endif;

    $action = getActionButton($editButton.$deleteButton);

    return [$action,$data->sr_no,$data->category_name,$data->parent_category_name,$data->is_final_text,$data->stock_type_text,$data->is_returnable_text,$data->remark];
}

function getBrandData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Make'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'modal-md', 'form_id' : 'editBrand', 'title' : 'Update Make'}";

    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="ti-pencil-alt"></i></a>';
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';

    $action = getActionButton($editButton.$deleteButton);

    return [$action,$data->sr_no,$data->brand_name,$data->remark];
}

function getProductData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : '".$data->item_type_text."'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'modal-xl', 'form_id' : 'editItem', 'title' : 'Update ".$data->item_type_text."'}";

    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="ti-pencil-alt"></i></a>';
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';

    $action = getActionButton($editButton.$deleteButton);

    return [$action,$data->sr_no,$data->item_code,$data->item_name,$data->category_name,$data->unit_name,$data->make_brand,floatVal($data->defualt_disc),floatVal($data->price)];
}