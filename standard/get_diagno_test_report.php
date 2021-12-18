<?php
ob_start();
error_reporting(0); 
session_start();


$admin_id = $_SESSION['user_id'];
$patient_id = $_SESSION['patient_id'];
require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();



$patientid=$_GET['patientid'];

if(isset($_GET['addinvestid']))
{
	$params     = split("-", $_GET['addinvestid']);
	$investid = $params[0];
	//To check whether chosen test is listed in group test table
	$getCheckTest= $objQuery->mysqlSelect("id,test_id,group_test","patient_diagnosis_tests","id='".$investid."'","","","","");
	if($getCheckTest[0]['group_test']=="Y")
	{
		$getTestList= $objQuery->mysqlSelect("test_id as group_test_id,sub_test_id as main_test_id","patient_diagnosis_group_tests ","test_id='".$getCheckTest[0]['test_id']."'","","","","");	

		while(list($key, $value) = each($getTestList)){
		$getTestName= $objQuery->mysqlSelect("test_id,test_name_site_name,normal_range,is_mref_range,min_range,max_range,test_units","patient_diagnosis_tests","test_id='".$value['main_test_id']."'","","","","");
	
		$arrFileds = array();
		$arrValues = array();
		
		$arrFileds[]='main_test_id';
		$arrValues[]=$getTestName[0]['test_id'];
		
		$arrFileds[]='group_test_id';
		$arrValues[]=$value['group_test_id'];	
		
		$arrFileds[]='test_name';
		$arrValues[]=$getTestName[0]['test_name_site_name'];
		
		if($getTestName[0]['is_mref_range'] == 'N'){
		$arrFileds[]='normal_range';
		$arrValues[]=$getTestName[0]['normal_range'];
		}
		
		$arrFileds[]='episode_id';
		$arrValues[]=$_GET['episodeid'];
		
		$arrFileds[]='patient_id';
		$arrValues[]=$_GET['patientid'];
		
		$arrFileds[]='doc_id';
		$arrValues[]=$_GET['docid'];
		
		$arrFileds[]='doc_type';
		$arrValues[]="2";
		$arrFileds[]='status';
		$arrValues[]="0";
		$check_temp_invest_active = $objQuery->mysqlSelect("*","patient_temp_investigation","main_test_id='".$getTestName[0]['test_id']."' and patient_id='".$_GET['patientid']."' and doc_id='".$admin_id."' and doc_type='2'","","","","");
		if(COUNT($check_temp_invest_active)==0){
		$insert_temp_value=$objQuery->mysqlInsert('patient_temp_investigation',$arrFileds,$arrValues);
		}
	  } //End while
	}
	if($getCheckTest[0]['group_test']=="N")
	{
		$getTestList= $objQuery->mysqlSelect("test_id,test_name_site_name,normal_range,is_mref_range,min_range,normal_range,max_range,test_units","patient_diagnosis_tests","id='".$getCheckTest[0]['id']."'","","","","");	
		
		$arrFileds[]='main_test_id';
		$arrValues[]=$getTestList[0]['test_id'];
		
		$arrFileds[]='group_test_id';
		$arrValues[]=$getTestList[0]['test_id'];	
		
		$arrFileds[]='test_name';
		$arrValues[]=$getTestList[0]['test_name_site_name'];
		
		if($getTestList[0]['is_mref_range'] == 'N'){
		$arrFileds[]='normal_range';
		$arrValues[]=$getTestList[0]['normal_range'];
		}
		
		$arrFileds[]='episode_id';
		$arrValues[]=$_GET['episodeid'];
		
		$arrFileds[]='patient_id';
		$arrValues[]=$_GET['patientid'];
		
		$arrFileds[]='doc_id';
		$arrValues[]=$_GET['docid'];
		
		$arrFileds[]='doc_type';
		$arrValues[]="2";
		$arrFileds[]='status';
		$arrValues[]="0";
		$check_temp_invest_active = $objQuery->mysqlSelect("*","patient_temp_investigation","main_test_id='".$getTestList[0]['test_id']."' and patient_id='".$_GET['patientid']."' and doc_id='".$admin_id."' and doc_type='2'","","","","");
		if(COUNT($check_temp_invest_active)==0){
		$insert_temp_value=$objQuery->mysqlInsert('patient_temp_investigation',$arrFileds,$arrValues);
		}
	}
	
	$getTestDetails= $objQuery->mysqlSelect("*","patient_temp_investigation","patient_id='".$_GET['patientid']."' and episode_id='".$_GET['episodeid']."'","","","","");

	?>
	<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  action="my_patient_profile_save.php"  name="frmAddInvest" >
			<table class="table table-bordered">
										<thead>
										<tr>
										<th>Test</th>
										<th>Normal Value</th>
										<th>Actual Value</th>
										
										</tr>
										<thead>
										<tbody>
										
										<?php 
										while(list($key_invest, $value_invest) = each($getTestDetails))	
										{  
										?>
										<tr>
												<td>
												<input type="hidden" name="main_test_id[]" value="<?php echo $value_invest['main_test_id']; ?>"/>
												<input type="hidden" name="investigation_id[]" value="<?php echo $value_invest['pti_id']; ?>"/><?php echo $value_invest['test_name']; ?></td>
												<td><?php echo $value_invest['normal_range']; ?></td>
												<td><input type="text" name="actualVal[]"  value="<?php echo $value_invest['test_actual_value']; ?>" placeholder="" style="width:100px;"></td>
												</tr>
												
										<?php } ?>
				</tbody>
			</table>
		</form>
<?php 	
}


if(isset($_GET['investid']) || isset($_GET['freqinvestid'])){
	$params     = split("-", $_GET['investid']);
	$investid = $params[0];
	//To check whether chosen test is listed in group test table
	$getCheckTest= $objQuery->mysqlSelect("id,test_id,group_test","patient_diagnosis_tests","id='".$investid."'","","","","");
	if($getCheckTest[0]['group_test']=="Y")
	{
		$getTestList= $objQuery->mysqlSelect("test_id as group_test_id,sub_test_id as main_test_id","patient_diagnosis_group_tests ","test_id='".$getCheckTest[0]['test_id']."'","","","","");	

		while(list($key, $value) = each($getTestList)){
		$getTestName= $objQuery->mysqlSelect("test_id,test_name_site_name,department,normal_range,is_mref_range,min_range,max_range,test_units","patient_diagnosis_tests","test_id='".$value['main_test_id']."'","","","","");
	
		$arrFileds = array();
		$arrValues = array();
		
		$arrFileds[]='main_test_id';
		$arrValues[]=$getTestName[0]['test_id'];
		
		$arrFileds[]='group_test_id';
		$arrValues[]=$value['group_test_id'];	
		
		$arrFileds[]='test_name';
		$arrValues[]=$getTestName[0]['test_name_site_name'];
		
		$arrFileds[]='department';
		$arrValues[]=$getTestName[0]['department'];
		
		if($getTestName[0]['is_mref_range'] == 'N'){
		$arrFileds[]='normal_range';
		$arrValues[]=$getTestName[0]['normal_range'];
		}
		
		$arrFileds[]='patient_id';
		$arrValues[]=$patientid;
		
		$arrFileds[]='doc_id';
		$arrValues[]=$admin_id;
		
		$arrFileds[]='doc_type';
		$arrValues[]="2";
		$arrFileds[]='status';
		$arrValues[]="1";
		$checkTestActive= $objQuery->mysqlSelect("pti_id","patient_temp_investigation","main_test_id='".$getTestName[0]['test_id']."' and patient_id='".$patientid."' and doc_id='".$admin_id."' and doc_type='2' and status='1'","","","","");	
		if(COUNT($checkTestActive)==0){
		$insert_temp_value=$objQuery->mysqlInsert('patient_temp_investigation',$arrFileds,$arrValues);
		}
	 }//End While
	}
	if($getCheckTest[0]['group_test']=="N")
	{
		$getTestList= $objQuery->mysqlSelect("test_id,test_name_site_name,department,normal_range,is_mref_range,min_range,normal_range,max_range,test_units","patient_diagnosis_tests","id='".$getCheckTest[0]['id']."'","","","","");	
		
		$arrFileds[]='main_test_id';
		$arrValues[]=$getTestList[0]['test_id'];
		
		$arrFileds[]='group_test_id';
		$arrValues[]=$getTestList[0]['test_id'];	
		
		$arrFileds[]='test_name';
		$arrValues[]=$getTestList[0]['test_name_site_name'];
		
		$arrFileds[]='department';
		$arrValues[]=$getTestList[0]['department'];
		
		if($getTestList[0]['is_mref_range'] == 'N'){
		$arrFileds[]='normal_range';
		$arrValues[]=$getTestList[0]['normal_range'];
		}
		
		$arrFileds[]='patient_id';
		$arrValues[]=$patientid;
		
		$arrFileds[]='doc_id';
		$arrValues[]=$admin_id;
		
		$arrFileds[]='doc_type';
		$arrValues[]="2";
		$arrFileds[]='status';
		$arrValues[]="1";
		$checkTestActive= $objQuery->mysqlSelect("pti_id","patient_temp_investigation","main_test_id='".$getTestList[0]['test_id']."' and patient_id='".$patientid."' and doc_id='".$admin_id."' and doc_type='2' and status='1'","","","","");	
		if(COUNT($checkTestActive)==0){
		$insert_temp_value=$objQuery->mysqlInsert('patient_temp_investigation',$arrFileds,$arrValues);
		}
	}
	
	$check_invest = $objQuery->mysqlSelect("*","doctor_frequent_investigations","main_test_id='".$investid."' and doc_id='".$admin_id."' and doc_type='2'","","","","");
					$freq_count = $check_invest[0]['freq_test_count']+1; //Count will increment by one
					$arrFieldsINVESTFREQ = array();
					$arrValuesINVESTFREQ = array();
					if(count($check_invest)>0){
						$arrFieldsINVESTFREQ[] = 'freq_test_count';
						$arrValuesINVESTFREQ[] = $freq_count;
						$update_icd=$objQuery->mysqlUpdate('doctor_frequent_investigations',$arrFieldsINVESTFREQ,$arrValuesINVESTFREQ,"dfi_id = '".$check_invest[0]['dfi_id']."'");
					}
					else{
						$arrFieldsINVESTFREQ[] = 'main_test_id';
						$arrValuesINVESTFREQ[] = $investid;
						$arrFieldsINVESTFREQ[] = 'doc_id';
						$arrValuesINVESTFREQ[] = $admin_id;
						$arrFieldsINVESTFREQ[] = 'doc_type';
						$arrValuesINVESTFREQ[] = "2";
						$arrFieldsINVESTFREQ[] = 'freq_test_count';
						$arrValuesINVESTFREQ[] = "1";
						$insert_freq_symp=$objQuery->mysqlInsert('doctor_frequent_investigations',$arrFieldsINVESTFREQ,$arrValuesINVESTFREQ);
						
						
					}

	

}


if(isset($_GET['editinvestid'])){
	$params     = split("-", $_GET['editinvestid']);
	$investid = $params[0];
	//To check whether chosen test is listed in group test table
	$getCheckTest= $objQuery->mysqlSelect("id,test_id,group_test","patient_diagnosis_tests","id='".$investid."'","","","","");
	if($getCheckTest[0]['group_test']=="Y")
	{
		$getTestList= $objQuery->mysqlSelect("test_id as group_test_id,sub_test_id as main_test_id","patient_diagnosis_group_tests ","test_id='".$getCheckTest[0]['test_id']."'","","","","");	

		while(list($key, $value) = each($getTestList)){
		$getTestName= $objQuery->mysqlSelect("test_id,test_name_site_name,department,normal_range,is_mref_range,min_range,max_range,test_units","patient_diagnosis_tests","test_id='".$value['main_test_id']."'","","","","");
	
		$arrFileds = array();
		$arrValues = array();
		
		$arrFileds[]='main_test_id';
		$arrValues[]=$getTestName[0]['test_id'];
		
		$arrFileds[]='group_test_id';
		$arrValues[]=$value['group_test_id'];	
		
		$arrFileds[]='test_name';
		$arrValues[]=$getTestName[0]['test_name_site_name'];
		
		$arrFileds[]='department';
		$arrValues[]=$getTestName[0]['department'];
		
		if($getTestName[0]['is_mref_range'] == 'N'){
		$arrFileds[]='normal_range';
		$arrValues[]=$getTestName[0]['normal_range'];
		}
		
		$arrFileds[]='patient_id';
		$arrValues[]=$patient_id;
		
		$arrFileds[]='doc_id';
		$arrValues[]=$admin_id;
		
		$arrFileds[]='episode_id';
		$arrValues[]=$_GET['episodeid'];
		
		$arrFileds[]='doc_type';
		$arrValues[]="2";
		$arrFileds[]='status';
		$arrValues[]="0";
		$checkTestActive= $objQuery->mysqlSelect("pti_id","patient_temp_investigation","main_test_id='".$getTestName[0]['test_id']."' and patient_id='".$patient_id."' and doc_id='".$admin_id."' and doc_type='2' and episode_id='".$_GET['episodeid']."'","","","","");	
		if(COUNT($checkTestActive)==0){
		$insert_temp_value=$objQuery->mysqlInsert('patient_temp_investigation',$arrFileds,$arrValues);
		}
	 }//End While
	}
	if($getCheckTest[0]['group_test']=="N")
	{
		$getTestList= $objQuery->mysqlSelect("test_id,test_name_site_name,department,normal_range,is_mref_range,min_range,normal_range,max_range,test_units","patient_diagnosis_tests","id='".$getCheckTest[0]['id']."'","","","","");	
		
		$arrFileds[]='main_test_id';
		$arrValues[]=$getTestList[0]['test_id'];
		
		$arrFileds[]='group_test_id';
		$arrValues[]=$getTestList[0]['test_id'];	
		
		$arrFileds[]='test_name';
		$arrValues[]=$getTestList[0]['test_name_site_name'];
		
		$arrFileds[]='department';
		$arrValues[]=$getTestList[0]['department'];
		
		if($getTestList[0]['is_mref_range'] == 'N'){
		$arrFileds[]='normal_range';
		$arrValues[]=$getTestList[0]['normal_range'];
		}
		
		$arrFileds[]='patient_id';
		$arrValues[]=$patient_id;
		
		$arrFileds[]='doc_id';
		$arrValues[]=$admin_id;
		
		$arrFileds[]='doc_type';
		$arrValues[]="2";
		
		$arrFileds[]='episode_id';
		$arrValues[]=$_GET['episodeid'];
		
		$arrFileds[]='status';
		$arrValues[]="0";
		$checkTestActive= $objQuery->mysqlSelect("pti_id","patient_temp_investigation","main_test_id='".$getTestList[0]['test_id']."' and patient_id='".$patient_id."' and doc_id='".$admin_id."' and doc_type='2' and episode_id='".$_GET['episodeid']."'","","","","");	
		if(COUNT($checkTestActive)==0){
		$insert_temp_value=$objQuery->mysqlInsert('patient_temp_investigation',$arrFileds,$arrValues);
		}
	}
	
	$check_invest = $objQuery->mysqlSelect("*","doctor_frequent_investigations","main_test_id='".$investid."' and doc_id='".$admin_id."' and doc_type='2'","","","","");
					$freq_count = $check_invest[0]['freq_test_count']+1; //Count will increment by one
					$arrFieldsINVESTFREQ = array();
					$arrValuesINVESTFREQ = array();
					if(count($check_invest)>0){
						$arrFieldsINVESTFREQ[] = 'freq_test_count';
						$arrValuesINVESTFREQ[] = $freq_count;
						$update_icd=$objQuery->mysqlUpdate('doctor_frequent_investigations',$arrFieldsINVESTFREQ,$arrValuesINVESTFREQ,"dfi_id = '".$check_invest[0]['dfi_id']."'");
					}
					else{
						$arrFieldsINVESTFREQ[] = 'main_test_id';
						$arrValuesINVESTFREQ[] = $investid;
						$arrFieldsINVESTFREQ[] = 'doc_id';
						$arrValuesINVESTFREQ[] = $admin_id;
						$arrFieldsINVESTFREQ[] = 'doc_type';
						$arrValuesINVESTFREQ[] = "2";
						$arrFieldsINVESTFREQ[] = 'freq_test_count';
						$arrValuesINVESTFREQ[] = "1";
						$insert_freq_symp=$objQuery->mysqlInsert('doctor_frequent_investigations',$arrFieldsINVESTFREQ,$arrValuesINVESTFREQ);
						
						
					}

	

}


if(isset($_GET['icdid']))
{
	$params     = split("-", $_GET['icdid']);
	$icdid = $params[0];
	//$getICDDetails= $objQuery->mysqlSelect("*","icd_code","icd_id='".$icdid."'","","","","");
				
		$arrFileds[]='icd_id';
		$arrValues[]=$icdid;	
		$arrFileds[]='patient_id';
		$arrValues[]=$_GET['patientid'];
		$arrFileds[]='doc_id';
		$arrValues[]=$admin_id;
		$arrFileds[]='doc_type';
		$arrValues[]="2";
		$arrFileds[]='status';
		$arrValues[]="1";
		
		$check_diagnosis_active = $objQuery->mysqlSelect("*","patient_diagnosis","icd_id='".$icdid."' and patient_id='".$_GET['patientid']."' and doc_id='".$admin_id."' and doc_type='2' and status='1'","","","","");
		if(COUNT($check_diagnosis_active)==0){
		$insert_temp_icd_value=$objQuery->mysqlInsert('patient_diagnosis',$arrFileds,$arrValues);
		}
		
		//Update doctor frequent diagnosis table
		$check_diagnosis = $objQuery->mysqlSelect("*","doctor_frequent_diagnosis","icd_id='".$icdid."' and doc_id='".$admin_id."' and doc_type='2'","","","","");
					$freq_count = $check_diagnosis[0]['freq_count']+1; //Count will increment by one
					$arrFieldsDIAGNO = array();
					$arrValuesDIAGNO = array();
					if(count($check_diagnosis)>0){
						$arrFieldsDIAGNO[] = 'freq_count';
						$arrValuesDIAGNO[] = $freq_count;
						$update_icd=$objQuery->mysqlUpdate('doctor_frequent_diagnosis',$arrFieldsDIAGNO,$arrValuesDIAGNO,"dfd_id = '".$check_diagnosis[0]['dfd_id']."'");
					}
					else{
						$arrFieldsDIAGNO[] = 'icd_id';
						$arrValuesDIAGNO[] = $icdid;
						$arrFieldsDIAGNO[] = 'doc_id';
						$arrValuesDIAGNO[] = $admin_id;
						$arrFieldsDIAGNO[] = 'doc_type';
						$arrValuesDIAGNO[] = "2";
						$arrFieldsDIAGNO[] = 'freq_count';
						$arrValuesDIAGNO[] = "1";
						$insert_freq_symp=$objQuery->mysqlInsert('doctor_frequent_diagnosis',$arrFieldsDIAGNO,$arrValuesDIAGNO);
						
						
					}
	
}
if(isset($_GET['editicdid']))
{
	$params     = split("-", $_GET['editicdid']);
	$icdid = $params[0];
	//$getICDDetails= $objQuery->mysqlSelect("*","icd_code","icd_id='".$icdid."'","","","","");
				
		$arrFileds[]='icd_id';
		$arrValues[]=$icdid;	
		$arrFileds[]='patient_id';
		$arrValues[]=$patient_id;
		$arrFileds[]='doc_id';
		$arrValues[]=$admin_id;
		$arrFileds[]='episode_id';
		$arrValues[]=$_GET['episodeid'];
		$arrFileds[]='doc_type';
		$arrValues[]="2";
		$arrFileds[]='status';
		$arrValues[]="0";
		
		$check_diagnosis_active = $objQuery->mysqlSelect("*","patient_diagnosis","icd_id='".$icdid."' and patient_id='".$_GET['patientid']."' and doc_id='".$admin_id."' and doc_type='2' and episode_id='".$_GET['episodeid']."'","","","","");
		if(COUNT($check_diagnosis_active)==0){
		$insert_temp_icd_value=$objQuery->mysqlInsert('patient_diagnosis',$arrFileds,$arrValues);
		}
		
		//Update doctor frequent diagnosis table
		$check_diagnosis = $objQuery->mysqlSelect("*","doctor_frequent_diagnosis","icd_id='".$icdid."' and doc_id='".$admin_id."' and doc_type='2'","","","","");
					$freq_count = $check_diagnosis[0]['freq_count']+1; //Count will increment by one
					$arrFieldsDIAGNO = array();
					$arrValuesDIAGNO = array();
					if(count($check_diagnosis)>0){
						$arrFieldsDIAGNO[] = 'freq_count';
						$arrValuesDIAGNO[] = $freq_count;
						$update_icd=$objQuery->mysqlUpdate('doctor_frequent_diagnosis',$arrFieldsDIAGNO,$arrValuesDIAGNO,"dfd_id = '".$check_diagnosis[0]['dfd_id']."'");
					}
					else{
						$arrFieldsDIAGNO[] = 'icd_id';
						$arrValuesDIAGNO[] = $icdid;
						$arrFieldsDIAGNO[] = 'doc_id';
						$arrValuesDIAGNO[] = $admin_id;
						$arrFieldsDIAGNO[] = 'doc_type';
						$arrValuesDIAGNO[] = "2";
						$arrFieldsDIAGNO[] = 'freq_count';
						$arrValuesDIAGNO[] = "1";
						$insert_freq_symp=$objQuery->mysqlInsert('doctor_frequent_diagnosis',$arrFieldsDIAGNO,$arrValuesDIAGNO);
						
						
					}
	
}
if(isset($_GET['delinvestid'])){
	
	$objQuery->mysqlDelete('patient_temp_investigation',"pti_id='".$_GET['delinvestid']."'");
}

if(isset($_GET['deleditinvestid'])){
	
	$objQuery->mysqlDelete('patient_temp_investigation',"pti_id='".$_GET['deleditinvestid']."'");
}

if(isset($_GET['delicdid'])){
	
	$objQuery->mysqlDelete('patient_diagnosis',"patient_diagnosis_id='".$_GET['delicdid']."'");
}
if(isset($_GET['delallInvest'])){
	
	$objQuery->mysqlDelete('patient_temp_investigation',"patient_id='".$_GET['patid']."' and doc_id='".$_GET['docid']."' and  doc_type='2'"); //Clear all temp data from table 'patient_diagnosis'
}

if(isset($_GET['updateinvestid']) && !empty($_GET['updateinvestid'])){
	
	if(isset($_GET['righteye']))
	{
				$arrFileds_invest[]='right_eye';
				$arrValues_invest[]=$_GET['righteye'];
	}
	if(isset($_GET['lefteye']))
	{
				$arrFileds_invest[]='left_eye';
				$arrValues_invest[]=$_GET['lefteye'];
	}
	
		
	$update_invest=$objQuery->mysqlUpdate('patient_temp_investigation',$arrFileds_invest,$arrValues_invest,"pti_id = '".$_GET['updateinvestid']."'");

}

$getTestCardioDetails= $objQuery->mysqlSelect("*","patient_temp_investigation","patient_id='".$patientid."' and doc_id='".$admin_id."' and doc_type='2' and status='1' and department='1'","","","","");
$getTestOpthalDetails= $objQuery->mysqlSelect("*","patient_temp_investigation","patient_id='".$patientid."' and doc_id='".$admin_id."' and doc_type='2' and status='1' and department='2'","","","","");


if((isset($_GET['investid'])) && (COUNT($getTestCardioDetails)>0 || COUNT($getTestOpthalDetails)>0)){
?>
					<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  action="my_patient_profile_save.php"  name="frmAddInvest" >
					<a class="btn btn-xs btn-white pull-right delete_all_diagnosis_test" data-patient-id="<?php echo $patientid; ?>" data-doctor-id="<?php echo $admin_id; ?>"><i class="fa fa-trash"></i> Clear All</a>
								<table class="table table-bordered">
								<?php if(COUNT($getTestCardioDetails)>0){ ?>
										<thead>
										
										<tr>
										<th>Test</th>
										<th>Normal Value</th>
										<th>Actual Value</th>
										<th>Delete</th>
										</tr>
										<thead>
										<tbody>
										<tr>
										
									<?php 
									
									foreach($getTestCardioDetails as $getTestDetailsList)	
									
									
									{  ?>
									<tr id="delInvestRow<?php echo $getTestDetailsList['pti_id'];?>">
									<td><input type="hidden" name="group_test_id[]" value="<?php echo $getTestDetailsList['group_test_id']; ?>" /><input type="hidden" name="main_test_id[]" value="<?php echo $getTestDetailsList['main_test_id']; ?>" /><?php echo $getTestDetailsList['test_name']; ?></td>
									<td><?php echo $getTestDetailsList['normal_range']; ?></td>
									<td><input type="text" class="tagName" name="" disabled value="" placeholder="" style="width:100px;"></td>
									<td><a class="del_Invest" data-invest-id="<?php echo $getTestDetailsList['pti_id'];?>"><span class="label label-danger">Delete</span></a></td>
									</tr>
									<?php }
									?>
                                   </tbody>
								<?php } if(COUNT($getTestOpthalDetails)>0){ ?>
									<thead>
										<tr>
										<th colspan="5">Ophthal Specific Tests</th>
										
										</tr>
										<tr>
										<th>Test</th>
										<th>Right Eye</th>
										<th>Left Eye</th>
										<th>Delete</th>
										</tr>
										<thead>
										<tbody>
										<tr>
										
									<?php 
									
									foreach($getTestOpthalDetails as $getOpthalDetailsList)	
									
									
									{  ?>
									<tr id="delInvestRow<?php echo $getOpthalDetailsList['pti_id'];?>">
									<td><input type="hidden" name="group_test_id[]" value="<?php echo $getOpthalDetailsList['group_test_id']; ?>" /><input type="hidden" name="main_test_id[]" value="<?php echo $getOpthalDetailsList['main_test_id']; ?>" /><?php echo $getOpthalDetailsList['test_name']; ?></td>
									<td><input type="text" class="right_eye" name="" data-investigation-id="<?php echo $getOpthalDetailsList['pti_id']; ?>"  value="" placeholder="" style="width:100px;"></td>
									<td><input type="text" class="left_eye" name="" value="" data-investigation-id="<?php echo $getOpthalDetailsList['pti_id']; ?>" placeholder="" style="width:100px;"></td>
									<td><a class="del_Invest" data-invest-id="<?php echo $getOpthalDetailsList['pti_id'];?>"><span class="label label-danger">Delete</span></a></td>
									</tr>
									<?php }
									?>
                                   </tbody>
			<?php } ?>
								   </table>
					</form>
<?php } 

	if(isset($_GET['editinvestid']))
	{ ?>
								<a class="btn btn-xs btn-white pull-right delete_all_diagnosis_test" data-patient-id="<?php echo $patient_id; ?>" data-doctor-id="<?php echo $admin_id; ?>"><i class="fa fa-trash"></i> Clear All</a>
								<table class="table table-bordered">
								<?php 
								$getTestCardioDetails= $objQuery->mysqlSelect("*","patient_temp_investigation","episode_id='".$_GET['episodeid']."' and department='1'","","","","");
								$getTestOpthalDetails= $objQuery->mysqlSelect("*","patient_temp_investigation","episode_id='".$_GET['episodeid']."' and department='2'","","","","");
								if(COUNT($getTestCardioDetails)>0){ ?>
										<thead>
										
										<tr>
										<th>Test</th>
										<th>Normal Value</th>
										<th>Actual Value</th>
										<th>Delete</th>
										</tr>
										<thead>
										<tbody>
										<tr>
										
									<?php 
									
									foreach($getTestCardioDetails as $getTestDetailsList)	
									
									
									{  ?>
									<tr id="delInvestRow<?php echo $getTestDetailsList['pti_id'];?>">
									<td><input type="hidden" name="group_test_id[]" value="<?php echo $getTestDetailsList['group_test_id']; ?>" /><input type="hidden" name="main_test_id[]" value="<?php echo $getTestDetailsList['main_test_id']; ?>" /><?php echo $getTestDetailsList['test_name']; ?></td>
									<td><?php echo $getTestDetailsList['normal_range']; ?></td>
									<td><input type="text" class="tagName" name="" value="<?php echo $getTestDetailsList['test_actual_value']; ?>"  style="width:100px;"></td>
									<td><a class="del_editInvest" data-invest-id="<?php echo $getTestDetailsList['pti_id'];?>"><span class="label label-danger">Delete</span></a></td>
									</tr>
									<?php }
									?>
                                   </tbody>
								<?php } if(COUNT($getTestOpthalDetails)>0){ ?>
									<thead>
										<tr>
										<th colspan="5">Ophthal Specific Tests</th>
										
										</tr>
										<tr>
										<th>Test</th>
										<th>Right Eye</th>
										<th>Left Eye</th>
										<th>Delete</th>
										</tr>
										<thead>
										<tbody>
										<tr>
										
									<?php 
									
									foreach($getTestOpthalDetails as $getOpthalDetailsList)	
									
									
									{  ?>
									<tr id="delInvestRow<?php echo $getOpthalDetailsList['pti_id'];?>">
									<td><input type="hidden" name="group_test_id[]" value="<?php echo $getOpthalDetailsList['group_test_id']; ?>" /><input type="hidden" name="main_test_id[]" value="<?php echo $getOpthalDetailsList['main_test_id']; ?>" /><?php echo $getOpthalDetailsList['test_name']; ?></td>
									<td><input type="text" class="right_eye" name="" data-investigation-id="<?php echo $getOpthalDetailsList['pti_id']; ?>"  value="<?php echo $getOpthalDetailsList['right_eye']; ?>" placeholder="" style="width:100px;"></td>
									<td><input type="text" class="left_eye" name="" value="<?php echo $getOpthalDetailsList['left_eye']; ?>" data-investigation-id="<?php echo $getOpthalDetailsList['pti_id']; ?>" placeholder="" style="width:100px;"></td>
									<td><a class="del_editInvest" data-invest-id="<?php echo $getOpthalDetailsList['pti_id'];?>"><span class="label label-danger">Delete</span></a></td>
									</tr>
									<?php }
									?>
                                   </tbody>
								<?php } ?>
								   </table>	
	
	
<?php }


if(isset($_GET['icdid']))
{
$getICDDetails= $objQuery->mysqlSelect("*","patient_diagnosis","patient_id='".$patientid."' and doc_id='".$admin_id."' and doc_type='2' and status='1'","","","","");
	if(COUNT($getICDDetails)>0){
	$get_diagnosis = $objQuery->mysqlSelect("a.icd_id as icd_id,b.icd_code as icd_name,a.patient_diagnosis_id as patient_diagnosis_id","patient_diagnosis as a inner join icd_code as b on a.icd_id=b.icd_id","a.patient_id = '".$patientid."' and a.doc_id= '". $admin_id ."' and a.doc_type='2' and a.status='1'","","","","");
									while(list($key, $value) = each($get_diagnosis))	
									{
									echo "<input type='hidden' name='icd_id[]' value='".$value['icd_id']."' />
									<input type='hidden' name='patient_diagnosis_id[]' value='".$value['patient_diagnosis_id']."' />
									<div class='input-group m-b'><span class='tag label label-primary m-b m-r' style='margin-bottom:30px;'>" . $value['icd_name'] . "<a data-role='remove' class='text-white del_diagnosis m-l' data-diagnosis-id='".$value['patient_diagnosis_id']."'>x</a></span></div>"; 
									}
	}
 } 
 if(isset($_GET['editicdid']))
{
	$get_diagnosis = $objQuery->mysqlSelect("a.icd_id as icd_id,b.icd_code as icd_name,a.patient_diagnosis_id as patient_diagnosis_id","patient_diagnosis as a inner join icd_code as b on a.icd_id=b.icd_id","a.episode_id='".$_GET['episodeid']."'","","","","");
									while(list($key, $value) = each($get_diagnosis))	
									{
									echo "<input type='hidden' name='icd_id[]' value='".$value['icd_id']."' />
									<input type='hidden' name='patient_diagnosis_id[]' value='".$value['patient_diagnosis_id']."' />
									<div class='input-group m-b'><span class='tag label label-primary m-b m-r' style='margin-bottom:30px;'>" . $value['icd_name'] . "<a data-role='remove' class='text-white del_editdiagnosis m-l' data-diagnosis-id='".$value['patient_diagnosis_id']."'>x</a></span></div>"; 
									}
}
 
 ?>
