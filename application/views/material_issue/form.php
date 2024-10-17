<form>
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""?>">
            <input type="hidden" name="trans_status" id="trans_status" value="<?=(!empty($dataRow->trans_status))?$dataRow->trans_status:"1"?>">

            <div class="col-md-3 form-group">
                <label for="trans_number">Entry No.</label>
                <input type="text" id="trans_number" class="form-control" value="<?=(!empty($dataRow->trans_no))?$dataRow->trans_prefix.$dataRow->trans_no:$trans_prefix.$trans_no?>" readonly>

                <input type="hidden" name="trans_prefix" id="trans_prefix" value="<?=(!empty($dataRow->trans_prefix))?$dataRow->trans_prefix:$trans_prefix?>">
                <input type="hidden" name="trans_no" id="trans_no" value="<?=(!empty($dataRow->trans_no))?$dataRow->trans_no:$trans_no?>">
            </div>

            <div class="col-md-3 form-group">
                <label for="trans_date">Entry Date</label>
                <input type="date" name="trans_date" id="trans_date" class="form-control fyDates" value="<?=(!empty($dataRow->trans_date))?$dataRow->trans_date:getFyDate()?>">
            </div>

            <div class="col-md-6 form-group">
                <label for="item_id">Item Name</label>
                <select name="item_id" id="item_id" class="form-control select2 req">
                    <option value="">Select Item</option>
                    <?=getItemListOption($itemList,((!empty($dataRow->item_id))?$dataRow->item_id:""))?>
                </select>
            </div>

            <div class="col-md-3 form-group">
                <label for="req_qty">Req. Qty</label>
                <input type="text" id="req_qty" class="form-control" value="<?=(!empty($dataRow->req_qty))?floatval($dataRow->req_qty):""?>" readonly>
            </div>

            <div class="col-md-3 form-group">
                <label for="collected_by">Collected By</label>
                <input type="text" name="collected_by" id="collected_by" class="form-control" value="<?=(!empty($dataRow->collected_by))?$dataRow->collected_by:""?>">
            </div>

            <div class="col-md-6 form-group">
                <label for="remark">Remark</label>
                <input type="text" name="remark" id="remark" class="form-control" value="<?=(!empty($dataRow->remark))?$dataRow->remark:""?>">
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table id="itemStockDetail" class="table table-bordered">
                        <thead class="thead-info">
                            <tr>
                                <th>#</th>
                                <th>Location</th>
                                <th>Stock Qty</th>
                                <th style="width:25%;">Issue Qty</th>
                            </tr>
                        </thead>
                        <tbody id="itemStockTrans">
                            <tr>
                                <td class="text-center" colspan="4">No data available in table</td>
                            </tr>
                        </tbody>
                        <tfoot  class="thead-info">
                            <tr>
                                <th colspan="3">Total Qty</th>
                                <th>
                                    <input type="text" name="issue_qty" id="issue_qty" class="form-control" value="<?=(!empty($dataRow->issue_qty))?$dataRow->issue_qty:""?>" readonly>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
var batchDetail = '<?php echo (!empty($dataRow->batch_detail))?$dataRow->batch_detail:""; ?>';
$(document).ready(function(){
    setTimeout(function() { $("#item_id").trigger('change'); },200);
    console.log(batchDetail);
    $(document).on('change','#item_id',function(){
        if($("#item_id").val() != ""){
            var stockTrans = {'postData':{'id' : $("#id").val(), 'item_id' : $("#item_id").val(), 'batchDetail' : batchDetail},'table_id':"itemStockDetail",'tbody_id':'itemStockTrans','tfoot_id':'','fnget':'getBatchWiseItemStock'};
            getTransHtml(stockTrans);
        }        
    });

    $(document).on('keyup change','.totalQty',function(){
        var qtyArray = $(".totalQty").map(function () { return $(this).val(); }).get();
        var qtySum = 0;
        $.each(qtyArray, function () { qtySum += parseFloat(this) || 0; });
        $("#issue_qty").val(qtySum.toFixed(2));
    });
});
</script>