<?php

$countFavourDoctor= $objQuery->mysqlSelect("COUNT(favourite_id) as Total_count","add_favourite_doctor","user_id='".$admin_id."'","","","","");		
	
$//Total_Count = $objQuery->mysqlSelect("COUNT(a.id) as count","appointment_transaction_detail as a inner join doctor_hosp as b on a.pref_doc=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id","a.user_type=1 and a.pref_doc='".$admin_id."'","","","","");
$TotalCount= $objQuery->mysqlSelect("COUNT(DISTINCT(a.patient_id)) as Result_Count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join source_list as e on e.source_id=a.patient_src","e.partner_id='".$admin_id."'","","","","");
$countMyPatient= $objQuery->mysqlSelect("COUNT(patient_id) as Total_count","my_patient","partner_id='".$admin_id."'","","","","");		
$Total_Appointment_Count = $objQuery->mysqlSelect("COUNT(a.appoint_id) as count","partner_appointment_transaction as a inner join our_partners as b on a.pref_doc=b.partner_id","a.pref_doc='".$admin_id."'","","","","");
$totalBlogs = $objQuery->mysqlSelect("COUNT(post_id) as Count_Blogs","home_posts","","post_id desc","","","");
$getPartner = $objQuery->mysqlSelect("*","our_partners","partner_id='".$admin_id."'","","","","");
	
?>
<link href="css/blink.css" rel="stylesheet">
<div class="col-md-3 left_col menu_fixed">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="min-height:50px;border: 0;">
              <center><a href="#"><img src="new_assests/img/logo.png" width="130" /></a></center>
            </div>

            <div class="clearfix"></div>

            <!-- menu profile quick info -->
            <div class="profile clearfix">
              <div class="profile_pic">
                
				<img src="<?php if(!empty($getPartner[0]['doc_photo'])){ echo "partnerProfilePic/".$getPartner[0]['partner_id']."/".$getPartner[0]['doc_photo']; } else { echo "../Hospital/images/anonymous-profile.png"; } ?>" alt="..." class="img-circle profile_img">
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
                  <li><a href="Blogs-Offers-Events-List" id="tutorFeed"><i class="fa fa-rss"></i>Feed </a></li>
				  
				   <?php if($getPartner[0]['Type']=="Doctor"){ ?><li><a href="My-Patient-List" ><i class="fa fa-stethoscope"></i>My Patients <!--<span class="fa fa-chevron-down"></span>--></a>
                    <!--<ul class="nav child_menu">
						<li><a href="My-Patient-List"><i class="fa fa-user"></i>Patients <span class="label label-success pull-right"><?php if($countMyPatient[0]['Total_count']!=0) { echo $countMyPatient[0]['Total_count']; } ?></span></a></li>
                        <li><a href="Appointments" ><i class="fa fa-calendar"></i>Appointments <span class="label label-success pull-right"><?php if($Total_Count[0]['count']!=0){ echo $Total_Count[0]['count']; }  ?></span></a></li>
					  </ul>-->
				   </li><?php } ?>
				   <li><a href="All-Patient-Records" ><i class="fa fa-user"></i>Cases Sent<span class="label label-success pull-right"><?php if($_SESSION['tot_ref_count']!=0) { echo $_SESSION['tot_ref_count']; } ?></span></a></li>
				   <li><a href="Doctors-List"><i class="fa fa-user-md"></i>Connections <span class="label label-success pull-right"><?php if($_SESSION['universal_doc']!=0) { echo $_SESSION['universal_doc']; } ?></span></a></li>
				<!-- <li><a><i class="fa fa-code-fork"></i>My Network <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
						<li><a href="All-Patient-Records"><i class="fa fa-user"></i>My requests <span class="label label-success pull-right"><?php if($_SESSION['tot_ref_count']!=0) { echo $_SESSION['tot_ref_count']; } ?></span></a></li>
						<li><a href="Doctors-List"><i class="fa fa-user-md"></i>Connections <span class="label label-success pull-right"><?php if($_SESSION['mycircle_doc']!=0) { echo $_SESSION['mycircle_doc']; } ?></span></a></li>
					</ul>
                  </li>-->
				    <!--<li><a href="Data-Analytics"><i class="fa fa-bar-chart-o"></i>Data Analytics </a>-->
                  
                  
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
        <div class="top_nav">
		
          <div class="nav_menu">
            <nav>
              <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
              </div>
				
              <ul class="nav navbar-nav navbar-right">
                <li class="">
                  <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <img src="<?php if(!empty($getPartner[0]['partner_logo'])){ echo "partner_img/".$getPartner[0]['partner_id']."/".$getPartner[0]['partner_logo']; } else { echo "../Hospital/images/anonymous-profile.png"; } ?>" alt=""><?php echo substr($_SESSION['company_name'],0,10);  ?> 
                    <span class=" fa fa-angle-down"></span>
                  </a>
                  <ul class="dropdown-menu dropdown-usermenu pull-right">
                    <li><a href="view-profile"> Profile</a></li>
					<!-- <li><a href="settings"> Settings</a></li>-->
                    
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
                    
                   <li>
                      <div class="text-center">
                        <a>
                          <strong>See All Alerts</strong>
                          <i class="fa fa-angle-right"></i>
                        </a>
                      </div>
                    </li>
                  </ul>
                </li>
				<li role="presentation" class="dropdown">
                  <?php if($countFavourDoctor[0]['Total_count']!=0) { ?>
				  <a href="Favourite-Doctors" class="dropdown-toggle info-number"  aria-expanded="false" data-toggle="tooltip" data-placement="bottom" title="Favourite Doctor">
                    <i class="fa fa-bookmark"></i>
                    
					<span class="badge bg-red"><?php echo $countFavourDoctor[0]['Total_count']; ?></span>
                  
				  </a>
                  <?php } else { ?>
				  <a href="#" class="dropdown-toggle info-number"  aria-expanded="false" data-toggle="tooltip" data-placement="bottom" title="Favourite Doctor">
                    <i class="fa fa-bookmark"></i>
                   
				  </a>
				  <?php } ?>
                </li>
				<li>
				<!--<h4 class="blink"><span class="label label-success pull-right"><i class="fa fa-star"></i>GET PREMIUM ACCOUNT<i class="fa fa-star"></i></span></h4>-->
				<a href="premium_login.php" ><img src="images/premium_tab.png" style="margin-top:10px;" /></a>
				</li>
				<li>
				<!--<h4 class="blink"><span class="label label-success pull-right"><i class="fa fa-star"></i>GET PREMIUM ACCOUNT<i class="fa fa-star"></i></span></h4>-->
				<a href="../standard/Home" >New Version</a>
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