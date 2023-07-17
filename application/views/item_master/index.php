<?php $this->load->view('includes/header'); ?>
<div class="page-wrapper">
    <div class="container-fluid bg-container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-3">
                                <h4 class="card-title"><?=$this->itemTypes[$item_type]?></h4>
                            </div>
                            <div class="col-md-6 text-center">
                                <div class="input-group">
                                    <input type="file" name="item_excel" id="item_excel" class="form-control-file" style="width: 45% !important;" />
                                    <div class="input-group-append">
                                        <a href="javascript:void(0);" class="btn btn-labeled btn-success bg-success-dark ml-1 importExcel" type="button">
                                            <i class="fa fa-upload"></i>&nbsp;
                                            <span class="btn-label">Upload &nbsp;<i class="fa fa-file-excel"></i></span>
                                        </a>
                                    </div>
                                    <div class="input-group-append">
                                        <a href="javascript:void(0);" class="btn btn-labeled btn-info bg-info-dark mr-1 downloadExcel">
                                            <i class="fa fa-download"></i> 
                                            <span class="btn-label">Download <i class="fa fa-file-excel"></i></span>
                                        </a>
                                    </div>
                                </div>
                                <div class="error importError"></div>
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="btn waves-effect waves-light btn-outline-primary float-right addNew permission-write press-add-btn" data-button="both" data-modal_id="modal-xl" data-function="addItem" data-form_title="Add <?=$this->itemTypes[$item_type]?>" data-postdata='{"item_type" : <?=$item_type?> }' ><i class="fa fa-plus"></i> Add <?=$this->itemTypes[$item_type]?></button>                                
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id='itemTable' class="table table-bordered ssTable ssTable-cf" data-ninput="[0,1]"  data-url='/getDTRows/<?=$item_type?>'></table>
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
    $(document).on('click',".downloadExcel",function(){
        var postData = {item_type : <?=$item_type?>, item_type_name : '<?=$this->itemTypes[$item_type]?>'};
        var url = base_url + controller + '/createExcel/' + encodeURIComponent(window.btoa(JSON.stringify(postData)));
		window.open(url);
    });

    $('body').on('click', '.importExcel', function() {
        $(".importError").html("");
        $(this).attr("disabled", "disabled");
        var fd = new FormData();
        fd.append("item_excel", $("#item_excel")[0].files[0]);
        fd.append("item_type",<?=$item_type?>);
        $.ajax({
            url: base_url + controller + '/importExcel',
            data: fd,
            type: "POST",
            processData: false,
            contentType: false,
            dataType: "json",
        }).done(function(data) {
            if (data.status === 0) {
                $(".importError").html("");
                var error='';
                $.each(data.message, function(key, value) {
                    error+=value+' <br> ';
                });
                $(".importError").html(error);
            } else if (data.status == 1) {
                toastr.success(data.message, 'Success', {
                    "showMethod": "slideDown",
                    "hideMethod": "slideUp",
                    "closeButton": true,
                    positionClass: 'toastr toast-bottom-center',
                    containerId: 'toast-bottom-center',
                    "progressBar": true
                });
                initTable();
            }else{
                toastr.error(data.message, 'Error', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });
            }
         
            $(this).removeAttr("disabled");
            $("#item_excel").val(null);
        });
    });
});
</script>