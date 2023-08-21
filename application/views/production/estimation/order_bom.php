<form>
    <div class="col-md-12">
        <!-- Excel Config Section Start -->
        <div class="row form-group">
            <div class="col-md-2">
                <button type="button" class="btn btn-secondary btn-block" title="Click Me" data-toggle="collapse" href="#import_excel_section" role="button" aria-expanded="false" aria-controls="import_excel"> Excel Config.</button>
            </div>
            <div class="col-md-10">
                <hr>
            </div>
        </div>

        <section class="collapse multi-collapse" id="import_excel_section">
            <div class="row" id="input_excel_column">
                <div class="col-md-3 form-group">
                    <label for="material_description_column">Material Desc. Column No.</label>
                    <input type="text" id="material_description_column" class="form-control numericOnly" value="1">
                </div>

                <div class="col-md-3 form-group">
                    <label for="make_column">Make Column No.</label>
                    <input type="text" id="make_column" class="form-control numericOnly" value="2">
                </div>

                <div class="col-md-3 form-group">
                    <label for="item_code_column">Cat No. Column No.</label>
                    <input type="text" id="item_code_column" class="form-control numericOnly" value="3">
                </div>

                <div class="col-md-3 form-group">
                    <label for="uom_column">UOM Column No.</label>
                    <input type="text" id="uom_column" class="form-control numericOnly" value="4">
                </div>

                <div class="col-md-3 form-group">
                    <label for="qty_column">Qty Column No.</label>
                    <input type="text" id="qty_column" class="form-control numericOnly" value="5">
                </div>

                <div class="col-md-3 form-group">
                    <label for="price_column">Other MRP Column No.</label>
                    <input type="text" id="price_column" class="form-control numericOnly" value="6">
                </div>

                <div class="col-md-3 form-group">
                    <label for="amount_column">OTHER AMOUNT Column No.</label>
                    <input type="text" id="amount_column" class="form-control numericOnly" value="7">
                </div>

                <div class="col-md-3 form-group">
                    <label for="disc_per_column">DISC. Column No.</label>
                    <input type="text" id="disc_per_column" class="form-control numericOnly" value="8">
                </div>

                <div class="col-md-3 form-group">
                    <label for="net_amount_column">FINAL OTHER AMOUNT Column No.</label>
                    <input type="text" id="net_amount_column" class="form-control numericOnly" value="9">
                </div>

                <div class="col-md-3 form-group">
                    <label for="start_row">Start Reading (Row No.)</label>
                    <input type="text" id="start_row" class="form-control numericOnly" value="4">
                </div>
            </div>
        </section>
        <!-- Excel Config Section End -->
        <hr>

        <div class="row">
            <input type="hidden" id="trans_main_id" value="<?=(!empty($dataRow->trans_main_id))?$dataRow->trans_main_id:""?>">
            <input type="hidden" id="trans_child_id" value="<?=(!empty($dataRow->trans_child_id))?$dataRow->trans_child_id:""?>">
            <div class="col-md-6 form-group">
                <label for="">Select File</label>
                <div class="input-group">
                    <a href="<?=base_url("assets/uploads/defualt/so_bom.xlsx")?>" class="btn btn-outline-info" title="Download Example File" download><i class="fa fa-download"></i></a>
                    <div class="input-group-append">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="excelFile" accept=".xlsx, .xls">
                            <label class="custom-file-label" for="excelFile">Choose file</label>
                        </div>
                    </div>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" id="readButton" type="button">Read Excel</button>
                    </div>
                    <!-- <div class="input-group-append">
                        <button class="btn btn-outline-secondary" id="clearData" type="button">Reset</button>
                    </div> -->
                </div>
                <div class="error excel_file"></div>
            </div>
        </div>
    </div>
    
    <hr>

    <div class="col-md-12">
        <div class="error itemData"></div>
        <div class="table-responsive">
            <table id="salesOrderBomItems" class="table table-bordered">
                <thead class="thead-info">
                    <tr>
                        <th>#</th>
                        <th>Material Description</th>
                        <th>Make</th>
                        <th>Cat. No.</th>
                        <th>UOM</th>
                        <th>Total Qty.</th>
                        <th>OTHER MRP</th>
                        <th>OTHER AMOUNT</th>
                        <th>DISC (IN %)</th>
                        <th>FINAL OTHER AMOUNT</th>
                        <th>Action</th>
                    </tr>                    
                </thead>
                <tbody>
                    <tr id="noData">
                        <td colspan="11" class="text-center">No data available in table</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</form>

<script src="<?php echo base_url(); ?>assets/js/xlsx.full.min.js?v=<?=time()?>"></script>
<script>
var clickedTr = 0;

$(document).ready(function() {
    
    /* $(document).on('change','#excelFile',function() {
        var fileName = $('#excelFile').val().split('\\').pop() || "Choose file";
        $(".custom-file-label").html(fileName);
    }); */

    $(document).on("click",'#readButton',function() {
        var inputArray = [
            "material_description",
            "make",
            "item_code",
            "uom",
            "qty",
            "price",
            "amount",
            "disc_per",
            "net_amount"
        ];
        var start_row = $("#input_excel_column #start_row").val();

        $("#input_excel_column .error").html("");

        $.each(inputArray,function(key,column){
            var input_val = $("#"+column+"_column").val();
            if(input_val == ""){ $("#input_excel_column ."+column+"_column").html("Please input column no."); }

            if(input_val == 0){ $("#input_excel_column ."+column+"_column").html("Please input column no."); }
        });

        if(start_row == ""){ $("#input_excel_column .start_row").html("Please input row no."); }
        if(start_row < 2){ $("#input_excel_column .start_row").html("Please input minimum row no. 2"); }

        var fileInput = document.getElementById('excelFile');
        var file = fileInput.files[0];
        $(".excel_file").html("");
        
        if(file){
            var errorCount = $('#input_excel_column .error:not(:empty)').length;

            if(errorCount == 0){
                var columnCount = $('table#salesOrderBomItems thead tr').first().children().length;
                $("table#salesOrderBomItems > TBODY").html('<tr><td id="noData" colspan="'+columnCount+'" class="text-center">Loading...</td></tr>'); 

                setTimeout(function(){
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var data = new Uint8Array(e.target.result);
                        var workbook = XLSX.read(data, { type: 'array' });

                        var sheetName = workbook.SheetNames[0]; // Assuming the first sheet
                        var worksheet = workbook.Sheets[sheetName];

                        var jsonData = XLSX.utils.sheet_to_json(worksheet, { header: 1 });
                        var fileData = [];
                        // Process the data or display it in the table

                        //Remove blank line.
                        $('table#salesOrderBomItems > TBODY').html("");              

                        var postData = [];
                        $.each(jsonData,function(ind,row){ 
                            postData = [];
                            if(ind >= (start_row - 1)){
                                var item_id = "";
                                if(row[1]){
                                    row[3] = row[3] || -1;
                                    $.ajax({
                                        url : base_url + '/items/getItemDetails',
                                        type : 'post',
                                        data : { item_code : row[3], item_types : "2,3"},
                                        global:false,
                                        async:false,
                                        dataType:'json'
                                    }).done(function(res){
                                        item_id = "";
                                        if(res != ""){
                                            var itemDetail = res.data.itemDetail;
                                            if(itemDetail != null){
                                                item_id = itemDetail.id;
                                                row[2] = itemDetail.make_brand;
                                            }                            
                                        }
                                        row[3] = (row[3] != -1)?row[3]:"";
                                        
                                        $.each(inputArray,function(key,column){
                                            var input_val = $("#"+column+"_column").val();
                                            if(input_val != ""){ 
                                                postData[column] = row[input_val]  || "";
                                            }
                                        });
                                        postData['item_id'] = item_id;
                                        postData = Object.assign({}, postData);
                                        
                                        AddRow(postData);
                                    }); 
                                } 
                            } 
                        });
                    };

                    reader.readAsArrayBuffer(file); 
                },200);
            }
        }else{
            $(".excel_file").html("Please Select File.");
        }         
    });

    $(document).on('click','.addNew',function(){ 
        clickedTr = $(this).data('row_id'); 
        var formData = $("#add-item-"+clickedTr).data('form_data');
        console.log(formData);
        
        setTimeout(function(){
            $("#addItem #item_code").val(formData.item_code);
            $("#addItem #item_name").val(formData.material_description);
            $("#addItem #make_brand").val(formData.make);
            $("#addItem #make_brand").select2({ with:null });//.comboSelect();
            $("#addItem #unit_id").val(25);
            $("#addItem #unit_id").select2({ with:null });//.comboSelect();
            $("#addItem #defualt_disc").val(formData.disc_per);
            $("#addItem #price").val(formData.price);
        },1000);
    });
});

function AddRow(data){

    var tblName = "salesOrderBomItems";

    //Remove blank line.
	$('table#'+tblName+' tr#noData').remove();

    //Get the reference of the Table's TBODY element.
	var tBody = $("#" + tblName + " > TBODY")[0];    

    var ind = -1 ;
	row = tBody.insertRow(ind);
    $(row).attr('style',((data.item_id == "")?"background:#f7b4b4;":"background:#8ce1d3;"));
    $(row).attr('class',((data.item_id == "")?"none":"success"));
    

    var disable = ((data.item_id == "")?true:false);
    
    //Add index cell
	var countRow = ($('#' + tblName + ' tbody tr:last').index() + 1);
	var cell = $(row.insertCell(-1));
	cell.html(countRow);
	cell.attr("style", "width:5%;");

    $(row).attr('id',countRow);

    var transMainIdInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][trans_main_id]",  value: $("#trans_main_id").val(), disabled:disable });
    var transChildInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][trans_child_id]",  value: $("#trans_child_id").val(), disabled:disable });
    var materialInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][material_description]",  value: data.material_description, disabled:disable });
    cell = $(row.insertCell(-1));
    cell.html(data.material_description);
    cell.append(materialInput);
    cell.append(transMainIdInput);
    cell.append(transChildInput);

    var makeInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][make]",  value: data.make, disabled:disable });
    cell = $(row.insertCell(-1));
    cell.html(data.make);
    cell.append(makeInput);

    var itemCodeInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][item_code]",  value: data.item_code, disabled:disable });
    var itemIdInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][item_id]",id:'item_id_'+countRow,  value: data.item_id, disabled:disable });
    cell = $(row.insertCell(-1));
    cell.html(data.item_code);
    cell.append(itemCodeInput);
    cell.append(itemIdInput);

    var unitInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][uom]",  value: data.uom, disabled:disable });
    cell = $(row.insertCell(-1));
    cell.html(data.uom);
    cell.append(unitInput);

    var qtyInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][qty]",  value: data.qty, disabled:disable });
    cell = $(row.insertCell(-1));
    cell.html(data.qty);
    cell.append(qtyInput);

    var priceInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][price]",  value: data.price, disabled:disable });
    cell = $(row.insertCell(-1));
    cell.html(data.price);
    cell.append(priceInput);

    var amountInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][amount]",  value: data.amount, disabled:disable });
    cell = $(row.insertCell(-1));
    cell.html(data.amount);
    cell.append(amountInput);

    var discPerInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][disc_per]",  value: data.disc_per, disabled:disable });
    cell = $(row.insertCell(-1));
    cell.html(data.disc_per);
    cell.append(discPerInput);

    var netAmtInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][net_amount]",  value: data.net_amount, disabled:disable });
    cell = $(row.insertCell(-1));
    cell.html(data.net_amount);
    cell.append(netAmtInput);

    var addNewItem = $('<button><i class="fa fa-plus"></i></button>');
	addNewItem.attr("type", "button");
	addNewItem.attr("id", "add-item-"+countRow);
	addNewItem.attr("class", "btn waves-effect waves-light btn-outline-primary float-right addNew permission-write press-add-btn");
	addNewItem.attr("data-button", "both");
	addNewItem.attr("data-row_id", countRow);
	addNewItem.attr("data-modal_id", "modal-xl");
	addNewItem.attr("data-controller", "items");
	addNewItem.attr("data-function", "addItem");
	addNewItem.attr("data-res_function", "resBomItem");
	addNewItem.attr("data-js_store_fn", "customStore");
	addNewItem.attr("data-form_title", "Add Raw Material");
	addNewItem.attr("data-postdata", '{"item_type" : 3 }');
	addNewItem.attr("data-form_data", JSON.stringify(data));

    cell = $(row.insertCell(-1));
    cell.append(((data.item_id == "")?addNewItem:""));
    cell.attr('id','cell-'+countRow)
}

function resBomItem(data,formId){
    if(data.status==1){
        $('#'+formId)[0].reset();$("#modal-xl").modal('hide');
        $('#salesOrderBomItems tbody #'+clickedTr).attr('style',"background:#8ce1d3;");
        $('#salesOrderBomItems tbody #'+clickedTr).removeClass('none');
        $('#salesOrderBomItems tbody #'+clickedTr).addClass('success');
        $('#salesOrderBomItems tbody #'+clickedTr+' input').prop('disabled',false);
        $("#cell-"+clickedTr).html("");  
        $("#salesOrderBomItems tbody #item_id_"+clickedTr).val(data.id);
        clickedTr = 0;

        $(".modal").css({'overflow':'auto'});

        $('#readButton').trigger("click");

        toastr.success(data.message, 'Success', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });
    }else{
        if(typeof data.message === "object"){
            $(".error").html("");
            $.each( data.message, function( key, value ) {$("."+key).html(value);});
        }else{
            toastr.error(data.message, 'Error', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });
        }			
    }
}

function resSaveOrderBom(data,formId){
    if(data.status==1){
        $("#salesOrderBomItems tr.success").remove();
        initTable();
        toastr.success(data.message, 'Success', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });
    }else{
        if(typeof data.message === "object"){
            $(".error").html("");
            $.each( data.message, function( key, value ) {$("."+key).html(value);});
        }else{
            toastr.error(data.message, 'Error', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });
        }			
    }
}
</script>