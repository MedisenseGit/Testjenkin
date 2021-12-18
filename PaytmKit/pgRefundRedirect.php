<?php
header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Expires: 0");
 // following files need to be included 
require_once("lib/config_paytm.php");
require_once("lib/encdec_paytm.php"); 

 $checkSum = ""; 
 $paramList = array();
 
 $ORDER_ID = $_POST["ORDER_ID"]; 
 $TXN_AMOUNT = $_POST["TXN_AMOUNT"]; 
 $MOBILE_NO = $_POST["MOBILE_NO"]; 
 $TXNID = $_POST["TXNID"]; 
 // Create an array having all required parameters for creating checksum. 
 $paramList["MID"] = PAYTM_MERCHANT_MID; 
 $paramList["ORDERID"] = $ORDER_ID; 
 $paramList["TXNTYPE"] = 'REFUND'; 
 $paramList["REFUNDAMOUNT"] = $TXN_AMOUNT; 
 $paramList["TXNID"] = $TXNID; 
 
 //Here checksum string will return by getChecksumFromArray() function. 
 $checkSum = getChecksumFromArray($paramList,PAYTM_MERCHANT_KEY); 
 $paramList["CHECKSUM"] = urlencode($checkSum); 
 $paramList["REFID"] = $_POST["REFID"]; 
 //Unique value all time 
 $data_string = 'JsonData='.json_encode($paramList); 
 //print_r($data_string); 
 $ch = curl_init(); 
 // initiate curl 
 $url = "https://pguat.paytm.com/oltp/HANDLER_INTERNAL/REFUND"; 
 // where you want to post data curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); 
 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
 curl_setopt($ch, CURLOPT_URL,$url); 
 curl_setopt($ch, CURLOPT_POST, true); 
 // tell curl you want to post something 
 curl_setopt($ch, CURLOPT_POSTFIELDS,$data_string); 
 // define what you want to post 
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
 // return the output in string format 
 $headers = array(); 
 $headers[] = 'Content-Type: application/json'; 
 curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
 $output = curl_exec ($ch); 
 // execute $info = curl_getinfo($ch); 
 //echo $output; 
$data = json_decode($output, true); 
// echo "";

print_r($data);

echo "<br><br>Refund Amount is:".$data['STATUS'];

//echo $data['RESPMSG']['ErrorCode'];



?>