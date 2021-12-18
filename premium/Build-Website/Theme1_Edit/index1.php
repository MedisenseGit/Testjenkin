<?php
ob_start();
error_reporting(0);
session_start();
$admin_id =  2030;
/*if(!empty($_POST['doc_id']) || !empty($_SESSION['user_id'])) {

	if(!empty( $_POST['doc_id'])) {
		$_SESSION['user_id'] = $_POST['doc_id'];
	}
	$admin_id = $_SESSION['user_id'];
}

if(empty($admin_id)){
	header("Location:../../index.php");
}*/

require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

$getdetails = $objQuery->mysqlSelect("*","webtemplate1_details","doc_id='".$admin_id."' and doc_type=1","","","","");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1" />
<META NAME="ROBOTS" CONTENT="INDEX, FOLLOW">
<title><?php echo $getdetails[0]['ref_name']; ?></title>
<link rel="shortcut icon" href="favicon.ico">

<!-- Google Font-->
<link href='http://fonts.googleapis.com/css?family=Roboto:400,300italic,300,100italic,100,400italic,500,500italic,700,900,900italic,700italic%7COswald:400,300,700' rel='stylesheet' type='text/css'>
<!-- Design Style -->
<link rel="stylesheet" type="text/css" href="css/scroll.css" />
<link rel="stylesheet" type="text/css" href="css/style.css" />
<!-- Icon -->
<link rel="stylesheet" type="text/css" href="css/font-awesome.css" />
<link rel="stylesheet" type="text/css" href="css/font-awesome.css" />
<!-- Portfolio Thumbnail / Slider -->
<link rel="stylesheet" type="text/css" href="css/portfolio.css" />
<link rel="stylesheet" type="text/css" href="css/carousel.css">
<!-- Responsive -->
<link rel="stylesheet" type="text/css" href="css/responsive.css" />
<!-- Pie Chart / Skills -->
<script type="text/javascript" src="js/jquery-2.0.3.min.js"></script>
<!-- Send Email -->
<script type="text/javascript" src="js/sendemail.js"></script>
<!-- Progressbar / Skills-->
<script type="text/javascript" src="js/progressbar.js"></script>
<!-- Portfolio-->
<script src="js/modernizr.custom.js"></script>

<style>
      #map {
        width: 100%;
        height: 400px;
        background-color: grey;
      }
    </style>

</head>
<body>
<div id="container" class="container">
  <!-- Left Menu / Logo-->
  <aside class="menu" id="menu">
    <div class="logo">
      <!-- Logo image-->
     <!-- <img src="images/logo.png" width="140" height="140" alt=""/>  -->
     <img src="theme1ImageAttach/<?php echo $getdetails[0]['webtemplate1_deatil_id']; ?>/<?php echo $getdetails[0]['about_main_logo']; ?>" width="140" height="140" alt=""/>
      <!-- Logo name-->
      <span><?php echo $getdetails[0]['home_username']; ?></span></div>
    <!-- Mobile Navigation-->
    <a href="#menu1" class="menu-link"></a>
    <!-- Left Navigation-->
    <nav id="menu1" role="navigation"> <a href="#chapterintroduction"><span id="link_introduction" class="active">Home</span></a> <a href="#chapterabout"><span id="link_about">About</span></a> <a href="#chapterskills"><span id="link_skills">SPECIALTIES</span></a> <a href="#chapterexperience"><span id="link_experience">Education</span></a> <a href="#chaptereducation"><span id="link_education">AWARDS & ACHIEVEMENTS</span></a> <a href="#chapterportfolio"><span id="link_portfolio">Hospitals</span></a><a href="#chaptercontact"><span id="link_contact">Contact</span></a><a href="#chapterblogs"><span id="link_blog">Blog</span></a></nav>
   <!-- <div class="social"> <a href="https://www.facebook.com" target="_blank" class="facebook"><i class="fa fa-facebook"></i></a> <a href="https://twitter.com" target="_blank" class="twitter"><i class="fa fa-twitter"></i></a> <a href="https://plus.google.com" target="_blank" class="google-plus"><i class="fa fa-google-plus"></i></a> </div>
  -->  <div class="copyright"> © <?php echo $getdetails[0]['home_username']; ?>.<br>
      All Rights Reserved. </div>
  </aside>
  <!-- Go to top link for mobile device -->
  <a href="#menu" class="totop-link">Go to the top</a>
  <div class="content-scroller">
    <div class="content-wrapper">

      <!-- Introduction -->
      <article class="content introduction" id="chapterintroduction">
	  <form method="post" enctype="multipart/form-data" name="frmAddHome" id="frmAddHome" action="add_webdetails.php">
	  <input type="hidden" id="doc_id" name="doc_id" value="<?php echo $admin_id; ?>"  placeholder="" required class="form-control typeahead_1">

        <div class="inner">
         <h2> <span>HEllo, I'm </span><br>
		  <input type="text" id="se_doc_name" name="se_doc_name" value="Dr.AAAA"   required class="form-control typeahead_1" />

		  <input id="se_doc_designation" name="se_doc_designation" value="cardiologist"  required type="text" />
		 </h2>

		 <h4> <label class="buttonsmall">Upload Image1</label>
		    <label class="buttonsmall"> <input type="file" name="txtPhoto" id="txtPhoto" /></label> </h4> <br>

		 <h4>  <label class="buttonsmall">Upload Image2</label>
		  <label class="buttonsmall"> <input type="file" name="txtPhoto1" id="txtPhoto1" /></label> </h4>  <br>

		<h4> <label class="buttonsmall">Upload Image3</label>
				<label class="buttonsmall"> <input type="file" name="txtPhoto2" id="txtPhoto2" /></label> </h4> <br>

		<h4> <label class="buttonsmall">Thumbnail Image</label>
			<label class="buttonsmall"> <input type="file" name="txtPhoto3" id="txtPhoto3" /></label> </h4> <br>

		<!-- <label><a href="#" class="button">Save Details</a>  <label> -->
		<button type="submit" name="add_home" id="add_home" class="button"> Save Details </button>
		</div>

	   <div id="owl-demo" class="owl-carousel">
           <div class="item"><img src="theme1ImageAttach/<?php echo $getdetails[0]['webtemplate1_deatil_id']; ?>/<?php echo $getdetails[0]['home_image1']; ?>" alt="" /> </div>
          <div class="item"><img src="theme1ImageAttach/<?php echo $getdetails[0]['webtemplate1_deatil_id']; ?>/<?php echo $getdetails[0]['home_image2']; ?>" alt="" /></div>
          <div class="item"><img src="theme1ImageAttach/<?php echo $getdetails[0]['webtemplate1_deatil_id']; ?>/<?php echo $getdetails[0]['home_image3']; ?>" alt="" /></div>
        </div>
	 </form>
      </article>

      <!-- About -->
      <article class="content about white-bg" id="chapterabout" style=" background: url(theme1ImageAttach/<?php echo $getdetails[0]['webtemplate1_deatil_id']; ?>/sample.jpg)no-repeat 100% 0 scroll;" >
	    <form method="post" enctype="multipart/form-data" name="frmAddAbout" id="frmAddAbout" action="add_webdetails.php">
		 <input type="hidden" id="doc_id" name="doc_id" value="<?php echo $admin_id; ?>"  placeholder="" required class="form-control typeahead_1">

        <div class="inner">
          <h2>About  </h2>
          <div class="title-divider"></div>

          <div class="about-con">
            <ul>
              <li>Name: <input type="text" id="about_name" name="about_name"  value="Dr.AAA"></li>
              <li>Speciality:  <input type="text" id="about_phone" name="about_phone" value="cardiologist" ></li>
              <li>Practicing In: <input type="text" id="about_email" name="about_email"  value="10+" ></li>

            </ul>
            <h3>Professional Profile</h3>
           <textarea class="form-control"  id="about_profprofile" name="about_profprofile" >Dr.AAA is a seasoned expert in all major specialties including but not limited to Emergency Medicine, Endocrinology, infectious diseases including Immunocompromised Diseases, Cardiac Care, and Intensive Cardiac Care Unit. His main interest has been lifestyle disease, diabetes and its complications. Diabetes is ever increasing in our country and Dr AAA has been named as one of the best Diabetologist in Place-name.During his professional tenure, Dr.AAA has to his credit clinical research papers, presentations and clinical drug trials conducted. Also he has done considerable study to emerge as one of the best diabetes doctor in Place-Name during the X decades of clinical practice. Managing TYPE1 and TYPE2 diabetes and its related complications like, IHD, diabetic kidney disease, peripheral neuropathy, prevention of diabetic retinopathy, prevention and management of strokes, diabetic ketoacidosis, hyperosmolar coma are some aspects he specializes in </textarea>
			<br />
		   <label><br /><b>Upload Signature </b> <img src="http://localhost/Doctor_template/template1demo/template1/theme1ImageAttach/1/signature.jpg" class="signature" alt=""  />  </label>
		<label class="buttonsmall"> <br /><input type="file" name="txtSignature" id="txtSignature" /></label><br>



		   <label><br /><b>Upload Background Image </b>  </label>

			<label class="buttonsmall"> <br /><input type="file" name="txtBgImage" id="txtBgImage" /></label>
			<br>

			 <label><br /><b>Upload Main Logo </b>  </label>

			<label class="buttonsmall"> <br /><input type="file" name="txtMainLogo" id="txtMainLogo" /></label>
			<br>

			<button type="submit" name="add_about" id="add_about" class="button"> Save Details </button>
			</div>

        </div>

		</form>
      </article>

      <!-- Skills -->
      <article class="content skills gray-bg" id="chapterskills">
	    <form method="post" enctype="multipart/form-data" name="frmAddAbout" id="frmAddAbout" action="add_webdetails.php">
		 <input type="hidden" id="doc_id" name="doc_id" value="<?php echo $admin_id; ?>"  placeholder="" required class="form-control typeahead_1">

        <div class="inner">
          <h2>Specialties</h2>
          <div class="title-divider"></div>
          <h3>Surgical Skills</h3>
		   <textarea class="form-control"  id="spec_surgical" name="spec_surgical" ><?php echo $getdetails[0]['spec_surgical']; ?></textarea>

		  <div class="skills-con">
            <div class="container-sub margin-top50">
              <div class="row">
                <div class="col-6 margin-bottom50">
                  <div class="col-6"><div class="circle-text">
				<input style="width: 70px; padding: 10px; font-size:24px;" type="text" maxlength="4" id="spec_surgical1_percent" name="spec_surgical1_percent"  value="40%" ></input>
			   </div></div>
                  <div class="col-6 chart-text">
                    <h4>
					 <textarea class="form-control" rows="2" maxlength="30" id="spec_surgical1_name" name="spec_surgical1_name"   >Complete Diabetes Management</textarea> </h4>
                  </div>
                </div>
                <div class="col-6 margin-bottom50">
                  <div class="col-6">
				  <div class="circle-text">
				<input style="width: 70px; padding: 10px; font-size:24px;" maxlength="4" type="text" id="spec_surgical2_percent" name="spec_surgical2_percent"  value="30%" ></input>
			   </div>
				  </div>
                  <div class="col-6 chart-text">
                    <h4><textarea class="form-control" rows="2" maxlength="30" id="spec_surgical2_name" name="spec_surgical2_name"   >Holistic Pain Management </textarea> </h4>
                  </div>
                </div>
                <div class="col-6">
                  <div class="col-6">
				   <div class="circle-text">
				<input style="width: 70px; padding: 10px; font-size:24px;" maxlength="4" type="text" id="spec_surgical3_percent" name="spec_surgical3_percent"  value="20%" ></input>
			   </div>
				  </div>
                  <div class="col-6 chart-text">
                    <h4><textarea class="form-control" rows="2" maxlength="30" id="spec_surgical3_name" name="spec_surgical3_name"  >Preventive cardiology </textarea></h4>
                  </div>
                </div>
                <div class="col-6">
                  <div class="col-6">
				   <div class="circle-text">
				<input style="width: 70px; padding: 10px; font-size:24px;" maxlength="4" type="text" id="spec_surgical4_percent" name="spec_surgical4_percent"  value="10%"  ></input>
			   </div></div>
                  <div class="col-6 chart-text">
                    <h4><textarea class="form-control" rows="2" maxlength="30" id="spec_surgery4_name" name="spec_surgery4_name"  >Geriatrics</textarea></h4>
                  </div>
                </div>
              </div>
            </div>
            <div class="full-divider"></div>
            <div class="container-sub skill-list">
              <div class="row">
                <h3>Specialities</h3>
               <textarea rows="3"  id="spec_specialities" name="spec_specialities" >General wellbeing with preventive care, pain management, Pre-diabetes, impaired glucose tolerance, early diabetics, pre cardiac indications and subtle early complications like dyslipidemia management are areas where he has achieved remarkable results due to all round therapeutic approach.</textarea>

			   <div class="col-4 margin-top10">
                  <ul>
                    <li><input id="specialty1" name="specialty1" maxlength="25"  value="<?php echo $getdetails[0]['spec_speciality1']; ?>" placeholder="Vitreoretinal surgery" ></li>
                    <li><input id="specialty2" name="specialty2" maxlength="25"  value="<?php echo $getdetails[0]['spec_speciality2']; ?>" placeholder="Ophthalmic Lasers" ></li>
                    <li><input id="specialty3" name="specialty3" maxlength="25"  value="<?php echo $getdetails[0]['spec_speciality3']; ?>" placeholder="Uveal diseases" ></li>
                    <li><input id="specialty4" name="specialty4" maxlength="25"  value="<?php echo $getdetails[0]['spec_speciality4']; ?>" placeholder="Macular Hole surgery" ></li>
                  </ul>
                </div>
                <div class="col-4 margin-top10">
                  <ul>
                    <li><input id="specialty5" name="specialty5" maxlength="25" value="<?php echo $getdetails[0]['spec_speciality5']; ?>" placeholder="Anti-VEGF injections" ></li>
                    <li><input id="specialty6" name="specialty6" maxlength="25" value="<?php echo $getdetails[0]['spec_speciality6']; ?>" placeholder="Age Related Macular Degeneration" ></li>
                    <li><input id="specialty7" name="specialty7" maxlength="25" value="<?php echo $getdetails[0]['spec_speciality7']; ?>" placeholder="Retinal Detachment surgery" ></li>
                    <li><input id="specialty8" name="specialty8" maxlength="25" value="<?php echo $getdetails[0]['spec_speciality8']; ?>" placeholder="Myopic Traction Maculopathy" ></li>
                  </ul>
                </div>
                <div class="col-4 margin-top10">
                  <ul>
                    <li><input id="specialty9" name="specialty9" maxlength="25" value="<?php echo $getdetails[0]['spec_speciality9']; ?>" placeholder="Pathological Myopia" ></li>
                    <li><input id="specialty10" name="specialty10" maxlength="25" value="<?php echo $getdetails[0]['spec_speciality10']; ?>" placeholder="Macular Hole Retinal Detachment" ></li>
                    <li><input id="specialty11" name="specialty11" maxlength="25" value="<?php echo $getdetails[0]['spec_speciality11']; ?>" placeholder="Fluorescein Angiography" ></li>
                    <li><input id="specialty12" name="specialty12" maxlength="25" value="<?php echo $getdetails[0]['spec_speciality12']; ?>" placeholder="Stem Cell treatment for RP and dry AMD" ></li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="full-divider"></div>
            <div class="container-sub">
              <div class="row">
                <h3>PATIENTS DEMOGRAPHICS</h3>
                <textarea rows="3" id="se_pat_demography" name="se_pat_demography" maxlength="250" placeholder="Dr. Bharath has commendable skills in vitreoretinal surgery and is a specialist in diseases of the retina, vitreous and uvea and their management. His academic disciplines include"  ><?php echo $getdetails[0]['spec_pat_demography']; ?></textarea>
			    <!-- <div class="progressbar-main margin-top50">
                  <div class="progress-bar-description">Domestic Patients Treated</div>
                  <div id="progressBar" class="progress">
                     <div class="progress-value"></div>

                  </div>
                </div>  -->

				<div class="row" >
					 <!--<div class="container-sub skill-list"><strong>Domestic Patients Treated </strong> -->
					 <div class="container-sub skill-list"><strong><input type="text" id="se_pat_domestic_title" name="se_pat_domestic_title"  value="Domestic patients treated"></strong>
						<input style="width: 70px; padding: 10px; margin-left:20px; font-size:24px;" maxlength="4" type="text" value="70%" name="se_pat_domestic" class="form-control">
						</div>
                 </div>
				 <div class="row" >
					<!-- <div class="container-sub skill-list"><strong>International Patients Treated </strong>  -->
					<div class="container-sub skill-list"><strong><input type="text" id="se_pat_international_title" name="se_pat_international_title"  value="International patients" ></strong>

						<input style="width: 70px; padding: 10px; margin-left:20px; font-size:24px;" maxlength="4" type="text" value="15%" name="se_pat_international" class="form-control">
						</div>
                 </div>
				 <div class="row" >
					<!-- <div class="container-sub skill-list"><strong>Patients Treated as Community Service </strong> -->
					<div class="container-sub skill-list"><strong><input type="text" id="se_pat_communityservice_title" name="se_pat_communityservice_title"  value="Patients Treated as Community Service" ></strong>

						<input style="width: 70px; padding: 10px; margin-left:20px; font-size:24px;" maxlength="4" type="text" value="15%" name="se_pat_communityservice" class="form-control">
						</div>
                 </div>

               <!-- <div class="progressbar-main margin-top40">
                  <div class="progress-bar-description">International Patients Treated</div>
                  <div id="progressBar2" class="progress">
                    <div class="progress-value"></div>
                  </div>
                </div>
                <div class="progressbar-main margin-top40">
                  <div class="progress-bar-description">Patients Treated as Community Service</div>
                  <div id="progressBar3" class="progress">
                    <div class="progress-value"></div>
                  </div>
                </div> -->
              </div>
            </div>
			<br>
			<button type="submit" name="add_specialty" id="add_specialty" class="button"> Save Details </button>
          </div>
        </div>
		</form>
      </article>

      <!-- Experience -->
      <article class="content experience white-bg" id="chapterexperience">
	   <form method="post" name="frmAddEducation" id="frmAddEducation" action="add_webdetails.php">
	    <input type="hidden" id="doc_id" name="doc_id" value="<?php echo $admin_id; ?>"  placeholder="" required class="form-control typeahead_1">

        <div class="inner">
          <h2>Education</h2>
          <div class="title-divider"></div>
          <h3>Just My Education </h3>
         <textarea rows="10"  name="edu_title" >Dr. AAA has completed his bachelors and masters in Medicine from one of the premier medical institutions in India with following credentials. In addition to being exceptional in academics, he takes time out from his busy schedule by contributing to continuous medical education by being part of scientific committees. Abreast with latest medical advancements and cutting edge scientific knowledge helps his patients get the best medical treatment. </textarea>
			 <div class="experience-con">
            <div class="container-sub">
              <div class="full-divider"></div>
              <div class="row">
                <div class="experience-details">
                  <div class="col-6 margin-bottom50 margin-top50">
                    <div class="col-3 icon-block"><i class="fa fa-graduation-cap"></i></div>
                    <div class="flot-left">
                      <h5><input type="text" maxlength="40" id="edu_university1" name="edu_university1" value="Grant Medical College"></h5>
                      <h4><input type="text" maxlength="20" id="edu_stream1" name="edu_stream1" value="M.D"></h4>
                      <h4><input type="text" maxlength="15" id="edu_year1" name="edu_year1" value="1975 – 1980"  ></h4></div>
                  </div>
                  <div class="col-6 margin-bottom50 margin-top50 no-margin-top"> <textarea rows="5" cols="50" maxlength="200" name="edu_address1"   >He achieved Doctor of Medicine (MD) from Grant Medical College,JJ Hospital,(Mumbai, University)</textarea> </div>
                </div>
                <div class="full-divider"></div>
                <div class="experience-details">
                  <div class="col-6 margin-bottom50 margin-top50">
                    <div class="col-3 icon-block"><i class="fa fa-graduation-cap"></i></div>
                    <div class="flot-left">
                     <h5><input type="text" maxlength="40" id="edu_university2" name="edu_university2" value="Grant Medical College" ></h5>
                      <h4><input type="text" maxlength="20" id="edu_stream2" name="edu_stream2" value="M.B.B.S"></h4>
                      <h4><input type="text" maxlength="15" id="edu_year2" name="edu_year2" value="1981 – 1984"></h4></div>
                  </div>
				<div class="col-6 margin-bottom50 margin-top50 no-margin-top"> <textarea rows="5" cols="50" maxlength="200" name="edu_address2" >He completed his Bachelors of Medicine,Bachelors of Surgery (MBBS) from  Grant Medical College,JJ Hospital,(Mumbai, University).</textarea> </div>
               </div>
                <div class="full-divider"></div>
                <div class="experience-details">
                  <div class="col-6 margin-bottom50 margin-top50">
                    <div class="col-3 icon-block"><i class="fa fa-graduation-cap"></i></div>
                    <div class="flot-left">
                    <h5><input type="text" maxlength="40" id="edu_university3" name="edu_university3" value="Grant Medical College"></h5>
                      <h4><input type="text" maxlength="20" id="edu_stream3" name="edu_stream3" value="M.B.B.S"></h4>
                      <h4><input type="text" maxlength="15" id="edu_year3" name="edu_year3" value="1985 – 1987" ></h4></div>
                  </div>
                <div class="col-6 margin-bottom50 margin-top50 no-margin-top"> <textarea rows="5" cols="50" maxlength="200" name="edu_address3" >He completed his Bachelors of Medicine,Bachelors of Surgery (MBBS) from  Grant Medical College,JJ Hospital,(Mumbai, University)</textarea> </div>
               </div>
                <div class="full-divider"></div>
                <div class="experience-details">
                  <div class="col-6 margin-bottom50 margin-top50">
                    <div class="col-3 icon-block"><i class="fa fa-graduation-cap"></i></div>
                    <div class="flot-left">
                     <h5><input type="text" maxlength="40" id="edu_university4" name="edu_university4" value="Grant Medical College"  ></h5>
                      <h4><input type="text" maxlength="20" id="edu_stream4" name="edu_stream4" value="M.B.B.S" ></h4>
                      <h4><input type="text"  maxlength="15" id="edu_year4" name="edu_year4" value="1991 – 1994" ></h4></div>
                  </div>
                 <div class="col-6 margin-bottom50 margin-top50 no-margin-top"> <textarea rows="5" cols="50" maxlength="200" name="edu_address4" >He completed his Bachelors of Medicine,Bachelors of Surgery (MBBS) from  Grant Medical College,JJ Hospital,(Mumbai, University)</textarea> </div>
               </div>
                <div class="full-divider"></div>
                <div class="experience-details">
                  <div class="col-6 margin-bottom50 margin-top50">
                    <div class="col-3 icon-block"><i class="fa fa-graduation-cap"></i></div>
                    <div class="flot-left">
                     <h5><input type="text" maxlength="40" id="edu_university5" name="edu_university5" value="Grant Medical College"   ></h5>
                      <h4><input type="text" maxlength="20" id="edu_stream5" name="edu_stream5" value="M.B.B.S" ></h4>
                      <h4><input type="text" maxlength="15" id="edu_year5" name="edu_year5" value="2017"></h4></div>
                  </div>
                   <div class="col-6 margin-bottom50 margin-top50 no-margin-top"> <textarea rows="5" cols="50" maxlength="200" name="edu_address5" >He completed his Bachelors of Medicine,Bachelors of Surgery (MBBS) from  Grant Medical College,JJ Hospital,(Mumbai, University)</textarea> </div>
              </div>
              </div>
            </div>
			<br>
			<button type="submit" name="add_education" id="add_education" class="button"> Save Details </button>
          </div>
        </div>
		</form>
      </article>

      <!-- Education -->
      <article class="content education gray-bg" id="chaptereducation">
	   <form method="post" name="frmAddAwards" id="frmAddAwards" action="add_webdetails.php">
	    <input type="hidden" id="doc_id" name="doc_id" value="<?php echo $admin_id; ?>"  placeholder="" required class="form-control typeahead_1">

        <div class="inner">
          <h2>AWARDS & ACHIEVEMENTS</h2>
          <div class="title-divider"></div>
          <h3><input type="text" maxlength="20" id="award_title" name="award_title" value="<?php echo $getdetails[0]['award_title']; ?>" placeholder="33+ years experience" ></h3>
          <textarea rows="3" cols="50" name="award_subtitle"  id="award_subtitle"  maxlength="200" >With Number-Exp Dr.AAA has been recognised in the following fields for showing knowledge, commitment and most of all formidable treatment and care. </textarea>
		 <div class="education-con">
            <div class="container-sub">
              <div class="full-divider"></div>
              <div class="row">
                <div class="education-details">
                  <div class="col-6 margin-bottom50 margin-top50">
                    <div class="col-3 icon-block"><i class="fa fa-trophy"></i></div>
                    <div class="flot-left">
                      <h4><input type="text" maxlength="20" id="award_name1" name="award_name1" value="Best Doctor "></h4> </div>
                  </div>
                 <div class="col-6 margin-bottom50 margin-top50 no-margin-top"> <textarea rows="10"  maxlength="500" name="awardinfo1" >Dr. AAA is considered as one of the leading names in the field of preventive cardiology and complete diabetes management.  His effort towards personalised medicine to address the cause for a better effect in patients is benefitting the public and for a better healthier India. </textarea> </div>
                </div>
                <div class="full-divider"></div>
                <div class="education-details">
                  <div class="col-6 margin-bottom50 margin-top50">
                    <div class="col-3 icon-block"><i class="fa fa-trophy"></i></div>
                    <div class="flot-left">
                     <h4><input type="text" maxlength="20" id="award_name2" name="award_name2" value="Best Doctor ">"</h4> </div>
                  </div>
                <div class="col-6 margin-bottom50 margin-top50 no-margin-top"> <textarea rows="10"  maxlength="500" name="awardinfo2" >Dr. AAA is considered as one of the leading names in the field of preventive cardiology and complete diabetes management.  His effort towards personalised medicine to address the cause for a better effect in patients is benefitting the public and for a better healthier India. </textarea> </div>
                 </div>
                <div class="full-divider"></div>
                <div class="education-details">
                  <div class="col-6 margin-bottom50 margin-top50">
                    <div class="col-3 icon-block"><i class="fa fa-trophy"></i></div>
                    <div class="flot-left">
                       <h4><input type="text"  maxlength="20" id="award_name3" name="award_name3" value="Best Doctor" ></h4> </div>
                  </div>
                <div class="col-6 margin-bottom50 margin-top50 no-margin-top"> <textarea rows="10"  maxlength="500" name="awardinfo3" >Dr. AAA is considered as one of the leading names in the field of preventive cardiology and complete diabetes management.  His effort towards personalised medicine to address the cause for a better effect in patients is benefitting the public and for a better healthier India. </textarea> </div>
                 </div>
				 <div class="full-divider"></div>
                <div class="education-details">
                  <div class="col-6 margin-bottom50 margin-top50">
                    <div class="col-3 icon-block"><i class="fa fa-trophy"></i></div>
                    <div class="flot-left">
                       <h4><input type="text"  maxlength="20" id="award_name4" name="award_name4" value="Best Doctor" ></h4> </div>
                  </div>
                <div class="col-6 margin-bottom50 margin-top50 no-margin-top"> <textarea rows="10"  maxlength="500" name="awardinfo4"  >Dr. AAA is considered as one of the leading names in the field of preventive cardiology and complete diabetes management.  His effort towards personalised medicine to address the cause for a better effect in patients is benefitting the public and for a better healthier India.</textarea> </div>
                 </div>
				 <div class="full-divider"></div>
                <div class="education-details">
                  <div class="col-6 margin-bottom50 margin-top50">
                    <div class="col-3 icon-block"><i class="fa fa-trophy"></i></div>
                    <div class="flot-left">
                       <h4><input type="text"  maxlength="20" id="award_name5" name="award_name5" value="Best Doctor"></h4> </div>
                  </div>
                <div class="col-6 margin-bottom50 margin-top50 no-margin-top"> <textarea rows="10"  maxlength="500" name="awardinfo5"  >Dr. AAA is considered as one of the leading names in the field of preventive cardiology and complete diabetes management.  His effort towards personalised medicine to address the cause for a better effect in patients is benefitting the public and for a better healthier India.</textarea> </div>
                 </div>
				  <div class="full-divider"></div>
                <div class="education-details">
                  <div class="col-6 margin-bottom50 margin-top50">
                    <div class="col-3 icon-block"><i class="fa fa-trophy"></i></div>
                    <div class="flot-left">
                       <h4><input type="text"  maxlength="20" id="award_name6" name="award_name6" value="Best Doctor"  ></h4> </div>
                  </div>
                <div class="col-6 margin-bottom50 margin-top50 no-margin-top"> <textarea rows="10"  maxlength="500" name="awardinfo6"  >Dr. AAA is considered as one of the leading names in the field of preventive cardiology and complete diabetes management.  His effort towards personalised medicine to address the cause for a better effect in patients is benefitting the public and for a better healthier India.</textarea> </div>
                 </div>

              </div>

			  <br>
			<button type="submit" name="add_awards" id="add_awards" class="button"> Save Details </button>

            </div>
          </div>
        </div>
		</form>
      </article>

      <!-- Pportfolio -->
      <article class="content portfolio white-bg" id="chapterportfolio">
	  <form method="post" enctype="multipart/form-data" name="frmAddHospital" id="frmAddHospital" action="add_webdetails.php">
	   <input type="hidden" id="doc_id" name="doc_id" value="<?php echo $admin_id; ?>"  placeholder="" required class="form-control typeahead_1">

        <div class="inner">
          <h2>Hospitals</h2>
          <div class="title-divider"></div>
         <!-- <h3>Our portfolio features a variety of projects and services</h3> -->
          <textarea rows="5" cols="50" maxlength="1000" name="hosp_title" placeholder="Dr. Rajendra Prasad Centre for Ophthalmic Sciences has been recognized as the Apex Organisation by the Government of India under the National Programme for the Control of Blindness. "  ><?php echo $getdetails[0]['hospital_title']; ?></textarea>
		 <div class="portfolio-con">
            <div class="container-sub margin-top50">
              <div class="row">
                <div id="grid-gallery" class="grid-gallery">


			<label><span>Hospital Information 1</span><br>
			<input type="text" id="hosp_title1" name="hosp_title1"  maxlength="30" value="<?php echo $getdetails[0]['hosp_photo1_title']; ?>"  placeholder="Title1"  class="form-control typeahead_1">
			<input id="hosp_subtitle1" name="hosp_subtitle1"  maxlength="200" value="<?php echo $getdetails[0]['hosp_photo1_subtitle']; ?>" placeholder="Description1"  type="text">
			</label>  <br> </br>
			<label class="buttonsmall"> <input type="file" name="txtHospPhoto1" id="txtHospPhoto1" /></label>
			<div class="full-divider"></div>

			<br>
			</br>
			<label><span>Hospital Information 2</span><br>
			<input type="text" id="hosp_title2" name="hosp_title2"  maxlength="30" value="<?php echo $getdetails[0]['hosp_photo2_title']; ?>"  placeholder="Title2"  class="form-control typeahead_1">
			<input id="hosp_subtitle2" name="hosp_subtitle2"  maxlength="200" value="<?php echo $getdetails[0]['hosp_photo2_subtitle']; ?>" placeholder="Description2"  type="text">
			</label>  <br> </br>
			<label class="buttonsmall"> <input type="file" name="txtHospPhoto2" id="txtHospPhoto2" /></label>
			<div class="full-divider"></div>

			<br> </br>
			<label><span>Hospital Information 3</span><br>
			<input type="text" id="hosp_title3" name="hosp_title3"  maxlength="30" value="<?php echo $getdetails[0]['hosp_photo3_title']; ?>"  placeholder="Title3"  class="form-control typeahead_1">
			<input id="hosp_subtitle3" name="hosp_subtitle3"  maxlength="200" value="<?php echo $getdetails[0]['hosp_photo3_subtitle']; ?>" placeholder="Description3"  type="text">
			</label>  <br> </br>
			<label class="buttonsmall"> <input type="file" name="txtHospPhoto3" id="txtHospPhoto3" /></label>
			<div class="full-divider"></div>

			<br> </br>
			<label><span>Hospital Information 4</span><br>
			<input type="text" id="hosp_title4" name="hosp_title4"  maxlength="30" value="<?php echo $getdetails[0]['hosp_photo4_title']; ?>"  placeholder="Title4"  class="form-control typeahead_1">
			<input id="hosp_subtitle4" name="hosp_subtitle4"  maxlength="200" value="<?php echo $getdetails[0]['hosp_photo4_subtitle']; ?>" placeholder="Description4"  type="text">
			</label>  <br> </br>
			<label class="buttonsmall"> <input type="file" name="txtHospPhoto4" id="txtHospPhoto4" /></label>
			<div class="full-divider"></div>

			<br> </br>
			<label><span>Hospital Information 5</span><br>
			<input type="text" id="hosp_title5" name="hosp_title5"  maxlength="30" value="<?php echo $getdetails[0]['hosp_photo5_title']; ?>"  placeholder="Title5"  class="form-control typeahead_1">
			<input id="hosp_subtitle5" name="hosp_subtitle5"  maxlength="200" value="<?php echo $getdetails[0]['hosp_photo5_subtitle']; ?>" placeholder="Description5" type="text">
			</label>  <br> </br>
			<label class="buttonsmall"> <input type="file" name="txtHospPhoto5" id="txtHospPhoto4" /></label>
			<div class="full-divider"></div>


			<br>
			<button type="submit" name="add_hospital" id="add_hospital" class="button"> Save Details </button>


                <!--<section class="grid-wrap">
                    <ul class="grid">
                      <li>
                        <figure> <img src="http://localhost/Doctor_template/template1demo/template1/theme1ImageAttach/1/h2.jpg" alt=""/>
                          <figcaption>
                            <div class="figcaption-details"> <img src="images/icon-plus.png" height="82" width="82" alt="" />
                              <h3><?php echo $getdetails[0]['hosp_photo1_title']; ?></h3>
                              <span><?php echo $getdetails[0]['hosp_photo1_subtitle']; ?></span> </div>
                          </figcaption>
                        </figure>
                      </li>
                      <li>
                        <figure> <img src="http://localhost/Doctor_template/template1demo/template1/theme1ImageAttach/1/h3.jpg" alt=""/>
                          <figcaption>
                            <div class="figcaption-details"> <img src="images/icon-plus.png" height="82" width="82" alt="" />
                              <h3><?php echo $getdetails[0]['hosp_photo2_title']; ?></h3>
                              <span><?php echo $getdetails[0]['hosp_photo2_subtitle']; ?></span> </div>
                          </figcaption>
                        </figure>
                      </li>
                      <li>
                        <figure> <img src="http://localhost/Doctor_template/template1demo/template1/theme1ImageAttach/1/h4.png" alt=""/>
                          <figcaption>
                            <div class="figcaption-details"> <img src="images/icon-plus.png" height="82" width="82" alt="" />
                              <h3><?php echo $getdetails[0]['hosp_photo3_title']; ?></h3>
                              <span><?php echo $getdetails[0]['hosp_photo3_subtitle']; ?></span> </div>
                          </figcaption>
                        </figure>
                      </li>
                      <li>
                        <figure> <img src="http://placehold.it/700x475" alt=""/>
                          <figcaption>
                            <div class="figcaption-details"> <img src="images/icon-plus.png" height="82" width="82" alt="" />
                              <h3>Fashion and You</h3>
                              <span>APPS and Web Design</span> </div>
                          </figcaption>
                        </figure>
                      </li>
                      <li>
                        <figure> <img src="http://placehold.it/700x475" alt=""/>
                          <figcaption>
                            <div class="figcaption-details"> <img src="images/icon-plus.png" height="82" width="82" alt="" />
                              <h3>Whole Food Flour</h3>
                              <span>Branding and Identity</span> </div>
                          </figcaption>
                        </figure>
                      </li>
                    </ul>
                  </section>



                 <section class="slideshow">
                    <ul>
                      <li>
                        <figure>
                          <figcaption>
                            <h3>The Flavour Restaurant</h3>
                            <span>Website Design & Development</span>
                            <p>Kale chips lomo biodiesel stumptown Godard Tumblr, mustache sriracha tattooed cray aute slow-carb placeat delectus. Letterpress asymmetrical fanny pack art party est pour-over skateboard anim quis, 						ullamco craft beer.</p>
                          </figcaption>
                          <div id="owl-demo1" class="owl-carousel">
                            <div class="item"><img src="http://placehold.it/700x475" alt="" /></div>
                            <div class="item"><img src="http://placehold.it/700x475" alt="" /></div>
                            <div class="item"><img src="http://placehold.it/700x475" alt="" /></div>
                            <div class="item"><img src="http://placehold.it/700x475" alt="" /></div>
                            <div class="item"><img src="http://placehold.it/700x475" alt="" /></div>
                          </div>
                        </figure>
                      </li>
                      <li>
                        <figure>
                          <figcaption>
                            <h3>Herbal Beauty Salon</h3>
                            <span>Photography</span>
                            <p>Kale chips lomo biodiesel stumptown Godard Tumblr, mustache sriracha tattooed cray aute slow-carb placeat delectus. Letterpress asymmetrical fanny pack art party est pour-over skateboard anim quis, ullamco craft beer.</p>
                          </figcaption>
                          <img src="http://placehold.it/700x475" alt=""/></figure>
                      </li>
                      <li>
                        <figure>
                          <figcaption>
                            <h3>Kayra Modelleri</h3>
                            <span>Branding and Identity</span>
                            <p>Kale chips lomo biodiesel stumptown Godard Tumblr, mustache sriracha tattooed cray aute slow-carb placeat delectus. Letterpress asymmetrical fanny pack art party est pour-over skateboard anim quis, ullamco craft beer.</p>
                          </figcaption>
                          <div id="owl-demo2" class="owl-carousel">
                             <div class="item"><img src="http://placehold.it/700x475" alt="" /></div>
                            <div class="item"><img src="http://placehold.it/700x475" alt="" /></div>
                            <div class="item"><img src="http://placehold.it/700x475" alt="" /></div>
                            <div class="item"><img src="http://placehold.it/700x475" alt="" /></div>
                            <div class="item"><img src="http://placehold.it/700x475" alt="" /></div>
                          </div>
                        </figure>
                      </li>
                      <li>
                        <figure>
                          <figcaption>
                            <h3>Fashion and You</h3>
                            <span>APPS and Web Design</span>
                            <p>Kale chips lomo biodiesel stumptown Godard Tumblr, mustache sriracha tattooed cray aute slow-carb placeat delectus. Letterpress asymmetrical fanny pack art party est pour-over skateboard anim quis, ullamco craft beer.</p>
                          </figcaption>
                          <iframe width="854" height="480" src="//www.youtube.com/embed/ZwzY1o_hB5Y" frameborder="0" allowfullscreen></iframe></figure>
                      </li>
                      <li>
                        <figure>
                          <figcaption>
                            <h3>Whole Food Flour</h3>
                            <span>Branding and Identity</span>
                            <p>Kale chips lomo biodiesel stumptown Godard Tumblr, mustache sriracha tattooed cray aute slow-carb placeat delectus. Letterpress asymmetrical fanny pack art party est pour-over skateboard anim quis, ullamco craft beer.</p>
                          </figcaption>
                          <img src="http://placehold.it/700x475" alt=""/></figure>
                      </li>
                    </ul>
                    <nav> <span class="fa nav-prev"></span> <span class="fa nav-next"></span> <span class="fa nav-close"></span> </nav>
                  </section> -->
                </div>
              </div>
            </div>

          </div>
        </div>
		</form>
      </article>

      <!-- Contact -->
      <article class="content contact gray-bg" id="chaptercontact">
	   <form method="post" enctype="multipart/form-data" name="frmAddContact" id="frmAddContact" action="add_webdetails.php">
	    <input type="hidden" id="doc_id" name="doc_id" value="<?php echo $admin_id; ?>"  placeholder="" required class="form-control typeahead_1">

        <div class="inner">
          <h2>Contact</h2>
          <div class="title-divider"></div>
          <h3>Should you need any help, feel free to approach at following coordinates</h3>
           <textarea rows="3" cols="50" name="contact_addressinfo" placeholder="Address Information"  ><?php echo $getdetails[0]['contact_address_info']; ?></textarea>
		  <div class="full-divider"></div>
          <div class="contact-con margin-top50">
            <div class="container-sub">
              <div class="row">
                <div class="contact-details">
                  <div class="col-6">
                    <div class="contact-text">
                      <div class="col-2 icon-block address"><i class="fa fa-map-marker"></i></div>
                      <div class="flot-left"> <label><input type="text"  id="contact_name" name="contact_name" value="<?php echo $getdetails[0]['contact_name']; ?>" placeholder="Name" ></label><br>
                        <input type="text"  id="contact_address_line1" maxlength="25" name="contact_address_line1" value="<?php echo $getdetails[0]['contact_add_line1']; ?>" placeholder="Address line 1" ><br>
                        <input type="text"  id="contact_address_line2" maxlength="25" name="contact_address_line2" value="<?php echo $getdetails[0]['contact_add_line2']; ?>" placeholder="Address line 2" ><br>
						<input type="text"  id="contact_address_line3" maxlength="25" name="contact_address_line3" value="<?php echo $getdetails[0]['contact_add_line3']; ?>" placeholder="Address line 3" ></div>

				   </div>
                    <div class="contact-text">
                      <div class="col-2 icon-block phone"><i class="fa fa-phone"></i></div>
                      <div class="flot-left"> <strong>Hospital Number</strong><br>
                       <input type="text"  id="contact_phone" name="contact_phone" value="<?php echo $getdetails[0]['contact_phone']; ?>" placeholder="Phone Number" > </div>
                    </div>
                    <div class="contact-text">
                      <div class="col-2 icon-block email"><i class="fa fa-envelope"></i></div>
                      <div class="flot-left"> <strong>Email</strong><br>
                        <a href="mailto:no-reply@domain.com"><input type="text"  id="contact_email" name="contact_email" value="<?php echo $getdetails[0]['contact_email']; ?>" placeholder="Email ID" ></a> </div>
                    </div>

                  </div>
                  <div class="col-6 m-margin-top30">
                    <h3>I'm also on Social Networks</h3>
                  <textarea rows="3" cols="50" name="contact_socialnetIfo" placeholder="You can get in touch with me on Social Networks below"  ><?php echo $getdetails[0]['contact_social_network']; ?></textarea>
				<!--  <div class="contact-social margin-top30"><a href="https://www.facebook.com" target="_blank"><i class="fa fa-facebook"></i></a> <a href="https://twitter.com" target="_blank"><i class="fa fa-twitter"></i></a> <a href="https://www.youtube.com" target="_blank"><i class="fa fa-youtube"></i></a><a href="https://plus.google.com" target="_blank"><i class="fa fa-google-plus"></i></a><a href="https://www.linkedin.com" target="_blank"><i class="fa fa-linkedin"></i></a> </div> -->
				 <div class="contact-social margin-top30"><a href="https://www.facebook.com" target="_blank"><i class="fa fa-facebook"></i></a> <strong> Facebook Link </strong> <input type="text"  id="contact_facebook" name="contact_facebook" value="<?php echo $getdetails[0]['contact_facebook']; ?>" placeholder="Facebook URL" ></div>
				<div class="contact-social margin-top30"><a href="https://www.linkedin.com" target="_blank"><i class="fa fa-linkedin"></i></a> <strong> Linkedin Link </strong> <input type="text"  id="contact_linkedin" name="contact_linkedin" value="<?php echo $getdetails[0]['contact_linkedin']; ?>" placeholder="Linkedin URL" ></div>
				<div class="contact-social margin-top30"><a href="https://twitter.com" target="_blank"><i class="fa fa-twitter"></i></a> <strong> Twitter Link </strong> <input type="text"  id="contact_twitter" name="contact_twitter" value="<?php echo $getdetails[0]['contact_twitter']; ?>" placeholder="Twitter URL" ></div>
                 <div class="contact-social margin-top30"><a href="https://www.youtube.com" target="_blank"><i class="fa fa-youtube"></i></a> <strong> Youtube Link </strong> <input type="text"  id="contact_youtube" name="contact_youtube" value="<?php echo $getdetails[0]['contact_youtube']; ?>" placeholder="Youtube URL" ></div>
                <div class="contact-social margin-top30"><a href="https://plus.google.com" target="_blank"><i class="fa fa-google-plus"></i></a> <strong> Google Plus Link </strong> <input type="text"  id="contact_gplus" name="contact_gplus" value="<?php echo $getdetails[0]['contact_gplus']; ?>" placeholder="GPlus URL" ></div>

                  </div>
                </div>

					<div class="contact-text">

					<input type="hidden" id="lattitudevalue" name="lat_value" value="<?php echo $getdetails[0]['contact_latitude']; ?>"  />
					<input type="hidden" id="longitudevalue" name="long_value" value="<?php echo $getdetails[0]['contact_longitude']; ?>"  />
					<div>Your Latitude:   <label id="lat_value" name="lat_value" value="<?php echo $getdetails[0]['contact_latitude']; ?>"><?php echo $getdetails[0]['contact_latitude']; ?></label></div>
					<div>Your Longitude: <label id="long_value" name="long_value" value="<?php echo $getdetails[0]['contact_longitude']; ?>"><?php echo $getdetails[0]['contact_longitude']; ?></label> </div>

					<div id="map" style="height:400px;"></div>
					</div>

					<script>
      function initMap() {
        var uluru = {lat: 21.7679, lng: 78.8718};
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 5,
		  disableDoubleClickZoom: true,
          center: uluru
        });
        var marker = new google.maps.Marker({
          position: uluru,
          map: map
        });

		  // double click event
      google.maps.event.addListener(map, 'dblclick', function(e) {
        var positionDoubleclick = e.latLng;
        marker.setPosition(positionDoubleclick);
        // if you don't do this, the map will zoom in
		 document.getElementById("lat_value").innerHTML = e.latLng.lat();
		document.getElementById("long_value").innerHTML = e.latLng.lng();

		document.getElementById("lattitudevalue").value  = e.latLng.lat();
		document.getElementById("longitudevalue").value  = e.latLng.lng();
      });
  google.maps.event.addDomListener(window, 'load', initialize);
      }

    </script>
    <script  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDbuQD_xndo_Xl53keO48KQMo0fLZ8RsxQ&callback=initMap"></script>


              </div>

			    <br>
			<button type="submit" name="add_contact" id="add_contact" class="button"> Save Details </button>

            </div>
           <!-- <div class="full-divider"></div>
            <div class="container-sub">
              <div class="row">
                <div class="contact-form">
                  <h3>Drop Me a Line</h3>
                  <form id="form1" name="form1" method="post" >
                    <input name="name" type="text" id="name" placeholder="Your Name..." />
                    <input name="email" type="text" id="email" placeholder="Your Email..." />
                    <textarea name="message" id="message" cols="45" rows="5" placeholder="Your Message..."></textarea>
                    <input type="submit" name="button" id="button" value="say hello!" >
                    <div id="successmsg" ></div>
                  </form>
                </div>
              </div>
            </div> -->
          </div>
        </div>
		</form>
      </article>

	    <!-- Blogs -->
      <article class="content skills white-bg" id="chapterblogs">
       <div class="inner">
          <h2>Blog</h2>
          <div class="title-divider"></div>

           <div class="about-con">

            <h5>We have provided a seperate section to add your Blogs </h5>
          </div>

        </div>

      </article>

      <!-- Introduction -->
      <article class="content introduction-end" id="chapterthankyou">
        <div class="inner">
          <div class="introduction-end-con margin-top50">
            <h3><strong>Andrew Smith</strong></h3>
            <div id="rotate" class="rotate">
              <div><span>awesome.</span></div>
              <div><span>invincible.</span></div>
              <div><span>unbeatable.</span></div>
              <div><span>indestructible.</span></div>
            </div>
          </div>
        </div>
      </article>
    </div>
    <!-- content-wrapper -->
  </div>
  <!-- content-scroller -->
</div>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/head.min.js"></script>
<!-- Portfolio Thumbnail -->
<script type="text/javascript" src="js/imagesloaded.min.js"></script>
<script type="text/javascript" src="js/masonry.min.js"></script>
<script type="text/javascript" src="js/class_helper.js"></script>
<script type="text/javascript" src="js/grid_gallery.js"></script>
<!-- Portfolio Grid -->
<script>
    new CBPGridGallery( document.getElementById( 'grid-gallery' ) );
</script>
<!-- Portfolio Slider-->
<script type="text/javascript"  src="js/carousel.js"></script>
<script type="text/javascript" src="js/jquery.easypiechart.js"></script>
<script type="text/javascript" src="js/text.rotator.js"></script>
<!-- Page Scrolling -->
<script>
head.js(
		{ mousewheel : "js/jquery.mousewheel.js" },
		{ mwheelIntent : "js/mwheelIntent.js" },
		{ jScrollPane : "js/jquery.jscrollpane.min.js" },
		{ history : "js/jquery.history.js" },
		{ stringLib : "js/core.string.js" },
		{ easing : "js/jquery.easing.1.3.js" },
		{ smartresize : "js/jquery.smartresize.js" },
		{ page : "js/jquery.page.js" }
		);
</script>
<!-- Fit Video -->
<script type="text/javascript"  src="js/jquery.fitvids.js"></script>
<!-- All Javascript Component-->
<script src="js/settings.js"></script>
</body>
</html>
