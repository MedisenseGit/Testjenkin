<?php ob_start();
 error_reporting(0);
 session_start();

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();



		if(isset($_GET['ref_id'])){
			
						
			$result = $objQuery->mysqlSelect("*","referal","ref_id='".$_GET['ref_id']."'","","","","");	
			
		}
	
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8" />
<title>Medisense CRM</title>


</head>

<body onload="noBack();" onpageshow="if (event.persisted) noBack();" onunload="">

<div class="content">
<div class="clearall">
 <div class="wrapper">
 <script language="javaScript" src="js/validation1.js"></script>
	
		
	<!-- LOGIN SECTION -->

	
		
	<div class="mainSec">
	
	
			<?php
			
			if($result==true){ ?>
			
			<table border="0" cellpadding="7" cellspacing="0" bgcolor="#dddddd">
			
			
				<tr class="bgimg"><th>Ref.Name</th>
					<th>Ref.Email</th>
					<th>Contact No.</th>
					<th>Address</th>
				</tr>
			
				<?php
				$j=0;
				foreach($result as $list){
			
			?>
			<tr style="background:#f1f2f2;">
				
				<td class="textAlign"><?php echo $list['ref_name']; ?></td>
				<td class="textAlign"><?php echo $list['ref_mail']; ?></td>
				<td class="textAlign"><?php echo $list['contact_num']; ?></td>
				<td class="textAlign"><?php echo $list['ref_address']; ?></td>
								
				</tr>
				<?php $j++; }  ?>
			
			</table>
			<?php } ?>
			
	</table>
	</div>
		
  </div>
</div>
</div>

</body>

</html>

