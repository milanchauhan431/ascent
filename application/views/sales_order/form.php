<?php $this->load->view('includes/header'); ?>

<div class="page-wrapper">
    <div class="container-fluid bg-container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header text-center">
                        <h4><u>Sales Order</u></h4>
                    </div>
                    <div class="card-body">
                        <form autocomplete="off" id="saveSalesOrder" data-res_function="resSaveOrder" enctype="multipart/form-data">
                            <div class="col-md-12">
                                <div class="row">

                                    <div class="hiddenInput">
                                        <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""?>">
                                        <input type="hidden" name="entry_type" id="entry_type" value="<?=(!empty($dataRow->entry_type))?$dataRow->entry_type:$entry_type?>">
                                        <input type="hidden" name="from_entry_type" id="from_entry_type" value="<?=(!empty($dataRow->from_entry_type))?$dataRow->from_entry_type:((!empty($from_entry_type))?$from_entry_type:"")?>">
                                        <input type="hidden" name="ref_id" id="ref_id" value="<?=(!empty($dataRow->ref_id))?$dataRow->ref_id:((!empty($ref_id))?$ref_id:"")?>">

                                        <input type="hidden" name="trans_prefix" id="trans_prefix" value="<?=(!empty($dataRow->trans_prefix))?$dataRow->trans_prefix:((!empty($trans_prefix))?$trans_prefix:"")?>">
                                        <input type="hidden" name="trans_no" id="trans_no" value="<?=(!empty($dataRow->trans_no))?$dataRow->trans_no:((!empty($trans_no))?$trans_no:"")?>">

                                        <input type="hidden" name="party_name" id="party_name" value="<?=(!empty($dataRow->party_name))?$dataRow->party_name:""?>">
                                        <input type="hidden" name="gst_type" id="gst_type" value="<?=(!empty($dataRow->gst_type))?$dataRow->gst_type:""?>">
                                        <input type="hidden" name="party_state_code" id="party_state_code" value="<?=(!empty($dataRow->party_state_code))?$dataRow->party_state_code:""?>">
                                        <input type="hidden" name="apply_round" id="apply_round" value="<?=(!empty($dataRow->apply_round))?$dataRow->apply_round:"1"?>">

                                        <input type="hidden" name="ledger_eff" id="ledger_eff" value="0">
                                    </div>

                                    <div class="col-md-2 form-group">
                                        <label for="trans_number">So. No.</label>
                                        <input type="text" name="trans_number" id="trans_number" class="form-control" value="<?=(!empty($dataRow->trans_number))?$dataRow->trans_number:((!empty($trans_number))?$trans_number:"")?>" readonly>
                                    </div>

                                    <div class="col-md-2 form-group">
                                        <label for="trans_date">So. Date</label>
                                        <input type="date" name="trans_date" id="trans_date" class="form-control" value="<?=(!empty($dataRow->trans_date))?$dataRow->trans_date:getFyDate()?>">
                                    </div>

                                    <div class="col-md-5 form-group">
                                        <label for="party_id">Customer Name</label>
                                        <div class="float-right">	
											<span class="dropdown float-right">
												<a class="text-primary font-bold waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" datatip="Progress" flow="down">+ Add New</a>

												<div class="dropdown-menu dropdown-menu-left user-dd animated flipInY" x-placement="start-left">
													<div class="d-flex no-block align-items-center p-10 bg-primary text-white">ACTION</div>
													
													<a class="dropdown-item addNew" href="javascript:void(0)" data-button="both" data-modal_id="modal-xl" data-function="addParty" data-controller="parties" data-postdata='{"party_category" : 1 }' data-res_function="resPartyMaster" data-form_title="Add Customer">+ Customer</a>
													
												</div>
											</span>
										</div>
                                        <select name="party_id" id="party_id" class="form-control single-select partyDetails partyOptions req" data-res_function="resPartyDetail">
											<option value="">Select Party</option>
											<?=getPartyListOption($partyList,((!empty($dataRow->party_id))?$dataRow->party_id:0))?>
										</select>

                                    </div>

                                    <div class="col-md-3 form-group">
                                        <label for="gstin">GST NO.</label>
                                        <select name="gstin" id="gstin" class="form-control single-select">
                                            <option value="">Select GST No.</option>
                                            <?php
                                                if(!empty($dataRow->party_id)):
                                                    foreach($gstinList as $row):
                                                        $selected = ($dataRow->gstin == $row->gstin)?"selected":"";
                                                        echo '<option value="'.$row->gstin.'" '.$selected.'>'.$row->gstin.'</option>';
                                                    endforeach;
                                                endif;
                                            ?>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-3 form-group">
                                        <label for="order_type">Production Type</label>
                                        <select name="order_type" id="order_type" class="form-control single-select">
                                            <option value="">Select Type</option>
                                            <option value="P" <?=(!empty($dataRow->order_type) && $dataRow->order_type == "P")?"selected":""?>>Panel</option>
                                            <option value="F" <?=(!empty($dataRow->order_type) && $dataRow->order_type == "F")?"selected":""?>>Fabrication</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3 form-group">
                                        <label for="doc_no">Cust. PO. No.</label>
                                        <input type="text" name="doc_no" id="doc_no" class="form-control" value="<?=(!empty($dataRow->doc_no))?$dataRow->doc_no:""?>">
                                    </div>

                                    <div class="col-md-3 form-group">
                                        <label for="doc_date">Cust. PO. Date</label>
                                        <input type="date" name="doc_date" id="doc_date" class="form-control" value="<?=(!empty($dataRow->doc_date))?$dataRow->doc_date:""?>">
                                    </div>

                                </div>

                                <hr>

                                <div class="col-md-12 row">
                                    <div class="col-md-6"><h4>Item Details : </h4></div>
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-outline-success waves-effect float-right add-item"><i class="fa fa-plus"></i> Add Item</button>
                                    </div>
                                </div>

                                <div class="col-md-12 mt-3">
                                    <div class="error itemData"></div>
                                    <div class="row form-group">
                                        <div class="table-responsive">
                                            <table id="salesOrderItems" class="table table-striped table-borderless">
                                                <thead class="thead-info">
                                                    <tr>
                                                        <th style="width:5%;">#</th>
                                                        <th>Item Name</th>
                                                        <th>HSN Code</th>
                                                        <th>Qty.</th>
                                                        <th>Unit</th>
                                                        <th>Price</th>
                                                        <th>Disc.</th>
                                                        <th class="igstCol">IGST</th>
                                                        <th class="cgstCol">CGST</th>
                                                        <th class="sgstCol">SGST</th>
                                                        <th class="amountCol">Amount</th>
                                                        <th class="netAmtCol">Amount</th>
                                                        <th>Remark</th>
                                                        <th class="text-center" style="width:10%;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tempItem" class="temp_item">
                                                    <tr id="noData">
                                                        <td colspan="14" class="text-center">No data available in table</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                
                                <hr>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table id="summaryTable" class="table">
                                                <thead class="table-info">
                                                    <tr>
                                                        <th style="width: 60%;">Descrtiption</th>
                                                        <!-- <th style="width: 30%;">Ledger</th> -->
                                                        <th style="width: 10%;">Percentage</th>
                                                        <th style="width: 10%;">Amount</th>
                                                        <th style="width: 20%;">Net Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Sub Total</td>
                                                        <!-- <td></td> -->
                                                        <td></td>
                                                        <td></td>
                                                        <td>
                                                            <input type="hidden" name="total_amount" id="total_amount" class="form-control" value="0" />
                                                            <input type="text" name="taxable_amount" id="taxable_amount" class="form-control summaryAmount" value="0" readonly />
                                                        </td>
                                                    </tr>
                                                    <?php
                                                    $beforExp = "";
                                                    $afterExp = "";
                                                    $tax = "";
                                                    $invExpenseData = (!empty($dataRow->expenseData)) ? $dataRow->expenseData : array();

                                                    foreach ($expenseList as $row) :

                                                        $expPer = 0;
                                                        $expAmt = 0;
                                                        $perFiledName = $row->map_code . "_per";
                                                        $amtFiledName = $row->map_code . "_amount";
                                                        if (!empty($invExpenseData) && $row->map_code != "roff") :
                                                            $expPer = $invExpenseData->{$perFiledName};
                                                            $expAmt = $invExpenseData->{$amtFiledName};
                                                        endif;

                                                        $options = '';
                                                        /* $options = '<select class="form-control single-select" name="expenseData[' . $row->map_code . '_acc_id]" id="' . $row->map_code . '_acc_id">';

                                                        foreach ($ledgerList as $ledgerRow) :
                                                            if ($ledgerRow->group_code != "DT") :
                                                                $filedName = $row->map_code . "_acc_id";
                                                                if (!empty($invExpenseData->{$filedName})) :
                                                                    if ($row->map_code != "roff") :
                                                                        $selected = ($ledgerRow->id == $invExpenseData->{$filedName}) ? "selected" : (($ledgerRow->id == $row->acc_id) ? 'selected' : '');
                                                                    else :
                                                                        $selected = ($ledgerRow->id == $dataRow->round_off_acc_id) ? "selected" : (($ledgerRow->id == $row->acc_id) ? 'selected' : '');
                                                                    endif;
                                                                else :
                                                                    $selected = ($ledgerRow->id == $row->acc_id) ? 'selected' : '';
                                                                endif;

                                                                $options .= '<option value="' . $ledgerRow->id . '" ' . $selected . '>' . $ledgerRow->party_name . '</option>';
                                                            endif;
                                                        endforeach;
                                                        $options .= '</select>'; */

                                                        if ($row->position == 1) :
                                                            $beforExp .= '<tr>
                                                                <td>' . $row->exp_name . '</td>
                                                                <!--<td>' . $options . '</td>-->
                                                                <td>';

                                                            $readonly = "";
                                                            $perBoxType = "text";
                                                            $calculateSummaryPer = "calculateSummary";
                                                            $calculateSummaryAmt = "calculateSummary";
                                                            if ($row->calc_type != 1) :
                                                                $perBoxType = "text";
                                                                $readonly = "readonly";
                                                                $calculateSummaryPer = "calculateSummary";
                                                                $calculateSummaryAmt = "";
                                                            else :
                                                                $perBoxType = "hidden";
                                                                $readonly = "";
                                                                $calculateSummaryPer = "";
                                                                $calculateSummaryAmt = "calculateSummary";
                                                            endif;



                                                            $beforExp .= "<input type='" . $perBoxType . "' name='expenseData[" . $row->map_code . "_per]' id='" . $row->map_code . "_per' data-row='" . json_encode($row) . "' value='" . $expPer . "' class='form-control " . $calculateSummaryPer . " floatOnly'> ";

                                                            $beforExp .= "</td>
                                                            <td><input type='text' id='" . $row->map_code . "_amt' class='form-control floatOnly " . $calculateSummaryAmt . "' data-sm_type='exp' data-row='" . json_encode($row) . "' value='" . $expAmt . "' " . $readonly . "></td>
                                                            <td><input type='text' name='expenseData[" . $row->map_code . "_amount]' id='" . $row->map_code . "_amount'  value='0' class='form-control summaryAmount' readonly /> <input type='hidden' id='other_" . $row->map_code . "_amount' class='otherGstAmount' value='0'> </td>
                                                            </tr>";

                                                        else :

                                                            $afterExp .= '<tr>
                                                                <td>' . $row->exp_name . '</td>
                                                                <!--<td>' . $options . '</td>--><td>';

                                                            $readonly = "";
                                                            $perBoxType = "text";
                                                            $calculateSummaryPer = "calculateSummary";
                                                            $calculateSummaryAmt = "calculateSummary";
                                                            if ($row->map_code != "roff" && $row->calc_type != 1) :
                                                                $perBoxType = "text";
                                                                $readonly = "readonly";
                                                                $calculateSummaryPer = "calculateSummary";
                                                                $calculateSummaryAmt = "";
                                                            else :
                                                                $perBoxType = "hidden";
                                                                $readonly = "";
                                                                $calculateSummaryPer = "";
                                                                $calculateSummaryAmt = "calculateSummary";
                                                            endif;

                                                            $afterExp .= "<input type='" . $perBoxType . "' name='expenseData[" . $row->map_code . "_per]' id='" . $row->map_code . "_per' data-row='" . json_encode($row) . "' value='" . $expPer . "' class='form-control  floatOnly " . $calculateSummaryPer . "'> ";

                                                            $readonly = ($row->map_code == "roff") ? "readonly" : $readonly;
                                                            $amtType = ($row->map_code == "roff") ? "hidden" : "text";
                                                            $afterExp .= "</td>
                                                            <td><input type='" . $amtType . "' id='" . $row->map_code . "_amt' class='form-control " . $calculateSummaryAmt . "  floatOnly ' data-sm_type='exp' data-row='" . json_encode($row) . "' value='" . $expAmt . "' " . $readonly . "></td>
                                                            <td><input type='text' name='expenseData[" . $row->map_code . "_amount]' id='" . $row->map_code . "_amount' value='0' class='form-control floatOnly " . (($row->map_code == "roff") ? "" : "summaryAmount") . "' readonly /> </td>
                                                            </tr>";
                                                        endif;
                                                    endforeach;

                                                    foreach ($taxList as $taxRow) :
                                                        $options = '';
                                                        /* $options = '<select class="form-control single-select" name="' . $taxRow->map_code . '_acc_id" id="' . $taxRow->map_code . '_acc_id">';

                                                        foreach ($ledgerList as $ledgerRow) :
                                                            if ($ledgerRow->group_code == "DT") :
                                                                $filedName = $taxRow->map_code . "_acc_id";
                                                                if (!empty($dataRow->{$filedName})) :
                                                                    $selected = ($ledgerRow->id == $dataRow->{$filedName}) ? "selected" : (($ledgerRow->id == $taxRow->acc_id) ? 'selected' : '');
                                                                else :
                                                                    $selected = ($ledgerRow->id == $taxRow->acc_id) ? 'selected' : '';
                                                                endif;

                                                                $options .= '<option value="' . $ledgerRow->id . '" ' . $selected . '>' . $ledgerRow->party_name . '</option>';
                                                            endif;
                                                        endforeach;
                                                        $options .= '</select>'; */

                                                        $taxClass = "";
                                                        $perBoxType = "text";
                                                        $calculateSummary = "calculateSummary";
                                                        $taxPer = 0;
                                                        $taxAmt = 0;
                                                        if (!empty($dataRow->id)) :
                                                            $taxPer = $dataRow->{$taxRow->map_code . '_per'};
                                                            $taxAmt = $dataRow->{$taxRow->map_code . '_amount'};
                                                        endif;
                                                        if ($taxRow->map_code == "cgst") :
                                                            $taxClass = "cgstCol";
                                                            $perBoxType = "hidden";
                                                            $calculateSummary = "";
                                                        elseif ($taxRow->map_code == "sgst") :
                                                            $taxClass = "sgstCol";
                                                            $perBoxType = "hidden";
                                                            $calculateSummary = "";
                                                        elseif ($taxRow->map_code == "igst") :
                                                            $taxClass = "igstCol";
                                                            $perBoxType = "hidden";
                                                            $calculateSummary = "";
                                                        endif;

                                                        $tax .= '<tr class="' . $taxClass . '">
                                                            <td>' . $taxRow->name . '</td>
                                                            <!--<td>' . $options . '</td>-->
                                                            <td>';

                                                        $tax .= "<input type='" . $perBoxType . "' name='" . $taxRow->map_code . "_per' id='" . $taxRow->map_code . "_per' data-row='" . json_encode($taxRow) . "' value='" . $taxPer . "' class='form-control floatOnly " . $calculateSummary . "'> ";

                                                        $tax .= "</td>
                                                            <td><input type='" . $perBoxType . "' id='" . $taxRow->map_code . "_amt' class='form-control floatOnly' data-sm_type='tax'data-row='" . json_encode($taxRow) . "' value='" . $taxAmt . "' readonly ></td>
                                                            <td><input type='text' name='" . $taxRow->map_code . "_amount' id='" . $taxRow->map_code . "_amount'  value='0' class='form-control floatOnly summaryAmount' readonly /> </td>
                                                        </tr>";
                                                    endforeach;

                                                    echo $beforExp;
                                                    echo $tax;
                                                    echo $afterExp;
                                                    ?>

                                                </tbody>
                                                <tfoot class="table-info">
                                                    <tr>
                                                        <th>Net. Amount</th>
                                                        <!-- <th></th> -->
                                                        <th></th>
                                                        <th></th>
                                                        <td>
                                                            <input type="text" name="net_amount" id="net_amount" class="form-control floatOnly" value="0" readonly />
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>									
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="row">
                                    <div class="col-md-9 form-group">
                                        <label for="remark">Remark</label>
                                        <input type="text" name="remark" id="remark" class="form-control" value="<?=(!empty($dataRow->remark))?$dataRow->remark:""?>">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="">&nbsp;</label>
                                        <button type="button" class="btn btn-outline-success waves-effect btn-block" data-toggle="modal" data-target="#termModel">Terms & Conditions (<span id="termsCounter">0</span>)</button>
                                        <div class="error term_id"></div>
                                    </div>
                                    <?php $this->load->view('includes/terms_form',['termsList'=>$termsList,'termsConditions'=>(!empty($dataRow->termsConditions)) ? $dataRow->termsConditions : array()])?>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer">
                        <div class="col-md-12">
                            <button type="button" class="btn waves-effect waves-light btn-outline-success float-right save-form" onclick="customStore({'formId':'saveSalesOrder'});" ><i class="fa fa-check"></i> Save</button>
                            <a href="javascript:void(0)" onclick="window.location.href='<?=base_url($headData->controller)?>'" class="btn waves-effect waves-light btn-outline-secondary float-right btn-close save-form" style="margin-right:10px;"><i class="fa fa-times"></i> Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div>

<div class="modal fade" id="itemModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content animated slideDown">
            <div class="modal-header" style="display:block;"><h4 class="modal-title">Add or Update Item</h4></div>
            <div class="modal-body">
                <form id="itemForm">
                    <div class="col-md-12">

                        <div class="row form-group">
							<div id="itemInputs">
								<input type="hidden" name="id" id="id" value="" />
								<input type="hidden" name="from_entry_type" id="from_entry_type" value="" />
                                <input type="hidden" name="ref_id" id="ref_id" value=""  />
                                
								<input type="hidden" name="row_index" id="row_index" value="">
								<input type="hidden" name="item_code" id="item_code" value="" />
                                <input type="hidden" name="item_name" id="item_name" value="" />
                                <input type="hidden" name="item_type" id="item_type" value="" />
                            </div>
                            

                            <div class="col-md-12 form-group">
								<label for="item_id">Product Name</label>
                                <select name="item_id" id="item_id" class="form-control single-select itemDetails itemOptions" data-res_function="resItemDetail">
                                    <option value="">Select Product Name</option>
                                    <?=getItemListOption($itemList)?>
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="qty">Quantity</label>
                                <input type="text" name="qty" id="qty" class="form-control floatOnly req" value="0">
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="disc_per">Disc. (%)</label>
                                <input type="text" name="disc_per" id="disc_per" class="form-control floatOnly" value="0">
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="price">Price</label>
                                <input type="text" name="price" id="price" class="form-control floatOnly req" value="0" />
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="unit_id">Unit</label>                                
                                <input type="text" name="unit_name" id="unit_name" class="form-control" value="" readonly />
                                <input type="hidden" name="unit_id" id="unit_id" value="" >
                            </div>
							<div class="col-md-4 form-group">
                                <label for="hsn_code">HSN Code</label>
                                <select name="hsn_code" id="hsn_code" class="form-control single-select">
                                    <option value="">Select HSN Code</option>
                                    <?php
                                        foreach($hsnList as $row):
                                            echo '<option value="'.$row->hsn.'">'.$row->hsn.'</option>';
                                        endforeach;
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="gst_per">GST Per.(%)</label>
                                <select name="gst_per" id="gst_per" class="form-control single-select">
                                    <?php
                                        foreach($this->gstPer as $per=>$text):
                                            echo '<option value="'.$per.'">'.$text.'</option>';
                                        endforeach;
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-12 form-group">
                                <label for="item_remark">Remark</label>
                                <input type="text" name="item_remark" id="item_remark" class="form-control" value="" />
                            </div>                            
                        </div>
                    </div>          
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn waves-effect waves-light btn-outline-success saveItem btn-save" data-fn="save"><i class="fa fa-check"></i> Save</button>
                <button type="button" class="btn waves-effect waves-light btn-outline-warning saveItem btn-save-close" data-fn="save_close"><i class="fa fa-check"></i> Save & Close</button>
                <button type="button" class="btn waves-effect waves-light btn-outline-secondary btn-close" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
            </div>
        </div>
    </div>
</div>


<?php $this->load->view('includes/footer'); ?>
<script src="<?php echo base_url(); ?>assets/js/custom/sales-order-form.js?v=<?= time() ?>"></script>
<script src="<?php echo base_url(); ?>assets/js/custom/calculate.js?v=<?= time() ?>"></script>

<?php
if(!empty($dataRow->itemList)):
    foreach($dataRow->itemList as $row):
        $row->row_index = "";
        $row->gst_per = floatVal($row->gst_per);
        echo '<script>AddRow('.json_encode($row).');</script>';
    endforeach;
endif;
?>