<?php ob_start();
 error_reporting(0);
 session_start();

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();



		if(isset($_GET['sub_id'])){
			
						
			$result = $objQuery->mysqlSelect("*","subscribe_family","subscribe_id='".$_GET['sub_id']."'","","","","");	
			
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
			
			
				<tr class="bgimg"><th>Name</th>
					<th>Relationship</th>
					<th>Age</th>
					<th>Ref. No.</th>
				</tr>
			
				<?php
				$j=0;
				foreach($result as $list){
			
			?>
			<tr style="background:#f1f2f2;">
				
				<td class="textAlign"><?php echo $list['name']; ?></td>
				<td class="textAlign"><?php echo $list['relationship']; ?></td>
				<td class="textAlign"><?php echo $list['age']; ?></td>
				<td class="textAlign"><?php echo $list['subfamilyref_id']; ?></td>
								
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

