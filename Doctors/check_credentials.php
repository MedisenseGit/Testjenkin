<?php ob_start();
session_start();
error_reporting(0);  

require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();


//USER LOGIN
if(isset($_POST['signin'])){
	$txtUserName = $_POST['txtuser'];
	 $txtPass = md5($_POST['txtpassword']);
	
	$result = $objQuery->mysqlSelect('*','referal',"(ref_mail='".$txtUserName."' or contact_num='".$txtUserName."') and doc_password='".$txtPass."'");
	
	if($result==true){
		$_SESSION['user_name'] = $result[0]['ref_name'];
		$_SESSION['user_id'] = $result[0]['ref_id'];
		$_SESSION['company_name'] = $result[0]['ref_name'];
		//When he first login "login_status" should make it 1 ie. 1 for "signup success"
		/*$arrFields[] = 'login_status';
		$arrValues[] = "1";
		$updateMapping=$objQuery->mysqlUpdate('our_partners',$arrFields,$arrValues,"partner_id='".$result[0]['partner_id']."'");
		*/
		
		/*$mycircleDoctor = $objQuery->mysqlSelect("COUNT(DISTINCT(a.ref_id)) as Count_Doc","referal as a inner join doctor_hosp as c on c.doc_id=a.ref_id inner join mapping_hosp_referrer as d on d.hosp_id=c.hosp_id","a.doc_spec!=555 and a.anonymous_status!=1 and d.partner_id='".$result[0]['ref_id']."'","","","","");	
		if($mycircleDoctor[0]['Count_Doc']>0){
		$_SESSION['mycircle_doc'] = $mycircleDoctor[0]['Count_Doc'];
		}
		else{
		$pannelDoctor = $objQuery->mysqlSelect("COUNT(DISTINCT(ref_id)) as Count_Doc","referal","doc_spec!=555 and anonymous_status!=1","","","","");	
		$_SESSION['mycircle_doc'] = $pannelDoctor[0]['Count_Doc'];	
		}*/
		$respondedRecord = $objQuery->mysqlSelect("COUNT(DISTINCT(a.patient_id)) as Patient_Count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","b.response_status=2 and b.ref_id='".$result[0]['ref_id']."'");
$autorespondedRecord = $objQuery->mysqlSelect("COUNT(DISTINCT(a.patient_id)) as Patient_Count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","b.ref_id='".$result[0]['ref_id']."' and b.response_status=1");
$notrespondedRecord = $objQuery->mysqlSelect("COUNT(DISTINCT(a.patient_id)) as Patient_Count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","b.ref_id='".$result[0]['ref_id']."' and b.response_status=0 and b.status2!=2");
$allRecord = $objQuery->mysqlSelect("COUNT(DISTINCT(a.patient_id)) as Patient_Count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","b.ref_id='".$result[0]['ref_id']."'");
$convertedRecord = $objQuery->mysqlSelect("COUNT(DISTINCT(a.patient_id)) as Patient_Count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.status2=8 or b.status2=7 or b.status2=9 or b.status2=11 or b.status2=12 or b.status2=13) and (b.ref_id='".$result[0]['ref_id']."')");

$pendingRecord = $objQuery->mysqlSelect("COUNT(DISTINCT(a.patient_id)) as Patient_Count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.ref_id='".$result[0]['ref_id']."') and (b.status2=2 or b.status2=3)");
$Totresponsetime= $objQuery->mysqlSelect("SUM(b.response_time) as Tot_response_time","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","b.response_time!=0 and b.ref_id='".$result[0]['ref_id']."'","","","","");
		$Countresponsetime= $objQuery->mysqlSelect("COUNT(a.patient_id) as Count_Response_Time","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","b.response_time!=0 and b.ref_id='".$result[0]['ref_id']."'","","","","");

		$actual_responded=$respondedRecord[0]['Patient_Count'];
		
		
		$conversion_rate=floor(($convertedRecord[0]['Patient_Count']*100)/$actual_responded);
		
		$response_rate=floor(($actual_responded/($allRecord[0]['Patient_Count']-$pendingRecord[0]['Patient_Count']))*100);
		
		$response_time=floor($Totresponsetime[0]['Tot_response_time']/$Countresponsetime[0]['Count_Response_Time']);
		
		//Total referred
		$Total_Referred_May = $objQuery->mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.ref_id='".$result[0]['ref_id']."') and (b.timestamp between '2017-05-01 00:00:00' and '2017-05-31 00:00:00')","","","","");
		$Total_Referred_Apr = $objQuery->mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.ref_id='".$result[0]['ref_id']."') and (b.timestamp between '2017-04-01 00:00:00' and '2017-04-31 00:00:00')","","","","");
		$Total_Referred_Mar = $objQuery->mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.ref_id='".$result[0]['ref_id']."') and (b.timestamp between '2017-03-01 00:00:00' and '2017-03-31 00:00:00')","","","","");
		$Total_Referred_Feb = $objQuery->mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.ref_id='".$result[0]['ref_id']."') and (b.timestamp between '2017-02-01 00:00:00' and '2017-02-29 00:00:00')","","","","");
		$Total_Referred_Jan = $objQuery->mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.ref_id='".$result[0]['ref_id']."') and (b.timestamp between '2017-01-01 00:00:00' and '2017-01-31 00:00:00')","","","","");
		$Total_Referred_Dec = $objQuery->mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.ref_id='".$result[0]['ref_id']."') and (b.timestamp between '2016-12-01 00:00:00' and '2016-12-31 00:00:00')","","","","");
		
		//Total responded
		$Total_Responded_May = $objQuery->mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (b.ref_id='".$result[0]['ref_id']."') and (b.timestamp between '2017-05-01 00:00:00' and '2017-05-31 00:00:00')","","","","");
		$Total_Responded_Apr = $objQuery->mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (b.ref_id='".$result[0]['ref_id']."') and (b.timestamp between '2017-04-01 00:00:00' and '2017-04-31 00:00:00')","","","","");
		$Total_Responded_Mar = $objQuery->mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (b.ref_id='".$result[0]['ref_id']."') and (b.timestamp between '2017-03-01 00:00:00' and '2017-03-31 00:00:00')","","","","");
		$Total_Responded_Feb = $objQuery->mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (b.ref_id='".$result[0]['ref_id']."') and (b.timestamp between '2017-02-01 00:00:00' and '2017-02-29 00:00:00')","","","","");
		$Total_Responded_Jan = $objQuery->mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (b.ref_id='".$result[0]['ref_id']."') and (b.timestamp between '2017-01-01 00:00:00' and '2017-01-31 00:00:00')","","","","");
		$Total_Responded_Dec = $objQuery->mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (b.ref_id='".$result[0]['ref_id']."') and (b.timestamp between '2016-12-01 00:00:00' and '2016-12-31 00:00:00')","","","","");
		
		$countConverted_May= $objQuery->mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.status2=8 or b.status2=7 or b.status2=9 or b.status2=11 or b.status2=12 or b.status2=13) and (b.ref_id='".$result[0]['ref_id']."') and (b.timestamp between '2017-05-01 00:00:00' and '2017-05-31 00:00:00')","","","","");
		$countConverted_Apr= $objQuery->mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.status2=8 or b.status2=7 or b.status2=9 or b.status2=11 or b.status2=12 or b.status2=13) and (b.ref_id='".$result[0]['ref_id']."') and (b.timestamp between '2017-04-01 00:00:00' and '2017-04-31 00:00:00')","","","","");
		$countConverted_Mar= $objQuery->mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.status2=8 or b.status2=7 or b.status2=9 or b.status2=11 or b.status2=12 or b.status2=13) and (b.ref_id='".$result[0]['ref_id']."') and (b.timestamp between '2017-03-01 00:00:00' and '2017-03-31 00:00:00')","","","","");
		$countConverted_Feb= $objQuery->mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.status2=8 or b.status2=7 or b.status2=9 or b.status2=11 or b.status2=12 or b.status2=13) and (b.ref_id='".$result[0]['ref_id']."') and (b.timestamp between '2017-02-01 00:00:00' and '2017-02-29 00:00:00')","","","","");
		$countConverted_Jan= $objQuery->mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.status2=8 or b.status2=7 or b.status2=9 or b.status2=11 or b.status2=12 or b.status2=13) and (b.ref_id='".$result[0]['ref_id']."') and (b.timestamp between '2017-01-01 00:00:00' and '2017-01-31 00:00:00')","","","","");
		$countConverted_Dec= $objQuery->mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.status2=8 or b.status2=7 or b.status2=9 or b.status2=11 or b.status2=12 or b.status2=13) and (b.ref_id='".$result[0]['ref_id']."') and (b.timestamp between '2016-12-01 00:00:00' and '2016-12-31 00:00:00')","","","","");
		
		//Avg Response Time
		$Totresponsetime_May= $objQuery->mysqlSelect("SUM(b.response_time) as Tot_response_time","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (b.response_time!=0) and (b.ref_id='".$result[0]['ref_id']."') and (b.timestamp between '2017-05-01 00:00:00' and '2017-05-31 00:00:00')","","","","");
		$Countresponsetime_May= $objQuery->mysqlSelect("COUNT(a.patient_id) as Count_Response_Time","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (b.response_time!=0) and (b.ref_id='".$result[0]['ref_id']."') and (b.timestamp between '2017-05-01 00:00:00' and '2017-05-31 00:00:00')","","","","");
		$_SESSION['response_time_May']=floor($Totresponsetime_Nov[0]['Tot_response_time']/$Countresponsetime_Nov[0]['Count_Response_Time']);

		$Totresponsetime_Apr= $objQuery->mysqlSelect("SUM(b.response_time) as Tot_response_time","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (b.response_time!=0) and (b.ref_id='".$result[0]['ref_id']."') and (b.timestamp between '2017-04-01 00:00:00' and '2017-04-31 00:00:00')","","","","");
		$Countresponsetime_Apr= $objQuery->mysqlSelect("COUNT(a.patient_id) as Count_Response_Time","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (b.response_time!=0) and (b.ref_id='".$result[0]['ref_id']."') and (b.timestamp between '2017-04-01 00:00:00' and '2017-04-31 00:00:00')","","","","");
		$_SESSION['response_time_Apr']=floor($Totresponsetime_Oct[0]['Tot_response_time']/$Countresponsetime_Oct[0]['Count_Response_Time']);
		
		
		$Totresponsetime_Mar= $objQuery->mysqlSelect("SUM(b.response_time) as Tot_response_time","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (b.response_time!=0) and (b.ref_id='".$result[0]['ref_id']."') and (b.timestamp between '2017-03-01 00:00:00' and '2017-03-31 00:00:00')","","","","");
		$Countresponsetime_Mar= $objQuery->mysqlSelect("COUNT(a.patient_id) as Count_Response_Time","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (b.response_time!=0) and (b.ref_id='".$result[0]['ref_id']."') and (b.timestamp between '2017-03-01 00:00:00' and '2017-03-31 00:00:00')","","","","");
		$_SESSION['response_time_Mar']=floor($Totresponsetime_Mar[0]['Tot_response_time']/$Countresponsetime_Mar[0]['Count_Response_Time']);
		
		
		$Totresponsetime_Feb= $objQuery->mysqlSelect("SUM(b.response_time) as Tot_response_time","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (b.response_time!=0) and (b.ref_id='".$result[0]['ref_id']."') and (b.timestamp between '2017-02-01 00:00:00' and '2017-02-29 00:00:00')","","","","");
		$Countresponsetime_Feb= $objQuery->mysqlSelect("COUNT(a.patient_id) as Count_Response_Time","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (b.response_time!=0) and (b.ref_id='".$result[0]['ref_id']."') and (b.timestamp between '2017-02-01 00:00:00' and '2017-02-29 00:00:00')","","","","");
		$_SESSION['response_time_Feb']=floor($Totresponsetime_Feb[0]['Tot_response_time']/$Countresponsetime_Feb[0]['Count_Response_Time']);

		$Totresponsetime_Jan= $objQuery->mysqlSelect("SUM(b.response_time) as Tot_response_time","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (b.response_time!=0) and (b.ref_id='".$result[0]['ref_id']."') and (b.timestamp between '2017-01-01 00:00:00' and '2017-01-31 00:00:00')","","","","");
		$Countresponsetime_Jan= $objQuery->mysqlSelect("COUNT(a.patient_id) as Count_Response_Time","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (b.response_time!=0) and (b.ref_id='".$result[0]['ref_id']."') and (b.timestamp between '2017-01-01 00:00:00' and '2017-01-31 00:00:00')","","","","");
		$_SESSION['response_time_Jan']=floor($Totresponsetime_Jan[0]['Tot_response_time']/$Countresponsetime_Jan[0]['Count_Response_Time']);

		$Totresponsetime_Dec= $objQuery->mysqlSelect("SUM(b.response_time) as Tot_response_time","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (b.response_time!=0) and (b.ref_id='".$result[0]['ref_id']."') and (b.timestamp between '2016-12-01 00:00:00' and '2016-12-31 00:00:00')","","","","");
		$Countresponsetime_Dec= $objQuery->mysqlSelect("COUNT(a.patient_id) as Count_Response_Time","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (b.response_time!=0) and (b.ref_id='".$result[0]['ref_id']."') and (b.timestamp between '2016-12-01 00:00:00' and '2016-12-31 00:00:00')","","","","");
		$_SESSION['response_time_Dec']=floor($Totresponsetime_Dec[0]['Tot_response_time']/$Countresponsetime_Dec[0]['Count_Response_Time']);

		$_SESSION['Total_Referred_May']=$Total_Referred_May[0]['Total_count'];
		$_SESSION['Total_Referred_Apr']=$Total_Referred_Apr[0]['Total_count'];
		$_SESSION['Total_Referred_Mar']=$Total_Referred_Mar[0]['Total_count'];	
		$_SESSION['Total_Referred_Feb']=$Total_Referred_Feb[0]['Total_count'];
		$_SESSION['Total_Referred_Jan']=$Total_Referred_Jan[0]['Total_count'];
		$_SESSION['Total_Referred_Dec']=$Total_Referred_Dec[0]['Total_count'];
			
		$_SESSION['Total_Responded_May']=$Total_Responded_May[0]['Total_count'];
		$_SESSION['Total_Responded_Apr']=$Total_Responded_Apr[0]['Total_count'];
		$_SESSION['Total_Responded_Mar']=$Total_Responded_Mar[0]['Total_count'];
		$_SESSION['Total_Responded_Feb']=$Total_Responded_Feb[0]['Total_count'];
		$_SESSION['Total_Responded_Jan']=$Total_Responded_Jan[0]['Total_count'];
		$_SESSION['Total_Responded_Dec']=$Total_Responded_Dec[0]['Total_count'];
		
		
		//Response rate
		$_SESSION['response_rate_May']=floor(($Total_Responded_May[0]['Total_count']/$Total_Referred_May[0]['Total_count'])*100);
		$_SESSION['response_rate_Apr']=floor(($Total_Responded_Apr[0]['Total_count']/$Total_Referred_Apr[0]['Total_count'])*100);
		$_SESSION['response_rate_Mar']=floor(($Total_Responded_Mar[0]['Total_count']/$Total_Referred_Mar[0]['Total_count'])*100);
		$_SESSION['response_rate_Feb']=floor(($Total_Responded_Feb[0]['Total_count']/$Total_Referred_Feb[0]['Total_count'])*100);
		$_SESSION['response_rate_Jan']=floor(($Total_Responded_Jan[0]['Total_count']/$Total_Referred_Jan[0]['Total_count'])*100);
		$_SESSION['response_rate_Dec']=floor(($Total_Responded_Dec[0]['Total_count']/$Total_Referred_Dec[0]['Total_count'])*100);
		
		//Conversion Rete
		$_SESSION['conversion_rate_May']=floor(($countConverted_May[0]['Total_count']*100)/$Total_Responded_May[0]['Total_count']);
		$_SESSION['conversion_rate_Apr']=floor(($countConverted_Apr[0]['Total_count']*100)/$Total_Responded_Apr[0]['Total_count']);
		$_SESSION['conversion_rate_Mar']=floor(($countConverted_Mar[0]['Total_count']*100)/$Total_Responded_Mar[0]['Total_count']);
		$_SESSION['conversion_rate_Feb']=floor(($countConverted_Feb[0]['Total_count']*100)/$Total_Responded_Feb[0]['Total_count']);
		$_SESSION['conversion_rate_Jan']=floor(($countConverted_Jan[0]['Total_count']*100)/$Total_Responded_Jan[0]['Total_count']);
		$_SESSION['conversion_rate_Dec']=floor(($countConverted_Dec[0]['Total_count']*100)/$Total_Responded_Dec[0]['Total_count']);
		
		
		
		//IF ITS TRUE THEN REDIRECTS TO "ALL RECORDS" PAGE
		
		$_SESSION['all_record'] = $allRecord[0]['Patient_Count'];
		$_SESSION['tot_ref_count'] = $totReferredCount[0]['Count_Records'];
		$_SESSION['tot_resp_count'] = $respondedRecord[0]['Patient_Count'];
		$_SESSION['not_resp_count'] = $notrespondedRecord[0]['Patient_Count'];
		$_SESSION['auto_resp_count'] = $autorespondedRecord[0]['Patient_Count'];
		$_SESSION['tot_treated_count'] = $totTreatedCount[0]['Count_Records'];
		$_SESSION['tot_converted_count'] = $convertedRecord[0]['Patient_Count'];
		$_SESSION['tot_Blogs'] = $totalBlogs[0]['Count_Blogs'];
		$_SESSION['conversion_rate']=$conversion_rate;
		$_SESSION['response_rate']=$response_rate;
		$_SESSION['response_time']=$response_time;
		header('location:Blogs-Offers-Events-List');
		
		
	}
	else
	{
		$respond=2;
		$errorMessage="Login failed. Username or password are invalid.";
		header('location:index.php?respond='.$respond);
	}

}


//CHANGE PASSWORD 
if(isset($_POST['changepassword'])){
	  $txtOrgName = $_POST['txtOrg'];
	 $txtUserName = $_POST['txtUSer'];
	 $txtEmail = $_POST['txtEmail'];
	 $txtMobile = $_POST['txtMobile'];
	 $txtPass = md5($_POST['txtnewpasswd']);
	 $logoImage = basename($_FILES['txtLogo']['name']);
	
	$result = $objQuery->mysqlSelect('*','our_partners',"company_id='".$_POST['user_id']."'");
	
	if(!empty($result)){
		
		$arrFields = array();
		$arrValues = array();
		
		$arrFields[] = 'company_name';
		$arrValues[] = $txtOrgName;
		$arrFields[] = 'owner_name';
		$arrValues[] = $txtUserName;
		$arrFields[] = 'email_id';
		$arrValues[] = $txtEmail;
		$arrFields[] = 'mobile';
		$arrValues[] = $txtMobile;
		$arrFields[] = 'password';
		$arrValues[] = $txtPass;
		if(!empty($logoImage)){
		$arrFields[] = 'company_logo';
		$arrValues[] = $logoImage;
		}
		
		$editrecord=$objQuery->mysqlUpdate('our_partners',$arrFields,$arrValues,"company_id='".$_POST['user_id']."'");
		$id=$_POST['user_id'];
				/* Uploading image file */ 
				if(basename($_FILES['txtLogo']['name']!=="")){ 
					$uploaddirectory = realpath("../Company_Logo");
					mkdir("../Company_Logo/". "/" . $id, 0777);
					$uploaddir = $uploaddirectory."/".$id;
					$dotpos = strpos($_FILES['txtLogo']['name'], '.');
					$photo = $logoImage;
					$uploadfile = $uploaddir . "/" . $photo;			
				
							
					/* Moving uploaded file from temporary folder to desired folder. */
					if(move_uploaded_file ($_FILES['txtLogo']['tmp_name'], $uploadfile)) {
						//echo "File uploaded.";
					} else {
						//echo "File cannot be uploaded";
					}
				}		
		header('location:view-profile?response=1');
	}
	else
	{
		
		header('location:logout.php');
	}

}
?>