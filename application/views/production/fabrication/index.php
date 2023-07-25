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
                                        <button onclick="statusTab('fabricationTable','27/30/1');" class="nav-tab btn waves-effect waves-light btn-outline-danger active" id="fab_pending" style="outline:0px" data-toggle="tab" aria-expanded="false">Pending</button> 
                                    </li>
                                    <li class="nav-item">
                                        <button onclick="statusTab('fabricationTable','30/30/1');" class="nav-tab btn waves-effect waves-light btn-outline-warning" id="fab_inprocess" style="outline:0px" data-toggle="tab" aria-expanded="false">In-Process</button>
                                    </li>
                                    <li class="nav-item">
                                        <button onclick="statusTab('fabricationTable','30/30/2');" class="nav-tab btn waves-effect waves-light btn-outline-success" id="fab_completed" style="outline:0px" data-toggle="tab" aria-expanded="false">Completed</button>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4 text-center">
                                <h4 class="card-title"><?=$headData->pageTitle?></h4>
                            </div>
                            <div class="col-md-4">
                                <!-- <button type="button" class="btn waves-effect waves-light btn-outline-primary float-right permission-write addNew press-add-btn" data-button="both" data-modal_id="modal-xl" data-function="addGateInward" data-form_title="Gate Inward"><i class="fa fa-plus"></i> Add GI</button> -->
                            </div>                             
                        </div>                                         
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id='fabricationTable' class="table table-bordered ssTable ssTable-cf" data-ninput="[0,1,6,7]"  data-url='/getDTRows' data-default_status="27/30/0"></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div>
<?php $this->load->view('includes/footer'); ?>
