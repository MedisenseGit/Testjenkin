<?php
ob_start();
error_reporting(0); 
session_start();

$admin_id = $_SESSION['user_id'];
$Patient_id=$_GET['p'];

include('../send_text_message.php');
include('../send_mail_function.php');
require_once("../classes/querymaker.class.php");


//echo $Patient_id;
$GetPatient = mysqlSelect("a.patient_id as patient_id,a.patient_name as patient_name,a.patient_age as patient_age,a.patient_gen as patient_gen,a.merital_status as merital_status,a.weight as weight,a.hyper_cond as hyper_cond,a.diabetes_cond as diabetes_cond,a.qualification as qualification,a.contact_person as contact_person,a.patient_addrs as patient_addrs,a.patient_loc as patient_loc,a.pat_state as pat_state,a.patient_mob as patient_mob,a.patient_email as patient_email,a.patient_complaint as patient_complaint,a.patient_desc as patient_desc,a.pat_query as pat_query,a.TImestamp as TImestamp,a.transaction_status as Trans_Status,b.status1 as status1,b.status2,b.bucket_status as bucket_status","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","md5(a.patient_id)='".$Patient_id."'","","","","");
//echo"result";
//var_dump ($GetPatient);
//exit();
$attachDet = mysqlSelect("*","patient_attachment","md5(patient_id)='".$Patient_id."'","","","","");
$attachCount = mysqlSelect("COUNT(attach_id) as Num_Attach","patient_attachment","md5(patient_id)='".$Patient_id."'","","","","");
$getDept = mysqlSelect("*","specialization","spec_id='".$GetPatient[0]['medDept']."'" ,"","","","");

				if($GetPatient[0]['patient_gen']=="1"){
					$gender="Male";
				}
				else if($GetPatient[0]['patient_gen']=="2"){
					$gender="Female";
				}
				
				if($GetPatient[0]['hyper_cond']=="1"){
					$hyperStatus="No";
				}
				else if($GetPatient[0]['hyper_cond']=="2"){
					$hyperStatus="Yes";
				}
				if($GetPatient[0]['diabetes_cond']=="1"){
					$diabetesStatus="No";
				}
				else if($GetPatient[0]['diabetes_cond']=="2"){
					$diabetesStatus="Yes";
				}

//Update patient status
if(isset($_POST['cmdchangeStatus'])){
	$arrFields = array();
	$arrValues = array();
	
	$arrFields[]= 'status2';
	$arrValues[]= $_POST['slct_val'];
	//Update Patient Status
	$patientRef=mysqlUpdate('patient_referal',$arrFields,$arrValues,"patient_id='".$_POST['patient_id']."'and ref_id='".$_POST['doc_id']."'");
	//GET Patient Details
	$getPatient = mysqlSelect("a.patient_name as PatName,a.patient_loc as Pat_loc,a.patient_id as Pat_Id,b.status2 as Current_Status,a.patient_src as Patient_Src","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","a.patient_id='".$_POST['patient_id']."'and b.ref_id='".$_POST['doc_id']."'","","","","");
	
	//GET Partner Details
	$getPartner = mysqlSelect("a.partner_name as Partner_Name,a.Email_id as Partner_Email,a.cont_num1 as Partner_Mobile","our_partners as a left join source_list as b on a.partner_id=b.partner_id","b.source_id='".$getPatient[0]['Patient_Src']."'","","","","");
	
	//GET Hospital Datails
	$getHospital = mysqlSelect("a.hosp_name as Hosp_Name,a.hosp_email as Hosp_Email","hosp_tab as a left join doctor_hosp as b on a.hosp_id=b.hosp_id","b.doc_id='".$_POST['doc_id']."'","","","","");
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

$get_pro = mysqlSelect("a.ref_id as RefId","referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id","","a.Tot_responded desc","","","");


?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Patient History</title>

    <?php include_once('support.php'); ?>
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
					<a href="Cases-Recieved"> <button type="button" class="btn btn-w-m btn-success"><i class="fa fa-arrow-left"></i> BACK</button></a>
                                
			   </div>
            </div>
		<div class="wrapper wrapper-content animated fadeInUp">	
		<?php if($_GET['response']=="Appointment-Success"){ ?>
			<div class="alert alert-success alert-dismissable">
				<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
				<strong>Appointment link has been sent successfully </strong>
			</div>
		<?php } ?>
        <div class="row">
            <div class="col-lg-8">
                  <div class="ibox">
                        <div class="ibox-content">
                            <div class="row">
                                <div class="col-lg-12">
								<script language="JavaScript" src="js/status_validationJs.js"></script>
					
					<form method="post" name="frmApp1" action="add_details.php">	
					<input type="hidden" name="ref_id" value="" />
					<input type="hidden" name="cmdAppt" value="" />
					<input type="hidden" name="patient_id" value="" />
                       
					<a class="btn btn-primary pull-right" href='#' onclick="return sendAptLink(<?php echo $GetPatient[0]['patient_id'];?>,<?php echo $admin_id;?>)"><i class="fa fa-calendar"></i> Send Appt Req</a>
					</form>
					<!--<br><br>
					<form method="post" name="frmPayment">	
					<input type="hidden" name="ref_id" value="" />
					<input type="hidden" name="cmdPay" value="" />
					<input type="hidden" name="patient_id" value="" />
					<a href='#' disabled ><span class="btn btn-success pull-right"><i class="fa fa-credit-card"></i> SEND PAYMENT LINK</span></a>
					</form>-->
					 
                                    <div class="m-b-md">
                                        <!--<a href="#" class="btn btn-white btn-xs pull-right">Edit Patient</a>-->
                                        <h2><?php echo $GetPatient[0]['patient_name']; ?>( #<?php echo $GetPatient[0]['patient_id']; ?> ) <?php if($GetPatient[0]['Trans_Status']=="TXN_SUCCESS"){ ?><img src="../assets/img/paid_icon.png" width="50" /><?php } ?></h2>
                                   
								   </div>
									
									<dl class="dl-horizontal">
									<?php if($GetPatient[0]['status2']=="2"){ $patient_status="<span class='label label-warning'>PENDING</span>"; ?>
										<?php } else if($GetPatient[0]['status2']=="3"){  $patient_status="<span class='label label-danger'>P-AWAITING</span>";?>
										<?php } else if($GetPatient[0]['status2']=="5"){  $patient_status="<span class='label label-success'>RESPONDED</span>";?>
										<?php } else if($GetPatient[0]['status2']=="6"){  $patient_status="<span class='label label-info'>RESPONSE-PATIENT-FAILED</span>";?>
										<?php } else if($GetPatient[0]['status2']=="7"){  $patient_status="<span class='label label-info'>STAGED</span>";?>
										<?php } else if($GetPatient[0]['status2']=="8"){  $patient_status="<span class='label label-warning'>OP-DESIRED</span>";?>
										<?php } else if($GetPatient[0]['status2']=="9"){  $patient_status="<span class='label label-success'>IP-CONVERTED</span>";?>
										<?php } else if($GetPatient[0]['status2']=="10"){  $patient_status="<span class='label label-danger'>NOT-CONVERTED</span>";?>
										<?php } else if($GetPatient[0]['status2']=="11"){  $patient_status="<span class='label label-success'>INVOICED</span>";?>
										<?php } else if($GetPatient[0]['status2']=="12"){  $patient_status="<span class='label label-success'>PAYMENT RECEIVED</span>";?>
										<?php } else if($GetPatient[0]['status2']=="13"){  $patient_status="<span class='label label-success'>OP-CONVERTED</span>";?>
										<?php } else if($GetPatient[0]['status2']=="1"){  $patient_status="<span class='label label-primary'>NEW</span>"; }?>
						
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
                                       
										<dt>Contact Person:</dt> <dd> 	<?php echo $GetPatient[0]['contact_person'];  ?></dd>
										<!--<dt>Mobile:</dt> <dd> 	<?php echo $GetPatient[0]['patient_mob'];  ?></dd>
										<dt>Email Id:</dt> <dd> 	<?php echo $GetPatient[0]['patient_email'];  ?></dd>-->
										
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

							<div class="mail-attachment">
							<p>
                            <span><i class="fa fa-paperclip"></i> <?php echo $attachCount[0]['Num_Attach']; ?> attachments - </span>
                           
                        </p>

                        <div class="attachment">
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
                                  <a href="<?php echo HOST_MAIN_URL; ?>download-Attachments.php?attach_id=<?php echo stripslashes($attachList['attach_id']);?>&attach_name=<?php echo $attachList['attachments']; ?>" target="_blank">Download</a></small>
                                        </div>
                                    </a>

                                </div>
                            </div>
                            <?php } ?>
							
                            <div class="clearfix"></div>
                        </div>
                        </div>
						
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
			
			<?php $getRefDoc = mysqlSelect("a.timestamp as ReferredOn,a.patient_id as patient_id,a.status2 as status2,b.doc_photo as doc_photo,b.ref_id as ref_id,b.ref_name as ref_name,c.hosp_id as hosp_id","patient_referal as a inner join referal as b on a.ref_id=b.ref_id inner join hosp_tab as c on c.hosp_id=b.ref_id","a.patient_id='".$GetPatient[0]['patient_id']."' and c.hosp_id='".$admin_id."'","","","","");
				var_dump ($getRefDoc);
				exit();
				?>
					<div class="ibox-title"><b>Queries received on:  <?php echo date('d M Y,H:i',strtotime($getRefDoc[0]['ReferredOn'])); ?></b></small>
					</div>
				
						<div class="chat-discussion">
							<?php $getchatHistory = mysqlSelect("b.ref_id as ref_id,b.ref_name as ref_name,b.doc_photo as doc_photo,a.ref_id as ref_id,a.partner_id as partner_id,a.chat_note as chat_note,b.doc_photo as doc_photo,a.TImestamp as Ref_Date","chat_notification as a inner join referal as b on a.ref_id=b.ref_id","a.patient_id='".$GetPatient[0]['patient_id']."'and .ref_id='".$admin_id."'","a.chat_id desc","","",""); 
								//var_dump ($getchatHistory);
								//exit();
								foreach($getchatHistory as $chatList){
									if($chatList['ref_id']!=0 && $chatList['partner_id']!=0)
									{	
										$getPartner = mysqlSelect("partner_id,partner_name,doc_photo","our_partners","partner_id='".$chatList['partner_id']."'","","","",""); 
								
										$tabSide="left";
										$chatUserName=$getPartner[0]['partner_name'];
										$userimg="../standard/partnerProfilePic/".$getPartner[0]['partner_id']."/".$getPartner[0]['doc_photo'];
									} else if($chatList['ref_id']!=0 && $chatList['partner_id']==0)
									{
										$tabSide="right";
										$chatUserName=$chatList['ref_name'];
										$userimg="../Doc/".$chatList['ref_id']."/".$chatList['doc_photo'];
									}
								?>
							<div class="chat-message <?php echo $tabSide; ?>">
								<img class="message-avatar" src="<?php echo $userimg; ?>" alt="" >
								<div class="message">
									<a class="message-author" href="#"> <?php echo $chatUserName; ?> </a><br>
									<span class="message-date"><?php echo date('d-M-Y H:i:s',strtotime($chatList['Ref_Date'])); ?></span><br>
									<span class="message-content">
									<?php echo $chatList['chat_note']; ?>
									</span>
								</div>
							</div>
							 <?php } ?>
						</div>				
					<div class="chat-message-form">
						<form action="add_details.php" method="post" name="frmActivity" id="frmActivity">
							<input type="hidden" name="patient_id" value="<?php echo $GetPatient[0]['patient_id']; ?>" />
							<input type="hidden" name="doc_id" value="<?php echo $admin_id; ?>" />
							<input type="hidden" name="ency_patient_id" value="<?php echo $Patient_id; ?>" />
							<input type="hidden" name="pname" value="<?php echo $_GET['pname']; ?>" />
							<div class="form-group">
								<textarea class="form-control message-input" name="txtDesc" id="txtDesc" required="required" placeholder="Enter message text"></textarea>
							</div>
							<br>
							<div class="form-group">
								<?php if( $GetPatient[0]['Trans_Status']!="TXN_SUCCESS"){  //If this patient case has been marked it as paid then hide this option ?>
								<label>Send this response to patient? </label>
								<div class="checkbox checkbox-success checkbox-inline">
												<input type="checkbox" id="inlineCheckbox2" value="1" name="patient_response_send">
												<label for="inlineCheckbox2"></label>
								</div> <?php } ?>
								<button type="submit" id="addActivity" name="addActivity" class="btn btn-primary pull-right">Submit</button>
							</div>
						</form>
					</div>
						<br><br>
					
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
	<!-- Chosen -->
    <script src="../assets/js/plugins/chosen/chosen.jquery.js"></script>
	<script>
       
        $('.chosen-select').chosen({width: "100%"});



    </script>
</body>

</html>
