<html>
    <body style="font-size:0.8rem;">    
        <div class="row" style="">
            <div class="col-12">

                <table class="table">
                    <tr><td><img src="<?=$letter_head?>" class="img"></td></tr>
                </table>

                <table class="table table-bordered">
                    <tr>
                        <td class="fs-20 text-center" style="letter-spacing: 0px;font-size:1.5rem;font-weight:bold;padding:0px !important;">FACTORY TEST CERTIFICATE</td>
                    </tr>
                </table>

                <table class="table table-bordered" style="margin-top:0px;font-size: 0.5rem !impotant;">
                    <tr>
                        <th class="text-left">Customer</th>
                        <td colspan="3"><?=$dataRow->customer_name?></td>
                        <th class="text-left">TC Sr. No.</th>
                        <td><?=$dataRow->tc_sr_number?></td>
                    </tr>

                    <tr>
                        <th class="text-left">Switchboards Tag</th>
                        <td colspan="3"><?=$dataRow->item_name?></td>
                        <th class="text-left">Job No.</th>
                        <td><?=$dataRow->job_number?></td>
                    </tr>

                    <tr>
                        <th class="text-left">Drgs Ref.</th>
                        <td><?=$dataRow->drgs_number?></td>
                        <th class="text-left">Switchgear SR. NO.</th>
                        <td><?=$dataRow->switchgear_no?></td>
                        <th class="text-left">Date of Mnf.</th>
                        <td><?=formatDate($dataRow->entry_date,"d/m/Y")?></td>
                    </tr>

                    <tr>
                        <th class="text-left">System Detail</th>
                        <td><?=$dataRow->system_detail?></td>
                        <th class="text-left">Control supply</th>
                        <td><?=$dataRow->control_supply?></td>
                        <th class="text-left">Total Qty.</th>
                        <td><?=floatval($dataRow->tested_qty)?> Nos</td>
                    </tr>

                    <tr>
                        <td colspan="6" class="text-center">
                            This is to certify that the above mentioned equipment has been manufactured in accordance with the specification stipulated in PO. This been tested at our works satisfactorily for the routine test as follows,and found in order.
                        </td>
                    </tr>
                </table>

                <table class="table table-bordered" style="margin-top:0px;font-size:8px !important;">
                    <thead>
                        <tr>
                            <th class="text-center">Sr. No.</th>
                            <th class="text-center">Test Carried Out</th>
                            <th class="text-center" colspan="2">Result</th>
                            <th class="text-center">Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        <tr>
                            <td class="text-center"><?=$i?></td>
                            <td class="text-center">Visual Inspection</td>
                            <td class="text-center" colspan="2">Verified as per approved GA Drgs.</td>
                            <td class="text-center">OK</td>
                        </tr>
                        <?php $i++; ?>

                        <tr>
                            <td class="text-center"><?=$i?></td>
                            <td class="text-center">Fabrication</td>
                            <td class="text-center" colspan="2">Verified as per approved GA Drgs.</td>
                            <td class="text-center">OK</td>
                        </tr>
                        <?php $i++; ?>

                        <tr>
                            <td class="text-center"><?=$i?></td>
                            <td class="text-center">Powder Coating</td>
                            <td class="text-center" colspan="2">Verified as per approved GA Drgs.</td>
                            <td class="text-center">OK</td>
                        </tr>
                        <?php $i++; ?>

                        <tr>
                            <td class="text-center"><?=$i?></td>
                            <td class="text-center">Bill Of Material</td>
                            <td class="text-center" colspan="2">Verified as per approved BOM</td>
                            <td class="text-center">OK</td>
                        </tr>
                        <?php $i++; ?>

                        <?php if(!empty($dataRow->hv_test)): ?>
                        <tr>
                            <td class="text-center"><?=$i?></td>
                            <td class="text-center"><?=$dataRow->hv_test?></td>
                            <td class="text-center" colspan="2">Power Ckt. : Withstood</td>
                            <td class="text-center">OK</td>
                        </tr>
                        <?php $i++; endif; ?>

                        <?php
                            if(!empty($dataRow->ins_res)):
                            $param = 1;$paramFirstRow = ''; $paramHtml = '';
                            foreach($dataRow->insulation_resistance_param as $row):
                                if(!empty($row->param_value)):
                                    if($param == 1):
                                        $paramFirstRow .= '<td class="text-center">'.$row->param_key.'</td>';
                                        $paramFirstRow .= '<td class="text-center">'.$row->param_value.'</td>';
                                    else:
                                        $paramHtml .= '<tr>
                                            <td class="text-center">'.$row->param_key.'</td>
                                            <td class="text-center">'.$row->param_value.'</td>
                                        </tr>';
                                    endif;
                                    $param++;
                                endif;
                            endforeach;
                            $rowSpan = ($param - 1);
                        ?>
                        <tr>
                            <td class="text-center" rowspan="<?=$rowSpan?>"><?=$i?></td>
                            <td class="text-center" rowspan="<?=$rowSpan?>"><?=nl2br($dataRow->ins_res)?></td>
                            <?=$paramFirstRow?>
                            <td class="text-center" rowspan="<?=$rowSpan?>">OK</td>
                        </tr>
                        <?=$paramHtml?>
                        <?php $i++; endif; ?>

                        <tr>
                            <td class="text-center"><?=$i?></td>
                            <td class="text-center">Functional Test</td>
                            <td class="text-center" colspan="2">Check of operation of switchgear & meter as per Approved scheme Drgs.</td>
                            <td class="text-center">OK</td>
                        </tr>
                        <?php $i++; ?>

                        <tr>
                            <td class="text-center"v><?=$i?></td>
                            <td class="text-center">Cable Connection Tightness</td>
                            <td class="text-center" colspan="2">Checked Tightness</td>
                            <td class="text-center">OK</td>
                        </tr>
                        <?php $i++; ?>

                        <tr>
                            <td class="text-center"><?=$i?></td>
                            <td class="text-center">Busbar Bolt Tightness</td>
                            <td class="text-center" colspan="2">Checked Tightness</td>
                            <td class="text-center">OK</td>
                        </tr>
                        <?php $i++; ?>

                        <tr>
                            <td class="text-center"><?=$i?></td>
                            <td class="text-center">Electrical Clearances</td>
                            <td class="text-center" colspan="2">Checked between phases,Ph-Neutral,Ph-Earth and found in order.</td>
                            <td class="text-center">OK</td>
                        </tr>
                    </tbody>
                    
                </table>

                <table  class="table table-bordered">
                    <tr>
                        <th class="text-center" colspan="3">
                            <img src="<?=$qc_logo?>" class="img">
                        </th>
                        <th class="text-center" colspan="2">
                            <?=$dataRow->tested_by?><br>
                            Testing Engineer
                        </th>
                    </tr>
                    <tr>
                        <td colspan="2">
                            LTPTC/019/R00
                        </td>
                        <td colspan="3">
                            This is System Genrate document not Need to Any Physicaly sign
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </body>
</html>