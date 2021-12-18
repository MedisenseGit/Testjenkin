<?php 
ob_start();
session_start();
error_reporting(0);  
require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

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
		$getMappedFeature = $objQuery->mysqlSelect("partner_id","mapping_hosp_referrer","partner_id='".$admin_id."'","","","","");
					
		if($getMappedFeature==true){
		$getMyConnection = $objQuery->mysqlSelect("a.ref_id as Ref_Id,a.ref_name as Ref_Name,a.doc_type_val as Doc_Type,a.ref_exp as Ref_Exp,a.ref_address as Ref_Address,a.doc_state as Doc_State,a.doc_photo as Doc_Image,b.spec_name as Specialization","referal as a inner join specialization as b on b.spec_id=a.doc_spec inner join doctor_hosp as c on c.doc_id=a.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id inner join mapping_hosp_referrer as e on e.hosp_id=c.hosp_id","((a.doc_spec!=555 and a.anonymous_status!=1 and e.partner_id='".$admin_id."') and ((a.ref_name LIKE '%".$_GET['s']."%' or a.doc_interest LIKE '%".$_GET['s']."%' or a.doc_research LIKE '%".$_GET['s']."%' or a.doc_contribute LIKE '%".$_GET['s']."%' or a.doc_pub LIKE '%".$_GET['s']."%' or d.hosp_name LIKE '%".$_GET['s']."%') or ((b.spec_name LIKE '%".$postid1."%' or a.ref_name LIKE '%".$postid1."%' or a.ref_address LIKE '%".$postid1."%' or a.doc_state LIKE '%".$postid1."%') and (a.ref_address LIKE '%".$postid2."%' or a.doc_state LIKE '%".$postid2."%' or b.spec_name LIKE '%".$postid2."%'))))","a.doc_type_val asc","","","");
		}
		else{
		$getMyConnection = $objQuery->mysqlSelect("a.ref_id as Ref_Id,a.ref_name as Ref_Name,a.doc_type_val as Doc_Type,a.ref_exp as Ref_Exp,a.ref_address as Ref_Address,a.doc_state as Doc_State,a.doc_photo as Doc_Image,b.spec_name as Specialization","referal as a inner join specialization as b on b.spec_id=a.doc_spec inner join doctor_hosp as c on c.doc_id=a.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","((a.doc_spec!=555 and a.anonymous_status!=1) and ((a.ref_name LIKE '%".$_GET['s']."%' or a.doc_interest LIKE '%".$_GET['s']."%' or a.doc_research LIKE '%".$_GET['s']."%' or a.doc_contribute LIKE '%".$_GET['s']."%' or a.doc_pub LIKE '%".$_GET['s']."%' or d.hosp_name LIKE '%".$_GET['s']."%') or ((b.spec_name LIKE '%".$postid1."%' or a.ref_name LIKE '%".$postid1."%' or a.ref_address LIKE '%".$postid1."%' or a.doc_state LIKE '%".$postid1."%') and (a.ref_address LIKE '%".$postid2."%' or a.doc_state LIKE '%".$postid2."%' or b.spec_name LIKE '%".$postid2."%'))))","a.doc_type_val asc","","","");
		}	
	//$countRslt = $objQuery->mysqlSelect("COUNT(a.ref_id) as count","referal as a inner join specialization as b on a.doc_spec=b.spec_id inner join doctor_hosp as c on c.doc_id=a.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","((a.doc_spec!=555 and a.anonymous_status!=1) and ((a.ref_name LIKE '%".$_GET['s']."%' or a.doc_interest LIKE '%".$_GET['s']."%' or a.doc_research LIKE '%".$_GET['s']."%' or a.doc_contribute LIKE '%".$_GET['s']."%' or a.doc_pub LIKE '%".$_GET['s']."%' or d.hosp_name LIKE '%".$_GET['s']."%') or ((b.spec_name LIKE '%".$postid1."%' or a.ref_name LIKE '%".$postid1."%' or a.ref_address LIKE '%".$postid1."%' or a.doc_state LIKE '%".$postid1."%') and (a.ref_address LIKE '%".$postid2."%' or a.doc_state LIKE '%".$postid2."%' or b.spec_name LIKE '%".$postid2."%'))))","a.doc_type_val asc","","","");
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
//	$getMyConnection = $objQuery->mysqlSelect("a.ref_id as Ref_Id,a.ref_name as Ref_Name,a.doc_type_val as Doc_Type,a.ref_exp as Ref_Exp,a.ref_address as Ref_Address,a.doc_state as Doc_State,a.doc_photo as Doc_Image,b.spec_name as Specialization","referal as a inner join specialization as b on a.doc_spec=b.spec_id","a.doc_spec!=555 and a.anonymous_status!=1","a.doc_type_val asc","","","$eu, $limit");
	
	$getMappedFeature = $objQuery->mysqlSelect("partner_id","mapping_hosp_referrer","partner_id='".$admin_id."'","","","","");
					
		if($getMappedFeature==true){
			$getMyConnection = $objQuery->mysqlSelect("DISTINCT(a.ref_id) as Ref_Id,a.ref_name as Ref_Name,a.doc_type_val as Doc_Type,a.ref_exp as Ref_Exp,a.ref_address as Ref_Address,a.doc_state as Doc_State,a.doc_photo as Doc_Image,b.spec_name as Specialization","referal as a inner join specialization as b on b.spec_id=a.doc_spec inner join doctor_hosp as c on c.doc_id=a.ref_id inner join mapping_hosp_referrer as d on d.hosp_id=c.hosp_id","a.doc_spec!=555 and a.anonymous_status!=1 and d.partner_id='".$admin_id."'","a.doc_type_val asc","","","0,50");
		}
		else{
			$getMyConnection = $objQuery->mysqlSelect("a.ref_id as Ref_Id,a.ref_name as Ref_Name,a.doc_type_val as Doc_Type,a.ref_exp as Ref_Exp,a.ref_address as Ref_Address,a.doc_state as Doc_State,a.doc_photo as Doc_Image,b.spec_name as Specialization","referal as a inner join specialization as b on b.spec_id=a.doc_spec inner join doctor_hosp as c on c.doc_id=a.ref_id","a.doc_spec!=555 and a.anonymous_status!=1","a.doc_type_val asc","","","0,24");
			
		}			
	$pag_result = $objQuery->mysqlSelect("*","referal as a inner join specialization as b on a.doc_spec=b.spec_id","(a.doc_spec!=555 and a.anonymous_status!=1)","a.doc_type_val asc","");
	$pageing = firstPaging($pag_result,$limit,$back,$next,$eu,$field,$type2);
	$arrPage = explode("-",$pageing);
  
	}
 

//Filter Option
if(isset($_POST['filterStatus'])){
$filter_val = $_POST['filter_val'];
?>
<SCRIPT LANGUAGE="JavaScript">	
	window.location.href="<?php echo $_SERVER['PHP_SELF'];?>?f="+<?php echo $filter_val; ?>;
	</SCRIPT>
<?php
}

if(isset($_GET['f'])){
	
	if($_GET['f']==0)
	{ 
		header("Location:".$_SERVER['PHP_SELF']);
	}
	

}

?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Favourite Doctors</title>

    <?php include_once('support.php'); ?>
	<link href="../assets/css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">	
	<link href="../assets/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">
	<script language="JavaScript" src="js/status_validation.js"></script>
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
				<!--<div class="col-lg-2 mgTop">
				<form method="post" name="frmWantContribute">
				<input type="hidden" name="partner_id" value=""/>
				<input type="hidden" name="cmdContribute" value=""/>
					<button type="button" class="btn btn-w-m btn-success" onclick="return cmdWantContributor(<?php echo $admin_id; ?>);"><i class="fa fa-user-md"></i> Want to be a Contributor?</button>
                 </form>               
			   </div>-->
            </div>
        <div class="wrapper wrapper-content animated fadeInRight">
        <div id="addFavour"></div>
		<div class="ibox-content" >
			<div class="m-b-lg">
							<div class="row">
                                <div class="col-sm-3 m-b-xs">
								
								
								<select class="chosen-select" name="slctStatus" onchange="return filterBy(this.value);">
								<option value="0" selected>Select Specialization</option>
								<option value="555" >All</option>
		<?php $DeptName= $objQuery->mysqlSelect("*","specialization","","spec_name asc","","","");
		$i=30;
		foreach($DeptName as $DeptList){
			if($DeptList['spec_id']==$_GET['f']){ ?> 
		<option value="<?php echo stripslashes($DeptList['spec_id']);?>" selected/><?php echo stripslashes($DeptList['spec_name']);?></option>
		<?php 
			}?>

			<option value="<?php echo stripslashes($DeptList['spec_id']);?>" /><?php echo stripslashes($DeptList['spec_name']);?></option>
		<?php
				$i++;
		}?>  
                                </select>
								
                                </div>
                                  <form method="post" name="frmSrchBox" action="add_details.php">
								<input type="hidden" name="postTextSrchCmd" value="" />
								<input type="hidden" name="postTextSrch" value="" />
                                <div class="col-sm-4 pull-right">
                                    <div class="input-group"><input type="text" placeholder="Search" class="input-sm form-control" name="postTextSrch" required="required"> <span class="input-group-btn">
                                        <button type="submit" name="postTextSrchCmd" class="btn btn-sm btn-primary"> Go!</button> </span></div>
                                </div>
								</form>
                            </div>
                                <br>
								<div class="row" id="allConnection" >
		<?php foreach($getMyConnection as $getList){ 
			if(!empty($getList)){ ?>
            <div class="col-lg-3" style="padding-bottom:30px;" >
                <div class="contact-box center-version">

                    <a href="Doctor-Profile?d=<?php echo md5($getList['Ref_Id']); ?>&start=<?php echo $_GET['start']; ?>">
						 <?php if(!empty($getList['Doc_Image'])){ ?>
                        <img alt="image" class="img-circle" src="../Doc/<?php echo $getList['Ref_Id']; ?>/<?php echo $getList['Doc_Image']; ?>">
						<?php }  else { ?>	
						<img alt="image" class="img-circle" src="../assets/img/anonymous-profile.png">
						<?php  } ?>

                        <h4 class="m-b-xs"><strong><?php echo $getList['Ref_Name']; ?></strong></h4>

                        <div class="font-bold"><small><?php echo substr($getList['Specialization'],0,21); ?></small></div>
                        <address class="m-t-md">
                            <strong><?php echo $getList['Ref_Exp']; ?> Years Exp.</strong><br>
                            <small><?php echo $getList['Ref_Address']; ?></small><br>
                            <!--<abbr title="Phone">P:</abbr> (123) 456-7890-->
                        </address>

                    </a>
                    <div class="contact-box-footer">
                        <div class="m-t-xs btn-group">
						<div class="tooltip-demo">
                           <!-- <a class="btn btn-xs btn-white"><i class="fa fa-phone"></i> Call </a>-->
                            <a href="Doctor-Profile?d=<?php echo md5($getList['Ref_Id']); ?>&start=<?php echo $_GET['start']; ?>" class="btn btn-xs btn-white" data-toggle="tooltip" data-placement="top" title="Send case to doctor"><i class="fa fa-wheelchair"></i> Send Case</a>
                            <a href="javascript:void(0)" onclick="return addFavour(<?php echo $getList['Ref_Id']; ?>);" class="btn btn-xs btn-white" data-toggle="tooltip" data-placement="top" title="Add to favourite list"><i class="fa fa-user-md"></i> Favourite</a>
                        </div>
						</div>
                    </div>

                </div>
            </div>
            <?php }
			}	?>

			
        </div>
		<div id="serConnection"></div>
		
		
		</div>
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
	<!-- Chosen -->
    <script src="../assets/js/plugins/chosen/chosen.jquery.js"></script>
	<script>
       
        $('.chosen-select').chosen({width: "100%"});



    </script>
	<!-- Switchery -->
   <script src="../assets/js/plugins/switchery/switchery.js"></script>


</body>

</html>
