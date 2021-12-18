<?php
$admin_id 		= $_SESSION['user_id'];
$secretary_id 	= $_SESSION['secretary_id'];
$secretary_name = $_SESSION['user_name'];
$reception_id 	= $_SESSION['secretary_userid'];
	
$Total_Recieved_Count = mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id","b.ref_id='".$admin_id."'","","","","");
	
$countPartners = mysqlSelect("COUNT(partner_id) as Count_Partners","our_partners","","","","","");
	
$countMyPatient= mysqlSelect("COUNT(a.patient_id)  as Total_count","patients_appointment as a inner join patients_transactions as b on a.patient_id=b.patient_id","b.doc_id='".$_SESSION['user_id']."'","","","","");	

//$countMyPatient= mysqlSelect("COUNT(patient_id) as Total_count","doc_my_patient","doc_id='".$_SESSION['user_id']."'","","","","");	
	

//$Total_Appointment_Count = mysqlSelect("COUNT(id) as count","appointment_transaction_detail","pref_doc='".$admin_id."' and hosp_id='".$_SESSION['login_hosp_id']."'","","","","");

$Total_Appointment_Count = mysqlSelect("count( patient_trans_id)  as count","patients_transactions","doc_id='".$admin_id."' and hosp_id='".$_SESSION['login_hosp_id']."'","","","","");

$getDoctorProfile= mysqlSelect("doc_photo as Doc_Photo,ref_id as Doc_Id,ref_name as Doc_name,ref_mail as doc_mail,contact_num as Doc_contact,ref_address as Doc_city,doc_state as Doc_State,doc_country as Doc_Country,sponsor_id as sponsor_id","referal","ref_id='".$admin_id."'","","","","");
	
$chkProfileTime = mysqlSelect("*","doc_time_set","doc_id=".$admin_id."","","","","");
	
$checkSetting= mysqlSelect("*","doctor_settings","doc_id='".$admin_id."' and doc_type='1'","","","","");
	
//$Total_Visit_Count = mysqlSelect("COUNT(a.patient_id) as Tot_App_Count","doc_patient_episodes as a left join doc_my_patient as b on a.patient_id=b.patient_id","a.admin_id='".$admin_id."'","","","","");

$Total_Visit_Count = mysqlSelect("COUNT(a.patient_id) as Tot_App_Count","doc_patient_episodes as a LEFT JOIN patients_appointment as b on a.patient_id = b.patient_id","a.admin_id='".$admin_id."'","","","","");

$check_reception_permission = mysqlSelect("*","receptionist_permission","doc_id='".$admin_id."' and reception_id='".$reception_id."'","","","","");
	
$getDocEMR = mysqlSelect("spec_group_id","specialization as a left join doc_specialization as b on a.spec_id=b.spec_id","b.doc_id='".$admin_id."' and b.doc_type='1'","","","","");
				
//$getLastMypatient= mysqlSelect("patient_id","doc_my_patient","doc_id='".$_SESSION['user_id']."'","patient_id desc","","","");

$getLastMypatient= mysqlSelect("DISTINCT (a.patient_id) as patient_id","patients_appointment as a INNER JOIN patients_transactions as b on a.patient_id=b.patient_id","b.doc_id='".$_SESSION['user_id']."'","a.patient_id desc","","","");
if(COUNT($getLastMypatient)>0)
{
					
	$patientid=md5($getLastMypatient[0]['patient_id']);

	$checkTodayVisit= mysqlSelect("episode_id","doc_patient_episodes","admin_id='".$admin_id."' and patient_id='".$getLastMypatient[0]['patient_id']."' and DATE_FORMAT(date_time,'%Y-%m-%d')='".date('Y-m-d')."'","episode_id desc","","","");
	if(COUNT($checkTodayVisit)>0)
	{
		$getEpisodeId="&episode=".md5($checkTodayVisit[0]['episode_id']);
	}
	else
	{
		$getEpisodeId="";
	}
}
else
{
	$patientid="0";	
}
				
if($getDocEMR[0]['spec_group_id']==1)
{  //If 'spec_group_id' is 1, Then it will navigate to Cardio Diabetic EMR

	$sidenavigateLink    = HOST_URL_PREMIUM."My-Patient-Details?p=".$patientid.$getEpisodeId;
	$_SESSION['EMR_URL'] = HOST_URL_PREMIUM."My-Patient-Details?p=";
}
else if($getDocEMR[0]['spec_group_id']==2)
{ //If 'spec_group_id' is 2, Then it will navigate to Ophthal EMR
	$sidenavigateLink 	 = HOST_URL_PREMIUM."Ophthal-EMR/?p=".$patientid;
	$_SESSION['EMR_URL'] = HOST_URL_PREMIUM."Ophthal-EMR/?p=";
}	

$quickPrescLink = "Quick-Prescription?p=".$patientid;
?>
<nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav metismenu" id="side-menu" style="position:fixed;">
                <li class="nav-header">
                    <div class="dropdown profile-element"> <span>
                            <a href="Home"><img alt="image" class="img" src="../assets/img/Practice_premium.png" /></a>
                             </span>
							 <span></span>
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="clear"> <span class="block m-t-xs">
                                <?php  $img_url=IMG_URL_VIEW."Doc/".$getDoctorProfile[0]['Doc_Id']."/".$getDoctorProfile[0]['Doc_Photo']; ?>
                                <img alt="image" class="img-circle" src="<?php if(!empty($getDoctorProfile[0]['Doc_Photo']) && $secretary_id!=1)
                                { 
                                    echo $img_url;
                                }
                                else 
                                { 
                                    echo "../assets/img/anonymous-profile.png"; 
                                } 
                                ?>" width="36" /> <strong class="font-bold">
                                <?php 
                                if($secretary_id!=1)
                                { 
                                    echo substr($getDoctorProfile[0]['Doc_name'],0,12);
                                }
                                else
                                { 
                                    echo substr($secretary_name,0,12); 
                                }
                                 ?></strong>
                              <b class="caret"></b></span> </span> </a>
                        <ul class="dropdown-menu animated fadeInRight m-t-xs">
                           <?php if($secretary_id!=1) { ?> <li><a href="Profile">Profile</a></li><?php } ?>
                           <li class="divider"></li>
						    <li><a href="Add-Diagnostics">Settings</a></li>
						  <li class="divider"></li>
                            <li><a href="logout.php">Logout</a></li>
							
                        </ul>
                    </div>
                    <div class="logo-element">
                        <img alt="image" class="img" src="../assets/img/Practice_premium.png" width="50px" />
                    </div>
                </li>
               
               
                <li>
                    <a href="Home"><i class="fa fa-home"></i> <span class="nav-label">Home</span></a>
                    
                </li>
				<li>
                    <a href="Appointments" id="myAppointment"><i class="fa fa-calendar"></i> <span class="nav-label">Appointments</span><?php if($Total_Appointment_Count[0]['count']>0){ ?><span class="label label-info pull-right"><?php echo $Total_Appointment_Count[0]['count']; ?></span><?php } ?></a>
                   
                </li>
				<?php if($secretary_id!=1) { ?><li>
                    <a href="<?php echo $sidenavigateLink; ?>"><i class="fa fa-address-card"></i> <span class="nav-label">EMR</span></a>
                    
                </li>
				<!--<li>
                    <a href="<?php echo $quickPrescLink; ?>"><i class="fa fa-address-card"></i> <span class="nav-label">Quick Prescription</span></a>
                    
                </li>-->
				<?php } ?>
                <li>
					<a href="#"><i class="fa fa-th-large"></i> <span class="nav-label">Reports</span> <span class="fa arrow"></span></a>
					 <ul class="nav nav-second-level collapse">
                        <li><a href="My-Patients" id="myPatient"><i class="fa fa-wheelchair"></i> <span class="nav-label">My Patients</span><?php if($Total_Visit_Count[0]['Tot_App_Count']>0){ ?><span class="label label-info pull-right"><?php echo $Total_Visit_Count[0]['Tot_App_Count']; ?></span><?php } ?></a></li>
						<li><a href="Drug-Reports" ><i class="fa fa-plus-square"></i> <span class="nav-label">Drug Reports</span></a></li>
						<li><a href="Discharge-Summary" ><i class="fa fa-plus-square"></i> <span class="nav-label">Create Reports</span></a></li>
						
					</ul>
                    
                   
                </li>
				
				<li>
                    <a href="Cases-Recieved" id="casesSent"><i class="fa fa-user"></i> <span class="nav-label">Cases Received</span><span class="label label-info pull-right"><?php echo $Total_Recieved_Count[0]['Total_count']; ?></span></a>
                   
                </li>
				<li>
                    <a href="Surgery-Scheduler"><i class="fa fa-clock-o"></i> <span class="nav-label">Surgery Scheduler</span></a>
                   
                </li>
				<?php if($checkSetting[0]['payment_opt']=="1"){ ?>
				<li>
                    <a href="Payments"><i class="fa fa-credit-card"></i> <span class="nav-label">Payments</span></a>
                   
                </li>
				<?php } ?>
				<li>
                    <a href="Doctor-Inout-Referrals"><i class="fa fa-external-link"></i> <span class="nav-label">Referrals</span></a>
                   
                </li>
				<li>
                    <a href="Blog-Surgical-List"><i class="fa fa-rss"></i> <span class="nav-label">Add Blogs & Videos</span></a>
                   
                </li>
				<li>
                    <a href="Build-Website/"><i class="fa fa-globe"></i> <span class="nav-label">Build Your Website</span></a>
                   
                </li>
				
				<li>
                    <a href="Add-Diagnostics"><i class="fa fa-cog"></i> <span class="nav-label">Settings</span></a>
                   
                </li>
				<?php if($secretary_id!=1) { ?>
				<li>
                    <a href="Profile"><i class="fa fa-user-circle-o"></i> <span class="nav-label">Profile</span></a>
                   
                </li>
				<?php } ?>
				<?php if($admin_id==3727) { ?>
				<li>
                    <a href="Pharma-validattion-List"><i class="fa fa-check"></i> <span class="nav-label">Pharma Validation</span></a>
                   
                </li>
				<?php } ?>
                
            </ul>

        </div>
    </nav>