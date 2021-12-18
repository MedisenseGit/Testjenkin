<?php
ob_start();
error_reporting(0); 
session_start();

/*$admin_id = 178;
if(empty($admin_id)){
	header("Location:index.php");
} */

$admin_id = $_SESSION['user_id'];
//Get the page name 
//$request_uri  = str_replace("", "", $_SERVER['REQUEST_URI']);
include('functions.php');
if(empty($admin_id)){
	header("Location:../login");
}

require_once("../../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();
			
$getdetails = mysqlSelect("*","doctor_webtemplates","doc_id='".$admin_id."' and doc_type=1","","","","");
$getdoctorinfo = mysqlSelect("*","referal","ref_id='".$admin_id."'","","","","");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <!-- viewport meta -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Rida vCard -  Responsive HTML5 Portfolio/Resume Template">
    <meta name="keywords" content="vcard, html5, portfolio, resume, material">

    <title>Doctor Web Template - Personal Portfolio</title>

    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,700,900" rel="stylesheet">

    <!-- bootstrap -->
    <link rel="stylesheet" href="rida/css/bootstrap.min.css"/>

    <!-- style css -->
    <link rel="stylesheet" href="preview.css"/>

    <!-- responsive css -->
    <link rel="stylesheet" href="rida/css/responsive.css">

    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="rida/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="rida/images/favicon-16x16.png">
<script type="text/javascript">
function mypreviewfunction(){
	alert(hi);
}
</script>
	</head>
<body>

   <!-- <section class="preview_hero">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="prev_heading">rida</h1>
                    <p class="item_name"><span>Resume, vCard, CV, Portfolio</span>  HTML Template</p>
                    <div class="buttons">
                        <a href="#demo_select">View Demos</a>
                        <a href="https://themeforest.net/item/rida-vcard-responsive-html5-portfolioresume-template/19807991">Purchase now</a>
                    </div>
                </div>
            </div>
        </div>
    </section>-->

    <section class="demo_select" id="demo_select">
        <div class="container">
             <a href="../Home" ><button  class="elegant-btn1" style="margin-left: 30px;" > BACK TO DASHBOARD </button> </a>
				 
                <div class="col-md-12">
					
                    <p class="choose float-left">Welcome Dr. <?php echo $getdoctorinfo[0]['ref_name']?> </p>
					<div><span class="sucess">
						   <?php if(isset($_GET['response'])){
							switch($_GET['response']){
							case '0':
								//echo '<font color=green; size=4><b> Thank you. Your appointment booked successfully. We will try to get back in 24-48 Hrs </b></font>';
								?>
								<div class="alert alert-success alert-dismissable">
									<button aria-hidden="true" data-dismiss="alert" class="close"  type="button">×</button>
									<strong>Your Website Theme Request Has Been Sent Successfully </strong>
								</div>
								<?php
								break;
							case '1' : 
									?>
								
								<div class="alert alert-danger alert-dismissable">
										<button aria-hidden="true" data-dismiss="alert" class="close"  type="button">×</button>
										<strong>You have already choosen the theme </strong>
								</div>
								
								<?php
								break;
							  }
							  }
							?></span>
						</div>
                </div>
				<form method="post" enctype="multipart/form-data" name="viewtheme" id="viewtheme" action="add_details.php">
             
				
                <div class="row">
                    <div class="col-md-6">
                       <a href="https://medisensecrm.com/premium/Build-Website/Theme_1_preview/" target="_blank" class="prev_link prev2">
                            <div class="single_preview">
                                <img src="img/theme1.jpg" alt="Previw 1">
							<h3> PRICE: Rs. 14,000/-</h3>
             <p style="color:#372db4";>
			 <?php if($getdetails[0]['template_id'] == '1') { ?> 
			  <input type="radio"   id="home_theme"   name="home_theme"  value="1" checked > <strong>Theme 1</strong>
			
			 <?php } else { ?> 
			  <input type="radio"   id="home_theme"   name="home_theme"  value="1" > <strong>Theme 1</strong>
			
			 <?php } ?>
			<label  class="elegant-btn1" >PREVIEW </label> </p>
			 </div>
                       </a>  
                    </div>
                    <div class="col-md-6">
                        <a href="https://medisensecrm.com/premium/Build-Website/Theme_2_preview/" target="_blank" class="prev_link prev2">
                            <div class="single_preview">
                                <img src="img/Theme2.jpg" alt="Previw 2">
								<h3> PRICE: Rs. 16,000/-</h3>
                                 <p style="color:#372db4";>
								  <?php if($getdetails[0]['template_id'] == '2') { ?> 
			  <input type="radio"   id="home_theme"   name="home_theme"  value="2" checked > <strong>Theme 2</strong>
			
			 <?php } else { ?> 
			  <input type="radio"   id="home_theme"   name="home_theme"  value="2" > <strong>Theme 2</strong>
			
			 <?php } ?>
			 <label  class="elegant-btn1" >PREVIEW </label>
			 </p>
								<!--<button  class="elegant-btn1" >PREVIEW </button>-->
								
                            </div>
                        </a>
                    </div>
					    </div>
						  <div class="row">
					<div class="col-md-6">
                        <a href="https://medisensecrm.com/premium/Build-Website/Theme_3_preview/" target="_blank" class="prev_link prev2">
                            <div class="single_preview">
                                <img src="img/theme3.jpg" alt="Previw 2">
								<h3> PRICE: Rs. 12,000/-</h3>
                             <p style="color:#372db4;">
							  <?php if($getdetails[0]['template_id'] == '3') { ?> 
			  <input type="radio"   id="home_theme"   name="home_theme"  value="3" checked > <strong>Theme 3</strong>
			
			 <?php } else { ?> 
			  <input type="radio"   id="home_theme"   name="home_theme"  value="3" > <strong>Theme 3</strong>
			
			 <?php } ?>
								<!--<button  class="elegant-btn1" >PREVIEW </button>-->
								<label  class="elegant-btn1" >PREVIEW </label></p>
								
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="https://medisensecrm.com/premium/Build-Website/Theme_4_preview/" target="_blank" class="prev_link prev2">
                            <div class="single_preview">
                                <img src="img/theme4.jpg" alt="Previw 2">
								<h3> PRICE: Rs. 12,000/-</h3>
                               <p style="color:#372db4";>
							    <?php if($getdetails[0]['template_id'] == '4') { ?> 
			  <input type="radio"   id="home_theme"   name="home_theme"  value="4" checked > <strong>Theme 4</strong>
			
			 <?php } else { ?> 
			  <input type="radio"   id="home_theme"   name="home_theme"  value="4" > <strong>Theme 4</strong>
			
			 <?php } ?>
								<!--<button  class="elegant-btn1" >PREVIEW </button>-->
								<label  class="elegant-btn1" >PREVIEW </label></p>
								
                            </div>
                        </a>
                    </div>
                </div>
			<section id="url">	
			<?php if(!empty($getdetails[0]['website_name'])) { ?> 
			 <div class="row">
		<div class="col-md-5">
		  <h4 style="color:#372db4;margin-left:30px";><strong>Your Website Link:</strong></h4>	
		 <a href="http://<?php echo $getdetails[0]['website_name'];?>" target="_blank" class="prev_link prev2"><strong>http://<?php echo $getdetails[0]['website_name'];?></strong></a>
							
		</div>
			<div class="col-md-4" >
			 <?php if($getdetails[0]['template_id'] == '1') {  ?>
			 <a href="https://medisensecrm.com/premium/Build-Website/Theme1_Edit/" target="_blank" > <label class="elegant-btn1" style="margin-left:30px;"> START BUILD  </label></a>
			 <?php } else if($getdetails[0]['template_id'] == '2') { ?>
			<a href="https://medisensecrm.com/premium/Build-Website/Theme2_Edit/" target="_blank" > <label class="elegant-btn1" style="margin-left:30px;">START BUILD </label></a>
			 <?php } else if($getdetails[0]['template_id'] == '3') {?>
			 <a href="https://medisensecrm.com/premium/Build-Website/Theme3_Edit/" target="_blank" > <label class="elegant-btn1" style="margin-left:30px;">START BUILD </label></a>
			 <?php } else if($getdetails[0]['template_id'] == '4') { ?>
			  <a href="https://medisensecrm.com/premium/Build-Website/Theme4_Edit/"  target="_blank" > <label class="elegant-btn1" style="margin-left:30px;">START BUILD </label></a>
			 <?php }  ?>
				</div>	
                </div>
			 <?php } else { ?> 
			   <div class="row">
			<div class="col-md-6" style="margin-left:30px;">
			<label>Your Preferred URL :</label>
             <input type="text" class="form-control"  id="home_url1" maxlength="200" placeholder="1.URL" name="home_url1"  value="" > <br>
            <input type="text" class="form-control" id="home_url2" maxlength="200" placeholder="2.URL" name="home_url2"  value="" > <br>
            	<input type="text" class="form-control" id="home_url3" maxlength="200" placeholder="3.URL" name="home_url3"  value="" > <br>
            </div>					  
			</div>
			 
           
			
			<div class="row">
			<div class="col-md-12" style="margin-left:30px;">
			<div class="col-md-2 col-md-offset-2" ><button type="submit" class="elegant-btn2" name="themechoice" >SUBMIT </button>
				</div>		
			</div>
        </div>
		<?php } ?>
						
		</form>
    </section>





    <!--//////////////////// JS GOES HERE ////////////////-->

    <!-- jquery latest version -->
    <script src="rida/js/jquery-1.12.3.js"></script>
</body>
</html>
