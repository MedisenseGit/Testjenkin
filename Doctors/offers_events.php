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

//Delete perticular event functionality
if(isset($_POST['cmdDelStatus'])){
	$event_id = $_POST['event_id'];
	$arrFields = array();
	$arrValues = array();
	
	$objQuery->mysqlDelete('offers_events',"event_id='".$event_id."'");	
	$objQuery->mysqlDelete('blogs_offers_events_listing',"(listing_type_id='".$event_id."')");
	$response="delete";
	header("Location:Offers-Events?response=".$response);
}

$busResult = $objQuery->mysqlSelect("*","offers_events","oganiser_doc_id='".$admin_id."'","event_id desc","","","");

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Events & Offers</title>
	<link rel="shortcut icon" href="../attachments/new_assets/img/favicon.ico">
    <!-- Bootstrap -->
    <link href="../Hospital/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../Hospital/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../Hospital/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="../Hospital/vendors/iCheck/skins/flat/green.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="../Hospital/build/css/custom.min.css" rel="stylesheet">
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
	  
        <?php include_once('side_menu.php'); ?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Offers & Events </h3>
              </div>

              <div class="title_right">
                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                  <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search for...">
                    <span class="input-group-btn">
                      <button class="btn btn-default" type="button">Go!</button>
                    </span>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Offers & Events</h2>
                    <div class="right">
                <div class="form-group pull-right top_search">
                  <div class="input-group">
                    <a href="Add-Offers" class="btn btn-primary btn-xs"><i class="fa fa-plus"></i> ADD NEW </a>
                     
                    </span>
                  </div>
                </div>
              </div>
                    <div class="clearfix"></div>
					
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
						<?php } else if($_GET['response']=="delete"){ ?>
						<div class="alert alert-success alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                    </button>
                    <strong>DELETED !!</strong> row deleted successfully.
                  </div>
						<?php } ?>
					
                  </div>
				  <script language="JavaScript" src="js/status_validation.js"></script>
				  <form method="post" name="frmOffer" >
				  <input type="hidden" name="cmdDelStatus" value="" />
				  <input type="hidden" name="event_id" value="" />
                  <div class="x_content">

                   <!-- <p>Simple table with project listing with progress and editing options</p>-->

                    <!-- start project list -->
                    <table class="table table-striped projects">
                      <thead>
					  
                        <tr>
                          <th style="width: 1%">Type</th>
                          <th style="width: 15%">Offers/Event Name</th>
                          <th>Organiser</th>
                          <th>Created Date</th>
                          <th>Status</th>
						  <th>Share</th>
                          <th style="width: 20%">#Edit</th>
                        </tr>
							
                      </thead>
                      <tbody>
					  <?php if(empty($busResult)) { ?><tr>
                                            <td colspan="8"><center>No result found</center></td>
                                        </tr> <?php }
							if(!empty($busResult)){	?>
							<?php foreach($busResult as $list){ 
							$getDoc = $objQuery->mysqlSelect("*","referal","ref_id='".$list['oganiser_doc_id']."'","","","","");
							$getPerson = $objQuery->mysqlSelect("*","hosp_marketing_person","person_id='".$list['organiser_market_id']."'","","","","");
							
							$countGo = $objQuery->mysqlSelect("COUNT(event_id) as Count","event_visitors_tab","event_id='".$list['event_id']."' and going!=0","","","","");
							$countMaybe = $objQuery->mysqlSelect("COUNT(event_id) as Count","event_visitors_tab","event_id='".$list['event_id']."' and maybe!=0","","","","");
							$countCannot = $objQuery->mysqlSelect("COUNT(event_id) as Count","event_visitors_tab","event_id='".$list['event_id']."' and cannotgo!=0","","","","");
							
							//Get Event Type
							if($list['event_type']==1){
							$eventType="<span class='label label-success'>EVENT</span>";	
							}
							else{
								$eventType="<span class='label label-danger'>OFFER</span>";
							}
							
							?>
                        <tr>
                          <td><?php echo $eventType; ?></td>
                          <td>
                            <a><b><?php echo $list['title']; ?></b><br><?php echo substr($list['description'],0,100)."..."; ?></a>
                          </td>
                          <td>
                            <ul class="list-inline">
                             <?php if(!empty($getDoc)){ ?><li>
                                <img src="images/user.png" class="avatar" alt="Avatar">&nbsp;&nbsp;
								<span><?php echo $getDoc[0]['ref_name']; ?></span>
                              </li><br>
							 <?php } ?>
							 <?php if(!empty($getPerson)){ ?><li>
                                <img src="images/user.png" class="avatar" alt="Avatar">&nbsp;&nbsp;
								<span><?php echo $getPerson[0]['person_name']; ?></span>
                              </li>
							 <?php } ?>
                             
                            </ul>
                          </td>
                          <!--<td class="project_progress">
                            <div class="progress progress_sm">
                              <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="57"></div>
                            </div>
                            <small>57% Complete</small>
                          </td>-->
						   <td>
                           <span><?php echo date('M d,Y',strtotime($list['created_date']))?></span>
                          </td>
						
                           <td>
                            <i class="fa fa-check"></i> <?php echo $countGo[0]['Count']; ?>
							&nbsp;<i class="fa fa-question"></i> <?php echo $countMaybe[0]['Count']; ?>
							&nbsp;<i class="fa fa-times"></i> <?php echo $countCannot[0]['Count']; ?>
                          </td>
                          <td>
						  <ul class="nav navbar-left panel_toolbox">
                            <li >
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-share-alt"></i></a>
								<ul class="dropdown-menu" role="menu">
								  <li><a href="#"><i class="fa fa-facebook-square"></i> Facebook</a>
								  </li>
								  <li><a href="#"><i class="fa fa-twitter-square"></i> Twitter</a>
								  </li>
								  <li><a href="#"><i class="fa fa-google-plus-square"></i> Google +</a>
								  </li>
								  <li><a href="#"><i class="fa fa-user"></i> Class A</a>
								  </li>
								  <li><a href="#"><i class="fa fa-user"></i> Class B</a>
								  </li>
								  <li><a href="#"><i class="fa fa-user"></i> Class C</a>
								  </li>
								</ul>
							  </li>
							  </ul>
                             </td>
							 <td>
							 <a href="Add-Offers?eventid=<?php echo $list['event_key']; ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit </a>
                            <a href='#' onclick="return delOfferEvent(<?php echo $list['event_id'];?>)" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete </a>
							</td>
                        </tr>
                        <tr>
						<?php } 
							} ?>
                      </tbody>
                    </table>
                    <!-- end project list -->

                  </div>
				  
				  </form>
                </div>
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
    <!-- bootstrap-progressbar -->
    <script src="../Hospital/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
    
    <!-- Custom Theme Scripts -->
    <script src="../Hospital/build/js/custom.min.js"></script>
  </body>
</html>