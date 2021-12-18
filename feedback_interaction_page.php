<?php ob_start();
 error_reporting(0);
 session_start();   
 
require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

$Feed_Interact = $objQuery->mysqlSelect("*","feedback_note","feedback_id='".$_SESSION['Feed_id']."'","note_id desc","","","");

	
?>
<html>
<head>	
<link type="text/css" rel="stylesheet" href="css/main.css" />
</head>
<body>
<!-- LOGIN SECTION -->
  <div class="maincont4 clearfix">	
  
  	<?php
		foreach($Feed_Interact as $FED_INT){
		$User = $objQuery->mysqlSelect("*","chckin_user","chk_userid='".$FED_INT['user_id']."'","","","","");
	?>
	<h4><?php echo $Date=date('d-m-Y H:i:s',strtotime($FED_INT['TImestamp']));?></h4> <h5><?php echo $User[0]['chk_username']; ?></h5>
	<div class="red_dev"></div>
	<div class="interaction_con">	
		<textarea class="txtArea" disabled><?php echo $FED_INT['chat_note']; ?></textarea>
	</div>
		<?php } ?>
	
  </div>

</div>
</body>
</html>

