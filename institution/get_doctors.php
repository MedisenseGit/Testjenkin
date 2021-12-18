<?php
ob_start();
error_reporting(0);
session_start();

//$docid=$_SESSION['docid'];

require_once("../classes/querymaker.class.php");

ob_start();

$ip 	= $_SERVER['REMOTE_ADDR']; // find time zone
$ipInfo = file_get_contents('http://ip-api.com/json/' .$ip);
$ipInfo = json_decode($ipInfo);
$timezone = $ipInfo->timezone;
date_default_timezone_set($timezone);

$Cur_Date=date('H:i A');
$str="";

$count=0;
if(isset($_POST["hosp_id"]))
{
	$hosp_id	=	$_POST["hosp_id"];
	$GetDoctors	= mysqlSelect("a.ref_id AS Ref_Id, a.ref_name AS Doc_name, c.hosp_name AS Hosp_name, d.spec_name AS Department","referal AS a INNER JOIN doctor_hosp AS b ON a.ref_id = b.doc_id INNER JOIN hosp_tab AS c ON c.hosp_id = b.hosp_id INNER JOIN doc_specialization AS e ON e.doc_id = a.ref_id INNER JOIN specialization AS d ON d.spec_id = e.spec_id
	","c.hosp_id ='".$hosp_id."' ","","","","");	

	foreach($GetDoctors as $list)
	{
		$count=$count+1;
?>
<!--option value="<?php echo ($list['Ref_Id']); ?>" ><?php echo $count.")".stripslashes($list['Doc_name']).", ".stripslashes($list['Department']).",".stripslashes($list['Hosp_name']);?></option-->

<option value="<?php echo ($list['Ref_Id']); ?>" ><?php echo stripslashes($list['Doc_name']).", ".stripslashes($list['Department']); ?></option>
<?php 
	}
}
	
?>