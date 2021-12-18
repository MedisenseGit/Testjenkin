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
                                <form  method="post" action="add_details.php" autocomplete="off">
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
                                    <h1 class="text-navy"><b><?php echo $patient_tab[0]['patient_name']; ?><!--(<?php echo $patient_tab[0]['patient_id']; ?>)--></b>  <small><a href="#"  data-toggle="modal" class="text-navy" data-target="#myModal"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> </a></small></h1>
                                    <h5><i class="fa fa-transgender"></i>  <?php echo $gender; ?></h5>
									<?php if(!empty($patient_tab[0]['patient_age']) && $patient_tab[0]['DOB']!="0000-00-00"){ ?><h5><img src="<?php echo HOST_MAIN_URL; ?>premium/icons-age.png" width="13"/>  <?php if($patient_tab[0]['DOB']!="0000-00-00") { echo date('d-M-Y',strtotime($patient_tab[0]['DOB']))." - "; } if(!empty($patient_tab[0]['patient_age'])) { echo $patient_tab[0]['patient_age']." Years"; } ?></h5><?php } ?>
				
				</div>
				<div class="col-md-2">
				<h5><i class="fa fa-map-marker"></i> <?php echo $patient_tab[0]['patient_addrs'].", ".$patient_tab[0]['patient_loc'].", ".$patient_tab[0]['pat_state']; ?></h5>
				<?php if(!empty($patient_tab[0]['patient_mob'])){ ?><h5><i class="fa fa-phone"></i> <?php echo $patient_tab[0]['patient_mob']; ?></h5><?php } ?>
                <?php if(!empty($patient_tab[0]['patient_email'])){ ?><h5><i class="fa fa-envelope"></i> <?php echo $patient_tab[0]['patient_email']; ?></h5><?php } ?>                 
				</div> 
				
				<div class="col-md-2">
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
				</div>
				
				<?php if(!empty($patient_tab[0]['height_cm']) && !empty($patient_tab[0]['weight'])) { ?>
				<div class="col-md-2">
				<h3>BMI   <?php 
				
				
				if($bmi>=18.5 && $bmi<=24.9){ echo $bmiStatus="<span class='label label-primary'>Healthy</span>"; } else if($bmi>=25 && $bmi<=30){ echo $bmiStatus="<span class='label label-warning'>Overweight</span>"; } else if($bmi>=30){ echo $bmiStatus="<span class='label label-danger'>Obese</span>"; } else { echo $bmiStatus="<span class='label label-danger'>Underweight</span>"; } ?></h3>
				<h1 class="text-navy"><b><?php echo $bmi; ?></b></h1>
                <?php if($patient_tab[0]['hyper_cond']!=0){  if($patient_tab[0]['hyper_cond']==1){ $hyperStatus="<span class='label label-danger'>Yes</span>"; } else if($patient_tab[0]['hyper_cond']==2){ $hyperStatus="<span class='label label-primary'>No</span>"; }?> <h5>Hypertension <?php echo $hyperStatus; ?></h5> <?php } ?>      
				<?php if($patient_tab[0]['diabetes_cond']!=0){ if($patient_tab[0]['diabetes_cond']==1){ $dibetesStatus="<span class='label label-danger'>Yes</span>"; } else if($patient_tab[0]['diabetes_cond']==2){ $dibetesStatus="<span class='label label-primary'>No</span>"; }?><h5>Diabetes <?php echo $dibetesStatus; ?></h5> <?php } ?>   				 
				</div>	
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
                                <div class="modal-dialog">
                                <div class="modal-content animated bounceInRight">
								<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  action="my_patient_profile_save.php"  name="frmAddPatient" id="frmAddPatient">
									
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            <?php if(!empty($patient_tab[0]['patient_image'])){ ?><img src="<?php echo HOST_URL_PREMIUM; ?>patientImage/<?php echo $patient_tab[0]['patient_id']; ?>/<?php echo $patient_tab[0]['patient_image']; ?>" width="100" class="img-thumbnail" /><?php } else { ?><img src="<?php echo HOST_MAIN_URL; ?>assets/img/user_noimg.png" width="100" class="img-thumbnail" /><?php } ?>
											<center><input type="file" name="txtPhoto" style="margin-left:50px;"></center>
                                            <h4 class="modal-title"><?php if(is_numeric($_GET['p'])){ echo $_GET['n']; } else { echo $patient_tab[0]['patient_name']; } ?></h4>
                                            <small class="font-bold">Patient Profile</small>
                                        </div>
										<input type="hidden" name="patient_id" value="<?php echo $patient_tab[0]['patient_id']; ?>">
										<input type="hidden" name="doc_id" value="<?php echo $patient_tab[0]['doc_id']; ?>">
                                    
                                        <div class="modal-body">
                                            <div class="form-group"><label>Patient Name</label> <input type="text" id="se_pat_name" name="se_pat_name" value="<?php if($_GET['p']==="0"){ echo $_GET['n']; } else { echo $patient_tab[0]['patient_name']; } ?>" class="form-control"></div>
                                 
								 <div class="row"><div class="form-group"><label class="col-sm-2 control-label"><?php if($checkSetting[0]['patient_age_type']=="0" || $checkSetting==false){ echo "Age"; } else if($checkSetting==true && $checkSetting[0]['patient_age_type']=="1") { echo "DOB"; } ?></label>
                                    <div class="col-sm-4">
									<?php if($checkSetting[0]['patient_age_type']=="0" || $checkSetting==false){ ?>
										<input type="text" id="se_pat_age" name="se_pat_age" value="<?php echo $patient_tab[0]['patient_age']; ?>" class="form-control">
                                    <?php } else if($checkSetting==true && $checkSetting[0]['patient_age_type']=="1") { ?>
									<input id="dateadded" name="date_birth" type="text" <?php if($patient_tab[0]['DOB']!="0000-00-00" && $_GET['p']!="0"){ ?>value="<?php echo date('d/m/Y',strtotime($patient_tab[0]['DOB']));?>"<?php } else if($_GET['p']=="0"){ ?>value=""<?php } ?> placeholder="DD/MM/YYYY" class="form-control" >
                                    <?php } ?>
									</div>
									<label class="col-sm-2 control-label">Gender</label>
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
									<?php } else if($patient_tab[0]['patient_gen']=="2") { ?>
									<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio1" value="1" name="se_gender">
                                            <label for="inlineRadio1"> Male </label>
                                        </div>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="2" name="se_gender" checked="">
                                            <label for="inlineRadio2"> Female </label>
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
									<label class="col-sm-3 control-label">Height(Centimeter)</label>
									<div class="col-sm-3">
									<input type="text"  placeholder="in cm"  name="height" id="aninput" onkeypress="return validateFloatKeyPress(this,event);" value="<?php echo $patient_tab[0]['height_cm']; ?>" class="form-control" maxlength="3">								
									</div>
									<label class="col-sm-2 control-label">Weight(Kgs)</label>
									<div class="col-sm-3">
									<input type="text" placeholder="in kgs"  name="weight" maxlength="3" value="<?php echo $patient_tab[0]['weight']; ?>" class="form-control">								
									</div>
								</div>
								
										
										<div class="form-group"><label>Country</label> <select class="form-control" name="se_country" name="se_country">
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
										<div class="form-group"><label>State</label> <select class="form-control"  name="se_state" id="se_state" placeholder="State"  >
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
										
										<div class="form-group"><label>City</label> <input type="text" id="se_city" name="se_city" value="<?php if(!empty($patient_tab[0]['patient_loc'])) { echo $patient_tab[0]['patient_loc'];} else { echo $get_doc_details[0]['doc_city']; } ?>" class="form-control"></div>
										
										<div class="form-group"><label>Address</label> <input type="text" id="se_address" name="se_address" value="<?php echo $patient_tab[0]['patient_addrs']; ?>" class="form-control"></div>
										<div class="form-group"><label>Mobile</label> <input type="text" id="se_phone_no" name="se_phone_no" value="<?php echo $patient_tab[0]['patient_mob']; ?>" class="form-control"></div>
										<div class="form-group"><label>Email</label> <input type="email" id="se_email" name="se_email" value="<?php echo $patient_tab[0]['patient_email']; ?>" class="form-control"></div>
																				
									
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
                                <div class="modal-dialog">
                                <div class="modal-content animated bounceInRight">
								<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  action="my_patient_profile_save.php"  name="frmAddPatient" id="frmAddPatient">
									
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            <img src="<?php echo HOST_MAIN_URL; ?>assets/img/user_noimg.png" width="100" class="img-thumbnail" />
											<center><input type="file" name="txtPhoto" style="margin-left:50px;"></center>
                                           
                                            <small class="font-bold">Add New Patient</small>
                                        </div>
										
                                        <div class="modal-body">
										    <div class="form-group">
									 
									 <label>Choose Doctor </label>

                                    <select data-placeholder="Choose doctor..." class="chosen-select" name="doc_id" id="doc_id" required="required" >
											<option value="" selected>Choose Doctor</option>
												<?php 
												$getDoctor= mysqlSelect("a.ref_id as Ref_Id,a.ref_name as Doc_name,c.hosp_name as Hosp_name,d.spec_name as Department","referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id inner join specialization as d on d.spec_id=a.doc_spec","c.hosp_id='".$admin_id."'","a.ref_name asc","","","");
													$i=30;
													foreach($getDoctor as $DocList){
												?> 
														
														<option value="<?php echo stripslashes($DocList['Ref_Id']); ?>" />
														<?php echo stripslashes($DocList['Doc_name']).", ".stripslashes($DocList['Department']).", ".stripslashes($DocList['Hosp_name']);?></option>
													
													
													<?php 
														$i++;
													}?> 
										</select>
									
                               </div>
                                            <div class="form-group"><label>Patient Name</label> <input type="text" id="se_pat_name" name="se_pat_name" value="" class="form-control"></div>
                                 
								 <div class="row"><div class="form-group"><label class="col-sm-2 control-label"><?php if($checkSetting[0]['patient_age_type']=="0" || $checkSetting==false){ echo "Age"; } else if($checkSetting==true && $checkSetting[0]['patient_age_type']=="1") { echo "DOB"; } ?></label>
                                    <div class="col-sm-4">
									<?php if($checkSetting[0]['patient_age_type']=="0" || $checkSetting==false){ ?>
										<input type="text" id="se_pat_age" name="se_pat_age" value="" class="form-control">
                                    <?php } else if($checkSetting==true && $checkSetting[0]['patient_age_type']=="1") { ?>
									<input id="dateadded" name="date_birth" type="text" value="" placeholder="DD/MM/YYYY" class="form-control" >
                                    <?php } ?>
									</div>
									<label class="col-sm-2 control-label">Gender</label>
                                    <div class="col-sm-4">
									
									<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio1" value="1" name="se_gender">
                                            <label for="inlineRadio1"> Male </label>
                                        </div>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="2" name="se_gender">
                                            <label for="inlineRadio2"> Female </label>
                                        </div>
									
                                    </div>
                                </div>
								</div><br>
										
									<div class="form-group">
									<label class="col-sm-3 control-label">Height(Centimeter)</label>
									<div class="col-sm-3">
									<input type="text"  placeholder="in cm"  name="height" id="aninput" onkeypress="return validateFloatKeyPress(this,event);" value="" class="form-control" maxlength="3">								
									</div>
									<label class="col-sm-2 control-label">Weight(Kgs)</label>
									<div class="col-sm-3">
									<input type="text" placeholder="in kgs"  name="weight" maxlength="3" value="" class="form-control">								
									</div>
								</div>
								
										
										<div class="form-group"><label>Country</label> <select class="form-control" name="se_country" name="se_country">
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
										<div class="form-group"><label>State</label> <select class="form-control"  name="se_state" id="se_state" placeholder="State"  >
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
										
										<div class="form-group"><label>City</label> <input type="text" id="se_city" name="se_city" value="<?php  echo $get_doc_details[0]['doc_city']; ?>" class="form-control"></div>
										
										<div class="form-group"><label>Address</label> <input type="text" id="se_address" name="se_address" value="" class="form-control"></div>
										<div class="form-group"><label>Mobile</label> <input type="text" id="se_phone_no" name="se_phone_no" value="" class="form-control" maxlength="10" minlength="10"></div>
										<div class="form-group"><label>Email</label> <input type="email" id="se_email" name="se_email" value="" class="form-control"></div>
																				
									
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
							
							$get_app_token_details = mysqlSelect("*","appointment_token_system","hosp_id='".$admin_id."' and doc_type='1' and app_date='".$cur_Date."' and status!='Cancelled'","token_no asc","","","");
							
							if(COUNT($get_app_token_details)>0){
								
							
							while(list($key, $value) = each($get_app_token_details)){
								
								$checkTodayVisit= mysqlSelect("episode_id","doc_patient_episodes"," patient_id='".$value['patient_id']."' and DATE_FORMAT(date_time,'%Y-%m-%d')='".date('Y-m-d')."'","episode_id desc","","","");
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
							?>
                            <tr>
                               <td><?php if($value['token_no']!="555") { echo "<button class='btn btn-success btn-circle' type='button'>".$value['token_no']."</button>";} else { echo "<button class='btn btn-primary btn-xs' type='button'>Online</button>"; } ?></td>
								<td><a href="<?php echo $_SESSION['EMR_URL']."?p=".md5($value['patient_id']).$getEpisodeId; ?>"><?php echo $value['patient_name']; ?></a></td>
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