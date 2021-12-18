<?php
$admin_id = $_SESSION['user_id'];
	$Total_Recieved_Count = mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id","","","","","");
	$countPartners = mysqlSelect("COUNT(partner_id) as Count_Partners","our_partners","","","","","");
	$getCompanyProfile= mysqlSelect("*","hosp_tab","hosp_id='".$admin_id."'","","","","");

	$getLastMypatient= mysqlSelect("e.patient_id","doc_my_patient as e inner join referal as a on a.ref_id=e.doc_id inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id","c.hosp_id='".$_SESSION['user_id']."'","e.patient_id desc","","","");
				if(COUNT($getLastMypatient)>0){
					
						$patientid=md5($getLastMypatient[0]['patient_id']);
					
						$checkTodayVisit= mysqlSelect("episode_id","doc_patient_episodes","admin_id='".$admin_id."' and patient_id='".$getLastMypatient[0]['patient_id']."' and DATE_FORMAT(date_time,'%Y-%m-%d')='".date('Y-m-d')."'","episode_id desc","","","");
						if(COUNT($checkTodayVisit)>0){
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
	//$sidenavigateLink = HOST_URL_PREMIUM."My-Patient-Details";
	$_SESSION['EMR_URL'] = "My-Patient-Details";
?>
<nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav metismenu" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown profile-element"> <span>
                            <img alt="image" class="img" src="../assets/img/Practice_premium.png" />
                             </span>
							 <span></span>
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="clear"> <span class="block m-t-xs"><img alt="image" class="img-circle" src="hospital-icon.png" width="36" /> <strong class="font-bold"><?php echo $getCompanyProfile[0]['hosp_name']; ?></strong>
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
                    <a href="Appointments" id="myAppointment"><i class="fa fa-stethoscope"></i> <span class="nav-label">Appointments</span><span class="label label-info pull-right"><?php echo $Total_Appointment_Count[0]['count']; ?></span></a>
                   
                </li>
                <!--<li>
                    <a href="Corporate-Appointment" id="myCorporateAppointment"><i class="fa fa-stethoscope"></i> <span class="nav-label">Corporate Appointments</span><span class="label label-info pull-right"><?php echo $Total_Appointment_Count[0]['count']; ?></span></a>
                   
                </li>-->
				<li>
                    <a href="My-Patient-Details?p=<?php echo $patientid.$getEpisodeId;?>" ><i class="fa-address-card"></i> <span class="nav-label">EMR</span></a>
                   
                </li>
				<li>
                    <a href="Cases-Recieved" id="casesReceive"><i class="fa fa-user"></i> <span class="nav-label">Cases received</span><span class="label label-info pull-right"><?php echo $_SESSION['tot_result_count']; ?></span></a>
                   
                </li>
				 <li id="manageHosp">
                    <a href="Add-Hospital"><i class="fa fa-hospital-o" ></i> <span class="nav-label">Manage Hospital</span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">
                       <li><a href="Add-Hospital-Doctors" ><i class="fa fa-user-md"></i>Doctor <span class="label label-success pull-right"><?php echo $countHospDoctor[0]['Total_count']; ?></span></a></li>
					  <li><a href="Add-Marketing-Persons"><i class="fa fa-user"></i>Marketing Professional</a></li>
					 
                       
                    </ul>
                </li>
				 <li id="prCloud">
                    <a href="Blog-List"><i class="fa fa-volume-up" ></i> <span class="nav-label">PR Cloud</span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">
						<li><a href="Blog-List" onclick="ShowLoading()"><i class="fa fa-rss"></i>Blogs </a></li>
						<li><a href="Offers-Events-List" onclick="ShowLoading()"><i class="fa fa-calendar-o"></i>Offers & Events </a></li>
						<li><a href="Job-Post" onclick="ShowLoading()"><i class="fa fa-graduation-cap"></i>Jobs</a></li>
						<li><a href="Surgical-Video" onclick="ShowLoading()"><i class="fa fa-film"></i>Surgical Video</a></li>
					
                    </ul>
                </li>
				<li>
                    <a href="Data-Analytics"><i class="fa fa-bar-chart"></i> <span class="nav-label">Analytics</span></a>
                   
                </li>
                
            </ul>

        </div>
    </nav>