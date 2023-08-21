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
                                        <button onclick="statusTab('electricalDesignTable',0);" id="pending_ed" class="nav-tab btn waves-effect waves-light btn-outline-warning" style="outline:0px" data-toggle="tab" aria-expanded="false">In-Process</button> 
                                    </li>
                                    <li class="nav-item"> 
                                        <button onclick="statusTab('electricalDesignTable',3);" id="complete_ed" class="nav-tab btn waves-effect waves-light btn-outline-success" style="outline:0px" data-toggle="tab" aria-expanded="false">Complete</button> 
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-3 text-left">
                                <h4 class="card-title">Electrical Design</h4>
                            </div>
                            <div class="col-md-3">
                            </div>                             
                        </div>                                         
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id='electricalDesignTable' class="table table-bordered ssTable ssTable-cf" data-ninput="[0,1,6,7,8,9]" data-url='/getDTRows' data-default_url="/getDTRows"></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div>

<?php $this->load->view('includes/footer'); ?>