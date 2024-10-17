<form>
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""?>">

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

            <div class="col-md-6 form-control">
                <label for="item_id">Item Name</label>
                <select name="item_id" id="item_id" class="form-control select2 req">
                    <option value="">Select Item</option>
                    <?=getItemListOption($itemList,((!empty($dataRow->item_id))?$dataRow->item_id:""))?>
                </select>
            </div>

            <div class="col-md-4 form-group">
                <label for="collected_by">Collected By</label>
                <input type="text" name="collected_by" id="collected_by" class="form-control" value="<?=(!empty($dataRow->collected_by))?$dataRow->collected_by:""?>">
            </div>
        </div>
    </div>
</form>