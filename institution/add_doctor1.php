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
// $busResult = mysqlSelect("a.ref_id as Doc_Id,a.ref_name as Doc_Name,a.ref_address as Doc_City,a.doc_state as Doc_State,c.hosp_name as Hosp_Name,c.hosp_city as Hosp_City,c.hosp_state as Hosp_State","referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id","c.company_id='".$admin_id."'","a.ref_id desc","","","$eu, $limit");

$busResult = mysqlSelect("a.ref_id as Doc_Id,a.ref_name as Doc_Name,a.ref_address as Doc_City,a.doc_state as Doc_State,c.hosp_name as Hosp_Name,c.hosp_city as Hosp_City,c.hosp_state as Hosp_State","referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id","","a.ref_id desc","","","$eu, $limit");

// $pag_result = mysqlSelect("a.ref_id as Doc_Id","referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id","c.company_id='".$admin_id."'","a.ref_id desc","");

$pag_result = mysqlSelect("a.ref_id as Doc_Id","referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id","","a.ref_id desc","");

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
                                              <td><center> <a href="Edit-Doctor1?doc_id=<?php echo $list['Doc_Id']; ?>" class="btn btn-white btn-bitbucket">
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
                                                    <td><center> <a href="Edit-Doctor1?doc_id=<?php echo $list['Doc_Id']; ?>"  class="btn btn-app">
                      <i class="fa fa-edit"></i> Edit </a></center></td><!--onclick="return showDoctor(<?php echo $list['Doc_Id']; ?>);"-->
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
                                    <form enctype="multipart/form-data"  class="form-horizontal" action="add_details1.php" method="post" name="frmAddHosp" id="frmAddHosp" >
                               <div class="form-group"><label class="col-sm-2 control-label">Doctor Profile picture<span class="required">*</span></label>

                                    <div class="col-sm-10"><label title="Upload image file" for="inputImage" class="btn btn-primary">
                                        <input type="file" id="inputImage" name="txtPhoto" required="required" multiple="true" class="hide">
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
                                    	<select data-placeholder="Choose a Country..." class="form-control autotab" name="txtCountry" id="txtCountry" tabindex="2" onchange="return getState(this.value); " required="">
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

                                    <div class="col-sm-10"><select data-placeholder="Choose a State..." class="form-control" required="required" name="slctState" id="slctState" tabindex="2">
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
								<div class="form-group"><label class="col-sm-2 control-label">Select Hospital <span class="required">*</span></label>

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
                                </div>
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
								<div class="form-group"><label class="col-sm-2 control-label">Online Opinion Cost(Rs.)</label>

                                    <div class="col-sm-4"><input type="text" class="form-control" name="onopcost"></div>
									<label class="col-sm-2 control-label">Consultation Charge(Rs.)</label>

                                    <div class="col-sm-4"><input type="text" name="conscharge" class="form-control"></div>
								
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
								
								
								
								
											<!------------------------------------------------------------STERT TABLE-----------------------------------------------------------------------------------> 
								<div class="form-group"><label class="col-sm-2 control-label">Set Appointment Timing</label>
									<div class="col-sm-10">
										<table border="" width="100%" id="tablesun">
											<thead>
												<tr>
													<th>Schedule</th>
													<th>From</th>
													<th>To</th>
												  <th>Action</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td style="padding-right: 31px;">Sun<input type="hidden" id="Sunid" name="Sunid[]" value="1" ></td>
													<td>
														<select id="from" name="fromSun[]">
															<option value="">Select</option>
															<?php
																$Time = mysqlSelect("*", "doctor_appointment_timings", "", "", "", "", "");
																$i       = 30;
																foreach ($Time as $TimeList) {
																	
															?> 
															<option value="<?php echo $TimeList['time_val'] ?>"><?php echo $TimeList['time_name'] ?> </option>
															<?php } ?>
														
														</select>
													</td>
													<td>
														<select id="to" name="toSun[]">
														<option value="">Select</option>
														<?php
															$Time = mysqlSelect("*", "doctor_appointment_timings", "", "", "", "", "");
															$i       = 30;
															foreach ($Time as $TimeList) {
																
														?> 
														
														<option value="<?php echo $TimeList['time_val'] ?>"><?php echo $TimeList['time_name'] ?> </option>
														<?php } ?>
														</select>
													</td>
													<td><input type="button" id="email" onclick="addrow(1);"  value="Add Row" /></td>
												</tr>	
											</tbody>
										</table>

										<table border="" width="100%" id="tablemon">
											<tbody>
											<tr>
												<td style="padding-right: 31px;">Mon<input type="hidden" id="Monid" name="Monid[]" value="2" ></td>
												<td>
													<select id="from" name="fromMon[]">
														<option value="">Select</option>
														<?php
															$Time = mysqlSelect("*", "doctor_appointment_timings", "", "", "", "", "");
															$i       = 30;
															foreach ($Time as $TimeList) {
																
														?> 
														<option value="<?php echo $TimeList['time_val'] ?>"><?php echo $TimeList['time_name'] ?> </option>
														<?php } ?>
													</select>

												</td>
												<td>
													<select id="to" name="toMon[]">
														<option value="">Select</option>
														<?php
															$Time = mysqlSelect("*", "doctor_appointment_timings", "", "", "", "", "");
															$i       = 30;
															foreach ($Time as $TimeList) {
																
														?> 
														<option value="<?php echo $TimeList['time_val'] ?>"><?php echo $TimeList['time_name'] ?> </option>
														<?php } ?>
												</td>
												<td><input type="button" id="email" onclick="addrow(2);"  value="Add Row" /></td>
											</tr>	
										
										</tbody>
										</table>



										<table border="" width="100%" id="tableTue">
											<thead>
												
											</thead>
											<tbody>

												<tr>
													<td style="padding-right: 35px;">Tue<input type="hidden" id="Tueid" name="Tueid[]" value="3" ></td>
													<td>
														<select id="from" name="fromTue[]">
															<option value="">Select</option>
															<?php
															$Time = mysqlSelect("*", "doctor_appointment_timings", "", "", "", "", "");
															$i       = 30;
															foreach ($Time as $TimeList)
															{
																
															?> 
																<option value="<?php echo $TimeList['time_val'] ?>"><?php echo $TimeList['time_name'] ?> </option>
															<?php 
															}
															?>
														</select>

													</td>
													<td>
													<select id="to" name="toTue[]">
														<option value="">Select</option>
															<?php
															$Time = mysqlSelect("*", "doctor_appointment_timings", "", "", "", "", "");
															$i       = 30;
															foreach ($Time as $TimeList)
															{
																
															?> 
																<option value="<?php echo $TimeList['time_val'] ?>"><?php echo $TimeList['time_name'] ?> </option>
															<?php 
															}
															?>
													</select>
													</td>
													<td><input type="button" id="email" onclick="addrow(3);"  value="Add Row" /></td>
												</tr>	
												
											</tbody>
										</table>
										

										<table border="" width="100%" id="tableWed">
											<thead>
												
											</thead>
											<tbody>

											<tr>
												<td style="padding-right: 31px;">Wed<input type="hidden" id="Wedid" name="Wedid[]" value="4" ></td>
												<td>
													<select id="from" name="fromWed[]">
														<option value="">Select</option>
														<?php
														$Time = mysqlSelect("*", "doctor_appointment_timings", "", "", "", "", "");
														$i       = 30;
														foreach ($Time as $TimeList)
														{
															
														?> 
															<option value="<?php echo $TimeList['time_val'] ?>"><?php echo $TimeList['time_name'] ?> </option>
														<?php 
														}
														?>
													</select>
												</td>
												<td>
												<select id="to" name="toWed[]">
													<option value="">Select</option>
													<?php
													$Time = mysqlSelect("*", "doctor_appointment_timings", "", "", "", "", "");
													$i       = 30;
													foreach ($Time as $TimeList)
													{
														
													?> 
														<option value="<?php echo $TimeList['time_val'] ?>"><?php echo $TimeList['time_name'] ?> </option>
													<?php 
													}
													?>
												</select>
												</td>
												<td><input type="button" id="email" onclick="addrow(4);"  value="Add Row" /></td>
											</tr>
											</tbody>
										</table>
										
										
										
										<table border="" width="100%" id="tableThu">
											<thead>
												
											</thead>
											<tbody>
											<tr>
												<td style="padding-right: 35px;">Thu<input type="hidden" id="Thuid" name="Thuid[]" value="5" ></td>
												<td>
													<select id="from" name="fromThu[]">
														<option value="">Select</option>
														<?php
														$Time = mysqlSelect("*", "doctor_appointment_timings", "", "", "", "", "");
														$i       = 30;
														foreach ($Time as $TimeList)
														{
															
														?> 
															<option value="<?php echo $TimeList['time_val'] ?>"><?php echo $TimeList['time_name'] ?> </option>
														<?php 
														}
														?>
													</select>
												</td>
												<td>
													<select id="to" name="toThu[]">
														<option value="">Select</option>
														<?php
														$Time = mysqlSelect("*", "doctor_appointment_timings", "", "", "", "", "");
														$i       = 30;
														foreach ($Time as $TimeList)
														{
															
														?> 
															<option value="<?php echo $TimeList['time_val'] ?>"><?php echo $TimeList['time_name'] ?> </option>
														<?php 
														}
														?>
													</select>
												</td>
												<td><input type="button" id="email" onclick="addrow(5);"  value="Add Row" /></td>
											</tr>
											</tbody>
										</table>
												
										<table border="" width="100%" id="tableFri">
											<thead>
												
											</thead>
											<tbody>
											<tr>
												<td style="padding-right: 43px;">Fri<input type="hidden" id="Friid" name="Friid[]" value="6" ></td>
												<td>
													<select id="from" name="fromFri[]">
														<option value="">Select</option>
														<?php
														$Time = mysqlSelect("*", "doctor_appointment_timings", "", "", "", "", "");
														$i       = 30;
														foreach ($Time as $TimeList)
														{
															
														?> 
															<option value="<?php echo $TimeList['time_val'] ?>"><?php echo $TimeList['time_name'] ?> </option>
														<?php 
														}
														?>
													</select>
												</td>
												<td>
													<select id="to" name="toFri[]">
														<option value="">Select</option>
														<?php
														$Time = mysqlSelect("*", "doctor_appointment_timings", "", "", "", "", "");
														$i       = 30;
														foreach ($Time as $TimeList)
														{
															
														?> 
															<option value="<?php echo $TimeList['time_val'] ?>"><?php echo $TimeList['time_name'] ?> </option>
														<?php 
														}
														?>
													</select>
												</td>
												<td><input type="button" id="email" onclick="addrow(6);"  value="Add Row" /></td>
											</tr>
											</tbody>
										</table>

										<table border="" width="100%" id="tableSat">
											<thead></thead>
											<tbody>
											<tr>
												<td style="padding-right: 39px;">Sat<input type="hidden" id="Satid" name="Satid[]" value="7" ></td>
												<td>
													<select id="from" name="fromSat[]">
														<option value="">Select</option>
														<?php
														$Time = mysqlSelect("*", "doctor_appointment_timings", "", "", "", "", "");
														$i       = 30;
														foreach ($Time as $TimeList)
														{
															
														?> 
															<option value="<?php echo $TimeList['time_val'] ?>"><?php echo $TimeList['time_name'] ?> </option>
														<?php 
														}
														?>
													</select>
												</td>
												<td>
												<select id="to" name="toSat[]">
													<option value="">Select</option>
													<?php
													$Time = mysqlSelect("*", "doctor_appointment_timings", "", "", "", "", "");
													$i       = 30;
													foreach ($Time as $TimeList)
													{
													?> 
														<option value="<?php echo $TimeList['time_val'] ?>"><?php echo $TimeList['time_name'] ?> </option>
													<?php 
													}
													?>
												</select>
												</td>
												<td><input type="button" id="email" onclick="addrow(7);"  value="Add Row" /></td>
											</tr>
											</tbody>
										</table>
									</div>
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


<script>

function addrow(val)
{
	
	var from = $("#from").val();
	var to = $("#to").val();
	
	if(val==1)
	{
		var markup = "<tr><td></td><td><input type='hidden' id='Sunid' name='Sunid[]' value='1' ><select id='from' name='fromSun[]'><option value='00:00'>12.00 AM</option><option value='00:30'>12.30 AM</option><option value='01:00'>01.00 AM</option><option value='01:30'>01.30 AM</option><option value='02:00'>02.00 AM</option><option value='02:30'>02.30 AM</option><option value='03:00'>03.00 AM</option><option value='03:30'>03.30 AM</option><option value='04:00'>04.00 AM</option><option value='04:30'>04.30 AM</option><option value='05:00'>05.00 AM</option><option value='05:30'>05.30 AM</option><option value='06:00'>06.00 AM</option><option value='06:30'>06.30 AM</option><option value='07:00'>07.00 AM</option><option value='07:30'>07.30 AM</option><option value='08:00'>08.00 AM</option><option value='08:30'>08.30 AM</option><option value='09:00'>09.00 AM</option><option value='09:30'>09.30 AM</option><option value='10:00'>10.00 AM</option><option value='10:30'>10.30 AM</option><option value='11:00'>11.00 AM</option><option value='11:30'>11.30 AM</option><option value='12:00'>12.00 PM</option><option value='12:30'>12.30 PM</option><option value='13:00'>01.00 PM</option><option value='13:30'>01.30 PM</option><option value='14:00'>02.00 PM</option><option value='14:30'>02.30 PM</option><option value='15:00'>03.00 PM</option><option value='15:30'>03.30 PM</option><option value='16:00'>04.00 PM</option><option value='16:30'>04.30 PM</option><option value='17:00'>05.00 PM</option><option value='17:30'>05.30 PM</option><option value='18:00'>06.00 PM</option><option value='18:30'>06.30 PM</option><option value='19:00'>07.00 PM</option><option value='19:30'>07.30 PM</option><option value='20:00'>08.00 PM</option><option value='20:30'>08.30 PM</option><option value='21:00'>09.00 PM</option><option value='21:30'>09.30 PM</option><option value='22:00'>10.00 PM</option><option value='22:30'>10.30 PM</option><option value='23:00'>11.00 PM</option><option value='23:30'>11.30 PM</option></select></td><td><select id='to' name='toSun[]'><option value='00:00'>12.00 AM</option><option value='00:30'>12.30 AM</option><option value='01:00'>01.00 AM</option><option value='01:30'>01.30 AM</option><option value='02:00'>02.00 AM</option><option value='02:30'>02.30 AM</option><option value='03:00'>03.00 AM</option><option value='03:30'>03.30 AM</option><option value='04:00'>04.00 AM</option><option value='04:30'>04.30 AM</option><option value='05:00'>05.00 AM</option><option value='05:30'>05.30 AM</option><option value='06:00'>06.00 AM</option><option value='06:30'>06.30 AM</option><option value='07:00'>07.00 AM</option><option value='07:30'>07.30 AM</option><option value='08:00'>08.00 AM</option><option value='08:30'>08.30 AM</option><option value='09:00'>09.00 AM</option><option value='09:30'>09.30 AM</option><option value='10:00'>10.00 AM</option><option value='10:30'>10.30 AM</option><option value='11:00'>11.00 AM</option><option value='11:30'>11.30 AM</option><option value='12:00'>12.00 PM</option><option value='12:30'>12.30 PM</option><option value='13:00'>01.00 PM</option><option value='13:30'>01.30 PM</option><option value='14:00'>02.00 PM</option><option value='14:30'>02.30 PM</option><option value='15:00'>03.00 PM</option><option value='15:30'>03.30 PM</option><option value='16:00'>04.00 PM</option><option value='16:30'>04.30 PM</option><option value='17:00'>05.00 PM</option><option value='17:30'>05.30 PM</option><option value='18:00'>06.00 PM</option><option value='18:30'>06.30 PM</option><option value='19:00'>07.00 PM</option><option value='19:30'>07.30 PM</option><option value='20:00'>08.00 PM</option><option value='20:30'>08.30 PM</option><option value='21:00'>09.00 PM</option><option value='21:30'>09.30 PM</option><option value='22:00'>10.00 PM</option><option value='22:30'>10.30 PM</option><option value='23:00'>11.00 PM</option><option value='23:30'>11.30 PM</option></select></td></tr>";
		
		$("#tablesun").append(markup);
		$("body").on("click",".remove-btn",function(e){
		$(this).parents('.work_his_data').remove();
		//the above method will remove the user_data div
		});
	}
	else if(val==2)
	{
		var markup = "<tr><td></td><td><input type='hidden' id='Monid' name='Monid[]' value='2' ><select id='from' name='fromMon[]'><option value='00:00'>12.00 AM</option><option value='00:30'>12.30 AM</option><option value='01:00'>01.00 AM</option><option value='01:30'>01.30 AM</option><option value='02:00'>02.00 AM</option><option value='02:30'>02.30 AM</option><option value='03:00'>03.00 AM</option><option value='03:30'>03.30 AM</option><option value='04:00'>04.00 AM</option><option value='04:30'>04.30 AM</option><option value='05:00'>05.00 AM</option><option value='05:30'>05.30 AM</option><option value='06:00'>06.00 AM</option><option value='06:30'>06.30 AM</option><option value='07:00'>07.00 AM</option><option value='07:30'>07.30 AM</option><option value='08:00'>08.00 AM</option><option value='08:30'>08.30 AM</option><option value='09:00'>09.00 AM</option><option value='09:30'>09.30 AM</option><option value='10:00'>10.00 AM</option><option value='10:30'>10.30 AM</option><option value='11:00'>11.00 AM</option><option value='11:30'>11.30 AM</option><option value='12:00'>12.00 PM</option><option value='12:30'>12.30 PM</option><option value='13:00'>01.00 PM</option><option value='13:30'>01.30 PM</option><option value='14:00'>02.00 PM</option><option value='14:30'>02.30 PM</option><option value='15:00'>03.00 PM</option><option value='15:30'>03.30 PM</option><option value='16:00'>04.00 PM</option><option value='16:30'>04.30 PM</option><option value='17:00'>05.00 PM</option><option value='17:30'>05.30 PM</option><option value='18:00'>06.00 PM</option><option value='18:30'>06.30 PM</option><option value='19:00'>07.00 PM</option><option value='19:30'>07.30 PM</option><option value='20:00'>08.00 PM</option><option value='20:30'>08.30 PM</option><option value='21:00'>09.00 PM</option><option value='21:30'>09.30 PM</option><option value='22:00'>10.00 PM</option><option value='22:30'>10.30 PM</option><option value='23:00'>11.00 PM</option><option value='23:30'>11.30 PM</option></select></td><td><select id='to' name='toMon[]'><option value='00:00'>12.00 AM</option><option value='00:30'>12.30 AM</option><option value='01:00'>01.00 AM</option><option value='01:30'>01.30 AM</option><option value='02:00'>02.00 AM</option><option value='02:30'>02.30 AM</option><option value='03:00'>03.00 AM</option><option value='03:30'>03.30 AM</option><option value='04:00'>04.00 AM</option><option value='04:30'>04.30 AM</option><option value='05:00'>05.00 AM</option><option value='05:30'>05.30 AM</option><option value='06:00'>06.00 AM</option><option value='06:30'>06.30 AM</option><option value='07:00'>07.00 AM</option><option value='07:30'>07.30 AM</option><option value='08:00'>08.00 AM</option><option value='08:30'>08.30 AM</option><option value='09:00'>09.00 AM</option><option value='09:30'>09.30 AM</option><option value='10:00'>10.00 AM</option><option value='10:30'>10.30 AM</option><option value='11:00'>11.00 AM</option><option value='11:30'>11.30 AM</option><option value='12:00'>12.00 PM</option><option value='12:30'>12.30 PM</option><option value='13:00'>01.00 PM</option><option value='13:30'>01.30 PM</option><option value='14:00'>02.00 PM</option><option value='14:30'>02.30 PM</option><option value='15:00'>03.00 PM</option><option value='15:30'>03.30 PM</option><option value='16:00'>04.00 PM</option><option value='16:30'>04.30 PM</option><option value='17:00'>05.00 PM</option><option value='17:30'>05.30 PM</option><option value='18:00'>06.00 PM</option><option value='18:30'>06.30 PM</option><option value='19:00'>07.00 PM</option><option value='19:30'>07.30 PM</option><option value='20:00'>08.00 PM</option><option value='20:30'>08.30 PM</option><option value='21:00'>09.00 PM</option><option value='21:30'>09.30 PM</option><option value='22:00'>10.00 PM</option><option value='22:30'>10.30 PM</option><option value='23:00'>11.00 PM</option><option value='23:30'>11.30 PM</option></select></td></tr>";
		$("#tablemon").append(markup);
	}
	else if(val==3)
	{
		var markup = "<tr><td></td><td><input type='hidden' id='Tueid' name='Tueid[]' value='3' ><select id='from' name='fromTue[]'><option value='00:00'>12.00 AM</option><option value='00:30'>12.30 AM</option><option value='01:00'>01.00 AM</option><option value='01:30'>01.30 AM</option><option value='02:00'>02.00 AM</option><option value='02:30'>02.30 AM</option><option value='03:00'>03.00 AM</option><option value='03:30'>03.30 AM</option><option value='04:00'>04.00 AM</option><option value='04:30'>04.30 AM</option><option value='05:00'>05.00 AM</option><option value='05:30'>05.30 AM</option><option value='06:00'>06.00 AM</option><option value='06:30'>06.30 AM</option><option value='07:00'>07.00 AM</option><option value='07:30'>07.30 AM</option><option value='08:00'>08.00 AM</option><option value='08:30'>08.30 AM</option><option value='09:00'>09.00 AM</option><option value='09:30'>09.30 AM</option><option value='10:00'>10.00 AM</option><option value='10:30'>10.30 AM</option><option value='11:00'>11.00 AM</option><option value='11:30'>11.30 AM</option><option value='12:00'>12.00 PM</option><option value='12:30'>12.30 PM</option><option value='13:00'>01.00 PM</option><option value='13:30'>01.30 PM</option><option value='14:00'>02.00 PM</option><option value='14:30'>02.30 PM</option><option value='15:00'>03.00 PM</option><option value='15:30'>03.30 PM</option><option value='16:00'>04.00 PM</option><option value='16:30'>04.30 PM</option><option value='17:00'>05.00 PM</option><option value='17:30'>05.30 PM</option><option value='18:00'>06.00 PM</option><option value='18:30'>06.30 PM</option><option value='19:00'>07.00 PM</option><option value='19:30'>07.30 PM</option><option value='20:00'>08.00 PM</option><option value='20:30'>08.30 PM</option><option value='21:00'>09.00 PM</option><option value='21:30'>09.30 PM</option><option value='22:00'>10.00 PM</option><option value='22:30'>10.30 PM</option><option value='23:00'>11.00 PM</option><option value='23:30'>11.30 PM</option></select></td><td><select id='to' name='toTue[]'><option value='00:00'>12.00 AM</option><option value='00:30'>12.30 AM</option><option value='01:00'>01.00 AM</option><option value='01:30'>01.30 AM</option><option value='02:00'>02.00 AM</option><option value='02:30'>02.30 AM</option><option value='03:00'>03.00 AM</option><option value='03:30'>03.30 AM</option><option value='04:00'>04.00 AM</option><option value='04:30'>04.30 AM</option><option value='05:00'>05.00 AM</option><option value='05:30'>05.30 AM</option><option value='06:00'>06.00 AM</option><option value='06:30'>06.30 AM</option><option value='07:00'>07.00 AM</option><option value='07:30'>07.30 AM</option><option value='08:00'>08.00 AM</option><option value='08:30'>08.30 AM</option><option value='09:00'>09.00 AM</option><option value='09:30'>09.30 AM</option><option value='10:00'>10.00 AM</option><option value='10:30'>10.30 AM</option><option value='11:00'>11.00 AM</option><option value='11:30'>11.30 AM</option><option value='12:00'>12.00 PM</option><option value='12:30'>12.30 PM</option><option value='13:00'>01.00 PM</option><option value='13:30'>01.30 PM</option><option value='14:00'>02.00 PM</option><option value='14:30'>02.30 PM</option><option value='15:00'>03.00 PM</option><option value='15:30'>03.30 PM</option><option value='16:00'>04.00 PM</option><option value='16:30'>04.30 PM</option><option value='17:00'>05.00 PM</option><option value='17:30'>05.30 PM</option><option value='18:00'>06.00 PM</option><option value='18:30'>06.30 PM</option><option value='19:00'>07.00 PM</option><option value='19:30'>07.30 PM</option><option value='20:00'>08.00 PM</option><option value='20:30'>08.30 PM</option><option value='21:00'>09.00 PM</option><option value='21:30'>09.30 PM</option><option value='22:00'>10.00 PM</option><option value='22:30'>10.30 PM</option><option value='23:00'>11.00 PM</option><option value='23:30'>11.30 PM</option></select></td></tr>";
		$("#tableTue").append(markup);
	}
	else if(val==4)
	{
		var markup = "<tr><td></td><td><input type='hidden' id='Wedid' name='Wedid[]' value='4' ><select id='from' name='fromWed[]'><option value='00:00'>12.00 AM</option><option value='00:30'>12.30 AM</option><option value='01:00'>01.00 AM</option><option value='01:30'>01.30 AM</option><option value='02:00'>02.00 AM</option><option value='02:30'>02.30 AM</option><option value='03:00'>03.00 AM</option><option value='03:30'>03.30 AM</option><option value='04:00'>04.00 AM</option><option value='04:30'>04.30 AM</option><option value='05:00'>05.00 AM</option><option value='05:30'>05.30 AM</option><option value='06:00'>06.00 AM</option><option value='06:30'>06.30 AM</option><option value='07:00'>07.00 AM</option><option value='07:30'>07.30 AM</option><option value='08:00'>08.00 AM</option><option value='08:30'>08.30 AM</option><option value='09:00'>09.00 AM</option><option value='09:30'>09.30 AM</option><option value='10:00'>10.00 AM</option><option value='10:30'>10.30 AM</option><option value='11:00'>11.00 AM</option><option value='11:30'>11.30 AM</option><option value='12:00'>12.00 PM</option><option value='12:30'>12.30 PM</option><option value='13:00'>01.00 PM</option><option value='13:30'>01.30 PM</option><option value='14:00'>02.00 PM</option><option value='14:30'>02.30 PM</option><option value='15:00'>03.00 PM</option><option value='15:30'>03.30 PM</option><option value='16:00'>04.00 PM</option><option value='16:30'>04.30 PM</option><option value='17:00'>05.00 PM</option><option value='17:30'>05.30 PM</option><option value='18:00'>06.00 PM</option><option value='18:30'>06.30 PM</option><option value='19:00'>07.00 PM</option><option value='19:30'>07.30 PM</option><option value='20:00'>08.00 PM</option><option value='20:30'>08.30 PM</option><option value='21:00'>09.00 PM</option><option value='21:30'>09.30 PM</option><option value='22:00'>10.00 PM</option><option value='22:30'>10.30 PM</option><option value='23:00'>11.00 PM</option><option value='23:30'>11.30 PM</option></select></td><td><select id='to' name='toWed[]'><option value='00:00'>12.00 AM</option><option value='00:30'>12.30 AM</option><option value='01:00'>01.00 AM</option><option value='01:30'>01.30 AM</option><option value='02:00'>02.00 AM</option><option value='02:30'>02.30 AM</option><option value='03:00'>03.00 AM</option><option value='03:30'>03.30 AM</option><option value='04:00'>04.00 AM</option><option value='04:30'>04.30 AM</option><option value='05:00'>05.00 AM</option><option value='05:30'>05.30 AM</option><option value='06:00'>06.00 AM</option><option value='06:30'>06.30 AM</option><option value='07:00'>07.00 AM</option><option value='07:30'>07.30 AM</option><option value='08:00'>08.00 AM</option><option value='08:30'>08.30 AM</option><option value='09:00'>09.00 AM</option><option value='09:30'>09.30 AM</option><option value='10:00'>10.00 AM</option><option value='10:30'>10.30 AM</option><option value='11:00'>11.00 AM</option><option value='11:30'>11.30 AM</option><option value='12:00'>12.00 PM</option><option value='12:30'>12.30 PM</option><option value='13:00'>01.00 PM</option><option value='13:30'>01.30 PM</option><option value='14:00'>02.00 PM</option><option value='14:30'>02.30 PM</option><option value='15:00'>03.00 PM</option><option value='15:30'>03.30 PM</option><option value='16:00'>04.00 PM</option><option value='16:30'>04.30 PM</option><option value='17:00'>05.00 PM</option><option value='17:30'>05.30 PM</option><option value='18:00'>06.00 PM</option><option value='18:30'>06.30 PM</option><option value='19:00'>07.00 PM</option><option value='19:30'>07.30 PM</option><option value='20:00'>08.00 PM</option><option value='20:30'>08.30 PM</option><option value='21:00'>09.00 PM</option><option value='21:30'>09.30 PM</option><option value='22:00'>10.00 PM</option><option value='22:30'>10.30 PM</option><option value='23:00'>11.00 PM</option><option value='23:30'>11.30 PM</option></select></td></tr>";
		
		$("#tableWed").append(markup);
	}
	
	else if(val==5)
	{
		var markup = "<tr><td></td><td><input type='hidden' id='Thuid' name='Thuid[]' value='5' ><select id='from' name='fromThu[]'><option value='00:00'>12.00 AM</option><option value='00:30'>12.30 AM</option><option value='01:00'>01.00 AM</option><option value='01:30'>01.30 AM</option><option value='02:00'>02.00 AM</option><option value='02:30'>02.30 AM</option><option value='03:00'>03.00 AM</option><option value='03:30'>03.30 AM</option><option value='04:00'>04.00 AM</option><option value='04:30'>04.30 AM</option><option value='05:00'>05.00 AM</option><option value='05:30'>05.30 AM</option><option value='06:00'>06.00 AM</option><option value='06:30'>06.30 AM</option><option value='07:00'>07.00 AM</option><option value='07:30'>07.30 AM</option><option value='08:00'>08.00 AM</option><option value='08:30'>08.30 AM</option><option value='09:00'>09.00 AM</option><option value='09:30'>09.30 AM</option><option value='10:00'>10.00 AM</option><option value='10:30'>10.30 AM</option><option value='11:00'>11.00 AM</option><option value='11:30'>11.30 AM</option><option value='12:00'>12.00 PM</option><option value='12:30'>12.30 PM</option><option value='13:00'>01.00 PM</option><option value='13:30'>01.30 PM</option><option value='14:00'>02.00 PM</option><option value='14:30'>02.30 PM</option><option value='15:00'>03.00 PM</option><option value='15:30'>03.30 PM</option><option value='16:00'>04.00 PM</option><option value='16:30'>04.30 PM</option><option value='17:00'>05.00 PM</option><option value='17:30'>05.30 PM</option><option value='18:00'>06.00 PM</option><option value='18:30'>06.30 PM</option><option value='19:00'>07.00 PM</option><option value='19:30'>07.30 PM</option><option value='20:00'>08.00 PM</option><option value='20:30'>08.30 PM</option><option value='21:00'>09.00 PM</option><option value='21:30'>09.30 PM</option><option value='22:00'>10.00 PM</option><option value='22:30'>10.30 PM</option><option value='23:00'>11.00 PM</option><option value='23:30'>11.30 PM</option></select></td><td><select id='to' name='toThu[]'><option value='00:00'>12.00 AM</option><option value='00:30'>12.30 AM</option><option value='01:00'>01.00 AM</option><option value='01:30'>01.30 AM</option><option value='02:00'>02.00 AM</option><option value='02:30'>02.30 AM</option><option value='03:00'>03.00 AM</option><option value='03:30'>03.30 AM</option><option value='04:00'>04.00 AM</option><option value='04:30'>04.30 AM</option><option value='05:00'>05.00 AM</option><option value='05:30'>05.30 AM</option><option value='06:00'>06.00 AM</option><option value='06:30'>06.30 AM</option><option value='07:00'>07.00 AM</option><option value='07:30'>07.30 AM</option><option value='08:00'>08.00 AM</option><option value='08:30'>08.30 AM</option><option value='09:00'>09.00 AM</option><option value='09:30'>09.30 AM</option><option value='10:00'>10.00 AM</option><option value='10:30'>10.30 AM</option><option value='11:00'>11.00 AM</option><option value='11:30'>11.30 AM</option><option value='12:00'>12.00 PM</option><option value='12:30'>12.30 PM</option><option value='13:00'>01.00 PM</option><option value='13:30'>01.30 PM</option><option value='14:00'>02.00 PM</option><option value='14:30'>02.30 PM</option><option value='15:00'>03.00 PM</option><option value='15:30'>03.30 PM</option><option value='16:00'>04.00 PM</option><option value='16:30'>04.30 PM</option><option value='17:00'>05.00 PM</option><option value='17:30'>05.30 PM</option><option value='18:00'>06.00 PM</option><option value='18:30'>06.30 PM</option><option value='19:00'>07.00 PM</option><option value='19:30'>07.30 PM</option><option value='20:00'>08.00 PM</option><option value='20:30'>08.30 PM</option><option value='21:00'>09.00 PM</option><option value='21:30'>09.30 PM</option><option value='22:00'>10.00 PM</option><option value='22:30'>10.30 PM</option><option value='23:00'>11.00 PM</option><option value='23:30'>11.30 PM</option></select></td></tr>";
		$("#tableThu").append(markup);
	}
	else if(val==6)
	{
		var markup = "<tr><td></td><td><input type='hidden' id='Friid' name='Friid[]' value='6' ><select id='from' name='fromFri[]'><option value='00:00'>12.00 AM</option><option value='00:30'>12.30 AM</option><option value='01:00'>01.00 AM</option><option value='01:30'>01.30 AM</option><option value='02:00'>02.00 AM</option><option value='02:30'>02.30 AM</option><option value='03:00'>03.00 AM</option><option value='03:30'>03.30 AM</option><option value='04:00'>04.00 AM</option><option value='04:30'>04.30 AM</option><option value='05:00'>05.00 AM</option><option value='05:30'>05.30 AM</option><option value='06:00'>06.00 AM</option><option value='06:30'>06.30 AM</option><option value='07:00'>07.00 AM</option><option value='07:30'>07.30 AM</option><option value='08:00'>08.00 AM</option><option value='08:30'>08.30 AM</option><option value='09:00'>09.00 AM</option><option value='09:30'>09.30 AM</option><option value='10:00'>10.00 AM</option><option value='10:30'>10.30 AM</option><option value='11:00'>11.00 AM</option><option value='11:30'>11.30 AM</option><option value='12:00'>12.00 PM</option><option value='12:30'>12.30 PM</option><option value='13:00'>01.00 PM</option><option value='13:30'>01.30 PM</option><option value='14:00'>02.00 PM</option><option value='14:30'>02.30 PM</option><option value='15:00'>03.00 PM</option><option value='15:30'>03.30 PM</option><option value='16:00'>04.00 PM</option><option value='16:30'>04.30 PM</option><option value='17:00'>05.00 PM</option><option value='17:30'>05.30 PM</option><option value='18:00'>06.00 PM</option><option value='18:30'>06.30 PM</option><option value='19:00'>07.00 PM</option><option value='19:30'>07.30 PM</option><option value='20:00'>08.00 PM</option><option value='20:30'>08.30 PM</option><option value='21:00'>09.00 PM</option><option value='21:30'>09.30 PM</option><option value='22:00'>10.00 PM</option><option value='22:30'>10.30 PM</option><option value='23:00'>11.00 PM</option><option value='23:30'>11.30 PM</option></select></td><td><select id='to' name='toFri[]'><option value='00:00'>12.00 AM</option><option value='00:30'>12.30 AM</option><option value='01:00'>01.00 AM</option><option value='01:30'>01.30 AM</option><option value='02:00'>02.00 AM</option><option value='02:30'>02.30 AM</option><option value='03:00'>03.00 AM</option><option value='03:30'>03.30 AM</option><option value='04:00'>04.00 AM</option><option value='04:30'>04.30 AM</option><option value='05:00'>05.00 AM</option><option value='05:30'>05.30 AM</option><option value='06:00'>06.00 AM</option><option value='06:30'>06.30 AM</option><option value='07:00'>07.00 AM</option><option value='07:30'>07.30 AM</option><option value='08:00'>08.00 AM</option><option value='08:30'>08.30 AM</option><option value='09:00'>09.00 AM</option><option value='09:30'>09.30 AM</option><option value='10:00'>10.00 AM</option><option value='10:30'>10.30 AM</option><option value='11:00'>11.00 AM</option><option value='11:30'>11.30 AM</option><option value='12:00'>12.00 PM</option><option value='12:30'>12.30 PM</option><option value='13:00'>01.00 PM</option><option value='13:30'>01.30 PM</option><option value='14:00'>02.00 PM</option><option value='14:30'>02.30 PM</option><option value='15:00'>03.00 PM</option><option value='15:30'>03.30 PM</option><option value='16:00'>04.00 PM</option><option value='16:30'>04.30 PM</option><option value='17:00'>05.00 PM</option><option value='17:30'>05.30 PM</option><option value='18:00'>06.00 PM</option><option value='18:30'>06.30 PM</option><option value='19:00'>07.00 PM</option><option value='19:30'>07.30 PM</option><option value='20:00'>08.00 PM</option><option value='20:30'>08.30 PM</option><option value='21:00'>09.00 PM</option><option value='21:30'>09.30 PM</option><option value='22:00'>10.00 PM</option><option value='22:30'>10.30 PM</option><option value='23:00'>11.00 PM</option><option value='23:30'>11.30 PM</option></select></td></tr>";
		$("#tableFri").append(markup);
	}
	
	else
	{
		var markup = "<tr><td></td><td><input type='hidden' id='Satid' name='Satid[]' value='7' ><select id='from' name='fromSat[]'><option value='00:00'>12.00 AM</option><option value='00:30'>12.30 AM</option><option value='01:00'>01.00 AM</option><option value='01:30'>01.30 AM</option><option value='02:00'>02.00 AM</option><option value='02:30'>02.30 AM</option><option value='03:00'>03.00 AM</option><option value='03:30'>03.30 AM</option><option value='04:00'>04.00 AM</option><option value='04:30'>04.30 AM</option><option value='05:00'>05.00 AM</option><option value='05:30'>05.30 AM</option><option value='06:00'>06.00 AM</option><option value='06:30'>06.30 AM</option><option value='07:00'>07.00 AM</option><option value='07:30'>07.30 AM</option><option value='08:00'>08.00 AM</option><option value='08:30'>08.30 AM</option><option value='09:00'>09.00 AM</option><option value='09:30'>09.30 AM</option><option value='10:00'>10.00 AM</option><option value='10:30'>10.30 AM</option><option value='11:00'>11.00 AM</option><option value='11:30'>11.30 AM</option><option value='12:00'>12.00 PM</option><option value='12:30'>12.30 PM</option><option value='13:00'>01.00 PM</option><option value='13:30'>01.30 PM</option><option value='14:00'>02.00 PM</option><option value='14:30'>02.30 PM</option><option value='15:00'>03.00 PM</option><option value='15:30'>03.30 PM</option><option value='16:00'>04.00 PM</option><option value='16:30'>04.30 PM</option><option value='17:00'>05.00 PM</option><option value='17:30'>05.30 PM</option><option value='18:00'>06.00 PM</option><option value='18:30'>06.30 PM</option><option value='19:00'>07.00 PM</option><option value='19:30'>07.30 PM</option><option value='20:00'>08.00 PM</option><option value='20:30'>08.30 PM</option><option value='21:00'>09.00 PM</option><option value='21:30'>09.30 PM</option><option value='22:00'>10.00 PM</option><option value='22:30'>10.30 PM</option><option value='23:00'>11.00 PM</option><option value='23:30'>11.30 PM</option></select></td><td><select id='to' name='toSat[]'><option value='00:00'>12.00 AM</option><option value='00:30'>12.30 AM</option><option value='01:00'>01.00 AM</option><option value='01:30'>01.30 AM</option><option value='02:00'>02.00 AM</option><option value='02:30'>02.30 AM</option><option value='03:00'>03.00 AM</option><option value='03:30'>03.30 AM</option><option value='04:00'>04.00 AM</option><option value='04:30'>04.30 AM</option><option value='05:00'>05.00 AM</option><option value='05:30'>05.30 AM</option><option value='06:00'>06.00 AM</option><option value='06:30'>06.30 AM</option><option value='07:00'>07.00 AM</option><option value='07:30'>07.30 AM</option><option value='08:00'>08.00 AM</option><option value='08:30'>08.30 AM</option><option value='09:00'>09.00 AM</option><option value='09:30'>09.30 AM</option><option value='10:00'>10.00 AM</option><option value='10:30'>10.30 AM</option><option value='11:00'>11.00 AM</option><option value='11:30'>11.30 AM</option><option value='12:00'>12.00 PM</option><option value='12:30'>12.30 PM</option><option value='13:00'>01.00 PM</option><option value='13:30'>01.30 PM</option><option value='14:00'>02.00 PM</option><option value='14:30'>02.30 PM</option><option value='15:00'>03.00 PM</option><option value='15:30'>03.30 PM</option><option value='16:00'>04.00 PM</option><option value='16:30'>04.30 PM</option><option value='17:00'>05.00 PM</option><option value='17:30'>05.30 PM</option><option value='18:00'>06.00 PM</option><option value='18:30'>06.30 PM</option><option value='19:00'>07.00 PM</option><option value='19:30'>07.30 PM</option><option value='20:00'>08.00 PM</option><option value='20:30'>08.30 PM</option><option value='21:00'>09.00 PM</option><option value='21:30'>09.30 PM</option><option value='22:00'>10.00 PM</option><option value='22:30'>10.30 PM</option><option value='23:00'>11.00 PM</option><option value='23:30'>11.30 PM</option></select></td></tr>";
		$("#tableSat").append(markup);
	}
	
}
   
</script>

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
