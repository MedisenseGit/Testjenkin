<?php
require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
ob_start();
if(!empty($_POST["specification_id"])) {
	$SuperSpecName= $objQuery->mysqlSelect("*","super_specialization","spec_id='".$_POST['specification_id']."'","","","","");
?>
	<option value="">Select Sub Specialization</option>
<?php
	foreach($SuperSpecName as $List) {
?>
	<option value="<?php echo $List["super_spec_id"]; ?>"><?php echo $List["super_spec_name"]; ?></option>
<?php
	}
}
?>
