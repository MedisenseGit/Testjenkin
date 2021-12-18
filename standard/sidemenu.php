<?php
$admin_id = $_SESSION['user_id'];
$countFavour = $objQuery->mysqlSelect("COUNT(a.ref_id) as Count_Favour","referal as a inner join specialization as b on a.doc_spec=b.spec_id inner join add_favourite_doctor as c on c.doc_id=a.ref_id","(a.doc_spec!=555 and a.anonymous_status!=1) and (c.user_id='".$admin_id."' and c.user_type=1)","","","","");
	$countConnection = $objQuery->mysqlSelect("COUNT(ref_id) as Count_Connections","referal","anonymous_status!=1","","","","");
	$countMyPatient= $objQuery->mysqlSelect("COUNT(patient_id) as Total_count","my_patient","partner_id='".$admin_id."'","","","","");		
	$Total_Appointment_Count = $objQuery->mysqlSelect("COUNT(a.appoint_id) as count","partner_appointment_transaction as a inner join our_partners as b on a.pref_doc=b.partner_id","a.pref_doc='".$admin_id."'","","","","");
	$getPartner = $objQuery->mysqlSelect("*","our_partners","partner_id='".$admin_id."'","","","","");
	$getCasesSent = $objQuery->mysqlSelect("COUNT(a.patient_id) as Count","patient_tab as a left join source_list as b on a.patient_src=b.source_id","b.partner_id='".$admin_id."'","","","","");
	$chkProfileTime = $objQuery->mysqlSelect("*","ref_doc_time_set","doc_id=".$admin_id."","","","","");
										
?>
<nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav metismenu" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown profile-element"> <span>
                            <a href="Home"><img alt="image" class="img" src="../assets/img/Practice_standard.png" /></a>
                             </span>
							 <span></span>
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="clear"> <span class="block m-t-xs"><img alt="image" class="img-circle" src="<?php if(!empty($getPartner[0]['doc_photo'])){ echo "partnerProfilePic/".$getPartner[0]['partner_id']."/".$getPartner[0]['doc_photo']; } else { echo "../assets/img/anonymous-profile.png"; } ?>" width="36" /> <strong class="font-bold"><?php echo $_SESSION['company_name']; ?></strong>
                              <b class="caret"></b></span> </span> </a>
                        <ul class="dropdown-menu animated fadeInRight m-t-xs">
                            <li><a href="Profile">Profile</a></li>
                           <li class="divider"></li>
                            <li><a href="logout.php">Logout</a></li>
                        </ul>
                    </div>
                    <div class="logo-element">
                        <img alt="image" class="img" src="../assets/img/Practice_premium.png" width="50px" />
                    </div>
                </li>
               
               
                <li>
                    <a href="Home"><i class="fa fa-home"></i> <span class="nav-label">Home</span><span class="fa arrow"></span></a>
                    
                </li>
                <li>
                    <a href="My-Patients" id="myPatient"><i class="fa fa-wheelchair"></i> <span class="nav-label">My Patients</span><span class="label label-info pull-right"><?php echo $countMyPatient[0]['Total_count']; ?></span></a>
                   
                </li>
				<li>
                    <a href="Appointments" id="myAppointment"><i class="fa fa-stethoscope"></i> <span class="nav-label">My Appointments</span><span class="label label-info pull-right"><?php echo $Total_Appointment_Count[0]['count']; ?></span></a>
                   
                </li>
				<li>
                    <a href="Cases-Sent" id="casesSent"><i class="fa fa-user"></i> <span class="nav-label">Cases Sent</span><span class="label label-info pull-right"><?php echo $_SESSION['tot_ref_count']; ?></span></a>
                   
                </li>
				<li>
                    <a href="My-Connections"><i class="fa fa-user-md"></i> <span class="nav-label">Connections</span><span class="label label-info pull-right"><?php echo $countConnection[0]['Count_Connections']; ?></span></a>
                   
                </li>
                
            </ul>

        </div>
    </nav>