<?php
ob_start();
session_start();
error_reporting(0);  
require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

$admin_id = $_SESSION['user_id'];
if(empty($admin_id)){
	header("Location:index.php");
}

include('connect.php');
include('functions.php');

$getOffersResult = $objQuery->mysqlSelect("*","offers_events","md5(event_id)='".$_GET['id']."'","","","","");
										

$countBlog = $objQuery->mysqlSelect("COUNT(a.listing_id) as Count","blogs_offers_events_listing as a inner join mapping_hosp_referrer as b on a.hosp_id=b.hosp_id","b.partner_id='".$admin_id."' and a.listing_type=Blog","","","","");
$countOffer = $objQuery->mysqlSelect("COUNT(a.listing_id) as Count","blogs_offers_events_listing as a inner join mapping_hosp_referrer as b on a.hosp_id=b.hosp_id","b.partner_id='".$admin_id."' and a.listing_type=Offers","","","","");
$countEvent = $objQuery->mysqlSelect("COUNT(a.listing_id) as Count","blogs_offers_events_listing as a inner join mapping_hosp_referrer as b on a.hosp_id=b.hosp_id","b.partner_id='".$admin_id."' and a.listing_type=Events","","","","");


//TO CHECK LOGIN USER TYPE WHETHER HE IS DOCTOR OR NORMAL USER
if($login_user_type=="doc"){
//$trimdocid  = str_replace("doc-", "", $postResult[$i]['Login_User_Id']);
$getloginuser = $objQuery->mysqlSelect("*","referal","ref_id='".$login_id."'","","","","");
$getloginSpec=$objQuery->mysqlSelect("*","specialization","spec_id='".$getloginuser[0]['doc_spec']."'","","","","");	
$login_username=$getloginuser[0]['ref_name'];
$login_userprof=$getloginSpec[0]['spec_name'];
						
if(!empty($getloginuser[0]['doc_photo'])){
$login_userimg="https://medisensecrm.com/Doc/".$login_id."/".$getloginuser[0]['doc_photo']; 
}
else{
	$login_userimg="images/anonymous-profile.png";
	}
} else if($login_user_type=="user"){
	$getloginuser = $objQuery->mysqlSelect("*","login_user","login_id='".$login_id."'","","","","");
	$username=$getloginuser[0]['sub_name'];
	$userprof=$getloginuser[0]['sub_proff'];
								
	if(!empty($getloginuser[0]['user_img'])){
	$login_userimg=$getloginuser[0]['user_img'];	
	}
	else{
		$login_userimg="images/anonymous-profile.png";	
		}
}


function hyphenize($string) {
    return 
    ## strtolower(
          preg_replace(
            array('#[\\s-]+#', '#[^A-Za-z0-9\. -]+#'),
            array('-', ''),
        ##     cleanString(
              urldecode($string)
        ##     )
        )
    ## )
    ;
}
$getFeature = $objQuery->mysqlSelect("*","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.doc_spec!=555 and a.anonymous_status!=1 and a.doc_type_val=1","rand()","","","");
$get_pro = $objQuery->mysqlSelect("a.ref_id as ref_id,a.ref_id as ref_id,a.doc_photo as doc_photo,a.ref_name as Doc_Name","referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id","a.ref_id='".$getOffersResult[0]['oganiser_doc_id']."'","","","","");

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Our Blogs</title>

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
	
	
	
	
    
    <!-- Themes -->
   
    <link rel="stylesheet" href="starrating/dist/themes/fontawesome-stars.css">
    <link rel="stylesheet" href="starrating/dist/themes/css-stars.css">
    <link rel="stylesheet" href="starrating/dist/themes/bootstrap-stars.css">
    <link rel="stylesheet" href="starrating/dist/themes/fontawesome-stars-o.css">

   
	
<script type="text/javascript">
$(document).ready(function(){
	var maxLength = 300;
	$(".show-read-more").each(function(){
		var myStr = $(this).text();
		if($.trim(myStr).length > maxLength){
			var newStr = myStr.substring(0, maxLength);
			var removedStr = myStr.substring(maxLength, $.trim(myStr).length);
			$(this).empty().html(newStr);
			$(this).append(' <a href="javascript:void(0);" class="read-more"><em>read more...</em></a>');
			$(this).append('<span class="more-text">' + removedStr + '</span>');
		}
	});
	$(".read-more").click(function(){
		$(this).siblings(".more-text").contents().unwrap();
		$(this).remove();
	});
});
</script>
<style type="text/css">
    .show-read-more .more-text{
        display: none;
    }
</style>
	
	</head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
       
		<?php include_once('side_menu.php');?>
		 
        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
		  <?php include_once('header_top_nav.php'); ?>
            <!--<div class="page-title">
              <div class="title_left">
                <h3>BLOGS</h3>
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
            </div>-->
            
            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  
                  <div class="x_content">

                    <div class="col-md-7 col-sm-7 col-xs-12">
                      <div class="product-image">
                        <?php if(!empty($getOffersResult[0]['photo'])){ ?><img src="../Hospital/Eventimages/<?php echo $getOffersResult[0]['event_id']; ?>/<?php echo $getOffersResult[0]['photo']; ?>" width="650" class="img-responsive"/> <?php } ?>
                      <p class="show-read-more"><?php echo $getOffersResult[0]['description']; ?></p>
                      <br />
					  </div>
                      <!--<div class="product_gallery">
					  <h4>Speakers</h4>
                        <a>
                          <img src="images/prod-2.jpg" alt="..." />
                        </a>
                        <a>
                          <img src="images/prod-3.jpg" alt="..." />
                        </a>
                        <a>
                          <img src="images/prod-4.jpg" alt="..." />
                        </a>
                        <a>
                          <img src="images/prod-5.jpg" alt="..." />
                        </a>
                      </div>-->
                    </div>

                    <div class="col-md-5 col-sm-5 col-xs-12" style="border:0px solid #e5e5e5;">

                      <h3 class="prod_title"><?php if(!empty($getOffersResult[0]['title'])){ echo $getOffersResult[0]['title']; } ?></h3>

                      
						<div class="">
                        <h2><i class="fa fa-user"></i> Organisers</h2>
                        <ul class="list-inline prod_color">
						<?php if(!empty($getOffersResult[0]['oganiser_doc_id'])){ 
						
							
						?>
						
                          <li>
                            <p><?php echo $get_pro[0]['Doc_Name']; ?></p>
                            <a>
							<img src="../Doc/<?php echo $get_pro[0]['ref_id']; ?>/<?php echo $get_pro[0]['doc_photo']; ?>" alt="..." width="50"/>
							</a>
                          </li>
						<?php } else {	?>
						  <li>
                            <p><?php echo $get_pro[0]['Doc_Name']; ?></p>
                            <a>
							<img src="images/anonymous-profile.png" alt="..."  />
							</a>
                          </li>
						<?php } ?>
                        </ul>
                      </div>
                      <br />
					  <div class="">
                        <h2><i class="fa fa-calendar"></i> Date</h2>
						<ul class="list-inline prod_color">
                          <li>
                            <p><?php echo $getOffersResult[0]['start_end_date']; ?></p>
                           
                          </li>
						 </ul>
					  </div>
					  <br />
                      <div class="">
                        <h2><i class="fa fa-microphone"></i> Keynote Speakers </h2>
						<?php $getKeyNoteSpeakers= $objQuery->mysqlSelect("*","referal as a inner join keynote_speakers as b on a.ref_id=b.doc_id inner join conference_login as c on c.conf_login_id=b.conf_id","b.conf_id='".$getOffersResult[0]['conf_id']."'","","","","");
?>						
                        <ul class="list-inline prod_color">
						<?php foreach($getKeyNoteSpeakers as $list){ 
						$speaker_name=$list['ref_name'];
						if(!empty($get_pro[0]['doc_photo'])){
							$docimg="../Doc/".$list['ref_id']."/".$list['doc_photo'];
						}else{
							$docimg="images/anonymous-profile.png";
						}
						?>
                          <li>
                            
                            <a>
							<img src="<?php echo $docimg; ?>" alt="..." width="50"/>
							</a>
							<p><?php echo $speaker_name; ?></p>
                          </li>
						<?php } ?>
						 
                        </ul>
                      </div>
                      <br />
					  
					<h2><i class="fa fa-comments-o"></i> Feedback </h2>
					<div class="product_social">
					<a href="#myModal" data-toggle="modal" ><button type="submit"  name="addType" id="addType" class="btn btn-success">LEAVE FEEDBACK HERE</button></a>
					</div>
					

                    </div>

						
						
					<div id="myModal" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-sm">
                      <div class="modal-content">

                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">??</span>
                          </button>
                          <h4 class="modal-title" id="myModalLabel"> Feedback</h4>
                        </div>
																<form method="post" name="frmType" action="add_details.php">
						
						
																	<div class="modal-body" style="padding-bottom:50px;">
																		<div class="col-md-12">
																		<label for="inputComment">Regn. No.</label>
																		<input type="text" id="reg_num" name="reg_num" required="required" class="form-control" placeholder="">
																		</div><br><br><br><br>
																		<div class="col-md-12">
																		<label for="inputComment">Keynote Speaker</label>
																		<select  name="speaker" required="required" class="form-control" >
																		<option value="">Select</option>
																		<?php foreach($getKeyNoteSpeakers as $list){ ?>
																			<option value="<?php echo $list['ref_id']; ?>"><?php echo $list['ref_name']; ?></option>
																		<?php } ?> 
																		</select>
																		</div><br><br><br><br>
																		<div class="col-md-12">
																		<label for="inputComment">Quality of the session</label>
																		<div class="stars stars-example-fontawesome">
																			<select id="example-fontawesome" name="rating" required="required" autocomplete="off">
																			  <option value="1">1</option>
																			  <option value="2">2</option>
																			  <option value="3">3</option>
																			  <option value="4">4</option>
																			  <option value="5">5</option>
																			</select>
																			
																		  </div>
																		</div><br><br><br><br>
																		<div class="col-md-12">
																		<label for="inputComment">Comment</label>
																			<textarea  name="comment"  class="form-control" rows="3" placeholder=""></textarea>
																		</div>
																		
                        
																			<div class="pull-right"  style="margin-top:10px;">	
																				
																			<button type="submit"  name="addFeedback" id="addType" class="btn btn-primary">Submit</button>
																			<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
																			</div>
																	</div>
																	</form>
																	
                       
						</div>
                        

                      </div>
                    </div>
						
						
                    
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
    <!-- ECharts -->
    <script src="../Hospital/vendors/echarts/dist/echarts.min.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="../Hospital/build/js/custom.min.js"></script>
 
    <script src="starrating/jquery.barrating.js"></script>
    <script src="starrating/js/examples.js"></script>
  </body>
</html>