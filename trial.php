<?php ob_start();
 error_reporting(0);

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
ob_start();

if(isset($_POST['cmdSubmit'])){
	
	
	$txtFollowDate = date('Y-m-d',strtotime($_POST['txtFollowDate']));
	$txtName = addslashes($_POST['txtname']);
	
	
	$busResult2 = $objQuery->mysqlSelect("DISTINCT(ref_id) as RefId","doc_op_payment","status='RECONCILED' and ref_amount!='0'","id desc","","","");	
		
	
}


?>

<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8" />
<title>Medisense CRM</title>

</head>

<body>
  



   <form method="post" name="frmPatient" action="">
  
	<div class="Cntform fl">	
	<?php
	if(isset($sucessMessage)){ ?>
	<span class="success"><?php echo $sucessMessage; ?></span>
	<?php	}
	if(isset($errorMessage)){ ?>
						<span class="error"><?php echo $errorMessage; ?></span>
	<?php } ?>
	
		
		<h3><input type="submit" name="cmdSubmit" value="UPDATE" class="submitBtn fl" /></h3>

	<table style="width:90%">
			
			<tr>
					
					<th style="width:60%">Doctor Name</th>
					<th>Total Amount</th>
					
					
					
			
			</tr>
			<?php if($busResult2==true){ 
				$j=0;
				foreach($busResult2 as $list){
					
					$getTotAmt = $objQuery->mysqlSelect("SUM(ref_amount) as TotAmt","doc_op_payment","ref_id='".$list['RefId']."'","","","","");
					$getResult = $objQuery->mysqlSelect("*","doc_op_payment","ref_id='".$list['RefId']."'","","","","");
					$getRef = $objQuery->mysqlSelect("*","patient_referal as a inner join referal as b on a.ref_id=b.ref_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","b.ref_id='".$list['RefId']."'","","","","");
					
						
				
					
			
						
		
			?>
			
			<tr >
				<td ><?php echo $getRef[0]['ref_name'].", ".$getRef[0]['hosp_name'].", ".$getRef[0]['hosp_city']; ?></td>
				<td ><?php echo $getTotAmt[0]['TotAmt']; ?> </td>
				
				
				</tr>
				
				<?php $j++; }  
				} ?>
				</table>
			
			
			
	</table>
</body>
</html>

