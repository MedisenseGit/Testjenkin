<?php
ob_start();
error_reporting(0); 
session_start();


$admin_id = $_SESSION['user_id'];
$patient_id = $_SESSION['patient_id'];
require_once("../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();



//$patientid=$_GET['patientid'];

//Add Trend Analysis Value
if(isset($_GET['investid']) && !empty($_GET['investid'])){
	
$params     = split("-", $_GET['investid']);
$invest_id=$params[0];
$getCheckTest= mysqlSelect("id,test_id,group_test","patient_diagnosis_tests","(id='".$invest_id."' and doc_id='".$admin_id."' and doc_type='1') or (id='".$invest_id."' and doc_id='0' and doc_type='0')","","","","");
		if(COUNT($getCheckTest)>0){
		}
		else
		{
			$arrFileds_test[]='test_id';
			$arrValues_test[]= time();
			
			if(!empty($admin_id))
			{
				$arrFileds_test[]='doc_id';
				$arrValues_test[]=$admin_id;
			}
				
			// if(!empty($admin_id))
			// {
				// $arrFieldsSYMPFREQ[] = 'doc_id';
				// $arrValuesSYMPFREQ[] = $admin_id;
			// }
						
			
			$arrFileds_test[]='doc_type';
			$arrValues_test[]="1";
			$arrFileds_test[]='test_name_site_name';
			$arrValues_test[]=$invest_id;
			$arrFileds_test[]='group_test';
			$arrValues_test[]="N";
			$arrFileds_test[]='department';
			$arrValues_test[]="5";
			
			$insert_new_val=mysqlInsert('patient_diagnosis_tests',$arrFileds_test,$arrValues_test);
			$invest_id= $insert_new_val;
		}
if(is_numeric($invest_id) == true)
{
		$arrFileds = array();
		$arrValues = array();
		
		if(!empty($admin_id))
		{
			$arrFileds[]='doc_id';
			$arrValues[]=$admin_id;
		}
		if(!empty($invest_id))
		{
			$arrFileds[]='invest_id';
			$arrValues[]=$invest_id;
		}
		if(!empty($_GET['patientid']))
		{
			$arrFileds[]='patient_id';
			$arrValues[]=$_GET['patientid'];
		}		
		
		
		
		

		$check_trend_active = mysqlSelect("*","trend_analysis_investigations","doc_id='".$admin_id."' and patient_id='".$_GET['patientid']."' and invest_id='".$invest_id."' and date_added='0000-00-00'","","","","");
		if(COUNT($check_trend_active)==0){		
		$insert_invests=mysqlInsert('trend_analysis_investigations',$arrFileds,$arrValues);
		
		}
		else if($check_trend_active[0]['active_status']==1){
			$arrFields = array();
			$arrValues = array();
			$arrFields[]='active_status';
			$arrValues[]='0';
			
			$update_invests=mysqlUpdate('trend_analysis_investigations',$arrFields,$arrValues,"doc_id='".$admin_id."' and patient_id='".$_GET['patientid']."' and invest_id='".$invest_id."'");
	
			
		}
		
		
}

	$getInvestigation= mysqlSelect("a.trend_invest_id,a.doc_id,a.invest_id,b.test_name_site_name","trend_analysis_investigations as a left join patient_diagnosis_tests as b on a.invest_id=b.id","a.doc_id='".$admin_id."' and a.invest_value='0' and patient_id='".$_GET['patientid']."' and a.date_added='0000-00-00' and a.active_status='0'","a.trend_invest_id DESC","","","");

 
								while(list($key, $value) = each($getInvestigation)){ 
									echo "<input type='hidden' name='investID[]' value='" . $value['trend_invest_id'] . "' /><span class='tag label label-primary m-r' style='display:inline-block;'>" . $value['test_name_site_name'] . "<a data-role='remove' class='text-white del_trend_invest m-l' data-invest-id='".$value['trend_invest_id']."' data-patient-id='".$_GET['patientid']."'>x</a></span>";
								}	
		
	
}
//End of add trend analysis

if(empty($_GET['investid'])){
	
	$getInvestigation= mysqlSelect("trend_invest_id,doc_id,invest_id,test_name_site_name","trend_analysis_investigations as a left join patient_diagnosis_tests as b on a.invest_id=b.id","doc_id='".$admin_id."'","a.trend_invest_id DESC","","","");

 
								while(list($key, $value) = each($getInvestigation)){ 
									echo "<input type='hidden' name='investID[]' value='" . $value['trend_invest_id'] . "' /><span class='tag label label-primary m-r' style='display:inline-block;'>" . $value['test_name_site_name'] . "<a data-role='remove' class='text-white del_trend_invest m-l' data-invest-id='".$value['trend_invest_id']."' >x</a></span>";
								}
}



if(isset($_GET['delinvestid'])){
	$arrFields = array();
	$arrValues = array();
	$arrFields[]='active_status';
	$arrValues[]='1';
	
	$check_trend_active = mysqlSelect("*","trend_analysis_investigations","trend_invest_id='".$_GET['delinvestid']."'","","","","");
		if(COUNT($check_trend_active)>0){	
	//mysqlDelete('trend_analysis_investigations',"trend_invest_id='".$_GET['delinvestid']."'");
	$update_invests=mysqlUpdate('trend_analysis_investigations',$arrFields,$arrValues,"doc_id='".$admin_id."' and patient_id='".$check_trend_active[0]['patient_id']."' and invest_id='".$check_trend_active[0]['invest_id']."'");
		}
}

if(isset($_GET['pat_id'])){
	$patient_id=$_GET['pat_id'];
	
	$getInvestigate= mysqlSelect("a.trend_invest_id,a.doc_id,a.invest_id,b.test_name_site_name,a.invest_value,a.date_added","trend_analysis_investigations as a left join patient_diagnosis_tests as b on a.invest_id=b.id","a.doc_id='".$admin_id."' and patient_id='".$patient_id."'  and a.invest_value='0' and a.date_added='0000-00-00' and a.active_status='0'","","","","");
	
	$get_CustomTrendAnalysisDate1 = mysqlSelect("date_added","trend_analysis_investigations","patient_id='".$patient_id."' and date_added!='0000-00-00' and active_status='0'","date_added asc","date_added","","0,8");
	$get_CustomTrendAnalysisPPCount1 = mysqlSelect("invest_value,date_added","trend_analysis_investigations","patient_id='".$patient_id."' and invest_id = '".$getInvestigate[0]['invest_id']."' and date_added!='0000-00-00' and active_status='0'","date_added asc","","","0,8");
	$get_CustomTrendAnalysisPPAfterCount1 = mysqlSelect("invest_value,date_added","trend_analysis_investigations","patient_id='".$patient_id."' and invest_id = '".$getInvestigate[1]['invest_id']."' and date_added!='0000-00-00' and active_status='0'","date_added asc","","","0,8");
	$get_CustomTrendAnalysisSystolic1 = mysqlSelect("invest_value,date_added","trend_analysis_investigations","patient_id='".$patient_id."' and invest_id = '".$getInvestigate[2]['invest_id']."' and date_added!='0000-00-00' and active_status='0'","date_added asc","","","0,8");
	$get_CustomTrendAnalysisDiastolic1 = mysqlSelect("invest_value,date_added","trend_analysis_investigations","patient_id='".$patient_id."' and invest_id = '".$getInvestigate[3]['invest_id']."' and date_added!='0000-00-00' and active_status='0'","date_added asc","","","0,8");
	$get_CustomTrendAnalysisHbA1c1 = mysqlSelect("invest_value,date_added","trend_analysis_investigations","patient_id='".$patient_id."' and invest_id = '".$getInvestigate[4]['invest_id']."' and date_added!='0000-00-00' and active_status='0'","date_added asc","","","0,8");
	$get_CustomTrendAnalysisHDL1 = mysqlSelect("invest_value,date_added","trend_analysis_investigations","patient_id='".$patient_id."' and invest_id = '".$getInvestigate[5]['invest_id']."' and date_added!='0000-00-00' and active_status='0'","date_added asc","","","0,8");
	$get_CustomTrendAnalysisVLDL1 = mysqlSelect("invest_value,date_added","trend_analysis_investigations","patient_id='".$patient_id."' and invest_id = '".$getInvestigate[6]['invest_id']."' and date_added!='0000-00-00' and active_status='0'","date_added asc","","","0,8");
	$get_CustomTrendAnalysisLDL1 = mysqlSelect("invest_value,date_added","trend_analysis_investigations","patient_id='".$patient_id."' and invest_id = '".$getInvestigate[7]['invest_id']."' and date_added!='0000-00-00' and active_status='0'","date_added asc","","","0,8");
	$get_CustomTrendAnalysisTriglyceride1 = mysqlSelect("invest_value,date_added","trend_analysis_investigations","patient_id='".$patient_id."' and invest_id = '".$getInvestigate[8]['invest_id']."' and date_added!='0000-00-00' and active_status='0'","date_added asc","","","0,8");
	$get_CustomTrendAnalysisCholesterol1 = mysqlSelect("invest_value,date_added","trend_analysis_investigations","patient_id='".$patient_id."' and invest_id = '".$getInvestigate[9]['invest_id']."' and date_added!='0000-00-00' and active_status='0'","date_added asc","","","0,8");
	
	?>
	<div style="width:100%; height:400px;">
			<canvas id="lineChart" ></canvas>
	</div> 
	<script>
        $(document).ready(function() {
	var configuration = {
		type: 'line',
		data: {
			labels: [<?php while(list($key, $value) = each($get_CustomTrendAnalysisDate1)){ echo $dateAdded= "'".date('d-M-Y',strtotime($value['date_added']))."',"; } ?>],
			datasets: [<?php  if(!empty($getInvestigate[0]['test_name_site_name'])){?>{
				label: <?php echo "'".$getInvestigate[0]['test_name_site_name']."'"; ?>,
				backgroundColor: window.chartColors.red,
				borderColor: window.chartColors.red,
				fill: false,
				data: [<?php for($k=0;$k<count($get_CustomTrendAnalysisDate1);$k++){$j=0; for($i=0;$i<count($get_CustomTrendAnalysisPPCount1);$i++){ if($get_CustomTrendAnalysisPPCount1[$i]['date_added']==$get_CustomTrendAnalysisDate1[$k]['date_added']){ echo $get_CustomTrendAnalysisPPCount1[$i]['invest_value'].","; $j++;}} if($j==0){  echo '0'.','; }?><?php } ?>],
				//data: [120,124,122,126,],
			}<?php } if(!empty($getInvestigate[1]['test_name_site_name'])){?>, {
				label: <?php echo "'".$getInvestigate[1]['test_name_site_name']."'"; ?>,
				backgroundColor: window.chartColors.blue,
				borderColor: window.chartColors.blue,
				fill: false,
				data: [<?php for($k=0;$k<count($get_CustomTrendAnalysisDate1);$k++){$j=0;for($i=0;$i<count($get_CustomTrendAnalysisPPAfterCount1);$i++){if($get_CustomTrendAnalysisPPAfterCount1[$i]['date_added']==$get_CustomTrendAnalysisDate1[$k]['date_added']){ echo $get_CustomTrendAnalysisPPAfterCount1[$i]['invest_value'].",";  $j++;}} if($j==0){ echo '0'.','; }?><?php }?>],
				//data: [120,124,122,126,],
			}<?php } if(!empty($getInvestigate[2]['test_name_site_name'])){?>, {
				
				label: <?php echo "'".$getInvestigate[2]['test_name_site_name']."'"; ?>,
				backgroundColor: window.chartColors.orange,
				borderColor: window.chartColors.orange,
				fill: false,
				data: [<?php for($k=0;$k<count($get_CustomTrendAnalysisDate1);$k++){$j=0;for($i=0;$i<count($get_CustomTrendAnalysisSystolic1);$i++){if($get_CustomTrendAnalysisSystolic1[$i]['date_added']==$get_CustomTrendAnalysisDate1[$k]['date_added']){ echo $get_CustomTrendAnalysisSystolic1[$i]['invest_value'].",";  $j++;}} if($j==0){ echo '0'.','; }?><?php } ?>],
				
				//data: [120,124,122,126,],
				
		}<?php } if(!empty($getInvestigate[3]['test_name_site_name'])){?>, {
				
				label: <?php echo "'".$getInvestigate[3]['test_name_site_name']."'"; ?>,
				backgroundColor: window.chartColors.mediumaquamarine,
				borderColor: window.chartColors.mediumaquamarine,
				fill: false,
				data: [<?php for($k=0;$k<count($get_CustomTrendAnalysisDate1);$k++){$j=0;for($i=0;$i<count($get_CustomTrendAnalysisDiastolic1);$i++){if($get_CustomTrendAnalysisDiastolic1[$i]['date_added']==$get_CustomTrendAnalysisDate1[$k]['date_added']){ echo $get_CustomTrendAnalysisDiastolic1[$i]['invest_value'].",";  $j++;}} if($j==0){ echo '0'.','; }?><?php } ?>],
				
				//data: [120,124,122,126,],
			}<?php } if(!empty($getInvestigate[4]['test_name_site_name'])){?>, {
				label: <?php echo "'".$getInvestigate[4]['test_name_site_name']."'"; ?>,
				backgroundColor: window.chartColors.purple,
				borderColor: window.chartColors.purple,
				fill: false,
				data: [<?php for($k=0;$k<count($get_CustomTrendAnalysisDate1);$k++){$j=0;for($i=0;$i<count($get_CustomTrendAnalysisHbA1c1);$i++){if($get_CustomTrendAnalysisHbA1c1[$i]['date_added']==$get_CustomTrendAnalysisDate1[$k]['date_added']){ echo $get_CustomTrendAnalysisHbA1c1[$i]['invest_value'].",";  $j++;}} if($j==0){ echo '0'.','; }?><?php } ?>],
				
				//data: [120,124,122,126,],
			}<?php } if(!empty($getInvestigate[5]['test_name_site_name'])){?>, {
				label: <?php echo "'".$getInvestigate[5]['test_name_site_name']."'"; ?>,
				backgroundColor: window.chartColors.thistle,
				borderColor: window.chartColors.thistle,
				fill: false,
				data: [<?php for($k=0;$k<count($get_CustomTrendAnalysisDate1);$k++){ $j=0;for($i=0;$i<count($get_CustomTrendAnalysisHDL1);$i++){if($get_CustomTrendAnalysisHDL1[$i]['date_added']==$get_CustomTrendAnalysisDate1[$k]['date_added']){ echo $get_CustomTrendAnalysisHDL1[$i]['invest_value'].",";  $j++;}} if($j==0){echo '0'.','; }?><?php }?>],
				
				//data: [120,124,122,126,],
			}<?php } if(!empty($getInvestigate[6]['test_name_site_name'])){?>, {
				label: <?php echo "'".$getInvestigate[6]['test_name_site_name']."'"; ?>,
				backgroundColor: window.chartColors.sienna,
				borderColor: window.chartColors.sienna,
				fill: false,
				data: [<?php for($k=0;$k<count($get_CustomTrendAnalysisDate1);$k++){$j=0;for($i=0;$i<count($get_CustomTrendAnalysisVLDL1);$i++){if($get_CustomTrendAnalysisVLDL1[$i]['date_added']==$get_CustomTrendAnalysisDate1[$k]['date_added']){ echo $get_CustomTrendAnalysisVLDL1[$i]['invest_value'].","; $j++;}} if($j==0){ echo '0'.','; }?><?php } ?>],
				
				//data: [120,124,122,126,],
			}<?php } if(!empty($getInvestigate[7]['test_name_site_name'])){?>, {
				label: <?php echo "'".$getInvestigate[7]['test_name_site_name']."'"; ?>,
				backgroundColor: window.chartColors.teal,
				borderColor: window.chartColors.teal,
				fill: false,
				data: [<?php for($k=0;$k<count($get_CustomTrendAnalysisDate1);$k++){$j=0;for($i=0;$i<count($get_CustomTrendAnalysisLDL1);$i++){if($get_CustomTrendAnalysisLDL1[$i]['date_added']==$get_CustomTrendAnalysisDate1[$k]['date_added']){ echo $get_CustomTrendAnalysisLDL1[$i]['invest_value'].",";  $j++;}} if($j==0){ echo '0'.','; }?><?php }?>],
				
				//data: [120,124,122,126,],
			}<?php } if(!empty($getInvestigate[8]['test_name_site_name'])){?>, {
				label: <?php echo "'".$getInvestigate[8]['test_name_site_name']."'"; ?>,
				backgroundColor: window.chartColors.yellow,
				borderColor: window.chartColors.yellow,
				fill: false,
				data: [<?php for($k=0;$k<count($get_CustomTrendAnalysisDate1);$k++){$j=0;for($i=0;$i<count($get_CustomTrendAnalysisTriglyceride1);$i++){if($get_CustomTrendAnalysisTriglyceride1[$i]['date_added']==$get_CustomTrendAnalysisDate1[$k]['date_added']){ echo $get_CustomTrendAnalysisTriglyceride1[$i]['invest_value'].",";  $j++;}} if($j==0){ echo '0'.','; }?><?php }?>],
				
				//data: [120,124,122,126,],
			}<?php } if(!empty($getInvestigate[9]['test_name_site_name'])){?>, {
				label: <?php echo "'".$getInvestigate[9]['test_name_site_name']."'"; ?>,
				backgroundColor: window.chartColors.green,
				borderColor: window.chartColors.green,
				fill: false,
				data: [<?php for($k=0;$k<count($get_CustomTrendAnalysisDate1);$k++){$j=0;for($i=0;$i<count($get_CustomTrendAnalysisCholesterol1);$i++){if($get_CustomTrendAnalysisCholesterol1[$i]['date_added']==$get_CustomTrendAnalysisDate1[$k]['date_added']){ echo $get_CustomTrendAnalysisCholesterol1[$i]['invest_value'].",";  $j++;}} if($j==0){ echo '0'.','; }?><?php } ?>],
				
				//data: [120,124,122,126,],
			}<?php }?>]
		},
		options: {
			responsive: true,
			title: {
				display: true,
				text: 'Customized Trend Analysis'
			},
			scales: {
				xAxes: [{
					display: true,
				}],
				yAxes: [{
					display: true,
					type: 'logarithmic',
				}]
			}
		}
	};
 	
	
		
		var ctxx = document.getElementById('lineChart').getContext('2d');
		window.myLine = new Chart(ctxx, configuration);
	
		});
		</script>
	<?php 
}


//ADD PATIENT GLUCOSE COUNT
	
if(isset($_POST['addTrendAnalyseCount']))
{
		$getDate=date('Y-m-d',strtotime($_POST['custom_dateadded']));
		$before_meals_count=$_POST['custom_before_meals'];
		$after_meals_count=$_POST['custom_after_meals'];
		$systolicCount=$_POST['custom_systolicCount'];
		$diastolicCount=$_POST['custom_diastolicCount'];
		$hba1cCount=$_POST['custom_hba1cCount'];
		$hdlCount=$_POST['custom_hdlCount'];
		$vldlCount=$_POST['custom_vldlCount'];
		$ldlCount=$_POST['custom_ldlCount'];
		$triglycerideCount=$_POST['custom_triglycerideCount'];
		$cholestrolCount=$_POST['custom_cholestrolCount'];
		$patient_id=$_POST['custom_patient_id'];
		
		//$arrFields = array();
		//$arrValues = array();
		
		//$arrFields[]='patient_id';
		//$arrValues[]=$patient_id;
		
		//$arrFields[]='date_added';
		//$arrValues[]=$getDate;
		
		if(!empty($before_meals_count) && $before_meals_count!= "undefined"){
		$arrFieldsInvest1 = array();
		$arrValuesInvest1 = array();
		$arrFieldsInvest1[]='invest_value';
		$arrValuesInvest1[]=$before_meals_count;
		$arrFieldsInvest1[]='date_added';
		$arrValuesInvest1[]=$getDate;
		
		if(!empty($admin_id))
		{
			$arrFieldsInvest1[]='doc_id';
			$arrValuesInvest1[]=$admin_id;
		}
		if(!empty($_POST['invest_id0']))
		{
			$arrFieldsInvest1[]='invest_id';
			$arrValuesInvest1[]=$_POST['invest_id0'];
		}
		if(!empty($_GET['patientid']))
		{
			$arrFieldsInvest1[]='patient_id';
			$arrValuesInvest1[]=$patient_id;
		}		
		
		
		
		$check_trend_active = mysqlSelect("*","trend_analysis_investigations","doc_id='".$admin_id."' and patient_id='".$patient_id."' and date_added='".$getDate."' and invest_id='".$_POST['invest_id0']."' and date_added!='0000-00-00'","","","","");
		if(COUNT($check_trend_active)>0){	
			$insert_invests1=mysqlUpdate('trend_analysis_investigations',$arrFieldsInvest1,$arrValuesInvest1,"doc_id='".$admin_id."' and patient_id='".$patient_id."' and invest_id='".$_POST['invest_id0']."' and date_added='".$getDate."' and date_added!='0000-00-00'");
				
		
		}
		else{
			$insert_invests1=mysqlInsert('trend_analysis_investigations',$arrFieldsInvest1,$arrValuesInvest1);
		}
	}
		if(!empty($after_meals_count) && $after_meals_count!= "undefined"){
		$arrFieldsInvest2 = array();
		$arrValuesInvest2 = array();
		$arrFieldsInvest2[]='invest_value';
		$arrValuesInvest2[]=$after_meals_count;
		$arrFieldsInvest2[]='date_added';
		$arrValuesInvest2[]=$getDate;
		
		if(!empty($admin_id))
		{
			$arrFieldsInvest2[]='doc_id';
			$arrValuesInvest2[]=$admin_id;
		}
		if(!empty($_POST['invest_id1']))
		{
			$arrFieldsInvest2[]='invest_id';
			$arrValuesInvest2[]=$_POST['invest_id1'];
		}
		if(!empty($_GET['patientid']))
		{
			$arrFieldsInvest2[]='patient_id';
			$arrValuesInvest2[]=$patient_id;
		}		
		
		
		$check_trend_active = mysqlSelect("*","trend_analysis_investigations","doc_id='".$admin_id."' and date_added='".$getDate."' and patient_id='".$patient_id."' and invest_id='".$_POST['invest_id1']."' and date_added!='0000-00-00'","","","","");
		if(COUNT($check_trend_active)>0){		
		
		$insert_invests2=mysqlUpdate('trend_analysis_investigations',$arrFieldsInvest2,$arrValuesInvest2,"doc_id='".$admin_id."' and patient_id='".$patient_id."' and invest_id='".$_POST['invest_id1']."' and date_added!='0000-00-00' and date_added='".$getDate."'");
		
		}
		else
		{
			$insert_invests2=mysqlInsert('trend_analysis_investigations',$arrFieldsInvest2,$arrValuesInvest2);
		}
		}
		if(!empty($systolicCount) && $systolicCount!= "undefined"){
		$arrFieldsInvest3 = array();
		$arrValuesInvest3 = array();
		$arrFieldsInvest3[]='invest_value';
		$arrValuesInvest3[]=$systolicCount;
		$arrFieldsInvest3[]='date_added';
		$arrValuesInvest3[]=$getDate;
		
		if(!empty($admin_id))
		{
			$arrFieldsInvest3[]='doc_id';
			$arrValuesInvest3[]=$admin_id;
		}
		if(!empty($_POST['invest_id2']))
		{
			$arrFieldsInvest3[]='invest_id';
			$arrValuesInvest3[]=$_POST['invest_id2'];
		}
		if(!empty($_GET['patientid']))
		{
			$arrFieldsInvest3[]='patient_id';
			$arrValuesInvest3[]=$patient_id;
		}		
		
		
		$check_trend_active = mysqlSelect("*","trend_analysis_investigations","doc_id='".$admin_id."' and date_added='".$getDate."' and patient_id='".$patient_id."' and invest_id='".$_POST['invest_id2']."' and date_added!='0000-00-00'","","","","");
		if(COUNT($check_trend_active)>0){		
		
		$insert_invests3=mysqlUpdate('trend_analysis_investigations',$arrFieldsInvest3,$arrValuesInvest3,"doc_id='".$admin_id."' and patient_id='".$patient_id."' and invest_id='".$_POST['invest_id2']."' and date_added!='0000-00-00' and date_added='".$getDate."'");
		
		}
		else{
			$insert_invests3=mysqlInsert('trend_analysis_investigations',$arrFieldsInvest3,$arrValuesInvest3);
			 }
		}
		if(!empty($diastolicCount) && $diastolicCount!= "undefined"){
		$arrFieldsInvest4 = array();
		$arrValuesInvest4 = array();
		$arrFieldsInvest4[]='invest_value';
		$arrValuesInvest4[]=$diastolicCount;
		$arrFieldsInvest4[]='date_added';
		$arrValuesInvest4[]=$getDate;
		
		if(!empty($admin_id))
		{
			$arrFieldsInvest4[]='doc_id';
			$arrValuesInvest4[]=$admin_id;
		}
		if(!empty($_POST['invest_id3']))
		{
			$arrFieldsInvest4[]='invest_id';
			$arrValuesInvest4[]=$_POST['invest_id3'];
		}
		if(!empty($_GET['patientid']))
		{
			$arrFieldsInvest4[]='patient_id';
			$arrValuesInvest4[]=$patient_id;
		}		
		
		
		
		$check_trend_active = mysqlSelect("*","trend_analysis_investigations","doc_id='".$admin_id."' and date_added='".$getDate."' and patient_id='".$patient_id."' and invest_id='".$_POST['invest_id3']."' and date_added!='0000-00-00'","","","","");
		if(COUNT($check_trend_active)>0){		
		$insert_invests4=mysqlUpdate('trend_analysis_investigations',$arrFieldsInvest4,$arrValuesInvest4,"doc_id='".$admin_id."' and patient_id='".$patient_id."' and invest_id='".$_POST['invest_id3']."' and date_added!='0000-00-00' and date_added='".$getDate."'");
		
		}
		else{
			$insert_invests4=mysqlInsert('trend_analysis_investigations',$arrFieldsInvest4,$arrValuesInvest4);
		
		}
		}
		if(!empty($hba1cCount) && $hba1cCount!= "undefined"){
		$arrFieldsInvest5 = array();
		$arrValuesInvest5 = array();
		$arrFieldsInvest5[]='invest_value';
		$arrValuesInvest5[]=$hba1cCount;
		$arrFieldsInvest5[]='date_added';
		$arrValuesInvest5[]=$getDate;
		
		if(!empty($admin_id))
		{
			$arrFieldsInvest5[]='doc_id';
			$arrValuesInvest5[]=$admin_id;
		}
		if(!empty($_POST['invest_id3']))
		{
			$arrFieldsInvest5[]='invest_id';
			$arrValuesInvest5[]=$_POST['invest_id4'];
		}
		if(!empty($_GET['patientid']))
		{
			$arrFieldsInvest5[]='patient_id';
			$arrValuesInvest5[]=$patient_id;
		}	
		
		$check_trend_active = mysqlSelect("*","trend_analysis_investigations","doc_id='".$admin_id."' and date_added='".$getDate."' and patient_id='".$patient_id."' and invest_id='".$_POST['invest_id4']."' and date_added!='0000-00-00'","","","","");
		if(COUNT($check_trend_active)>0){		
		
		$insert_invests5=mysqlUpdate('trend_analysis_investigations',$arrFieldsInvest5,$arrValuesInvest5,"doc_id='".$admin_id."' and patient_id='".$patient_id."' and invest_id='".$_POST['invest_id4']."' and date_added!='0000-00-00' and date_added='".$getDate."'");
		
		}
		else{
			$insert_invests5=mysqlInsert('trend_analysis_investigations',$arrFieldsInvest5,$arrValuesInvest5);
		}
		}
		if(!empty($hdlCount) && $hdlCount!= "undefined"){
		$arrFieldsInvest6 = array();
		$arrValuesInvest6 = array();
		$arrFieldsInvest6[]='invest_value';
		$arrValuesInvest6[]=$hdlCount;
		$arrFieldsInvest6[]='date_added';
		$arrValuesInvest6[]=$getDate;
		
		if(!empty($admin_id))
		{
			$arrFieldsInvest6[]='doc_id';
		$arrValuesInvest6[]=$admin_id;
		}
		if(!empty($_POST['invest_id5']))
		{
			$arrFieldsInvest6[]='invest_id';
		$arrValuesInvest6[]=$_POST['invest_id5'];
		}
		if(!empty($_GET['patientid']))
		{
			$arrFieldsInvest6[]='patient_id';
		$arrValuesInvest6[]=$patient_id;
		}	
		
		
		$check_trend_active = mysqlSelect("*","trend_analysis_investigations","doc_id='".$admin_id."' and date_added='".$getDate."' and patient_id='".$patient_id."' and invest_id='".$_POST['invest_id5']."' and date_added!='0000-00-00'","","","","");
		if(COUNT($check_trend_active)>0){		
		$insert_invests6=mysqlUpdate('trend_analysis_investigations',$arrFieldsInvest6,$arrValuesInvest6,"doc_id='".$admin_id."' and patient_id='".$patient_id."' and invest_id='".$_POST['invest_id5']."' and date_added!='0000-00-00' and date_added='".$getDate."'");
		
		}
		else{
			$insert_invests6=mysqlInsert('trend_analysis_investigations',$arrFieldsInvest6,$arrValuesInvest6);
		
		}
		}
		if(!empty($vldlCount) && $vldlCount!= "undefined"){
		$arrFieldsInvest7 = array();
		$arrValuesInvest7 = array();
		$arrFieldsInvest7[]='invest_value';
		$arrValuesInvest7[]=$vldlCount;
		$arrFieldsInvest7[]='date_added';
		$arrValuesInvest7[]=$getDate;
		
		if(!empty($admin_id))
		{
			$arrFieldsInvest7[]='doc_id';
		$arrValuesInvest7[]=$admin_id;
		}
		if(!empty($_POST['invest_id6']))
		{
			$arrFieldsInvest7[]='invest_id';
		$arrValuesInvest7[]=$_POST['invest_id6'];
		}
		if(!empty($_GET['patientid']))
		{
			$arrFieldsInvest7[]='patient_id';
		$arrValuesInvest7[]=$patient_id;
		}	
		
		
		$check_trend_active = mysqlSelect("*","trend_analysis_investigations","doc_id='".$admin_id."' and date_added='".$getDate."' and patient_id='".$patient_id."' and invest_id='".$_POST['invest_id6']."' and date_added!='0000-00-00'","","","","");
		if(COUNT($check_trend_active)>0){		
		$insert_invests7=mysqlUpdate('trend_analysis_investigations',$arrFieldsInvest7,$arrValuesInvest7,"doc_id='".$admin_id."' and patient_id='".$patient_id."' and invest_id='".$_POST['invest_id6']."' and date_added!='0000-00-00' and date_added='".$getDate."'");
		
		}
		else{
		$insert_invests7=mysqlInsert('trend_analysis_investigations',$arrFieldsInvest7,$arrValuesInvest7);
			
		}
		}
		if(!empty($ldlCount) && $ldlCount!= "undefined"){
		$arrFieldsInvest8 = array();
		$arrValuesInvest8 = array();
		$arrFieldsInvest8[]='invest_value';
		$arrValuesInvest8[]=$ldlCount;
		$arrFieldsInvest8[]='date_added';
		$arrValuesInvest8[]=$getDate;
		
		if(!empty($admin_id))
		{
			$arrFieldsInvest8[]='doc_id';
		$arrValuesInvest8[]=$admin_id;
		}
		if(!empty($_POST['invest_id7']))
		{
			$arrFieldsInvest8[]='invest_id';
		$arrValuesInvest8[]=$_POST['invest_id7'];
		}
		if(!empty($_GET['patientid']))
		{
			$arrFieldsInvest8[]='patient_id';
		$arrValuesInvest8[]=$patient_id;
		}	
		
		$check_trend_active = mysqlSelect("*","trend_analysis_investigations","doc_id='".$admin_id."' and date_added='".$getDate."' and patient_id='".$patient_id."' and invest_id='".$_POST['invest_id7']."' and date_added!='0000-00-00'","","","","");
		if(COUNT($check_trend_active)>0){		
		$insert_invests8=mysqlUpdate('trend_analysis_investigations',$arrFieldsInvest8,$arrValuesInvest8,"doc_id='".$admin_id."' and patient_id='".$patient_id."' and invest_id='".$_POST['invest_id7']."' and date_added!='0000-00-00' and date_added='".$getDate."'");
		
		}
		else
		{
			$insert_invests8=mysqlInsert('trend_analysis_investigations',$arrFieldsInvest8,$arrValuesInvest8);
		
		}
		}
		if(!empty($triglycerideCount) && $triglycerideCount!= "undefined"){
		$arrFieldsInvest9 = array();
		$arrValuesInvest9 = array();
		$arrFieldsInvest9[]='invest_value';
		$arrValuesInvest9[]=$triglycerideCount;
		$arrFieldsInvest9[]='date_added';
		$arrValuesInvest9[]=$getDate;
		
		if(!empty($admin_id))
		{
			$arrFieldsInvest9[]='doc_id';
		$arrValuesInvest9[]=$admin_id;
		}
		if(!empty($_POST['invest_id8']))
		{
			$arrFieldsInvest9[]='invest_id';
		$arrValuesInvest9[]=$_POST['invest_id8'];
		}
		if(!empty($_GET['patientid']))
		{
			$arrFieldsInvest9[]='patient_id';
		$arrValuesInvest9[]=$patient_id;
		}	
		
		
		
		
		
		$check_trend_active = mysqlSelect("*","trend_analysis_investigations","doc_id='".$admin_id."' and date_added='".$getDate."' and patient_id='".$patient_id."' and invest_id='".$_POST['invest_id8']."' and date_added!='0000-00-00'","","","","");
		if(COUNT($check_trend_active)>0){		
		$insert_invests9=mysqlUpdate('trend_analysis_investigations',$arrFieldsInvest9,$arrValuesInvest9,"doc_id='".$admin_id."' and patient_id='".$patient_id."' and invest_id='".$_POST['invest_id8']."' and date_added!='0000-00-00' and date_added='".$getDate."'");
		}
		else{
			$insert_invests9=mysqlInsert('trend_analysis_investigations',$arrFieldsInvest9,$arrValuesInvest9);
		
		}
		}
		if(!empty($cholestrolCount) && $cholestrolCount!= "undefined"){
		$arrFieldsInvest10 = array();
		$arrValuesInvest10 = array();
		$arrFieldsInvest10[]='invest_value';
		$arrValuesInvest10[]=$cholestrolCount;
		$arrFieldsInvest10[]='date_added';
		$arrValuesInvest10[]=$getDate;
		
		if(!empty($admin_id))
		{
			$arrFieldsInvest10[]='doc_id';
		$arrValuesInvest10[]=$admin_id;
		}
		if(!empty($_POST['invest_id8']))
		{
			$arrFieldsInvest10[]='invest_id';
		$arrValuesInvest10[]=$_POST['invest_id9'];
		}
		if(!empty($_GET['patientid']))
		{
			$arrFieldsInvest10[]='patient_id';
		$arrValuesInvest10[]=$patient_id;
		}	
		
		
		
		
		
		
		$check_trend_active = mysqlSelect("*","trend_analysis_investigations","doc_id='".$admin_id."' and date_added='".$getDate."' and patient_id='".$patient_id."' and invest_id='".$_POST['invest_id9']."' and date_added!='0000-00-00'","","","","");
		if(COUNT($check_trend_active)>0){		
		$insert_invests10=mysqlUpdate('trend_analysis_investigations',$arrFieldsInvest10,$arrValuesInvest10,"doc_id='".$admin_id."' and patient_id='".$patient_id."' and invest_id='".$_POST['invest_id9']."' and date_added!='0000-00-00' and date_added='".$getDate."'");
		
		}
		else{
			$insert_invests10=mysqlInsert('trend_analysis_investigations',$arrFieldsInvest10,$arrValuesInvest10);
		
		}
		}
		//$arrFields[]='patient_type';
		//$arrValues[]="1";
		
		//$checkTrend= mysqlSelect("*","trend_analysis","date_added='".$getDate."' and patient_id='".$patient_id."' and patient_type='1'","","","","");
		//if(count($checkTrend)>0)
		//{
		//	$update_medicine=mysqlUpdate('trend_analysis',$arrFields,$arrValues,"date_added='".$getDate."' and patient_id = '".$patient_id."' and patient_type='1'");
		//}
		//else
		//{
		//$insert_patient=mysqlInsert('trend_analysis',$arrFields,$arrValues);
		//}
		$response="updated";
		//header("Location:My-Patient-Details?p=".md5($patient_id));
		echo $response;
}	

?>
