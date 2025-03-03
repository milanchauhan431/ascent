<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

function getPurchaseDtHeader($page){
    /* Purchase Order Header */
    $data['purchaseOrders'][] = ["name"=>"Action","sortable"=>"FALSE","textAlign"=>"center"];
	$data['purchaseOrders'][] = ["name"=>"#","sortable"=>"FALSE","textAlign"=>"center"]; 
	$data['purchaseOrders'][] = ["name"=>"PO. No."];
	$data['purchaseOrders'][] = ["name"=>"PO. Date"];
	$data['purchaseOrders'][] = ["name"=>"Party Name"];
	$data['purchaseOrders'][] = ["name"=>"Created BY"];

    $data['poItemWise'][] = ["name"=>"Action","sortable"=>"FALSE","textAlign"=>"center"];
	$data['poItemWise'][] = ["name"=>"#","sortable"=>"FALSE","textAlign"=>"center"]; 
	$data['poItemWise'][] = ["name"=>"PO. No."];
	$data['poItemWise'][] = ["name"=>"PO. Date"];
	$data['poItemWise'][] = ["name"=>"Party Name"];
	$data['poItemWise'][] = ["name"=>"Job No."];
	$data['poItemWise'][] = ["name"=>"CAT No."];
	$data['poItemWise'][] = ["name"=>"Item Name"];
	$data['poItemWise'][] = ["name"=>"Make"];
	$data['poItemWise'][] = ["name"=>"Price"];
	$data['poItemWise'][] = ["name"=>"Disc. (%)"];
    $data['poItemWise'][] = ["name"=>"Order Qty"];
    $data['poItemWise'][] = ["name"=>"Received Qty"];
    $data['poItemWise'][] = ["name"=>"Pending Qty"];
    $data['poItemWise'][] = ["name"=>"Remark"];

    /* Purchase Indent Header */
    $masterCheckBox = '<input type="checkbox" id="masterSelect" class="filled-in chk-col-success BulkRequest" value=""><label for="masterSelect">ALL</label>';

    $data['purchaseIndent'][] = ["name"=>"Action","sortable"=>"FALSE","textAlign"=>"center"];
	$data['purchaseIndent'][] = ["name"=>"#","sortable"=>"FALSE","textAlign"=>"center"];
    $data['purchaseIndent'][] = ["name"=>$masterCheckBox,"sortable"=>"FALSE","textAlign"=>"center"];
    $data['purchaseIndent'][] = ["name"=>"Indent Date"];
    $data['purchaseIndent'][] = ["name"=>"Indent No"];
    $data['purchaseIndent'][] = ["name"=>"Job No"];
    $data['purchaseIndent'][] = ["name"=>"Make"];
    $data['purchaseIndent'][] = ["name"=>"Item Name"];
    $data['purchaseIndent'][] = ["name"=>"UOM"];   
    $data['purchaseIndent'][] = ["name"=>"Req. Qty"];    
    $data['purchaseIndent'][] = ["name"=>"Remark"]; 
    $data['purchaseIndent'][] = ["name"=>"Status"];

    return tableHeader($data[$page]);
}

function getPurchaseOrderData($data){
    $editButton = '<a class="btn btn-warning btn-edit permission-modify" href="'.base_url('purchaseOrders/edit/'.$data->id).'" datatip="Edit" flow="down" ><i class="ti-pencil-alt"></i></a>';

    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Purchase Order'}";
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';    

    $cancelParam = "{'postData':{'id':".$data->id.",'trans_child_id' : ".$data->trans_child_id."},'fnsave':'cancelPO','message' : 'Are you sure want to cancel this order item ?'}";
    $cancelButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="confirmStore('.$cancelParam.');" datatip="Cancel Item" flow="down"><i class="ti-close"></i></a>';

    $printBtn = '<a class="btn btn-success btn-info permission-approve" href="'.base_url('purchaseOrders/printPO/'.$data->id).'" target="_blank" datatip="Print" flow="down"><i class="fas fa-print" ></i></a>';

    if($data->trans_status > 0): $editButton = $cancelButton = $deleteButton = ""; endif;

    if(empty($data->list_type)):
        $completeButton = '';
        if($data->received_qty > 0 && $data->pending_qty > 0):
            $completeParam = "{'postData':{'id':".$data->id.",'trans_child_id' : ".$data->trans_child_id."},'fnsave':'completePoItem','message' : 'Are you sure want to complete this order item ?'}";
            $completeButton = '<a class="btn btn-success btn-delete permission-remove" href="javascript:void(0)" onclick="confirmStore('.$completeParam.');" datatip="Complete Item" flow="down"><i class="ti-check"></i></a>';
        endif;

        $action = getActionButton($printBtn.$editButton.$completeButton.$cancelButton);

        return [$action,$data->sr_no,$data->trans_number,$data->trans_date,$data->party_name,$data->job_number,$data->item_code,$data->item_name,$data->make,$data->price,$data->disc_per,$data->qty,$data->received_qty,$data->pending_qty,$data->item_remark];
    else:
        $action = getActionButton($printBtn.$editButton);

        return [$action,$data->sr_no,$data->trans_number,$data->trans_date,$data->party_name,$data->created_by_name];
    endif;
}

/* Purchase Request Data  */
function getPurchaseIndentData($data){
    $approveReq=""; $closeReq="";

    $closeParam = "{'postData':{'id':".$data->id.",'order_status':3},'message':'Are you sure want to Close this Request?','fnsave':'changeRequestStatus'}";
    $closeReq = '<a href="javascript:void(0)" class="btn btn-dark closePreq permission-modify" onclick="confirmStore('.$closeParam.');" data-msg="Close" datatip="Close Purchase Request" flow="down" ><i class="ti-close"></i></a>';

    $selectBox ="";
    if($data->order_status ==0):
        $approveParam = "{'postData':{'id':".$data->id.",'order_status':1},'message':'Are you sure want to Approve this Request?','fnsave':'changeRequestStatus'}";
        $approveReq = '<a href="javascript:void(0)" class="btn btn-facebook permission-modify" onclick="confirmStore('.$approveParam.');" data-msg="Approve" datatip="Approve Purchase Request" flow="down" ><i class="fa fa-check"></i></a>';
    elseif($data->order_status == 1):
        $selectBox = '<input type="checkbox" name="ref_id[]" id="ref_id_'.$data->sr_no.'" class="filled-in chk-col-success BulkRequest" value="'.$data->id.'"><label for="ref_id_'.$data->sr_no.'"></label>';
    else:
        $closeReq="";
    endif;

    $action = getActionButton($approveReq.$closeReq);

    return [$action,$data->sr_no,$selectBox,(!empty($data->req_date))?date("d-m-Y",strtotime($data->req_date)):"",sprintf("IND%03d",$data->req_no),$data->job_number,$data->make,$data->item_name,$data->uom,$data->req_qty,$data->remark,$data->order_status_label];
   
}

?>