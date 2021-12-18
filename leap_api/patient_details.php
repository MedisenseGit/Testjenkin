<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//Patient Detail
 if(API_KEY == $_POST['API_KEY'] && isset($_POST['patient_id']) ) {
	 
	$json = json_decode(file_get_contents('php://input'), true);	//Purpose: Used to read parameters as String not as JSON 	
	 
	$patient_id = $_POST['patient_id'];
	//echo $patient_id;

/*	
	// $result = $objQuery->mysqlSelect('a.patient_id as pid, a.patient_name as pname, a.system_date as pdate, a.patient_age as page, a.patient_gen as pgen, a.merital_status as pmarital,a.weight as pweight, a.hyper_cond as phyper, a.diabetes_cond as pdiabetes, a.qualification as pqual,a.pref_city as ploc, a.patient_addrs as paddress, a.pat_state as pstate, a.pat_country as pcountry, a.contact_person as pcontact,a.patient_mob as pcontact, a.patient_email as pemail, a.medDept as pdept, a.profession as pprof, a.patient_complaint as pcomplaint,a.pat_query as pquery, a.patient_desc as pdesc, b.attach_id as attachemetid, b.attachments as pattach, c.status2 as pstatus','patient_tab as a inner join patient_attachment as b on a.patient_id = b.patient_id inner join patient_referal as c on c.patient_id= a.patient_id',"a.patient_id='".$patient_id."'","","","","");
	//$result_count = $objQuery->mysqlSelect('coun','patient_tab as a inner join patient_attachment as b on a.patient_id = b.patient_id',"a.patient_id='".$patient_id."'","","","","");
	
	// $result = $objQuery->mysqlSelect('a.patient_id as pid, a.patient_name as pname, a.system_date as pdate, a.patient_age as page, a.patient_gen as pgen, a.merital_status as pmarital,a.weight as pweight, a.hyper_cond as phyper, a.diabetes_cond as pdiabetes, a.qualification as pqual,a.pref_city as ploc, a.patient_addrs as paddress, a.pat_state as pstate, a.pat_country as pcountry, a.contact_person as pcontact,a.patient_mob as pcontact, a.patient_email as pemail, a.medDept as pdept, a.profession as pprof, a.patient_complaint as pcomplaint,a.pat_query as pquery, a.patient_desc as pdesc, c.status2 as pstatus','patient_tab as a inner join patient_referal as c on c.patient_id= a.patient_id',"a.patient_id='".$patient_id."'","","","","");
	
//	$result = $objQuery->mysqlSelect('*','patient_tab as a inner join patient_referal as c on c.patient_id= a.patient_id',"a.patient_id='".$patient_id."'","","","","");

	// $result = $objQuery->mysqlSelect('*','patient_tab as a inner join patient_referal as c on c.patient_id= a.patient_id',"a.patient_id='".$patient_id."'","","","","");
*/	

//	$result = $objQuery->mysqlSelect('*','patient_tab as a inner join patient_referal as c on c.patient_id= a.patient_id inner join referal as d on d.ref_id = c.ref_id inner join specialization as e on e.spec_id = a.medDept',"a.patient_id='".$patient_id."'","","","","");
	
	$result = $objQuery->mysqlSelect('a.patient_id as patient_id, a.patient_name as patient_name, a.patient_age as patient_age, a.patient_gen as patient_gen, a.patient_email as patient_email,a.merital_status as merital_status, a.qualification as qualification, a.contact_person as contact_person, a.profession as profession,a.patient_mob as patient_mob, a.patient_loc as patient_loc, a.pat_state as pat_state, a.pat_country as pat_country, a.patient_addrs as patient_addrs,a.patient_complaint as patient_complaint, a.patient_desc as patient_desc, a.pat_query as pat_query, a.TImestamp as assign_date, a.weight as weight, a.hyper_cond as hyper_cond, a.diabetes_cond as diabetes_cond, a.medDept as medDept, c.status2 as status2,e.spec_id as spec_id, e.spec_name as spec_name, d.ref_id as ref_id, d.ref_name as ref_name, a.currentTreatDoc as currentTreatDoc, a.currentTreatHosp as currentTreatHosp','patient_tab as a inner join patient_referal as c on c.patient_id= a.patient_id inner join referal as d on d.ref_id = c.ref_id inner join specialization as e on e.spec_id = a.medDept',"a.patient_id='".$patient_id."'","","","","");
	
	
	if($result == true)
	{
		$success = array('status' => "true","patient_details" => $result);    	//  patient details array
		echo json_encode($success);
	}
	else {
		$success = array('status' => "false","patient_details" => $result);      // Invalid patient details
		echo json_encode($success);
	}
	
}


?>