<?php ob_start();
 error_reporting(0);
 session_start();   
require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

$Get_Pat_Ref = $objQuery->mysqlSelect("*","patient_referal","patient_id='".$_SESSION['Pat_Id']."'","","","","");
$Pro3_Interact = $objQuery->mysqlSelect("*","chat_notification","patient_id='".$_SESSION['Pat_Id']."'and ref_id='".$Get_Pat_Ref[2]['ref_id']."'","chat_id desc","","","");

?>

<html>
<head>

<link type="text/css" rel="stylesheet" href="css/main.css" />

</head>
<body>
<!-- LOGIN SECTION -->
<script language="javaScript" src="js/validation.js"></script> 
  <div class="maincont3 clearfix">	
  
  	<?php
	foreach($Pro3_Interact as $PRO3_INT){
		$User = $objQuery->mysqlSelect("*","chckin_user","chk_userid='".$PRO3_INT['user_id']."'","","","","");
		if($PRO3_INT['ref_id']!=0){
	?>
	<h4><?php echo $Date=date('d-m-Y H:i:s',strtotime($PRO3_INT['TImestamp']));?></h4>

	<?php if($PRO3_INT['chk_msg']==1) { ?>
	<input type="checkbox" name="chatCheck" value="1" style="float:right; margin-top:20px;" onchange="return chkSend1(this.value,<?php echo $PRO3_INT['chat_id'];?>);" checked disabled>
	<?php } ?>
	<h5><?php echo $User[0]['chk_username']; ?></h5><br>
	<div class="pro_interaction_con">	
		<textarea class="txtArea" disabled><?php echo $PRO3_INT['chat_note']; ?></textarea>
	</div>
	<?php } 
	}
	?>
	
  </div>

</div>
</body>
</html>

