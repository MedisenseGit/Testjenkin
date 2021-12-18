<?php ob_start();
 error_reporting(0);
 session_start();

 $admin_id = $_SESSION['admin_id'];
 $user_flag = $_SESSION['flag_id'];
if(empty($admin_id)){
header("Location:index.php");
}

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8" />
<title>Medisense CRM</title>
<?php include_once('support_file.php'); ?>
</head>

<body onload="noBack();" onpageshow="if (event.persisted) noBack();" onunload="">
  


<?php include_once('header.php'); ?>


<div class="clearall">
 <div class="wrapper">
 <script language="javaScript" src="js/validation.js"></script>
	
	<!-- HEADING SECTION -->
	<div class="firstSec">
		<div class="clearfix">

		<h1 class="headfont fl">
		<span>Manage Accounts</span></h1>	
		</div>
	</div>
	
	<!-- LOGIN SECTION -->
	<script language="JavaScript" src="js/validation.js"></script>
	
	
		
	<div class="mainSec1">
	
	<?php if($user_flag==0 || $user_flag==1 ){ ?><a href="main.php?disp=1"><?php } 
	else { ?>
	<a href="main.php?disp=6"><?php } ?><div class="manageSec fl">
		<div class="icon_patient"></div>
		<h2>View Patient Details</h2>
		<p>Manage Patient Case Sheet</p>
		</div></a>
	<?php if($user_flag==0 || $user_flag==1 ){ ?>	
		<!--<a href="Add-Doctors.php">-->
		<a href="Add-Doctors.php"><div class="manageSec fl">
		<div class="icon_pro"></div>
		<h2>Add Doctors</h2>
		<p>Manage Panel Doctors Details</p>
		</div></a>
		
		<a href="Add-Hospital.php"><div class="manageSec fl">
		<div class="icon_patient"></div>
		<h2>Add Hospital</h2>
		<p>Add New Hospital Details</p>
		</div></a>
		
		<?php if($_SESSION['flag_id']==0) { ?>
		<a href="Add-User1.php">
		<div class="manageSec fl">
		<div class="icon_user"></div>
		<h2>Create New User</h2>
		<p>Add New User</p>
		</div></a>
		<?php } ?>
		
		
		<a href="checkIn-Transaction.php">
		<div class="manageSec fl">
		<div class="icon_checkin"></div>
		<h2>Check-In Transaction</h2>
		<p>Manage Check-In Transaction</p>
		</div>
		</a>
		<?php if($user_flag==0){ ?>
		<a href="op_payment.php?disp=1">
		<div class="manageSec fl">
		<div class="icon_pay1"></div>
		<h2>Opinion Payment</h2>
		<p>To manage opinion payment to Doctor</p>
		</div>
		</a>
		<?php } ?>
		<a href="login-tracker.php?disp=1&type=3">
		<div class="manageSec fl">
		<div class="icon_login"></div>
		<h2>Practice Usage stats</h2>
		<p>You can track Practice premium & standard user</p>
		</div>
		</a> 
		
		<a href="manage_doctor_domain.php">
		<div class="manageSec fl">
		<div class="icon_pro"></div>
		<h2>Manage Doctors Domain</h2>
		<p>Manage Doctors Domain</p>
		</div>
		</a>
		
		<div class="manageSec fl">
		<div class="icon_pass"></div>
		<h2>Change Password</h2>
		<p>Change User Name & Password</p>
		</div>
		
		<a href="Add-Blog-Video.php">
		<div class="manageSec fl">
		<div class="icon_pro"></div>
		<h2>Add Blogs/ Videos</h2>
		<p>Add New Blogs or Videos</p>
		</div>
		</a>
	<?php } ?>
		
	</div>
		
  </div>
</div>
</div>






<script>
		$(function() {
			// Clickable Dropdown
			$('.click-nav > ul').toggleClass('no-js js');
			$('.click-nav .js ul').hide();
			$('.click-nav .js').click(function(e) {
				$('.click-nav .js ul').slideToggle(200);
				$('.clicker').toggleClass('active');
				e.stopPropagation();
			});
			$(document).click(function() {
				if ($('.click-nav .js ul').is(':visible')) {
					$('.click-nav .js ul', this).slideUp();
					$('.clicker').removeClass('active');
				}
			});
		});
		</script>


</body>

</html>

