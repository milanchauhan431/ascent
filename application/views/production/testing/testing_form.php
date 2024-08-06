<form  data-confirm_message="Are you sure want to complete this Testing?">
    <div class="col-md-12">
        <div class="row">

            <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""?>">
            <input type="hidden" name="ref_id" id="ref_id" value="<?=(!empty($dataRow->ref_id))?$dataRow->ref_id:$postData->id?>">
            <input type="hidden" name="pm_id" id="pm_id" value="<?=(!empty($dataRow->pm_id))?$dataRow->pm_id:
            ""?>">
            <input type="hidden" name="job_status" id="job_status" value="<?=(!empty($dataRow->job_status))?$dataRow->job_status:$postData->job_status?>">
            <input type="hidden" name="entry_type" id="entry_type" value="<?=(!empty($dataRow->entry_type))?$dataRow->entry_type:$postData->next_dept_id?>">
            <input type="hidden" name="trans_child_id" id="trans_child_id" value="<?=(!empty($dataRow->trans_child_id))?$dataRow->trans_child_id:$postData->trans_child_id?>">

            <div class="col-md-12 form-group">
                <label for="customer_name">Customer Name</label>
                <input type="text" name="customer_name" id="customer_name" class="form-control" value="<?=(!empty($dataRow->customer_name))?$dataRow->customer_name:$postData->party_name?>">
            </div>

            <div class="col-md-12 form-group">
                <label for="switchboard_tag">Switchboards Tag</label>
                <input type="text" id="switchboard_tag" class="form-control" value="<?=(!empty($dataRow->item_name))?$dataRow->item_name:$postData->item_name?>" readonly>
            </div>

            <div class="col-md-6 form-group">
                <label for="tc_sr_no">TC Sr. Nos.</label>
                <input type="text" id="tc_sr_number" class="form-control" value="<?=(!empty($dataRow->tc_sr_number))?$dataRow->tc_sr_number:$postData->tc_sr_number?>" readonly>
                <input type="hidden" name="tc_prefix" id="tc_prefix" value="<?=(!empty($dataRow->tc_prefix))?$dataRow->tc_prefix:$postData->tc_prefix?>">
                <input type="hidden" name="tc_sr_no" id="tc_sr_no" value="<?=(!empty($dataRow->tc_sr_no))?$dataRow->tc_sr_no:$postData->tc_sr_no?>">
            </div>

            <div class="col-md-6 form-group">
                <label for="job_number">JOB Number</label>
                <input type="text" id="job_number" class="form-control" value="<?=(!empty($dataRow->job_number))?$dataRow->job_number:$postData->job_number?>" readonly>
            </div>

            <div class="col-md-6 form-group">
                <label for="drgs_no">Drgs Ref.</label>
                <input type="text" id="drgs_number" class="form-control" value="<?=(!empty($dataRow->drgs_number))?$dataRow->drgs_number:$postData->drgs_number?>" readonly>
                <input type="hidden" name="drgs_no" id="drgs_no" value="<?=(!empty($dataRow->drgs_no))?$dataRow->drgs_no:$postData->drgs_no?>">
            </div>

            <div class="col-md-6 form-group">
                <label for="switchgear_no">Switchgear Sr. No.</label>
                <input type="text" name="switchgear_no" id="switchgear_no" class="form-control" value="<?=(!empty($dataRow->switchgear_no))?$dataRow->switchgear_no:""?>">
            </div>

            <div class="col-md-6 form-group">
                <label for="entry_date">Date of Mnf.</label>
                <input type="date" name="entry_date" id="entry_date" class="form-control" value="<?=(!empty($dataRow->entry_date))?$dataRow->entry_date:date("Y-m-d")?>" readonly>
            </div>

            <div class="col-md-6 form-group">
                <label for="tested_qty">Qty.</label>
                <input type="text" name="tested_qty" id="tested_qty" class="form-control" value="1" readonly>
            </div>

            <div class="col-md-12 form-group">
                <label for="system_detail_id">System Detail</label>
                <select name="system_detail_id" id="system_detail_id" class="form-control select2">
                    <option value="">Select System Detail</option>
                    <?php
                        foreach($systemDetailList as $row):
                            $selected = (!empty($dataRow->system_detail_id) && $dataRow->system_detail_id == $row->id)?"selected":"";
                            echo '<option value="'.$row->id.'" '.$selected.'>'.$row->system_detail.'</option>';
                        endforeach;
                    ?>
                </select>
            </div>

            <div class="col-md-12 form-group">
                <label for="control_supply">Control Supply</label>
                <input type="text" name="control_supply" id="control_supply" class="form-control" value="<?=(!empty($dataRow->control_supply))?$dataRow->control_supply:""?>">
            </div>

            <div class="col-md-12 form-group">
                <label for="hv_test">HV Test</label>
                <input type="text" name="hv_test" id="hv_test" class="form-control" value="<?=(!empty($dataRow->hv_test))?$dataRow->hv_test:""?>">
            </div>

            <div class="col-md-12 form-group">
                <label for="ins_res">Insulation Resistance</label>
                <textarea name="ins_res" id="ins_res" class="form-control"><?=(!empty($dataRow->ins_res))?$dataRow->ins_res:""?></textarea>
            </div>
            
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="error paramError"></div>
            </div>
            <div class="col-md-12">
                <div class="table table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-info">
                            <tr>
                                <th>Ins. Resis. Parameter</th>
                                <th>Value</th>
                            </tr>
                        </thead>
                        <tbody id="ins_res_param_data">
                            <?php
                                if(!empty($dataRow->insulation_resistance_param)):
                                    $i=1;
                                    foreach($dataRow->insulation_resistance_param as $row):
                                        echo '<tr>
                                            <td>'.$row->param_key.'</td>
                                            <td>
                                                <input type="hidden" name="paramData['.$i.'][id]" value="'.$row->id.'">
                                                <input type="hidden" name="paramData['.$i.'][param_key]" value="'.$row->param_key.'">
                                                <input type="text" name="paramData['.$i.'][param_value]" class="form-control" value="'.$row->param_value.'">
                                            </td>
                                        </tr>';
                                        $i++;
                                    endforeach;
                                else:
                                    echo '<tr>
                                        <td colspan="2" class="text-center">No data available in table</td>
                                    </tr>';
                                endif;
                            ?>                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
$(document).ready(function(){
    $(document).on('change','#system_detail_id',function(e){
        e.stopImmediatePropagation();
        e.preventDefault();

        var system_detail_id = $(this).val();
        if(system_detail_id == ""){ 
            $("#control_supply").val("");
            $("#hv_test").val("");
            $("#ins_res").val("");
            $("#ins_res_param_data").html('<tr><td colspan="2" class="text-center">No data available in table</td></tr>');
            return false; 
        }

        $.ajax({
            url : base_url + 'production/testingParameters/getSystemDetail',
            type : 'post',
            data : {id:system_detail_id},
            dataType : 'json'
        }).done(function(response){
            $("#control_supply").val(response.data.control_supply);
            $("#hv_test").val(response.data.hv_test);
            $("#ins_res").val(response.data.insulation_resistance);
            $("#ins_res_param_data").html(response.data.insulation_resistance_param);
        });
    });
});
</script>