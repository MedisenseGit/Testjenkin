<?php
ob_start();
error_reporting(0); 
session_start();

$admin_id = $_SESSION['user_id'];
//Get the page name 
//$request_uri  = str_replace("", "", $_SERVER['REQUEST_URI']);
include('functions.php');
if(empty($admin_id)){
	header("Location:index.php");
}

require_once("../classes/querymaker.class.php");



$get_docInfo = mysqlSelect("*","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$admin_id."'","","","","");
$get_provHospInfo = mysqlSelect("*","hosp_tab as a left join doctor_hosp as b on a.hosp_id=b.hosp_id","b.doc_id='".$admin_id."'","","","","");
                
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>User Profile</title>

   <?php include_once('support.php'); ?>
	<link href="../assets/css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">	
	<link href="../assets/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">
	<link href="../assets/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
	<script language="JavaScript" src="js/status_validationJs.js"></script>
	</head>

<body>

    <div id="wrapper">

    <?php include_once('sidemenu.php'); ?>

        <div id="page-wrapper" class="gray-bg">
        <?php include_once('header_top.php'); ?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>Profile</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="Home">Home</a>
                        </li>
                       
                        <li class="active">
                            <strong>Your Profile</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-2">

                </div>
            </div>
        <div class="wrapper wrapper-content">
            <div class="row animated fadeInRight">
			<?php if($_GET['response']=="update"){ ?>
								<div class="alert alert-success alert-dismissable">
											<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										   <strong>Appointment timing has been updated successfully </strong>
								 </div>
								<?php } ?>
                <div class="col-md-4">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Profile Detail</h5>
                        </div>
                        <div>
                            <div class="ibox-content border-left-right">
							<?php if(!empty($get_docInfo[0]['doc_photo'])){ ?>
                                <img alt="image" class="img-xlg img-responsive" src="../Doc/<?php echo $get_docInfo[0]['ref_id']; ?>/<?php echo $get_docInfo[0]['doc_photo']; ?>">
                             <?php }  else { ?>
							 <img alt="image" class="img-xlg img-responsive" src="../assets/img/anonymous-profile.png">
                             <?php  } ?>
							</div>
                            <div class="ibox-content profile-content">
                                <h4><strong><?php echo $get_docInfo[0]['ref_name']; ?></strong><br><br><?php echo $get_docInfo[0]['spec_name']; ?></h4>
                                <p><i class="fa fa-map-marker"></i> <?php if(!empty($get_provHospInfo[0]['hosp_name'])){ echo $get_provHospInfo[0]['hosp_name']; } if(!empty($get_docInfo[0]['doc_city'])){ echo ", ".$get_docInfo[0]['doc_city'];} if(!empty($get_docInfo[0]['doc_state'])){ echo ", ".$get_docInfo[0]['doc_state']; } if(!empty($get_docInfo[0]['doc_country'])){ echo ", ".$get_docInfo[0]['doc_country']; } ?></p>
                                <br><h4><strong>
                                    About <?php echo $get_docInfo[0]['ref_name']; ?>
                                </strong></h4>
                                <p><?php if(!empty($get_docInfo[0]['ref_exp'])){ ?><b>Exp:</b> <?php echo $get_docInfo[0]['ref_exp']; ?> Yrs<br><?php } ?>
								   
								  <!-- start Interest/Expertise -->
								<?php if(!empty($get_docInfo[0]['doc_interest'])){ ?>
								 <br><b>Area's of Interest/Expertise</b><br>
								 <?php echo stripslashes($get_docInfo[0]['doc_interest']); ?><br>
								  <?php } ?>
								 <!-- end of Interest/Expertise -->
								 
								 <?php if(!empty($get_docInfo[0]['doc_contribute'])){ ?>
								 <br><b>Professional Contribution</b><br>
								 <?php echo stripslashes($get_docInfo[0]['doc_contribute']); ?><br>
								 <?php } ?>
								<!-- end of Contribution -->
								
								 <?php if(!empty($get_docInfo[0]['doc_pub'])){ ?>
								 <br><b>Publications</b><br>
								 <?php echo stripslashes($get_docInfo[0]['doc_pub']); ?><br>
								 <?php } ?>
								<!-- end of Publications -->
								
								
                                </p>
                              
                            </div>
                    </div>
                </div>
                    </div>
                <div class="col-md-8">
                    <div class="ibox float-e-margins">
                        
                        <div class="ibox-content">
							<div class="tabs-container">
                        <ul class="nav nav-tabs">
                            <li><a href="Profile"><i class="fa fa-pencil-square-o"></i>Edit Profile</a></li>
                            <li class="active"><a href="#"><i class="fa fa-calendar"></i>Set Appointment Timing</a></li>
                            <li><a href="Password"><i class="fa fa-key"></i>Change Password</a></li>
                        </ul>
                        <div class="tab-content">
                            				
							
							<div class="tab-pane active">
							
                                <div class="panel-body">
								
									<div class="form-group">
											
										<div class="col-sm-12">
										
										<div class="title">
											<h4>Set Appointment Timing</h4>
										</div>
								<form enctype="multipart/form-data" action="add_details.php" method="post" class="form-horizontal" id="frmAddDoctor">
                                <input type="hidden" name="Prov_Id"	value="<?php echo $admin_id; ?>" />
								
										<table border="1" width="100%">
									<tr>
									<td style="text-align:center; font-weight:bold;">Schedule</td>
									<?php
									$getDays = mysqlSelect("*","seven_days ","","","","","");


										foreach($getDays as $daysList) 
										{
											?>
											<td style="text-align:center; font-weight:bold;"><?php echo $daysList['da_name']; ?></td>
											<?php
										}

									?>
									</tr>
									<?php

									$getTimings = mysqlSelect("*","timings ","","","","","");
										$i=0;
										foreach($getTimings as $timeList) 
										{
											
											
											$i++;
											$j=0;
											?>
											<tr>
											<td style="text-align:center; "><input type="hidden" name="<?php echo "time_id" .$i ?>" value="<?php echo $timeList['Timing_id']?>" /><?php echo $timeList['Timing']?></td>
											<?php
											$getDaycount = mysqlSelect("*","seven_days ","","","","","");
											foreach($getDaycount as $countList) 
												{
													
													$j++;
													 $chkDay = mysqlSelect("*","doc_time_set","doc_id=".$admin_id." and time_set=1 and day_id=".$j." and time_id=".$i,"","","","");
													?> 	
														 <td style="text-align:center;">
														
														 <input type="hidden" size="4" value="<?php echo $countList['day_id'] ?>" name="<?php echo "day_id" . $i . $j ?>">
															<?php if($chkDay==true){ ?>
															<div class="checkbox checkbox-success checkbox-inline">
																<input type="checkbox" id="inlineCheckbox2" checked="true" value="1" name="<?php echo "time". $i . $j ?>">
																<label for="inlineCheckbox2"></label>
															</div>
															
															<?php } else { ?>
															<div class="checkbox checkbox-success checkbox-inline">
																<input type="checkbox" id="inlineCheckbox<?php echo $j; ?>" value="1" name="<?php echo "time". $i . $j ?>">
																<label for="inlineCheckbox2"></label>
															</div>
															
															<?php } ?>
															<input type="hidden" name="limit_j" value="<?php echo $j; ?>" size="4">
															</td>
														
													<?php
												}
												
												
										
											?></tr>
											<input type="hidden" name="limit_i" value="<?php echo $i; ?>" size="4">
											<?php
											

										}


									?>
									</table>
									<br>
									<div class="form-group">
								<div class="col-sm-4 pull-right">
								<button type="submit" name="edit_timings" id="edit_timings" class="btn btn-primary block full-width m-b ">UPDATE</button>
								</div>
								</div>
								</form>
								<br><br>
								
										<div class="title">
											<h4>Set Holidays</h4>
										</div>
										
										<div class="form-group">
										<form action="add_details.php" method="post" class="form-horizontal" id="frmHoliday">
										<input type="hidden" name="Prov_Id"	value="<?php echo $admin_id; ?>" />
								
										<div class="col-sm-4 pull-left m-r input-group date">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input id="dateadded" name="dateadded" type="text" placeholder="Select date" required="required" class="form-control" >
										</div>
										<div class="col-sm-4 pull-left input-group">
											<input placeholder="Enter reason" type="text" name="txt_desc" class="form-control" >
										</div>
										<div class="col-sm-3 pull-left">
										<button type="submit" name="add_holiday" id="add_holiday" class="btn btn-primary block full-width m-b ">ADD</button>
										</div>
										</form>
										</div>
										 <div class="ibox-content" id="allHList">
										<table class="table">
                                <thead>
                                <tr>
                                    <th>
                                        Holiday Date
                                    </th>
                                    <th>
                                        Reason
                                    </th>
									<th>
                                        Delete
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
								<?php
								$get_Holidaylist = mysqlSelect("*","doc_holidays","doc_id='".$admin_id."'","holiday_id desc","","","");

								foreach($get_Holidaylist as $holidayList)
								{
								?>
                                <tr>
                                    <td><code><?php echo date('d-M-Y',strtotime($holidayList['holiday_date'])); ?></code></td>
                                    <td><span class="text-muted"><?php echo $holidayList['reason']; ?></span></td>
									<td><a href="#" onclick="return deleteHoliday(<?php echo $holidayList['holiday_id']; ?>);"><span class="label label-danger">REMOVE</span></a></td>
                                </tr>
                                <?php } ?>
                                </tbody>
								
                            </table>
							</div>
							<div id="afterHList"></div>
										
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
         <?php include_once('footer.php'); ?>

        </div>
        </div>



    <!-- Mainly scripts -->
    <script src="../assets/js/jquery-3.1.1.min.js"></script>
    <script src="../assets/js/bootstrap.js"></script>
    <script src="../assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="../assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="../assets/js/inspinia.js"></script>
    <script src="../assets/js/plugins/pace/pace.min.js"></script>

    <!-- Peity -->
    <script src="../assets/js/plugins/peity/jquery.peity.min.js"></script>

    <!-- Peity -->
    <script src="../assets/js/demo/peity-demo.js"></script>
 <!-- Chosen -->
    <script src="../assets/js/plugins/chosen/chosen.jquery.js"></script>
	<script>
       
        $('.chosen-select').chosen({width: "100%"});



    </script>
	<!-- Switchery -->
   <script src="../assets/js/plugins/switchery/switchery.js"></script>
    <!-- FooTable -->
    <script src="../assets/js/plugins/footable/footable.all.min.js"></script>
<!-- Data picker -->
    <script src="../assets/js/plugins/datapicker/bootstrap-datepicker.js"></script>
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

            $('#date_modified').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true
            });

        });

    </script>
</body>

</html>
