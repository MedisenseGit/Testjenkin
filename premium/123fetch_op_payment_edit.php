<?php
ob_start();
session_start();
error_reporting(0);  

$admin_id = $_SESSION['user_id'];

require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();	
$getPayList= $objQuery->mysqlSelect("*","out_patient_billing","doc_id='".$admin_id."' and md5(opb_temp_id)='".$_POST['temp_id']."'","","","","");
$getTotAmount= $objQuery->mysqlSelect("SUM(total_amount) as tot_amount","out_patient_billing","doc_id='".$admin_id."' and md5(opb_temp_id)='".$_POST['temp_id']."'","","","","");
print_r($getPayList);				

$output = '';
$output .= '<table class="invoice-items" width="100%" cellpadding="0" cellspacing="0">
			<tr><th style="width:10%;text-align:left;">Sl. No.</th>
				<th style="width:50%; text-align:left;">Narration</th>
				<th style="width:10%;text-align:left;">Amount</th>
				<th style="width:10%;text-align:left;">Qty</th>
				<th style="width:10%;text-align:left;">Discount</th>
				<th style="width:30%;text-align:left;">Total</th>
			</tr>';
$i=1;
foreach($getPayList as $row)
	
{
	
 $output .= '<tr><td style="width:10%;">'.$i.'</td>
                <td style="width:50%; text-align:left;">'.$row['narration'].'</td>
				<td style="width:10%;text-align:left;" class="alignright">Rs. '.$row['amount'].'</td>
				<td style="width:10%;text-align:left;" class="alignright">'.$row['qty'].'</td>
				<td style="width:10%;text-align:left;" class="alignright">'.$row['discount'].'%</td>
                <td style="width:30%;text-align:left;" class="alignright">Rs. '.$row['total_amount'].'</td>
				<td style="width:10%;"><a href="#" id="delRow" data-row-id='.$row['billing_id'].' ><img src="delete_icon.png" width="15" /></a></td>
            </tr>';
 $i++;
}
$output .='<tr class="total"><td style="width:10%;">&nbsp;</td>
							<td style="width:50%; text-align:left;">&nbsp;</td>
							<td style="width:10%;text-align:left;" class="alignright">&nbsp;</td>
							<td style="width:10%;text-align:left;" class="alignright">&nbsp;</td>
                            <td style="width:10%;text-align:left;" class="alignright">Total</td>
                            <td style="width:30%;text-align:left;" class="alignright" id="totalAmount">Rs. '.$getTotAmount[0]['tot_amount'].'</td>
            </tr></table>';

echo $output;


?>