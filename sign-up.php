<?php ob_start();
 error_reporting(0);
 session_start(); 

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
ob_start();

if(isset($_POST['cmdSubmit'])){
	
	$txtCompany = $_POST['txtCmpny'];
	$txtName = $_POST['txtName'];
	$txtAddres = $_POST['txtAddrss'];
	$txtUser = $_POST['txtUser'];
	$txtPasswd = md5($_POST['txtPassword']);
	$CmpnyLogo  = basename($_FILES['attachLogo']['name']);
	
	$arrFields = array();
	$arrValues = array();

		$arrFields[] = 'company_name';
		$arrValues[] = $txtCompany;
		$arrFields[] = 'owner_name';
		$arrValues[] = $txtName;
		$arrFields[] = 'company_addrs';
		$arrValues[] = $txtAddres;
		$arrFields[] = 'company_logo';
		$arrValues[] = $CmpnyLogo;

		$ChkCmpny= $objQuery->mysqlSelect("*","compny_tab","company_name='".$txtCompany."'","","","","");
	
	if($ChkCmpny==true){	
	$errorMessage="This Company is already existed";
	}
	else{
	//Insert Company Details to compny_tab Table	
		$usercraete=$objQuery->mysqlInsert('compny_tab',$arrFields,$arrValues);
		$id = mysql_insert_id();
		/* Insert photoe in Company_Logo folder only when its not empty field*/

		if(basename($_FILES['attachLogo']['name']!=="")){ 
		/* Uploading image file */ 
			 $uploaddirectory = realpath("Company_Logo");
			 $uploaddir = $uploaddirectory . "/" .$id;
			 $dotpos = strpos($fileName, '.');
			 $Photo1 = str_replace(substr($CmpnyLogo, 0, $dotpos), $id, $CmpnyLogo);
			 $uploadfile = $uploaddir . "/" . $Photo1;
			
		
			/*Checking whether folder with category id already exist or not. */
			if (file_exists($uploaddir)) {
				//echo "The file $uploaddir exists";
				} else {
				$newdir = mkdir($uploaddirectory . "/" . $id, 0777);
			}
			
			/* Moving uploaded file from temporary folder to desired folder. */
			if(move_uploaded_file ($_FILES['attachLogo']['tmp_name'], $uploadfile)) {
				//echo "File uploaded.";
			} else {
				//echo "File cannot be uploaded";
			}

			/**/	
		 
			}//end if
		
		
		$arrFields1 = array();
		$arrValues1 = array();
		
		$arrFields1[] = 'chk_username';
		$arrValues1[] = $txtUser ;
		$arrFields1[] = 'chk_passwd';
		$arrValues1[] = $txtPasswd;
		$arrFields1[] = 'cmpny_id';
		$arrValues1[] = $id;
		$arrFields1[] = 'flag_val';
		$arrValues1[] = '0';
			
	//Insert New User(Admin) password & User name to chckin_user table
		$usercreat=$objQuery->mysqlInsert('chckin_user',$arrFields1,$arrValues1);
		$id1=mysql_insert_id();
		$User= $objQuery->mysqlSelect("*","chckin_user","chk_userid='".$id1."'","","","","");
		$_SESSION['user'] = $User[0]['chk_username'];
		$_SESSION['flag_id'] = $User[0]['flag_val'];
		$_SESSION['admin_id'] = $User[0]['chk_userid'];
		$_SESSION['comp_id'] = $User[0]['cmpny_id'];
		header("location:manage_account.php");

	} 

}
	

?>

<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8" />
<title>Check-In</title>
<?php include_once('support_file.php'); ?>
</head>

<body onload="noBack();" onpageshow="if (event.persisted) noBack();" onunload="">
  

<div class="header">
  <div class="clearfix">
    <div class="wrapper rel">
           
 			  	  
    </div> 
  </div>
</div>

<div class="content">
<div class="clearall">
 <div class="wrapper">
 <script language="javaScript" src="js/validation.js"></script>
 
  <div class="maincont1 clearfix">	
  <div class="rightNav fr">
		<a href="index.php"><< Back</a>
	</div>
  
  	
	<div class="Cntform fl">	
	<?php	
	if(isset($errorMessage)){ ?>
						<span class="error"><?php echo $errorMessage; ?></span>
	<?php } ?>
		<h2>SIGN-UP</h2>
		 <form enctype="multipart/form-data" method="post" name="frmSignUp" action="" onsubmit="return createSignUp()">
		<h3>Company :<input type="text" name="txtCmpny" class="txtfield fr"/></h3>
		<h3>Name :<input type="text" name="txtName" class="txtfield fr"/></h3>
		<h3>Address :<textarea name="txtAddrss" class="txtArea fr"></textarea></h3><br><br>
		<h3>Logo : <input name="attachLogo" type="file" value="" class="txtfield fr"/></h3>
		<h3>User Name :<input type="text" name="txtUser" class="txtfield fr"/></h3>
		<h3>Password :<input type="password" name="txtPassword" class="txtfield fr"/></h3>
		<h3>Re-Type Password :<input type="password" name="txtRePass" class="txtfield fr"/></h3>
		<h3><input type="submit" name="cmdSubmit" value="SIGN UP" class="submitBtn fl" /></h3>
		
		</div>
	</form>
	
  </div>

</div>
</div>
</div>


<div class="footer">
<div class="clearfix">
   
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

