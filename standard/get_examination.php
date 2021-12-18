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

if(isset($_GET['examinationid']) && !empty($_GET['examinationid'])){
	
	$params = split("-", $_GET['examinationid']);
	
	if(is_numeric($params[0]) == false){
		$arrFileds_exam[]='examination';
		$arrValues_exam[]=$params[0];
		$arrFileds_exam[]='doc_id';
		$arrValues_exam[]=$admin_id;
		$arrFileds_exam[]='doc_type';
		$arrValues_exam[]='2';
		$insert_symptoms=$objQuery->mysqlInsert('examination',$arrFileds_exam,$arrValues_exam);
		$exam_id = mysql_insert_id(); //Get Patient Id
		
		$arrFileds_exam_freq[]='examination_id';
		$arrValues_exam_freq[]=$exam_id;
		$arrFileds_exam_freq[]='doc_id';
		$arrValues_exam_freq[]=$admin_id;
		$arrFileds_exam_freq[]='doc_type';
		$arrValues_exam_freq[]='2';
		$insert_symptoms=$objQuery->mysqlInsert('doctor_frequent_examination',$arrFileds_exam_freq,$arrValues_exam_freq);
		
	} else
	{
		$exam_id = $params[0];
	}
	
		$arrFileds = array();
		$arrValues = array();
		
		$arrFileds[]='examination';
		$arrValues[]=$exam_id;
							
		$arrFileds[]='patient_id';
		$arrValues[]=$_GET['patientid'];
		
		$arrFileds[]='doc_id';
		$arrValues[]=$admin_id;
		
		$arrFileds[]='doc_type';
		$arrValues[]="2";
		
		$arrFileds[]='status';
		$arrValues[]="1";
		
		$check_active_exam = $objQuery->mysqlSelect("*","doc_patient_examination_active","examination='".$params[0]."' and doc_id='".$admin_id."' and doc_type='2' and patient_id='".$_GET['patientid']."' and status='1'","","","","");
		if(COUNT($check_active_exam)==0){ //To prevent double entry
		$insert_symptoms=$objQuery->mysqlInsert('doc_patient_examination_active',$arrFileds,$arrValues);
		}
		
		$check_exam = $objQuery->mysqlSelect("*","doctor_frequent_examination","examination_id='".$params[0]."' and doc_id='".$admin_id."' and doc_type='2'","","","","");
					$freq_count = $check_exam[0]['freq_count']+1; //Count will increment by one
					$arrFieldsINVESTFREQ = array();
					$arrValuesINVESTFREQ = array();
					if(count($check_exam)>0){
						$arrFieldsINVESTFREQ[] = 'freq_count';
						$arrValuesINVESTFREQ[] = $freq_count;
						$update_icd=$objQuery->mysqlUpdate('doctor_frequent_examination',$arrFieldsINVESTFREQ,$arrValuesINVESTFREQ,"dfe_id = '".$check_exam[0]['dfe_id']."'");
					}
					

}

if(isset($_GET['editexaminationid']) && !empty($_GET['editexaminationid'])){
	
	$params = split("-", $_GET['editexaminationid']);
	
	if(is_numeric($params[0]) == false){
		$arrFileds_exam[]='examination';
		$arrValues_exam[]=$params[0];
		$arrFileds_exam[]='doc_id';
		$arrValues_exam[]=$admin_id;
		$arrFileds_exam[]='doc_type';
		$arrValues_exam[]='2';
		$insert_symptoms=$objQuery->mysqlInsert('examination',$arrFileds_exam,$arrValues_exam);
		$exam_id = mysql_insert_id(); //Get Patient Id
		
		$arrFileds_exam_freq[]='examination_id';
		$arrValues_exam_freq[]=$exam_id;
		$arrFileds_exam_freq[]='doc_id';
		$arrValues_exam_freq[]=$admin_id;
		$arrFileds_exam_freq[]='doc_type';
		$arrValues_exam_freq[]='2';
		$insert_symptoms=$objQuery->mysqlInsert('doctor_frequent_examination',$arrFileds_exam_freq,$arrValues_exam_freq);
		
	} else
	{
		$exam_id = $params[0];
	}
	
		$arrFileds = array();
		$arrValues = array();
		
		$arrFileds[]='examination';
		$arrValues[]=$exam_id;
							
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
		
		$check_active_exam = $objQuery->mysqlSelect("*","doc_patient_examination_active","examination='".$params[0]."' and doc_id='".$admin_id."' and doc_type='2' and patient_id='".$patient_id."' and episode_id='".$_GET['episodeid']."'","","","","");
		if(COUNT($check_active_exam)==0){ //To prevent double entry
		$insert_symptoms=$objQuery->mysqlInsert('doc_patient_examination_active',$arrFileds,$arrValues);
		}
		
		$check_exam = $objQuery->mysqlSelect("*","doctor_frequent_examination","examination_id='".$params[0]."' and doc_id='".$admin_id."' and doc_type='2'","","","","");
					$freq_count = $check_exam[0]['freq_count']+1; //Count will increment by one
					$arrFieldsINVESTFREQ = array();
					$arrValuesINVESTFREQ = array();
					if(count($check_exam)>0){
						$arrFieldsINVESTFREQ[] = 'freq_count';
						$arrValuesINVESTFREQ[] = $freq_count;
						$update_icd=$objQuery->mysqlUpdate('doctor_frequent_examination',$arrFieldsINVESTFREQ,$arrValuesINVESTFREQ,"dfe_id = '".$check_exam[0]['dfe_id']."'");
					}
					

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
				$arrValues_exam[]=$_GET['examfindings'];
	}
	
		
	$update_exam=$objQuery->mysqlUpdate('doc_patient_examination_active',$arrFileds_exam,$arrValues_exam,"examination_id = '".$_GET['updateexamid']."'");

}

if(isset($_GET['delallExam']))
{
	//Delete perticular symptoms from table 'doc_patient_examination_active'
	$objQuery->mysqlDelete('doc_patient_examination_active',"doc_id='".$admin_id."' and doc_type='2' and status='1'");
}

if(isset($_GET['delallEditExam']))
{
	//Delete all examination  from table 'doc_patient_examination_active'
	$objQuery->mysqlDelete('doc_patient_examination_active',"md5(episode_id)='".$_GET['delallEditExam']."'");
}

if(isset($_GET['deleaxaminationid']) || isset($_GET['deleditexaminationid']))
{
	//Delete perticular symptoms from table 'doc_patient_symptoms_active'
	$objQuery->mysqlDelete('doc_patient_examination_active',"examination_id='".$_GET['deleaxaminationid']."' or examination_id='".$_GET['deleditexaminationid']."'");
}


if(isset($_GET['examinationid']) && !empty($_GET['examinationid']))
{
$getExamination= $objQuery->mysqlSelect("b.examination as examination,a.examination_id as examination_id,a.exam_result as exam_result,a.findings as findings","doc_patient_examination_active as a left join examination as b on a.examination=b.examination_id","a.doc_id='".$admin_id."' and a.patient_id='".$_GET['patientid']."' and a.doc_type='2' and a.status='1'","a.examination_id asc","","","");

?>
							
				<a class="btn btn-xs btn-white pull-right delete_all_examination" data-patient-id="<?php echo $patientid; ?>" data-doctor-id="<?php echo $admin_id; ?>"><i class="fa fa-trash"></i> Clear All</a>
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
									<td><?php echo $getExaminationList['examination']; ?></td>
									<td><select class="form-control exam_res" name="slctReslt" data-examination-id="<?php echo $getExaminationList['examination_id']; ?>" style="width:200px;">
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
									<td><input type="text" class="form-control findings" name="finding" data-examination-id="<?php echo $getExaminationList['examination_id']; ?>" value="<?php echo $getExaminationList['findings']; ?>" placeholder="Finding" style="width:650px;"></td>
									<td><a class="del_examination" data-examination-id="<?php echo $getExaminationList['examination_id'];?>"><span class="label label-danger">Delete</span></a></td>
									</tr>
									<?php }
									?>
                                   </tbody>
					</table>
				
					
<?php } 
if(isset($_GET['editexaminationid']) && !empty($_GET['editexaminationid']))
{
$getExamination= $objQuery->mysqlSelect("a.examination_id as examination_id,b.examination as examination,a.exam_result as exam_result,a.findings as findings","doc_patient_examination_active as a left join examination as b on a.examination=b.examination_id","a.episode_id='".$_GET['episodeid']."'","a.examination_id asc","","","");
								
?>	
<a class="btn btn-xs btn-white pull-right delete_all_edit_examination" data-episode-id="<?php echo md5($_GET['episodeid']); ?>" data-doctor-id="<?php echo $admin_id; ?>"><i class="fa fa-trash"></i> Clear All</a>
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
									
									<tr id="del_editexamination_row<?php echo $getExaminationList['examination_id'];?>">
									<td><?php echo $getExaminationList['examination']; ?></td>
									<td><select class="form-control exam_res" name="slctReslt" data-examination-id="<?php echo $getExaminationList['examination_id']; ?>" style="width:200px;">
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
									<td><input type="text" class="form-control findings" name="finding" id="findings" data-examination-id="<?php echo $getExaminationList['examination_id']; ?>" value="<?php echo $getExaminationList['findings']; ?>" placeholder="Finding" style="width:650px;"></td>
									<td><a class="del_editexamination" data-examination-id="<?php echo $getExaminationList['examination_id'];?>"><span class="label label-danger">Delete</span></a></td>
									</tr>
									<?php }
									?>
                                   </tbody>
					</table>	
	
<?php } ?>				