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
                                        <button onclick="statusTab('fabAssembelyTable','32/33/2');" class="nav-tab btn waves-effect waves-light btn-outline-danger active" id="fab_ass_pending" style="outline:0px" data-toggle="tab" aria-expanded="false">Pending</button> 
                                    </li>
                                    <li class="nav-item">
                                        <button onclick="statusTab('fabAssembelyTable','33/33/1');" class="nav-tab btn waves-effect waves-light btn-outline-warning" id="fab_ass_inprocess" style="outline:0px" data-toggle="tab" aria-expanded="false">In-Process</button>
                                    </li>
                                    <li class="nav-item">
                                        <button onclick="statusTab('fabAssembelyTable','33/33/2');" class="nav-tab btn waves-effect waves-light btn-outline-success" id="fab_ass_completed" style="outline:0px" data-toggle="tab" aria-expanded="false">Completed</button>
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
                            <table id='fabAssembelyTable' class="table table-bordered ssTable ssTable-cf" data-ninput="[0,1,6,7,8,9]"  data-url='/getDTRows/32/33/2' data-default_url="/getDTRows" data-default_status="32/33/2"></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div>
<?php $this->load->view('includes/footer'); ?>
