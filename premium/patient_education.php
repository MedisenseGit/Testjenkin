<?php
ob_start();
error_reporting(0); 
session_start();

$admin_id = $_SESSION['user_id'];
//Get the page name 
//$request_uri  = str_replace("", "", $_SERVER['REQUEST_URI']);
include('functions.php');
if(empty($admin_id)){
	header("Location:index.php");
}

require_once("../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();
						
$allRecord = mysqlSelect("*","patient_education","doc_id='".$admin_id."' and doc_type=1","edu_id desc","","","");

?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Payments</title>
	<?php include_once('support.php'); ?>
    <script language="JavaScript" src="js/status_validationJs.js"></script>
	<link href="../assets/css/plugins/iCheck/custom.css" rel="stylesheet">
	<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-113157294-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-113157294-1');
</script>
</head>

<body>

    <div id="wrapper">
	<?php include_once('sidemenu.php'); ?>

        <div id="page-wrapper" class="gray-bg">
        <?php include_once('header_top.php'); ?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                     <h2>Patient Education</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="Home">Home</a>
                        </li>
                        
                        <li class="active">
                            <strong>Patient Education</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-2">

                </div>
            </div>

        <div class="wrapper wrapper-content animated fadeInRight ecommerce">


            <div class="ibox-content m-b-sm border-bottom">
			<?php if($_GET['response']=="created-success"){ ?>
			<div class="alert alert-success alert-dismissable">
					<button aria-hidden="true" data-dismiss="alert" class="close"  type="button">×</button>
					<strong>Created successfully </strong>
			</div>
			<?php } ?>
			
			<?php include_once('settings_common_header.php'); ?>		
              <form enctype="multipart/form-data" method="post" action="add_details.php" >
					
                    <div class="panel-body">
					<div class="form-group">
                         <label class="col-sm-2 control-label">Title <span class="required">*</span></label>

                                    <div class="col-sm-10"><input type="text" name="edu_title" required="required" class="form-control"></div>
					</div><br><br>
					<div class="form-group ">
									 <label class="col-sm-2 control-label">Description <span class="required">*</span></label>

                                    <div class="col-sm-10  m-b-xl"><textarea class="form-control" required="required" name="edu_descr" rows="3"></textarea></div>
                    </div>
					
					</div>
					<div class="form-group">
								
								<div class="col-sm-2 pull-right">
								<button type="submit" name="cmdPatEdu" class="btn btn-primary block full-width m-b "><i class="fa fa-plus"></i> ADD</button>
								</div>
								
					</div><br><br>
					</form> 

            </div>

            <div class="row" id="allEducation">
                <div class="col-lg-12">
                    <div class="ibox">
                        <div class="ibox-content" >

                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>

                                        
                                        <th>Title </th>
										<th>Description</th>
										
                                    </tr>
                                    </thead>
                                    <tbody>
									
									<?php 
									if(!empty($allRecord)){
									foreach($allRecord as $list)
									{ 									
									?>
                                    <tr>
                                       
                                        <td><strong><?php echo $list['edu_title']; ?></strong> </td>
										<td><?php echo $list['edu_description']; ?></td>
										<td><td><a href="javascript:void(0)" onclick="return delEducation(<?php echo $list['edu_id']; ?>);" class="btn btn-danger btn-bitbucket btn-xs">
										 <i class="fa fa-trash-o"></i> DELETE</a></td></td>
                                       
                                    </tr>
                                    <?php } 
									} else { 
									?>
									<tr>
                                       
                                        <td colspan="2" class="text-center">No record found </td>
										                                       
                                    </tr>
									<?php } ?>
                                    </tbody>
                                </table>
                            </div>

                        </div>
							
                    </div>
					
                </div>
            </div>
			<div id="afterDelEdu"></div>

        </div>
        <?php include_once('footer.php'); ?>

        </div>
        </div>


<!-- Mainly scripts -->
    <script src="../assets/js/jquery-3.1.1.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="../assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Peity -->
    <script src="../assets/js/plugins/peity/jquery.peity.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="../assets/js/inspinia.js"></script>
    <script src="../assets/js/plugins/pace/pace.min.js"></script>

    <!-- iCheck -->
    <script src="../assets/js/plugins/iCheck/icheck.min.js"></script>

    <!-- Peity -->
    <script src="../assets/js/demo/peity-demo.js"></script>

    <script>
        $(document).ready(function(){
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });
        });
    </script>
<!-- Chosen -->
    <script src="../assets/js/plugins/chosen/chosen.jquery.js"></script>
	<script>
       
        $('.chosen-select').chosen({width: "100%"});



    </script>
	<!-- Switchery -->
   <script src="../assets/js/plugins/switchery/switchery.js"></script>
	
    <!-- iCheck -->
    <script src="../assets/js/plugins/iCheck/icheck.min.js"></script>
        

</body>

</html>
