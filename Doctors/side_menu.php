<?php
$getDoctorProfile= $objQuery->mysqlSelect("doc_photo as Doc_Photo,ref_id as Doc_Id","referal","ref_id='".$_SESSION['user_id']."'","","","","");
//$countHospitals= $objQuery->mysqlSelect("COUNT(hosp_id) as Total_count","hosp_tab","company_id='".$_SESSION['user_id']."'","","","","");
$countMyPatient= $objQuery->mysqlSelect("COUNT(patient_id) as Total_count","doc_my_patient","doc_id='".$_SESSION['user_id']."'","","","","");		
$Total_Appointment_Count = $objQuery->mysqlSelect("COUNT(a.id) as count","appointment_transaction_detail as a inner join doctor_hosp as b on a.pref_doc=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id","a.pref_doc='".$admin_id."'","","","","");
$totalBlogs = $objQuery->mysqlSelect("COUNT(post_id) as Count_Blogs","home_posts","","post_id desc","","","");
$countHospPartner= $objQuery->mysqlSelect("COUNT(DISTINCT(a.partner_id)) as Total_count","mapping_hosp_referrer as a inner join hosp_tab as b on b.hosp_id=a.hosp_id inner join doctor_hosp as c on c.hosp_id=b.hosp_id","c.doc_id='".$_SESSION['user_id']."' and a.doc_id='".$admin_id."'","","","","");
	
?>
<div class="col-md-3 left_col menu_fixed">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="#" class="site_title"><i class="fa fa-paw"></i> <span>Medisense Leap</span></a>
            </div>

            <div class="clearfix"></div>

            <!-- menu profile quick info -->
            <div class="profile clearfix">
              <div class="profile_pic">
			  <?php if(!empty($getDoctorProfile[0]['Doc_Photo'])){ ?>
				<img src="../Doc/<?php echo $getDoctorProfile[0]['Doc_Id']; ?>/<?php echo $getDoctorProfile[0]['Doc_Photo']; ?>" alt="..." class="img-circle profile_img">  
			<?php } else { ?>
				<img src="../Hospital/images/anonymous-profile.png" alt="..." class="img-circle profile_img">  
			 <?php } ?>
                
              </div>
              <div class="profile_info">
                <span>Welcome,</span>
                <h2><?php echo $_SESSION['company_name']; ?></h2>
              </div>
            </div>
            <!-- /menu profile quick info -->

            <br />

            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">
               
                <ul class="nav side-menu">
                  
				  <li><a href="Blogs-Offers-Events-List"><i class="fa fa-rss"></i> Feed <span class="label label-success pull-right"><?php echo $_SESSION['mycircle_doc']; ?></span></a></li>
				  <li><a href="My-Patient-List"><i class="fa fa-stethoscope"></i>My Patients <!--<span class="fa fa-chevron-down"></span>--></a>
                   <!-- <ul class="nav child_menu">
						<li><a href="My-Patient-List"><i class="fa fa-user"></i>Patients <span class="label label-success pull-right"><?php if($countMyPatient[0]['Total_count']!=0) { echo $countMyPatient[0]['Total_count']; } ?></span></a></li>
                        <li><a href="Appointments" ><i class="fa fa-calendar"></i>Appointments <span class="label label-success pull-right"><?php if($Total_Appointment_Count[0]['count']!=0){ echo $Total_Appointment_Count[0]['count']; }  ?></span></a></li>
					  </ul>-->
                  </li>
				  <li><a href="All-Records"><i class="fa fa-code-fork"></i>Cases received <!--<span class="fa fa-chevron-down"></span>--></a>
                    <!--<ul class="nav child_menu">
						<li><a href="All-Records"><i class="fa fa-home"></i> Referrals <span class="label label-success pull-right"><?php if($_SESSION['all_record']!=0) { echo $_SESSION['all_record']; } ?></span></a></li>
						 <li><a href="Add-Referring-Partner" onclick="ShowLoading()"><i class="fa fa-user"></i>Referring Partners <span class="label label-success pull-right"><?php if(!empty($countHospPartner[0]['Total_count'])){ echo $countHospPartner[0]['Total_count']; } ?></span></a></li>
					</ul>-->
                  </li>
				   <li><a href="Add-Referring-Partner"><i class="fa fa-user"></i>Care Partners<!--<span class="fa fa-chevron-down"></span>--></a>
                  
                  </li>
				  
				   <!--<li><a href="Appointments" ><i class="fa fa-calendar"></i> Appointments <span class="label label-success pull-right"><?php if($TotalApp_Count[0]['count']!=0) { echo $TotalApp_Count[0]['count']; } ?></span></a>
				    </li>
					<li><a href="My-Patient-List"><i class="fa fa-user"></i>Patients <span class="label label-success pull-right"><?php if($countMyPatient[0]['Total_count']!=0) { echo $countMyPatient[0]['Total_count']; } ?></span></a></li>
                 -->
				 
				 
				<li><a href="Offers-Events" onclick="ShowLoading()"><i class="fa fa-volume-up"></i>CME <!--<span class="fa fa-chevron-down"></span>--></a>
					<!--<ul class="nav child_menu">
                      <li><a href="Add-Blog" onclick="ShowLoading()"><i class="fa fa-rss"></i>Blogs </a></li>
                      <li><a href="Offers-Events" onclick="ShowLoading()"><i class="fa fa-calendar-o"></i>Offers & Events </a></li>
					</ul>-->
				  
				</li>
				<li><a href="#" ><i class="fa fa-bar-chart-o"></i> Data Analytics </a></li>
                </ul>
				
              </div>
              

            </div>
            <!-- /sidebar menu -->

            <!-- /menu footer buttons -->
            <div class="sidebar-footer hidden-small">
              
              <a data-toggle="tooltip" data-placement="top" title="Logout" href="logout.php">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
              </a>
            </div>
            <!-- /menu footer buttons -->
          </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav " >
          <div class="nav_menu">
            <nav>
              <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
              </div>

              <ul class="nav navbar-nav navbar-right">
                <li class="">
                  <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
				  
				   <?php if(!empty($getDoctorProfile[0]['Doc_Photo'])){ ?>
				<img src="../Doc/<?php echo $getDoctorProfile[0]['Doc_Id']; ?>/<?php echo $getDoctorProfile[0]['Doc_Photo']; ?>" alt="profile picture">  
			<?php } else { ?>
				<img src="images/anonymous-profile.png" alt="profile picture">  
			 <?php } ?>
                    <?php echo substr($_SESSION['user_name'],0,10);  ?> 
                    <span class=" fa fa-angle-down"></span>
                  </a>
                  <ul class="dropdown-menu dropdown-usermenu pull-right">
                    <li><a href="Doctor-Profile"> Profile</a></li>
                    
                    <li><a href="logout.php"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
                  </ul>
                </li>

                <li role="presentation" class="dropdown">
                  <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-envelope-o"></i>
                    <!--<span class="badge bg-green">6</span>-->
                  </a>
                  <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
                    <li>
                      <a>
                       <!-- <span class="image"><img src="images/anonymous-profile.png" alt="Profile Image" /></span>
                        <span>
                          <span><?php echo substr($_SESSION['user_name'],0,10);  ?></span>
                          <span class="time">3 mins ago</span>
                        </span>-->
                        <span class="message">
                          No notification
                        </span>
                      </a>
                    </li>
                    
                   <!-- <li>
                      <div class="text-center">
                        <a>
                          <strong>See All Alerts</strong>
                          <i class="fa fa-angle-right"></i>
                        </a>
                      </div>
                    </li>-->
                  </ul>
                </li>
              </ul>
            </nav>
          </div>
        </div>
        <!-- /top navigation -->
		
		<script type="text/javascript">
    function ShowLoading(e) {
        var div = document.createElement('div');
        var img = document.createElement('img');
        img.src = 'loading.gif';
        div.innerHTML = "";
        div.style.cssText = 'position: fixed; top: 45%; left: 40%; z-index: 5000; width: 40px; height: 40px; text-align: center; background: transparent;';
        div.appendChild(img);
        document.body.appendChild(div);
        return true;
        // These 2 lines cancel form submission, so only use if needed.
        //window.event.cancelBubble = true;
        //e.stopPropagation();
    }
	</script>