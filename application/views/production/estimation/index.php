<?php $this->load->view('includes/header'); ?>
<div class="page-wrapper">
    <div class="container-fluid bg-container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="nav nav-pills">
                                    <li class="nav-item"> 
                                        <button onclick="statusTab('estimationTable',-1);" id="order_list" class="nav-tab btn waves-effect waves-light btn-outline-danger active" style="outline:0px" data-toggle="tab" aria-expanded="false">Order List</button> 
                                    </li>
                                    <li class="nav-item"> 
                                        <button onclick="statusTab('estimationTable',0);" id="pending_job" class="nav-tab btn waves-effect waves-light btn-outline-info" style="outline:0px" data-toggle="tab" aria-expanded="false">Pending Job</button> 
                                    </li>
                                    <li class="nav-item"> 
                                        <button onclick="statusTab('estimationTable',1);" id="in_process_job" class="nav-tab btn waves-effect waves-light btn-outline-warning" style="outline:0px" data-toggle="tab" aria-expanded="false">In-Process Job</button> 
                                    </li>
                                    <li class="nav-item"> 
                                        <button onclick="statusTab('estimationTable',3);" id="complete_job" class="nav-tab btn waves-effect waves-light btn-outline-success" style="outline:0px" data-toggle="tab" aria-expanded="false">Complete Job</button> 
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-3 text-left">
                                <h4 class="card-title">Estimation & Design</h4>
                            </div>
                            <div class="col-md-3">
                            </div>                             
                        </div>                                         
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id='estimationTable' class="table table-bordered ssTable ssTable-cf" data-ninput="[0,1]" data-url='/getEstimationDTRows/-1' data-default_url="/getEstimationDTRows"></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div>

<?php $this->load->view('includes/footer'); ?>