<?php ob_start();
 error_reporting(0);
 session_start(); 

// required headers
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
//echo $data ->api_key;

date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d H:i:s');
require_once("../classes/querymaker.class.php");
require_once("../DigitalOceanSpaces/src/upload_function.php");
//$objQuery = new CLSQueryMaker();
ob_start();

function getAuthToken($limit)
{
    return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
}

	if( HEALTH_API_KEY == $data ->api_key && $data->filter_type == 0 && isset($data->memberid))	
	{
		$member_id 		= $data->memberid;
		$user_id 		= $data->adminid;
		$country_name 	= $data ->country_name;

		$member_basic = mysqlSelect("*","user_family_member","md5(member_id)='".$member_id."' ","","","","");

		$getMemberDet=mysqlSelect('*','user_family_member',"user_id='".$user_id."'","","","","");
		
		$member_general_health = mysqlSelect('*','user_family_general_health',"md5(member_id)='".$member_id."'","","","","");

		if(empty($member_general_health)){
			$arrFileds_medical[]='member_id';
			$arrValues_medical[]= $member_basic[0]['member_id'];
        	$family_general_health = mysqlInsert('user_family_general_health',$arrFileds_medical,$arrValues_medical );

			$member_general_health = mysqlSelect('*','user_family_general_health',"md5(member_id)='".$member_id."'","","","","");
		}

		//fetch report images

		$episodeReport= array();

		$report_list = mysqlSelect('*','health_app_healthfile_reports',"md5(member_id)='".$member_id."'","created_date DESC","","","");

		$report_attachments = mysqlSelect('*','health_app_healthfile_report_attachments',"md5(member_id)='".$member_id."'","","","","");

		$reportlist_details = mysqlSelect("*","health_app_healthfile_reports","md5(member_id) ='".$member_id."'","id DESC","","","");

		$reports_details= array();
		foreach($reportlist_details as $result_reportList) {

				$getReportList['report_id']=$result_reportList['id'];
				$getReportList['title']=$result_reportList['title'];
				$getReportList['description']=$result_reportList['description'];
				$getReportList['report_date']=$result_reportList['report_date'];
				$getReportList['report_date']=$result_reportList['report_date'];
				$getReportList['date_time']=$result_reportList['created_date'];
				$getReportList['doc_id']="";
				
				$attachment_details = mysqlSelect("id as attachment_id, attachment_name as attachment_name","health_app_healthfile_report_attachments","report_id ='".$result_reportList['id']."'","id ASC","","","");

				$getReportList['attachments']= $attachment_details;

				$getReportList['type']= '1';
				
			array_push($reports_details, $getReportList);
		}

		
		$episodeList_details = mysqlSelect("*","doc_my_patient as a inner join doc_patient_episodes as b on a.patient_id=b.patient_id","md5(a.member_id) ='".$member_id."'","episode_id DESC","","","");

		foreach($episodeList_details as $result_reportList){

			$getReportList['report_id']=$result_reportList['episode_id'];
			$getReportList['patient_id']=$result_reportList['patient_id'];
			$getReportList['doc_id']=$result_reportList['doc_id'];
			$getReportList['report_date']=$result_reportList['date_time'];
			$getReportList['date_time']=$result_reportList['date_time'];

			$doctor_name= mysqlSelect("ref_name","referal","ref_id ='".$result_reportList['doc_id']."'","","","","");

			$getReportList['title']= $doctor_name[0]['ref_name'];

			$patient_symptons = mysqlSelect("*","doc_patient_symptoms_active as a inner join chief_medical_complaints as b on a.symptoms=b.complaint_id","a.episode_id='".$result_reportList['episode_id']."'","","","","");

			$getReportList['description']= $patient_symptons;

			$patient_prescription = mysqlSelect("*","doc_patient_episode_prescriptions","episode_id='".$result_reportList['episode_id']."'","","","","");

			$getReportList['attachments']= $patient_prescription;
			
			$getReportList['type']= '2';

			array_push($reports_details, $getReportList);
		}

		
		//adults vaccine
		$vaccinelist_details = mysqlSelect("*","vaccine_adults","md5(member_id) ='".$member_id."'","id DESC","","","");
		$vaccine_details= array();
		foreach($vaccinelist_details as $result_vaccineList) {
				$getVaccineList['report_id']=$result_vaccineList['id'];
				$getVaccineList['member_id']=$result_vaccineList['member_id'];
				$getVaccineList['given_date']=$result_vaccineList['given_date'];
				$getVaccineList['vaccine_name']=$result_vaccineList['vaccine_name'];
				$getVaccineList['hospital_name']=$result_vaccineList['hospital_name'];
				$getVaccineList['dose']=$result_vaccineList['dose'];
				$getVaccineList['created_date']=$result_vaccineList['created_date'];
				
				$attachment_details = mysqlSelect("id as attachment_id, report_name as report_name","vaccine_adults_reports","vaccine_id ='".$result_vaccineList['id']."'","id ASC","","","");
				$getVaccineList['attachments']= $attachment_details;
				
			array_push($vaccine_details, $getVaccineList);
		}

		//child vaccine

		$getCountry=mysqlSelect('*','countries',"country_name='".$country_name."'","","","","");

		if(!empty($getCountry) && $getCountry[0]['country_id']=='179') { //only for qatar vaccine
			$country_id = $getCountry[0]['country_id'];
		}
		else {
			$country_id = '0'; 					// Default Local doctors 
		}

		$childId= mysqlSelect("*","child_tab","md5(member_id)='".$member_id."'","","","","");

		$vaccineduration = mysqlSelect("*","vaccine_duration","","duartion_id asc","","","");

		$vaccineduration_details= array();
		
		$actual_array= array();

		foreach($vaccineduration as $result_vaccineduration) {

			$getVaccineDur['duartion_id']=$result_vaccineduration['duartion_id'];
			$getVaccineDur['duration_name']=$result_vaccineduration['duration_name'];

			$vaccinename = mysqlSelect("*","vaccine_mapping as a left join vaccine_tab as b on a.vaccine_tab_id=b.vaccine_id","a.vaccine_duration_id='".$result_vaccineduration['duartion_id']."' AND b.country_id ='".$country_id."'","a.vaccine_duration_id asc","","","");

			$getVaccineDur['vaccinename']=$vaccinename;

			if(!empty($childId))
			{
			$getLastVaccineVal= mysqlSelect("*","vaccine_child_tab","child_tab_id='".$childId[0]['child_id']."' and vaccine_duration_id='".$result_vaccineduration['duartion_id']."'","","","","");

			
			}
			else
			{
				$getLastVaccineVal=array();
			}
			$getVaccineDur['LastVaccineVal']=$getLastVaccineVal;

			
			//foreach($vaccinename as $vaccinenameList) {
				
				if(!empty($childId))
				{

					$getActualVaccine= mysqlSelect("*","vaccine_child_tab","child_tab_id='".$childId[0]['child_id']."' and vaccine_id='".$vaccinename[0]['vaccine_id']."' and vaccine_duration_id='".$vaccinename[0]['vaccine_duration_id']."'","","","","");
				}
				else
				{
					$getActualVaccine=array();
				}


			$getVaccineDur['ActualVaccine']=$getActualVaccine;
	
			array_push($vaccineduration_details, $getVaccineDur);
		
			
		}
				

		$response['status'] = "true";
		$response['member_basic_array'] = $member_basic;


		$response['getMember'] = $getMemberDet;

		$response['general_health_array'] = $member_general_health;

		$response['report_list'] = $report_list;
		$response['report_attachments'] = $report_attachments;

		$response['reports_details'] = $reports_details;

		$response['vaccine_details'] = $vaccine_details;

		$response['vaccineduration'] = $vaccineduration_details;

		$response['childId'] = $childId;

		echo json_encode($response);
		

	}

	else if(HEALTH_API_KEY == $data ->api_key && $data ->query == "insert" && isset($data ->temp_name))
	{
		$login_id = $data ->login_id;
		$member_id = $data ->member_id;
		$txtTitle = addslashes($data ->title);
		$txtDescription = addslashes($data ->description);
		// $txtTimeStamp = $data ->date_given;
		$Cur_Date=date('Y-m-d H:i:s');
		$txtDate = $data ->date_given;
		
		$temp_name=$data ->temp_name;
		$file_name=$data ->file_name;
		
		$arrFields = array();
		$arrValues = array();

		$arrFields[] = 'login_id';
		$arrValues[] = $login_id;
		
		$arrFields[] = 'member_id';
		$arrValues[] = $member_id;

		$arrFields[] = 'title';
		$arrValues[] = $txtTitle;

		$arrFields[] = 'description';
		$arrValues[] = $txtDescription;

		// $arrFields[] = 'timeStampNum';
		// $arrValues[] = $txtTimeStamp;
		
		$arrFields[] = 'created_date';
		$arrValues[] = $Cur_Date;
		
		$arrFields[] = 'report_date';
		$arrValues[] = date('Y-m-d',strtotime($txtDate));


		$usercraete=mysqlInsert('health_app_healthfile_reports',$arrFields,$arrValues);
		$id = $usercraete;

		//Add Lab Test Attachments functionality
		$errors= array();
		foreach($data ->temp_name as $key => $tmp_name ){

				$temp_name = 	$data ->temp_name[$key];
				$file_name =	$data ->file_name[$key];

				if(!empty($file_name)){
					$Photo1  = $file_name;
					$arrFields_attach = array();
					$arrValues_attach = array();

					$arrFields_attach[] = 'report_id';
					$arrValues_attach[] = $id;
					
					$arrFields_attach[] = 'login_id';
					$arrValues_attach[] = $login_id;
					
					$arrFields_attach[] = 'member_id';
					$arrValues_attach[] = $member_id;

					$arrFields_attach[] = 'attachment_name';
					$arrValues_attach[] = $file_name;

					$pat_attach=mysqlInsert('health_app_healthfile_report_attachments',$arrFields_attach,$arrValues_attach);
					$attachid= $pat_attach;

					$folder_name	=	"HealthFilesReports";
					$sub_folder		=	$attachid;
					$filename		=	$data ->file_name[$key];
					$file_url		=	$data ->temp_name[$key];
					fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
					
					
										
					//Uploading image file
					// $uploaddirectory = realpath("../HealthFilesReports");

					// $uploaddir = $uploaddirectory . "/" .$attachid;
					// $dotpos = strpos($uploaddir, '.');

					// $Photo1 = str_replace(substr($Photo1, 0, $dotpos), $attachid, $Photo1);
					// $uploadfile = $uploaddir . "/" . $Photo1;


					//Checking whether folder with category id already exist or not.
					// if (file_exists($uploaddir)) {
					//echo "The file $uploaddir exists";
					// } else {
						// $newdir = mkdir($uploaddirectory . "/" . $attachid, 0777, true);
					// }


					// $conn_id = ftp_connect('128.199.207.75');

					// ftp_login($conn_id, 'root', 'rootConsole@25Nova');
					// ftp_pasv($conn_id, true);


					// $remove_server_dir="/var/www/html/HealthFilesReports"

					// $h = fopen('php://temp', 'r+');
					// fwrite($h, $out);
					// rewind($h);

					// ftp_fput($conn_id, '$remove_server_dir', $h, FTP_BINARY, 0);

					// fclose($h);


					// $target_dir = realpath("/var/www/html/HealthFilesReports/");
					// $file = $file_name;
					// $path = pathinfo($file);
					// $filename = $path['filename'];
					// $ext = $path['extension'];
					// $temp_name = $temp_name;
					// $path_filename_ext = $target_dir."/". $filename.".".$ext;


				 
					// // Check if file already exists
					// if (file_exists($path_filename_ext)) {
					//  //echo "Sorry, file already exists.";
					//  }else{
					//  move_uploaded_file($temp_name,$path_filename_ext);
					//  //echo "Congratulations! File Uploaded Successfully.";
					//  }


					// //$file = 'somefile.txt';
					// $remote_file = '/var/www/html/HealthFilesReports';

					// // Create temporary file
					// $local_file=fopen('php://temp', 'r+');
					// fwrite($local_file, $file_string);
					// rewind($local_file);


					// // set up basic connection
					// $ftp = ftp_connect("128.199.207.75");

					// // login with username and password
					// $login_result = ftp_login($ftp, "root", "rootConsole@25Nova");

					// // upload a file
					// if (ftp_put($ftp, $remote_file, $local_file, FTP_ASCII)) {
					//  //echo "successfully uploaded $file\n";
					// } else {
					//  //echo "There was a problem while uploading $file\n";
					// }

					// // close the connection
					// ftp_close($ftp);


					// Moving uploaded file from temporary folder to desired folder.

					// move_uploaded_file($temp_name, $uploadfile);

					// if(move_uploaded_file ($temp_name, $uploadfile)) {

					// 	$successAttach="";
					// } else {
					// 					//echo "File cannot be uploaded";
					// }
				}  
			}

			$reulst = array("result"=>"true");

			echo json_encode($reulst);

			
		}

		else if(HEALTH_API_KEY == $data ->api_key && $data ->query == "delete" && isset($data ->report_id))
		{
			$report_id = $data ->report_id;

			$delReports = mysqlDelete('health_app_healthfile_reports',"id='".$report_id."'");
			$delReportAttachments = mysqlDelete('health_app_healthfile_report_attachments',"report_id='".$report_id."'");

			$result = array("result"=>"true");

			echo json_encode($result);
		}
		else if(HEALTH_API_KEY == $data ->api_key && $data ->query == "insertvaccine" && isset($data ->temp_name))
		{

			$login_id = $data ->login_id;
			$member_id = $data ->member_id;
			$txtVaccineName = addslashes($data ->vaccine_name);
			$txtHospital = addslashes($data ->hospital_name);
			$txtDose = $data ->dose;
			$txtGivenDate = $data ->certifiacte_date;
			$Cur_Date=date('Y-m-d H:i:s');

			$temp_name=$data ->temp_name;
			$file_name=$data ->file_name;

			$arrFields = array();
			$arrValues = array();

			$arrFields[] = 'login_id';
			$arrValues[] = $login_id;
			
			$arrFields[] = 'member_id';
			$arrValues[] = $member_id;

			$arrFields[] = 'given_date';
			$arrValues[] = date('Y-m-d',strtotime($txtGivenDate));

			$arrFields[] = 'vaccine_name';
			$arrValues[] = $txtVaccineName;

			$arrFields[] = 'hospital_name';
			$arrValues[] = $txtHospital;
			
			$arrFields[] = 'dose';
			$arrValues[] = $txtDose;
			
			$arrFields[] = 'created_date';
			$arrValues[] = $Cur_Date;


			$usercraete=mysqlInsert('vaccine_adults',$arrFields,$arrValues);
			$id = $usercraete;

			$errors= array();
				if(!empty($file_name)){

					$Photo1  = $file_name;
					$arrFields_attach = array();
					$arrValues_attach = array();

					$arrFields_attach[] = 'vaccine_id';
					$arrValues_attach[] = $id;
					
					$arrFields_attach[] = 'login_id';
					$arrValues_attach[] = $login_id;
					
					$arrFields_attach[] = 'member_id';
					$arrValues_attach[] = $member_id;

					$arrFields_attach[] = 'report_name';
					$arrValues_attach[] = $file_name;
					
					$arrFields_attach[] = 'created_date';
					$arrValues_attach[] = $Cur_Date;


					$pat_attach=mysqlInsert('vaccine_adults_reports',$arrFields_attach,$arrValues_attach);
					$attachid= $pat_attach;

					//Uploading image file
					// $uploaddirectory = realpath("../VaccineAdultReports");
					// $uploaddir = $uploaddirectory . "/" .$attachid;
					// $dotpos = strpos($fileName, '.');
					// $Photo1 = str_replace(substr($Photo1, 0, $dotpos), $attachid, $Photo1);
					// $uploadfile = $uploaddir . "/" . $Photo1;


					// //Checking whether folder with category id already exist or not.
					// if (file_exists($uploaddir)) {
					// //echo "The file $uploaddir exists";
					// 	} else {
					// 		$newdir = mkdir($uploaddirectory . "/" . $attachid, 0777);
					// 	}

					// 	// Moving uploaded file from temporary folder to desired folder.
					// if(move_uploaded_file ($file_tmp, $uploadfile)) {

					// 	$successAttach="";
					// } else {
					// 	//echo "File cannot be uploaded";
					// }
				
				}  

			$result = array("result"=>"true");

			echo json_encode($result);

		}

		else if(HEALTH_API_KEY == $data ->api_key && $data ->query == "deletevaccine" && isset($data ->vaccine_id))
		{
			$vaccine_id = $data ->vaccine_id;

			$delReports = mysqlDelete('vaccine_adults',"id='".$vaccine_id."'");
			$delReportAttachments = mysqlDelete('vaccine_adults_reports',"vaccine_id='".$vaccine_id."'");

			$result = array("result"=>"true");

			echo json_encode($result);
		}
		else if(HEALTH_API_KEY == $data ->api_key && $data ->query == "updatehealth")
		{

			$login_id = $data ->login_id;
			$member_id = $data ->member_id;

			$txtbp           = addslashes($data ->bp);
			$txtthyroid      = addslashes($data ->thyroid);
			$txthypertension = addslashes($data ->hypertension);
			$txtasthama      = addslashes($data ->asthama);
			$txtcholestrol   = addslashes($data ->cholesterol);
			$txtepilepsy     = addslashes($data ->epilepsy);
			$txtdiabetic      = addslashes($data ->diabetic);
			$txtallergies     = addslashes($data ->allergies);


			$arrhealthFields = array();
			$arrhealthValues = array();
			
			$arrhealthFields[] = 'member_id';
			$arrhealthValues[] = $member_id;
			
			$arrhealthFields[] = 'user_id';
			$arrhealthValues[] = $user_id;
			
			$arrhealthFields[] = 'bp';
			$arrhealthValues[] = $txtbp;
			
			$arrhealthFields[] = 'hypertension';
			$arrhealthValues[] = $txthypertension;
			
			$arrhealthFields[] = 'cholesterol';
			$arrhealthValues[] = $txtcholestrol;
			
			$arrhealthFields[] = 'diabetic';
			$arrhealthValues[] = $txtdiabetic;
			
			$arrhealthFields[] = 'thyroid';
			$arrhealthValues[] = $txtthyroid;
			
			$arrhealthFields[] = 'asthama';
			$arrhealthValues[] = $txtasthama;
			
			$arrhealthFields[] = 'epilepsy';
			$arrhealthValues[] = $txtepilepsy;
			
			$arrhealthFields[] = 'allergies_any';
			$arrhealthValues[] = $txtallergies;
			
			// $usercraete=mysqlInsert('user_family_general_health',$arrhealthFields,$arrhealthValues); //user_family_general_health

			$usercraete=mysqlUpdate('user_family_general_health',$arrhealthFields,$arrhealthValues,"member_id='".$member_id."'");
			
			$result = array("result"=>"true");

			echo json_encode($result);
		}

		else if(HEALTH_API_KEY == $data ->api_key && $data ->query == "insertchildvaccine")
		{

			$login_id = $data ->login_id;
			$vaccine_given_date = $data ->vaccine_given_date;

			$child_vaccine_id = $data ->child_vaccine_id;
			$child_duration_id = $data ->child_duration_id;


			$child_id = $data ->child_id;
			$remarks = $data ->remarks;


			$user_type = $data ->user_type;
			$created_at = $data ->created_at;


			$arrFields1 = array();
			$arrValues1 = array();

			$arrFields1[]= 'vaccine_given_date';
			$arrValues1[]=  date('Y-m-d',strtotime($vaccine_given_date));
			$arrFields1[]= 'vaccine_id';
			$arrValues1[]=  $child_vaccine_id;
			$arrFields1[]= 'vaccine_duration_id';
			$arrValues1[]=  $child_duration_id;
			$arrFields1[]= 'child_tab_id';
			$arrValues1[]=  $child_id;
			$arrFields1[]= 'remarks';
			$arrValues1[]=  $remarks;
			$arrFields1[]= 'user_id';
			$arrValues1[]=  $login_id;
			$arrFields1[]= 'user_type';
			$arrValues1[]=  $user_type;
			$arrFields1[]= 'created_at';
			$arrValues1[]=  $created_at;


			$getChild = mysqlSelect('child_name','child_tab',"child_id='".$child_id."'","","","","");
		
			$userDetails = mysqlSelect("*","referal","ref_id='".$admin_id."'","","","","");
			$userName=$userDetails[0]['ref_name'];
			
			$getcount = mysqlSelect('count(vaccine_given_date) AS NumberofGivenDate','vaccine_child_tab',"vaccine_id='".$child_vaccine_id."' and child_tab_id='".$child_id."' and vaccine_duration_id='".$child_duration_id."' and user_id='".$login_id."' and user_type='".$user_type."'","","","","");

			

			if( $getcount[0]['NumberofGivenDate'] >= 1) 
			{
				$vaccine_update=mysqlUpdate('vaccine_child_tab',$arrFields1,$arrValues1,"vaccine_id='".$child_vaccine_id."' and child_tab_id='".$child_id."' and vaccine_duration_id='".$child_duration_id."' and user_id='".$login_id."' and user_type='".$user_type."'");
				
			}
			else {
				$add_vaccine=mysqlInsert('vaccine_child_tab',$arrFields1,$arrValues1);
				
			}
			
			$result = array("result"=>"true");

			echo json_encode($result);
		}

?>


