<form>
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""?>">

            <div class="col-md-2 form-group">
                <label for="trans_number">Entry No.</label>
                <input type="text" id="trans_number" class="form-control" value="<?=(!empty($dataRow->trans_no))?$dataRow->trans_prefix.$dataRow->trans_no:""?>" readonly>

                <input type="hidden" name="trans_prefix" id="trans_prefix" value="<?=(!empty($dataRow->trans_prefix))?$dataRow->trans_prefix:$trans_prefix?>">
                <input type="hidden" name="trans_no" id="trans_no" value="<?=(!empty($dataRow->trans_no))?$dataRow->trans_no:$trans_no?>">
            </div>

            <div class="col-md-2 form-group">
                <label for="trans_date">Entry Date</label>
                <input type="date" name="trans_date" id="trans_date" class="form-control fyDates" value="<?=(!empty($dataRow->trans_date))?$dataRow->trans_date:""?>" readonly>
            </div>

            <div class="col-md-8 form-group">
                <label for="item_id">Item Name</label>
                <input type="hidden" name="item_id" id="item_id" value="<?=(!empty($dataRow->item_id))?$dataRow->item_id:""?>">
                <div class="input-group">
                    <input type="text" id="item_code" class="form-control" value="<?=(!empty($dataRow->item_code))?$dataRow->item_code:""?>" style="width:30%;" readonly>
                    <input type="text" id="item_name" class="form-control" value="<?=(!empty($dataRow->item_name))?$dataRow->item_name:""?>" style="width:70%;" readonly>
                </div>
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
                                <th>Issue Qty</th>
                                <th style="width:25%;">Return Qty</th>
                            </tr>
                        </thead>
                        <tbody id="itemStockTrans">
                            <?php
                                $batchDetail = (!empty($dataRow->batch_detail))?json_decode($dataRow->batch_detail):[];

                                $i=1;
                                foreach($batchDetail as $row):
                                    echo '<tr id="'.$row->remark.'" data-ind="'.$i.'">
                                        <td>'.$i.'</td>
                                        <td>'.$row->location_name.'</td>
                                        <td>
                                            '.floatval($row->batch_qty).'
                                        </td>
                                        <td>
                                            <input type="hidden" name="batchDetail['.$i.'][location_id]" id="location_id_'.$i.'" value="'.$row->location_id.'">
                                            <input type="hidden" name="batchDetail['.$i.'][batch_no]" id="batch_no_'.$i.'" value="'.$row->batch_no.'">
                                            <input type="hidden" name="batchDetail['.$i.'][location_name]" id="location_name_'.$i.'" value="'.$row->location_name.'">
                                            <input type="hidden" name="batchDetail['.$i.'][batch_qty]" id="batch_qty_'.$i.'" value="'.$row->batch_qty.'">
                                            <input type="text" name="batchDetail['.$i.'][return_qty]" id="return_qty_'.$i.'" class="form-control floatOnly totalQty" value="'.((!empty($row->return_qty))?$row->return_qty:"").'">
                                            <input type="hidden" name="batchDetail['.$i.'][remark]" id="batch_id_'.$i.'" value="'.$row->remark.'">
                                            <input type="hidden" name="batchDetail['.$i.'][batch_stock]" id="batch_stock_'.$i.'" value="'.floatVal($row->batch_stock).'">
                                            <div class="error batch_qty_'.$i.'"></div>
                                        </td>
                                    </tr>';
                                    $i++;
                                endforeach;
                            ?>
                        </tbody>
                        <tfoot  class="thead-info">
                            <tr>
                                <th colspan="3">Total Qty</th>
                                <th>
                                    <input type="text" name="return_qty" id="return_qty" class="form-control" value="<?=(!empty($dataRow->return_qty))?$dataRow->return_qty:""?>" readonly>
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
$(document).ready(function(){
    $(document).on('keyup change','.totalQty',function(){
        var qtyArray = $(".totalQty").map(function () { return $(this).val(); }).get();
        var qtySum = 0;
        $.each(qtyArray, function () { qtySum += parseFloat(this) || 0; });
        $("#return_qty").val(qtySum.toFixed(2));
    });
});
</script>