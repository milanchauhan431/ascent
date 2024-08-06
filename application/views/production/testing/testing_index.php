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
                                        <button onclick="statusTab('testingTable','38/40/3','getProductionDtHeader','pending_testing',[0,1,7,8]);" class="nav-tab btn waves-effect waves-light btn-outline-danger active" id="powder_coating_pending" style="outline:0px" data-toggle="tab" aria-expanded="false">Pending</button> 
                                    </li>
                                    <li class="nav-item">
                                        <button onclick="statusTab('testingTable','40/40/2','getProductionDtHeader','complete_testing',[0,1,10,11]);" class="nav-tab btn waves-effect waves-light btn-outline-success" id="powder_coating_completed" style="outline:0px" data-toggle="tab" aria-expanded="false">Completed</button>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-8 text-left">
                                <h4 class="card-title"><?=$headData->pageTitle?></h4>
                            </div>                          
                        </div>                                         
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id='testingTable' class="table table-bordered ssTable ssTable-cf" data-ninput="[0,1,7,8]"  data-url='/getDTRows/38/40/3' data-default_url="/getDTRows" data-default_status="38/40/3"></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div>
<?php $this->load->view('includes/footer'); ?>
