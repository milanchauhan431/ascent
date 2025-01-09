<form>
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""?>">
            <input type="hidden" name="item_type" id="item_type" value="<?=(!empty($dataRow->item_type))?$dataRow->item_type:$item_type?>">

            <div class="col-md-2 form-group">
                <label for="item_code">CAT No.</label>
                <input type="text" name="item_code" id="item_code" class="form-control" value="<?= (!empty($dataRow->item_code)) ? $dataRow->item_code : ""; ?>" />
            </div>

            <div class="col-md-4 form-group">
                <label for="item_name">Item Name</label>
                <input type="text" name="item_name" id="item_name" class="form-control req" value="<?=htmlentities((!empty($dataRow->item_name)) ? $dataRow->item_name : "")?>" />
                <input type="hidden" name="full_name" class="form-control " value="" />
            </div>

            <div class="<?=(!empty($dataRow->item_type) && $dataRow->item_type == 1 || !empty($item_type) && $item_type == 1)?"col-md-3":"col-md-2"?> form-group">
                <label for="unit_id">Unit</label>
                <select name="unit_id" id="unit_id" class="form-control select2 req">
                    <option value="0">--</option>
                    <?=getItemUnitListOption($unitData,((!empty($dataRow->unit_id))?$dataRow->unit_id:""))?>
                </select>
            </div>            

            <div class="<?=(!empty($dataRow->item_type) && $dataRow->item_type == 1 || !empty($item_type) && $item_type == 1)?"hidden":""?> col-md-2 form-group">
                <label for="price">Price</label>
                <input type="text" name="price" id="price" class="form-control floatOnly" value="<?=(!empty($dataRow->price))?floatVal($dataRow->price):""?>">
            </div>

            <div class="<?=(!empty($dataRow->item_type) && $dataRow->item_type == 1 || !empty($item_type) && $item_type == 1)?"col-md-3":"col-md-2"?> form-group">
                <label for="defualt_disc">Defual Disc. (%)</label>
                <input type="text" name="defualt_disc" id="defualt_disc" class="form-control floatOnly req" value="<?=(!empty($dataRow->defualt_disc)) ? floatVal($dataRow->defualt_disc) : ""?>" />
            </div>

            <div class="col-md-3 form-group">
                <label for="category_id">Category</label>
                <select name="category_id" id="category_id" class="form-control select2 req">
                    <option value="0">Select</option>
                    <?php
                        foreach ($categoryList as $row) :
                            $selected = (!empty($dataRow->category_id) && $dataRow->category_id == $row->id) ? "selected" : "";
                            echo '<option value="' . $row->id . '" ' . $selected . '>' . $row->category_name . '</option>';
                        endforeach;
                    ?>
                </select>
            </div>

            <div class="col-md-3 form-group">
                <label for="make_brand">Make</label>
                <select name="make_brand" id="make_brand" class="form-control select2">
                    <option value="">Select Make</option>
                    <?php
                        foreach ($brandList as $row) :
                            $selected = (!empty($dataRow->make_brand) && $dataRow->make_brand == $row->brand_name) ? "selected" : "";
                            echo '<option value="' . $row->brand_name . '" ' . $selected . '>' . $row->brand_name . '</option>';
                        endforeach;
                    ?>
                </select>
            </div>

            <div class="col-md-2 form-group">
                <label for="hsn_code">HSN Code</label>
                <select name="hsn_code" id="hsn_code" class="form-control select2">
                    <option value="">Select HSN Code</option>
                    <?=getHsnCodeListOption($hsnData,((!empty($dataRow->hsn_code))?$dataRow->hsn_code:""))?>
                </select>
            </div>

            <div class="col-md-2 form-group">
                <label for="gst_per">GST (%)</label>
                <select name="gst_per" id="gst_per" class="form-control select2">
                    <?php
                        foreach($this->gstPer as $per=>$text):
                            $selected = (!empty($dataRow->gst_per) && floatVal($dataRow->gst_per) == $per)?"selected":"";
                            echo '<option value="'.$per.'" '.$selected.'>'.$text.'</option>';
                        endforeach;
                    ?>
                </select>
            </div>

            <div class="col-md-2 form-group">
                <label for="active">Active</label>
                <select name="active" id="active" class="form-control">
                    <option value="1" <?=(!empty($dataRow->active) && $dataRow->active == 1)?"selected":""?>>Active</option>
                    <option value="0" <?=(!empty($dataRow->active) && $dataRow->active == 0)?"selected":""?>>De-active</option>
                    <option value="2" <?=(!empty($dataRow->active) && $dataRow->active ==2)?"selected":((!empty($active) && $active == 2)?'selected':'')?>>Enquiry</option>
                </select>
            </div>

            <div class="col-md-3 form-group">
                <label for="std_qty">Conversion Qty</label>
                <div class="input-group">
                    <div class="input-group-append"  style="width:60%!important;">
                        <select name="sec_unit_id" id="sec_unit_id" class="form-control select2">
                            <option value="0">--</option>
                            <?=getItemUnitListOption($unitData,((!empty($dataRow->sec_unit_id))?$dataRow->sec_unit_id:""))?>
                        </select>
                    </div>

                    <div class="input-group-append" style="width:40%!important;">
                        <input type="hidden" name="std_qty" id="std_qty" class="form-control floatOnly" placeholder="Qty." value="<?=(!empty($dataRow->std_qty))?floatVal($dataRow->std_qty):""?>">

                        <input type="text" name="std_pck_qty" id="std_pck_qty" class="form-control numericOnly" placeholder="Qty." value="<?=(!empty($dataRow->std_pck_qty))?floatVal($dataRow->std_pck_qty):""?>">
                    </div>
                </div>
            </div>

            <!-- <div class="col-md-3 form-group">
                <label for="std_pck_qty">Standard Packing Qty</label>
                <input type="text" name="std_pck_qty" id="std_pck_qty" class="form-control numericOnly" value="<?=(!empty($dataRow->std_pck_qty))?floatVal($dataRow->std_pck_qty):""?>">
            </div> -->

            <div class="col-md-2 form-group">
                <label for="min_qty">Min. Qty.</label>
                <input type="text" name="min_qty" id="min_qty" class="form-control floatOnly" value="<?=(!empty($dataRow->min_qty))?$dataRow->min_qty:""?>">
            </div>

            <div class="col-md-7 form-group">
                <label for="description">Product Description</label>
                <textarea name="description" id="description" class="form-control" rows="1"><?=(!empty($dataRow->description))?$dataRow->description:""?></textarea>
            </div>

            <div class="col-md-12 form-group">
                <label for="note">Remark</label>
                <textarea name="note" id="note" class="form-control" rows="1"><?=(!empty($dataRow->note))?$dataRow->note:""?></textarea>
            </div>
        </div>
    </div>
</form>
<script>
$(document).ready(function(){
    $(document).on('change','#hsn_code',function(){
        $("#gst_per").val(($(this).find(':selected').data('gst_per') || 0));
        $("#gst_per").select2({with:null});
    });
});
</script>
