<form>
    <div class="col-md-12">
        <div class="row">

            <?php
                $batchDiv = "";$serialNoDiv = "";$readonly = "";
                $qtyClass = "";
                if(!empty($gateInwardData->id)):                       
                    if(!empty($gateInwardData->item_stock_type) && $gateInwardData->item_stock_type == 1):
                        $serialNoDiv = 'style="display:none;"';
                        $qtyClass = "floatOnly";
                    elseif(!empty($gateInwardData->item_stock_type) && $gateInwardData->item_stock_type == 2):
                        $batchDiv = 'style="display:none;"';
                        $qtyClass = "numericOnly";
                        $readonly = "readonly";
                    else:
                        $batchDiv = 'style="display:none;"';
                        $serialNoDiv = 'style="display:none;"';
                        $qtyClass = "floatOnly";
                    endif;
                else:
                    if(!empty($gateEntryData->batch_stock) && $gateEntryData->batch_stock == 1):
                        $serialNoDiv = 'style="display:none;"';
                        $qtyClass = "floatOnly";
                    elseif(!empty($gateEntryData->batch_stock) && $gateEntryData->batch_stock == 2):
                        $batchDiv = 'style="display:none;"';
                        $qtyClass = "numericOnly";
                        $readonly = "readonly";
                    else:
                        $batchDiv = 'style="display:none;"';
                        $serialNoDiv = 'style="display:none;"';
                        $qtyClass = "floatOnly";
                    endif;
                endif;
            ?>

            <input type="hidden" name="id" id="id" value="<?=(!empty($gateInwardData->id))?$gateInwardData->id:""?>">
            <input type="hidden" name="ref_id" id="ref_id" value="<?=(!empty($gateInwardData->ref_id))?$gateInwardData->ref_id:((!empty($gateEntryData->id))?$gateEntryData->id:"")?>">
            
            <input type="hidden" name="po_id" id="po_id" value="<?=(!empty($gateInwardData->po_id))?$gateInwardData->po_id:""?>">
            <input type="hidden" name="item_stock_type" id="item_stock_type" value="<?=(!empty($gateInwardData->item_stock_type))?$gateInwardData->item_stock_type:0?>">
            <input type="hidden" name="inward_qty" id="inward_qty" value="<?=(!empty($gateInwardData->inward_qty))?$gateInwardData->inward_qty:""?>">
            <input type="hidden" name="item_type" id="item_type" value="<?=(!empty($gateInwardData->item_type))?$gateInwardData->item_type:((!empty($gateEntryData->item_type))?$gateEntryData->item_type:"")?>">

            <input type="hidden" name="trans_prefix" id="trans_prefix" value="<?=(!empty($gateInwardData->trans_prefix))?$gateInwardData->trans_prefix:$trans_prefix?>">
            <input type="hidden" name="trans_no" id="trans_no" value="<?=(!empty($gateInwardData->trans_no))?$gateInwardData->trans_no:$trans_no?>">

            <div class="col-md-2 form-group">
                <label for="trans_no">GI No.</label>
                <input type="text" name="trans_number" id="trans_number" class="form-control" value="<?=(!empty($gateInwardData->trans_number))?$gateInwardData->trans_number:$trans_number?>" readonly>
            </div>

            <div class="col-md-3 form-group">
                <label for="trans_date">GI Date</label>
                <input type="datetime-local" name="trans_date" id="trans_date" class="form-control" value="<?=(!empty($gateInwardData->trans_date))?$gateInwardData->trans_date:getFyDate("Y-m-d H:i:s")?>">
            </div>

            <div class="col-md-4 form-group">
                <label for="party_id">Party Name</label>
                <select name="party_id" id="party_id" class="form-control single-select">
                    <option value="">Select Party Name</option>
                    <?=getPartyListOption($partyList,((!empty($gateInwardData->party_id))?$gateInwardData->party_id:((!empty($gateEntryData->party_id))?$gateEntryData->party_id:"")))?>
                </select>                
            </div>

            <div class="col-md-3 form-group">
                <label for="item_id">Item Name</label>
                <select name="item_id" id="item_id" class="form-control itemDetails single-select" data-res_function="resItemDetail">
                    <option value="">Select Item Name</option>
                    <?=getItemListOption($itemList,(!empty($gateInwardData->item_id))?$gateInwardData->item_id:"")?>
                </select>
            </div>

            <div class="col-md-4 form-group">
                <label for="po_trans_id">Purchase Order</label>
                <select name="po_trans_id" id="po_trans_id" class="form-control single-select">
                    <option value="">Select Purchase Order</option>
                </select>
                <div class="error po_trans_id"></div>
            </div>

            <div class="col-md-4 form-group">
                <label for="qty">Qty</label>
                <input type="text" name="qty" id="qty" class="form-control <?=$qtyClass?> req" value="<?=(!empty($gateInwardData->qty))?$gateInwardData->qty:""?>">
            </div>

            <div class="col-md-4 form-group">
                <label for="price">Price</label>
                <input type="text" name="price" id="price" class="form-control floatVal" value="<?=(!empty($gateInwardData->price))?$gateInwardData->price:""?>">
            </div>

            <div class="col-md-4 form-group">
                <label for="location_id">Location</label>
                <select id="location_id" class="form-control model-select2">
                    <option value="">Select Location</option>
                    <?php
                        if(!empty($locationList)):
                            echo getLocationListOption($locationList);
                        endif;
                    ?>
                </select>
                <div class="error location_id"></div>
            </div>

            <div class="col-md-4 form-group" >
                <label for="heat_no">Heat No.</label>
                <input type="text" id="heat_no" class="form-control" value="">
                <div class="error heat_no"></div>
            </div>

            <div class="col-md-4 form-group" >
                <label for="mill_heat_no">Mill Heat No.</label>
                <input type="text" id="mill_heat_no" class="form-control" value="">
                <div class="error mill_heat_no"></div>
            </div>

        </div>

        <hr>

        <div class="row">

            <div class="col-md-12 row" id="palateDivs"></div>

            <div class="col-md-12 form-group">
                <button type="button" class="btn btn-outline-info float-right addBatch"><i class="fa fa-plus"></i> Add</button>
            </div>

        </div>

        <hr>

        <div class="row">
            <div class="error batch_details"></div>
            <div class="table-responsive">
                <table id="batchTable" class="table table-bordered">
                    <thead class="thead-info">
                        <tr>
                            <th>#</th>
                            <th>PO No</th>
                            <th>Item</th>
                            <th>Location</th>
                            <th>Batch NO</th>
                            <th>Heat No</th>
                            <th>Mill Heat No</th>
                            <th>Qty</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="batchData">                            
                        <tr id="noData">
                            <td class="text-center" colspan="9">No data available in table</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>
<script src="<?php echo base_url();?>assets/js/custom/gate-inward-form.js?v=<?=time()?>"></script>
<?php
    if(!empty($gateInwardData->batchItems)):
        foreach($gateInwardData->batchItems as $row):
            echo "<script>AddBatchRow(".json_encode($row).");</script>";
        endforeach;
    endif;
?>