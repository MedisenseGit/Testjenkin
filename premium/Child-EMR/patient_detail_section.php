<style>
									.collapsible {
									  background-color: #d3d3d3ab;
									  color: black;
									  cursor: pointer;
									  padding: 10px;
									  width: 100%;
									  border: none;
									  text-align: left;
									  outline: none;
									  font-size: 14px;									 
									}

									.active, .collapsible:hover {
									 /* background-color: #d3d3d3ab;*/
									}

									.collapsible:after {
									  content: '\002B';
									  color: black;
									  font-weight: bold;
									  float: right;
									  margin-left: 5px;
									}

									#before-search .active:after {
									  content: "\2212";
									}

									.content {
									  padding: 0 18px;
									 /* max-height: 0;*/
									  overflow: hidden;
									  transition: max-height 0.2s ease-out;
									  background-color: white;
									   margin-bottom:20px;									  
									   border: 2px solid #d3d3d3ab;
									}
									</style>
<div class="row wrapper border-bottom white-bg page-heading">
              <!--  <div class="col-lg-3">
                    <h2>My Patient Profile</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="Home">Home</a>
                        </li>
                        <li class="active">
                            <strong>My Patient Profile</strong>
                        </li>
                    </ol>
                </div>-->
				 <div class="col-lg-12 mgTop">
				    <form method="post" name="frmchangePatient" >
						<input type="hidden" name="cmdchangePatient" value=""/>
						<input type="hidden" name="slct_valPat" value="" />
					</form>
					<div class="search-form">
                                <form  method="post" action="<?php echo HOST_URL_PREMIUM;?>add_details.php" autocomplete="off">
								<input type="hidden" name="curURI" value="My-Patient-Details" />
                                    <div class="input-group">
				
                                       <input type="text" id="serPatient" placeholder="Enter name or mobile number to search an existing patient or add a new patient" name="search" value="" class="form-control input-lg typeahead_1" onchange="return getPatientDet(this.value);">
                                        <div class="input-group-btn">
                                            <button class="btn btn-lg btn-primary m-r" name="cmdSearch" type="submit">
                                                <i class="fa fa-search"></i> Search
                                            </button>&nbsp;&nbsp;&nbsp;
											<a href="#"  data-toggle="modal" class="btn btn-lg btn-primary m-r" name="addPatientNew" id="addPatientNew" data-target="#myModalNew"> 
                                              <i class="fa fa-wheelchair"></i> Add Patient
                                            </a>
											&nbsp;&nbsp;&nbsp;
											<a href="#" data-toggle="modal" data-target="#myModaWaiting" class="btn btn-lg btn-warning" >
                                                <i class="fa fa-clock-o"></i> <!--<img src="waiting_room_icon.png" width="22"/>-->Waiting Room
                                            </a>
                                        </div>
                                    </div>

                                </form>
                    </div>            
			   </div>
               <!-- <div class="col-lg-2 mgTop">
					<a href="My-Patients"> <button type="button" class="btn btn-w-m btn-success"><i class="fa fa-arrow-left"></i> BACK</button></a>
                                
			   </div>-->
            </div>
			<?php if(is_numeric($_GET['p'])){ 
			
			}
			else
			{?>
			<div class="row white-bg page-heading">
			<div class="ibox-content ">
				<?php if(!empty($patient_tab[0]['patient_image'])){ ?>
				<div class="col-md-1">
                             <div class="profile-image">
                        <img src="<?php echo HOST_URL_PREMIUM; ?>patientImage/<?php echo $patient_tab[0]['patient_id']; ?>/<?php echo $patient_tab[0]['patient_image']; ?>" class="img-circle circle-border m-b-md" alt="profile">
						</div>
				</div>
				<?php } ?>
				<div class="col-md-3">
				<style>
									#toolbox-tools {
    width: 700px;
	height: 500px;
	overflow: hidden;
}
.group-handle {
	height: 34px;
	width: 10px;
	cursor: move;
}

.panel-heading {
    cursor: move;
}

.draggable-group {
	float: left;
}

#sortable {
	padding: 0px;
}

#sort-holder {
	display: none;
}

#toggle-toolbox-tools {
    cursor: pointer;
}

#close-toolbox-tools {
	cursor: pointer;
}

.toggle-button-group {
    cursor: pointer;
}

.draggable-group {
    overflow: hidden;
}

.minimized {
    width: 12px;
    height: 36px;
}
</style>	
                     <h1 class="text-navy"><b><?php echo $patient_tab[0]['patient_name']; ?><!--(<?php echo $patient_tab[0]['patient_id']; ?>)--></b>  <small><a href="#"  data-toggle="modal" class="text-navy" data-target="#myModal"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> </a></small></h1>
                                    <h5><i class="fa fa-transgender"></i>  <?php echo $gender; ?></h5>
									<?php if(!empty($patient_tab[0]['patient_age']) && $patient_tab[0]['DOB']!="0000-00-00"){ ?><h5><img src="<?php echo HOST_MAIN_URL; ?>premium/icons-age.png" width="13"/>  <?php if($patient_tab[0]['DOB']!="0000-00-00") { echo date('d-M-Y',strtotime($patient_tab[0]['DOB']))." - "; } if(!empty($patient_tab[0]['patient_age'])) { echo $patient_tab[0]['patient_age']." Years"; } ?></h5><?php } ?>
				          <?php $get_app_Status = mysqlSelect("*","appointment_transaction_detail","pref_doc='".$admin_id."' and patient_id='".$patient_tab[0]['patient_id']."'","id desc","","","");
							if($get_app_Status[0]['pay_status']=="Pending"){ $appStatus="text-danger"; } 
							else if($get_app_Status[0]['pay_status']=="At reception"){	$appStatus="text-warning"; }
							else if($get_app_Status[0]['pay_status']=="Consulted"){		$appStatus="text-navy"; }
							else if($get_app_Status[0]['pay_status']=="Missed"){	$appStatus="text-danger";	}
							else if($get_app_Status[0]['pay_status']=="VC Ready"){		$appStatus="text-navy"; }
							else if($get_app_Status[0]['pay_status']=="VC Confirmed"){	$appStatus="text-info"; } ?>
							 <h5>Status: <b class="text-warning"><?php echo $get_app_Status[0]['pay_status']; ?></b></h5>
							 
							<button onclick="myVideoClick(1)" class="btn btn-outline btn-primary"><i class="fa fa-video-camera" aria-hidden="true"> </i> Start Video Call</button>
												
												<div class="panel panel-primary draggable-panel toolbar-panel ui-draggable ui-resizable videoCallDisplay1" id="toolbox-tools" style="position:absolute;display:none;top:50px;left: 200px;z-index:1001;">
    <div class="panel-heading lang-panel-header-tools">Calling To <?php echo $patient_tab[0]['patient_name']; ?> <i class="fa fa-times pull-right close-panel" id="close-toolbox-tools"></i><i class="fa pull-right fa-chevron-down" id="toggle-toolbox-tools"></i></div>
<iframe  width="100%" height="100%" class="iframeVideo" style="height:90%;width:100%;overflow:scroll" id="toolbox-tools" id="resizable" class="resizable" class="panel panel-primary draggable-panel toolbar-panel ui-draggable ui-resizable" src="" allow="camera;microphone"></iframe>

</div>
				</div>
				<div class="col-md-3">
				<h5><b>Father Name:</b> <?php echo $parent_tab[0]['father_name']; ?></h5>
				<h5><b>Mother Name:</b> <?php echo $parent_tab[0]['mother_name']; ?></h5>    
				<h5><b>EDD:</b> <?php echo date('d M Y',strtotime($child_tab[0]['edd'])); ?></h5> 
				<h5><b>Corrected Age:</b> <?php echo $child_tab[0]['corrected_age']; ?></h5> 
				<h5><b>Actual Age:</b> <?php echo $child_tab[0]['actual_age']; ?></h5>    				
				</div> 
				<div class="col-md-3">
				<h5><i class="fa fa-map-marker"></i> <?php echo $patient_tab[0]['patient_addrs'].", ".$patient_tab[0]['patient_loc'].", ".$patient_tab[0]['pat_state']; ?></h5>
				<?php if(!empty($patient_tab[0]['patient_mob'])){ ?><h5><i class="fa fa-phone"></i> <?php echo $patient_tab[0]['patient_mob']; ?></h5><?php } ?>
                <?php if(!empty($patient_tab[0]['patient_email'])){ ?><h5><i class="fa fa-envelope"></i> <?php echo $patient_tab[0]['patient_email']; ?></h5><?php } ?> 
				<h5><b>Vaccine Start Date:</b>  <?php echo date('d M Y',strtotime($child_tab[0]['vaccine_start_date'])); ?></h5> 
				<h5><b>Date of Admission to NICU:</b> <?php echo date('d M Y',strtotime($child_tab[0]['date_of_nicu_admit'])); ?></h5>
				<h5><b>Date of Discharge:</b> <?php echo date('d M Y',strtotime($child_tab[0]['date_of_discharge'])); ?></h5>				
				</div> 
				
				<!--<div class="col-md-2">
				<?php 
				//BMI Calculation
							/*$explode = explode(".", $patient_tab[0]['height']);  
							$wholeFeet = $explode[0];							
							$fraction = $explode[1];
							$frctionFeet=$fraction*0.0833333; // Convert inches to feet
							$actaulFeet = $wholeFeet+$frctionFeet;
							$heightinMeter=$actaulFeet*0.3048; //Convert feet to meter
							*/
							
							//Convert height in centimeter to meter
							$heightinMeter = $patient_tab[0]['height_cm']/100;
							$bmi = substr(($patient_tab[0]['weight']/($heightinMeter*$heightinMeter)),0,4);
				
				if(!empty($patient_tab[0]['height_cm'])){ ?><h5><img src="<?php echo HOST_MAIN_URL; ?>premium/height_icon.png" width="20" class="m-r"/><?php echo $patient_tab[0]['height_cm'];?> cms</h5><?php } ?>
				<?php if(!empty($patient_tab[0]['weight'])) { ?><h5><img src="<?php echo HOST_MAIN_URL; ?>premium/weight_icon.png" width="20" class="m-r"/><?php echo $patient_tab[0]['weight']; ?> Kgs</h5><?php } ?>
                <?php if(!empty($patient_tab[0]['alcoholic'])) { ?> <h5><i class="fa fa-glass m-r"></i> <?php echo $patient_tab[0]['alcoholic']; ?></h5> <?php } ?>                 
				 <?php if(!empty($patient_tab[0]['smoking'])) { ?> <h5><img src="<?php echo HOST_MAIN_URL; ?>premium/smoking_icon.png" width="20" class="m-r"/><?php echo $patient_tab[0]['smoking']; ?></h5> <?php } ?>  
				</div>-->
				
				<?php if(!empty($patient_tab[0]['height_cm']) && !empty($patient_tab[0]['weight'])) { ?>
				<!--<div class="col-md-2">
				<h3>BMI   <?php 
				
				
				if($bmi>=18.5 && $bmi<=24.9){ echo $bmiStatus="<span class='label label-primary'>Healthy</span>"; } else if($bmi>=25 && $bmi<=30){ echo $bmiStatus="<span class='label label-warning'>Overweight</span>"; } else if($bmi>=30){ echo $bmiStatus="<span class='label label-danger'>Obese</span>"; } else { echo $bmiStatus="<span class='label label-danger'>Underweight</span>"; } ?></h3>
				<h1 class="text-navy"><b><?php echo $bmi; ?></b></h1>
                <?php if($patient_tab[0]['hyper_cond']!=0){  if($patient_tab[0]['hyper_cond']==1){ $hyperStatus="<span class='label label-danger'>Yes</span>"; } else if($patient_tab[0]['hyper_cond']==2){ $hyperStatus="<span class='label label-primary'>No</span>"; }?> <h5>Hypertension <?php echo $hyperStatus; ?></h5> <?php } ?>      
				<?php if($patient_tab[0]['diabetes_cond']!=0){ if($patient_tab[0]['diabetes_cond']==1){ $dibetesStatus="<span class='label label-danger'>Yes</span>"; } else if($patient_tab[0]['diabetes_cond']==2){ $dibetesStatus="<span class='label label-primary'>No</span>"; }?><h5>Diabetes <?php echo $dibetesStatus; ?></h5> <?php } ?>   				 
				</div>	-->
				<?php } 
				
				//Featured medicine enabled only for FDC Sponsered Doctors
				$getSponsoresProduct= mysqlSelect("a.sp_id as sp_id,a.sp_title as sp_title,a.sp_description as sp_description,a.sp_image as sp_image","sponsores_products as a left join sponsors as b on a.sponsores_id=b.sponsor_id","a.sponsores_id='".$get_doc_details[0]['sponsor_id']."'","","","","");
				if(count($getSponsoresProduct)>0){
				?>		
				<!--<div class="col-md-3 pull-right">
				
					<center><h3>Featured Medicine</h3> <div class="slick_demo_3 autoplay">
								
										<?php 
										
										while(list($key, $value) = each($getSponsoresProduct)){ ?>
											
										<div>
										<h4 class="text-navy font-bold"><?php echo $value['sp_title']; ?></h4>
										<p class="text-black"><?php echo substr($value['sp_description'],0,20); ?></p>
											
										<a href="Product-Details-Page?sp_id=<?php echo $value['sp_id']; ?>" target="_blank"><span class="btn btn-danger btn-xs">VIEW DETAILS</span></a>
										</div>
										
										<?php } ?>
										
										   
										</div></center>
				
				</div>-->
				<?php } ?>
			</div>
												
			</div>
			
			<?php } 
			
			?>
			
			<div class="modal inmodal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                <div class="modal-content animated bounceInRight">
								<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  action="<?php echo HOST_URL_PREMIUM; ?>Child-EMR/my_patient_profile_save.php"  name="frmAddPatient" id="frmAddPatient">
									
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            <?php if(!empty($patient_tab[0]['patient_image'])){ ?><img src="<?php echo HOST_URL_PREMIUM; ?>patientImage/<?php echo $patient_tab[0]['patient_id']; ?>/<?php echo $patient_tab[0]['patient_image']; ?>" width="100" class="img-thumbnail" /><?php } else { ?><img src="<?php echo HOST_MAIN_URL; ?>assets/img/user_noimg.png" width="100" class="img-thumbnail" /><?php } ?>
											<center><input type="file" name="txtPhoto" style="margin-left:50px;"></center>
                                            <h4 class="modal-title"><?php if(is_numeric($_GET['p'])){ echo $_GET['n']; } else { echo $patient_tab[0]['patient_name']; } ?></h4>
                                            <small class="font-bold">Patient Profile</small>
                                        </div>
										<input type="hidden" name="patient_id" value="<?php echo $patient_tab[0]['patient_id']; ?>">
                                    
                                        <div class="modal-body">
										 <div class="row">
                                            <div class="form-group"><label class="col-sm-2 control-label">Child Name</label> 
											<div class="col-sm-2">
											<input type="text" id="se_pat_name" name="se_pat_name" value="<?php if($_GET['p']==="0"){ echo $_GET['n']; } else { echo $patient_tab[0]['patient_name']; } ?>" class="form-control">
											</div>
                                           <label class="col-sm-1 control-label">DOB<?php //if($checkSetting[0]['patient_age_type']=="0" || $checkSetting==false){ echo "Age"; } else if($checkSetting==true && $checkSetting[0]['patient_age_type']=="1") { echo "DOB"; } ?></label>
                                    <div class="col-sm-2">
									<?php //if($checkSetting[0]['patient_age_type']=="0" || $checkSetting==false){ ?>
										<!--<input type="text" id="se_pat_age" name="se_pat_age" value="<?php echo $patient_tab[0]['patient_age']; ?>" class="form-control">-->
                                    <?php //} else if($checkSetting==true && $checkSetting[0]['patient_age_type']=="1") { ?>
									<input id="dateadded" name="date_birth" type="text" <?php if($patient_tab[0]['DOB']!="0000-00-00" && $_GET['p']!="0"){ ?>value="<?php echo date('m/d/Y',strtotime($patient_tab[0]['DOB']));?>"<?php } else if($_GET['p']=="0"){ ?>value=""<?php } ?> placeholder="DD/MM/YYYY" class="form-control" >
                                    <?php //} ?>
									</div>
									<label class="col-sm-1 control-label">Gender</label>
                                    <div class="col-sm-4">
									<?php if($patient_tab[0]['patient_gen']=="1"){ ?>

										<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio1" value="1" name="se_gender" checked="checked">
                                            <label for="inlineRadio1"> Male </label>
                                        </div>
										<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="2" name="se_gender">
                                            <label for="inlineRadio2"> Female </label>
                                        </div>
										<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="3" name="se_gender">
                                            <label for="inlineRadio2"> Others </label>
                                        </div>
									<?php } else if($patient_tab[0]['patient_gen']=="2") { ?>
									<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio1" value="1" name="se_gender">
                                            <label for="inlineRadio1"> Male </label>
                                        </div>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="2" name="se_gender" checked="">
                                            <label for="inlineRadio2"> Female </label>
                                        </div>
										<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="3" name="se_gender">
                                            <label for="inlineRadio2"> Others </label>
                                        </div>
									<?php } else if($patient_tab[0]['patient_gen']=="3") { ?>
									<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio1" value="1" name="se_gender">
                                            <label for="inlineRadio1"> Male </label>
                                        </div>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="2" name="se_gender">
                                            <label for="inlineRadio2"> Female </label>
                                        </div>
										<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="3" name="se_gender" checked="">
                                            <label for="inlineRadio2"> Others </label>
                                        </div>
									<?php } else { ?>
									<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio1" value="1" name="se_gender">
                                            <label for="inlineRadio1"> Male </label>
                                        </div>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="2" name="se_gender">
                                            <label for="inlineRadio2"> Female </label>
                                        </div>
									<?php } ?>
                                    </div>
									</div>								
								</div><br>
										
									<div class="form-group">
									<label class="col-sm-2 control-label">Height(Centimeter)</label>
									<div class="col-sm-2">
									<input type="text"  placeholder="in cm"  name="height" id="aninput" onkeypress="return validateFloatKeyPress(this,event);" value="<?php echo $patient_tab[0]['height_cm']; ?>" class="form-control" maxlength="3">								
									</div>
									<label class="col-sm-2 control-label">Birth Weight(Kgs)</label>
									<div class="col-sm-2">
									<input type="text" placeholder="in kgs"  name="weight" maxlength="3" value="<?php echo $patient_tab[0]['weight']; ?>" class="form-control">								
									</div>
									<label class="control-label col-md-2 col-sm-2 col-xs-12">Birth Order <span class="required">*</span></label>
									<div class="col-md-2 col-sm-2 col-xs-12">
									  <input type="text" id="se_birth_order" name="se_birth_order" required="required" value="<?php echo $child_tab[0]['birth_order']; ?>" class="form-control" placeholder="">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Mobile</label>
										<div class="col-sm-2"><input type="text" id="se_phone_no" name="se_phone_no" value="<?php echo $patient_tab[0]['patient_mob']; ?>" class="form-control mobileChange"></div>
										<label class="col-sm-2 control-label">Email</label> 
										<div class="col-sm-2"><input type="email" id="se_email" name="se_email" value="<?php echo $patient_tab[0]['patient_email']; ?>" class="form-control"></div>
										<label class="control-label col-md-2 col-sm-2 col-xs-12">EDD <span class="required">*</span></label>
											<div class="col-md-2 col-sm-2 col-xs-12">
											  <input type="text" id="J-demo-07" name="se_edd" required="required" class="form-control" placeholder="" value="<?php echo $child_tab[0]['edd']; ?>">
											<script type="text/javascript">
												$('#J-demo-07').dateTimePicker({
													mode: 'date',
													format: 'yyyy-MM-dd'
												});
											</script>
											</div>
								</div>		
								<div class="form-group">
									
								     <label class="control-label col-md-2 col-sm-2 col-xs-12">Date of Admission to NICU <span class="required">*</span></label>
										<div class="col-md-2 col-sm-2 col-xs-12">
										  <input type="text" id="J-demo-01" name="se_date_of_admit" required="required" class="form-control" placeholder="" value="<?php echo $child_tab[0]['date_of_nicu_admit']; ?>">
										<script type="text/javascript">
											$('#J-demo-01').dateTimePicker();
										</script>
									   </div>
									   <label class="control-label col-md-2 col-sm-2 col-xs-12">Date of Discharge  <span class="required">*</span></label>
										<div class="col-md-2 col-sm-2 col-xs-12">
										  <input type="text" id="J-demo-06" name="se_date_of_discharge" class="form-control" placeholder="" value="<?php echo $child_tab[0]['date_of_discharge']; ?>">
									   <script type="text/javascript">
											$('#J-demo-06').dateTimePicker({
												mode: 'date',
												format: 'yyyy-MM-dd'
											});
										</script>
										</div>
										<label class="control-label col-md-2 col-sm-2 col-xs-12">Vaccination Start Date <span class="required">*</span></label>
											<div class="col-md-2 col-sm-2 col-xs-12">
											  <input type="text" id="J-demo-09" name="vaccine_start_date" required="required" class="form-control" value="<?php echo $child_tab[0]['vaccine_start_date']; ?>">
										   <script type="text/javascript">
												$('#J-demo-09').dateTimePicker({
													mode: 'date',
													format: 'yyyy-MM-dd'
												});
											</script>
											</div>	
									</div>
									<div class="form-group">
											<!--<label class="control-label col-md-2 col-sm-2 col-xs-12">Date of Creation  <span class="required">*</span></label>
											<div class="col-md-2 col-sm-2 col-xs-12">
											  <input type="text" id="J-demo-08" name="creation_date" class="form-control" placeholder="" value="<?php echo $child_tab[0]['creation_date']; ?>">
										   <script type="text/javascript">
											$('#J-demo-08').dateTimePicker({
												mode: 'date',
												format: 'yyyy-MM-dd'
											});
										</script>
											</div>-->
											
											<!--<label class="control-label col-md-2 col-sm-2 col-xs-12">Detailed Description</label>
											<div class="col-md-2 col-sm-2 col-xs-12">
											  <textarea class="form-control" id="se_des" name="se_des" rows="3"><?php echo $child_tab[0]['description']; ?></textarea>
											
											</div>		-->									
										  </div>	
										  	
										<button type="button" class="collapsible" data-toggle="collapse" data-target="#collapseRow">Add Parent Details</button>
										<div class="content collapse" id="collapseRow">
										  <div class="form-group" style="padding-top:15px;">
												<label class="control-label col-md-2 col-sm-2 col-xs-12">Mother Name <span class="required">*</span></label>
												<div class="col-md-2 col-sm-2 col-xs-12">
												  <input type="text" id="mother_name" name="se_mother_name"  required="required" class="form-control" placeholder="" value="<?php echo $parent_tab[0]['mother_name']; ?>">
												</div>
												<label class="control-label col-md-2 col-sm-2 col-xs-12">Mother Age <span class="required">*</span></label>
												<div class="col-md-2 col-sm-2 col-xs-12">
												  <input type="text" id="se_mother_age" name="se_mother_age" required="required" class="form-control" placeholder="" value="<?php echo $parent_tab[0]['mother_age']; ?>">
												</div>
												<!--<label class="control-label col-md-2 col-sm-2 col-xs-12">PHC Location <span class="required">*</span></label>
													<div class="col-md-2 col-sm-2 col-xs-12">
													  <input type="text" id="se_phc_location" name="se_phc_location" value="<?php echo $parent_tab[0]['phc_location']; ?>" required="required" class="form-control" placeholder="">
													</div>-->
												</div>
											  <br>
											  
											   <div class="form-group">
												<label class="control-label col-md-2 col-sm-2 col-xs-12">Father Name <span class="required">*</span></label>
												<div class="col-md-2 col-sm-2 col-xs-12">
												  <input type="text" id="father_name" name="se_father_name" required="required" class="form-control" placeholder="" value="<?php echo $parent_tab[0]['father_name']; ?>">
												</div>
												<label class="control-label col-md-2 col-sm-2 col-xs-12">Father Age  <span class="required">*</span></label>
												<div class="col-md-2 col-sm-2 col-xs-12">
												  <input type="text" id="se_father_age" name="se_father_age"  class="form-control" placeholder="" value="<?php echo $parent_tab[0]['father_age']; ?>">
												</div>
												</div>
											  <br>
											  <div class="form-group"><label class="col-sm-2 control-label">Country</label> 
										<div class="col-sm-2"><select class="form-control" name="se_country" name="se_country">
														<option value="India" <?php echo (!isset($patient_tab[0]['pat_country']) ? 'selected' : ($patient_tab[0]['pat_country'] == 'India' ? 'Selected' : '' ) ) ?> selected>India</option>
														<?php
														$getCountry= mysqlSelect("*","countries","","country_name asc","","","");
														$i=30;
														foreach($getCountry as $CountryList){
														?>

														<option value="<?php echo stripslashes($CountryList['country_name']); ?>" <?php echo ($patient_tab[0]['pat_country'] == stripslashes($CountryList['country_name']) ? 'selected' : '') ?> />
														<?php echo stripslashes($CountryList['country_name']);?></option>


														<?php
														$i++;
														}?>
													</select></div>
										<label class="col-sm-2 control-label">State</label>
										<div class="col-sm-2"><select class="form-control"  name="se_state" id="se_state" placeholder="State"  >
														<option value="">Select State</option>
														<?php
														$GetState = mysqlSelect("*","countries as a left join states as b on a.country_id=b.country_id", "a.country_id=100", "b.state_name asc", "", "", "");
														 
														foreach ($GetState as $StateList) {
														?>
														<option value="<?php echo $StateList["state_name"];	?>" <?php echo ($get_doc_details[0]['doc_state'] == $StateList["state_name"] ? 'selected' : '' ) ?> ><?php echo $StateList["state_name"]; ?></option>

														<?php
														}
													
														?>
													</select></div>
													<label class="col-sm-2 control-label">City</label> 
											<div class="col-sm-2"><input type="text" id="se_city" name="se_city" value="<?php if(!empty($patient_tab[0]['patient_loc'])) { echo $patient_tab[0]['patient_loc'];} else { echo $get_doc_details[0]['doc_city']; } ?>" class="form-control"></div>
										
										</div>
										
										<div class="form-group"><label class="col-sm-2 control-label">Address</label> 
										<div class="col-sm-2"><input type="text" id="se_address" name="se_address" value="<?php echo $patient_tab[0]['patient_addrs']; ?>" class="form-control"></div>
										</div>	
											   
											</div>						
									
										</div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
											<?php if(is_numeric($_GET['p'])){ ?>
                                            <button type="submit" name="save_patient" class="btn btn-primary">SAVE</button>
											<?php } else {  ?>
											<button type="submit" name="update_patient" class="btn btn-primary">UPDATE</button>
											<?php } ?>
                                        </div>
										</form>
                                    </div>
                                </div>
                            </div>
				
				<div class="modal inmodal" id="myModalNew" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                <div class="modal-content animated bounceInRight">
								<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  action="<?php echo HOST_URL_PREMIUM; ?>Child-EMR/my_patient_profile_save.php"  name="frmAddPatient" id="frmAddPatient">
									
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            <img src="<?php echo HOST_MAIN_URL; ?>assets/img/user_noimg.png" width="100" class="img-thumbnail" />
											<center><input type="file" name="txtPhoto" style="margin-left:50px;"></center>
                                           
                                            <small class="font-bold">Add New Patient</small>
                                        </div>
										
                                        <div class="modal-body">
										 <div class="row">
                                            <div class="form-group">
											<label class="col-sm-2 control-label">Child Name</label> 
											<div class="col-sm-2">
											<input type="text" id="se_pat_name" name="se_pat_name" value="" class="form-control">
											</div>
											<label class="col-sm-1 control-label">DOB<?php //if($checkSetting[0]['patient_age_type']=="0" || $checkSetting==false){ echo "Age"; } else if($checkSetting==true && $checkSetting[0]['patient_age_type']=="1") { echo "DOB"; } ?></label>
											<div class="col-sm-2">
											<?php //if($checkSetting[0]['patient_age_type']=="0" || $checkSetting==false){ ?>
												<!--<input type="text" id="se_pat_age" name="se_pat_age" value="" class="form-control">-->
											<?php //} else if($checkSetting==true && $checkSetting[0]['patient_age_type']=="1") { ?>
											<input id="dateadded1" name="date_birth" type="text" value="" placeholder="DD/MM/YYYY" class="form-control" >
											<?php //} ?>
											</div>
											<label class="col-sm-1 control-label">Gender</label>
											<div class="col-sm-4">
											
											<div class="radio radio-info radio-inline">
													<input type="radio" id="inlineRadio1" value="1" name="se_gender">
													<label for="inlineRadio1"> Male </label>
												</div>
												<div class="radio radio-info radio-inline">
													<input type="radio" id="inlineRadio2" value="2" name="se_gender">
													<label for="inlineRadio2"> Female </label>
												</div>
											   <div class="radio radio-info radio-inline">
													<input type="radio" id="inlineRadio2" value="3" name="se_gender">
													<label for="inlineRadio2"> Others </label>
												</div>
											</div>
                                 </div>
								
								</div>
										
									<div class="form-group">
									<label class="col-sm-2 control-label">Height(Centimeter)</label>
									<div class="col-sm-2">
									<input type="text"  placeholder="in cm"  name="height" id="aninput" onkeypress="return validateFloatKeyPress(this,event);" value="" class="form-control" maxlength="3">								
									</div>
									<label class="col-sm-2 control-label">Birth Weight(Kgs)</label>
									<div class="col-sm-2">
									<input type="text" placeholder="in kgs"  name="weight" maxlength="3" value="" class="form-control">								
									</div>
									<label class="control-label col-md-2 col-sm-2 col-xs-12">Birth Order <span class="required">*</span></label>
									<div class="col-md-2 col-sm-2 col-xs-12">
									  <input type="text" id="se_birth_order" name="se_birth_order" required="required" class="form-control" placeholder="">
									</div>
								</div>
								<div class="form-group"><label class="col-sm-2 control-label">Mobile</label> 	
										<div class="col-sm-2"><input type="text" id="se_phone_no" name="se_phone_no" value="" class="form-control mobileChange" >
										</div><label class="col-sm-2 control-label">Email</label> 
										<div class="col-sm-2"><input type="email" id="se_email" name="se_email" value="" class="form-control"></div>
										<label class="control-label col-md-2 col-sm-2 col-xs-12">EDD <span class="required">*</span></label>
											<div class="col-md-2 col-sm-2 col-xs-12">
											  <input type="text" id="J-demo-10" name="se_edd" required="required" class="form-control" placeholder="">
											<script type="text/javascript">
												$('#J-demo-10').dateTimePicker({
													mode: 'date',
													format: 'yyyy-MM-dd'
												});
											</script>
											</div>
									
								</div>
								<div class="form-group">
									
								     <label class="control-label col-md-2 col-sm-2 col-xs-12">Date of Admission to NICU <span class="required">*</span></label>
										<div class="col-md-2 col-sm-2 col-xs-12">
										  <input type="text" id="J-demo-11" name="se_date_of_admit" required="required" class="form-control" placeholder="">
										<script type="text/javascript">
											$('#J-demo-11').dateTimePicker();
										</script>
									   </div>
									   <label class="control-label col-md-2 col-sm-2 col-xs-12">Date of Discharge  <span class="required">*</span></label>
										<div class="col-md-2 col-sm-2 col-xs-12">
										  <input type="text" id="J-demo-12" name="se_date_of_discharge" class="form-control" placeholder="">
									   <script type="text/javascript">
											$('#J-demo-12').dateTimePicker({
												mode: 'date',
												format: 'yyyy-MM-dd'
											});
										</script>
										</div>
											<label class="control-label col-md-2 col-sm-2 col-xs-12">Vaccination Start Date <span class="required">*</span></label>
											<div class="col-md-2 col-sm-2 col-xs-12">
											  <input type="text" id="J-demo-14" name="vaccine_start_date" required="required" class="form-control" value="<?php echo $getChild[0]['vaccine_start_date']; ?>">
										   <script type="text/javascript">
												$('#J-demo-14').dateTimePicker({
													mode: 'date',
													format: 'yyyy-MM-dd'
												});
											</script>
											</div>	
									</div>
								<div class="form-group">
											<!--<label class="control-label col-md-2 col-sm-2 col-xs-12">Date of Creation  <span class="required">*</span></label>
											<div class="col-md-2 col-sm-2 col-xs-12">
											  <input type="text" id="J-demo-13" name="creation_date" class="form-control" placeholder="">
										   <script type="text/javascript">
											$('#J-demo-13').dateTimePicker({
												mode: 'date',
												format: 'yyyy-MM-dd'
											});
										</script>
											</div>-->
										
										<!--	<label class="control-label col-md-2 col-sm-2 col-xs-12">Detailed Description</label>
											<div class="col-md-2 col-sm-2 col-xs-12">
											  <textarea class="form-control" id="se_des" name="se_des" rows="3"></textarea>
											
											</div>-->
										  </div>
										 
									<button type="button" class="collapsible" data-toggle="collapse" data-target="#collapseRow1">Add Parent Details</button>
									<div class="content collapse" id="collapseRow1">
										    <div class="form-group" style="padding-top:15px;">
												<label class="control-label col-md-2 col-sm-2 col-xs-12">Mother Name <span class="required">*</span></label>
												<div class="col-md-2 col-sm-2 col-xs-12">
												  <input type="text" id="mother_name1" name="se_mother_name" value="" required="required" class="form-control" placeholder="">
												</div>
												<label class="control-label col-md-2 col-sm-2 col-xs-12">Mother Age <span class="required">*</span></label>
												<div class="col-md-2 col-sm-2 col-xs-12">
												  <input type="text" id="se_mother_age1" name="se_mother_age" value="" required="required" class="form-control" placeholder="" >
												</div>
												<!--<label class="control-label col-md-2 col-sm-2 col-xs-12">PHC Location <span class="required">*</span></label>
													<div class="col-md-2 col-sm-2 col-xs-12">
													  <input type="text" id="se_phc_location1" name="se_phc_location" value="<?php echo $phc_location ?>" required="required" class="form-control" placeholder="">
													</div>-->
												</div>
											  <br>
											  
											   <div class="form-group">
												<label class="control-label col-md-2 col-sm-2 col-xs-12">Father Name <span class="required">*</span></label>
												<div class="col-md-2 col-sm-2 col-xs-12">
												  <input type="text" id="father_name1" name="se_father_name" value="" required="required" class="form-control" placeholder="">
												</div>
												<label class="control-label col-md-2 col-sm-2 col-xs-12">Father Age  <span class="required">*</span></label>
												<div class="col-md-2 col-sm-2 col-xs-12">
												  <input type="text" id="se_father_age1" name="se_father_age" value=""  class="form-control" placeholder="">
												</div>
												</div>
											  <br>
											    <div class="form-group"><label class="col-sm-2 control-label">Country</label>
										<div class="col-sm-2"><select class="form-control se_country1" name="se_country" id="se_country">
														<option value="India" <?php echo (!isset($patient_tab[0]['pat_country']) ? 'selected' : ($patient_tab[0]['pat_country'] == 'India' ? 'Selected' : '' ) ) ?> selected>India</option>
														<?php
														$getCountry= mysqlSelect("*","countries","","country_name asc","","","");
														$i=30;
														foreach($getCountry as $CountryList){
														?>

														<option value="<?php echo stripslashes($CountryList['country_name']); ?>" <?php echo ($patient_tab[0]['pat_country'] == stripslashes($CountryList['country_name']) ? 'selected' : '') ?> />
														<?php echo stripslashes($CountryList['country_name']);?></option>


														<?php
														$i++;
														}?>
													</select></div>
													<label class="col-sm-2 control-label">State</label> 
													<div class="col-sm-2"><select class="form-control se_state1"  name="se_state" id="se_state" placeholder="State"  >
														<option value="">Select State</option>
														<?php
														$GetState = mysqlSelect("*","countries as a left join states as b on a.country_id=b.country_id", "a.country_id=100", "b.state_name asc", "", "", "");
														 
														foreach ($GetState as $StateList) {
														?>
														<option value="<?php echo $StateList["state_name"];	?>" <?php echo ($get_doc_details[0]['doc_state'] == $StateList["state_name"] ? 'selected' : '' ) ?> ><?php echo $StateList["state_name"]; ?></option>

														<?php
														}
													
														?>
													</select></div>
											<label class="col-sm-2 control-label">City</label> 
											<div class="col-sm-2"><input type="text" id="se_city1" name="se_city" value="<?php  echo $get_doc_details[0]['doc_city']; ?>" class="form-control">
											</div>
										</div>
										
										<div class="form-group"><label class="col-sm-2 control-label">Address</label> 
										<div class="col-sm-2"><input type="text" id="se_address1" name="se_address" value="" class="form-control"></div>
																													
									</div>
										 </div>
										</div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
											
                                            <button type="submit" name="save_patient" class="btn btn-primary">SAVE</button>
											
                                        </div>
										</form>
                                    </div>
                                </div>
                            </div>
							
						
							<div class="modal inmodal" id="myModaWaiting" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" >
                                <div class="modal-content animated bounceInRight" style="background:url('<?php echo HOST_MAIN_URL; ?>premium/bg_image1.jpg');">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true" class="text-white">&times;</span><span class="sr-only text-white">Close</span></button>
                                            <h4 class="modal-title text-white">Waiting Room</h4>
											 <h6 class="modal-title text-white"><i class="fa fa-hospital-o"></i> <?php echo $_SESSION['login_hosp_name']; ?></h6>
                                            <small class="text-white"><i class="fa fa-calendar"></i> <?php echo date('d M Y'); ?></small>
                                        </div>
										<div class="ibox-content">
							
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Token</th>
                                <th>Name</th>
                                <th>Visit Time</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
							<?php
							
							$get_app_token_details = mysqlSelect("*","appointment_token_system","doc_id='".$admin_id."' and doc_type='1' and hosp_id='".$_SESSION['login_hosp_id']."' and app_date='".$cur_Date."' and status!='Cancelled'","token_no asc","","","");
							
							if(COUNT($get_app_token_details)>0){
								
							
							while(list($key, $value) = each($get_app_token_details)){
								
								$checkTodayVisit= mysqlSelect("episode_id","doc_patient_episodes","admin_id='".$admin_id."' and patient_id='".$value['patient_id']."' and DATE_FORMAT(date_time,'%Y-%m-%d')='".date('Y-m-d')."'","episode_id desc","","","");
								if(COUNT($checkTodayVisit)>0){
								$getEpisodeId="&episode=".md5($checkTodayVisit[0]['episode_id']);
								}
								else
								{
									$getEpisodeId="";
								}
								
								
								if($value['status']=="Pending"){
									$appStatus="text-danger";
								} 
								else if($value['status']=="At reception")
								{
									$appStatus="text-warning";
								}
								else if($value['status']=="Consulted")
								{									
									$appStatus="text-navy";
								}
								else if($value['status']=="Missed")
								{									
									$appStatus="text-danger";
								}
								else if($value['status']=="VC Ready")
								{									
									$appStatus="text-navy";
								}
								else if($value['status']=="VC Confirmed")
								{									
									$appStatus="text-info";
								}
							?>
                            <tr>
                               <td><?php if($value['token_no']!="555") { echo "<button class='btn btn-success btn-circle' type='button'>".$value['token_no']."</button>";} else { echo "<button class='btn btn-primary btn-xs' type='button'>Online</button>"; } ?></td>
								<td><a href="<?php echo $_SESSION['EMR_URL'].md5($value['patient_id']).$getEpisodeId; ?>"><?php echo $value['patient_name']; ?></a></td>
                                <td><span class="line"><i class="fa fa-clock-o"></i> <?php echo $value['app_time']; ?></span></td>
                                
                                <td class="<?php echo $appStatus; ?>"><b> <?php echo $value['status']; ?> </b></td>
                            </tr>
							<?php } 
							}
							?>
                         
                            </tbody>
                        </table>
                    </div>
                                    </div>
                                </div>
                            </div>


<script>
                        function myVideoClick(temp) {
  $('.videoCallDisplay'+temp).css('display','block');
  $('.iframeVideo').attr("src", "https://maayayoga.com/msvV2.0/index.php?ref_name=<?php echo rawurlencode($_SESSION['user_name']); ?>&pat_name=<?php echo rawurlencode($patient_tab[0]['patient_name']); ?>&type=1&r=<?php echo $admin_id."_".$patient_tab[0]['patient_id']; ?>");
}
                        $(document).ready(function() {
// Add drag and resize option to panel
    $("#toolbox-tools").draggable({
        handle: ".panel-heading",
        stop: function(evt, el) {
            // Save size and position in cookie
            /*
            $.cookie($(evt.target).attr("id"), JSON.stringify({
                "el": $(evt.target).attr("id"),
                "left": el.position.left,
                "top": el.position.top,
                "width": $(evt.target).width(),
                "height": $(evt.target).height()
            }));
            */
        }
    }).resizable({
        handles: "e, w, s, se",
        stop: function(evt, el) {
            // Save size and position in cookie
            /*
            $.cookie($(evt.target).attr("id"), JSON.stringify({
                "el": $(evt.target).attr("id"),
                "left": el.position.left,
                "top": el.position.top,
                "width": el.size.width,
                "height": el.size.height
            }));
            */
        }
    });

    // Expand and collaps the toolbar
    $("#toggle-toolbox-tools").on("click", function() {
        var panel = $("#toolbox-tools");

        if ($(panel).data("org-height") == undefined) {
                $(panel).data("org-height", $(panel).css("height"));
                $(panel).css("height","41px");
        } else {
                $(panel).css("height", $(panel).data("org-height"));
                $(panel).removeData("org-height");
        }

        $(this).toggleClass('fa-chevron-down').toggleClass('fa-chevron-right');
    });


    // Make toolbar groups sortable
    $( "#sortable" ).sortable({
                stop: function (event, ui) {
            var ids = [];
                        $.each($(".draggable-group"), function(idx, grp) {
                                ids.push($(grp).attr("id"));
                        });

            // Save order of groups in cookie
                        //$.cookie("group_order", ids.join());
                }
        });
        $( "#sortable" ).disableSelection();


    // Make Tools panel group minimizable
        $.each($(".draggable-group"), function(idx, grp) {
                var tb = $(grp).find(".toggle-button-group");

                $(tb).on("click", function() {
                        $(grp).toggleClass("minimized");
                        $(this).toggleClass("fa-caret-down").toggleClass("fa-caret-up");


                        // Save draggable groups to cookie (frue = Minimized, false = Not Minimized)
                        var ids = [];
                        $.each($(".draggable-group"), function(iidx, igrp) {
                                var itb = $(igrp).find(".toggle-button-group");
                                var min = $(igrp).hasClass("minimized");

                                ids.push($(igrp).attr("id") + "=" + min);
                        });

                        $.cookie("group_order", ids.join());
                });
        });

    // Close thr panel
    $(".close-panel").on("click", function() {
                $(this).parent().parent().hide();
        });

    // Add Tooltips
    $('button').tooltip();
    $('.toggle-button-group').tooltip();

        /*var iframe = $("#iframe");
var newWindow = window.open(iframe.attr(src), 'Dynamic Popup', 'height=' + iframe.height() + ', width=' + iframe.width() + 'scrollbars=auto, resizable=no, location=no, status=no');
newWindow.document.write(iframe[0].outerHTML);
newWindow.document.close();
iframe[0].outerHTML = '';*/ // to remove iframe in page.

	$(".mobileChange").on("blur", function() {
		var mobileNo= $(this).val();
		var url = "<?php echo HOST_URL_PREMIUM; ?>Child-EMR/fetch_parent_details.php?mobileNo="+mobileNo;
	/*	$.get(url, function(response) {
			var data=JSON.parse(response);
			console.log(data.mother_name);
			console.log(response);
			
			$('#mother_name').val(response[0].mother_name);
		});*/
		  $.ajax({
		   url:"Child-EMR/fetch_parent_details.php",
		   method:"POST",
		   data:'mobileNo='+mobileNo,
		     dataType:"JSON",
		   success:function(data)
		   {
			console.log(data);
			$('#mother_name1').val(data[0].mother_name);
			$('#se_mother_age1').val(data[0].mother_age);
			$('#father_name1').val(data[0].father_name);
			$('#se_father_age1').val(data[0].father_age);
			$('#se_phc_location1').val(data[0].phc_location);
			$('.se_country1').val(data[0].country);
			$('.se_state1').val(data[0].state);
			$('#se_city1').val(data[0].city);
			$('#se_address1').val(data[0].address);
		   }
		  })
	});
	
});
</script>


