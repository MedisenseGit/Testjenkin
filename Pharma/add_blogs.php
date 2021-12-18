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
<script src="https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=k4bskbhavbopqhldtcaifwjar0xo7yxzkbb902mi84dto3rj"></script>

  <script type="text/javascript">
  tinymce.init({
    selector: 'textarea',
    theme: 'modern',
    plugins: [
      'advlist autolink link lists charmap hr anchor pagebreak spellchecker',
      'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime nonbreaking',
      'save table contextmenu directionality template paste'
    ],
    toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link | forecolor backcolor emoticons'
  });
  </script>
</head>

<body>

    <div id="wrapper">

    <?php include_once('sidemenu.php'); ?>

        <div id="page-wrapper" class="gray-bg">
        <?php include_once('header_top.php'); ?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>Add Blogs</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="Home">Home</a>
                        </li>
                        <li>
                            <a href="Blog-Surgical-List">Blog Surgical List</a>
                        </li>
                        <li class="active">
                            <strong>Add Blogs</strong>
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
                         <label class="col-sm-2 control-label">Blog Title <span class="required">*</span></label>

                                    <div class="col-sm-10"><input type="text" name="blog_title" required="required" class="form-control"></div>
					</div><br><br>
					<div class="form-group ">
									 <label class="col-sm-2 control-label">Blog description <span class="required">*</span></label>

                                    <div class="col-sm-10  m-b-xl"><textarea class="form-control"  name="descr" rows="15"></textarea></div>
                    </div>
					<div class="form-group m-t-xl">
					 <label class="col-sm-2 control-label">Tags </label>

					 <div class="col-sm-10 m-b-xl"><input class="tagsinput form-control" type="text" name="searchTags" value="<?php echo $docSpec[0]['Doc_Name'].",".$docSpec[0]['specialization']; ?>" /></div>
					</div>

					<br><br>
					<div class="form-group"><label class="col-sm-2 control-label">Add picture</label>

                                    <div class="col-sm-10"><label title="Upload image file" for="inputImage" class="btn btn-primary">
                                        <input type="file" id="inputImage" name="txtPhoto" class="hide">
                                        <i class="fa fa-upload"></i> Upload picture
                                    </label></div>
                     </div>
					</div>
					<div class="form-group">
								<div class="col-sm-2 pull-right">
								<a href="Blog-Surgical-List" class="btn btn-primary block full-width m-b "><i class="fa fa-arrow-left"></i> BACK</a>
								</div>
								<div class="col-sm-2 pull-right">
								<button type="submit" name="cmdBlg" class="btn btn-primary block full-width m-b "><i class="fa fa-paper-plane"></i> PUBLISH</button>
								</div>
								
					</div><br><br>
					
                 </div>  </form>
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
