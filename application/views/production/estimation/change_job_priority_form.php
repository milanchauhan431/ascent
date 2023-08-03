<form data-confirm_message="Are you sure want to change this job priority ?">
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""?>">
            <div class="col-md-12 form-group">
                <label for="priority">Priority</label>
                <select name="priority" id="priority" class="form-control single-select">
                    <option value="0">Select</option>
                    <option value="1" <?=(!empty($dataRow->priority) && $dataRow->priority == 1)?"selected":""?> >HIGH</option>
                    <option value="2" <?=(!empty($dataRow->priority) && $dataRow->priority == 2)?"selected":""?> >MEDIUM</option>
                    <option value="3" <?=(!empty($dataRow->priority) && $dataRow->priority == 3)?"selected":""?> >LOW</option>
                </select>
            </div>
        </div>
    </div>
</form>