<?php ob_start();
 error_reporting(0);
 session_start(); 


date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d h:i:s');

require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
ob_start();



$name = ''; $type = ''; $size = ''; $error = '';
	function compress_image($source_url, $destination_url, $quality) {

		$info = getimagesize($source_url);

    		if ($info['mime'] == 'image/jpeg')
        			$image = imagecreatefromjpeg($source_url);

    		elseif ($info['mime'] == 'image/gif')
        			$image = imagecreatefromgif($source_url);

   		elseif ($info['mime'] == 'image/png')
        			$image = imagecreatefrompng($source_url);

    		imagejpeg($image, $destination_url, $quality);
		return $destination_url;
	}

if(isset($_POST['submit'])){

	$docname = addslashes($_POST['doc_name']);
	$docspec = addslashes($_POST['specialization']);
	$docgend = $_POST['doc_gender'];
	$docage = addslashes($_POST['doc_age']);
	$doctqual = addslashes($_POST['doc_qual']);
	$docexp = addslashes($_POST['doc_exp']);
	$docmail = addslashes($_POST['doc_mail']);
	$docweb = addslashes($_POST['doc_website']);
	$doccontact = addslashes($_POST['doc_contact']);
	$doccountry = addslashes($_POST['doc_country']);
	$docstate = addslashes($_POST['doc_state']);
	$doccity = addslashes($_POST['doc_city']);	
	$dochosp1 = addslashes($_POST['doc_hosp1']);
	$dochosp2 = addslashes($_POST['doc_hosp2']);
	$dochosp3 = addslashes($_POST['doc_hosp3']);	
	$docexpert = addslashes($_POST['doc_expert']);
	$doccontribute = addslashes($_POST['doc_contrubute']);
	$docresearch = addslashes($_POST['doc_research']);
	$docpublication = addslashes($_POST['doc_publication']);
	$doconlinecharge = addslashes($_POST['online_charge']);
	$docinpercharge = addslashes($_POST['inper_charge']);
	$docconscharge = addslashes($_POST['cons_charge']);
	$docImage = addslashes($_FILES['txtPhoto']['name']);
	
	$respond=0;
	if($respond==0)
	{
	$arrFields = array();
	$arrValues = array();
		
		$arrFields[] = 'ref_name';
		$arrValues[] = $docname;
		$arrFields[] = 'doc_spec';
		$arrValues[] = $docspec;
		$arrFields[] = 'ref_mail';
		$arrValues[] = $docmail;
		$arrFields[] = 'contact_num';
		$arrValues[] = $doccontact;		
		$arrFields[] = 'ref_web';
		$arrValues[] = $docweb;
		$arrFields[] = 'doc_gen';
		$arrValues[] = $docgend;
		$arrFields[] = 'doc_age';
		$arrValues[] = $docage;
		$arrFields[] = 'doc_qual';
		$arrValues[] = $doctqual;
		$arrFields[] = 'doc_city';
		$arrValues[] = $doccity;
		$arrFields[] = 'doc_state';
		$arrValues[] = $docstate;
		$arrFields[] = 'doc_country';
		$arrValues[] = $doccountry;
		$arrFields[] = 'ref_address';
		$arrValues[] = $doccity;
		$arrFields[] = 'cons_hosp_address1';
		$arrValues[] = $dochosp1;
		$arrFields[] = 'cons_hosp_address2';
		$arrValues[] = $dochosp2;
		$arrFields[] = 'cons_hosp_address3';
		$arrValues[] = $dochosp3;
		$arrFields[] = 'ref_exp';
		$arrValues[] = $docexp;
		$arrFields[] = 'doc_interest';
		$arrValues[] = $docexpert;
		$arrFields[] = 'doc_research';
		$arrValues[] = $docresearch;
		$arrFields[] = 'doc_contribute';
		$arrValues[] = $doccontribute;
		$arrFields[] = 'doc_pub';
		$arrValues[] = $docpublication;
		$arrFields[] = 'in_op_cost';
		$arrValues[] = $docinpercharge;
		$arrFields[] = 'on_op_cost';
		$arrValues[] = $doconlinecharge;
		$arrFields[] = 'cons_charge';
		$arrValues[] = $docconscharge;
		if(!empty($_FILES['txtPhoto']['name'])){
		$arrFields[] = 'doc_photo';
		$arrValues[] = $docImage;
		}
			
		$patientRef=$objQuery->mysqlUpdate('referal',$arrFields,$arrValues,"ref_id='".$_POST['docId']."'");
		
		$id = $_POST['docId'];
		
		//UPLOAD COMPRESSED IMAGE
		if ($_FILES["txtPhoto"]["error"] > 0) {
        			$error = $_FILES["txtPhoto"]["error"];
    		} 
    		else if (($_FILES["txtPhoto"]["type"] == "image/gif") || 
			($_FILES["txtPhoto"]["type"] == "image/jpeg") || 
			($_FILES["txtPhoto"]["type"] == "image/png") || 
			($_FILES["txtPhoto"]["type"] == "image/pjpeg")) {
			
			 $uploaddirectory = realpath("../Doc");
			 $uploaddir = $uploaddirectory . "/" .$id;
			 
			 /*Checking whether folder with category id already exist or not. */
			if (file_exists($uploaddir)) {
				//echo "The file $uploaddir exists";
				} else {
				$newdir = mkdir($uploaddirectory . "/" . $id, 0777);
			}
			 
			 
        			$url = $uploaddir.'/'.$_FILES["txtPhoto"]["name"];

        			$filename = compress_image($_FILES["txtPhoto"]["tmp_name"], $url, 40);
        			$buffer = file_get_contents($url);

    		}else {
        			$error = "Uploaded image should be jpg or gif or png";
    		}
		
			//Send value to remote server
					
					$docEncyId=$_POST['docEncyId'];
					$url_page = 'get_refer_val.php';
					
					$url = "https://medisensehealth.com/CRM/";
					$url .= rawurlencode($url_page);
					$post .= "&docid=" . urlencode($id);
					$post .= "&docname=" . urlencode($docname);
					$post .= "&docgend=" . urlencode($docgend);
					$post .= "&docage=" . urlencode($docage);
					$post .= "&dochosp=" . urlencode($slctHosp);
					$post .= "&doccity=" . urlencode($doccity);
					$post .= "&docstate=" . urlencode($docstate);
					$post .= "&doccountry=" . urlencode($doccountry);
					$post .= "&dochosp1=" . urlencode($dochosp1);
					$post .= "&dochosp2=" . urlencode($dochosp2);
					$post .= "&dochosp3=" . urlencode($dochosp3);
					$post .= "&docspec=" . urlencode($docspec);
					$post .= "&docqual=" . urlencode($doctqual);
					$post .= "&docexp=" . urlencode($docexp);
					$post .= "&docmobile=" . urlencode($doccontact);
					$post .= "&docemail=" . urlencode($docmail);
					$post .= "&docweb=" . urlencode($docweb);
					$post .= "&medemail=" . urlencode($txtMedEmail);
					$post .= "&docinterest=" . urlencode($docexpert);
					$post .= "&doccontribute=" . urlencode($doccontribute);
					$post .= "&docresearch=" . urlencode($docresearch);
					$post .= "&docpublication=" . urlencode($docpublication);
					//$post .= "&dockeyword=" . urlencode($txtKeyword);
					$post .= "&docimage=" . urlencode($docImage);
					//$post .= "&compid=" . urlencode($Company_id);
					//$post .= "&anonystate=" . urlencode($anonystate);
					//$post .= "&numop=" . urlencode($txtNumOpinion);
					$post .= "&inopcost=" . urlencode($docinpercharge);
					$post .= "&onopcost=" . urlencode($doconlinecharge);
					$post .= "&conscharge=" . urlencode($docconscharge);
					$post .= "&timestamp=" . urlencode($Cur_Date);
					$post .= "&encykey=" . urlencode($docEncyId);
														
					
					$ch = curl_init (); // setup a curl
					
					curl_setopt($ch, CURLOPT_URL, $url); // set url to send to
					curl_setopt($ch, CURLOPT_POST, true);
					//curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: multipart/form-data"));
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // return data reather than echo
					
						
					$output = curl_exec ($ch);
					echo $output;
					// echo "output".$output;
					
					curl_close ( $ch );
		
		
		
		
		
		
	$sucessMessage="Updated Successfully";
	}
	else {
		$respond=1;
	}
	
header('Location:doctors-profile?respond='.$respond.'&ency_id='.$_POST['docEncyId']);
}
if(isset($_POST['requestSubmit'])){
	
					$message= stripslashes("<b>".$_POST['docName']."</b> has requested you to resend the profile updation web link, Kindly help to proceed further");
					$url_page = 'med_ref_update_notification.php';
					$url = "https://referralio.com/EMAIL/";
					$url .= rawurlencode($url_page);
					$url .= "?message=".urlencode($message);
					
					$ch = curl_init (); // setup a curl
					
					curl_setopt ( $ch, CURLOPT_URL, $url ); // set url to send to
					
					curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers ); // set custom headers
					
					curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true ); // return data reather than echo
					
					curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false ); // required as godaddy fails
					
					curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false );
					
					$output = curl_exec ( $ch );
					
					// echo "output".$output;
					
					curl_close ( $ch );
					$respond=2;
					header('Location:doctors-profile?respond='.$respond.'&ency_id='.$_POST['docEncyId']);
}