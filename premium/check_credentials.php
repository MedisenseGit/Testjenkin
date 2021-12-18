<?php 

ob_start();
session_start();
error_reporting(0);  

require_once("../classes/querymaker.class.php");
//$objQuery = new mysqlRawquery();
include('send_mail_function.php');
include('send_text_message.php');

$systemIP = $_SERVER['REMOTE_ADDR'];

//Random Password Generator
function randomPassword() 
{
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) 
	{
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

date_default_timezone_set('Asia/Kolkata');
	$Cur_Date=date('Y-m-d H:i:s');
	
	
	
//USER DIRECT LOGIN
if(isset($_POST['signinDirect']))
{
	$txtUserName = $_POST['txtuser'];
	$txtPass = md5($_POST['txtpassword']);
	
	
	$result = mysqlSelect('*','our_partners',"(Email_id='".$txtUserName."' or cont_num1='".$txtUserName."') and password='".$txtPass."'");
	
	if($result==true)
	{
		
		//When he first login "login_status" should make it 1 ie. 1 for "signup success"
		$arrFields[] 	= 'login_status';
		$arrValues[] 	= "1";
		$updateMapping	=	mysqlUpdate('our_partners',$arrFields,$arrValues,"partner_id='".$result[0]['partner_id']."'");
		
		$mycircleDoctor 	= mysqlSelect("COUNT(DISTINCT(a.ref_id)) as Count_Doc","referal as a inner join doctor_hosp as c on c.doc_id=a.ref_id inner join mapping_hosp_referrer as d on d.hosp_id=c.hosp_id","a.doc_spec!=555 and a.anonymous_status!=1 and d.partner_id='".$result[0]['partner_id']."'","","","","");	
		
		$universalDoctor 	= mysqlSelect("COUNT(DISTINCT(a.ref_id)) as Count_Doc","referal as a inner join doctor_hosp as c on c.doc_id=a.ref_id","a.doc_spec!=555 and a.anonymous_status!=1","","","","");
		
		$getDignosticCenter = mysqlSelect("COUNT(DISTINCT(ref_id)) as Count_Doc","referal","doc_spec=89 and anonymous_status!=1","","","","");	
		$getOnlinePharma = mysqlSelect("COUNT(DISTINCT(ref_id)) as Count_Doc","referal","doc_spec=90 and anonymous_status!=1","","","","");	
	
		
		$_SESSION['mycircle_doc'] 		= 	$mycircleDoctor[0]['Count_Doc'];
		$_SESSION['universal_doc'] 		= 	$universalDoctor[0]['Count_Doc'];
		$_SESSION['diagnostics_center'] = 	$getDignosticCenter[0]['Count_Doc'];
		$_SESSION['online_pharma'] 		= 	$getOnlinePharma[0]['Count_Doc'];
		
		$totReferredCount 	= 	mysqlSelect('COUNT(DISTINCT(a.patient_id)) as Count_Records','patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join source_list as c on c.source_id=a.patient_src',"(c.partner_id='".$result[0]['partner_id']."') and (b.status2=2 or b.status2>=5)");
		
		$totRespondedCount =  mysqlSelect('COUNT(DISTINCT(a.patient_id)) as Count_Records','patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join source_list as c on c.source_id=a.patient_src',"(c.partner_id='".$result[0]['partner_id']."') and (b.status2>=5)");
		
		$totTreatedCount  = mysqlSelect('COUNT(DISTINCT(a.patient_id)) as Count_Records','patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join source_list as c on c.source_id=a.patient_src',"(c.partner_id='".$result[0]['partner_id']."') and (b.status2=6 or b.status2=7 or b.status2=8 or b.status2=9 or b.status2=11 or b.status2=12 or b.status2=13)");
		
		$totalBlogs 	 = 	mysqlSelect("COUNT(a.listing_id) as Count_Blogs","blogs_offers_events_listing as a inner join mapping_hosp_referrer as b on a.hosp_id=b.hosp_id","b.partner_id='".$result[0]['partner_id']."'","a.listing_id desc","","","");
		
		$totPendingCount = mysqlSelect('COUNT(DISTINCT(a.patient_id)) as Count_Records','patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join source_list as c on c.source_id=a.patient_src',"(c.partner_id='".$result[0]['partner_id']."') and (b.bucket_status=2)");
		
		//IF ITS TRUE THEN REDIRECTS TO "ALL RECORDS" PAGE
		$_SESSION['user_name'] 			= $result[0]['user_name'];
		$_SESSION['user_id'] 			= $result[0]['partner_id'];
		$_SESSION['company_name'] 		= $result[0]['contact_person'];
		$_SESSION['all_record'] 		= $allRecordCount[0]['Count_Records'];
		$_SESSION['tot_ref_count']  	= $totReferredCount[0]['Count_Records'];
		$_SESSION['tot_resp_count'] 	= $totRespondedCount[0]['Count_Records'];
		$_SESSION['tot_pending_count'] 	= $totPendingCount[0]['Count_Records'];
		$_SESSION['tot_treated_count'] 	= $totTreatedCount[0]['Count_Records'];
		$_SESSION['tot_Blogs'] 			= $totalBlogs[0]['Count_Blogs'];
		header('location:offers.php?s=Jobs&id='.md5($_POST['eventid']));
	}
	else
	{
		$_SESSION['status']		=	"error";
		$errorMessage="Login failed. Username or password are invalid.";
		header('location:'.$_POST['currenturl']);
	}

}


//USER LOGIN
if(isset($_POST['signin']))
{

	$txtUserName 	= 	$_POST['txtuser'];
	$txtPass 		= 	md5($_POST['txtpassword']);
	$logintype  	= 	$_POST['logintype'];
	
	
	if($logintype==2)
	{
			$result = mysqlSelect('*','compny_tab',"(email_id='".$txtUserName."' or owner_name='".$txtUserName."') and password='".$txtPass."'");
	
			if($result==true)
			{
				//HERE SYSTEM WILL CHECK WHEATHER ORGANISATION HOLDS PATIENT RECORDS OR NOT
				$getRecord = mysqlSelect('COUNT(a.patient_id) as Count_Records','patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id',"d.company_id='".$result[0]['company_id']."'");
				
				$_SESSION['user_name']  = 	$result[0]['owner_name'];
				$_SESSION['user_id'] 	= 	$result[0]['company_id'];
				$_SESSION['company_name'] = $result[0]['company_name'];
				
				$Total_Rslt_Count = mysqlSelect("COUNT(DISTINCT(a.patient_id)) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","d.company_id='".$_SESSION['user_id']."'","","","","");
				
				$countPending = mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.status2=2 or b.status2=3) and (d.company_id='".$_SESSION['user_id']."')","","","","");
				
				$countResponded = mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (d.company_id='".$_SESSION['user_id']."')","","","","");
				
				$countAutoResponse = mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","b.response_status=1 and d.company_id='".$_SESSION['user_id']."'","","","","");
				
				$countConverted = mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.status2=8 or b.status2=7 or b.status2=9 or b.status2=11 or b.status2=12 or b.status2=13) and (d.company_id='".$_SESSION['user_id']."')","","","","");
				
				$countNotResponded = mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","b.response_status=0 and b.status2!=2 and d.company_id='".$_SESSION['user_id']."'","","","","");
				
				$Totresponsetime = mysqlSelect("SUM(b.response_time) as Tot_response_time","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","b.response_time!=0 and d.company_id='".$_SESSION['user_id']."'","","","","");
				
				$Countresponsetime = mysqlSelect("COUNT(a.patient_id) as Count_Response_Time","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","b.response_time!=0 and d.company_id='".$_SESSION['user_id']."'","","","","");

				$actual_responded=$countResponded[0]['Total_count'];
				$conversion_rate=floor(($countConverted[0]['Total_count']*100)/$actual_responded);
				$response_rate=floor(($actual_responded/($Total_Rslt_Count[0]['Total_count']-$countPending[0]['Total_count']))*100);
				$response_time=floor($Totresponsetime[0]['Tot_response_time']/$Countresponsetime[0]['Count_Response_Time']);
				
				$_SESSION['pending_count']		=	$countPending[0]['Total_count'];
				$_SESSION['responded_count']	=	$countResponded[0]['Total_count'];
				$_SESSION['not_responded_count']=	$countNotResponded[0]['Total_count'];
				$_SESSION['converted_count']	=	$countConverted[0]['Total_count'];
				$_SESSION['tot_result_count']	=	$Total_Rslt_Count[0]['Total_count'];
				$_SESSION['autoresponse_count']	=	$countAutoResponse[0]['Total_count'];
				$_SESSION['conversion_rate']	=	$conversion_rate;
				$_SESSION['response_rate']		=	$response_rate;
				$_SESSION['response_time']		=	$response_time;
				
				
				//Total referred
				$Total_Referred_Mar = mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2017-03-01 00:00:00' and '".$curDate."')","","","","");
				
				$Total_Referred_Feb = mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2017-02-01 00:00:00' and '2017-02-29 00:00:00')","","","","");
				
				$Total_Referred_Jan = mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2017-01-01 00:00:00' and '2017-01-31 00:00:00')","","","","");
				
				$Total_Referred_Dec = mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2016-12-01 00:00:00' and '2016-12-31 00:00:00')","","","","");
				
				$Total_Referred_Nov = mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2016-11-01 00:00:00' and '2016-11-31 00:00:00')","","","","");
				
				$Total_Referred_Oct = mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2016-10-01 00:00:00' and '2016-10-31 00:00:00')","","","","");

				//Total responded
				$Total_Responded_Mar = mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2017-03-01 00:00:00' and '".$curDate."')","","","","");
				
				$Total_Responded_Feb = mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2017-02-01 00:00:00' and '2017-02-29 00:00:00')","","","","");
				
				$Total_Responded_Jan = mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2017-01-01 00:00:00' and '2017-01-31 00:00:00')","","","","");
				
				$Total_Responded_Dec = mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2016-12-01 00:00:00' and '2016-12-31 00:00:00')","","","","");
				
				$Total_Responded_Nov = mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2016-11-01 00:00:00' and '2016-11-31 00:00:00')","","","","");
				
				$Total_Responded_Oct = mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2016-10-01 00:00:00' and '2016-10-31 00:00:00')","","","","");

				$countConverted_Mar= mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.status2=8 or b.status2=7 or b.status2=9 or b.status2=11 or b.status2=12 or b.status2=13) and (d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2017-03-01 00:00:00' and '".$curDate."')","","","","");
				
				$countConverted_Feb= mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.status2=8 or b.status2=7 or b.status2=9 or b.status2=11 or b.status2=12 or b.status2=13) and (d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2017-02-01 00:00:00' and '2017-02-29 00:00:00')","","","","");
				
				$countConverted_Jan= mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.status2=8 or b.status2=7 or b.status2=9 or b.status2=11 or b.status2=12 or b.status2=13) and (d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2017-01-01 00:00:00' and '2017-01-31 00:00:00')","","","","");
				
				$countConverted_Dec= mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.status2=8 or b.status2=7 or b.status2=9 or b.status2=11 or b.status2=12 or b.status2=13) and (d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2016-12-01 00:00:00' and '2016-12-31 00:00:00')","","","","");
				
				$countConverted_Nov= mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.status2=8 or b.status2=7 or b.status2=9 or b.status2=11 or b.status2=12 or b.status2=13) and (d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2016-11-01 00:00:00' and '2016-11-31 00:00:00')","","","","");
				
				$countConverted_Oct= mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.status2=8 or b.status2=7 or b.status2=9 or b.status2=11 or b.status2=12 or b.status2=13) and (d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2016-10-01 00:00:00' and '2016-10-31 00:00:00')","","","","");

				//Avg Response Time
				$Totresponsetime_Mar= mysqlSelect("SUM(b.response_time) as Tot_response_time","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (b.response_time!=0) and (d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2017-03-01 00:00:00' and '".$curDate."')","","","","");
				
				$Countresponsetime_Mar= mysqlSelect("COUNT(a.patient_id) as Count_Response_Time","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (b.response_time!=0) and (d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2017-03-01 00:00:00' and '".$curDate."')","","","","");
				$_SESSION['response_time_Mar']=floor($Totresponsetime_Mar[0]['Tot_response_time']/$Countresponsetime_Mar[0]['Count_Response_Time']);
				
				
				$Totresponsetime_Feb= mysqlSelect("SUM(b.response_time) as Tot_response_time","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (b.response_time!=0) and (d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2017-02-01 00:00:00' and '2017-02-29 00:00:00')","","","","");
				
				$Countresponsetime_Feb= mysqlSelect("COUNT(a.patient_id) as Count_Response_Time","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (b.response_time!=0) and (d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2017-02-01 00:00:00' and '2017-02-29 00:00:00')","","","","");
				$_SESSION['response_time_Feb']=floor($Totresponsetime_Feb[0]['Tot_response_time']/$Countresponsetime_Feb[0]['Count_Response_Time']);

				$Totresponsetime_Jan= mysqlSelect("SUM(b.response_time) as Tot_response_time","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (b.response_time!=0) and (d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2017-01-01 00:00:00' and '2017-01-31 00:00:00')","","","","");
				
				$Countresponsetime_Jan= mysqlSelect("COUNT(a.patient_id) as Count_Response_Time","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (b.response_time!=0) and (d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2017-01-01 00:00:00' and '2017-01-31 00:00:00')","","","","");
				$_SESSION['response_time_Jan']=floor($Totresponsetime_Jan[0]['Tot_response_time']/$Countresponsetime_Jan[0]['Count_Response_Time']);

				$Totresponsetime_Dec= mysqlSelect("SUM(b.response_time) as Tot_response_time","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (b.response_time!=0) and (d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2016-12-01 00:00:00' and '2016-12-31 00:00:00')","","","","");
				
				$Countresponsetime_Dec= mysqlSelect("COUNT(a.patient_id) as Count_Response_Time","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (b.response_time!=0) and (d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2016-12-01 00:00:00' and '2016-12-31 00:00:00')","","","","");
				$_SESSION['response_time_Dec']=floor($Totresponsetime_Dec[0]['Tot_response_time']/$Countresponsetime_Dec[0]['Count_Response_Time']);

				$Totresponsetime_Nov= mysqlSelect("SUM(b.response_time) as Tot_response_time","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (b.response_time!=0) and (d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2016-11-01 00:00:00' and '2016-11-31 00:00:00')","","","","");
				
				$Countresponsetime_Nov= mysqlSelect("COUNT(a.patient_id) as Count_Response_Time","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (b.response_time!=0) and (d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2016-11-01 00:00:00' and '2016-11-31 00:00:00')","","","","");
				$_SESSION['response_time_Nov']=floor($Totresponsetime_Nov[0]['Tot_response_time']/$Countresponsetime_Nov[0]['Count_Response_Time']);

				$Totresponsetime_Oct= mysqlSelect("SUM(b.response_time) as Tot_response_time","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (b.response_time!=0) and (d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2016-10-01 00:00:00' and '2016-10-31 00:00:00')","","","","");
				
				$Countresponsetime_Oct= mysqlSelect("COUNT(a.patient_id) as Count_Response_Time","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (b.response_time!=0) and (d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2016-10-01 00:00:00' and '2016-10-31 00:00:00')","","","","");
				$_SESSION['response_time_Oct']=floor($Totresponsetime_Oct[0]['Tot_response_time']/$Countresponsetime_Oct[0]['Count_Response_Time']);
						
				$_SESSION['Total_Referred_Mar']=$Total_Referred_Mar[0]['Total_count'];	
				$_SESSION['Total_Referred_Feb']=$Total_Referred_Feb[0]['Total_count'];
				$_SESSION['Total_Referred_Jan']=$Total_Referred_Jan[0]['Total_count'];
				$_SESSION['Total_Referred_Dec']=$Total_Referred_Dec[0]['Total_count'];
				$_SESSION['Total_Referred_Nov']=$Total_Referred_Nov[0]['Total_count'];
				$_SESSION['Total_Referred_Oct']=$Total_Referred_Oct[0]['Total_count'];	

				$_SESSION['Total_Responded_Mar']=$Total_Responded_Mar[0]['Total_count'];
				$_SESSION['Total_Responded_Feb']=$Total_Responded_Feb[0]['Total_count'];
				$_SESSION['Total_Responded_Jan']=$Total_Responded_Jan[0]['Total_count'];
				$_SESSION['Total_Responded_Dec']=$Total_Responded_Dec[0]['Total_count'];
				$_SESSION['Total_Responded_Nov']=$Total_Responded_Nov[0]['Total_count'];
				$_SESSION['Total_Responded_Oct']=$Total_Responded_Oct[0]['Total_count'];
				
				//Response rate
				$_SESSION['response_rate_Mar']=floor(($Total_Responded_Mar[0]['Total_count']/$Total_Referred_Mar[0]['Total_count'])*100);
				$_SESSION['response_rate_Feb']=floor(($Total_Responded_Feb[0]['Total_count']/$Total_Referred_Feb[0]['Total_count'])*100);
				$_SESSION['response_rate_Jan']=floor(($Total_Responded_Jan[0]['Total_count']/$Total_Referred_Jan[0]['Total_count'])*100);
				$_SESSION['response_rate_Dec']=floor(($Total_Responded_Dec[0]['Total_count']/$Total_Referred_Dec[0]['Total_count'])*100);
				$_SESSION['response_rate_Nov']=floor(($Total_Responded_Nov[0]['Total_count']/$Total_Referred_Nov[0]['Total_count'])*100);
				$_SESSION['response_rate_Oct']=floor(($Total_Responded_Oct[0]['Total_count']/$Total_Referred_Oct[0]['Total_count'])*100);

				//Conversion Rete
				$_SESSION['conversion_rate_Mar']=floor(($countConverted_Mar[0]['Total_count']*100)/$Total_Responded_Mar[0]['Total_count']);
				$_SESSION['conversion_rate_Feb']=floor(($countConverted_Feb[0]['Total_count']*100)/$Total_Responded_Feb[0]['Total_count']);
				$_SESSION['conversion_rate_Jan']=floor(($countConverted_Jan[0]['Total_count']*100)/$Total_Responded_Jan[0]['Total_count']);
				$_SESSION['conversion_rate_Dec']=floor(($countConverted_Dec[0]['Total_count']*100)/$Total_Responded_Dec[0]['Total_count']);
				$_SESSION['conversion_rate_Nov']=floor(($countConverted_Nov[0]['Total_count']*100)/$Total_Responded_Nov[0]['Total_count']);
				$_SESSION['conversion_rate_Oct']=floor(($countConverted_Oct[0]['Total_count']*100)/$Total_Responded_Oct[0]['Total_count']);
				
				header('location:../institution/Home');
				
				
			}
			else
			{
				$response=0;
				//$errorMessage="Login failed. Username or password are invalid.";
				header('location:login?response='.$response);
			}
	
	
	}
	else if($logintype==1)
	{
		$result = mysqlSelect('ref_id,ref_name,password_recovery','referal',"(ref_mail = '".$txtUserName."' or contact_num='".$txtUserName."') and doc_password='".$txtPass."'");
	
		
			if($result==true)
			{
				
				$chkLoginTrack = mysqlSelect('*','practice_login_tracker',"doc_id ='".$result[0]['ref_id']."' and type='1'");
				$arrFileds_loginTrack[] = 'doc_id';
				$arrValues_loginTrack[] = $result[0]['ref_id'];
				$arrFileds_loginTrack[] = 'type';
				$arrValues_loginTrack[] = "1";
				$arrFileds_loginTrack[] = 'system_ip';
				$arrValues_loginTrack[] = $systemIP;
				$arrFileds_loginTrack[] = 'timestamp';
				$arrValues_loginTrack[] = $Cur_Date;
				if(count($chkLoginTrack)>0)
				{
					
					$update_loginTracker=mysqlUpdate('practice_login_tracker',$arrFileds_loginTrack,$arrValues_loginTrack,"doc_id = '".$result[0]['ref_id']."' and type='1'");

				}
				
				else 
				{

					$insert_loginTracker	=	mysqlInsert('practice_login_tracker',$arrFileds_loginTrack,$arrValues_loginTrack);

				}
				//echo" val : ".$result[0]['ref_id']."<br>";
				$respondedRecord = mysqlSelect("COUNT(DISTINCT(a.patient_id)) as Patient_Count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","b.response_status=2 and b.ref_id='".$result[0]['ref_id']."'");
				
				$autorespondedRecord = mysqlSelect("COUNT(DISTINCT(a.patient_id)) as Patient_Count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","b.ref_id='".$result[0]['ref_id']."' and b.response_status=1");
				
				$notrespondedRecord = mysqlSelect("COUNT(DISTINCT(a.patient_id)) as Patient_Count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","b.ref_id='".$result[0]['ref_id']."' and b.response_status=0 and b.status2!=2");
				
				$allRecord = mysqlSelect("COUNT(DISTINCT(a.patient_id)) as Patient_Count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","b.ref_id='".$result[0]['ref_id']."'");
				
				$convertedRecord = mysqlSelect("COUNT(DISTINCT(a.patient_id)) as Patient_Count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.status2=8 or b.status2=7 or b.status2=9 or b.status2=11 or b.status2=12 or b.status2=13) and (b.ref_id='".$result[0]['ref_id']."')");

				$pendingRecord = mysqlSelect("COUNT(DISTINCT(a.patient_id)) as Patient_Count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.ref_id='".$result[0]['ref_id']."') and (b.status2=2 or b.status2=3)");
				
				$Totresponsetime= mysqlSelect("SUM(b.response_time) as Tot_response_time","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","b.response_time!=0 and b.ref_id='".$result[0]['ref_id']."'","","","","");
				
				$Countresponsetime= mysqlSelect("COUNT(a.patient_id) as Count_Response_Time","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","b.response_time!=0 and b.ref_id='".$result[0]['ref_id']."'","","","","");

				$actual_responded=$respondedRecord[0]['Patient_Count'];
				
				
				$conversion_rate=floor(($convertedRecord[0]['Patient_Count']*100)/$actual_responded);
				
				$response_rate=floor(($actual_responded/($allRecord[0]['Patient_Count']-$pendingRecord[0]['Patient_Count']))*100);
				
				$response_time=floor($Totresponsetime[0]['Tot_response_time']/$Countresponsetime[0]['Count_Response_Time']);
				
				$chkHospLogin = mysqlSelect("a.hosp_id as hosp_id,b.hosp_name as hosp_name", "doctor_hosp as a left join hosp_tab as b on a.hosp_id=b.hosp_id", "a.doc_id='".$result[0]['ref_id']."'", "b.hosp_name asc", "", "", "");
				
				//IF ITS TRUE THEN REDIRECTS TO "ALL RECORDS" PAGE
				$_SESSION['user_name'] 			= 	$result[0]['ref_name'];
				$_SESSION['user_id']			= 	$result[0]['ref_id'];
				$_SESSION['hosp_id'] 			= 	$chkHospLogin[0]['hosp_id'];
				$_SESSION['company_name'] 		= 	$result[0]['ref_name'];
				$_SESSION['all_record'] 		= 	$allRecord[0]['Patient_Count'];
				$_SESSION['tot_ref_count'] 		= 	$totReferredCount[0]['Count_Records'];
				$_SESSION['tot_resp_count'] 	= 	$respondedRecord[0]['Patient_Count'];
				$_SESSION['not_resp_count'] 	= 	$notrespondedRecord[0]['Patient_Count'];
				$_SESSION['auto_resp_count'] 	= 	$autorespondedRecord[0]['Patient_Count'];
				$_SESSION['tot_treated_count'] 	= 	$totTreatedCount[0]['Count_Records'];
				$_SESSION['tot_converted_count'] = 	$convertedRecord[0]['Patient_Count'];
				$_SESSION['tot_Blogs'] 			= 	$totalBlogs[0]['Count_Blogs'];
				$_SESSION['conversion_rate']	=	$conversion_rate;
				$_SESSION['response_rate']		=	$response_rate;
				$_SESSION['response_time']		=	$response_time;
				$_SESSION['login_hosp_id']		=	$chkHospLogin[0]['hosp_id'];
				$_SESSION['login_hosp_name']	=	$chkHospLogin[0]['hosp_name'];
				
				
				
		
				$getLastMypatient= mysqlSelect("a.patient_id","patients_appointment as a inner join patients_transactions as b on a.patient_id=b.patient_id","b.doc_id='".$result[0]['ref_id']."'","a.patient_id desc","","","");
				
				$checkTodayVisit= mysqlSelect("episode_id","doc_patient_episodes","admin_id='".$result[0]['ref_id']."' and patient_id='".$getLastMypatient[0]['patient_id']."' and DATE_FORMAT(date_time,'%Y-%m-%d')='".date('Y-m-d')."'","episode_id desc","","","");
				
				if(COUNT($getLastMypatient)>0 && COUNT($checkTodayVisit)>0)
				{
					
					$patientid=md5($getLastMypatient[0]['patient_id'])."&episode=".md5($checkTodayVisit[0]['episode_id'])."&w=1";
				}
				else if(COUNT($getLastMypatient)>0)
				{
					
					$patientid=md5($getLastMypatient[0]['patient_id'])."&w=1";
				}
				else
				{
					$patientid="0";
				}
				//To Check EMR Page Navigation based on doctors specialisation
				$getDocEMR = mysqlSelect("spec_group_id","specialization as a left join doc_specialization as b on a.spec_id=b.spec_id","b.doc_id='".$result[0]['ref_id']."'","","","","");
				
				if(COUNT($getDocEMR)>0) 
				{
					if($result[0]['password_recovery']=='1')
					{
						header('location:Password?response=password-recommendation');
					}
					else if($getDocEMR[0]['spec_group_id']==1)
					{  //If 'spec_group_id' is 1, Then it will navigate to Cardio Diabetic EMR
						header('location:My-Patient-Details?p='.$patientid);
					}
					else if($getDocEMR[0]['spec_group_id']==2)
					{ //If 'spec_group_id' is 2, Then it will navigate to Ophthal EMR
						header('location:Ophthal-EMR/?p='.$patientid);
					}
				}
				else
				{
					header('location:Profile?response=spec_req');
				}
				
				
			}
			else
			{
				$response=0;
				$errorMessage="Login failed. Username or password are invalid.";
				header('location:login?response='.$response);
			}
			
	
	}
	else if($logintype==4)
	{
		$result = mysqlSelect('hosp_id,hosp_name','hosp_tab',"hosp_user_name = '".$txtUserName."' and hosp_passwd='".$txtPass."'");
	
			if($result==true)
			{
				
				//HERE SYSTEM WILL CHECK WHEATHER ORGANISATION HOLDS PATIENT RECORDS OR NOT
				$getRecord = mysqlSelect('COUNT(a.patient_id) as Count_Records','patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id',"d.hosp_id='".$result[0]['hosp_id']."'");
				
				$_SESSION['user_name'] 		= $result[0]['hosp_name'];
				$_SESSION['user_id'] 		= $result[0]['hosp_id'];
				$_SESSION['company_name']   = $result[0]['hosp_name'];
				
				$Total_Rslt_Count = mysqlSelect("COUNT(DISTINCT(a.patient_id)) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","d.hosp_id='".$_SESSION['user_id']."'","","","","");
				
				$countPending = mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.status2=2 or b.status2=3) and (d.hosp_id='".$_SESSION['user_id']."')","","","","");
				
				$countResponded = mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (d.hosp_id='".$_SESSION['user_id']."')","","","","");
				
				$countAutoResponse = mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","b.response_status=1 and d.hosp_id='".$_SESSION['user_id']."'","","","","");
				
				$countConverted= mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.status2=8 or b.status2=7 or b.status2=9 or b.status2=11 or b.status2=12 or b.status2=13) and (d.hosp_id='".$_SESSION['user_id']."')","","","","");
				
				$countNotResponded = mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","b.response_status=0 and b.status2!=2 and d.hosp_id='".$_SESSION['user_id']."'","","","","");
				
				$Totresponsetime= mysqlSelect("SUM(b.response_time) as Tot_response_time","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","b.response_time!=0 and d.hosp_id='".$_SESSION['user_id']."'","","","","");
				
				$Countresponsetime= mysqlSelect("COUNT(a.patient_id) as Count_Response_Time","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","b.response_time!=0 and d.hosp_id='".$_SESSION['user_id']."'","","","","");

				$actual_responded=$countResponded[0]['Total_count'];
				
				
				$conversion_rate=floor(($countConverted[0]['Total_count']*100)/$actual_responded);
				
				$response_rate=floor(($actual_responded/($Total_Rslt_Count[0]['Total_count']-$countPending[0]['Total_count']))*100);
				
				$response_time=floor($Totresponsetime[0]['Tot_response_time']/$Countresponsetime[0]['Count_Response_Time']);
				
				$_SESSION['pending_count']		=	$countPending[0]['Total_count'];
				$_SESSION['responded_count']	=	$countResponded[0]['Total_count'];
				$_SESSION['not_responded_count']=$countNotResponded[0]['Total_count'];
				$_SESSION['converted_count']=$countConverted[0]['Total_count'];
				$_SESSION['tot_result_count']=$Total_Rslt_Count[0]['Total_count'];
				$_SESSION['autoresponse_count']=$countAutoResponse[0]['Total_count'];
				$_SESSION['conversion_rate']=$conversion_rate;
				$_SESSION['response_rate']=$response_rate;
				$_SESSION['response_time']=$response_time;
				
				
				//Total referred
				$Total_Referred_Mar = mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2017-03-01 00:00:00' and '".$curDate."')","","","","");
				
				$Total_Referred_Feb = mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2017-02-01 00:00:00' and '2017-02-29 00:00:00')","","","","");
				
				$Total_Referred_Jan = mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2017-01-01 00:00:00' and '2017-01-31 00:00:00')","","","","");
				$Total_Referred_Dec = mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2016-12-01 00:00:00' and '2016-12-31 00:00:00')","","","","");
				$Total_Referred_Nov = mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2016-11-01 00:00:00' and '2016-11-31 00:00:00')","","","","");
				$Total_Referred_Oct = mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2016-10-01 00:00:00' and '2016-10-31 00:00:00')","","","","");

				//Total responded
				$Total_Responded_Mar = mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2017-03-01 00:00:00' and '".$curDate."')","","","","");
				$Total_Responded_Feb = mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2017-02-01 00:00:00' and '2017-02-29 00:00:00')","","","","");
				$Total_Responded_Jan = mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2017-01-01 00:00:00' and '2017-01-31 00:00:00')","","","","");
				$Total_Responded_Dec = mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2016-12-01 00:00:00' and '2016-12-31 00:00:00')","","","","");
				$Total_Responded_Nov = mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2016-11-01 00:00:00' and '2016-11-31 00:00:00')","","","","");
				$Total_Responded_Oct = mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2016-10-01 00:00:00' and '2016-10-31 00:00:00')","","","","");

				$countConverted_Mar= mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.status2=8 or b.status2=7 or b.status2=9 or b.status2=11 or b.status2=12 or b.status2=13) and (d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2017-03-01 00:00:00' and '".$curDate."')","","","","");
				$countConverted_Feb= mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.status2=8 or b.status2=7 or b.status2=9 or b.status2=11 or b.status2=12 or b.status2=13) and (d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2017-02-01 00:00:00' and '2017-02-29 00:00:00')","","","","");
				$countConverted_Jan= mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.status2=8 or b.status2=7 or b.status2=9 or b.status2=11 or b.status2=12 or b.status2=13) and (d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2017-01-01 00:00:00' and '2017-01-31 00:00:00')","","","","");
				$countConverted_Dec= mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.status2=8 or b.status2=7 or b.status2=9 or b.status2=11 or b.status2=12 or b.status2=13) and (d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2016-12-01 00:00:00' and '2016-12-31 00:00:00')","","","","");
				$countConverted_Nov= mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.status2=8 or b.status2=7 or b.status2=9 or b.status2=11 or b.status2=12 or b.status2=13) and (d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2016-11-01 00:00:00' and '2016-11-31 00:00:00')","","","","");
				$countConverted_Oct= mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.status2=8 or b.status2=7 or b.status2=9 or b.status2=11 or b.status2=12 or b.status2=13) and (d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2016-10-01 00:00:00' and '2016-10-31 00:00:00')","","","","");

				//Avg Response Time
				$Totresponsetime_Mar= mysqlSelect("SUM(b.response_time) as Tot_response_time","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (b.response_time!=0) and (d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2017-03-01 00:00:00' and '".$curDate."')","","","","");
				$Countresponsetime_Mar= mysqlSelect("COUNT(a.patient_id) as Count_Response_Time","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (b.response_time!=0) and (d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2017-03-01 00:00:00' and '".$curDate."')","","","","");
				$_SESSION['response_time_Mar']=floor($Totresponsetime_Mar[0]['Tot_response_time']/$Countresponsetime_Mar[0]['Count_Response_Time']);
				
				
				$Totresponsetime_Feb= mysqlSelect("SUM(b.response_time) as Tot_response_time","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (b.response_time!=0) and (d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2017-02-01 00:00:00' and '2017-02-29 00:00:00')","","","","");
				$Countresponsetime_Feb= mysqlSelect("COUNT(a.patient_id) as Count_Response_Time","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (b.response_time!=0) and (d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2017-02-01 00:00:00' and '2017-02-29 00:00:00')","","","","");
				$_SESSION['response_time_Feb']=floor($Totresponsetime_Feb[0]['Tot_response_time']/$Countresponsetime_Feb[0]['Count_Response_Time']);

				$Totresponsetime_Jan= mysqlSelect("SUM(b.response_time) as Tot_response_time","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (b.response_time!=0) and (d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2017-01-01 00:00:00' and '2017-01-31 00:00:00')","","","","");
				$Countresponsetime_Jan= mysqlSelect("COUNT(a.patient_id) as Count_Response_Time","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (b.response_time!=0) and (d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2017-01-01 00:00:00' and '2017-01-31 00:00:00')","","","","");
				$_SESSION['response_time_Jan']=floor($Totresponsetime_Jan[0]['Tot_response_time']/$Countresponsetime_Jan[0]['Count_Response_Time']);

				$Totresponsetime_Dec= mysqlSelect("SUM(b.response_time) as Tot_response_time","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (b.response_time!=0) and (d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2016-12-01 00:00:00' and '2016-12-31 00:00:00')","","","","");
				$Countresponsetime_Dec= mysqlSelect("COUNT(a.patient_id) as Count_Response_Time","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (b.response_time!=0) and (d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2016-12-01 00:00:00' and '2016-12-31 00:00:00')","","","","");
				$_SESSION['response_time_Dec']=floor($Totresponsetime_Dec[0]['Tot_response_time']/$Countresponsetime_Dec[0]['Count_Response_Time']);

				$Totresponsetime_Nov= mysqlSelect("SUM(b.response_time) as Tot_response_time","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (b.response_time!=0) and (d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2016-11-01 00:00:00' and '2016-11-31 00:00:00')","","","","");
				$Countresponsetime_Nov= mysqlSelect("COUNT(a.patient_id) as Count_Response_Time","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (b.response_time!=0) and (d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2016-11-01 00:00:00' and '2016-11-31 00:00:00')","","","","");
				$_SESSION['response_time_Nov']=floor($Totresponsetime_Nov[0]['Tot_response_time']/$Countresponsetime_Nov[0]['Count_Response_Time']);

				$Totresponsetime_Oct= mysqlSelect("SUM(b.response_time) as Tot_response_time","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (b.response_time!=0) and (d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2016-10-01 00:00:00' and '2016-10-31 00:00:00')","","","","");
				$Countresponsetime_Oct= mysqlSelect("COUNT(a.patient_id) as Count_Response_Time","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (b.response_time!=0) and (d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '2016-10-01 00:00:00' and '2016-10-31 00:00:00')","","","","");
				$_SESSION['response_time_Oct']=floor($Totresponsetime_Oct[0]['Tot_response_time']/$Countresponsetime_Oct[0]['Count_Response_Time']);
						
				$_SESSION['Total_Referred_Mar']=$Total_Referred_Mar[0]['Total_count'];	
				$_SESSION['Total_Referred_Feb']=$Total_Referred_Feb[0]['Total_count'];
				$_SESSION['Total_Referred_Jan']=$Total_Referred_Jan[0]['Total_count'];
				$_SESSION['Total_Referred_Dec']=$Total_Referred_Dec[0]['Total_count'];
				$_SESSION['Total_Referred_Nov']=$Total_Referred_Nov[0]['Total_count'];
				$_SESSION['Total_Referred_Oct']=$Total_Referred_Oct[0]['Total_count'];	

				$_SESSION['Total_Responded_Mar']=$Total_Responded_Mar[0]['Total_count'];
				$_SESSION['Total_Responded_Feb']=$Total_Responded_Feb[0]['Total_count'];
				$_SESSION['Total_Responded_Jan']=$Total_Responded_Jan[0]['Total_count'];
				$_SESSION['Total_Responded_Dec']=$Total_Responded_Dec[0]['Total_count'];
				$_SESSION['Total_Responded_Nov']=$Total_Responded_Nov[0]['Total_count'];
				$_SESSION['Total_Responded_Oct']=$Total_Responded_Oct[0]['Total_count'];
				
				//Response rate
				$_SESSION['response_rate_Mar']=floor(($Total_Responded_Mar[0]['Total_count']/$Total_Referred_Mar[0]['Total_count'])*100);
				$_SESSION['response_rate_Feb']=floor(($Total_Responded_Feb[0]['Total_count']/$Total_Referred_Feb[0]['Total_count'])*100);
				$_SESSION['response_rate_Jan']=floor(($Total_Responded_Jan[0]['Total_count']/$Total_Referred_Jan[0]['Total_count'])*100);
				$_SESSION['response_rate_Dec']=floor(($Total_Responded_Dec[0]['Total_count']/$Total_Referred_Dec[0]['Total_count'])*100);
				$_SESSION['response_rate_Nov']=floor(($Total_Responded_Nov[0]['Total_count']/$Total_Referred_Nov[0]['Total_count'])*100);
				$_SESSION['response_rate_Oct']=floor(($Total_Responded_Oct[0]['Total_count']/$Total_Referred_Oct[0]['Total_count'])*100);

				//Conversion Rete
				$_SESSION['conversion_rate_Mar']=floor(($countConverted_Mar[0]['Total_count']*100)/$Total_Responded_Mar[0]['Total_count']);
				$_SESSION['conversion_rate_Feb']=floor(($countConverted_Feb[0]['Total_count']*100)/$Total_Responded_Feb[0]['Total_count']);
				$_SESSION['conversion_rate_Jan']=floor(($countConverted_Jan[0]['Total_count']*100)/$Total_Responded_Jan[0]['Total_count']);
				$_SESSION['conversion_rate_Dec']=floor(($countConverted_Dec[0]['Total_count']*100)/$Total_Responded_Dec[0]['Total_count']);
				$_SESSION['conversion_rate_Nov']=floor(($countConverted_Nov[0]['Total_count']*100)/$Total_Responded_Nov[0]['Total_count']);
				$_SESSION['conversion_rate_Oct']=floor(($countConverted_Oct[0]['Total_count']*100)/$Total_Responded_Oct[0]['Total_count']);
				
				header('location:../Hospital/Home');
			}
			else
			{
				$response=0;
				$errorMessage="Login failed. Username or password are invalid.";
				header('location:login?response='.$response);
			}
	
	}
	else if($logintype==3)
	{
		$chkReception = mysqlSelect('*','receptionist_login',"receptionist_mobile='".$txtUserName."' and reception_password='".$txtPass."'");
		$result = mysqlSelect('*','referal as a left join doctor_hosp as b on a.ref_id=b.doc_id',"a.ref_id='".$chkReception[0]['doc_id']."'");
	
			if($chkReception==true)
			{
				$chkLoginTrack = mysqlSelect('*','practice_login_tracker',"doc_id ='".$result[0]['ref_id']."' and type='1'");
						$arrFileds_loginTrack[] = 'doc_id';
						$arrValues_loginTrack[] = $result[0]['ref_id'];
						$arrFileds_loginTrack[] = 'type';
						$arrValues_loginTrack[] = "1";
						$arrFileds_loginTrack[] = 'system_ip';
						$arrValues_loginTrack[] = $systemIP;
						$arrFileds_loginTrack[] = 'timestamp';
						$arrValues_loginTrack[] = $Cur_Date;
				if(count($chkLoginTrack)>0)
				{
						
						$update_loginTracker=mysqlUpdate('practice_login_tracker',$arrFileds_loginTrack,$arrValues_loginTrack,"doc_id = '".$result[0]['ref_id']."' and type='1'");

				}
				
				else 
				{
					$insert_loginTracker=mysqlInsert('practice_login_tracker',$arrFileds_loginTrack,$arrValues_loginTrack);

				}
				$respondedRecord = mysqlSelect("COUNT(DISTINCT(a.patient_id)) as Patient_Count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","b.response_status=2 and b.ref_id='".$result[0]['ref_id']."'");
				
				$autorespondedRecord = mysqlSelect("COUNT(DISTINCT(a.patient_id)) as Patient_Count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","b.ref_id='".$result[0]['ref_id']."' and b.response_status=1");
				
				$notrespondedRecord = mysqlSelect("COUNT(DISTINCT(a.patient_id)) as Patient_Count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","b.ref_id='".$result[0]['ref_id']."' and b.response_status=0 and b.status2!=2");
				
				$allRecord = mysqlSelect("COUNT(DISTINCT(a.patient_id)) as Patient_Count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","b.ref_id='".$result[0]['ref_id']."'");
				
				$convertedRecord = mysqlSelect("COUNT(DISTINCT(a.patient_id)) as Patient_Count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.status2=8 or b.status2=7 or b.status2=9 or b.status2=11 or b.status2=12 or b.status2=13) and (b.ref_id='".$result[0]['ref_id']."')");

				$pendingRecord = mysqlSelect("COUNT(DISTINCT(a.patient_id)) as Patient_Count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.ref_id='".$result[0]['ref_id']."') and (b.status2=2 or b.status2=3)");
				
				$Totresponsetime= mysqlSelect("SUM(b.response_time) as Tot_response_time","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","b.response_time!=0 and b.ref_id='".$result[0]['ref_id']."'","","","","");
				
				$Countresponsetime= mysqlSelect("COUNT(a.patient_id) as Count_Response_Time","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","b.response_time!=0 and b.ref_id='".$result[0]['ref_id']."'","","","","");

				$actual_responded=$respondedRecord[0]['Patient_Count'];
				
				
				$conversion_rate=floor(($convertedRecord[0]['Patient_Count']*100)/$actual_responded);
				
				$response_rate=floor(($actual_responded/($allRecord[0]['Patient_Count']-$pendingRecord[0]['Patient_Count']))*100);
				
				$response_time=floor($Totresponsetime[0]['Tot_response_time']/$Countresponsetime[0]['Count_Response_Time']);
				
				$chkHospLogin = mysqlSelect("a.hosp_id as hosp_id,b.hosp_name as hosp_name", "doctor_hosp as a left join hosp_tab as b on a.hosp_id=b.hosp_id", "a.doc_id='".$result[0]['ref_id']."'", "b.hosp_name asc", "", "", "");
				
				
				//IF ITS TRUE THEN REDIRECTS TO "ALL RECORDS" PAGE
				$_SESSION['user_name'] 			= 	$chkReception[0]['reception_user'];
				$_SESSION['secretary_id'] 		= 	"1";
				$_SESSION['secretary_userid'] 	= 	$chkReception[0]['reception_id'];
				$_SESSION['user_id']			= 	$result[0]['ref_id'];
				$_SESSION['hosp_id'] 			= 	$result[0]['hosp_id'];
				$_SESSION['company_name'] 		= 	$result[0]['ref_name'];
				$_SESSION['all_record']			=	$allRecord[0]['Patient_Count'];
				$_SESSION['tot_ref_count'] 		= 	$totReferredCount[0]['Count_Records'];
				$_SESSION['tot_resp_count'] 	= 	$respondedRecord[0]['Patient_Count'];
				$_SESSION['not_resp_count'] 	= 	$notrespondedRecord[0]['Patient_Count'];
				$_SESSION['auto_resp_count'] 	= 	$autorespondedRecord[0]['Patient_Count'];
				$_SESSION['tot_treated_count'] 	= 	$totTreatedCount[0]['Count_Records'];
				$_SESSION['tot_converted_count']= 	$convertedRecord[0]['Patient_Count'];
				$_SESSION['tot_Blogs'] 			= 	$totalBlogs[0]['Count_Blogs'];
				$_SESSION['conversion_rate']	=	$conversion_rate;
				$_SESSION['response_rate']		=	$response_rate;
				$_SESSION['response_time']		=	$response_time;
				$_SESSION['login_hosp_id']		=	$chkHospLogin[0]['hosp_id'];
				$_SESSION['login_hosp_name']	=	$chkHospLogin[0]['hosp_name'];
				header('location:Appointments');
				
				
			}
			else
			{
				$response=0;
				$errorMessage="Login failed. Username or password are invalid.";
				header('location:login?response='.$response);
			}
	
	}

}

//USER REGISTRATION 
if(isset($_POST['register']))
{
	 $txtDocName 	= addslashes($_POST['txtDocName']);
	 $slctCountry 	= addslashes($_POST['slctCountry']);
	 $slctState 	= addslashes($_POST['slctState']);
	 $txtCity 		= addslashes($_POST['txtCity']);
	 $slctSpec 		= addslashes($_POST['slctSpec']);
	 $txtHosp 		= addslashes($_POST['txtHosp']);
	 $txtQual 		= addslashes($_POST['txtQual']);
	 $txtMob 		= addslashes($_POST['txtMob']);
	 $txtEmail 		= addslashes($_POST['txtEmail']);
	  $passwd 		= addslashes($_POST['passwd']);
	 $txtMedCouncil = addslashes($_POST['txtMedCouncil']);
	 $txtMedRegnum 	= addslashes($_POST['txtMedRegnum']);
	
	$mainDescription="Dear Sir <br><br>We got one PIXEL Standard account signup request. Please go through below client details<br><br> <b>Name: </b>".$txtDocName."<br><b>Location: </b>".$txtCity.", ".$slctState.", ".$slctCountry."<br><b>Specialization:</b> ".$slctSpec."<br><b>Hospital Name:</b> ".$txtHosp."<br><b>Qualification:</b> ".$txtQual."<br><b>Mobile No.:</b> ".$txtMob."<br><b>Email Id:</b> ".$txtEmail."<br><b>Medical council name:</b> ".$txtMedCouncil."<br><b>Registration no.:</b> ".$txtMedRegnum."<br><br><b>Many Thanks</b><br>FDC Pixel";
	$tomail="ambarish@medisense.me";
	$orgmail = "ambarishbhat@gmail.com";
	$subject="FDC PIXEL Standard account signup request";	
					$url_page="new_signup_request.php";
					$url = rawurlencode($url_page);
					$url .= "?maindescription=".urlencode($mainDescription);
					$url .= "&subject=".urlencode($subject);
					$url .= "&tomail=".urlencode($tomail);
					$url .= "&orgmail=".urlencode($orgmail);
					send_mail($url);
		$respond='0';
		header('location:login?respond='.$respond);	
	
}


if(isset($_POST['forgot'])) 
{

	$useremail = $_POST['txtemail'];
	$password  = randomPassword();
	$encypassword = md5($password);
	$chkUser = mysqlSelect("*","referal","ref_mail='".$useremail."'","","","","");
		if($chkUser==true)
		{
			$arrFields[] = 'doc_password';
			$arrValues[] = $encypassword;
			$arrFields[] = 'password_recovery';
			$arrValues[] = "1";
		
			$updateUser	=	mysqlUpdate('referal',$arrFields,$arrValues,"ref_id='".$chkUser[0]['ref_id']."'");
			if(!empty($chkUser[0]['ref_mail']) && !empty($chkUser[0]['contact_num']))
			{
				$orSlash = " / ";
			}
			$recoverLink="Link: www.medisensepractice.com<br>User Name: ".$chkUser[0]['ref_mail'].$orSlash.$chkUser[0]['contact_num']."<br>Password: ".$password;
			
			$message= stripslashes("We received a request to reset the password associated with this e-mail address. If you made this request, please follow the instructions below. <br>

				Please use below temporary user name & password using our secure server:<br><br> 

			".$recoverLink."<br><br>
			If you did not request to reset your password, you can safely ignore this email. Rest assured your customer account is safe.");
			
			
						$url_page = 'send_recoverLink.php';
						$url .= rawurlencode($url_page);
						$url .= "?usermail=".urlencode($useremail);
						$url .= "&username=".urlencode($chkUser[0]['contact_person']);
						$url .= "&message=".urlencode($message);
						$url .= "&reclink=".urlencode($recoverLink);
						send_mail($url);
						
						if(!empty($chkUser[0]['contact_num'])){
						$txtMob = $chkUser[0]['contact_num'];
						$msg= "Hello ".$chkUser[0]['ref_name']." We received a request to reset the password associated with this e-mail address. Plz check your registered email address. Thanks";
						send_msg($txtMob,$msg);
						}
			header('Location:login?response=3');
		}
		else
		{
			header('Location:login?response=4');
		}
	
	
}
?>