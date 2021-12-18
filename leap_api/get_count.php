<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

// Get Count of ALL

 if(API_KEY == $_POST['API_KEY']  && isset($_POST['userid']) && isset($_POST['login_type'])) {
	 
	$login_type = $_POST['login_type'];
	$admin_id = ($_POST['userid']);

	if($login_type == 1)				// Hospital Doctor Login
	{
		$appointmentCount = $objQuery->mysqlSelect("COUNT(a.id) as CountAppoint","appointment_transaction_detail as a inner join doctor_hosp as b on a.pref_doc=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id inner join timings as d on d.Timing_id = a.Visiting_time","a.pref_doc='".$admin_id."' and a.pay_status!='Cancelled'","","","","");		
		$myPatientCount = $objQuery->mysqlSelect("COUNT(patient_id) as CountMyPatient","doc_my_patient","doc_id='".$admin_id."'","","","","");
		$patientsCount = $objQuery->mysqlSelect('COUNT(a.patient_id) as CountPatients','patient_tab as a inner join patient_referal as b on a.patient_id = b.patient_id inner join referal as c on c.ref_id = b.ref_id inner join source_list as d on d.source_id = a.patient_src',"c.ref_id='".$admin_id."'","","","","");
		
		$getHospital = $objQuery->mysqlSelect("b.hosp_id as hosp_id","referal as a inner join doctor_hosp as b on a.ref_id = b.doc_id","a.ref_id='".$admin_id."'","","","","");
		$hospital_id = $getHospital[0]['hosp_id'];
		$doctorsCount = $objQuery->mysqlSelect("COUNT(a.ref_id) as CountDoctors","referal as a inner join specialization as b on a.doc_spec=b.spec_id inner join doctor_hosp as c on a.ref_id = c.doc_id ","c.hosp_id='".$hospital_id."'","","","","");

		$blogsCount = $objQuery->mysqlSelect("COUNT(listing_id) as CountBlogs","blogs_offers_events_listing","listing_type = 'Blog'","","","","");
		$eventsCount = $objQuery->mysqlSelect("COUNT(listing_id) as CountEvents","blogs_offers_events_listing","listing_type = 'Events'","","","","");
		$jobsCount = $objQuery->mysqlSelect("COUNT(listing_id) as CountJobs","blogs_offers_events_listing","listing_type = 'Jobs'","","","","");
		$videosCount = $objQuery->mysqlSelect("COUNT(listing_id) as CountVideos","blogs_offers_events_listing","listing_type = 'Surgical'","","","","");
	
		//$getDoc = $objQuery->mysqlSelect("a.ref_id as Doc_Id,c.hosp_id as Hosp_Id,c.company_id as Comp_Id","referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id","a.ref_id='".$admin_id."'","","","","");
		//$carepartnersCount = $objQuery->mysqlSelect("COUNT(Partner_Id) as CountCarePartners","our_partners as a inner join mapping_hosp_referrer as b on a.partner_id=b.partner_id inner join hosp_tab as d on d.hosp_id=b.hosp_id","b.doc_id='".$admin_id."' and d.company_id='".$getDoc[0]['Comp_Id']."'","","","","");
		$getDoc = $objQuery->mysqlSelect("a.ref_id as Doc_Id,c.hosp_id as Hosp_Id,c.company_id as Comp_Id","referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id","a.ref_id='".$admin_id."'","","","","");
		$carepartnersCount = $objQuery->mysqlSelect("COUNT(a.partner_id) as CountCarePartners","our_partners as a inner join mapping_hosp_referrer as b on a.partner_id=b.partner_id inner join hosp_tab as d on d.hosp_id=b.hosp_id","b.doc_id='".$admin_id."' and d.company_id='".$getDoc[0]['Comp_Id']."'","","","","");
	
	}
	else if($login_type == 2)			// Partner Login
	{
		$appointmentCount = $objQuery->mysqlSelect("COUNT(a.appoint_id) as CountAppoint","partner_appointment_transaction as a inner join our_partners as b on a.pref_doc=b.partner_id inner join timings as d on d.Timing_id = a.Visiting_time","a.pref_doc='".$admin_id."' and a.pay_status!='Cancelled'","","","","");
		$myPatientCount = $objQuery->mysqlSelect("COUNT(patient_id) as CountMyPatient","my_patient","partner_id='".$admin_id."'","","","","");
		$patientsCount = $objQuery->mysqlSelect('COUNT(a.patient_id) as CountPatients','patient_tab as a inner join source_list as b on a.patient_src=b.source_id inner join patient_referal as c on c.patient_id =a.patient_id inner join referal as d on d.ref_id = c.ref_id inner join our_partners as e on e.partner_id = b.partner_id',"b.partner_id='".$admin_id."'","","","","");
		$doctorsCount = $objQuery->mysqlSelect("COUNT(a.ref_id) as CountDoctors","referal as a inner join doctor_hosp as c on c.doc_id=a.ref_id inner join mapping_hosp_referrer as d on d.hosp_id=c.hosp_id","a.doc_spec!=555 and a.anonymous_status!=1 and d.partner_id='".$admin_id."'","","","","");
		$blogsCount = $objQuery->mysqlSelect("COUNT(listing_id) as CountBlogs","blogs_offers_events_listing","listing_type = 'Blog'","","","","");
		$eventsCount = $objQuery->mysqlSelect("COUNT(listing_id) as CountEvents","blogs_offers_events_listing","listing_type = 'Events'","","","","");
		$jobsCount = $objQuery->mysqlSelect("COUNT(listing_id) as CountJobs","blogs_offers_events_listing","listing_type = 'Jobs'","","","","");
		$videosCount = $objQuery->mysqlSelect("COUNT(listing_id) as CountVideos","blogs_offers_events_listing","listing_type = 'Surgical'","","","","");
		$carepartnersCount = 0;
	}
	if($login_type == 3)				// Marketing Person Login
	{
		$marketingid=$admin_id;	 //Holds Marketing Person Id
	
		$getHospId = $objQuery->mysqlSelect("hosp_id","hosp_marketing_person","person_id='".$marketingid."'","","","","");
		$checkMapMarket = $objQuery->mysqlSelect("hosp_id","mapping_hosp_referrer","market_person_id='".$marketingid."'","","","","");
		if($checkMapMarket==true){
		$appointmentCount = $objQuery->mysqlSelect("COUNT(a.id) as CountAppoint","appointment_transaction_detail as a inner join referal as e on e.ref_id=a.pref_doc inner join doctor_hosp as b on a.pref_doc=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id","b.hosp_id='".$getHospId[0]['hosp_id']."' and a.pay_status!='Cancelled'","","","","");
		}
		else{
		$appointmentCount = $objQuery->mysqlSelect("COUNT(a.id) as CountAppoint","appointment_transaction_detail as a inner join referal as e on e.ref_id=a.pref_doc inner join doctor_hosp as b on a.pref_doc=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id","b.hosp_id='".$getHospId[0]['hosp_id']."' and a.pay_status!='Cancelled'","","","","");
		}
		
		$myPatientCount = 0;
		$patientsCount = $objQuery->mysqlSelect('COUNT(DISTINCT(b.patient_id)) as CountPatients','patient_tab AS a INNER JOIN patient_referal AS b ON b.patient_id = a.patient_id INNER JOIN source_list AS c ON c.source_id = a.patient_src INNER JOIN doctor_hosp AS e ON e.doc_id = b.ref_id INNER JOIN mapping_hosp_referrer AS d ON c.partner_id = d.partner_id',"d.market_person_id =  '".$marketingid."' AND e.hosp_id =  '".$getHospId[0]['hosp_id']."'","","","","");
	
		$getHospital = $objQuery->mysqlSelect("a.hosp_id","hosp_marketing_person as a inner join hosp_tab as b on a.hosp_id = b.hosp_id","a.person_id='".$admin_id."'","","","","");
		$hospital_id = $getHospital[0]['hosp_id'];
		$doctorsCount = $objQuery->mysqlSelect("COUNT(a.ref_id) as CountDoctors","referal as a inner join specialization as b on a.doc_spec=b.spec_id inner join doctor_hosp as c on a.ref_id = c.doc_id ","c.hosp_id='".$hospital_id."'","","","","");
		$blogsCount = $objQuery->mysqlSelect("COUNT(listing_id) as CountBlogs","blogs_offers_events_listing","listing_type = 'Blog'","","","","");
		$eventsCount = $objQuery->mysqlSelect("COUNT(listing_id) as CountEvents","blogs_offers_events_listing","listing_type = 'Events'","","","","");
		$jobsCount = $objQuery->mysqlSelect("COUNT(listing_id) as CountJobs","blogs_offers_events_listing","listing_type = 'Jobs'","","","","");
		$videosCount = $objQuery->mysqlSelect("COUNT(listing_id) as CountVideos","blogs_offers_events_listing","listing_type = 'Surgical'","","","","");
		$carepartnersCount = 0;
	}
		
		$result = array('status' => "true",'CountAppoint' => $appointmentCount[0]['CountAppoint'],'CountMyPatient' => $myPatientCount[0]['CountMyPatient'],'CountPatients' => $patientsCount[0]['CountPatients'],'CountDoctors' => $doctorsCount[0]['CountDoctors'],'CountBlogs' => $blogsCount[0]['CountBlogs'],'CountEvents' => $eventsCount[0]['CountEvents'],'CountJobs' => $jobsCount[0]['CountJobs'],'CountVideos' => $videosCount[0]['CountVideos'],'CountCarePartners' => $carepartnersCount[0]['CountCarePartners']);
		echo json_encode($result);
		/*if($appointmentCount == true)
		{
			$result = array('status' => "true",'CountAppoint' => $appointmentCount[0]['CountAppoint'],'CountMyPatient' => $myPatientCount[0]['CountMyPatient'],'CountPatients' => $patientsCount[0]['CountPatients'],'CountDoctors' => $doctorsCount[0]['CountDoctors'],'CountBlogs' => $blogsCount[0]['CountBlogs'],'CountEvents' => $eventsCount[0]['CountEvents'],'CountJobs' => $jobsCount[0]['CountJobs'],'CountVideos' => $videosCount[0]['CountVideos'],'CountCarePartners' => $carepartnersCount[0]['CountCarePartners']);
			echo json_encode($result);
		}
		else {	
			$result = array('status' => "true",'status' => "true",'CountAppoint' => $appointmentCount[0]['CountAppoint'],'CountMyPatient' => $myPatientCount[0]['CountMyPatient'],'CountPatients' => $patientsCount[0]['CountPatients'],'CountDoctors' => $doctorsCount[0]['CountDoctors'],'CountBlogs' => $blogsCount[0]['CountBlogs'],'CountEvents' => $eventsCount[0]['CountEvents'],'CountJobs' => $jobsCount[0]['CountJobs'],'CountVideos' => $videosCount[0]['CountVideos'],'CountCarePartners' => $carepartnersCount[0]['CountCarePartners']);
			echo json_encode($result);
		}*/
	
}


?>