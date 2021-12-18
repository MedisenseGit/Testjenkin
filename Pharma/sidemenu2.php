<?php
$admin_id = $_SESSION['user_id'];
	//$Total_Recieved_Count = mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id","b.ref_id='".$admin_id."'","","","","");
	$Total_Recieved_Count = mysqlSelect("COUNT(a.diagnostic_customer_id) as Total_count","diagnostic_referrals as a left join diagnostic_customer as b on a.diagnostic_customer_id=b.diagnostic_customer_id","a.diagnostic_id='".$admin_id."'","a.diagnostic_customer_id desc","a.diagnostic_customer_id","","");

	$countPartners = mysqlSelect("COUNT(partner_id) as Count_Partners","our_partners","","","","","");
	$countMyPatient= mysqlSelect("COUNT(patient_id) as Total_count","doc_my_patient","doc_id='".$_SESSION['user_id']."'","","","","");		
	$Total_Appointment_Count = mysqlSelect("COUNT(id) as count","appointment_transaction_detail","pref_doc='".$admin_id."'","","","","");
	$getDoctorProfile= mysqlSelect("doc_photo as Doc_Photo,ref_id as Doc_Id,ref_name as Doc_name,ref_mail as doc_mail,contact_num as Doc_contact,ref_address as Doc_city,doc_state as Doc_State,doc_country as Doc_Country","referal","ref_id='".$admin_id."'","","","","");
	$chkProfileTime = mysqlSelect("*","doc_time_set","doc_id=".$admin_id."","","","","");
										
?>
<nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav metismenu" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown profile-element"> <span>
                            <a href="Home"><img alt="image" class="img" src="../assets/img/PRACTICE_ logo_diagno.png"  width="200"/></a>
                             </span>
							 <span></span>
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                           <!-- <span class="clear"> <span class="block m-t-xs"><img alt="image" class="img-circle" src="<?php if(!empty($getDoctorProfile[0]['Doc_Photo'])){ echo "../Doc/".$getDoctorProfile[0]['Doc_Id']."/".$getDoctorProfile[0]['Doc_Photo']; } else { echo "../assets/img/anonymous-profile.png"; } ?>" width="36" /> <strong class="font-bold"><?php echo $_SESSION['user_name']; ?></strong>-->
						   <span class="clear"> <span class="block m-t-xs"><img alt="image" class="img-circle" src="<?php echo "../assets/img/anonymous-profile.png";  ?>" width="36" /> <strong class="font-bold"><?php echo $_SESSION['user_name']; ?></strong>
                            
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
               
               <!--<li>
                    <a href="#"><i class="fa fa-home"></i> <span class="nav-label">Reports</span><span class="fa arrow"></span></a>
                    
                </li>-->
                <li>
                    <a href="Appointments"><i class="fa fa-hospital-o"></i> <span class="nav-label">Appointments</span><!--<span class="label label-info pull-right"><?php echo count($Total_Recieved_Count); ?></span>--></a>
                    
                </li>
              
				<li>
                    <a href="Payments"><i class="fa fa-credit-card"></i> <span class="nav-label">Payments</span></a>
                   
                </li>
			
                
            </ul>

        </div>
    </nav>