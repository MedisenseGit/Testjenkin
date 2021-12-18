<?php
$con=mysqli_connect("localhost","root","","shashi_medisense_crm");
if (mysqli_connect_errno($con))
{
   echo '{"query_result":"ERROR"}';
}
 
$mobile = $_GET['contact_num'];
$password = $_GET['doc_password'];

 
$get_res= mysqli_query($con,"SELECT * FROM referal WHERE contact_num = '$mobile' AND doc_password = md5('$password')");
 print_r($get_res);
if($get_res == true) {
    echo '{"query_result":"SUCCESS"}';
	 $result_mod_enc = mysql_fetch_array($get_res);
	echo json_encode(array("result"=>$result_mod_enc));
	/*while($row = mysql_fetch_array($get_res,MYSQL_ASSOC)){
array_push($result,array('id'=>$row[0],'name'=>$row[1],'address'=>$row[2]));
print_r($result);
echo json_encode(array("result"=>$result));
}*/
	
}
else{
    echo '{"query_result":"FAILURE"}';
}
mysql_close($con);
?>