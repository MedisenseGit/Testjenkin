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
//$objQuery = new CLSQueryMaker();
ob_start();

if(HEALTH_API_KEY == $data ->api_key && $data->filter_type>=0)
{
    $country_all = mysqlSelect('*','countries',"status = 1","country_name ASC","","","");
    if(!empty($country_all)){
        $response = array('status' => "true","country_all" => $country_all, 'messaage'=>'sucsess');
        echo json_encode($response);
    }else{
        $response = array('status' => "false");
        $response = array('messaage' => "query failed");
        echo json_encode($response);
    }
    
}
else{
    $response["status"] = "false";
    $response["messaage"] = "Api failed";
    echo(json_encode($response));
}


?>


