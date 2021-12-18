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


		if(!isset($_GET['start'])) {
			$start = 0;
			}else{
			$start = $_GET['start'];
			}

			$eu = ($start - 0); 
			$limit = 50;         // No of records to be shown per page.
			$this1 = $eu + $limit; 
			$back = $eu - $limit; 
			$next = $eu + $limit;
$busResult = mysqlSelect("a.ref_id as Doc_Id,a.ref_name as Doc_Name,a.ref_address as Doc_City,a.doc_state as Doc_State,c.hosp_name as Hosp_Name,c.hosp_city as Hosp_City,c.hosp_state as Hosp_State","referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id","c.hosp_id='".$admin_id."'","a.ref_id desc","","","");
$pag_result = mysqlSelect("a.ref_id as Doc_Id","referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id","c.hosp_id='".$admin_id."'","a.ref_id desc","");
$pageing = firstPaging($pag_result,$limit,$back,$next,$eu,$field,$type2);
$arrPage = explode("-",$pageing);  

//Search By Name,Email,Location & Contact No.
if(isset($_POST['postTextSrchCmd'])){
	$txtSearch = addslashes($_POST['postTextSrch']);
	?>
	<SCRIPT LANGUAGE="JavaScript">	
	window.location.href="Add-Hospital-Doctors?docsrch=<?php echo $txtSearch; ?>";
	</SCRIPT>
	<?php
}
if(isset($_GET['docsrch'])){
	$SerchResult = mysqlSelect("a.ref_id as Doc_Id,a.ref_name as Doc_Name,a.ref_address as Doc_City,a.doc_state as Doc_State,c.hosp_name as Hosp_Name,c.hosp_city as Hosp_City,c.hosp_state as Hosp_State","referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id","(c.company_id='".$_POST['compny_id']."') and (a.ref_name LIKE '%".$txtSearch."'or c.hosp_name ='".$txtSearch."%' or c.hosp_city ='".$txtSearch."%')" ,"a.ref_id desc","","","");	
	} 

              
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Hospital List</title>

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
                <div class="col-lg-10">
                    <h2>Hospital Doctor List</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="Home">Home</a>
                        </li>
                        
                        <li class="active">
                            <strong>Hospital Doctor List</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-2">

                </div>
            </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-6">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Hospital Doctor List</h5>
                        
                    </div>
                    <div class="ibox-content table-responsive">

                        <table class="table table-striped">
                            <thead>
                            <tr>
                               <th style="width:200px;">Doctor</th>
								<th style="width:200px;">Hospital</th>
								<th style="width:50px;">Edit</th>
                                            
                            </tr>
                            </thead>
                            <tbody>
							<?php if(empty($busResult)) { ?><tr>
                                            <td colspan="5"><center>No result found</center></td>
                                        </tr> <?php }
										if(!empty($busResult)){	
												?>
							<?php foreach($busResult as $list){ ?>
                           <tr>
                                            <td><?php echo $list['Doc_Name'].", ".$list['Doc_City'].", ".$list['Doc_State'];  ?></td> 
											<td><?php echo $list['Hosp_Name'].", ".$list['Hosp_City'].", ".$list['Hosp_State'];  ?></td> 
                                         <td><center> <a href="Edit-Doctor?doc_id=<?php echo $list['Doc_Id']; ?>" class="btn btn-white btn-bitbucket">
             <i class="fa fa-edit"></i></a></center></td>
                                        </tr>
									<?php } 
										}
										if(!empty($SerchResult)){	
												?>
									<?php foreach($SerchResult as $list){ ?>
                                        <tr>
                                            <td><?php echo $list['Doc_Name'].", ".$list['Doc_City'].", ".$list['Doc_State'];  ?></td> 
											<td><?php echo $list['Hosp_Name'].", ".$list['Hosp_City'].", ".$list['Hosp_State'];  ?></td> 
                                              <td><center> <a href="Edit-Doctor?doc_id=<?php echo $list['Doc_Id']; ?>" class="btn btn-app">
                   <i class="fa fa-edit"></i> Edit </a></center></td>
                                        </tr>
									<?php } 
										}?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="ibox float-e-margins" id="addDoctorSection">
					<?php
						if($_GET['response']=="add"){ ?>
					<div class="alert alert-success alert-dismissable">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                               <strong>SUCCESS !! Details are added successfully.</strong>
                     </div>
					<?php 
						} else if($_GET['response']=="update"){ ?>
						<div class="alert alert-success alert-dismissable">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                               <strong>UPDATED!! Details are updated successfully.</strong>
                     </div>
						<?php } else if($_GET['response']=="error"){ ?>
						<div class="alert alert-danger alert-dismissable">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                               <strong>Error!!! please fill required field properly.</strong>
                     </div>
						<?php } ?>
                    <div class="ibox-title">
                        <h5><i class="fa fa-calendar"></i> ADD HOSPITAL DOCTOR</h5>
                       
                    </div>
                    <div class="ibox-content">

                        <div class="panel-body">
                                    <form enctype="multipart/form-data"  class="form-horizontal" action="add_details.php" method="post" name="frmAddHosp" id="frmAddHosp" >
                               <div class="form-group"><label class="col-sm-2 control-label">Doctor Profile picture<span class="required">*</span></label>

                                    <div class="col-sm-10"><label title="Upload image file" for="inputImage" class="btn btn-primary">
                                        <input type="file" id="inputImage" name="txtPhoto" multiple="true" class="hide">
                                        <i class="fa fa-upload"></i> Upload Profile picture
                                    </label></div>
                                </div>
								
								<div class="form-group"><label class="col-sm-2 control-label">Doctor Name <span class="required">*</span></label>

                                    <div class="col-sm-10"><input type="text" name="txtDoc" required="required" class="form-control"></div>
                                </div>
								
                                 <script type="text/javascript">
												function getState(val) { 
													var data_val = $("#txtCountry option:selected").attr("myTag");
													$('#cntryid').val(data_val);
													$.ajax({
													type: "POST",
													url: "get_state.php",
													data:'country_name='+val,
													success: function(data){
														$("#slctState").html(data);
													}
													});
												}
								</script>
							   <div class="form-group"><label class="col-sm-2 control-label">Country <span class="required">*</span></label>

                                    <div class="col-sm-10">
                                    	<input type="hidden" id="cntryid" name="countryId">
                                    	<select data-placeholder="Choose a Country..." class="form-control autotab" name="txtCountry" id="txtCountry" tabindex="2" onchange="return getState(this.value); " required>
											<option value="" selected>Select Country</option>
												<?php 
												$getCountry= mysqlSelect("*","countries","","country_name asc","","","");
													$i=30;
													foreach($getCountry as $CountryList){
												?> 
														
														<option value="<?php echo stripslashes($CountryList['country_name']); ?>" myTag="<?php echo stripslashes($CountryList['country_id']); ?>"/>
														<?php echo stripslashes($CountryList['country_name']);?></option>
													
													
													<?php 
														$i++;
													}?> 
										</select>
									</div>
									</div>
									<div class="form-group"><label class="col-sm-2 control-label">State <span class="required">*</span></label>

                                    <div class="col-sm-10"><select data-placeholder="Choose a State..." class="form-control"required="required" name="slctState" id="slctState" tabindex="2">
											<option value="">Select State</option>
													<?php
													$GetState = mysqlSelect("*","countries as a left join states as b on a.country_id=b.country_id", "a.country_id=100", "b.state_name asc", "", "", "");
													foreach ($GetState as $StateList) {
													?>
													<option value="<?php echo $StateList["state_name"];	?>"><?php echo $StateList["state_name"]; ?></option>
													
													<?php
													}
													?>
										</select>
									</div>
                                </div>
								
								<div class="form-group"><label class="col-sm-2 control-label">City <span class="required">*</span></label>

                                    <div class="col-sm-10"><input type="text" name="txtCity" required="required" class="form-control"></div>
                                </div>
								<!--<div class="form-group"><label class="col-sm-2 control-label">Select Hospital <span class="required">*</span></label>

                                    <div class="col-sm-10"><select data-placeholder="Choose a State..." class="chosen-select" required="required" name="selectHosp" id="selectHosp" tabindex="2">
											 <option value="">---Please Select---</option>
												<?php
												$GetHosp = mysqlSelect("*", "hosp_tab", "company_id='".$admin_id."'", "hosp_name asc", "", "", "");
												foreach ($GetHosp as $HospList) {
												?>
													<option value="<?php echo $HospList["hosp_id"];	?>"><?php echo $HospList["hosp_name"].", ".$HospList["hosp_city"].", ".$HospList["hosp_state"];	?></option>
												<?php
												}
												?>
										</select>
									</div>
                                </div>-->
								<div class="form-group"><label class="col-sm-2 control-label">Select Specialization <span class="required">*</span></label>

                                    <div class="col-sm-10"><select data-placeholder="Choose a Specialization..." class="chosen-select" required="required" name="slctSpec" id="slctSpec" tabindex="2">
											 <option value="" >Select Specialization</option>
												<?php $DeptName= mysqlSelect("*","specialization","","spec_name asc","","","");
												$i=30;
												foreach($DeptName as $DeptList){
													if($DeptList['spec_id']==$get_provInfo[0]['doc_spec']){ ?> 
												<option value="<?php echo stripslashes($DeptList['spec_id']);?>" selected /><?php echo stripslashes($DeptList['spec_name']);?></option>
												<?php 
													}?>

													<option value="<?php echo stripslashes($DeptList['spec_id']);?>" /><?php echo stripslashes($DeptList['spec_name']);?></option>
												<?php
														$i++;
												}?> 
												</select>
									</div>
                                </div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Qualification <span class="required">*</span></label>

                                    <div class="col-sm-10"><input type="text" name="txtQual" required="required" class="form-control"></div>
                                
								</div>
								
								<div class="form-group"><label class="col-sm-2 control-label">Years of Experience <span class="required">*</span></label>

                                    <div class="col-sm-6"><input type="text" class="form-control" name="txtExp" required="required" placeholder=""></div>
										
                                </div>
								<div class="form-group">
								<label class="col-sm-2 control-label">Email Address</label>

                                    <div class="col-sm-10"><input type="email" name="txtEmail" class="form-control"></div>
								</div>
								<div class="form-group">
								<label class="col-sm-2 control-label">Mobile No.</label>

                                    <div class="col-sm-10"><input type="text" name="txtMobile" class="form-control"></div>
								</div>
								<div class="form-group">
								<label class="col-sm-2 control-label">Website</label>

                                    <div class="col-sm-10"><input type="text" name="txtWebsite" class="form-control"></div>
								</div>
								
								<div class="form-group"><label class="col-sm-2 control-label">Area's of Interest, Expertise</label>

                                    <div class="col-sm-10"><textarea class="form-control" name="txtInterest" rows="3"></textarea></div>
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">Professional Contributions</label>

                                    <div class="col-sm-10"><textarea class="form-control" name="txtContribute" rows="3"></textarea></div>
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">Research Details</label>

                                    <div class="col-sm-10"><textarea class="form-control" name="txtResearch" rows="3"></textarea></div>
                                </div>	
								<div class="form-group"><label class="col-sm-2 control-label">Publications</label>

                                    <div class="col-sm-10"><textarea class="form-control" name="txtPublication" rows="3"></textarea></div>
                                </div>	
								<div class="form-group">
								<label class="col-sm-2 control-label">Online Tele Consultation Cost(Rs.)</label>

                                    <div class="col-sm-10"><input type="text" class="form-control" name="onopcost"></div>
								<!--	<label class="col-sm-2 control-label">Consultation Charge(Rs.)</label>

                                    <div class="col-sm-4"><input type="text" name="conscharge" class="form-control"></div>-->
								
                                </div>	
								<div class="form-group">
								<label class="col-sm-2 control-label">Secretary Email Id</label>

                                    <div class="col-sm-10"><input type="text" name="txtSecEmail" class="form-control"></div>
								</div>
								<div class="form-group">
								<label class="col-sm-2 control-label">Secretary Phone</label>

                                    <div class="col-sm-10"><input type="text" name="txtSecPhone" class="form-control"></div>
								</div>
								<div class="form-group">
								<label class="col-sm-2 control-label">Ready for tele opinion ?</label>

                                <div class="col-sm-10"><label>
								<input type="checkbox" class="flat" name="teleop" value="1" > Yes
								</label><input type="text" name="txtSecPhone" class="form-control" placeholder="Tele Op. contact no."></div>
								</div>
								<div class="form-group">
								<label class="col-sm-2 control-label">Ready for video opinion ?</label>

                                <div class="col-sm-10"><label>
								<input type="checkbox" class="flat" name="videoop" value="1"> Yes
								</label><input type="text" id="videoopnumber" name="videoopnumber" class="form-control" placeholder="Video Op. contact no."></div>
								</div>
								
								<div class="form-group">
								<label class="col-sm-2 control-label">Available timings for Tele/Video Opinion</label>

                                    <div class="col-sm-10"><input type="text" name="televidop_time" class="form-control"></div>
								</div>
								<div class="form-group"><label class="col-sm-2 control-label">No. of Patient per hour <span class="required">*</span></label>

                                    <div class="col-sm-10"><input type="number" name="num_slot" value=""  class="form-control" required="required"></div>
                                </div>
                               <div class="form-group"><label class="col-sm-2 control-label">Set Tele Consultation Appointment Timing</label>

                                    <div class="col-sm-10"><table border="1" width="100%">
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
						</table></div>
                                </div>
								<div class="form-group">
								<div class="col-sm-6 pull-right">
								<button type="submit" name="add_doctor" class="btn btn-primary block full-width m-b ">ADD</button>
								</div>
								</div>
							</form>
							</div>
							
							
							
							
                    </div>
                </div>
				
				<!-- EDIT HOSPITAL SECTION -->
				<div id="editContent"></div> 
				
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

    <!-- Peity -->
    <script src="../assets/js/plugins/peity/jquery.peity.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="../assets/js/inspinia.js"></script>
    <script src="../assets/js/plugins/pace/pace.min.js"></script>

    <!-- iCheck -->
    <script src="../assets/js/plugins/iCheck/icheck.min.js"></script>

    <!-- Peity -->
    <script src="../assets/js/demo/peity-demo.js"></script>

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
