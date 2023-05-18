<?php $this->load->view('includes/header'); ?>
<div class="page-wrapper">
    <div class="container-fluid bg-container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="card-title">Company Info</h4>
                            </div>                         
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="addCompanyInfo" data-res_function="companyInfoRes">
                            <div class="col-md-12">
                                <div class="row">
                                    <input type="hidden" name="id" value="<?= (!empty($dataRow->id)) ? $dataRow->id : ""; ?>" />

                                    <div class="col-md-4 form-group">
                                       <label for="company_name">Company Name</label>
                                       <input type="text" name="company_name" id="company_name" class="form-control req" value="<?= (!empty($dataRow->company_name)) ? $dataRow->company_name : "" ?>">
                                    </div> 

                                    <div class="col-md-4 form-group">
                                       <label for="company_email">Company Email</label>
                                       <input type="text" name="company_email" id="company_email" class="form-control req" value="<?= (!empty($dataRow->company_email)) ? $dataRow->company_email : "" ?>">
                                    </div> 

                                    <div class="col-md-4 form-group">
                                        <label for="company_slogan">Company Slogen</label>
                                        <input name="company_slogan" id="company_slogan" class="form-control" value="<?= (!empty($dataRow->company_slogan)) ? $dataRow->company_slogan : "" ?>">
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label for="company_contact">Company Contact</label>
                                        <input name="company_contact" id="company_contact" class="form-control req" value="<?= (!empty($dataRow->company_contact)) ? $dataRow->company_contact : "" ?>">
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label for="company_phone">Company Phone</label>
                                        <input name="company_phone" id="company_phone" class="form-control" value="<?= (!empty($dataRow->company_phone)) ? $dataRow->company_phone : "" ?>">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="company_city">Company City</label>
                                        <input name="company_city" id="company_city" class="form-control req" value="<?= (!empty($dataRow->company_city)) ? $dataRow->company_city : "" ?>">
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="company_state">Company State</label>
                                        <input name="company_state" id="company_state" class="form-control req" value="<?= (!empty($dataRow->company_state)) ? $dataRow->company_state : "" ?>">
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="company_country">Company Country</label>
                                        <input name="company_country" id="company_country" class="form-control" value="<?= (!empty($dataRow->company_country)) ? $dataRow->company_country : "" ?>">
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label for="company_address">Company Address</label>
                                        <input name="company_address" id="company_address" class="form-control req" value="<?= (!empty($dataRow->company_address)) ? $dataRow->company_address : "" ?>">
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label for="company_pincode">Company Pincode</label>
                                        <input name="company_pincode" id="company_pincode" class="form-control req" value="<?= (!empty($dataRow->company_pincode)) ? $dataRow->company_pincode : "" ?>">
                                    </div>

                                    <div class="col-md-3 form-group">
                                        <label for="company_reg_no">Registration No.</label>
                                        <input name="company_reg_no" id="company_reg_no" class="form-control" value="<?= (!empty($dataRow->company_reg_no)) ? $dataRow->company_reg_no : "" ?>">
                                    </div>

                                    <div class="col-md-3 form-group">
                                        <label for="company_gst_no">Company GST No.</label>
                                        <input name="company_gst_no" id="company_gst_no" class="form-control" value="<?= (!empty($dataRow->company_gst_no)) ? $dataRow->company_gst_no : "" ?>">
                                    </div>

                                    <div class="col-md-3 form-group">
                                        <label for="company_pan_no">Company Pan No.</label>
                                        <input name="company_pan_no" id="company_pan_no" class="form-control" value="<?= (!empty($dataRow->company_pan_no)) ? $dataRow->company_pan_no : "" ?>">
                                    </div>

                                    <div class="col-md-3 form-group">
                                        <label for="company_state_code">Company State Code </label>
                                        <input name="company_state_code" id="company_state_code" class="form-control" value="<?= (!empty($dataRow->company_state_code)) ? $dataRow->company_state_code : "" ?>">
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="company_bank_name">Company Bank Name</label>
                                        <input name="company_bank_name" id="company_bank_name" class="form-control" value="<?= (!empty($dataRow->company_bank_name)) ? $dataRow->company_bank_name : "" ?>">
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="company_bank_branch">Company Bank Branch</label>
                                        <input name="company_bank_branch" id="company_bank_branch" class="form-control" value="<?= (!empty($dataRow->company_bank_branch)) ? $dataRow->company_bank_branch : "" ?>">
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="company_acc_name">Company Account Name</label>
                                        <input name="company_acc_name" id="company_acc_name" class="form-control" value="<?= (!empty($dataRow->company_acc_name)) ? $dataRow->company_acc_name : "" ?>">
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="company_acc_no">Company Account No.</label>
                                        <input name="company_acc_no" id="company_acc_no" class="form-control" value="<?= (!empty($dataRow->company_acc_no)) ? $dataRow->company_acc_no : "" ?>">
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="company_ifsc_code">Company IFSC Code</label>
                                        <input name="company_ifsc_code" id="company_ifsc_code" class="form-control" value="<?= (!empty($dataRow->company_ifsc_code)) ? $dataRow->company_ifsc_code : "" ?>">
                                    </div> 

                                    <div class="col-md-4 form-group">
                                        <label for="swift_code">Swift Code</label>
                                        <input name="swift_code" id="swift_code" class="form-control" value="<?= (!empty($dataRow->swift_code)) ? $dataRow->swift_code : "" ?>">
                                    </div>
                                </div>                                
                            </div>
                        </form>
                    </div>
                    <div class="card-footer">
                        <div class="row">                    
                            <div class="col-md-12">
                                <button type="button" class="btn waves-effect waves-light btn-outline-success btn-save float-right save-form permission-write" onclick="customStore('addCompanyInfo','save');"><i class="fa fa-check"></i> Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('includes/footer'); ?>
<script>
function companyInfoRes(data,formId){
    if(data.status==1){
        toastr.success(data.message, 'Success', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });

        window.location.reload();
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