<?php
ob_start();
error_reporting(0);
session_start();
$admin_id = $_SESSION['user_id'];

require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
ob_start();
$template_id=$_POST["template_id"];
$prescription_seq=$_POST["prescription_seq"];

$strResponse = '';
if(!empty($_POST["template_id"])) {
	$template_details = $objQuery->mysqlSelect("*","doc_patient_episode_prescription_template_details","template_id='".$template_id."' and admin_id = '". $admin_id ."' ","","","","");
	$strResponse = array();
	//$prescription_seq = 0;
	while (list($template_detail_key, $template_detail_val) = each($template_details))
	{
		$prescription_seq++;
		//$strResponse .= '<tr class="link1" id="prescription_del_'. $prescription_seq .'_row">';
			$strResponse .= '<tr class="link1" id="prescription_del_'. $template_id.'_'.$prescription_seq .'_row">';
						$strResponse .=  '<td class="fields">';
							//$strResponse .=  '<textarea name="prescription_trade_name['. $prescription_seq .']" id="prescription_trade_name_'. $prescription_seq .'" placeholder="Trade" style="width:100px;border:none;">'. $template_detail_val['prescription_trade_name'] .'</textarea>';
							$strResponse .=  '<input type="text" class="tagName  expandwidth" name="prescription_trade_name['. $prescription_seq .']" id="prescription_trade_name_'. $prescription_seq .'" placeholder="Trade" style="width:100px;border:none;" value="'. $template_detail_val['prescription_trade_name'] .'">';
						$strResponse .=  '</td>';
						$strResponse .=  '<td class="fields">';
							//$strResponse .=  '<textarea name="prescription_generic_name['. $prescription_seq .']" id="prescription_generic_name_'. $prescription_seq .'" placeholder="Generic" style="width:100px;border:none;">'. $template_detail_val['prescription_generic_name'] .'</textarea>';
							$strResponse .=  '<input type="text" class="genericName expandwidth" name="prescription_generic_name['. $prescription_seq .']" id="prescription_generic_name_'. $prescription_seq .'" placeholder="Generic" style="width:100px;border:none;" value="'. $template_detail_val['prescription_generic_name'] .'">';
						$strResponse .=  '</td>';
						$strResponse .=  '<td class="fields">';
							//$strResponse .=  '<textarea name="prescription_generic_name['. $prescription_seq .']" id="prescription_generic_name_'. $prescription_seq .'" placeholder="Generic" style="width:100px;border:none;">'. $template_detail_val['prescription_generic_name'] .'</textarea>';
							$strResponse .=  '<input type="text" class="dosageName expandwidth" name="prescription_dosage_name['. $prescription_seq .']" id="prescription_dosage_name_'. $prescription_seq .'" placeholder="Dosage" style="width:100px;border:none;" value="'. $template_detail_val['prescription_dosage_name'] .'">';
						$strResponse .=  '</td>';
						$strResponse .=  '<td class="fields">';
							//$strResponse .=  '<textarea name="prescription_route['. $prescription_seq .']" id="prescription_route_'. $prescription_seq .'" placeholder="Route" style="width:100px;border:none;">'. $template_detail_val['prescription_route'] .'</textarea>';
							$strResponse .=  '<input type="text" class="route expandwidth" name="prescription_route['. $prescription_seq .']" id="prescription_route_'. $prescription_seq .'" placeholder="Route" style="width:100px;border:none;" value="'. $template_detail_val['prescription_route'] .'">';
						$strResponse .=  '</td>';
						$strResponse .=  '<td class="fields">';
							//$strResponse .=  '<textarea name="prescription_frequency['. $prescription_seq .']" id="prescription_frequency_'. $prescription_seq .'" placeholder="Freq" style="width:100px;border:none;">'. $template_detail_val['prescription_frequency'] .'</textarea>';
							$strResponse .=  '<input type="text" class="frequency expandwidth" name="prescription_frequency['. $prescription_seq .']" id="prescription_frequency_'. $prescription_seq .'" placeholder="Freq" style="width:100px;border:none;" value="'. $template_detail_val['prescription_frequency'] .'">';
						$strResponse .=  '</td>';
						$strResponse .=  '<td class="fields">';
							$strResponse .=  '<textarea name="prescription_instruction['. $prescription_seq .']" id="prescription_instruction_'. $prescription_seq .'" placeholder="Instruction" style="width:100px;border:none;">'. $template_detail_val['prescription_instruction'] .'</textarea>';
						$strResponse .=  '</td>';
						$strResponse .=  '<td class="fields">';
							$strResponse .=  '<button id="prescription_del_'. $template_id.'_'.$prescription_seq .'" class="delbutton" >Delete</button>';
						$strResponse .=  '</td>';
					$strResponse .=  '</tr>';
	}
}
	echo $strResponse;
?>