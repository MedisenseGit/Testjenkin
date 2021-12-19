<?php ob_start();
 error_reporting(1);
 session_start();   
require_once("classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();
if(isset($_POST['cmdLogin'])){
	 $txtUsrname = $_POST['txtusername'];
	 $txtUsrname = str_replace("|","",$txtUsrname);
	 $txtPass = md5($_POST['txtpasswd']);
	//echo $txtUsrname;
	//echo $txtPass;
	$result = mysqlSelect('*','chckin_user',"((chk_username='".$txtUsrname."') and (chk_passwd='".$txtPass."'))");
	//echo 'br/'.$result[0]['chk_username'];
	if(empty($result)){
		echo "<script type='text/javascript'>\n"; 
		echo "alert('Login Failed ,Please check you have enterd User Name or Password field');\n"; 
		echo "</script>"; 
	}
	else{
		$_SESSION['user'] = $result[0]['chk_username'];
		$_SESSION['flag_id'] = $result[0]['flag_val'];
		$_SESSION['admin_id'] = $result[0]['chk_userid'];
		$_SESSION['comp_id'] = $result[0]['cmpny_id'];
		if($result[0]['flag_val']==3){
			header("location:Doctor-Tracking?disp=1&type=1");
		}
		else if($result[0]['flag_val']==4){
			header("location:Medisense-Tracker");
		}
		else
		{
			
		header("location:manage_account.php");
		}
	}
}
?>

<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8" />
<title>Medisense CRM</title>
<link type="text/css" rel="stylesheet" href="css/main.css" />

<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />

</head>
<body>
<!-- LOGIN SECTION -->
  <div class="maincont clearfix">	
  <?php if($_GET['endsession']=='1'){ echo '<h4 style=padding-top:20px;font-size:15px;font-style:italic;color:green;>Logged of Successfully</h3>';} ?>
  <form method="post" name="loginForm" action="" onsubmit="return chkLogin()">
  	<div class="loginconatiner">	
		<h2>LOGIN</h2>
		<h3>User Name :<input type="text" name="txtusername" class="txtfield fr"/></h3>
		<h3>Password :<input type="password" name="txtpasswd" class="txtfield fr"/></h3>
		<input type="submit" name="cmdLogin" value="SUBMIT" class="registerbtn" />
	</div>
	</form>
	<!--<span class="signup"><a href="sign-up.php">Sign-Up</a></span>-->
	
  </div>

</div>
</body>
</html>

