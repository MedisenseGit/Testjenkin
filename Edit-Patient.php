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
	
	
	
	$txtName = addslashes($_POST['txtname']);
	$txtAge = $_POST['txtAge'];
	$txtMail = $_POST['txtMail'];
	$txtGen = $_POST['slctGen'];
	
	$slctMerital = $_POST['slctMerital'];
	$txtQualification = $_POST['slctQualification'];
	$txtWeight = $_POST['txtWeight'];
	$hyperCond = $_POST['hypTension'];
	$diabetesCond = $_POST['diabetes'];
	$patBlood = addslashes($_POST['txtBlood']);	
	
	$txtContact = addslashes($_POST['txtContact']);	
	$txtProf = addslashes($_POST['txtProf']);
	$slctProfLevel = addslashes($_POST['slctProfLevel']);
	$slctInsState = addslashes($_POST['slctInsState']);
	$txtInsCmpny = addslashes($_POST['txtInsCmpny']);
	$slctLeadType = addslashes($_POST['slctLeadType']);
	
	$txtMob = addslashes($_POST['txtMob']);
	$txtAddress = addslashes($_POST['txtAddress']);
	$txtLoc = addslashes($_POST['txtLoc']);
	$txtCountry = addslashes($_POST['txtCountry']);
	$txtState = addslashes($_POST['txtState']);
	$txtSource = $_POST['slctSource'];
	$SlctDept = $_POST['slctDept'];
	$AttchState = $_POST['attchState'];
	$UrgState = $_POST['urgState'];
	$txtRefId= $_POST['txtref'];
	$txtTreatDoc = addslashes($_POST['txtTreatDoc']);
	$txtTreatHosp = addslashes($_POST['txtTreatHosp']);
	$txtNote1 = addslashes($_POST['txtNote1']);
	$txtNote2 = addslashes($_POST['txtNote2']);
	$txtNote3 = addslashes($_POST['txtNote3']);
	
	$txtPrefHosp = addslashes($_POST['txtPrefHosp']);
	$txtPrefDoc = addslashes($_POST['txtPrefDoc']);
	
	$txtTreatedHosp = addslashes($_POST['txtTreatedHosp']);
	$txtTreatedDoc = addslashes($_POST['txtTreatedDoc']);
	$txtBillAmt = addslashes($_POST['txtBillAmt']);
	
	$arrFields = array();
	$arrValues = array();
		
	
		$arrFields[] = 'patient_name';
		$arrValues[] = $txtName;
		$arrFields[] = 'patient_email';
		$arrValues[] = $txtMail;
		$arrFields[] = 'patient_age';
		$arrValues[] = $txtAge;
		$arrFields[] = 'patient_gen';
		$arrValues[] = $txtGen;
		
		$arrFields[] = 'merital_status';
		$arrValues[] = $slctMerital;
		$arrFields[] = 'qualification';
		$arrValues[] = $txtQualification;
		$arrFields[] = 'weight';
		$arrValues[] = $txtWeight;
		$arrFields[] = 'hyper_cond';
		$arrValues[] = $hyperCond;
		$arrFields[] = 'diabetes_cond';
		$arrValues[] = $diabetesCond;
		$arrFields[] = 'pat_blood';
		$arrValues[] = $patBlood;
		
		$arrFields[] = 'contact_person';
		$arrValues[] = $txtContact;
		
		$arrFields[] = 'profession';
		$arrValues[] = $txtProf;
		$arrFields[] = 'proflevel';
		$arrValues[] = $slctProfLevel;
		$arrFields[] = 'insurance_state';
		$arrValues[] = $slctInsState;
		$arrFields[] = 'insur_cmpny';
		$arrValues[] = $txtInsCmpny;
		$arrFields[] = 'lead_type';
		$arrValues[] = $slctLeadType;
				
		$arrFields[] = 'patient_mob';
		$arrValues[] = $txtMob;
		$arrFields[] = 'patient_addrs';
		$arrValues[] = $txtAddress;
		$arrFields[] = 'patient_loc';
		$arrValues[] = $txtLoc;
		$arrFields[] = 'pat_state';
		$arrValues[] = $txtState;
		$arrFields[] = 'pat_country';
		$arrValues[] = $txtCountry;
		$arrFields[] = 'patient_src';
		$arrValues[] = $txtSource;
		
		$arrFields[] = 'medDept';
		$arrValues[] = $SlctDept;
		
		$arrFields[] = 'currentTreatDoc';
		$arrValues[] = $txtTreatDoc;
		$arrFields[] = 'currentTreatHosp';
		$arrValues[] = $txtTreatHosp;
		
		$arrFields[] = 'attchState';
		$arrValues[] = $AttchState;
		
		$arrFields[] = 'urgentState';
		$arrValues[] = $UrgState;
		$arrFields[] = 'patient_complaint';
		$arrValues[] = $txtNote1;
		$arrFields[] = 'patient_desc';
		$arrValues[] = $txtNote2;
		$arrFields[] = 'pat_query';
		$arrValues[] = $txtNote3;
		
		$arrFields[] = 'pref_hosp';
		$arrValues[] = $txtPrefHosp;
		$arrFields[] = 'pref_doc';
		$arrValues[] = $txtPrefDoc;
		
		$arrFields[] = 'user_id';
		$arrValues[] = $admin_id;
		$arrFields[] = 'company_id';
		$arrValues[] = $Company_id;
		
		$arrFields[] = 'treatedDoc';
		$arrValues[] = $txtTreatedDoc;
		$arrFields[] = 'treatedHosp';
		$arrValues[] = $txtTreatedHosp;
		$arrFields[] = 'totalBillAmt';
		$arrValues[] = $txtBillAmt;

		$patientRef=$objQuery->mysqlUpdate('patient_tab',$arrFields,$arrValues,"patient_id='".$_POST['Pat_Id']."'");
	$sucessMessage="Updated Successfully";
}
$getInfo = $objQuery->mysqlSelect("*","patient_tab","patient_id='".$_GET['pat_id']."'" ,"","","","");	

?>

<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8" />
<title>Medisense CRM</title>
<?php include_once('support_file.php'); ?>

</head>

<body onload="noBack();" onpageshow="if (event.persisted) noBack();" onunload="">
  


<?php include_once('header.php'); ?>

<div class="content">
<div class="clearall">
 <div class="wrapper">
 <script language="javaScript" src="js/validation.js"></script>
 
  <div class="maincont1 clearfix">	
  <div class="rightNav fr">
		<a href="Patient_History.php?<?php if(!empty($_GET['pat_id'])) { ?>pat_id=<?php echo $_GET['pat_id']; } if(!empty($_GET['disp'])) { ?>&disp=<?php echo $_GET['disp']; } if(!empty($_GET['assignId'])) { ?>&assignId=<?php echo $_GET['assignId']; } if(!empty($_GET['refid'])) { ?>&refid=<?php echo $_GET['refid']; } if(!empty($_GET['start'])) { ?>&start=<?php echo $_GET['start']; } ?>"><< Back</a>
	</div>
   <form method="post" name="frmPatient" action="" onsubmit="return createPatient()">
  	<input type="hidden" name="Pat_Id" value="<?php echo stripslashes($getInfo[0]['patient_id']);?>" />
	<div class="Cntform fl">	
	<?php
	if(isset($sucessMessage)){ ?>
	<span class="success"><?php echo $sucessMessage; ?></span>
	<?php	}
	if(isset($errorMessage)){ ?>
						<span class="error"><?php echo $errorMessage; ?></span>
	<?php } ?>
	
		<h2>Add New Patient</h2>
		<form name="frmPatient" method="POST" action="" onsubmit="return createPatient()">
		<h3>Enquiry Date :<input type="text" value="<?php echo date('d-m-Y h:i a',strtotime($getInfo[0]['TImestamp'])); ?>" name="txtDate" class="txtfield fr" disabled /></h3>
		<!--<h3>Follow-Up Date :<input type="text" value="<?php echo date('d-m-Y',strtotime($getInfo[0]['follow_date'])); ?>" name="txtFollowDate" class="txtfield fr"/></h3>-->
		<h3>Lead Type : <select class="slctField fr" name="slctLeadType"  >
			<?php if($getInfo[0]['lead_type']=="Hot"){ ?>
			<option value="Hot" selected>Hot</option>
			<option value="Warm">Warm</option>
			<option value="Cold">Cold</option>
			<?php } else if($getInfo[0]['lead_type']=="Warm"){ ?>
			<option value="Warm" selected>Warm</option>
			<option value="Hot">Hot</option>
			<option value="Cold">Cold</option>
			<?php } else if($getInfo[0]['lead_type']=="Cold"){ ?>
			<option value="Cold" selected>Cold</option>
			<option value="Hot">Hot</option>
			<option value="Warm">Warm</option>
			<?php } else { ?>
			<option value="0">Select</option>
			<option value="Hot">Hot</option>
			<option value="Warm">Warm</option>
			<option value="Cold">Cold</option>
			<?php } ?>
		</select></h3>
		<h3>Patient Name :<input type="text" name="txtname" value="<?php echo $getInfo[0]['patient_name']; ?>" class="txtfield fr"/></h3>
		<h3>Age :<input type="text" name="txtAge" value="<?php echo $getInfo[0]['patient_age']; ?>" class="txtfield fr"/></h3>
		<h3>Email :<input type="text" name="txtMail" value="<?php echo $getInfo[0]['patient_email']; ?>" class="txtfield fr"/></h3>
		<h3>Gender :<select name="slctGen" id="slctGen" class="slctField fr"/>
					<?php if($getInfo[0]['patient_gen']==1){ ?>
					<option value="1" selected>Male</option>
					<option value="2">Female</option>	
					<?php } 
					else if($getInfo[0]['patient_gen']==2){?>
					<option value="1" >Male</option>
					<option value="2" selected>Female</option>	
					<?php } else {?>
					<option value="">Select Gender</option>
					<option value="1" >Male</option>
					<option value="2" >Female</option>	
					<?php } ?> ?>
					</select></h3>
					
		<h3>Merital Status :<select name="slctMerital" id="slctMerital" class="slctField fr"/> 
					<?php if($getInfo[0]['merital_status']=="Married"){ ?>
					<option value="Married" selected>Married</option>
					<option value="Unmarried">Unmarried</option>
					<option value="Widow">Widow</option>
					<?php } else if($getInfo[0]['merital_status']=="Unmarried"){ ?>
					<option value="Married" >Married</option>
					<option value="Unmarried" selected>Unmarried</option>
					<option value="Widow">Widow</option>
					<?php } else if($getInfo[0]['merital_status']=="Widow"){ ?>
					<option value="Married" >Married</option>
					<option value="Unmarried">Unmarried</option>
					<option value="Widow" selected>Widow</option>
					<?php } else { ?>
					<option value="0">Select </option>
					<option value="Married">Married</option>
					<option value="Unmarried">Unmarried</option>
					<option value="Widow">Widow</option>
					<?php } ?>
					</select></h3>
					
		<h3>Weight :<input type="text" name="txtWeight" value="<?php echo $getInfo[0]['weight']; ?>" class="txtfield fr"/></h3>
		<h3>Blood Group :<input type="text" name="txtBlood" value="<?php echo $getInfo[0]['pat_blood']; ?>" class="txtfield fr"/></h3>
		<h3>Qualification :<select name="slctQualification" id="slctQualification" class="slctField fr"/> 
					<?php if($getInfo[0]['qualification']=="Graduate"){ ?>
					<option value="Graduate"selected>Graduate</option>
					<option value="Undergraduate">Undergraduate</option>
					<option value="Post Graduate">Post Graduate</option>
					<?php } else if($getInfo[0]['qualification']=="Undergraduate"){ ?>
					<option value="Graduate">Graduate</option>
					<option value="Undergraduate" selected>Undergraduate</option>
					<option value="Post Graduate">Post Graduate</option>
					<?php } else if($getInfo[0]['qualification']=="Post Graduate"){ ?>
					<option value="Graduate">Graduate</option>
					<option value="Undergraduate" >Undergraduate</option>
					<option value="Post Graduate" selected>Post Graduate</option>
					<?php } else { ?>
					<option value="0">Select </option>
					<option value="Graduate">Graduate</option>
					<option value="Undergraduate" >Undergraduate</option>
					<option value="Post Graduate">Post Graduate</option>
					<?php } ?>
					</select></h3>	
<h3>Blood Pressure ? :<div style="float:right;margin-top:0px; margin-right:50px;"><?php if($getInfo[0]['hyper_cond']==1){ ?> High<input type="radio" name="hypTension" value="1" style="" checked />&nbsp;&nbsp; Normal <input type="radio" name="hypTension" value="2" style="" />&nbsp;&nbsp; Low <input type="radio" name="hypTension" value="3" style="" />
<?php } else if($getInfo[0]['hyper_cond']==2){ ?> High<input type="radio" name="hypTension" value="0" style="" />&nbsp;&nbsp; Normal <input type="radio" name="hypTension" value="1" style="" checked />&nbsp;&nbsp; Low <input type="radio" name="hypTension" value="3" style="" />
<?php } else if($getInfo[0]['hyper_cond']==3){ ?> High<input type="radio" name="hypTension" value="0" style="" />&nbsp;&nbsp; Normal <input type="radio" name="hypTension" value="1" style=""  />&nbsp;&nbsp; Low <input type="radio" name="hypTension" value="3" style="" checked  />
<?php } else { ?> No<input type="radio" name="hypTension" value="0" style="" />&nbsp;&nbsp; Yes <input type="radio" name="hypTension" value="1" style="" checked />
<?php } ?></div></h3>	
<h3>Diabetes ? :<div style="float:right;margin-top:0px; margin-right:150px;"><?php if($getInfo[0]['diabetes_cond']==0){ ?> No <input type="radio" name="diabetes" value="0" style="" checked />&nbsp;&nbsp; Yes <input type="radio" name="diabetes" value="1" style="" />
<?php } else { ?> No<input type="radio" name="diabetes" value="0" style="" />&nbsp;&nbsp; Yes <input type="radio" name="diabetes" value="1" style="" checked />
<?php } ?></div></h3>			
					
		<h3>Decision Maker :<input type="text" name="txtContact" value="<?php echo $getInfo[0]['contact_person']; ?>" class="txtfield fr"/></h3>
		<h3>Profession :<select class="slctField1 mrt30 fr" name="slctProfLevel" >
			<?php if($getInfo[0]['proflevel']=="HIG"){ ?>
			<option value="HIG" selected>HIG</option>
			<option value="MIG">MIG</option>
			<option value="LIG">LIG</option>
			<?php } else if($getInfo[0]['proflevel']=="MIG") { ?>
			<option value="MIG" selected>MIG</option>
			<option value="HIG">HIG</option>
			<option value="LIG">LIG</option>
			<?php } else if($getInfo[0]['proflevel']=="LIG") { ?>
			<option value="LIG" selected>LIG</option>
			<option value="HIG">HIG</option>
			<option value="MIG">MIG</option>
			<?php } else { ?>
			<option value="0">Select</option>
			<option value="HIG">HIG</option>
			<option value="MIG">MIG</option>
			<option value="LIG">LIG</option>
			<?php } ?>
		</select><input type="text" name="txtProf" placeholder="Profession" value="<?php echo $getInfo[0]['profession']; ?>" class="txtfield1 mrt10 fr" /></h3>
		<h3>Insurance status : <input type="text" name="txtInsCmpny" placeholder="Insurance Company" value="<?php echo $getInfo[0]['insur_cmpny']; ?>" class="txtfield1 mrt30 fr" /><select class="slctField1 mrt10 fr" name="slctInsState"  >
			<?php if($getInfo[0]['insurance_state']=="Corporate"){ ?>
			<option value="Corporate" selected>Corporate</option>
			<option value="No insurance">No insurance</option>
			<option value="Govt">Govt</option>
			<?php } else if($getInfo[0]['insurance_state']=="No insurance") { ?>
			<option value="No insurance" selected>No insurance</option>
			<option value="Corporate">Corporate</option>
			<option value="Govt">Govt</option>
			<?php } else if($getInfo[0]['insurance_state']=="Govt") { ?>
			<option value="Govt" selected>Govt</option>
			<option value="Corporate">Corporate</option>
			<option value="No insurance">No insurance</option>
			<?php } else { ?>
			<option value="0">Select</option>
			<option value="Corporate">Corporate</option>
			<option value="No insurance">No insurance</option>
			<option value="Govt">Govt</option>
			<?php } ?>
		</select></h3>
		<h3>Mobile No. :<input type="text" name="txtMob" value="<?php echo $getInfo[0]['patient_mob']; ?>" class="txtfield fr"/></h3>
		<h3>Address :<input type="text" name="txtAddress" value="<?php echo $getInfo[0]['patient_addrs']; ?>" class="txtfield fr"/></h3>
		<h3>Location :<input type="text"  value="<?php echo $getInfo[0]['patient_loc']; ?>" name="txtLoc" class="txtfield fr"/></h3>
		<h3>State <?php if($getInfo[0]['pat_state']==""){ ?>
		<select name="txtState" id="txtState" class="slctField fr"/>
			<option value="">Select</option>
			<option value='Others'>Others</option>
			<option value='Andaman and Nicobar Islands'>Andaman and Nicobar Islands</option>
			<option value='Andhra Pradesh'>Andhra Pradesh</option>
			<option value='Arunachal Pradesh'>Arunachal Pradesh</option>
			<option value='Assam'>Assam</option>
			<option value='Bihar'>Bihar</option>
			<option value='Chandigarh'>Chandigarh</option>
			<option value='Chhattisgarh'>Chhattisgarh</option>
			<option value='Dadra and Nagar Haveli'>Dadra and Nagar Haveli</option>
			<option value='Daman and Diu'>Daman and Diu</option>
			<option value='Delhi'>Delhi</option>
			<option value='Goa'>Goa</option>
			<option value='Gujarat'>Gujarat</option>
			<option value='Haryana'>Haryana</option>
			<option value='Himachal Pradesh'>Himachal Pradesh</option>
			<option value='Jammu and Kashmir'>Jammu and Kashmir</option>
			<option value='Jharkhand'>Jharkhand</option>
			<option value='Karnataka'>Karnataka</option>
			<option value='Kerala'>Kerala</option>
			<option value='Lakshadweep'>Lakshadweep</option>
			<option value='Madhya Pradesh'>Madhya Pradesh</option>
			<option value='Maharashtra'>Maharashtra</option>
			<option value='Manipur'>Manipur</option>
			<option value='Meghalaya'>Meghalaya</option>
			<option value='Mizoram'>Mizoram</option>
			<option value='Nagaland'>Nagaland</option>
			<option value='Odisha'>Odisha</option>
			<option value='Puducherry'>Puducherry</option>
			<option value='Punjab'>Punjab</option>
			<option value='Rajasthan'>Rajasthan</option>
			<option value='Sikkim'>Sikkim</option>
			<option value='Tamil Nadu'>Tamil Nadu</option>
			<option value='Telengana'>Telengana</option>
			<option value='Tripura'>Tripura</option>
			<option value='Uttar Pradesh'>Uttar Pradesh</option>
			<option value='Uttarakhand'>Uttarakhand</option>
			<option value='West Bengal'>West Bengal</option>
			</select>
		<?php } else { 
		?>
		<select name="txtState" id="txtState" class="slctField fr"/>
			<option value="<?php echo $getInfo[0]['pat_state']; ?>" selected><?php echo $getInfo[0]['pat_state']; ?></option>
			<option value='Others'>Others</option>
			<option value='Andaman and Nicobar Islands'>Andaman and Nicobar Islands</option>
			<option value='Andhra Pradesh'>Andhra Pradesh</option>
			<option value='Arunachal Pradesh'>Arunachal Pradesh</option>
			<option value='Assam'>Assam</option>
			<option value='Bihar'>Bihar</option>
			<option value='Chandigarh'>Chandigarh</option>
			<option value='Chhattisgarh'>Chhattisgarh</option>
			<option value='Dadra and Nagar Haveli'>Dadra and Nagar Haveli</option>
			<option value='Daman and Diu'>Daman and Diu</option>
			<option value='Delhi'>Delhi</option>
			<option value='Goa'>Goa</option>
			<option value='Gujarat'>Gujarat</option>
			<option value='Haryana'>Haryana</option>
			<option value='Himachal Pradesh'>Himachal Pradesh</option>
			<option value='Jammu and Kashmir'>Jammu and Kashmir</option>
			<option value='Jharkhand'>Jharkhand</option>
			<option value='Karnataka'>Karnataka</option>
			<option value='Kerala'>Kerala</option>
			<option value='Lakshadweep'>Lakshadweep</option>
			<option value='Madhya Pradesh'>Madhya Pradesh</option>
			<option value='Maharashtra'>Maharashtra</option>
			<option value='Manipur'>Manipur</option>
			<option value='Meghalaya'>Meghalaya</option>
			<option value='Mizoram'>Mizoram</option>
			<option value='Nagaland'>Nagaland</option>
			<option value='Odisha'>Odisha</option>
			<option value='Puducherry'>Puducherry</option>
			<option value='Punjab'>Punjab</option>
			<option value='Rajasthan'>Rajasthan</option>
			<option value='Sikkim'>Sikkim</option>
			<option value='Tamil Nadu'>Tamil Nadu</option>
			<option value='Telengana'>Telengana</option>
			<option value='Tripura'>Tripura</option>
			<option value='Uttar Pradesh'>Uttar Pradesh</option>
			<option value='Uttarakhand'>Uttarakhand</option>
			<option value='West Bengal'>West Bengal</option>
			</select>
		<?php } ?>
		</h3>
		<h3>Country 
		<?php if($getInfo[0]['pat_country']==""){ ?>
		<select name="txtCountry" id="txtCountry" class="slctField fr"/>
			<option value="Afghanistan">Afghanistan</option>
			<option value="Albania">Albania</option>
			<option value="Algeria">Algeria</option>
			<option value="American Samoa">American Samoa</option>
			<option value="Andorra">Andorra</option>
			<option value="Angola">Angola</option>
			<option value="Anguilla">Anguilla</option>
			<option value="Antartica">Antarctica</option>
			<option value="Antigua and Barbuda">Antigua and Barbuda</option>
			<option value="Argentina">Argentina</option>
			<option value="Armenia">Armenia</option>
			<option value="Aruba">Aruba</option>
			<option value="Australia">Australia</option>
			<option value="Austria">Austria</option>
			<option value="Azerbaijan">Azerbaijan</option>
			<option value="Bahamas">Bahamas</option>
			<option value="Bahrain">Bahrain</option>
			<option value="Bangladesh">Bangladesh</option>
			<option value="Barbados">Barbados</option>
			<option value="Belarus">Belarus</option>
			<option value="Belgium">Belgium</option>
			<option value="Belize">Belize</option>
			<option value="Benin">Benin</option>
			<option value="Bermuda">Bermuda</option>
			<option value="Bhutan">Bhutan</option>
			<option value="Bolivia">Bolivia</option>
			<option value="Bosnia and Herzegowina">Bosnia and Herzegowina</option>
			<option value="Botswana">Botswana</option>
			<option value="Bouvet Island">Bouvet Island</option>
			<option value="Brazil">Brazil</option>
			<option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
			<option value="Brunei Darussalam">Brunei Darussalam</option>
			<option value="Bulgaria">Bulgaria</option>
			<option value="Burkina Faso">Burkina Faso</option>
			<option value="Burundi">Burundi</option>
			<option value="Cambodia">Cambodia</option>
			<option value="Cameroon">Cameroon</option>
			<option value="Canada">Canada</option>
			<option value="Cape Verde">Cape Verde</option>
			<option value="Cayman Islands">Cayman Islands</option>
			<option value="Central African Republic">Central African Republic</option>
			<option value="Chad">Chad</option>
			<option value="Chile">Chile</option>
			<option value="China">China</option>
			<option value="Christmas Island">Christmas Island</option>
			<option value="Cocos Islands">Cocos (Keeling) Islands</option>
			<option value="Colombia">Colombia</option>
			<option value="Comoros">Comoros</option>
			<option value="Congo">Congo</option>
			<option value="Congo">Congo, the Democratic Republic of the</option>
			<option value="Cook Islands">Cook Islands</option>
			<option value="Costa Rica">Costa Rica</option>
			<option value="Cota D'Ivoire">Cote d'Ivoire</option>
			<option value="Croatia">Croatia (Hrvatska)</option>
			<option value="Cuba">Cuba</option>
			<option value="Cyprus">Cyprus</option>
			<option value="Czech Republic">Czech Republic</option>
			<option value="Denmark">Denmark</option>
			<option value="Djibouti">Djibouti</option>
			<option value="Dominica">Dominica</option>
			<option value="Dominican Republic">Dominican Republic</option>
			<option value="East Timor">East Timor</option>
			<option value="Ecuador">Ecuador</option>
			<option value="Egypt">Egypt</option>
			<option value="El Salvador">El Salvador</option>
			<option value="Equatorial Guinea">Equatorial Guinea</option>
			<option value="Eritrea">Eritrea</option>
			<option value="Estonia">Estonia</option>
			<option value="Ethiopia">Ethiopia</option>
			<option value="Falkland Islands">Falkland Islands (Malvinas)</option>
			<option value="Faroe Islands">Faroe Islands</option>
			<option value="Fiji">Fiji</option>
			<option value="Finland">Finland</option>
			<option value="France">France</option>
			<option value="France Metropolitan">France, Metropolitan</option>
			<option value="French Guiana">French Guiana</option>
			<option value="French Polynesia">French Polynesia</option>
			<option value="French Southern Territories">French Southern Territories</option>
			<option value="Gabon">Gabon</option>
			<option value="Gambia">Gambia</option>
			<option value="Georgia">Georgia</option>
			<option value="Germany">Germany</option>
			<option value="Ghana">Ghana</option>
			<option value="Gibraltar">Gibraltar</option>
			<option value="Greece">Greece</option>
			<option value="Greenland">Greenland</option>
			<option value="Grenada">Grenada</option>
			<option value="Guadeloupe">Guadeloupe</option>
			<option value="Guam">Guam</option>
			<option value="Guatemala">Guatemala</option>
			<option value="Guinea">Guinea</option>
			<option value="Guinea-Bissau">Guinea-Bissau</option>
			<option value="Guyana">Guyana</option>
			<option value="Haiti">Haiti</option>
			<option value="Heard and McDonald Islands">Heard and Mc Donald Islands</option>
			<option value="Holy See">Holy See (Vatican City State)</option>
			<option value="Honduras">Honduras</option>
			<option value="Hong Kong">Hong Kong</option>
			<option value="Hungary">Hungary</option>
			<option value="Iceland">Iceland</option>
			<option value="India" selected>India</option>
			<option value="Indonesia">Indonesia</option>
			<option value="Iran">Iran (Islamic Republic of)</option>
			<option value="Iraq">Iraq</option>
			<option value="Ireland">Ireland</option>
			<option value="Israel">Israel</option>
			<option value="Italy">Italy</option>
			<option value="Jamaica">Jamaica</option>
			<option value="Japan">Japan</option>
			<option value="Jordan">Jordan</option>
			<option value="Kazakhstan">Kazakhstan</option>
			<option value="Kenya">Kenya</option>
			<option value="Kiribati">Kiribati</option>
			<option value="Democratic People's Republic of Korea">Korea, Democratic People's Republic of</option>
			<option value="Korea">Korea, Republic of</option>
			<option value="Kuwait">Kuwait</option>
			<option value="Kyrgyzstan">Kyrgyzstan</option>
			<option value="Lao">Lao People's Democratic Republic</option>
			<option value="Latvia">Latvia</option>
			<option value="Lebanon" >Lebanon</option>
			<option value="Lesotho">Lesotho</option>
			<option value="Liberia">Liberia</option>
			<option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
			<option value="Liechtenstein">Liechtenstein</option>
			<option value="Lithuania">Lithuania</option>
			<option value="Luxembourg">Luxembourg</option>
			<option value="Macau">Macau</option>
			<option value="Macedonia">Macedonia, The Former Yugoslav Republic of</option>
			<option value="Madagascar">Madagascar</option>
			<option value="Malawi">Malawi</option>
			<option value="Malaysia">Malaysia</option>
			<option value="Maldives">Maldives</option>
			<option value="Mali">Mali</option>
			<option value="Malta">Malta</option>
			<option value="Marshall Islands">Marshall Islands</option>
			<option value="Martinique">Martinique</option>
			<option value="Mauritania">Mauritania</option>
			<option value="Mauritius">Mauritius</option>
			<option value="Mayotte">Mayotte</option>
			<option value="Mexico">Mexico</option>
			<option value="Micronesia">Micronesia, Federated States of</option>
			<option value="Moldova">Moldova, Republic of</option>
			<option value="Monaco">Monaco</option>
			<option value="Mongolia">Mongolia</option>
			<option value="Montserrat">Montserrat</option>
			<option value="Morocco">Morocco</option>
			<option value="Mozambique">Mozambique</option>
			<option value="Myanmar">Myanmar</option>
			<option value="Namibia">Namibia</option>
			<option value="Nauru">Nauru</option>
			<option value="Nepal">Nepal</option>
			<option value="Netherlands">Netherlands</option>
			<option value="Netherlands Antilles">Netherlands Antilles</option>
			<option value="New Caledonia">New Caledonia</option>
			<option value="New Zealand">New Zealand</option>
			<option value="Nicaragua">Nicaragua</option>
			<option value="Niger">Niger</option>
			<option value="Nigeria">Nigeria</option>
			<option value="Niue">Niue</option>
			<option value="Norfolk Island">Norfolk Island</option>
			<option value="Northern Mariana Islands">Northern Mariana Islands</option>
			<option value="Norway">Norway</option>
			<option value="Oman">Oman</option>
			<option value="Pakistan">Pakistan</option>
			<option value="Palau">Palau</option>
			<option value="Panama">Panama</option>
			<option value="Papua New Guinea">Papua New Guinea</option>
			<option value="Paraguay">Paraguay</option>
			<option value="Peru">Peru</option>
			<option value="Philippines">Philippines</option>
			<option value="Pitcairn">Pitcairn</option>
			<option value="Poland">Poland</option>
			<option value="Portugal">Portugal</option>
			<option value="Puerto Rico">Puerto Rico</option>
			<option value="Qatar">Qatar</option>
			<option value="Reunion">Reunion</option>
			<option value="Romania">Romania</option>
			<option value="Russia">Russian Federation</option>
			<option value="Rwanda">Rwanda</option>
			<option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option> 
			<option value="Saint LUCIA">Saint LUCIA</option>
			<option value="Saint Vincent">Saint Vincent and the Grenadines</option>
			<option value="Samoa">Samoa</option>
			<option value="San Marino">San Marino</option>
			<option value="Sao Tome and Principe">Sao Tome and Principe</option> 
			<option value="Saudi Arabia">Saudi Arabia</option>
			<option value="Senegal">Senegal</option>
			<option value="Seychelles">Seychelles</option>
			<option value="Sierra">Sierra Leone</option>
			<option value="Singapore">Singapore</option>
			<option value="Slovakia">Slovakia (Slovak Republic)</option>
			<option value="Slovenia">Slovenia</option>
			<option value="Solomon Islands">Solomon Islands</option>
			<option value="Somalia">Somalia</option>
			<option value="South Africa">South Africa</option>
			<option value="South Georgia">South Georgia and the South Sandwich Islands</option>
			<option value="Span">Spain</option>
			<option value="SriLanka">Sri Lanka</option>
			<option value="St. Helena">St. Helena</option>
			<option value="St. Pierre and Miguelon">St. Pierre and Miquelon</option>
			<option value="Sudan">Sudan</option>
			<option value="Suriname">Suriname</option>
			<option value="Svalbard">Svalbard and Jan Mayen Islands</option>
			<option value="Swaziland">Swaziland</option>
			<option value="Sweden">Sweden</option>
			<option value="Switzerland">Switzerland</option>
			<option value="Syria">Syrian Arab Republic</option>
			<option value="Taiwan">Taiwan, Province of China</option>
			<option value="Tajikistan">Tajikistan</option>
			<option value="Tanzania">Tanzania, United Republic of</option>
			<option value="Thailand">Thailand</option>
			<option value="Togo">Togo</option>
			<option value="Tokelau">Tokelau</option>
			<option value="Tonga">Tonga</option>
			<option value="Trinidad and Tobago">Trinidad and Tobago</option>
			<option value="Tunisia">Tunisia</option>
			<option value="Turkey">Turkey</option>
			<option value="Turkmenistan">Turkmenistan</option>
			<option value="Turks and Caicos">Turks and Caicos Islands</option>
			<option value="Tuvalu">Tuvalu</option>
			<option value="Uganda">Uganda</option>
			<option value="Ukraine">Ukraine</option>
			<option value="United Arab Emirates">United Arab Emirates</option>
			<option value="United Kingdom">United Kingdom</option>
			<option value="United States">United States</option>
			<option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
			<option value="Uruguay">Uruguay</option>
			<option value="Uzbekistan">Uzbekistan</option>
			<option value="Vanuatu">Vanuatu</option>
			<option value="Venezuela">Venezuela</option>
			<option value="Vietnam">Viet Nam</option>
			<option value="Virgin Islands (British)">Virgin Islands (British)</option>
			<option value="Virgin Islands (U.S)">Virgin Islands (U.S.)</option>
			<option value="Wallis and Futana Islands">Wallis and Futuna Islands</option>
			<option value="Western Sahara">Western Sahara</option>
			<option value="Yemen">Yemen</option>
			<option value="Yugoslavia">Yugoslavia</option>
			<option value="Zambia">Zambia</option>
			<option value="Zimbabwe">Zimbabwe</option>
		</select>
		<?php } else { ?>
		<select name="txtCountry" id="txtCountry" class="slctField fr"/>
			<option value="<?php echo $getInfo[0]['pat_country']; ?>" selected><?php echo $getInfo[0]['pat_country']; ?></option>
			<option value="Afghanistan">Afghanistan</option>
			<option value="Albania">Albania</option>
			<option value="Algeria">Algeria</option>
			<option value="American Samoa">American Samoa</option>
			<option value="Andorra">Andorra</option>
			<option value="Angola">Angola</option>
			<option value="Anguilla">Anguilla</option>
			<option value="Antartica">Antarctica</option>
			<option value="Antigua and Barbuda">Antigua and Barbuda</option>
			<option value="Argentina">Argentina</option>
			<option value="Armenia">Armenia</option>
			<option value="Aruba">Aruba</option>
			<option value="Australia">Australia</option>
			<option value="Austria">Austria</option>
			<option value="Azerbaijan">Azerbaijan</option>
			<option value="Bahamas">Bahamas</option>
			<option value="Bahrain">Bahrain</option>
			<option value="Bangladesh">Bangladesh</option>
			<option value="Barbados">Barbados</option>
			<option value="Belarus">Belarus</option>
			<option value="Belgium">Belgium</option>
			<option value="Belize">Belize</option>
			<option value="Benin">Benin</option>
			<option value="Bermuda">Bermuda</option>
			<option value="Bhutan">Bhutan</option>
			<option value="Bolivia">Bolivia</option>
			<option value="Bosnia and Herzegowina">Bosnia and Herzegowina</option>
			<option value="Botswana">Botswana</option>
			<option value="Bouvet Island">Bouvet Island</option>
			<option value="Brazil">Brazil</option>
			<option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
			<option value="Brunei Darussalam">Brunei Darussalam</option>
			<option value="Bulgaria">Bulgaria</option>
			<option value="Burkina Faso">Burkina Faso</option>
			<option value="Burundi">Burundi</option>
			<option value="Cambodia">Cambodia</option>
			<option value="Cameroon">Cameroon</option>
			<option value="Canada">Canada</option>
			<option value="Cape Verde">Cape Verde</option>
			<option value="Cayman Islands">Cayman Islands</option>
			<option value="Central African Republic">Central African Republic</option>
			<option value="Chad">Chad</option>
			<option value="Chile">Chile</option>
			<option value="China">China</option>
			<option value="Christmas Island">Christmas Island</option>
			<option value="Cocos Islands">Cocos (Keeling) Islands</option>
			<option value="Colombia">Colombia</option>
			<option value="Comoros">Comoros</option>
			<option value="Congo">Congo</option>
			<option value="Congo">Congo, the Democratic Republic of the</option>
			<option value="Cook Islands">Cook Islands</option>
			<option value="Costa Rica">Costa Rica</option>
			<option value="Cota D'Ivoire">Cote d'Ivoire</option>
			<option value="Croatia">Croatia (Hrvatska)</option>
			<option value="Cuba">Cuba</option>
			<option value="Cyprus">Cyprus</option>
			<option value="Czech Republic">Czech Republic</option>
			<option value="Denmark">Denmark</option>
			<option value="Djibouti">Djibouti</option>
			<option value="Dominica">Dominica</option>
			<option value="Dominican Republic">Dominican Republic</option>
			<option value="East Timor">East Timor</option>
			<option value="Ecuador">Ecuador</option>
			<option value="Egypt">Egypt</option>
			<option value="El Salvador">El Salvador</option>
			<option value="Equatorial Guinea">Equatorial Guinea</option>
			<option value="Eritrea">Eritrea</option>
			<option value="Estonia">Estonia</option>
			<option value="Ethiopia">Ethiopia</option>
			<option value="Falkland Islands">Falkland Islands (Malvinas)</option>
			<option value="Faroe Islands">Faroe Islands</option>
			<option value="Fiji">Fiji</option>
			<option value="Finland">Finland</option>
			<option value="France">France</option>
			<option value="France Metropolitan">France, Metropolitan</option>
			<option value="French Guiana">French Guiana</option>
			<option value="French Polynesia">French Polynesia</option>
			<option value="French Southern Territories">French Southern Territories</option>
			<option value="Gabon">Gabon</option>
			<option value="Gambia">Gambia</option>
			<option value="Georgia">Georgia</option>
			<option value="Germany">Germany</option>
			<option value="Ghana">Ghana</option>
			<option value="Gibraltar">Gibraltar</option>
			<option value="Greece">Greece</option>
			<option value="Greenland">Greenland</option>
			<option value="Grenada">Grenada</option>
			<option value="Guadeloupe">Guadeloupe</option>
			<option value="Guam">Guam</option>
			<option value="Guatemala">Guatemala</option>
			<option value="Guinea">Guinea</option>
			<option value="Guinea-Bissau">Guinea-Bissau</option>
			<option value="Guyana">Guyana</option>
			<option value="Haiti">Haiti</option>
			<option value="Heard and McDonald Islands">Heard and Mc Donald Islands</option>
			<option value="Holy See">Holy See (Vatican City State)</option>
			<option value="Honduras">Honduras</option>
			<option value="Hong Kong">Hong Kong</option>
			<option value="Hungary">Hungary</option>
			<option value="Iceland">Iceland</option>
			<option value="India">India</option>
			<option value="Indonesia">Indonesia</option>
			<option value="Iran">Iran (Islamic Republic of)</option>
			<option value="Iraq">Iraq</option>
			<option value="Ireland">Ireland</option>
			<option value="Israel">Israel</option>
			<option value="Italy">Italy</option>
			<option value="Jamaica">Jamaica</option>
			<option value="Japan">Japan</option>
			<option value="Jordan">Jordan</option>
			<option value="Kazakhstan">Kazakhstan</option>
			<option value="Kenya">Kenya</option>
			<option value="Kiribati">Kiribati</option>
			<option value="Democratic People's Republic of Korea">Korea, Democratic People's Republic of</option>
			<option value="Korea">Korea, Republic of</option>
			<option value="Kuwait">Kuwait</option>
			<option value="Kyrgyzstan">Kyrgyzstan</option>
			<option value="Lao">Lao People's Democratic Republic</option>
			<option value="Latvia">Latvia</option>
			<option value="Lebanon" >Lebanon</option>
			<option value="Lesotho">Lesotho</option>
			<option value="Liberia">Liberia</option>
			<option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
			<option value="Liechtenstein">Liechtenstein</option>
			<option value="Lithuania">Lithuania</option>
			<option value="Luxembourg">Luxembourg</option>
			<option value="Macau">Macau</option>
			<option value="Macedonia">Macedonia, The Former Yugoslav Republic of</option>
			<option value="Madagascar">Madagascar</option>
			<option value="Malawi">Malawi</option>
			<option value="Malaysia">Malaysia</option>
			<option value="Maldives">Maldives</option>
			<option value="Mali">Mali</option>
			<option value="Malta">Malta</option>
			<option value="Marshall Islands">Marshall Islands</option>
			<option value="Martinique">Martinique</option>
			<option value="Mauritania">Mauritania</option>
			<option value="Mauritius">Mauritius</option>
			<option value="Mayotte">Mayotte</option>
			<option value="Mexico">Mexico</option>
			<option value="Micronesia">Micronesia, Federated States of</option>
			<option value="Moldova">Moldova, Republic of</option>
			<option value="Monaco">Monaco</option>
			<option value="Mongolia">Mongolia</option>
			<option value="Montserrat">Montserrat</option>
			<option value="Morocco">Morocco</option>
			<option value="Mozambique">Mozambique</option>
			<option value="Myanmar">Myanmar</option>
			<option value="Namibia">Namibia</option>
			<option value="Nauru">Nauru</option>
			<option value="Nepal">Nepal</option>
			<option value="Netherlands">Netherlands</option>
			<option value="Netherlands Antilles">Netherlands Antilles</option>
			<option value="New Caledonia">New Caledonia</option>
			<option value="New Zealand">New Zealand</option>
			<option value="Nicaragua">Nicaragua</option>
			<option value="Niger">Niger</option>
			<option value="Nigeria">Nigeria</option>
			<option value="Niue">Niue</option>
			<option value="Norfolk Island">Norfolk Island</option>
			<option value="Northern Mariana Islands">Northern Mariana Islands</option>
			<option value="Norway">Norway</option>
			<option value="Oman">Oman</option>
			<option value="Pakistan">Pakistan</option>
			<option value="Palau">Palau</option>
			<option value="Panama">Panama</option>
			<option value="Papua New Guinea">Papua New Guinea</option>
			<option value="Paraguay">Paraguay</option>
			<option value="Peru">Peru</option>
			<option value="Philippines">Philippines</option>
			<option value="Pitcairn">Pitcairn</option>
			<option value="Poland">Poland</option>
			<option value="Portugal">Portugal</option>
			<option value="Puerto Rico">Puerto Rico</option>
			<option value="Qatar">Qatar</option>
			<option value="Reunion">Reunion</option>
			<option value="Romania">Romania</option>
			<option value="Russia">Russian Federation</option>
			<option value="Rwanda">Rwanda</option>
			<option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option> 
			<option value="Saint LUCIA">Saint LUCIA</option>
			<option value="Saint Vincent">Saint Vincent and the Grenadines</option>
			<option value="Samoa">Samoa</option>
			<option value="San Marino">San Marino</option>
			<option value="Sao Tome and Principe">Sao Tome and Principe</option> 
			<option value="Saudi Arabia">Saudi Arabia</option>
			<option value="Senegal">Senegal</option>
			<option value="Seychelles">Seychelles</option>
			<option value="Sierra">Sierra Leone</option>
			<option value="Singapore">Singapore</option>
			<option value="Slovakia">Slovakia (Slovak Republic)</option>
			<option value="Slovenia">Slovenia</option>
			<option value="Solomon Islands">Solomon Islands</option>
			<option value="Somalia">Somalia</option>
			<option value="South Africa">South Africa</option>
			<option value="South Georgia">South Georgia and the South Sandwich Islands</option>
			<option value="Span">Spain</option>
			<option value="SriLanka">Sri Lanka</option>
			<option value="St. Helena">St. Helena</option>
			<option value="St. Pierre and Miguelon">St. Pierre and Miquelon</option>
			<option value="Sudan">Sudan</option>
			<option value="Suriname">Suriname</option>
			<option value="Svalbard">Svalbard and Jan Mayen Islands</option>
			<option value="Swaziland">Swaziland</option>
			<option value="Sweden">Sweden</option>
			<option value="Switzerland">Switzerland</option>
			<option value="Syria">Syrian Arab Republic</option>
			<option value="Taiwan">Taiwan, Province of China</option>
			<option value="Tajikistan">Tajikistan</option>
			<option value="Tanzania">Tanzania, United Republic of</option>
			<option value="Thailand">Thailand</option>
			<option value="Togo">Togo</option>
			<option value="Tokelau">Tokelau</option>
			<option value="Tonga">Tonga</option>
			<option value="Trinidad and Tobago">Trinidad and Tobago</option>
			<option value="Tunisia">Tunisia</option>
			<option value="Turkey">Turkey</option>
			<option value="Turkmenistan">Turkmenistan</option>
			<option value="Turks and Caicos">Turks and Caicos Islands</option>
			<option value="Tuvalu">Tuvalu</option>
			<option value="Uganda">Uganda</option>
			<option value="Ukraine">Ukraine</option>
			<option value="United Arab Emirates">United Arab Emirates</option>
			<option value="United Kingdom">United Kingdom</option>
			<option value="United States">United States</option>
			<option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
			<option value="Uruguay">Uruguay</option>
			<option value="Uzbekistan">Uzbekistan</option>
			<option value="Vanuatu">Vanuatu</option>
			<option value="Venezuela">Venezuela</option>
			<option value="Vietnam">Viet Nam</option>
			<option value="Virgin Islands (British)">Virgin Islands (British)</option>
			<option value="Virgin Islands (U.S)">Virgin Islands (U.S.)</option>
			<option value="Wallis and Futana Islands">Wallis and Futuna Islands</option>
			<option value="Western Sahara">Western Sahara</option>
			<option value="Yemen">Yemen</option>
			<option value="Yugoslavia">Yugoslavia</option>
			<option value="Zambia">Zambia</option>
			<option value="Zimbabwe">Zimbabwe</option>
		</select>	
		<?php } ?>		
		</h3>		
		<h3>Source :<select name="slctSource" class="slctField fr" >	
							<option value="" >Select Source</option>
								<?php 
								$SrcName= $objQuery->mysqlSelect("*","source_list","","source_name asc","","","");
									$i=30;
									foreach($SrcName as $srcList){
										if($srcList['source_id']==$getInfo[0]['patient_src']){
											?>
								       <option value="<?php echo stripslashes($srcList['source_id']);?>" selected>
										<?php echo stripslashes($srcList['source_name']);?></option>
                                        <?php } ?>
                                        <option value="<?php echo stripslashes($srcList['source_id']);?>" />
										<?php echo stripslashes($srcList['source_name']);?></option>
									
									
									<?php 	$i++;
									}?>   
							</select>
		
		</h3>
		<h3>Department : 
		
		<select name="slctDept" class="slctField fr" >	
		<option value="" >Select Department</option>
		<?php $DeptName= $objQuery->mysqlSelect("*","specialization","","spec_name asc","","","");
		$i=30;
		foreach($DeptName as $DeptList){
			if($DeptList['spec_id']==$getInfo[0]['medDept']){ ?> 
		<option value="<?php echo stripslashes($DeptList['spec_id']);?>" selected/><?php echo stripslashes($DeptList['spec_name']);?></option>
		<?php 
			}?>

			<option value="<?php echo stripslashes($DeptList['spec_id']);?>" /><?php echo stripslashes($DeptList['spec_name']);?></option>
		<?php
				$i++;
		}?>   
		</select>
		
		<h3>Treating Doctor :<input type="text" name="txtTreatDoc" value="<?php echo $getInfo[0]['currentTreatDoc']; ?>" class="txtfield fr"/></h3>
		<h3>Treating Hospital :<input type="text"  value="<?php echo $getInfo[0]['currentTreatHosp']; ?>" name="txtTreatHosp" class="txtfield fr"/></h3>
		
				
		<h3>Medical reports attached ? :<?php if($getInfo[0]['attchState']==1) { ?><input type="checkbox" name="attchState" id="attchState" class="chkBox fr" style="margin-right:180px; margin-top:10px;" value="1" checked/>
					<?php } else { ?>
					<input type="checkbox" name="attchState" style="margin-right:180px; margin-top:10px;" class="chkBox fr" value="1"/>
					<?php } ?></h3><br>
		<h3>Urgent ?:<?php if($getInfo[0]['urgentState']==1) { ?><input type="checkbox" name="urgState" class="chkBox fr" style="margin-right:180px; margin-top:8px;" value="1" checked/>
					<?php } else { ?>
					<input type="checkbox" name="urgState" style="margin-right:180px; margin-top:8px;" class="chkBox fr" value="1"/>
					<?php } ?></h3>
		<h3>Chief Medical Complaints :<textarea name="txtNote1" class="txtArea fr"><?php echo $getInfo[0]['patient_complaint']; ?></textarea></h3><br><br>
		<h3>Brief Description :<textarea name="txtNote2" class="txtArea fr"><?php echo $getInfo[0]['patient_desc']; ?></textarea></h3><br><br>
		<h3>Medical query to the doctor :<textarea name="txtNote3" class="txtArea fr"><?php echo $getInfo[0]['pat_query']; ?></textarea></h3><br><br>
		</h3>
		<h3>Prefered Hospital :<input type="text" name="txtPrefHosp" value="<?php echo $getInfo[0]['pref_hosp']; ?>" class="txtfield fr"/></h3>
		<h3>Prefered Doctor :<input type="text" name="txtPrefDoc" value="<?php echo $getInfo[0]['pref_doc']; ?>" class="txtfield fr"/></h3><br>
		
		<h3>Treated Hospital :<input type="text" name="txtTreatedHosp" value="<?php echo $getInfo[0]['treatedHosp']; ?>" class="txtfield fr"/></h3>
		<h3>Treated Doctor :<input type="text" name="txtTreatedDoc" value="<?php echo $getInfo[0]['treatedDoc']; ?>" class="txtfield fr"/></h3>
		<h3>Total Bill Amount(Rs.) :<input type="text" name="txtBillAmt" value="<?php echo $getInfo[0]['totalBillAmt']; ?>" class="txtfield fr"/></h3>
		
		
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

