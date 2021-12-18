<?php
ob_start();
error_reporting(0);
session_start();
$docid = $_SESSION['user_id'];
require_once("../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();
ob_start();
$_SESSION['visit_date']		=	$_POST["day_val"];
if(!empty($_POST["day_val"]))
{
	$day_val	=	date('D', strtotime($_POST["day_val"]));
	$GetTiming	= mysqlSelect("*","seven_days as a left join doc_time_set as b on a.day_id=b.day_id","b.doc_id='".$docid."' and b.hosp_id='".$_SESSION['login_hosp_id']."' and a.da_name='".$day_val."'","","","","");
?>
	<option value="">Select Timing</option>
<?php
	foreach($GetTiming as $TimeList)
	{
		$chkDocTimeSlot = mysqlSelect("num_patient_hour","doc_appointment_slots","doc_id='".$docid."' and doc_type='1' and hosp_id = '".$_SESSION['login_hosp_id']."'","","","","");
		
		$countPrevAppBook = mysqlSelect("COUNT(id) as Appoint_Count","appointment_transaction_detail","pref_doc='".$docid."' and hosp_id = '".$_SESSION['login_hosp_id']."' and Visiting_date = '".$_POST["day_val"]."' and Visiting_time = '".$TimeList["time_id"]."'","","","","");
		
		$Timing= mysqlSelect("*","timings","Timing_id='".$TimeList["time_id"]."'","","","","");
		
		if($countPrevAppBook[0]['Appoint_Count']<$chkDocTimeSlot[0]['num_patient_hour'])
		{
			?>
			<option value="<?php echo $Timing[0]["Timing_id"]; ?>"><?php echo $Timing[0]["Timing"]; ?></option>
			<?php
		}
		else
		{ 
			?>
			<option value="" >Slot unavailable</option>
			
<?php  
		}
	}
}
?>
