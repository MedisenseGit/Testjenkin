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


if(isset($_GET['updatecon']) && $_GET['updatecon']=="1"){
	
	$docDomain = addslashes($_GET['chnageUrl']);

		
		$arrFields[] = 'website_name';
		$arrValues[] = $docDomain;
				
				
		if($_GET['docid']!==""){
			$updateUrl=$objQuery->mysqlUpdate('doctor_webtemplates',$arrFields,$arrValues,"doc_id='".$_GET['docid']."'");
		//$sucessMessage="Updated Successfully";
		}
		else
		{
			$errorMessage="Not updated";
		}
		
}
	
$busResult = $objQuery->mysqlSelect("doc_id,website_name","doctor_webtemplates","","Time_stamp desc","","","");	

?>

<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8" />
<title>Medisense CRM</title>
<?php include_once('support_file.php'); ?>

<!-- Sweet Alert -->
    <link href="assets/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">	 
</head>

<body>
  


<?php include_once('header.php'); ?>


<div class="clearall">
 <div class="wrapper">

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
	
		<h2>Manage Doctor Domain</h2>
		
		
		<form id="frmstatus" name="frmstatus" method="post" action="">
		<input type="hidden" name="doc_id" value="<?php echo $list['doc_id'];?>" />
		<table class="tableclass1" border="1" width="100%">
			<tr class="bgimg"><th>Doctor Name</th>
			<th width="300">Website URL</th>
		
			<th>Update</th>
			</tr>
			<?php
			if(!empty($busResult)){
				$i=0;
				foreach($busResult as $list){
				$docDetails = $objQuery->mysqlSelect("ref_name","referal","ref_id='".$list['doc_id']."'");	

			?>
				<tr>
				<td class="textAlign"><?php echo $docDetails[0]['ref_name']; ?></td>
				<td class="textAlign"><input type="text" name="docDomain" value="<?php echo $list['website_name']; ?>" data-doc-id="<?php echo $list['doc_id']; ?>" placeholder="Website URL" class="txtfield fr changeURL" style="width:500px;"/></td>
				
				<td class="textAlign"><input type="submit" name="cmdSubmit" value="UPDATE" class="submitBtn" /></td>
			
				</tr>
				<?php } 
			} else { ?>
				
				<tr><td class="textAlign" colspan="10">List Empty</td></tr>
			<?php }?>
			
	</table>
	</form>	
	</div>
	
	
  </div>

</div>
</div>




<!-- Sweet alert -->
<script src="assets/js/plugins/sweetalert/sweetalert.min.js"></script>

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
	<script src="assets/js/jquery-3.1.1.min.js"></script>
	<script language="javaScript" src="js/validation.js"></script>
</body>
</html>

