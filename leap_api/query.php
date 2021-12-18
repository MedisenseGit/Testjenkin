<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

	$status_id1 = 2;
	$status_id2 = 5;
	$user_id=28;
	
	//echo $user_id;
		
	// $result = $objQuery->mysqlSelect('j1.chat_id AS chat_id, (j2.TImestamp - j1.TImestamp) AS time_run','chat_notification j1 INNER JOIN chat_notification j2 ON (j1.chat_id = j2.chat_id)',"j1.status_id='".$status_id1."' and j2.status_id='".$status_id2."'","","","","");
	
/*	 $result = $objQuery->mysqlSelect('*','chat_notification',"user_id !='".$user_id."'","","","","");
	

	$length = count($result);
		// echo $length;
		for ($i = 0; $i < $length; $i++) {
			// print_r ($result[$i]['chat_id'] ." ".$result[$i]['status_id'] ." <br />");
			// $result1 = $objQuery->mysqlSelect('j1.chat_id AS chat_id','chat_notification j1 INNER JOIN chat_notification j2 ON (j1.chat_id = j2.chat_id)',"j1.status_id='".$status_id1."' and j2.status_id='".$status_id2."'","","","","0,1000");
			// $length1 = count($result1);
		//	echo $length1;
		//	for ($j = 0; $j < $length1; $j++) {
		//			print_r ($result1[$j]['chat_id'] ." ".$result1[$j]['time_run'] ." <br />");
		//		}
		}
    

	/*	$result1 = $objQuery->mysqlSelect('j1.chat_id AS chat_id','chat_notification j1 INNER JOIN chat_notification j2 ON (j1.chat_id = j2.chat_id)',"j1.status_id='".$status_id1."' OR j2.status_id='".$status_id2."'","","","","");
		$length1 = count($result1);
		echo $length1;
		echo "<br />";
		for ($i = 0; $i < $length1; $i++) {
			print_r ($result1[$i]['chat_id']." <br />");
		}  */
		
	//	$result1 = $objQuery->mysqlSelect('*','chat_notification j1 INNER JOIN chat_notification j2 ON (j1.chat_id = j2.chat_id)'," j2.status_id='".$status_id2."'","","","","12000,13000");
		
		
/*	$result1 = $objQuery->mysqlSelect('*','chat_notification',"status_id='".$status_id2."'","","","","");
			
		$length1 = count($result1);
		echo $length1;
		echo "<br />";
		
		for ($i = 0; $i < $length1; $i++) {
			
		//	echo $result1[$i]['chat_id']." ". $result1[$i]['TImestamp'];
		//	$datetime1 = new DateTime($result1[$i]['TImestamp']);
		echo date('M d, Y h:i:s',strtotime($result1[$i]['TImestamp']));

			// print_r ($result1[$i]['chat_id']." :Status: " . $result1[$i]['status_id']." <br />");
			// $result2 = $objQuery->mysqlSelect('*','chat_notification',"status_id='".$status_id1."'","","","","");
			
			
			
		//	$datetime2 = new DateTime($result2[$i]['TImestamp']);
		//	$interval = $datetime1->diff($datetime2);

		//	print_r ($result2[$i]['patient_id'] ." ");
		//	echo $interval->format('%a%H%i');
			echo "<br />";

		}
		
*/

$getDocResponse = $objQuery->mysqlSelect("*","chat_notification","status_id='".$status_id1."'or status_id='".$status_id2."'","","","","");
//echo count($getDocResponse);	
$res1 = $objQuery->mysqlSelect('*','chat_notification',"status_id = '2'","","","","");
//echo count($res1);



for($i =0; i< count($getDocResponse); $i++) {

	echo " ";
	echo $getDocResponse[$i]['TImestamp'];
	echo " ";
	echo $res1[$i]['TImestamp'];
	echo " ";
	echo $res1[$i]['patient_id'];
	
}
		 echo "<br />";

?>