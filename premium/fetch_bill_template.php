<?php
ob_start();
session_start();
error_reporting(0);  

$admin_id = $_SESSION['user_id'];

$template_id = $_POST['templateid'];

require_once("../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();	
$getPayList= mysqlSelect("*","out_patient_billing","doc_id='".$admin_id."' and opb_temp_id='".$template_id."'","","","","");
$getTotAmount= mysqlSelect("SUM(total_amount) as tot_amount","out_patient_billing","doc_id='".$admin_id."' and opb_temp_id='".$template_id."'","","","","");
$getPatientDetails = mysqlSelect("*","out_patient_billing_template","doc_id='".$admin_id."' and opb_temp_id='".$template_id."'","","","","");
$getPatientPayMethod = mysqlSelect("*","out_patient_billing_payment_method","opb_temp_id='".$template_id."'","opbpm_id asc","","","");


$output = '';
$output .= '<br /><table class="invoice-items" width="100%" cellpadding="0" cellspacing="0">
			<tr><th style="width:10%;text-align:left;">Sl. No.</th>
				<th style="width:40%; text-align:left;">Narration</th>
				<th style="width:10%;text-align:left;">Amount(Rs.)</th>
				<th style="width:10%;text-align:left;">Qty</th>
				<th style="width:20%;text-align:left;">Discount(%)</th>
				<th style="width:40%;text-align:left;">Total</th>
			</tr>';
$i=1;
foreach($getPayList as $row)
	
{
	
 $output .= '<tr><td style="width:10%;">'.$i.'</td>
                <td style="width:40%; text-align:left;"><input class="form-control" style="width:400px;margin-right: 10px;" size="60" type="text"  data-id="'.$row['billing_id'].'"  id="txtNarrationRow'.$i.'" name="txtNarrationRow'.$i.'" value="'.$row['narration'].'" onchange="return getNarrationChange(this.value,'.$i.','.$template_id.');"></td>
				<td style="width:10%;text-align:left;" class="alignright"><input class="form-control" style="width:100px;text-align:center;margin-right: 10px;" size="8" type="text"  data-id="'.$row['billing_id'].'"  id="txtAmountRow'.$i.'" name="txtAmountRow'.$i.'" value="'.$row['amount'].'" onchange="return getAmountChange(this.value,'.$i.','.$template_id.');"></td>
				<td style="width:10%;text-align:left;" class="alignright"><input class="form-control" style="width:60px;text-align:center;margin-right: 10px;" size="3" type="number"  data-id="'.$row['billing_id'].'"  id="txtQtyRow'.$i.'" name="txtQtyRow'.$i.'" value="'.$row['qty'].'" onchange="return getQtyChange(this.value,'.$i.','.$template_id.');"></td>
				<td style="width:20%;text-align:left;" class="alignright"><input class="form-control" style="width:60px; text-align:center;margin-right: 10px;" size="3" type="number"  data-id="'.$row['billing_id'].'"  id="txtDiscountRow'.$i.'" name="txtDiscountRow'.$i.'" value="'.$row['discount'].'" onchange="return getDiscountChange(this.value,'.$i.','.$template_id.');"></td>
                <td style="width:40%;text-align:left;" class="alignright" id="totRowBill'.$i.'">Rs. '.$row['total_amount'].'</td>
				<td style="width:10%;"><a href="#" id="delTempRow" data-template-id='.$template_id.' data-row-id='.$row['billing_id'].' ><img src="delete_icon.png" width="15" /></a></td>
            </tr>';
 $i++;
}
$output .='<tr class="total"><td style="width:10%;">&nbsp;</td>
							<td style="width:40%; text-align:left;">&nbsp;</td>
							<td style="width:10%;text-align:left;" class="alignright">&nbsp;</td>
							<td style="width:10%;text-align:left;" class="alignright">&nbsp;</td>
                            <td style="width:10%;text-align:left;" class="alignright">Total</td>
                            <td style="width:40%;text-align:left;" class="alignright" id="totalAmount" colspan="2" name="totalAmount">Rs. '.$getTotAmount[0]['tot_amount'].'</td>
            </tr></table>';

$output .='<input type="hidden" name="patientID" id="patientID" value="'.$getPatientDetails[0]['patient_id'].'"/>
			<input type="hidden" name="patientName" id="patientName" value="'.$getPatientDetails[0]['patient_name'].'"/>
			<input type="hidden" name="patientMobile" id="patientMobile" value="'.$getPatientDetails[0]['patient_mobile'].'"/>
			<input type="hidden" name="patientAddress" id="patientAddress" value="'.$getPatientDetails[0]['address'].'"/>
			<input type="hidden" name="pay_Type1" id="pay_Type1" value="'.$getPatientPayMethod[0]['pay_type'].'"/>
			<input type="hidden" name="pay_Narration1" id="pay_Narration1" value="'.$getPatientPayMethod[0]['narration'].'"/>
			<input type="hidden" name="pay_Amount1" id="pay_Amount1" value="'.$getPatientPayMethod[0]['amount'].'"/>
			<input type="hidden" name="pay_Type2" id="pay_Type2" value="'.$getPatientPayMethod[1]['pay_type'].'"/>
			<input type="hidden" name="pay_Narration2" id="pay_Narration2" value="'.$getPatientPayMethod[1]['narration'].'"/>
			<input type="hidden" name="pay_Amount2" id="pay_Amount2" value="'.$getPatientPayMethod[1]['amount'].'"/>
			<input type="hidden" name="pay_Type3" id="pay_Type3" value="'.$getPatientPayMethod[2]['pay_type'].'"/>
			<input type="hidden" name="pay_Narration3" id="pay_Narration3" value="'.$getPatientPayMethod[2]['narration'].'"/>
			<input type="hidden" name="pay_Amount3" id="pay_Amount3" value="'.$getPatientPayMethod[2]['amount'].'"/>
			<input type="hidden" name="credit_pay_type" id="credit_pay_type" value="'.$getPatientDetails[0]['payment_type'].'"/>
			<input type="hidden" name="credit_bill_payer" id="credit_bill_payer" value="'.$getPatientDetails[0]['credit_bill_payer'].'"/>';
			
			
			
echo $output;


?>
<script>
function getAmountChange(amount,rowId,templateId){
		var qty = $("#txtQtyRow"+rowId).val();
		var discount = $("#txtDiscountRow"+rowId).val();
				
		var bill_id = $("#txtAmountRow"+rowId).attr('data-id');
		calculatePrices(amount, qty, discount, bill_id, rowId,templateId);
}

function getQtyChange(qty,rowId,templateId){
		var amount = $("#txtAmountRow"+rowId).val();
		var discount = $("#txtDiscountRow"+rowId).val();
			
		var bill_id = $("#txtQtyRow"+rowId).attr('data-id');
			
		calculatePrices(amount, qty, discount, bill_id, rowId,templateId);
}

function getDiscountChange(discount,rowId,templateId){
		var qty = $("#txtQtyRow"+rowId).val();
		var amount = $("#txtAmountRow"+rowId).val();	
		var bill_id = $("#txtDiscountRow"+rowId).attr('data-id');
		
		calculatePrices(amount, qty, discount, bill_id, rowId,templateId);
}

	
		  function calculatePrices(amount, qty, discount, bill_id, rowId,templateId) {
		  console.log("Bill_id:"+bill_id+"Amount:"+amount+"Qty:"+qty+"Discount:"+discount+"RowId:"+rowId+"TemplateId:"+templateId);
		   
				
				var dataValue='bill_id='+bill_id+'&quantity='+qty+'&amount='+amount+'&discount='+discount+'&template_id='+templateId ;
				$.ajax({
					type: "POST",
					url: "update_cost.php",
					data:dataValue,
					dataType: 'json',
					success: function(data){
						console.log(data);
						
						var total_amt_res = data.error.getTotAmount;
						var total_row_res = data.error.getTotRowAmount;
						
						$("#totalAmount").text("Rs. "+total_amt_res);
						$("#totRowBill"+rowId).text("Rs. "+total_row_res);
						
					}
					});
			
		
		}
		</script>
	