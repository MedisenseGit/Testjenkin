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
//$objQuery = new CLSQueryMaker();

$patientid=$_GET['patientid'];

//Edit Investigation Value
if(isset($_GET['editinvestactual'])){
	
		$arrFileds_INVEST[]='test_actual_value';
		$arrValues_INVEST[]=$_GET['editinvestval'];

	$ud_invest=mysqlUpdate('patient_temp_investigation',$arrFileds_INVEST,$arrValues_INVEST,"pti_id = '".$_GET['editinvestactual']."'");
	$update_invest = mysqlSelect("*","patient_temp_investigation","pti_id = '".$_GET['editinvestactual']."'","","","","");
		
	
		if($update_invest[0]['main_test_id']=="GLU009")  //BLOOD GLUCOSE (Post Prandial)
		{
			$arrFieldTrend[]='bp_afterfood_count';
			$arrValueTrend[]=$_GET['editinvestval'];
		}
		else if($update_invest[0]['main_test_id']=="GLU017") //BLOOD GLUCOSE (Fasting)
		{
			$arrFieldTrend[]='bp_beforefood_count';
			$arrValueTrend[]=$_GET['editinvestval'];
		}
		else if($update_invest[0]['main_test_id']=="CHO001") //HDL CHOLESTEROL
		{
			
			$arrFieldTrend[]='HDL';
			$arrValueTrend[]=$_GET['editinvestval'];
		}
		else if($update_invest[0]['main_test_id']=="LDL") //LDL CHOLESTEROL
		{
			
			$arrFieldTrend[]='LDL';
			$arrValueTrend[]=$_GET['editinvestval'];
		}
		else if($update_invest[0]['main_test_id']=="CHOL/HDL") //VLDL
		{
			
			$arrFieldTrend[]='VLDL';
			$arrValueTrend[]=$_GET['editinvestval'];
		}
		else if($update_invest[0]['main_test_id']=="TRI001")   //TRIGLYCERIDES
		{
			
			$arrFieldTrend[]='triglyceride';
			$arrValueTrend[]=$_GET['editinvestval'];
		}
		else if($update_invest[0]['main_test_id']=="GLU006")  //Glyco Hb (HbA1c)
		{
			
			$arrFieldTrend[]='HbA1c';
			$arrValueTrend[]=$_GET['editinvestval'];
		}
		else if($update_invest[0]['main_test_id']=="CHO002")  //TOTAL CHOLESTEROL
		{
			
			$arrFieldTrend[]='cholesterol';
			$arrValueTrend[]=$_GET['editinvestval'];
		}
		else if($update_invest[0]['main_test_id']=="URI012") //URINE SUGAR
		{
			
			$arrFieldTrend[]='urine_sugar';
			$arrValueTrend[]=$_GET['editinvestval'];
		}
		
		$arrFieldTrend[]='date_added';
		$arrValueTrend[]=$_GET['dateadded'];
		if(!empty($update_invest[0]['patient_id']))
		{
			$arrFieldTrend[]='patient_id';
			$arrValueTrend[]=$update_invest[0]['patient_id'];
		}
		
		$arrFieldTrend[]='patient_type';
		$arrValueTrend[]="1";
		$checkTrend= mysqlSelect("*","trend_analysis","date_added='".$_GET['dateadded']."' and patient_id='".$update_invest[0]['patient_id']."' and patient_type='1'","","","","");
		if(count($checkTrend)>0)
		{
			$update_trend = mysqlUpdate('trend_analysis',$arrFieldTrend,$arrValueTrend,"date_added='".$_GET['dateadded']."' and patient_id = '".$update_invest[0]['patient_id']."' and patient_type='1'");
		}
		else
		{
		$insert_trend_analysis = mysqlInsert('trend_analysis',$arrFieldTrend,$arrValueTrend);
		}
	
}
//End of edit Investigation Value
if(isset($_GET['delinvesttemp']))
{
	//Delete perticular symptoms from table 'doc_patient_examination_active'
	mysqlDelete('doc_patient_episode_investigations_templates',"md5(invest_template_id)='".$_GET['delinvesttemp']."'");
	mysqlDelete('doc_patient_episode_investigation_template_details',"md5(invest_template_id)='".$_GET['delinvesttemp']."'");
}

if(isset($_GET['gettemplateval']))
{
	$invest_templates = mysqlSelect("*","doc_patient_episode_investigations_templates","doc_id='".$admin_id."' and doc_type='1'","invest_template_id desc","","","10");
	if($_GET['gettempEdit']==1)
	{
		while(list($key_investtemp, $value_investtemp) = each($invest_templates)){
		
		echo "<span class='tag label label-primary m-l' >".$value_investtemp['template_name']."<a data-role='remove' class='text-white m-l del_invest_template' data-invest-template-id='".md5($value_investtemp['invest_template_id'])."'>x</a></span>";
		 }
	}
	else
	{
		
		while(list($key_investtemp, $value_investtemp) = each($invest_templates)){
		
		echo "<a class='btn btn-xs btn-white m-l invest_load_template' title='".$value_investtemp['template_name']."' data-invest-template-id='".md5($value_investtemp['invest_template_id'])."' data-edit-status='0' data-patient-id='".$_GET['patientid']."' ><code> ".substr($value_investtemp['template_name'],0,10)."</code></a>";
		 }
	}
}

if(isset($_GET['addinvestid']))
{
	$params     = explode("-", $_GET['addinvestid']);
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
		
		if(!empty($getTestName[0]['test_id']))
		{
			$arrFileds[]='main_test_id';
			$arrValues[]=$getTestName[0]['test_id'];
		}
		if(!empty($value['group_test_id']))
		{
			$arrFileds[]='group_test_id';
			$arrValues[]=$value['group_test_id'];	
		}
		
		$arrFileds[]='test_name';
		$arrValues[]=$getTestName[0]['test_name_site_name'];
		
		if($getTestName[0]['is_mref_range'] == 'N')
		{
			$arrFileds[]='normal_range';
			$arrValues[]=$getTestName[0]['normal_range'];
		}
		
		if(!empty($_GET['episodeid']))
		{
			$arrFileds[]='episode_id';
			$arrValues[]=$_GET['episodeid'];	
		}
		if(!empty($_GET['patientid']))
		{
			$arrFileds[]='patient_id';
			$arrValues[]=$_GET['patientid'];	
		}
		if(!empty($_GET['docid']))
		{
			$arrFileds[]='doc_id';
			$arrValues[]=$_GET['docid'];	
		}
		
		
		$arrFileds[]='doc_type';
		$arrValues[]="1";
		$arrFileds[]='status';
		$arrValues[]="0";
		$check_temp_invest_active = mysqlSelect("*","patient_temp_investigation","main_test_id='".$getTestName[0]['test_id']."' and patient_id='".$_GET['patientid']."' and doc_id='".$admin_id."' and doc_type='1'","","","","");
		if(COUNT($check_temp_invest_active)==0){
		$insert_temp_value=mysqlInsert('patient_temp_investigation',$arrFileds,$arrValues);
		}
	  } //End while
	}
	if($getCheckTest[0]['group_test']=="N")
	{
		$getTestList= mysqlSelect("test_id,test_name_site_name,normal_range,is_mref_range,min_range,normal_range,max_range,test_units","patient_diagnosis_tests","id='".$getCheckTest[0]['id']."'","","","","");	
		
		if(!empty($getTestList[0]['test_id']))
		{
			$arrFileds[]='main_test_id';
			$arrValues[]=$getTestList[0]['test_id'];
		}
		
		
		
		$arrFileds[]='group_test_id';
		$arrValues[]=$getTestList[0]['test_id'];	
		
		$arrFileds[]='test_name';
		$arrValues[]=$getTestList[0]['test_name_site_name'];
		
		if($getTestList[0]['is_mref_range'] == 'N'){
		$arrFileds[]='normal_range';
		$arrValues[]=$getTestList[0]['normal_range'];
		}
		
		if(!empty($_GET['episodeid']))
		{
			$arrFileds[]='episode_id';
			$arrValues[]=$_GET['episodeid'];	
		}
		if(!empty($_GET['patientid']))
		{
			$arrFileds[]='patient_id';
			$arrValues[]=$_GET['patientid'];	
		}
		if(!empty($_GET['docid']))
		{
			$arrFileds[]='doc_id';
			$arrValues[]=$_GET['docid'];	
		}
		
		// $arrFileds[]='episode_id';
		// $arrValues[]=$_GET['episodeid'];
		
		// $arrFileds[]='patient_id';
		// $arrValues[]=$_GET['patientid'];
		
		// $arrFileds[]='doc_id';
		// $arrValues[]=$_GET['docid'];
		
		$arrFileds[]='doc_type';
		$arrValues[]="1";
		$arrFileds[]='status';
		$arrValues[]="0";
		$check_temp_invest_active = mysqlSelect("*","patient_temp_investigation","main_test_id='".$getTestList[0]['test_id']."' and patient_id='".$_GET['patientid']."' and doc_id='".$admin_id."' and doc_type='1'","","","","");
		
		if(COUNT($check_temp_invest_active)==0){
		$insert_temp_value=mysqlInsert('patient_temp_investigation',$arrFileds,$arrValues);
		}
	}
	
	$getTestDetails= mysqlSelect("*","patient_temp_investigation","patient_id='".$_GET['patientid']."' and episode_id='".$_GET['episodeid']."'","","","","");

	?>
	<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  action="my_patient_profile_save.php"  name="frmAddInvest" >
			<table class="table table-bordered">
										<thead>
										<tr>
										<th>Test</th>
										<!--<th>Normal Value</th>-->
										<th>Result</th>
										
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
												<!--<td><?php echo $value_invest['normal_range']; ?></td>-->
												<td><input type="text" name="actualVal[]"  value="<?php echo $value_invest['test_actual_value']; ?>" placeholder="Result" style="width:100px;"></td>
												</tr>
												
										<?php } ?>
				</tbody>
			</table>
		</form>
<?php 	
}


if(isset($_GET['investid']) || isset($_GET['freqinvestid'])){
	$params     = explode("-", $_GET['investid']);
	$investid = $params[0];
	//To check whether chosen test is listed in group test table
	$getCheckTest= mysqlSelect("id,test_id,group_test","patient_diagnosis_tests","(id='".$investid."' and doc_id='".$admin_id."' and doc_type='1') or (id='".$investid."' and doc_id='0' and doc_type='0')","","","","");
	if(COUNT($getCheckTest)>0){
		$invest_id = $investid;
		if($getCheckTest[0]['group_test']=="Y")
		{
			$getTestList= mysqlSelect("test_id as group_test_id,sub_test_id as main_test_id","patient_diagnosis_group_tests ","test_id='".$getCheckTest[0]['test_id']."'","","","","");	

			while(list($key, $value) = each($getTestList)){
			$getTestName= mysqlSelect("test_id,test_name_site_name,department,normal_range,is_mref_range,min_range,max_range,test_units","patient_diagnosis_tests","test_id='".$value['main_test_id']."'","","","","");
		
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
			
			// if(!empty($_GET['episodeid']))
			// {
				// $arrFileds[]='episode_id';
				// $arrValues[]=$_GET['episodeid'];	
			// }
			if(!empty($_GET['patientid']))
			{
				$arrFileds[]='patient_id';
				$arrValues[]=$_GET['patientid'];	
			}
			if(!empty($admin_id))
			{
				$arrFileds[]='doc_id';
				$arrValues[]=$admin_id;	
			}
			
			// $arrFileds[]='patient_id';
			// $arrValues[]=$patientid;
			
			// $arrFileds[]='doc_id';
			// $arrValues[]=$admin_id;
			
			$arrFileds[]='doc_type';
			$arrValues[]="1";
			$arrFileds[]='status';
			$arrValues[]="1";
			$checkTestActive= mysqlSelect("pti_id","patient_temp_investigation","main_test_id='".$getTestName[0]['test_id']."' and patient_id='".$patientid."' and doc_id='".$admin_id."' and doc_type='1' and status='1'","","","","");	
			if(COUNT($checkTestActive)==0){
			$insert_temp_value=mysqlInsert('patient_temp_investigation',$arrFileds,$arrValues);
			}
		 }//End While
		}
		if($getCheckTest[0]['group_test']=="N")
		{
			$getTestList= mysqlSelect("test_id,test_name_site_name,department,normal_range,is_mref_range,min_range,normal_range,max_range,test_units","patient_diagnosis_tests","id='".$getCheckTest[0]['id']."'","","","","");	
			
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
			
			if(!empty($_GET['patientid']))
			{
				$arrFileds[]='patient_id';
				$arrValues[]=$_GET['patientid'];	
			}
			if(!empty($admin_id))
			{
				$arrFileds[]='doc_id';
				$arrValues[]=$admin_id;	
			}
			
			// $arrFileds[]='patient_id';
			// $arrValues[]=$patientid;
			
			// $arrFileds[]='doc_id';
			// $arrValues[]=$admin_id;
			
			$arrFileds[]='doc_type';
			$arrValues[]="1";
			$arrFileds[]='status';
			$arrValues[]="1";
			$checkTestActive= mysqlSelect("pti_id","patient_temp_investigation","main_test_id='".$getTestList[0]['test_id']."' and patient_id='".$patientid."' and doc_id='".$admin_id."' and doc_type='1' and status='1'","","","","");	
			if(COUNT($checkTestActive)==0){
			$insert_temp_value=mysqlInsert('patient_temp_investigation',$arrFileds,$arrValues);
			}
		}
	}
	else
	{
			$arrFileds_test[]='test_id';
			$arrValues_test[]= time();
			$arrFileds_test[]='doc_id';
			$arrValues_test[]=$admin_id;
			$arrFileds_test[]='doc_type';
			$arrValues_test[]="1";
			$arrFileds_test[]='test_name_site_name';
			$arrValues_test[]=$investid;
			$arrFileds_test[]='group_test';
			$arrValues_test[]="N";
			$arrFileds_test[]='department';
			$arrValues_test[]="5";
			
			$insert_new_val=mysqlInsert('patient_diagnosis_tests',$arrFileds_test,$arrValues_test);
			$invest_id = $insert_new_val;
			$getDiagnosisDetails= mysqlSelect("test_id,test_name_site_name","patient_diagnosis_tests","id='".$invest_id."'","","","","");	
			
			
			$arrFileds[]='main_test_id';
			$arrValues[]=$getDiagnosisDetails[0]['test_id'];
			
			$arrFileds[]='group_test_id';
			$arrValues[]=$getDiagnosisDetails[0]['test_id'];	
			
			$arrFileds[]='test_name';
			$arrValues[]=$getDiagnosisDetails[0]['test_name_site_name'];
			
			$arrFileds[]='department';
			$arrValues[]="5";
			
			if(!empty($admin_id))
			{
				$arrFileds[]='doc_id';
				$arrValues[]=$admin_id;
			}
			if(!empty($patientid))
			{
				$arrFileds[]='patient_id';
				$arrValues[]=$patientid;
			}
			
			$arrFileds[]='doc_type';
			$arrValues[]="1";
			$arrFileds[]='status';
			$arrValues[]="1";
			
			$insert_temp_value=mysqlInsert('patient_temp_investigation',$arrFileds,$arrValues);
			
		
	}
	$check_invest = mysqlSelect("*","doctor_frequent_investigations","main_test_id='".$invest_id."' and doc_id='".$admin_id."' and doc_type='1'","","","","");
	$freq_count = $check_invest[0]['freq_test_count']+1; //Count will increment by one
	$arrFieldsINVESTFREQ = array();
	$arrValuesINVESTFREQ = array();
	if(count($check_invest)>0)
	{
		$arrFieldsINVESTFREQ[] = 'freq_test_count';
		$arrValuesINVESTFREQ[] = $freq_count;
		$update_icd=mysqlUpdate('doctor_frequent_investigations',$arrFieldsINVESTFREQ,$arrValuesINVESTFREQ,"dfi_id = '".$check_invest[0]['dfi_id']."'");
	}
	else
	{
		$arrFieldsINVESTFREQ[] = 'main_test_id';
		$arrValuesINVESTFREQ[] = $invest_id;
		$arrFieldsINVESTFREQ[] = 'doc_id';
		$arrValuesINVESTFREQ[] = $admin_id;
		$arrFieldsINVESTFREQ[] = 'doc_type';
		$arrValuesINVESTFREQ[] = "1";
		$arrFieldsINVESTFREQ[] = 'freq_test_count';
		$arrValuesINVESTFREQ[] = "1";
		$insert_freq_symp=mysqlInsert('doctor_frequent_investigations',$arrFieldsINVESTFREQ,$arrValuesINVESTFREQ);
	}

	

}


if(isset($_GET['editinvestid']))
{
	$params     = explode("-", $_GET['editinvestid']);
	$investid = $params[0];
	//To check whether chosen test is listed in group test table
	$getCheckTest= mysqlSelect("id,test_id,group_test","patient_diagnosis_tests","id='".$investid."'","","","","");
	if($getCheckTest[0]['group_test']=="Y")
	{
		$getTestList= mysqlSelect("test_id as group_test_id,sub_test_id as main_test_id","patient_diagnosis_group_tests ","test_id='".$getCheckTest[0]['test_id']."'","","","","");	

		while(list($key, $value) = each($getTestList))
		{
			$getTestName= mysqlSelect("test_id,test_name_site_name,department,normal_range,is_mref_range,min_range,max_range,test_units","patient_diagnosis_tests","test_id='".$value['main_test_id']."'","","","","");
	
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
		
			if($getTestName[0]['is_mref_range'] == 'N')
			{
				$arrFileds[]='normal_range';
				$arrValues[]=$getTestName[0]['normal_range'];
			}
		
			if(!empty($admin_id))
			{
				$arrFileds[]='doc_id';
				$arrValues[]=$admin_id;
			}
			if(!empty($patientid))
			{
				$arrFileds[]='patient_id';
				$arrValues[]=$patientid;
			}
			if(!empty($_GET['episodeid']))
			{
				$arrFileds[]='episode_id';
				$arrValues[]=$_GET['episodeid'];
			}
			
			// $arrFileds[]='patient_id';
			// $arrValues[]=$patient_id;
			
			// $arrFileds[]='doc_id';
			// $arrValues[]=$admin_id;
			
		
		
		$arrFileds[]='doc_type';
		$arrValues[]="1";
		$arrFileds[]='status';
		$arrValues[]="0";
		$checkTestActive= mysqlSelect("pti_id","patient_temp_investigation","main_test_id='".$getTestName[0]['test_id']."' and patient_id='".$patient_id."' and doc_id='".$admin_id."' and doc_type='1' and episode_id='".$_GET['episodeid']."'","","","","");	
		if(COUNT($checkTestActive)==0){
		$insert_temp_value=mysqlInsert('patient_temp_investigation',$arrFileds,$arrValues);
		}
	 }//End While
	}
	if($getCheckTest[0]['group_test']=="N")
	{
		$getTestList= mysqlSelect("test_id,test_name_site_name,department,normal_range,is_mref_range,min_range,normal_range,max_range,test_units","patient_diagnosis_tests","id='".$getCheckTest[0]['id']."'","","","","");	
		
		$arrFileds[]='main_test_id';
		$arrValues[]=$getTestList[0]['test_id'];
		
		$arrFileds[]='group_test_id';
		$arrValues[]=$getTestList[0]['test_id'];	
		
		$arrFileds[]='test_name';
		$arrValues[]=$getTestList[0]['test_name_site_name'];
		
		$arrFileds[]='department';
		$arrValues[]=$getTestList[0]['department'];
		
		if($getTestList[0]['is_mref_range'] == 'N')
		{
			$arrFileds[]='normal_range';
			$arrValues[]=$getTestList[0]['normal_range'];
		}
		
		if(!empty($admin_id))
		{
			$arrFileds[]='doc_id';
			$arrValues[]=$admin_id;
		}
		if(!empty($patientid))
		{
			$arrFileds[]='patient_id';
			$arrValues[]=$patientid;
		}
		if(!empty($_GET['episodeid']))
		{
			$arrFileds[]='episode_id';
			$arrValues[]=$_GET['episodeid'];
		}
		
		// $arrFileds[]='patient_id';
		// $arrValues[]=$patient_id;
		
		// $arrFileds[]='doc_id';
		// $arrValues[]=$admin_id;
		
		// $arrFileds[]='doc_type';
		// $arrValues[]="1";
		
		// $arrFileds[]='episode_id';
		// $arrValues[]=$_GET['episodeid'];
		
		$arrFileds[]='status';
		$arrValues[]="0";
		$checkTestActive= mysqlSelect("pti_id","patient_temp_investigation","main_test_id='".$getTestList[0]['test_id']."' and patient_id='".$patient_id."' and doc_id='".$admin_id."' and doc_type='1' and episode_id='".$_GET['episodeid']."'","","","","");	
		if(COUNT($checkTestActive)==0)
		{
			$insert_temp_value=mysqlInsert('patient_temp_investigation',$arrFileds,$arrValues);
		}
	}
	
						
	$check_invest = mysqlSelect("*","doctor_frequent_investigations","main_test_id='".$investid."' and doc_id='".$admin_id."' and doc_type='1'","","","","");
	$freq_count = $check_invest[0]['freq_test_count']+1; //Count will increment by one
	$arrFieldsINVESTFREQ = array();
	$arrValuesINVESTFREQ = array();
	if(count($check_invest)>0)
	{
		$arrFieldsINVESTFREQ[] = 'freq_test_count';
		$arrValuesINVESTFREQ[] = $freq_count;
		$update_icd=mysqlUpdate('doctor_frequent_investigations',$arrFieldsINVESTFREQ,$arrValuesINVESTFREQ,"dfi_id = '".$check_invest[0]['dfi_id']."'");
	}
	else
	{
		$arrFieldsINVESTFREQ[] = 'main_test_id';
		$arrValuesINVESTFREQ[] = $investid;
		$arrFieldsINVESTFREQ[] = 'doc_id';
		$arrValuesINVESTFREQ[] = $admin_id;
		$arrFieldsINVESTFREQ[] = 'doc_type';
		$arrValuesINVESTFREQ[] = "1";
		$arrFieldsINVESTFREQ[] = 'freq_test_count';
		$arrValuesINVESTFREQ[] = "1";
		$insert_freq_symp=mysqlInsert('doctor_frequent_investigations',$arrFieldsINVESTFREQ,$arrValuesINVESTFREQ);
	}

	

}
if((isset($_GET['loadtemplate']) && !empty($_GET['loadtemplate'])) || (isset($_GET['editloadtemplate']) && !empty($_GET['editloadtemplate'])))
{
	if(isset($_GET['loadtemplate'])){
		$invest_template_id = $_GET['loadtemplate'];
	}
	else if(isset($_GET['editloadtemplate'])){
		$invest_template_id = $_GET['editloadtemplate'];
	}
	$getTemplateDetails= mysqlSelect("*","doc_patient_episode_investigation_template_details","md5(invest_template_id)='".$invest_template_id."'","","","","");
while(list($key,$value) = each($getTemplateDetails))
	{		
			$arrFileds = array();
			$arrValues = array();
			
			$arrFileds[]='main_test_id';
			$arrValues[]=$value['main_test_id'];
			
			$arrFileds[]='group_test_id';
			$arrValues[]=$value['main_test_id'];	
			
			$arrFileds[]='test_name';
			$arrValues[]=$value['test_name'];
			
			$arrFileds[]='department';
			$arrValues[]="1";
			
			// $arrFileds[]='patient_id';
			// $arrValues[]=$_GET['patientid'];
			
			// $arrFileds[]='doc_id';
			// $arrValues[]=$admin_id;
			
			if(!empty($admin_id))
			{
				$arrFileds[]='doc_id';
				$arrValues[]=$admin_id;
			}
			if(!empty($patientid))
			{
				$arrFileds[]='patient_id';
				$arrValues[]=$patientid;
			}
			// if(!empty($_GET['episodeid']))
			// {
				// $arrFileds[]='episode_id';
				// $arrValues[]=$_GET['episodeid'];
			// }
				
			$arrFileds[]='doc_type';
			$arrValues[]="1";
			if($_GET['editstatus']==0){
			$arrFileds[]='status';
			$arrValues[]="1";
			}
			else if($_GET['editstatus']==1)
			{
				$arrFileds[]='episode_id';
				$arrValues[]=$_GET['episodeid'];
				$arrFileds[]='status';
				$arrValues[]="0";	
			}
			
			$insert_temp_value=mysqlInsert('patient_temp_investigation',$arrFileds,$arrValues);
		
		
	}
	
}

if(isset($_GET['icdid']))
{
	$params     = explode("-", $_GET['icdid']);
	$icdid = $params[0];
	$getICDDetails= mysqlSelect("*","icd_code","icd_id='".$icdid."'","","","","");
	if(COUNT($getICDDetails)>0)
	{	

		if(!empty($icdid))
		{
			$arrFileds[]='icd_id';
			$arrValues[]=$icdid;
		}
		if(!empty($_GET['patientid']))
		{
			$arrFileds[]='patient_id';
			$arrValues[]=$_GET['patientid'];
		}
		if(!empty($admin_id))
		{
			$arrFileds[]='doc_id';
			$arrValues[]=$admin_id;
		}
		
		$arrFileds[]='doc_type';
		$arrValues[]="1";
		$arrFileds[]='status';
		$arrValues[]="1";
		
		$check_diagnosis_active = mysqlSelect("*","patient_diagnosis","icd_id='".$icdid."' and patient_id='".$_GET['patientid']."' and doc_id='".$admin_id."' and doc_type='1' and status='1'","","","","");
		if(COUNT($check_diagnosis_active)==0){
		$insert_temp_icd_value=mysqlInsert('patient_diagnosis',$arrFileds,$arrValues);
		}
		
		//Update doctor frequent diagnosis table
		$check_diagnosis = mysqlSelect("*","doctor_frequent_diagnosis","icd_id='".$icdid."' and doc_id='".$admin_id."' and doc_type='1'","","","","");
					$freq_count = $check_diagnosis[0]['freq_count']+1; //Count will increment by one
					$arrFieldsDIAGNO = array();
					$arrValuesDIAGNO = array();
					if(count($check_diagnosis)>0){
						$arrFieldsDIAGNO[] = 'freq_count';
						$arrValuesDIAGNO[] = $freq_count;
						$update_icd=mysqlUpdate('doctor_frequent_diagnosis',$arrFieldsDIAGNO,$arrValuesDIAGNO,"dfd_id = '".$check_diagnosis[0]['dfd_id']."'");
					}
					else
					{
						if(!empty($icdid))
						{
							$arrFieldsDIAGNO[] = 'icd_id';
							$arrValuesDIAGNO[] = $icdid;
						}
						// if(!empty($_GET['patientid']))
						// {
							// $arrFileds[]='patient_id';
							// $arrValues[]=$_GET['patientid'];
						// }
						if(!empty($admin_id))
						{
							$arrFieldsDIAGNO[] = 'doc_id';
							$arrValuesDIAGNO[] = $admin_id;
						}
		
		
						
						
						$arrFieldsDIAGNO[] = 'doc_type';
						$arrValuesDIAGNO[] = "1";
						$arrFieldsDIAGNO[] = 'freq_count';
						$arrValuesDIAGNO[] = "1";
						$insert_freq_symp=mysqlInsert('doctor_frequent_diagnosis',$arrFieldsDIAGNO,$arrValuesDIAGNO);
					}
	}
	else
	{
		if(!empty($icdid))
		{
			$arrFileds_icd[] = 'icd_code';
			$arrValues_icd[] = $icdid;
		}
		
		if(!empty($admin_id))
		{
			$arrFileds_icd[] = 'doc_id';
			$arrValues_icd[] = $admin_id;
		}
						
		
		
		$arrFileds_icd[] = 'doc_type';
		$arrValues_icd[] = "1";
					
		$insert_doc_icd_value=mysqlInsert('icd_code',$arrFileds_icd,$arrValues_icd);
		$diagno_id = $insert_doc_icd_value;
		
		if(!empty($diagno_id))
		{
			$arrFileds[]='icd_id';
			$arrValues[]=$diagno_id;
		}
		
		if(!empty($admin_id))
		{
			$arrFileds[]='doc_id';
			$arrValues[]=$admin_id;
		}
		
		if(!empty($_GET['patientid']))
		{
			$arrFileds[]='patient_id';
			$arrValues[]=$_GET['patientid'];
			
		}
		
		$arrFileds[]='doc_type';
		$arrValues[]="1";
		$arrFileds[]='status';
		$arrValues[]="1";
		
		$insert_temp_icd_value=mysqlInsert('patient_diagnosis',$arrFileds,$arrValues);
		//Update doctor frequent diagnosis table
		$check_diagnosis = mysqlSelect("*","doctor_frequent_diagnosis","icd_id='".$diagno_id."' and doc_id='".$admin_id."' and doc_type='1'","","","","");
					$freq_count = $check_diagnosis[0]['freq_count']+1; //Count will increment by one
					$arrFieldsDIAGNO = array();
					$arrValuesDIAGNO = array();
					if(count($check_diagnosis)>0){
						$arrFieldsDIAGNO[] = 'freq_count';
						$arrValuesDIAGNO[] = $freq_count;
						$update_icd=mysqlUpdate('doctor_frequent_diagnosis',$arrFieldsDIAGNO,$arrValuesDIAGNO,"dfd_id = '".$check_diagnosis[0]['dfd_id']."'");
					}
					else
					{
						
						if(!empty($diagno_id))
						{
							$arrFieldsDIAGNO[] = 'icd_id';
							$arrValuesDIAGNO[] = $diagno_id;
						}
						
						if(!empty($admin_id))
						{
							$arrFieldsDIAGNO[] = 'doc_id';
							$arrValuesDIAGNO[] = $admin_id;
						}
						
						// if(!empty($_GET['patientid']))
						// {
							// $arrFileds[]='patient_id';
							// $arrValues[]=$_GET['patientid'];
							
						// }
		
						
						
						$arrFieldsDIAGNO[] = 'doc_type';
						$arrValuesDIAGNO[] = "1";
						$arrFieldsDIAGNO[] = 'freq_count';
						$arrValuesDIAGNO[] = "1";
						$insert_freq_symp=mysqlInsert('doctor_frequent_diagnosis',$arrFieldsDIAGNO,$arrValuesDIAGNO);
						
						
					}
		
	}
}
if(isset($_GET['editicdid']))
{
	$params     = explode("-", $_GET['editicdid']);
	$icdid = $params[0];
	$getICDDetails= mysqlSelect("*","icd_code","icd_id='".$icdid."'","","","","");
	if(COUNT($getICDDetails)>0){

		if(!empty($icdid))
		{
			$arrFileds[]='icd_id';
			$arrValues[]=$icdid;
		}
		
		if(!empty($admin_id))
		{
			$arrFileds[]='doc_id';
			$arrValues[]=$admin_id;
		}
		
		if(!empty($patient_id))
		{
			$arrFileds[]='patient_id';
			$arrValues[]=$patient_id;
			
		}
		
		if(!empty($_GET['episodeid']))
		{
			$arrFileds[]='episode_id';
			$arrValues[]=$_GET['episodeid'];
			
		}
		
		$arrFileds[]='doc_type';
		$arrValues[]="1";
		$arrFileds[]='status';
		$arrValues[]="0";
		
		$check_diagnosis_active = mysqlSelect("*","patient_diagnosis","icd_id='".$icdid."' and patient_id='".$_GET['patientid']."' and doc_id='".$admin_id."' and doc_type='1' and episode_id='".$_GET['episodeid']."'","","","","");
		if(COUNT($check_diagnosis_active)==0){
		$insert_temp_icd_value=mysqlInsert('patient_diagnosis',$arrFileds,$arrValues);
		}
		
		//Update doctor frequent diagnosis table
		$check_diagnosis = mysqlSelect("*","doctor_frequent_diagnosis","icd_id='".$icdid."' and doc_id='".$admin_id."' and doc_type='1'","","","","");
					$freq_count = $check_diagnosis[0]['freq_count']+1; //Count will increment by one
					$arrFieldsDIAGNO = array();
					$arrValuesDIAGNO = array();
					if(count($check_diagnosis)>0){
						$arrFieldsDIAGNO[] = 'freq_count';
						$arrValuesDIAGNO[] = $freq_count;
						$update_icd=mysqlUpdate('doctor_frequent_diagnosis',$arrFieldsDIAGNO,$arrValuesDIAGNO,"dfd_id = '".$check_diagnosis[0]['dfd_id']."'");
					}
					else
					{
						
						if(!empty($icdid))
						{
							$arrFieldsDIAGNO[] = 'icd_id';
							$arrValuesDIAGNO[] = $icdid;
						}
						
						if(!empty($admin_id))
						{
							$arrFieldsDIAGNO[] = 'doc_id';
							$arrValuesDIAGNO[] = $admin_id;
						}
						$arrFieldsDIAGNO[] = 'doc_type';
						$arrValuesDIAGNO[] = "1";
						$arrFieldsDIAGNO[] = 'freq_count';
						$arrValuesDIAGNO[] = "1";
						$insert_freq_symp=mysqlInsert('doctor_frequent_diagnosis',$arrFieldsDIAGNO,$arrValuesDIAGNO);
						
						
					}
	}
}
if(isset($_GET['delinvestid'])){
	
	mysqlDelete('patient_temp_investigation',"pti_id='".$_GET['delinvestid']."'");
}

if(isset($_GET['deleditinvestid'])){
	
	mysqlDelete('patient_temp_investigation',"pti_id='".$_GET['deleditinvestid']."'");
}

if(isset($_GET['delicdid'])){
	
	mysqlDelete('patient_diagnosis',"patient_diagnosis_id='".$_GET['delicdid']."'");
}
if(isset($_GET['delallInvest'])){
	
	mysqlDelete('patient_temp_investigation',"patient_id='".$_GET['patid']."' and doc_id='".$_GET['docid']."' and  doc_type='1'"); //Clear all temp data from table 'patient_diagnosis'
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
	
		
	$update_invest=mysqlUpdate('patient_temp_investigation',$arrFileds_invest,$arrValues_invest,"pti_id = '".$_GET['updateinvestid']."'");

}

$getTestCardioDetails= mysqlSelect("*","patient_temp_investigation","patient_id='".$patientid."' and doc_id='".$admin_id."' and doc_type='1' and status='1' and department='1'","","","","");
$getTestRadiologyDetails= mysqlSelect("*","patient_temp_investigation","patient_id='".$patientid."' and doc_id='".$admin_id."' and doc_type='1' and status='1' and department='3'","","","","");
$getTestOpthalDetails= mysqlSelect("*","patient_temp_investigation","patient_id='".$patientid."' and doc_id='".$admin_id."' and doc_type='1' and status='1' and department='2'","","","","");
$getLabTestDetails= mysqlSelect("*","patient_temp_investigation","patient_id='".$patientid."' and doc_id='".$admin_id."' and doc_type='1' and status='1' and department='4'","","","","");
$getOtherTestDetails= mysqlSelect("*","patient_temp_investigation","patient_id='".$patientid."' and doc_id='".$admin_id."' and doc_type='1' and status='1' and department='5'","","","","");

if((isset($_GET['investid']) || isset($_GET['loadtemplate'])) && (COUNT($getTestCardioDetails)>0 || COUNT($getTestRadiologyDetails)>0 || COUNT($getLabTestDetails)>0 || COUNT($getOtherTestDetails)>0 || COUNT($getTestOpthalDetails)>0)){


?>
					<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  action="my_patient_profile_save.php"  name="frmAddInvest" >
					<a class="btn btn-xs btn-white pull-right delete_all_diagnosis_test" data-patient-id="<?php echo $patientid; ?>" data-doctor-id="<?php echo $admin_id; ?>"><i class="fa fa-trash"></i> Clear All</a>
								<table class="table table-bordered">
								<?php if(COUNT($getLabTestDetails)>0){ ?>
										<thead>
										<tr>
										<th colspan="3">Clinical Laboratory Tests</th>
										
										</tr>
										<tr>
										<th>Test</th>
										<!--<th>Normal Value</th>-->
										<th>Result</th>
										<th>Delete</th>
										</tr>
										<thead>
										<tbody>
										<tr>
										
									<?php 
									
									foreach($getLabTestDetails as $getLabTestDetailsList)	
									
									
									{  ?>
									<tr id="delInvestRow<?php echo $getLabTestDetailsList['pti_id'];?>">
									<td><input type="hidden" name="group_test_id[]" value="<?php echo $getLabTestDetailsList['group_test_id']; ?>" /><input type="hidden" name="main_test_id[]" value="<?php echo $getLabTestDetailsList['main_test_id']; ?>" /><?php echo $getLabTestDetailsList['test_name']; ?></td>
									<!--<td><?php echo $getLabTestDetailsList['normal_range']; ?></td>-->
									<td><input type="text" class="tagName addTestActualVal" name="" value="<?php echo $getLabTestDetailsList['test_actual_value']; ?>" data-invest-id="<?php echo $getLabTestDetailsList['pti_id'];?>" placeholder="" style="width:100px;"></td>
									<td><a class="del_Invest" data-invest-id="<?php echo $getLabTestDetailsList['pti_id'];?>"><span class="label label-danger">Delete</span></a></td>
									</tr>
									<?php }
									?>
                                   </tbody>
								<?php } if(COUNT($getTestCardioDetails)>0){ ?>
										<thead>
										<tr>
										<th colspan="3">Cardiology Specific Tests</th>
										
										</tr>
										<tr>
										<th>Test</th>
										<!--<th>Normal Value</th>-->
										<th>Result</th>
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
									<!--<td><?php echo $getTestDetailsList['normal_range']; ?></td>-->
									<td><input type="text" class="tagName addTestActualVal" name="" value="<?php echo $getTestDetailsList['test_actual_value']; ?>" data-invest-id="<?php echo $getTestDetailsList['pti_id'];?>" placeholder="" style="width:100%;"></td>
									<td><a class="del_Invest" data-invest-id="<?php echo $getTestDetailsList['pti_id'];?>"><span class="label label-danger">Delete</span></a></td>
									</tr>
									<?php }
									?>
                                   </tbody>
								<?php } if(COUNT($getTestRadiologyDetails)>0){ ?>
										<thead>
										<tr>
										<th colspan="3">Radiology Specific Tests</th>
										
										</tr>
										<tr>
										<th>Test</th>
										<!--<th>Normal Value</th>-->
										<th>Result</th>
										<th>Delete</th>
										</tr>
										<thead>
										<tbody>
										<tr>
										
									<?php 
									
									foreach($getTestRadiologyDetails as $getRadiologyDetailsList)	
									
									
									{  ?>
									<tr id="delInvestRow<?php echo $getRadiologyDetailsList['pti_id'];?>">
									<td><input type="hidden" name="group_test_id[]" value="<?php echo $getRadiologyDetailsList['group_test_id']; ?>" /><input type="hidden" name="main_test_id[]" value="<?php echo $getRadiologyDetailsList['main_test_id']; ?>" /><?php echo $getRadiologyDetailsList['test_name']; ?></td>
									<!--<td><?php echo $getRadiologyDetailsList['normal_range']; ?></td>-->
									<td><input type="text" class="tagName addTestActualVal" name="" value="<?php echo $getRadiologyDetailsList['test_actual_value']; ?>" data-invest-id="<?php echo $getRadiologyDetailsList['pti_id'];?>" placeholder="" style="width:100%;"></td>
									<td><a class="del_Invest" data-invest-id="<?php echo $getRadiologyDetailsList['pti_id'];?>"><span class="label label-danger">Delete</span></a></td>
									</tr>
									<?php }
									?>
                                   </tbody>
								<?php } if(COUNT($getTestOpthalDetails)>0){ ?>
									<thead>
										<tr>
										<th colspan="4">Ophthalmology Specific Tests</th>
										
										</tr>
										<tr>
										<th>Test</th>
										<th>Right Eye &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Left Eye</th>
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
									<td><input type="text" class="right_eye" name="" data-investigation-id="<?php echo $getOpthalDetailsList['pti_id']; ?>"  value="" placeholder="" style="width:100px;"> &nbsp;&nbsp; <input type="text" class="left_eye" name="" value="" data-investigation-id="<?php echo $getOpthalDetailsList['pti_id']; ?>" placeholder="" style="width:100px;"></td>
									<td><a class="del_Invest" data-invest-id="<?php echo $getOpthalDetailsList['pti_id'];?>"><span class="label label-danger">Delete</span></a></td>
									</tr>
									<?php }
									?>
                                   </tbody>
								<?php } if(COUNT($getOtherTestDetails)>0){ ?>
										<thead>
										<tr>
										<th colspan="3">Other Tests</th>
										
										</tr>
										<tr>
										<th>Test</th>
										<!--<th>Normal Value</th>-->
										<th>Result</th>
										<th>Delete</th>
										</tr>
										<thead>
										<tbody>
										<tr>
										
									<?php 
									
									foreach($getOtherTestDetails as $getOtherTestDetailsList)	
									
									
									{  ?>
									<tr id="delInvestRow<?php echo $getOtherTestDetailsList['pti_id'];?>">
									<td><input type="hidden" name="group_test_id[]" value="<?php echo $getOtherTestDetailsList['group_test_id']; ?>" /><input type="hidden" name="main_test_id[]" value="<?php echo $getOtherTestDetailsList['main_test_id']; ?>" /><?php echo $getOtherTestDetailsList['test_name']; ?></td>
									<!--<td><?php echo $getOtherTestDetailsList['normal_range']; ?></td>-->
									<td><input type="text" class="tagName addTestActualVal" name="" value="<?php echo $getOtherTestDetailsList['test_actual_value']; ?>" data-invest-id="<?php echo $getOtherTestDetailsList['pti_id'];?>" placeholder="" style="width:100%;"></td>
									<td><a class="del_Invest" data-invest-id="<?php echo $getOtherTestDetailsList['pti_id'];?>"><span class="label label-danger">Delete</span></a></td>
									</tr>
									<?php }
									?>
                                   </tbody>
								<?php } ?>
								   </table>
					</form>
					
					
<?php } 

	if(isset($_GET['editinvestid']) || isset($_GET['editloadtemplate']))
	{ ?>
								<a class="btn btn-xs btn-white pull-right delete_all_diagnosis_test" data-patient-id="<?php echo $patient_id; ?>" data-doctor-id="<?php echo $admin_id; ?>"><i class="fa fa-trash"></i> Clear All</a>
								<table class="table table-bordered">
								<?php 
								$getTestCardioDetails= mysqlSelect("*","patient_temp_investigation","episode_id='".$_GET['episodeid']."' and department='1'","","","","");
								$getTestRadiologyDetails= mysqlSelect("*","patient_temp_investigation","episode_id='".$_GET['episodeid']."' and department='3'","","","","");
								$getTestOpthalDetails= mysqlSelect("*","patient_temp_investigation","episode_id='".$_GET['episodeid']."' and department='2'","","","","");
								$getLabTestDetails= mysqlSelect("*","patient_temp_investigation","episode_id='".$_GET['episodeid']."' and department='4'","","","","");
								$getOtherTestDetails= mysqlSelect("*","patient_temp_investigation","episode_id='".$_GET['episodeid']."' and department='5'","","","","");
								
								if(COUNT($getLabTestDetails)>0){ ?>
										<thead>
										<tr>
										<th colspan="3">Clinical Laboratory Tests</th>
										
										</tr>
										<tr>
										<th>Test</th>
										<!--<th>Normal Value</th>-->
										<th>Result</th>
										<th>Delete</th>
										</tr>
										<thead>
										<tbody>
										<tr>
										
									<?php 
									
									foreach($getLabTestDetails as $getLabTestDetailsList)	
									
									
									{  ?>
									<tr id="delInvestRow<?php echo $getLabTestDetailsList['pti_id'];?>">
									<td><input type="hidden" name="group_test_id[]" value="<?php echo $getLabTestDetailsList['group_test_id']; ?>" /><input type="hidden" name="main_test_id[]" value="<?php echo $getLabTestDetailsList['main_test_id']; ?>" /><?php echo $getLabTestDetailsList['test_name']; ?></td>
									<!--<td><?php echo $getLabTestDetailsList['normal_range']; ?></td>-->
									<td><input type="text" class="tagName editTestActualVal" name="" data-invest-id="<?php echo $getLabTestDetailsList['pti_id'];?>" value="<?php echo $getLabTestDetailsList['test_actual_value']; ?>"  style="width:100px;"></td>
									<td><a class="del_editInvest" data-invest-id="<?php echo $getLabTestDetailsList['pti_id'];?>"><span class="label label-danger">Delete</span></a></td>
									</tr>
									<?php }
									?>
                                   </tbody>
								<?php }
								if(COUNT($getTestCardioDetails)>0){ ?>
										<thead>
										<tr>
										<th colspan="3">Cardiology Specific Tests</th>
										
										</tr>
										<tr>
										<th>Test</th>
										<!--<th>Normal Value</th>-->
										<th>Result</th>
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
									<!--<td><?php echo $getTestDetailsList['normal_range']; ?></td>-->
									<td><input type="text" class="tagName editTestActualVal" name="" data-invest-id="<?php echo $getLabTestDetailsList['pti_id'];?>" value="<?php echo $getTestDetailsList['test_actual_value']; ?>"  style="width:100%;"></td>
									<td><a class="del_editInvest" data-invest-id="<?php echo $getTestDetailsList['pti_id'];?>"><span class="label label-danger">Delete</span></a></td>
									</tr>
									<?php }
									?>
                                   </tbody>
								<?php } if(COUNT($getTestRadiologyDetails)>0){ ?>
										<thead>
										<tr>
										<th colspan="3">Radiology Specific Tests</th>
										
										</tr>
										<tr>
										<th>Test</th>
										<!--<th>Normal Value</th>-->
										<th>Result</th>
										<th>Delete</th>
										</tr>
										<thead>
										<tbody>
										<tr>
										
									<?php 
									
									foreach($getTestRadiologyDetails as $getRadiologyDetailsList)	
									
									
									{  ?>
									<tr id="delInvestRow<?php echo $getRadiologyDetailsList['pti_id'];?>">
									<td><input type="hidden" name="group_test_id[]" value="<?php echo $getRadiologyDetailsList['group_test_id']; ?>" /><input type="hidden" name="main_test_id[]" value="<?php echo $getRadiologyDetailsList['main_test_id']; ?>" /><?php echo $getRadiologyDetailsList['test_name']; ?></td>
									<!--<td><?php echo $getRadiologyDetailsList['normal_range']; ?></td>-->
									<td><input type="text" class="tagName editTestActualVal" name="" data-invest-id="<?php echo $getLabTestDetailsList['pti_id'];?>" value="<?php echo $getRadiologyDetailsList['test_actual_value']; ?>"  style="width:100%;"></td>
									<td><a class="del_editInvest" data-invest-id="<?php echo $getRadiologyDetailsList['pti_id'];?>"><span class="label label-danger">Delete</span></a></td>
									</tr>
									<?php }
									?>
                                   </tbody>
								<?php } if(COUNT($getTestOpthalDetails)>0){ ?>
									<thead>
										<tr>
										<th colspan="3">Ophthal Specific Tests</th>
										
										</tr>
										<tr>
										<th>Test</th>
										<th>Right Eye &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Left Eye</th>
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
									<td><input type="text" class="right_eye" name="" data-investigation-id="<?php echo $getOpthalDetailsList['pti_id']; ?>"  value="<?php echo $getOpthalDetailsList['right_eye']; ?>" placeholder="" style="width:100px;"> &nbsp;&nbsp; <input type="text" class="left_eye" name="" value="<?php echo $getOpthalDetailsList['left_eye']; ?>" data-investigation-id="<?php echo $getOpthalDetailsList['pti_id']; ?>" placeholder="" style="width:100px;"></td>
									<td><a class="del_editInvest" data-invest-id="<?php echo $getOpthalDetailsList['pti_id'];?>"><span class="label label-danger">Delete</span></a></td>
									</tr>
									<?php }
									?>
                                   </tbody>
								<?php } if(COUNT($getOtherTestDetails)>0){ ?>
										<thead>
										<tr>
										<th colspan="3">Other Tests</th>
										
										</tr>
										<tr>
										<th>Test</th>
										<!--<th>Normal Value</th>-->
										<th>Result</th>
										<th>Delete</th>
										</tr>
										<thead>
										<tbody>
										<tr>
										
									<?php 
									
									foreach($getOtherTestDetails as $getOtherTestDetailsList)	
									
									
									{  ?>
									<tr id="delInvestRow<?php echo $getOtherTestDetailsList['pti_id'];?>">
									<td><input type="hidden" name="group_test_id[]" value="<?php echo $getOtherTestDetailsList['group_test_id']; ?>" /><input type="hidden" name="main_test_id[]" value="<?php echo $getOtherTestDetailsList['main_test_id']; ?>" /><?php echo $getOtherTestDetailsList['test_name']; ?></td>
									<!--<td><?php echo $getOtherTestDetailsList['normal_range']; ?></td>-->
									<td><input type="text" class="tagName editTestActualVal" name="" value="<?php echo $getOtherTestDetailsList['test_actual_value']; ?>"  style="width:100%;"></td>
									<td><a class="del_editInvest" data-invest-id="<?php echo $getOtherTestDetailsList['pti_id'];?>"><span class="label label-danger">Delete</span></a></td>
									</tr>
									<?php }
									?>
                                   </tbody>
								<?php } ?>
								   </table>	
	
	
<?php }


if(isset($_GET['icdid']))
{
$getICDDetails= mysqlSelect("*","patient_diagnosis","patient_id='".$patientid."' and doc_id='".$admin_id."' and doc_type='1' and status='1'","","","","");
	if(COUNT($getICDDetails)>0){
	$get_diagnosis = mysqlSelect("a.icd_id as icd_id,b.icd_code as icd_name,a.patient_diagnosis_id as patient_diagnosis_id","patient_diagnosis as a inner join icd_code as b on a.icd_id=b.icd_id","a.patient_id = '".$patientid."' and a.doc_id= '". $admin_id ."' and a.doc_type='1' and a.status='1'","","","","");
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
	$get_diagnosis = mysqlSelect("a.icd_id as icd_id,b.icd_code as icd_name,a.patient_diagnosis_id as patient_diagnosis_id","patient_diagnosis as a inner join icd_code as b on a.icd_id=b.icd_id","a.episode_id='".$_GET['episodeid']."'","","","","");
									while(list($key, $value) = each($get_diagnosis))	
									{
									echo "<input type='hidden' name='icd_id[]' value='".$value['icd_id']."' />
									<input type='hidden' name='patient_diagnosis_id[]' value='".$value['patient_diagnosis_id']."' />
									<div class='input-group m-b'><span class='tag label label-primary m-b m-r' style='margin-bottom:30px;'>" . $value['icd_name'] . "<a data-role='remove' class='text-white del_editdiagnosis m-l' data-diagnosis-id='".$value['patient_diagnosis_id']."'>x</a></span></div>"; 
									}
}
 
 ?>
