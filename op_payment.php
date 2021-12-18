<?php ob_start();
 error_reporting(0);
 session_start();

 $admin_id = $_SESSION['admin_id'];
 $Company_id=$_SESSION['comp_id'];
 $user_flag = $_SESSION['flag_id'];
 
 date_default_timezone_set('Asia/Kolkata');
 $Cur_Date=date('Y-m-d h:i:s');

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
	
			$busResult2 = $objQuery->mysqlSelect("DISTINCT(a.patient_id) as PatId","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join customer_transaction as c on c.patient_id=a.patient_id","c.Payment_id!='Null'","c.transaction_time desc","","","$eu, $limit");	
		
			$pag_result = $objQuery->mysqlSelect("DISTINCT(a.patient_id) as PatId","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join customer_transaction as c on c.patient_id=a.patient_id","c.Payment_id!='Null'","");


			$pageing = firstPaging($pag_result,$limit,$back,$next,$eu,$field,$type2);
			$arrPage = explode("-",$pageing);



		
					

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
		<span>Opinion Payment</span></h1>
		
		
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
	<a href="payment_summery.php?disp=3"  <?php if($_GET['disp']==3){ echo "class=active"; } ?>>PAID</a>
	</h3>
	</form>
</div>

 </div>
		
	<div class="mainSec">
	
	<?php
	if(isset($message)){ ?>
	<span class="success"><?php echo $message; ?></span>
	<?php	}
	?>
	
	 <b>Total No. of records :<?php echo $Total_Count[0]['count'];?></b>

			<?php
			if($busResult2==true){ ?>
			<table class="tableclass1">
			<tr class="bgimg">
					<th>Trans Date</th>
					<th>PayTm Id</th>
					<th>Patient ID</th>
					<th>Tot. Collection</th>
					<th>Medisense Share</th>
					<th>Ref1</th>
					<th>Ref2</th>
					<th>Ref3</th>
					<th>Ref4</th>
					
					<th>RECONCILE</th>	
					<th>Status</th>
					
			
			</tr>
				<tr>
				<td colspan="10">
				<?php echo $arrPage[0];?>
				</td>
				</tr>
				<?php
				
				foreach($busResult2 as $list){
					$custTrans = $objQuery->mysqlSelect("*","customer_transaction","patient_id='".$list['PatId']."'","","","","");
					$getRef = $objQuery->mysqlSelect("*","patient_referal as a inner join referal as b on a.ref_id=b.ref_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","a.patient_id='".$list['PatId']."'","","","","");
					$getDocAmt1 = $objQuery->mysqlSelect("*","doc_op_payment","patient_id='".$list['PatId']."'and ref_id='".$getRef[0]['ref_id']."'","","","","");
					$getDocAmt2 = $objQuery->mysqlSelect("*","doc_op_payment","patient_id='".$list['PatId']."'and ref_id='".$getRef[1]['ref_id']."'","","","","");
					$getDocAmt3 = $objQuery->mysqlSelect("*","doc_op_payment","patient_id='".$list['PatId']."'and ref_id='".$getRef[2]['ref_id']."'","","","","");
					$getDocAmt4 = $objQuery->mysqlSelect("*","doc_op_payment","patient_id='".$list['PatId']."'and ref_id='".$getRef[3]['ref_id']."'","","","","");
					//TO CHECK DOCTOR STATUS
					switch($getRef[0]['status2'])
					{
						case '2' : $doc1_status="<span style='font-size:7px; padding:3px;  background:#e3e006; color:#000;'>REFERED</span>";
						break;
						case '5' : $doc1_status="<span style='font-size:7px; padding:3px;  background:#0da214; color:#000;'>RESPONDED</span>";
						break;
						case '7' : $doc1_status="<span style='font-size:7px; padding:3px;  background:#f68c34; color:#000;'>STAGED</span>";
						break;
						case '8' : $doc1_status="<span style='font-size:7px; padding:3px;  background:#f68c34; color:#000;'>OP-CONVERTED</span>";
						break;
						case '9' : $doc1_status="<span style='font-size:7px; padding:3px;  background:#f68c34; color:#000;'>IP-CONVERTED</span>";
						break;
						case '11' : $doc1_status="<span style='font-size:7px; padding:3px;  background:#f68c34; color:#000;'>INVOICED</span>";
						break;
						case '12' : $doc1_status="<span style='font-size:7px; padding:3px;  background:#f68c34; color:#000;'>PAYMENT RECEIVED</span>";
						break;
					}
					switch($getRef[1]['status2'])
					{
						case '2' : $doc2_status="<span style='font-size:7px; padding:3px;  background:#e3e006; color:#000;'>REFERED</span>";
						break;
						case '5' : $doc2_status="<span style='font-size:7px; padding:3px;  background:#0da214; color:#000;'>RESPONDED</span>";
						break;
						case '7' : $doc2_status="<span style='font-size:7px; padding:3px;  background:#f68c34; color:#000;'>STAGED</span>";
						break;
						case '8' : $doc2_status="<span style='font-size:7px; padding:3px;  background:#f68c34; color:#000;'>OP-CONVERTED</span>";
						break;
						case '9' : $doc2_status="<span style='font-size:7px; padding:3px;  background:#f68c34; color:#000;'>IP-CONVERTED</span>";
						break;
						case '11' : $doc2_status="<span style='font-size:7px; padding:3px;  background:#f68c34; color:#000;'>INVOICED</span>";
						break;
						case '12' : $doc2_status="<span style='font-size:7px; padding:3px;  background:#f68c34; color:#000;'>PAYMENT RECEIVED</span>";
						break;
					}
					switch($getRef[2]['status2'])
					{
						case '2' : $doc3_status="<span style='font-size:7px; padding:3px;  background:#e3e006; color:#000;'>REFERED</span>";
						break;
						case '5' : $doc3_status="<span style='font-size:7px; padding:3px;  background:#0da214; color:#000;'>RESPONDED</span>";
						break;
						case '7' : $doc3_status="<span style='font-size:7px; padding:3px;  background:#f68c34; color:#000;'>STAGED</span>";
						break;
						case '8' : $doc3_status="<span style='font-size:7px; padding:3px;  background:#f68c34; color:#000;'>OP-CONVERTED</span>";
						break;
						case '9' : $doc3_status="<span style='font-size:7px; padding:3px;  background:#f68c34; color:#000;'>IP-CONVERTED</span>";
						break;
						case '11' : $doc3_status="<span style='font-size:7px; padding:3px;  background:#f68c34; color:#000;'>INVOICED</span>";
						break;
						case '12' : $doc3_status="<span style='font-size:7px; padding:3px;  background:#f68c34; color:#000;'>PAYMENT RECEIVED</span>";
						break;
					}
					switch($getRef[3]['status2'])
					{
						case '2' : $doc4_status="<span style='font-size:7px; padding:3px;  background:#e3e006; color:#000;'>REFERED</span>";
						break;
						case '5' : $doc4_status="<span style='font-size:7px; padding:3px;  background:#0da214; color:#000;'>RESPONDED</span>";
						break;
						case '7' : $doc4_status="<span style='font-size:7px; padding:3px;  background:#f68c34; color:#000;'>STAGED</span>";
						break;
						case '8' : $doc4_status="<span style='width:150px;font-size:7px; padding:5px;  background:#f68c34; color:#000;'>OP-CONVERTED</span>";
						break;
						case '9' : $doc4_status="<span style='font-size:7px; padding:3px;  background:#f68c34; color:#000;'>IP-CONVERTED</span>";
						break;
						case '11' : $doc4_status="<span style='font-size:7px; padding:3px;  background:#f68c34; color:#000;'>INVOICED</span>";
						break;
						case '12' : $doc4_status="<span style='font-size:7px; padding:3px;  background:#f68c34; color:#000;'>PAYMENT RECEIVED</span>";
						break;
					}
			
			
							 
					//GET TOT. AMOUNT COLLECTED FROM Patient
					$Tot_amt=$custTrans[0]['amount']+$custTrans[1]['amount']+$custTrans[2]['amount']+$custTrans[3]['amount'];
					
					//MEDISENSE SHARE
					$Med_Share=$Tot_amt-$getRef[0]['amount_paid']-$getRef[1]['amount_paid']-$getRef[2]['amount_paid']-$getRef[3]['amount_paid'];
				
				if($custTrans[0]['Pay_status']=="RECONCILED"){
				$Paystatus="<span style='width:150px;font-size:9px; padding:5px;  background:#e3e006; color:#000;'>RECONCILED</span>";
				}
				else if($custTrans[0]['Pay_status']=="PAID"){
				$Paystatus="<span style='width:150px;font-size:9px; padding:5px;  background:#36cc07; color:#fff;'>PAID</span>";
				}	
					else{
						$Paystatus="";
					}
		
			?>
			
			<form method="post" id="frmstatus" name="frmstatus"  action="add_reconcilation.php" >
			<input type="hidden" name="pat_id" value="<?php echo $custTrans[0]['patient_id']; ?>">
			<input type="hidden" name="Pay_Id" value="<?php echo $custTrans[0]['Payment_id']; ?>">
			<input type="hidden" name="ref_id1" value="<?php echo $getRef[0]['ref_id']; ?>">
			<input type="hidden" name="ref_id2" value="<?php echo $getRef[1]['ref_id']; ?>">
			<input type="hidden" name="ref_id3" value="<?php echo $getRef[2]['ref_id']; ?>">
			<input type="hidden" name="ref_id4" value="<?php echo $getRef[3]['ref_id']; ?>">
						
			<tr style="background:#f1f2f2;">
				<td class="textAlign"><?php echo $Date=date('d-m-Y, h:i',strtotime($custTrans[0]['transaction_time']));?></td>
				<td class="textAlign"><span style="background:#f4081e; padding:5px; color:#fff;"><?php echo $custTrans[0]['Payment_id']; ?></span> </td>
				<td class="textAlign"><span style="background:#0481ab; padding:5px; color:#fff;"><?php echo $custTrans[0]['patient_id']; ?></span> </td>
				<td class="textAlign"><span style="background:#f4081e; padding:5px; color:#fff;"><?php echo "Rs.".$Tot_amt; ?></span> </td>
				<td class="textAlign"><span style="background:#f4081e; padding:5px; color:#fff;"><?php echo "Rs.".$Med_Share; ?></span> </td>
				<td class="textAlign" style="width:300px;"><?php if(!empty($getRef[0]['ref_id'])){ echo $getRef[0]['ref_name']."<br> ".$getRef[0]['hosp_name']." ".$getRef[0]['hosp_city']."<br>"; ?>
				<div class="col-md-4"><div class="outer-wrapper"><div class="on-hover-content"><p>  
				<?php $Pro1_Interact = $objQuery->mysqlSelect("*","chat_notification","patient_id='".$list['PatId']."'and ref_id='".$getRef[0]['ref_id']."'","chat_id desc","","","");
				
							foreach($Pro1_Interact as $chalist){
							echo "( ";
							echo $Date=date('d-m-Y H:i:s',strtotime($chalist['TImestamp']))." )<br>";
							echo $chalist['chat_note']."<br><br>"; } ?>
							</p>
				</div><a href="javascript:void(0);" class='hover_cnt' ><?php echo $doc1_status; ?></a></div></div><br><br><label class="fl">Rs. </label><input type="text" name="amt1" class="amt" value="<?php echo $getRef[0]['amount_paid']; ?>" <?php if(!empty($getRef[0]['amount_paid'])){ echo "disabled"; } ?> /><?php } ?></td>
				
				<td class="textAlign" style="width:300px;"><?php if(!empty($getRef[1]['ref_id'])){ echo $getRef[1]['ref_name']."<br> ".$getRef[1]['hosp_name']." ".$getRef[1]['hosp_city']."<br>"; ?>
				<div class="col-md-4"><div class="outer-wrapper"><div class="on-hover-content"><p>  
				<?php $Pro2_Interact = $objQuery->mysqlSelect("*","chat_notification","patient_id='".$list['PatId']."'and ref_id='".$getRef[1]['ref_id']."'","chat_id desc","","","");
							foreach($Pro2_Interact as $chalist){
							echo "( ";
							echo $Date=date('d-m-Y H:i:s',strtotime($chalist['TImestamp']))." )<br>";
							echo $chalist['chat_note']."<br><br>"; } ?>
							</p>
				</div><a href="javascript:void(0);" class='hover_cnt' ><?php echo $doc2_status; ?></a></div></div><br><br><label class="fl">Rs. </label><input type="text" name="amt2" class="amt" value="<?php echo $getRef[1]['amount_paid']; ?>" <?php if(!empty($getRef[1]['amount_paid'])){ echo "disabled"; } ?> /><?php } ?></td>
				<td class="textAlign" style="width:300px;"><?php if(!empty($getRef[2]['ref_id'])){ echo $getRef[2]['ref_name']."<br> ".$getRef[2]['hosp_name']." ".$getRef[2]['hosp_city']."<br>"; ?>
				<div class="col-md-4"><div class="outer-wrapper"><div class="on-hover-content"><p>  
				<?php $Pro3_Interact = $objQuery->mysqlSelect("*","chat_notification","patient_id='".$list['PatId']."'and ref_id='".$getRef[2]['ref_id']."'","chat_id desc","","","");
							foreach($Pro3_Interact as $chalist){
							echo "( ";
							echo $Date=date('d-m-Y H:i:s',strtotime($chalist['TImestamp']))." )<br>";
							echo $chalist['chat_note']."<br><br>"; } ?>
							</p>
				</div><a href="javascript:void(0);" class='hover_cnt' ><?php echo $doc3_status; ?></a></div></div><br><br><label class="fl">Rs. </label><input type="text" name="amt3" class="amt" value="<?php echo $getRef[2]['amount_paid']; ?>" <?php if(!empty($getRef[2]['amount_paid'])){ echo "disabled"; } ?> /><?php } ?></td>
				<td class="textAlign" style="width:300px;"><?php if(!empty($getRef[3]['ref_id'])){ echo $getRef[3]['ref_name']."<br> ".$getRef[3]['hosp_name']." ".$getRef[3]['hosp_city']."<br>"; ?>
				<div class="col-md-4"><div class="outer-wrapper"><div class="on-hover-content"><p>  
				<?php $Pro4_Interact = $objQuery->mysqlSelect("*","chat_notification","patient_id='".$list['PatId']."'and ref_id='".$getRef[3]['ref_id']."'","chat_id desc","","","");
							foreach($Pro4_Interact as $chalist){
							echo "( ";
							echo $Date=date('d-m-Y H:i:s',strtotime($chalist['TImestamp']))." )<br>";
							echo $chalist['chat_note']."<br><br>"; } ?>
							</p>
				</div><a href="javascript:void(0);" class='hover_cnt' ><?php echo $doc4_status; ?></a></div></div><br><br><label class="fl">Rs. </label><input type="text" name="amt4" class="amt" value="<?php echo $getRef[3]['amount_paid']; ?>" <?php if(!empty($getRef[3]['amount_paid'])){ echo "disabled"; } ?> /><?php } ?></td>
				
				<td class="textAlign"><?php if($custTrans[0]['Pay_status']!="RECONCILED"){ ?><input type="submit" value="RECONCILE" name="cmdReconcile" /><?php } ?></td>
				<td class="textAlign"><?php echo $Paystatus; ?></td>
				</tr>
				</form>
				<?php  }  ?>
				
				</table>
			<?php } ?>
			
			
	</table>
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
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
<script type="text/javascript">
      $(document).ready(function(){
          $("div.on-hover-content").hide();
      });

      $(".hover_cnt").click(function(){
        $(this).siblings("div.on-hover-content").fadeToggle( "slow", "linear" );
      })
   

    </script>
     

</body>

</html>

