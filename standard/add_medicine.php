<?php
ob_start();
error_reporting(0); 
session_start();


$admin_id = $_SESSION['user_id'];
$patient_id = $_SESSION['patient_id'];

if(empty($admin_id)){
	header("Location:index.php");
}
require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

date_default_timezone_set('Asia/Kolkata');
$curDate=date('Y-m-d H:i:s');

$medid=$_GET['medid'];
$patientid=$_GET['patientid'];


if(isset($medid) && !empty($medid)){

$param = split("-", $medid);
//echo $param[0];
//echo $param[1];
	if(is_numeric($param[0]) == false && $param[0] != "I"){
		$arrFileds_freq[]='pp_id';
		$arrValues_freq[]=time();
		$arrFileds_freq[]='med_trade_name';
		$arrValues_freq[]=$param[0];
		$arrFileds_freq[]='doc_id';
		$arrValues_freq[]=$admin_id;
		$arrFileds_freq[]='doc_type';
		$arrValues_freq[]="2";
		$arrFileds_freq[]='status';
		$arrValues_freq[]="1";
		$insert_medicine=$objQuery->mysqlInsert('doctor_temp_frequent_medicine',$arrFileds_freq,$arrValues_freq);
		$freq_id = mysql_insert_id(); //Get Frequent Medicine Id
	}
	else if($param[0] == "I"){
	
	$getMedicine= $objQuery->mysqlSelect("pharma_brand,pharma_generic","pharma_products","pp_id='".$param[1]."'","","","","");
		$chkFreqMedicine= $objQuery->mysqlSelect("med_frequency,med_timing,med_duration","doctor_frequent_medicine","pp_id='".$param[1]."' and doc_type='2'","","","","");
		if($chkFreqMedicine==true)
		{
			$arrFileds_freq[]='med_frequency';
			$arrValues_freq[]=$chkFreqMedicine[0]['med_frequency'];
			$arrFileds_freq[]='med_timing';
			$arrValues_freq[]=$chkFreqMedicine[0]['med_timing'];
			$arrFileds_freq[]='med_duration';
			$arrValues_freq[]=$chkFreqMedicine[0]['med_duration'];
		}
		
		$arrFileds_freq[]='pp_id';
		$arrValues_freq[]=$param[1];
		$arrFileds_freq[]='med_trade_name';
		$arrValues_freq[]=$getMedicine[0]['pharma_brand'];
		$arrFileds_freq[]='med_generic_name';
		$arrValues_freq[]=$getMedicine[0]['pharma_generic'];
		$arrFileds_freq[]='doc_id';
		$arrValues_freq[]=$admin_id;
		$arrFileds_freq[]='doc_type';
		$arrValues_freq[]="2";
		$arrFileds_freq[]='status';
		$arrValues_freq[]="1";

		$insert_medicine=$objQuery->mysqlInsert('doctor_temp_frequent_medicine',$arrFileds_freq,$arrValues_freq);
		$freq_id = mysql_insert_id(); //Get Frequent Medicine Id
		
	}
	else
	{
		$getFreqCount= $objQuery->mysqlSelect("*","doctor_frequent_medicine","freq_medicine_id='".$param[0]."' and doc_type='2'","","","","");
		
		$arrFileds_freq[]='pp_id';
		$arrValues_freq[]=$getFreqCount[0]['pp_id'];
		$arrFileds_freq[]='med_trade_name';
		$arrValues_freq[]=$getFreqCount[0]['med_trade_name'];
		$arrFileds_freq[]='med_generic_name';
		$arrValues_freq[]=$getFreqCount[0]['med_generic_name'];
		$arrFileds_freq[]='med_frequency';
		$arrValues_freq[]=$getFreqCount[0]['med_frequency'];
		$arrFileds_freq[]='med_timing';
		$arrValues_freq[]=$getFreqCount[0]['med_timing'];
		$arrFileds_freq[]='med_duration';
		$arrValues_freq[]=$getFreqCount[0]['med_duration'];
		$arrFileds_freq[]='doc_id';
		$arrValues_freq[]=$admin_id;
		$arrFileds_freq[]='doc_type';
		$arrValues_freq[]="2";
		$arrFileds_freq[]='status';
		$arrValues_freq[]="1";

		$insert_medicine=$objQuery->mysqlInsert('doctor_temp_frequent_medicine',$arrFileds_freq,$arrValues_freq);
		$freq_id = mysql_insert_id(); //Get Frequent Medicine Id
	}

}

if(isset($_GET['editmedid']) && !empty($_GET['editmedid'])){

$param = split("-", $_GET['editmedid']);
//echo $param[0];
//echo $param[1];
	if(is_numeric($param[0]) == false && $param[0] != "I"){
		$arrFileds_freq[]='pp_id';
		$arrValues_freq[]=time();
		$arrFileds_freq[]='prescription_trade_name';
		$arrValues_freq[]=$param[0];
		$arrFileds_freq[]='doc_id';
		$arrValues_freq[]=$admin_id;
		$arrFileds_freq[]='episode_id';
		$arrValues_freq[]=$_GET['episodeid'];
		$arrFileds_freq[]='prescription_date_time';
		$arrValues_freq[]=$curDate;
		
		$insert_medicine=$objQuery->mysqlInsert('patient_episode_prescriptions',$arrFileds_freq,$arrValues_freq);
		
	}
	else if($param[0] == "I"){
	
	$getMedicine= $objQuery->mysqlSelect("pharma_brand,pharma_generic","pharma_products","pp_id='".$param[1]."'","","","","");
		$chkFreqMedicine= $objQuery->mysqlSelect("med_frequency,med_timing,med_duration","doctor_frequent_medicine","pp_id='".$param[1]."' and doc_type='2'","","","","");
		if($chkFreqMedicine==true)
		{
			$arrFileds_freq[]='prescription_frequency';
			$arrValues_freq[]=$chkFreqMedicine[0]['med_frequency'];
			$arrFileds_freq[]='timing';
			$arrValues_freq[]=$chkFreqMedicine[0]['med_timing'];
			$arrFileds_freq[]='duration';
			$arrValues_freq[]=$chkFreqMedicine[0]['med_duration'];
		}
				
		$arrFileds_freq[]='pp_id';
		$arrValues_freq[]=$param[1];
		$arrFileds_freq[]='prescription_trade_name';
		$arrValues_freq[]=$getMedicine[0]['pharma_brand'];
		$arrFileds_freq[]='prescription_generic_name';
		$arrValues_freq[]=$getMedicine[0]['pharma_generic'];
		$arrFileds_freq[]='episode_id';
		$arrValues_freq[]=$_GET['episodeid'];
		$arrFileds_freq[]='doc_id';
		$arrValues_freq[]=$admin_id;
		$arrFileds_freq[]='prescription_date_time';
		$arrValues_freq[]=$curDate;
		$insert_medicine=$objQuery->mysqlInsert('patient_episode_prescriptions',$arrFileds_freq,$arrValues_freq);
		
	}
	else
	{
		$getFreqCount= $objQuery->mysqlSelect("*","doctor_frequent_medicine","freq_medicine_id='".$param[0]."' and doc_type='2'","","","","");
		
		$arrFileds_freq[]='pp_id';
		$arrValues_freq[]=$getFreqCount[0]['pp_id'];
		$arrFileds_freq[]='prescription_trade_name';
		$arrValues_freq[]=$getFreqCount[0]['med_trade_name'];
		$arrFileds_freq[]='prescription_generic_name';
		$arrValues_freq[]=$getFreqCount[0]['med_generic_name'];
		$arrFileds_freq[]='prescription_frequency';
		$arrValues_freq[]=$getFreqCount[0]['med_frequency'];
		$arrFileds_freq[]='timing';
		$arrValues_freq[]=$getFreqCount[0]['med_timing'];
		$arrFileds_freq[]='duration';
		$arrValues_freq[]=$getFreqCount[0]['med_duration'];
		$arrFileds_freq[]='episode_id';
		$arrValues_freq[]=$_GET['episodeid'];
		$arrFileds_freq[]='doc_id';
		$arrValues_freq[]=$admin_id;
		$arrFileds_freq[]='prescription_date_time';
		$arrValues_freq[]=$curDate;
		$insert_medicine=$objQuery->mysqlInsert('patient_episode_prescriptions',$arrFileds_freq,$arrValues_freq);
	}

}

if(isset($_GET['updatefreqmedid']) && !empty($_GET['updatefreqmedid'])){
	
	if(isset($_GET['tradename']))
	{
				$arrFileds_freq[]='med_trade_name';
				$arrValues_freq[]=$_GET['tradename'];
	}
	if(isset($_GET['genericname']))
	{
				$arrFileds_freq[]='med_generic_name';
				$arrValues_freq[]=$_GET['genericname'];
	}
	if(isset($_GET['frequency']))
	{
				$arrFileds_freq[]='med_frequency';
				$arrValues_freq[]=$_GET['frequency'];
	}
	if(isset($_GET['medtiming']))
	{
				$arrFileds_freq[]='med_timing';
				$arrValues_freq[]=$_GET['medtiming'];
	}
	if(isset($_GET['duration']))
	{
				$arrFileds_freq[]='med_duration';
				$arrValues_freq[]=$_GET['duration'];
	}
		
	$update_medicine=$objQuery->mysqlUpdate('doctor_temp_frequent_medicine',$arrFileds_freq,$arrValues_freq,"temp_freq_id = '".$_GET['updatefreqmedid']."'");

}

if(isset($_GET['editfreqmedid']) && !empty($_GET['editfreqmedid'])){
	
	if(isset($_GET['tradename']))
	{
				$arrFileds_freq[]='prescription_trade_name';
				$arrValues_freq[]=$_GET['tradename'];
	}
	if(isset($_GET['genericname']))
	{
				$arrFileds_freq[]='prescription_generic_name';
				$arrValues_freq[]=$_GET['genericname'];
	}
	if(isset($_GET['frequency']))
	{
				$arrFileds_freq[]='prescription_frequency';
				$arrValues_freq[]=$_GET['frequency'];
	}
	if(isset($_GET['medtiming']))
	{
				$arrFileds_freq[]='timing';
				$arrValues_freq[]=$_GET['medtiming'];
	}
	if(isset($_GET['duration']))
	{
				$arrFileds_freq[]='duration';
				$arrValues_freq[]=$_GET['duration'];
	}
		
	$update_medicine=$objQuery->mysqlUpdate('patient_episode_prescriptions',$arrFileds_freq,$arrValues_freq,"episode_prescription_id = '".$_GET['editfreqmedid']."'");

}

if(isset($_GET['delprescid']) && !empty($_GET['delprescid'])){
	$objQuery->mysqlDelete('doctor_temp_frequent_medicine',"temp_freq_id = '".$_GET['delprescid']."'");
	
}
if(isset($_GET['clearall']) && !empty($_GET['clearall'])){
	$objQuery->mysqlDelete('doctor_temp_frequent_medicine',"doc_id='".$admin_id."' and doc_type ='2' and status='1'");
}


if(isset($_GET['deleditprescid']) && !empty($_GET['deleditprescid'])){
	$objQuery->mysqlDelete('patient_episode_prescriptions',"episode_prescription_id = '".$_GET['deleditprescid']."'");
	
}

if(isset($_GET['prevprescid']) && !empty($_GET['prevprescid'])){
	$getTemplateDetails= $objQuery->mysqlSelect("*","patient_episode_prescriptions","episode_id='".$_GET['prevprescid']."'","","","","");
while(list($key,$value) = each($getTemplateDetails))
	{		
			$arrFileds_freq=array();
			$arrValues_freq=array();
			
		$arrFileds_freq[]='pp_id';
		$arrValues_freq[]=$value['pp_id'];
		$arrFileds_freq[]='med_trade_name';
		$arrValues_freq[]=$value['prescription_trade_name'];
		$arrFileds_freq[]='med_generic_name';
		$arrValues_freq[]=$value['prescription_generic_name'];
		$arrFileds_freq[]='med_frequency';
		$arrValues_freq[]=$value['prescription_frequency'];
		$arrFileds_freq[]='med_timing';
		$arrValues_freq[]=$value['timing'];
		$arrFileds_freq[]='med_duration';
		$arrValues_freq[]=$value['duration'];
		$arrFileds_freq[]='doc_id';
		$arrValues_freq[]=$admin_id;
		$arrFileds_freq[]='doc_type';
		$arrValues_freq[]="2";
		$arrFileds_freq[]='status';
		$arrValues_freq[]="1";

		$insert_medicine=$objQuery->mysqlInsert('doctor_temp_frequent_medicine',$arrFileds_freq,$arrValues_freq);
		
		
	}
	
}
if(isset($_GET['editprevprescid']) && !empty($_GET['editprevprescid'])){
	$getTemplateDetails= $objQuery->mysqlSelect("*","patient_episode_prescriptions","episode_id='".$_GET['editprevprescid']."'","","","","");
while(list($key,$value) = each($getTemplateDetails))
	{	
		$arrFileds_freq = array();
		$arrValues_freq = array();
		
		$arrFileds_freq[]='pp_id';
		$arrValues_freq[]=$value['pp_id'];
		$arrFileds_freq[]='prescription_trade_name';
		$arrValues_freq[]=$value['prescription_trade_name'];
		$arrFileds_freq[]='prescription_generic_name';
		$arrValues_freq[]=$value['prescription_generic_name'];
		$arrFileds_freq[]='prescription_frequency';
		$arrValues_freq[]=$value['prescription_frequency'];
		$arrFileds_freq[]='timing';
		$arrValues_freq[]=$value['timing'];
		$arrFileds_freq[]='duration';
		$arrValues_freq[]=$value['duration'];
		$arrFileds_freq[]='episode_id';
		$arrValues_freq[]=$_GET['episodeid'];
		$arrFileds_freq[]='doc_id';
		$arrValues_freq[]=$admin_id;
		

		$insert_medicine=$objQuery->mysqlInsert('patient_episode_prescriptions',$arrFileds_freq,$arrValues_freq);
		
		
	}
	
}
if(isset($_GET['loadtemplate']) && !empty($_GET['loadtemplate'])){
	$getTemplateDetails= $objQuery->mysqlSelect("*","doc_medicine_prescription_template_details","template_id='".$_GET['loadtemplate']."'","","","","");
while(list($key,$value) = each($getTemplateDetails))
	{		
			$arrFileds_freq=array();
			$arrValues_freq=array();
			
		$arrFileds_freq[]='pp_id';
		$arrValues_freq[]=$value['pp_id'];
		$arrFileds_freq[]='med_trade_name';
		$arrValues_freq[]=$value['prescription_trade_name'];
		$arrFileds_freq[]='med_generic_name';
		$arrValues_freq[]=$value['prescription_generic_name'];
		$arrFileds_freq[]='med_frequency';
		$arrValues_freq[]=$value['prescription_frequency'];
		$arrFileds_freq[]='med_timing';
		$arrValues_freq[]=$value['prescription_timing'];
		$arrFileds_freq[]='med_duration';
		$arrValues_freq[]=$value['prescription_duration'];
		$arrFileds_freq[]='doc_id';
		$arrValues_freq[]=$admin_id;
		$arrFileds_freq[]='doc_type';
		$arrValues_freq[]="2";
		$arrFileds_freq[]='status';
		$arrValues_freq[]="1";

		$insert_medicine=$objQuery->mysqlInsert('doctor_temp_frequent_medicine',$arrFileds_freq,$arrValues_freq);
		
		
	}
	
}

if(isset($_GET['editloadtemplate']) && !empty($_GET['editloadtemplate'])){
	$getTemplateDetails= $objQuery->mysqlSelect("*","doc_medicine_prescription_template_details","template_id='".$_GET['editloadtemplate']."'","","","","");
while(list($key,$value) = each($getTemplateDetails))
	{		
			$arrFileds_freq=array();
			$arrValues_freq=array();
			
		$arrFileds_freq[]='pp_id';
		$arrValues_freq[]=$value['pp_id'];
		$arrFileds_freq[]='prescription_trade_name';
		$arrValues_freq[]=$value['prescription_trade_name'];
		$arrFileds_freq[]='prescription_generic_name';
		$arrValues_freq[]=$value['prescription_generic_name'];
		$arrFileds_freq[]='prescription_frequency';
		$arrValues_freq[]=$value['prescription_frequency'];
		$arrFileds_freq[]='timing';
		$arrValues_freq[]=$value['prescription_timing'];
		$arrFileds_freq[]='duration';
		$arrValues_freq[]=$value['prescription_duration'];
		$arrFileds_freq[]='episode_id';
		$arrValues_freq[]=$_GET['episodeid'];
		$arrFileds_freq[]='doc_id';
		$arrValues_freq[]=$admin_id;
	
		$insert_medicine=$objQuery->mysqlInsert('patient_episode_prescriptions',$arrFileds_freq,$arrValues_freq);
		
		
	}
	
}

if(isset($medid) || isset($_GET['loadtemplate']) || isset($_GET['prevprescid']) || isset($_GET['updatefreqmedid']))
{
//$getTmplate= $objQuery->mysqlSelect("*","doc_medicine_prescription_template_details","doc_id='".$admin_id."' and patient_id='".$patientid."' and status=1","","","","");
$getTmplate= $objQuery->mysqlSelect("*","doctor_temp_frequent_medicine","doc_id='".$admin_id."' and doc_type='2' and status='1'","temp_freq_id asc","","","");
		

?>
							<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  action="my_patient_profile_save.php"  name="frmAddEpisode" id="frmAddEpisode">
										<input type="hidden" name="patient_id" value="<?php echo $patientid; ?>">
							
										
									<a class="btn btn-xs btn-white pull-right clear_all"><i class="fa fa-trash"></i> Clear All</a>			
									<table  cellpadding="2" cellspacing="2" border="1" class="table table-bordered" width="82%">
																			<thead>
																				<th style="width:30px;">Medicine</th>
																				<th style="width:30px;">Generic Name</th>
																				<!--<th>Dosage</th>
																				<th>Route</th>-->
																				<th style="width:30px;">Dosage Frequency</th>
																				<th style="width:30px;">Timing</th>
																				<th style="width:30px;">Duration</th>
																				<!--<th>Note</th>-->
																				<th></th>
																			</thead>
																			
																			<tbody>
																			<?php foreach($getTmplate as $TempList) { 
																			$get_Timing = $objQuery->mysqlSelect("language_id,english","doc_medicine_timing_language","language_id='".$TempList['med_timing']."'","","","","");
																			$get_Timing_list = $objQuery->mysqlSelect("language_id,english","doc_medicine_timing_language","","priority ASC","","","");
																			$check_pharma = $objQuery->mysqlSelect("generic_id","pharma_products","pp_id='".$TempList['pp_id']."'","","","","");
																			$check_allergy = $objQuery->mysqlSelect("generic_id","doc_patient_drug_allergy_active","patient_id='".$patientid."' and generic_id='".$check_pharma[0]['generic_id']."'","","","","");
																			
																			?>
																			<tr id="medRow<?php echo $TempList['temp_freq_id'];?>">
																				<td><input type="hidden" name="product_id[]" value="<?php echo $TempList['pp_id'];?>"/><?php if(COUNT($check_allergy)>0) { ?><img src="danger_icon.gif" width="20" /><?php } ?> <input type="text" class="form-control tagName tradename" name="prescription_trade_name[]" data-episode-id="0" data-freq-medicine-id="<?php echo $TempList['temp_freq_id'];?>" value="<?php echo $TempList['med_trade_name'];?>" placeholder="Medicine" style="width:280px;"></td>
																				<td><input type="text" class="form-control tagName genericname" name="prescription_generic_name[]" data-episode-id="0" data-freq-medicine-id="<?php echo $TempList['temp_freq_id'];?>" value="<?php echo $TempList['med_generic_name'];?>" placeholder="Generic Name" style="width:290px;"></td>
																				<td><input type="text" class="form-control tagName frequency" name="prescription_frequency[]" data-episode-id="0" data-freq-medicine-id="<?php echo $TempList['temp_freq_id'];?>" value="<?php echo $TempList['med_frequency'];?>" placeholder="Frequency" style="width:70px;"></td>
																				<td>
																				<select name="slctTiming" class="form-control medtiming" data-freq-medicine-id="<?php echo $TempList['temp_freq_id'];?>" data-episode-id="0" style="width:160px;" >
																				<?php if($get_Timing>0){
																				?>
																				<option value="<?php echo $get_Timing[0]['language_id']; ?>" selected><?php echo $get_Timing[0]['english']; ?></option>
																				<?php
																				
																				while(list($key_lng, $value_lng) = each($get_Timing_list)){ ?>
																				<option value="<?php echo $value_lng['language_id']; ?>"><?php echo $value_lng['english']; ?></option>
																				<?php } 
																				} else { ?>
																				<option value="">Select</option>
																				<?php while(list($key_lng, $value_lng) = each($get_Timing_list)){ ?>
																				<option value="<?php echo $value_lng['language_id']; ?>"><?php echo $value_lng['english']; ?></option>
																				<?php } 
																				}?>
																				</select>
																				
																				<td><input type="text" class="form-control tagName duration" name="prescription_duration[]" data-episode-id="0"  data-freq-medicine-id="<?php echo $TempList['temp_freq_id'];?>" value="<?php echo $TempList['med_duration'];?>" placeholder="Duration" style="width:90px;"></td>
																				<!--<td><textarea name="prescription_instruction" id="prescription_instruction[]" placeholder="Note" style="width:100px;border:none;"></textarea></td>-->
																				<td class="text-center"><a class="del_medicine" data-medicine-id="<?php echo $TempList['temp_freq_id'];?>"><img src="https://medisensecrm.com/premium/trash.png" width="15"/></a> </td>
																			</tr>
																			<?php } ?>
																			</tbody>
																			
																		</table>	
										
							
						</form>
<?php } 
if(isset($_GET['editmedid']) || isset($_GET['editloadtemplate']) || isset($_GET['editprevprescid']) || isset($_GET['editfreqmedid']))
{
$getTmplate= $objQuery->mysqlSelect("*","patient_episode_prescriptions","episode_id='".$_GET['episodeid']."'","episode_prescription_id asc","","","");
								if(COUNT($getTmplate)>0){
								?>
														<a class="btn btn-xs btn-white pull-right clear_all"><i class="fa fa-trash"></i> Clear All</a>			
														<table  cellpadding="2" cellspacing="2" border="1" class="table table-bordered" width="82%">
																	<thead>
																				<th style="width:30px;">Medicine</th>
																				<th style="width:30px;">Generic Name</th>
																				<th style="width:30px;">Dosage Frequency</th>
																				<th style="width:30px;">Timing</th>
																				<th style="width:30px;">Duration</th>
																				<!--<th>Note</th>-->
																				<th></th>
																			</thead>
																			
																			<tbody>
																			<?php foreach($getTmplate as $TempList) { 
																			$get_Timing = $objQuery->mysqlSelect("language_id,english","doc_medicine_timing_language","language_id='".$TempList['timing']."'","","","","");
																			$get_Timing_list = $objQuery->mysqlSelect("language_id,english","doc_medicine_timing_language","","priority ASC","","","");
																			$check_pharma = $objQuery->mysqlSelect("generic_id","pharma_products","pp_id='".$TempList['pp_id']."'","","","","");
																			$check_allergy = $objQuery->mysqlSelect("generic_id","doc_patient_drug_allergy_active","patient_id='".$patient_id."' and generic_id='".$check_pharma[0]['generic_id']."' and doc_type = '2'","","","","");
																			
																			?>
																			<tr id="medRow<?php echo $TempList['episode_prescription_id'];?>">
																				<td><input type="hidden" name="product_id[]" value="<?php echo $TempList['pp_id'];?>"/><?php if(COUNT($check_allergy)>0) { ?><img src="danger_icon.gif" width="20" /><?php } ?> <input type="text" class="form-control tagName tradename" name="prescription_trade_name[]" data-episode-id="<?php echo $_GET['episodeid']; ?>" data-freq-medicine-id="<?php echo $TempList['episode_prescription_id'];?>" value="<?php echo $TempList['prescription_trade_name'];?>" placeholder="Medicine" style="width:220px;"></td>
																				<td><input type="text" class="form-control tagName genericname" name="prescription_generic_name[]"  data-freq-medicine-id="<?php echo $TempList['episode_prescription_id'];?>" value="<?php echo $TempList['prescription_generic_name'];?>" data-episode-id="<?php echo $_GET['episodeid']; ?>" placeholder="Generic Name" style="width:290px;"></td>
																				<td><input type="text" class="form-control tagName frequency" name="prescription_frequency[]"  data-freq-medicine-id="<?php echo $TempList['episode_prescription_id'];?>" value="<?php echo $TempList['prescription_frequency'];?>" data-episode-id="<?php echo $_GET['episodeid']; ?>" placeholder="Frequency" style="width:70px;"></td>
																				<td>
																				<select name="slctTiming" class="form-control medtiming" data-episode-id="<?php echo $_GET['episodeid']; ?>" data-freq-medicine-id="<?php echo $TempList['episode_prescription_id'];?>" style="width:160px;" >
																				<?php if($get_Timing>0){
																				?>
																				<option value="<?php echo $get_Timing[0]['language_id']; ?>" selected><?php echo $get_Timing[0]['english']; ?></option>
																				<?php
																				
																				while(list($key_lng, $value_lng) = each($get_Timing_list)){ ?>
																				<option value="<?php echo $value_lng['language_id']; ?>"><?php echo $value_lng['english']; ?></option>
																				<?php } 
																				} else { ?>
																				<option value="">Select</option>
																				<?php while(list($key_lng, $value_lng) = each($get_Timing_list)){ ?>
																				<option value="<?php echo $value_lng['language_id']; ?>"><?php echo $value_lng['english']; ?></option>
																				<?php } 
																				}?>
																				</select>
																				
																				<td><input type="text" class="form-control tagName duration" name="prescription_duration[]" data-episode-id="<?php echo $_GET['episodeid']; ?>" data-freq-medicine-id="<?php echo $TempList['episode_prescription_id'];?>" value="<?php echo $TempList['duration'];?>" placeholder="Duration" style="width:90px;"></td>
																				<!--<td><textarea name="prescription_instruction" id="prescription_instruction[]" placeholder="Note" style="width:100px;border:none;"></textarea></td>-->
																				<td class="text-center"><a class="edit_del_medicine" data-medicine-id="<?php echo $TempList['episode_prescription_id'];?>"><img src="https://medisensecrm.com/premium/trash.png" width="15"/></a> </td>
																			</tr>
																			<?php } ?>
																			</tbody>
																			
																		</table>
								<?php }
}								?>