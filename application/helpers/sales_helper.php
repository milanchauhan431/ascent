<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

function getSalesDtHeader($page){
    /* Sales Order Header */
    $data['salesOrders'][] = ["name"=>"Action","sortable"=>"FALSE","textAlign"=>"center"];
	$data['salesOrders'][] = ["name"=>"#","sortable"=>"FALSE","textAlign"=>"center"]; 
	$data['salesOrders'][] = ["name"=>"SO. No."];
	$data['salesOrders'][] = ["name"=>"SO. Date"];
	$data['salesOrders'][] = ["name"=>"Job No."];
	$data['salesOrders'][] = ["name"=>"Customer Name"];
	$data['salesOrders'][] = ["name"=>"Item Name"];
    $data['salesOrders'][] = ["name"=>"Order Qty"];
    $data['salesOrders'][] = ["name"=>"Dispatch Qty"];
    $data['salesOrders'][] = ["name"=>"Pending Qty"];

    /* Dispatch Challan Header */
    $data['dispatchChallan'][] = ["name"=>"Action","sortable"=>"FALSE","textAlign"=>"center"];
	$data['dispatchChallan'][] = ["name"=>"#","sortable"=>"FALSE","textAlign"=>"center"]; 
	$data['dispatchChallan'][] = ["name"=>"CHL. No."];
	$data['dispatchChallan'][] = ["name"=>"CHL. Date"];
	$data['dispatchChallan'][] = ["name"=>"Inv. No."];
	$data['dispatchChallan'][] = ["name"=>"Vehicle No."];
	$data['dispatchChallan'][] = ["name"=>"So. No."];
	$data['dispatchChallan'][] = ["name"=>"Job No."];
	$data['dispatchChallan'][] = ["name"=>"Customer Name"];
	$data['dispatchChallan'][] = ["name"=>"Item Name"];
    $data['dispatchChallan'][] = ["name"=>"Qty"];
    $data['dispatchChallan'][] = ["name"=>"Remark"];

    return tableHeader($data[$page]);
}

function getSalesOrderData($data){
    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="'.base_url('salesOrders/edit/'.$data->id).'" datatip="Edit" flow="down" ><i class="ti-pencil-alt"></i></a>';

    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Sales Order'}";
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';

    /* $soBomParam = "{'postData':{'trans_main_id' : ".$data->id.",'trans_child_id':".$data->trans_child_id."},'modal_id' : 'modal-xxl', 'form_id' : 'addOrderBom', 'fnedit':'orderBom', 'fnsave':'saveOrderBom','title' : 'Order Bom','res_function':'resSaveOrderBom','js_store_fn':'customStore'}";
    $soBom = '<a class="btn btn-info btn-delete permission-write" href="javascript:void(0)" onclick="edit('.$soBomParam.');" datatip="SO Bom" flow="down"><i class="fa fa-database"></i></a>'; */

    $viewBomParam = "{'postData':{'trans_child_id':".$data->trans_child_id.",'trans_main_id':'".$data->id."'},'modal_id' : 'modal-xl','controller':'production/estimation','fnedit':'viewOrderBom','title' : 'View Bom [Item Name : ".$data->item_name."]','button':'close'}";
    $viewBom = '<a class="btn btn-primary permission-read" href="javascript:void(0)" onclick="edit('.$viewBomParam.');" datatip="View Item Bom" flow="down"><i class="fa fa-eye"></i></a>';$viewBom = '';

    /* $reqParam = "{'postData':{'trans_child_id':".$data->trans_child_id.",'trans_number':'".$data->trans_number."','item_name':'".$data->item_name."'},'modal_id' : 'modal-xl', 'form_id' : 'addOrderBom', 'fnedit':'purchaseRequest', 'fnsave':'savePurchaseRequest','title' : 'Send Purchase Request'}";
    $reqButton = '<a class="btn btn-info btn-delete permission-write" href="javascript:void(0)" onclick="edit('.$reqParam.');" datatip="Purchase Request" flow="down"><i class="fa fa-paper-plane"></i></a>'; */

    $cancelParam = "{'postData':{'id':".$data->id.",'trans_child_id' : ".$data->trans_child_id."},'fnsave':'cancelSO','message' : 'Are you sure want to cancel this order item ?'}";
    $cancelButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="confirmStore('.$cancelParam.');" datatip="Cancel Item" flow="down"><i class="ti-close"></i></a>';

    if($data->job_status != null):
        $editButton = "";
    endif;

    if(in_array($data->trans_status,[1,3])):
        $cancelButton = $editButton = $deleteButton = "";
    endif;

    $dispatchButton = '';
    if(in_array($data->job_status,[3])):
        $dispatchParam = "{'postData':{'id' : ".$data->id."},'form_id':'dispatchForm','modal_id':'modal-lg','title':'Dispatch [Order No. : ".$data->trans_number."]','fnedit':'dispatch','fnsave':'saveDispatchDetails'}";//,'js_store_fn':'customStore','res_function':'resSaveDispatchDetails'
        $dispatchButton = '<a class="btn btn-dark" href="javascript:void(0)" datatip="Dispatch" flow="down" onclick="edit('.$dispatchParam.');"><i class="fa fa-truck"></i></a>';
    endif;

    $action = getActionButton($dispatchButton.$viewBom.$editButton.$cancelButton);

    return [$action,$data->sr_no,$data->trans_number,$data->trans_date,$data->job_number,$data->party_name,$data->item_name,$data->qty,$data->dispatch_qty,$data->pending_qty];
}

function getDispatchChallanData($data){
    $editParam = "{'postData':{'chl_no' : ".$data->chl_no."},'modal_id' : 'modal-lg', 'form_id' : 'dispatchForm', 'title' : 'Dispatch [Order No. : ".$data->trans_number."]','fnedit':'editDispatch','fnsave':'saveDispatchDetails'}";
    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="ti-pencil-alt"></i></a>';
    
    $deleteParam = "{'postData':{'id' : ".$data->chl_no."},'message' : 'Dispatch Challan'}";
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';

    $action = getActionButton($editButton.$deleteButton);
    return [$action,$data->sr_no,$data->challan_no,$data->dispatch_date,$data->invoice_no,$data->vehicle_no,$data->trans_number,$data->job_number,$data->party_name,$data->item_name,$data->qty,$data->remark];
}
?>