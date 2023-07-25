<form id="orderBomForm" data-res_function="resSaveOrderBomItem">
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="trans_child_id" id="trans_child_id" value="<?=$postData->trans_child_id?>">
            <input type="hidden" name="trans_main_id" id="trans_main_id" value="<?=$postData->trans_main_id?>">
            <input type="hidden" name="id" id="id" value="">

            <div class="col-md-4 form-group">
                <label for="item_id">Product Name</label>
                <select name="item_id" id="item_id" class="form-control select2 req itemDetails2" data-res_function="resItemDetail">
                    <option value="">Select Product Name</option>
                    <?=getItemListOption($itemList)?>
                </select>
            </div>

            <div class="col-md-2 form-group">
                <label for="qty">Qty</label>
                <input type="text" name="qty" id="qty" class="form-control floatOnly req" value="">
            </div>

            <div class="col-md-2 form-group">
                <label for="uom">Unit</label>
                <select name="uom" id="uom" class="form-control select2 req">
                    <option value="" selected>--</option>
                    <?php
                        foreach($unitList as $row):
                            $selected = ($row->unit_name == "NOS")?"selected":"";
                            echo '<option value="'.$row->unit_name.'" '.$selected.'>'.$row->unit_name.'</option>';
                        endforeach;
                    ?>
                </select>
            </div>

            <div class="col-md-2 form-group">
                <label for="price">Price</label>
                <input type="text" name="price" id="price" class="form-control floatOnly req" value="0" />
            </div>

            <div class="col-md-2 form-group">
                <label for="disc_per">Disc. (%)</label>
                <input type="text" name="disc_per" id="disc_per" class="form-control floatOnly" value="0">
            </div>              

            <div class="col-md-2 form-group">
                <label for="make">Make</label>
                <select name="make" id="make" class="form-control select2 req">
                    <option value="">Select Make</option>
                    <?php
                        foreach ($brandList as $row) :
                            echo '<option value="' . $row->brand_name . '">' . $row->brand_name . '</option>';
                        endforeach;
                    ?>
                </select>
            </div> 

            <div class="col-md-2">
                <label for="">&nbsp;</label>
                <button type="button" class="btn waves-effect btn-block waves-light btn-outline-success float-right save-form" onclick="customStore({'formId':'orderBomForm','fnsave':'saveOrderBomItem','controller':'production/estimation'});" ><i class="fa fa-check"></i> Save</button>
            </div>
        </div>
    </div>    
</form>
<hr>
<div class="col-md-12">    
    <div class="row">
        <div class="table-responsive">
            <table id="bomItems" class="table table-bordered">
                <thead class="thead-info">
                    <tr>
                        <th>#</th>
                        <th>Material Description</th>
                        <th>Make</th>
                        <th>Cat. No.</th>
                        <th>UOM</th>
                        <th>Total Qty.</th>
                        <th>OTHER MRP</th>
                        <th>OTHER AMOUNT</th>
                        <th>DISC (IN %)</th>
                        <th>FINAL OTHER AMOUNT</th>
                        <th class="text-center">Action</th>
                    </tr>          
                </thead>
                <tbody id="bomItemsDetails">
                    <tr><td colspan="11" class="text-center">No data available in table</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
var bomTrans = {'postData':{'trans_child_id':$("#trans_child_id").val()},'table_id':"bomItems",'tbody_id':'bomItemsDetails','tfoot_id':'','fnget':'getOrderBomHtml','controller':'production/estimation'};
getTransHtml(bomTrans);

function resSaveOrderBomItem(data,formId){
    if(data.status==1){
        $('#'+formId)[0].reset();
        toastr.success(data.message, 'Success', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });

        var bomTrans = {'postData':{'trans_child_id':$("#trans_child_id").val()},'table_id':"bomItems",'tbody_id':'bomItemsDetails','tfoot_id':'','fnget':'getOrderBomHtml','controller':'production/estimation'};
        getTransHtml(bomTrans);   
        initTable();     
    }else{
        if(typeof data.message === "object"){
            $(".error").html("");
            $.each( data.message, function( key, value ) {$("."+key).html(value);});
        }else{
            toastr.error(data.message, 'Error', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });
        }			
    }	
}

function resDeleteBomItem(response){
    if(response.status==0){
        toastr.error(response.message, 'Sorry...!', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });
    }else{
        var bomTrans = {'postData':{'trans_child_id':$("#trans_child_id").val()},'table_id':"bomItems",'tbody_id':'bomItemsDetails','tfoot_id':'','fnget':'getOrderBomHtml','controller':'production/estimation'};
        getTransHtml(bomTrans);
        initTable();

        toastr.success(response.message, 'Success', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });
    }	
}
</script>