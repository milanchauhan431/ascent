<?php $this->load->view('includes/header'); ?>
<div class="page-wrapper">
    <div class="container-fluid bg-container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-5">
                                <ul class="nav nav-pills">
                                    <li class="nav-item"> 
                                        <select id="list_type" class="form-control">
                                            <option value="0">Item Wise</option>
                                            <option value="1">Party Wise</option>
                                        </select> 
                                    </li>
                                    <li class="nav-item"> 
                                        <button onclick="statusTab('purchaseOrderTable',0);" id="pending_po" class="nav-tab btn waves-effect waves-light btn-outline-danger active" style="outline:0px" data-toggle="tab" aria-expanded="false">Pending</button> 
                                    </li>
                                    <li class="nav-item"> 
                                        <button onclick="statusTab('purchaseOrderTable',1);" id="complete_po" class="nav-tab btn waves-effect waves-light btn-outline-success" style="outline:0px" data-toggle="tab" aria-expanded="false">Completed</button> 
                                    </li>
                                    <li class="nav-item"> 
                                        <button onclick="statusTab('purchaseOrderTable',3);" id="canceled_po" class="nav-tab btn waves-effect waves-light btn-outline-dark" style="outline:0px" data-toggle="tab" aria-expanded="false">Canceled</button> 
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-2 text-center">
                                <h4 class="card-title">Purchase Orders</h4>
                            </div>
                            <div class="col-md-5">
                                <a href="javascript:void(0)" onclick="window.location.href='<?=base_url($headData->controller.'/addOrder')?>'" class="btn waves-effect waves-light btn-outline-primary float-right permission-write press-add-btn"><i class="fa fa-plus"></i> Add Order</a>
                            </div>                             
                        </div>                                         
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id='purchaseOrderTable' class="table table-bordered ssTable ssTable-cf" data-ninput=[0,1] data-url='/getDTRows'></table>
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
    $(document).on('change',"#list_type",function(){
        if($(this).val() == 0){
            $("#pending_po").attr("onclick","statusTab('purchaseOrderTable','0/0','getPurchaseDtHeader','poItemWise');");
            $("#complete_po").attr("onclick","statusTab('purchaseOrderTable','1/0','getPurchaseDtHeader','poItemWise');");
            $("#canceled_po").attr("onclick","statusTab('purchaseOrderTable','3/0','getPurchaseDtHeader','poItemWise');");
        }else{
            $("#pending_po").attr("onclick","statusTab('purchaseOrderTable','0/1','getPurchaseDtHeader','purchaseOrders');");
            $("#complete_po").attr("onclick","statusTab('purchaseOrderTable','1/1','getPurchaseDtHeader','purchaseOrders');");
            $("#canceled_po").attr("onclick","statusTab('purchaseOrderTable','3/1','getPurchaseDtHeader','purchaseOrders');");
        }

        $("#pending_po").trigger('click');
    });
});
</script>