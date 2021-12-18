<?php ob_start();
 error_reporting(0);
 session_start(); 


date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d h:i:s');
$cur_Date=date('Y-m-d',strtotime($Cur_Date));
$add_days = 3;
$Follow_Date = date('Y-m-d',strtotime($cur_Date) + (24*3600*$add_days));

require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
$checkPatient= $objQuery->mysqlSelect("*","patient_tab","md5(patient_id)='".$_GET['d']."'","","","","");

?>
<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>

<!-- Meta Tags -->
<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
<meta name="description" content="Medisense Healthcare Solution pvt.ltd" />
<meta name="keywords" content=" clinic, dental, doctor, health, hospital, medical, medical theme, medicine, therapy" />
<meta name="author" content="medical@medisense.me" />

<!-- Page Title -->
<title>Medisense Healthcare Solution pvt.ltd</title>

<!-- Favicon and Touch Icons -->
<!--<link href="images/favicon.png" rel="shortcut icon" type="image/png">
<link href="images/apple-touch-icon.png" rel="icon">-->
<link href="images/apple-touch-icon-72x72.png" rel="icon" sizes="72x72">
<!--<link href="images/apple-touch-icon-114x114.png" rel="icon" sizes="114x114">
<link href="images/apple-touch-icon-144x144.png" rel="icon" sizes="144x144">

<!-- Stylesheet -->
<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="css/jquery-ui.min.css" rel="stylesheet" type="text/css">
<link href="css/animate.css" rel="stylesheet" type="text/css">
<link href="css/css-plugin-collections.css" rel="stylesheet"/>
<!-- CSS | menuzord megamenu skins -->
<link id="menuzord-menu-skins" href="css/menuzord-skins/menuzord-boxed.css" rel="stylesheet"/>
<!-- CSS | Main style file -->
<link href="css/style-main.css" rel="stylesheet" type="text/css">
<!-- CSS | Preloader Styles -->
<link href="css/preloader.css" rel="stylesheet" type="text/css">
<!-- CSS | Custom Margin Padding Collection -->
<link href="css/custom-bootstrap-margin-padding.css" rel="stylesheet" type="text/css">
<!-- CSS | Responsive media queries -->
<link href="css/responsive.css" rel="stylesheet" type="text/css">
<!-- CSS | Style css. This is the file where you can place your own custom css code. Just uncomment it and use it. -->
 <link href="css/style.css" rel="stylesheet" type="text/css"> 
<link href="fileUpload/css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>
    <link href="fileUpload/themes/explorer-fa/theme.css" media="all" rel="stylesheet" type="text/css"/>
   
<!-- Revolution Slider 5.x CSS settings -->
<link  href="js/revolution-slider/css/settings.css" rel="stylesheet" type="text/css"/>
<link  href="js/revolution-slider/css/layers.css" rel="stylesheet" type="text/css"/>
<link  href="js/revolution-slider/css/navigation.css" rel="stylesheet" type="text/css"/>

<!-- CSS | Theme Color -->
<link href="css/colors/theme-skin-blue.css" rel="stylesheet" type="text/css">

<!-- external javascripts -->

<script src="js/jquery-2.2.4.min.js"></script>
<script src="js/jquery-ui.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<!-- JS | jquery plugin collection for this theme -->
<script src="js/jquery-plugin-collection.js"></script>

<!-- Revolution Slider 5.x SCRIPTS -->
<script src="js/revolution-slider/js/jquery.themepunch.tools.min.js"></script>
<script src="js/revolution-slider/js/jquery.themepunch.revolution.min.js"></script>
 <script src="fileUpload/js/plugins/sortable.js" type="text/javascript"></script>
    <script src="fileUpload/js/fileinput.js" type="text/javascript"></script>
    <script src="fileUpload/js/locales/fr.js" type="text/javascript"></script>
    <script src="fileUpload/js/locales/es.js" type="text/javascript"></script>
    <script src="fileUpload/themes/explorer-fa/theme.js" type="text/javascript"></script>
    <script src="fileUpload/themes/fa/theme.js" type="text/javascript"></script>
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
</head>
<body class="has-side-panel side-panel-right fullwidth-page side-push-panel">
<div class="body-overlay"></div>
<div id="side-panel" class="dark" data-bg-img="http://placehold.it/1920x1280">
  <div class="side-panel-wrap">
    <div id="side-panel-trigger-close" class="side-panel-trigger"><a href="#"><i class="pe-7s-close font-36"></i></a></div>
    <a href="javascript:void(0)"><img alt="logo" src="images/medisense_og.png"></a>
    <!--<div class="side-panel-nav mt-30">
      <div class="widget no-border">
        <nav>
          <ul class="nav nav-list">
            <li><a href="#">Home</a></li>
            <li><a href="#">Services</a></li>
            <li><a class="tree-toggler nav-header">Pages <i class="fa fa-angle-down"></i></a>
              <ul class="nav nav-list tree">
                <li><a href="#">About</a></li>
                <li><a href="#">Terms</a></li>
                <li><a href="#">FAQ</a></li>
              </ul>
            </li>
            <li><a href="#">Contact</a></li>
          </ul>
        </nav>        
      </div>
    </div>
    <div class="clearfix"></div>-->
    <div class="side-panel-widget mt-30">
      <div class="widget no-border">
        <ul>
          <li class="font-14 mb-5"> <i class="fa fa-phone text-theme-colored"></i> <a href="#" class="text-gray">+91 7026646022</a> </li>
          <li class="font-14 mb-5"> <i class="fa fa-clock-o text-theme-colored"></i> Mon-Sat 9:00 AM to 6:00 PM </li>
          <li class="font-14 mb-5"> <i class="fa fa-envelope-o text-theme-colored"></i> <a href="#" class="text-gray">medical@medisense.me</a> </li>
        </ul>      
      </div>
      <div class="widget">
        <ul class="styled-icons icon-dark icon-theme-colored icon-sm">
          <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
          <li><a href="#"><i class="fa fa-facebook"></i></a></li>
          <li><a href="#"><i class="fa fa-twitter"></i></a></li>
        </ul>
      </div>
      <p>Copyright &copy;2018 Medisense Healthcare Solutions Pvt. Ltd.<br> All Rights Reserved</p>
    </div>
  </div>
</div>

  
  <!-- Header -->
 <header id="header" class="header">
  
    <div class="header-nav">
      <div class="header-nav-wrapper navbar-scrolltofixed bg-lightest">
        <div class="container">
          <nav id="menuzord-right" class="menuzord blue bg-lightest">
            <a class="menuzord-brand pull-left flip" href="javascript:void(0)">
      <a href="HOME">
	  <img src="images/home logo 3.png" alt="">
            </a>
           
          </nav>
        </div>
      </div>
    </div>
  </header>
  <div class="wrapper wrapper-content">
            <div class="container">
            <div class="row">
				
			<?php if($_GET['response']=="reports-attached"){ ?>
			<div class="alert alert-success alert-dismissable">
					<button aria-hidden="true" data-dismiss="alert" class="close"  type="button">×</button>
					<strong>Reports uploaded successfully!!! </strong>
			</div>
			<?php } ?>
                <div class="col-md-12">
                    <div class="ibox-content text-center p-md">

                    <h3 class="text-theme-colored"><span class="text-navy">Hello <?php echo $checkPatient[0]['patient_name']; ?> !!!</span>
                    If you have any medical report, then please upload here</h3>

                    


                </div>
                </div>
				
				 <div class="col-md-12">
				 <form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  action="add_details.php"  name="frmAddPatient" id="frmAddPatient">
					<input type="hidden" name="patient_id" value="<?php echo $checkPatient[0]['patient_id']; ?>">
                    <div class="ibox-content text-center p-md">
					<div class="row">	
					<label><i class="fa fa-file-medical"></i> Attach Reports here ( Allowed file types: jpg, jpeg, png)</label>
                   
									<div class="form-group col-lg-12">
										<div class="file-loading">
											<input id="file-5" name="file-5[]" class="file" type="file" multiple data-preview-file-type="any" data-upload-url="#" tabindex="7" required>
										</div>
									</div>
                    
					</div>
					<div class="row" id="image_preview"></div>
					<div class="row">
						<button type="submit" name="addAttachments" class="btn btn-primary block full-width m-b ">SUBMIT</button>
					</div>
					</div>
				</form>
                </div>
               
            </div>
                

               

            </div>

        </div>
  
  
  
  <footer id="footer" class="footer pb-0" data-bg-img="images/footer-bg.png" data-bg-color="#25272e">
    <div class="container pt-95 pb-10">

      <div class="row">
        <div class="col-md-12">
          <ul class="list-inline styled-icons icon-hover-theme-colored icon-gray icon-circled text-center mt-30 mb-10">
            <li><a href="https://www.facebook.com/medisensehealthcom-1542369946078959/"><i class="fa fa-facebook"></i></a> </li>
            <li><a href="https://twitter.com/medisensehealth"><i class="fa fa-twitter"></i></a> </li>
          
            <li><a href="https://plus.google.com/u/0/102486349280922812025"><i class="fa fa-google-plus"></i></a> </li>
            <li><a href="#"><i class="fa fa-youtube"></i></a> </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="container-fluid bg-theme-colored p-20">
      <div class="row text-center">
        <div class="col-md-12">
          <p class="text-white font-11 m-0">Copyright &copy;2018 Medisense Healthcare Solutions Pvt. Ltd.<br> All Rights Reserved</p>
        </div>
      </div>
    </div>
  </footer>
  <a class="scrollToTop" href="#"><i class="fa fa-angle-up"></i></a>
</div>
<!-- end wrapper -->

<!-- Footer Scripts -->
<!-- JS | Custom script for all pages -->
<script src="js/custom.js"></script>

<!-- SLIDER REVOLUTION 5.0 EXTENSIONS  
      (Load Extensions only on Local File Systems ! 
       The following part can be removed on Server for On Demand Loading) -->
<script type="text/javascript" src="js/revolution-slider/js/extensions/revolution.extension.actions.min.js"></script>
<script type="text/javascript" src="js/revolution-slider/js/extensions/revolution.extension.carousel.min.js"></script>
<script type="text/javascript" src="js/revolution-slider/js/extensions/revolution.extension.kenburn.min.js"></script>
<script type="text/javascript" src="js/revolution-slider/js/extensions/revolution.extension.layeranimation.min.js"></script>
<script type="text/javascript" src="js/revolution-slider/js/extensions/revolution.extension.migration.min.js"></script>
<script type="text/javascript" src="js/revolution-slider/js/extensions/revolution.extension.navigation.min.js"></script>
<script type="text/javascript" src="js/revolution-slider/js/extensions/revolution.extension.parallax.min.js"></script>
<script type="text/javascript" src="js/revolution-slider/js/extensions/revolution.extension.slideanims.min.js"></script>
<script type="text/javascript" src="js/revolution-slider/js/extensions/revolution.extension.video.min.js"></script>

</body>
</html>