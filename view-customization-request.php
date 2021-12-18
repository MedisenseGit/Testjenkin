<?php ob_start();
 error_reporting(0);
 session_start();

 $admin_id = $_SESSION['admin_id'];
 $Company_id=$_SESSION['comp_id'];
 $user_flag = $_SESSION['flag_id'];
 if($user_flag == "0" || $user_flag == "1")
 {
	$webUrl="login-tracker.php?disp=1&type=3";
 }
 else if($user_flag == "3")
 {
	$webUrl="Doctor-Tracking?disp=1&type=1"; 
 }
 
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
			
			
			if(isset($_GET['type']) && $_GET['type']=="1"){   //$_GET['type'] is 1 for FDC Panelist, $_GET['type'] is 2 for All panelist
			unset($_SESSION['type']);
			$_SESSION['type'] = $_GET['type'];
			$busResult2 = $objQuery->mysqlSelect("a.request_id as request_id,a.doc_id as doc_id,a.doc_type as doc_type,a.customization_request as customization_request,a.request_date as request_date,a.completed_date as completed_date,a.customization_done_by as customization_done_by,a.status as status,a.taken_by as taken_by,a.release_version as release_version,b.ref_name as ref_name,a.report as report","practice_customization_request as a left join referal as b on a.doc_id=b.ref_id","","a.request_id desc","","","");	
			} 
		
			$pageing = firstPaging($pag_result,$limit,$back,$next,$eu,$field,$type2);
			$arrPage = explode("-",$pageing);		
		
	
$FDC_Total_Rslt = $objQuery->mysqlSelect("COUNT(ref_id) as count","referal","sponsor_id='2'","","","");	
$Total_Rslt = $objQuery->mysqlSelect("COUNT(ref_id) as count","referal","","","","");


$FDC_active_user = $objQuery->mysqlSelect("COUNT(a.track_id) as count","practice_login_tracker as a left join referal as b on a.doc_id=b.ref_id","b.sponsor_id='2' and a.type='1'","","","");	
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

$add_days = 7;
$Followup_Date = date('Y-m-d',strtotime($cur_Date) - (24*3600*$add_days));

$followupReqCount = $objQuery->mysqlSelect("COUNT(track_id) as followCount","practice_login_tracker","DATE_FORMAT(timestamp,'%Y-%m-%d')<'".$Followup_Date."'","","","","");	

$status_val=array("INITIATED"=>"1","IN-PROGRESS"=>"2","ON-HOLD"=>"3","COMPLETED"=>"4","PENDING"=>"5");			
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Practice Tracker</title>

    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="assets/css/animate.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
	 <!-- FooTable -->
    <link href="assets/css/plugins/footable/footable.core.css" rel="stylesheet">
	<link href="assets/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
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
		z-index:9999;
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
           
          <!--  <div class="row">
               
              
				<div class="col-md-2">
				<div class="widget white-bg p-lg text-center">
                        <div class="m-b-md">
                           
                            <h1 class="m-xs"><?php echo $FDC_Total_Rslt[0]['count']; ?></h1>
                            <h3 class="font-bold no-margins">
                                Total FDC Panelist
                            </h3>
                           
                        </div>
                    </div>
				</div>
				<div class="col-md-2">
				<div class="widget navy-bg p-lg text-center">
                        <div class="m-b-md">
                           
                            <h1 class="m-xs"><?php echo $FDC_active_user[0]['count']; ?></h1>
                            <h3 class="font-bold no-margins">
                                Active Users
                            </h3>
                           
                        </div>
                    </div>
				</div>
				<div class="col-md-2">
				<div class="widget yellow-bg p-lg text-center">
                        <div class="m-b-md">
                           
                            <h1 class="m-xs"><?php echo $followupReqCount[0]['followCount']; ?></h1>
                            <h3 class="font-bold no-margins">
                                Follow-up Required
                            </h3>
                           
                        </div>
                    </div>
				</div>
				<div class="col-md-2">
				<div class="widget red-bg p-lg text-center">
                        <div class="m-b-md">
                           
                            <h1 class="m-xs"><?php echo $trainingRequired = $FDC_Total_Rslt[0]['count']-$FDC_active_user[0]['count']; ?></h1>
                            <h3 class="font-bold no-margins">
                                Training Needed
                            </h3>
                           
                        </div>
                    </div>
				</div>
               
            </div>-->
               
				
               <div class="row">
					<?php if($_GET['response']=="Added"){ ?>
								<div class="alert alert-success alert-dismissable">
											<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										   <strong>Customization request has been initiated successfully </strong>
								 </div>
								<?php } if($_GET['response']=="Report-Added"){ ?>
								<div class="alert alert-success alert-dismissable">
											<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										   <strong>Customization report has been added successfully </strong>
								 </div>
								<?php } ?>
                    <div class="col-lg-12">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <h5>Customization Requests</h5>
								<?php if($user_flag != "3")
									{
								?>
								<div class="ibox-tools">
							    <button type="button" class="btn btn-w-m btn-default" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i> Add New</button>
								</div>
								<?php } ?>
								
								<div class="modal inmodal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog">
                                <div class="modal-content animated bounceInRight">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                           
                                            <h4 class="modal-title">Add New Customization</h4>
                                            
                                        </div>
										<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  action="add_details.php"  name="frmAddDiagnostic">
										
                                        <div class="modal-body">
                                            <div class="form-group"><label>Doctor Name</label> <select name='txtref' id="txtref" placeholder="Search Doctor" style="width:260px; padding:5px; margin-top:5px; margin-left:65px;" >
				<option value="">Select Panel Doctor</option>
	<?php 
	$get_pro = $objQuery->mysqlSelect("ref_id as RefId","referal","","","","","");
	foreach($get_pro as $listDoc) {
			$get_Ref = $objQuery->mysqlSelect('*','referal',"ref_id='".$listDoc['RefId']."'","","","","");
			$get_spec = $objQuery->mysqlSelect('*','specialization',"spec_id='".$get_Ref[0]['doc_spec']."'","","","","");
			$getHosp = $objQuery->mysqlSelect("*","hosp_tab as a left join doctor_hosp as b on a.hosp_id=b.hosp_id","doc_id='".$get_Ref[0]['ref_id']."'" ,"","","",""); 
			
			?>	
			
			
      <option value="<?php echo $get_Ref[0]['ref_id']; ?>"  ><?php echo addslashes($get_Ref[0]['ref_name'])."&nbsp;".addslashes($get_Ref[0]['ref_address'])."&nbsp;".addslashes($get_spec[0]['spec_name'])."&nbsp;".addslashes($getHosp[0]['hosp_name'])."&nbsp;".addslashes($get_Ref[0]['doc_state']); ?></option>
	<?php } ?>
    </select></div>
											<div class="form-group">
											<div class="col-sm-6">
											<label>Requested Date</label> <input id="dateadded" name="date_request" type="text"  value="" class="form-control" tabindex="2">
											</div><div class="col-sm-6">
											<label>Assigned To(Developer)</label> <input type="text" name="assigned_to" value=""  class="form-control" tabindex="4">
											</div>
											</div>
											<div class="form-group"><label>Customization Request</label> <textarea class="form-control" required id="customRequest" name="customRequest" rows="4" tabindex="6"></textarea></div>
											
											<div class="form-group">
											<div class="col-sm-6">
											<label>Followed By</label> 
											<select class="form-control" name="checkIn_user">
												<option value="" selected>Select</option>
												<option value="8" >Shashidhar Pai</option>
												<option value="53" >Shashi</option>
												<option value="36" >Sunita</option>
												<option value="37" >Satish</option>
												
												
												</select>
											</div>
											<div class="col-sm-6">
											<label>Released Version</label> 
											<input type="text" name="released_version" value=""  class="form-control" tabindex="5">

											</div>
												</div>
											
										
										</div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
											
                                            <button type="submit" name="add_new_customization" class="btn btn-primary">Add</button>
											
                                        </div>
										</form>
                                    </div>
									</div>
								</div>
								
                            </div>
                            <div class="ibox-content">
                                <div class="row">
                                    <div class="col-sm-7 m-b-xs">
                                        <div data-toggle="buttons" class="btn-group">
                                             <label class="btn btn-sm btn-white "> <input type="radio" id="option1" name="options"> Login Tracker </label>
                                            <label class="btn btn-sm btn-white active"> <input type="radio" id="option2" name="options" > Customization Request </label>
                                           
                                        </div>
                                    </div>
                                    
								</div>
								<div class="row">
                                <div class="table-responsive">
								<?php
										if($busResult2==true){ ?>
                                     <table class="footable table table-stripped toggle-arrow-tiny">
                                <thead>
                                <tr>

                                    <th >Requested Date</th>
									<th>Doctor Name</th>
                                    <th>Customization Request</th>
									<th data-hide="all">Customization Request</th>
									
									<th>Status</th>
									<th>Completed Date</th>
									<th>Release Version</th>
									<th>Assigned To</th>
									<th>Followed by</th>
                                </tr>
                                </thead>
                                <tbody>
								<?php
									$customization_count = count($busResult2);
									$i=0;
									foreach($busResult2 as $list){
									$custom_count=$customization_count-$i;
									
									$get_user = $objQuery->mysqlSelect("chk_username","chckin_user","chk_userid='".$list['taken_by']."'","","","");

									if($list['status']=="1"){
									$status = "INITIATED";
									$status_label = "<span class='label label-warning'>INITIATED</span>";
									}
									else if($list['status']=="2")
									{
									$status = "IN-PROGRESS";
									$status_label = "<span class='label label-success'>IN-PROGRESS</span>";									
									}
									else if($list['status']=="3")
									{
									$status = "ON-HOLD";	
									$status_label = "<span class='label label-warning'>ON-HOLD</span>";
									}
									else if($list['status']=="4")
									{
									$status = "COMPLETED";	
									$status_label = "<span class='label label-primary'>COMPLETED</span>";
									}
									else if($list['status']=="5")
									{
									$status = "PENDING";
									$status_label = "<span class='label label-danger'>PENDING</span>";									
									}
								?>
                                <tr>
                                    <td><?php echo "<span style='font-weight:bold;'>".$custom_count.")</span> ".date('d-M-Y',strtotime($list['request_date'])); ?></td>
									<td><?php echo $list['ref_name']; ?></td>
									<td><?php echo "VIEW"; if($user_flag != "3"){ ?> <a href="#" data-toggle="modal" data-target="#myModal<?php echo $list['request_id']; ?>" ><i class="fa fa-pencil-square-o"></i> </a><?php } ?></td>
									<td><?php echo $list['customization_request']."<br>";
									
									if(!empty($list['report'])){ 
									echo "<b>Reports / Comments: </b>".$list['report'];
									}
									?></td>
                                    <td><?php 
									
									if($user_flag == "3")
									{									
									echo $status_label; 
									}
									else  if($user_flag == "0" || $user_flag == "1")
									{
									?>
									<div class="btn-group">
								<?php 
								if($list['status']=="1"){
									$btn_type= "btn-warning";
								}else if($list['status']=="2"){
									$btn_type= "btn-success";
								}else if($list['status']=="3"){
									$btn_type= "btn-warning";
								}else if($list['status']=="4"){
									$btn_type= "btn-primary";
								}else if($list['status']=="5"){
									$btn_type= "btn-danger";
								}
								?>
                                        <button data-toggle="dropdown" class="btn <?php echo $btn_type;?> btn-xs dropdown-toggle"><?php echo $status; ?> <span class="caret"></span></button>
										<ul class="dropdown-menu">
										
									  <?php foreach($status_val as $key=>$value){ ?>
									   <li><a href="#" class="custom-status" data-status-id="<?php echo $value; ?>" data-request-id="<?php echo $list['request_id']; ?>"><?php echo $key; ?></a></li>
									  <?php } ?>
										</ul>
									</div>
									<?php } ?>
									
									</td>
                                    <td><?php if($list['completed_date']!="0000-00-00"){ echo date('d-M-Y',strtotime($list['completed_date'])); }?></td>
									<td><?php echo "V ".$list['release_version']; ?></td>
									<td><?php echo $list['customization_done_by']; ?></td>
                                    <td><?php echo $get_user[0]['chk_username']; ?></td>
                                </tr>
								
								<div class="modal inmodal" id="myModal<?php echo $list['request_id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog">
                                <div class="modal-content animated bounceInRight">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                           
                                            <h4 class="modal-title">Add Report / Comments</h4>
                                            
                                        </div>
										<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  action="add_details.php"  name="frmAddDiagnostic">
										<input type="hidden" name="request_id" value="<?php echo $list['request_id']; ?>" />
                                        <div class="modal-body">
                                            <div class="form-group"><label>Completed Date</label> <input id="dateadded<?php echo $list['request_id']; ?>" name="date_completed" type="text"  value="<?php if($list['completed_date']!="0000-00-00"){ echo date('m/d/Y',strtotime($list['completed_date'])); }?>" class="form-control" tabindex="2"></div>
											<div class="form-group">
											<div class="col-sm-6">
											<label>Assigned To(Developer)</label> <input type="text" name="assigned_to" value="<?php echo $list['customization_done_by']; ?>"  class="form-control" >
											</div>
											<div class="col-sm-6">
											<label>Released Version</label><input type="text" name="released_version" value="<?php echo $list['release_version']; ?>"  class="form-control" tabindex="5">
											</div>
											</div><br><br>
											
											<div class="form-group"><label>Customization Request</label> <textarea class="form-control" id="customRequest" name="customRequest" rows="4" tabindex="6"><?php echo $list['customization_request']; ?></textarea></div>
											
											<div class="form-group"><label>Report / Comments</label> <textarea class="form-control" id="customReport" name="customReport" rows="4" ><?php echo $list['report']; ?></textarea></div>
											
											
											
										
										</div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
											
                                            <button type="submit" name="add_report" class="btn btn-primary">Add</button>
											
                                        </div>
										</form>
                                    </div>
									</div>
								</div>
								    <script>
									$(document).ready(function() {
										$('#dateadded<?php echo $list['request_id']; ?>').datepicker({
											todayBtn: "linked",
											keyboardNavigation: false,
											forceParse: false,
											calendarWeeks: true,
											autoclose: true
										});
									});
									 </script>
										<?php  
										$i++;
										}
										?>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="8">
                                        <ul class="pagination pull-right"></ul>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
								<?php  
										
										}
										?>		
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


    <!-- Peity -->
    <script src="assets/js/plugins/peity/jquery.peity.min.js"></script>
    <!-- Peity demo -->
    <script src="assets/js/demo/peity-demo.js"></script>
<!-- FooTable -->
    <script src="assets/js/plugins/footable/footable.all.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="assets/js/inspinia.js"></script>
    <script src="assets/js/plugins/pace/pace.min.js"></script>

    <!-- Page-Level Scripts -->
    <script>
        $(document).ready(function() {

            $('.footable').footable();
            $('.footable2').footable();
			
			$('input:radio').change(function() {
		  // alert('ole');
		   window.location.href="<?php echo $webUrl; ?>";
		});
        });

    </script>
	<!-- Data picker -->
    <script src="assets/js/plugins/datapicker/bootstrap-datepicker.js"></script>
   <!-- Page-Level Scripts -->
    <script>
        $(document).ready(function() {

            $('.footable').footable();

            $('#dateadded').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true
            });
			
			$('#dateadded1').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true
            });

            $('#date_modified').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true
            });

        });

    </script>
	<script src="js/validation1.js" ></script>
</body>

</html>
