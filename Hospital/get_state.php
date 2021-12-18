<?php
ob_start();
error_reporting(0);
session_start();

require_once("../classes/querymaker.class.php");

ob_start();
$Country_name=$_POST["country_name"];

?>

<?php
if(!empty($_POST["country_name"])) {
	$GetState= mysqlSelect("*","countries as a left join states as b on a.country_id=b.country_id","a.country_name='".$Country_name."'","b.state_name asc","","","");
?>
	<option value="">Select State</option>
<?php
	foreach($GetState as $StateList) {
	
?>
	<option value="<?php echo $StateList["state_name"]; ?>"><?php echo $StateList["state_name"]; ?></option>
<?php
	}
}
else
{	
	$GetState= mysqlSelect("*","countries as a left join states as b on a.country_id=b.country_id","a.country_name=India","b.state_name asc","","","");
	foreach($GetState as $StateList) {
	?>
	<option value="">Select State</option>
	<option value="<?php echo $StateList["state_name"]; ?>"><?php echo $StateList["state_name"]; ?></option>
<?php
}
}
?>
