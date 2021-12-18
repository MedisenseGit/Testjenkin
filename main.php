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


if(isset($_POST['cmdStatus'])){
	$bus_id = $_POST['pat_id'];
	$status = $_POST['status_id'];
	$arrValues = array();
	$arrFields = array();

	$arrFields[] = 'assigned_to';
	$arrValues[] = $status;
	
	$arrFields[] = 'assign_date';
	$arrValues[] = $Assign_Date;

	$assignTask=$objQuery->mysqlUpdate('patient_tab',$arrFields,$arrValues,"patient_id='".$bus_id."'");
	
}
//Search By Date
/*if(isset($_POST['dateStatus']) && $_POST['Date_fld']!=0){
$Date_Fld1 = date('Y-m-d',strtotime($_POST['Date_fld']));
	
$busResult = $objQuery->mysqlSelect("*","patient_tab","TImestamp='".$Date_Fld1."'","TImestamp desc","","","");	
$Total_Rslt = $objQuery->mysqlSelect("COUNT(patient_id) as count","patient_tab","TImestamp='".$Date_Fld1."'","TImestamp desc","","","");		
}
else{
	$busResult = $objQuery->mysqlSelect("*","patient_tab ","","TImestamp desc","","","");	
	$Total_Rslt = $objQuery->mysqlSelect("COUNT(patient_id) as count","patient_tab ","","TImestamp desc","","","");	
}*/


//Search By Referrals
if(isset($_POST['refStatus']) && $_POST['refer_id']!=0){
$Ref_id = $_POST['refer_id'];
$disp_id = $_POST['disp_val'];
?>
<SCRIPT LANGUAGE="JavaScript">	
	window.location.href="<?php echo $_SERVER['PHP_SELF'];?>?refid="+<?php echo $Ref_id; ?>+"&disp="+<?php echo $disp_id; ?>;
	</SCRIPT>
<?php
}
/*if(isset($_GET['refid'])){
$busResultRef = $objQuery->mysqlSelect("DISTINCT(a.patient_id) as PatId","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","b.ref_id='".$_GET['refid']."'","a.TImestamp desc","","","");	
$Total_Ref_Rslt = $objQuery->mysqlSelect("COUNT(a.patient_id) as ref_count","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","b.ref_id='".$_GET['refid']."'","a.TImestamp desc","","","");		
}*/
if(isset($_GET['refid']) && empty($_GET['disp'])){
$totrefpat = $objQuery->mysqlSelect("DISTINCT(a.patient_id) as PatId","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","b.ref_id='".$_GET['refid']."'","a.TImestamp desc","","","");	
$Total_Ref_Rslt = $objQuery->mysqlSelect("COUNT(a.patient_id) as ref_count","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","b.ref_id='".$_GET['refid']."'","a.TImestamp desc","","","");		
$Only_Ref_Rslt = $objQuery->mysqlSelect("COUNT(a.patient_id) as ref_count","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","b.ref_id='".$_GET['refid']."'and b.status1=1 and b.bucket_status=2","a.TImestamp desc","","","");		
$Only_Responded_Rslt = $objQuery->mysqlSelect("COUNT(a.patient_id) as ref_count","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","b.ref_id='".$_GET['refid']."'and b.status1=1 and (b.bucket_status=5 or b.bucket_status=6)","a.TImestamp desc","","","");		
$Only_Converted_Rslt = $objQuery->mysqlSelect("COUNT(a.patient_id) as ref_count","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","b.ref_id='".$_GET['refid']."'and b.status1=1 and (b.bucket_status=7 or b.bucket_status=8)","a.TImestamp desc","","","");		

}
if(isset($_GET['refid']) && $_GET['disp']=='undefined'){
$totrefpat = $objQuery->mysqlSelect("DISTINCT(a.patient_id) as PatId","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","b.ref_id='".$_GET['refid']."'","a.TImestamp desc","","","");	
$Total_Ref_Rslt = $objQuery->mysqlSelect("COUNT(a.patient_id) as ref_count","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","b.ref_id='".$_GET['refid']."'","a.TImestamp desc","","","");		
$Only_Ref_Rslt = $objQuery->mysqlSelect("COUNT(a.patient_id) as ref_count","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","b.ref_id='".$_GET['refid']."'and b.status1=1 and b.bucket_status=2","a.TImestamp desc","","","");		
$Only_Responded_Rslt = $objQuery->mysqlSelect("COUNT(a.patient_id) as ref_count","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","b.ref_id='".$_GET['refid']."'and b.status1=1 and (b.bucket_status=5 or b.bucket_status=6)","a.TImestamp desc","","","");		
$Only_Converted_Rslt = $objQuery->mysqlSelect("COUNT(a.patient_id) as ref_count","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","b.ref_id='".$_GET['refid']."'and b.status1=1 and (b.bucket_status=7 or b.bucket_status=8)","a.TImestamp desc","","","");		
}
if((isset($_GET['refid']) && $_GET['disp']==4) || (isset($_GET['refid']) && $_GET['disp']==1) || (isset($_GET['refid']) && $_GET['disp']==5) || (isset($_GET['refid']) && $_GET['disp']==6) || (isset($_GET['refid']) && $_GET['disp']==8)){
$totrefpat = $objQuery->mysqlSelect("DISTINCT(a.patient_id) as PatId","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","b.ref_id='".$_GET['refid']."'","a.TImestamp desc","","","");	
$Total_Ref_Rslt = $objQuery->mysqlSelect("COUNT(a.patient_id) as ref_count","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","b.ref_id='".$_GET['refid']."'","a.TImestamp desc","","","");		
$Only_Ref_Rslt = $objQuery->mysqlSelect("COUNT(a.patient_id) as ref_count","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","b.ref_id='".$_GET['refid']."'and b.status1=1 and b.bucket_status=2","a.TImestamp desc","","","");		
$Only_Responded_Rslt = $objQuery->mysqlSelect("COUNT(a.patient_id) as ref_count","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","b.ref_id='".$_GET['refid']."'and b.status1=1 and (b.bucket_status=5 or b.bucket_status=6)","a.TImestamp desc","","","");		
$Only_Converted_Rslt = $objQuery->mysqlSelect("COUNT(a.patient_id) as ref_count","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","b.ref_id='".$_GET['refid']."'and b.status1=1 and (b.bucket_status=7 or b.bucket_status=8)","a.TImestamp desc","","","");		

}
if(isset($_GET['refid']) && $_GET['disp']==2){
$busResultRefState = $objQuery->mysqlSelect("DISTINCT(a.patient_id) as PatId","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id left join chat_notification as c on c.patient_id=a.patient_id","b.status1=1 and b.bucket_status=2 and c.status_id=2","c.TImestamp desc","","","");	
$Total_Ref_Rslt = $objQuery->mysqlSelect("COUNT(a.patient_id) as ref_count","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","b.ref_id='".$_GET['refid']."'","a.TImestamp desc","","","");		
$Only_Ref_Rslt = $objQuery->mysqlSelect("COUNT(a.patient_id) as ref_count","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","b.ref_id='".$_GET['refid']."'and b.status1=1 and b.bucket_status=2","a.TImestamp desc","","","");		
$Only_Responded_Rslt = $objQuery->mysqlSelect("COUNT(a.patient_id) as ref_count","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","b.ref_id='".$_GET['refid']."'and b.status1=1 and (b.bucket_status=5 or b.bucket_status=6)","a.TImestamp desc","","","");		
$Only_Converted_Rslt = $objQuery->mysqlSelect("COUNT(a.patient_id) as ref_count","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","b.ref_id='".$_GET['refid']."'and b.status1=1 and (b.bucket_status=7 or b.bucket_status=8)","a.TImestamp desc","","","");		

}

if(isset($_GET['refid']) && $_GET['disp']==3){
$ResultRespondState = $objQuery->mysqlSelect("DISTINCT(a.patient_id) as PatId","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","b.ref_id='".$_GET['refid']."'and b.status1=1 and (b.bucket_status=5 or b.bucket_status=6)","a.TImestamp desc","","","");	
$Total_Ref_Rslt = $objQuery->mysqlSelect("COUNT(a.patient_id) as ref_count","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","b.ref_id='".$_GET['refid']."'","a.TImestamp desc","","","");		
$Only_Ref_Rslt = $objQuery->mysqlSelect("COUNT(a.patient_id) as ref_count","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","b.ref_id='".$_GET['refid']."'and b.status1=1 and b.bucket_status=2","a.TImestamp desc","","","");		
$Only_Responded_Rslt = $objQuery->mysqlSelect("COUNT(a.patient_id) as ref_count","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","b.ref_id='".$_GET['refid']."'and b.status1=1 and (b.bucket_status=5 or b.bucket_status=6)","a.TImestamp desc","","","");		
$Only_Converted_Rslt = $objQuery->mysqlSelect("COUNT(a.patient_id) as ref_count","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","b.ref_id='".$_GET['refid']."'and b.status1=1 and (b.bucket_status=7 or b.bucket_status=8)","a.TImestamp desc","","","");		
}
if(isset($_GET['refid']) && $_GET['disp']==7){
$ResultConvertedState = $objQuery->mysqlSelect("DISTINCT(a.patient_id) as PatId","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","b.ref_id='".$_GET['refid']."'and b.bucket_status=5","a.TImestamp desc","","","");	
$Total_Ref_Rslt = $objQuery->mysqlSelect("COUNT(a.patient_id) as ref_count","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","b.ref_id='".$_GET['refid']."'","a.TImestamp desc","","","");		
$Only_Ref_Rslt = $objQuery->mysqlSelect("COUNT(a.patient_id) as ref_count","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","b.ref_id='".$_GET['refid']."'and b.status1=1 and b.bucket_status=2","a.TImestamp desc","","","");		
$Only_Responded_Rslt = $objQuery->mysqlSelect("COUNT(a.patient_id) as ref_count","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","b.ref_id='".$_GET['refid']."'and b.status1=1 and (b.bucket_status=5 or b.bucket_status=6)","a.TImestamp desc","","","");		
$Only_Converted_Rslt = $objQuery->mysqlSelect("COUNT(a.patient_id) as ref_count","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","b.ref_id='".$_GET['refid']."'and b.status1=1 and (b.bucket_status=7 or b.bucket_status=8)","a.TImestamp desc","","","");		
}

//Search By Assigned User
if(isset($_POST['assignStatus']) && $_POST['assign_id']!=0){
$Assign_id = $_POST['assign_id'];
?>
<SCRIPT LANGUAGE="JavaScript">	
	window.location.href="<?php echo $_SERVER['PHP_SELF'];?>?assignId="+<?php echo $Assign_id; ?>;
	</SCRIPT>
<?php
}	
if(isset($_GET['assignId'])){
$ResultAssigned = $objQuery->mysqlSelect("DISTINCT(patient_id) as PatId","patient_tab","assigned_to='".$_GET['assignId']."'","assign_date desc","","","");	
$Total_Rslt = $objQuery->mysqlSelect("COUNT(DISTINCT(patient_id)) as count","patient_tab","assigned_to='".$_GET['assignId']."'","","","","");		
}

//Search By Name,Email,Location & Contact No.
if(isset($_POST['postTextSrchCmd'])){
	$txtSearch = addslashes($_POST['postTextSrch']);
	?>
	<SCRIPT LANGUAGE="JavaScript">	
	window.location.href="main.php?srch=<?php echo $txtSearch; ?>";
	</SCRIPT>
	<?php
}
if(isset($_GET['srch'])){
	$SerchResult = $objQuery->mysqlSelect("DISTINCT(patient_id) as PatId","patient_tab","patient_id ='".$_GET['srch']."'or patient_mob ='".$_GET['srch']."'or patient_name LIKE '%".$_GET['srch']."%'or patient_email ='".$_GET['srch']."'or patient_loc LIKE '%".$_GET['srch']."%'or patient_desc LIKE '%".$_GET['srch']."%'or patient_complaint LIKE '%".$_GET['srch']."%'" ,"TImestamp desc","","","");	
	} 

//Display by Capture, Refer,Respond,&Close Buttons	
			
		if($_GET['disp']==2 && empty($_GET['refid'])){	
		
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
	
			$busResult2 = $objQuery->mysqlSelect("DISTINCT(a.patient_id) as PatId","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id left join chat_notification as c on c.patient_id=a.patient_id","b.status1=1 and b.bucket_status=2 and c.status_id=2","c.TImestamp desc","","","$eu, $limit");	
			$Total_Rslt = $objQuery->mysqlSelect("COUNT(DISTINCT(a.patient_id)) as count","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id left join chat_notification as c on c.patient_id=a.patient_id","b.status1=1 and b.bucket_status=2 and c.status_id=2","","","");	
			
			$pag_result = $objQuery->mysqlSelect("DISTINCT(a.patient_id) as PatId","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id left join chat_notification as c on c.patient_id=a.patient_id","b.status1=1 and  b.bucket_status=2 and c.status_id=2","");


			$pageing = firstPaging($pag_result,$limit,$back,$next,$eu,$field,$type2);
			$arrPage = explode("-",$pageing);


		}
		if($_GET['disp']==3 && empty($_GET['refid']) && empty($_GET['assignId'])){
			
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
			
			$busResult3 = $objQuery->mysqlSelect("DISTINCT(a.patient_id) as PatId","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","b.status1=1 and (b.bucket_status=5 or b.bucket_status=6)","a.TImestamp desc","","","$eu, $limit");	
			$Total_Rslt = $objQuery->mysqlSelect("COUNT(DISTINCT(a.patient_id)) as count","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","b.status1=1 and (b.bucket_status=5 or b.bucket_status=6)","","","","");	
			
			$pag_result = $objQuery->mysqlSelect("DISTINCT(a.patient_id) as PatId","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","b.status1=1 and (b.bucket_status=5 or b.bucket_status=6)","");


			$pageing = firstPaging($pag_result,$limit,$back,$next,$eu,$field,$type2);
			$arrPage = explode("-",$pageing);		
		
		}
		//Auto Responded Patient Records
		if($_GET['disp']==8 && empty($_GET['refid']) && empty($_GET['assignId'])){
			
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
			
			$busResult8 = $objQuery->mysqlSelect("DISTINCT(a.patient_id) as PatId","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","b.status1=1 and b.bucket_status=5 and response_status=1","a.TImestamp desc","","","$eu, $limit");	
			$Total_Rslt = $objQuery->mysqlSelect("COUNT(DISTINCT(a.patient_id)) as count","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","b.status1=1 and b.bucket_status=5 and response_status=1","","","","");	
			
			$pag_result = $objQuery->mysqlSelect("DISTINCT(a.patient_id) as PatId","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","b.status1=1 and b.bucket_status=5 and response_status=1","");


			$pageing = firstPaging($pag_result,$limit,$back,$next,$eu,$field,$type2);
			$arrPage = explode("-",$pageing);		
		
		}
		if($_GET['disp']==4 && empty($_GET['refid'])){
			
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
			
			$busResult4 = $objQuery->mysqlSelect("DISTINCT(a.patient_id) as PatId","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","b.status1=2","a.TImestamp desc","","","$eu, $limit");	
			$Total_Rslt = $objQuery->mysqlSelect("COUNT(DISTINCT(a.patient_id)) as count","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","b.status1=2","a.TImestamp desc","","","");	
			
			$pag_result = $objQuery->mysqlSelect("DISTINCT(a.patient_id) as PatId","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","b.status1=2","");


			$pageing = firstPaging($pag_result,$limit,$back,$next,$eu,$field,$type2);
			$arrPage = explode("-",$pageing);
		
		}
		if($_GET['disp']==1 && empty($_GET['refid'])){
			
			if(!isset($_GET['start'])) {  // This variable is set to zero for the first page
			$start = 0;
			}else{
			$start = $_GET['start'];
			}

			$eu = ($start - 0); 
			$limit = 30;         // No of records to be shown per page.
			$this1 = $eu + $limit; 
			$back = $eu - $limit; 
			$next = $eu + $limit;
			
			$busResult1 = $objQuery->mysqlSelect("DISTINCT(a.patient_id) as PatId","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","b.status1=1 and b.bucket_status=1","a.patient_id desc","","","$eu, $limit");	
			$Total_Rslt = $objQuery->mysqlSelect("COUNT(DISTINCT(a.patient_id)) as count","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","b.status1=1 and b.bucket_status=1","a.TImestamp desc","","","");	
			
			$pag_result = $objQuery->mysqlSelect("DISTINCT(a.patient_id) as PatId","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","b.status1=1 and b.bucket_status=1","");

			$pageing = firstPaging($pag_result,$limit,$back,$next,$eu,$field,$type2);
			$arrPage = explode("-",$pageing);
		
		}
		if($_GET['disp']==5 && empty($_GET['refid'])){
			
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
			
			$busResult5 = $objQuery->mysqlSelect("DISTINCT(b.patient_id) as PatId","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","b.status1=1 and b.bucket_status=3","a.TImestamp desc","","","$eu, $limit");	
			$Total_Rslt = $objQuery->mysqlSelect("COUNT(DISTINCT(b.patient_id)) as count","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","b.status1=1 and b.bucket_status=3","a.TImestamp desc","","","");	
			
			$pag_result = $objQuery->mysqlSelect("DISTINCT(b.patient_id) as PatId","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","b.status1=1 and b.bucket_status=3","");

			$pageing = firstPaging($pag_result,$limit,$back,$next,$eu,$field,$type2);
			$arrPage = explode("-",$pageing);
		
		}
		if($_GET['disp']==6 && empty($_GET['refid'])){
			
			$busResult6 = $objQuery->mysqlSelect("DISTINCT(a.patient_id) as PatId","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","a.assigned_to='".$admin_id."'","a.assign_date desc","","","");	
			$Total_Rslt = $objQuery->mysqlSelect("COUNT(DISTINCT(a.patient_id)) as count","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","a.assigned_to='".$admin_id."'","","","","");	
		}
		if($_GET['disp']==7 && empty($_GET['refid'])){
			
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
			
			$busResult7 = $objQuery->mysqlSelect("DISTINCT(a.patient_id) as PatId","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","b.status1=1 and (b.bucket_status=7 or b.bucket_status=8)","a.TImestamp desc","","","$eu, $limit");	
			$Total_Rslt = $objQuery->mysqlSelect("COUNT(DISTINCT(a.patient_id)) as count","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","b.status1=1 and (b.bucket_status=7 or b.bucket_status=8)","a.TImestamp desc","","","");	
			
			$pag_result = $objQuery->mysqlSelect("DISTINCT(a.patient_id) as PatId","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","b.status1=1 and (b.bucket_status=7 or b.bucket_status=8)","");

			$pageing = firstPaging($pag_result,$limit,$back,$next,$eu,$field,$type2);
			$arrPage = explode("-",$pageing);
		
		}
		/*if($_GET['disp']==8 && empty($_GET['refid'])){
			
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
			
			$busResult8 = $objQuery->mysqlSelect("DISTINCT(a.subscribe_id) as SubId","subscription as a left join subscribe_family as b on a.subscribe_id=b.subscribe_id","","a.reg_date desc","","","$eu, $limit");	
			$Total_Rslt = $objQuery->mysqlSelect("COUNT(DISTINCT(a.subscribe_id)) as count","subscription as a left join subscribe_family as b on a.subscribe_id=b.subscribe_id","","a.reg_date desc","","","");	
			
			$pag_result = $objQuery->mysqlSelect("*","subscription as a left join subscribe_family as b on a.subscribe_id=b.subscribe_id","","");

			$pageing = firstPaging($pag_result,$limit,$back,$next,$eu,$field,$type2);
			$arrPage = explode("-",$pageing);
		
		}*/
		//Invoice Bucket
		if($_GET['disp']==9 && empty($_GET['refid'])){
			
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
			
			$busResult9 = $objQuery->mysqlSelect("DISTINCT(a.patient_id) as PatId","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","b.status1=1 and (b.bucket_status=9 or b.bucket_status=11 or b.bucket_status=12)","a.TImestamp desc","","","$eu, $limit");	
			$Total_Rslt = $objQuery->mysqlSelect("COUNT(DISTINCT(a.patient_id)) as count","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","b.status1=1 and (b.bucket_status=9 or b.bucket_status=11 or b.bucket_status=12)","a.TImestamp desc","","","");	
			
			$pag_result = $objQuery->mysqlSelect("DISTINCT(a.patient_id) as PatId","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","b.status1=1 and (b.bucket_status=9 or b.bucket_status=11 or b.bucket_status=12)","");

			$pageing = firstPaging($pag_result,$limit,$back,$next,$eu,$field,$type2);
			$arrPage = explode("-",$pageing);
		
		}
		//header($_SERVER['PHP_SELF']);

		

					
$Total_Count = $objQuery->mysqlSelect("COUNT(patient_id) as count","patient_tab","","","","","");
$User_Entry = $objQuery->mysqlSelect("COUNT(patient_id) as count","patient_tab","user_id='".$admin_id."'and system_date='".$cur_Date."'","","","","");

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


$ChkInUsrName= $objQuery->mysqlSelect("*","chckin_user","cmpny_id='".$Company_id."' and user_status='ACTIVE'","","","","");
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8" />
<title>Medisense CRM</title>
<?php include_once('support_file.php'); ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

  
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	<script type="text/javascript" src="jquery.searchabledropdown-1.0.8.min.js"></script>
	
	
	<script type="text/javascript">
		$(document).ready(function() {
			$("#myselect").searchable();
		});
		$(document).ready(function() {
			$("#myselect1").searchable();
		});
	
	
		// demo functions
		$(document).ready(function() {
			$("#value").html($("#myselect :selected").text() + " (VALUE: " + $("#myselect").val() + ")");
			$("#myselect").change(function(){
				$("#value").html(this.options[this.selectedIndex].text + " (VALUE: " + this.value + ")");
			});
		});
		$(document).ready(function() {
			$("#value").html($("#myselect1 :selected").text() + " (VALUE: " + $("#myselect1").val() + ")");
			$("#myselect1").change(function(){
				$("#value").html(this.options[this.selectedIndex].text + " (VALUE: " + this.value + ")");
			});
		});
	
		function modifySelect() {
			$("#myselect").get(0).selectedIndex = 5;
			$("#myselect1").get(0).selectedIndex = 5;
		}
	
		function appendSelectOption(str) {
			$("#myselect").append("<option value=\"" + str + "\">" + str + "</option>");
			$("#myselect1").append("<option value=\"" + str + "\">" + str + "</option>");
		}
	
		function applyOptions() {			  
			$("#myselect").searchable({
				maxListSize: $("#maxListSize").val(),
				maxMultiMatch: $("#maxMultiMatch").val(),
				latency: $("#latency").val(),
				exactMatch: $("#exactMatch").get(0).checked,
				wildcards: $("#wildcards").get(0).checked,
				ignoreCase: $("#ignoreCase").get(0).checked
			});
			$("#myselect1").searchable({
				maxListSize: $("#maxListSize").val(),
				maxMultiMatch: $("#maxMultiMatch").val(),
				latency: $("#latency").val(),
				exactMatch: $("#exactMatch").get(0).checked,
				wildcards: $("#wildcards").get(0).checked,
				ignoreCase: $("#ignoreCase").get(0).checked
			});
	
			alert(
				"OPTIONS\n---------------------------\n" + 
				"maxListSize: " + $("#maxListSize").val() + "\n" +
				"maxMultiMatch: " + $("#maxMultiMatch").val() + "\n" +
				"exactMatch: " + $("#exactMatch").get(0).checked + "\n"+
				"wildcards: " + $("#wildcards").get(0).checked + "\n" +
				"ignoreCase: " + $("#ignoreCase").get(0).checked + "\n" +
				"latency: " + $("#latency").val()
			);
		}
	</script>
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
		<span>Patient Details </span></h1>
		
		<?php if($User_Entry[0]['count']>0){ ?>
		<h4 class="headfont fr">
		<span>Today's Entry : <?php echo $User_Entry[0]['count']; ?> </span></h4><?php }?>
		</div>
	</div>
	
	<!-- LOGIN SECTION -->
	<script language="JavaScript" src="js/validation.js"></script>
	<?php if($user_flag==0 || $user_flag==1) { ?>
	<div class="serchSec fl">
		
	<!--<form method="post" name="frmDate" >
		<input type="hidden" name="dateStatus" value="" />
		<input type="hidden" name="Date_fld" value=""/>
		<input type="text" name="datepicker" id="datepicker" value="<?php echo $_POST['Date_fld']; ?>" class="datefield fl" onchange="return srchDate(this.value);">
	</form>-->
	
	<form method="post" name="frmSrch" >
	<input type="hidden" name="refStatus" value="" />
	<input type="hidden" name="refer_id" value=""/>
	<input type="hidden" name="disp_val" value=""/>
			<?php if(!empty($_GET['disp'])){ ?><select id="myselect" class="myselect fl" onchange="return srchRef(this.value,<?php echo $_GET['disp']; ?>);"><?php }
			else { ?><select id="myselect" class="myselect fl" onchange="return srchRef(this.value);"><?php } ?>
			<option value="0" selected>Search By Referer</option>
								<?php 
								$chkRefName= $objQuery->mysqlSelect("*","referal","ref_id='".$_GET['refid']."'","ref_name asc","","","");
								$i=30;
								
										if($chkRefName==true){
											
											?>
										<option value="<?php echo stripslashes($chkRefName[0]['ref_id']);?>" selected/>
										<?php echo stripslashes($chkRefName[0]['ref_name']);?></option>
										<?php }
								$RefName= $objQuery->mysqlSelect("*","referal","","ref_name asc","","","");
								foreach($RefName as $RefNameList){	
								$SpecName= $objQuery->mysqlSelect("*","specialization","spec_id='".$RefNameList['doc_spec']."'","","","","");
								?> 
										
										<option value="<?php echo stripslashes($RefNameList['ref_id']);?>" />
										<?php echo stripslashes($RefNameList['ref_name']);?><?php echo "&nbsp;".$SpecName[0]['spec_name']."&nbsp;".$RefNameList['ref_address']; ?></option>
									
									
									<?php 
										$i++;
									}
									?> 
			</select>
	</form>
							
						
							
							<form method="post" name="frmSrchBox">
								<input type="hidden" name="postTextSrchCmd" value="" />
								<input type="hidden" name="postTextSrch" value="" />
								<input name="txtSearch"  placeholder="Search" type="text" onchange="return srchText(this.value);" class="txtfield fr"/>
							</form>
							<form method="post" name="frmSrchAssign" >
		<input type="hidden" name="assignStatus" value="" />
		<input type="hidden" name="assign_id" value=""/>
		<!--<label class="fl" style="color:#5a5a5a; font-size:10px; font-weight:bold; margin-left:20px;">Search By <br>Assigned User : </label>-->
		<select  class="myselect fl" onchange="return srchAssign(this.value);">	
							<option value="0" selected>Search Assigned User</option>
								<?php 
								$chkAssignName= $objQuery->mysqlSelect("*","chckin_user","chk_userid='".$_GET['assignId']."'","chk_username asc","","","");
								$i=30;
								
										if($chkAssignName==true){
											
											?>
										<option value="<?php echo stripslashes($chkAssignName[0]['chk_userid']);?>" selected/>
										<?php echo stripslashes($chkAssignName[0]['chk_username']);?></option>
										<?php }
								$UserName= $objQuery->mysqlSelect("*","chckin_user","","chk_username asc","","","");
								foreach($UserName as $UserNameList){	
								?> 
										
										<option value="<?php echo stripslashes($UserNameList['chk_userid']);?>" />
										<?php echo stripslashes($UserNameList['chk_username']);?></option>
									
									
									<?php 
										$i++;
									}
									?>   
							</select>
							
	</form>
	</div>
	<?php } ?>
	<?php include_once('main_nav.php'); ?>
		
	<div class="mainSec"><?php
	if(isset($message)){ ?>
	<span class="success"><?php echo $message; ?></span>
	<?php	}
	?>
	
	 <b>Total No. of records :<?php echo $Total_Count[0]['count'];?></b><br>
	 <?php if($_GET['refid']!=""){ ?><b><a href="<?php $_SERVER["PHP_SELF"] ?>?refid=<?php echo $_GET['refid']; ?>">Total Patient Refered :<?php echo $Total_Ref_Rslt[0]['ref_count'];?></a></b><br><?php } ?>
	<?php if($Only_Ref_Rslt[0]['ref_count']!=""){ ?><b><a href="<?php $_SERVER["PHP_SELF"] ?>?refid=<?php echo $_GET['refid']; ?>&disp=2">Refered Patient  :<?php echo $Only_Ref_Rslt[0]['ref_count'];?></a></b><br><?php } ?>
	<?php if($Only_Responded_Rslt[0]['ref_count']!=""){ ?><b><a href="<?php $_SERVER["PHP_SELF"] ?>?refid=<?php echo $_GET['refid']; ?>&disp=3">Responded Patient  :<?php echo $Only_Responded_Rslt[0]['ref_count'];?></a></b><br><?php } ?>
	<?php if($Only_Converted_Rslt[0]['ref_count']!=""){ ?><b><a href="<?php $_SERVER["PHP_SELF"] ?>?refid=<?php echo $_GET['refid']; ?>&disp=7">Converted Patient  :<?php echo $Only_Converted_Rslt[0]['ref_count'];?></a></b><br><?php } ?>
	
	
			<?php
			if($busResult1==true){ ?>
			<table class="tableclass1">
			<form id="frmstatus" name="frmstatus" method="post" action="" onsubmit="return chkForm()">
			<input type="hidden" name="cmdStatus" value="">
			<input type="hidden" name="pat_id" value="">
			<input type="hidden" name="status_id" value="">
					<tr class="bgimg"><th>Date & Time</th>
					<th>Patient ID</th>
					<th>Patient Name</th>
					<th>Source</th>
					<th>Status</th>
					<th>Contact no.</th>
					<th>Location</th>
					<th>Assigned To</th>						
					<th>Email</th>
					<th>Edit</th>
			
			
			</tr>
				<tr>
				<td colspan="10">
				<?php echo $arrPage[0];?>
				</td>
				</tr>
				<?php
				$j=0;
				foreach($busResult1 as $list){
					$DistinctRslt = $objQuery->mysqlSelect("*","patient_tab","patient_id='".$list['PatId']."'","TImestamp desc","","","");
						if($DistinctRslt[0]['urgentState']==1){
						$color = "<span style='font-size:18px;  color:red;font-weight:bold;'>*</span>";
						}else{
						$color = "<span style='font-size:16px;  color:red;font-weight:bold;'></span>";
						}
						
						$Get_Feedback = $objQuery->mysqlSelect("*","patient_feedback","patient_id='".$DistinctRslt[0]['patient_id']."'","","","","");

						if($Get_Feedback[0]['pat_resp_status']!=0 && $Get_Feedback[0]['pat_resp_status']==1){
						$FedStatus = "<span><img src='images/tickgreen.png' /></span>";
						}else if($Get_Feedback[0]['pat_resp_status']!=0 && $Get_Feedback[0]['pat_resp_status']==2){
						$FedStatus = "<span><img src='images/tickred.png' /></span>";
						}
						
						if($DistinctRslt[0]['opinion_for']==1){
							$color_corona = "<span style='font-size:10px; color:red;font-weight:bold;'> - Corona</span>";
						}
						else{
							$color_corona = "";
						}
			
			$chkStatus = $objQuery->mysqlSelect("*","patient_referal","patient_id='".$list['PatId']."'and bucket_status=1","","","","");
			$getSource = $objQuery->mysqlSelect("*","source_list","source_id='".$DistinctRslt[0]['patient_src']."'" ,"","","","");
		if($chkStatus==true){
			$status="<span style='font-size:12px; color:red;font-weight:bold;'>NEW</span>";
		}
		
		
			?>
			<tr style="background:#f1f2f2;">
				<td class="textAlign"><?php echo $Date=date('d-M-Y, h:i',strtotime($DistinctRslt[0]['TImestamp']));?></td>
				<td class="textAlign"><?php if($DistinctRslt[0]['transaction_status']=="TXN_SUCCESS"){ ?><span style="background:#f4081e; padding:5px; color:#fff;"><?php echo $DistinctRslt[0]['patient_id']; ?></span><img src="images/paid_icon.png"/><?php } else { ?><span style="background:#04bad5; padding:5px; color:#fff;"><?php echo $DistinctRslt[0]['patient_id']; ?></span><?php } if($DistinctRslt[0]['transaction_status']=="Pending"){?><img src="images/pending.png"/><?php } ?> </td>
				<td class="textAlign" ><a href="Patient_History.php?pat_id=<?php echo $DistinctRslt[0]['patient_id']; if(!empty($_GET['disp'])) { ?>&disp=<?php echo $_GET['disp']; } if(!empty($_GET['assignId'])) {?>&assignId=<?php echo $_GET['assignId']; } if(!empty($_GET['refid'])) { ?>&refid=<?php echo $_GET['refid']; } if(!empty($_GET['start'])) { ?>&start=<?php echo $_GET['start']; } ?>"><?php echo $DistinctRslt[0]['patient_name']; ?><?php echo $color; ?><?php if($Get_Feedback[0]['pat_resp_status']!=0){ echo $FedStatus; } ?></a></td>
				<td class="textAlign"><?php if($DistinctRslt[0]['patient_src']==11) { ?><img src="images/mas_icon.png" /> 
				<?php } else if($DistinctRslt[0]['patient_src']==13) { ?><span style="width:10px; padding:3px; color:#fff; background:red;">Sushrutt</span><?php } else if($DistinctRslt[0]['patient_src']==14) { ?><span style="width:10px; padding:3px; color:#fff; background:red;">Mullen-Us</span><?php } else if($DistinctRslt[0]['patient_src']==281) { ?><span style="width:10px; padding:3px; color:#fff; background:red;">Jio-Health</span><?php } else { echo "Medisensehealth.com"; } ?></td>
				<td class="textAlign" ><?php echo $status. "". $color_corona; ?></td>
				<td class="textAlign"><?php echo $DistinctRslt[0]['patient_mob']; ?></td>
				<td class="textAlign" ><?php echo $DistinctRslt[0]['patient_loc']; ?></td>
				<td class="textAlign"><select name="slctAssign" onchange="return Assignsubmit(this.value,<?php echo $DistinctRslt[0]['patient_id']; ?>);">
				<?php 
				
				
				?>
				<option value="0">Select</option>
				<?php 
								
									$i=30;
									
									foreach($ChkInUsrName as $UsrNameList){
										if($UsrNameList['chk_userid']==$DistinctRslt[0]['assigned_to']){
								?> 
									<option value="<?php echo stripslashes($UsrNameList['chk_userid']);?>" selected>
										<?php echo stripslashes($UsrNameList['chk_username']);?></option>
									<?php }?>
										<option value="<?php echo stripslashes($UsrNameList['chk_userid']);?>" >
										<?php echo stripslashes($UsrNameList['chk_username']);?></option>
									
									
									<?php 
										$i++;
										
									}?> 
				
				</select></td>
				
				<td class="textAlign"><?php echo $DistinctRslt[0]['patient_email']; ?></td>
				<!--<td class="textAlign"><a href="Add_Patient_Attachments.php?pat_id=<?php echo $DistinctRslt[0]['patient_id'];  ?>" title="Add/View Patient Attachments"><img src="images/i-9.png" /></a></td>-->
				<td class="textAlign"><a href="Edit-Patient.php?pat_id=<?php echo $DistinctRslt[0]['patient_id'];?><?php if(!empty($_GET['disp'])) { ?>&disp=<?php echo $_GET['disp']; } if(!empty($_GET['assignId'])) { ?>&assignId=<?php echo $_GET['assignId']; } if(!empty($_GET['refid'])) { ?>&refid=<?php echo $_GET['refid']; } if(!empty($_GET['start'])) { ?>&start=<?php echo $_GET['start']; } ?>" title="Edit Patient Details"><img src="images/i-5.png" /></a></td>
				<!--<td class="textAlign"><?php echo $Follow_Date=date('d-m-Y',strtotime($DistinctRslt[0]['follow_date']));?></td>-->
				
				</tr>
				<?php $j++; }  ?>
				
				</table>
			<?php } ?>
			
				<?php
			if($busResult2==true){ ?>
			<table class="tableclass1">
			<form id="frmstatus" name="frmstatus" method="post" action="" onsubmit="return chkForm()">
			<input type="hidden" name="cmdStatus" value="">
			<input type="hidden" name="pat_id" value="">
			<input type="hidden" name="status_id" value="">
					<tr class="bgimg"><th>Refered Date</th>
					<th>Patient ID</th>
					<th>Patient Name</th>
					<th>Source</th>
					<th>Status</th>
					<th>Contact no.</th>
					<th>Location</th>
					<th>Assigned To</th>						
					<th>Email</th>
					<!--<th>Add / View Attachments</th>-->
					<th>Department</th>
			
			
			</tr>
				<tr>
				<td colspan="10">
				<?php echo $arrPage[0];?>
				</td>
				</tr>
				
				<?php
				$j=0;
				foreach($busResult2 as $list){
					$DistinctRslt = $objQuery->mysqlSelect("*","patient_tab","patient_id='".$list['PatId']."'","","","",""); 
					$DistinctPatReferal = $objQuery->mysqlSelect("*","patient_referal","patient_id='".$list['PatId']."'","","","",""); 
					 if($DistinctRslt[0]['urgentState']==1){
						$urgentState = "<span style='font-size:18px;  color:red;font-weight:bold;'>*</span>";
						}else{
						$urgentState = "<span style='font-size:16px;  color:red;font-weight:bold;'></span>";
						}
						
						$Get_Feedback = $objQuery->mysqlSelect("*","patient_feedback","patient_id='".$list['PatId']."'","","","","");

						if($Get_Feedback[0]['pat_resp_status']!=0 && $Get_Feedback[0]['pat_resp_status']==1){
						$FedStatus = "<span><img src='images/tickgreen.png' /></span>";
						}else if($Get_Feedback[0]['pat_resp_status']!=0 && $Get_Feedback[0]['pat_resp_status']==2){
						$FedStatus = "<span><img src='images/tickred.png' /></span>";
						}
			
			$chkStatus = $objQuery->mysqlSelect("*","chat_notification","patient_id='".$list['PatId']."'and status_id=2","","","","");
			$getDept = $objQuery->mysqlSelect("*","specialization","spec_id='".$DistinctRslt[0]['medDept']."'" ,"","","","");
	
		date_default_timezone_set('Asia/Kolkata');
		$cur_Date=date('Y-m-d h:i:s');
		$subtract_days = 2;
		$Check_Date = date('Y-m-d h:i:s',strtotime($cur_Date) - (24*3600*$subtract_days));
		
		$Ref_Date = date('d-m-Y, h:i',strtotime($chkStatus[0]['TImestamp']));
		
	if($chkStatus==true)
		{
			if($chkStatus[0]['TImestamp']<= $Check_Date){
				if($DistinctPatReferal[0]['status2']==14){
					$status="<span style='font-size:12px; color:red;font-weight:bold;'>NOT RESPONDED</span>";
				} else {
				$status="<span style='font-size:12px; color:red;font-weight:bold;'>REFERRED</span>";
				}
			}else{
			$status="<span style='font-size:12px; color:#f9e223;font-weight:bold;'>REFERRED</span>";
			}
			
			
			
		}
		
			?>
			
			<tr style="background:#f1f2f2;">
				<td class="textAlign"><?php echo $Ref_Date;?></td>
				<td class="textAlign"><?php if($DistinctRslt[0]['transaction_status']=="TXN_SUCCESS"){ ?><span style="background:#f4081e; padding:5px; color:#fff;"><?php echo $DistinctRslt[0]['patient_id']; ?></span><img src="images/paid_icon.png"/><?php } else { ?><span style="background:#04bad5; padding:5px; color:#fff;"><?php echo $DistinctRslt[0]['patient_id']; ?></span><?php }  if($DistinctRslt[0]['transaction_status']=="Pending"){?><img src="images/pending.png"/><?php } ?></td>
				<td class="textAlign" ><a href="Patient_History.php?pat_id=<?php echo $DistinctRslt[0]['patient_id']; if(!empty($_GET['disp'])) { ?>&disp=<?php echo $_GET['disp']; } if(!empty($_GET['assignId'])) {?>&assignId=<?php echo $_GET['assignId']; } if(!empty($_GET['refid'])) { ?>&refid=<?php echo $_GET['refid']; } if(!empty($_GET['start'])) { ?>&start=<?php echo $_GET['start']; }?>"><?php echo $DistinctRslt[0]['patient_name']; ?><?php echo $urgentState; ?><?php if($Get_Feedback[0]['pat_resp_status']!=0){ echo $FedStatus; } ?></a></td>
				<td class="textAlign"><?php if($DistinctRslt[0]['patient_src']==11) { ?><img src="images/mas_icon.png" /> 
				<?php } else if($DistinctRslt[0]['patient_src']==13) { ?><span style="width:10px; padding:3px; color:#fff; background:red;">Sushrutt</span><?php } else if($DistinctRslt[0]['patient_src']==14) { ?><span style="width:10px; padding:3px; color:#fff; background:red;">Mullen-Us</span><?php } else if($DistinctRslt[0]['patient_src']==281) { ?><span style="width:10px; padding:3px; color:#fff; background:red;">Jio-Health</span><?php } else { echo "Medisensehealth.com"; } ?></td>
				<td class="textAlign" ><?php echo $status; ?></td>
				<td class="textAlign"><?php echo $DistinctRslt[0]['patient_mob']; ?></td>
				<td class="textAlign" ><?php echo $DistinctRslt[0]['patient_loc']; ?></td>
				<td class="textAlign"><select name="slctAssign" onchange="return Assignsubmit(this.value,<?php echo $DistinctRslt[0]['patient_id']; ?>);">
				<?php 
				
				
				?>
				<option value="0">Select</option>
				<?php 
								$i=30;
									
									foreach($ChkInUsrName as $UsrNameList){
										if($UsrNameList['chk_userid']==$DistinctRslt[0]['assigned_to']){
								?> 
									<option value="<?php echo stripslashes($UsrNameList['chk_userid']);?>" selected>
										<?php echo stripslashes($UsrNameList['chk_username']);?></option>
									<?php }?>
										<option value="<?php echo stripslashes($UsrNameList['chk_userid']);?>" >
										<?php echo stripslashes($UsrNameList['chk_username']);?></option>
									
									
									<?php 
										$i++;
										
									}?> 
				
				</select></td>
				
				<td class="textAlign"><?php echo $DistinctRslt[0]['patient_email']; ?></td>
				<td class="textAlign"><?php echo $getDept[0]['spec_name'];?></td>
				<!--<td class="textAlign"><?php echo $Follow_Date=date('d-m-Y',strtotime($DistinctRslt[0]['follow_date']));?></td>-->
				
				</tr>
				<?php $j++; }  ?>
				
				</table>
			<?php
				
			} 
			if($busResult3==true){
				?>
				<table class="tableclass1">
			<form id="frmstatus" name="frmstatus" method="post" action="" onsubmit="return chkForm()">
			<input type="hidden" name="cmdStatus" value="">
			<input type="hidden" name="pat_id" value="">
			<input type="hidden" name="status_id" value="">
					<tr class="bgimg"><th>Date & Time</th>
					<th>Patient ID</th>
					<th>Patient Name</th>
					<th>Source</th>
					<th>Status</th>
					<th>Contact no.</th>
					<th>Location</th>
					<th>Assigned To</th>						
					<th>Email</th>
					<!--<th>Add / View Attachments</th>-->
					<th>Edit</th>
			
			
			</tr>
				<tr>
				<td colspan="10">
				<?php echo $arrPage[0];?>
				</td>
				</tr>
				<?php
				$j=0;
				foreach($busResult3 as $list){
					$DistinctRslt = $objQuery->mysqlSelect("*","patient_tab","patient_id='".$list['PatId']."'","","","",""); 
					 if($DistinctRslt[0]['urgentState']==1){
						$color = "<span style='font-size:18px;  color:red;font-weight:bold;'>*</span>";
						}else{
						$color = "<span style='font-size:16px;  color:red;font-weight:bold;'></span>";
						}
						
						$Get_Feedback = $objQuery->mysqlSelect("*","patient_feedback","patient_id='".$list['PatId']."'","","","","");

						if($Get_Feedback[0]['pat_resp_status']!=0 && $Get_Feedback[0]['pat_resp_status']==1){
						$FedStatus = "<span><img src='images/tickgreen.png' /></span>";
						}else if($Get_Feedback[0]['pat_resp_status']!=0 && $Get_Feedback[0]['pat_resp_status']==2){
						$FedStatus = "<span><img src='images/tickred.png' /></span>";
						}
			
			
			$chkStatus = $objQuery->mysqlSelect("*","patient_referal","patient_id='".$list['PatId']."'and (bucket_status=5 or bucket_status=6)","","","","");
			$getSource = $objQuery->mysqlSelect("*","source_list","source_id='".$DistinctRslt[0]['patient_src']."'" ,"","","","");
		
	if($chkStatus[0]['bucket_status']==5 && $chkStatus[0]['response_status']==2)
		{
			$status="<span style='font-size:12px; color:#01d337;font-weight:bold;'>RESPONDED</span>";
		}
		if($chkStatus[0]['bucket_status']==5 && $chkStatus[0]['response_status']==1)
		{
			$status="<span style='font-size:12px; color:red;font-weight:bold;'>AUTO-RESPONDED</span>";
		}
		if($chkStatus[0]['bucket_status']==6)
		{
			$status="<span style='font-size:12px; color:#01d337;font-weight:bold;'>RESPONSE-P FAILED</span>";
		}
		
			?>
			<tr style="background:#f1f2f2;">
				<td class="textAlign" ><?php echo $Date=date('d-m-Y',strtotime($DistinctRslt[0]['TImestamp']));?></td>
				<td class="textAlign"><?php if($DistinctRslt[0]['transaction_status']=="TXN_SUCCESS"){ ?><span style="background:#f4081e; padding:5px; color:#fff;"><?php echo $DistinctRslt[0]['patient_id']; ?></span><img src="images/paid_icon.png"/><?php } else { ?><span style="background:#04bad5; padding:5px; color:#fff;"><?php echo $DistinctRslt[0]['patient_id']; ?></span><?php }  if($DistinctRslt[0]['transaction_status']=="Pending"){?><img src="images/pending.png"/><?php } ?></td>
				<td class="textAlign" ><a href="Patient_History.php?pat_id=<?php echo $DistinctRslt[0]['patient_id']; if(!empty($_GET['disp'])) { ?>&disp=<?php echo $_GET['disp']; } if(!empty($_GET['assignId'])) {?>&assignId=<?php echo $_GET['assignId']; } if(!empty($_GET['refid'])) { ?>&refid=<?php echo $_GET['refid']; } if(!empty($_GET['start'])) { ?>&start=<?php echo $_GET['start']; }?>"><?php echo $DistinctRslt[0]['patient_name']; ?><?php echo $color; ?><?php if($Get_Feedback[0]['pat_resp_status']!=0){ echo $FedStatus; } ?></a></td>
				<td class="textAlign"><?php if($DistinctRslt[0]['patient_src']==11) { ?><img src="images/mas_icon.png" /> 
				<?php } else if($DistinctRslt[0]['patient_src']==13) { ?><span style="width:10px; padding:3px; color:#fff; background:red;">Sushrutt</span><?php } else if($DistinctRslt[0]['patient_src']==14) { ?><span style="width:10px; padding:3px; color:#fff; background:red;">Mullen-Us</span><?php } else if($DistinctRslt[0]['patient_src']==281) { ?><span style="width:10px; padding:3px; color:#fff; background:red;">Jio-Health</span><?php } else { echo "Medisensehealth.com"; } ?></td>
				<td class="textAlign" ><?php echo $status; ?></td>
				<td class="textAlign"><?php echo $DistinctRslt[0]['patient_mob']; ?></td>
				<td class="textAlign" ><?php echo $DistinctRslt[0]['patient_loc']; ?></td>
				<td class="textAlign"><select name="slctAssign" onchange="return Assignsubmit(this.value,<?php echo $DistinctRslt[0]['patient_id']; ?>);">
				<?php 
				
				
				?>
				<option value="0">Select</option>
				<?php 
								$i=30;
									
									foreach($ChkInUsrName as $UsrNameList){
										if($UsrNameList['chk_userid']==$DistinctRslt[0]['assigned_to']){
								?> 
									<option value="<?php echo stripslashes($UsrNameList['chk_userid']);?>" selected>
										<?php echo stripslashes($UsrNameList['chk_username']);?></option>
									<?php }?>
										<option value="<?php echo stripslashes($UsrNameList['chk_userid']);?>" >
										<?php echo stripslashes($UsrNameList['chk_username']);?></option>
									
									
									<?php 
										$i++;
										
									}?> 
				
				</select></td>
				
				<td class="textAlign"><?php echo $DistinctRslt[0]['patient_email']; ?></td>
				<!--<td class="textAlign"><a href="Add_Patient_Attachments.php?pat_id=<?php echo $DistinctRslt[0]['patient_id'];  ?>" title="Add/View Patient Attachments"><img src="images/i-9.png" /></a></td>-->
				<td class="textAlign"><a href="Edit-Patient.php?pat_id=<?php echo $DistinctRslt[0]['patient_id'];?><?php if(!empty($_GET['disp'])) { ?>&disp=<?php echo $_GET['disp']; } if(!empty($_GET['assignId'])) { ?>&assignId=<?php echo $_GET['assignId']; } if(!empty($_GET['refid'])) { ?>&refid=<?php echo $_GET['refid']; } if(!empty($_GET['start'])) { ?>&start=<?php echo $_GET['start']; } ?>" title="Edit Patient Details"><img src="images/i-5.png" /></a></td>
				<!--<td class="textAlign"><?php echo $Follow_Date=date('d-m-Y',strtotime($DistinctRslt[0]['follow_date']));?></td>-->
				
				</tr>
				<?php $j++; }  ?>
				
				</table>
			<?php
			}
			
			if($busResult4==true){
				?>
				<table class="tableclass1">
			<form id="frmstatus" name="frmstatus" method="post" action="" onsubmit="return chkForm()">
			<input type="hidden" name="cmdStatus" value="">
			<input type="hidden" name="pat_id" value="">
			<input type="hidden" name="status_id" value="">
					<tr class="bgimg"><th>Date & Time</th>
					<th>Patient ID</th>
					<th>Patient Name</th>
					<th>Source</th>
					<th>Status</th>
					<th>Contact no.</th>
					<th>Location</th>
					<th>Assigned To</th>						
					<th>Email</th>
					<!--<th>Add / View Attachments</th>-->
					<th>Edit</th>
			
			
			</tr>
				<tr>
				<td colspan="10">
				<?php echo $arrPage[0];?>
				</td>
				</tr>
				<?php
				$j=0;
				foreach($busResult4 as $list){
					$DistinctRslt = $objQuery->mysqlSelect("*","patient_tab","patient_id='".$list['PatId']."'","","","",""); 
					 if($DistinctRslt[0]['urgentState']==1){
						$color = "<span style='font-size:18px;  color:red;font-weight:bold;'>*</span>";
						}else{
						$color = "<span style='font-size:16px;  color:red;font-weight:bold;'></span>";
						}
						
						$Get_Feedback = $objQuery->mysqlSelect("*","patient_feedback","patient_id='".$list['PatId']."'","","","","");

						if($Get_Feedback[0]['pat_resp_status']!=0 && $Get_Feedback[0]['pat_resp_status']==1){
						$FedStatus = "<span><img src='images/tickgreen.png' /></span>";
						}else if($Get_Feedback[0]['pat_resp_status']!=0 && $Get_Feedback[0]['pat_resp_status']==2){
						$FedStatus = "<span><img src='images/tickred.png' /></span>";
						}
			
			
			$chkStatus = $objQuery->mysqlSelect("*","patient_referal","patient_id='".$list['PatId']."'and status1=2","","","","");
			$getSource = $objQuery->mysqlSelect("*","source_list","source_id='".$DistinctRslt[0]['patient_src']."'" ,"","","","");
		
		date_default_timezone_set('Asia/Kolkata');
		$today=date('Y-m-d h:i:s');
		$subtract_days = 2;
		$Check_Date = date('Y-m-d h:i:s',strtotime($today) - (24*3600*$subtract_days));
		
		
	if($chkStatus[0]['bucket_status']==1)
		{
			$status="<span style='font-size:12px;  color:red;font-weight:bold;'>NEW</span>"; //Capture Bucket
		}
		if($chkStatus[0]['bucket_status']==2)
		{
			if($chkStatus[0]['timestamp']<= $Check_Date){
				$status="<span style='font-size:12px; color:red;font-weight:bold;'>REFERRED</span>";
			}else{																			//Refer Bucket
			$status="<span style='font-size:12px; color:#f9e223;font-weight:bold;'>REFERRED</span>";
			}
		}
		if($chkStatus[0]['bucket_status']==3)
		{
			$status="<span style='font-size:12px;  color:red;font-weight:bold;'>P-AWAITING</span>"; //P-Awaiting Bucket
		}
		if($chkStatus[0]['bucket_status']==4)
		{
			$status="<span style='font-size:12px;  color:red;font-weight:bold;'>NOT-QUALIFIED</span>"; //Close Bucket
		}
		
		if($chkStatus[0]['bucket_status']==10)
		{
			$status="<span style='font-size:12px;  color:red;font-weight:bold;'>NOT CONVERTED</span>"; //Close Bucket
		}
		if($chkStatus[0]['bucket_status']==5)
		{
			$status="<span style='font-size:12px;  color:#01d337;font-weight:bold;'>RESPONDED</span>"; //Respond Bucket
		}
		if($chkStatus[0]['bucket_status']==6)
		{
			$status="<span style='font-size:12px;  color:#01d337;font-weight:bold;'>RESPONSE-P FAILED</span>";//Respond Bucket
		}
		if($chkStatus[0]['bucket_status']==7)
		{
			$status="<span style='font-size:12px; color:#f68c34;font-weight:bold;'>STAGED</span>"; //Conversion Bucket
		}
		if($chkStatus[0]['bucket_status']==8)
		{
			$status="<span style='font-size:12px; color:#f68c34;font-weight:bold;'>OP-CONVERTED</span>"; //Conversion Bucket
		}
		if($chkStatus[0]['bucket_status']==9)
		{
			$status="<span style='font-size:12px;  color:#f68c34;font-weight:bold;'>IP-CONVERTED</span>";//Invoice Bucket
		}
		if($chkStatus[0]['bucket_status']==11)
		{
			$status="<span style='font-size:12px;  color:#f68c34;font-weight:bold;'>INVOICED</span>";//Invoice Bucket
		}
		if($chkStatus[0]['bucket_status']==12)
		{
			$status="<span style='font-size:12px;  color:#f68c34;font-weight:bold;'>PAYMENT RECEIVED</span>"; //Invoice Bucket
		}
	
		
			?>
			<tr style="background:#f1f2f2;">
				<td class="textAlign"><?php echo $Date=date('d-m-Y',strtotime($DistinctRslt[0]['TImestamp']));?></td>
				<td class="textAlign"><?php if($DistinctRslt[0]['transaction_status']=="TXN_SUCCESS"){ ?><span style="background:#f4081e; padding:5px; color:#fff;"><?php echo $DistinctRslt[0]['patient_id']; ?></span><img src="images/paid_icon.png"/><?php } else { ?><span style="background:#04bad5; padding:5px; color:#fff;"><?php echo $DistinctRslt[0]['patient_id']; ?></span><?php }  if($DistinctRslt[0]['transaction_status']=="Pending"){?><img src="images/pending.png"/><?php } ?></td>
				<td class="textAlign" ><a href="Patient_History.php?pat_id=<?php echo $DistinctRslt[0]['patient_id']; if(!empty($_GET['disp'])) { ?>&disp=<?php echo $_GET['disp']; } if(!empty($_GET['assignId'])) {?>&assignId=<?php echo $_GET['assignId']; } if(!empty($_GET['refid'])) { ?>&refid=<?php echo $_GET['refid']; } if(!empty($_GET['start'])) { ?>&start=<?php echo $_GET['start']; }?>"><?php echo $DistinctRslt[0]['patient_name']; ?><?php echo $color; ?><?php if($Get_Feedback[0]['pat_resp_status']!=0){ echo $FedStatus; } ?></a></td>
				<td class="textAlign"><?php if($DistinctRslt[0]['patient_src']==11) { ?><img src="images/mas_icon.png" /> 
				<?php } else if($DistinctRslt[0]['patient_src']==13) { ?><span style="width:10px; padding:3px; color:#fff; background:red;">Sushrutt</span><?php } else if($DistinctRslt[0]['patient_src']==14) { ?><span style="width:10px; padding:3px; color:#fff; background:red;">Mullen-Us</span><?php } else if($DistinctRslt[0]['patient_src']==281) { ?><span style="width:10px; padding:3px; color:#fff; background:red;">Jio-Health</span><?php } else { echo "Medisensehealth.com"; } ?></td>
				<td class="textAlign" ><?php echo $status; ?></td>
				<td class="textAlign"><?php echo $DistinctRslt[0]['patient_mob']; ?></td>
				<td class="textAlign" ><?php echo $DistinctRslt[0]['patient_loc']; ?><br><?php echo $DistinctRslt[0]['pat_state']; ?></td>
				<td class="textAlign"><select name="slctAssign" onchange="return Assignsubmit(this.value,<?php echo $DistinctRslt[0]['patient_id']; ?>);">
				<?php 
				
				
				?>
				<option value="0">Select</option>
				<?php 
								$i=30;
									
									foreach($ChkInUsrName as $UsrNameList){
										if($UsrNameList['chk_userid']==$DistinctRslt[0]['assigned_to']){
								?> 
									<option value="<?php echo stripslashes($UsrNameList['chk_userid']);?>" selected>
										<?php echo stripslashes($UsrNameList['chk_username']);?></option>
									<?php }?>
										<option value="<?php echo stripslashes($UsrNameList['chk_userid']);?>" >
										<?php echo stripslashes($UsrNameList['chk_username']);?></option>
									
									
									<?php 
										$i++;
										
									}?> 
				
				</select></td>
				
				<td class="textAlign"><?php echo $DistinctRslt[0]['patient_email']; ?></td>
				<!--<td class="textAlign"><a href="Add_Patient_Attachments.php?pat_id=<?php echo $DistinctRslt[0]['patient_id'];  ?>" title="Add/View Patient Attachments"><img src="images/i-9.png" /></a></td>-->
				<td class="textAlign"><a href="Edit-Patient.php?pat_id=<?php echo $DistinctRslt[0]['patient_id'];?><?php if(!empty($_GET['disp'])) { ?>&disp=<?php echo $_GET['disp']; } if(!empty($_GET['assignId'])) { ?>&assignId=<?php echo $_GET['assignId']; } if(!empty($_GET['refid'])) { ?>&refid=<?php echo $_GET['refid']; } if(!empty($_GET['start'])) { ?>&start=<?php echo $_GET['start']; } ?>" title="Edit Patient Details"><img src="images/i-5.png" /></a></td>
				<!--<td class="textAlign"><?php echo $Follow_Date=date('d-m-Y',strtotime($DistinctRslt[0]['follow_date']));?></td>-->
				
				</tr>
				<?php $j++; }  ?>
				
				</table>
			<?php
			}
			
			if($busResult5==true){
				?>
				<table class="tableclass1">
			<form id="frmstatus" name="frmstatus" method="post" action="" onsubmit="return chkForm()">
			<input type="hidden" name="cmdStatus" value="">
			<input type="hidden" name="pat_id" value="">
			<input type="hidden" name="status_id" value="">
					<tr class="bgimg"><th>Date & Time</th>
					<th>Patient ID</th>
					<th>Patient Name</th>
					<th>Source</th>
					<th>Status</th>
					<th>Contact no.</th>
					<th>Location</th>
					<th>Assigned To</th>						
					<th>Email</th>
					<!--<th>Add / View Attachments</th>-->
					<th>Edit</th>
			
			
			</tr>
				<tr>
				<td colspan="10">
				<?php echo $arrPage[0];?>
				</td>
				</tr>
				<?php
				$j=0;
				foreach($busResult5 as $list){
					$DistinctRslt = $objQuery->mysqlSelect("*","patient_tab","patient_id='".$list['PatId']."'","","","",""); 
					 if($DistinctRslt[0]['urgentState']==1){
						$color = "<span style='font-size:18px;  color:red;font-weight:bold;'>*</span>";
						}else{
						$color = "<span style='font-size:16px;  color:red;font-weight:bold;'></span>";
						}
						
						$Get_Feedback = $objQuery->mysqlSelect("*","patient_feedback","patient_id='".$list['PatId']."'","","","","");

						if($Get_Feedback[0]['pat_resp_status']!=0 && $Get_Feedback[0]['pat_resp_status']==1){
						$FedStatus = "<span><img src='images/tickgreen.png' /></span>";
						}else if($Get_Feedback[0]['pat_resp_status']!=0 && $Get_Feedback[0]['pat_resp_status']==2){
						$FedStatus = "<span><img src='images/tickred.png' /></span>";
						}
			
			
			$chkStatus = $objQuery->mysqlSelect("*","patient_referal","patient_id='".$list['PatId']."'and bucket_status=3","","","","");
			$getSource = $objQuery->mysqlSelect("*","source_list","source_id='".$DistinctRslt[0]['patient_src']."'" ,"","","","");
		
	if($chkStatus==true)
		{
			$status="<span style='font-size:12px; color:red;font-weight:bold;'>P-AWAITING</span>";
		}
	
		
			?>
			<tr style="background:#f1f2f2;">
				<td class="textAlign"><?php echo $Date=date('d-m-Y',strtotime($DistinctRslt[0]['TImestamp']));?></td>
				<td class="textAlign"><?php if($DistinctRslt[0]['transaction_status']=="TXN_SUCCESS"){ ?><span style="background:#f4081e; padding:5px; color:#fff;"><?php echo $DistinctRslt[0]['patient_id']; ?></span><img src="images/paid_icon.png"/><?php } else { ?><span style="background:#04bad5; padding:5px; color:#fff;"><?php echo $DistinctRslt[0]['patient_id']; ?></span><?php }  if($DistinctRslt[0]['transaction_status']=="Pending"){?><img src="images/pending.png"/><?php } ?></td>
				<td class="textAlign" ><a href="Patient_History.php?pat_id=<?php echo $DistinctRslt[0]['patient_id']; if(!empty($_GET['disp'])) { ?>&disp=<?php echo $_GET['disp']; } if(!empty($_GET['assignId'])) {?>&assignId=<?php echo $_GET['assignId']; } if(!empty($_GET['refid'])) { ?>&refid=<?php echo $_GET['refid']; } if(!empty($_GET['start'])) { ?>&start=<?php echo $_GET['start']; }?>"><?php echo $DistinctRslt[0]['patient_name']; ?><?php echo $color; ?><?php if($Get_Feedback[0]['pat_resp_status']!=0){ echo $FedStatus; } ?></a></td>
				<td class="textAlign"><?php if($DistinctRslt[0]['patient_src']==11) { ?><img src="images/mas_icon.png" /> 
				<?php } else if($DistinctRslt[0]['patient_src']==13) { ?><span style="width:10px; padding:3px; color:#fff; background:red;">Sushrutt</span><?php } else if($DistinctRslt[0]['patient_src']==14) { ?><span style="width:10px; padding:3px; color:#fff; background:red;">Mullen-Us</span><?php } else if($DistinctRslt[0]['patient_src']==281) { ?><span style="width:10px; padding:3px; color:#fff; background:red;">Jio-Health</span><?php } else { echo "Medisensehealth.com"; } ?></td>
				<td class="textAlign" ><?php echo $status; ?></td>
				<td class="textAlign"><?php echo $DistinctRslt[0]['patient_mob']; ?></td>
				<td class="textAlign" ><?php echo $DistinctRslt[0]['patient_loc']; ?></td>
				<td class="textAlign"><select name="slctAssign" onchange="return Assignsubmit(this.value,<?php echo $DistinctRslt[0]['patient_id']; ?>);">
				<?php 
				
				
				?>
				<option value="0">Select</option>
				<?php 
								$i=30;
									
									foreach($ChkInUsrName as $UsrNameList){
										if($UsrNameList['chk_userid']==$DistinctRslt[0]['assigned_to']){
								?> 
									<option value="<?php echo stripslashes($UsrNameList['chk_userid']);?>" selected>
										<?php echo stripslashes($UsrNameList['chk_username']);?></option>
									<?php }?>
										<option value="<?php echo stripslashes($UsrNameList['chk_userid']);?>" >
										<?php echo stripslashes($UsrNameList['chk_username']);?></option>
									
									
									<?php 
										$i++;
										
									}?> 
				
				</select></td>
				
				<td class="textAlign"><?php echo $DistinctRslt[0]['patient_email']; ?></td>
				<!--<td class="textAlign"><a href="Add_Patient_Attachments.php?pat_id=<?php echo $DistinctRslt[0]['patient_id'];  ?>" title="Add/View Patient Attachments"><img src="images/i-9.png" /></a></td>-->
				<td class="textAlign"><a href="Edit-Patient.php?pat_id=<?php echo $DistinctRslt[0]['patient_id'];?><?php if(!empty($_GET['disp'])) { ?>&disp=<?php echo $_GET['disp']; } if(!empty($_GET['assignId'])) { ?>&assignId=<?php echo $_GET['assignId']; } if(!empty($_GET['refid'])) { ?>&refid=<?php echo $_GET['refid']; } if(!empty($_GET['start'])) { ?>&start=<?php echo $_GET['start']; } ?>" title="Edit Patient Details"><img src="images/i-5.png" /></a></td>
				<!--<td class="textAlign"><?php echo $Follow_Date=date('d-m-Y',strtotime($DistinctRslt[0]['follow_date']));?></td>-->
				
				</tr>
				<?php $j++; }  ?>
			
				</table>
			<?php
			}
			
			
			if($busResult6==true){
				?>
				<table class="tableclass1">
			<form id="frmstatus" name="frmstatus" method="post" action="" onsubmit="return chkForm()">
			<input type="hidden" name="cmdStatus" value="">
			<input type="hidden" name="pat_id" value="">
			<input type="hidden" name="status_id" value="">
					<tr class="bgimg"><th>Date & Time</th>
					<th>Patient ID</th>
					<th>Patient Name</th>
					<th>Source</th>
					<th>Status</th>
					<th>Contact no.</th>
					<th>Location</th>
					<th>Assigned To</th>						
					<th>Email</th>
					<!--<th>Add / View Attachments</th>-->
					<th>Edit</th>
			
			
			</tr>
				<tr>
				<td colspan="10">
				<?php echo $arrPage[0];?>
				</td>
				</tr>
				<?php
				$j=0;
				foreach($busResult6 as $list){
					$DistinctRslt = $objQuery->mysqlSelect("*","patient_tab","patient_id='".$list['PatId']."'","","","",""); 
					 if($DistinctRslt[0]['urgentState']==1){
						$color = "<span style='font-size:18px;  color:red;font-weight:bold;'>*</span>";
						}else{
						$color = "<span style='font-size:16px;  color:red;font-weight:bold;'></span>";
						}
						
						$Get_Feedback = $objQuery->mysqlSelect("*","patient_feedback","patient_id='".$list['PatId']."'","","","","");

						if($Get_Feedback[0]['pat_resp_status']!=0 && $Get_Feedback[0]['pat_resp_status']==1){
						$FedStatus = "<span><img src='images/tickgreen.png' /></span>";
						}else if($Get_Feedback[0]['pat_resp_status']!=0 && $Get_Feedback[0]['pat_resp_status']==2){
						$FedStatus = "<span><img src='images/tickred.png' /></span>";
						}
			
			
			$chkStatus = $objQuery->mysqlSelect("*","patient_referal","patient_id='".$list['PatId']."'","","","","");
			$getSource = $objQuery->mysqlSelect("*","source_list","source_id='".$DistinctRslt[0]['patient_src']."'" ,"","","","");
		
	if($chkStatus[0]['bucket_status']==1)
		{
			$status="<span style='font-size:12px;  color:red;font-weight:bold;'>NEW</span>"; //Capture Bucket
		}
		if($chkStatus[0]['bucket_status']==2)
		{
			if($chkStatus[0]['timestamp']<= $Check_Date){
				$status="<span style='font-size:12px; color:red;font-weight:bold;'>REFERRED</span>";
			}else{																			//Refer Bucket
			$status="<span style='font-size:12px; color:#f9e223;font-weight:bold;'>REFERRED</span>";
			}
		}
		if($chkStatus[0]['bucket_status']==3)
		{
			$status="<span style='font-size:12px;  color:red;font-weight:bold;'>P-AWAITING</span>"; //P-Awaiting Bucket
		}
		if($chkStatus[0]['bucket_status']==4)
		{
			$status="<span style='font-size:12px;  color:red;font-weight:bold;'>NOT-QUALIFIED</span>"; //Close Bucket
		}
		
		if($chkStatus[0]['bucket_status']==10)
		{
			$status="<span style='font-size:12px;  color:red;font-weight:bold;'>NOT CONVERTED</span>"; //Close Bucket
		}
		if($chkStatus[0]['bucket_status']==5)
		{
			$status="<span style='font-size:12px;  color:#01d337;font-weight:bold;'>RESPONDED</span>"; //Respond Bucket
		}
		if($chkStatus[0]['bucket_status']==6)
		{
			$status="<span style='font-size:12px;  color:#01d337;font-weight:bold;'>RESPONSE-P FAILED</span>";//Respond Bucket
		}
		if($chkStatus[0]['bucket_status']==7)
		{
			$status="<span style='font-size:12px; color:#f68c34;font-weight:bold;'>STAGED</span>"; //Conversion Bucket
		}
		if($chkStatus[0]['bucket_status']==8)
		{
			$status="<span style='font-size:12px; color:#f68c34;font-weight:bold;'>OP-CONVERTED</span>"; //Conversion Bucket
		}
		if($chkStatus[0]['bucket_status']==9)
		{
			$status="<span style='font-size:12px;  color:#f68c34;font-weight:bold;'>IP-CONVERTED</span>";//Invoice Bucket
		}
		if($chkStatus[0]['bucket_status']==11)
		{
			$status="<span style='font-size:12px;  color:#f68c34;font-weight:bold;'>INVOICED</span>";//Invoice Bucket
		}
		if($chkStatus[0]['bucket_status']==12)
		{
			$status="<span style='font-size:12px;  color:#f68c34;font-weight:bold;'>PAYMENT RECEIVED</span>"; //Invoice Bucket
		}
	
		
			?>
			<tr style="background:#f1f2f2;">
				<td class="textAlign"><?php echo $Date=date('d-m-Y',strtotime($DistinctRslt[0]['TImestamp']));?></td>
				<td class="textAlign"><?php if($DistinctRslt[0]['transaction_status']=="TXN_SUCCESS"){ ?><span style="background:#f4081e; padding:5px; color:#fff;"><?php echo $DistinctRslt[0]['patient_id']; ?></span><img src="images/paid_icon.png"/><?php } else { ?><span style="background:#04bad5; padding:5px; color:#fff;"><?php echo $DistinctRslt[0]['patient_id']; ?></span><?php }  if($DistinctRslt[0]['transaction_status']=="Pending"){?><img src="images/pending.png"/><?php } ?></td>
				<td class="textAlign" ><a href="Patient_History.php?pat_id=<?php echo $DistinctRslt[0]['patient_id']; if(!empty($_GET['disp'])) { ?>&disp=<?php echo $_GET['disp']; } if(!empty($_GET['assignId'])) {?>&assignId=<?php echo $_GET['assignId']; } if(!empty($_GET['refid'])) { ?>&refid=<?php echo $_GET['refid']; } if(!empty($_GET['start'])) { ?>&start=<?php echo $_GET['start']; }?>"><?php echo $DistinctRslt[0]['patient_name']; ?><?php echo $color; ?><?php if($Get_Feedback[0]['pat_resp_status']!=0){ echo $FedStatus; } ?></a></td>
				<td class="textAlign"><?php if($DistinctRslt[0]['patient_src']==11) { ?><img src="images/mas_icon.png" /> 
				<?php } else if($DistinctRslt[0]['patient_src']==13) { ?><span style="width:10px; padding:3px; color:#fff; background:red;">Sushrutt</span><?php } else if($DistinctRslt[0]['patient_src']==14) { ?><span style="width:10px; padding:3px; color:#fff; background:red;">Mullen-Us</span><?php } else if($DistinctRslt[0]['patient_src']==281) { ?><span style="width:10px; padding:3px; color:#fff; background:red;">Jio-Health</span><?php } else { echo "Medisensehealth.com"; } ?></td>
				<td class="textAlign" ><?php echo $status; ?></td>
				<td class="textAlign"><?php echo $DistinctRslt[0]['patient_mob']; ?></td>
				<td class="textAlign" ><?php echo $DistinctRslt[0]['patient_loc']; ?></td>
				<td class="textAlign"><select name="slctAssign" onchange="return Assignsubmit(this.value,<?php echo $DistinctRslt[0]['patient_id']; ?>);">
				<?php 
				
				
				?>
				<option value="0">Select</option>
				<?php 
								$i=30;
									
									foreach($ChkInUsrName as $UsrNameList){
										if($UsrNameList['chk_userid']==$DistinctRslt[0]['assigned_to']){
								?> 
									<option value="<?php echo stripslashes($UsrNameList['chk_userid']);?>" selected>
										<?php echo stripslashes($UsrNameList['chk_username']);?></option>
									<?php }?>
										<option value="<?php echo stripslashes($UsrNameList['chk_userid']);?>" >
										<?php echo stripslashes($UsrNameList['chk_username']);?></option>
									
									
									<?php 
										$i++;
										
									}?> 
				
				</select></td>
				
				<td class="textAlign"><?php echo $DistinctRslt[0]['patient_email']; ?></td>
				<!--<td class="textAlign"><a href="Add_Patient_Attachments.php?pat_id=<?php echo $DistinctRslt[0]['patient_id'];  ?>" title="Add/View Patient Attachments"><img src="images/i-9.png" /></a></td>-->
				<td class="textAlign"><a href="Edit-Patient.php?pat_id=<?php echo $DistinctRslt[0]['patient_id'];?><?php if(!empty($_GET['disp'])) { ?>&disp=<?php echo $_GET['disp']; } if(!empty($_GET['assignId'])) { ?>&assignId=<?php echo $_GET['assignId']; } if(!empty($_GET['refid'])) { ?>&refid=<?php echo $_GET['refid']; } if(!empty($_GET['start'])) { ?>&start=<?php echo $_GET['start']; }?>" title="Edit Patient Details"><img src="images/i-5.png" /></a></td>
				<!--<td class="textAlign"><?php echo $Follow_Date=date('d-m-Y',strtotime($DistinctRslt[0]['follow_date']));?></td>-->
				
				</tr>
				<?php $j++; }  ?>
				
				</table>
			<?php
			}
			
			if($busResult7==true){
				?>
				<table class="tableclass1">
			<form id="frmstatus" name="frmstatus" method="post" action="" onsubmit="return chkForm()">
			<input type="hidden" name="cmdStatus" value="">
			<input type="hidden" name="pat_id" value="">
			<input type="hidden" name="status_id" value="">
					<tr class="bgimg"><th>Date & Time</th>
					<th>Patient ID</th>
					<th>Patient Name</th>
					<th>Source</th>
					<th>Status</th>
					<th>Contact no.</th>
					<th>Location</th>
					<th>Assigned To</th>						
					<th>Email</th>
					<!--<th>Add / View Attachments</th>-->
					<th>Edit</th>
			
			
			</tr>
				<tr>
				<td colspan="10">
				<?php echo $arrPage[0];?>
				</td>
				</tr>
				<?php
				$j=0;
				foreach($busResult7 as $list){
					$DistinctRslt = $objQuery->mysqlSelect("*","patient_tab","patient_id='".$list['PatId']."'","","","",""); 
					 if($DistinctRslt[0]['urgentState']==1){
						$color = "<span style='font-size:18px;  color:red;font-weight:bold;'>*</span>";
						}else{
						$color = "<span style='font-size:16px;  color:red;font-weight:bold;'></span>";
						}
						
						$Get_Feedback = $objQuery->mysqlSelect("*","patient_feedback","patient_id='".$list['PatId']."'","","","","");

						if($Get_Feedback[0]['pat_resp_status']!=0 && $Get_Feedback[0]['pat_resp_status']==1){
						$FedStatus = "<span><img src='images/tickgreen.png' /></span>";
						}else if($Get_Feedback[0]['pat_resp_status']!=0 && $Get_Feedback[0]['pat_resp_status']==2){
						$FedStatus = "<span><img src='images/tickred.png' /></span>";
						}
			
			
			$chkStatus = $objQuery->mysqlSelect("*","patient_referal","patient_id='".$list['PatId']."'and (bucket_status=8 or bucket_status=7)","","","","");
			$getSource = $objQuery->mysqlSelect("*","source_list","source_id='".$DistinctRslt[0]['patient_src']."'" ,"","","","");
		
	
	if($chkStatus[0]['bucket_status']==8)
		{
			$status="<span style='font-size:12px; color:#fc5900;font-weight:bold;'>OP CONVERTED</span>";
		}
		if($chkStatus[0]['bucket_status']==7)
		{
			$status="<span style='font-size:12px; color:#fc5900;font-weight:bold;'>STAGED</span>";
		}
		
			?>
			<tr style="background:#f1f2f2;">
				<td class="textAlign"><?php echo $Date=date('d-m-Y',strtotime($DistinctRslt[0]['TImestamp']));?></td>
				<td class="textAlign"><?php if($DistinctRslt[0]['transaction_status']=="TXN_SUCCESS"){ ?><span style="background:#f4081e; padding:5px; color:#fff;"><?php echo $DistinctRslt[0]['patient_id']; ?></span><img src="images/paid_icon.png"/><?php } else { ?><span style="background:#04bad5; padding:5px; color:#fff;"><?php echo $DistinctRslt[0]['patient_id']; ?></span><?php }  if($DistinctRslt[0]['transaction_status']=="Pending"){?><img src="images/pending.png"/><?php } ?></td>
				<td class="textAlign" ><a href="Patient_History.php?pat_id=<?php echo $DistinctRslt[0]['patient_id']; if(!empty($_GET['disp'])) { ?>&disp=<?php echo $_GET['disp']; } if(!empty($_GET['assignId'])) {?>&assignId=<?php echo $_GET['assignId']; } if(!empty($_GET['refid'])) { ?>&refid=<?php echo $_GET['refid']; } if(!empty($_GET['start'])) { ?>&start=<?php echo $_GET['start']; }?>"><?php echo $DistinctRslt[0]['patient_name']; ?><?php echo $color; ?><?php if($Get_Feedback[0]['pat_resp_status']!=0){ echo $FedStatus; } ?></a></td>
				<td class="textAlign"><?php if($DistinctRslt[0]['patient_src']==11) { ?><img src="images/mas_icon.png" /> 
				<?php } else if($DistinctRslt[0]['patient_src']==13) { ?><span style="width:10px; padding:3px; color:#fff; background:red;">Sushrutt</span><?php } else if($DistinctRslt[0]['patient_src']==14) { ?><span style="width:10px; padding:3px; color:#fff; background:red;">Mullen-Us</span><?php } else if($DistinctRslt[0]['patient_src']==281) { ?><span style="width:10px; padding:3px; color:#fff; background:red;">Jio-Health</span><?php } else { echo "Medisensehealth.com"; } ?></td>
				<td class="textAlign" ><?php echo $status; ?></td>
				<td class="textAlign"><?php echo $DistinctRslt[0]['patient_mob']; ?></td>
				<td class="textAlign" ><?php echo $DistinctRslt[0]['patient_loc']; ?></td>
				<td class="textAlign"><select name="slctAssign" onchange="return Assignsubmit(this.value,<?php echo $DistinctRslt[0]['patient_id']; ?>);">
				<?php 
				
				
				?>
				<option value="0">Select</option>
				<?php 
								$i=30;
									
									foreach($ChkInUsrName as $UsrNameList){
										if($UsrNameList['chk_userid']==$DistinctRslt[0]['assigned_to']){
								?> 
									<option value="<?php echo stripslashes($UsrNameList['chk_userid']);?>" selected>
										<?php echo stripslashes($UsrNameList['chk_username']);?></option>
									<?php }?>
										<option value="<?php echo stripslashes($UsrNameList['chk_userid']);?>" >
										<?php echo stripslashes($UsrNameList['chk_username']);?></option>
									
									
									<?php 
										$i++;
										
									}?> 
				
				</select></td>
				
				<td class="textAlign"><?php echo $DistinctRslt[0]['patient_email']; ?></td>
				<!--<td class="textAlign"><a href="Add_Patient_Attachments.php?pat_id=<?php echo $DistinctRslt[0]['patient_id'];  ?>" title="Add/View Patient Attachments"><img src="images/i-9.png" /></a></td>-->
				<td class="textAlign"><a href="Edit-Patient.php?pat_id=<?php echo $DistinctRslt[0]['patient_id'];?><?php if(!empty($_GET['disp'])) { ?>&disp=<?php echo $_GET['disp']; } if(!empty($_GET['assignId'])) { ?>&assignId=<?php echo $_GET['assignId']; } if(!empty($_GET['refid'])) { ?>&refid=<?php echo $_GET['refid']; } if(!empty($_GET['start'])) { ?>&start=<?php echo $_GET['start']; } ?>" title="Edit Patient Details"><img src="images/i-5.png" /></a></td>
				<!--<td class="textAlign"><?php echo $Follow_Date=date('d-m-Y',strtotime($DistinctRslt[0]['follow_date']));?></td>-->
				
				</tr>
				<?php $j++; }  ?>
				
				</table>
			<?php
			}
			
			if($busResult8==true){
				?>
				<!--<script>
				function myFunction(subid) {
				 var myWindow = window.open("http://medisensecrm.com/view_family_member.php?sub_id="+subid, "", "width=380,height=220");
				}
				</script>-->
				<table class="tableclass1">
			<form id="frmstatus" name="frmstatus" method="post" action="" onsubmit="return chkForm()">
			<input type="hidden" name="cmdStatus" value="">
			<input type="hidden" name="pat_id" value="">
			<input type="hidden" name="status_id" value="">
					<tr class="bgimg"><th>Date & Time</th>
					<th>Patient ID</th>
					<th>Patient Name</th>
					<th>Source</th>
					<th>Status</th>
					<th>Contact no.</th>
					<th>Location</th>
					<th>Assigned To</th>						
					<th>Email</th>
					<!--<th>Add / View Attachments</th>-->
					<th>Edit</th>
			
			
			</tr>
				<tr>
				<td colspan="10">
				<?php echo $arrPage[0];?>
				</td>
				</tr>
				<?php
				$j=0;
				foreach($busResult8 as $list){
					$DistinctRslt = $objQuery->mysqlSelect("*","patient_tab","patient_id='".$list['PatId']."'","","","",""); 
					 if($DistinctRslt[0]['urgentState']==1){
						$color = "<span style='font-size:18px;  color:red;font-weight:bold;'>*</span>";
						}else{
						$color = "<span style='font-size:16px;  color:red;font-weight:bold;'></span>";
						}
						
						$Get_Feedback = $objQuery->mysqlSelect("*","patient_feedback","patient_id='".$list['PatId']."'","","","","");

						if($Get_Feedback[0]['pat_resp_status']!=0 && $Get_Feedback[0]['pat_resp_status']==1){
						$FedStatus = "<span><img src='images/tickgreen.png' /></span>";
						}else if($Get_Feedback[0]['pat_resp_status']!=0 && $Get_Feedback[0]['pat_resp_status']==2){
						$FedStatus = "<span><img src='images/tickred.png' /></span>";
						}
			
			
			$chkStatus = $objQuery->mysqlSelect("*","patient_referal","patient_id='".$list['PatId']."'and (bucket_status=5 or bucket_status=6)","","","","");
			$getSource = $objQuery->mysqlSelect("*","source_list","source_id='".$DistinctRslt[0]['patient_src']."'" ,"","","","");
		
	if($chkStatus[0]['bucket_status']==5 && $chkStatus[0]['response_status']==1)
		{
			$status="<span style='font-size:12px; color:red;font-weight:bold;'>AUTO-RESPONDED</span>";
		}
		
		
			?>
			<tr style="background:#f1f2f2;">
				<td class="textAlign" ><?php echo $Date=date('d-m-Y',strtotime($DistinctRslt[0]['TImestamp']));?></td>
				<td class="textAlign"><?php if($DistinctRslt[0]['transaction_status']=="TXN_SUCCESS"){ ?><span style="background:#f4081e; padding:5px; color:#fff;"><?php echo $DistinctRslt[0]['patient_id']; ?></span><img src="images/paid_icon.png"/><?php } else { ?><span style="background:#04bad5; padding:5px; color:#fff;"><?php echo $DistinctRslt[0]['patient_id']; ?></span><?php }  if($DistinctRslt[0]['transaction_status']=="Pending"){?><img src="images/pending.png"/><?php } ?></td>
				<td class="textAlign" ><a href="Patient_History.php?pat_id=<?php echo $DistinctRslt[0]['patient_id']; if(!empty($_GET['disp'])) { ?>&disp=<?php echo $_GET['disp']; } if(!empty($_GET['assignId'])) {?>&assignId=<?php echo $_GET['assignId']; } if(!empty($_GET['refid'])) { ?>&refid=<?php echo $_GET['refid']; } if(!empty($_GET['start'])) { ?>&start=<?php echo $_GET['start']; }?>"><?php echo $DistinctRslt[0]['patient_name']; ?><?php echo $color; ?><?php if($Get_Feedback[0]['pat_resp_status']!=0){ echo $FedStatus; } ?></a></td>
				<td class="textAlign"><?php if($DistinctRslt[0]['patient_src']==11) { ?><img src="images/mas_icon.png" /> 
				<?php } else if($DistinctRslt[0]['patient_src']==13) { ?><span style="width:10px; padding:3px; color:#fff; background:red;">Sushrutt</span><?php } else if($DistinctRslt[0]['patient_src']==14) { ?><span style="width:10px; padding:3px; color:#fff; background:red;">Mullen-Us</span><?php } else if($DistinctRslt[0]['patient_src']==281) { ?><span style="width:10px; padding:3px; color:#fff; background:red;">Jio-Health</span><?php } else { echo "Medisensehealth.com"; } ?></td>
				<td class="textAlign" ><?php echo $status; ?></td>
				<td class="textAlign"><?php echo $DistinctRslt[0]['patient_mob']; ?></td>
				<td class="textAlign" ><?php echo $DistinctRslt[0]['patient_loc']; ?></td>
				<td class="textAlign"><select name="slctAssign" onchange="return Assignsubmit(this.value,<?php echo $DistinctRslt[0]['patient_id']; ?>);">
				<?php 
				
				
				?>
				<option value="0">Select</option>
				<?php 
								$i=30;
									
									foreach($ChkInUsrName as $UsrNameList){
										if($UsrNameList['chk_userid']==$DistinctRslt[0]['assigned_to']){
								?> 
									<option value="<?php echo stripslashes($UsrNameList['chk_userid']);?>" selected>
										<?php echo stripslashes($UsrNameList['chk_username']);?></option>
									<?php }?>
										<option value="<?php echo stripslashes($UsrNameList['chk_userid']);?>" >
										<?php echo stripslashes($UsrNameList['chk_username']);?></option>
									
									
									<?php 
										$i++;
										
									}?> 
				
				</select></td>
				
				<td class="textAlign"><?php echo $DistinctRslt[0]['patient_email']; ?></td>
				<!--<td class="textAlign"><a href="Add_Patient_Attachments.php?pat_id=<?php echo $DistinctRslt[0]['patient_id'];  ?>" title="Add/View Patient Attachments"><img src="images/i-9.png" /></a></td>-->
				<td class="textAlign"><a href="Edit-Patient.php?pat_id=<?php echo $DistinctRslt[0]['patient_id'];?><?php if(!empty($_GET['disp'])) { ?>&disp=<?php echo $_GET['disp']; } if(!empty($_GET['assignId'])) { ?>&assignId=<?php echo $_GET['assignId']; } if(!empty($_GET['refid'])) { ?>&refid=<?php echo $_GET['refid']; } if(!empty($_GET['start'])) { ?>&start=<?php echo $_GET['start']; } ?>" title="Edit Patient Details"><img src="images/i-5.png" /></a></td>
				<!--<td class="textAlign"><?php echo $Follow_Date=date('d-m-Y',strtotime($DistinctRslt[0]['follow_date']));?></td>-->
				
				</tr>
				<?php $j++; }  ?>
				
				</table>
			<?php
			}
			
			
			
			if($busResultRef==true){
				
				$j=0;
				foreach($busResultRef as $list){
					$DistinctRslt = $objQuery->mysqlSelect("*","patient_tab","patient_id='".$list['PatId']."'","","","",""); 
					 if($DistinctRslt[0]['urgentState']==1){
						$color = "<span style='font-size:18px;  color:red;font-weight:bold;'>*</span>";
						}else{
						$color = "<span style='font-size:16px;  color:red;font-weight:bold;'></span>";
						}
						
						$Get_Feedback = $objQuery->mysqlSelect("*","patient_feedback","patient_id='".$list['PatId']."'","","","","");

						if($Get_Feedback[0]['pat_resp_status']!=0 && $Get_Feedback[0]['pat_resp_status']==1){
						$FedStatus = "<span><img src='images/tickgreen.png' /></span>";
						}else if($Get_Feedback[0]['pat_resp_status']!=0 && $Get_Feedback[0]['pat_resp_status']==2){
						$FedStatus = "<span><img src='images/tickred.png' /></span>";
						}
			
			
			$chkStatus = $objQuery->mysqlSelect("*","patient_referal","patient_id='".$list['PatId']."'","","","","");
			$getSource = $objQuery->mysqlSelect("*","source_list","source_id='".$DistinctRslt[0]['patient_src']."'" ,"","","","");
		
	if($chkStatus[0]['bucket_status']==1)
		{
			$status="<span style='font-size:12px;  color:red;font-weight:bold;'>NEW</span>"; //Capture Bucket
		}
		if($chkStatus[0]['bucket_status']==2)
		{
			if($chkStatus[0]['timestamp']<= $Check_Date){
				$status="<span style='font-size:12px; color:red;font-weight:bold;'>REFERRED</span>";
			}else{																			//Refer Bucket
			$status="<span style='font-size:12px; color:#f9e223;font-weight:bold;'>REFERRED</span>";
			}
		}
		if($chkStatus[0]['bucket_status']==3)
		{
			$status="<span style='font-size:12px;  color:red;font-weight:bold;'>P-AWAITING</span>"; //P-Awaiting Bucket
		}
		if($chkStatus[0]['bucket_status']==4)
		{
			$status="<span style='font-size:12px;  color:red;font-weight:bold;'>NOT-QUALIFIED</span>"; //Close Bucket
		}
		
		if($chkStatus[0]['bucket_status']==10)
		{
			$status="<span style='font-size:12px;  color:red;font-weight:bold;'>NOT CONVERTED</span>"; //Close Bucket
		}
		if($chkStatus[0]['bucket_status']==5)
		{
			$status="<span style='font-size:12px;  color:#01d337;font-weight:bold;'>RESPONDED</span>"; //Respond Bucket
		}
		if($chkStatus[0]['bucket_status']==6)
		{
			$status="<span style='font-size:12px;  color:#01d337;font-weight:bold;'>RESPONSE-P FAILED</span>";//Respond Bucket
		}
		if($chkStatus[0]['bucket_status']==7)
		{
			$status="<span style='font-size:12px; color:#f68c34;font-weight:bold;'>STAGED</span>"; //Conversion Bucket
		}
		if($chkStatus[0]['bucket_status']==8)
		{
			$status="<span style='font-size:12px; color:#f68c34;font-weight:bold;'>OP-CONVERTED</span>"; //Conversion Bucket
		}
		if($chkStatus[0]['bucket_status']==9)
		{
			$status="<span style='font-size:12px;  color:#f68c34;font-weight:bold;'>IP-CONVERTED</span>";//Invoice Bucket
		}
		if($chkStatus[0]['bucket_status']==11)
		{
			$status="<span style='font-size:12px;  color:#f68c34;font-weight:bold;'>INVOICED</span>";//Invoice Bucket
		}
		if($chkStatus[0]['bucket_status']==12)
		{
			$status="<span style='font-size:12px;  color:#f68c34;font-weight:bold;'>PAYMENT RECEIVED</span>"; //Invoice Bucket
		}
	
		
			?>
			<table class="tableclass1">
			<form id="frmstatus" name="frmstatus" method="post" action="" onsubmit="return chkForm()">
			<input type="hidden" name="cmdStatus" value="">
			<input type="hidden" name="pat_id" value="">
			<input type="hidden" name="status_id" value="">
					<tr class="bgimg"><th>Date & Time</th>
					<th>Patient ID</th>
					<th>Patient Name</th>
					<th>Source</th>
					<th>Status</th>
					<th>Contact no.</th>
					<th>Location</th>
					<th>Assigned To</th>						
					<th>Email</th>
					<!--<th>Add / View Attachments</th>-->
					<th>Edit</th>
			
			
			</tr>
			<tr style="background:#f1f2f2;">
				<td class="textAlign"><?php echo $Date=date('d-m-Y',strtotime($DistinctRslt[0]['TImestamp']));?></td>
				<td class="textAlign"><?php if($DistinctRslt[0]['transaction_status']=="TXN_SUCCESS"){ ?><span style="background:#f4081e; padding:5px; color:#fff;"><?php echo $DistinctRslt[0]['patient_id']; ?></span><img src="images/paid_icon.png"/><?php } else { ?><span style="background:#04bad5; padding:5px; color:#fff;"><?php echo $DistinctRslt[0]['patient_id']; ?></span><?php }  if($DistinctRslt[0]['transaction_status']=="Pending"){?><img src="images/pending.png"/><?php } ?></td>
				<td class="textAlign" ><a href="Patient_History.php?pat_id=<?php echo $DistinctRslt[0]['patient_id']; if(!empty($_GET['disp'])) { ?>&disp=<?php echo $_GET['disp']; } if(!empty($_GET['assignId'])) {?>&assignId=<?php echo $_GET['assignId']; } if(!empty($_GET['refid'])) { ?>&refid=<?php echo $_GET['refid']; } if(!empty($_GET['start'])) { ?>&start=<?php echo $_GET['start']; } ?>"><?php echo $DistinctRslt[0]['patient_name']; ?><?php echo $color; ?><?php if($Get_Feedback[0]['pat_resp_status']!=0){ echo $FedStatus; } ?></a></td>
				<td class="textAlign"><?php if($DistinctRslt[0]['patient_src']==11) { ?><img src="images/mas_icon.png" /> 
				<?php } else if($DistinctRslt[0]['patient_src']==13) { ?><span style="width:10px; padding:3px; color:#fff; background:red;">Sushrutt</span><?php } else if($DistinctRslt[0]['patient_src']==14) { ?><span style="width:10px; padding:3px; color:#fff; background:red;">Mullen-Us</span><?php } else { ?><img src="images/cold_icon.png" /> <?php } ?></td>
				<td class="textAlign" ><?php echo $status; ?></td>
				<td class="textAlign"><?php echo $DistinctRslt[0]['patient_mob']; ?></td>
				<td class="textAlign" ><?php echo $DistinctRslt[0]['patient_loc']; ?></td>
				<td class="textAlign"><select name="slctAssign" onchange="return Assignsubmit(this.value,<?php echo $DistinctRslt[0]['patient_id']; ?>);">
				<?php 
				
				
				?>
				<option value="0">Select</option>
				<?php 
								$i=30;
									
									foreach($ChkInUsrName as $UsrNameList){
										if($UsrNameList['chk_userid']==$DistinctRslt[0]['assigned_to']){
								?> 
									<option value="<?php echo stripslashes($UsrNameList['chk_userid']);?>" selected>
										<?php echo stripslashes($UsrNameList['chk_username']);?></option>
									<?php }?>
										<option value="<?php echo stripslashes($UsrNameList['chk_userid']);?>" >
										<?php echo stripslashes($UsrNameList['chk_username']);?></option>
									
									
									<?php 
										$i++;
										
									}?> 
				
				</select></td>
				
				<td class="textAlign"><?php echo $DistinctRslt[0]['patient_email']; ?></td>
				<!--<td class="textAlign"><a href="Add_Patient_Attachments.php?pat_id=<?php echo $DistinctRslt[0]['patient_id'];  ?>" title="Add/View Patient Attachments"><img src="images/i-9.png" /></a></td>-->
				<td class="textAlign"><a href="Edit-Patient.php?pat_id=<?php echo $DistinctRslt[0]['patient_id'];?><?php if(!empty($_GET['disp'])) { ?>&disp=<?php echo $_GET['disp']; } if(!empty($_GET['assignId'])) { ?>&assignId=<?php echo $_GET['assignId']; } if(!empty($_GET['refid'])) { ?>&refid=<?php echo $_GET['refid']; } if(!empty($_GET['start'])) { ?>&start=<?php echo $_GET['start']; } ?>" title="Edit Patient Details"><img src="images/i-5.png" /></a></td>
				<!--<td class="textAlign"><?php echo $Follow_Date=date('d-m-Y',strtotime($DistinctRslt[0]['follow_date']));?></td>-->
				
				</tr>
				<?php $j++; }  ?>
				
				</table>
			<?php
			}
			if($busResultRefState==true){
				?>
			<table class="tableclass1">	
			<form id="frmstatus" name="frmstatus" method="post" action="" onsubmit="return chkForm()">
			<input type="hidden" name="cmdStatus" value="">
			<input type="hidden" name="pat_id" value="">
			<input type="hidden" name="status_id" value="">
					<tr class="bgimg"><th>Refered Date</th>
					<th>Patient ID</th>
					<th>Patient Name</th>
					<th>Source</th>
					<th>Status</th>
					<th>Contact no.</th>
					<th>Location</th>
					<th>Assigned To</th>						
					<th>Email</th>
					<!--<th>Add / View Attachments</th>-->
					<th>Edit</th>
			
			
			</tr>	
			<?php
				$j=0;
				foreach($busResultRefState as $list){
					$DistinctRslt = $objQuery->mysqlSelect("*","patient_tab","patient_id='".$list['PatId']."'","","","",""); 
					 if($DistinctRslt[0]['urgentState']==1){
						$color = "<span style='font-size:18px;  color:red;font-weight:bold;'>*</span>";
						}else{
						$color = "<span style='font-size:16px;  color:red;font-weight:bold;'></span>";
						}
						
						$Get_Feedback = $objQuery->mysqlSelect("*","patient_feedback","patient_id='".$list['PatId']."'","","","","");

						if($Get_Feedback[0]['pat_resp_status']!=0 && $Get_Feedback[0]['pat_resp_status']==1){
						$FedStatus = "<span><img src='images/tickgreen.png' /></span>";
						}else if($Get_Feedback[0]['pat_resp_status']!=0 && $Get_Feedback[0]['pat_resp_status']==2){
						$FedStatus = "<span><img src='images/tickred.png' /></span>";
						}
			
			
			$chkStatus = $objQuery->mysqlSelect("*","chat_notification","patient_id='".$list['PatId']."'and status_id=2","","","","");
			$getSource = $objQuery->mysqlSelect("*","source_list","source_id='".$DistinctRslt[0]['patient_src']."'" ,"","","","");
		
		date_default_timezone_set('Asia/Kolkata');
		$cur_Date=date('Y-m-d h:i:s');
		$subtract_days = 2;
		$Check_Date = date('Y-m-d h:i:s',strtotime($cur_Date) - (24*3600*$subtract_days));
		
		$Ref_Date = date('d-m-Y h:i',strtotime($chkStatus[0]['TImestamp']));
		
	if($chkStatus==true)
		{
			if($chkStatus[0]['TImestamp']<= $Check_Date){
				$status="<span style='font-size:12px; color:red;font-weight:bold;'>REFERRED</span>";
			}else{
			$status="<span style='font-size:12px; color:#f9e223;font-weight:bold;'>REFERRED</span>";
			}
		}
		
			?>
			<tr style="background:#f1f2f2;">
				<td class="textAlign"><?php echo $Ref_Date;?></td>
				<td class="textAlign"><?php if($DistinctRslt[0]['transaction_status']=="TXN_SUCCESS"){ ?><span style="background:#f4081e; padding:5px; color:#fff;"><?php echo $DistinctRslt[0]['patient_id']; ?></span><img src="images/paid_icon.png"/><?php } else { ?><span style="background:#04bad5; padding:5px; color:#fff;"><?php echo $DistinctRslt[0]['patient_id']; ?></span><?php }  if($DistinctRslt[0]['transaction_status']=="Pending"){?><img src="images/pending.png"/><?php } ?></td>
				<td class="textAlign" ><a href="Patient_History.php?pat_id=<?php echo $DistinctRslt[0]['patient_id']; if(!empty($_GET['disp'])) { ?>&disp=<?php echo $_GET['disp']; } if(!empty($_GET['assignId'])) {?>&assignId=<?php echo $_GET['assignId']; } if(!empty($_GET['refid'])) { ?>&refid=<?php echo $_GET['refid']; } if(!empty($_GET['start'])) { ?>&start=<?php echo $_GET['start']; } ?>"><?php echo $DistinctRslt[0]['patient_name']; ?><?php echo $color; ?><?php if($Get_Feedback[0]['pat_resp_status']!=0){ echo $FedStatus; } ?></a></td>
				<td class="textAlign"><?php if($DistinctRslt[0]['patient_src']==11) { ?><img src="images/mas_icon.png" /> 
				<?php } else if($DistinctRslt[0]['patient_src']==13) { ?><span style="width:10px; padding:3px; color:#fff; background:red;">Sushrutt</span><?php } else if($DistinctRslt[0]['patient_src']==14) { ?><span style="width:10px; padding:3px; color:#fff; background:red;">Mullen-Us</span><?php } else { ?><img src="images/cold_icon.png" /> <?php } ?></td>
				<td class="textAlign" ><?php echo $status; ?></td>
				<td class="textAlign"><?php echo $DistinctRslt[0]['patient_mob']; ?></td>
				<td class="textAlign" ><?php echo $DistinctRslt[0]['patient_loc']; ?></td>
				<td class="textAlign"><select name="slctAssign" onchange="return Assignsubmit(this.value,<?php echo $DistinctRslt[0]['patient_id']; ?>);">
				<?php 
				
				
				?>
				<option value="0">Select</option>
				<?php 
								$i=30;
									
									foreach($ChkInUsrName as $UsrNameList){
										if($UsrNameList['chk_userid']==$DistinctRslt[0]['assigned_to']){
								?> 
									<option value="<?php echo stripslashes($UsrNameList['chk_userid']);?>" selected>
										<?php echo stripslashes($UsrNameList['chk_username']);?></option>
									<?php }?>
										<option value="<?php echo stripslashes($UsrNameList['chk_userid']);?>" >
										<?php echo stripslashes($UsrNameList['chk_username']);?></option>
									
									
									<?php 
										$i++;
										
									}?> 
				
				</select></td>
				
				<td class="textAlign"><?php echo $DistinctRslt[0]['patient_email']; ?></td>
				<!--<td class="textAlign"><a href="Add_Patient_Attachments.php?pat_id=<?php echo $DistinctRslt[0]['patient_id'];  ?>" title="Add/View Patient Attachments"><img src="images/i-9.png" /></a></td>-->
				<td class="textAlign"><a href="Edit-Patient.php?pat_id=<?php echo $DistinctRslt[0]['patient_id'];?><?php if(!empty($_GET['disp'])) { ?>&disp=<?php echo $_GET['disp']; } if(!empty($_GET['assignId'])) { ?>&assignId=<?php echo $_GET['assignId']; } if(!empty($_GET['refid'])) { ?>&refid=<?php echo $_GET['refid']; } if(!empty($_GET['start'])) { ?>&start=<?php echo $_GET['start']; } ?>" title="Edit Patient Details"><img src="images/i-5.png" /></a></td>
				<!--<td class="textAlign"><?php echo $Follow_Date=date('d-m-Y',strtotime($DistinctRslt[0]['follow_date']));?></td>-->
				
				</tr>
				<?php $j++; }  ?>
				
				</table>
			<?php
			}
			
			
			
			if($ResultRespondState==true){ ?>
				<table class="tableclass1">
			<form id="frmstatus" name="frmstatus" method="post" action="" onsubmit="return chkForm()">
			<input type="hidden" name="cmdStatus" value="">
			<input type="hidden" name="pat_id" value="">
			<input type="hidden" name="status_id" value="">
					<tr class="bgimg"><th>Date & Time</th>
					<th>Patient ID</th>
					<th>Patient Name</th>
					<th>Source</th>
					<th>Status</th>
					<th>Contact no.</th>
					<th>Location</th>
					<th>Assigned To</th>						
					<th>Email</th>
					<!--<th>Add / View Attachments</th>-->
					<th>Edit</th>
			
			
			</tr>
			<?php
				$j=0;
				foreach($ResultRespondState as $list){
					$DistinctRslt = $objQuery->mysqlSelect("*","patient_tab","patient_id='".$list['PatId']."'","","","",""); 
					 if($DistinctRslt[0]['urgentState']==1){
						$color = "<span style='font-size:18px;  color:red;font-weight:bold;'>*</span>";
						}else{
						$color = "<span style='font-size:16px;  color:red;font-weight:bold;'></span>";
						}
						
						$Get_Feedback = $objQuery->mysqlSelect("*","patient_feedback","patient_id='".$list['PatId']."'","","","","");

						if($Get_Feedback[0]['pat_resp_status']!=0 && $Get_Feedback[0]['pat_resp_status']==1){
						$FedStatus = "<span><img src='images/tickgreen.png' /></span>";
						}else if($Get_Feedback[0]['pat_resp_status']!=0 && $Get_Feedback[0]['pat_resp_status']==2){
						$FedStatus = "<span><img src='images/tickred.png' /></span>";
						}
			
			
			$chkStatus = $objQuery->mysqlSelect("*","patient_referal","patient_id='".$list['PatId']."'","","","","");
			$getSource = $objQuery->mysqlSelect("*","source_list","source_id='".$DistinctRslt[0]['patient_src']."'" ,"","","","");
		
		if($chkStatus[0]['provider_status']==1)
		{
			$status="<span style='font-size:12px; color:#01d337;font-weight:bold;'>RESPONDED</span>";
		}
		if($chkStatus[0]['provider_status']==4)
		{
			$status="<span style='font-size:12px; color:#01d337;font-weight:bold;'>STAGED</span>";
		}
		
			?>
			
			<tr style="background:#f1f2f2;">
				<td class="textAlign"><?php echo $Date=date('d-m-Y',strtotime($DistinctRslt[0]['TImestamp']));?></td>
				<td class="textAlign"><?php if($DistinctRslt[0]['transaction_status']=="TXN_SUCCESS"){ ?><span style="background:#f4081e; padding:5px; color:#fff;"><?php echo $DistinctRslt[0]['patient_id']; ?></span><img src="images/paid_icon.png"/><?php } else { ?><span style="background:#04bad5; padding:5px; color:#fff;"><?php echo $DistinctRslt[0]['patient_id']; ?></span><?php }  if($DistinctRslt[0]['transaction_status']=="Pending"){?><img src="images/pending.png"/><?php } ?></td>
				<td class="textAlign" ><a href="Patient_History.php?pat_id=<?php echo $DistinctRslt[0]['patient_id']; if(!empty($_GET['disp'])) { ?>&disp=<?php echo $_GET['disp']; } if(!empty($_GET['assignId'])) {?>&assignId=<?php echo $_GET['assignId']; } if(!empty($_GET['refid'])) { ?>&refid=<?php echo $_GET['refid']; } if(!empty($_GET['start'])) { ?>&start=<?php echo $_GET['start']; } ?>"><?php echo $DistinctRslt[0]['patient_name']; ?><?php echo $color; ?><?php if($Get_Feedback[0]['pat_resp_status']!=0){ echo $FedStatus; } ?></a></td>
				<td class="textAlign"><?php if($DistinctRslt[0]['patient_src']==11) { ?><img src="images/mas_icon.png" /> 
				<?php } else if($DistinctRslt[0]['patient_src']==13) { ?><span style="width:10px; padding:3px; color:#fff; background:red;">Sushrutt</span><?php } else if($DistinctRslt[0]['patient_src']==14) { ?><span style="width:10px; padding:3px; color:#fff; background:red;">Mullen-Us</span><?php } else { ?><img src="images/cold_icon.png" /> <?php } ?></td>
				<td class="textAlign" ><?php echo $status; ?></td>
				<td class="textAlign"><?php echo $DistinctRslt[0]['patient_mob']; ?></td>
				<td class="textAlign" ><?php echo $DistinctRslt[0]['patient_loc']; ?></td>
				<td class="textAlign"><select name="slctAssign" onchange="return Assignsubmit(this.value,<?php echo $DistinctRslt[0]['patient_id']; ?>);">
				<?php 
				
				
				?>
				<option value="0">Select</option>
				<?php 
								$i=30;
									
									foreach($ChkInUsrName as $UsrNameList){
										if($UsrNameList['chk_userid']==$DistinctRslt[0]['assigned_to']){
								?> 
									<option value="<?php echo stripslashes($UsrNameList['chk_userid']);?>" selected>
										<?php echo stripslashes($UsrNameList['chk_username']);?></option>
									<?php }?>
										<option value="<?php echo stripslashes($UsrNameList['chk_userid']);?>" >
										<?php echo stripslashes($UsrNameList['chk_username']);?></option>
									
									
									<?php 
										$i++;
										
									}?> 
				
				</select></td>
				
				<td class="textAlign"><?php echo $DistinctRslt[0]['patient_email']; ?></td>
				<!--<td class="textAlign"><a href="Add_Patient_Attachments.php?pat_id=<?php echo $DistinctRslt[0]['patient_id'];  ?>" title="Add/View Patient Attachments"><img src="images/i-9.png" /></a></td>-->
				<td class="textAlign"><a href="Edit-Patient.php?pat_id=<?php echo $DistinctRslt[0]['patient_id'];?><?php if(!empty($_GET['disp'])) { ?>&disp=<?php echo $_GET['disp']; } if(!empty($_GET['assignId'])) { ?>&assignId=<?php echo $_GET['assignId']; } if(!empty($_GET['refid'])) { ?>&refid=<?php echo $_GET['refid']; } if(!empty($_GET['start'])) { ?>&start=<?php echo $_GET['start']; } ?>" title="Edit Patient Details"><img src="images/i-5.png" /></a></td>
				<!--<td class="textAlign"><?php echo $Follow_Date=date('d-m-Y',strtotime($DistinctRslt[0]['follow_date']));?></td>-->
				
				</tr>
				<?php $j++; }  ?>
				
				</table>
			<?php
			}
			
			if($ResultConvertedState==true){
				?>
				<table class="tableclass1">	
			<form id="frmstatus" name="frmstatus" method="post" action="" onsubmit="return chkForm()">
			<input type="hidden" name="cmdStatus" value="">
			<input type="hidden" name="pat_id" value="">
			<input type="hidden" name="status_id" value="">
					<tr class="bgimg"><th>Date & Time</th>
					<th>Patient ID</th>
					<th>Patient Name</th>
					<th>Source</th>
					<th>Status</th>
					<th>Contact no.</th>
					<th>Location</th>
					<th>Assigned To</th>						
					<th>Email</th>
					<!--<th>Add / View Attachments</th>-->
					<th>Edit</th>
			
			
			</tr>	
			<?php
				
				$j=0;
				foreach($ResultConvertedState as $list){
					$DistinctRslt = $objQuery->mysqlSelect("*","patient_tab","patient_id='".$list['PatId']."'","","","",""); 
					 if($DistinctRslt[0]['urgentState']==1){
						$color = "<span style='font-size:18px;  color:red;font-weight:bold;'>*</span>";
						}else{
						$color = "<span style='font-size:16px;  color:red;font-weight:bold;'></span>";
						}
						
						$Get_Feedback = $objQuery->mysqlSelect("*","patient_feedback","patient_id='".$list['PatId']."'","","","","");

						if($Get_Feedback[0]['pat_resp_status']!=0 && $Get_Feedback[0]['pat_resp_status']==1){
						$FedStatus = "<span><img src='images/tickgreen.png' /></span>";
						}else if($Get_Feedback[0]['pat_resp_status']!=0 && $Get_Feedback[0]['pat_resp_status']==2){
						$FedStatus = "<span><img src='images/tickred.png' /></span>";
						}
			
			
			$chkStatus = $objQuery->mysqlSelect("*","patient_referal","patient_id='".$list['PatId']."'and provider_status=2","","","","");
			$getSource = $objQuery->mysqlSelect("*","source_list","source_id='".$DistinctRslt[0]['patient_src']."'" ,"","","","");
		
		if($chkStatus==true)
		{
			$status="<span style='font-size:12px; color:#fc5900;font-weight:bold;'>CONVERTED</span>";
		}
		
			?>
		<tr style="background:#f1f2f2;">
				<td class="textAlign"><?php echo $Date=date('d-m-Y',strtotime($DistinctRslt[0]['TImestamp']));?></td>
				<td class="textAlign"><?php if($DistinctRslt[0]['transaction_status']=="TXN_SUCCESS"){ ?><span style="background:#f4081e; padding:5px; color:#fff;"><?php echo $DistinctRslt[0]['patient_id']; ?></span><img src="images/paid_icon.png"/><?php } else { ?><span style="background:#04bad5; padding:5px; color:#fff;"><?php echo $DistinctRslt[0]['patient_id']; ?></span><?php }  if($DistinctRslt[0]['transaction_status']=="Pending"){?><img src="images/pending.png"/><?php } ?></td>
				<td class="textAlign" ><a href="Patient_History.php?pat_id=<?php echo $DistinctRslt[0]['patient_id']; if(!empty($_GET['disp'])) { ?>&disp=<?php echo $_GET['disp']; } if(!empty($_GET['assignId'])) {?>&assignId=<?php echo $_GET['assignId']; } if(!empty($_GET['refid'])) { ?>&refid=<?php echo $_GET['refid']; } if(!empty($_GET['start'])) { ?>&start=<?php echo $_GET['start']; } ?>"><?php echo $DistinctRslt[0]['patient_name']; ?><?php echo $color; ?><?php if($Get_Feedback[0]['pat_resp_status']!=0){ echo $FedStatus; } ?></a></td>
				<td class="textAlign"><?php if($DistinctRslt[0]['patient_src']==11) { ?><img src="images/mas_icon.png" /> 
				<?php } else if($DistinctRslt[0]['patient_src']==13) { ?><span style="width:10px; padding:3px; color:#fff; background:red;">Sushrutt</span><?php } else if($DistinctRslt[0]['patient_src']==14) { ?><span style="width:10px; padding:3px; color:#fff; background:red;">Mullen-Us</span><?php } else { ?><img src="images/cold_icon.png" /> <?php } ?></td>
				<td class="textAlign" ><?php echo $status; ?></td>
				<td class="textAlign"><?php echo $DistinctRslt[0]['patient_mob']; ?></td>
				<td class="textAlign" ><?php echo $DistinctRslt[0]['patient_loc']; ?></td>
				<td class="textAlign"><select name="slctAssign" onchange="return Assignsubmit(this.value,<?php echo $DistinctRslt[0]['patient_id']; ?>);">
				<?php 
				
				
				?>
				<option value="0">Select</option>
				<?php 
								$i=30;
									
									foreach($ChkInUsrName as $UsrNameList){
										if($UsrNameList['chk_userid']==$DistinctRslt[0]['assigned_to']){
								?> 
									<option value="<?php echo stripslashes($UsrNameList['chk_userid']);?>" selected>
										<?php echo stripslashes($UsrNameList['chk_username']);?></option>
									<?php }?>
										<option value="<?php echo stripslashes($UsrNameList['chk_userid']);?>" >
										<?php echo stripslashes($UsrNameList['chk_username']);?></option>
									
									
									<?php 
										$i++;
										
									}?> 
				
				</select></td>
				
				<td class="textAlign"><?php echo $DistinctRslt[0]['patient_email']; ?></td>
				<!--<td class="textAlign"><a href="Add_Patient_Attachments.php?pat_id=<?php echo $DistinctRslt[0]['patient_id'];  ?>" title="Add/View Patient Attachments"><img src="images/i-9.png" /></a></td>-->
				<td class="textAlign"><a href="Edit-Patient.php?pat_id=<?php echo $DistinctRslt[0]['patient_id'];?><?php if(!empty($_GET['disp'])) { ?>&disp=<?php echo $_GET['disp']; } if(!empty($_GET['assignId'])) { ?>&assignId=<?php echo $_GET['assignId']; } if(!empty($_GET['refid'])) { ?>&refid=<?php echo $_GET['refid']; } if(!empty($_GET['start'])) { ?>&start=<?php echo $_GET['start']; } ?>" title="Edit Patient Details"><img src="images/i-5.png" /></a></td>
				<!--<td class="textAlign"><?php echo $Follow_Date=date('d-m-Y',strtotime($DistinctRslt[0]['follow_date']));?></td>-->
				
				</tr>
				<?php $j++; }  ?>
				
				</table>
			<?php
			}
			
			if($totrefpat==true){
				?>
			<table class="tableclass1">
			<form id="frmstatus" name="frmstatus" method="post" action="" onsubmit="return chkForm()">
			<input type="hidden" name="cmdStatus" value="">
			<input type="hidden" name="pat_id" value="">
			<input type="hidden" name="status_id" value="">
					<tr class="bgimg"><th>Date & Time</th>
					<th>Patient ID</th>
					<th>Patient Name</th>
					<th>Department</th>
					<th>Status</th>
					<th>Contact no.</th>
					<th>Location</th>
					<th>Assigned To</th>						
					<th>Email</th>
					<!--<th>Add / View Attachments</th>-->
					<th>Edit</th>
			
			
			</tr>	
			<?php
				$j=0;
				foreach($totrefpat as $list){
					$DistinctRslt = $objQuery->mysqlSelect("*","patient_tab","patient_id='".$list['PatId']."'","","","",""); 
					 if($DistinctRslt[0]['urgentState']==1){
						$color = "<span style='font-size:18px;  color:red;font-weight:bold;'>*</span>";
						}else{
						$color = "<span style='font-size:16px;  color:red;font-weight:bold;'></span>";
						}
						
						$Get_Feedback = $objQuery->mysqlSelect("*","patient_feedback","patient_id='".$list['PatId']."'","","","","");

						if($Get_Feedback[0]['pat_resp_status']!=0 && $Get_Feedback[0]['pat_resp_status']==1){
						$FedStatus = "<span><img src='images/tickgreen.png' /></span>";
						}else if($Get_Feedback[0]['pat_resp_status']!=0 && $Get_Feedback[0]['pat_resp_status']==2){
						$FedStatus = "<span><img src='images/tickred.png' /></span>";
						}
			
			
			$chkStatus = $objQuery->mysqlSelect("*","patient_referal","patient_id='".$list['PatId']."'","","","","");
			$getDept = $objQuery->mysqlSelect("*","specialization","spec_id='".$DistinctRslt[0]['medDept']."'" ,"","","","");
		
		date_default_timezone_set('Asia/Kolkata');
		$today=date('Y-m-d h:i:s');
		$subtract_days = 2;
		$Check_Date = date('Y-m-d h:i:s',strtotime($today) - (24*3600*$subtract_days));
		
		
	if($chkStatus[0]['bucket_status']==1)
		{
			$status="<span style='font-size:12px;  color:red;font-weight:bold;'>NEW</span>"; //Capture Bucket
		}
		if($chkStatus[0]['bucket_status']==2)
		{
			if($chkStatus[0]['timestamp']<= $Check_Date){
				$status="<span style='font-size:12px; color:red;font-weight:bold;'>REFERRED</span>";
			}else{																			//Refer Bucket
			$status="<span style='font-size:12px; color:#f9e223;font-weight:bold;'>REFERRED</span>";
			}
		}
		if($chkStatus[0]['bucket_status']==3)
		{
			$status="<span style='font-size:12px;  color:red;font-weight:bold;'>P-AWAITING</span>"; //P-Awaiting Bucket
		}
		if($chkStatus[0]['bucket_status']==4)
		{
			$status="<span style='font-size:12px;  color:red;font-weight:bold;'>NOT-QUALIFIED</span>"; //Close Bucket
		}
		
		if($chkStatus[0]['bucket_status']==10)
		{
			$status="<span style='font-size:12px;  color:red;font-weight:bold;'>NOT CONVERTED</span>"; //Close Bucket
		}
		if($chkStatus[0]['bucket_status']==5)
		{
			$status="<span style='font-size:12px;  color:#01d337;font-weight:bold;'>RESPONDED</span>"; //Respond Bucket
		}
		if($chkStatus[0]['bucket_status']==6)
		{
			$status="<span style='font-size:12px;  color:#01d337;font-weight:bold;'>RESPONSE-P FAILED</span>";//Respond Bucket
		}
		if($chkStatus[0]['bucket_status']==7)
		{
			$status="<span style='font-size:12px; color:#f68c34;font-weight:bold;'>STAGED</span>"; //Conversion Bucket
		}
		if($chkStatus[0]['bucket_status']==8)
		{
			$status="<span style='font-size:12px; color:#f68c34;font-weight:bold;'>OP-CONVERTED</span>"; //Conversion Bucket
		}
		if($chkStatus[0]['bucket_status']==9)
		{
			$status="<span style='font-size:12px;  color:#f68c34;font-weight:bold;'>IP-CONVERTED</span>";//Invoice Bucket
		}
		if($chkStatus[0]['bucket_status']==11)
		{
			$status="<span style='font-size:12px;  color:#f68c34;font-weight:bold;'>INVOICED</span>";//Invoice Bucket
		}
		if($chkStatus[0]['bucket_status']==12)
		{
			$status="<span style='font-size:12px;  color:#f68c34;font-weight:bold;'>PAYMENT RECEIVED</span>"; //Invoice Bucket
		}
		
			?>
			
			<tr style="background:#f1f2f2;">
				<td class="textAlign"><?php echo $Date=date('d-m-Y',strtotime($DistinctRslt[0]['TImestamp']));?></td>
				<td class="textAlign"><?php if($DistinctRslt[0]['transaction_status']=="TXN_SUCCESS"){ ?><span style="background:#f4081e; padding:5px; color:#fff;"><?php echo $DistinctRslt[0]['patient_id']; ?></span><img src="images/paid_icon.png"/><?php } else { ?><span style="background:#04bad5; padding:5px; color:#fff;"><?php echo $DistinctRslt[0]['patient_id']; ?></span><?php }  if($DistinctRslt[0]['transaction_status']=="Pending"){?><img src="images/pending.png"/><?php } ?></td>
				<td class="textAlign" ><a href="Patient_History.php?pat_id=<?php echo $DistinctRslt[0]['patient_id']; if(!empty($_GET['disp'])) { ?>&disp=<?php echo $_GET['disp']; } if(!empty($_GET['assignId'])) {?>&assignId=<?php echo $_GET['assignId']; } if(!empty($_GET['refid'])) { ?>&refid=<?php echo $_GET['refid']; } if(!empty($_GET['start'])) { ?>&start=<?php echo $_GET['start']; } ?>"><?php echo $DistinctRslt[0]['patient_name']; ?><?php echo $color; ?><?php if($Get_Feedback[0]['pat_resp_status']!=0){ echo $FedStatus; } ?></a></td>
				<td class="textAlign"><?php echo $getDept[0]['spec_name'];?></td>
				<td class="textAlign" ><?php echo $status; ?></td>
				<td class="textAlign"><?php echo $DistinctRslt[0]['patient_mob']; ?></td>
				<td class="textAlign" ><?php echo $DistinctRslt[0]['patient_loc']; ?></td>
				<td class="textAlign"><select name="slctAssign" onchange="return Assignsubmit(this.value,<?php echo $DistinctRslt[0]['patient_id']; ?>);">
				<?php 
				
				
				?>
				<option value="0">Select</option>
				<?php 
								$i=30;
									
									foreach($ChkInUsrName as $UsrNameList){
										if($UsrNameList['chk_userid']==$DistinctRslt[0]['assigned_to']){
								?> 
									<option value="<?php echo stripslashes($UsrNameList['chk_userid']);?>" selected>
										<?php echo stripslashes($UsrNameList['chk_username']);?></option>
									<?php }?>
										<option value="<?php echo stripslashes($UsrNameList['chk_userid']);?>" >
										<?php echo stripslashes($UsrNameList['chk_username']);?></option>
									
									
									<?php 
										$i++;
										
									}?> 
				
				</select></td>
				
				<td class="textAlign"><?php echo $DistinctRslt[0]['patient_email']; ?></td>
				<!--<td class="textAlign"><a href="Add_Patient_Attachments.php?pat_id=<?php echo $DistinctRslt[0]['patient_id'];  ?>" title="Add/View Patient Attachments"><img src="images/i-9.png" /></a></td>-->
				<td class="textAlign"><a href="Edit-Patient.php?pat_id=<?php echo $DistinctRslt[0]['patient_id'];?><?php if(!empty($_GET['disp'])) { ?>&disp=<?php echo $_GET['disp']; } if(!empty($_GET['assignId'])) { ?>&assignId=<?php echo $_GET['assignId']; } if(!empty($_GET['refid'])) { ?>&refid=<?php echo $_GET['refid']; } if(!empty($_GET['start'])) { ?>&start=<?php echo $_GET['start']; } ?>" title="Edit Patient Details"><img src="images/i-5.png" /></a></td>
				<!--<td class="textAlign"><?php echo $Follow_Date=date('d-m-Y',strtotime($DistinctRslt[0]['follow_date']));?></td>-->
				
				</tr>
				<?php $j++; }  ?>
				
				</table>
			<?php
			}
			
			if($ResultAssigned==true){
				?>
			<table class="tableclass1">
			<form id="frmstatus" name="frmstatus" method="post" action="" onsubmit="return chkForm()">
			<input type="hidden" name="cmdStatus" value="">
			<input type="hidden" name="pat_id" value="">
			<input type="hidden" name="status_id" value="">
					<tr class="bgimg"><th>Date & Time</th>
					<th>Patient ID</th>
					<th>Patient Name</th>
					<th>Source</th>
					<th>Status</th>
					<th>Contact no.</th>
					<th>Location</th>
					<th>Assigned To</th>						
					<th>Email</th>
					<!--<th>Add / View Attachments</th>-->
					<th>Edit</th>
			
			
			</tr>	
			<?php
				$j=0;
				foreach($ResultAssigned as $list){
					$DistinctRslt = $objQuery->mysqlSelect("*","patient_tab","patient_id='".$list['PatId']."'","","","",""); 
					 if($DistinctRslt[0]['urgentState']==1){
						$color = "<span style='font-size:18px;  color:red;font-weight:bold;'>*</span>";
						}else{
						$color = "<span style='font-size:16px;  color:red;font-weight:bold;'></span>";
						}
						
						$Get_Feedback = $objQuery->mysqlSelect("*","patient_feedback","patient_id='".$list['PatId']."'","","","","");

						if($Get_Feedback[0]['pat_resp_status']!=0 && $Get_Feedback[0]['pat_resp_status']==1){
						$FedStatus = "<span><img src='images/tickgreen.png' /></span>";
						}else if($Get_Feedback[0]['pat_resp_status']!=0 && $Get_Feedback[0]['pat_resp_status']==2){
						$FedStatus = "<span><img src='images/tickred.png' /></span>";
						}
			
			
			$chkStatus = $objQuery->mysqlSelect("*","patient_referal","patient_id='".$list['PatId']."'","","","","");
			$getSource = $objQuery->mysqlSelect("*","source_list","source_id='".$DistinctRslt[0]['patient_src']."'" ,"","","","");
		
	date_default_timezone_set('Asia/Kolkata');
		$today=date('Y-m-d h:i:s');
		$subtract_days = 2;
		$Check_Date = date('Y-m-d h:i:s',strtotime($today) - (24*3600*$subtract_days));
		
	if($chkStatus[0]['bucket_status']==1)
		{
			$status="<span style='font-size:12px;  color:red;font-weight:bold;'>NEW</span>"; //Capture Bucket
		}
		if($chkStatus[0]['bucket_status']==2)
		{
			if($chkStatus[0]['timestamp']<= $Check_Date){
				$status="<span style='font-size:12px; color:red;font-weight:bold;'>REFERRED</span>";
			}else{																			//Refer Bucket
			$status="<span style='font-size:12px; color:#f9e223;font-weight:bold;'>REFERRED</span>";
			}
		}
		if($chkStatus[0]['bucket_status']==3)
		{
			$status="<span style='font-size:12px;  color:red;font-weight:bold;'>P-AWAITING</span>"; //P-Awaiting Bucket
		}
		if($chkStatus[0]['bucket_status']==4)
		{
			$status="<span style='font-size:12px;  color:red;font-weight:bold;'>NOT-QUALIFIED</span>"; //Close Bucket
		}
		
		if($chkStatus[0]['bucket_status']==10)
		{
			$status="<span style='font-size:12px;  color:red;font-weight:bold;'>NOT CONVERTED</span>"; //Close Bucket
		}
		if($chkStatus[0]['bucket_status']==5)
		{
			$status="<span style='font-size:12px;  color:#01d337;font-weight:bold;'>RESPONDED</span>"; //Respond Bucket
		}
		if($chkStatus[0]['bucket_status']==6)
		{
			$status="<span style='font-size:12px;  color:#01d337;font-weight:bold;'>RESPONSE-P FAILED</span>";//Respond Bucket
		}
		if($chkStatus[0]['bucket_status']==7)
		{
			$status="<span style='font-size:12px; color:#f68c34;font-weight:bold;'>STAGED</span>"; //Conversion Bucket
		}
		if($chkStatus[0]['bucket_status']==8)
		{
			$status="<span style='font-size:12px; color:#f68c34;font-weight:bold;'>OP-CONVERTED</span>"; //Conversion Bucket
		}
		if($chkStatus[0]['bucket_status']==9)
		{
			$status="<span style='font-size:12px;  color:#f68c34;font-weight:bold;'>IP-CONVERTED</span>";//Invoice Bucket
		}
		if($chkStatus[0]['bucket_status']==11)
		{
			$status="<span style='font-size:12px;  color:#f68c34;font-weight:bold;'>INVOICED</span>";//Invoice Bucket
		}
		if($chkStatus[0]['bucket_status']==12)
		{
			$status="<span style='font-size:12px;  color:#f68c34;font-weight:bold;'>PAYMENT RECEIVED</span>"; //Invoice Bucket
		}
		
			?>
			<tr style="background:#f1f2f2;">
				<td class="textAlign"><?php echo $Date=date('d-m-Y',strtotime($DistinctRslt[0]['TImestamp']));?></td>
				<td class="textAlign"><?php if($DistinctRslt[0]['transaction_status']=="TXN_SUCCESS"){ ?><span style="background:#f4081e; padding:5px; color:#fff;"><?php echo $DistinctRslt[0]['patient_id']; ?></span><img src="images/paid_icon.png"/><?php } else { ?><span style="background:#04bad5; padding:5px; color:#fff;"><?php echo $DistinctRslt[0]['patient_id']; ?></span><?php }  if($DistinctRslt[0]['transaction_status']=="Pending"){?><img src="images/pending.png"/><?php } ?></td>
				<td class="textAlign" ><a href="Patient_History.php?pat_id=<?php echo $DistinctRslt[0]['patient_id']; if(!empty($_GET['disp'])) { ?>&disp=<?php echo $_GET['disp']; } if(!empty($_GET['assignId'])) {?>&assignId=<?php echo $_GET['assignId']; } if(!empty($_GET['refid'])) { ?>&refid=<?php echo $_GET['refid']; } if(!empty($_GET['start'])) { ?>&start=<?php echo $_GET['start']; } ?>"><?php echo $DistinctRslt[0]['patient_name']; ?><?php echo $color; ?><?php if($Get_Feedback[0]['pat_resp_status']!=0){ echo $FedStatus; } ?></a></td>
				<td class="textAlign"><?php if($DistinctRslt[0]['patient_src']==11) { ?><img src="images/mas_icon.png" /> 
				<?php } else if($DistinctRslt[0]['patient_src']==13) { ?><span style="width:10px; padding:3px; color:#fff; background:red;">Sushrutt</span><?php } else if($DistinctRslt[0]['patient_src']==14) { ?><span style="width:10px; padding:3px; color:#fff; background:red;">Mullen-Us</span><?php } else { ?><img src="images/cold_icon.png" /> <?php } ?></td>
				<td class="textAlign" ><?php echo $status; ?></td>
				<td class="textAlign"><?php echo $DistinctRslt[0]['patient_mob']; ?></td>
				<td class="textAlign" ><?php echo $DistinctRslt[0]['patient_loc']; ?></td>
				<td class="textAlign"><select name="slctAssign" onchange="return Assignsubmit(this.value,<?php echo $DistinctRslt[0]['patient_id']; ?>);">
				<?php 
				
				
				?>
				<option value="0">Select</option>
				<?php 
								$i=30;
									
									foreach($ChkInUsrName as $UsrNameList){
										if($UsrNameList['chk_userid']==$DistinctRslt[0]['assigned_to']){
								?> 
									<option value="<?php echo stripslashes($UsrNameList['chk_userid']);?>" selected>
										<?php echo stripslashes($UsrNameList['chk_username']);?></option>
									<?php }?>
										<option value="<?php echo stripslashes($UsrNameList['chk_userid']);?>" >
										<?php echo stripslashes($UsrNameList['chk_username']);?></option>
									
									
									<?php 
										$i++;
										
									}?> 
				
				</select></td>
				
				<td class="textAlign"><?php echo $DistinctRslt[0]['patient_email']; ?></td>
				<!--<td class="textAlign"><a href="Add_Patient_Attachments.php?pat_id=<?php echo $DistinctRslt[0]['patient_id'];  ?>" title="Add/View Patient Attachments"><img src="images/i-9.png" /></a></td>-->
				<td class="textAlign"><a href="Edit-Patient.php?pat_id=<?php echo $DistinctRslt[0]['patient_id'];?><?php if(!empty($_GET['disp'])) { ?>&disp=<?php echo $_GET['disp']; } if(!empty($_GET['assignId'])) { ?>&assignId=<?php echo $_GET['assignId']; } if(!empty($_GET['refid'])) { ?>&refid=<?php echo $_GET['refid']; } if(!empty($_GET['start'])) { ?>&start=<?php echo $_GET['start']; } ?>" title="Edit Patient Details"><img src="images/i-5.png" /></a></td>
				<!--<td class="textAlign"><?php echo $Follow_Date=date('d-m-Y',strtotime($DistinctRslt[0]['follow_date']));?></td>-->
				
				</tr>
				<?php $j++; }  ?>
				<tr>
				<td colspan="10">
				
				</td>
				</tr>
				</table>
			<?php
			}
			
			if($SerchResult==true){
				?>
			<table class="tableclass1">
			<form id="frmstatus" name="frmstatus" method="post" action="" onsubmit="return chkForm()">
			<input type="hidden" name="cmdStatus" value="">
			<input type="hidden" name="pat_id" value="">
			<input type="hidden" name="status_id" value="">
					<tr class="bgimg"><th>Date & Time</th>
					<th>Patient ID</th>
					<th>Patient Name</th>
					<th>Source</th>
					<th>Department</th>
					<th>Status</th>
					<th>Contact no.</th>
					<th>Location</th>
					<th>Assigned To</th>						
					<th>Email</th>
					<!--<th>Add / View Attachments</th>-->
					<th>Edit</th>
			
			
			</tr>	
			<?php
				$j=0;
				foreach($SerchResult as $list){
					$DistinctRslt = $objQuery->mysqlSelect("*","patient_tab","patient_id='".$list['PatId']."'","","","",""); 
					 if($DistinctRslt[0]['urgentState']==1){
						$color = "<span style='font-size:18px;  color:red;font-weight:bold;'>*</span>";
						}else{
						$color = "<span style='font-size:16px;  color:red;font-weight:bold;'></span>";
						}
						
						$Get_Feedback = $objQuery->mysqlSelect("*","patient_feedback","patient_id='".$list['PatId']."'","","","","");

						if($Get_Feedback[0]['pat_resp_status']!=0 && $Get_Feedback[0]['pat_resp_status']==1){
						$FedStatus = "<span><img src='images/tickgreen.png' /></span>";
						}else if($Get_Feedback[0]['pat_resp_status']!=0 && $Get_Feedback[0]['pat_resp_status']==2){
						$FedStatus = "<span><img src='images/tickred.png' /></span>";
						}
			
			
			$chkStatus = $objQuery->mysqlSelect("*","patient_referal","patient_id='".$list['PatId']."'","","","","");
			$getDept = $objQuery->mysqlSelect("*","specialization","spec_id='".$DistinctRslt[0]['medDept']."'" ,"","","","");
		
	date_default_timezone_set('Asia/Kolkata');
		$today=date('Y-m-d h:i:s');
		$subtract_days = 2;
		$Check_Date = date('Y-m-d h:i:s',strtotime($today) - (24*3600*$subtract_days));
		
		
	if($chkStatus[0]['bucket_status']==1)
		{
			$status="<span style='font-size:12px;  color:red;font-weight:bold;'>NEW</span>"; //Capture Bucket
		}
		if($chkStatus[0]['bucket_status']==2)
		{
			if($chkStatus[0]['timestamp']<= $Check_Date){
				$status="<span style='font-size:12px; color:red;font-weight:bold;'>REFERRED</span>";
			}else{																			//Refer Bucket
			$status="<span style='font-size:12px; color:#f9e223;font-weight:bold;'>REFERRED</span>";
			}
		}
		if($chkStatus[0]['bucket_status']==3)
		{
			$status="<span style='font-size:12px;  color:red;font-weight:bold;'>P-AWAITING</span>"; //P-Awaiting Bucket
		}
		if($chkStatus[0]['bucket_status']==4)
		{
			$status="<span style='font-size:12px;  color:red;font-weight:bold;'>NOT-QUALIFIED</span>"; //Close Bucket
		}
		
		if($chkStatus[0]['bucket_status']==10)
		{
			$status="<span style='font-size:12px;  color:red;font-weight:bold;'>NOT CONVERTED</span>"; //Close Bucket
		}
		if($chkStatus[0]['bucket_status']==5)
		{
			$status="<span style='font-size:12px;  color:#01d337;font-weight:bold;'>RESPONDED</span>"; //Respond Bucket
		}
		if($chkStatus[0]['bucket_status']==6)
		{
			$status="<span style='font-size:12px;  color:#01d337;font-weight:bold;'>RESPONSE-P FAILED</span>";//Respond Bucket
		}
		if($chkStatus[0]['bucket_status']==7)
		{
			$status="<span style='font-size:12px; color:#f68c34;font-weight:bold;'>STAGED</span>"; //Conversion Bucket
		}
		if($chkStatus[0]['bucket_status']==8)
		{
			$status="<span style='font-size:12px; color:#f68c34;font-weight:bold;'>OP-CONVERTED</span>"; //Conversion Bucket
		}
		if($chkStatus[0]['bucket_status']==9)
		{
			$status="<span style='font-size:12px;  color:#f68c34;font-weight:bold;'>IP-CONVERTED</span>";//Invoice Bucket
		}
		if($chkStatus[0]['bucket_status']==11)
		{
			$status="<span style='font-size:12px;  color:#f68c34;font-weight:bold;'>INVOICED</span>";//Invoice Bucket
		}
		if($chkStatus[0]['bucket_status']==12)
		{
			$status="<span style='font-size:12px;  color:#f68c34;font-weight:bold;'>PAYMENT RECEIVED</span>"; //Invoice Bucket
		}
		
			?>
			
			<tr style="background:#f1f2f2;">
				<td class="textAlign"><?php echo $Date=date('d-m-Y',strtotime($DistinctRslt[0]['TImestamp']));?></td>
				<td class="textAlign"><?php if($DistinctRslt[0]['transaction_status']=="TXN_SUCCESS"){ ?><span style="background:#f4081e; padding:5px; color:#fff;"><?php echo $DistinctRslt[0]['patient_id']; ?></span><img src="images/paid_icon.png"/><?php } else { ?><span style="background:#04bad5; padding:5px; color:#fff;"><?php echo $DistinctRslt[0]['patient_id']; ?></span><?php }  if($DistinctRslt[0]['transaction_status']=="Pending"){?><img src="images/pending.png"/><?php } ?></td>
				<td class="textAlign" ><a href="Patient_History.php?pat_id=<?php echo $DistinctRslt[0]['patient_id']; if(!empty($_GET['disp'])) { ?>&disp=<?php echo $_GET['disp']; } if(!empty($_GET['assignId'])) {?>&assignId=<?php echo $_GET['assignId']; } if(!empty($_GET['refid'])) { ?>&refid=<?php echo $_GET['refid']; } if(!empty($_GET['start'])) { ?>&start=<?php echo $_GET['start']; } ?>"><?php echo $DistinctRslt[0]['patient_name']; ?><?php echo $color; ?><?php if($Get_Feedback[0]['pat_resp_status']!=0){ echo $FedStatus; } ?></a></td>
				<td class="textAlign"><?php if($DistinctRslt[0]['patient_src']==11) { ?><img src="images/mas_icon.png" /> 
				<?php } else if($DistinctRslt[0]['patient_src']==13) { ?><span style="width:10px; padding:3px; color:#fff; background:red;">Sushrutt</span><?php } else if($DistinctRslt[0]['patient_src']==14) { ?><span style="width:10px; padding:3px; color:#fff; background:red;">Mullen-Us</span><?php } else { ?><img src="images/cold_icon.png" /> <?php } ?></td>
				<td class="textAlign" ><?php echo $getDept[0]['spec_name']; ?></td>
				<td class="textAlign" ><?php echo $status; ?></td>
				<td class="textAlign"><?php echo $DistinctRslt[0]['patient_mob']; ?></td>
				<td class="textAlign" ><?php echo $DistinctRslt[0]['patient_loc']; ?></td>
				<td class="textAlign"><select name="slctAssign" onchange="return Assignsubmit(this.value,<?php echo $DistinctRslt[0]['patient_id']; ?>);">
				<?php 
				
				
				?>
				<option value="0">Select</option>
				<?php 
								$i=30;
									
									foreach($ChkInUsrName as $UsrNameList){
										if($UsrNameList['chk_userid']==$DistinctRslt[0]['assigned_to']){
								?> 
									<option value="<?php echo stripslashes($UsrNameList['chk_userid']);?>" selected>
										<?php echo stripslashes($UsrNameList['chk_username']);?></option>
									<?php }?>
										<option value="<?php echo stripslashes($UsrNameList['chk_userid']);?>" >
										<?php echo stripslashes($UsrNameList['chk_username']);?></option>
									
									
									<?php 
										$i++;
										
									}?> 
				
				</select></td>
				
				<td class="textAlign"><?php echo $DistinctRslt[0]['patient_email']; ?></td>
				<!--<td class="textAlign"><a href="Add_Patient_Attachments.php?pat_id=<?php echo $DistinctRslt[0]['patient_id'];  ?>" title="Add/View Patient Attachments"><img src="images/i-9.png" /></a></td>-->
				<td class="textAlign"><a href="Edit-Patient.php?pat_id=<?php echo $DistinctRslt[0]['patient_id'];?><?php if(!empty($_GET['disp'])) { ?>&disp=<?php echo $_GET['disp']; } if(!empty($_GET['assignId'])) { ?>&assignId=<?php echo $_GET['assignId']; } if(!empty($_GET['refid'])) { ?>&refid=<?php echo $_GET['refid']; } if(!empty($_GET['start'])) { ?>&start=<?php echo $_GET['start']; } ?>" title="Edit Patient Details"><img src="images/i-5.png" /></a></td>
				<!--<td class="textAlign"><?php echo $Follow_Date=date('d-m-Y',strtotime($DistinctRslt[0]['follow_date']));?></td>-->
				
				</tr>
				<?php $j++; }  ?>
				<tr>
				<td colspan="10">
				
				</td>
				</tr>
				</table>
			<?php
			}
			
			/*else { ?>
				
				<tr><td class="textAlign" colspan="10">No patient list found</td></tr>
			<?php } */
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

