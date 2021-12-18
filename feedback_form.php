<?php ob_start();
 error_reporting(0);
 session_start(); 

$admin_id = $_SESSION['admin_id'];
$Company_id=$_SESSION['comp_id'];
date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d h:i:s');
$cur_Date=date('Y-m-d',strtotime($Cur_Date));

if(empty($admin_id)){
header("Location:index.php");
}
require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
ob_start();

if(isset($_POST['cmdSubmit'])){
	$medResId = $_POST['medresId'];
	$medResStId = $_POST['medresStId'];
	$medComment = addslashes($_POST['medComment']);
	$hosResId = $_POST['hospresId'];
	$hosComment = addslashes($_POST['hospComment']);
	$hospVisitId = $_POST['visitId'];
	$slctHospType = $_POST['slctHosp'];
	$slctServiceId = $_POST['servId'];
	$slctMsgStId = $_POST['msgStatus'];

	$slctTreatId1 = $_POST['slctTreatId1'];
	$HospComment1 = addslashes($_POST['HospComment1']);
		
	$slctTreatId2 = $_POST['slctTreatId2'];
	$HospComment2 = addslashes($_POST['HospComment2']);
	
	$OtherHospName = addslashes($_POST['txtOther']);	
	$slctTreatId_Other = $_POST['slctTreatId_Other'];
	$HospComment_Other = addslashes($_POST['HospComment_other']);
	$Patient_Resp_Status = $_POST['patresId'];
	
	$arrFields = array();
	$arrValues = array();

		$arrFields[] = 'patient_id';
		$arrValues[] = $_GET['pat_id'];
		$arrFields[] = 'med_resid';
		$arrValues[] = $medResId;
		$arrFields[] = 'med_res_statid';
		$arrValues[] = $medResStId;
		$arrFields[] = 'med_comment';
		$arrValues[] = $medComment;
		
		$arrFields[] = 'hosp_repond';
		$arrValues[] = $hosResId;
		$arrFields[] = 'hosp_resp_Comment';
		$arrValues[] = $hosComment;				
		
		$arrFields[] = 'hosp_visit_cond';
		$arrValues[] = $hospVisitId;
		$arrFields[] = 'hosp_slct_cond';
		$arrValues[] = $slctHospType;
		$arrFields[] = 'med_service_id';
		$arrValues[] = $slctServiceId;
		$arrFields[] = 'msg_id';
		$arrValues[] = $slctMsgStId;
		$arrFields[] = 'datetime';
		$arrValues[] = $Cur_Date;
		$arrFields[] = 'user_id';
		$arrValues[] = $admin_id;
		$arrFields[] = 'pat_resp_status';
		$arrValues[] = $Patient_Resp_Status;
		$arrFields[] = 'system_date';
		$arrValues[] = $cur_Date;
		
		
		$ChkPatFeed= $objQuery->mysqlSelect("*","patient_feedback","patient_id='".$_GET['pat_id']."'","","","","");
		
				
 if($ChkPatFeed==true){
	$updateProvider=$objQuery->mysqlUpdate('patient_feedback',$arrFields,$arrValues,"patient_id='".$_POST['Pat_Id']."'");
	
	
	}
	else{	
		if($medResId!=="" || $medResStId!=="" || $hospVisitId!=""){
		$feedCreate=$objQuery->mysqlInsert('patient_feedback',$arrFields,$arrValues);
		$id = mysql_insert_id();
		$arrFields1 = array();
		$arrValues1 = array();
		$arrFields1[] = 'feedback_id';
		$arrValues1[] = $id;
		$arrFields1[] = 'ref_id';
		$arrValues1[] = $_POST['Ref_id1'];
		$arrFields1[] = 'chat_note';
		$arrValues1[] = $HospComment1;
		$arrFields1[] = 'ref_hospServId';
		$arrValues1[] = $slctTreatId1;
		$arrFields1[] = 'user_id';
		$arrValues1[] = $admin_id;
			if($_POST['HospComment1']!=""){
			$feedNote=$objQuery->mysqlInsert('feedback_note',$arrFields1,$arrValues1);
			}
		$arrFields2 = array();
		$arrValues2 = array();
		$arrFields2[] = 'feedback_id';
		$arrValues2[] = $id;
		$arrFields2[] = 'ref_id';
		$arrValues2[] = $_POST['Ref_id2'];
		$arrFields2[] = 'chat_note';
		$arrValues2[] = $HospComment2;
		$arrFields2[] = 'ref_hospServId';
		$arrValues2[] = $slctTreatId2;
		$arrFields2[] = 'user_id';
		$arrValues2[] = $admin_id;
			
			if($_POST['HospComment2']!=""){
			$feedNote=$objQuery->mysqlInsert('feedback_note',$arrFields2,$arrValues2);
			}
			
		$arrFields3 = array();
		$arrValues3 = array();
		$arrFields3[] = 'feedback_id';
		$arrValues3[] = $id;
		$arrFields3[] = 'ref_id';
		$arrValues3[] = '0';
		$arrFields3[] = 'other_ref_name';
		$arrValues3[] = $OtherHospName;
		$arrFields3[] = 'chat_note';
		$arrValues3[] = $HospComment_Other;
		$arrFields3[] = 'ref_hospServId';
		$arrValues3[] = $slctTreatId_Other;
		$arrFields3[] = 'user_id';
		$arrValues3[] = $admin_id;
			
			if($HospComment_Other!=""){
			$feedNote=$objQuery->mysqlInsert('feedback_note',$arrFields3,$arrValues3);
			}
		}			
	} 

}
	
$get_FeedBack = $objQuery->mysqlSelect("*","patient_feedback ","patient_id='".$_GET['pat_id']."'","","","","");
$get_ChatFeedBack = $objQuery->mysqlSelect("*","feedback_note","feedback_id='".$get_FeedBack[0]['feedback_id']."'","","","","");
$_SESSION['Feed_id']=$get_FeedBack[0]['feedback_id'];
$getInfo = $objQuery->mysqlSelect("*","patient_tab","patient_id='".$_GET['pat_id']."'" ,"","","","");
$getUser = $objQuery->mysqlSelect("*","chckin_user","chk_userid='".$get_FeedBack[0]['user_id']."'" ,"","","","");
$getRefCount = $objQuery->mysqlSelect("*","patient_referal as a left join referal as b on a.ref_id=b.ref_id","a.patient_id='".$_GET['pat_id']."'" ,"","","","");
?>

<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8" />
<title>Check-In</title>
<?php include_once('support_file.php'); ?>
<script>

window.onload = function() {
document.getElementById('slctHosp').onchange = disableSelect;
document.getElementById('visit_hosp').onchange = disableAll;
}
function disableSelect()
{
if ( document.getElementById('slctHosp').value == '1' ){
document.getElementById('Chk_TreatId').disabled = false;
document.getElementById('Chk_TreatId1').disabled = false;
document.getElementById('Chk_TreatId2').disabled = false;
document.getElementById('Chk_TreatId3').disabled = false;
document.getElementById('Chk_TreatId4').disabled = false;
document.getElementById('Chk_TreatId5').disabled = false;
document.getElementById('HospComment1').disabled = false;
document.getElementById('HospComment2').disabled = false;
document.getElementById('other_name').disabled = true;
document.getElementById('Chk_TreatId_other1').disabled = true;
document.getElementById('Chk_TreatId_other2').disabled = true;
document.getElementById('Chk_TreatId_other3').disabled = true;
document.getElementById('HospComment_other').disabled = true;
}
else if (document.getElementById('slctHosp').value == '2' ){
document.getElementById('Chk_TreatId').disabled = true;
document.getElementById('Chk_TreatId1').disabled = true;
document.getElementById('Chk_TreatId2').disabled = true;
document.getElementById('Chk_TreatId3').disabled = true;
document.getElementById('Chk_TreatId4').disabled = true;
document.getElementById('Chk_TreatId5').disabled = true;
document.getElementById('HospComment1').disabled = true;
document.getElementById('HospComment2').disabled = true;
document.getElementById('other_name').disabled = false;
document.getElementById('Chk_TreatId_other1').disabled = false;
document.getElementById('Chk_TreatId_other2').disabled = false;
document.getElementById('Chk_TreatId_other3').disabled = false;
document.getElementById('HospComment_other').disabled = false;
}
}

function disableAll()
{
if ( document.getElementById('visit_hosp').value == '1' ){
document.getElementById('slctHosp').disabled = false;


}
else if (document.getElementById('visit_hosp').value == '2' ){
document.getElementById('slctHosp').disabled = true;}
}
</script>
</head>

<body onload="noBack();" onpageshow="if (event.persisted) noBack();" onunload="">
  


<?php include_once('header.php'); ?>


<div class="clearall">
 <div class="wrapper">
 <script language="javaScript" src="js/validation.js"></script>
 
  <div class="Feedback_sec clearfix">	
  <div class="rightNav fr">
  <?php if($_GET['pat_id']!=""){ ?>
		<a href="Patient_History.php?<?php if(!empty($_GET['pat_id'])) { ?>pat_id=<?php echo $_GET['pat_id']; } if(!empty($_GET['disp'])) { ?>&disp=<?php echo $_GET['disp']; } if(!empty($_GET['assignId'])) { ?>&assignId=<?php echo $_GET['assignId']; } if(!empty($_GET['refid'])) { ?>&refid=<?php echo $_GET['refid']; } if(!empty($_GET['start'])) { ?>&start=<?php echo $_GET['start']; } ?>"><< Back</a>
  <?php } else { ?>
		<a href="manage_account.php"><< Back</a>
  <?php } ?>
	</div>
	
<!--Patient Details-->
	<div class="Patient_history fl">
		<div class="left_sec fl">
		<p><span>Enquiry Date : </span><?php echo $Date=date('d-m-Y',strtotime($getInfo[0]['TImestamp']));?><br>
			<span>Patient Id :</span> 00<?php echo $getInfo[0]['patient_id']; ?><br>
			<span>Patient Name :</span> <?php echo $getInfo[0]['patient_name']; ?><br>
			<span>Contact Person :</span> <?php echo $getInfo[0]['contact_person']; ?> <br>
			<span>Contact No.:</span> <?php echo $getInfo[0]['patient_mob']; ?> <br>
			<span>Location :</span> <?php echo $getInfo[0]['patient_loc']; ?> 
		</p>
		</div>
		<div class="right_sec">
			<p><span>Brief Description : </span><textarea name="patDesc" class="txtArea" disabled><?php echo $getInfo[0]['patient_desc']; ?></textarea></p> 
		</div>
	</div>
	
	
	
   <form method="post" name="frmFeedback" action="">
  	
	<div class="Feedback fl">	
	<h2>PATIENT FEEDBACK</h2>
	<div class="Feedback_left fl">
	
	<?php
	if(isset($sucessMessage)){ ?>
	<span class="success"><?php echo $sucessMessage; ?></span>
	<?php	}
	if(isset($errorMessage)){ ?>
						<span class="error"><?php echo $errorMessage; ?></span>
	<?php } ?>
	
		
		
		<table>
	
		<input type="hidden" name="Pat_Id" value="<?php echo $_GET['pat_id']; ?>" />
		<input type="hidden" name="Ref_id1" value="<?php echo $getRefCount[0]['ref_id']; ?>" />
		<input type="hidden" name="Ref_id2" value="<?php echo $getRefCount[1]['ref_id']; ?>" />
		
		<?php if($get_FeedBack[0]['user_id']) { ?><tr><td class="txtWidth"><h3>Feedback Taken By :</h3></td><td><h4> <div class="fl"><label><?php echo $getUser[0]['chk_username']; ?></label></div></td></tr> <?php } ?>
			
		<tr><td class="txtWidth"><h3>Patient Response Status :</h3></td><td><h4> <div class="fl"><label><input type="radio" name="patresId" value="1" class="rdBtn" <?php if($get_FeedBack[0]['pat_resp_status']==1){ echo "checked"; } ?>/>Responded</label></div>
													<div class="fl"><label><input type="radio" name="patresId" value="2" class="rdBtn" <?php if($get_FeedBack[0]['pat_resp_status']==2){ echo "checked"; } ?>/>Not Responded</label></div></h4></td></tr>
				
		<tr><td class="txtWidth"><h3>Responded by Medisense ? :</h3></td><td><h4> <div class="fl"><label><input type="radio" name="medresId" value="1" class="rdBtn" <?php if($get_FeedBack[0]['med_resid']==1){ echo "checked"; } ?>/>Yes</label></div>
													<div class="fl"><label><input type="radio" name="medresId" value="2" class="rdBtn" <?php if($get_FeedBack[0]['med_resid']==2){ echo "checked"; } ?>/>No</label></div></h4></td></tr>
													
		<tr><td class="txtWidth"><h3>Medisense Response ? : </h3></td><td><h4><div class="fl"><label><input type="radio" name="medresStId" value="1" class="rdBtn" <?php if($get_FeedBack[0]['med_res_statid']==1){ echo "checked"; } ?>/>Satisfactory</label></div>
													<div class="fl"><label><input type="radio" name="medresStId" value="2" class="rdBtn" <?php if($get_FeedBack[0]['med_res_statid']==2){ echo "checked"; } ?>/>Neutral</label></div>
													<div class="fl"><label><input type="radio" name="medresStId" value="3" class="rdBtn" <?php if($get_FeedBack[0]['med_res_statid']==3){ echo "checked"; } ?>/>Not satisfactory</label></div></h4></td></tr>
		
		<tr><td class="txtWidth"><h3>Medisense Comments :</h3></td><td><h4><textarea name="medComment" value="" class="txtArea fr"><?php echo $get_FeedBack[0]['med_comment']; ?></textarea></h4></td></tr>
		
		<tr><td class="txtWidth"><h3>Responded by the Hospital ? :</h3></td><td><h4> <div class="fl"><label><input type="radio" name="hospresId" value="1" class="rdBtn" <?php if($get_FeedBack[0]['hosp_repond']==1){ echo "checked"; } ?>/>Yes</label></div>
													<div class="fl"><label><input type="radio" name="hospresId" value="2" class="rdBtn" <?php if($get_FeedBack[0]['hosp_repond']==2){ echo "checked"; } ?>/>No</label></div></h4></td></tr>
		
		<tr><td class="txtWidth"><h3>Hospital Comments :</h3></td><td><h4><textarea name="hospComment" value="" class="txtArea fr"><?php echo $get_FeedBack[0]['hosp_resp_Comment']; ?></textarea></h4></td></tr>
				
		<tr><td class="txtWidth"><h3>Did they visit the Hospital ? :</h3></td><td><h4> <div class="fl"><label><input type="radio" name="visitId" id="visit_hosp" value="1" class="rdBtn" <?php if($get_FeedBack[0]['hosp_visit_cond']==1){ echo "checked"; } ?>/>Yes</label></div>
		<div class="fl"><label><input type="radio" name="visitId" id="visit_hosp" value="2" class="rdBtn" <?php if($get_FeedBack[0]['hosp_visit_cond']==2){ echo "checked"; } ?>/>No</label></div></td></tr>
		
		<tr><td class="txtWidth"><h3>Which Hospital ? :</h3></td><td><h4> <div class="fl"><select class="slctField" name="slctHosp" id="slctHosp" >
																							<?php if($get_FeedBack[0]['hosp_slct_cond']==1){ ?>
																							<option value="1" selected>Refered</option>
																							<?php } 
																							if($get_FeedBack[0]['hosp_slct_cond']==2){ ?>
																							<option value="2" selected>Others</option>
																							<?php } 
																							if($get_FeedBack[0]['hosp_slct_cond']==0){ ?>
																							<option value="0">Select</option>
																							<option value="1">Refered</option>
																							<option value="2">Others</option>
																							<?php } ?>
																							</select></div></td></tr>		
		<?php if($get_FeedBack[0]['hosp_slct_cond']==1 || empty($get_FeedBack[0]['hosp_slct_cond'])){ ?>
		<tr><td class="txtWidth"><h3>Refered Hospital Name :</h3></td><td><h4> <div class="fl"><label><?php echo $getRefCount[0]['ref_name']; ?></label></div></td></tr>
		<tr><td class="txtWidth"><h3>Hospital Treatment ? : </h3></td><td><h4><div class="fl"><label><input type="radio" name="slctTreatId1" id="Chk_TreatId" value="1" class="rdBtn" <?php if($get_ChatFeedBack[0]['ref_hospServId']==1){ echo "checked"; } ?> disabled="disabled"/>Satisfactory</label></div>
													<div class="fl"><label><input type="radio" name="slctTreatId1" id="Chk_TreatId1" value="2" class="rdBtn" <?php if($get_ChatFeedBack[0]['ref_hospServId']==2){ echo "checked"; } ?> disabled="disabled"/>Neutral</label></div>
													<div class="fl"><label><input type="radio" name="slctTreatId1" id="Chk_TreatId2"  value="3" class="rdBtn" <?php if($get_ChatFeedBack[0]['ref_hospServId']==3){ echo "checked"; } ?> disabled="disabled"/>Not satisfactory</label></div></h4></td></tr>
		<tr><td class="txtWidth"><h3>Add Comment :</h3></td><td><h4> <div class="fl"><textarea name="HospComment1" id="HospComment1" value="" class="txtArea fr" disabled><?php echo $get_ChatFeedBack[0]['chat_note']; ?></textarea></h4></div></td></tr>
	
		<tr><td class="txtWidth"><h3>Refered Hospital Name :</h3></td><td><h4> <div class="fl"><label><?php echo $getRefCount[1]['ref_name']; ?></label></div></td></tr>
		<tr><td class="txtWidth"><h3>Hospital Treatment ? : </h3></td><td><h4><div class="fl"><label><input type="radio" name="slctTreatId2" id="Chk_TreatId3" value="1" class="rdBtn" <?php if($get_ChatFeedBack[1]['ref_hospServId']==1){ echo "checked"; } ?> disabled="disabled"/>Satisfactory</label></div>
													<div class="fl"><label><input type="radio" name="slctTreatId2" id="Chk_TreatId4" value="2" class="rdBtn" <?php if($get_ChatFeedBack[1]['ref_hospServId']==2){ echo "checked"; } ?> disabled="disabled"/>Neutral</label></div>
													<div class="fl"><label><input type="radio" name="slctTreatId2" id="Chk_TreatId5"  value="3" class="rdBtn" <?php if($get_ChatFeedBack[1]['ref_hospServId']==3){ echo "checked"; } ?> disabled="disabled"/>Not satisfactory</label></div></h4></td></tr>
		<tr><td class="txtWidth"><h3>Add Comment :</h3></td><td><h4> <div class="fl"><textarea name="HospComment2" id="HospComment2" value="" class="txtArea fr" disabled><?php echo $get_ChatFeedBack[1]['chat_note']; ?></textarea></h4></div></td></tr>
		
		<?php } if($get_FeedBack[0]['hosp_slct_cond']==2 || empty($get_FeedBack[0]['hosp_slct_cond'])){ ?>
		<tr><td class="txtWidth"><h3>Other Hospital Name :</h3></td><td><h4> <div class="fl"><input type="text" name="txtOther" id="other_name" value="<?php if($get_ChatFeedBack[0]['ref_id']==0){ echo $get_ChatFeedBack[0]['other_ref_name']; } ?>" class="txtfield" disabled /></div></td></tr>
		<tr><td class="txtWidth"><h3>Hospital Treatment ? : </h3></td><td><h4><div class="fl"><label><input type="radio" name="slctTreatId_Other" id="Chk_TreatId_other1" value="1" class="rdBtn" <?php if($get_ChatFeedBack[0]['ref_hospServId']==1 && $get_ChatFeedBack[0]['ref_id']==0){ echo "checked"; } ?> disabled="disabled"/>Satisfactory</label></div>
													<div class="fl"><label><input type="radio" name="slctTreatId_Other" id="Chk_TreatId_other2" value="2" class="rdBtn" <?php if($get_ChatFeedBack[0]['ref_hospServId']==2 && $get_ChatFeedBack[0]['ref_id']==0){ echo "checked"; } ?> disabled="disabled"/>Neutral</label></div>
													<div class="fl"><label><input type="radio" name="slctTreatId_Other" id="Chk_TreatId_other3"  value="3" class="rdBtn" <?php if($get_ChatFeedBack[0]['ref_hospServId']==3 && $get_ChatFeedBack[0]['ref_id']==0){ echo "checked"; } ?> disabled="disabled"/>Not satisfactory</label></div></h4></td></tr>
		<tr><td class="txtWidth"><h3>Add Comment :</h3></td><td><h4> <div class="fl"><textarea name="HospComment_other" id="HospComment_other" value="" class="txtArea fr" disabled><?php if($get_ChatFeedBack[0]['ref_id']==0){ echo $get_ChatFeedBack[0]['chat_note']; } ?></textarea></h4></div></td></tr>
		<?php } ?>
	
		<tr><td class="txtWidth"><h3>Will they tell about our service to their friends and groups ? : </h3></td><td><h4><div class="fl"><label><input type="radio" name="servId" value="1" class="rdBtn" <?php if($get_FeedBack[0]['med_service_id']==1){ echo "checked"; } ?>/>Yes</label></div>
													<div class="fl"><label><input type="radio" name="servId" value="2" class="rdBtn" <?php if($get_FeedBack[0]['med_service_id']==2){ echo "checked"; } ?>/>No</label></div>
													<div class="fl"><label><input type="radio" name="servId" value="3" class="rdBtn" <?php if($get_FeedBack[0]['med_service_id']==3){ echo "checked"; } ?>/>Don't Know</label></div></h4></td></tr>
		
		<tr><td class="txtWidth"><h3> have you sent the message to them on whatsapp or email ? : </h3></td><td><h4><div class="fl"><label><input type="radio" name="msgStatus" value="1" class="rdBtn" <?php if($get_FeedBack[0]['msg_id']==1){ echo "checked"; } ?>/>Yes</label></div>
													<div class="fl"><label><input type="radio" name="msgStatus" value="2" class="rdBtn" <?php if($get_FeedBack[0]['msg_id']==2){ echo "checked"; } ?>/>No</label></div></h4></td></tr>
		
		<tr><td></td><td colspan="2" align="left"><h3><input type="submit" name="cmdSubmit" value="ADD" class="submitBtn fl" /></h3></td></tr>
		</form>
		</table>
		
	</div>	
		
	<!--<div class="Feedback_right fl">
			<h4>Feedback</h4>
			<iframe src="feedback_interaction_page.php" width="460" height="420">
					</iframe>
		</div>
	</div>-->
	
	
  </div>

</div>
</div>






<script>
		$(function() {
			// Clickable Dropdown
			$('.click-nav > ul').toggleClass('no-js js');
			$('.click-nav .js ul').hide();
			$('.click-nav .js').click(function(e) {
				$('.click-nav .js ul').slideToggle(200);
				$('.clicker').toggleClass('active');
				e.stopPropagation();
			});
			$(document).click(function() {
				if ($('.click-nav .js ul').is(':visible')) {
					$('.click-nav .js ul', this).slideUp();
					$('.clicker').removeClass('active');
				}
			});
		});
		</script>
</body>
</html>

