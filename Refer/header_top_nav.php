<?php
ob_start();
error_reporting(0); 
session_start();

$admin_id = $_SESSION['user_id'];

require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//Convert Minutes to Days HH:MM Format functionality
 function con_min_days($mins)
    {

            $hours = str_pad(floor($mins /60),2,"0",STR_PAD_LEFT);
            $mins  = str_pad($mins %60,2,"0",STR_PAD_LEFT);

            if((int)$hours > 24){
            $days = str_pad(floor($hours /24),2,"0",STR_PAD_LEFT);
            $hours = str_pad($hours %24,2,"0",STR_PAD_LEFT);
            }
            if(isset($days)) { $days = $days." Day[s] ";}

            return $days.$hours." Hour[s] ".$mins." Min[s]";
    }
$Total_Rslt_Count = $objQuery->mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join source_list as e on e.source_id=a.patient_src","e.partner_id='".$admin_id."'","","","","");
$Total_Doctor = $objQuery->mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join source_list as e on e.source_id=a.patient_src","e.partner_id='".$admin_id."'","","","","");
$countFavourDoctor= $objQuery->mysqlSelect("COUNT(favour_id) as Total_count","add_favourite_doctor","user_id='".$admin_id."'","","","","");		

?>	

			
			<!-- top tiles -->
          <div class="row tile_count">
		  <div class="col-md-2 col-sm-2 col-xs-6 tile_stats_count" id="tutorAppointment">
             <a href="Appointments"> <span class="count_top"><i class="fa fa-calendar"></i> <b>Appointments</b></span><br>
			 
              <div class="count"><?php echo $Total_Appointment_Count[0]['count']; ?></div></a>
			 
			</div>
			<div class="col-md-2 col-sm-2 col-xs-6 tile_stats_count" id="tutorPatient" >
             <a href="My-Patient-List"> <span class="count_top"><i class="fa fa-stethoscope"></i> <b>MY PATIENTS</b></span>
              <div class="count green"><?php echo $countMyPatient[0]['Total_count'];; ?></div>
             </a>
			</div>
			<div class="col-md-2 col-sm-2 col-xs-6 tile_stats_count" id="tutorConnections">
             <a href="Doctors-List"> <span class="count_top"><i class="fa fa-user-md"></i> <b>CONNECTIONS</b></span>
              <div class="count red"><?php echo $_SESSION['universal_doc']; ?></div>
             </a>
			</div>
			<div class="col-md-2 col-sm-2 col-xs-6 tile_stats_count" id="tutorRequest">
             <a href="All-Patient-Records"> <span class="count_top"><i class="fa fa-user"></i> <b>CASES SENT</b></span>
              <div class="count green"><?php echo $_SESSION['tot_ref_count']; ?></div>
             </a>
			</div>
			<!--
            <div class="col-md-2 col-sm-2 col-xs-6 tile_stats_count">
             <a href="My-Patient-List"> <span class="count_top"><i class="fa fa-stethoscope"></i> <b>MY PRACTICE</b></span><br>
			 <span class="count_top"><i class="fa fa-user"></i> Patients</span>
              <div class="count"><?php echo $countMyPatient[0]['Total_count'];; ?></div></a>
			  <a href="Appointments"><span class="count_bottom"><i class="red"><?php echo $Total_Appointment_Count[0]['count']; ?> </i> Appointments </span></a>
              </a>
			</div>
			<div class="col-md-2 col-sm-2 col-xs-6 tile_stats_count">
             <a href="Doctors-List"> <span class="count_top"><i class="fa fa-user-md"></i> <b>CONNECTIONS</b></span>
              <div class="count red"><?php echo $_SESSION['universal_doc']; ?></div>
             <a href="Doctors-List"><span class="count_bottom"><i class="red"><?php echo $_SESSION['mycircle_doc']; ?> </i> My Connections</span></a>
            </a>
			</div>
			<div class="col-md-2 col-sm-2 col-xs-6 tile_stats_count">
             <a href="Referred-Patient-Records"><span class="count_top"><i class="fa fa-code-fork"></i> <b>MY NETWORK</b></span>
               <br><span class="count_top"> Cases Sent</span>
              <div class="count"><?php echo $_SESSION['tot_ref_count']; ?></div></a>
			  <a href="Responded-Patient-Records"><i class="green"><?php echo $_SESSION['tot_resp_count']; ?></i> Responded  &</a><br>
              <a href="Pending-Records"><i class="red"><?php echo $_SESSION['tot_pending_count']; ?> Pending</i> </a>
			  
            
			</div>
			<div class="col-md-2 col-sm-2 col-xs-6 tile_stats_count">
              <a href="Treated-Patient-Records"><span class="count_top"><i class="fa fa-comments-o"></i> <b>NETWORK VISITS</b></span>
              <div class="count green"><?php echo $_SESSION['tot_treated_count']; ?></div>
             
            </a>
			</div>
			            
           <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
             <a href="Blogs-Offers-Events-List?s=Events"><span class="count_top"><i class="fa fa-rss"></i> <b>Offers/Events</b></span>
              <div class="count"><b><?php echo $_SESSION['tot_Blogs']; ?></b></div></a>
            </div>-->
          </div>
          <!-- /top tiles -->
			
			