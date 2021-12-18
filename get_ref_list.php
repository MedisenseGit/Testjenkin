<?php
require_once "config.php";
$q = strtolower($_GET["q"]);
$Tlk=$_GET['Taluk'];
if (!$q) return;

$sql = "select DISTINCT Bus_PrdList as Bus_PrdList from dm_buslist where Bus_District='1' and Bus_Taluk='".$Tlk."' and  Bus_PrdList LIKE '%$q%'";
$rsd = mysql_query($sql);
while($rs = mysql_fetch_array($rsd)) {
	$cname = $rs['Bus_PrdList'];
	echo "$cname\n";
}
?>