<?php ob_start();
 error_reporting(0);
 session_start();

 $admin_id = $_SESSION['admin_id'];
 $Company_id=$_SESSION['comp_id'];
 $user_flag = $_SESSION['flag_id'];
 
 date_default_timezone_set('Asia/Kolkata');
 $Assign_Date=date('Y-m-d h:i:s');
$Cur_Date=date('d-m-Y h:i:s');
$cur_Date=date('Y-m-d',strtotime($Cur_Date));
$add_days = 2;
$Follow_Date = date('d-m-Y',strtotime($cur_Date) - (24*3600*$add_days));

if(empty($admin_id)){
header("Location:index.php");
}

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
 

//Display by Capture, Refer,Respond,&Close Buttons	
			
		if($_GET['disp']==2){	
		
			if(!isset($_GET['start'])) {  // This variable is set to zero for the first page
			$start = 0;
			}else{
			$start = $_GET['start'];
			}

			$eu = ($start - 0); 
			$limit = 500;         // No of records to be shown per page.
			$this1 = $eu + $limit; 
			$back = $eu - $limit; 
			$next = $eu + $limit;
	
			$busResult1 = $objQuery->mysqlSelect("a.doc_id as Doc_Id,b.contact_person as Partner_Name,b.Email_id as Email,b.cont_num1 as Cont_Num,b.location as City,a.system_ip as SystemIP,a.timestamp as LastUpdate","practice_login_tracker as a left join our_partners as b on a.doc_id=b.partner_id","a.type=2","a.timestamp desc","","","$eu, $limit");	
			$Total_Rslt = $objQuery->mysqlSelect("COUNT(DISTINCT(a.doc_id)) as count","practice_login_tracker as a left join our_partners as b on a.doc_id=b.partner_id","a.type=2","","","");	
			
			$pag_result = $objQuery->mysqlSelect("DISTINCT(a.doc_id) as PatId","practice_login_tracker as a left join our_partners as b on a.doc_id=b.partner_id","a.type=2","");


			$pageing = firstPaging($pag_result,$limit,$back,$next,$eu,$field,$type2);
			$arrPage = explode("-",$pageing);


		}
		if($_GET['disp']==1){
			
			if(!isset($_GET['start'])) {  // This variable is set to zero for the first page
			$start = 0;
			}else{
			$start = $_GET['start'];
			}

			$eu = ($start - 0); 
			$limit = 500;         // No of records to be shown per page.
			$this1 = $eu + $limit; 
			$back = $eu - $limit; 
			$next = $eu + $limit;
			
			if(isset($_GET['type']) && $_GET['type']=="1"){
			$busResult2 = $objQuery->mysqlSelect("ref_id,ref_name,doc_city,ref_address,TImestamp","referal","sponsor_id='2'","ref_id desc","","","$eu, $limit");	
			$Total_Rslt = $objQuery->mysqlSelect("COUNT(ref_id) as count","referal","sponsor_id='2'","","","");	
			$pag_result = $objQuery->mysqlSelect("ref_id","referal","sponsor_id='2'","");
			} 
			else{
			$busResult2 = $objQuery->mysqlSelect("ref_id,ref_name,doc_city,ref_address,TImestamp","referal","","ref_id desc","","","$eu, $limit");	
			$Total_Rslt = $objQuery->mysqlSelect("COUNT(ref_id) as count","referal","","","","");	
			$pag_result = $objQuery->mysqlSelect("ref_id","referal","","");	
				
			}

			$pageing = firstPaging($pag_result,$limit,$back,$next,$eu,$field,$type2);
			$arrPage = explode("-",$pageing);		
		
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

//Search By Referrals
if(isset($_POST['docType']) && $_POST['doc_type']!=0){
$doc_type = $_POST['doc_type'];
?>
<SCRIPT LANGUAGE="JavaScript">	
	window.location.href="<?php echo $_SERVER['PHP_SELF'];?>?disp=<?php echo $_POST['disp'];?>&type="+<?php echo $doc_type; ?>;
	</SCRIPT>
<?php
}
$ChkInUsrName= $objQuery->mysqlSelect("*","chckin_user","cmpny_id='".$Company_id."' and user_status='ACTIVE'","","","","");
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8" />
<title>Medisense Practice Tracker</title>
<?php include_once('support_file.php'); ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

  
  
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
		<span>Practice Tracker </span></h1>
		
		
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
	
	<a href="login-tracker.php?disp=1"  <?php if($_GET['disp']==1){ echo "class=active"; } ?>>PREMIUM </a>
	<a href="login-tracker.php?disp=2"  <?php if($_GET['disp']==2){ echo "class=active"; } ?>>STANDARD </a>
		
	</h3>
	</form>
</div>

 </div>
 
		
	<div class="mainSec"><?php
	if(isset($message)){ ?>
	<span class="success"><?php echo $message; ?></span>
	<?php	}
	?>
	
	 <b>Total No. of records :<?php echo $Total_Rslt[0]['count'];?></b><br>

	<div class="serchSec fr" style="float:right;">
		
	
	<form method="post" name="frmSrch" >
	<input type="hidden" name="docType" value="" />
	<input type="hidden" name="doc_type" value=""/>
	<input type="hidden" name="disp" value="<?php echo $_GET['disp'];?>"/>
			<select id="myselect" class="myselect fl" onchange="return srchDoctor(this.value);">
			<option value="0" selected>Search</option>
				<option value="1" <?php if($_GET['type']=="1") { echo "selected"; }?>>FDC</option>
				<option value="2" <?php if($_GET['type']=="2") { echo "selected"; }?> >All</option>
									 
			</select>
	</form>
							
						
							
							
	</div><br>
			<?php
			if($busResult1==true){ ?>
			<table class="tableclass1" width="100%">
			<form id="frmstatus" name="frmstatus" method="post" action="" onsubmit="return chkForm()">
			<input type="hidden" name="cmdStatus" value="">
			<input type="hidden" name="pat_id" value="">
			<input type="hidden" name="status_id" value="">
					<tr class="bgimg">
					<th>Doctor Name</th>
					<th>City</th>
					<th>No. of Appointments</th>
					<th>No. of Visits</th>					
					<th>System IP</th>
					<th>Last Login Date</th>
					
			
			
			</tr>
				<tr>
				<td colspan="10">
				<?php echo $arrPage[0];?>
				</td>
				</tr>
				<?php
				$j=0;
				foreach($busResult1 as $list){
					
		
			?>
			<tr style="background:#f1f2f2;">
				<td class="textAlign" ><?php echo $list['Partner_Name']; ?></td>
				<td class="textAlign" ><?php echo $list['Email']; ?></td>
				<td class="textAlign" ><?php echo $list['Cont_Num']; ?></td>
				<td class="textAlign"><?php echo $list['City'];?></td>
				<td class="textAlign"><?php echo $list['SystemIP'];?> </td>
				<td class="textAlign" ><?php echo date('d-M-Y H:i:s',strtotime($list['LastUpdate']));?></td>
				
				</tr>
				<?php $j++; }  ?>
				
				</table>
			<?php } ?>
			
				<?php
			if($busResult2==true){ ?>
			<table class="tableclass1" width="100%">
			<form id="frmstatus" name="frmstatus" method="post" action="" onsubmit="return chkForm()">
			<input type="hidden" name="cmdStatus" value="">
			<input type="hidden" name="pat_id" value="">
			<input type="hidden" name="status_id" value="">
					<tr class="bgimg">
					<th>Reg. Date</th>
					<th>Doctor Name</th>
					<th>City</th>
					<th>No. of Appointments</th>
					<th>No. of Visits</th>					
					<th>System IP</th>
					<th>Last Login Date</th>
					
			
			
			</tr>
				<tr>
				<td colspan="10">
				<?php echo $arrPage[0];?>
				</td>
				</tr>
				<?php
				$j=0;
				foreach($busResult2 as $list){
				$Total_Visit_Count = $objQuery->mysqlSelect("COUNT(a.patient_id) as Tot_App_Count","doc_patient_episodes as a left join doc_my_patient as b on a.patient_id=b.patient_id","a.admin_id='".$list['ref_id']."'","","","","");
				$Total_Appointment_Count = $objQuery->mysqlSelect("COUNT(id) as count","appointment_transaction_detail","pref_doc='".$list['ref_id']."'","","","","");
				
				$chkLoginTrack =$objQuery->mysqlSelect("*","practice_login_tracker","doc_id='".$list['ref_id']."' and type='1'","","","","");
				
		
			?>
			<tr style="background:#f1f2f2;">
			<td class="textAlign" ><?php echo date('d-M-Y',strtotime($list['TImestamp'])); ?></td>
				<td class="textAlign" ><?php echo $list['ref_name']; ?></td>
				<td class="textAlign"><?php echo $list['ref_address'];?></td>
				<td class="textAlign"><?php echo $Total_Appointment_Count[0]['count'];?></td>
				<td class="textAlign"><?php echo $Total_Visit_Count[0]['Tot_App_Count'];?></td>				
				<td class="textAlign"><?php if(count($chkLoginTrack)>0){ echo $chkLoginTrack[0]['system_ip']; }?> </td>
				<td class="textAlign" ><?php if(count($chkLoginTrack)>0){ echo date('d-M-Y H:i:s a',strtotime($chkLoginTrack[0]['timestamp']));} ?></td>
				
				</tr>
				<?php $j++; }  ?>
				
				</table>
			<?php
				
			} 
			?>
			</form>
			
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

</body>

</html>

