<form data-confirm_message="Are you sure want to save this Fab. Assembly ?">
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
                            <td style="width:50%;">PANEL QTY <span class="text-danger">*</span></td>
                            <td style="width:50%;">
                                <input type="hidden" name="transData[<?=$i?>][id]" id="id_<?=$i?>" value="">
                                <input type="hidden" name="transData[<?=$i?>][param_id]" id="param_id_<?=$i?>" value="">
                                <input type="hidden" name="transData[<?=$i?>][param_key]" id="param_key_<?=$i?>" value="panel_qty">
                                <input type="text" name="transData[<?=$i?>][param_value]" id="param_value_<?=$i?>" class="form-control floatOnly panel_qty" value="1">
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

                                echo '<tr>
                                    <td>'.$row->param_name.' <span class="text-danger">*</span></td>
                                    <td>
                                        <input type="hidden" name="transData['.$i.'][id]" id="id_'.$i.'" value="">
                                        <input type="hidden" name="transData['.$i.'][param_id]" id="param_id_'.$i.'" value="'.$row->id.'">
                                        <input type="hidden" name="transData['.$i.'][param_key]" id="param_key_'.$i.'" value="'.$param_key.'">
                                        <input type="text" name="transData['.$i.'][param_value]" id="param_value_'.$i.'" class="form-control '.$claculationClass.' '. $inputClass.'" value="">
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
                        <tr>
                            <td style="width:50%;">WEIGHT OF PER PANEL (KG) <span class="text-danger">*</span></td>
                            <td style="width:50%;">
                                <input type="hidden" name="transData[<?=$i?>][id]" id="id_<?=$i?>" value="">
                                <input type="hidden" name="transData[<?=$i?>][param_id]" id="param_id_<?=$i?>" value="">
                                <input type="hidden" name="transData[<?=$i?>][param_key]" id="param_key_<?=$i?>" value="weight_of_per_panel">
                                <input type="text" name="transData[<?=$i?>][param_value]" id="param_value_<?=$i?>" class="form-control floatOnly weight_of_per_panel" value="">
                                <?php $i++; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>WEIGHT OF BASE PER PANEL (KG) <span class="text-danger">*</span></td>
                            <td>
                                <input type="hidden" name="transData[<?=$i?>][id]" id="id_<?=$i?>" value="">
                                <input type="hidden" name="transData[<?=$i?>][param_id]" id="param_id_<?=$i?>" value="">
                                <input type="hidden" name="transData[<?=$i?>][param_key]" id="param_key_<?=$i?>" value="weight_of_base_per_panel">
                                <input type="text" name="transData[<?=$i?>][param_value]" id="param_value_<?=$i?>" class="form-control floatOnly weight_of_base_per_panel" value="">
                                <?php $i++; ?>
                            </td>
                        </tr>
                        <tr class="thead-info">
                            <th>TOTAL WEIGHT OF PANEL (KG)</th>
                            <th>
                                <input type="hidden" name="transData[<?=$i?>][id]" id="id_<?=$i?>" value="">
                                <input type="hidden" name="transData[<?=$i?>][param_id]" id="param_id_<?=$i?>" value="">
                                <input type="hidden" name="transData[<?=$i?>][param_key]" id="param_key_<?=$i?>" value="total_weight_of_panel">
                                <input type="text" name="transData[<?=$i?>][param_value]" id="param_value_<?=$i?>" class="form-control floatOnly total_weight_of_panel" value="" readonly>
                                <?php $i++; ?>
                            </th>
                        </tr>
                        <tr>
                            <td>FAB. QUALITY CHECK AS PER QC CHECK LIST <span class="text-danger">*</span></td>
                            <td>
                                <input type="hidden" name="transData[<?=$i?>][id]" id="id_<?=$i?>" value="">
                                <input type="hidden" name="transData[<?=$i?>][param_id]" id="param_id_<?=$i?>" value="">
                                <input type="hidden" name="transData[<?=$i?>][param_key]" id="param_key_<?=$i?>" value="fab_quality_check_as_per_qc_check_list">
                                <select name="transData[<?=$i?>][param_value]" id="param_value_<?=$i?>" class="form-control">
                                    <option value="NO">NO</option>
                                    <option value="YES">YES</option>
                                </select>
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
    $(document).on('keyup change','.totalQty',function(){
        var qtyArray = $(".totalQty").map(function () { return $(this).val(); }).get();
        var qtySum = 0;
        $.each(qtyArray, function () { qtySum += parseFloat(this) || 0; });
        $(".totalParts").val(qtySum.toFixed(2));
    });

    $(document).on('keyup change','.panel_qty,.weight_of_per_panel,.weight_of_base_per_panel',function(){
        var panel_qty = $(".panel_qty").val() || 0;
        var weight_of_per_panel = $(".weight_of_per_panel").val() || 0;
        var weight_of_base_per_panel = $(".weight_of_base_per_panel").val() || 0;

        var total_weight_of_panel = parseFloat((parseFloat(weight_of_per_panel) + parseFloat(weight_of_base_per_panel)) * parseFloat(panel_qty)).toFixed(3);

        $(".total_weight_of_panel").val(total_weight_of_panel);
    });
});
</script>