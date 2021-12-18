<?php ob_start();
 error_reporting(0);
 session_start();   
 
require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

$Med_Interact = $objQuery->mysqlSelect("*","chat_notification","patient_id='".$_SESSION['Pat_Id']."'and ref_id=0","chat_id desc","","","");

	
?>
<html>
<head>	
<link type="text/css" rel="stylesheet" href="css/main.css" />
</head>
<body>
<!-- LOGIN SECTION -->
  <div class="maincont2 clearfix">	
  
  	<?php
		foreach($Med_Interact as $MED_INT){
		$User = $objQuery->mysqlSelect("*","chckin_user","chk_userid='".$MED_INT['user_id']."'","","","","");
	?>
	<h4><?php echo $Date=date('d-m-Y H:i:s',strtotime($MED_INT['TImestamp']));?></h4> <h5><?php echo $User[0]['chk_username']; ?></h5>
	<div class="red_dev"></div>
	<div class="interaction_con">	
		<textarea class="txtArea" disabled><?php echo $MED_INT['chat_note']; ?></textarea>
	</div>
		<?php } ?>
	
  </div>

</div>
</body>
</html>

