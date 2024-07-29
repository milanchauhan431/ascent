<form>
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""?>">

            <div class="col-md-12 form-group">
                <label for="system_detail">System Detail</label>
                <input type="text" name="system_detail" id="system_detail" class="form-control req" value="<?=(!empty($dataRow->system_detail))?$dataRow->system_detail:""?>">
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
                <label for="insulation_resistance">Insulation Resistence</label>
                <textarea name="insulation_resistance" id="insulation_resistance" class="form-control"><?=(!empty($dataRow->insulation_resistance))?$dataRow->insulation_resistance:""?></textarea>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-md-12">
                <div class="table table-responsive">
                    <table id="ins_res_param" class="table table-bordered">
                        <thead class="thead-info">
                            <tr>
                                <th>Insulation Resistence Params.</th>
                                <th>Value</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="ins_res_param_data">
                            <tr id="noData">
                                <td colspan="3" class="text-center">No data available in table</td>
                            </tr>
                        </tbody>
                        <tfoot class="thead-info" id="ins_res_param_input">
                            <tr>
                                <th>
                                    <input type="text" id="param" class="form-control" value="">
                                </th>
                                <th>
                                    <input type="text" id="param_value" class="form-control" value="">
                                </th>
                                <th class="text-center">
                                    <button class="btn btn-success btn-sm addParam"><i class="fa fa-check"></i></button>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
$(document).ready(function(){
    $(document).on('click','.addParam',function(e){
        e.stopImmediatePropagation();
        e.preventDefault();
        $("#ins_res_param_input .error").html("");

        var formData = {};
        formData.row_index = "";
        formData.param = $("#ins_res_param_input #param").val();
        formData.param_value = $("#ins_res_param_input #param_value").val();

        if(formData.param == ""){
            $(".param").html("Ins. Res. Params. is required.");
        }

        if(formData.param_value == ""){
            $(".param_value").html("Value is required.");
        }

        var errorCount = $('#ins_res_param_input .error:not(:empty)').length;
        if(errorCount == 0){
            addRow(formData);
            $("#ins_res_param_input #param").val("").focus();
            $("#ins_res_param_input #param_value").val("");
        }
    });
});

var itemCount = 0;
function addRow(data){
    $('table#ins_res_param tr#noData').remove();
    var tblName = "ins_res_param";
    var tBody = $("#"+tblName+" > TBODY")[0];

    //Add Row.
	row = tBody.insertRow(-1);

    var paramInput = $("<input/>",{type:"text",name:"insulation_resistance_json["+itemCount+"][param]",class:'form-control',value:data.param});
    var cell = $(row.insertCell(-1));
	cell.html(paramInput);

    var paramValueInput = $("<input/>",{type:"text",name:"insulation_resistance_json["+itemCount+"][param_value]",class:'form-control',value:data.param_value});
    var cell = $(row.insertCell(-1));
	cell.html(paramValueInput);

    //Add Button cell.	
    var btnRemove = $('<button><i class="ti-trash"></i></button>');
	btnRemove.attr("type", "button");
	btnRemove.attr("onclick", "remove(this);");
	btnRemove.attr("style", "margin-left:4px;");
	btnRemove.attr("class", "btn btn-outline-danger btn-sm waves-effect waves-light");
    cell = $(row.insertCell(-1));
    cell.append(btnRemove);
    cell.attr("class","text-center");
    cell.attr("style","width:10%;");

    itemCount++;
}

function remove(button){
    var row = $(button).closest("TR");
	var table = $("#ins_res_param")[0];
	table.deleteRow(row[0].rowIndex);

	/* $('#ins_res_param tbody tr td:nth-child(1)').each(function(idx, ele) {
        ele.textContent = idx + 1;
    }); */
	var countTR = $('#ins_res_param tbody tr:last').index() + 1;

    if (countTR == 0) {
        $("#ins_res_param tbody").html('<tr id="noData"><td colspan="3" class="text-center">No data available in table</td></tr>');
    }
}
</script>

<?php
if(!empty($dataRow->insulation_resistance_json)):
    $insulation_resistance_json = json_decode($dataRow->insulation_resistance_json);
    foreach($insulation_resistance_json as $row):
        $row = json_encode($row);
        echo '<script>addRow('.$row.');</script>';
    endforeach;
endif;
?>