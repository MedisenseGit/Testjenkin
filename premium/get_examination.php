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

if(isset($_GET['examinationid']) && !empty($_GET['examinationid'])){
	
	$params = explode("-", $_GET['examinationid']);
	
	if(is_numeric($params[0]) == false){
		$arrFileds_exam[]='examination';
		$arrValues_exam[]=$params[0];
		
		
		// if(!empty($icdid))
		// {
			// $arrFieldsDIAGNO[] = 'icd_id';
			// $arrValuesDIAGNO[] = $icdid;
		// }
		
		if(!empty($admin_id))
		{
			$arrFileds_exam[]='doc_id';
			$arrValues_exam[]=$admin_id;
		}
						
		
		$arrFileds_exam[]='doc_type';
		$arrValues_exam[]='1';
		$insert_symptoms=mysqlInsert('examination',$arrFileds_exam,$arrValues_exam);
		$exam_id = $insert_symptoms; //Get Patient Id
		
		if(!empty($exam_id))
		{
			$arrFileds_exam_freq[]='examination_id';
			$arrValues_exam_freq[]=$exam_id;
		}
		
		if(!empty($admin_id))
		{
			$arrFileds_exam_freq[]='doc_id';
			$arrValues_exam_freq[]=$admin_id;
		}
		
		
		
		$arrFileds_exam_freq[]='doc_type';
		$arrValues_exam_freq[]='1';
		$insert_symptoms=mysqlInsert('doctor_frequent_examination',$arrFileds_exam_freq,$arrValues_exam_freq);
		
	} else
	{
		$exam_id = $params[0];
	}
	
		$arrFileds = array();
		$arrValues = array();
		
		if(!empty($exam_id))
		{
			$arrFileds[]='examination';
			$arrValues[]=$exam_id;
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
		
		$check_active_exam = mysqlSelect("*","doc_patient_examination_active","examination='".$params[0]."' and doc_id='".$admin_id."' and doc_type='1' and patient_id='".$_GET['patientid']."' and status='1'","","","","");
		if(COUNT($check_active_exam)==0){ //To prevent double entry
		$insert_symptoms=mysqlInsert('doc_patient_examination_active',$arrFileds,$arrValues);
		}
		
		$check_exam = mysqlSelect("*","doctor_frequent_examination","examination_id='".$params[0]."' and doc_id='".$admin_id."' and doc_type='1'","","","","");
					$freq_count = $check_exam[0]['freq_count']+1; //Count will increment by one
					$arrFieldsINVESTFREQ = array();
					$arrValuesINVESTFREQ = array();
					if(count($check_exam)>0){
						$arrFieldsINVESTFREQ[] = 'freq_count';
						$arrValuesINVESTFREQ[] = $freq_count;
						$update_icd=mysqlUpdate('doctor_frequent_examination',$arrFieldsINVESTFREQ,$arrValuesINVESTFREQ,"dfe_id = '".$check_exam[0]['dfe_id']."'");
					}
					

}
if(isset($_GET['loadtemplate']) && !empty($_GET['loadtemplate'])){
	$getTemplateDetails= mysqlSelect("*","doc_patient_episode_examination_template_details","md5(exam_template_id)='".$_GET['loadtemplate']."'","","","","");
while(list($key,$value) = each($getTemplateDetails))
	{		
		
		$arrFileds = array();
		$arrValues = array();
		
		$arrFileds[]='examination';
		$arrValues[]=$value['examination'];
		
		$arrFileds[]='exam_result';
		$arrValues[]=$value['exam_result'];
		
		$arrFileds[]='findings';
		$arrValues[]=addslashes($value['findings']);
		
		
		// if(!empty($exam_id))
		// {
			// $arrFileds[]='examination';
			// $arrValues[]=$exam_id;
		// }
		
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
		
		
							
		// $arrFileds[]='patient_id';
		// $arrValues[]=$_GET['patientid'];
		
		// $arrFileds[]='doc_id';
		// $arrValues[]=$admin_id;
		
		$arrFileds[]='doc_type';
		$arrValues[]="1";
		
		$arrFileds[]='status';
		$arrValues[]="1";
			
			$insert_exam_value=mysqlInsert('doc_patient_examination_active',$arrFileds,$arrValues);
		
		
	}
	
}
if(isset($_GET['editexaminationid']) && !empty($_GET['editexaminationid'])){
	
	$params = explode("-", $_GET['editexaminationid']);
	
	if(is_numeric($params[0]) == false){
		$arrFileds_exam[]='examination';
		$arrValues_exam[]=$params[0];
		
		if(!empty($admin_id))
		{
			$arrFileds_exam[]='doc_id';
			$arrValues_exam[]=$admin_id;
		
		}
		// if(!empty($_GET['patientid']))
		// {
			// $arrFileds[]='patient_id';
			// $arrValues[]=$_GET['patientid'];
		
		
		// }
		
		
		$arrFileds_exam[]='doc_type';
		$arrValues_exam[]='1';
		$insert_symptoms=mysqlInsert('examination',$arrFileds_exam,$arrValues_exam);
		$exam_id = $insert_symptoms; //Get Patient Id
		
		if(!empty($admin_id))
		{
			$arrFileds_exam_freq[]='doc_id';
			$arrValues_exam_freq[]=$admin_id;
		}
		if(!empty($exam_id))
		{
			$arrFileds_exam_freq[]='examination_id';
			$arrValues_exam_freq[]=$exam_id;
		}
		
		$arrFileds_exam_freq[]='doc_type';
		$arrValues_exam_freq[]='1';
		$insert_symptoms=mysqlInsert('doctor_frequent_examination',$arrFileds_exam_freq,$arrValues_exam_freq);
		
	} else
	{
		$exam_id = $params[0];
	}
	
		$arrFileds = array();
		$arrValues = array();
		
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
		if(!empty($exam_id))
		{
			$arrFileds[]='examination';
			$arrValues[]=$exam_id;
		}
		
		
		$arrFileds[]='doc_type';
		$arrValues[]="1";
		
		$arrFileds[]='episode_id';
		$arrValues[]=$_GET['episodeid'];
		
		$arrFileds[]='status';
		$arrValues[]="0";
		
		$check_active_exam = mysqlSelect("*","doc_patient_examination_active","examination='".$params[0]."' and doc_id='".$admin_id."' and doc_type='1' and patient_id='".$patient_id."' and episode_id='".$_GET['episodeid']."'","","","","");
		if(COUNT($check_active_exam)==0){ //To prevent double entry
		$insert_symptoms=mysqlInsert('doc_patient_examination_active',$arrFileds,$arrValues);
		}
		
		$check_exam = mysqlSelect("*","doctor_frequent_examination","examination_id='".$params[0]."' and doc_id='".$admin_id."' and doc_type='1'","","","","");
					$freq_count = $check_exam[0]['freq_count']+1; //Count will increment by one
					$arrFieldsINVESTFREQ = array();
					$arrValuesINVESTFREQ = array();
					if(count($check_exam)>0){
						$arrFieldsINVESTFREQ[] = 'freq_count';
						$arrValuesINVESTFREQ[] = $freq_count;
						$update_icd=mysqlUpdate('doctor_frequent_examination',$arrFieldsINVESTFREQ,$arrValuesINVESTFREQ,"dfe_id = '".$check_exam[0]['dfe_id']."'");
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
				$arrValues_exam[]=addslashes($_GET['examfindings']);
	}
	
		
	$update_exam=mysqlUpdate('doc_patient_examination_active',$arrFileds_exam,$arrValues_exam,"examination_id = '".$_GET['updateexamid']."'");

}

if(isset($_GET['delexamtemp']))
{
	//Delete perticular symptoms from table 'doc_patient_examination_active'
	mysqlDelete('doc_patient_episode_examination_templates',"md5(exam_template_id)='".$_GET['delexamtemp']."'");
	mysqlDelete('doc_patient_episode_examination_template_details',"md5(exam_template_id)='".$_GET['delexamtemp']."'");
}

if(isset($_GET['delallExam']))
{
	//Delete perticular symptoms from table 'doc_patient_examination_active'
	mysqlDelete('doc_patient_examination_active',"doc_id='".$admin_id."' and doc_type='1' and status='1'");
}

if(isset($_GET['delallEditExam']))
{
	//Delete all examination  from table 'doc_patient_examination_active'
	mysqlDelete('doc_patient_examination_active',"md5(episode_id)='".$_GET['delallEditExam']."'");
}

if(isset($_GET['deleaxaminationid']) || isset($_GET['deleditexaminationid']))
{
	//Delete perticular symptoms from table 'doc_patient_symptoms_active'
	mysqlDelete('doc_patient_examination_active',"examination_id='".$_GET['deleaxaminationid']."' or examination_id='".$_GET['deleditexaminationid']."'");
}

if(isset($_GET['gettemplateval']))
{
	$exam_templates = mysqlSelect("*","doc_patient_episode_examination_templates","doc_id='".$admin_id."' and doc_type='1'","exam_template_id desc","","","10");
	if($_GET['gettempEdit']==1)
	{
		while(list($key_examtemp, $value_examtemp) = each($exam_templates)){
		
		echo "<span class='tag label label-primary m-l' >".$value_examtemp['template_name']."<a data-role='remove' class='text-white m-l del_exam_template' data-exam-template-id='".md5($value_examtemp['exam_template_id'])."'>x</a></span>";
		 }
	}
	else
	{
		
		while(list($key_examtemp, $value_examtemp) = each($exam_templates)){
		
		echo "<a class='btn btn-xs btn-white m-l exam_load_template' title='".$value_examtemp['template_name']."' data-exam-template-id='".md5($value_examtemp['exam_template_id'])."' data-edit-status='0' data-patient-id='".$_GET['patientid']."' ><code> ".substr($value_examtemp['template_name'],0,10)."</code></a>";
		 }
	}
}


if((isset($_GET['examinationid']) && !empty($_GET['examinationid'])) || (isset($_GET['loadtemplate']) && !empty($_GET['loadtemplate'])))
{
$getExamination= mysqlSelect("b.examination as examination,a.examination_id as examination_id,a.exam_result as exam_result,a.findings as findings","doc_patient_examination_active as a left join examination as b on a.examination=b.examination_id","a.doc_id='".$admin_id."' and a.patient_id='".$_GET['patientid']."' and a.doc_type='1' and a.status='1'","a.examination_id asc","","","");

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
										</thead>
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
									<td><input type="text" class="form-control findings" name="finding" data-examination-id="<?php echo $getExaminationList['examination_id']; ?>"  placeholder="Finding" value="<?php echo $getExaminationList['findings']; ?>" style="width:650px;"></td>
									<td><a class="del_examination" data-examination-id="<?php echo $getExaminationList['examination_id'];?>"><span class="label label-danger">Delete</span></a></td>
									</tr>
									<?php }
									?>
                                   </tbody>
					</table>
				
					
<?php  
}
if(isset($_GET['editexaminationid']) && !empty($_GET['editexaminationid']))
{
$getExamination= mysqlSelect("a.examination_id as examination_id,b.examination as examination,a.exam_result as exam_result,a.findings as findings","doc_patient_examination_active as a left join examination as b on a.examination=b.examination_id","a.episode_id='".$_GET['episodeid']."'","a.examination_id asc","","","");
								
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
									<td><input type="text" class="form-control findings" name="finding" data-examination-id="<?php echo $getExaminationList['examination_id']; ?>"  placeholder="Finding" value="<?php echo $getExaminationList['findings']; ?>" style="width:650px;"></td>
									<td><a class="del_editexamination" data-examination-id="<?php echo $getExaminationList['examination_id'];?>"><span class="label label-danger">Delete</span></a></td>
									</tr>
									<?php }
									?>
                                   </tbody>
					</table>	
					
					
	
<?php } ?>				