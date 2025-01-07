<form>
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""?>">

            <div class="col-md-4 form-group">
                <label for="param_type">Parameter Type</label>
                <select name="param_type" id="param_type" class="form-control">
                    <option value="1" <?=(!empty($dataRow->param_type) && $dataRow->param_type == 1)?"selected":""?>>Production</option>
                    <option value="2" <?=(!empty($dataRow->param_type) && $dataRow->param_type == 2)?"selected":""?>>Testing</option>
                </select>
            </div>

            <div class="col-md-4 form-group">
                <label for="input_type">Input Type</label>
                <select name="input_type" id="input_type" class="form-control">
                    <option value="1" <?=(!empty($dataRow->input_type) && $dataRow->input_type == 1)?"selected":""?>>Number</option>
                    <option value="2" <?=(!empty($dataRow->input_type) && $dataRow->input_type == 2)?"selected":""?>>Decimal</option>
                    <option value="3" <?=(!empty($dataRow->input_type) && $dataRow->input_type == 3)?"selected":""?>>Text</option>
                </select>
            </div>

            <div class="col-md-4 form-group">
                <label for="is_required">Input Required</label>
                <select name="is_required" id="is_required" class="form-control">
                    <option value="0" <?=(!empty($dataRow) && $dataRow->is_required == 0)?"selected":""?>>No</option>
                    <option value="1" <?=(!empty($dataRow->is_required) && $dataRow->is_required == 1)?"selected":""?>>Yes</option>
                </select>
            </div>

            <div class="col-md-8 form-group">
                <label for="param_name">Parameter Name</label>
                <input type="text" name="param_name" id="param_name" class="form-control" value="<?=(!empty($dataRow->param_name))?$dataRow->param_name:""?>">
            </div>

            <div class="col-md-4 form-group">
                <label for="seq">Seq.</label>
                <input type="text" name="seq" id="seq" class="form-control numericOnly" value="<?=(!empty($dataRow->seq))?$dataRow->seq:""?>">
            </div>

            <div class="col-md-12 form-group">
                <label for="remark">Remark</label>
                <input type="text" name="remark" id="remark" class="form-control" value="<?=(!empty($dataRow->remark))?$dataRow->remark:""?>">
            </div>
        </div>
    </div>
</form>