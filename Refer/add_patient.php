<?php
ob_start();
error_reporting(0); 
session_start();
$admin_id = $_SESSION['user_id'];
if(empty($admin_id)){
	header("Location:index.php");
}
require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();


//$busResult = $objQuery->mysqlSelect("hosp_id as Hosp_Id,hosp_name as Hosp_Name,hosp_city as Hosp_City,hosp_state as Hosp_State","hosp_tab","company_id='".$admin_id."'","hosp_id desc","","","");

$get_pro = $objQuery->mysqlSelect("ref_id as RefId","referal","doc_spec!=555 and anonymous_status!=1","Tot_responded desc","","","");
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Add Patient</title>

    <?php include('support_file.php'); ?>
	<script src="search/jquery-1.11.1.min.js"></script>
	<script src="search/jquery-ui.min.js"></script>
	<script src="search/jquery.select-to-autocomplete.js"></script>
	<script>
	  (function($){
	    $(function(){
	      $('#selectref').selectToAutocomplete();
	      
	    });
	  })(jQuery);
	  
	  (function($){
	    $(function(){
	      $('#selectRefpartner').selectToAutocomplete();
	      
	    });
	  })(jQuery);
	  
	  function call_refer(){
		   //alert("Logging in.........");
		   var user=document.getElementById('selectref').value;
		   		   
		   if(user==""){
		     alert("Please choose hospital doctor");
			 return false;
		   }
		   
		 }
		 
		 
		 
		(function () {
    $(document).on("click", "#refer_patient", function (event) {
        var elem = $(event.currentTarget);
        elem.addClass('active');
        var formdata = $("#filterdata").serializeArray();
        var url1 = "/SelectUnit";
        $.ajax({url:"/echo/html", data: {delay:1}}).always(function(){
            elem.removeClass('active');
        });
    });
})(); 
		 
	</script>

	<style>
	
    .ui-autocomplete {
      padding: 10px;
      list-style: none;
      background-color: #fff;
      width: 720px;
      border: 1px solid #B0BECA;
      max-height: 350px;
      overflow-x: hidden;
	   white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  max-width: 720px;
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

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <?php include_once('side_menu.php'); ?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
           

            <div class="clearfix"></div>

            <div class="row">
           
              <!-- /form color picker -->
				<!-- form input mask -->
              <div class="col-md-12 col-xs-12" id="addHospSection">
						<?php
						if($_GET['response']=="add"){ ?>
						<div class="alert alert-success alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                    </button>
                    <strong>SUCCESS !!</strong> Details are added successfully.
                  </div>
						
						<?php 
						} else if($_GET['response']=="update"){ ?>
						<div class="alert alert-success alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                    </button>
                    <strong>UPDATED !!</strong> Details are updated successfully.
                  </div>
						<?php } else if($_GET['response']=="error"){ ?>
						<div class="alert alert-danger alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                    </button>
                    <strong>ERROR !!</strong> please fill required field properly.
                  </div>
						<?php } ?>
                <div class="x_panel">
                  <div class="x_title">
                  <h2>ADD PATIENT</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />
					<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  action="add_patient_details.php"  name="frmAddPatient" id="frmAddPatient">
						<div class="form-group">
                        
						<label class="control-label col-md-1 col-sm-1 col-xs-12">Choose Hospital Doctor</label>
                        <div class="col-md-7 col-sm-7 col-xs-12">
                          <select class="left form-control autotab" name="selectref" id="selectref" placeholder="Select Hospital Doctor" style="float:left; width:550px; padding:5px;">
													<option value="">Select Hospital Doctor</option>
													<?php foreach($get_pro as $listDoc) {
													$get_Ref = $objQuery->mysqlSelect('ref_id,ref_name,ref_address','referal',"ref_id='".$listDoc['RefId']."'","","","","");
													$get_spec = $objQuery->mysqlSelect('spec_name','specialization',"spec_id='".$get_Ref[0]['doc_spec']."'","","","","");
													$getHosp = $objQuery->mysqlSelect("hosp_name","hosp_tab as a left join doctor_hosp as b on a.hosp_id=b.hosp_id","doc_id='".$get_Ref[0]['ref_id']."'" ,"","","",""); 
													//$ResponseCount = $objQuery->mysqlSelect("*","patient_referal","ref_id='".$listDoc['ref_id']."'" ,"","","","");
													
													
													?>	
													
													
											  <option value="<?php echo $get_Ref[0]['ref_id']; ?>"  ><?php echo addslashes($get_Ref[0]['ref_name'])."&nbsp;".addslashes($get_spec[0]['spec_name'])."&nbsp;".addslashes($getHosp[0]['hosp_name'])."&nbsp;".addslashes($get_Ref[0]['ref_address']); ?></option>
											<?php } ?>
											</select>
                        </div>
						
						<label class="control-label col-md-1 col-sm-1 col-xs-12">Patient Name <span class="required">*</span></label>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                          <input type="text" id="se_pat_name" name="se_pat_name" required="required" class="form-control" placeholder="">
                        </div>
                      </div>
					  <label class="control-label col-md-1 col-sm-1 col-xs-12">Gender <span class="required">*</span></label>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                         <div class="radio"><label>
                             <input type="radio" name="se_gender" id="male" checked="checked" value="1" >Male
                            </label>
							<label>
                              <input type="radio" name="se_gender" id="female" value="2" >Female
                          </label>
						  </div>
                        </div>
						<div class="form-group">
                        						<label class="control-label col-md-1 col-sm-1 col-xs-12">Contact Number <span class="required">*</span></label>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                          <input type="text" id="se_phone_no" name="se_phone_no" required="required" class="form-control" placeholder="10 digit Mobile No." maxlength="10">
                        </div>
						<label class="control-label col-md-1 col-sm-1 col-xs-12">Email</label>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                          <input type="email" id="se_email" name="se_email" class="form-control" placeholder="">
                        </div>
                      </div>
					  <br>
					
					  <div class="form-group">
                        <label class="control-label col-md-1 col-sm-1 col-xs-12">Country <span class="required">*</span></label>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                          <select class="form-control" name="se_country" required="required" name="se_country">
                            <option value="India" selected>India</option>
												<?php 
												$getCountry= $objQuery->mysqlSelect("*","countries","","country_name asc","","","");
													$i=30;
													foreach($getCountry as $CountryList){
												?> 
														
														<option value="<?php echo stripslashes($CountryList['country_name']); ?>" />
														<?php echo stripslashes($CountryList['country_name']);?></option>
													
													
													<?php 
														$i++;
													}?> 
                          </select>
                        </div>
						<label class="control-label col-md-1 col-sm-1 col-xs-12">State <span class="required">*</span></label>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                          <select class="form-control"  name="se_state" id="se_state" required="required" placeholder="State"  >
													<option value="">Select State</option>
													<?php
													$GetState = $objQuery->mysqlSelect("*","countries as a left join states as b on a.country_id=b.country_id", "a.country_id=100", "b.state_name asc", "", "", "");
													foreach ($GetState as $StateList) {
													?>
													<option value="<?php echo $StateList["state_name"];	?>"><?php echo $StateList["state_name"]; ?></option>
													
													<?php
													}
													?>
							</select>
						</div>
						<label class="control-label col-md-1 col-sm-1 col-xs-12">City <span class="required">*</span></label>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                          <input type="text" id="se_city" name="se_city" required="required" class="form-control" placeholder="">
                        </div>
                      </div>
					  <br>
					  <div class="form-group">
                        
						<label class="control-label col-md-1 col-sm-1 col-xs-12">Address </label>
                        <div class="col-md-7 col-sm-7 col-xs-12">
                          <textarea class="form-control" id="se_address" name="se_address"  rows="3"></textarea>
                        
                        </div>
						
						<label class="control-label col-md-1 col-sm-1 col-xs-12">Add Attachments</label>
                           <div class="col-md-3 col-sm-3 col-xs-12">
											  <em>Multiple Select</em>
											  <input type="file" id="file-3" name="file-3[]"  multiple="true">
											</div>
                      </div>
					  <br>
					  <div class="form-group">
						<label class="control-label col-md-1 col-sm-1 col-xs-12">Specialization <span class="required">*</span></label>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                          <select class="form-control" name="se_depart" id="se_depart" required="required" placeholder="Specialization"  >
									<option value="">Select Specialization</option>
            
												<?php
												$SrcName = $objQuery->mysqlSelect("*", "specialization", "", "spec_name asc", "", "", "");
												$i       = 30;
												foreach ($SrcName as $SrcNameList) {
												?> 
																						
																									<option value="<?php
													echo stripslashes($SrcNameList['spec_id']);
												?>" />
																									<?php
													echo stripslashes($SrcNameList['spec_name']);
												?></option>
																								
																									<?php
													$i++;
												}
												?>
							</select>
                        </div>
						</div>
					  <br>	
					  <div class="form-group">
					  
					  <label class="control-label col-md-1 col-sm-1 col-xs-12">Chief Medical Complaint <span class="required">*</span></label>
                        <div class="col-md-7 col-sm-7 col-xs-12">
                          <textarea class="form-control" id="se_info" required="required" name="se_info" rows="3"></textarea>
                        
                        </div>
					  
					 
					  </div>
					  
					   <div class="form-group">
					   <label class="control-label col-md-2 col-sm-2 col-xs-12" style="float:left;">
					<a data-toggle="collapse" data-target="#demo" style="cursor:pointer" >					
					More(optional)</a> <i class="fa fa-arrow-down"></i></label>
					</div>
					<div id="demo" class="collapse">	
					
					<div class="form-group">
					<label class="control-label col-md-1 col-sm-1 col-xs-12">Patient Age </label>
                        <div class="col-md-2 col-sm-2 col-xs-12">
                          <input type="text" id="se_pat_age" name="se_pat_age" class="form-control" placeholder="">
                        </div>
						<label class="control-label col-md-1 col-sm-1 col-xs-12">Patient Weight</label>
                        <div class="col-md-2 col-sm-2 col-xs-12">
                          <input type="text" id="se_weight" name="se_weight" class="form-control" placeholder="">
                        </div>
						
					</div>
					
					
					<div class="form-group">
                        
						<label class="control-label col-md-1 col-sm-1 col-xs-12">Hypertension</label>
                        <div class="col-md-2 col-sm-2 col-xs-12">
                          <div class="radio"><label>
                              <input type="radio" name="se_hyper" id="option2" value="1">Yes
                            </label>
							<label>
                              <input type="radio" name="se_hyper" checked="checked" id="option4" value="0" > No
                          </label>
						  </div>
						</div>
						<label class="control-label col-md-1 col-sm-1 col-xs-12">Diabetes</label>
							<div class="col-md-2 col-sm-2 col-xs-12">
							<div class="radio"><label>
                              <input type="radio" name="se_diabets" value="1" id="option3" >Yes
                            </label>
							<label>
                              <input type="radio" name="se_diabets" checked="checked" id="option4" value="0"> No
                          </label>
						  </div>
							</div>
                      </div>
					<br>
					<div class="form-group">
                        <label class="control-label col-md-1 col-sm-1 col-xs-12">Contact Person </label>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                          <input type="text" id="se_con_per" name="se_con_per"  class="form-control" placeholder="">
                        </div>
						<label class="control-label col-md-1 col-sm-1 col-xs-12">Current Treating Doctor</label>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                          <input type="text" id="se_treat_doc" name="se_treat_doc" class="form-control" placeholder="">
                        </div>
						<label class="control-label col-md-1 col-sm-1 col-xs-12">Current Treating Hospital</label>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                          <input type="text" id="se_treat_hosp" name="se_treat_hosp" class="form-control" placeholder="">
                        </div>
                      </div>
					  <br>
					  <div class="form-group">
                        <label class="control-label col-md-1 col-sm-1 col-xs-12">Detailed Description</label>
                        <div class="col-md-7 col-sm-7 col-xs-12">
                          <textarea class="form-control" id="se_des"  name="se_des" rows="3"></textarea>
                        
                        </div>
						
                      </div>
					  <br>
					  
					  <div class="form-group">
                        
						<label class="control-label col-md-1 col-sm-1 col-xs-12">Query to the doctors</label>
                        <div class="col-md-7 col-sm-7 col-xs-12">
                          <textarea class="form-control" id="se_query" name="se_query" rows="3"></textarea>
                        
                        </div>
                      </div>
					  <br>
					 
					  </div>
					  
					  <div class="form-group">
                        
                      </div>
                      </div>
						<br><br>
                    <div class="form-group-left">
					
                        <div class="col-md-1 col-sm-1 col-xs-12 col-md-offset-1" style="margin-top:20px;">
						<button type="submit" name="refer_patient" id="refer_patient" class="btn btn-success"><i class="fa fa-mail-forward"></i> SEND CASE </button>
                        </div>
						<div class="col-md-1 col-sm-1 col-xs-12 col-md-offset-1" style="margin-top:20px;">
						
                         <button type="submit" name="save_patient" id="save_patient" class="btn btn-primary"><i class="fa fa-floppy-o"></i> SAVE </button>
                          
                        </div>
                      </div>
						

                    </form>
                  </div>
				  <br><br><br><br><br><br><br><br>
				   
                </div>
				
				
              </div>
              <!-- /form input mask -->
              
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->

         <?php include_once('footer.php'); ?>
      </div>
    </div>

    <!-- jQuery -->
    <script src="../Hospital/vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../Hospital/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="../Hospital/vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="../Hospital/vendors/nprogress/nprogress.js"></script>
    
    <!-- jQuery Knob -->
    <script src="../Hospital/vendors/jquery-knob/dist/jquery.knob.min.js"></script>
    <!-- Cropper -->
    <script src="../Hospital/vendors/cropper/dist/cropper.min.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="../Hospital/build/js/custom.min.js"></script>
  <!-- Assets -->
    <script src="../Hospital/hover-dropdown/bootstrap-hover-dropdown.min.js"></script>
  </body>
</html>