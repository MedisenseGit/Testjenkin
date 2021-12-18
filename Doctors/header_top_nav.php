<?php
ob_start();
error_reporting(0); 
session_start();

$admin_id = $_SESSION['user_id'];

require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

$request  = str_replace("", "", $_SERVER['REQUEST_URI']);
#split the path by '/'
$params     = split("/", $request);
$pagename = $params[2];

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
		$pendingCount = $objQuery->mysqlSelect('COUNT(DISTINCT(a.patient_id)) as Count_Records','patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id',"(b.ref_id='".$_SESSION['user_id']."') and (b.status2=2 or b.status2=3)");
		$totReferredCount = $objQuery->mysqlSelect('COUNT(DISTINCT(a.patient_id)) as Count_Records','patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join source_list as c on c.source_id=a.patient_src',"(c.partner_id='".$result[0]['partner_id']."') and (b.status2=2 or b.status2>=5)");
		$totRespondedCount = $objQuery->mysqlSelect('COUNT(DISTINCT(a.patient_id)) as Count_Records','patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join source_list as c on c.source_id=a.patient_src',"(c.partner_id='".$result[0]['partner_id']."') and (b.status2>=5)");
		$totTreatedCount = $objQuery->mysqlSelect('COUNT(DISTINCT(a.patient_id)) as Count_Records','patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join source_list as c on c.source_id=a.patient_src',"(c.partner_id='".$result[0]['partner_id']."') and (b.status2=6 or b.status2=7 or b.status2=8 or b.status2=9 or b.status2=11 or b.status2=12 or b.status2=13)");
		$totalBlogs = $objQuery->mysqlSelect("COUNT(post_id) as Count_Blogs","home_posts","","post_id desc","","","");
	
	
?>	

	<!--<div class="row top_tiles">
              <a href="Pending-Records"><div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                  <div class="icon"><i class="fa fa-exclamation-triangle"></i></div>
                  <div class="count"><?php echo $countPending[0]['Total_count']; ?></div>
                  <h3>Pending</h3>
                  <p></p>
                </div>
              </div></a>
              <a href="Responded-Records"><div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                  <div class="icon"><i class="fa fa-comments-o"></i></div>
                  <div class="count"><?php echo $countResponded[0]['Total_count']; ?></div>
                  <h3>Responded</h3>
				  <span class="count_bottom"><i class="red"><i class="fa fa-sort-desc"></i><?php echo $countAutoResponse[0]['Total_count']; ?></i> Auto responded</span>
                  
                </div>
              </div></a>
              <a href="Converted-Records"><div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                  <div class="icon"><i class="fa fa-check-square-o"></i></div>
                  <div class="count"><?php echo $countConverted[0]['Total_count']; ?></div>
                  <h3>Converted</h3>
                  <p></p>
                </div>
              </div></a>
              <a href="All-Records"><div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                  <div class="icon"><i class="fa fa-check-square-o"></i></div>
                  <div class="count"><?php echo $Total_Rslt_Count[0]['Total_count']; ?></div>
                  <h3>All Records</h3>
                  <p></p>
                </div>
              </div></a>
            </div>-->
			
			<!-- top tiles -->
          <div class="row tile_count">
		  
		  <div class="col-md-2 col-sm-2 col-xs-6 tile_stats_count">
             <a href="My-Patient-List"> <span class="count_top"><i class="fa fa-stethoscope"></i> <b>MY PRACTICE</b></span><br>
			 <span class="count_top"><i class="fa fa-user"></i> Patients</span>
              <div class="count"><?php echo $countMyPatient[0]['Total_count'];; ?></div></a>
			  <a href="Appointments"><span class="count_bottom"><i class="red"><?php echo $Total_Appointment_Count[0]['count']; ?> </i> Appointments </a>
              <!--<span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i> From last Week</span>-->
            </a>
			</div>
			<div class="col-md-2 col-sm-2 col-xs-6 tile_stats_count">
             <a href="All-Records"><span class="count_top red"><i class="fa fa-code-fork"></i> <b>CONNECTIONS</b></span>
              <br><span class="count_top"><i class="fa fa-user"></i> Cases received</span>
			  <div class="count"><?php echo $_SESSION['all_record']; ?></div></a>
              <a href="Responded-Records"><span class="count_bottom"><i class="green"><?php echo $_SESSION['tot_resp_count']; ?> </i> Responded  &</a><br><a href="Not-Responded"> <i class="red"><?php echo $_SESSION['not_resp_count']; ?></i> not responded</span></a>
            
			</div>
			
			<div class="col-md-2 col-sm-2 col-xs-6 tile_stats_count">
             <a href="Converted-Records"> <span class="count_top"><b> VISIT FROM NETWORK</b></span>
              <div class="count green"><?php echo $_SESSION['tot_converted_count']; ?></div>
              <span class="count_bottom"><i class="green"><?php echo $_SESSION['conversion_rate']; ?>% </i> Visit rate</span>
            </div>
			<div class="col-md-2 col-sm-2 col-xs-6 tile_stats_count">
              <span class="count_top"><b> RESPONSE RATE</b></span>
              <div class="count"><?php echo $_SESSION['response_rate']; ?>%</div>
             
           
			</div>
			<div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-clock-o"></i> AVG. RESPONSE TIME</span><br><br>
              <div class="green count_bottom"><b><?php echo con_min_days($_SESSION['response_time']); ?></b></div>
              
            </div>
                     
            
           <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
             <a href="Blogs-List"> <span class="count_top"><b>BLOGS, EVENTS, OFFERS</b></span>
              <div class="count"><b><?php echo $totalBlogs[0]['Count_Blogs']; ?></b></div></a>
              
            </div>
		  
		  
            
			
          </div>
          <!-- /top tiles -->
		  
			<!--<div class="right">
                <div class="form-group top_search">
                  <form method="post" action="add_details.php" name="frmSrchBox">
                  <div class="col-md-3 col-sm-3 col-xs-12 input-group">
                    <input type="text" name="postTextSrch" class="form-control" placeholder="Search for...">
                    <span class="input-group-btn">
                      <button type="submit" name="postTextSrchCmd" class="btn btn-default" >Go!</button>
                    </span>
                  </div>
                </form>
				
                </div>
				
					
		
              </div>-->
			 
			  <?php if($pagename!="My-Patient-List"){ ?>
			 <div class="form-group pull-right">
				<div class="input-group">
                    <a href="Add-Referring-Partner" class="btn btn-primary"><i class="fa fa-user"></i> ADD CARE PARTNERS </a>                     
                    </span>
                  </div>
				</div>
			  <?php } ?>