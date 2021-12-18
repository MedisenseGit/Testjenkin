<?php 
ob_start();
session_start();
error_reporting(0);  
require_once("../classes/querymaker.class.php");


//$getClientIp=$_SERVER['REMOTE_ADDR'];

include('functions.php');
$admin_id = $_SESSION['user_id'];
if(empty($admin_id)){
	header("Location:login");
}

if(isset($_GET['s'])){
	$params     = explode(" ", $_GET['s']);
	$postid1 = $params[0];
	$postid2 = $params[1];
	
	
	$getFeature = mysqlSelect("a.ref_id as Ref_Id,a.ref_name as Ref_Name,a.doc_type_val as Doc_Type,a.ref_exp as Ref_Exp,a.ref_address as Ref_Address,a.doc_state as Doc_State,a.doc_photo as Doc_Image,b.spec_name as Specialization","referal as a inner join specialization as b on a.doc_spec=b.spec_id inner join doctor_hosp as c on c.doc_id=a.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","((a.doc_spec!=555 and a.anonymous_status!=1) and ((a.ref_name LIKE '%".$_GET['s']."%' or a.doc_interest LIKE '%".$_GET['s']."%' or a.doc_research LIKE '%".$_GET['s']."%' or a.doc_contribute LIKE '%".$_GET['s']."%' or a.doc_pub LIKE '%".$_GET['s']."%' or d.hosp_name LIKE '%".$_GET['s']."%') or ((b.spec_name LIKE '%".$postid1."%' or a.ref_name LIKE '%".$postid1."%' or a.ref_address LIKE '%".$postid1."%' or a.doc_state LIKE '%".$postid1."%') and (a.ref_address LIKE '%".$postid2."%' or a.doc_state LIKE '%".$postid2."%' or b.spec_name LIKE '%".$postid2."%'))))","a.doc_type_val asc","","","");	
	$countRslt = mysqlSelect("COUNT(a.ref_id) as count","referal as a inner join specialization as b on a.doc_spec=b.spec_id inner join doctor_hosp as c on c.doc_id=a.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","((a.doc_spec!=555 and a.anonymous_status!=1) and ((a.ref_name LIKE '%".$_GET['s']."%' or a.doc_interest LIKE '%".$_GET['s']."%' or a.doc_research LIKE '%".$_GET['s']."%' or a.doc_contribute LIKE '%".$_GET['s']."%' or a.doc_pub LIKE '%".$_GET['s']."%' or d.hosp_name LIKE '%".$_GET['s']."%') or ((b.spec_name LIKE '%".$postid1."%' or a.ref_name LIKE '%".$postid1."%' or a.ref_address LIKE '%".$postid1."%' or a.doc_state LIKE '%".$postid1."%') and (a.ref_address LIKE '%".$postid2."%' or a.doc_state LIKE '%".$postid2."%' or b.spec_name LIKE '%".$postid2."%'))))","a.doc_type_val asc","","","");
	}
	else {
			if(!isset($_GET['start'])) {
			$start = 0;
			}else{
			$start = $_GET['start'];
			}

			$eu = ($start - 0); 
			$limit = 50;         // No of records to be shown per page.
			$this1 = $eu + $limit; 
			$back = $eu - $limit; 
			$next = $eu + $limit;
	$getMyConnection = mysqlSelect("a.ref_id as Ref_Id,a.ref_name as Ref_Name,a.doc_type_val as Doc_Type,a.ref_exp as Ref_Exp,a.ref_address as Ref_Address,a.doc_state as Doc_State,a.doc_photo as Doc_Image,b.spec_name as Specialization","referal as a inner join specialization as b on a.doc_spec=b.spec_id","a.doc_spec!=555 and a.anonymous_status!=1","a.doc_type_val asc","","","$eu, $limit");
	$pag_result = mysqlSelect("*","referal as a inner join specialization as b on a.doc_spec=b.spec_id","(a.doc_spec!=555 and a.anonymous_status!=1)","a.doc_type_val asc","");
	$pageing = firstPaging($pag_result,$limit,$back,$next,$eu,$field,$type2);
	$arrPage = explode("-",$pageing);
  
	}
 


?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Favorite Doctors</title>

    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="../assets/css/animate.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">

</head>

<body>

    <div id="wrapper">

    <?php include_once('sidemenu.php'); ?>

        <div id="page-wrapper" class="gray-bg">
       <?php include_once('header_top.php'); ?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-9">
                    <h2>My Connections</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="Home">Home</a>
                        </li>
                        
                        <li class="active">
                            <strong>My Connections</strong>
                        </li>
                    </ol>
                </div>
            </div>
        <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
		<?php foreach($getMyConnection as $getList){ 
			if(!empty($getList)){ ?>
            <div class="col-lg-3">
                <div class="contact-box center-version">

                    <a href="Doctor-Profile?d=<?php echo md5($getList['Ref_Id']); ?>&start=<?php echo $_GET['start']; ?>">
						 <?php if(!empty($getList['Doc_Image'])){ ?>
                        <img alt="image" class="img-circle" src="../Admin/docProfilePic/<?php echo $getList['Ref_Id']; ?>/<?php echo $getList['Doc_Image']; ?>">
						<?php }  else { ?>	
						<img alt="image" class="img-circle" src="../assets/img/anonymous-profile.png">
						<?php  } ?>

                        <h3 class="m-b-xs"><strong><?php echo $getList['Ref_Name']; ?></strong></h3>

                        <div class="font-bold"><?php echo $getList['Specialization']; ?></div>
                        <address class="m-t-md">
                            <strong><?php echo $getList['Ref_Exp']; ?> Years Exp.</strong><br>
                            <?php echo $getList['Ref_Address'].", ".$getList['Doc_State']; ?><br>
                            <!--<abbr title="Phone">P:</abbr> (123) 456-7890-->
                        </address>

                    </a>
                    <div class="contact-box-footer">
                        <div class="m-t-xs btn-group">
						<div class="tooltip-demo">
                           <!-- <a class="btn btn-xs btn-white"><i class="fa fa-phone"></i> Call </a>-->
                            <a href="Doctor-Profile?d=<?php echo md5($getList['Ref_Id']); ?>&start=<?php echo $_GET['start']; ?>" class="btn btn-xs btn-white" data-toggle="tooltip" data-placement="top" title="Send case to doctor"><i class="fa fa-wheelchair"></i> Send Case</a>
                            <a class="btn btn-xs btn-white" data-toggle="tooltip" data-placement="top" title="Add to favorite list"><i class="fa fa-user-md"></i> Favorite</a>
                        </div>
						</div>
                    </div>

                </div>
            </div>
            <?php }
			}	?>


        </div>
        </div>
         <?php include_once('footer.php'); ?>

        </div>
        </div>

    <!-- Mainly scripts -->
    <script src="../assets/js/jquery-3.1.1.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="../assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="../assets/js/inspinia.js"></script>
    <script src="../assets/js/plugins/pace/pace.min.js"></script>


</body>

</html>
