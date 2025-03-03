<form enctype="multipart/form-data">
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="ref_id" id="ref_id" value="<?=$pm_id?>">
            <input type="hidden" name="main_pm_id" id="main_pm_id" value="<?=$pm_id?>">
            <input type="hidden" name="entry_type" id="entry_type" value="<?=$entry_type?>">

            <div class="col-md-12 form-group">
                <div class="input-group">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input multifiles" name="attachments[]" id="attachments" accept=".jpg, .png, .jpeg, .pdf, .xlsx, .xls" multiple>
                        <label class="custom-file-label" for="attachments">Choose file</label>
                    </div>
                </div>
                <div class="error attachments"></div>
            </div>

        </div>        
    </div>
</form>
<hr>
<div class="col-md-12">
    <div class="row">
        <div class="col-md-12 form-group">
            <?php $deleteParam = "{'postData':{'ref_id' : ".$pm_id.",'entry_type':".$entry_type."},'message' : 'File','res_function':'resDeleteFile','fndelete':'deleteAllElectricalDesignFile'}"; ?>
            <button type="button" class="btn btn-outline-danger float-right" onclick="trash(<?=$deleteParam?>);">Remove All Files</button>
        </div>
        <div class="col-md-12">
            <div class="table table-responsive">
                <table id="designFiles" class="table table-bordered">
                    <thead class="thead-info">
                        <tr>
                            <th class="text-center">File</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody id="designFilesList">
                        <tr>
                            <td colspan="2" class="text-center">No data available in table</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function(){
    var filesTrans = {'postData':{'pm_id':$("#main_pm_id").val(),'entry_type':$("#entry_type").val()},'table_id':"designFiles",'tbody_id':'designFilesList','tfoot_id':'','fnget':'getElectricalDesignFilesHtml'};
    getTransHtml(filesTrans);
});

function resSaveElectricalDesign(data){
    if(data.status==1){
        initTable();

        var filesTrans = {'postData':{'pm_id':$("#main_pm_id").val(),'entry_type':$("#entry_type").val()},'table_id':"designFiles",'tbody_id':'designFilesList','tfoot_id':'','fnget':'getElectricalDesignFilesHtml'};
        getTransHtml(filesTrans);

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

function resDeleteFile(data){
    if(data.status==1){
        initTable();

        var filesTrans = {'postData':{'pm_id':$("#main_pm_id").val(),'entry_type':$("#entry_type").val()},'table_id':"designFiles",'tbody_id':'designFilesList','tfoot_id':'','fnget':'getElectricalDesignFilesHtml'};
        getTransHtml(filesTrans);

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