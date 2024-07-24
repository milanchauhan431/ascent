<form data-confirm_message="Are you sure want to assign this Job?">
    <div class="col-md-12">
        <div class="row">

            <input type="hidden" name="id" id="id" value="<?=(!empty($postData->id))?$postData->id:""?>">
            <input type="hidden" name="job_status" id="job_status" value="<?=(!empty($postData->job_status))?$postData->job_status:""?>">
            <input type="hidden" name="next_dept_id" id="next_dept_id" value="<?=(!empty($postData->next_dept_id))?$postData->next_dept_id:""?>">

            <div class="col-md-12 form-group">
                <label for="vendor_id">Vendor Name</label>
                <select name="vendor_id" id="vendor_id" class="form-control select2 req">
                    <option value="">Select Vendor</option>
                    <?=getEmployeeListOption($partyList)?>
                </select>
            </div>

            <div class="col-md-12 form-group">
                <label for="qty">Panel Qty.</label>
                <input type="text" name="vendor_qty" id="vendor_qty" class="form-control req numericOnly" value="">
            </div>

        </div>
    </div>
</form>