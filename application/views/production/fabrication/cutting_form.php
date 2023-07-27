<form>
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
                                <input type="text" name="transData[<?=$i?>][param_value]" id="param_value_<?=$i?>" class="form-control floatOnly" value="1">
                                <?php $i++; ?>
                            </td>
                        </tr>

                        <tr>
                            <td style="width:50%;">CUTTING METER</td>
                            <td style="width:50%;">
                                <input type="hidden" name="transData[<?=$i?>][id]" id="id_<?=$i?>" value="">
                                <input type="hidden" name="transData[<?=$i?>][param_id]" id="param_id_<?=$i?>" value="">
                                <input type="hidden" name="transData[<?=$i?>][param_key]" id="param_key_<?=$i?>" value="cutting_meter">
                                <input type="text" name="transData[<?=$i?>][param_value]" id="param_value_<?=$i?>" class="form-control floatOnly" value="">
                                <?php $i++; ?>
                            </td>
                        </tr>

                        <tr>
                            <td style="width:50%;">CUTTING PIERCING</td>
                            <td style="width:50%;">
                                <input type="hidden" name="transData[<?=$i?>][id]" id="id_<?=$i?>" value="">
                                <input type="hidden" name="transData[<?=$i?>][param_id]" id="param_id_<?=$i?>" value="">
                                <input type="hidden" name="transData[<?=$i?>][param_key]" id="param_key_<?=$i?>" value="cutting_piercing">
                                <input type="text" name="transData[<?=$i?>][param_value]" id="param_value_<?=$i?>" class="form-control floatOnly" value="">
                                <?php $i++; ?>
                            </td>
                        </tr>

                        <tr>
                            <td style="width:50%;">NESTATED PART SHEET UTILIZATION</td>
                            <td style="width:50%;">
                                <input type="hidden" name="transData[<?=$i?>][id]" id="id_<?=$i?>" value="">
                                <input type="hidden" name="transData[<?=$i?>][param_id]" id="param_id_<?=$i?>" value="">
                                <input type="hidden" name="transData[<?=$i?>][param_key]" id="param_key_<?=$i?>" value="nestated_part_sheet_utilization">
                                <input type="text" name="transData[<?=$i?>][param_value]" id="param_value_<?=$i?>" class="form-control floatOnly" value="">
                                <?php $i++; ?>
                            </td>
                        </tr>

                        <tr>
                            <td style="width:50%;">DISCRIPTION OF SHEET <span class="text-danger">*</span></td>
                            <td style="width:50%;">
                                <input type="hidden" name="transData[<?=$i?>][id]" id="id_<?=$i?>" value="">
                                <input type="hidden" name="transData[<?=$i?>][param_id]" id="param_id_<?=$i?>" value="">
                                <input type="hidden" name="transData[<?=$i?>][param_key]" id="param_key_<?=$i?>" value="discription_of_sheet">
                                                                
                                <select name="transData[<?=$i?>][param_value]" id="param_value_<?=$i?>" class="form-control select2">
                                    <option value="">Select Item</option>
                                    <?=getItemListOption($itemList)?>
                                </select>
                                <?php $i++; ?>
                            </td>
                        </tr>

                        <tr>
                            <td style="width:50%;">SHEET QTY. <span class="text-danger">*</span></td>
                            <td style="width:50%;">
                                <input type="hidden" name="transData[<?=$i?>][id]" id="id_<?=$i?>" value="">
                                <input type="hidden" name="transData[<?=$i?>][param_id]" id="param_id_<?=$i?>" value="">
                                <input type="hidden" name="transData[<?=$i?>][param_key]" id="param_key_<?=$i?>" value="sheet_qty">
                                <input type="text" name="transData[<?=$i?>][param_value]" id="param_value_<?=$i?>" class="form-control floatOnly" value="">
                                <?php $i++; ?>
                            </td>
                        </tr>

                        <tr>
                            <td style="width:50%;">DISCRIPTION OF LAZER ROW PRODUCT</td>
                            <td style="width:50%;">
                                <input type="hidden" name="transData[<?=$i?>][id]" id="id_<?=$i?>" value="">
                                <input type="hidden" name="transData[<?=$i?>][param_id]" id="param_id_<?=$i?>" value="">
                                <input type="hidden" name="transData[<?=$i?>][param_key]" id="param_key_<?=$i?>" value="discription_of_lazer_row_product">
                                                                
                                <select name="transData[<?=$i?>][param_value]" id="param_value_<?=$i?>" class="form-control select2">
                                    <option value="">Select Item</option>
                                    <?=getItemListOption($itemList)?>
                                </select>
                                <?php $i++; ?>
                            </td>
                        </tr>

                        <tr>
                            <td style="width:50%;">LRP QTY.</td>
                            <td style="width:50%;">
                                <input type="hidden" name="transData[<?=$i?>][id]" id="id_<?=$i?>" value="">
                                <input type="hidden" name="transData[<?=$i?>][param_id]" id="param_id_<?=$i?>" value="">
                                <input type="hidden" name="transData[<?=$i?>][param_key]" id="param_key_<?=$i?>" value="lrp_qty">
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