<?php
ob_start();
error_reporting(0);
session_start();

require_once("../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();
ob_start();
$doc_email=$_POST["email"];
$Contact_num=$_POST["contact"];

?>

<?php
if(!empty($_POST["email"]) || !empty($_POST["contact"]))
	{
	
		$GetState= mysqlSelect("*","referal","ref_mail='".$doc_email."'","ref_id asc","","","");
		$val=$GetState[0]['ref_mail'];
		 
		if(($val)!="")
		{
			 $result="This EmailId is already registerd...."; 
			 
		}
		else
		{
			 $result="";
		}
		 echo $result;
	}
	
?>