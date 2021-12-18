<?php ob_start();
 error_reporting(0);
 session_start();

 $admin_id = $_SESSION['admin_id'];
 $Company_id=$_SESSION['comp_id'];
 $user_flag = $_SESSION['flag_id'];
 
 date_default_timezone_set('Asia/Kolkata');
 $Cur_Date=date('d-m-Y');

if(empty($admin_id)){
header("Location:index.php");
}

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();


		
			if(!isset($_GET['start'])) {  // This variable is set to zero for the first page
			$start = 0;
			}else{
			$start = $_GET['start'];
			}

			$eu = ($start - 0); 
			$limit = 50;         // No of records to be shown per page.
			$this1 = $eu + $limit; 
			$back = $eu - $limit; 
			$next = $eu + $limit;
	
			$busResult2 = $objQuery->mysqlSelect("DISTINCT(ref_id) as RefId","doc_op_payment","status='RECONCILED'","id desc","","","$eu, $limit");	
		
			$pag_result = $objQuery->mysqlSelect("DISTINCT(ref_id) as RefId","doc_op_payment","status='RECONCILED'","");


			$pageing = firstPaging($pag_result,$limit,$back,$next,$eu,$field,$type2);
			$arrPage = explode("-",$pageing);


if(isset($_POST['cmdPaid'])){
	$ref_id = $_POST['ref_id'];
	$amt = $_POST['pay_amt'];
	$payId = $_POST['payId'];
	$paytm_id=$_POST['paytm_id'];	
	$payDate=date('Y-m-d',strtotime($_POST['calendar']));	
	$getResult = $objQuery->mysqlSelect("*","doc_paid_transaction","payment_id='".$payId."'","");
	
if(empty($getResult) && $payId!="" && $payDate!=""){	
	$arrValues = array();
	$arrFields = array();
	
	
	$arrFields[] = 'paytm_id';
	$arrValues[] = $paytm_id;
	$arrFields[] = 'ref_id';
	$arrValues[] = $ref_id;
	$arrFields[] = 'paid_amount';
	$arrValues[] = $amt;
	$arrFields[] = 'payment_id';
	$arrValues[] = $payId;
	$arrFields[] = 'payment_date';
	$arrValues[] = $payDate;
	$arrFields[] = 'status';
	$arrValues[] = "PAID";
	$objQuery->mysqlDelete('doc_paid_transaction',"ref_id='".$ref_id."'and status='RECONCILED'");
	$insertTrans=$objQuery->mysqlInsert('doc_paid_transaction',$arrFields,$arrValues);
	$objQuery->mysqlDelete('doc_op_payment',"ref_id='".$ref_id."'");
	
	$chkRes = $objQuery->mysqlSelect("*","doc_op_payment","paytm_id='".$paytm_id."' and status='RECONCILED'","");
	if($chkRes==false){
	$arrValues55= array();
	$arrFields55 = array();
	$arrFields55[] = 'Pay_status';
	$arrValues55[] = "PAID";
	$updateCustTrans=$objQuery->mysqlUpdate('customer_transaction',$arrFields55,$arrValues55,"Payment_id='".$_POST['paytm_id']."'");
	}
}	
header('Location:op_pending_payment.php?disp=2');
	
}		
					

function firstPaging($pag_result,$limit,$back,$next,$eu,$field,$type2){
				$nume = count($pag_result);
				if($nume>=2600){
					$tot_num=2600;
				}else {
					$tot_num=count($pag_result);
				}
				$this1 = $eu + $limit; 
				$strPaging = " ";
				$strPaging.="<table width='100%' border='none' align = 'center'><tr><td  align='left' width='5%'>";
				if($back >=0){ 
					$strPaging.="<a href='$page_name?disp=$_GET[disp]&start=$back&field=".$field."&type=".$type2."'class=\"pagenav\"><font face='Verdana' size='8' color='#3c3e3e'>PREV</font></a>"; 
				}else{
					$strPaging.="<font face='Verdana' size='8' color='#3c3e3e'><span class='pagenav'>PREV</span></font>"; 
				}
				$strPaging.="</td><td align=center width='1200px' class='pagenav'><center>";
				$i=0;
				$l=1;
				$strPaging.="Page No.";
				for($i=0;$i < $tot_num;$i=$i+$limit){
					if($i <> $eu){
						$strPaging.="<a href='$page_name?disp=$_GET[disp]&start=$i&field=".$field."&type=".$type2."'class=\"pagenav1\" ><font face='Verdana' size='8' color='#3c3e3e' >$l</font></a>&nbsp;";
					}else{
						$strPaging.="<font face='Verdana' size='8' color='#ec042a' ><span class='pagenav1' ><b>$l</b></span></font>&nbsp;";
					}
					$l=$l+1;
				}
				$strPaging.="</center></td><td  align='right' width='5%'>";
				if($this1 < $nume) { 
					
					$strPaging.="<a href='$page_name?disp=$_GET[disp]&start=$next&field=".$field."&type=".$type2."'class=\"pagenav1\"><font face='Verdana' size='8' color='#3c3e3e'>NEXT</font></a>";
				}else{
					$strPaging.="<font face='Verdana' size='8' color='#3c3e3e'><span class='pagenav'>NEXT</span></font>";
				}

				$strPaging.="</td></tr></table>";
				return $strPaging."-".$nume;
}



?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8" />
<title>Medisense CRM</title>
<?php include_once('support_file.php'); ?>
<link href="css/bootstrap.min.css" rel="stylesheet">



</head>

<body >
  


<?php include_once('header.php'); ?>

<div class="content">
<div class="clearall">
 <div class="wrapper">
 <script language="javaScript" src="js/validation1.js"></script>
	
	<!-- HEADING SECTION -->
	<div class="firstSec">
		<div class="clearfix">

		<h1 class="headfont fl">
		<span>Payment Summery</span></h1>
		
		
		</div>
	</div>
	
	<!-- LOGIN SECTION -->
	<script language="JavaScript" src="js/validation.js"></script>
	
	<div class="wrapper">
  <div class="main_nav fl">
  <form name="frmNavigate" method="post" >
  <input type="hidden" name="cmdNavigate" value="">
  <input type="hidden" name="buttonVal" value="">
  
	<h3>
	<a href="op_payment.php?disp=1"  <?php if($_GET['disp']==1){ echo "class=active"; } ?>>RECONCILE</a>
	<a href="op_pending_payment.php?disp=2"  <?php if($_GET['disp']==2){ echo "class=active"; } ?>>PENDING</a>
	<a href="payment_summery.php?disp=3"  <?php if($_GET['disp']==3){ echo "class=active"; } ?>>PAID</a></h3>
	</form>
</div>

 </div>
		
	<div class="mainSec">
	
	<!-- <b>Total Count :<?php echo $Total_Count[0]['count'];?></b>-->

			
			
			<div class="fl">
			<center><h4>Pending Payments</h4></center>
			<table class="tableclass1 fl" style="width:90%">
			
			<tr class="bgimg">
					<th>Bank Transaction ID</th>
					<th>Payment Date</th>
					<th style="width:60%">Doctor Name</th>
					<th>Total Amount</th>
					<th>Pay Status</th>
					
					
			
			</tr>
			<?php if($busResult2==true){ ?>
				<tr>
				<td colspan="10">
				<?php echo $arrPage[0];?>
				</td>
				</tr>
				<?php
				$j=0;
				foreach($busResult2 as $list){
					
					$getTotAmt = $objQuery->mysqlSelect("SUM(ref_amount) as TotAmt","doc_op_payment","ref_id='".$list['RefId']."'","","","","");
					$getResult = $objQuery->mysqlSelect("*","doc_op_payment","ref_id='".$list['RefId']."'","","","","");
					$getRef = $objQuery->mysqlSelect("*","patient_referal as a inner join referal as b on a.ref_id=b.ref_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","b.ref_id='".$list['RefId']."'","","","","");
					
						
				
					
			
						
		
			?>
			
			<form id="frmstatus" name="frmstatus" method="post" action="" >
			<input type="hidden" name="ref_id" value="<?php echo $list['RefId']; ?>">
			<input type="hidden" name="patient_id" value="<?php echo $getResult[0]['patient_id']; ?>">
			<input type="hidden" name="paytm_id" value="<?php echo $getResult[0]['paytm_id']; ?>">
			<input type="hidden" name="pay_amt" value="<?php echo $getTotAmt[0]['TotAmt']; ?>">
			<script src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>
			<script>   
			$(function() {
				 $( "#calendar<?php echo $getResult[0]['id']; ?>" ).datepicker();   
			}); 
			</script>	
			<tr style="background:#f1f2f2;">
				<td class="textAlign"><input type="text" name="payId" /></td>
				<td class="textAlign"><input type="text" name="calendar" id="calendar<?php echo $getResult[0]['id']; ?>" value="<?php echo $Cur_Date; ?>" /></td>
				<td class="textAlign" width="60%"><?php echo $getRef[0]['ref_name'].", ".$getRef[0]['hosp_name'].", ".$getRef[0]['hosp_city']; ?></td>
				<td class="textAlign"><span style="background:#0481ab; padding:5px; color:#fff;"><?php echo $getTotAmt[0]['TotAmt']; ?> </span> </td>
				
				<td class="textAlign"><input type="submit" value="PAY" name="cmdPaid" /></td>
				</tr>
				</form>
				<?php $j++; }  
				} ?>
				</table>
			
			
			
	</table>
	</div>
	
	</div>
		
  </div>
</div>
</div>


<div class="footer">
<div class="clearfix">
   
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

