<?php
ob_start();
error_reporting(0); 
session_start();

$admin_id = $_SESSION['user_id'];
$Patient_id=$_GET['p'];
include('../send_text_message.php');
include('../send_mail_function.php');
require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

$GetPatient = $objQuery->mysqlSelect("a.patient_id as patient_id,a.patient_name as patient_name,a.patient_age as patient_age,a.patient_gen as patient_gen,a.merital_status as merital_status,a.weight as weight,a.hyper_cond as hyper_cond,a.diabetes_cond as diabetes_cond,a.qualification as qualification,a.contact_person as contact_person,a.patient_addrs as patient_addrs,a.patient_loc as patient_loc,a.pat_state as pat_state,a.patient_mob as patient_mob,a.patient_email as patient_email,a.patient_complaint as patient_complaint,a.patient_desc as patient_desc,a.pat_query as pat_query,a.TImestamp as TImestamp,b.status1 as status1,b.status2,b.bucket_status as bucket_status","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","md5(a.patient_id)='".$Patient_id."'","","","","");
$attachDet = $objQuery->mysqlSelect("*","patient_attachment","md5(patient_id)='".$Patient_id."'","","","","");
$attachCount = $objQuery->mysqlSelect("COUNT(attach_id) as Num_Attach","patient_attachment","md5(patient_id)='".$Patient_id."'","","","","");
$getDept = $objQuery->mysqlSelect("*","specialization","spec_id='".$GetPatient[0]['medDept']."'" ,"","","","");

				if($GetPatient[0]['patient_gen']=="1"){
					$gender="Male";
				}
				else if($GetPatient[0]['patient_gen']=="2"){
					$gender="Female";
				}
				
				if($GetPatient[0]['hyper_cond']=="2"){
					$hyperStatus="No";
				}
				else if($GetPatient[0]['hyper_cond']=="1"){
					$hyperStatus="Yes";
				}
				if($GetPatient[0]['diabetes_cond']=="2"){
					$diabetesStatus="No";
				}
				else if($GetPatient[0]['diabetes_cond']=="1"){
					$diabetesStatus="Yes";
				}

//Update patient status
if(isset($_POST['cmdchangeStatus'])){
	$arrFields = array();
	$arrValues = array();
	
	$arrFields[]= 'status2';
	$arrValues[]= $_POST['slct_val'];
	//Update Patient Status
	$patientRef=$objQuery->mysqlUpdate('patient_referal',$arrFields,$arrValues,"patient_id='".$_POST['patient_id']."'and ref_id='".$_POST['doc_id']."'");
	//GET Patient Details
	$getPatient = $objQuery->mysqlSelect("a.patient_name as PatName,a.patient_loc as Pat_loc,a.patient_id as Pat_Id,b.status2 as Current_Status","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","a.patient_id='".$_POST['patient_id']."'and b.ref_id='".$_POST['doc_id']."'","","","","");
	
	//GET Partner Details
	$getPartner = $objQuery->mysqlSelect("a.partner_name as Partner_Name,a.Email_id as Partner_Email,a.cont_num1 as Partner_Mobile","our_partners as a left join source_list as b on a.partner_id=b.partner_id","b.source_id='".$getPatient[0]['patient_src']."'","","","","");
	
	//GET Hospital Datails
	$getHospital = $objQuery->mysqlSelect("a.hosp_name as Hosp_Name,a.hosp_email as Hosp_Email","hosp_tab as a left join doctor_hosp as b on a.hosp_id=b.hosp_id","b.doc_id='".$_POST['doc_id']."'","","","","");
	if($getPatient[0]['Current_Status']==13){
		$Current_Status="OP-Visited";
	}
	else if($getPatient[0]['Current_Status']==9){
		$Current_Status="IP-Treated";
	}
					//Mail Notification to Referred Parties
					$url_page = 'status_notification_partner.php';					
					$url = rawurlencode($url_page);
					$url .= "?patname=".urlencode($getPatient[0]['PatName']);
					$url .= "&patplace=" . urlencode($getPatient[0]['Pat_loc']);
					$url .= "&patid=" . urlencode($getPatient[0]['Pat_Id']);
					$url .= "&currentstatus=".urlencode($Current_Status);
					$url .= "&partnername=".urlencode($getPartner[0]['Partner_Name']);
					$url .= "&partnermail=".urlencode($getPartner[0]['Partner_Email']);
					$url .= "&hospname=".urlencode($getHospital[0]['Hosp_Name']);
					$url .= "&hospmail=".urlencode($getHospital[0]['Hosp_Email']);
					send_mail($url);
					
					
					//Message Notification to Referred Parties
					$mobile = $getPartner[0]['Partner_Mobile'];
					$responsemsg = "Dear ".$getPartner[0]['Partner_Name'].", Status for patient ".$getPatient[0]['PatName']." changed to ".$Current_Status." Thanks, ".$getHospital[0]['Hosp_Name'];
					send_msg($mobile,$responsemsg);
	
	header('location:patient-history?p='.$Patient_id.'&c='.$admin_id);		
}

if(isset($_POST['cmdGetId'])){
	$bus_id = $_POST['user_id'];
	$_SESSION['trans_id']=$_POST['user_id'];
	header('location:edit-user');	
}

					
$get_pro = $objQuery->mysqlSelect("ref_id as RefId","referal","doc_spec!=555 and anonymous_status!=1","Tot_responded desc","","","");

?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Patient History</title>

    <?php include_once('support.php'); ?>
	<link href="../assets/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
	<link href="../assets/css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">	
	<link href="../assets/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">
	
</head>

<body>

    <div id="wrapper">

    <?php include_once('sidemenu.php'); ?>

        <div id="page-wrapper" class="gray-bg">
        <?php include_once('header_top.php'); ?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-sm-10">
                    <h2>Patient History</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="Home">Home</a>
                        </li>
                        <li class="active">
                            <strong>Patient History</strong>
                        </li>
                    </ol>
                </div>
				<div class="col-lg-2 mgTop">
					<a href="Cases-Sent"> <button type="button" class="btn btn-w-m btn-success"><i class="fa fa-arrow-left"></i> BACK</button></a>
                                
			   </div>
            </div>
		<div class="wrapper wrapper-content animated fadeInUp">	
        <div class="row">
            <div class="col-lg-8">
                  <div class="ibox">
                        <div class="ibox-content">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="m-b-md">
                                        <!--<a href="#" class="btn btn-white btn-xs pull-right">Edit Patient</a>-->
                                        <h2><?php echo $GetPatient[0]['patient_name']; ?>( #<?php echo $GetPatient[0]['patient_id']; ?> )</h2>
										<a href="#" class="btn btn-success btn-xs" data-toggle="modal" data-target="#myModal">Edit Patient</a>
										
										<div class="modal inmodal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog">
                                <div class="modal-content animated bounceInRight">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            <img src="../assets/img/user_noimg.png" width="100" class="img-thumbnail" />
                                            <h4 class="modal-title"><?php echo $GetPatient[0]['patient_name']; ?></h4>
                                            <small class="font-bold">Patient Profile</small>
                                        </div>
										<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  action="add_patient_details.php"  name="frmAddPatient" id="frmAddPatient">
									<input type="hidden" name="patient_id" value="<?php echo $GetPatient[0]['patient_id']; ?>">
                                    
                                        <div class="modal-body">
                                            <div class="form-group"><label>Patient Name</label> <input type="text" id="se_pat_name" name="se_pat_name" value="<?php echo $GetPatient[0]['patient_name']; ?>" class="form-control"></div>
                                 
								 <div class="row"><div class="form-group"><label class="col-sm-2 control-label">Age</label>
                                    <div class="col-sm-4"><input type="text" id="se_pat_age" name="se_pat_age" value="<?php echo $GetPatient[0]['patient_age']; ?>" class="form-control">
                                    </div>
									<label class="col-sm-2 control-label">Gender</label>
                                    <div class="col-sm-4">
									<?php if($GetPatient[0]['patient_gen']=="1"){ ?>

										<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio1" value="1" name="se_gender" checked="checked">
                                            <label for="inlineRadio1"> Male </label>
                                        </div>
										<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="2" name="se_gender">
                                            <label for="inlineRadio2"> Female </label>
                                        </div>
									<?php } else if($GetPatient[0]['patient_gen']=="2") { ?>
									<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio1" value="1" name="se_gender">
                                            <label for="inlineRadio1"> Male </label>
                                        </div>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="2" name="se_gender" checked="">
                                            <label for="inlineRadio2"> Female </label>
                                        </div>
									<?php } else { ?>
									<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio1" value="1" name="se_gender">
                                            <label for="inlineRadio1"> Male </label>
                                        </div>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="2" name="se_gender">
                                            <label for="inlineRadio2"> Female </label>
                                        </div>
									<?php } ?>
                                    </div>
                                </div>
								</div><br>
								<div class="row">
								 <div class="form-group"><label class="col-sm-3 control-label">Hypertension</label>
                                    <div class="col-sm-3">
									<?php if($GetPatient[0]['hyper_cond']=="1"){ ?>
									<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio1" value="1" name="se_hyper" checked="">
                                            <label for="inlineRadio1"> Yes </label>
                                        </div>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="2" name="se_hyper">
                                            <label for="inlineRadio2"> No </label>
                                        </div>
									<?php } else if($GetPatient[0]['hyper_cond']=="2"){ ?>
									<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio1" value="1" name="se_hyper">
                                            <label for="inlineRadio1"> Yes </label>
                                        </div>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="2" name="se_hyper"  checked="">
                                            <label for="inlineRadio2"> No </label>
                                        </div>
                                    <?php } else { ?>
									<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio1" value="1" name="se_hyper">
                                            <label for="inlineRadio1"> Yes </label>
                                        </div>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="2" name="se_hyper">
                                            <label for="inlineRadio2"> No </label>
                                        </div>
                                    <?php } ?>
									</div>
                                    
									<label class="col-sm-2 control-label">Diabetes</label>
                                    <div class="col-sm-3">
									<?php if($GetPatient[0]['diabetes_cond']=="1"){ ?>
									<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio1" value="1" name="se_diabets" checked="">
                                            <label for="inlineRadio1"> Yes </label>
                                        </div>
										<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="2" name="se_diabets">
                                            <label for="inlineRadio2"> No </label>
                                        </div>
									<?php } else if($GetPatient[0]['diabetes_cond']=="2"){ ?>
									<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio1" value="1" name="se_diabets">
                                            <label for="inlineRadio1"> Yes </label>
                                        </div>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="2" name="se_diabets"  checked="">
                                            <label for="inlineRadio2"> No </label>
                                        </div>
									<?php } else { ?>
									<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio1" value="1" name="se_diabets">
                                            <label for="inlineRadio1"> Yes </label>
                                        </div>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="2" name="se_diabets">
                                            <label for="inlineRadio2"> No </label>
                                        </div>
									<?php } ?>
                                    </div>
                                </div>
								</div>		
								<br>		
										
										<div class="form-group"><label>Weight</label> <input type="text" id="se_weight" name="se_weight" value="<?php echo $GetPatient[0]['weight']; ?>" class="form-control"></div>
										
										<div class="form-group"><label>Specialization</label> <select class="form-control" name="se_depart" name="se_depart">
														<?php $DeptName= $objQuery->mysqlSelect("*","specialization","","spec_name asc","","","");
														$i=30;
														foreach($DeptName as $DeptList){
															if($DeptList['spec_id']==$GetPatient[0]['medDept']){ ?> 
														<option value="<?php echo stripslashes($DeptList['spec_id']);?>" selected/><?php echo stripslashes($DeptList['spec_name']);?></option>
														<?php 
															}?>

															<option value="<?php echo stripslashes($DeptList['spec_id']);?>" /><?php echo stripslashes($DeptList['spec_name']);?></option>
														<?php
																$i++;
														}?>
													</select>
										</div>
										
										<div class="form-group"><label>Country</label> <select class="form-control" name="se_country" name="se_country">
														<option value="India" <?php echo (!isset($GetPatient[0]['pat_country']) ? 'selected' : ($GetPatient[0]['pat_country'] == 'India' ? 'Selected' : '' ) ) ?> selected>India</option>
														<?php
														$getCountry= $objQuery->mysqlSelect("*","countries","","country_name asc","","","");
														$i=30;
														foreach($getCountry as $CountryList){
														?>

														<option value="<?php echo stripslashes($CountryList['country_name']); ?>" <?php echo ($GetPatient[0]['pat_country'] == stripslashes($CountryList['country_name']) ? 'selected' : '') ?> />
														<?php echo stripslashes($CountryList['country_name']);?></option>


														<?php
														$i++;
														}?>
													</select></div>
										<div class="form-group"><label>State</label> <select class="form-control"  name="se_state" id="se_state" placeholder="State"  >
														<option value="">Select State</option>
														<?php
														$GetState = $objQuery->mysqlSelect("*","countries as a left join states as b on a.country_id=b.country_id", "a.country_id=100", "b.state_name asc", "", "", "");
														foreach ($GetState as $StateList) {
														?>
														<option value="<?php echo $StateList["state_name"];	?>" <?php echo ($GetPatient[0]['pat_state'] == $StateList["state_name"] ? 'selected' : '' ) ?> ><?php echo $StateList["state_name"]; ?></option>

														<?php
														}
														?>
													</select></div>
										
										<div class="form-group"><label>City</label> <input type="text" id="se_city" name="se_city" value="<?php echo $GetPatient[0]['patient_loc']; ?>" class="form-control"></div>
										
										<div class="form-group"><label>Address</label> <input type="text" id="se_address" name="se_address" value="<?php echo $GetPatient[0]['patient_addrs']; ?>" class="form-control"></div>
										<div class="form-group"><label>Mobile</label> <input type="text" id="se_phone_no" name="se_phone_no" value="<?php echo $GetPatient[0]['patient_mob']; ?>" class="form-control"></div>
										<div class="form-group"><label>Email</label> <input type="email" id="se_email" name="se_email" value="<?php echo $GetPatient[0]['patient_email']; ?>" class="form-control"></div>
																				
										<div class="form-group"><label>Contact Person</label> <input type="text" id="se_con_per" name="se_con_per" value="<?php echo $GetPatient[0]['contact_person']; ?>" class="form-control"></div>
										
										<div class="form-group"><label>Chief Medical Complaint</label> <textarea id="se_info" name="se_info" class="form-control"><?php echo $GetPatient[0]['patient_complaint']; ?></textarea></div>
										<div class="form-group"><label>Medical Query</label> <textarea id="se_query" name="se_query" class="form-control"><?php echo $GetPatient[0]['pat_query']; ?></textarea></div>
										<div class="form-group"><label>Brief Description</label> <textarea id="se_description" name="se_description" class="form-control"><?php echo $GetPatient[0]['patient_desc']; ?></textarea></div>
										
										</div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                                            <button type="submit" name="updatePatient" class="btn btn-primary">UPDATE</button>
                                        </div>
										</form>
                                    </div>
                                </div>
                            </div>



								   </div>
                                    <dl class="dl-horizontal">
									<?php if($GetPatient[0]['bucket_status']=="2"){ $patient_status="<span class='label label-warning'>SENT</span>"; ?>
										<?php } else if($GetPatient[0]['bucket_status']=="3"){  $patient_status="<span class='label label-danger'>P-AWAITING</span>";?>
										<?php } else if($GetPatient[0]['bucket_status']=="5"){  $patient_status="<span class='label label-success'>RESPONDED</span>";?>
										<?php } else if($GetPatient[0]['bucket_status']=="6"){  $patient_status="<span class='label label-info'>RESPONSE-PATIENT-FAILED</span>";?>
										<?php } else if($GetPatient[0]['bucket_status']=="7"){  $patient_status="<span class='label label-info'>STAGED</span>";?>
										<?php } else if($GetPatient[0]['bucket_status']=="8"){  $patient_status="<span class='label label-warning'>OP-DESIRED</span>";?>
										<?php } else if($GetPatient[0]['bucket_status']=="9"){  $patient_status="<span class='label label-success'>IP-CONVERTED</span>";?>
										<?php } else if($GetPatient[0]['bucket_status']=="10"){  $patient_status="<span class='label label-danger'>NOT-CONVERTED</span>";?>
										<?php } else if($GetPatient[0]['bucket_status']=="11"){  $patient_status="<span class='label label-success'>INVOICED</span>";?>
										<?php } else if($GetPatient[0]['bucket_status']=="12"){  $patient_status="<span class='label label-success'>PAYMENT RECEIVED</span>";?>
										<?php } else if($GetPatient[0]['bucket_status']=="13"){  $patient_status="<span class='label label-success'>OP-CONVERTED</span>";?>
										<?php } else if($GetPatient[0]['bucket_status']=="1"){  $patient_status="<span class='label label-primary'>NEW</span>"; }?>
						
                                        <dt>Status: </dt> <dd><?php echo $patient_status; ?></dd>
                                    </dl>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-5">
                                    <dl class="dl-horizontal">

                                        <dt>Age:</dt> <dd><?php echo $GetPatient[0]['patient_age']; ?></dd>
                                        <dt>Gender:</dt> <dd>  <?php echo $gender; ?></dd>
                                        <dt>Marital Status:</dt> <dd><?php if(empty($GetPatient[0]['merital_status'])){ echo "NS"; } else { echo $GetPatient[0]['merital_status']; } ?></dd>
                                        <dt>Weight:</dt> <dd> 	<?php echo $GetPatient[0]['weight'];  ?></dd>
										<dt>City:</dt> <dd> 	<?php echo $GetPatient[0]['patient_loc'];  ?></dd>
										<dt>State:</dt> <dd> 	<?php echo $GetPatient[0]['pat_state'];  ?></dd>
										<dt>Address:</dt> <dd> 	<?php echo $GetPatient[0]['patient_addrs'];  ?></dd>
                                    </dl>
                                </div>
                                <div class="col-lg-3" id="cluster_info">
                                    <dl class="dl-horizontal" >

                                        <dt>Hypertension ?:</dt> <dd><?php echo $hyperStatus;  ?></dd>
                                        <dt>Diabetes ?:</dt> <dd> <?php echo $diabetesStatus;  ?></dd>
                                        <dt>Qualification:</dt> <dd> <?php if(empty($GetPatient[0]['qualification'])){ echo "NS"; } else { echo $GetPatient[0]['qualification']; } ?></dd>
										<dt>Contact Person:</dt> <dd> 	<?php echo $GetPatient[0]['contact_person'];  ?></dd>
										<dt>Mobile:</dt> <dd> 	<?php echo $GetPatient[0]['patient_mob'];  ?></dd>
										<dt>Email Id:</dt> <dd> 	<?php echo $GetPatient[0]['patient_email'];  ?></dd>
										
								   </dl>
                                </div>
                            </div>
                            
                            <div class="row m-t-sm">
                                <div class="col-lg-12">
                                <div class="panel blank-panel">
                                <div class="panel-heading">
                                    <div class="panel-options">
                                        <ul class="nav nav-tabs">
                                            <li class="active"><a href="#tab-1" data-toggle="tab">Patient Query</a></li>
                                            <li class=""><a href="#tab-2" data-toggle="tab">Medical Reports</a></li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="panel-body">

                                <div class="tab-content">
                                <div class="tab-pane active" id="tab-1">
                                   <p><b>Patient Complaint</b><br><?php echo $GetPatient[0]['patient_complaint'];  ?></p>
									<p><b>Description</b><br><?php echo $GetPatient[0]['patient_desc'];  ?></p>
									 <p><b>Medical Query</b><br><?php echo $GetPatient[0]['pat_query'];  ?></p>

                                </div>
                                <div class="tab-pane" id="tab-2">

                                    <p>
                              <span><i class="fa fa-paperclip"></i> <?php echo $attachCount[0]['Num_Attach']; ?> attachments </span>
                              <!--<a href="#">Download all attachments</a> |
                              <a href="#">View all images</a>-->
                            </p>
                            <ul>
							<?php foreach($attachDet as $attachList){ 
							//Here we need to check file type
							$img_type =  array('gif','png' ,'jpg' ,'jpeg');
							$extractPath = pathinfo($attachList['attachments'], PATHINFO_EXTENSION);
							if(in_array($extractPath,$img_type) ) {
								$imgIcon="../Attach/".$attachList['attach_id']."/".$attachList['attachments'];
							}
							else if($extractPath=="docx"){
									$imgIcon="../assets/images/doc.png";
							}
							else if($extractPath=="pdf" || $extractPath=="PDF"){
								$imgIcon="../assets/images/pdf.png";
							} ?>
                              <div class="file-box">
                                <div class="file">
                                    <a href="#">
                                        <span class="corner"></span>
										<a href="../Attach/<?php echo stripslashes($attachList['attach_id']);?>/<?php echo stripslashes($attachList['attachments']);?>" target="_blank"  title="<?php echo stripslashes($attachList['attachments']);?>">
                                        <div class="image">
                                            <img alt="image" class="img-responsive" src="<?php echo $imgIcon; ?>">
                                        </div></a>
                                        <div class="file-name">
                                            <?php echo substr($attachList['attachments'],0,10); ?>
                                            <br/>
                                            <small><a href="../Attach/<?php echo stripslashes($attachList['attach_id']);?>/<?php echo stripslashes($attachList['attachments']);?>" target="_blank"  title="<?php echo stripslashes($attachList['attachments']);?>">View</a> -
                                  <a href="https://medisensecrm.com/download-Attachments.php?attach_id=<?php echo stripslashes($attachList['attach_id']);?>&attach_name=<?php echo $attachList['attachments']; ?>" target="_blank">Download</a></small>
                                        </div>
                                    </a>

                                </div>
                            </div>
							<?php } ?>
                              

                            </ul>

                                </div>
                                </div>

                                </div>

                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="col-sm-4">
                <?php $getRefDoc = $objQuery->mysqlSelect("a.patient_id as patient_id,a.status2 as status2,b.doc_photo as doc_photo,b.ref_id as ref_id,b.ref_name as ref_name,c.spec_name as spec_name,a.timestamp as timestamp","patient_referal as a inner join referal as b on a.ref_id=b.ref_id inner join specialization as c on c.spec_id=b.doc_spec","a.patient_id='".$GetPatient[0]['patient_id']."'","a.timestamp desc","","","");
						foreach($getRefDoc as $getRefDocList){ 
                        
										if($getRefDocList['status2']=="2"){ $patient_status="<span class='label label-warning'>SENT</span>"; ?>
										<?php } else if($getRefDocList['status2']=="3"){  $patient_status="<span class='label label-danger'>P-AWAITING</span>";?>
										<?php } else if($getRefDocList['status2']=="5"){  $patient_status="<span class='label label-success'>RESPONDED</span>";?>
										<?php } else if($getRefDocList['status2']=="6"){  $patient_status="<span class='label label-info'>RESPONSE-PATIENT-FAILED</span>";?>
										<?php } else if($getRefDocList['status2']=="7"){  $patient_status="<span class='label label-info'>STAGED</span>";?>
										<?php } else if($getRefDocList['status2']=="8"){  $patient_status="<span class='label label-warning'>OP-DESIRED</span>";?>
										<?php } else if($getRefDocList['status2']=="9"){  $patient_status="<span class='label label-success'>IP-CONVERTED</span>";?>
										<?php } else if($getRefDocList['status2']=="10"){  $patient_status="<span class='label label-danger'>NOT-CONVERTED</span>";?>
										<?php } else if($getRefDocList['status2']=="11"){  $patient_status="<span class='label label-success'>INVOICED</span>";?>
										<?php } else if($getRefDocList['status2']=="12"){  $patient_status="<span class='label label-success'>PAYMENT RECEIVED</span>";?>
										<?php } else if($getRefDocList['status2']=="13"){  $patient_status="<span class='label label-success'>OP-CONVERTED</span>";?>
										<?php } ?>
								
						
				<div class="ibox ">
						<div class="ibox-content">
                            <div class="tab-content">
                                <div id="contact-1">
                                    <div class="row m-b-lg">
                                        <div class="col-lg-4 text-center">
                                            <div class="m-b-sm">
											<?php if(!empty($getRefDocList['doc_photo'])){?>
                                                <img alt="image" class="img-circle" src="../Contributors/docProfilePic/<?php echo $getRefDocList['ref_id']; ?>/<?php echo $getRefDocList['doc_photo']; ?>"
                                                     style="width: 62px">
											<?php } else { ?>
											<img alt="image" class="img-circle" src="../assets/img/anonymous-profile.png" style="width: 62px">
                                            <?php } ?>
											</div>
                                        </div>
                                        <div class="col-lg-8">
                                            <strong>
                                                <?php echo $getRefDocList['ref_name']; ?>
                                            </strong>

                                            <p>
                                                <?php echo $getRefDocList['spec_name'];  ?>
                                            </p>
                                          Status: <?php echo $patient_status; ?>
										  <br><br><p><a href="#" data-toggle="modal" data-target="#myModal<?php echo $getRefDocList['ref_id']; ?>"><span class='label label-success'>Request Appointment</span></a></p>
										  <div class="modal inmodal" id="myModal<?php echo $getRefDocList['ref_id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog">
                                <div class="modal-content animated bounceInRight">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            <?php if(!empty($getRefDocList['doc_photo'])){?>
                                                <img alt="image" class="img-circle" src="../Contributors/docProfilePic/<?php echo $getRefDocList['ref_id']; ?>/<?php echo $getRefDocList['doc_photo']; ?>"
                                                     style="width: 62px">
											<?php } else { ?>
											<img alt="image" class="img-circle" src="../assets/img/anonymous-profile.png" style="width: 62px">
                                            <?php } ?>
                                            <h4 class="modal-title"><?php echo $getRefDocList['ref_name']; ?></h4>
                                            <small class="font-bold">Appointment Request</small>
                                        </div>
										<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  action="get_action.php"  name="frmAddPatient" id="frmAddPatient">
									<input type="hidden" name="Pat_Id" value="<?php echo $GetPatient[0]['patient_id']; ?>">
                                    <input type="hidden" name="docId" value="<?php echo $getRefDocList['ref_id']; ?>">
                                        <div class="modal-body">
                                             <div class="form-group" id="data_1">
                                <label class="font-normal">Simple data input format</label>
                                <div class="input-group date">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" name="dateadded" id="dateadded" class="form-control" value="03/04/2014">
                                </div>
                            </div>
											<div class="form-group">
												<label>Preferred Time</label>
												<div class="input-group date">
												<span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
												<select class="left form-control autotab" name="selectTime" id="selectTime" required="required" >
												<option value="0">Timing</option>
														<?php
															$Timing= $objQuery->mysqlSelect("*","timings","","","","","");
															foreach($Timing as $TimeList) {
																
														?>
															<option value="<?php echo $TimeList["Timing_id"]; ?>"><?php echo $TimeList["Timing"]; ?></option>
														<?php
															}
													
														?>	
														</select>
														</div>
												</div>
								 
										</div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                                            <button type="submit" name="bookAppointment" class="btn btn-primary">SEND</button>
                                        </div>
										</form>
                                    </div>
                                </div>
                            </div>
                                        </div>
									
                                    </div>
									
									
									
                                    <div class="client-detail">
									
                                    <div class="full-height-scroll">
										
                                        <hr/>
                                        <strong>Activity</strong>
										
										
										<?php $getchatHistory = $objQuery->mysqlSelect("b.ref_id as ref_id,b.ref_name as ref_name,a.chat_note as chat_note,b.doc_photo as doc_photo,a.TImestamp as Ref_Date","chat_notification as a inner join referal as b on a.ref_id=b.ref_id","a.patient_id='".$getRefDocList['patient_id']."'and a.ref_id='".$getRefDocList['ref_id']."'","a.chat_id desc","","",""); 
										
										foreach($getchatHistory as $chatList){
										?>
						
                                        <div id="vertical-timeline" class="vertical-container dark-timeline">
                                            <div class="vertical-timeline-block">
                                                <div class="vertical-timeline-icon gray-bg">
                                                    <i class="fa fa-comments"></i>
                                                </div>
                                                <div class="vertical-timeline-content">
                                                    <p><?php echo $chatList['chat_note']; ?>
                                                    </p>
                                                    <span class="vertical-date small text-muted"> <?php echo date('d-M-Y H:i:s',strtotime($chatList['Ref_Date'])); ?></span>
                                                </div>
                                            </div>
                                        </div>
									<?php } ?>
									
									
									
									
                                    </div>
									
                                    </div>
									

									<div class="row m-b-lg" style="padding-top:20px;">
									<div class="col-lg-12 text-center">
                                         <form class="form-inline" method="post" role="form" action="add_details.php">
										 <input type="hidden" name="patient_id" value="<?php echo $GetPatient[0]['patient_id']; ?>" />
										 <input type="hidden" name="doc_id" value="<?php echo $getRefDocList['ref_id']; ?>">
											<div class="form-group">
												<input class="form-control" type="text" name="txtDesc" required="required" placeholder="Your comments" />
											</div>
											<div class="form-group">
												<button type="submit" name="addActivity" class="btn btn-default">Add</button>
											</div>
										</form>
									</div>
                                    </div>									
									
                                </div>
                               </div>
                        </div>
					 </div>
				 <?php } ?>
					
					
        </div>
		</div>
		</div>
        <?php include_once('footer.php'); ?>

        </div>
        </div>

    <!-- Mainly scripts -->
    <script src="../assets/js/jquery-3.1.1.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="../assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="../assets/js/inspinia.js"></script>
    <script src="../assets/js/plugins/pace/pace.min.js"></script>

    <script>
        $(document).ready(function(){

            $('#loading-example-btn').click(function () {
                btn = $(this);
                simpleLoad(btn, true)

                // Ajax example
//                $.ajax().always(function () {
//                    simpleLoad($(this), false)
//                });

                simpleLoad(btn, false)
            });
        });

        function simpleLoad(btn, state) {
            if (state) {
                btn.children().addClass('fa-spin');
                btn.contents().last().replaceWith(" Loading");
            } else {
                setTimeout(function () {
                    btn.children().removeClass('fa-spin');
                    btn.contents().last().replaceWith(" Refresh");
                }, 2000);
            }
        }
    </script>
	<!-- FooTable -->
    <script src="../assets/js/plugins/footable/footable.all.min.js"></script>
<!-- Data picker -->
    <script src="../assets/js/plugins/datapicker/bootstrap-datepicker.js"></script>
   <!-- Page-Level Scripts -->
    <script>
        $(document).ready(function() {

             $('#data_1 .input-group.date').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true
            });

        });

    </script>
	<script>
        $(document).ready(function(){

            $('#loading-example-btn').click(function () {
                btn = $(this);
                simpleLoad(btn, true)

                // Ajax example
//                $.ajax().always(function () {
//                    simpleLoad($(this), false)
//                });

                simpleLoad(btn, false)
            });
        });

        function simpleLoad(btn, state) {
            if (state) {
                btn.children().addClass('fa-spin');
                btn.contents().last().replaceWith(" Loading");
            } else {
                setTimeout(function () {
                    btn.children().removeClass('fa-spin');
                    btn.contents().last().replaceWith(" Refresh");
                }, 2000);
            }
        }
    </script>
	 <script>
        $(document).ready(function(){
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });
        });
    </script>
<!-- Chosen -->
    <script src="../assets/js/plugins/chosen/chosen.jquery.js"></script>
	<script>
       
        $('.chosen-select').chosen({width: "100%"});



    </script>
	<!-- Switchery -->
   <script src="../assets/js/plugins/switchery/switchery.js"></script>
	
</body>

</html>
