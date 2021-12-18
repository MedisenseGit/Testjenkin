
 <?php 

 $request  		= str_replace("/premium/", "", $_SERVER['REQUEST_URI']);
 
 $GetLoginHosp = mysqlSelect("a.hosp_id as hosp_id,b.hosp_name as hosp_name,b.hosp_city as hosp_city", "doctor_hosp as a left join hosp_tab as b on a.hosp_id=b.hosp_id", "a.doc_id='".$admin_id."'", "b.hosp_name asc", "", "", "");

				
//$allRecord = mysqlSelect("patient_id,patient_name,patient_email,patient_mob,patient_loc,TImestamp","doc_my_patient","doc_id='".$admin_id."'","TImestamp desc","","","$eu, $limit");

$getDocEMR = mysqlSelect("spec_group_id","specialization as a left join doc_specialization as b on a.spec_id=b.spec_id","b.doc_id='".$admin_id."' and b.doc_type='1'","","","","");

if($getDocEMR[0]['spec_group_id']==1)
{  
	//If 'spec_group_id' is 1, Then it will navigate to Cardio Diabetic EMR
	$navigateLink = "My-Patient-Details";
}
else if($getDocEMR[0]['spec_group_id']==2)
{ 
	//If 'spec_group_id' is 2, Then it will navigate to Ophthal EMR
	$navigateLink = "Ophthal-EMR/";
} 

										
	?>


 <div class="row border-bottom">
        <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
            <!--<form role="search" class="navbar-form-custom" action="">
                <div class="form-group">
                     <input type="text" placeholder="Search for something..." class="form-control" name="top-search" id="top-search">
                </div>
            </form>-->
			
			<?php if($request=="Home"){ ?>
                 <a href="#" class="minimalize-styl-2 btn btn-primary startTour"><i class="fa fa-play"></i> Start Tour</a>
			<?php } ?>
        </div>
            <ul class="nav navbar-top-links navbar-right"  id="VC_request">
                <!--<li>
                    <span class="m-r-sm text-muted welcome-message">Signed in to Hospital Name</span>
                </li>-->
				
				<li class="dropdown">
				
                    <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                       <?php if(!empty($_SESSION['login_hosp_name'])){ ?>Signed in to <?php echo substr($_SESSION['login_hosp_name'],0,15); ?> <i class="fa fa-exchange"></i><?php } ?>
                    </a>
                    <ul class="dropdown-menu dropdown-alerts">
                     <?php foreach ($GetLoginHosp as $GetHospLoginList) { ?>   
						<li>
                            <a href="action?continue=<?php echo $request; ?>&hospital=<?php echo md5($GetHospLoginList['hosp_id']); ?>">
                                <div>
                                    <i class="fa fa-hospital-o fa-fw"></i> <?php echo $GetHospLoginList["hosp_name"]; ?>
                                   <?php if($_SESSION['login_hosp_id']==$GetHospLoginList['hosp_id']){?><span class="label label-primary">Signed IN</span><?php } ?>
                                </div>
                            </a>
                        </li>
                     <?php } ?>
                    </ul>
                </li>
				
					<?php include('VC_Request_list.php'); ?>
				
				<li>
                    <a href="logout.php">
                        <i class="fa fa-sign-out"></i> Log out
                    </a>
                </li>
            </ul>

        </nav>
        </div>
		
		