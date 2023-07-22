<form data-confirm_message="Are you sure want to save this Mechanical Design ?">
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
                        <?php
                            $i=1;
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
                                    <td>'.$row->param_name.'</td>
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
                            <td style="width:50%;">HINGE</td>
                            <td style="width:50%;">
                                <input type="hidden" name="transData[<?=$i?>][id]" id="id_<?=$i?>" value="">
                                <input type="hidden" name="transData[<?=$i?>][param_id]" id="param_id_<?=$i?>" value="">
                                <input type="hidden" name="transData[<?=$i?>][param_key]" id="param_key_<?=$i?>" value="hinge">
                                <input type="text" name="transData[<?=$i?>][param_value]" id="param_value_<?=$i?>" class="form-control floatOnly" value="">
                                <?php $i++; ?>
                            </td>
                        </tr>

                        <tr>
                            <td>EARTHING BUSH</td>
                            <td>
                                <input type="hidden" name="transData[<?=$i?>][id]" id="id_<?=$i?>" value="">
                                <input type="hidden" name="transData[<?=$i?>][param_id]" id="param_id_<?=$i?>" value="">
                                <input type="hidden" name="transData[<?=$i?>][param_key]" id="param_key_<?=$i?>" value="earthing_bush">
                                <input type="text" name="transData[<?=$i?>][param_value]" id="param_value_<?=$i?>" class="form-control floatOnly" value="">
                                <?php $i++; ?>
                            </td>
                        </tr>

                        <tr>
                            <td>LIFTING HOOK</td>
                            <td>
                                <input type="hidden" name="transData[<?=$i?>][id]" id="id_<?=$i?>" value="">
                                <input type="hidden" name="transData[<?=$i?>][param_id]" id="param_id_<?=$i?>" value="">
                                <input type="hidden" name="transData[<?=$i?>][param_key]" id="param_key_<?=$i?>" value="lifting_hook">
                                <input type="text" name="transData[<?=$i?>][param_value]" id="param_value_<?=$i?>" class="form-control floatOnly" value="">
                                <?php $i++; ?>
                            </td>
                        </tr>

                        <tr>
                            <td>CUTTING DRAWINGS</td>
                            <td>
                                <input type="hidden" name="transData[<?=$i?>][id]" id="id_<?=$i?>" value="">
                                <input type="hidden" name="transData[<?=$i?>][param_id]" id="param_id_<?=$i?>" value="">
                                <input type="hidden" name="transData[<?=$i?>][param_key]" id="param_key_<?=$i?>" value="cutting_drawings">
                                <input type="file" name="cutting_drawings" id="cutting_drawings" class="form-control-file">
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
});
</script>