<form>
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""?>">
            <input type="hidden" name="item_type" id="item_type" value="<?=(!empty($dataRow->item_type))?$dataRow->item_type:$item_type?>">

            <div class="col-md-2 form-group">
                <label for="item_code">CAT Code</label>
                <input type="text" name="item_code" class="form-control" value="<?= (!empty($dataRow->item_code)) ? $dataRow->item_code : ""; ?>" />
            </div>

            <div class="col-md-4 form-group">
                <label for="item_name">Item Name</label>
                <input type="text" name="item_name" class="form-control req" value="<?=htmlentities((!empty($dataRow->item_name)) ? $dataRow->item_name : "")?>" />
                <input type="hidden" name="full_name" class="form-control " value="" />
            </div>

            <div class="<?=(!empty($dataRow->item_type) && $dataRow->item_type == 1 || !empty($item_type) && $item_type == 1)?"col-md-3":"col-md-2"?> form-group">
                <label for="part_no">Part No.</label>
                <input type="text" name="part_no" class="form-control req" value="<?=htmlentities((!empty($dataRow->part_no)) ? $dataRow->part_no : "")?>" />
            </div>

            <div class="<?=(!empty($dataRow->item_type) && $dataRow->item_type == 1 || !empty($item_type) && $item_type == 1)?"col-md-3":"col-md-2"?> form-group">
                <label for="unit_id">Unit</label>
                <select name="unit_id" id="unit_id" class="form-control single-select req">
                    <option value="0">--</option>
                    <?php
                    foreach ($unitData as $row) :
                        $selected = (!empty($dataRow->unit_id) && $dataRow->unit_id == $row->id) ? "selected" : "";
                        echo '<option value="' . $row->id . '" ' . $selected . '>[' . $row->unit_name . '] ' . $row->description . '</option>';
                    endforeach;
                    ?>
                </select>
            </div>

            <div class="<?=(!empty($dataRow->item_type) && $dataRow->item_type == 1 || !empty($item_type) && $item_type == 1)?"hidden":""?> col-md-2 form-group">
                <label for="price">Price</label>
                <input type="text" name="price" id="price" class="form-control floatOnly" value="<?=(!empty($dataRow->price))?$dataRow->price:""?>">
            </div>

            <div class="col-md-3 form-group">
                <label for="category_id">Category</label>
                <select name="category_id" id="category_id" class="form-control single-select req">
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
                <label for="fg_id">Product Type</label>
                <select name="fg_id" id="fg_id" class="form-control single-select">
                    <option value="">Select Type</option>
                    <option value="1" <?=(!empty($dataRow->fg_id) && $dataRow->fg_id == 1)?"selected":""?>>Semi Finish</option>
                    <option value="2" <?=(!empty($dataRow->fg_id) && $dataRow->fg_id == 2)?"selected":""?>>Finish</option>
                </select>
            </div>

            <div class="col-md-3 form-group">
                <label for="hsn_code">HSN Code</label>
                <select name="hsn_code" id="hsn_code" class="form-control single-select req">
                    <option value="">Select HSN Code</option>
                    <?php
                        foreach ($hsnData as $row) :
                            $selected = (!empty($dataRow->hsn_code) && $dataRow->hsn_code == $row->hsn) ? "selected" : "";
                            echo '<option value="' . floatVal($row->hsn) . '" ' . $selected . '>' . floatVal($row->hsn) . '</option>';
                        endforeach;
                    ?>
                </select>
            </div>

            <div class="col-md-3 form-group">
                <label for="active">Active</label>
                <select name="active" id="active" class="form-control">
                    <option value="1" <?=(!empty($dataRow->active) && $dataRow->active == 1)?"selected":""?>>Active</option>
                    <option value="0" <?=(!empty($dataRow->active) && $dataRow->active == 0)?"selected":""?>>De-active</option>
                    <option value="2" <?=(!empty($dataRow->active) && $dataRow->active ==2)?"selected":((!empty($active) && $active == 2)?'selected':'')?>>Enquiry</option>
                </select>
            </div>

            <div class="col-md-3 form-group">
                <label for="drawing_no">Drawing No</label>
                <input type="text" name="drawing_no" class="form-control" value="<?= (!empty($dataRow->drawing_no)) ? $dataRow->drawing_no : "" ?>" />
            </div>

            <div class="col-md-3 form-group">
                <label for="rev_no">Revision No</label>
                <input type="text" name="rev_no" class="form-control" value="<?= (!empty($dataRow->rev_no)) ? $dataRow->rev_no : "" ?>" />
            </div>

            <div class="col-md-3 form-group">
                <label for="install_year">Revision Date</label>
                <input type="date" name="install_year" class="form-control" value="<?= (!empty($dataRow->install_year)) ? $dataRow->install_year : "" ?>" />
            </div>

            <div class="col-md-3 form-group">
                <label for="eco_no">ECO No.</label>
                <input type="text" name="eco_no" class="form-control floatOnly" value="<?= (!empty($dataRow->eco_no)) ? $dataRow->eco_no : "" ?>" />
            </div>

            <div class="col-md-3 form-group">
                <label for="wh_min_qty">Min. Stock Qty (WH)</label>
                <input type="text" name="wh_min_qty" class="form-control floatOnly" value="<?= (!empty($dataRow->wh_min_qty)) ? $dataRow->wh_min_qty : "" ?>" />
            </div>
            
            <div class="col-md-3 form-group">
                <label for="wkg">Weight/Nos <small>(In Kg.)</small> </label>
                <input type="text" name="wkg" class="form-control floatOnly" value="<?= (!empty($dataRow->wkg)) ? $dataRow->wkg : "" ?>" />
            </div>

            <div class="col-md-6 form-group">
                <label for="description">Product Description</label>
                <textarea name="note" id="note" class="form-control" rows="1"><?=(!empty($dataRow->note))?$dataRow->note:""?></textarea>
            </div>

            <div class="col-md-12 form-group">
                <label for="note">Remark</label>
                <textarea name="note" id="note" class="form-control" rows="1"><?=(!empty($dataRow->note))?$dataRow->note:""?></textarea>
            </div>
        </div>
    </div>
</form>