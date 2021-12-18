<?php ob_start();
 error_reporting(0);
 session_start(); 


date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d h:i:s');

$add_days = 365;
$Expiry_Date = date('Y-m-d h:i:s',strtotime($Cur_Date) + (24*3600*$add_days));

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
ob_start();

if(isset($_POST['subname'])){
	$id = $_POST['subid'];
	$txtname = $_POST['subname'];
	$txtcontact = $_POST['subcontact'];
	$txtmail = $_POST['submail'];
	$txtage = $_POST['subage'];
	$txtmerital = $_POST['submerital'];
	$txtqual = $_POST['subqual'];
	$txtprof = $_POST['subprof'];
	$txtgend = $_POST['subgend'];
	$txtcountry = $_POST['subcountry'];
	$txtstate = $_POST['substate'];
	$txtcity = $_POST['subcity'];
	$txtaddress = $_POST['subaddress'];
	$txtpasswd = $_POST['subpasswd'];
	
	$txtfbname1 = $_POST['subfbname1'];
	$txtrel1 = $_POST['subrel1'];
	$txtage1 = $_POST['subage1'];
	
	$txtfbname2 = $_POST['subfbname2'];
	$txtrel2 = $_POST['subrel2'];
	$txtage2 = $_POST['subage2'];
	
	$txtfbname3 = $_POST['subfbname3'];
	$txtrel3 = $_POST['subrel3'];
	$txtage3 = $_POST['subage3'];
	
	$txtfbname4 = $_POST['subfbname4'];
	$txtrel4 = $_POST['subrel4'];
	$txtage4 = $_POST['subage4'];
	
	$txtfbname5 = $_POST['subfbname5'];
	$txtrel5 = $_POST['subrel5'];
	$txtage5 = $_POST['subage5'];
	
	$arrFields = array();
	$arrValues = array();
		
		$arrFields[] = 'sub_name';
		$arrValues[] = $txtname;
		
		$arrFields[] = 'sub_contact';
		$arrValues[] = $txtcontact;
		
		$arrFields[] = 'sub_email';
		$arrValues[] = $txtmail;
		
		$arrFields[] = 'sub_age';
		$arrValues[] = $txtage;
		
		$arrFields[] = 'sub_merital';
		$arrValues[] = $txtmerital;
		
		$arrFields[] = 'sub_gender';
		$arrValues[] = $txtgend;
		
		$arrFields[] = 'sub_qualification';
		$arrValues[] = $txtqual;
		
		$arrFields[] = 'sub_proff';
		$arrValues[] = $txtprof;
		
		$arrFields[] = 'sub_address';
		$arrValues[] = $txtaddress;
		
		$arrFields[] = 'sub_city';
		$arrValues[] = $txtcity;
		
		$arrFields[] = 'sub_state';
		$arrValues[] = $txtstate;
		
		$arrFields[] = 'sub_country';
		$arrValues[] = $txtcountry;
		
			
		if(!empty($txtpasswd)){
		$arrFields[] = 'passwd';
		$arrValues[] = $txtpasswd;
		}
		$updateSub=$objQuery->mysqlUpdate('subscription',$arrFields,$arrValues,"subscribe_id='".$id."'");	
		$getSubscribeFamily= $objQuery->mysqlSelect("*","subscribe_family","subscribe_id='".$id."'","","","","");
		
		$familyrefId1="MED16S".$id."A";
		$familyrefId2="MED16S".$id."B";
		$familyrefId3="MED16S".$id."C";
		$familyrefId4="MED16S".$id."D";
		$familyrefId5="MED16S".$id."E";	
		
		if(!empty($getSubscribeFamily[0]['name']) || !empty($getSubscribeFamily[0]['subfamilyref_id']) || !empty($getSubscribeFamily[0]['relationship']) || !empty($getSubscribeFamily[0]['age'])){
				$arrFields1 = array();
				$arrValues1 = array();
				
				$arrFields1[] = 'name';
				$arrValues1[] = $txtfbname1;
				$arrFields1[] = 'relationship';
				$arrValues1[] = $txtrel1;
				$arrFields1[] = 'age';
				$arrValues1[] = $txtage1;
				
				$usercraete1=$objQuery->mysqlUpdate('subscribe_family',$arrFields1,$arrValues1,"sub_fam_id='".$getSubscribeFamily[0]['sub_fam_id']."'");
		}  else if(empty($getSubscribeFamily[0]['name']) && !empty($txtfbname1)){
				
					
				$arrFields1[] = 'subscribe_id';
				$arrValues1[] = $id;
				$arrFields1[] = 'subfamilyref_id';
				$arrValues1[] = $familyrefId1;
				
				$arrFields1[] = 'name';
				$arrValues1[] = $txtfbname1;
				$arrFields1[] = 'relationship';
				$arrValues1[] = $txtrel1;
				$arrFields1[] = 'age';
				$arrValues1[] = $txtage1;
				
				$usercraete=$objQuery->mysqlInsert('subscribe_family',$arrFields1,$arrValues1);
		}
		
		if(!empty($getSubscribeFamily[1]['name']) || !empty($getSubscribeFamily[1]['subfamilyref_id']) || !empty($getSubscribeFamily[1]['relationship']) || !empty($getSubscribeFamily[1]['age'])){
				$arrFields2 = array();
				$arrValues2 = array();
				
				$arrFields2[] = 'name';
				$arrValues2[] = $txtfbname2;
				$arrFields2[] = 'relationship';
				$arrValues2[] = $txtrel2;
				$arrFields2[] = 'age';
				$arrValues2[] = $txtage2;
				
				$usercraete1=$objQuery->mysqlUpdate('subscribe_family',$arrFields2,$arrValues2,"sub_fam_id='".$getSubscribeFamily[1]['sub_fam_id']."'");
		} else if(empty($getSubscribeFamily[1]['name']) && !empty($txtfbname2)){
				
					
				$arrFields2[] = 'subscribe_id';
				$arrValues2[] = $id;	
				$arrFields2[] = 'subfamilyref_id';
				$arrValues2[] = $familyrefId2;
				$arrFields2[] = 'name';
				$arrValues2[] = $txtfbname2;
				$arrFields2[] = 'relationship';
				$arrValues2[] = $txtrel2;
				$arrFields2[] = 'age';
				$arrValues2[] = $txtage2;
				
				$usercraete=$objQuery->mysqlInsert('subscribe_family',$arrFields2,$arrValues2);
		}
		if(!empty($getSubscribeFamily[2]['name']) || !empty($getSubscribeFamily[2]['subfamilyref_id']) || !empty($getSubscribeFamily[2]['relationship']) || !empty($getSubscribeFamily[2]['age'])){
				$arrFields3 = array();
				$arrValues3 = array();
				
				$arrFields3[] = 'name';
				$arrValues3[] = $txtfbname3;
				$arrFields3[] = 'relationship';
				$arrValues3[] = $txtrel3;
				$arrFields3[] = 'age';
				$arrValues3[] = $txtage3;
				
				$usercraete1=$objQuery->mysqlUpdate('subscribe_family',$arrFields3,$arrValues3,"sub_fam_id='".$getSubscribeFamily[2]['sub_fam_id']."'");
		} else if(empty($getSubscribeFamily[2]['name']) && !empty($txtfbname3)){
				
					
				$arrFields3[] = 'subscribe_id';
				$arrValues3[] = $id;	
				$arrFields3[] = 'subfamilyref_id';
				$arrValues3[] = $familyrefId3;
				$arrFields3[] = 'name';
				$arrValues3[] = $txtfbname3;
				$arrFields3[] = 'relationship';
				$arrValues3[] = $txtrel3;
				$arrFields3[] = 'age';
				$arrValues3[] = $txtage3;
				
				$usercraete=$objQuery->mysqlInsert('subscribe_family',$arrFields3,$arrValues3);
		}
		if(!empty($getSubscribeFamily[3]['name']) || !empty($getSubscribeFamily[3]['subfamilyref_id']) || !empty($getSubscribeFamily[3]['relationship']) || !empty($getSubscribeFamily[3]['age'])){
				$arrFields4 = array();
				$arrValues4 = array();
				
				$arrFields4[] = 'name';
				$arrValues4[] = $txtfbname4;
				$arrFields4[] = 'relationship';
				$arrValues4[] = $txtrel4;
				$arrFields4[] = 'age';
				$arrValues4[] = $txtage4;
				
				$usercraete1=$objQuery->mysqlUpdate('subscribe_family',$arrFields4,$arrValues4,"sub_fam_id='".$getSubscribeFamily[3]['sub_fam_id']."'");
		} else if(empty($getSubscribeFamily[3]['name']) && !empty($txtfbname4)){
				
					
				$arrFields4[] = 'subscribe_id';
				$arrValues4[] = $id;
				$arrFields4[] = 'subfamilyref_id';
				$arrValues4[] = $familyrefId3;				
				$arrFields4[] = 'name';
				$arrValues4[] = $txtfbname4;
				$arrFields4[] = 'relationship';
				$arrValues4[] = $txtrel4;
				$arrFields4[] = 'age';
				$arrValues4[] = $txtage4;
				
				$usercraete=$objQuery->mysqlInsert('subscribe_family',$arrFields4,$arrValues4);
		}
		if(!empty($getSubscribeFamily[4]['name']) || !empty($getSubscribeFamily[4]['subfamilyref_id']) || !empty($getSubscribeFamily[4]['relationship']) || !empty($getSubscribeFamily[4]['age'])){
				$arrFields5 = array();
				$arrValues5 = array();
				
				$arrFields5[] = 'name';
				$arrValues5[] = $txtfbname5;
				$arrFields5[] = 'relationship';
				$arrValues5[] = $txtrel5;
				$arrFields5[] = 'age';
				$arrValues5[] = $txtage5;
				
				$usercraete1=$objQuery->mysqlUpdate('subscribe_family',$arrFields5,$arrValues5,"sub_fam_id='".$getSubscribeFamily[4]['sub_fam_id']."'");
		} else if(empty($getSubscribeFamily[4]['name']) && !empty($txtfbname5)){
				
					
				$arrFields5[] = 'subscribe_id';
				$arrValues5[] = $id;	
				$arrFields5[] = 'subfamilyref_id';
				$arrValues5[] = $familyrefId5;
				$arrFields5[] = 'name';
				$arrValues5[] = $txtfbname5;
				$arrFields5[] = 'relationship';
				$arrValues5[] = $txtrel5;
				$arrFields5[] = 'age';
				$arrValues5[] = $txtage5;
				
				$usercraete=$objQuery->mysqlInsert('subscribe_family',$arrFields5,$arrValues5);
		}
}
		
?>


