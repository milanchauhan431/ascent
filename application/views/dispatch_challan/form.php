<form enctype="multipart/form-data">
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="is_edit" value="<?=(!empty($dataRow->is_edit))?$dataRow->is_edit:""?>">
            <input type="hidden" name="chl_prefix" value="<?=(!empty($dataRow->chl_prefix))?$dataRow->chl_prefix:$chl_prefix?>">
            <input type="hidden" name="chl_no" value="<?=(!empty($dataRow->chl_no))?$dataRow->chl_no:$chl_no?>">

            <div class="col-md-3 form-group">
                <label for="challan_no">Challan No.</label>
                <input type="text" name="challan_no" id="challan_no" class="form-control" value="<?=(!empty($dataRow->challan_no))?$dataRow->challan_no:$chl_prefix.$chl_no?>" readonly>
            </div>

            <div class="col-md-3 form-group">
                <label for="dispatch_date">Dispatch Date</label>
                <input type="date" name="dispatch_date" id="dispatch_date" class="form-control req" value="<?=(!empty($dataRow->dispatch_date))?$dataRow->dispatch_date:getFyDate()?>">
            </div>

            <div class="col-md-3 form-group">
                <label for="invoice_no">Invoice No.</label>
                <input type="text" name="invoice_no" id="invoice_no" class="form-control req" value="<?=(!empty($dataRow->invoice_no))?$dataRow->invoice_no:""?>">
            </div>

            <div class="col-md-3 form-group">
                <label for="vehicle_no">Vehicle No.</label>
                <input type="text" name="vehicle_no" id="vehicle_no" class="form-control" value="<?=(!empty($dataRow->vehicle_no))?$dataRow->vehicle_no:""?>">
            </div>

            <div class="col-md-12 form-group">
                <label for="remark">Remark</label>
                <input type="text" name="remark" id="remark" class="form-control" value="<?=(!empty($dataRow->remark))?$dataRow->remark:""?>">
            </div>

            <div class="col-md-12 form-group">
                <div class="error item_error"></div>
                <div class="table-responsive">
                    <table id="pendingDispatch" class="table table-bordered">
                        <thead class="thead-info">
                            <tr>
                                <th>#</th>
                                <th>Item Name</th>
                                <th>Job No.</th>
                                <th>Order Qty</th>
                                <th>Pending Qty</th>
                                <th>Dispatch Qty</th>
                            </tr>
                        </thead>
                        <tbody id="pendingDispatchList">
                            <?=(!empty($dataRow->itemList))?$dataRow->itemList:$itemList?>
                        </tbody>
                    </table>                    
                </div>
            </div>

        </div>        
    </div>
</form>