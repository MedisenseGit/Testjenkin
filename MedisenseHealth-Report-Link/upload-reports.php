<?php ob_start();
 error_reporting(0);
 session_start(); 


date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d h:i:s');
$cur_Date=date('Y-m-d',strtotime($Cur_Date));
$add_days = 3;
$Follow_Date = date('Y-m-d',strtotime($cur_Date) + (24*3600*$add_days));

require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

?>
<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  <?php include('support_new.php'); ?>
      <!-- Bootstrap  -->
     
	   <!-- Favicons================================================== -->
	   
     

<link href="assets/css/fileinput.css" rel="stylesheet">	

 <script src="assets/js/fileinput.js"></script>		

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
</head>
<body class="has-side-panel side-panel-right fullwidth-page side-push-panel">
<div class="body-overlay"></div>


<div style="position: relative;
    min-height: 100vh;">
<div id="wrapper" class="clearfix">
 
  <!-- Header -->
 <style>
.alert {
  padding: 20px;
  background-color: #006600;
  color: white;
  margin-top:15px;
  text-align:center;
				   
				  
  
  /*margin-right:25%;
  margin-left:25%;*/
  
}

.closebtn {
  color: white;
  font-weight: bold;
  float: right;
  font-size: 22px;
  line-height: 20px;
  cursor: pointer;
  transition: 0.3s;
}

.closebtn:hover {
  color: black;
}
</style>

  <!--sec-->
<div class="container pt-100">
	<div class="col-lg-12">
		<center><img src="assets/img/medisense_og.png" alt="Medisense-Logo"> </center>							
	</div>
</div>
  
  <div class="container pt-50">

<!--<div class="col-xs-12 col-sm-10 text-center">-->

<!--<h2 class="text-theme-colored">
<i class="fa fa-calendar-check-o sectionIcons"></i>
Medical Opinion</h2>
<!--<h4 class="sectionSubHeading" style="margin-top: 11px;">Just let us know. We will be happy to assist you</h4>

-->
<div class="row">

<?php if(isset($_GET['resultMail'])){
			if($_GET['resultMail'] == "success") { ?>
			
			<div class="alert">
			<span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
			 <p>We have successfully received your Reports. We will get Back to you in 24 to 48 working hours. Please go to back and complete Payment.</p>
			 </div>
			<?php } 
			} ?>

<?php $getPatientID= $objQuery->mysqlSelect("*","patient_tab","md5(patient_id)='".$_GET['pat_id']."'","","","",""); ?>

<form enctype="multipart/form-data" class="quick-contact-form" method="post" action="submit-upload-reports.php">
					<div class="form-group">
                        <label class="col-sm-3 control-label">
                            Upload Your Issue Related Medical Reports :
                        </label>
                        <div class="col-sm-9">
                            <span class="btn btn-default btn-file">
								<input type='hidden' name='Pat_Id' value='<?php echo $getPatientID[0]['patient_id'] ?>' />
                                <input id="txtphoto1[]" name="txtphoto1[]" type="file" class="file"  data-show-upload="false" data-show-caption="true" multiple accept="image/*" required>
                            </span>
                        </div>
                    </div>
<div class="row" style="margin-left:5px;margin-right:5px;">
 <div class="col-sm-12 col-sm-offset-5">
 <br><button type="submit" class="btn btn-theme-colored mb-10" name="upload_reports" >Upload Reports</button>
  

	</div>
</div>
</form>

<!--<form enctype="multipart/form-data" method="post" name="frmPatient" action="" onsubmit="return createPatient()">
					<div class="det"><a href="Add_Patient_Attachments.php?pat_id=<?php echo $_GET['pat_id']; ?>&refid=<?php echo $_GET['refid']; ?>&disp=<?php echo $_GET['disp']; ?>&assignId=<?php echo $_GET['assignId']; ?>">No. of records : <?php echo $getAttach[0]['Count_Attach']; ?></a><br><br>
						
						<div id="fileAttach"  >
						<form method='post' name='frmAttach'>
							<input type='hidden' name='Pat_Id' value='<?php echo $_GET['pat_id']; ?>' />
							<input type='file' name='txtphoto1[]' id='txtphoto1[]' multiple style='margin-bottom:10px;'>
							<input type='submit' name='addAttach'  value='ADD' class='addAttchFile' />
						</form>
						</div>
						
					</div>
					</form>-->


	 
	 </div>
	 <!--</div>-->
	 
	 </div>
	 </div>
	  </div>
	 
 


 

</body>
</html>