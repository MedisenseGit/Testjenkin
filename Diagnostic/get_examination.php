<?php
ob_start();
error_reporting(0); 
session_start();


$admin_id = $_SESSION['user_id'];
if(empty($admin_id)){
	header("Location:index.php");
}
require_once("../classes/querymaker.class.php");


if(isset($_GET['examinationid']) && !empty($_GET['examinationid'])){
	
	$params = split("-", $_GET['examinationid']);
	
	/*if(is_numeric($params[0]) == false){
		$arrFileds_exam = array();
		$arrValues_exam = array();
		
		$arrFileds_exam[]='examination';
		$arrValues_exam[]=$params[0];
		$arrFileds_exam[]='diagnostic_id';
		$arrValues_exam[]=$admin_id;
	
		
		$insert_symptoms=mysqlInsert('diagnosis_frequent_examination',$arrFileds_exam,$arrValues_exam);
		$exam_id = mysql_insert_id(); //Get Patient Id
	} else
	{
		$exam_id = $params[0];
	}*/
	if(is_numeric($params[0]) == true){
		
		$exam_id = $params[0];
		
		$arrFileds = array();
		$arrValues = array();
		
		$arrFileds[]='examination';
		$arrValues[]=$exam_id;
							
		$arrFileds[]='diagnostic_id';
		$arrValues[]=$admin_id;
		
		$arrFileds[]='diagnostic_customer_id';
		$arrValues[]=$_GET['patientid'];
			
		$arrFileds[]='status';
		$arrValues[]="1";
		
		$insert_symptoms=mysqlInsert('diagnostic_patient_examination_active',$arrFileds,$arrValues);
	}
		
		/*$check_exam = mysqlSelect("*","diagnosis_frequent_examination","diagno_exam_id='".$exam_id."' and diagnostic_id='".$admin_id."'","","","","");
					$freq_count = $check_exam[0]['freq_count']+1; //Count will increment by one
					$arrFieldsINVESTFREQ = array();
					$arrValuesINVESTFREQ = array();
					if(count($check_exam)>0){
						$arrFieldsINVESTFREQ[] = 'freq_count';
						$arrValuesINVESTFREQ[] = $freq_count;
						$update_icd=mysqlUpdate('diagnosis_frequent_examination',$arrFieldsINVESTFREQ,$arrValuesINVESTFREQ,"diagno_exam_id = '".$check_exam[0]['diagno_exam_id']."'");
					}*/
					

}

if(isset($_GET['updateexamid']) && !empty($_GET['updateexamid'])){
	
	if(isset($_GET['examres']))
	{
				$arrFileds_exam[]='exam_result';
				$arrValues_exam[]=$_GET['examres'];
	}
	if(isset($_GET['examfindings']))
	{
				$arrFileds_exam[]='findings';
				$arrValues_exam[]=addslashes($_GET['examfindings']);
	}
	
		
	$update_exam=mysqlUpdate('diagnostic_patient_examination_active',$arrFileds_exam,$arrValues_exam,"examination_id = '".$_GET['updateexamid']."'");

}

if(isset($_GET['deleaxaminationid']))
{
	//Delete perticular symptoms from table 'diagnostic_patient_examination_active'
	mysqlDelete('diagnostic_patient_examination_active',"examination_id='".$_GET['deleaxaminationid']."'");
}

if(isset($_GET['delallExam']))
{
	//Delete perticular symptoms from table 'diagnostic_patient_examination_active'
	mysqlDelete('diagnostic_patient_examination_active',"diagnostic_customer_id='".$_GET['patid']."' and diagnostic_id='".$_GET['docid']."' and status='1'");
}
/*$getExamination= mysqlSelect("b.examination as examination,a.diagnosis_examination_id as diagnosis_examination_id","diagnosis_patient_examination_active as a left join diagnosis_frequent_examination as b on a.diagno_exam_id=b.diagno_exam_id","a.diagnostic_id='".$admin_id."' and a.patient_id='".$_GET['patientid']."' and a.status='1'","a.diagnosis_examination_id asc","","","");

?>
								<?php 
								while(list($key, $value) = each($getExamination)){ 
									echo '<span class="tag label label-primary m-r">' . $value['examination'] . '<a data-role="remove" class="text-white del_examination m-l" data-examination_id="'.$value['diagnosis_examination_id'].'">x</a></span>';
								}*/
								
if(isset($_GET['examinationid']) && !empty($_GET['examinationid']))
{
$getExamination= mysqlSelect("b.examination as examination,a.examination_id as examination_id,a.exam_result as exam_result,a.findings as findings","diagnostic_patient_examination_active as a left join examination as b on a.examination=b.examination_id","a.diagnostic_id='".$admin_id."' and a.diagnostic_customer_id='".$_GET['patientid']."' and a.status='1'","a.examination_id asc","","","");

?>
							
				<a class="btn btn-xs btn-white pull-right delete_all_examination" data-patient-id="<?php echo $_GET['patientid']; ?>" data-doctor-id="<?php echo $admin_id; ?>"><i class="fa fa-trash"></i> Clear All</a>
					<table class="table table-bordered">
										<thead>
										<tr>
										<th>Examination</th>
										<th>Result</th>
										<th>Finding</th>
										<th>Delete</th>
										</tr>
										<thead>
										<tbody>
										<tr>
										
									<?php 
									
									foreach($getExamination as $getExaminationList){ ?>
									
									<tr id="del_examination_row<?php echo $getExaminationList['examination_id'];?>">
									<td><input type="hidden" name="examination_id[]" value="<?php echo $getExaminationList['examination_id']; ?>"/><?php echo $getExaminationList['examination']; ?></td>
									<td><select class="form-control exam_res" name="slctReslt[]" data-examination-id="<?php echo $getExaminationList['examination_id']; ?>" style="width:200px;">
									<?php if($getExaminationList['exam_result']=="Normal"){ ?>
									<option value="Normal" selected>Normal</option>
									<option value="Abnormal">Abnormal</option>
									<?php } else if($getExaminationList['exam_result']=="Abnormal"){ ?>
									<option value="Normal" >Normal</option>
									<option value="Abnormal" selected>Abnormal</option>
									<?php } else { ?>
									<option value="">Select</option>
									<option value="Normal">Normal</option>
									<option value="Abnormal">Abnormal</option>
									<?php } ?>
									</select></td>
									<td><input type="text" class="form-control findings" name="findings[]" data-examination-id="<?php echo $getExaminationList['examination_id']; ?>" value="<?php echo $getExaminationList['findings']; ?>" placeholder="Finding" style="width:350px;"></td>
									<td><a class="del_examination" data-examination-id="<?php echo $getExaminationList['examination_id'];?>"><span class="label label-danger">Delete</span></a></td>
									</tr>
									<?php }
									?>
                                   </tbody>
					</table>
				
					
<?php } 
								?>
								