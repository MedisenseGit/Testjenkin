<?php
ob_start();
error_reporting(0);
session_start();

$docid=$_SESSION['docid'];

require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
ob_start();

$_SESSION['visit_date']=$_POST["day_val"];
if(!empty($_POST["day_val"])) {
	$day_val=date('D', strtotime($_POST["day_val"]));
	$GetTiming= $objQuery->mysqlSelect("*","seven_days as a left join doc_time_set as b on a.day_id=b.day_id","b.doc_id='".$docid."' and a.da_name='".$day_val."'","","","","");
?>
	<option value="">Select Timing</option>
<?php
	foreach($GetTiming as $TimeList) {
		$Timing= $objQuery->mysqlSelect("*","timings","Timing_id='".$TimeList["time_id"]."'","","","","");
?>
	<option value="<?php echo $Timing[0]["Timing_id"]; ?>"><?php echo $Timing[0]["Timing"]; ?></option>
<?php
	}
}
?>
