<div class="col-md-12">
    <input type="hidden" id="trans_child_id" value="<?=$postData->trans_child_id?>">
    <div class="row">
        <div class="table-responsive">
            <table id="prodBomItems" class="table table-bordered">
                <thead class="thead-info">
                    <tr>
                        <th>#</th>
                        <th>Material Description</th>
                        <th>Make</th>
                        <th>Cat. No.</th>
                        <th>UOM</th>
                        <th>Total Qty.</th>
                    </tr>          
                </thead>
                <tbody id="prodBomItemsDetails">
                    <tr><td colspan="6" class="text-center">No data available in table</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
var bomTrans = {'postData':{'trans_child_id':$("#trans_child_id").val()},'table_id':"prodBomItems",'tbody_id':'prodBomItemsDetails','tfoot_id':'','fnget':'getProductionBomHtml','controller':'production/estimation'};
getTransHtml(bomTrans);

function resDeleteBomItem(response){
    if(response.status==0){
        toastr.error(response.message, 'Sorry...!', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });
    }else{
        var bomTrans = {'postData':{'trans_child_id':$("#trans_child_id").val()},'table_id':"prodBomItems",'tbody_id':'prodBomItemsDetails','tfoot_id':'','fnget':'getProductionBomHtml','controller':'production/estimation'};
        getTransHtml(bomTrans);

        toastr.success(response.message, 'Success', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });
    }	
}
</script>