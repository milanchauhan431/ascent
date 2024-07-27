<form data-confirm_message="Are you sure want to complete this Quality Checking?">
    <div class="col-md-12">
        <div class="row">
            
            <input type="hidden" name="id" id="id" value="<?=(!empty($postData->id))?$postData->id:""?>">
            <input type="hidden" name="job_status" id="job_status" value="<?=(!empty($postData->job_status))?$postData->job_status:""?>">
            <input type="hidden" name="next_dept_id" id="next_dept_id" value="<?=(!empty($postData->next_dept_id))?$postData->next_dept_id:""?>">

            <div class="col-md-12 form-group">
                <label for="quality_check">Quality Check As Per QC Check List</label>
                <select name="quality_check" id="quality_check" class="form-control">
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>

        </div>
    </div>
</form>