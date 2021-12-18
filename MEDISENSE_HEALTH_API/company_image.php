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


if(HEALTH_API_KEY == $data ->api_key && $data->id)
	
	{
		$getCompany= $objQuery->mysqlSelect("*","compny_tab","company_logo!= ' ' AND md5(company_id)='".$data->id."' ","","","","");
		$getCompanyImages= $objQuery->mysqlSelect("*","company_tab_images","image_name!= ' ' AND md5(company_id)='".$data->id."' ","","","","");
		   	
			$response = array('status' => "true","company_array" => $getCompany,"company_image_array" => $getCompanyImages);
		
			echo json_encode($response);
		
		
	}
	
	
	else {
			
			$response["status"] = "false";
			echo(json_encode($response));
		}


?>


