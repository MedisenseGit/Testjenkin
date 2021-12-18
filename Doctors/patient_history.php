<?php
ob_start();
error_reporting(0); 
session_start();

include('send_text_message.php');
include('send_mail_function.php');
require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

$admin_id = $_SESSION['user_id'];

if(empty($admin_id)){
	header("Location:index.php");
}

$_SESSION['adminid']=$_GET['c'];
$_SESSION['patientid']=$_GET['p'];
$_SESSION['pname']=$_GET['pname'];
$admin_id = $_SESSION['adminid'];
$Patient_id=$_SESSION['patientid'];
$Cur_Date=date('Y-m-d h:i:s');

$GetPatient = $objQuery->mysqlSelect("a.patient_id as patient_id,a.patient_name as patient_name,a.patient_age as patient_age,a.patient_email as patient_email,a.patient_gen as patient_gen,a.merital_status as merital_status,a.qualification as qualification,a.weight as weight,a.hyper_cond as hyper_cond,a.diabetes_cond as diabetes_cond,a.pat_blood as pat_blood,a.contact_person as contact_person,a.profession as profession,a.patient_mob as patient_mob,a.patient_loc as patient_loc,a.pat_state as pat_state,a.pat_country as pat_country,a.patient_addrs as patient_addrs,a.patient_src as patient_src,a.medDept as medDept,a.patient_complaint as patient_complaint,a.patient_desc as patient_desc,a.pat_query as pat_query,a.TImestamp as TImestamp,a.data_source as data_source,b.status2 as status2,b.bucket_status as bucket_status,b.share_parient_contact as share_parient_contact,b.ref_id as ref_id","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","md5(a.patient_id)='".$Patient_id."' and md5(b.ref_id)='".$_SESSION['adminid']."'","","","","");
$attachDet = $objQuery->mysqlSelect("attach_id,attachments","patient_attachment","md5(patient_id)='".$Patient_id."'","","","","");
$attachCount = $objQuery->mysqlSelect("COUNT(attach_id) as Num_Attach","patient_attachment","md5(patient_id)='".$Patient_id."'","","","","");
$getDept = $objQuery->mysqlSelect("*","specialization","spec_id='".$GetPatient[0]['medDept']."'" ,"","","","");

				if($GetPatient[0]['patient_gen']=="1"){
					$gender="Male";
				}
				else if($GetPatient[0]['patient_gen']=="2"){
					$gender="Female";
				}
				
				if($GetPatient[0]['hyper_cond']=="0"){
					$hyperStatus="No";
				}
				else if($GetPatient[0]['hyper_cond']=="1"){
					$hyperStatus="Yes";
				}
				if($GetPatient[0]['diabetes_cond']=="0"){
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
	$getPatient = $objQuery->mysqlSelect("a.patient_name as PatName,a.patient_loc as Pat_loc,a.patient_id as Pat_Id,b.status2 as Current_Status,a.patient_src as Patient_Src","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","a.patient_id='".$_POST['patient_id']."'and b.ref_id='".$_POST['doc_id']."'","","","","");
	
	//GET Partner Details
	$getPartner = $objQuery->mysqlSelect("a.partner_name as Partner_Name,a.Email_id as Partner_Email,a.cont_num1 as Partner_Mobile","our_partners as a left join source_list as b on a.partner_id=b.partner_id","b.source_id='".$getPatient[0]['Patient_Src']."'","","","","");
	
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

//TURN TO DIRECT APPOINTMENT
if(isset($_POST['cmdAppt'])){
	
$txtRefId= $_POST['ref_id'];
$patientID= $_POST['patient_id'];

$trans_id=time(); //GET TRANSACTION ID
	
$chkRefInfo = $objQuery->mysqlSelect("*","patient_referal","ref_id='".$txtRefId."' and patient_id='".$patientID."'","","","","");
$arrFields2 = array();
$arrValues2 = array();
$arrFields2[]= 'patient_id'; 
$arrValues2[]= $patientID;
$arrFields2[]= 'ref_id'; 
$arrValues2[]= $txtRefId;
$arrFields2[]= 'status1';
$arrValues2[]= "1";
$arrFields2[]= 'status2';
$arrValues2[]= "7";
$arrFields2[]= 'conversion_status';
$arrValues2[]= "2";

if($chkRefInfo==true){
$editPatientStatus=$objQuery->mysqlUpdate('patient_referal',$arrFields2,$arrValues2,"patient_id='".$patientID."' and ref_id='".$txtRefId."'");
$arrFields1 = array();
$arrValues1 = array();
$arrFields1[]= 'bucket_status'; //UPDATE BUCKET STATUS TO "OP-DESIRED"
$arrValues1[]= "8";
$editPatientStatus=$objQuery->mysqlUpdate('patient_referal',$arrFields1,$arrValues1,"patient_id='".$patientID."'");

}

$chkPatInfo = $objQuery->mysqlSelect("patient_id,patient_name,patient_email,patient_mob,TImestamp","patient_tab","patient_id='".$patientID."'","","","","");	
$get_pro = $objQuery->mysqlSelect('a.ref_id as ref_id,a.ref_name as ref_name,a.ref_address as ref_address,a.doc_state as doc_state,a.doc_spec as doc_spec,a.doc_photo as doc_photo,c.hosp_name as hosp_name,d.company_name as company_name,d.email_id as CompEmail','referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id inner join compny_tab as d on d.company_id=c.company_id',"a.ref_id='".$txtRefId."'");
$getDepartment = $objQuery->mysqlSelect("*","specialization","spec_id='".$get_pro[0]['doc_spec']."'" ,"","","","");
			
									
						if(!empty($chkPatInfo[0]['patient_email'])){
							
							
						if(!empty($get_pro[0]['doc_photo'])){
							$docimg="https://medisensecrm.com/Doc/".$get_pro[0]['ref_id']."/".$get_pro[0]['doc_photo'];
						}	
						else{
							$docimg="https://medisensecrm.com/images/doc_icon.jpg";
						}
		
						$getDocName=urlencode(str_replace(' ','-',$get_pro[0]['ref_name']));
						$getDocSpec=urlencode(str_replace(' ','-',$getDepartment[0]['spec_name']));
						$getDocCity=urlencode(str_replace(' ','-',$get_pro[0]['ref_address']));
						$getDocState=urlencode(str_replace(' ','-',$get_pro[0]['doc_state']));
						$getDocHosp=urlencode(str_replace(' ','-',$get_pro[0]['hosp_name']));
						//$getDocHospAdd=urlencode(str_replace(' ','-',$get_pro[0]['hosp_addrs']));
						
						$reg_date=date('d-m-Y h:i',strtotime($chkPatInfo[0]['TImestamp']));
						$Link='https://medisensehealth.com/Panel-Of-Doctors/'.$getDocName.'-'.$getDocSpec.'-'.$getDocHosp.'-'.$getDocCity.'-'.$getDocState.'/'.$get_pro[0]['ref_id'];
						
						$url_page = 'Custom_Turn_to_Appointment.php';
						$url = rawurlencode($url_page);
						$url .= "?docname=".urlencode($get_pro[0]['ref_name']);
						$url .= "&docid=" . urlencode($get_pro[0]['ref_id']);
						$url .= "&docimg=".urlencode($docimg);
						$url .= "&docspec=".urlencode($getDepartment[0]['spec_name']);
						$url .= "&doclink=".urlencode($Link);
						$url .= "&regdate=" . urlencode($reg_date);
						$url .= "&patid=" . urlencode($chkPatInfo[0]['patient_id']);
						$url .= "&patname=" . urlencode($chkPatInfo[0]['patient_name']);
						$url .= "&patmail=" . urlencode($chkPatInfo[0]['patient_email']);
						$url .= "&hospname=" . urlencode($get_pro[0]['hosp_name']);
						$url .= "&compemail=" . urlencode($get_pro[0]['CompEmail']);
						$url .= "&ccmail=" . urlencode($ccmail);		
								
						send_mail($url);
						}	
						
					//SMS notification to Refering Doctors only when messge_status is active
					if(!empty($chkPatInfo[0]['patient_mob'])){
					$mobile = $chkPatInfo[0]['patient_mob'];
					$msg = "Action Required. We have sent you a mail. Please complete the action to get an appointment. Thx, ".$get_pro[0]['hosp_name'];
					
					send_msg($mobile,$msg);
					
					}
					
					$txtProNote= "Appointment Link for ".$get_pro[0]['ref_name']." Sent to ".$chkPatInfo[0]['patient_name']." Successfully";
					$arrFields1 = array();
					$arrValues1 = array();
									
					$arrFields1[]= 'patient_id';
					$arrValues1[]= $patientID;
					$arrFields1[]= 'ref_id';
					$arrValues1[]= $txtRefId;
					$arrFields1[]= 'chat_note';
					$arrValues1[]= $txtProNote;
					$arrFields1[]= 'user_id';
					$arrValues1[]= $admin_id;
					$arrFields1[]= 'status_id';
					$arrValues1[]= "7";
					$arrFields1[]= 'TImestamp';
					$arrValues1[]= $Cur_Date;
					
				
					$patientNote=$objQuery->mysqlInsert('chat_notification',$arrFields1,$arrValues1);
					
										
					$Successmessage="Appointment Link for ".$get_pro[0]['ref_name']." Sent to ".$chkPatInfo[0]['patient_name']." Successfully";
	//$response="Appointment-Success";
	//header("Location:patient-history?p=".$_SESSION['patientid']."&c=".$_SESSION['adminid']."&response=".$response);			
}

//TURN TO DIRECT APPOINTMENT
if(isset($_POST['cmdPay'])){
$txtRefId= $_POST['ref_id'];
$patientID= $_POST['patient_id'];

$trans_id=time(); //UPDATE TRANSACTION ID	
$arrFields = array();
$arrValues = array();
$arrFields[]= 'transaction_id';
$arrValues[]= $trans_id;
$arrFields[]= 'transaction_status';
$arrValues[]= "Pending";

$editPatient=$objQuery->mysqlUpdate('patient_tab',$arrFields,$arrValues,"patient_id='".$_POST['patient_id']."'");


$chkDocStatus = $objQuery->mysqlSelect("status2","patient_referal","patient_id='".$_POST['patient_id']."'and ref_id='".$txtRefId."'","","","","");
$chkPatInfo = $objQuery->mysqlSelect("patient_id,patient_name,patient_mob,patient_email,pat_country,TImestamp","patient_tab","patient_id='".$_POST['patient_id']."'","","","","");	
$get_pro = $objQuery->mysqlSelect('a.ref_id as ref_id,a.ref_name as ref_name,a.doc_spec as doc_spec,a.on_op_cost as on_op_cost,a.doc_photo as doc_photo,a.ref_address as ref_address,a.doc_state as doc_state,c.hosp_name as hosp_name,d.company_name as companyName,d.email_id as compEmail,d.company_logo as compLogo','referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id inner join compny_tab as d on d.company_id=c.company_id',"a.ref_id='".$txtRefId."'");
$getDepartment = $objQuery->mysqlSelect("*","specialization","spec_id='".$get_pro[0]['doc_spec']."'" ,"","","","");
			
				
				if(!empty($get_pro[0]['on_op_cost'])){
						
						if(!empty($get_pro[0]['doc_photo'])){
							$docimg="https://medisensecrm.com/Doc/".$get_pro[0]['ref_id']."/".$get_pro[0]['doc_photo'];
						}	
						else{
							$docimg="https://medisensecrm.com/images/doc_icon.jpg";
						}
						
						$getDocName=urlencode(str_replace(' ','-',$get_pro[0]['ref_name']));
						$getDocSpec=urlencode(str_replace(' ','-',$getDepartment[0]['spec_name']));
						$getDocCity=urlencode(str_replace(' ','-',$get_pro[0]['ref_address']));
						$getDocState=urlencode(str_replace(' ','-',$get_pro[0]['doc_state']));
						$getDocHosp=urlencode(str_replace(' ','-',$get_pro[0]['hosp_name']));
						//$getDocHospAdd=urlencode(str_replace(' ','-',$get_pro[0]['hosp_addrs']));
						
						$reg_date=date('d-m-Y h:i',strtotime($chkPatInfo[0]['TImestamp']));
						$Link='https://medisensehealth.com/Panel-Of-Doctors/'.$getDocName.'-'.$getDocSpec.'-'.$getDocHosp.'-'.$getDocCity.'-'.$getDocState.'/'.$get_pro[0]['ref_id'];
						
						$service="Second Opinion";
						if(!empty($chkPatInfo[0]['patient_email']) && $chkPatInfo[0]['pat_country']!=" " && $chkPatInfo[0]['pat_country']=="India")
						{ //DOMESTIC PATIENT MAIL
						
						
						$opcost=$get_pro[0]['on_op_cost'].".00";
						$paylink="https://medisensehealth.com/turn-to-pay.php?patid=".$_POST['patient_id']."&patname=".$chkPatInfo[0]['patient_name']."&mobile=".$chkPatInfo[0]['patient_mob']."&email=".$chkPatInfo[0]['patient_email']."&amount=".$opcost."&service=".$service."&docname=".$get_pro[0]['ref_name']."&docid=".$txtRefId;
							if($chkDocStatus[0]['status2']==5){ //IF DOCTOR ALREADY RESPONDED TO PATIENT QUERY THEN FOLLOWING PAYMENT MAIL WILL SEND TO PATIENT
								$url_page = 'Custom_Turn_to_Paylink.php';
							}else{
								$url_page = 'Custom_Turn_to_Paylink.php';
							}
								
								$url = rawurlencode($url_page);
								$url .= "?docname=".urlencode($get_pro[0]['ref_name']);
								$url .= "&docid=" . urlencode($get_pro[0]['ref_id']);
								$url .= "&docimg=".urlencode($docimg);
								$url .= "&docspec=".urlencode($getDepartment[0]['spec_name']);
								$url .= "&doclink=".urlencode($Link);
								$url .= "&regdate=" . urlencode($reg_date);
								$url .= "&paylink=".urlencode($paylink);
								$url .= "&docamount=".urlencode($get_pro[0]['on_op_cost']);
								$url .= "&patid=" . urlencode($chkPatInfo[0]['patient_id']);
								$url .= "&patname=" . urlencode($chkPatInfo[0]['patient_name']);
								$url .= "&patmobile=" . urlencode($chkPatInfo[0]['patient_mob']);					
								$url .= "&patmail=" . urlencode($chkPatInfo[0]['patient_email']);
								$url .= "&hospname=" . urlencode($get_pro[0]['hosp_name']);
								$url .= "&compemail=" . urlencode($get_pro[0]['compEmail']);
								$url .= "&ccmail=" . urlencode($ccmail);		
								
								send_mail($url);
						}
						else if(!empty($chkPatInfo[0]['patient_email']) && $chkPatInfo[0]['pat_country']!=" " && $chkPatInfo[0]['pat_country']!="India")
						{ //INTERNATIONAL PATIENT MAIL (PAYPAL LINK NEED TO BE SEND)
						
						
						$url_page = 'Custom_Non_Indian_paylink.php';
						$url = rawurlencode($url_page);
						$url .= "?docname=".urlencode($get_pro[0]['ref_name']);
						$url .= "&docid=" . urlencode($get_pro[0]['ref_id']);
						$url .= "&docimg=".urlencode($docimg);
						$url .= "&docspec=".urlencode($getDepartment[0]['spec_name']);
						$url .= "&doclink=".urlencode($Link);
						$url .= "&regdate=" . urlencode($reg_date);
						$url .= "&patid=" . urlencode($chkPatInfo[0]['patient_id']);
						$url .= "&patname=" . urlencode($chkPatInfo[0]['patient_name']);
						$url .= "&patmobile=" . urlencode($chkPatInfo[0]['patient_mob']);					
						$url .= "&patmail=" . urlencode($chkPatInfo[0]['patient_email']);
						$url .= "&hospname=" . urlencode($get_pro[0]['hosp_name']);
						$url .= "&compemail=" . urlencode($get_pro[0]['compEmail']);
						$url .= "&ccmail=" . urlencode($ccmail);		
								
						send_mail($url);
						}
								
						
					//SMS notification to Refering Doctors only when messge_status is active
					if(!empty($chkPatInfo[0]['patient_mob'])){
					$mobile = $chkPatInfo[0]['patient_mob'];
					$msg = $get_pro[0]['ref_name']."-Action Required. We have sent you a mail. Please complete the action to get an opinion. Thanks, Medisensehealth.com";
					
					//send_msg($mobile,$msg);
					
					}
					
					$txtProNote= "Payment Link Sent to ".$chkPatInfo[0]['patient_name']." Successfully";
					$arrFields1 = array();
					$arrValues1 = array();
									
					$arrFields1[]= 'patient_id';
					$arrValues1[]= $_POST['patient_id'];
					$arrFields1[]= 'ref_id';
					$arrValues1[]= $get_pro[0]['ref_id'];
					$arrFields1[]= 'chat_note';
					$arrValues1[]= $txtProNote;
					$arrFields1[]= 'user_id';
					$arrValues1[]= '0';
					$arrFields1[]= 'TImestamp';
					$arrValues1[]= $Cur_Date;
					
				
					$patientNote=$objQuery->mysqlInsert('chat_notification',$arrFields1,$arrValues1);
					
					//SET PAYEMNT REMINDER TABLE
					$arrFields3 = array();
					$arrValues3 = array();
									
					$arrFields3[]= 'patient_id';
					$arrValues3[]= $_POST['patient_id'];
					$arrFields3[]= 'doc_id';
					$arrValues3[]= $get_pro[0]['ref_id'];
					$arrFields3[]= 'reminder_count';
					$arrValues3[]= '0';
					$arrFields3[]= 'payment_status';
					$arrValues3[]= '1';
					$arrFields3[]= 'TImestamp';
					$arrValues3[]= $Cur_Date;
					$inserReminder=$objQuery->mysqlInsert('payment_reminder',$arrFields3,$arrValues3);
					
					$Successmessage="Payment Link for ".$get_pro[0]['ref_name']." Sent to ".$chkPatInfo[0]['patient_name']." Successfully";
				
				}
				else{
					$errormessage="Error !!!! Please check this Expert Opinion Cost";
					
				}
}

$get_pro = $objQuery->mysqlSelect("a.ref_id as RefId","referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id","","a.Tot_responded desc","","","");


?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Patient History</title>

    <!-- Bootstrap -->
    <link href="../Hospital/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../Hospital/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../Hospital/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- bootstrap-wysiwyg -->
    <link href="../Hospital/vendors/google-code-prettify/bin/prettify.min.css" rel="stylesheet">

    <!-- Custom styling plus plugins -->
    <link href="../Hospital/build/css/custom.min.css" rel="stylesheet">
	<script src="search/jquery-1.11.1.min.js"></script>
	<script src="search/jquery-ui.min.js"></script>
	<script src="search/jquery.select-to-autocomplete.js"></script>
	<script>
	  (function($){
	    $(function(){
	      $('#selectref').selectToAutocomplete();
	      $('#selectref1').selectToAutocomplete();
	    });
	  })(jQuery);
	  function call_refer(){
		   //alert("Logging in.........");
		   var user=document.getElementById('selectref').value;
		   		   
		   if(user==""){
		     alert("Please choose");
			 return false;
		   }
		   
		 }
	</script>

	<style>
	
    .ui-autocomplete {
      padding: 10px;
      list-style: none;
      background-color: #fff;
      width: 720px;
      border: 1px solid #B0BECA;
      max-height: 350px;
      overflow-x: hidden;
	   white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  max-width: 720px;
    }
    .ui-autocomplete .ui-menu-item {
      border-top: 1px solid #B0BECA;
      display: block;
      padding: 4px 6px;
      color: #353D44;
      cursor: pointer;
    }
    .ui-autocomplete .ui-menu-item:first-child {
      border-top: none;
    }
    .ui-autocomplete .ui-menu-item.ui-state-focus {
      background-color: #D5E5F4;
      color: #161A1C;
    }
	
	</style>
	
	<script type="text/javascript">
    $(function () {
        $("#headingOne").click(function () {
			
				$("#headingOne").show();
				$("#headingOne2").show();
           
        });
		$("#headingOne2").click(function () {
            
                
				$("#headingOne").show();
				$("#headingOne2").show();
           
        });
		$("#closetab2").click(function () {
            
                
				$("#headingOne").show();
				$("#headingOne2").show();
           
        });
		$("#closetab1").click(function () {
            
                
				$("#headingOne").show();
				$("#headingOne2").show();
           
        });
    });
</script>
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
       
		<!--Side Menu & Top Navigation -->
        <?php include_once('side_menu.php'); ?>

        <!-- page content -->
		
        <div class="right_col" role="main">
          <div class="">

            
            <div class="clearfix"></div>
			
						<?php 
						if($GetPatient[0]['status2']=="2"){ $patient_status="REFERRED"; ?>
						<?php } else if($GetPatient[0]['status2']=="3"){  $patient_status="P-AWAITING";?>
						<?php } else if($GetPatient[0]['status2']=="5"){  $patient_status="RESPONDED";?>
						<?php } else if($GetPatient[0]['status2']=="6"){  $patient_status="RESPONSE-PATIENT-FAILED</span>";?>
						<?php } else if($GetPatient[0]['status2']=="7"){  $patient_status="STAGED";?>
						<?php } else if($GetPatient[0]['status2']=="8"){  $patient_status="OP-DESIRED";?>
						<?php } else if($GetPatient[0]['status2']=="9"){  $patient_status="IP-TREATED";?>
						<?php } else if($GetPatient[0]['status2']=="10"){  $patient_status="NOT-CONVERTED";?>
						<?php } else if($GetPatient[0]['status2']=="11"){  $patient_status="INVOICED";?>
						<?php } else if($GetPatient[0]['status2']=="12"){  $patient_status="PAYMENT RECEIVED"; ?>
						<?php } else if($GetPatient[0]['status2']=="13"){  $patient_status="OP-VISITED"; 
						}
						
						?>
			
			<div class="right">
			<?php
			
					if(isset($Successmessage)){ ?>
						<div class="alert alert-success alert-dismissable">
												<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
												<strong><?php echo $Successmessage; ?> </strong>
											  </div>
					<?php }
					
					if(isset($errormessage)){ ?>
						<div class="alert alert-danger alert-dismissable">
												<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
												<strong><?php echo $errormessage; ?> </strong>
											  </div>
					<?php }
					?>
							<div class="form-group pull-right top_search">
								<div class="input-group">
									<a href="<?php echo $_GET['pname']; ?>" class="btn btn-primary btn-xs"><i class="fa fa-arrow-left"></i> BACK</a>
									</span>
								</div>
							</div>
						</div>
            <div class="row">
              <div class="col-md-12">
                <div class="x_panel">
                  <div class="x_title">
               		
					
					<!--START REASSIGN SECTION -->
					
					<div class="col-sm-4" style="float:left;margin-top:8px; ">
                      
                        <a class="panel" role="tab" id="headingOne" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                          <h4 class="label label-danger" style="font-size:14px; padding:8px; font-weight:normal;"> <i class="fa fa-reply"></i> REASSIGN</h4>   
						
                        </a>
						<form method="post" name="frmReassign" action="add_details.php">
						<input type="hidden" name="patientid" value="<?php echo $GetPatient[0]['patient_id']; ?>" />
						<input type="hidden" name="oldrefid" value="<?php echo $GetPatient[0]['ref_id']; ?>" />
						<input type="hidden" name="patmobile" value="<?php echo $GetPatient[0]['patient_mob']; ?>" />
						<input type="hidden" name="patemail" value="<?php echo $GetPatient[0]['patient_email']; ?>" />
						<input type="hidden" name="patname" value="<?php echo $GetPatient[0]['patient_name']; ?>" />
						
                        <div id="collapseOne" class="panel-collapse collapse out" role="tabpanel" aria-labelledby="headingOne">
                        <a class="panel" role="tab" id="headingOne" data-toggle="collapse" href="#collapseOne"> <span style="float:right; font-size:16px; float:right;margin-right:20px;">X</span></a>
						 <div class="panel-body">
                            <select class="left form-control autotab" name="selectref" id="selectref" placeholder="Select Panel Doctor" style="float:left; width:200px; padding:5px;">
													<option value="">Select Panel Doctor</option>
													<?php foreach($get_pro as $listDoc) {
													$get_Ref = $objQuery->mysqlSelect('*','referal',"ref_id='".$listDoc['RefId']."'","","","","");
													$get_spec = $objQuery->mysqlSelect('*','specialization',"spec_id='".$get_Ref[0]['doc_spec']."'","","","","");
													$getHosp = $objQuery->mysqlSelect("*","hosp_tab as a left join doctor_hosp as b on a.hosp_id=b.hosp_id","doc_id='".$get_Ref[0]['ref_id']."'" ,"","","",""); 
													//$ResponseCount = $objQuery->mysqlSelect("*","patient_referal","ref_id='".$listDoc['ref_id']."'" ,"","","","");
													
													$ResponseRate=($get_Ref[0]['Tot_responded']/$get_Ref[0]['Total_Referred'])*100;
													
													if($get_Ref[0]['doc_type']=="volunteer"){
														$star="<span style='color:red;'>**</font>";
													} else if($get_Ref[0]['doc_type']=="star"){
														$star="<span style='color:red;'>*****</font>";
													} else if($get_Ref[0]['doc_type']=="featured"){
														$star="<span style='color:red;'>***</font>";
													}else{
														$star="<span style='color:red;'></font>";
														
													}
													?>	
													
													
											  <option value="<?php echo $get_Ref[0]['ref_id']; ?>"  ><?php echo addslashes($get_Ref[0]['ref_name'])."&nbsp;".addslashes($get_spec[0]['spec_name'])."&nbsp;".addslashes($getHosp[0]['hosp_name'])."&nbsp;".addslashes($get_Ref[0]['ref_address'])."&nbsp;".addslashes($get_Ref[0]['doc_state'])."&nbsp;".trim(preg_replace('/\s+/',' ', $get_Ref[0]['doc_keywords'])); ?></option>
											<?php } ?>
											</select>
											<button type="submit" name="cmdreassign" id="cmdreassign" class="btn btn-sm btn-primary" style="float:right;"> REASSIGN</button>
							</div>
							</div>
							</form>
					  
					  </div>
                    	
					
					<!--END REASSIGN SECTION -->
					
					
					<script language="JavaScript" src="js/status_validation.js"></script>
					
					<div class="col-sm-2 pull-right">
					<form method="post" name="frmApp1">	
					<input type="hidden" name="ref_id" value="" />
					<input type="hidden" name="cmdAppt" value="" />
					<input type="hidden" name="patient_id" value="" />
                       
					<a class="btn btn-primary" href='#' onclick="return sendAptLink(<?php echo $GetPatient[0]['patient_id'];?>,<?php echo $GetPatient[0]['ref_id'];?>)"><i class="fa fa-calendar"></i> Send Appt Req</a>				
					<!--<button type="submit" name="cmdAppt" id="cmdAppt" class="btn btn-primary" ><i class="fa fa-calendar"></i> Send Appt Req</button>	-->			
					</form>
					</div>
					<div class="col-sm-2 pull-right">
					<form method="post" name="frmPayment">	
					<input type="hidden" name="ref_id" value="" />
					<input type="hidden" name="cmdPay" value="" />
					<input type="hidden" name="patient_id" value="" />
					<a class="btn btn-success" href='#' disabled ><i class="fa fa-credit-card"></i> Send Payment Link</a>
					<!--<a class="btn btn-success" href='#' onclick="return sendPayLink(<?php echo $GetPatient[0]['patient_id'];?>,<?php echo $GetPatient[0]['ref_id'];?>)"><i class="fa fa-credit-card"></i> Send Payment Link</a>-->	
					</form>
					</div>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <div class="row">
                      
                      <!-- CONTENT MAIL -->
                      <div class="col-sm-8 mail_view">
                        <div class="inbox-body">
                          <div class="mail_heading row">
                            <!--<div class="col-md-8">
                              <div class="btn-group">
                                <button class="btn btn-sm btn-primary" type="button"><i class="fa fa-reply"></i> Reply</button>
                                <button class="btn btn-sm btn-default" type="button"  data-placement="top" data-toggle="tooltip" data-original-title="Forward"><i class="fa fa-share"></i></button>
                                <button class="btn btn-sm btn-default" type="button" data-placement="top" data-toggle="tooltip" data-original-title="Print"><i class="fa fa-print"></i></button>
                                <button class="btn btn-sm btn-default" type="button" data-placement="top" data-toggle="tooltip" data-original-title="Trash"><i class="fa fa-trash-o"></i></button>
                              </div>
                            </div>-->
							
                            <div class="col-md-12">
							<h2 class="left"><?php echo $GetPatient[0]['patient_name']; ?>( #<?php echo $GetPatient[0]['patient_id']; ?> )</h2>
                              <p class="date text-right"><i class="fa fa-calendar"></i> Reg. Date:  <?php echo date('H:i',strtotime($GetPatient[0]['TImestamp'])); ?>  <?php echo date('M d-Y',strtotime($GetPatient[0]['TImestamp'])); ?></p>
                            </div>
							<br><br><br>
                            <div class="col-md-12">
							<!-- info row -->
							<div class="row invoice-info">
									<div class="col-sm-4 invoice-col">
									  <b>Patient ID: </b><?php echo $GetPatient[0]['patient_id']; ?>
									  <br><br>
									
									  <b>Patient Name: </b> <?php echo $GetPatient[0]['patient_name']; ?>
									  <br><br>
									  <b>Age: </b> <?php echo $GetPatient[0]['patient_age']; ?>
									  <br><br>
									  <b>Gender: </b> <?php echo $gender; ?>
									  <br><br>
									  <b>Marital Status: </b> <?php if(empty($GetPatient[0]['merital_status'])){ echo "NS"; } else { echo $GetPatient[0]['merital_status']; } ?>
									  <br><br>
									  <b>Weight: </b> <?php echo $GetPatient[0]['weight'];  ?>
									  <br><br>
									  <b>Hypertension ?: </b> <?php echo $hyperStatus;  ?>
									  <br><br>
									  <b>Diabetes ?: </b> <?php echo $diabetesStatus;  ?>
									  <br><br>
									  <b>Qualification: </b> <?php if(empty($GetPatient[0]['qualification'])){ echo "NS"; } else { echo $GetPatient[0]['qualification']; } ?>
									</div>
									<!-- /.col -->
								
								  
									<div class="col-sm-4 invoice-col">
									  <b>Decision Maker: </b><?php echo $GetPatient[0]['contact_person'];  ?>
									  <br><br><b>Address</b>
									  <address><?php echo $GetPatient[0]['patient_addrs'];  ?><br>
													  <?php echo $GetPatient[0]['patient_loc'];  ?>, <?php echo $GetPatient[0]['pat_state'];  ?>
												  </address>
												  
										 <b>City: </b><?php echo $GetPatient[0]['patient_loc'];  ?><br><br>
										 <b>State: </b><?php echo $GetPatient[0]['pat_state'];  ?><br><br>
										 <b>Country: </b><?php echo $GetPatient[0]['pat_country'];  ?><br><br>
										 <b>Department: </b><?php echo $getDept[0]['spec_name']; ?><br>
									</div>
									<!-- /.col -->
									<div class="col-sm-4 invoice-col">
									<ul class="nav navbar-right">
										<!--<script language="javaScript" src="js/status_validation.js"></script> --> 
										<!-- Status Change button -->
										<form method="post" name="frmchangeStatus" id="frmchangeStatus">
										<input type="hidden" name="slct_val" value="" />
										<input type="hidden" name="patient_id" value="" />
										<input type="hidden" name="doc_id" value="" /> 
										<input type="hidden" name="cmdchangeStatus" value="" />  
											<div class="btn-group">
											  <button type="button" class="btn btn-success"><?php echo $patient_status; ?></button>
											  <button type="button" class="btn btn-success dropdown-toggle"  data-toggle="dropdown" aria-expanded="false">
												<span class="caret" style="color:#fff;"></span>
												<span class="sr-only">Toggle Dropdown</span>
											  </button>
											  <ul class="dropdown-menu" role="menu">
												<li><a href="#" onclick="return ChangeStatus(13,<?php echo $GetPatient[0]['patient_id']; ?>,<?php echo $GetPatient[0]['ref_id']; ?>);">OP-Visited</a>
												</li>
												<li><a href="#" onclick="return ChangeStatus(9,<?php echo $GetPatient[0]['patient_id']; ?>,<?php echo $GetPatient[0]['ref_id']; ?>);">IP-Treated</a>
												</li>
												
											  </ul>
											</div>
										</form>
										</ul>
									</div>
									<!-- /.col -->
									
								  </div>
								<hr>
								</div>
                          </div>
                           <?php if(!empty($GetPatient[0]['patient_complaint'])){ ?><div class="sender-info">
                            <div class="row">
                              <div class="col-md-12">
                                <strong>Patient Complaint</strong>
                                
                                <a class="sender-dropdown"><i class="fa fa-chevron-down"></i></a>
                              </div>
                            </div>
                          </div>
						 
                          <div class="view-mail">
						    <p><?php echo $GetPatient[0]['patient_complaint'];  ?></p>
                          </div>
						  <?php } ?>
						  
						  <?php if(!empty($GetPatient[0]['patient_desc'])){ ?><div class="sender-info">
                            <div class="row">
                              <div class="col-md-12">
                                <strong>Description</strong>
                                
                                <a class="sender-dropdown"><i class="fa fa-chevron-down"></i></a>
                              </div>
                            </div>
                          </div>
						 
                          <div class="view-mail">
						    <p><?php echo $GetPatient[0]['patient_desc'];  ?></p>
                          </div>
						  <?php } ?>
						  
						   <?php if(!empty($GetPatient[0]['pat_query'])){ ?><div class="sender-info">
                            <div class="row">
                              <div class="col-md-12">
                                <strong>Medical Query</strong>
                                
                                <a class="sender-dropdown"><i class="fa fa-chevron-down"></i></a>
                              </div>
                            </div>
                          </div>
						 
                          <div class="view-mail">
						    <p><?php echo $GetPatient[0]['pat_query'];  ?></p>
                          </div>
						  <?php } ?>
                          <div class="attachment">
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
							else if($extractPath=="docs"){
									$imgIcon="../Hospital/images/docs_icon.png";
							}
							else{
								$imgIcon="../Hospital/images/PDF-Icon.png";
							} ?>
                              <li>
                                <a href="../Attach/<?php echo stripslashes($attachList['attach_id']);?>/<?php echo stripslashes($attachList['attachments']);?>" target="_blank"  title="<?php echo stripslashes($attachList['attachments']);?>">
                                  <img src="<?php echo $imgIcon; ?>" alt="<?php echo $attachList['attachments']; ?>"/>
                                </a>

                                <div class="file-name">
                                 <?php echo substr($attachList['attachments'],0,10); ?>
                                </div>
                               <!--<span>12KB</span>-->


                                <div class="links">
                                  <a href="../Attach/<?php echo stripslashes($attachList['attach_id']);?>/<?php echo stripslashes($attachList['attachments']);?>" target="_blank"  title="<?php echo stripslashes($attachList['attachments']);?>">View</a> -
                                  <a href="https://medisensecrm.com/download-Attachments.php?attach_id=<?php echo stripslashes($attachList['attach_id']);?>&attach_name=<?php echo $attachList['attachments']; ?>" target="_blank">Download</a>
                                </div>
                              </li>
							<?php } ?>
                              

                            </ul>
                          </div>
                         <!-- <div class="btn-group">
                            <button class="btn btn-sm btn-primary" type="button"><i class="fa fa-reply"></i> Reply</button>
                            <button class="btn btn-sm btn-default" type="button"  data-placement="top" data-toggle="tooltip" data-original-title="Forward"><i class="fa fa-share"></i></button>
                            <button class="btn btn-sm btn-default" type="button" data-placement="top" data-toggle="tooltip" data-original-title="Print"><i class="fa fa-print"></i></button>
                            <button class="btn btn-sm btn-default" type="button" data-placement="top" data-toggle="tooltip" data-original-title="Trash"><i class="fa fa-trash-o"></i></button>
                          </div>-->
                        </div>

                      </div>
                      <!-- /CONTENT MAIL -->
					  <div class="col-sm-4 mail_list_column" style="min-height:600px;">
					  <!-- Message Section -->
							<?php if($_GET['response']==1){ ?> <div class="alert alert-success alert-dismissable">
												<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
												<strong>Success!</strong> response has been sent to the patient
											  </div>
								<?php } if($_GET['response']=="reassign"){ ?> <div class="alert alert-success alert-dismissable">
												<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
												<strong>Success!</strong> case has been reassigned successfully
											  </div>
								<?php } if($_GET['response']=="error-reassign"){ ?> <div class="alert alert-danger alert-dismissable">
												<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
												<strong>Error !!!!</strong> Please Select our panelist before proceeding further
											  </div>
								<?php } if($_GET['response']=="Appointment-Success"){ ?> <div class="alert alert-success alert-dismissable">
												<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
												<strong>Success !!!!</strong> Appointment link has been sent to the patient 
											  </div>
								<?php } ?>
                        <form method="post" name="frmAppointment" action="add_details.php">
						<button id="compose" class="btn btn-sm btn-success" type="button">CLICK HERE TO RESPOND</button>
						
						<input type="hidden" name="ref_id" value="<?php echo $GetPatient[0]['ref_id'];?>" />
						<input type="hidden" name="patient_id" value="<?php echo $GetPatient[0]['patient_id'];?>" />
                        <!--<button type="submit" class="btn btn-sm btn-primary" name="sendAppReq" ><i class="fa fa-calendar"></i> SEND APPT REQ.</button>-->
						</form>
						
						<br><br>
						<?php $getchatHistory = $objQuery->mysqlSelect("*,a.TImestamp as Ref_Date","chat_notification as a inner join referal as b on a.ref_id=b.ref_id","a.patient_id='".$GetPatient[0]['patient_id']."'and a.ref_id='".$GetPatient[0]['ref_id']."'","a.chat_id desc","","","");
						foreach($getchatHistory as $chatList){ ?>
                        <a href="#">
                          <div class="mail_list">
						 
                            <div class="left">
                              <?php if(!empty($chatList['doc_photo'])){ ?>
											<img src="../Doc/<?php echo $chatList['ref_id']; ?>/<?php echo $chatList['doc_photo']; ?>" width="50" alt="User Avatar" class="img-circle" />
											<?php } else { ?>
											<img src="../Hospital/images/anonymous_doc.png" width="50" alt="User Avatar" class="img-circle" style="margin-right:15px;" />
											<?php } ?>
							  
							  
                            </div>
                            <div style="margin-left:60px;">
                              <h3><?php echo $chatList['ref_name'];  ?><?php echo $chatList['spec_name'];  ?><small><i class="fa fa-calendar"></i> <?php echo date('d-M-Y h:i',strtotime($chatList['Ref_Date']));  ?></small></h3>
                              <br><p><i class="fa fa-edit"></i> - <?php echo $chatList['chat_note']; ?></p>
                            </div>
                          </div>
                        </a>
						<?php } ?>
						
						
						
					 
                      </div>
                      <!-- /MAIL LIST -->
					  
					  
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->

        <?php include_once('footer.php'); ?>
      </div>
    </div>

    <!-- compose -->
	<form action="add_details.php" method="post" name="frmActivity" id="frmActivity">
		<input type="hidden" name="patient_id" value="<?php echo $GetPatient[0]['patient_id']; ?>" />
		<input type="hidden" name="doc_id" value="<?php echo $GetPatient[0]['ref_id']; ?>" />
		<input type="hidden" name="ency_patient_id" value="<?php echo $Patient_id; ?>" />
		<input type="hidden" name="ency_admin_id" value="<?php echo $admin_id; ?>" />
		<input type="hidden" name="pname" value="<?php echo $_GET['pname']; ?>" />							
		<div class="compose col-md-6 col-xs-12">
		  <div class="compose-header">
			Respond Here
			<button type="button" class="close compose-close">
			  <span>×</span>
			</button>
		  </div>
		<textarea style="width:100%; border:none;" rows="8" name="txtDesc" id="txtDesc" placeholder="Text written here will be visible to patients" ></textarea>
		<div class="compose-footer">
			Would you like to send this response to patient? <input type="checkbox" name="patient_response_send" value="1" /> Yes
			<button type="submit" id="addActivity" name="addActivity" class="btn btn-sm btn-success" >SUBMIT</button>
		</div>
		</div>
	</form>
    <!-- /compose -->
	
	 <!-- jQuery -->
    <script src="../Hospital/vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../Hospital/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="../Hospital/vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="../Hospital/vendors/nprogress/nprogress.js"></script>
    <!-- bootstrap-wysiwyg -->
    <script src="../Hospital/vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js"></script>
    <script src="../Hospital/vendors/jquery.hotkeys/jquery.hotkeys.js"></script>
    <script src="../Hospital/vendors/google-code-prettify/src/prettify.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="../Hospital/build/js/custom.min.js"></script>

  </body>
</html>