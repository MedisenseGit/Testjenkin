<script src="js/symptoms.js"></script>
<?php
ob_start();
error_reporting(0); 
session_start();


$admin_id = $_SESSION['user_id'];
if(empty($admin_id)){
	header("Location:index.php");
}
require_once("../../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();


$lidsid=$_GET['lidsid'];
$patientid=$_GET['patientid'];

if(isset($_GET['lidsid']) && !empty($_GET['lidsid'])){
	
	$params     = split("-", $_GET['lidsid']);
	
	if(is_numeric($params[0]) == false){
		$arrFileds_symp = array();
		$arrValues_symp = array();
		
		$arrFileds_symp[]='lids_name';
		$arrValues_symp[]=$params[0];
		$arrFileds_symp[]='eye_type';
		$arrValues_symp[]='1';
		$arrFileds_symp[]='doc_id';
		$arrValues_symp[]=$admin_id;
		$arrFileds_symp[]='doc_type';
		$arrValues_symp[]='1';
		
		$arrFileds_symp_le = array();
		$arrValues_symp_le = array();
		
		$arrFileds_symp_le[]='lids_name';
		$arrValues_symp_le[]=$params[0];
		$arrFileds_symp_le[]='eye_type';
		$arrValues_symp_le[]='2';
		$arrFileds_symp_le[]='doc_id';
		$arrValues_symp_le[]=$admin_id;
		$arrFileds_symp_le[]='doc_type';
		$arrValues_symp_le[]='1';
		
		$check_exists_lids = mysqlSelect("*","examination_ophthal_lids","lids_name='".$params[0]."' and doc_id='".$admin_id."' and doc_type='1' ","","","","");
		if(COUNT($check_exists_lids)==0){ //To prevent double entry
			$insert_lids=mysqlInsert('examination_ophthal_lids',$arrFileds_symp,$arrValues_symp);
			$lidsid = $insert_lids;
			
			$insert_lids_left=mysqlInsert('examination_ophthal_lids',$arrFileds_symp_le,$arrValues_symp_le);
		}
		
		//$insert_symptoms=mysqlInsert('examination_ophthal_lids',$arrFileds_symp,$arrValues_symp);
		//$symp_id = mysqli_insert_id(); //Get Patient Id
	} else
	{
		$lidsid = $params[0];
	}
	
		$arrFileds = array();
		$arrValues = array();
		
		$arrFileds[]='lids';
		$arrValues[]=$lidsid;
							
		$arrFileds[]='patient_id';
		$arrValues[]=$patientid;
		
		$arrFileds[]='doc_id';
		$arrValues[]=$admin_id;
		
		$arrFileds[]='doc_type';
		$arrValues[]="1";
		
		$arrFileds[]='status';
		$arrValues[]="1";
		
		$arrFileds[]='eye_type';
		$arrValues[]="1";
		
		$check_active_lids = mysqlSelect("*","doc_patient_lids_active","lids='".$params[0]."' and doc_id='".$admin_id."' and doc_type='1' and eye_type='1' and patient_id='".$_GET['patientid']."' and status='1'","","","","");
		if(COUNT($check_active_lids)==0){ //To prevent double entry
		$insert_lids=mysqlInsert('doc_patient_lids_active',$arrFileds,$arrValues);
		}
		else {
			mysqlDelete('doc_patient_lids_active',"lids='".$params[0]."' and doc_id='".$admin_id."' and doc_type='1' and eye_type='1' and patient_id='".$_GET['patientid']."' and status='1'");
		}	
		
}

if(isset($_GET['dellidsid']))
{
	//Delete perticular symptoms from table 'doc_patient_lids_active'
	mysqlDelete('doc_patient_lids_active',"lids_id='".$_GET['dellidsid']."'");
}

$get_all_lids = mysqlSelect("*","examination_ophthal_lids","(doc_id='".$admin_id."' and doc_type='1' and eye_type='1') or (doc_id='0' and doc_type='0' and eye_type='1') ","lids_name ASC","","","");			
$get_all_lidsLE = mysqlSelect("*","examination_ophthal_lids","(doc_id='".$admin_id."' and doc_type='1' and eye_type='2') or (doc_id='0' and doc_type='0' and eye_type='2') ","lids_name ASC","","","");			
								
?>
						<form method="post" action="my_patient_profile_save.php"  name="frmAddSymp" >
					
						
						<td> 
									<div class="col-lg-12" style="background-color:#f8f8f8; border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
										<div class="input-group">
										<?php 
											while(list($key, $value) = each($get_all_lids)){ 
												$getSelectedLidsRE= mysqlSelect("b.lids_name as lids_name,a.lids as lids,b.lids_id as lids_id","doc_patient_lids_active as a left join examination_ophthal_lids as b on a.lids=b.lids_id","a.doc_id='".$admin_id."' and a.patient_id='".$patientid."' and a.doc_type='1' and a.eye_type='1' and a.status='1' and a.lids='".$value['lids_id']."'","","","","");
												if(COUNT($getSelectedLidsRE)>0 ) {
													echo "<input type='checkbox' style='width: 20px;height: 20px; vertical-align: bottom;margin-right:5px;' class='i-checks m-l get_lids_prior_New' name='" . $value['lids_id'] . "' value='" . $value['lids_id'] . "' data-lidsNew-id='" . $value['lids_id'] . "' data-patient-id='".$patientid."' checked / >" . $value['lids_name'] . "</label>&nbsp;&nbsp;&nbsp;&nbsp";
												}
												else {
													echo "<input type='checkbox' style='width: 20px;height: 20px; vertical-align: bottom;margin-right:5px;' class='i-checks m-l get_lids_prior_New' name='" . $value['lids_id'] . "' value='" . $value['lids_id'] . "' data-lidsNew-id='" . $value['lids_id'] . "' data-patient-id='".$patientid."'  / >" . $value['lids_name'] . "</label>&nbsp;&nbsp;&nbsp;&nbsp";
												} 
											}
										?>
										</div>
										<div class="ibox">
										<div class="ibox-tools">
											<a class="collapse-link">
											<i class="fa fa-plus"></i> ADD
											</a>
										</div>
										<div class="ibox-content" style="display: none;">
											<div class="input-group">
												<input type="text" placeholder="Add lids here..." data-patient-id="<?php echo $patient_id; ?>" id="get_lids_new" name="srchLidsNew" value="" class="form-control input-lg searchLidsNew" tabindex="1">
												<div class="input-group-btn">
												<button class="btn btn-lg btn-primary"  name="" type="button">
													ADD
												</button>
												</div>
											</div>
										</div>
										</div>
										</div>
									</td>
									<td>
									<div class="col-lg-12" style="background-color:#f8f8f8; border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
										<div class="input-group">
											<?php 
												while(list($key, $value) = each($get_all_lidsLE)){ 
													$getSelectedLidsLE= mysqlSelect("b.lids_name as lids_name,a.lids as lids,b.lids_id as lids_id","doc_patient_lids_active as a left join examination_ophthal_lids as b on a.lids=b.lids_id","a.doc_id='".$admin_id."' and a.patient_id='".$patientid."' and a.doc_type='1' and a.eye_type='2' and a.status='1' and a.lids='".$value['lids_id']."'","","","","");
													if(COUNT($getSelectedLidsLE)>0 ) {
														echo "<input type='checkbox' style='width: 20px;height: 20px; vertical-align: bottom;margin-right:5px;' class='i-checks m-l get_lids_prior_NewLE' name='" . $value['lids_id'] . "' value='" . $value['lids_id'] . "' data-lidsNewLE-id='" . $value['lids_id'] . "' data-patient-id='".$patientid."' checked / >" . $value['lids_name'] . "</label>&nbsp;&nbsp;&nbsp;&nbsp";
													}
													else {
														echo "<input type='checkbox' style='width: 20px;height: 20px; vertical-align: bottom;margin-right:5px;' class='i-checks m-l get_lids_prior_NewLE' name='" . $value['lids_id'] . "' value='" . $value['lids_id'] . "' data-lidsNewLE-id='" . $value['lids_id'] . "' data-patient-id='".$patientid."'  / >" . $value['lids_name'] . "</label>&nbsp;&nbsp;&nbsp;&nbsp";
													}
												}
											?>
										</div>
										<div class="ibox">
										<div class="ibox-tools">
											<a class="collapse-link">
											<i class="fa fa-plus"></i> ADD
											</a>
										</div>
										<div class="ibox-content" style="display: none;">
											<div class="input-group">
												<input type="text" placeholder="Add lids here..." data-patient-id="<?php echo $patient_id; ?>" id="get_lids_newLE" name="srchLidsNewLE" value="" class="form-control input-lg searchLidsNewLE" tabindex="1">
												<div class="input-group-btn">
												<button class="btn btn-lg btn-primary"  name="" type="button">
													ADD
												</button>
												</div>
											</div>
										</div>
										</div>
										</div>
									</td>
								
								
						</form>		

