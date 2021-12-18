<?php ob_start();
 error_reporting(0);
 session_start(); 

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
ob_start();

//TO CHECK COMPANY USER WORK ASSIGN STATUS
if(isset($_POST['send'])){
	
	$chkPatInfo = $objQuery->mysqlSelect("*","referal","ref_id between 1690 and 1727","","","","");

	foreach($chkPatInfo as $patList) {
		if(!empty($patList['doc_photo'])){
			$doc_image="doc_img/".$patList['doc_photo'];
		}
		else{
			$doc_image="assets/img/doc_icon.jpg";
		}
		$getSpec = $objQuery->mysqlSelect("*","specialization","spec_id='".$patList['doc_spec']."'","","","","");
		echo $display='{<br>"docname": "'.$patList['ref_name'].'",<br>
    "briefinfo": "Specialization: '.$getSpec[0]['spec_name'].' Location : '.$patList['ref_address'].'",<br>
	"location": "'.$patList['ref_address'].'",<br>
        "yearexp": "'.$patList['ref_exp'].'",<br>
		"docid": "'.$patList['ref_id'].'",<br>
    "specialize": "'.$getSpec[0]['spec_name'].'",<br>
	 "specials": [<br>
     "'.$getSpec[0]['spec_name'].'","'.$patList['doc_interest'].'"<br>
    ],<br>
   "docimage": "'.$doc_image.'",<br>
    "state": [<br>
     
      "'.$patList['doc_state'].'"<br>
    ],<br>
   
    "id": 4<br>
  },<br>';
				
	}
	
				
	
}

?>

<form method="post" name="sendMail" >
<input type="submit" name="send" />
</form>