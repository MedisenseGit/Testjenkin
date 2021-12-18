<?php ob_start();
 error_reporting(0);
 session_start(); 

$admin_id = $_SESSION['admin_id'];
$Company_id=$_SESSION['comp_id'];
if(empty($admin_id)){
header("Location:index.php");
}

 

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
ob_start();

date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d h:i:s');

$refId=time();

?>

<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8" />
<title>Medisense CRM</title>
<?php include_once('support_file.php'); ?>

<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>


	 
</head>

<body onload="noBack();" onpageshow="if (event.persisted) noBack();" onunload="">
  


<?php include_once('header.php'); ?>


<div class="clearall">
 <div class="wrapper">
 <script language="javaScript" src="js/validation.js"></script>
 <script>
				function myFunction(subid) {
				 var myWindow = window.open("http://medisensecrm.com/create_time.php?doc_id="+subid, "myWindow", "width=440,height=460");
				}
				</script>
  <div class="Provider_sec clearfix">	
  <div class="rightNav fr">
  <?php if($_GET['pat_id']!=""){ ?>
		<a href="Patient_History.php?pat_id=<?php echo $_GET['pat_id']; if(!empty($_GET['refid'])){ ?>refid=<?php echo $_GET['refid']; } if(!empty($_GET['disp'])){ ?>&disp=<?php echo $_GET['disp']; } if(!empty($_GET['assignId'])){ ?>&assignId=<?php echo $_GET['assignId']; } if(!empty($_GET['start'])) { ?>&start=<?php echo $_GET['start']; } ?>"><< Back</a>
  <?php } else { ?>
		<a href="manage_account.php"><< Back</a>
  <?php } ?>
	</div>
   
	<div class="Provider fl">	
	<?php
	if(isset($sucessMessage)){ ?>
	<span class="success"><?php echo $sucessMessage; ?></span>
	<?php	}
	if(isset($errorMessage)){ ?>
						<span class="error"><?php echo $errorMessage; ?></span>
	<?php } ?>
	
		<h2>Refund Amount</h2>
		
		<form  method="post" action="PaytmKit/pgRefundRedirect.php">
		<input type="hidden" name="TXNTYPE" value="REFUND" />
		<input type="hidden" name="REFID" value="<?php echo $refId; ?>" />
		<table>
		
		<input type="hidden" name="Prov_Id" value="<?php echo $_GET['pr_id']; ?>" />
		<tr><td><h3>
		<lable style="font-size:11px;">TXN ID</label><br>
		<input type="text" name="TXNID" value=""  class="txtfield fr"/></h3></td>
		
		<td><h3>
		<lable style="font-size:11px;">ORDER ID</label><br>
		<input type="text" name="ORDERID" value="" class="txtfield fr"/></h3></td>
		
		<td><h3>
		<lable style="font-size:11px;">REFUND AMOUNT</label><br>
		<input type="text" name="REFUNDAMOUNT" value="" class="txtfield fr"/></h3></td>
		
		
		
		</tr>
		<tr><td><br><br></td></tr>
		<tr>
		<td><h3><input type="submit" name="cmdSubmit" value="<?php if($_GET['pr_id']>0 || $_POST['Prov_Id']>0){ echo "UPDATE"; }else { echo "ADD";} ?>" class="submitBtn" /></h3></form>
		<form method="post" name="frmDocNeddInfo" action="" >
		<input type="hidden" name="cmdDoc" value="" />
		<input type="hidden" name="doc_id" value="" />
		<?php if(time()>$get_provInfo[0]['expires'] && $_GET['pr_id']>0){ ?><a href="#" type="submit" style="font-size:12px; color:#1b08d7; text-decoration:underline;" onclick="return docInfo(<?php echo $_GET['pr_id']; ?>);">Need Info</a><?php } ?></td>
		</form>
		</tr>
		<tr><td><br><br></td></tr>
		</table>
		
 
		
		
		
	
	
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

