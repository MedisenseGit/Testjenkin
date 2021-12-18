<?php
ob_start();
session_start();
error_reporting(0);  
require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

$request  = str_replace("/share-post/", "", $_SERVER['REQUEST_URI']);
#split the path by '/'
$params     = split("/", $request);
$postid = $params[2];

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



//$postid  = "1502260337";
$getOffersResult = $objQuery->mysqlSelect("*","offers_events","event_trans_id='".$postid."'","","","","");
$getAuthor = $objQuery->mysqlSelect("company_name,company_addrs","compny_tab","company_id='".$getOffersResult[0]['company_id']."'","","","","");
//Update number of views
$num_views=$getOffersResult[0]['num_views'];
$num_views=$num_views+1;
$arrFields = array();
$arrValues = array();
$arrFields[]= 'num_views';
$arrValues[]= $num_views;
$updateviews=$objQuery->mysqlUpdate('offers_events',$arrFields,$arrValues,"event_id='".$getOffersResult[0]['event_id']."'");

if($getOffersResult[0]['event_type']=="1"){
	$eventType="EVENT";
	$eventButton="<li><a href='../../login' ><button type='submit'  name='addType' id='addType' class='btn btn-success'><i class='fa fa-user-plus'></i> REGISTER HERE</button></a></li>";
							
}
else if($getOffersResult[0]['event_type']=="2"){
	$eventType="OFFERS";
	$eventButton="<li><a href='../../login' ><button type='submit'  name='addType' id='addType' class='btn btn-success'><i class='fa fa-graduation-cap'></i> APPLY JOB HERE</button></a></li>";

}
else{
	$eventType="JOBS";
	$eventButton="<li><a href='../../login' ><button type='submit'  name='addType' id='addType' class='btn btn-success'><i class='fa fa-graduation-cap'></i> APPLY JOB HERE</button></a></li>";

}
$get_pro = $objQuery->mysqlSelect("a.ref_id as ref_id,a.ref_id as ref_id,a.doc_photo as doc_photo,a.ref_name as Doc_Name","referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id","a.ref_id='".$getOffersResult[0]['oganiser_doc_id']."'","","","","");
	
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">
<link rel="icon" href="https://medisensecrm.com/Refer/new_assests/img/favicon.ico">

<meta property="og:image" content="https://medisensecrm.com/Hospital/Eventimages/<?php echo $getOffersResult[0]['event_id']; ?>/<?php echo $getOffersResult[0]['photo']; ?>" />
<meta property="og:title" content="<?php echo $getOffersResult[0]['title']; ?>">
<meta property="og:site_name" content="Practice Medisense">
<meta property="og:url" content="www.medisensecrm.com">
<meta property="og:description" content="<?php echo $getOffersResult[0]['description']; ?>">
<meta property="fb:app_id" content="">
<meta property="og:type" content="article">


<title>Practice Medisense</title>
<!-- Bootstrap core CSS -->
<link href="https://medisensecrm.com/Refer/new_assests/css/bootstrap.min.css" rel="stylesheet">

 <!-- Font Awesome -->
    <link href="../../../Hospital/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<!-- Jasny CSS -->
<link href="https://medisensecrm.com/Refer/new_assests/css/jasny-bootstrap.min.css" rel="stylesheet">
<!-- Animate CSS -->
<link href="https://medisensecrm.com/Refer/new_assests/css/animate.css" rel="stylesheet">
<!-- Code CSS -->
<link href="https://medisensecrm.com/Refer/new_assests/css/tomorrow-night.css" rel="stylesheet" />
<!-- Gallery CSS -->
<link href="https://medisensecrm.com/Refer/new_assests/css/bootstrap-gallery.css" rel="stylesheet">
<!-- ColorBox CSS -->
<link href="https://medisensecrm.com/Refer/new_assests/css/colorbox.css" rel="stylesheet">
<!-- Custom font -->
<link href='https://fonts.googleapis.com/css?family=Raleway:400,200,100,300,500,600,700,800,900' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Roboto+Slab&amp;subset=latin,latin-ext' rel='stylesheet' type='text/css'>
<!-- Custom styles for this template -->
<link href="https://medisensecrm.com/Refer/new_assests/css/style.css" rel="stylesheet">
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.6&appId=191717377898171&quote=medisense-community";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
</head>
<body>
	<div class="page-loader">
		<div class="loader-in">Loading...</div>
		<div class="loader-out">Loading...</div>
	</div>

	<!--<aside class="navmenu">
		<div class="post-titles">
			<div class="tag-title">
				<div class="container">
					<p class="tags" id="post-titles">
						<a data-filter=".pt-fashion" href="#">fashion</a>
						<a data-filter=".pt-culture" href="#">culture</a>
						<a data-filter=".pt-art" href="#">art</a>
						<a data-filter="*" href="#" class="unfilter hide">all</a>
					</p>
				</div>
			</div>
			<button type="button" class="remove-navbar"><i class="fa fa-times"></i></button>
			<ul class="post-title-list clearfix">
				<li class="pt-fashion pt-culture">
					<div>
						<h5>
							<i class="fa fa-file-text-o"></i>
							<a href="#">Five simple steps to designing grid systems preface</a>
						</h5>
						<div class="post-subinfo">
							<span>June 28</span>   •   
							<span>2 Comments</span>
						</div>
					</div>
				</li>
				<li class="pt-culture pt-art">
					<div>
						<h5>
							<i>26</i>
							<a href="#">Hemingway: A Text Editor That Cares About What You Write</a>
						</h5>
						<div class="post-subinfo">
							<span>June 26</span>   •   
							<span>2 Comments</span>
						</div>
					</div>
				</li>
				<li class="pt-fashion pt-art">
					<div>
						<h5>
							<i class="fa fa-link"></i>
							<a href="#">Mobile Design Inspiration and Creativity</a>
						</h5>
						<div class="post-subinfo">
							<span>June 25</span>   •   
							<span>4 Comments</span>
						</div>
					</div>
				</li>
				<li class="pt-fashion pt-culture">
					<div>
						<h5>
							<i class="fa fa-comment"></i>
							<a href="#">Envato Stories: Coming August 2014</a>
						</h5>
						<div class="post-subinfo">
							<span>June 24</span>   •   
							<span>3 Comments</span>
						</div>
					</div>
				</li>
				<li class="pt-culture pt-art">
					<div>
						<h5>
							<i class="fa fa-file-text-o"></i>
							<a href="#">Meet #59 Interface Designer Kerem Suer</a>
						</h5>
						<div class="post-subinfo">
							<span>June 24</span>   •   
							<span>6 Comments</span>
						</div>
					</div>
				</li>
				<li class="pt-fashion pt-art">
					<div>
						<h5>
							<i class="fa fa-file-text-o"></i>
							<a href="#">Founders, Travel and Epic Beards: What Happens After Envato</a>
						</h5>
						<div class="post-subinfo">
							<span>June 22</span>   •   
							<span>12 Comments</span>
						</div>
					</div>
				</li>
				<li class="pt-fashion pt-culture">
					<div>
						<h5>
							<i class="fa fa-file-text-o"></i>
							<a href="#">Why Designers Make Good Founders (and Cofounders)</a>
						</h5>
						<div class="post-subinfo">
							<span>June 21</span>   •   
							<span>9 Comments</span>
						</div>
					</div>
				</li>
				<li class="pt-culture pt-art">
					<div>
						<h5>
							<i class="fa fa-file-text-o"></i>
							<a href="#">Getting Your Team Through the Storm A Good Product Designer...</a>
						</h5>
						<div class="post-subinfo">
							<span>June 20</span>   •   
							<span>16 Comments</span>
						</div>
					</div>
				</li>
				<li class="pt-fashion pt-art">
					<div>
						<h5>
							<i class="fa fa-file-text-o"></i>
							<a href="#">Introducing LaRead Chat Post</a>
						</h5>
						<div class="post-subinfo">
							<span>June 18</span>   •   
							<span>24 Comments</span>
						</div>
					</div>
				</li>
				<li class="pt-fashion pt-culture">
					<div>
						<h5>
							<i class="fa fa-file-text-o"></i>
							<a href="#">The Future of WordPress</a>
						</h5>
						<div class="post-subinfo">
							<span>June 16</span>   •   
							<span>13 Comments</span>
						</div>
					</div>
				</li>
				<li class="pt-culture pt-art">
					<div>
						<h5>
							<i class="fa fa-file-text-o"></i>
							<a href="#">Workshop: Brand Asset Management</a>
						</h5>
						<div class="post-subinfo">
							<span>June 16</span>   •   
							<span>8 Comments</span>
						</div>
					</div>
				</li>
				<li class="pt-fashion pt-art">
					<div>
						<h5>
							<i class="fa fa-file-text-o"></i>
							<a href="#">Long Live The Kings - Short Film</a>
						</h5>
						<div class="post-subinfo">
							<span>June 12</span>   •   
							<span>26 Comments</span>
						</div>
					</div>
				</li>
			</ul>
		</div>
	</aside>-->

	<div class="canvas">
		<div class="canvas-overlay"></div>
		<header>
			<nav class="navbar navbar-fixed-top navbar-laread">
				<div class="container">
					<div class="navbar-header">
						<a class="navbar-brand" href="http://medisensepractice.com" target="_blank"><img height="64" src="https://medisensecrm.com/Refer/new_assests/img/logo.png" class="img-responsive" alt=""></a>
					</div>
					<!--<div class="get-post-titles">
						<button type="button" class="navbar-toggle push-navbar" data-navbar-type="default">
							<i class="fa fa-bars"></i>
						</button>
					</div>
					<a href="#" data-toggle="modal" data-target="#login-form" class="modal-form">-->
						<a href="http://medisensepractice.com/" class="modal-form" target="_blank"><i class="fa fa-user"></i>
					</a>
					
					<div class="collapse navbar-collapse" id="main-nav">
						<!--<ul class="nav navbar-nav">
							<li>
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">HOME</a>
								<ul class="dropdown-menu" role="menu">
									<li><a href="large-image-v1-1.html">Large Image v1</a></li>
									<li><a href="large-image-v2-1.html">Large Image v2</a></li>
									<li><a href="medium-image-v1-1.html">Medium Image v1</a></li>
									<li><a href="medium-image-v2-1.html">Medium Image v2</a></li>
									<li><a href="masonry-1.html">Masonry</a></li>
									<li><a href="banner-v1.html">BannerMode v1</a></li>
									<li><a href="banner-v2.html">-v2</a></li>
								</ul>
							</li>
							
						</ul>-->
					</div><!--/.nav-collapse -->
				</div>
			</nav>
		</header>

		<div class="container">
			<div class="head-text">
				<h1 style="font-size:30px;"><?php echo $getOffersResult[0]['title']; ?></h1>
				<p class="lead-text"><em><?php echo $getAuthor[0]['company_name']; ?></em></p>
			</div>
		</div>
		<?php if($_SESSION['status']=="error"){ ?>
		<div class="container">
						<div class="alert alert-danger alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                    </button>
                   <span style="color:red;"> <strong>Login failed !!</strong> Please check your user name & password.</span>
                  </div>
			</div>			
						<?php 
						} if($_SESSION['status']=="success"){ ?>
		<div class="container">
						<div class="alert alert-success alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                    </button>
                   <span style="color:green;"> <strong>Success !!</strong> Link has been shared successfully</span>
                  </div>
			</div>			
						<?php 
						}?>
		<div class="container">
			<div class="row">
				<div class="col-md-8">

					<div class="post-fluid post-medium-vertical">

						<div class="container-fluid post-default">
							<div class="container-medium">
								<div class="row post-items">
									<div class="post-item-banner">
										<img src="../../../Hospital/Eventimages/<?php echo $getOffersResult[0]['event_id']; ?>/<?php echo $getOffersResult[0]['photo']; ?>" alt="" />
									</div>
									<div class="col-md-12">
										<div class="post-item">
											<div class="post-item-paragraph">
												<div>
													
													<a href="#" class="mute-text"><?php echo $eventType; ?></a>
												</div>
												<h3><a href="#"><?php echo $getOffersResult[0]['title']; ?></a></h3>
												<p><?php echo $getOffersResult[0]['description']; ?></p>
											</div>
											<div class="post-item-info clearfix">
												<div class="pull-left">
													<span>Posted on: <?php echo date('d M',strtotime($getOffersResult[0]['created_date'])); ?></span>   •   By <?php echo $getAuthor[0]['company_name']; ?>
												</div>
												<div class="pull-right post-item-social">
													<!--<a href="#" class="quick-read qr-not-phone" ><i class="fa fa-eye"></i></a>-->
													<a href="#" tabindex="0" role="button" data-toggle="popover" data-trigger="focus" data-placement="top" data-content="<span>VIEWS <?php echo $num_views; ?>" class="pis-share"><i class="fa fa-eye"></i></a>
													<!--<a href="#" tabindex="0" role="button" data-toggle="popover" data-trigger="focus" data-placement="top" data-content="<a href='#'><i class='fa fa-facebook'></i></a><a href='#'><i class='fa fa-twitter'></i></a>" class="pis-share"><i class="fa fa-share-alt"></i></a>
													<a href="#" class="post-like"><i class="fa fa-heart"></i><span>28</span></a>-->
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<!--<div class="container-fluid post-single">
							<div class="container-medium">
								<div class="row post-items">
									<div class="col-md-12">
										<div class="post-item">
											<div class="post-item-paragraph">
												<div>
													<a href="#" class="quick-read qr-only-phone"><i class="fa fa-eye"></i></a>
													<a href="#" class="mute-text">26 June 2015</a>
												</div>
												<h3><a href="#">Workshop: Brand Asset Management</a></h3>
												<p class="five-lines">Consectetur adipiscing elit. Vivamus nec mauris pulvinar leo dignissim sollicitudin eleifend eget velit. Nunc sed dolor enim, vitae sodales diam. Aenean imperdiet urna a lectus imperdiet consequat. Fusce eu nibh metus. Curabitur nec dignissim diam. Nulla eget massa at urna sagittis malesuada eget a erat. Sed vel magna leo, in pretium nunc. Ut ornare turpis vel ipsum vulputate lacinia. Pellentesque blandit sagittis tempor. Curabitur adipiscing est vitae quam bibendum at euismod ligula dignissim. Duis nec volutpat leo. Nam mollis massa ut nibh blandit ac faucibus metus tincidunt. Cras sagittis facilisis dui, id posuere tortor aliquam in. Aenean rhoncus purus a tortor posuere at interdum mi venenatis. Integer at urna quis nulla egestas dapibus. <a href="#">[...]</a></p>
											</div>
											<div class="post-item-info clearfix">
												<div class="pull-left">
													By <a href="#">Jason Bourne</a>   •   <a href="#">#travel</a>
												</div>
												<div class="pull-right post-item-social">
													<a href="#" class="quick-read qr-not-phone"><i class="fa fa-eye"></i></a>
													<a href="#" tabindex="0" role="button" data-toggle="popover" data-trigger="focus" data-placement="top" data-content="<a href='#'><i class='fa fa-facebook'></i></a><a href='#'><i class='fa fa-twitter'></i></a>" class="pis-share"><i class="fa fa-share-alt"></i></a>
													<a href="#" class="post-like"><i class="fa fa-heart"></i><span>28</span></a>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>



						<div class="row">
							<div class="col-md-12">
								<a href="medium-image-v1-2.html" class="post-nav post-older">OLDER →</a>
							</div>
						</div>-->

					</div>
				</div>
				
				<aside class="col-md-4">
					<div class="laread-right">
					<ul class="laread-list">
							<!--<li><a href="#" data-toggle="modal" data-target="#login-form"  ><button type="submit"  name="addType" id="addType" class="btn btn-success"><i class="fa fa-graduation-cap"></i> APPLY JOB HERE</button></a></li>-->
							<?php echo $eventButton; ?>
							
						</ul>
					</div>
					<?php if($getOffersResult[0]['event_type']=="1"){  //For Type Event ?> 
					<div class="laread-right">

						<ul class="laread-list barbg-grey">
							<li class="title"><i class="fa fa-user"></i> ORGANISERS</li>
							<?php if(!empty($getOffersResult[0]['oganiser_doc_id'])){?>
							<li class="newsletter-bar">
								<p><?php echo $get_pro[0]['Doc_Name']; ?></p>
                            <a>
							<img src="../../../Doc/<?php echo $get_pro[0]['ref_id']; ?>/<?php echo $get_pro[0]['doc_photo']; ?>" alt="..." width="70"/>
							
							</a>
								
							</li>
							<?php } else { ?>
							<li class="newsletter-bar">
								  <?php echo $getOffersResult[0]['organising_committee']; ?>
							</li>
							<?php } ?>
							
							<li><br></li>
							<li class="title"><i class="fa fa-calendar"></i> EVENT DATE</li>
							<li class="newsletter-bar">
								<p><?php echo date('d M Y',strtotime($getOffersResult[0]['start_date']))." - ".date('d M Y',strtotime($getOffersResult[0]['end_date'])); ?></p>
								
							</li>
						
						</ul>
						<?php if(!empty($getOffersResult[0]['description_attachment'])){ ?>
					   <br />
					  <div class="">
						<a href="download-Attachments.php?event_id=<?php echo $getOffersResult[0]['event_id'];?>&type=event&attach_name=<?php echo $getOffersResult[0]['description_attachment']; ?>" target="_blank"><h4><i class="fa fa-paperclip"></i><em> Click here to download brochure</em></h4></a>
					  </div>
					  <br>
					  <?php } 

					  $getKeyNoteSpeakers= $objQuery->mysqlSelect("*","referal as a inner join keynote_speakers as b on a.ref_id=b.doc_id  inner join conference_login as c on c.conf_login_id=b.conf_id","b.conf_id='".$getOffersResult[0]['conf_id']."'","","","","");
						if($getKeyNoteSpeakers==true){
						?>
						<ul class="laread-list">
							<li class="title"><i class="fa fa-microphone"></i> SPEAKERS</li>
							<li>
							<p>
							
                        <ul class="list-inline prod_color">
						<?php foreach($getKeyNoteSpeakers as $list){ 
						$speaker_name=substr($list['ref_name'],0,16);
						if(!empty($list['doc_photo'])){
							$docimg="../../../Doc/".$list['ref_id']."/".$list['doc_photo'];
							
						}else{
							$docimg="../../images/anonymous-profile.png";
						}
						
						if(!empty($list['doc_photo'])){
						?>
                          <li style="margin-right:5px; height:100px;">
                            
                            <a>
							<img src="<?php echo $docimg; ?>" alt="..." width="60"/>
							</a>
							<p style="font-size:10px;"><?php echo $speaker_name; ?></p>
                          </li>
						<?php
						}
						
						} ?>
						 
                        </ul></p></li>
						</ul>
						<?php } ?>
						

						<ul class="laread-list barbg-grey">
							<li class="title"><i class="fa fa-share-alt"></i> SHARE</li>
							<li class="newsletter-bar">
								<p>Lets share this post to your friends on their emails</p>
								
								<form method="post" action="../../add_details.php" name="frmShare" >
								<div class="input-group">
								
								<input type="hidden" name="currenturl" value="<?php echo $_SERVER['REQUEST_URI']; ?>" />
								<input type="hidden" name="mailsub" value="<?php echo $getOffersResult[0]['title']; ?>" />
								<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
									<input type="email" class="form-control" required="required" name="receiverMail" placeholder="raj@mail.com" />
									<span class="input-group-btn">
										<button class="btn" type="submit" name="cmdshare" ><i class="fa fa-check"></i></button>
									</span>
								
								</div>
								</form>
							</li>
							
						</ul>
						<ul class="laread-list social-bar">
							<?php 
							$getPostKey=urlencode($postid);
							$getTitle= hyphenize($getOffersResult[0]['title']);
							
							?>
							<li class="social-icons">
								<a target="_blank" class="fb-xfbml-parse-ignore facebook" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fmedisensecrm.com%2FRefer%2Fshare-post%2F<?php echo $getTitle; ?>%2F<?php echo $getPostKey; ?>" data-layout="button_count" data-size="small" data-mobile-iframe="true" ><i class="fa fa-facebook"></i></a>
								<a target="_blank" href="https://twitter.com/intent/tweet?text=<?php echo $getOffersResult[0]['title']."...For more info visit : https://medisensecrm.com/Refer/share-post/".$getTitle."/".$getPostKey; ?>"  data-size="small" class="tweet"><i class="fa fa-twitter"></i></a>
								<a target="_blank" href="https://plus.google.com/share?url=https%3A%2F%2Fmedisensecrm.com%2FRefer%2Fshare-post%2F<?php echo $getTitle; ?>%2F<?php echo $getPostKey; ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" class="google"><i class="fa fa-google-plus"></i></a>
								<a href="whatsapp://send?text=​I thought this could be useful to you, hence sharing. ​Register soon to avail. https://medisensecrm.com//Refer/share-post/International-Training-Fellowships-2018/1414868347  Many Thanks, www.medisensepractice.com" data-action="share/whatsapp/share" class="watsapp"><i class="fa fa-whatsapp"></i></a>
							</li>
						</ul>

											

					</div>
					<?php } else if($getOffersResult[0]['event_type']=="3"){  //For Type Jobs ?>
					
					<div class="laread-right">

						

						
						<ul class="laread-list barbg-grey">
							<li class="title"><i class="fa fa-paperclip"></i> JOB DESCRIPTIONS</li>
							<li class="newsletter-bar">
								<p><!--<a href="../../download-Attachments.php?comp_id=<?php echo $getOffersResult[0]['company_id'];?>&attach_name=<?php echo $getOffersResult[0]['description_attachment']; ?>" target="_blank" style="color:#0714bf; font-weight:bold;"><em>Click here</em> </a>--><a href="../../login" target="_blank" style="color:#0714bf; font-weight:bold;"><em>Click here</em> </a> to download job descriptions</p>
								
							</li>
							<li><br></li>
							<li class="title"><i class="fa fa-calendar"></i> INTERVIEW DATE</li>
							<li class="newsletter-bar">
								<p><?php echo $getOffersResult[0]['start_end_date']; ?></p>
								
							</li>
							
						</ul>
						
						<ul class="laread-list">
							<li class="title"><i class="fa fa-info-circle"></i> Contact Information</li>
							<li><?php echo $getAuthor[0]['company_addrs']; ?></li>
						</ul>
						
						

						<ul class="laread-list barbg-grey">
							<li class="title"><i class="fa fa-share-alt"></i> SHARE</li>
							<li class="newsletter-bar">
								<p>Lets share this post to your friends on their emails</p>
								
								<form method="post" action="../../add_details.php" name="frmShare" >
								<div class="input-group">
								
								<input type="hidden" name="currenturl" value="<?php echo $_SERVER['REQUEST_URI']; ?>" />
								<input type="hidden" name="mailsub" value="<?php echo $getOffersResult[0]['title']; ?>" />
								<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
									<input type="email" class="form-control" required="required" name="receiverMail" placeholder="raj@mail.com" />
									<span class="input-group-btn">
										<button class="btn" type="submit" name="cmdshare" ><i class="fa fa-check"></i></button>
									</span>
								
								</div>
								</form>
							</li>
							
						</ul>
						<ul class="laread-list social-bar">
							<?php 
							$getPostKey=urlencode($postid);
							$getTitle= hyphenize($getOffersResult[0]['title']);
							
							?>
							<li class="social-icons">
								<a target="_blank" class="fb-xfbml-parse-ignore facebook" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fmedisensecrm.com%2FRefer%2Fshare-post%2F<?php echo $getTitle; ?>%2F<?php echo $getPostKey; ?>" data-layout="button_count" data-size="small" data-mobile-iframe="true" ><i class="fa fa-facebook"></i></a>
								<a target="_blank" href="https://twitter.com/intent/tweet?text=<?php echo $getOffersResult[0]['title']."...For more info visit : https://medisensecrm.com/Refer/share-post/".$getTitle."/".$getPostKey; ?>"  data-size="small" class="tweet"><i class="fa fa-twitter"></i></a>
								<a target="_blank" href="https://plus.google.com/share?url=https%3A%2F%2Fmedisensecrm.com%2FRefer%2Fshare-post%2F<?php echo $getTitle; ?>%2F<?php echo $getPostKey; ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" class="google"><i class="fa fa-google-plus"></i></a>
								<a href="whatsapp://send?text=​I thought this could be useful to you, hence sharing. ​Register soon to avail. https://medisensecrm.com//Refer/share-post/International-Training-Fellowships-2018/1414868347  Many Thanks, www.medisensepractice.com" data-action="share/whatsapp/share" class="watsapp"><i class="fa fa-whatsapp"></i></a>
							</li>
						</ul>

						<!--<div class="laread-list quotes-basic">
							<i class="fa fa-quote-left"></i>
							<p>“The difference between stupidity and genius is that genius has its limits.”</p>
							<span class="whosay">- Albert Einstein </span>
						</div>-->

						

					</div>
					<?php } ?>

				</aside>
			</div>
		</div>

		<!--<footer class="container-fluid footer">
			<div class="container text-center">
				<div class="footer-logo"><img src="https://medisensecrm.com/Refer/new_assests/img/logo-black.png" alt=""></div>
				<p class="laread-motto">Designed for Read.</p>
				<div class="laread-social">
					<a href="#" class="fa fa-twitter"></a>
					<a href="#" class="fa fa-facebook"></a>
					<a href="#" class="fa fa-pinterest"></a>
				</div>
			</div>
		</footer>-->
	</div>

	<div id="quick-read" class="qr-white-theme">
		<div class="quick-read-head">
			<div class="container">
				<a href="#" class="qr-logo"></a>
				<div class="qr-tops">
					<a href="#" class="qr-search-close"><i class="fa fa-times"></i></a>
					<a href="#" class="qr-search"><i class="fa fa-search"></i></a>
					<a href="#" class="qr-change"><i class="fa fa-adjust"></i></a>
					<a href="#" class="qr-close"><i class="fa fa-times"></i></a>
				</div>
				<form class="qr-search-form">
					<input type="text" placeholder="Search LaRead">
				</form>
			</div>
		</div>
		<div class="quick-dialog">
			<div class="quick-body">
				<div class="container">
					<div class="col-md-8 col-md-offset-2">
						<div class="qr-content post-item-paragraph">

							<article>
								<h2>A Nice Street Cafe in London</h2>

								<p>Consectetur adipiscing elit. Vivamus nec mauris pulvinar leo dignissim sollicitudin eleifend eget velit. Nunc sed dolor enim, vitae sodales diam. Mauris fermentum fringilla lorem, in rutrum massa sodales et. Praesent mollis sodales est, eget fringilla libero sagittis eget. Nunc gravida varius risus ac luctus. Mauris ornare eros sed libero euismod ornare. Nulla id sem a mauris egestas pulvinar vitae non dui. Cras odio tortor, feugiat nec sagittis sed, laoreet ut mauris. In hac habitasse platea dictumst.</p>

								<p>What if instead your website used machine learning to build itself, and then rebuilt as necessary, based on data it was gathering about how it was being used? That's what The Grid is aiming to do. After you add content such as pictures, text, the stuff everyone enjoys interacting with your obligation to design...</p>

								<h4>The Truth about Teens and Privacy</h4>

								<p>Social media has introduced a new dimension to the well-worn fights over private space and personal expression. Teens do not want their parents to view their online profiles or look over their shoulder when they’re chatting with friends. Parents are no longer simply worried about what their children wear out of the house but what they photograph themselves wearing in their bedroom to post online. Interactions that were previously invisible to adults suddenly have traces, prompting parents to fret over.</p>

								<h4>Here are some of the ways you may be already being hacked:</h4>

								<ul class="in-list">
									<li>Everyone makes mistakes</li>
									<li>You can control only your behavior</li>
									<li>Good habits create discipline</li>
									<li>Remember the <u>big picture</u></li>
									<li>Everyone learns differently</li>
									<li>Focus on the Benefits, Not the Difficulties</li>
									<li>Traditions are bonding opportunities</li>
								</ul>

								<p>This is not a comprehensive list. Rather, it is a snapshot in time of real-life events that are happening right now. In the future, we will likely read this list and laugh at all the things I failed to envision.</p>
								<p class="with-img">
									<a href="https://medisensecrm.com/Refer/new_assests/img/banner-85-1.jpg" data-fluidbox-qr><img src="https://medisensecrm.com/Refer/new_assests/img/banner-85.jpg" alt=""></a>
									<span class="img-caption">Walk through the Forest</span>
								</p>
								<p>Elit try-hard consectetur, dolore voluptate minim distillery. Bespoke Cosby sweater pug street art et keytar. Nihil fish whatever trust fund, dreamcatcher in fingerstache squid seitan accusamus. Organic Wes Anderson High Life setruhe authentic iPhone, aute art party hashtag fixie church-key art veniam Tumblr polaroid. DIY polaroid vinyl, sustainable hella scenester accusamus fanny pack. Ut Neutra enim pariatur cornhole actually Banksy, tote bag fugiat ad accusamus. Incididunt fixie normcore fingerstache. Freegan proident literally brunch before they sold out.
								</p>

								<p>Readymade fugiat narwhal, typewriter VHS aute stumptown hoodie irure put a bird on it. Fashion axe raw denim brunch put a bird on it voluptate Truffaut. Bitters PBR&amp;B nulla Odd Future swag leggings. Banh mi Wes Anderson butcher letterpress skateboard quis. Chambray hella retro viral Cosby sweater photo booth. Schlitz elit Cosby sweater, Blue Bottle non chambray chia. Single-origin coffee pickled.</p>

								<h5>Blockquote</h5>

								<p>Do officia aliqua, pop-up ut et occupy sriracha. YOLO meggings PBR sartorial mollit, Schlitz assumenda vero kitsch plaid post-ironic PBR&amp;B keffiyeh. Cosby sweater wolf YOLO Austin bespoke, American Apparel crucifix paleo flexitarian. Aliquip bitters food truck, incididunt tofu accusamus magna nesciunt typewriter drinking vinegar Shoreditch try-hard you probably haven’t heard of them labore. </p>

								<blockquote>
									<p><i>“The Muppets Take Manhattan”</i><br />
									This movie was a disappointment. The Muppets do not take Manhattan at all. They merely visit it.<br />
									<span>— No stars.</span></p>
								</blockquote>

								<p>Do officia aliqua, pop-up ut et occupy sriracha. YOLO meggings PBR sartorial mollit, Schlitz assumenda vero kitsch plaid post-ironic PBR&amp;B keffiyeh. Cosby sweater wolf YOLO Austin bespoke, American Apparel crucifix paleo flexitarian. Aliquip bitters food truck, incididunt tofu accusamus.</p>
							</article>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="quick-read-bottom">
			<p class="qr-info">By <a href="#">Daniele Zedda</a>   •   18 February</p>
			<div class="qr-nav">
				<a href="#" class="qr-prev">← PREV POST</a>
				<a href="#" class="qr-share" tabindex="0" role="button" data-toggle="popover" data-placement="top" data-trigger="focus" data-content="<a href='#'><i class='fa fa-facebook'></i></a><a href='#'><i class='fa fa-twitter'></i></a>"><i class="fa fa-share-alt"></i></a>
				<a href="#" class="qr-comment"><i class="fa fa-comment"></i></a>
				<a href="#" class="qr-like"><i class="fa fa-heart"></i> 34</a>
				<a href="#" class="qr-next">NEXT POST →</a>
			</div>
		</div>
		<div class="quick-read-bottom qr-bottom-2 hide">
			<div class="qr-nav">
				<a href="#" class="qr-prev">← PREV POST</a>
				<p class="qr-info">By <a href="#">Daniele Zedda</a>   •   18 February</p>
				<a href="#" class="qr-next">NEXT POST →</a>
				<a href="#" class="qr-like"><i class="fa fa-heart"></i> 34</a>
				<div class="qr-sharebox">
					<span>Share on</span>
					<a href='#'><i class='fa fa-facebook'></i></a>
					<a href='#'><i class='fa fa-twitter'></i></a>
				</div>
			</div>
		</div>
	</div>

	<!-- Login Modal -->
	<div class="modal leread-modal fade" id="login-form" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content" id="login-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title"><i class="fa fa-unlock-alt"></i>Sign In</h4>
				</div>
				<div class="modal-body">
					<form method="post" action="../../check_credentials.php" id="partner-login">
					<input type="hidden" name="eventid" value="<?php echo $getOffersResult[0]['event_id']; ?>" />
					<input type="hidden" name="currenturl" value="<?php echo $_SERVER['REQUEST_URI']; ?>" />
					<div class="form-group">
							<input type="text" class="form-control" id="txtuser" name="txtuser" placeholder="Email / Mobile No.">
						</div>
						<div class="form-group">
							<input type="password" id="txtpassword" name="txtpassword" class="form-control" placeholder="Password">
						</div>
						<div class="linkbox">
							<!--<a href="#">Forgot password ?</a>
							<span>No account ? <a href="#" id="register-btn" data-toggle="modal" data-target="#register-form">Sign Up.</a></span>-->
							<span>No account ? <a href="../../login" id="register-btn" >Sign Up.</a></span>
							
							<!--<span class="form-warning"><i class="fa fa-exclamation"></i>Fill the require.</span>-->
						</div>
						<div class="linkbox">
							<label><input type="checkbox"><span>Remember me</span><i class="fa"></i></label>
							<button type="submit"  id="signinDirect" name="signinDirect" class="btn btn-golden btn-signin">SIGN IN</button>
						</div>
					</form>
				</div>
				<!--<div class="modal-footer">
					<div class="provider">
						<span>Sign In With</span>
						<a href="#"><i class="fa fa-facebook"></i></a>
						<a href="#"><i class="fa fa-twitter"></i></a>
					</div>
				</div>-->
			</div>
			<div class="modal-content" id="register-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title"><i class="fa fa-lock"></i>Sign Up</h4>
				</div>
				<div class="modal-body">
					<form>
						<div class="form-group">
							<input class="form-control" placeholder="Name">
						</div>
						<div class="form-group">
							<input class="form-control" placeholder="Username">
						</div>
						<div class="form-group">
							<input class="form-control" placeholder="Email">
						</div>
						<div class="form-group">
							<input class="form-control" type="password" placeholder="Password">
						</div>
						<div class="linkbox">
							<span>Already got account? <a href="#" id="login-btn" data-target="#login-form">Sign In.</a></span>
						</div>
						<div class="linkbox">
							<label><input type="checkbox"><span>Remember me</span><i class="fa"></i></label>
							<button type="button" class="btn btn-golden btn-success">SIGN UP</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<!-- Bootstrap core JavaScript
	================================================== -->
	<!-- Placed at the end of the document so the pages load faster -->
	<script src="https://medisensecrm.com/Refer/new_assests/js/jquery.min.js"></script>
	<script src="https://medisensecrm.com/Refer/new_assests/js/bootstrap.min.js"></script>
	<script src="https://medisensecrm.com/Refer/new_assests/js/jasny-bootstrap.min.js"></script>
	<script src="https://medisensecrm.com/Refer/new_assests/js/prettify.js"></script>
	<script src="https://medisensecrm.com/Refer/new_assests/js/lang-css.js"></script>
	<script src="https://medisensecrm.com/Refer/new_assests/js/jquery.blueimp-gallery.min.js"></script>
	<script src="https://medisensecrm.com/Refer/new_assests/js/imagesloaded.js"></script>
	<script src="https://medisensecrm.com/Refer/new_assests/js/masonry.js"></script>
	<script src="https://medisensecrm.com/Refer/new_assests/js/viewportchecker.js"></script>
	<script src="https://medisensecrm.com/Refer/new_assests/js/jquery.dotdotdot.min.js"></script>
	<script src="https://medisensecrm.com/Refer/new_assests/js/jquery.colorbox-min.js"></script>
	<script src="https://medisensecrm.com/Refer/new_assests/js/jquery.nicescroll.min.js"></script>
	<script src="https://medisensecrm.com/Refer/new_assests/js/isotope.pkgd.min.js"></script>
	<script src="https://medisensecrm.com/Refer/new_assests/js/jquery.ellipsis.min.js"></script>
	<script src="https://medisensecrm.com/Refer/new_assests/js/calendar.js"></script>
	<script src="https://medisensecrm.com/Refer/new_assests/js/jquery.touchSwipe.min.js"></script>
	<script src="https://medisensecrm.com/Refer/new_assests/js/script.js"></script>
	
</body>
</html>
