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
$objQuery = new CLSQueryMaker();
ob_start();

function getAuthToken($limit)
{
    return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
}

	// if(HEALTH_API_KEY == $data ->api_key && $data->filter_type==0 && $data->filter_type!=4 )
	// {

	// 	$result_doctor = $objQuery->mysqlSelect("ref_id ,md5(ref_id) as doc_encyid, ref_name, ref_address, ref_exp, doc_photo, doc_country_id, doc_city, doc_qual, doc_interest, geo_latitude, geo_longitude, cons_charge, cons_charge_currency_type","referal","nova_default_doctor!=1","ref_id DESC","","","");
			
	// 		$doc_details= array();
	// 		foreach($result_doctor as $result_doctorList) {
	// 			$getDocList['ref_id']=$result_doctorList['ref_id'];
	// 			$getDocList['doc_encyid']=$result_doctorList['doc_encyid'];
	// 			$getDocList['ref_name']=$result_doctorList['ref_name'];
	// 			$getDocList['ref_address']=$result_doctorList['ref_address'];
	// 			$getDocList['doc_exp']=$result_doctorList['ref_exp'];
	// 			$getDocList['doc_photo']=$result_doctorList['doc_photo'];
	// 			$getDocList['doc_country_id']=$result_doctorList['doc_country_id'];
	// 			$getDocList['doc_city']=$result_doctorList['doc_city'];
	// 			$getDocList['doc_qual']=$result_doctorList['doc_qual'];
	// 			$getDocList['doc_interest']=$result_doctorList['doc_interest'];
	// 			$getDocList['geo_latitude']=$result_doctorList['geo_latitude'];
	// 			$getDocList['geo_longitude']=$result_doctorList['geo_longitude']; 
	// 			$getDocList['cons_charge']=$result_doctorList['cons_charge']; 
	// 			$getDocList['cons_charge_currency_type']=$result_doctorList['cons_charge_currency_type']; 
			
	// 			$doc_specialization = $objQuery->mysqlSelect('a.doc_id as doc_id, a.doc_type as doc_type, a.spec_id as spec_id, b.spec_name as spec_name, b.spec_group_id as spec_group_id','doc_specialization as a left join specialization as b on a.spec_id=b.spec_id',"a.doc_id='".$result_doctorList['ref_id']."'","","","","");
	// 			$getDocList['doc_specializations']= $doc_specialization;
				
	// 			$getDocHospital = $objQuery->mysqlSelect("a.doc_hosp_id as doc_hosp_id, a.doc_id as doc_id, a.hosp_id as hosp_id, b.hosp_name as hosp_name, b.hosp_addrs as hosp_addrs, b.hosp_city as hosp_city, b.hosp_state as hosp_state, b.hosp_country as hosp_country, b.geo_latitude as geo_latitude, b.geo_longitude as geo_longitude","doctor_hosp as a inner join hosp_tab as b on b.hosp_id = a.hosp_id ","a.doc_id = '".$result_doctorList['ref_id']."'","","","","");
	// 			$getDocList['doc_hospitals']= $getDocHospital;

	// 			$getDocCountry=$objQuery->mysqlSelect("DISTINCT a.country_id,a.country_name","countries as a inner join referal as b on a.country_id=b.doc_country_id","b.doc_country_id='".$result_doctorList['doc_country_id']."'","","","","");

	// 			$getDocList['doc_country']= $getDocCountry;
					
	// 			array_push($doc_details, $getDocList);
	// 		}
		
					
	// 	$getCountries= $objQuery->mysqlSelect("DISTINCT a.country_id,a.country_name","countries as a inner join referal as b on a.country_id=b.doc_country_id","","a.country_id asc","","","");

	// 	$GetState = $objQuery->mysqlSelect("*","countries as a left join states as b on a.country_id=b.country_id", "a.country_name='".$getUserDet[0]['sub_country']."'", "b.state_name asc", "", "", "");
	// 	$fetchCity = $objQuery->mysqlSelect("d.city_name",'doctor_hosp as a inner join hosp_tab as b on b.hosp_id = a.hosp_id  inner join referal c on a.doc_id=c.ref_id inner join newcitylist d on d.city_id=b.hosp_new_city',"d.state='".$getUserDet[0]['sub_state']."'","d.city_name asc","d.city_name","","");
		
	// 	$getCity=$objQuery->mysqlSelect("d.city_name,d.city_id",'doctor_hosp as a inner join hosp_tab as b on b.hosp_id = a.hosp_id  inner join referal c on a.doc_id=c.ref_id inner join newcitylist d on d.city_id=b.hosp_new_city',"","d.city_name asc","d.city_id","","");
	// 	$getSpec= $objQuery->mysqlSelect("*","specialization","","spec_id asc","","","");
	
	// 	$getLang= $objQuery->mysqlSelect("DISTINCT(b.id) as id,b.name","doctor_langauges as a inner join languages as b on a.language_id=b.id","","","","","");

		
			
	// 	$default_doctor = $objQuery->mysqlSelect("ref_id ,md5(ref_id) as doc_encyid, ref_name, ref_address, ref_exp, doc_photo, doc_city, doc_qual, doc_country, doc_interest, geo_latitude, geo_longitude, cons_charge, cons_charge_currency_type, physical_consultation_charge","referal","nova_default_doctor=1","","","","");
	// 	$doc_details1= array();
	// 	foreach($default_doctor as $result_doctorList1) {
	// 		$getDocList1['ref_id']=$result_doctorList1['ref_id'];
	// 		$getDocList1['doc_encyid']=$result_doctorList1['doc_encyid'];
	// 		$getDocList1['ref_name']=$result_doctorList1['ref_name'];
	// 		$getDocList1['doc_exp']=$result_doctorList1['ref_exp'];
	// 		$getDocList1['doc_photo']=$result_doctorList1['doc_photo'];
	// 		$getDocList1['doc_city']=$result_doctorList1['doc_city'];
	// 		$getDocList1['doc_country']=$result_doctorList1['doc_country'];
	// 		$getDocList1['doc_qual']=$result_doctorList1['doc_qual'];
	// 		$getDocList1['ref_address']=$result_doctorList1['ref_address'];
	// 		$getDocList1['doc_interest']=$result_doctorList1['doc_interest'];
	// 		$getDocList1['geo_latitude']=$result_doctorList1['geo_latitude'];
	// 		$getDocList1['geo_longitude']=$result_doctorList1['geo_longitude']; 
	// 		$getDocList1['cons_charge']=$result_doctorList1['cons_charge']; 
	// 		$getDocList1['cons_charge_currency_type']=$result_doctorList1['cons_charge_currency_type']; 
	// 		$getDocList1['consult_charge']=$result_doctorList1['physical_consultation_charge']; 
		
	// 		$doc_specialization1 = $objQuery->mysqlSelect('a.doc_id as doc_id, a.doc_type as doc_type, a.spec_id as spec_id, b.spec_name as spec_name, b.spec_group_id as spec_group_id','doc_specialization as a left join specialization as b on a.spec_id=b.spec_id',"a.doc_id='".$result_doctorList['ref_id']."'","","","","");
	// 		$getDocList1['doc_specializations']= $doc_specialization1;
			
	// 		$getDocHospital1 = $objQuery->mysqlSelect("a.doc_hosp_id as doc_hosp_id, a.doc_id as doc_id, a.hosp_id as hosp_id, b.hosp_name as hosp_name, b.hosp_addrs as hosp_addrs, b.hosp_city as hosp_city, b.hosp_state as hosp_state, b.hosp_country as hosp_country, b.geo_latitude as geo_latitude, b.geo_longitude as geo_longitude","doctor_hosp as a inner join hosp_tab as b on b.hosp_id = a.hosp_id ","a.doc_id = '".$result_doctorList['ref_id']."'","","","","");
	// 		$getDocList1['doc_hospitals']= $getDocHospital1;

	// 		$docLanguages1 = $objQuery->mysqlSelect("a.id as id, a.doc_id as doc_id, a.language_id as language_id, b.name as language_name","doctor_langauges as a inner join languages as b on b.id = a.language_id","a.doc_id='".$result_doctorList['ref_id']."'","a.id ASC","","","");
	// 		$getDocList1['doc_languages']= $docLanguages1;
				
	// 		array_push($doc_details1, $getDocList1);
	// 	}


			
	// 	$response = array('status' => "true","getState" => $GetState,"fetchCity" => $fetchCity,"user_array" => $getUserDet,"doctor_array" => $doc_details,"getCountries" => $getCountries,"city_name" => $getCity,"spec_array" => $getSpec,"getLang" => $getLang, "default_doctor" => $doc_details1);
		
	// 	echo json_encode($response);
		
		
	// }
	if(HEALTH_API_KEY == $data ->api_key && $data->filter_type==2)
	{
		if($data->doctor!="")
		{
			$doctor=$data->doctor;
			$doc="and a.ref_name LIKE '%".$doctor."%'";
		}
		else
		{
			$doc="";
			
		}

		if($data->lang!="")
		{
			$lang=$data->lang;
			$language=$objQuery->mysqlSelect("DISTINCT(b.id) as id,b.name","doctor_langauges as a inner join languages as b on a.language_id=b.id","b.id='".$lang."'","","","","");
			
		}
		else
		{
			//$lang=array();
			$lang1=$objQuery->mysqlSelect("DISTINCT(b.id) as id,b.name","doctor_langauges as a inner join languages as b on a.language_id=b.id","","","","","");
			//$lang=$lang[0]['id'];

			$lang="";
	        foreach($lang1 as $language)
	        {
	            if($lang != "")
	            $lang .= ",";
	            $lang .= $language['id'];
	           
	        }
		}
		if($data->country!="")
		{
			$country=$data->country;

			$cntry=$objQuery->mysqlSelect("DISTINCT a.country_id,a.country_name","countries as a inner join referal as b on a.country_id=b.doc_country_id","b.doc_country_id='".$country."'","","","","");

			$country="and a.doc_country_id in (".$country.")";
		}
		else if($data->country_name!=""){

			$cntry=$objQuery->mysqlSelect("DISTINCT a.country_id,a.country_name","countries as a inner join referal as b on a.country_id=b.doc_country_id","md5(a.country_name)='".$data->country_name."'","","","","");
			$cn=$cntry[0]['country_id'];
			$country="and a.doc_country_id in (".$cn.")";
		}

		else if($data->global_country_name!=""){

			$cntry1=$objQuery->mysqlSelect("DISTINCT a.country_id,a.country_name","countries as a inner join referal as b on a.country_id=b.doc_country_id","md5(a.country_name) !='".$data->global_country_name."'","","","","");


			$cn="";
	        foreach($cntry1 as $country)
	        {
	            if($cn != "")
	            $cn .= ",";
	            $cn .= $country['country_id'];
	           
	        }

			$country="and a.doc_country_id in (".$cn.")";
		}

		else
		{
			$country="";
			
		}
		if($data->spcl!="")
		{
			$spcl=$data->spcl;
			$specialization=$objQuery->mysqlSelect("*","specialization","spec_id in(".$spcl.")","spec_id asc","","","");
			$spcl="and c.spec_id in (".$spcl.")";
			
		}
		else
		{
			$spcl="";
		}
		$result_doctor = $objQuery->mysqlSelect("DISTINCT a.ref_id ,md5(a.ref_id) as doc_encyid, a.ref_name, a.ref_address, a.ref_exp, a.doc_photo,a.doc_country_id, a.doc_city, a.doc_state, a.doc_qual, a.doc_interest, a.physical_consultation_charge,a.cons_charge_currency_type, a.doc_contribute, a.doc_research","referal as a left join doctor_langauges as b on a.ref_id=b.doc_id left join doc_specialization as c on c.doc_id=a.ref_id","a.anonymous_status!=1 and nova_default_doctor!=1 and b.language_id in (".$lang.")  ".$country." ".$spcl." ".$doc,"a.ref_id desc","","","");

			$doc_details= array();
			foreach($result_doctor as $result_doctorList) {
				$getDocList['ref_id']=$result_doctorList['ref_id'];
				$getDocList['doc_encyid']=$result_doctorList['doc_encyid'];
				$getDocList['ref_name']=$result_doctorList['ref_name'];
				$getDocList['ref_address']=$result_doctorList['ref_address'];
				$getDocList['doc_exp']=$result_doctorList['ref_exp'];
				$getDocList['doc_photo']=$result_doctorList['doc_photo'];
				$getDocList['doc_country_id']=$result_doctorList['doc_country_id'];
				$getDocList['doc_city']=$result_doctorList['doc_city'];
				$getDocList['doc_state']=$result_doctorList['doc_state'];
				$getDocList['doc_qual']=$result_doctorList['doc_qual'];
				$getDocList['doc_interest']=$result_doctorList['doc_interest'];
				$getDocList['cons_charge']=$result_doctorList['physical_consultation_charge'];
				$getDocList['cons_charge_currency_type']=$result_doctorList['cons_charge_currency_type'];
				$getDocList['doc_contribute']=$result_doctorList['doc_contribute'];
				$getDocList['doc_research']=$result_doctorList['doc_research']; 
			
				$doc_specialization = $objQuery->mysqlSelect('a.doc_id as doc_id, a.doc_type as doc_type, a.spec_id as spec_id, b.spec_name as spec_name, b.spec_group_id as spec_group_id','doc_specialization as a left join specialization as b on a.spec_id=b.spec_id',"a.doc_id='".$result_doctorList['ref_id']."'","","","","");
				$getDocList['doc_specializations']= $doc_specialization;
				
				$getDocHospital = $objQuery->mysqlSelect("a.doc_hosp_id as doc_hosp_id, a.doc_id as doc_id, a.hosp_id as hosp_id, b.hosp_name as hosp_name, b.hosp_addrs as hosp_addrs, b.hosp_city as hosp_city, b.hosp_state as hosp_state, b.hosp_country as hosp_country, b.geo_latitude as geo_latitude, b.geo_longitude as geo_longitude","doctor_hosp as a left join hosp_tab as b on b.hosp_id = a.hosp_id ","a.doc_id = '".$result_doctorList['ref_id']."'","","","","");
				$getDocList['doc_hospitals']= $getDocHospital;

				$getDocCountry=$objQuery->mysqlSelect("DISTINCT a.country_id,a.country_name","countries as a left join referal as b on a.country_id=b.doc_country_id","b.doc_country_id='".$result_doctorList['doc_country_id']."'","","","","");
				$getDocList['doc_country']= $getDocCountry;

				$getDocLang = $objQuery->mysqlSelect('b.name','doctor_langauges as a left join languages as b on a.language_id=b.id',"a.doc_id='".$result_doctorList['ref_id']."'","","","","");

				$getDocList['doc_language']= $getDocLang;
					
				array_push($doc_details, $getDocList);
			
			}
					
		$getCountries= $objQuery->mysqlSelect("DISTINCT a.country_id,a.country_name","countries as a left join referal as b on a.country_id=b.doc_country_id","","a.country_id asc","","","");
		$GetState = $objQuery->mysqlSelect("*","countries as a left join states as b on a.country_id=b.country_id", "a.country_name='".$getUserDet[0]['sub_country']."'", "b.state_name asc", "", "", "");
		$fetchCity = $objQuery->mysqlSelect("d.city_name",'doctor_hosp as a left join hosp_tab as b on b.hosp_id = a.hosp_id  left join referal c on a.doc_id=c.ref_id left join newcitylist d on d.city_id=b.hosp_new_city',"d.state='".$getUserDet[0]['sub_state']."'","d.city_name asc","d.city_name","","");
		
		$getCity=$objQuery->mysqlSelect("d.city_name,d.city_id",'doctor_hosp as a left join hosp_tab as b on b.hosp_id = a.hosp_id  left join referal c on a.doc_id=c.ref_id left join newcitylist d on d.city_id=b.hosp_new_city',"","d.city_name asc","d.city_id","","");
		$getSpec= $objQuery->mysqlSelect("*","specialization","","spec_id asc","","","");
		

		$getLang= $objQuery->mysqlSelect("DISTINCT(b.id) as id,b.name","doctor_langauges as a left join languages as b on a.language_id=b.id","","","","","");

		$default_doctor = $objQuery->mysqlSelect("DISTINCT(a.ref_id) as ref_id,md5(a.ref_id) as doc_encyid, a.ref_name, a.ref_address, a.ref_exp, a.doc_photo,a.doc_country_id, a.doc_city, a.doc_state, a.doc_qual, a.doc_interest, a.physical_consultation_charge,a.cons_charge_currency_type, a.doc_contribute, a.doc_research, a.geo_latitude, a.geo_longitude","referal as a left join doctor_langauges as b on a.ref_id=b.doc_id left join doc_specialization as c on c.doc_id=a.ref_id","nova_default_doctor=1","a.ref_id desc","","","");


		$doc_details1= array();
		foreach($default_doctor as $result_doctorList1) {
			$getDocList1['ref_id']=$result_doctorList1['ref_id'];
			$getDocList1['doc_encyid']=$result_doctorList1['doc_encyid'];
			$getDocList1['ref_name']=$result_doctorList1['ref_name'];
			$getDocList1['ref_address']=$result_doctorList1['ref_address'];
			$getDocList1['doc_exp']=$result_doctorList1['ref_exp'];
			$getDocList1['doc_photo']=$result_doctorList1['doc_photo'];
			$getDocList1['doc_city']=$result_doctorList1['doc_city'];
			$getDocList1['doc_state']=$result_doctorList1['doc_state'];
			$getDocList1['doc_country_id']=$result_doctorList1['doc_country_id'];
			$getDocList1['doc_qual']=$result_doctorList1['doc_qual'];
			$getDocList1['ref_address']=$result_doctorList1['ref_address'];
			$getDocList1['doc_interest']=$result_doctorList1['doc_interest'];
			$getDocList1['geo_latitude']=$result_doctorList1['geo_latitude'];
			$getDocList1['geo_longitude']=$result_doctorList1['geo_longitude']; 
			$getDocList1['cons_charge']=$result_doctorList1['cons_charge']; 
			$getDocList1['doc_contribute']=$result_doctorList1['doc_contribute'];
			$getDocList1['doc_research']=$result_doctorList1['doc_research'];
			$getDocList1['cons_charge_currency_type']=$result_doctorList1['cons_charge_currency_type']; 
			$getDocList1['consult_charge']=$result_doctorList1['physical_consultation_charge']; 
		
			$doc_specialization1 = $objQuery->mysqlSelect('a.doc_id as doc_id, a.doc_type as doc_type, a.spec_id as spec_id, b.spec_name as spec_name, b.spec_group_id as spec_group_id','doc_specialization as a left join specialization as b on a.spec_id=b.spec_id',"a.doc_id='".$result_doctorList1['ref_id']."'","","","","");
			$getDocList1['doc_specializations']= $doc_specialization1;
			
			$getDocHospital1 = $objQuery->mysqlSelect("a.doc_hosp_id as doc_hosp_id, a.doc_id as doc_id, a.hosp_id as hosp_id, b.hosp_name as hosp_name, b.hosp_addrs as hosp_addrs, b.hosp_city as hosp_city, b.hosp_state as hosp_state, b.hosp_country as hosp_country, b.geo_latitude as geo_latitude, b.geo_longitude as geo_longitude","doctor_hosp as a left join hosp_tab as b on b.hosp_id = a.hosp_id ","a.doc_id = '".$result_doctorList1['ref_id']."'","","","","");
			$getDocList1['doc_hospitals']= $getDocHospital1;

			$getDocLang1 = $objQuery->mysqlSelect('b.name','doctor_langauges as a left join languages as b on a.language_id=b.id',"a.doc_id='".$result_doctorList1['ref_id']."'","","","","");

			$getDocList1['doc_languages']= $getDocLang1;


			$getDocCountry1=$objQuery->mysqlSelect("DISTINCT a.country_id,a.country_name","countries as a left join referal as b on a.country_id=b.doc_country_id","b.doc_country_id='".$result_doctorList1['doc_country_id']."'","","","","");
				$getDocList1['doc_country']= $getDocCountry1;
				
			array_push($doc_details1, $getDocList1);
		}
		

		$getLang= $objQuery->mysqlSelect("DISTINCT(b.id) as id,b.name","doctor_langauges as a left join languages as b on a.language_id=b.id","","","","","");
		$response = array('status' => "true","getState" => $GetState,"fetchCity" => $fetchCity,"user_array" => $getUserDet,"doctor_array" => $doc_details,"getCountries" => $getCountries,"city_name" => $getCity,"spec_array" => $getSpec,"getLang" => $getLang,'doc_lang'=>$language,"specialization"=>$specialization,"cntry"=>$cntry, "default_doctor" => $doc_details1);
		
			echo json_encode($response);
	}
	
	else {
			
			$response["status"] = "false";
			echo(json_encode($response));
		}


?>


