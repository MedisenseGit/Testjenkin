<?php
ob_start();
session_start();
error_reporting(0);  
require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

$request  = str_replace("/share-blogs/", "", $_SERVER['REQUEST_URI']);
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



//$postid  = "1502108078";
$getBlogsResult = $objQuery->mysqlSelect("*","home_posts","postkey='".$postid."'","","","","");
						//TO CHECK USER TYPE WHETHER HE IS DOCTOR OR NOT
						if($getBlogsResult[0]['Login_User_Type']=="doc"){
							$getDocName = $objQuery->mysqlSelect("a.ref_id as ref_id,a.ref_name as ref_name,a.doc_photo as Prof_pic,b.spec_name as spec_name","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$getBlogsResult[0]['Login_User_Id']."'","","","","");
							
								if($getBlogsResult[0]['Login_User_Id']!=0){
								
								$AuthorName=$getDocName[0]['ref_name'];
								$userprof=$getDocName[0]['spec_name'];
								}
								else{
									$getOrg = $objQuery->mysqlSelect("company_name,company_addrs","compny_tab","company_id='".$getBlogsResult[0]['company_id']."'","","","","");
									$AuthorName=$getOrg[0]['company_name'];
									$userprof=$getOrg[0]['company_addrs'];
								}
								if(!empty($getDocName[0]['Prof_pic'])){
								$userimg1="../../../Doc/".$getDocName[0]['ref_id']."/".$getDocName[0]['Prof_pic']; 
								}
								else{
									$userimg1="../../assets/images/anonymous-profile.png";
								}
					
								
						} 
//Update number of views
$num_views=$getBlogsResult[0]['num_views'];
$num_views=$num_views+1;
$arrFields = array();
$arrValues = array();
$arrFields[]= 'num_views';
$arrValues[]= $num_views;
$updateviews=$objQuery->mysqlUpdate('home_posts',$arrFields,$arrValues,"post_id='".$getBlogsResult[0]['post_id']."'");

$get_pro = $objQuery->mysqlSelect("a.ref_id as ref_id,a.ref_id as ref_id,a.doc_photo as doc_photo,a.ref_name as Doc_Name","referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id","a.ref_id='".$getBlogsResult[0]['Login_User_Id']."'","","","","");
	
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">
<link rel="icon" href="<?php echo HOST_MAIN_URL;?>assets/img/favicon_icon.png">

<meta property="og:image" content="<?php echo HOST_MAIN_URL;?>premium/Postimages/<?php echo $getBlogsResult[0]['post_id']; ?>/<?php echo $getBlogsResult[0]['post_image']; ?>" />
<meta property="og:title" content="<?php echo $getBlogsResult[0]['post_tittle']; ?>">
<meta property="og:site_name" content="Practice Medisense">
<meta property="og:url" content="www.medisensecrm.com">
<meta property="og:description" content="<?php echo strip_tags($getBlogsResult[0]['description']); ?>">
<meta property="fb:app_id" content="">
<meta property="og:type" content="article">


<title>Practice Medisense</title>
<!-- Bootstrap core CSS -->
<link href="<?php echo HOST_MAIN_URL;?>Refer/new_assests/css/bootstrap.min.css" rel="stylesheet">

 <!-- Font Awesome -->
    <link href="../../../Hospital/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<!-- Jasny CSS -->
<link href="<?php echo HOST_MAIN_URL;?>Refer/new_assests/css/jasny-bootstrap.min.css" rel="stylesheet">
<!-- Animate CSS -->
<link href="<?php echo HOST_MAIN_URL;?>Refer/new_assests/css/animate.css" rel="stylesheet">
<!-- Code CSS -->
<link href="<?php echo HOST_MAIN_URL;?>Refer/new_assests/css/tomorrow-night.css" rel="stylesheet" />
<!-- Gallery CSS -->
<link href="<?php echo HOST_MAIN_URL;?>Refer/new_assests/css/bootstrap-gallery.css" rel="stylesheet">
<!-- ColorBox CSS -->
<link href="<?php echo HOST_MAIN_URL;?>Refer/new_assests/css/colorbox.css" rel="stylesheet">
<!-- Custom font -->
<link href='https://fonts.googleapis.com/css?family=Raleway:400,200,100,300,500,600,700,800,900' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Roboto+Slab&amp;subset=latin,latin-ext' rel='stylesheet' type='text/css'>
<!-- Custom styles for this template -->
<link href="<?php echo HOST_MAIN_URL;?>Refer/new_assests/css/style.css" rel="stylesheet">
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


	<div class="canvas">
		<div class="canvas-overlay"></div>
		<header>
			<nav class="navbar navbar-fixed-top navbar-laread">
				<div class="container">
					<div class="navbar-header">
						<a class="navbar-brand" href="http://medisensepractice.com" target="_blank"><img height="64" src="<?php echo HOST_MAIN_URL;?>Refer/new_assests/img/logo.png" class="img-responsive" alt=""></a>
					</div>
					<!--<div class="get-post-titles">
						<button type="button" class="navbar-toggle push-navbar" data-navbar-type="default">
							<i class="fa fa-bars"></i>
						</button>
					</div>
					<a href="#" data-toggle="modal" data-target="#login-form" class="modal-form">-->
						<a href="http://medisensepractice.com" class="modal-form" target="_blank"><i class="fa fa-user"></i>
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
				<h1 style="font-size:30px;"><?php echo $getBlogsResult[0]['post_tittle']; ?></h1>
				<!--<p class="lead-text"><em><?php echo $AuthorName; ?></em></p>-->
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
										<img src="../../../premium/Postimages/<?php echo $getBlogsResult[0]['post_id']; ?>/<?php echo $getBlogsResult[0]['post_image']; ?>" alt="" />
									</div>
									<div class="col-md-12">
										<div class="post-item">
											<div class="post-item-paragraph">
												
												<h3><a href="#"><?php echo $getBlogsResult[0]['post_tittle']; ?></a></h3>
												<div>
													
													<a href="#" class="mute-text"><img src="<?php echo $userimg1; ?>" class="img-circle" width="30" /> <?php echo $AuthorName; ?></a>
												</div>
												<p><?php echo $getBlogsResult[0]['post_description']; ?></p>
											</div>
											<div class="post-item-info clearfix">
												<div class="pull-left">
													<span>Posted on: <?php echo date('d M Y',strtotime($getBlogsResult[0]['post_date'])); ?></span>
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

					</div>
				</div>
				
				<aside class="col-md-4">
										
					<div class="laread-right">

						

						
						<!--<ul class="laread-list">
							<li class="title"><i class="fa fa-newspaper-o"></i> RELATED POSTS</li>
							<li class="newsletter-bar">
								<ul class="in-list">
								<?php $getBlogList = $objQuery->mysqlSelect("*","home_posts","post_id!='".$getBlogsResult[0]['post_id']."'","post_id desc","","","0,4"); 
										foreach($getBlogList as $listBlog){
											$title=hyphenize($listBlog['post_tittle']);
								?>
									<li><a href="https://medisensecrm.com/Refer/share-blogs/<?php echo $title; ?>/<?php echo $listBlog['postkey']; ?>"><?php echo $listBlog['post_tittle']; ?></a></li>
								<?php } ?>	
								</ul>
							</li>
							<li><br></li>
							
						</ul>-->
								
						

						<ul class="laread-list barbg-grey">
							<li class="title"><i class="fa fa-share-alt"></i> SHARE</li>
							<li class="newsletter-bar">
								<p>Lets share this post to your friends on their emails</p>
								
								<form method="post" action="../../add_details.php" name="frmShare" >
								<div class="input-group">
								
								<input type="hidden" name="currenturl" value="<?php echo $_SERVER['REQUEST_URI']; ?>" />
								<input type="hidden" name="mailsub" value="<?php echo $getBlogsResult[0]['post_tittle']; ?>" />
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
							$getTitle= hyphenize($getBlogsResult[0]['post_tittle']);
							
							?>
							<li class="social-icons">
								<a target="_blank" class="fb-xfbml-parse-ignore facebook" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo HOST_MAIN_URL;?>Refer%2Fshare-blogs%2F<?php echo $getTitle; ?>%2F<?php echo $getPostKey; ?>" data-layout="button_count" data-size="small" data-mobile-iframe="true" ><i class="fa fa-facebook"></i></a>
								<a target="_blank" href="https://twitter.com/intent/tweet?text=<?php echo $getBlogsResult[0]['post_tittle']."...For more info visit : ".HOST_MAIN_URL."Refer/share-blogs/".$getTitle."/".$getPostKey; ?>"  data-size="small" class="tweet"><i class="fa fa-twitter"></i></a>
								<a target="_blank" href="https://plus.google.com/share?url=<?php echo HOST_MAIN_URL;?>Refer%2Fshare-blogs%2F<?php echo $getTitle; ?>%2F<?php echo $getPostKey; ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" class="google"><i class="fa fa-google-plus"></i></a>
								<a href="whatsapp://send?text=​I thought this could be useful to you, hence sharing. ​Register soon to avail. <?php echo HOST_MAIN_URL;?>Refer/share-blogs/International-Training-Fellowships-2018/1414868347  Many Thanks, www.medisensepractice.com" data-action="share/whatsapp/share" class="watsapp"><i class="fa fa-whatsapp"></i></a>
							</li>
						</ul>


					</div>
					

				</aside>
			</div>
		</div>

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
	<script src="<?php echo HOST_MAIN_URL;?>Refer/new_assests/js/jquery.min.js"></script>
	<script src="<?php echo HOST_MAIN_URL;?>Refer/new_assests/js/bootstrap.min.js"></script>
	<script src="<?php echo HOST_MAIN_URL;?>Refer/new_assests/js/jasny-bootstrap.min.js"></script>
	<script src="<?php echo HOST_MAIN_URL;?>Refer/new_assests/js/prettify.js"></script>
	<script src="<?php echo HOST_MAIN_URL;?>Refer/new_assests/js/lang-css.js"></script>
	<script src="<?php echo HOST_MAIN_URL;?>Refer/new_assests/js/jquery.blueimp-gallery.min.js"></script>
	<script src="<?php echo HOST_MAIN_URL;?>Refer/new_assests/js/imagesloaded.js"></script>
	<script src="<?php echo HOST_MAIN_URL;?>Refer/new_assests/js/masonry.js"></script>
	<script src="<?php echo HOST_MAIN_URL;?>Refer/new_assests/js/viewportchecker.js"></script>
	<script src="<?php echo HOST_MAIN_URL;?>Refer/new_assests/js/jquery.dotdotdot.min.js"></script>
	<script src="<?php echo HOST_MAIN_URL;?>Refer/new_assests/js/jquery.colorbox-min.js"></script>
	<script src="<?php echo HOST_MAIN_URL;?>Refer/new_assests/js/jquery.nicescroll.min.js"></script>
	<script src="<?php echo HOST_MAIN_URL;?>Refer/new_assests/js/isotope.pkgd.min.js"></script>
	<script src="<?php echo HOST_MAIN_URL;?>Refer/new_assests/js/jquery.ellipsis.min.js"></script>
	<script src="<?php echo HOST_MAIN_URL;?>Refer/new_assests/js/calendar.js"></script>
	<script src="<?php echo HOST_MAIN_URL;?>Refer/new_assests/js/jquery.touchSwipe.min.js"></script>
	<script src="<?php echo HOST_MAIN_URL;?>Refer/new_assests/js/script.js"></script>
	
</body>
</html>
