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
                                        <button onclick="statusTab('salesOrderTable',0);" id="pending_so" class="nav-tab btn waves-effect waves-light btn-outline-danger active" style="outline:0px" data-toggle="tab" aria-expanded="false">Pending</button> 
                                    </li>
                                    <li class="nav-item"> 
                                        <button onclick="statusTab('salesOrderTable',1);" id="bom_so" class="nav-tab btn waves-effect waves-light btn-outline-warning" style="outline:0px" data-toggle="tab" aria-expanded="false">In-Process</button> 
                                    </li>
                                    <li class="nav-item"> 
                                        <button onclick="statusTab('salesOrderTable',2);" id="complete_so" class="nav-tab btn waves-effect waves-light btn-outline-success" style="outline:0px" data-toggle="tab" aria-expanded="false">Completed</button> 
                                    </li>
                                    <li class="nav-item"> 
                                        <button onclick="statusTab('salesOrderTable',3);" id="canceled_so" class="nav-tab btn waves-effect waves-light btn-outline-dark" style="outline:0px" data-toggle="tab" aria-expanded="false">Canceled</button> 
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-3 text-center">
                                <h4 class="card-title">Sales Orders</h4>
                            </div>
                            <div class="col-md-4">
                                <a href="javascript:void(0)" class="btn waves-effect waves-light btn-outline-primary float-right permission-write press-add-btn" onclick="window.location.href='<?=base_url($headData->controller.'/addOrder')?>'"><i class="fa fa-plus"></i> Add Order</a>
                            </div>                             
                        </div>                                         
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id='salesOrderTable' class="table table-bordered ssTable  ssTable-cf" data-ninput="[0,1]" data-url='/getDTRows'></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div>
<?php $this->load->view('includes/footer'); ?>