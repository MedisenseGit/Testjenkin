<?php
ob_start();
error_reporting(0); 
session_start();

$doc_id=$_GET['doc_id'];

$admin_id = $_SESSION['user_id'];
if(empty($admin_id)){
	header("Location:user-login");
}
require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
	
	$chkDoc = $objQuery->mysqlSelect("*","add_favourite_doctor","doc_id='".$doc_id."' and user_id='".$admin_id ."'","","","","");
	$getDoc = $objQuery->mysqlSelect("ref_name","referal","ref_id='".$doc_id."'","","","","");
	
	if($chkDoc==true){
		$errormsg= $getDoc[0]['ref_name']." has already added in your favourite list";
	} else {
  $arrFields = array();
  $arrValues = array();

	$arrFields[]= 'user_id';
	$arrValues[]= $admin_id;
	$arrFields[]= 'doc_id';
	$arrValues[]= $doc_id;
	$arrFields[]= 'user_type';
	$arrValues[]= "1";
	$addfavour=$objQuery->mysqlInsert('add_favourite_doctor',$arrFields,$arrValues); 
  
  
	
	$successmsg= $getDoc[0]['ref_name']." has been added to your favourite list";
	}
?>

					<?php if(isset($successmsg)){ ?>
					<div class="alert alert-success alert-dismissable">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                               <strong><?php echo $successmsg; ?> </strong>
                     </div>
					<?php } else { ?>
					<div class="alert alert-danger alert-dismissable">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                               <strong><?php echo $errormsg; ?> </strong>
                     </div>
					<?php } ?>
		