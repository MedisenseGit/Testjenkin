<?php
ob_start();
error_reporting(0); 
session_start();

require_once("../../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();

$admin_id = $_SESSION['user_id'];
if(empty($admin_id)){
	header("Location:../logout.php");
}
date_default_timezone_set('Asia/Kolkata');
$curDate=date('Y-m-d H:i:s');
	
	$hostType="https://";
	$getUrl = $_SERVER['REQUEST_URI'];
	$getHost = $_SERVER['HTTP_HOST'];
	$url = explode("print-emr/?", $getUrl)[0];
	$completeURL = $hostType.$getHost.$url;
	
	
	$discharge_summary = mysqlSelect("*","patient_discharge_summaray","md5(discharge_id)='".$_GET['id']."'","","","","");
	$checkSetting= mysqlSelect("*","doctor_settings","doc_id='".$admin_id."' and doc_type='1'","","","","");
	
?>

<!DOCTYPE html>
<html>
    <head>
	<meta charset="utf-8" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width">
	<title>Print Patient Discharge Summary</title>
	<link rel="stylesheet" media="all" href="assets/css/print-emr.css">
	<!-- Sweet Alert -->
    <link href="../../assets/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
    </head>
    <body id="<?php echo $_GET['pid']; ?>" data-pmail="<?php echo $patient_email; ?>" data-mobile="<?php echo $patient_mob; ?>">
	
	<div id="actions">
			
	    <a href="#" class="btn" onclick="window.print(); return false;">PRINT</a>
	
		<a href="../Discharge-Summary" class="btn" >EXIT</a>
		
	</div>
	<div class="container" id="main-content">
		
		<?php 
			$headerHeightPixel = $checkSetting[0]['presc_pad_header_height']*37.795276;
			$footerHeightPixel = $checkSetting[0]['presc_pad_footer_height']*37.795276;
			?>
		 <header class="group">
			
			<div style="margin-top:<?php echo $headerHeightPixel; ?>px;"></div>
		 
		 </header>
		 
	
	   

		<div id="diagnosis" >
			<ul>
			<?php if(!empty($discharge_summary[0]['discharge_summary'])){ ?><li><?php echo $discharge_summary[0]['discharge_summary']; ?></li><?php } ?>
			</ul>
			
		</div>


	    
		 <!-- Sweet alert -->
    <script src="../../assets/js/plugins/sweetalert/sweetalert.min.js"></script>
	    <script src="assets/js/print-emr.min.js"></script>
		
		
		<div style="margin-bottom:<?php echo $footerHeightPixel; ?>px;"></div>
		
		
	</div>
    </body>
</html>
