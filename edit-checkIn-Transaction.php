<?php ob_start();
 error_reporting(0);
 session_start(); 

$admin_id = $_SESSION['admin_id'];
$Company_id=$_SESSION['comp_id'];
$Cur_Date=date("d-m-Y");
if(empty($admin_id)){
header("Location:index.php");
}
require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
ob_start();



if(isset($_POST['cmdSubmit'])){
	
	
	
	$visitDate = date('Y-m-d',strtotime($_POST['datepicker']));
	$hospId = addslashes($_POST['txtHospId']);
	
	
		
	$arrFields = array();
	$arrValues = array();
		
	
		$arrFields[] = 'Visiting_date';
		$arrValues[] = $visitDate;
		$arrFields[] = 'Hosp_patient_Id';
		$arrValues[] = $hospId;
		
		$patientRef=$objQuery->mysqlUpdate('appointment_transaction_detail',$arrFields,$arrValues,"appoint_trans_id='".$_POST['Pat_Id']."'");
		
		$getInfo1 = $objQuery->mysqlSelect("*","appointment_transaction_detail","appoint_trans_id='".$_POST['Pat_Id']."'" ,"","","","");	
		
	
		//UPDATE REMOTE SERVER
					$url_page = 'get_checkin_editval.php';
					
					$url = HOST_HEALTH_URL."CRM/";
					$url .= rawurlencode($url_page);
					$post .= "&appointtransid=" . urlencode($getInfo1[0]['appoint_trans_id']);
					$post .= "&visitdate=" . urlencode($getInfo1[0]['Visiting_date']);
					$post .= "&hospid=" . urlencode($getInfo1[0]['Hosp_patient_Id']);
																
					
					$ch = curl_init (); // setup a curl
					
					curl_setopt($ch, CURLOPT_URL, $url); // set url to send to
					curl_setopt($ch, CURLOPT_POST, true);
					//curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: multipart/form-data"));
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // return data reather than echo
					
						
					$output = curl_exec ($ch);
					echo $output;
					// echo "output".$output;
					
					curl_close ( $ch );
					
	$sucessMessage="Updated Successfully";
}
$getInfo = $objQuery->mysqlSelect("*","appointment_transaction_detail","appoint_trans_id='".$_GET['pattransid']."'" ,"","","","");	
$getNewVisitInfo = $objQuery->mysqlSelect("*","new_hospvisitor_details","Transaction_id='".$_GET['pattransid']."'" ,"","","","");
$getDoc= $objQuery->mysqlSelect("*","hosp_tab as a left join doctor_hosp as b on a.hosp_id=b.hosp_id left join referal as c on c.ref_id=b.doc_id","b.doc_id='".$getInfo[0]['pref_doc']."'" ,"","","","");
$getSpec = $objQuery->mysqlSelect("*","specialization","spec_id='".$getInfo[0]['department']."'","","","","");
$getTime= $objQuery->mysqlSelect("*","timings","Timing_id='".$getInfo[0]['Visiting_time']."'","","","","");
?>

<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8" />
<title>Medisense CRM</title>
<?php include_once('support_file.php'); ?>

</head>

<body>
  <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>  
   <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>  
   <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script> 

   <script type="text/javascript">
       $(function() {
               $("#datepicker").datepicker({ dateFormat: "dd-mm-yy" }).val()
       });
   </script> 


<?php include_once('header.php'); ?>

<div class="content">
<div class="clearall">
 <div class="wrapper">
 <script language="javaScript" src="js/validation.js"></script>
 
  <div class="maincont_chkin clearfix">	
  <div class="rightNav fr">
		<a href="checkIn-Transaction.php?start=<?php echo $_GET['start']; ?>"><< Back</a>
	</div>
   <form method="post" name="frmPatient" action="">
  	<input type="hidden" name="Pat_Id" value="<?php echo stripslashes($getInfo[0]['appoint_trans_id']);?>" />
	<div class="chkineditform fl">	
	<?php
	if(isset($sucessMessage)){ ?>
	<span class="success"><?php echo $sucessMessage; ?></span>
	<?php	}
	if(isset($errorMessage)){ ?>
						<span class="error"><?php echo $errorMessage; ?></span>
	<?php } 
	$Fullname=explode(" ",$getInfo[0]['patient_name']);
	$Firstname=$Fullname[0]; 
	$Lastname=$Fullname[1];
	?>
	
		<h2>CheckIn Patient Info</h2>
		<form name="frmPatient" method="POST" action="">
		<h3>Visit Date :<input type="text" id="datepicker" value="<?php echo date('d-m-Y',strtotime($getInfo[0]['Visiting_date'])); ?>" name="datepicker" class="txtfield fr" /></h3>
		<h3>Patient Email : <input type="text" value="<?php echo $getInfo[0]['Email_address']; ?>" name="txtDate" class="txtfield fr" disabled /></h3>
		<h3>First Name :<input type="text" name="txtname" value="<?php echo $Firstname; ?>" class="txtfield fr" disabled /></h3>
		<h3>Last Name :<input type="text" name="txtAge" value="<?php echo $Lastname; ?>" class="txtfield fr" disabled /></h3>
		<h3>Mobile No. :<input type="text" name="txtMail" value="<?php echo $getInfo[0]['Mobile_no']; ?>" class="txtfield fr" disabled /></h3>
		<h3>Father Name :<input type="text" name="txtname" value="<?php echo $getNewVisitInfo[0]['Father_name']; ?>" class="txtfield fr" disabled /></h3>
		<h3>Mother Name :<input type="text" name="txtname" value="<?php echo $getNewVisitInfo[0]['Mother_name']; ?>" class="txtfield fr" disabled /></h3>
		<h3>Spouse Name:<input type="text" name="txtname" value="<?php echo $getNewVisitInfo[0]['Husband_wife_name']; ?>" class="txtfield fr" disabled /></h3>
		<h3>Age :<input type="text" name="txtname" value="<?php echo $getNewVisitInfo[0]['pat_age']; ?>" class="txtfield fr" disabled /></h3>
		
		<h3>Gender :<select name="slctGen" id="slctGen" class="slctField fr" disabled />
					<?php if($getNewVisitInfo[0]['pat_gen']=="Male"){ ?>
					<option value="Male" selected>Male</option>
					<option value="Female">Female</option>	
					<?php } 
					else if($getNewVisitInfo[0]['pat_gen']=="Female"){?>
					<option value="Male" >Male</option>
					<option value="Female" selected>Female</option>	
					<?php } else {?>
					<option value="">Select Gender</option>
					<option value="Male" >Male</option>
					<option value="Female" >Female</option>	
					<?php } ?> ?>
					</select></h3>
		<h3>City :<input type="text" name="txtAddress" value="<?php echo $getNewVisitInfo[0]['City']; ?>" class="txtfield fr" disabled /></h3>
		<h3>Address :<textarea name="txtNote1" class="txtArea fr"  disabled><?php echo $getNewVisitInfo[0]['Address']." ".$getNewVisitInfo[0]['State']." ".$getNewVisitInfo[0]['Country']; ?></textarea></h3><br><br><br>
		<h3>Religion  :<input type="text" name="txtAddress" value="<?php echo $getNewVisitInfo[0]['Religion']; ?>" class="txtfield fr" disabled /></h3>
		<h3>Occupation   :<input type="text" name="txtAddress" value="<?php echo $getNewVisitInfo[0]['Occupation']; ?>" class="txtfield fr" disabled /></h3>
		<h3>Specialization    :<input type="text" name="txtAddress" value="<?php echo $getSpec[0]['spec_name']; ?>" class="txtfield fr" disabled /></h3>
		<h3>Hospital     :<input type="text" name="txtAddress" value="<?php echo $getDoc[0]['hosp_name']; ?>" class="txtfield fr" disabled /></h3>
		<h3>Slot  :<input type="text" name="txtAddress" value="<?php echo $getSpec[0]['spec_name']." | ".$getTime[0]['Timing']; ?>" class="txtfield fr" disabled /></h3>
		<h3>Type  :<input type="text" name="txtAddress" value="<?php echo $getInfo[0]['visit_status']; ?>" class="txtfield fr" disabled /></h3>
		<h3>Doctor name   :<input type="text" name="txtAddress" value="<?php echo $getDoc[0]['ref_name']; ?>" class="txtfield fr" disabled /></h3>
							
		<h3>Patient Hid :<input type="text" name="txtHospId" value="<?php echo $getInfo[0]['Hosp_patient_Id']; ?>" class="txtfield fr" /></h3>
		<h3>Created Date :<input type="text" name="txtBlood" value="<?php echo date('d-m-Y h:i:s',strtotime($getInfo[0]['Time_stamp'])); ?>" class="txtfield fr" disabled /></h3>
		<h3><input type="submit" name="cmdSubmit" value="UPDATE" class="submitBtn fl" /></h3>
	</div>
	</form>
	
  </div>

</div>
</div>
</div>


<div class="footer">
<div class="clearfix">
   
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

