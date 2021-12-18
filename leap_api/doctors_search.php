<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//DOCTOR SEARCH 
 if(API_KEY == $_POST['API_KEY'] && isset($_POST['search_string'])  && isset($_POST['login_type']) && isset($_POST['userid']) ) {
	 
	 $json = json_decode(file_get_contents('php://input'), true);	//Purpose: Used to read parameters as String not as JSON 

	$search_string = $_POST['search_string'];
	$login_type = $_POST['login_type'];
	$user_id = $_POST['userid'];
	//echo $login_type;
	//echo $user_id;

	$params     = explode(" ", $_POST['search_string']);
	$postid1 = $params[0];
	$postid2 = $params[1];
	//echo $_POST['search_string'];
	//echo $postid1;
	//echo $postid2;
	
	if($login_type == 1)	 //login_type is 1 for Hospital Doctor
	{
		$getHospital = $objQuery->mysqlSelect("b.hosp_id as hosp_id,d.institution_type as Institute_type","referal as a inner join doctor_hosp as b on a.ref_id = b.doc_id  inner join hosp_tab as c on c.hosp_id=b.hosp_id inner join compny_tab as d on d.company_id=c.company_id","a.ref_id='".$user_id."'","","","","");
		$hospital_id = $getHospital[0]['hosp_id'];
		//echo $hospital_id;
		
		if($getHospital[0]['Institute_type']=="2"){  //If Institute_type 1 is for Institutional doctor then display only perticular institutional doctor, And 2 is for Individual doctor then display all doctors
		
		$pag_result = $objQuery->mysqlSelect("*","referal as a inner join specialization as b on a.doc_spec=b.spec_id inner join doctor_hosp as c on c.doc_id=a.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","((a.doc_spec!=555 and a.anonymous_status!=1) and ((a.ref_name LIKE '%".$search_string."%' or a.doc_interest LIKE '%".$search_string."%' or a.doc_research LIKE '%".$search_string."%' or a.doc_contribute LIKE '%".$search_string."%' or a.doc_pub LIKE '%".$search_string."%' or d.hosp_name LIKE '%".$search_string."%') or ((b.spec_name LIKE '%".$postid1."%' or a.ref_name LIKE '%".$postid1."%' or a.ref_address LIKE '%".$postid1."%' or a.doc_city LIKE '%".$postid1."%' or a.doc_state LIKE '%".$postid1."%') and (a.ref_address LIKE '%".$postid2."%' or a.doc_city LIKE '%".$postid2."%' or a.doc_state LIKE '%".$postid2."%' or b.spec_name LIKE '%".$postid2."%'))))","a.doc_type_val asc","","","");
		} else {
		$pag_result = $objQuery->mysqlSelect("*","referal as a inner join specialization as b on a.doc_spec=b.spec_id inner join doctor_hosp as c on c.doc_id=a.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","((a.doc_spec!=555 and a.anonymous_status!=1 and c.hosp_id='".$hospital_id."') and ((a.ref_name LIKE '%".$search_string."%' or a.doc_interest LIKE '%".$search_string."%' or a.doc_research LIKE '%".$search_string."%' or a.doc_contribute LIKE '%".$search_string."%' or a.doc_pub LIKE '%".$search_string."%' or d.hosp_name LIKE '%".$search_string."%') or ((b.spec_name LIKE '%".$postid1."%' or a.ref_name LIKE '%".$postid1."%' or a.ref_address LIKE '%".$postid1."%' or a.doc_city LIKE '%".$postid1."%' or a.doc_state LIKE '%".$postid1."%') and (a.ref_address LIKE '%".$postid2."%' or a.doc_city LIKE '%".$postid2."%' or a.doc_state LIKE '%".$postid2."%' or b.spec_name LIKE '%".$postid2."%'))))","a.doc_type_val asc","","","");
		}	
		
		if($pag_result == true)
		{
			$success = array('status' => "true","page_result" => $pag_result);
			echo json_encode($success);
		}
		else {
			$success = array('status' => "false","page_result" => $pag_result);
			echo json_encode($success);
		}
	}
	else if($login_type == 2) 		//login_type is 2 for Partner
	{
		
	//	$pag_result = $objQuery->mysqlSelect("*","referal as a inner join specialization as b on b.spec_id=a.doc_spec inner join doctor_hosp as c on c.doc_id=a.ref_id inner join hosp_tab as e on e.hosp_id=c.hosp_id","((a.doc_spec!=555 and a.anonymous_status!=1) and ((a.ref_name LIKE '%".$search_string."%' or a.doc_interest LIKE '%".$search_string."%' or a.doc_research LIKE '%".$search_string."%' or a.doc_contribute LIKE '%".$search_string."%' or a.doc_pub LIKE '%".$search_string."%' or e.hosp_name LIKE '%".$search_string."%') or ((b.spec_name LIKE '%".$postid1."%' or a.ref_name LIKE '%".$postid1."%' or a.ref_address LIKE '%".$postid1."%' or a.doc_city LIKE '%".$postid1."%' or a.doc_state LIKE '%".$postid1."%') and (a.ref_address LIKE '%".$postid2."%' or a.doc_city LIKE '%".$postid2."%' or a.doc_state LIKE '%".$postid2."%' or b.spec_name LIKE '%".$postid2."%'))))","a.doc_type_val asc","","","");

		$pag_result = $objQuery->mysqlSelect("*","referal as a inner join specialization as b on b.spec_id=a.doc_spec inner join doctor_hosp as c on c.doc_id=a.ref_id inner join hosp_tab as e on e.hosp_id=c.hosp_id","((a.doc_spec!=555 and a.anonymous_status!=1) and ((a.ref_name LIKE '%".$search_string."%' or e.hosp_name LIKE '%".$search_string."%') or ((b.spec_name LIKE '%".$postid1."%' or a.ref_name LIKE '%".$postid1."%' or a.ref_address LIKE '%".$postid1."%' or a.doc_city LIKE '%".$postid1."%' or a.doc_state LIKE '%".$postid1."%') and (a.ref_address LIKE '%".$postid2."%' or a.doc_city LIKE '%".$postid2."%' or a.doc_state LIKE '%".$postid2."%' or b.spec_name LIKE '%".$postid2."%'))))","a.doc_type_val asc","","","");

		if($pag_result == true)
		{
			$success = array('status' => "true","page_result" => $pag_result);
			echo json_encode($success);
		}
		else {
			$success = array('status' => "false","page_result" => $pag_result);
			echo json_encode($success);
		}
	
	}
	else if($login_type == 3)	 //login_type is 3 for marketing Person
	{
		$getHospital = $objQuery->mysqlSelect("a.hosp_id as hosp_id","hosp_marketing_person as a inner join hosp_tab as b on a.hosp_id = b.hosp_id","a.person_id='".$user_id."'","","","","");
		$hospital_id = $getHospital[0]['hosp_id'];
		//ECHO $hospital_id;	
		
		// $pag_result = $objQuery->mysqlSelect("*","referal as a inner join specialization as b on a.doc_spec=b.spec_id inner join doctor_hosp as c on c.doc_id=a.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","((a.doc_spec!=555 and a.anonymous_status!=1 and c.hosp_id='".$hospital_id."') and ((a.ref_name LIKE '%".$search_string."%' or a.doc_interest LIKE '%".$search_string."%' or a.doc_research LIKE '%".$search_string."%' or a.doc_contribute LIKE '%".$search_string."%' or a.doc_pub LIKE '%".$search_string."%' or d.hosp_name LIKE '%".$search_string."%') or ((b.spec_name LIKE '%".$postid1."%' or a.ref_name LIKE '%".$postid1."%' or a.ref_address LIKE '%".$postid1."%' or a.doc_state LIKE '%".$postid1."%') and (a.ref_address LIKE '%".$postid2."%' or a.doc_state LIKE '%".$postid2."%' or b.spec_name LIKE '%".$postid2."%'))))","a.doc_type_val asc","","","0,5");

		$pag_result = $objQuery->mysqlSelect("*","referal as a inner join specialization as b on a.doc_spec=b.spec_id inner join doctor_hosp as c on c.doc_id=a.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","((a.doc_spec!=555 and a.anonymous_status!=1 and c.hosp_id='".$hospital_id."') and ((a.ref_name LIKE '%".$search_string."%' or a.doc_interest LIKE '%".$search_string."%' or a.doc_research LIKE '%".$search_string."%' or a.doc_contribute LIKE '%".$search_string."%' or a.doc_pub LIKE '%".$search_string."%' or d.hosp_name LIKE '%".$search_string."%') or ((b.spec_name LIKE '%".$postid1."%' or a.ref_name LIKE '%".$postid1."%' or a.ref_address LIKE '%".$postid1."%' or a.doc_state LIKE '%".$postid1."%') and (a.ref_address LIKE '%".$postid2."%' or a.doc_state LIKE '%".$postid2."%' or b.spec_name LIKE '%".$postid2."%'))))","a.doc_type_val asc","","","");

		if($pag_result == true)
		{
			$success = array('status' => "true","page_result" => $pag_result);
			echo json_encode($success);
		}
		else {
			$success = array('status' => "false","page_result" => $pag_result);
			echo json_encode($success);
		}
	}
		
		
}


?>