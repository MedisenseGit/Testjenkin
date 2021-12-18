<?php
ob_start();
error_reporting(0); 
session_start();


$admin_id = $_SESSION['user_id'];

require_once("../classes/querymaker.class.php");




$patientid=$_GET['patientid'];

if(isset($_GET['investid']) || isset($_GET['freqinvestid'])){
	$params     = explode("-", $_GET['investid']);
	$investid = $params[0];
	//To check whether chosen test is listed in group test table
	$getCheckTest= mysqlSelect("id,test_id,group_test","patient_diagnosis_tests","id='".$investid."'","","","","");
	if($getCheckTest[0]['group_test']=="Y")
	{
		$getTestList= mysqlSelect("test_id as group_test_id,sub_test_id as main_test_id","patient_diagnosis_group_tests ","test_id='".$getCheckTest[0]['test_id']."'","","","","");	

		while(list($key, $value) = each($getTestList)){
		$getTestName= mysqlSelect("test_id,test_name_site_name,normal_range,is_mref_range,min_range,max_range,test_units","patient_diagnosis_tests","test_id='".$value['main_test_id']."'","","","","");
	
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
		
		//$arrFileds[]='patient_id';
		//$arrValues[]=$patientid;
		
		$arrFileds[]='diagnostic_customer_id';
		$arrValues[]=$_GET['patientid'];
		
		$arrFileds[]='diagnostic_id';
		$arrValues[]=$admin_id;
		
		$arrFileds[]='status';
		$arrValues[]="1";
		
		$insert_temp_value=mysqlInsert('diagnostic_patient_temp_investigation',$arrFileds,$arrValues);
		}
	}
	if($getCheckTest[0]['group_test']=="N")
	{
		$getTestList= mysqlSelect("test_id,test_name_site_name,normal_range,is_mref_range,min_range,normal_range,max_range,test_units","patient_diagnosis_tests","id='".$getCheckTest[0]['id']."'","","","","");	
		
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
		
		//$arrFileds[]='patient_id';
		//$arrValues[]=$patientid;
		
		$arrFileds[]='diagnostic_customer_id';
		$arrValues[]=$_GET['patientid'];
		$arrFileds[]='diagnostic_id';
		$arrValues[]=$admin_id;
		
		$arrFileds[]='status';
		$arrValues[]="1";
		
		$insert_temp_value=mysqlInsert('diagnostic_patient_temp_investigation',$arrFileds,$arrValues);
	}
	
	/*$check_invest = mysqlSelect("*","diagnosis_frequent_examination","main_test_id='".$investid."' and diagnostic_id='".$admin_id."'","","","","");
					$freq_count = $check_invest[0]['freq_test_count']+1; //Count will increment by one
					$arrFieldsINVESTFREQ = array();
					$arrValuesINVESTFREQ = array();
					if(count($check_invest)>0){
						$arrFieldsINVESTFREQ[] = 'freq_test_count';
						$arrValuesINVESTFREQ[] = $freq_count;
						$update_icd=mysqlUpdate('diagnosis_frequent_examination',$arrFieldsINVESTFREQ,$arrValuesINVESTFREQ,"diagno_exam_id = '".$check_invest[0]['diagno_exam_id']."'");
					}
					else{
						$arrFieldsINVESTFREQ[] = 'main_test_id';
						$arrValuesINVESTFREQ[] = $investid;
						$arrFieldsINVESTFREQ[] = 'diagnostic_id';
						$arrValuesINVESTFREQ[] = $admin_id;
						
						$arrFieldsINVESTFREQ[] = 'freq_test_count';
						$arrValuesINVESTFREQ[] = "1";
						$insert_freq_symp=mysqlInsert('diagnosis_frequent_examination',$arrFieldsINVESTFREQ,$arrValuesINVESTFREQ);
						
					}*/

}

//Edit Investigation Value
if(isset($_GET['editinvestactual'])){
	
		$arrFileds_INVEST[]='test_actual_value';
		$arrValues_INVEST[]=$_GET['editinvestval'];

	$ud_invest=mysqlUpdate('diagnostic_patient_temp_investigation',$arrFileds_INVEST,$arrValues_INVEST,"pti_id = '".$_GET['editinvestactual']."'");
	
}

if(isset($_GET['delsubtestid'])){
	
	mysqlDelete('diagnostic_patient_temp_investigation',"pti_id='".$_GET['delsubtestid']."'");
}

if(isset($_GET['delallInvest'])){
	
	mysqlDelete('diagnostic_patient_temp_investigation',"diagnostic_customer_id='".$_GET['patid']."' and diagnostic_id='".$_GET['docid']."' and status='1'"); //Clear all temp data from table 'patient_diagnosis'
}
$getTestDetails= mysqlSelect("*","diagnostic_patient_temp_investigation","diagnostic_customer_id	='".$patientid."' and diagnostic_id='".$admin_id."' and status='1'","","","","");


$getICDDetails= mysqlSelect("*","patient_diagnosis","patient_id='".$patientid."' and diagnostic_id='".$admin_id."'","","","","");

if((isset($_GET['investid']) || isset($_GET['delsubtestid']) || isset($_GET['freqinvestid']) || isset($_GET['delallInvest'])) && (COUNT($getTestDetails)>0)){
?>
					<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  action="my_patient_profile_save.php"  name="frmAddInvest" >
					<a class="btn btn-xs btn-white pull-right delete_all_diagnosis_test" data-patient-id="<?php echo $patientid; ?>" data-doctor-id="<?php echo $admin_id; ?>"><i class="fa fa-trash"></i> Clear All</a>
								<table class="table table-bordered">
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
									
									foreach($getTestDetails as $getTestDetailsList)	
									
									
									{  ?>
									<tr id="delInvestRow<?php echo $getTestDetailsList['pti_id'];?>">
									<td><input type="hidden" name="group_test_id[]" value="<?php echo $getTestDetailsList['group_test_id']; ?>" /><input type="hidden" name="investigation_id[]" value="<?php echo $getTestDetailsList['pti_id']; ?>" /><input type="hidden" name="main_test_id[]" value="<?php echo $getTestDetailsList['main_test_id']; ?>" /><?php echo $getTestDetailsList['test_name']; ?></td>
									<td><?php echo $getTestDetailsList['normal_range']; ?></td>
									<td><input type="text" class="tagName addTestActualVal" name="actualVal[]" value="<?php echo $getTestDetailsList['test_actual_value']; ?>" data-diagno-invest-id="<?php echo $getTestDetailsList['pti_id']; ?>" placeholder="" style="width:100px;"></td>
									<td><a class="del_Invest" data-invest-id="<?php echo $getTestDetailsList['pti_id'];?>"><span class="label label-danger del_diagnosis_test">Delete</span></a></td>
									</tr>
									<?php }
									?>
                                   </tbody>
								   </table>
					</form>
<?php } 
?>

					