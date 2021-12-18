<?php
ob_start();
session_start();
error_reporting(0);

require_once("../classes/querymaker.class.php");


date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d H:i:s');
$cur_Date=date('Y-m-d',strtotime($Cur_Date));


$headers = apache_request_headers();
if($headers)
{
    $doctor_id = $headers['doctor-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey   = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $doctor_id, $device_id);

		$admin_id = $_POST['admin_id'];	
		
		$recived_cases= array();
		$allRecord = mysqlSelect("DISTINCT(a.patient_id) as Patient_Id","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","","b.timestamp desc","","","");
		
		
		
		foreach($allRecord as $list)
		{ 
			$admin_id = $_POST['admin_id']; //2837							
			$refDoctors = mysqlSelect("a.patient_name as Patient_Name,a.TImestamp as Reg_Date,a.patient_id as Patient_Id,a.patient_src as patient_src,b.ref_id as Doc_Id,a.transaction_status as Pay_Status,b.status2 as status2","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","a.patient_id='".$list['Patient_Id']."' and d.company_id='".$admin_id."'","","","","");
			
			
			$getEpiList['patient_id'] = $list['Patient_Id'];
			$getEpiList['Reg_date']   = $list['Reg_Date'];
			$getEpiList['Patientname'] = $list['Patient_Name'];
			
			
			$getCurrentStatus = mysqlSelect("*","patient_referal","patient_id='".$list['Patient_Id']."'","","","","");
			
			$getReferredBy = mysqlSelect("*","patient_referal","patient_id='".$list['Patient_Id']."'","","","","");
			
			$getEpiList['status2']   = $getCurrentStatus[0]['status2'];
			$getEpiList['Patientname'] = $list['Patient_Name'];
			
			/*$getRefDoc = mysqlSelect("a.contact_person as partner_name","our_partners as a left join source_list as b on a.partner_id=b.partner_id","b.source_id='".$refDoctors[0]['patient_src']."'","","","","");
			
			$getCurrentStatus = mysqlSelect("*","patient_referal","patient_id='".$list['Patient_Id']."'","","","","");
			
			$getReferredBy = mysqlSelect("*","patient_referal","patient_id='".$list['Patient_Id']."'","","","","");	*/
			
			//$getEpiList['Patient_Name']=$list['Patient_Name'];
			
			array_push($recived_cases, $getEpiList);

		
			
							
		}
		

$success = array('status' => "true", "recived_cases"=>$recived_cases, 'err_msg' => '');
echo json_encode($success);
//echo"second_opinion..";
//$allRecord = mysqlSelect("*","patient_tab","","","","","");
//var_dump($allRecord);

?>