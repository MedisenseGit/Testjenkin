<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//PATIENT SEARCH
 if(API_KEY == $_POST['API_KEY'] && isset($_POST['search_string']) && isset($_POST['userid']) && isset($_POST['login_type']) ) {
	 
	 $json = json_decode(file_get_contents('php://input'), true);	//Purpose: Used to read parameters as String not as JSON 

	$search_string = $_POST['search_string'];
	$user_id = $_POST['userid'];
	$login_type = $_POST['login_type'];
	
	//	echo $search_string;
	//	echo $user_id;
	//	echo $login_type;
	
	 if($login_type == 2)		// Type-2 Referring Partners
	{
		$getPartner = $objQuery->mysqlSelect('a.patient_id as pat_id, a.patient_name as pat_name, a.patient_age as pat_age, a.patient_loc as pat_loc, c.status2 as pat_status, d.ref_name as pat_doc_name, d.ref_id as pat_doc_id, e.partner_name as pat_refered_by,c.timestamp as pat_status_time','patient_tab as a inner join source_list as b on a.patient_src=b.source_id inner join patient_referal as c on c.patient_id =a.patient_id inner join referal as d on d.ref_id = c.ref_id inner join our_partners as e on e.partner_id = b.partner_id',"b.partner_id='".$user_id."' and (a.patient_id ='".$search_string."'or a.patient_mob ='".$search_string."'or a.patient_name LIKE '%".$search_string."%' or a.patient_email ='".$search_string."' or a.patient_loc LIKE '%".$search_string."%' or a.patient_desc LIKE '%".$search_string."%' or a.patient_complaint LIKE '%".$search_string."%')","a.TImestamp desc","","","");
		$success = array('status' => "true","page_result" => $getPartner);
		echo json_encode($success);
		/* if($getPartner == true)
		{
			$success = array('status' => "true","page_result" => $getPartner);
			echo json_encode($success);
		}
		else {
			$success = array('status' => "false","page_result" => $getPartner);
			echo json_encode($success);
		} 
	 */
		
	} 
	else if($login_type == 3)		// Type-3 Marketing Person
	{
		
		$getCompany =  $objQuery->mysqlSelect('*','hosp_marketing_person as a inner join mapping_hosp_referrer as b on a.person_id = b.market_person_id',"a.person_id='".$user_id."'","","","","");
		
		if($getCompany == true) {
			foreach($getCompany as $partnerList){
				// echo $partnerList['partner_id'];
				$getMarket = $objQuery->mysqlSelect('a.patient_id as pat_id, a.patient_name as pat_name, a.patient_age as pat_age, a.patient_loc as pat_loc, c.status2 as pat_status, d.ref_name as pat_doc_name, d.ref_id as pat_doc_id, e.partner_name as pat_refered_by,c.timestamp as pat_status_time','patient_tab as a inner join source_list as b on a.patient_src=b.source_id inner join patient_referal as c on c.patient_id =a.patient_id inner join referal as d on d.ref_id = c.ref_id inner join our_partners as e on e.partner_id = b.partner_id',"b.partner_id='".$partnerList['partner_id']."' and (a.patient_id ='".$search_string."'or a.patient_mob ='".$search_string."'or a.patient_name LIKE '%".$search_string."%' or a.patient_email ='".$search_string."' or a.patient_loc LIKE '%".$search_string."%' or a.patient_desc LIKE '%".$search_string."%' or a.patient_complaint LIKE '%".$search_string."%')","a.TImestamp desc","","","");
			
			}
				$success = array('status' => "true","page_result" => $getMarket);
				echo json_encode($success);
		}
			
	}
	else if($login_type == 1)		// Type-1 Hospital Doctors
	{
	
		$getPartner = $objQuery->mysqlSelect('a.patient_id as pat_id, a.patient_name as pat_name, a.patient_age as pat_age, a.patient_loc as pat_loc, c.status2 as pat_status, d.ref_name as pat_doc_name, d.ref_id as pat_doc_id, e.partner_name as pat_refered_by,c.timestamp as pat_status_time','patient_tab as a inner join source_list as b on a.patient_src=b.source_id inner join patient_referal as c on c.patient_id =a.patient_id inner join referal as d on d.ref_id = c.ref_id inner join our_partners as e on e.partner_id = b.partner_id',"d.ref_id='".$user_id."' and (a.patient_id ='".$search_string."'or a.patient_mob ='".$search_string."'or a.patient_name LIKE '%".$search_string."%' or a.patient_email ='".$search_string."' or a.patient_loc LIKE '%".$search_string."%' or a.patient_desc LIKE '%".$search_string."%' or a.patient_complaint LIKE '%".$search_string."%')","a.TImestamp desc","","","");
		$success = array('status' => "true","page_result" => $getPartner);
		echo json_encode($success);
	
			
	}
		
}


?>