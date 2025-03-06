<form data-confirm_message="Are you sure want to save this Powder Coating ?">
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="entry_type" id="entry_type" value="<?=(!empty($dataRow->entry_type))?$dataRow->entry_type:""?>">
            <input type="hidden" name="ref_id" id="ref_id" value="<?=(!empty($dataRow->ref_id))?$dataRow->ref_id:""?>">
            <input type="hidden" name="pm_id" id="pm_id" value="<?=(!empty($dataRow->pm_id))?$dataRow->pm_id:""?>">

            <div class="table table-responsive">
                <table class="table table-borderless">
                    <thead class="thead-info">
                        <th style="width:50%;">Part Name</th>
                        <th style="width:50%;">Qty.</th>
                    </thead>
                    <tbody>
                        <?php $i=1; ?>
                        <tr>
                            <?php
                                $panelQty = 1;
                                if(!empty($fabAssemblyData)):
                                    $filteredObjects = array_filter($fabAssemblyData, fn($object) => $object->param_key === "panel_qty");
                                    $filteredObjects = array_values($filteredObjects);
                                    $panelQty = $filteredObjects[0]->param_value;
                                endif;
                            ?>
                            <td style="width:50%;">PANEL QTY <span class="text-danger">*</span></td>
                            <td style="width:50%;">
                                <input type="hidden" name="transData[<?=$i?>][id]" id="id_<?=$i?>" value="">
                                <input type="hidden" name="transData[<?=$i?>][param_id]" id="param_id_<?=$i?>" value="">
                                <input type="hidden" name="transData[<?=$i?>][param_key]" id="param_key_<?=$i?>" value="panel_qty">
                                <input type="text" name="transData[<?=$i?>][param_value]" id="param_value_<?=$i?>" class="form-control floatOnly panel_qty" value="<?=$panelQty?>">
                                <?php $i++; ?>
                            </td>
                        </tr>
                        <?php
                            foreach($parameterList as $row):
                                $param_key = preg_replace('/[^A-Za-z0-9]+/', '_', strtolower($row->param_name));
                                $claculationClass = $inputClass = "";

                                if($row->input_type == 1):
                                    $claculationClass = "totalQty";
                                    $inputClass = "numericOnly";
                                elseif($row->input_type == 2):
                                    $claculationClass = "totalQty";
                                    $inputClass = "floatOnly";
                                endif;

                                $param_value = "";
                                if(!empty($fabAssemblyData)):
                                    $filteredObjects = array_filter($fabAssemblyData, fn($object) => $object->param_key === $param_key);
                                    $filteredObjects = array_values($filteredObjects);
                                    $param_value = $filteredObjects[0]->param_value;
                                endif;

                                echo '<tr>
                                    <td>'.$row->param_name.' </td>
                                    <td>
                                        <input type="hidden" name="transData['.$i.'][id]" id="id_'.$i.'" value="">
                                        <input type="hidden" name="transData['.$i.'][param_id]" id="param_id_'.$i.'" value="'.$row->id.'">
                                        <input type="hidden" name="transData['.$i.'][param_key]" id="param_key_'.$i.'" value="'.$param_key.'">
                                        <input type="text" name="transData['.$i.'][param_value]" id="param_value_'.$i.'" class="form-control '.$claculationClass.' '. $inputClass.'" value="'.$param_value.'">
                                    </td>
                                </tr>';
                                $i++;
                            endforeach;
                        ?>
                    </tbody>
                    <tfoot class="thead-info">
                        <th>TOTAL OF PARTS</th>
                        <th>
                            <input type="hidden" name="transData[<?=$i?>][id]" id="id_<?=$i?>" value="">
                            <input type="hidden" name="transData[<?=$i?>][param_id]" id="param_id_<?=$i?>" value="">
                            <input type="hidden" name="transData[<?=$i?>][param_key]" id="param_key_<?=$i?>" value="total_parts">
                            <input type="text" name="transData[<?=$i?>][param_value]" id="param_value_<?=$i?>" class="form-control totalParts" value="" readonly>
                            <?php $i++; ?>
                        </th>
                    </tfoot>                    
                </table>
                <table class="table table-borderless">
                    <tbody>
                        <?php
                            $options = getItemListOption($itemList);
                        ?>
                        <tr>
                            <td style="width:50%;">DISCRIPTION OF POWDER </td>
                            <td style="width:50%;">
                                <input type="hidden" name="transData[<?=$i?>][id]" id="id_<?=$i?>" value="">
                                <input type="hidden" name="transData[<?=$i?>][param_id]" id="param_id_<?=$i?>" value="">
                                <input type="hidden" name="transData[<?=$i?>][param_key]" id="param_key_<?=$i?>" value="discription_of_powder">

                                <select name="transData[<?=$i?>][param_value]" id="param_value_<?=$i?>" class="form-control select2">
                                    <option value="">Select Item</option>
                                    <?=$options?>
                                </select>
                                <?php $i++; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>POWDER QTY. </td>
                            <td>
                                <input type="hidden" name="transData[<?=$i?>][id]" id="id_<?=$i?>" value="">
                                <input type="hidden" name="transData[<?=$i?>][param_id]" id="param_id_<?=$i?>" value="">
                                <input type="hidden" name="transData[<?=$i?>][param_key]" id="param_key_<?=$i?>" value="powder_qty">
                                <input type="text" name="transData[<?=$i?>][param_value]" id="param_value_<?=$i?>" class="form-control floatOnly" value="">
                                <?php $i++; ?>
                            </td>
                        </tr>

                        <tr>
                            <td style="width:50%;">DISCRIPTION OF POWDER 2</td>
                            <td style="width:50%;">
                                <input type="hidden" name="transData[<?=$i?>][id]" id="id_<?=$i?>" value="">
                                <input type="hidden" name="transData[<?=$i?>][param_id]" id="param_id_<?=$i?>" value="">
                                <input type="hidden" name="transData[<?=$i?>][param_key]" id="param_key_<?=$i?>" value="discription_of_powder_2">

                                <select name="transData[<?=$i?>][param_value]" id="param_value_<?=$i?>" class="form-control select2">
                                    <option value="">Select Item</option>
                                    <?=$options?>
                                </select>
                                <?php $i++; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>POWDER QTY. 2</td>
                            <td>
                                <input type="hidden" name="transData[<?=$i?>][id]" id="id_<?=$i?>" value="">
                                <input type="hidden" name="transData[<?=$i?>][param_id]" id="param_id_<?=$i?>" value="">
                                <input type="hidden" name="transData[<?=$i?>][param_key]" id="param_key_<?=$i?>" value="powder_qty_2">
                                <input type="text" name="transData[<?=$i?>][param_value]" id="param_value_<?=$i?>" class="form-control floatOnly" value="">
                                <?php $i++; ?>
                            </td>
                        </tr>

                        <tr>
                            <td style="width:50%;">DISCRIPTION OF POWDER 3</td>
                            <td style="width:50%;">
                                <input type="hidden" name="transData[<?=$i?>][id]" id="id_<?=$i?>" value="">
                                <input type="hidden" name="transData[<?=$i?>][param_id]" id="param_id_<?=$i?>" value="">
                                <input type="hidden" name="transData[<?=$i?>][param_key]" id="param_key_<?=$i?>" value="discription_of_powder_3">

                                <select name="transData[<?=$i?>][param_value]" id="param_value_<?=$i?>" class="form-control select2">
                                    <option value="">Select Item</option>
                                    <?=$options?>
                                </select>
                                <?php $i++; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>POWDER QTY. 3</td>
                            <td>
                                <input type="hidden" name="transData[<?=$i?>][id]" id="id_<?=$i?>" value="">
                                <input type="hidden" name="transData[<?=$i?>][param_id]" id="param_id_<?=$i?>" value="">
                                <input type="hidden" name="transData[<?=$i?>][param_key]" id="param_key_<?=$i?>" value="powder_qty_3">
                                <input type="text" name="transData[<?=$i?>][param_value]" id="param_value_<?=$i?>" class="form-control floatOnly" value="">
                                <?php $i++; ?>
                            </td>
                        </tr>

                        <tr>
                            <td style="width:50%;">DISCRIPTION OF POWDER 4</td>
                            <td style="width:50%;">
                                <input type="hidden" name="transData[<?=$i?>][id]" id="id_<?=$i?>" value="">
                                <input type="hidden" name="transData[<?=$i?>][param_id]" id="param_id_<?=$i?>" value="">
                                <input type="hidden" name="transData[<?=$i?>][param_key]" id="param_key_<?=$i?>" value="discription_of_powder_4">

                                <select name="transData[<?=$i?>][param_value]" id="param_value_<?=$i?>" class="form-control select2">
                                    <option value="">Select Item</option>
                                    <?=$options?>
                                </select>
                                <?php $i++; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>POWDER QTY. 4</td>
                            <td>
                                <input type="hidden" name="transData[<?=$i?>][id]" id="id_<?=$i?>" value="">
                                <input type="hidden" name="transData[<?=$i?>][param_id]" id="param_id_<?=$i?>" value="">
                                <input type="hidden" name="transData[<?=$i?>][param_key]" id="param_key_<?=$i?>" value="powder_qty_4">
                                <input type="text" name="transData[<?=$i?>][param_value]" id="param_value_<?=$i?>" class="form-control floatOnly" value="">
                                <?php $i++; ?>
                            </td>
                        </tr>

                        <tr>
                            <td style="width:50%;">DISCRIPTION OF POWDER 5</td>
                            <td style="width:50%;">
                                <input type="hidden" name="transData[<?=$i?>][id]" id="id_<?=$i?>" value="">
                                <input type="hidden" name="transData[<?=$i?>][param_id]" id="param_id_<?=$i?>" value="">
                                <input type="hidden" name="transData[<?=$i?>][param_key]" id="param_key_<?=$i?>" value="discription_of_powder_5">

                                <select name="transData[<?=$i?>][param_value]" id="param_value_<?=$i?>" class="form-control select2">
                                    <option value="">Select Item</option>
                                    <?=$options?>
                                </select>
                                <?php $i++; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>POWDER QTY. 5</td>
                            <td>
                                <input type="hidden" name="transData[<?=$i?>][id]" id="id_<?=$i?>" value="">
                                <input type="hidden" name="transData[<?=$i?>][param_id]" id="param_id_<?=$i?>" value="">
                                <input type="hidden" name="transData[<?=$i?>][param_key]" id="param_key_<?=$i?>" value="powder_qty_5">
                                <input type="text" name="transData[<?=$i?>][param_value]" id="param_value_<?=$i?>" class="form-control floatOnly" value="">
                                <?php $i++; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>

<script>
$(document).ready(function(){
    setTimeout(function(){ $('.totalQty').trigger('change'); },500);
    $(document).on('keyup change','.totalQty',function(){
        var qtyArray = $(".totalQty").map(function () { return $(this).val(); }).get();
        var qtySum = 0;
        $.each(qtyArray, function () { qtySum += parseFloat(this) || 0; });
        $(".totalParts").val(qtySum.toFixed(2));
    });
});
</script>