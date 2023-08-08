<div class="row">
	<div class="col-12">
		<table class="table">
			<tr><td><img src="<?=$letter_head?>" class="img"></td></tr>
		</table>

		<table class="table table-bordered">
			<tr>
				<td class="fs-20 text-center" style="letter-spacing: 0px;font-size:1.5rem;font-weight:bold;padding:0px !important;">PURCHASE ORDER</td>
			</tr>
		</table>
		
		<table class="table table-bordered" style="margin-top:0px;">			
			<tr>
				<th class="text-left" style="width:50%;vertical-align:top;">PO No. : <?=$poData->trans_number?></th>
				<th class="text-right" style="width:50%;vertical-align:top;">PO Date : <?=formatDate($poData->trans_date)?></th>
			</tr>
			<tr>
				<td style="width:50%;vertical-align:top;">
					TO,<br>
					<b>M/S. <?=$poData->party_name?></b> <br>
					<small>
						<?=htmlspecialchars($partyData->party_address)." ".$partyData->state_name.", ".$partyData->city_name." - ".$partyData->party_pincode?><br>
						GSTIN : <?=$poData->gstin?>
					</small>
				</td>
				<td style="width:50%;vertical-align:top;">
					<small><br>
						Contact Person : <?=ucwords($partyData->contact_person)?><br>
						Contact No. : <?=$partyData->party_mobile?><br>
						Email : <?=$partyData->party_email?><br>
					</small><br><br>
				</td> 
			</tr>
			
			<tr>
				<td style="width:50%;vertical-align:top;">
					Billing Address: <br>
					<b><?=$companyData->company_name?></b> <br>
					<small>
						<?=$companyData->company_address?><br>
						Contact No. : <?=$companyData->company_contact?><br>
						GSTIN : <?=$companyData->company_gst_no?><br><br>
					</small><br><br>
				</td>
				<td style="width:50%;vertical-align:top;">
					Delivery/Booking Address: <br>
					<b><?=$companyData->company_name?></b> <br>
					<small>
						<?=$poData->delivery_address." ".$partyData->delivery_state_name.", ".$partyData->delivery_city_name." - ".$partyData->delivery_pincode?></br><br>
						Transport Name : <?=$poData->transport_name?><br>
						Contact Person : <?=ucwords($poData->contact_person)?><br>
						Contact No. : <?=$poData->contact_no?><br>
					</small><br><br>
				</td>
			</tr>
			<tr>
				<th colspan="2" class="text-center">Sub:- Purchase order for Supply of following material</th>
			</tr>
			
			<!-- <tr>
				<td colspan="2">
					Dear Sir,<br>
					We are pleased to issue this Purchase Order for supply of goods as per our discussion
					Schedule of Quantities and Prices 
				</td>
			</tr> -->
		</table>
		
		<table class="table item-list-bb" style="margin-top:0px;">
			<?php
				$thead = '<thead>
					<tr>
						<th style="width:40px;">No.</th>
						<th>MAKE</th>
						<th>CAT No.</th>
						<th class="text-left">PRODUCT NAME</th>
						<th style="width:80px;">Qty</th>
						<th style="width:50px;">Rate<br><small>(INR)</small></th>
						<th style="width:50px;">Disc<br><small>(%)</small></th>
						<th style="width:50px;">GST<br><small>(%)</small></th>
						<th style="width:80px;">Amount<br><small>(INR)</small></th>
					</tr>
				</thead>';
				echo $thead;
			?>
			
			<tbody>
				<?php
					$i=0;$rowCount = 1;$pageCount = 1;$totalQty = 0;$migst=0;$mcgst=0;$msgst=0;
					if(!empty($poData->itemList)):
						foreach($poData->itemList as $row):						
							echo '<tr>';
								echo '<td class="text-center">'.++$i.'</td>';
								echo '<td>'.$row->make.'</td>';
								echo '<td>'.$row->item_code.'</td>';
								echo '<td>'.$row->item_name.'</td>';
								echo '<td class="text-right">'.floatVal($row->qty).' '.$row->unit_name.'  '.((!empty(floatVal($row->qty_kg)))?"<br>(".$row->qty_kg." ".$row->sec_unit_name.")":"").'</td>';
								echo '<td class="text-right">'.floatVal($row->price).'</td>';
								echo '<td class="text-right">'.floatVal($row->disc_per).'</td>';
								echo '<td class="text-right">'.floatVal($row->gst_per).'</td>';							
								echo '<td class="text-right">'.sprintf('%.2f',$row->taxable_amount).'</td>';
							echo '</tr>';

							if(($rowCount == 10 && $pageCount == 1) || ($rowCount == 25 && $pageCount != 1)): 
								echo '
									</tbody></table>
									<div class="text-right"><i>Continue to Next Page</i></div>
									<pagebreak>
									<table class="table item-list-bb" style="margin-top:10px;">
										'.$thead.'
									<tbody>'; 
								$rowCount = 0; // Reset the row count
								$pageCount++; // Increment the page count
							endif;
							$rowCount++;

							$totalQty += $row->qty;
							if($row->gst_per > $migst){$migst=$row->gst_per;$mcgst=$row->cgst_per;$msgst=$row->sgst_per;}
						endforeach;
					endif;
					
					$rwspan= 0; $srwspan = '';
					$beforExp = "";
					$afterExp = "";
					$invExpenseData = (!empty($poData->expenseData)) ? $poData->expenseData : array();
					foreach ($expenseList as $row) :
						$expAmt = 0;
						$amtFiledName = $row->map_code . "_amount";
						if (!empty($invExpenseData) && $row->map_code != "roff") :
							$expAmt = floatVal($invExpenseData->{$amtFiledName});
						endif;

						if(!empty($expAmt)):
							if ($row->position == 1) :
								if($rwspan == 0):
									$beforExp .= '<th class="text-right" colspan="2">'.$row->exp_name.'</th>
									<td class="text-right">'.sprintf('%.2f',$expAmt).'</td>';
								else:
									$beforExp .= '<tr>
										<th colspan="2" class="text-right">' . $row->exp_name . '</th>
										<td class="text-right">'.sprintf('%.2f',$expAmt).'</td>
									</tr>';
								endif;
							else:
								if($rwspan == 0):
									$afterExp .= '<th class="text-right" colspan="2">'.$row->exp_name.'</th>
									<td class="text-right">'.sprintf('%.2f',$expAmt).'</td>';
								else:
									$afterExp .= '<tr>
										<th colspan="2" class="text-right">' . $row->exp_name . '</th>
										<td class="text-right">'.sprintf('%.2f',$expAmt).'</td>
									</tr>';
								endif;
							endif;
							$rwspan++;
						endif;
					endforeach;

					$taxHtml = '';
					foreach ($taxList as $taxRow) :
						$taxAmt = 0;
						$taxAmt = floatVal($poData->{$taxRow->map_code . '_amount'});
						if(!empty($taxAmt)):
							if($rwspan == 0):
								$taxHtml .= '<th colspan="2" class="text-right">'.$taxRow->name.'</th>
								<td class="text-right">'.sprintf('%.2f',$taxAmt).'</td>';
							else:
								$taxHtml .= '<tr>
									<th colspan="2" class="text-right">' . $taxRow->name . ' @'.(($poData->gst_type == 1)?floatVal($migst/2):$migst).'%</th>
									<td class="text-right">'.sprintf('%.2f',$taxAmt).'</td>
								</tr>';
							endif;
							$rwspan++;
						endif;
					endforeach;

					$fixRwSpan = (!empty($rwspan))?3:0;
				?>
			</tbody>
			<tfoot>
				<tr>
					<th colspan="4" class="text-right">Total Qty.</th>
					<th class="text-right"><?=sprintf('%.3f',$totalQty)?></th>
					<th></th>
					<th colspan="2" class="text-right border-bottom-none">Sub Total</th>
					<th class="text-right"><?=sprintf('%.2f',$poData->taxable_amount)?></th>
				</tr>
				<tr>
					<th class="text-left" colspan="6" rowspan="<?=$rwspan?>">
						Notes : <br><?=$poData->remark?>
					</th>		
					<?php if(empty($rwspan)): ?>		
						<th colspan="2" class="text-right">Round Off</th>
						<td class="text-right"><?=sprintf('%.2f',$poData->round_off_amount)?></td>
					<?php endif; ?>
				</tr>
				<?=$beforExp.$taxHtml.$afterExp?>
				<tr>
					<th class="text-left" colspan="6" rowspan="<?=$fixRwSpan?>">
						Amount In Words : <br><?=numToWordEnglish($poData->net_amount)?>
					</th>		
					<?php if(empty($rwspan)): ?>		
						<th colspan="2" class="text-right">Grand Total</th>
						<th class="text-right"><?=sprintf('%.2f',$poData->net_amount)?></th>
					<?php endif; ?>		
				</tr>
				
				<?php if(!empty($rwspan)): ?>
				<tr>
					<th colspan="2" class="text-right">Round Off</th>
					<td class="text-right"><?=sprintf('%.2f',$poData->round_off_amount)?></td>
				</tr>
				<tr>
					<th colspan="2" class="text-right">Grand Total</th>
					<th class="text-right"><?=sprintf('%.2f',$poData->net_amount)?></th>
				</tr>
				<?php endif; ?>
			</tfoot>
		</table>
		
		<table class="table table-terms" style="margin-top:0px;">
			<tr>
				<th colspan="2" class="text-left border-bottom">Terms & Conditions :</th>
			</tr>
			<?php
				if(!empty($poData->termsConditions)):
					foreach($poData->termsConditions as $row):
						echo '<tr>';
							echo '<th class="text-left fs-11" style="width:140px;">'.$row->term_title.'</th>';
							echo '<td class=" fs-11"> : '.$row->condition.'</td>';
						echo '</tr>';
					endforeach;
				endif;
			?>
		</table>
		
		<htmlpagefooter name="lastpage">
			<table class="table top-table" style="margin-top:10px;border-top:1px solid #545454;">
				<tr>
					<td style="width:50%;" rowspan="3"></td>
					<th colspan="2">For, <?=$companyData->company_name?></th>
				</tr>
				<tr>
					<td style="width:25%;" class="text-center"><?=$prepareBy?></td>
					<td style="width:25%;" class="text-center"><?=$approveBy?></td>
				</tr>
				<tr>
					<td style="width:25%;" class="text-center">Prepared By</td>
					<td style="width:25%;" class="text-center">Authorised By</td>
				</tr>
			</table>
			<table class="table top-table" style="margin-top:10px;border-top:1px solid #545454;">
				<tr>
					<td style="width:25%;">PO No. & Date : <?=$poData->trans_number.' ['.formatDate($poData->trans_date).']'?></td>
					<td style="width:25%;"></td>
					<td style="width:25%;text-align:right;">Page No. {PAGENO}/{nbpg}</td>
				</tr>
			</table>
		</htmlpagefooter>
		<sethtmlpagefooter name="lastpage" value="on" />
	</div>
</div>