<?php $this->load->view('includes/header'); ?>
<div class="page-wrapper">
	<div class="container-fluid bg-container">
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<div class="row">
							<div class="col-md-4">
								<ul class="nav nav-pills">
									<li class="nav-item"> 
                                        <button onclick="statusTab('purchaseRequestTable',0);" id="PurIndPending" class="nav-tab btn waves-effect waves-light btn-outline-danger active" style="outline:0px" data-toggle="tab" aria-expanded="false">Pending</button> 
                                    </li>
									<li class="nav-item"> 
                                        <button onclick="statusTab('purchaseRequestTable',2);" id="PurIndCompleted" class="nav-tab btn waves-effect waves-light btn-outline-success" style="outline:0px" data-toggle="tab" aria-expanded="false">Completed</button> 
                                    </li>
									<li class="nav-item"> 
                                        <button onclick="statusTab('purchaseRequestTable',3);" id="PurIndClose" class="nav-tab btn waves-effect waves-light btn-outline-dark" style="outline:0px" data-toggle="tab" aria-expanded="false">Close</button> 
                                    </li>
								</ul>
							</div>
							<div class="col-md-4 text-center">
								<h4 class="card-title">Purchase Indent</h4>
							</div>
                            <div class="col-md-4">
							</div>
						</div>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table id='purchaseRequestTable' class="table table-bordered ssTable ssTable-cf" data-ninput="[0,1,2]"  data-url='/getDTRows'></table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php $this->load->view('includes/footer'); ?>
<script>
$(document).ready(function(){
    initBulkCreateButton();
    $(document).on('click','.nav-tab, .nav-tab-refresh',function(){
        initBulkCreateButton();
    });
        
    $(document).on('click', '.BulkRequest', function() {
        if ($(this).attr('id') == "masterSelect") {
            if ($(this).prop('checked') == true) {
                $(".bulkBtn").show();
                $("input[name='ref_id[]']").prop('checked', true);
            } else {
                $(".bulkBtn").hide();
                $("input[name='ref_id[]']").prop('checked', false);
            }
        } else {
            var checkboxLength = $("input[name='ref_id[]']").length;
            var checkedLength = $("input[name='ref_id[]']:checked").length;

            if(checkedLength == 0){
                $("#masterSelect").prop('checked', false);
                $(".bulkBtn").hide();
            }else{
                $(".bulkBtn").show();
                if(checkedLength == checkboxLength){
                    $("#masterSelect").prop('checked', true);
                }else{
                    $("#masterSelect").prop('checked', false);
                }
            }
        }
    });

    $(document).on('click', '.generateBulkPO', function() {
        var ref_id = [];
        $("input[name='ref_id[]']:checked").each(function() {
            ref_id.push(this.value);
        });
        var ids = ref_id.join("~");
        var send_data = {
            ids
        };
        $.confirm({
            title: 'Confirm!',
            content: 'Are you sure want to generate PO?',
            type: 'red',
            buttons: {
                ok: {
                    text: "ok!",
                    btnClass: 'btn waves-effect waves-light btn-outline-success',
                    keys: ['enter'],
                    action: function() {
                        ids = encodeURIComponent(window.btoa(JSON.stringify(ids)));
                        window.open(base_url + 'purchaseOrders/createOrder/' + ids, '_self');
                    }
                },
                cancel: {
                    btnClass: 'btn waves-effect waves-light btn-outline-secondary',
                    action: function() {

                    }
                }
            }
        });
    });

    $(document).on('click','.cancelBulkPO',function(){
        var ref_id = [];
        $("input[name='ref_id[]']:checked").each(function() {
            ref_id.push(this.value);
        });
        var ids = ref_id.join("~");
        var send_data = {
            ids
        };
        $.confirm({
            title: 'Confirm!',
            content: 'Are you sure want to cancel bulk PO?',
            type: 'red',
            buttons: {
                ok: {
                    text: "ok!",
                    btnClass: 'btn waves-effect waves-light btn-outline-success',
                    keys: ['enter'],
                    action: function() {
                        $.ajax({
                            url : base_url + controller + '/cancelBulkPO',
                            type : 'post',
                            data : send_data,
                            dataType : 'json'
                        }).done(function(response){
                            if(response.status==1){
                                initTable();initBulkCreateButton();$("#masterSelect").prop('checked', false);
                                toastr.success(response.message, 'Success', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });
                            }else{
                                if(typeof response.message === "object"){
                                    $(".error").html("");
                                    $.each( response.message, function( key, value ) {$("."+key).html(value);});
                                }else{
                                    initTable();initBulkCreateButton();
                                    toastr.error(response.message, 'Error', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });
                                }			
                            }
                        });
                    }
                },
                cancel: {
                    btnClass: 'btn waves-effect waves-light btn-outline-secondary',
                    action: function() {

                    }
                }
            }
        });
    });
});

function initBulkCreateButton() {
    var bulkPOBtn = '<button class="btn btn-outline-primary bulkBtn generateBulkPO" tabindex="0" aria-controls="purchaseRequestTable" type="button"><span>Generate PO</span></button>';
    $("#purchaseRequestTable_wrapper .dt-buttons").append(bulkPOBtn);
    var bulkCancelBtn = '<button class="btn btn-outline-primary bulkBtn cancelBulkPO ml-1" tabindex="0" aria-controls="purchaseRequestTable" type="button"><span>Cancel PO</span></button>';
    $("#purchaseRequestTable_wrapper .dt-buttons").append(bulkCancelBtn);
    $(".bulkBtn").hide();
}
</script>