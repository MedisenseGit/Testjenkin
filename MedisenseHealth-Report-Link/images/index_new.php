<?php ob_start();
 error_reporting(0);
 session_start(); 

//include('connect.php');
include('functions.php');


//$login_id = $_SESSION['subid'];
$login_id =$_SESSION['med_login_id'];
$login_user_type =$_SESSION['login_user_type'];

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
/*if(isset($_GET['category'])){
	if(!isset($_GET['start'])) {
			$start = 0;
			}else{
			$start = $_GET['start'];
			}

			$eu = ($start - 0); 
			$limit = 5;         // No of records to be shown per page.
			$this1 = $eu + $limit; 
			$back = $eu - $limit; 
			$next = $eu + $limit;


$postResult = $objQuery->mysqlSelect("*","home_posts","post_type='".$_GET['category']."'","post_id desc","","","$eu, $limit");
$pag_result = $objQuery->mysqlSelect("*","home_posts","post_type='".$_GET['category']."'","post_id desc","");
//$pageing = firstPaging($pag_result,$limit,$back,$next,$eu,$field,$type2);
$arrPage = explode("-",$pageing);
}else{
			if(!isset($_GET['start'])) {
			$start = 0;
			}else{
			$start = $_GET['start'];
			}

			$eu = ($start - 0); 
			$limit = 5;         // No of records to be shown per page.
			$this1 = $eu + $limit; 
			$back = $eu - $limit; 
			$next = $eu + $limit;


$postResult = $objQuery->mysqlSelect("*","home_posts","","post_id desc","","","0,12");
//$pag_result = $objQuery->mysqlSelect("*","home_posts","","post_id desc","");
$pageing = firstPaging($pag_result,$limit,$back,$next,$eu,$field,$type2);
$arrPage = explode("-",$pageing);
}*/

$data = array('api_key'=>HEALTH_API_KEY,'filter_type'=>'0','userid'=>'0');
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://medisensecrm.com/MEDISENSE_HEALTH_API/HOME_PAGE_API");
curl_setopt($ch, CURLOPT_POST, 1);// set post data to true
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
$json = curl_exec($ch);
curl_close ($ch);

$obj = json_decode($json);

if ($obj->{'status'} == 'true')
{
  $processed = TRUE;
}else{
  $ERROR_MESSAGE = $obj->{'data'};
}
$blog_array = array();
$blog_array = $obj->blog_array;
$doctor_array = array();
$doctor_array = $obj->doctor_array;
$getCountries = array();
$getCountries = $obj->getCountries;
$map_hospital_array = array();
$map_hospital_array = $obj->getMapHosp;
$city_name = array();
$city_name = $obj->city_name;
$spec_array = array();
$spec_array = $obj->spec_array;	

if (!$processed && $ERROR_MESSAGE != '') {
    echo $ERROR_MESSAGE;
}

function firstPaging($pag_result,$limit,$back,$next,$eu,$field,$type2){
				$nume = count($pag_result);
				if($nume>=50){
					$tot_num=50;
				}else {
					$tot_num=count($pag_result);
				}
				$this1 = $eu + $limit; 
				$strPaging = "";
				$strPaging.="<div class='doc_paging'>";
				if($back >=0){ 
					$strPaging.="<a href='$page_name?start=$back' class='doc_paging_prev_btn doc_paging_prev' onclick='ShowLoading()'><<</font></a>"; 
				}else{
					$strPaging.=""; 
				}
				$strPaging.="";
				$i=0;
				$l=1;
				$strPaging.="";
				for($i=0;$i < $tot_num;$i=$i+$limit){
					if($i <> $eu){
						$strPaging.="<a href='$page_name?start=$i' class='doc_paging_hide' onclick='ShowLoading()'>$l</a>";
					}else{
						$strPaging.="<span class='doc_paging_hide'>$l</span>";
					}
					$l=$l+1;
				}
				$strPaging.="";
				if($this1 < $nume) { 
					
					$strPaging.="<a href='$page_name?start=$next' class='doc_paging_next_btn doc_paging_next' onclick='ShowLoading()'>>></a>";
				}else{
					$strPaging.="";
				}

				$strPaging.="</div>";
				return $strPaging."-".$nume;
}

//TO CHECK LOGIN USER TYPE WHETHER HE IS DOCTOR OR NORMAL USER


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
//$getFeature = $objQuery->mysqlSelect("*","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.doc_spec!=555 and a.anonymous_status!=1 and a.doc_type_val=1","rand()","","","");
//$getCountries= $objQuery->mysqlSelect("*","countries","","country_name asc","","","");
		
?>
<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>

<!-- Meta Tags -->
<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
<meta name="description" content="MediSenseHealth is a online platform that provides medical second opinion, Medical tourism for international patients, Information on treatment like Ayurveda etc">
      <meta name="keywords" content="Best Medical Tourism Company in India, Medical treatment abroad, reliable medical tourism company, online medical opinion, Online Diagnosis, online medical advice">
		 
 <title>Online Medical Opinion, Medical tourism, Patient Assistance</title>
		 <meta property="og:image" content="https://medisensehealth.com/new_assets/img/medisense_og.jpg" />
		 <meta property="og:title" content="Medisense Health Solutions">
<meta name="google-site-verification" content="7KU_CVFP21KnYciTjJtEIGoy_xH3nXF0Er39DtmA44A" />		
<meta name="msvalidate.01" content="7D01C13A2BA97CDB2C103CF763EC4AC5" />	
<meta property="og:site_name" content="Medisense Health Solutions">
<meta property="og:url" content="https://medisensehealth.com/">
<meta property="og:description" content="MedisenseHealth.com is an online platform, which helps patients from all walks of life receive an unbiased second opinion from volunteering Medical experts who could be individuals or Institutions.">
<meta property="fb:app_id" content="">
<meta property="og:type" content="article">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
      <!-- Bootstrap  -->
      <link type="text/css" rel="stylesheet" href="https://medisensehealth.com/new_assets/css/bootstrap.min.css">
	   <!-- Favicons================================================== -->
      <link rel="shortcut icon" href="https://medisensehealth.com/new_assets/images/favicon_img.png">
	  
	 <meta property="og:image" content="https://medisensehealth.com/new_assets/img/medisense_og.jpg" />
<meta property="og:title" content="Medisense Health Solutions">
<meta property="og:site_name" content="Medisense Health Solutions">
<meta property="og:url" content="https://medisensehealth.com/">
<meta property="og:description" content="MedisenseHealth.com is an online platform, which helps patients from all walks of life receive an unbiased second opinion from volunteering Medical experts who could be individuals or Institutions.">
<meta property="fb:app_id" content="">
<meta property="og:type" content="article">

<?php include('support_new.php'); ?>

<style>
 #centered {
  position: absolute;
  top: 50%;
  left: 50%;
  right:2%;
  transform: translate(-50%, -50%);
  background: #33333373;
  padding:10px;
}

.flip-box {
  background-color: transparent;
  width: 300px;
  height: 300px;
  <!-- border: 1px solid #f1f1f1; -->
  perspective: 1000px; /* Remove this if you don't want the 3D effect */
  padding-bottom:30px;
}

/* This container is needed to position the front and back side */
.flip-box-inner {
  position: relative;
  width: 100%;
  height: 100%;
  text-align: center;
  transition: transform 0.8s;
  transform-style: preserve-3d;


 
}

/* Do an horizontal flip when you move the mouse over the flip box container */
.flip-box:hover .flip-box-inner {
  transform: rotateY(180deg);
}

/* Position the front and back side */
.flip-box-front, .flip-box-back {
  position: absolute;
  width: 100%;
  height: 100%;
  backface-visibility: hidden;
  border-radius:10px;
  padding:30px;
  <!-- margin:10px 10px 10px 10px;  -->
  
  
}

/* Style the front side (fallback if image is missing) */
.flip-box-front {
  background-color: #2fa0da;
  color: white;
}

/* Style the back side */
.flip-box-back {
  background-color: #2fa0da;
  color: white;
  transform: rotateY(180deg);
 
  
}


	.ctaBlock {
    display: inline-block;
    border: 1px solid #78a2bd;
    border-radius: 7px;
    margin: 10px;
    padding: 30px 100px 30px 100px;
    background: #0092b4;
    box-shadow: 0 0 45px -12px #000;
}

.ctaBlock span {
    color: #fff;
    font-size: 19px;
    display: block;
}
.so-Demo a.so-ctaBtn.gso {
    background: #52c4ea;
    color: #fff;
    
}
@media only screen and (max-width: 480px){
.ctaBlock {
    margin: 10px 0;
    padding: 5px 5px 0;
}
}
.so-Demo a.so-ctaBtn {
    padding: 30px 30px 30px 30px;
    text-align: center;
    background: #fff;
    border-radius: 30px;
    font-size: 19px;
    text-decoration: none;
    margin: 20px 10px;
    text-transform: uppercase;
    display: inline-block;
    font-weight: bold;
    color: #207ca7;
    letter-spacing: 1px;
}

	.so-Demo a.so-ctaBtn:hover, .so-Demo a.so-ctaBtn:focus {
		 animation: pulse 1s;
  box-shadow: 0 0 0 2em rgba(#fff,0);
	}
	p.so-hiLt {
    padding: 0 20px;
    font-size: 16px;
    line-height: 20px;
    color: #212020;
    text-align: justify;
    margin: 0 0 25px 0;
    width: 100%;
    position: relative;
    border: none;
}
 p.so-hiLt::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 4px;
    background: #2dc7ff;
    border-radius: 3px;
}
p.so-hiLt span {
    display: block;
    font-weight: bold;
    margin-bottom: 4px;
}
.owl-carousel-1col .owl-prev{
	display:block;
}


.second-opinion {
  background-color : #f68c34;
  color: white;
  padding: 10px 20px;
  border-radius: 4px;
  border-color: #46b8da;
}

#mybutton {
	
  position: fixed;
  bottom: -4px;
  right: 5px;
  z-index:1000;
  top:160px;
  
}
.second-opinion:hover{
	cursor:pointer;
	
}
.second-opinion1 {
  background-color : #f68c34;
  color: white;
  padding: 10px 20px;
  padding-bottom:30px;
  border-radius: 4px;
  border-color: #46b8da;
}


a.second-opinion1:hover, a.second-opinion1:focus{
	cursor:pointer;
	 animation: pulse 1s;
  box-shadow: 0 0 0 2em rgba(#fff,0);
}

.panel-heading {
  padding: 0;
	border:0;
}
.panel-title>a, .panel-title>a:active{
	display:block;
	/*padding:15px;*/
  color:#555;
  /*font-size:16px;*/
  font-weight:bold;	
	text-decoration:none;
}
.panel-heading  a:before {
 /*  font-family: 'Glyphicons Halflings';
   content: "\e114";
   float: right;
   transition: all 0.5s;*/
}
.panel-heading.active a:before {
	/*-webkit-transform: rotate(180deg);
	-moz-transform: rotate(180deg);
	transform: rotate(180deg);*/
} 
.panel-default>.panel-heading {
    background-color: #f5f5f500;
}
.panel-default {
   background-color: #f5f5f5;
}

.panel-group .panel-title a.active::after {
    color: #777777; 
   background: #eee;
}
.panel-group .panel-title a.active {
     background: #f6f6f6 !important; 
	 border-bottom: 1px solid #f6f6f6;
	 color: #555;
}
.panel-group.toggle .panel-heading {
  /*padding: 10px 15px 10px 48px;*/
}
.panel-group .panel-title a::after {
    color: #00a3c8;
}
.panel-group .panel-title a.active::after {
    color: #00a3c8;
}
</style>
<script src="search/jquery.select-to-autocomplete.js"></script>
<script>
  var hospitalList=<?php echo json_encode($map_hospital_array); ?>;
  
  /*(function($){
	    $(function(){
	      $('#txtref').selectToAutocomplete();
	      
	    });
	  })(jQuery);
	  (function($){
	    $(function(){
	      $('#txtspec').selectToAutocomplete();
	      
	    });
	  })(jQuery);*/
	  
	  $(document).ready(function(){
		

		  $("#searchCityState").submit(function(event){
			  event.preventDefault();
			  var cityId=$("#txtref").val();
			  var specId=$("#txtspec").val();
			  
			 if(specId=="" && cityId==""){
			 }
			 else{
				 if(specId==""){
				  specId="0";
			  }
			  if(cityId==""){
				  cityId="0";
			  }
			  $.ajax({
				type: "POST",
				url: "get_cityState.php",
				data:{"cityId":cityId,"spec_id":specId},
				dataType: 'json',
				success: function(data){
					//$("#getDoctorsSearch").html(data);
					//$("#map").html(data);
					hospitalList=data;
					initMap();
					//$(".ui-autocomplete-input").val("");
					//$("#txtspec").val("");
					//google.maps.event.trigger(map, 'resize');
				}
			});
		  }
		  });
	  });
</script>
 <script>
 
  
   $('.panel-collapse1').on('show.bs.collapse', function () {
    $(this).siblings('.panel-heading').addClass('active');
  });

  $('.panel-collapse1').on('hide.bs.collapse', function () {
    $(this).siblings('.panel-heading').removeClass('active');
  });
  
  $('.panel-collapse2').on('show.bs.collapse', function () {
    $(this).siblings('.panel-heading').addClass('active');
  });

  $('.panel-collapse2').on('hide.bs.collapse', function () {
    $(this).siblings('.panel-heading').removeClass('active');
  });
    $('.panel-collapse3').on('show.bs.collapse', function () {
    $(this).siblings('.panel-heading').addClass('active');
  });

  $('.panel-collapse3').on('hide.bs.collapse', function () {
    $(this).siblings('.panel-heading').removeClass('active');
  });
    $('.panel-collapse4').on('show.bs.collapse', function () {
    $(this).siblings('.panel-heading').addClass('active');
  });

  $('.panel-collapse4').on('hide.bs.collapse', function () {
    $(this).siblings('.panel-heading').removeClass('active');
  });
  
</script>	
</head>
<body class="has-side-panel side-panel-right fullwidth-page side-push-panel">
<div class="body-overlay"></div>
<?php include('side_panel.php');?>
<div id="wrapper" class="clearfix">
 

  <!--header-->
<?php include('header_new1.php');?>
  
  <!-- Start main-content -->
  <div class="main-content">
   
    
   <section id="home" class="divider">
      <div class="container-fluid p-0">
          <!-- Slider Revolution Start -->
        <div class="rev_slider_wrapper">
          <div class="rev_slider" data-version="5.0">
            <ul>

              <!-- SLIDE 1 -->
              <li data-index="rs-1" data-transition="random" data-slotamount="7"  data-easein="default" data-easeout="default" data-masterspeed="1000"  data-thumb=""  data-rotate="0"  data-fstransition="fade" data-fsmasterspeed="1500" data-fsslotamount="7" data-saveperformance="off"  data-title="Intro" data-description="">
                <!-- MAIN IMAGE -->
                <img src="images/final_map1.PNG"  alt=""  data-bgposition="center top" data-bgfit="cover" data-bgrepeat="no-repeat" class="rev-slidebg" data-bgparallax="6" data-no-retina>
                <!-- LAYERS -->

                <!-- LAYER NR. 1 -->
                <!--<div class="tp-caption tp-resizeme text-uppercase text-white bg-dark-transparent-5 pl-30 pr-30"
                  id="rs-1-layer-1"
                
                  data-x="['center']"
                  data-hoffset="['0']"
                  data-y="['middle']"
                  data-voffset="['-90']" 
                  data-fontsize="['28']"
                  data-lineheight="['54']"
                  data-width="none"
                  data-height="none"
                  data-whitespace="nowrap"
                  data-transform_idle="o:1;s:500"
                  data-transform_in="y:100;scaleX:1;scaleY:1;opacity:0;"
                  data-transform_out="x:left(R);s:1000;e:Power3.easeIn;s:1000;e:Power3.easeIn;"
                  data-mask_in="x:0px;y:0px;s:inherit;e:inherit;"
                  data-mask_out="x:inherit;y:inherit;s:inherit;e:inherit;"
                  data-start="1000" 
                  data-splitin="none" 
                  data-splitout="none" 
                  data-responsive_offset="on"
                  style="z-index: 7; white-space: nowrap; font-weight:600; border-radius:45px;">Second Opinion
                </div>-->

                <!-- LAYER NR. 2 -->
                <div class="tp-caption tp-resizeme text-uppercase text-white bg-theme-colored-transparent pl-40 pr-40"
                  id="rs-1-layer-2"

                  data-x="['center']"
                  data-hoffset="['0']"
                  data-y="['middle']"
                  data-voffset="['-20']"
                  data-fontsize="['48']"
                  data-lineheight="['70']"
                  data-width="none"
                  data-height="none"
                  data-whitespace="nowrap"
                  data-transform_idle="o:1;s:500"
                  data-transform_in="y:100;scaleX:1;scaleY:1;opacity:0;"
                  data-transform_out="x:left(R);s:1000;e:Power3.easeIn;s:1000;e:Power3.easeIn;"
                  data-mask_in="x:0px;y:0px;s:inherit;e:inherit;"
                  data-mask_out="x:inherit;y:inherit;s:inherit;e:inherit;"
                  data-start="1000" 
                  data-splitin="none" 
                  data-splitout="none" 
                  data-responsive_offset="on"
                  style="z-index: 7; white-space: nowrap; font-weight:600; border-radius:45px;">Best-in-class Medical Second Opinion.
                </div>
<br>
                <!-- LAYER NR. 3 -->
                <div class="tp-caption tp-resizeme text-center text-black" 
                  id="rs-1-layer-3"

                  data-x="['center']"
                  data-hoffset="['0']"
                  data-y="['middle']"
                  data-voffset="['50','60','70']"
                  data-fontsize="['16','18','24']"
                  data-lineheight="['28']"
                  data-width="none"
                  data-height="none"
                  data-whitespace="nowrap"
                  data-transform_idle="o:1;s:500"
                  data-transform_in="y:100;scaleX:1;scaleY:1;opacity:0;"
                  data-transform_out="x:left(R);s:1000;e:Power3.easeIn;s:1000;e:Power3.easeIn;"
                  data-mask_in="x:0px;y:0px;s:inherit;e:inherit;"
                  data-mask_out="x:inherit;y:inherit;s:inherit;e:inherit;"
                  data-start="1400" 
                  data-splitin="none" 
                  data-splitout="none" 
                  data-responsive_offset="on"
                  style="z-index: 5; white-space: nowrap; letter-spacing:0px; font-weight:400;">
				  <ul style="margin: 45px 0px 0px 15px; padding:15px; border-radius:15px; background-color:#C0C0C0;opacity:75%">
				  <li style="color:#000000;">
				  - Have I been diagnosed correctly ?
				  </li>
				  <li>
				  - Is my current treatment best for me ?
				  </li>
				  <li>
				  - What are my options ?  Allopathic, Ayurvedic or yogic ...
				  </li>
				  </ul>
				  
				  <div id="mybutton1" style="margin:15px">
<a href="second-opinion" class="second-opinion1">I want a second opinion NOW</a>
</div>
                </div>

                <!-- LAYER NR. 4 -->
               <!-- <div class="tp-caption tp-resizeme" 
                  id="rs-1-layer-4"

                  data-x="['center']"
                  data-hoffset="['0']"
                  data-y="['middle']"
                  data-voffset="['135','145','155']"
                  data-width="none"
                  data-height="none"
                  data-whitespace="nowrap"
                  data-transform_idle="o:1;"
                  data-transform_in="y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;s:2000;e:Power4.easeInOut;" 
                  data-transform_out="y:[100%];s:1000;e:Power2.easeInOut;s:1000;e:Power2.easeInOut;" 
                  data-mask_in="x:0px;y:[100%];s:inherit;e:inherit;" 
                  data-mask_out="x:inherit;y:inherit;s:inherit;e:inherit;"
                  data-start="1400" 
                  data-splitin="none" 
                  data-splitout="none" 
                  data-responsive_offset="on"
                  style="z-index: 5; white-space: nowrap; letter-spacing:1px;"><a class="btn btn-colored btn-lg btn-theme-colored pl-20 pr-20" href="#">View Details</a> 
                </div>-->
              </li>

              <!-- SLIDE 2 -->
              <li data-index="rs-2" data-transition="random" data-slotamount="7"  data-easein="default" data-easeout="default" data-masterspeed="1000"  data-thumb=""  data-rotate="0"  data-fstransition="fade" data-fsmasterspeed="1500" data-fsslotamount="7" data-saveperformance="off"  data-title="Intro" data-description="">
                <!-- MAIN IMAGE -->
                <img src="images/slide-no-2b.jpg"  alt=""  data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat" class="rev-slidebg" data-bgparallax="6" data-no-retina>
                <!-- LAYERS -->

                <!-- LAYER NR. 1 -->
                <!--<div class="tp-caption tp-resizeme text-uppercase text-white bg-dark-transparent-5 pl-15 pr-15"
                  id="rs-2-layer-1"

                  data-x="['left']"
                  data-hoffset="['30']"
                  data-y="['middle']"
                  data-voffset="['-110']" 
                  data-fontsize="['30']"
                  data-lineheight="['50']"

                  data-width="none"
                  data-height="none"
                  data-whitespace="nowrap"
                  data-transform_idle="o:1;s:500"
                  data-transform_in="y:100;scaleX:1;scaleY:1;opacity:0;"
                  data-transform_out="x:left(R);s:1000;e:Power3.easeIn;s:1000;e:Power3.easeIn;"
                  data-mask_in="x:0px;y:0px;s:inherit;e:inherit;"
                  data-mask_out="x:inherit;y:inherit;s:inherit;e:inherit;"
                  data-start="1000" 
                  data-splitin="none" 
                  data-splitout="none" 
                  data-responsive_offset="on"
                  style="z-index: 7; white-space: nowrap; font-weight:600;">Options
                </div>-->

                <!-- LAYER NR. 2 -->
                <div class="tp-caption tp-resizeme text-uppercase text-white bg-theme-colored-transparent pl-15 pr-15"
                  id="rs-2-layer-2"

                  data-x="['left']"
                  data-hoffset="['30']"
                  data-y="['middle']"
                  data-voffset="['-45']" 
                  data-fontsize="['48']"
                  data-lineheight="['70']"

                  data-width="none"
                  data-height="none"
                  data-whitespace="nowrap"
                  data-transform_idle="o:1;s:500"
                  data-transform_in="y:100;scaleX:1;scaleY:1;opacity:0;"
                  data-transform_out="x:left(R);s:1000;e:Power3.easeIn;s:1000;e:Power3.easeIn;"
                  data-mask_in="x:0px;y:0px;s:inherit;e:inherit;"
                  data-mask_out="x:inherit;y:inherit;s:inherit;e:inherit;"
                  data-start="1000" 
                  data-splitin="none" 
                  data-splitout="none" 
                  data-responsive_offset="on"
                  style="z-index: 7; white-space: nowrap; font-weight:600;">
				  Treatment Assistance
                </div>
<br>
                <!-- LAYER NR. 3 -->
                <div class="tp-caption tp-resizeme text-black" 
                  id="rs-2-layer-3"

                  data-x="['left']"
                  data-hoffset="['35']"
                  data-y="['middle']"
                  data-voffset="['35','45','55']"
                  data-fontsize="['16','18','24']"
                  data-lineheight="['28']"
                  data-width="none"
                  data-height="none"
                  data-whitespace="nowrap"
                  data-transform_idle="o:1;s:500"
                  data-transform_in="y:100;scaleX:1;scaleY:1;opacity:0;"
                  data-transform_out="x:left(R);s:1000;e:Power3.easeIn;s:1000;e:Power3.easeIn;"
                  data-mask_in="x:0px;y:0px;s:inherit;e:inherit;"
                  data-mask_out="x:inherit;y:inherit;s:inherit;e:inherit;"
                  data-start="1400" 
                  data-splitin="none" 
                  data-splitout="none" 
                  data-responsive_offset="on"
                  style="z-index: 5; white-space: nowrap; letter-spacing:0px; font-weight:400;"><ul style="margin: 40px 0px 0px 15px;">
				  <li>
				  - Support before, during and after Treatment  
				  </li>
				  <li>
				  - Support on Travel, Visa, Accommodation, Recuperation holidays etc. 
				  </li>
				  <li>
				  - Post treatment online support
				  </li>
				  </ul><br>
				 
				  <div id="mybutton1" style="margin:10px;">
<a href="second-opinion" class="second-opinion1">Yes, I need assistance</a>
</div>
                </div>

                <!-- LAYER NR. 4 -->
               <!-- <div class="tp-caption tp-resizeme" 
                  id="rs-2-layer-4"

                  data-x="['left']"
                  data-hoffset="['35']"
                  data-y="['middle']"
                  data-voffset="['110','120','140']"
                  data-width="none"
                  data-height="none"
                  data-whitespace="nowrap"
                  data-transform_idle="o:1;"
                  data-transform_in="y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;s:2000;e:Power4.easeInOut;" 
                  data-transform_out="y:[100%];s:1000;e:Power2.easeInOut;s:1000;e:Power2.easeInOut;" 
                  data-mask_in="x:0px;y:[100%];s:inherit;e:inherit;" 
                  data-mask_out="x:inherit;y:inherit;s:inherit;e:inherit;"
                  data-start="1400" 
                  data-splitin="none" 
                  data-splitout="none" 
                  data-responsive_offset="on"
                  style="z-index: 5; white-space: nowrap; letter-spacing:1px;"><a class="btn btn-colored btn-lg btn-theme-colored pl-20 pr-20" href="#">View Details</a> 
                </div>-->
              </li>

              <!-- SLIDE 3 -->
              <!--<li data-index="rs-3" data-transition="random" data-slotamount="7"  data-easein="default" data-easeout="default" data-masterspeed="1000"  data-thumb=""  data-rotate="0"  data-fstransition="fade" data-fsmasterspeed="1500" data-fsslotamount="7" data-saveperformance="off"  data-title="Intro" data-description="">
                <!-- MAIN IMAGE -->
                <!--<img src="images/slide-no-3c.jpg"  alt=""  data-bgposition="center top" data-bgfit="cover" data-bgrepeat="no-repeat" class="rev-slidebg" data-bgparallax="6" data-no-retina>
                <!-- LAYERS -->

                <!-- LAYER NR. 1 -->
                <!--<div class="tp-caption tp-resizeme text-uppercase text-white bg-dark-transparent-5 pl-15 pr-15"
                  id="rs-3-layer-1"

                  data-x="['right']"
                  data-hoffset="['30']"
                  data-y="['middle']"
                  data-voffset="['-110']" 
                  data-fontsize="['30']"
                  data-lineheight="['50']"

                  data-width="none"
                  data-height="none"
                  data-whitespace="nowrap"
                  data-transform_idle="o:1;s:500"
                  data-transform_in="y:100;scaleX:1;scaleY:1;opacity:0;"
                  data-transform_out="x:left(R);s:1000;e:Power3.easeIn;s:1000;e:Power3.easeIn;"
                  data-mask_in="x:0px;y:0px;s:inherit;e:inherit;"
                  data-mask_out="x:inherit;y:inherit;s:inherit;e:inherit;"
                  data-start="1000" 
                  data-splitin="none" 
                  data-splitout="none" 
                  data-responsive_offset="on"
                  style="z-index: 7; white-space: nowrap; font-weight:600;">Assistance
                </div>

                <!-- LAYER NR. 2 -->
                <!--<div class="tp-caption tp-resizeme text-uppercase text-white bg-theme-colored-transparent pl-15 pr-15"
                  id="rs-3-layer-2"

                  data-x="['right']"
                  data-hoffset="['30']"
                  data-y="['middle']"
                  data-voffset="['-45']" 
                  data-fontsize="['48']"
                  data-lineheight="['70']"

                  data-width="none"
                  data-height="none"
                  data-whitespace="nowrap"
                  data-transform_idle="o:1;s:500"
                  data-transform_in="y:100;scaleX:1;scaleY:1;opacity:0;"
                  data-transform_out="x:left(R);s:1000;e:Power3.easeIn;s:1000;e:Power3.easeIn;"
                  data-mask_in="x:0px;y:0px;s:inherit;e:inherit;"
                  data-mask_out="x:inherit;y:inherit;s:inherit;e:inherit;"
                  data-start="1000" 
                  data-splitin="none" 
                  data-splitout="none" 
                  data-responsive_offset="on"
                  style="z-index: 7; white-space: nowrap; font-weight:600;">before, during and after Treatment.
				 
                </div>

                <!-- LAYER NR. 3 -->
                <!--<div class="tp-caption tp-resizeme text-right text-black" 
                  id="rs-3-layer-3"

                  data-x="['right']"
                  data-hoffset="['35']"
                  data-y="['middle']"
                  data-voffset="['30','40','50']"
                  data-fontsize="['16','18','24']"
                  data-lineheight="['28']"
                  data-width="none"
                  data-height="none"
                  data-whitespace="nowrap"
                  data-transform_idle="o:1;s:500"
                  data-transform_in="y:100;scaleX:1;scaleY:1;opacity:0;"
                  data-transform_out="x:left(R);s:1000;e:Power3.easeIn;s:1000;e:Power3.easeIn;"
                  data-mask_in="x:0px;y:0px;s:inherit;e:inherit;"
                  data-mask_out="x:inherit;y:inherit;s:inherit;e:inherit;"
                  data-start="1400" 
                  data-splitin="none" 
                  data-splitout="none" 
                  data-responsive_offset="on"
                  style="z-index: 5; white-space: nowrap; letter-spacing:0px; font-weight:400; padding-bottom:30px;">Travel, Visa, Accommodation, Recuperation holidays, Post treatment online support.<br>
				  <div id="mybutton1">
<a href="second-opinion" class="second-opinion1">I need assistance NOW</a>
</div>
                </div>

                <!-- LAYER NR. 4 -->
                <!--<div class="tp-caption tp-resizeme" 
                  id="rs-3-layer-4"

                  data-x="['right']"
                  data-hoffset="['35']"
                  data-y="['middle']"
                  data-voffset="['110','120','140']"
                  data-width="none"
                  data-height="none"
                  data-whitespace="nowrap"
                  data-transform_idle="o:1;"
                  data-transform_in="y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;s:2000;e:Power4.easeInOut;" 
                  data-transform_out="y:[100%];s:1000;e:Power2.easeInOut;s:1000;e:Power2.easeInOut;" 
                  data-mask_in="x:0px;y:[100%];s:inherit;e:inherit;" 
                  data-mask_out="x:inherit;y:inherit;s:inherit;e:inherit;"
                  data-start="1400" 
                  data-splitin="none" 
                  data-splitout="none" 
                  data-responsive_offset="on"
                  style="z-index: 5; white-space: nowrap; letter-spacing:1px;"><a class="btn btn-colored btn-lg btn-theme-colored pl-20 pr-20" href="#">View Details</a> 
                </div>-->
              <!--</li>-->
            </ul>
          </div><!-- end .rev_slider -->
        </div>
        <!-- end .rev_slider_wrapper -->
        <script>
          $(document).ready(function(e) {
            var revapi = $(".rev_slider").revolution({
              sliderType:"standard",
              sliderLayout: "auto",
              dottedOverlay: "none",
              delay: 10000,
              navigation: {
                  keyboardNavigation: "off",
                  keyboard_direction: "horizontal",
                  mouseScrollNavigation: "off",
                  onHoverStop: "off",
                  touch: {
                      touchenabled: "on",
                      swipe_threshold: 75,
                      swipe_min_touches: 1,
                      swipe_direction: "horizontal",
                      drag_block_vertical: false
                  },
                  arrows: {
                      style: "zeus",
                      enable: true,
                      hide_onmobile: true,
                      hide_under:600,
                      hide_onleave: true,
                      hide_delay: 200,
                      hide_delay_mobile: 1200,
                      tmp:'<div class="tp-title-wrap">    <div class="tp-arr-imgholder"></div> </div>',
                      left: {
                          h_align: "left",
                          v_align: "center",
                          h_offset: 30,
                          v_offset: 0
                      },
                      right: {
                          h_align: "right",
                          v_align: "center",
                          h_offset: 30,
                          v_offset: 0
                      }
                  },
                    bullets: {
                    enable: true,
                    hide_onmobile: true,
                    hide_under: 600,
                    style: "hebe",
                    hide_onleave: false,
                    direction: "horizontal",
                    h_align: "center",
                    v_align: "bottom",
                    h_offset: 0,
                    v_offset: 30,
                    space: 5,
                    tmp: '<span class="tp-bullet-image"></span><span class="tp-bullet-imageoverlay"></span><span class="tp-bullet-title"></span>'
                }
              },
              responsiveLevels: [1240, 1024, 778],
              visibilityLevels: [1240, 1024, 778],
              gridwidth: [1170, 1024, 778, 480],
              gridheight: [680, 500, 400, 400],
              lazyType: "none",
              parallax: {
                  origo: "slidercenter",
                  speed: 1000,
                  levels: [5, 10, 15, 20, 25, 30, 35, 40, 45, 46, 47, 48, 49, 50, 100, 55],
                  type: "scroll"
              },
              shadow: 0,
              spinner: "off",
              stopLoop: "on",
              stopAfterLoops: 0,
              stopAtSlide: -1,
              shuffle: "off",
              autoHeight: "off",
              fullScreenAutoWidth: "off",
              fullScreenAlignForce: "off",
              fullScreenOffsetContainer: "",
              fullScreenOffset: "0",
              hideThumbsOnMobile: "off",
              hideSliderAtLimit: 0,
              hideCaptionAtLimit: 0,
              hideAllCaptionAtLilmit: 0,
              debugMode: false,
              fallbacks: {
                  simplifyAll: "off",
                  nextSlideOnWindowFocus: "off",
                  disableFocusListener: false,
              }
            });
          });
        </script>
        <!-- Slider Revolution Ends -->
       </div>
    </section>
	
	<style>
	#about .about-container .icon-box .icon {
    float: left;
    background: #fff;
    width: 64px;
    height: 64px;
    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-pack: center;
    -webkit-justify-content: center;
    -ms-flex-pack: center;
    justify-content: center;
    -webkit-box-align: center;
    -webkit-align-items: center;
    -ms-flex-align: center;
    align-items: center;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -webkit-flex-direction: column;
    -ms-flex-direction: column;
    flex-direction: column;
    text-align: center;
    border-radius: 50%;
    border: 2px solid #007bff;
    transition: all 0.3s ease-in-out;
}
#about .about-container .icon-box {
    background: #fff;
    background-size: cover;
    padding: 0 0 30px 0;
}
#about .about-container .icon-box .title {
    margin-left: 80px;
    font-weight: 600;
    margin-bottom: 5px;
    font-size: 18px;
}
#about .about-container .icon-box .description {
    margin-left: 80px;
    line-height: 24px;
    font-size: 14px;
}
	</style>
	
<div id="mybutton">
<a href="second-opinion" class="second-opinion">Get Second Opinion</a>
</div>
<section id="about"><div class="container">
      <div class="text-center">
           <!-- <p>
            Binary Fraction is a revolutionary step towards ensuring your E-visibility. This is the place where creativity and supremacy come together in order to generate optimized business solutions through high end technology that holds immense importance in the global market.
            </p>  
			<p>
			We deliver high-quality, high-value products, and support services. We have industry-leading positions in mobile, web development and digital marketing. With costomized solutions and strategy, we provide the right technology solutions for your unique business goals.
			</p>-->
			<h2 class="text-uppercase mt-0 line-height-1">Welcome To <span class="text-theme-colored">Medisense</span></h2>
			</br>
			</div>
			<div class="row about-container">
			<div class="col-lg-6 ">
           <!-- <div class="icon-box wow fadeInUp">
              <div class="icon"><i class="fa fa-spinner"></i></div>
              <h4 class="title"><a href="">Who is this for ?</a></h4>
              <p class="description">
			  You or  your family has been advised a surgery or a medical procedure. 
			 </p>
            </div>-->
			<div class="wrapper center-block">
			           <div class="panel-group" id="accordion1" role="tablist" aria-multiselectable="true" style="padding:10px;margin-bottom:1px;">
					 
								<div class="panel panel-default">
							<div class="panel-heading" role="tab" id="headingRow1">
							  <h4 class="panel-title">
								<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion1" href="#collapseRow1" aria-expanded="false" aria-controls="collapseRow1">
								Who are we?
								</a>
							  </h4>
							</div>
							<div id="collapseRow1" class="panel-collapse1 collapse" role="tabpanel" aria-labelledby="headingRow1">
							  <div class="panel-body" style="background-color: white;border:2px solid #f6f6f6">
								<ul style="list-style:circle;padding-left:15px;">	<li>We are a healthcare platform connecting patients to doctors. We provide second opinion, treatment options and treatment assistance to patients.</li>
										<li>	We provide allopathic, ayurvedic and also spiritual healing for those seeking. </li>
										<li>	We have given over 20,000 medical second opinions so far. </li>
										<li>	Our platform has 3000+ super specialist doctors and 200+ JCI/NABH accredited hospitals. </li>
										</ul>
							  </div>
							</div>
						  </div>
						</div>
					 </div>
					 <div class="wrapper center-block">
			           <div class="panel-group" id="accordion2" role="tablist" aria-multiselectable="true" style="padding:10px;margin-bottom:1px;">
					 
								<div class="panel panel-default">
							<div class="panel-heading" role="tab" id="headingRow2">
							  <h4 class="panel-title">
								<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion2" href="#collapseRow2" aria-expanded="false" aria-controls="collapseRow2">
								Who is this for ?
								</a>
							  </h4>
							</div>
							<div id="collapseRow2" class="panel-collapse2 collapse" role="tabpanel" aria-labelledby="headingRow2">
							  <div class="panel-body" style="background-color: white;border:2px solid #f6f6f6">
								<p>For anyone who has been advised a surgery or a medical procedure.</p>
							  </div>
							</div>
						  </div>
						</div>
					 </div>
					 <div class="wrapper center-block">
			           <div class="panel-group" id="accordion3" role="tablist" aria-multiselectable="true" style="padding:10px;margin-bottom:1px;">
					 
								<div class="panel panel-default">
							<div class="panel-heading" role="tab" id="headingRow1">
							  <h4 class="panel-title">
								<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion3" href="#collapseRow3" aria-expanded="false" aria-controls="collapseRow3">
								What we do ?
								</a>
							  </h4>
							</div>
							<div id="collapseRow3" class="panel-collapse3 collapse" role="tabpanel" aria-labelledby="headingRow3">
							  <div class="panel-body" style="background-color: white;border:2px solid #f6f6f6">
								<ul style="list-style:circle;padding-left:15px;">	<li>When you submit your reports through our website   <a href="second-opinion" target="_blank">https://www.medisensehealth.com/second-opinion</a> , your case will be reviewed by our medical experts and given a second opinion stating whether your current diagnosis and treatment plan are suited for you or not.</li>
										<li>In case you wish to travel to India for treatment, we can offer you the best treatment course depending on your location and budget preferences. </li>
										
										</ul>
							  </div>
							</div>
						  </div>
						</div>
					 </div>
					 <div class="wrapper center-block">
			           <div class="panel-group" id="accordion4" role="tablist" aria-multiselectable="true" style="padding:10px;margin-bottom:1px;">
					 
								<div class="panel panel-default">
							<div class="panel-heading" role="tab" id="headingRow4">
							  <h4 class="panel-title">
								<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion4" href="#collapseRow4" aria-expanded="false" aria-controls="collapseRow4">
								What do you get?
								</a>
							  </h4>
							</div>
							<div id="collapseRow4" class="panel-collapse4 collapse" role="tabpane4" aria-labelledby="headingRow4">
							  <div class="panel-body" style="background-color: white;border:2px solid #f6f6f6">
							  <p>If you have opted only for second opinion, you will get an opinion from one of the best doctors. 
								If you choose to get opinion and treatment, you will be provided with treatment options based on your preferences along with complete travel & treatment assistance as stated below.
								</p>
								<ul style="list-style:circle;padding-left:15px;">	<li>Travel assistance.</li>
								<li>Medical visa support.</li>
								<li>Treatment options. </li>
								<li>Local transfers required during treatment.</li>
								<li>Discharge support. </li>
								<li>Recuperation options like yoga retreats, nature based healing, massages and spas, professional physiotherapists, etc.</li>
								<li>Post treatment transfers. </li>
								<li>Online medical support post treatment.  </li>
								<li>Coordination between our experts and your local medical experts.</li>
										</ul>
										<p>We leave no stone unturned to ensure that you have a complete and comfortable healing experience. </p>
							  </div>
							</div>
						  </div>
						</div>
					 </div>
			</div>
			<div class="col-lg-6 ">
			<p>Way back in 2012, when we were living in the UK, we had a friend who was diagnosed with Cancer. The chances of survival for this 34 year old young man was sleek and medical science had given up. As we hail from India, we spoke to our family and friends to know if there was an alternate treatment which could be an option. However before we could get the contact details of the right person it was too late. That is when we conceived the idea of Medisense Healthcare, connecting the best medical practitioners in the world to people in need. It's a humble attempt to be the first point of contact for you, whoever you are, wherever you are in the world, for you to know whether the line of treatment is correct or what are your medical options.</p>
          <!--  <div class="icon-box wow fadeInUp" data-wow-delay="0.2s">
              <div class="icon"><i class="fa fa-tasks "></i></div>
              <h4 class="title"><a href="">What do you get ?</a></h4>
              <p class="description">A detailed report answering all the below questions.</p>
            </div>-->
			</div>
          </div>
	<!--<div class="row about-container">
          <div class="col-lg-6 wow fadeInUp">
            <!-- <img src="img/about-img.svg" class="img-fluid" alt=""> -->
			<!-- <div class="icon-box wow fadeInUp" data-wow-delay="0.4s">
              <div class="icon"><i class="fa fa-briefcase"></i></div>
              <h4 class="title"><a href="">What do we do ? </a></h4>
              <p class="description">We have a specialisation specific panel of 3000+ medical experts and 200+ JCI/NABH accredited hosptials.
Your case will be reviewed by the most relevant of these medical experts and once done, you will be able to affirm the following. 
 <ul class="description">
<li>- Whether your diagnosis and treatment are correct ?  </li>
<li>- What are your medical options, that includes allopathic, Ayurvedic, yogic and more. </li>
<li>- Which are the best hospitals or doctors or you to undergo treatment depending on your location and budget ?</li> </ul> </p>
            </div>
			</div>
			
			 <div class="col-lg-6 wow fadeInUp">
			<div class="icon-box wow fadeInUp" data-wow-delay="0.6s">
              <div class="icon"><i class="fa fa-television"></i></div>
              <h4 class="title"><a href="">What more ?</a></h4>
              <p class="description">Complete treatment assistance  when you decide to visit any of our panel hospitals for treatment, we plan and arrange your entire visit including but not limited to 

<ul class="description">
<li>- travel, </li>
<li>- Medical visa, </li>
<li>- treatment support, </li>
<li>- local transfers required during treatment, </li>
<li>- discharge support, </li>
<li>- recuperation options like yoga retreats, nature based healing, massages and spas, professional physiotherapists, etc., </li>
<li>- Post treatment transfers</li>
<li>- Online medical support post treatment.</li>  
<li>- Coordination between our experts and your local medical experts </li>
<li>- We leave no stone unturned to ensure that you have a complete and comfortable healing experience</li></ul> </p>
            </div>
          </div>
        </div>-->
		</div>
		</section>
		
	<!--<section>
      <div class="container pt-100 pb-100">
        <div class="row">
          <div class="col-md-12">
            <div class="vertical-masonry-timeline-wrapper">
              <ul class="vertical-masonry-timeline">
                 <li class="each-masonry-item wow fadeInUp" data-wow-duration="1.5s" data-wow-offset="10">
                    <div class="timeline-block">
                      <span class="timeline-post-format"><i class="fa fa-user"></i></span>
                      <article class="post clearfix">
                        <div class="entry-header">
                          
                          <h5 class="entry-title"><a href="second-opinion">Who is this for?</a></h5>
                          
                        </div>
                        <div class="entry-content">
                          <p class="mb-30">You or  your family has been advised a surgery or a medical procedure.</p>
                         
                          
                        </div>
                      </article>
                    </div>
                 </li>
                 <li class="each-masonry-item wow fadeInUp" data-wow-duration="1.5s" data-wow-offset="10">
                    <div class="timeline-block">
                      <span class="timeline-post-format"><i class="fa fa-user"></i></span>
                      <article class="post clearfix">
                        <div class="entry-header">
                         
                          <h5 class="entry-title"><a href="second-opinion">What do you get ? </a></h5>
                          
                        </div>
                        <div class="entry-content">
                          <p class="mb-30">A detailed report answering all the below questions. 
</p>
                          
                        </div>
                      </article>
                    </div>
                 </li>
                 <li class="each-masonry-item wow fadeInUp" data-wow-duration="1.5s" data-wow-offset="10">
                    <div class="timeline-block">
                      <span class="timeline-post-format"><i class="fa fa-user"></i></span>
                      <article class="post clearfix">
                        <div class="entry-header">
                          
                          <h5 class="entry-title"><a href="second-opinion">What do we do ? </a></h5>
                          
                        </div>
                        <div class="entry-content">
                          <p class="mb-30">We have a specialisation specific panel of 3000+ medical experts and 200+ JCI/NABH accredited hosptials.
Your case will be reviewed by the most relevant of these medical experts and once done, you will be able to affirm the following. <ul>
<li>- Whether your diagnosis and treatment are correct ?  </li>
<li>- What are your medical options, that includes allopathic, Ayurvedic, yogic and more. </li>
<li>- Which are the best hospitals or doctors or you to undergo treatment depending on your location and budget ?</li> </ul></p>
                          
                         
                        </div>
                      </article>
                    </div>
                 </li>
                 <li class="each-masonry-item wow fadeInUp" data-wow-duration="1.5s" data-wow-offset="10">
                    <div class="timeline-block">
                      <span class="timeline-post-format"><i class="fa fa-user"></i></span>
                      <article class="post clearfix">
                        <div class="entry-header">
                          
                          <h5 class="entry-title"><a href="second-opinion">What more ?</a></h5>
                          
                        </div>
                        <div class="entry-content">
                          <p class="mb-30">Complete treatment assistance  when you decide to visit any of our panel hospitals for treatment, we plan and arrange your entire visit including but not limited to 
<ul>
<li>travel, </li>
<li>Medical visa, </li>
<li>treatment support, </li>
<li>local transfers required during treatment, </li>
<li>discharge support, </li>
<li>recuperation options like yoga retreats, nature based healing, massages and spas, professional physiotherapists, etc., </li>
<li>Post treatment transfers</li>
<li>Online medical support post treatment.</li>  
<li>Coordination between our experts and your local medical experts </li>
<li>We leave no stone unturned to ensure that you have a complete and comfortable healing experience</li></ul>
                        </div>
                      </article>
                    </div>
                 </li>
                 
                 
              </ul>
            </div>
          </div>
        </div>
      </div>
    </section>-->
	
	<section id="why-us" class="wow fadeIn bg-lighter  so-Demo">
      <div class="container" >
        <center>
              <h2 class="text-uppercase font-weight-600 mt-0 font-28">Get Second Opinion From Specialists All Over The World.</h2>

      </center>
<br>
  <div class="row">
  <center><div class="col-md-12">
		 <!--<div class="ctaBlock">
               <span>Yes, I need Second Opinion</span>-->
                <a href="second-opinion" class="so-ctaBtn gso">Yes, I realize the need of second opinion</a>
             <!--</div>-->
			</div></center>
	</div>
      </div>
	  
    </section>
	<!--<section id="about" class="wow fadeIn">
      <div class="container" >
        <center>
              <h2 class="text-uppercase font-weight-600 mt-0 font-28">What we do?</h2>
              <p style="color:#f68c34; font-size:20px;" >Improving Health Outcomes by Empowering Patients in Informed Medical Decision Making</p></center>


 
	  <div class="row">
	  <center>
<p>Streamlines the process of obtaining a medical second opinion by connecting patients with independent expert physicians. Our innovative technology platform synthesizes medical data into a convenient report and efficiently deliverng it to physicians. We provide video consultation. We provide Second opinion by the Best around the world.</p></center>
			<!--<div class="col-lg-12">
			 <br>
			<center><h4 class="title">What we are?</h4></center>
			</div>
        <div class="row" style="padding-top:100px;">	
			
		   <div class="col-lg-6 wow fadeInUp">
            <p style="margin-left:20px;margin-right:20px;">
              Binary Fraction is a website development company engaged in offering numerous web solutions to our customers.  Being one of the India's top most web development company, Binary Fraction works on sole objective of offering quality in whatever we do. The company is widely known for its qualitative range of service in limited period of time. 
            </p>
            <p style="margin-left:20px;margin-right:20px;">
              Due to our endeavor in offering wide range of solutions at really affordable prices we have won accolades from our esteemed customers who hold special place in this competitive business environment.
            </p>
			</br>
			 </div>
          <div class="col-lg-6 wow fadeInUp">
            <img src="images/family.jpg" class="img-fluid" alt="">
          </div>
        </div>

		 <br>
		<div class="col-lg-12">
			<center><h4 class="title">What we do?</h4></center>
			</div>
        <div class="row" style="padding-top:100px;">
		 
          <div class="col-lg-6 wow fadeInUp">
            <img src="images/neurology2.jpg" class="img-fluid" alt="">
          </div>
		<div class="col-lg-6 wow fadeInUp ">
           
            <p style="margin-left:20px;margin-right:20px;">
             Mobile applications are the  big thing in the town, in order to either grow business, generate revenue or for sheer entertainment.We are well known company, having diverse experience in developing world class and quality applications for mobile and other tablet pcs.
            </p>
            <p style="margin-left:20px;margin-right:20px;">
              We will help your business systematically mobilized in the most hassle-free manner, with our personalized, user-friendly and technical approach. Our Mobile apps and cross-functional experts render constantly evolving yet focused business operations that are faster, productive and efficient. 
            </p>
           	</br>
          </div>
       </div>
		
		 <br>
		<div class="col-lg-12">
			<center><h4 class="title">Why pay us?</h4></center>
			</div>
		 <div class="row" style="padding-top:100px;">
          
          <div class="col-lg-6 wow fadeInUp">
            
            <p style="margin-left:20px;margin-right:20px;">
              Digital marketing also known as online marketing or internet marketing. At BINART FRACTION we help business to get more online exposure and more offline and web traffic. We offer you wide range of Digital Marketing services like Search engine optimization (SEO), Social Media Marketing (SMM), Pay Per Click (PPC). 
            </p>
            <p style="margin-left:20px;margin-right:20px;">
             Our SEO solution helps business’s websites to rank high on the SERP of different search engines like Google, Bing, Yahoo etc. With the help of social media marketing we link our clients to right audience. We provide Pay per click/Google Ads service to our clients to expose themselves to online visitors.
            </p>
			</br>
	</br>
          </div>
		  <div class="col-lg-6 wow fadeInUp">
            <img src="images/inter_31.jpg" class="img-fluid" alt="">
          </div>
        </div>-->
<!--</div>-->
  <!--<div class="row  justify-content-center">
		<div class=" col-lg-4 wow bounceInUp" data-wow-duration="1.4s">
				<center>	<div class="flip-box">
					  <div class="flip-box-inner">
						<div class="flip-box-front">
						  </br>  
						  <div><i class="fa fa-medkit" style="color: #ffffff;font-size:48px; padding-top:15px;"></i></div>
						  </br>
						   <h3 style="font-size:30px; color:#ffff;">What are we?</h3>
						</div>
						<div class="flip-box-back">
						</br>
						   <p style="margin-left:0px;">-Business </br>
				  -Mobile Device Website </br>
				  -Personal/Blog</br>
				  -CRM</br>
				  -Logistic</br>
				  -Inventory</br>
				  </p>
						  
						 
						</div>
					  </div>
					</div></center>
				  </div>
		  
		  
       
		<div class=" col-lg-4 wow bounceInUp" data-wow-duration="1.4s">
            <center><div class="flip-box">
			  <div class="flip-box-inner">
				<div class="flip-box-front">
				  </br>  
				  <div><i class="fa fa-tasks" style="color: #ffffff;font-size:48px; padding-top:15px;"></i></div>
				  </br>
				   <h3 style="font-size:30px; color:#ffff;">What we do?</h3>
				</div>
				<div class="flip-box-back">
						   <p style="margin-left:0px;">-Efficiently deliver medical report to physicians. </br>
				  -Connect patients with independent expert physicians. </br>
				  -Provide video consultation </br>
				  -Second opinion by the Best around the world.
				  </p>
						  
						 
						</div>
			  </div>
			</div></center>
          </div>
		  
		  
		  <div class=" col-lg-4 wow bounceInUp" data-wow-duration="1.4s">
           <center> <div class="flip-box">
			  <div class="flip-box-inner">
				<div class="flip-box-front">
				  </br>  
				  <div><i class="fa fa-money" style="color: #ffffff;font-size:48px; padding-top:15px;"></i></div>
				  </br>
				   <h3 style="font-size:30px; color:#ffff;">Why pay us?</h3>
				</div>
				<div class="flip-box-back">
						</br>
						<p style="margin-left:0px;">-Recognized professionals </br>
				  -Inexpensive </br>
				  -Experenced Doctors </br>
				  </p>
						  
						 
						</div>
			  </div>
			</div></center>
          </div>
        </div>-->
      <!--</div>
	 </section>-->
	
	
 <section id="why-us" class="wow fadeIn bg-lighter  so-Demo">
      <div class="container" >
       

  <div class="row  justify-content-center">
		<div class="col-md-7">
		
              <h2 class="text-uppercase font-weight-600 mt-0 font-28">Benefits</h2>
			  <p style="color:#f68c34">Improving Healthcare. Reducing Cost</p><br>
     
		<p class="so-hiLt"><span>OPTIMIZE TREATMENT & AVOID UNNECESSARY RISKS</span>
Beyond the diagnosis, a second opinion provides us with a chance to ask questions, understand the options, and help in deciding whether to proceed with a potentially risky therapy or not and thereby restore confidence that the treatment plan recommended is appropriate.</p>

<p class="so-hiLt"><span>COST SAVING FROM AVOIDING UNNECESSARY SURGERY</span>
Good medical services provide smart, proactive, and informed choices that patients can trust with confidence.</p>

<p class="so-hiLt"><span>FROM THE COMFORT OF YOUR HOME</span>
The advantages of second opinions are many: financial, physical and psychological. Providing these services remotely via our cutting-edge HIPAA-compliant technology, XperTeleConsult™ system, will improve patient access to medical care and no need for fixing appointments, waiting at the clinic, privacy, etc.</p>

<p class="so-hiLt"><span>IMPROVE HEALTHCARE OUTCOMES</span>
Second opinions have been found to bring down cost of healthcare and reduces misdiagnoses.</p>
		</div>
		<div class="col-md-5" style="margin-top:100px;">
		 <img src="images/inter_30.jpg" class="img-fluid" alt="">
		</div>
	</div>
      </div>
	  
    </section>
			
    <!-- Section: about -->
    <section id="about" class="">
      <div class="container">
        <div class="section-content">
		   <center>
              <h2 class="text-uppercase font-weight-600 mt-0 font-28">Hear from the people.</h2>
              <p style="color:#f68c34;" >What people say about us</p></center>
          <div class="row">
           <div class="col-md-12"  style="margin-top:10px;">
		   <div class="testimonial style1 owl-carousel-1col">
                <div class="item">
				<div class="col-md-6">
                  <div class="comment border-radius-15px">
                    <p>I had this shoulder pain as a result of an accident that I had 2 years back. As doctors suggested surgery, I contacted Medisense with the reports. Within 24Hrs opinion was provided to go for conventional treatment with braces and I was connected to a very good doctor in Mysore itself. He is fantastic. Thanks a lot. Pls continue this service.</p>
                  </div>
                  <div class="content mt-20">
                    <div class="thumb pull-right">
                      <img class="img-circle" alt="" src="images/anonymous-profile.png">
                    </div>
                    <div class="patient-details text-right pull-right mr-20 mt-10">
                      <h5 class="">Ms.Preethi</h5>
                      <h6 class="title">Software Engineer, Mysore</h6>
                    </div>
                  </div>
				   </div>
				   <div class="col-md-6">
				   <iframe width="450" height="250" frameborder="0" allowfullscreen
			src="https://www.youtube.com/embed/lK73HHtlkXU?enablejsapi=1">
			
			</iframe>
			 </div>
                </div>
				
                <div class="item">
				<div class="col-md-6">
                  <div class="comment border-radius-15px">
                    <p>We had sent the report of my wife who had been diagnosed with fibroids in Uterus; local doctors had advised for a surgery to remove. My wife spoke to Dr.Vani Ramkumar of Medisense. She is God, We felt so much better after speaking to her. She reassured that the procedure that we are undergoing is right, and we must continue to visit our doctor. You are real angels.</p>
                  </div>
                  <div class="content mt-20">
                    <div class="thumb pull-right">
                      <img class="img-circle" alt="" src="images/anonymous-profile.png">
                    </div>
                    <div class="patient-details text-right pull-right mr-20 mt-10">
                      <h5 class="">Mr. Suyadh, husband of patient Ms.Arthi</h5>
                      <h6 class="title">Hyderabad</h6>
                    </div>
                  </div>
				                    </div>
				  <div class="col-md-6">
				   <iframe width="450" height="250" frameborder="0" allowfullscreen
			src="https://www.youtube.com/embed/DbpjzdIYKMg?enablejsapi=1">
			
			</iframe>
			 </div>
                </div>
				
<div class="item">
				<div class="col-md-6">
                  <div class="comment border-radius-15px">
                    <p>My son had been diagnosed with 20% vision on left eye and had been referred to SVPrasad Hospital Hyderabad. We were on our way via Mumbai, when we approached Medisense. They suggested us Dr.,in Mumbai itself. We cancelled our tickets to HYD from Mumbai and visited this Dr. on an emergency basis. Although there is big queue to visit the doctor, we could meet him on the same day. He checked our son and suggested a good option. I am so much grateful to Medisense. God bless.</p>
                                           </div>
                  <div class="content mt-20">
                    <div class="thumb pull-right">
                      <img class="img-circle" alt="" src="images/anonymous-profile.png">
                    </div>
                    <div class="patient-details text-right pull-right mr-20 mt-10">
                      <h5 class="">Badrikanth father of Shivam 12 Year old</h5>
                      <h6 class="title">Nagpur</h6>
                    </div>
                  </div>
				   </div>
				   <div class="col-md-6">
				   <iframe width="450" height="250" frameborder="0" allowfullscreen
			src="https://www.youtube.com/embed/RURkuOfHepA?enablejsapi=1" id="3">
			
			</iframe>
			
			 </div>
                </div>
				<div class="item">
				<div class="col-md-6">
                  <div class="comment border-radius-15px">
                      <p> I was so so happy to read this message on Medisense. I have been asking my doctor friends for so long to do something similar to this. Kudos to you people; Do let me know if you need volunteers.</p>
                              </div>
                  <div class="content mt-20">
                    <div class="thumb pull-right">
                      <img class="img-circle" alt="" src="images/anonymous-profile.png">
                    </div>
                    <div class="patient-details text-right pull-right mr-20 mt-10">
                      <h5 class="">Vidya</h5>
                      <h6 class="title">Mumbai</h6>
                    </div>
                  </div>
				   </div>
				  <div class="col-md-6">
				   <iframe width="450" height="250" frameborder="0" allowfullscreen
			src="https://www.youtube.com/embed/YWjNW-8L-oY?enablejsapi=1">
			
			
			 </div>
                </div>
				<div class="item">
				 <div class="col-md-6">
                  <div class="comment border-radius-15px">
                  <p> I was asked to undergo gall bladder removal by a local hospital. I contacted Medisense with all reports. Although they took 4 days to respond, I received Ayurvedik as well as English medicine. I got the ayurvedik medicine delivered at home from Muniyal Ayurveda.</p>
                                </div>
                  <div class="content mt-20">
                    <div class="thumb pull-right">
                      <img class="img-circle" alt="" src="images/anonymous-profile.png">
                    </div>
                    <div class="patient-details text-right pull-right mr-20 mt-10">
                      <h5 class="">Mr. Sunil Kulkarni, 53 years</h5>
                      <h6 class="title">Pune</h6>
                    </div>
                  </div>
				   </div>
				  <div class="col-md-6">
				   <iframe width="450" height="250" frameborder="0" allowfullscreen
			src="https://www.youtube.com/embed/lK73HHtlkXU?enablejsapi=1">
			
			</iframe>
			 </div>
                </div>
				 </div>
              </div>
			
          </div>
        </div>
      </div>
    </section>
	
		
   

    <!-- Section: Doctors -->
	
   

    <!-- Divider: Funfact -->
    <section class="divider parallax layer-overlay overlay-theme-colored-9" data-bg-img="http://placehold.it/1920x1280" data-parallax-ratio="0.7">
      <div class="container pt-60 pb-60">
        <div class="row">
          <div class="col-xs-12 col-sm-6 col-md-3 mb-md-50">
            <div class="funfact text-center">
              <i class="fa fa-user-md mt-5 text-white"></i>
              <h2 data-animation-duration="2000" data-value="3000" class="animate-number text-white font-42 font-weight-500">0</h2>
              <h5 class="text-white text-uppercase font-weight-600">Doctors</h5>
            </div>
          </div>
          <div class="col-xs-12 col-sm-6 col-md-3 mb-md-50">
            <div class="funfact text-center">
              <i class="fa fa-wheelchair mt-5 text-white"></i>
              <h2 data-animation-duration="2000" data-value="10000" class="animate-number text-white font-42 font-weight-500">0</h2>
              <h5 class="text-white text-uppercase font-weight-600">Patients served yearly</h5>
            </div>
          </div>
          <div class="col-xs-12 col-sm-6 col-md-3 mb-md-50">
            <div class="funfact text-center">
              <i class="flaticon-medical-illness  mt-5 text-white"></i>
              <h2 data-animation-duration="2000" data-value="8000" class="animate-number text-white font-42 font-weight-500">0</h2>
              <h5 class="text-white text-uppercase font-weight-600">No. of surgeries supported</h5>
            </div>
          </div>
          <div class="col-xs-12 col-sm-6 col-md-3 mb-md-50">
            <div class="funfact text-center">
              <i class="fa fa-hospital-o mt-5 text-white"></i>
              <h2 data-animation-duration="2000" data-value="200" class="animate-number text-white font-42 font-weight-500">0</h2>
              <h5 class="text-white text-uppercase font-weight-600">Hospitals</h5>
            </div>
          </div>
        </div>
      </div>
    </section>


<!--doctors-->
<section id="doctors">
      <div class="container">
        <div class="section-title text-center">
          <div class="row">
            <div class="col-md-8 col-md-offset-2">
              <h2 class="text-uppercase mt-0 line-height-1">Our Physicians</h2>
			  <div class="title-icon">
                <img class="mb-10" src="images/title-icon.png" alt="">
              </div>
              <div class="text-uppercase">
                <a class="btn btn-theme-colored" href="your-doctors">View all </a> </div>
              <!--<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Rem autem<br> voluptatem obcaecati!</p>
            --></div>
          </div>
        </div>
        <div class="row mtli-row-clearfix">
          <div class="col-md-12">
            <div class="owl-carousel-4col">
		  <?php foreach($doctor_array as $getList){
						// $getDocAddress= $objQuery->mysqlSelect("*","doctor_hosp as a left join hosp_tab as b on a.hosp_id=b.hosp_id","a.doc_id='".$getList['ref_id']."'","","","","");	
						foreach($getList->doc_specializations as $docSpecList){
							$getDocSpec = $docSpecList->spec_name." ";
						}
						$getDocName=urlencode(str_replace(' ','-',$getList->ref_name));
						$getDocCity=urlencode(str_replace(' ','-',$getList->doc_city));
						$getDocState=urlencode(str_replace(' ','-',$getList->doc_state));
						$getDocSpecialization=urlencode(str_replace(' ','-',$getDocSpec));

						$Link=$getDocName.' '.$getDocSpecialization.' '.$getDocCity.' '.$getDocState;
						$actualLink=hyphenize($Link);
						?>	
              <div class="item">
                <div class="team-members border-bottom-theme-color-2px text-center maxwidth400">
                  <div class="team-thumb">
				  <a href="<?php echo "Panel-Of-Doctors/".$actualLink."/".md5($getList->ref_id); ?>">
                     <?php if(!empty($getList->doc_photo)){ ?>
                                        <img src="https://medisensecrm.com/Doc/<?php echo $getList->ref_id; ?>/<?php echo $getList->doc_photo; ?>" class="img-responsive"   style="margin-bottom:5px;display:block;height:240px !important; margin:0 auto 30px;"  alt="user">
                                        <?php }  else { ?>
										 <img src="https://medisensehealth.com/new_assets/img/doc_icon.jpg" alt="" draggable="false" class="img-responsive thumbnail"  style="margin-bottom:5px;display:block;height:240px !important; margin:0 auto 30px;" data-pin-nopin="true"/>
					</a>				<?php  } ?>
                    <div class="team-overlay"></div>
                  </div>
                  <div class="bg-silver-light pt-10 pb-10">
                     <a href="<?php echo "Panel-Of-Doctors/".$actualLink."/".md5($getList->ref_id); ?>"> <h5 class="text-uppercase font-weight-600 m-5"> <?php echo $getList->ref_name; ?></h5>
                    <h6 class="text-theme-colored font-15 font-weight-400 mt-0"><?php echo $getDocSpec; ?></h6>
					
					<h6 class="text-uppercase btn btn-theme-colored btn-sm">View Profile</h6></a>
                     
                    
                  </div>
                </div>
		  </div><?php } ?>
              
            </div>
          </div>
        </div>
      </div>
    </section>



    <!-- Section: Departments -->
    <section data-bg-img="images/pattern/p4.png" id="departments">
      <div class="container">
        <div class="section-title text-center">
          <div class="row">
            <div class="col-md-8 col-md-offset-2">
              <h2 class="text-uppercase mt-0 line-height-1">Speciality</h2>
              <div class="title-icon">
                <img class="mb-10" src="images/title-icon.png" alt="">
              </div>
              <h5 class="text-theme-colored">We cover almost everthing.</h5>    </div>
          </div>
        </div>
        <div class="section-centent">
          <div class="row">
            <div class="col-md-12">
              <div class="services-tab border-10px bg-white">
                <ul class="nav nav-tabs">
                  <li class="active"><a href="Cardiac-Sciences" ><i class="flaticon-medical-heart36"></i><span>Cardiology</span></a></li>
                 <li><a href="gastroenterology" ><i class="flaticon-medical-stomach2"></i><span>Gastroenterology
</span></a></li>
 <li><a href="orthopedics" ><i class="flaticon-medical-xray2"></i><span>Orthopedics</span></a></li>
                   <li><a href="neurocare" ><i class="flaticon-medical-brain9"></i><span>Neuro surgery</span></a></li>
              <li><a href="medical-oncology" ><i class="flaticon-medical-symbolic"></i><span>Medical Oncology</span></a></li>
                 <li><a href="ENT" ><i class="flaticon-medical-mouth1"></i><span>ENT</span></a></li>
       
                 <li><a href="gynaecology" ><i class="flaticon-medical-mother19"></i><span>Gynaecology</span></a></li>
                <li><a href="surgical-oncology" ><i class="flaticon-medical-symbolic"></i><span>Surgical Oncology</span></a></li>
                   <li><a href="nephrology" ><i class="flaticon-medical-urology"></i><span>Nephrology</span></a></li>
               <li><a href="neurology" ><i class="flaticon-medical-brain9"></i><span>Neurology
</span></a></li>
              
             <li><a href="radiation-oncology" ><i class="flaticon-medical-symbolic"></i><span>Radiation oncology



</span></a></li>
             <li><a href="urology" ><i class="flaticon-medical-urology"></i><span>Urology

</span></a></li>
                <li><a href="spine-surgery" ><i class="flaticon-medical-bone11"></i><span>Spine Surgery
</span></a></li>
            <li><a href="haematology" ><i class="flaticon-medical-blood11"></i><span> Haematology
</span></a></li>

<li><a href="others" ><i class="flaticon-medical-medical55"></i><span> Others
</span></a></li>


                     </ul>
              
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
	
	

	
	<!--client say-->
	<!-- <section class="divider parallax layer-overlay overlay-theme-colored-9  " >

      <div class="container pt-60 pb-60">
        
			
			
			
			

<a href="tab11"></a>
<div class="col-md-8">
<div class="row">
 <h2 class=" text-white text-left  mt-0 mb-30">Download Medisense App now</h2>
            </div>
<!--<div class="row">
<p class="text-white">By providing your number / email id, you agree to receive a one-time automated text <br>message / email with a link to get the app. No purchase necessary.</p>
</div><form  id="footer_quick_contact_form" name="footer_quick_contact_form" class="quick-contact-form">

<div class="row">
<div class="col-md-3">
<label class="text-white">Country<span class="error-star"> *</span></label>
<select name="usercountry" name="usercountry"  class="form-control">
<option value="select">Select Country</option>
					  <?php 
							$i=30;
							foreach($getCountries as $CntNameList){
								?> 
										
								<option value="<?php echo stripslashes($CntNameList->country_name);?>" />
								<?php echo stripslashes($CntNameList->country_name);?></option>
								
								<?php 
								$i++;
							}?>
</select>
</div>
<div class="col-md-3 ">
<label class="text-white">Mobile Number<span class="error-star"> *</span></label>
<input type="text" class="form-control"  name="usermobile"  id="usermobile" required="" placeholder="Ex:7026646022" maxlength="10" minlength="10">

</div>



</div>
<br>

<div class="row">

<div class="col-md-6">

<label class="text-white">Email<span class="error-star"> *</span></label>
<input type="email" class="form-control"  name="usermail" id="usermail" required="" placeholder="Ex:medical@medisense.me" >
</div>
<div class="col-md-1">

<button type="submit" class="btn btn-warning app_link" name="app_link" style="margin-top: 35px;">SEND APP LINK</button>
</div>
<!--<br>
<div class="col-md-1">
<a class="btn btn-warning">SEND APP LINK</a>
</div>
</div> 	
</form>

</div>
<div class="col-md-4">

<img src="images/download app.png" width="100%" alt="Medisense Health App">
  
  </div>
</div>

     
 
    </section>  -->

    <!-- Divider: Appoinment Form -->
    <section id="Appoinment" class="bg-lighter">
      <div class="container pt-50 pb-0">
        <div class="row">
          <div class="col-md-6">
            <div class="p-10">
              <!-- Reservation Form Start-->
              <h2 class="mt-0 line-bottom line-height-1 text-black mb-30"><span class="text-theme-colored font-weight-600">Need Help?</span> Request callback</h2>
			  <br><p>If you need any assistance, please reach out to us.<br> We are more than happy to help you!</p><br>
               <div id="message"></div>
                <div class="row">
				 <div class="col-sm-12">
                    <div class="form-group mb-30">
                      <input placeholder="Enter Name" type="text" id="user_name" name="user_name" required="" class="form-control">
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group mb-30">
                      <input placeholder="Email" type="email"  id="user_email" name="user_email" class="form-control" required="">
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group mb-30">
                      <input placeholder="Mobile Number (10 digits)" type="text" id="user_phone" name="user_phone" required="" class="form-control" maxlength="10" minlength="10" >
                    </div>
                  </div>
                
                  <div class="col-sm-12">
                    <div class="form-group mb-30">
                    <!--  <textarea  id="user_msg" name="user_msg" class="form-control required"  placeholder="Enter Message" rows="5" aria-required="true"></textarea>-->
					<textarea  id="user_msg" name="user_msg" class="form-control"  placeholder="Enter Message" rows="5"></textarea>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group mb-0 mt-0">
                      
                      <button type="submit" class="btn btn-theme-colored btn-lg btn-block request_help" name="request_help">Submit Now</button>
                    </div>
                  </div>
				  
                </div>
              <!-- Reservation Form End-->

              <!-- Reservation Form Validation Start-->
             <!-- <script type="text/javascript">
                $("#reservation_form").validate({
                  submitHandler: function(form) {
                    var form_btn = $(form).find('button[type="submit"]');
                    var form_result_div = '#form-result';
                    $(form_result_div).remove();
                    form_btn.before('<div id="form-result" class="alert alert-success" role="alert" style="display: none;"></div>');
                    var form_btn_old_msg = form_btn.html();
                    form_btn.html(form_btn.prop('disabled', true).data("loading-text"));
                    $(form).ajaxSubmit({
                      dataType:  'json',
                      success: function(data) {
                        if( data.status == 'true' ) {
                          $(form).find('.form-control').val('');
                        }
                        form_btn.prop('disabled', false).html(form_btn_old_msg);
                        $(form_result_div).html(data.message).fadeIn('slow');
                        setTimeout(function(){ $(form_result_div).fadeOut('slow') }, 6000);
                      }
                    });
                  }
                });
              </script>-->
              <!-- Reservation Form Validation Start -->
            </div>
          </div>
          <div class="col-md-6">
            <img src="images/callback4.png" alt="Medisense Health Call Back image">
          </div>
        </div>
      </div>
    </section>

    <!-- Section: blog -->
    <section id="blog">
      <div class="container">
        <div class="section-title text-center">
          <div class="row">
            <div class="col-md-8 col-md-offset-2">
              <h2 class="text-uppercase mt-0 line-height-1">Health Blogs</h2>
              <div class="title-icon">
                <img class="mb-10" src="images/title-icon.png" alt="Medisense Health Blogs Title Icon">
              </div>
			   <div class="text-uppercase">
                <a class="btn btn-theme-colored" href="Blogs">View all </a> </div>
            </div>
          </div>
        </div>
        <div class="section-content">
          <div class="row">
            <div class="col-md-12">
              <div class="owl-carousel-3col">
			  <?php 
			  //print_r($postResult);
			  foreach ($blog_array as $postResultList)
			  {
	//$getTitle=urlencode(str_replace(' ','-',$postResult[$i]['post_tittle']));
	
		//VIEW MORE URL LINK
	$getTitle= hyphenize($postResultList->post_tittle);

	$getPostKey=urlencode($postResultList->postkey);
	
	$_SESSION['geturl']=$getTitle.'/'.$getPostKey;
	
	
	?>
                <div class="item">
                  <article class="post clearfix bg-lighter">
                    <div class="entry-header">
                      <div class="post-thumb thumb"> 
                       <?php if(!empty($postResultList->post_image)){ ?>	<a href="view-more/<?php echo $getTitle; ?>/<?php echo $getPostKey; ?>" target="_blank"> <img src="https://www.medisensecrm.com/Postimages/<?php echo $postResultList->post_id; ?>/<?php echo $postResultList->post_image; ?>" alt="" class="img-responsive" style="margin-bottom:5px;display:block;height:300px !important; margin:0 auto 30px;"> </a>
					   <?php } ?>
					  </div>                    
                      <div class="entry-date media-left text-center flip bg-theme-colored text-white pt-5 pr-15 pb-5 pl-15">
                        <?php echo date('M d',strtotime($postResultList->post_date)); ?>
                      </div>
                    </div>
                    <div class="entry-content p-15 pt-10 pb-10">
                      <div class="entry-meta media no-bg no-border mt-0 mb-10">
                        <div class="media-body pl-0">
                          <div class="event-content pull-left flip">
                            <h4 class="entry-title text-white font-weight-600 m-0 mt-5"><a href="view-more/<?php echo $getTitle; ?>/<?php echo $getPostKey; ?>"><?php if(!empty($postResultList->post_tittle)){ echo substr($postResultList->post_tittle,0,50)."..."; } ?> </a></h4>
                            <span class="mb-10 text-gray-darkgray mr-10 font-13"><i class="fa fa-eye mr-5 text-theme-colored"></i> <?php echo $postResultList->num_views;?> Views</span>
                            </div>
                        </div>
                      </div>
                      <p class="mt-5"><?php if(!empty($postResultList->post_description)){ echo substr(strip_tags($postResultList->post_description),0,100)."..."; } ?> <a class="text-theme-colored font-12 ml-5" href="view-more/<?php echo $getTitle; ?>/<?php echo $getPostKey; ?>"> View Details</a></p>
                    </div>
                  </article>
                </div>
			  <?php } ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  <!-- end main-content -->
  
  <script>
  
$(window).scroll(function() {
    var height = $(window).scrollTop();
    if (height > 990) {
        $('#mybutton').fadeIn();
    } else {
        $('#mybutton').fadeOut();
    }
});

  $(".owl-carousel-1col").owlCarousel({
     items : 1,
     loop  : true,
     margin : 30,
     nav    : true,
     smartSpeed :900,
	 
     navText : ["<i class='fa fa-chevron-left'></i>","<i class='fa fa-chevron-right'></i>"],
	  onTranslate: function(event) {
    var currentSlide, player, command;

    currentSlide = $('.owl-item.active');

    player = currentSlide.find(" iframe").get(0);

    command = {
        "event": "command",
        "func": "pauseVideo"
    };

    if (player != undefined) {
        player.contentWindow.postMessage(JSON.stringify(command), "*");

    }

}
	 
   });
  </script>
  
 
  <!-- Footer -->
  <?php include('footer_new.php'); ?>
  
</body>
</html>