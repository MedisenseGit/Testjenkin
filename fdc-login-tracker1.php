<?php ob_start();
 error_reporting(0);
 session_start();

 $admin_id = $_SESSION['admin_id'];
 $Company_id=$_SESSION['comp_id'];
 $user_flag = $_SESSION['flag_id'];

 date_default_timezone_set('Asia/Kolkata');
 $Assign_Date=date('Y-m-d h:i:s');
$Cur_Date=date('d-m-Y h:i:s');
$cur_Date=date('Y-m-d',strtotime($Cur_Date));
$add_days = 2;
$Follow_Date = date('d-m-Y',strtotime($cur_Date) - (24*3600*$add_days));

if(empty($admin_id)){
header("Location:index.php");
}

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
 

//Display by Capture, Refer,Respond,&Close Buttons	
			
		if($_GET['disp']==2){	
		unset($_SESSION['disp']);
		$_SESSION['disp'] = $_GET['disp'];
			if(!isset($_GET['start'])) {  // This variable is set to zero for the first page
			$start = 0;
			}else{
			$start = $_GET['start'];
			}

			$eu = ($start - 0); 
			$limit = 100;         // No of records to be shown per page.
			$this1 = $eu + $limit; 
			$back = $eu - $limit; 
			$next = $eu + $limit;
	
			$busResult1 = $objQuery->mysqlSelect("a.doc_id as Doc_Id,b.contact_person as Partner_Name,b.Email_id as Email,b.cont_num1 as Cont_Num,b.location as City,a.system_ip as SystemIP,a.timestamp as LastUpdate","practice_login_tracker as a left join our_partners as b on a.doc_id=b.partner_id","a.type=2","a.timestamp desc","","","$eu, $limit");	
			$Total_Rslt = $objQuery->mysqlSelect("COUNT(DISTINCT(a.doc_id)) as count","practice_login_tracker as a left join our_partners as b on a.doc_id=b.partner_id","a.type=2","","","");	
			
			$pag_result = $objQuery->mysqlSelect("DISTINCT(a.doc_id) as PatId","practice_login_tracker as a left join our_partners as b on a.doc_id=b.partner_id","a.type=2","");


			$pageing = firstPaging($pag_result,$limit,$back,$next,$eu,$field,$type2);
			$arrPage = explode("-",$pageing);


		}
		if($_GET['disp']==1){
		unset($_SESSION['disp']);
		$_SESSION['disp'] = $_GET['disp'];	
			if(!isset($_GET['start'])) {  // This variable is set to zero for the first page
			$start = 0;
			}else{
			$start = $_GET['start'];
			}

			$eu = ($start - 0); 
			$limit = 100;         // No of records to be shown per page.
			$this1 = $eu + $limit; 
			$back = $eu - $limit; 
			$next = $eu + $limit;
			
			
			if(isset($_GET['type']) && $_GET['type']=="1"){
			unset($_SESSION['type']);
			$_SESSION['type'] = $_GET['type'];
			//$busResult2 = $objQuery->mysqlSelect("*","practice_login_tracker as a left join referal as b on a.doc_id=b.ref_id","b.sponsor_id='2'","a.num_visits desc","","","");
			//$busResult3 = $objQuery->mysqlSelect("ref_id,ref_name,doc_city,ref_address,TImestamp,login_status,ref_mail,contact_num,ABM_Name,RBM_name","referal","sponsor_id='2'","ref_id desc","","","");
			$busResult2 = $objQuery->mysqlSelect("*","practice_login_tracker as a left join referal as b on a.doc_id=b.ref_id","b.sponsor_id='2' or b.sponsor_id='3'","a.num_visits desc","","","");			
			$busResult3 = $objQuery->mysqlSelect("ref_id,ref_name,doc_city,ref_address,TImestamp,login_status,ref_mail,contact_num,ABM_Name,RBM_name","referal","sponsor_id='2' or sponsor_id='3'","ref_id desc","","","");	
			
			//$Total_Rslt = $objQuery->mysqlSelect("COUNT(a.track_id) as count","practice_login_tracker as a left join referal as b on a.doc_id=b.ref_id","b.sponsor_id='2'","","","");	
			//$pag_result = $objQuery->mysqlSelect("*","practice_login_tracker as a left join referal as b on a.doc_id=b.ref_id","b.sponsor_id='2'","");
			
			//doc_patient_episodes as a left join doc_my_patient as b on a.patient_id=b.patient_id
			} 
		
			$pageing = firstPaging($pag_result,$limit,$back,$next,$eu,$field,$type2);
			$arrPage = explode("-",$pageing);		
		
		}
		//Get Search Result
			if(isset($_GET['search']) && !empty($_GET['search'])){
				//echo $_GET['search'];
			$busResult2 = $objQuery->mysqlSelect("*","practice_login_tracker as a left join referal as b on a.doc_id=b.ref_id","md5(b.ref_id)='".$_GET['search']."'","","","","");	
			//print_r($busResult2);
			
			}
//$FDC_Total_Rslt = $objQuery->mysqlSelect("COUNT(ref_id) as count","referal","sponsor_id='2'","","","");	
$FDC_Total_Rslt = $objQuery->mysqlSelect("COUNT(ref_id) as count","referal","sponsor_id='2' or sponsor_id='3'","","","");	
$Total_Rslt = $objQuery->mysqlSelect("COUNT(ref_id) as count","referal","","","","");
//$FDC_WebsiteTotal_Rslt = $objQuery->mysqlSelect("COUNT(a.doctor_webtemplate_id) as count","doctor_webtemplates as a inner join referal as b on b.ref_id = a.doc_id","b.sponsor_id='2'","","","");
$FDC_WebsiteTotal_Rslt = $objQuery->mysqlSelect("COUNT(a.doctor_webtemplate_id) as count","doctor_webtemplates as a inner join referal as b on b.ref_id = a.doc_id","b.sponsor_id='2' or b.sponsor_id='3'","","","");

//$Tot_Visit_Count = $objQuery->mysqlSelect("COUNT(a.episode_id) as Tot_Visit_Count","doc_patient_episodes as a left join referal as b on a.admin_id=b.ref_id","b.sponsor_id='2'","","","","");
//$Tot_Appointment_Count = $objQuery->mysqlSelect("COUNT(a.id) as count","appointment_transaction_detail as a left join referal as b on a.pref_doc=b.ref_id","b.sponsor_id='2'","","","","");

//$totMyPatientCount = $objQuery->mysqlSelect("COUNT(a.patient_id) as MyPatientCount","doc_my_patient as a left join referal AS b on a.doc_id = b.ref_id","b.sponsor_id='2'","","","","");
//$TotReferralCount = $objQuery->mysqlSelect("COUNT(a.outgoing_referrals_id) as ReferCount","doctor_outgoing_referrals as a left join referal AS b on a.doc_id = b.ref_id","b.sponsor_id='2'","","","","");

$Tot_Visit_Count = $objQuery->mysqlSelect("COUNT(a.episode_id) as Tot_Visit_Count","doc_patient_episodes as a left join referal as b on a.admin_id=b.ref_id","b.sponsor_id='2' or b.sponsor_id='3'","","","","");
$Tot_Appointment_Count = $objQuery->mysqlSelect("COUNT(a.id) as count","appointment_transaction_detail as a left join referal as b on a.pref_doc=b.ref_id","b.sponsor_id='2' or b.sponsor_id='3'","","","","");

$totMyPatientCount = $objQuery->mysqlSelect("COUNT(a.patient_id) as MyPatientCount","doc_my_patient as a left join referal AS b on a.doc_id = b.ref_id","b.sponsor_id='2' or b.sponsor_id='3'","","","","");
$TotReferralCount = $objQuery->mysqlSelect("COUNT(a.outgoing_referrals_id) as ReferCount","doctor_outgoing_referrals as a left join referal AS b on a.doc_id = b.ref_id","b.sponsor_id='2' or b.sponsor_id='3'","","","","");
									
									
//$FDC_active_user = $objQuery->mysqlSelect("COUNT(a.track_id) as count","practice_login_tracker as a left join referal as b on a.doc_id=b.ref_id","b.sponsor_id='2' and a.type='1'","","","");	
$FDC_active_user = $objQuery->mysqlSelect("COUNT(a.track_id) as count","practice_login_tracker as a left join referal as b on a.doc_id=b.ref_id","(b.sponsor_id='2' or b.sponsor_id='3') and a.type='1'","","","");	
$all_active_user = 	$objQuery->mysqlSelect("COUNT(a.track_id) as count","practice_login_tracker as a left join referal as b on a.doc_id=b.ref_id","a.type='1'","","","");	
function firstPaging($pag_result,$limit,$back,$next,$eu,$field,$type2){
				$nume = count($pag_result);
				if($nume>=2600){
					$tot_num=2600;
				}else {
					$tot_num=count($pag_result);
				}
				$this1 = $eu + $limit; 
				$strPaging = " ";
				$strPaging.="<table width='100%' border='none' align = 'center'><tr><td  align='left' width='5%'>";
				if($back >=0){ 
					$strPaging.="<a href='$page_name?disp=$_GET[disp]&start=$back&field=".$field."&type=".$type2."'class=\"pagenav\"><font face='Verdana' size='8' color='#3c3e3e'>PREV</font></a>"; 
				}else{
					$strPaging.="<font face='Verdana' size='8' color='#3c3e3e'><span class='pagenav'>PREV</span></font>"; 
				}
				$strPaging.="</td><td align=center width='1200px' class='pagenav'><center>";
				$i=0;
				$l=1;
				$strPaging.="Page No.";
				for($i=0;$i < $tot_num;$i=$i+$limit){
					if($i <> $eu){
						$strPaging.="<a href='$page_name?disp=$_GET[disp]&start=$i&field=".$field."&type=".$type2."'class=\"pagenav1\" ><font face='Verdana' size='8' color='#3c3e3e' >$l</font></a>&nbsp;";
					}else{
						$strPaging.="<font face='Verdana' size='8' color='#ec042a' ><span class='pagenav1' ><b>$l</b></span></font>&nbsp;";
					}
					$l=$l+1;
				}
				$strPaging.="</center></td><td  align='right' width='5%'>";
				if($this1 < $nume) { 
					
					$strPaging.="<a href='$page_name?disp=$_GET[disp]&start=$next&field=".$field."&type=".$type2."'class=\"pagenav1\"><font face='Verdana' size='8' color='#3c3e3e'>NEXT</font></a>";
				}else{
					$strPaging.="<font face='Verdana' size='8' color='#3c3e3e'><span class='pagenav'>NEXT</span></font>";
				}

				$strPaging.="</td></tr></table>";
				return $strPaging."-".$nume;
}

//Search By Referrals
if(isset($_POST['docType']) && $_POST['doc_type']!=0){
$doc_type = $_POST['doc_type'];
?>
<SCRIPT LANGUAGE="JavaScript">	
	window.location.href="<?php echo $_SERVER['PHP_SELF'];?>?disp=<?php echo $_POST['disp'];?>&type="+<?php echo $doc_type; ?>;
	</SCRIPT>
<?php
}
$ChkInUsrName= $objQuery->mysqlSelect("*","chckin_user","cmpny_id='".$Company_id."' and user_status='ACTIVE'","","","","");

if(isset($_POST['searchRef']) && !empty($_POST['txtref'])){
$txtRefId= $_POST['txtref'];

if($txtRefId!=""){
	//unset($_SESSION['serRefId']);
	//$_SESSION['serRefId'] = $txtRefId;
	header("Location:Doctor-Tracking?disp=".$_SESSION['disp']."&type=".$_SESSION['type']."&search=".md5($txtRefId));
 }

}

$add_days = 10;
$Followup_Date = date('Y-m-d',strtotime($cur_Date) - (24*3600*$add_days));

$followupReqCount = $objQuery->mysqlSelect("COUNT(a.track_id) as followCount","practice_login_tracker as a left join referal as b on a.doc_id=b.ref_id","DATE_FORMAT(a.timestamp,'%Y-%m-%d')<'".$Followup_Date."' and b.login_status='0'","","","","");	

			
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Practice Tracker</title>

 <!--   <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="assets/css/animate.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
	
    <link href="assets/css/plugins/footable/footable.core.css" rel="stylesheet">
	<script src="search/jquery-1.11.1.min.js"></script>
	<script src="search/jquery-ui.min.js"></script>
	<script src="search/jquery.select-to-autocomplete.js"></script>
	<script>
	  (function($){
	    $(function(){
	      $('#txtref').selectToAutocomplete();
	      
	    });
	  })(jQuery);
	  function call_login_hospital(){
		   //alert("Logging in.........");
		   var user=document.getElementById('txtref').value;
		   		   
		   if(user==""){
		     alert("Please choose our panel doctor");
			 return false;
		   }
		   
		 }
		 
	</script>

	<style>
	
    .ui-autocomplete {
      padding: 10px;
	  font-size:12px;
      list-style: none;
      background-color: #fff;
      width: 658px;
      border: 1px solid #B0BECA;
      max-height: 350px;
      overflow-x: hidden;
	   white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  max-width: 658px;
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
	-->
	<link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="assets/css/animate.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>

<body class="top-navigation">
	
    <div id="wrapper">
        <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom white-bg">
        <nav class="navbar navbar-static-top" role="navigation">
            <div class="navbar-header">
                <button aria-controls="navbar" aria-expanded="false" data-target="#navbar" data-toggle="collapse" class="navbar-toggle collapsed" type="button">
                    <i class="fa fa-reorder"></i>
                </button>
                <a href="#" class="navbar-brand">Practice</a>
            </div>
            <div class="navbar-collapse collapse" id="navbar">
               <!-- <ul class="nav navbar-nav">
                    <li class="active">
                        <a aria-expanded="false" role="button" href="layouts.html"> Back to main Layout page</a>
                    </li>
                    <li class="dropdown">
                        <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> Menu item <span class="caret"></span></a>
                        <ul role="menu" class="dropdown-menu">
                            <li><a href="">Menu item</a></li>
                            <li><a href="">Menu item</a></li>
                            <li><a href="">Menu item</a></li>
                            <li><a href="">Menu item</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> Menu item <span class="caret"></span></a>
                        <ul role="menu" class="dropdown-menu">
                            <li><a href="">Menu item</a></li>
                            <li><a href="">Menu item</a></li>
                            <li><a href="">Menu item</a></li>
                            <li><a href="">Menu item</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> Menu item <span class="caret"></span></a>
                        <ul role="menu" class="dropdown-menu">
                            <li><a href="">Menu item</a></li>
                            <li><a href="">Menu item</a></li>
                            <li><a href="">Menu item</a></li>
                            <li><a href="">Menu item</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> Menu item <span class="caret"></span></a>
                        <ul role="menu" class="dropdown-menu">
                            <li><a href="">Menu item</a></li>
                            <li><a href="">Menu item</a></li>
                            <li><a href="">Menu item</a></li>
                            <li><a href="">Menu item</a></li>
                        </ul>
                    </li>

                </ul>-->
                <ul class="nav navbar-top-links navbar-right">
                    <li>
                        <a href="logout.php">
                            <i class="fa fa-sign-out"></i> Log out
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
        </div>
		<script language="javaScript" src="js/validation1.js"></script>

        <div class="wrapper wrapper-content">
           
            <div class="row">
               
               <!-- <div class="col-md-2">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                             <h5> FDC Doctors</h5>
                        </div>
                        <div class="ibox-content">
                                <a href="<?php echo $_SERVER['PHP_SELF'];?>?disp=1&type=1"><h1 class="no-margins"><i class="fa fa-user-md"></i> <?php echo $FDC_Total_Rslt[0]['count']; ?></h1></a>
                                 
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                           
                            <h5>Active Users</h5>
                        </div>
                        <div class="ibox-content">

                            <div class="row">
                               
                                <div class="col-md-6">
                                    <a href="<?php echo $_SERVER['PHP_SELF'];?>?disp=1&type=4"><h1 class="no-margins"><?php echo $FDC_active_user[0]['count']; ?></h1></a>
                                    <div class="font-bold text-navy">FDC Panelist</div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>-->
				<div class="row col-md-9">
				<div class="col-md-3">
				<div class="widget white-bg p-lg text-center">
                        <div class="m-b-md">
                           
                            <h1 class="m-xs"><?php echo $FDC_Total_Rslt[0]['count']; ?></h1>
                            <h3 class="font-bold no-margins">
                                Total FDC Panelist
                            </h3>
                           
                        </div>
                    </div>
				</div>
				<div class="col-md-3">
				<div class="widget navy-bg p-lg text-center">
                        <div class="m-b-md">
                           
                            <h1 class="m-xs"><?php echo $FDC_active_user[0]['count']; ?></h1>
                            <h3 class="font-bold no-margins">
                                Active Users
                            </h3>
                           
                        </div>
                    </div>
				</div>
				
				<div class="col-md-3">
				<div class="widget red-bg p-lg text-center">
                        <div class="m-b-md">
                           
                            <h1 class="m-xs"><?php echo $trainingRequired = $FDC_Total_Rslt[0]['count']-$FDC_active_user[0]['count']; ?></h1>
                            <h3 class="font-bold no-margins">
                                Training Needed
                            </h3>
                           
                        </div>
                    </div>
				</div>
				<div class="col-md-3">
				<div class="widget yellow-bg p-lg text-center">
                        <div class="m-b-md">
                           
                            <h1 class="m-xs"><?php $TotalTransaction = $totMyPatientCount[0]['MyPatientCount']+$Tot_Visit_Count[0]['Tot_Visit_Count']+$Tot_Appointment_Count[0]['count']+$TotReferralCount[0]['ReferCount']; echo $TotalTransaction*10; ?></h1>
                            <h3 class="font-bold no-margins">
                                Total Transactions
                            </h3>
                           
                        </div>
                    </div>
				</div>
				<div class="col-md-3">
				<div class="widget navy-bg p-lg text-center">
                        <div class="m-b-md">
                           
                            <h1 class="m-xs"><?php echo $FDC_WebsiteTotal_Rslt[0]['count'];  ?></h1>
                            <h3 class="font-bold no-margins">
                                Websites
                            </h3>
                           
                        </div>
                    </div>
				</div>
				</div>
				
				<div class="col-md-3">
				 
                           <div id="piechart"></div>
                        </div>
				</div>
				<!--<div class="col-md-2">
				<div class="widget blue-bg p-lg text-center">
                        <div class="m-b-md">
                           
                            <h1 class="m-xs"><?php echo $Tot_Visit_Count[0]['Tot_Visit_Count']; ?></h1>
                            <h3 class="font-bold no-margins">
                                Total Visits
                            </h3>
                           
                        </div>
                    </div>
				</div>
				<div class="col-md-2">
				<div class="widget navy-bg p-lg text-center">
                        <div class="m-b-md">
                           
                            <h1 class="m-xs"><?php echo $Tot_Appointment_Count[0]['count']; ?></h1>
                            <h3 class="font-bold no-margins">
                                Total Appointments
                            </h3>
                           
                        </div>
                    </div>
				</div>
				-->
               <!-- <div class="col-md-4">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Monthly income</h5>
                            <div class="ibox-tools">
                                <span class="label label-primary">Updated 12.2015</span>
                            </div>
                        </div>
                        <div class="ibox-content no-padding">
                            <div class="flot-chart m-t-lg" style="height: 55px;">
                                <div class="flot-chart-content" id="flot-chart1"></div>
                            </div>
                        </div>

                    </div>
                </div>-->
            </div>
               
				
               <div class="row">

                    <div class="col-lg-12">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <h5><?php if($_GET['type']=="1"){ echo "All FDC"; } else if($_GET['type']=="2") { echo "All Medisense"; } else if($_GET['type']=="3"){ echo "All Active"; } else if($_GET['type']=="4"){ echo "FDC Active"; } ?> Doctors</h5>
                               <div class="ibox-tools">
								  <form  action="export_data.php" method="post" name="upload_excel" enctype="multipart/form-data">
												<a href="Analytics" class="btn btn-success " ><i class="fa fa-bar-chart" aria-hidden="true"></i> ANALYTICS</a>
												<button type="submit" name="Export" class="btn btn-success " ><i class="fa fa-download" aria-hidden="true"></i> EXPORT</button>
												<button type="submit" name="ExportPrescription" class="btn btn-success " ><i class="fa fa-download" aria-hidden="true"></i> EXPORT PRESCRIPTION DATA</button>
									</form>
								   </div>
                            </div>
                            <div class="ibox-content">
                                <div class="row">
                                    <div class="col-sm-6 m-b-xs pull-left">
                                        <div data-toggle="buttons" class="btn-group">
                                           <label class="btn btn-sm btn-white active"> <input type="radio" id="option1" name="options"> Login Tracker </label>
                                            <label class="btn btn-sm btn-white "> <input type="radio" id="option2" name="options" > Customization Request </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 m-b-xs pull-right">
									<!--<form method="post" name="frmSrch" >
									<input type="hidden" name="docType" value="" />
									<input type="hidden" name="doc_type" value=""/>
									<input type="hidden" name="disp" value="<?php echo $_GET['disp'];?>"/>
									<select class="input-sm form-control input-s-sm inline" onchange="return srchDoctor(this.value);">
										<option value="0" selected>Select</option>
										<option value="1" <?php if($_GET['type']=="1") { echo "selected"; }?>>FDC</option>
										<option value="2" <?php if($_GET['type']=="2") { echo "selected"; }?> >All</option>
									</select>-->
									<div class="input-group"><!--<input type="text" placeholder="Search" class="input-sm form-control"> -->
									<!--<select name='txtref' id="txtref" placeholder="Select Panel Doctor" class="input-sm form-control input-s-sm inline" >
				<option value="">Select Panel Doctor</option>-->
	<?php 
	
	//$get_pro = $objQuery->mysqlSelect("ref_id as RefId","referal","sponsor_id='2'","","","","0,200");
	$get_pro = $objQuery->mysqlSelect("ref_id as RefId","referal","sponsor_id='2' or sponsor_id='3'","","","","0,200");
	?>

									<form method="post" name="reportFrm">
				<input type="hidden" name="cmdRef" value="" />
				<input type="hidden" name="refferId" value="" />
				<input type="hidden" name="Pat_Id" value="<?php echo $_GET['pat_id']; ?>" />
				<input type="hidden" name="disp" vlaue="<?php echo $_GET['disp']; ?>" />
				<input type="hidden" name="type" vlaue="<?php echo $_GET['type']; ?>" />
				<select name='txtref' id="txtref" placeholder="Search Doctor" style="width:260px; padding:5px; margin-top:5px; margin-left:65px;" >
				<option value="">Select Panel Doctor</option>
	<?php foreach($get_pro as $listDoc) {
			$get_Ref = $objQuery->mysqlSelect('*','referal',"ref_id='".$listDoc['RefId']."'","","","","");
			$get_spec = $objQuery->mysqlSelect('*','specialization',"spec_id='".$get_Ref[0]['doc_spec']."'","","","","");
			$getHosp = $objQuery->mysqlSelect("*","hosp_tab as a left join doctor_hosp as b on a.hosp_id=b.hosp_id","doc_id='".$get_Ref[0]['ref_id']."'" ,"","","",""); 
			
			?>	
			
			
      <option value="<?php echo $get_Ref[0]['ref_id']; ?>"  ><?php echo addslashes($get_Ref[0]['ref_name'])."&nbsp;".addslashes($get_Ref[0]['ref_address'])."&nbsp;".addslashes($get_spec[0]['spec_name'])."&nbsp;".addslashes($getHosp[0]['hosp_name'])."&nbsp;".addslashes($get_Ref[0]['doc_state']); ?></option>
	<?php } ?>
    </select>
   
    <input type='submit' name='searchRef' value='SEARCH' class="btn btn-sm btn-primary" onClick="call_login_hospital()"/>
	 <a href="Doctor-Tracking?disp=1&type=1"><input type='button' name='allPatient' value='ALL' class="btn btn-sm btn-primary pull-right m-l"  style="margin-top:5px;"/></a>
	
	  </form>
	   </div>
	   
                               
                                </div>
								</div>
								<div class="row">
                                <div class="table-responsive">
								<?php
										if($busResult2==true){ ?>
                                     <table class="table table-stripped toggle-arrow-tiny">
                                <thead>
                                <tr>

                                    <th data-toggle="true">Reg. Date</th>
									<th>Status</th>
                                    <th>Doctor Name</th>
                                    <th>RBM / ABM </th>
                                   <!-- <th data-hide="all">Next Follow up Date</th>
                                    <th data-hide="all">Comments</th>-->
                                 
                                    <th style="width:100px;">Appointments</th>
									<th style="width:100px;">Visits</th>
									<th style="width:100px;">Patients</th>
									<th style="width:100px;">Referrals</th>
									<th style="width:100px;">Website</th>
									<th>System IP</th>
									<th>Last Login Date</th>
                                </tr>
                                </thead>
                                <tbody>
								<?php
				
									foreach($busResult2 as $list){
									$Total_Visit_Count = $objQuery->mysqlSelect("COUNT(episode_id) as Tot_App_Count","doc_patient_episodes","admin_id='".$list['ref_id']."'","","","","");
									$Total_Appointment_Count = $objQuery->mysqlSelect("COUNT(id) as count","appointment_transaction_detail","pref_doc='".$list['ref_id']."'","","","","");
									$PatientCount = $objQuery->mysqlSelect("COUNT(patient_id) as PatientCount","doc_my_patient","doc_id='".$list['ref_id']."'","","","","");
									
									$WebsiteLink = $objQuery->mysqlSelect("doctor_webtemplate_id, doc_id, website_name","doctor_webtemplates","	doc_id='".$list['ref_id']."'","","","","");

									$ReferralCount = $objQuery->mysqlSelect("COUNT(outgoing_referrals_id) as ReferCount","doctor_outgoing_referrals","doc_id='".$list['ref_id']."' and doc_type='1'","","","","");
									
									$arrField[]="num_visits";
									$arrVal[]=$Total_Visit_Count[0]['Tot_App_Count'];
									
									$arrField[]="num_appointments";
									$arrVal[]=$Total_Appointment_Count[0]['count'];
									
									$update_login_tracker=$objQuery->mysqlUpdate('practice_login_tracker',$arrField,$arrVal,"doc_id='".$list['ref_id']."' and type='1'");
									
									$chkLoginTrack =$objQuery->mysqlSelect("*","practice_login_tracker","doc_id='".$list['ref_id']."' and type='1'","","","","");
									
								$add_days = 10;
								$Followup_Date = date('Y-m-d',strtotime($list['timestamp']) + (24*3600*$add_days));
								$today_date = date('Y-m-d');
								if($list['login_status']!=9 || $list['login_status']!=10){
									if($list['login_status']=="1"){
									$status = "<span class='label label-warning'>Doctor Busy</span>";
									}
									else if($list['login_status']=="2")
									{
									$status = "<span class='label label-danger'>Data Privacy issue</span>";									
									}
									else if($list['login_status']=="3")
									{
									$status = "<span class='label label-danger'>Did not like our software</span>";
									}
									else if($list['login_status']=="4")
									{
									$status = "<span class='label label-danger'>Already using other software</span>";
									}
									else if($list['login_status']=="5")
									{
									$status = "<span class='label label-warning'>Internet issue. Needs offline version</span>";									
									}
									else if($list['login_status']=="6")
									{
									$status = "<span class='label label-danger'>Likes some other software</span>";									
									}
									else if($list['login_status']=="7")
									{
									$status = "<span class='label label-danger'>Dont need any software</span>";									
									}
									else if($list['login_status']=="8")
									{
									$status = "<span class='label label-warning'>Wants to use. WIP</span>";									
									}
									else if($list['login_status']=="9")
									{
									$status = "<span class='label label-warning'>FOLLOW-UP REQUIRED</span>";									
									}
									else if($list['login_status']=="10")
									{
									$status = "<span class='label label-primary'>ACTIVE</span>";									
									}
									
								}
								else
								{
									if(($Followup_Date<=$today_date) && (count($chkLoginTrack)>0)){
									$status = "<span class='label label-warning'>FOLLOW-UP REQUIRED</span>";	
									$arrFieldStatus[]="login_status";
									$arrValStatus[]="9";									
									$update_login_status=$objQuery->mysqlUpdate('referal',$arrFieldStatus,$arrValStatus,"ref_id='".$list['ref_id']."'");									
									
									
									}
									else if(($Followup_Date>$today_date) && (count($chkLoginTrack)>0))
									{
									$status = "<span class='label label-primary'>ACTIVE</span>";	
									$arrFieldStatus[]="login_status";
									$arrValStatus[]="10";									
									$update_login_status=$objQuery->mysqlUpdate('referal',$arrFieldStatus,$arrValStatus,"ref_id='".$list['ref_id']."'");
									
										
									}
									else if(count($chkLoginTrack)==0)
									{
									$status = "<span class='label label-danger'>TRAINING NEEDED</span>";	
									}
								}
								?>
                                <tr>
                                    <td><?php echo date('d-M-Y',strtotime($list['TImestamp'])); ?></td>
									<td><?php echo $status; ?></td>
                                    <td><a href="#" data-toggle="modal" data-target="#myModal<?php echo $list['ref_id']; ?>"><?php echo $list['ref_name']; ?></a><br><?php echo $list['ref_address']; ?><br><i class="fa fa-mobile"></i> <?php echo $list['contact_num']; ?></td>
                                    <td><?php if(!empty($list['RBM_name'])){ echo "<b>RBM:</b>".$list['RBM_name']."<br>";} if(!empty($list['ABM_Name'])){ echo "<b>ABM:</b>".$list['ABM_Name'];}?></td>
                                   <!-- <td> 28-July-2018, 2pm</td>
                                    <td>Comments1</td>-->
                                  
									<td><a href="#" class="text-navy"><b><?php echo $chkLoginTrack[0]['num_appointments']*2;?></b></a></td>
									<td><a href="#" class="text-navy"><b><?php echo $chkLoginTrack[0]['num_visits']*2;?></b></td>
									<td><a href="#" class="text-navy"><b><?php echo $PatientCount[0]['PatientCount']*2;?></b></td>
									<td><a href="#" class="text-navy"><b><?php echo $ReferralCount[0]['ReferCount']*2;?></b></td>
									<td><a href="http://<?php echo $WebsiteLink[0]['website_name']; ?>" target="_blank"><?php if(!empty($WebsiteLink[0]['website_name'])){ ?><span class='label label-primary'>View</span> <?php } ?></a></td>
									<td><?php if(count($chkLoginTrack)>0){ echo $list['system_ip']; }?></td>
                                    <td><?php if(count($chkLoginTrack)>0){ echo date('d-M-Y H:i:s a',strtotime($list['timestamp']));} ?></td>
                                </tr>
								<div class="modal inmodal" id="myModal<?php echo $list['ref_id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog">
                                <div class="modal-content animated bounceInRight">
								<form action="add_details.php" method="post" name="frmActivity" id="frmActivity">
								<input type="hidden" name="track_id" value="<?php echo $chkLoginTrack[0]['track_id']; ?>" />
								<input type="hidden" name="doc_id" value="<?php echo $list['ref_id']; ?>" />
								<input type="hidden" name="disp" value="<?php echo $_GET['disp']; ?>" />
								<input type="hidden" name="type" value="<?php echo $_GET['type']; ?>" />
								<input type="hidden" name="page_uri" value="Doctor-Tracking" />
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                           
                                            <h4 class="modal-title">Add Comments</h4>
                                            <h3><i class="fa fa-user-md"></i> <?php echo $list['ref_name']; ?></h3>
											
											<div class="col-sm-6" style="margin-left:150px;">
											<label class="pull-left">Status</label>
											<select class="form-control" name="doc_status" >
												<option value="" <?php if($list['login_status']==0){ echo "selected";}?>>Select Status</option>
												<option value="1" <?php if($list['login_status']==1){ echo "selected";}?> >Doctor Busy</option>
												<option value="2" <?php if($list['login_status']==2){ echo "selected";}?>>Data Privacy issue</option>
												<option value="3" <?php if($list['login_status']==3){ echo "selected";}?>>Did not like our software</option>
												<option value="4" <?php if($list['login_status']==4){ echo "selected";}?>>Already using other software</option>
												<option value="5" <?php if($list['login_status']==5){ echo "selected";}?>>Internet issue. Needs offline version</option>
												<option value="6" <?php if($list['login_status']==6){ echo "selected";}?>>Likes some other software</option>
												<option value="7" <?php if($list['login_status']==7){ echo "selected";}?>>Dont need any software</option>
												<option value="8" <?php if($list['login_status']==8){ echo "selected";}?>>Wants to use. WIP</option>
												<option value="9" <?php if($list['login_status']==9){ echo "selected";}?> >Follow-up Required</option>
												<option value="10" <?php if($list['login_status']==10){ echo "selected";}?>>Active</option>
										</select></div>
										<div class="col-sm-6" style="margin-left:150px;">
											<label class="pull-left">RBM Name</label> <input type="text" name="rbm_name" value="<?php echo $list['RBM_name']; ?>"  class="form-control" >
											</div>
											<div class="col-sm-6" style="margin-left:150px;">
											<label class="pull-left">ABM Name</label> <input type="text" name="abm_name" value="<?php echo $list['ABM_Name']; ?>"  class="form-control" >
											</div>
                                        </div>
										
                                        <div class="modal-body">
                                            <div class="chat-discussion">
									<?php $getchatHistory = $objQuery->mysqlSelect("*","practice_login_tracker_comments as a inner join chckin_user as b on a.user_id=b.chk_userid","a.doc_id='".$list['ref_id']."' and a.doc_type='1'","a.track_comments_id desc","","",""); 
										
										foreach($getchatHistory as $chatList){
											if($chatList['track_comments_id'] % 2 == 0)
											{	
												
												$tabSide="left";
											} else
											{
												$tabSide="right";
											}
										?>
                                    <div class="chat-message <?php echo $tabSide; ?>">
                                        <img class="message-avatar" src="<?php if(!empty($chatList['profile_pic'])){ echo "images/loginUser/".$chatList['profile_pic'];} else { echo "assets/img/user_noimg12.png"; } ?>" alt="" >
                                        <div class="message">
                                            <a class="message-author" href="#"> <?php echo $chatList['chk_username']; ?> </a><br>
											<span class="message-date"><i class="fa fa-calendar"></i> <?php echo date('d-M-Y H:i a',strtotime($chatList['timestamp'])); ?></span><br>
                                            <span class="message-content text-center">
											<?php echo $chatList['comments']; ?>
                                            </span>
                                        </div>
                                    </div>
									 <?php } ?>
                                    
                                    

                                </div>				
						<div class="chat-message-form">
								
								
                                    <div class="form-group">

                                        <textarea class="form-control message-input" name="txtDesc" id="txtDesc" placeholder="Enter message text"></textarea>
                                    </div>
									<br>
									<div class="form-group">
									<div class="col-sm-6">
										
										
									</div>
									<div class="col-sm-6">
										<button type="submit" id="addDocActivityReport" name="addDocActivityReport" class="btn btn-primary pull-right">Submit</button>
									</div>
									</div>
								
                                </div>
								<br><br>
										</div>
                                        
										</form>
                                    </div>
									</div>
								</div>
                               <?php  } 
							   if(!isset($_GET['search'])){
									foreach($busResult3 as $list){
									$chkLoginTrack =$objQuery->mysqlSelect("*","practice_login_tracker","doc_id='".$list['ref_id']."' and type='1'","","","","");
								if($list['login_status']!=0){
									if($list['login_status']=="1"){
									$status = "<span class='label label-warning'>Doctor Busy</span>";
									}
									else if($list['login_status']=="2")
									{
									$status = "<span class='label label-danger'>Data Privacy issue</span>";									
									}
									else if($list['login_status']=="3")
									{
									$status = "<span class='label label-danger'>Did not like our software</span>";
									}
									else if($list['login_status']=="4")
									{
									$status = "<span class='label label-danger'>Already using other software</span>";
									}
									else if($list['login_status']=="5")
									{
									$status = "<span class='label label-warning'>Internet issue. Needs offline version</span>";									
									}
									else if($list['login_status']=="6")
									{
									$status = "<span class='label label-danger'>Likes some other software</span>";									
									}
									else if($list['login_status']=="7")
									{
									$status = "<span class='label label-danger'>Dont need any software</span>";									
									}
									else if($list['login_status']=="8")
									{
									$status = "<span class='label label-warning'>Wants to use. WIP</span>";									
									}
									
								}
								else
								{
									
								$status = "<span class='label label-danger'>TRAINING NEEDED</span>";	
								}
								if(count($chkLoginTrack)==0) {	?>
                                <tr>
                                    <td><?php echo date('d-M-Y',strtotime($list['TImestamp'])); ?></td>
									<td><?php echo $status; ?></td>
                                    <td><a href="#" data-toggle="modal" data-target="#myModal<?php echo $list['ref_id']; ?>"><?php echo $list['ref_name']; ?></a><br><?php echo $list['ref_address']; ?></td>
                                    <td><?php if(!empty($list['RBM_name'])){ echo "<b>RBM:</b>".$list['RBM_name']."<br>";} if(!empty($list['ABM_Name'])){ echo "<b>ABM:</b>".$list['ABM_Name'];}?></td>
                                   <!-- <td> 28-July-2018, 2pm</td>
                                    <td>Comments1</td>-->
                                  
									<td></td>
									<td></td>
									<td></td>
                                    <td></td>
                                </tr>
								<div class="modal inmodal" id="myModal<?php echo $list['ref_id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog">
                                <div class="modal-content animated bounceInRight">
								<form action="add_details.php" method="post" name="frmActivity" id="frmActivity">
								<input type="hidden" name="track_id" value="<?php echo $chkLoginTrack[0]['track_id']; ?>" />
								<input type="hidden" name="doc_id" value="<?php echo $list['ref_id']; ?>" />
								<input type="hidden" name="disp" value="<?php echo $_GET['disp']; ?>" />
								<input type="hidden" name="type" value="<?php echo $_GET['type']; ?>" />
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                           
                                            <h4 class="modal-title">Add Comments</h4>
                                            <h3><i class="fa fa-user-md"></i> <?php echo $list['ref_name']; ?></h3>
											
											<div class="col-sm-6" style="margin-left:150px;"><select class="form-control" name="doc_status">
												<option value="" <?php if($list['login_status']==0){ echo "selected";}?>>Select Status</option>
												<option value="1" <?php if($list['login_status']==1){ echo "selected";}?> >Doctor Busy</option>
												<option value="2" <?php if($list['login_status']==2){ echo "selected";}?>>Data Privacy issue</option>
												<option value="3" <?php if($list['login_status']==3){ echo "selected";}?>>Did not like our software</option>
												<option value="4" <?php if($list['login_status']==4){ echo "selected";}?>>Already using other software</option>
												<option value="5" <?php if($list['login_status']==5){ echo "selected";}?>>Internet issue. Needs offline version</option>
												<option value="6" <?php if($list['login_status']==6){ echo "selected";}?>>Likes some other software</option>
												<option value="7" <?php if($list['login_status']==7){ echo "selected";}?>>Dont need any software</option>
												<option value="8" <?php if($list['login_status']==8){ echo "selected";}?>>Wants to use. WIP</option>
										</select></div>
										<div class="col-sm-6" style="margin-left:150px;">
											<label class="pull-left">RBM Name</label> <input type="text" name="rbm_name" value="<?php echo $list['RBM_name']; ?>"  class="form-control" >
											</div>
											<div class="col-sm-6" style="margin-left:150px;">
											<label class="pull-left">ABM Name</label> <input type="text" name="abm_name" value="<?php echo $list['ABM_Name']; ?>"  class="form-control" >
											</div>
                                        </div>
										
                                        <div class="modal-body">
                                            <div class="chat-discussion">
									<?php $getchatHistory = $objQuery->mysqlSelect("*","practice_login_tracker_comments as a inner join chckin_user as b on a.user_id=b.chk_userid","a.doc_id='".$list['ref_id']."' and a.doc_type='1'","a.track_comments_id desc","","",""); 
										
										foreach($getchatHistory as $chatList){
											if($chatList['track_comments_id'] % 2 == 0)
											{	
												
												$tabSide="left";
											} else
											{
												$tabSide="right";
											}
										?>
                                    <div class="chat-message <?php echo $tabSide; ?>">
                                        <img class="message-avatar" src="assets/img/user_noimg12.png" alt="" >
                                        <div class="message">
                                            <a class="message-author" href="#"> <?php echo $chatList['chk_username']; ?> </a><br>
											<span class="message-date"><i class="fa fa-calendar"></i> <?php echo date('d-M-Y H:i a',strtotime($chatList['timestamp'])); ?></span><br>
                                            <span class="message-content text-center">
											<?php echo $chatList['comments']; ?>
                                            </span>
                                        </div>
                                    </div>
									 <?php } ?>
                                    
                                    

                                </div>				
						<div class="chat-message-form">
								
								
                                    <div class="form-group">

                                        <textarea class="form-control message-input" name="txtDesc" id="txtDesc" placeholder="Enter message text"></textarea>
                                    </div>
									<br>
									<div class="form-group">
									<div class="col-sm-6">
										
										
									</div>
									<div class="col-sm-6">
										<button type="submit" id="addDocActivityReport" name="addDocActivityReport" class="btn btn-primary pull-right">Submit</button>
									</div>
									</div>
								
                                </div>
								<br><br>
										</div>
                                        
										</form>
                                    </div>
									</div>
								</div>
                               <?php 
								}
								
								} 
								
								}?>
                                </tbody>
                                <!--<tfoot>
                                <tr>
                                    <td colspan="5">
                                        <ul class="pagination pull-right"></ul>
                                    </td>
                                </tr>
                                </tfoot>-->
                            </table>
										<?php } ?>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>

            

        </div>
        <div class="footer">
            
            <div>
                <strong>Copyright @</strong> Medisense Healthcare Solutions Pvt. Ltd.
            </div>
        </div>

        </div>
        </div>



    <!-- Mainly scripts -->
    <script src="assets/js/jquery-3.1.1.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="assets/js/inspinia.js"></script>
    <script src="assets/js/plugins/pace/pace.min.js"></script>
	
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<?php
//$doc_busy = $objQuery->mysqlSelect("COUNT(ref_id) as count","referal","sponsor_id='2' and login_status='1'","","","");
//$doc_wantuse = $objQuery->mysqlSelect("COUNT(ref_id) as count","referal","sponsor_id='2' and login_status='8'","","","");	
//$doc_dontneed = $objQuery->mysqlSelect("COUNT(ref_id) as count","referal","sponsor_id='2' and login_status='7'","","","");
//$doc_alreadyuse = $objQuery->mysqlSelect("COUNT(ref_id) as count","referal","sponsor_id='2' and login_status='4'","","","");
//$doc_dataprivacy = $objQuery->mysqlSelect("COUNT(ref_id) as count","referal","sponsor_id='2' and login_status='2'","","","");
$doc_busy = $objQuery->mysqlSelect("COUNT(ref_id) as count","referal","(sponsor_id='2' or sponsor_id='3') and login_status='1'","","","");
$doc_wantuse = $objQuery->mysqlSelect("COUNT(ref_id) as count","referal","(sponsor_id='2' or sponsor_id='3') and login_status='8'","","","");	
$doc_dontneed = $objQuery->mysqlSelect("COUNT(ref_id) as count","referal","(sponsor_id='2' or sponsor_id='3') and login_status='7'","","","");
$doc_alreadyuse = $objQuery->mysqlSelect("COUNT(ref_id) as count","referal","(sponsor_id='2' or sponsor_id='3') and login_status='4'","","","");
$doc_dataprivacy = $objQuery->mysqlSelect("COUNT(ref_id) as count","referal","(sponsor_id='2' or sponsor_id='3') and login_status='2'","","","");
?>
<script type="text/javascript">
// Load google charts
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);

// Draw the chart and set the chart values
function drawChart() {
  var data = google.visualization.arrayToDataTable([
  ['Task', 'Hours per Day'],
  ['Active Users', <?php echo $FDC_active_user[0]['count']; ?>],
  ['Doctor Busy', <?php echo $doc_busy[0]['count']; ?>],
  ['Wants to use. WIP', <?php echo $doc_wantuse[0]['count']; ?>],
  ['Follow-up Required', <?php echo $followupReqCount[0]['followCount']; ?>],
  ['Dont need any software', <?php echo $doc_dontneed[0]['count']; ?>],
  ['Already using other software', <?php echo $doc_alreadyuse[0]['count']; ?>],
  ['Data Privacy issue', <?php echo $doc_dataprivacy[0]['count']; ?>],
  ['Training Needed', <?php echo $trainingRequired; ?>]
]);

  // Optional; add a title and set the width and height of the chart
  var options = {'title':'', 'width':450, 'height':300};

  // Display the chart inside the <div> element with id="piechart"
  var chart = new google.visualization.PieChart(document.getElementById('piechart'));
  chart.draw(data, options);
}
</script>
	
    <!-- Page-Level Scripts -->
    <script>
        $(document).ready(function() {

		
		$('input:radio').change(function() {
			// alert('ole');
			window.location.href="View-Customization-Request?type=1"
		});
	
        });

    </script>


</body>

</html>
