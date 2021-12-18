<?php
ob_start();
error_reporting(0);
session_start();

require_once("../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();
ob_start();
$Country_name=$_POST["country_name"];
//echo $Country_name;
?>

<?php
if(!empty($_POST["country_name"]))
	{
	$GetState= mysqlSelect("*","countries as a left join states as b on a.country_id=b.country_id","a.country_id='".$Country_name."'","b.state_name asc","","","");
?>
	<option value="">Select State</option>
<?php
	foreach($GetState as $StateList) {
	
?>
	<option value="<?php echo $StateList["state_name"]; ?>"><?php echo $StateList["state_name"]; ?></option>
	
	
<?php
	}
	
	
	$GetCountryCode= mysqlSelect("*","countries","country_id='".$Country_name."'","ph_extn asc","","","");
	
	echo"@";
	?>
	
	
<?php
	foreach($GetCountryCode as $CodeList) {
	
?>
	<option value="<?php echo $CodeList["ph_extn"]; ?>">+<?php echo $CodeList["ph_extn"]; ?> </option>
	
	
<?php
	}
	
	
}
else
{	
	$GetState= mysqlSelect("*","countries as a left join states as b on a.country_id=b.country_id","a.country_id=100","b.state_name asc","","","");
	foreach($GetState as $StateList) {
	?>
	<option value="">Select State</option>
	<option value="<?php echo $StateList["state_name"]; ?>"><?php echo $StateList["state_name"]; ?></option>
<?php
		}
		
		echo"@";
		
	$GetCountryCode= mysqlSelect("*","countries","country_id=100","ph_extn asc","","","");
	foreach($GetCountryCode as $CodeList) {
	?>
	
	<option value="<?php echo $CodeList["ph_extn"]; ?>"><?php echo $CodeList["ph_extn"]; ?></option>
<?php
		}
}
?>
