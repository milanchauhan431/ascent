<form enctype="multipart/form-data">
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" id="id" value="<?=$id?>">

            <div class="col-md-3 form-group">
                <label for="dispatch_date">Dispatch Date</label>
                <input type="date" name="dispatch_date" id="dispatch_date" class="form-control req" value="<?=getFyDate()?>">
            </div>

            <div class="col-md-3 form-group">
                <label for="vehicle_no">Vehicle No.</label>
                <input type="text" name="vehicle_no" id="vehicle_no" class="form-control" value="">
            </div>

            <div class="col-md-3 form-group">
                <label for="challan_no">Challan No.</label>
                <input type="text" name="challan_no" id="challan_no" class="form-control" value="">
            </div>

            <div class="col-md-3 form-group">
                <label for="invoice_no">Invoice No.</label>
                <input type="text" name="invoice_no" id="invoice_no" class="form-control req" value="">
            </div>

            <div class="col-md-12 form-group">
                <label for="remark">Remark</label>
                <input type="text" name="remark" id="remark" class="form-control" value="">
            </div>

            <div class="col-md-12 form-group">
                <div class="error item_error"></div>
                <div class="table-responsive">
                    <table id="pendingDispatch" class="table table-bordered">
                        <thead class="thead-info">
                            <tr>
                                <th>#</th>
                                <th>Item Name</th>
                                <th>Job No.</th>
                                <th>Order Qty</th>
                                <th>Pending Qty</th>
                                <th>Dispatch Qty</th>
                            </tr>
                        </thead>
                        <tbody id="pendingDispatchList">
                            <tr>
                                <td colspan="6" class="text-center">No data available in table</td>
                            </tr>
                        </tbody>
                    </table>                    
                </div>
            </div>

        </div>        
    </div>
</form>
<hr>
<div class="col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="table table-responsive">
                <table id="dispatched" class="table table-bordered">
                    <thead class="thead-info">
                        <tr>
                            <th>#</th>
                            <th>Item Name</th>
                            <th>Job No.</th>
                            <th>Dispatch Date</th>
                            <th>Vehicle No.</th>
                            <th>Challan No.</th>
                            <th>Invoice No.</th>
                            <th>Dispatch Qty</th>
                            <th>Remark</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="dispatchedList">
                        <tr>
                            <td colspan="10" class="text-center">No data available in table</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function(){
    var pendingTrans = {'postData':{'id':$("#id").val()},'table_id':"pendingDispatch",'tbody_id':'pendingDispatchList','tfoot_id':'','fnget':'getPendingDispatchList'};
    getTransHtml(pendingTrans);

    var completeTrans = {'postData':{'id':$("#id").val()},'table_id':"dispatched",'tbody_id':'dispatchedList','tfoot_id':'','fnget':'getDispatchedList'};
    getTransHtml(completeTrans);
});

function resSaveDispatchDetails(data){
    if(data.status==1){
        initTable();

        var pendingTrans = {'postData':{'id':$("#id").val()},'table_id':"pendingDispatch",'tbody_id':'pendingDispatchList','tfoot_id':'','fnget':'getPendingDispatchList'};
        getTransHtml(pendingTrans);

        var completeTrans = {'postData':{'id':$("#id").val()},'table_id':"dispatched",'tbody_id':'dispatchedList','tfoot_id':'','fnget':'getDispatchedList'};
        getTransHtml(completeTrans);

        toastr.success(data.message, 'Success', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });
    }else{
        if(typeof data.message === "object"){
            $(".error").html("");
            $.each( data.message, function( key, value ) {$("."+key).html(value);});
        }else{
            toastr.error(data.message, 'Error', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });
        }			
    }	
}

function resDeleteDispatchTrans(data){
    if(data.status==1){
        initTable();
        
        var pendingTrans = {'postData':{'id':$("#id").val()},'table_id':"pendingDispatch",'tbody_id':'pendingDispatchList','tfoot_id':'','fnget':'getPendingDispatchList'};
        getTransHtml(pendingTrans);

        var completeTrans = {'postData':{'id':$("#id").val()},'table_id':"dispatched",'tbody_id':'dispatchedList','tfoot_id':'','fnget':'getDispatchedList'};
        getTransHtml(completeTrans);

        toastr.success(data.message, 'Success', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });
    }else{
        if(typeof data.message === "object"){
            $(".error").html("");
            $.each( data.message, function( key, value ) {$("."+key).html(value);});
        }else{
            toastr.error(data.message, 'Error', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });
        }			
    }
}
</script>