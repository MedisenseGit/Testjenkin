<?php ob_start();
 error_reporting(0);
 session_start();   
require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

$Get_Pat_Ref = $objQuery->mysqlSelect("*","patient_referal","patient_id='".$_SESSION['Pat_Id']."'","","","","");
$Pro2_Interact = $objQuery->mysqlSelect("*","chat_notification","patient_id='".$_SESSION['Pat_Id']."'and ref_id='".$Get_Pat_Ref[1]['ref_id']."'","chat_id desc","","","");


?>

<html>
<head>

<link type="text/css" rel="stylesheet" href="css/main.css" />

</head>
<body>
<!-- LOGIN SECTION -->

  <div class="maincont3 clearfix">	
  
  	<?php
	foreach($Pro2_Interact as $PRO2_INT){
		$User = $objQuery->mysqlSelect("*","chckin_user","chk_userid='".$PRO2_INT['user_id']."'","","","","");
		if($PRO2_INT['ref_id']!=0){
	?>
	<h4><?php echo $Date=date('d-m-Y H:i:s',strtotime($PRO2_INT['TImestamp']));?></h4> <h5><?php echo $User[0]['chk_username']; ?></h5>
	<div class="pro_interaction_con">	
		<textarea class="txtArea" disabled><?php echo $PRO2_INT['chat_note']; ?></textarea>
	</div>
	<?php } 
	}
	?>
	
  </div>

</div>
</body>
</html>

