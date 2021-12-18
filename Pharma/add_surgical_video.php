<?php 
ob_start();
session_start();
error_reporting(0);  
require_once("../classes/querymaker.class.php");


//$getClientIp=$_SERVER['REMOTE_ADDR'];


$admin_id = $_SESSION['user_id'];
if(empty($admin_id)){
	header("Location:login");
}

$docSpec = mysqlSelect("a.ref_name as Doc_Name,b.spec_name as specialization","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$admin_id."'","","");

?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add Blogs</title>

    <?php include_once('support.php'); ?>
	<link href="../assets/css/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet">
</head>

<body>

    <div id="wrapper">

    <?php include_once('sidemenu.php'); ?>

        <div id="page-wrapper" class="gray-bg">
        <?php include_once('header_top.php'); ?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>Add Video</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="Home">Home</a>
                        </li>
                        <li>
                            <a href="Blog-Surgical-List">Video List</a>
                        </li>
                        <li class="active">
                            <strong>Add Video</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-2">

                </div>
            </div>
        <div class="wrapper wrapper-content">

            <div class="row">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">
				 <div class="ibox-content">
				 <form enctype="multipart/form-data" method="post" action="add_details.php" >
					
                    <div class="panel-body">
					<div class="form-group">
                         <label class="col-sm-2 control-label">Video Title <span class="required">*</span></label>

                                    <div class="col-sm-10"><input type="text" name="video_title" required="required" class="form-control"></div>
					</div><br><br>
					<div class="form-group">
                         <label class="col-sm-2 control-label">Add Youtube Video Url <span class="required">*</span></label>

                                    <div class="col-sm-10"><input type="text" name="video_link" required="required" placeholder="Enter Youtube Url - www.youtube.com/watch?v=rdmycu13Png" class="form-control"></div>
					</div><br><br>
					<div class="form-group">
									 <label class="col-sm-2 control-label">Video Description <span class="required">*</span></label>

                                    <div class="col-sm-10 m-b-xl"><textarea class="form-control"  name="video_Description" required="required" rows="5"></textarea></div>
                    </div>
					<div class="form-group m-t-xl">
					 <label class="col-sm-2 control-label">Tags </label>

					 <div class="col-sm-10"><input class="tagsinput form-control" type="text" name="searchTags" value="<?php echo $docSpec[0]['Doc_Name'].",".$docSpec[0]['specialization']; ?>" /></div>
					</div>
					</div>

					<div class="form-group">
								<div class="col-sm-2 pull-right">
								<a href="Blog-Surgical-List" class="btn btn-primary block full-width m-b "><i class="fa fa-arrow-left"></i> BACK</a>
								</div>
								<div class="col-sm-2 pull-right">
								<button type="submit" name="video_publish" class="btn btn-primary block full-width m-b "><i class="fa fa-paper-plane"></i> PUBLISH</button>
								</div>
								
					</div><br><br>
					</form>
                 </div>  
                </div>
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
      <!-- Tags Input -->
    <script src="../assets/js/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js"></script>
	<script>
        $(document).ready(function(){

            $('.tagsinput').tagsinput({
                tagClass: 'label label-primary'
            });
		 });
	</script>

</body>

</html>
