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


?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add Event</title>

    <?php include_once('support.php'); ?>
	<link href="../assets/css/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet">
	<link href="../assets/css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">
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
  <link href="../assets/css/plugins/dropzone/basic.css" rel="stylesheet">
    <link href="../assets/css/plugins/dropzone/dropzone.css" rel="stylesheet">
    <link href="../assets/css/plugins/jasny/jasny-bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/plugins/codemirror/codemirror.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
	<link href="../assets/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
</head>

<body>

    <div id="wrapper">

    <?php include_once('sidemenu.php'); ?>

        <div id="page-wrapper" class="gray-bg">
        <?php include_once('header_top.php'); ?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>Add Jobs</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="Home">Home</a>
                        </li>
                        <li>
                            <a href="Job-Post">Job Post</a>
                        </li>
                        <li class="active">
                            <strong>Add Jobs</strong>
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
                         <label class="col-sm-2 control-label">Job Title <span class="required">*</span></label>

                                    <div class="col-sm-10 m-b-xl"><input type="text" name="job_title" required="required" class="form-control"></div>
					</div><br><br>
					<div class="form-group">
					<label class="col-sm-2 control-label">Interview Date <span class="required">*</span></label>
							<div class="col-sm-10 m-b-xl">
							<div class="pull-left">
                            <label class="col-sm-1 control-label" for="date_added">From <span class="required">*</span></label>
							<div class="col-sm-3 input-group date">
							<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input id="dateadded" name="start_date" type="text" required="required" class="form-control" >
                           	</div>
							</div>
							<div class="pull-right">
							<label class="col-sm-1 control-label" for="date_added">To <span class="required">*</span></label>
                            <div class="col-sm-3 input-group date">
							<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input id="date_modified" name="end_date" type="text" required="required" class="form-control" >
                            </div>
							</div>
							</div>
					</div>
					<div class="form-group">
							
					</div>
					<div class="form-group">
									 <label class="col-sm-2 control-label">Job description <span class="required">*</span></label>

                                    <div class="col-sm-10 m-b-xl"><textarea class="form-control"  name="descr" rows="15"></textarea></div>
                    </div>
					
					
					<div class="form-group">
                         <label class="col-sm-2 control-label">Website Link </label>

                                    <div class="col-sm-10 m-b-xl"><input type="text" name="web_link"  class="form-control"></div>
					</div>
					<div class="form-group">
                         <label class="col-sm-2 control-label">Contact Mobile No. <span class="required">*</span></label>

                                    <div class="col-sm-10 m-b-xl"><input type="text" name="cont_num" required="required" class="form-control" placeholder="10 digit mobile no." minlength="10" maxlength="15"></div>
					</div>
					<div class="form-group">
                         <label class="col-sm-2 control-label">Contact Email Address <span class="required">*</span></label>

                                    <div class="col-sm-10 m-b-xl"><input type="email" name="cont_email" required="required" class="form-control"></div>
					</div>
					<div class="form-group">
					 <label class="col-sm-2 control-label">Tags </label>

					 <div class="col-sm-10 m-b-xl"><input class="tagsinput form-control" type="text" name="searchTags" value="<?php echo $docSpec[0]['Doc_Name'].",".$docSpec[0]['specialization']; ?>" /></div>
					</div>
					<div class="form-group">
					 <label class="col-sm-2 control-label">Upload Picture </label>
						<div class="col-sm-10 m-b-xl">
						<div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                <div class="form-control" data-trigger="fileinput"><i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div>
                                <span class="input-group-addon btn btn-default btn-file"><span class="fileinput-new">Select file</span><span class="fileinput-exists">Change</span><input type="file" name="txtPhoto"></span>
                                <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                            </div>
						</div>
					</div>
					<div class="form-group">
					 <label class="col-sm-2 control-label">Upload Job Description </label>
						<div class="col-sm-10 m-b-xl">
						<div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                <div class="form-control" data-trigger="fileinput"><i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div>
                                <span class="input-group-addon btn btn-default btn-file"><span class="fileinput-new">Select file</span><span class="fileinput-exists">Change</span><input type="file" name="txtBrochure"></span>
                                <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                            </div>
						</div>
					</div>
					</div>
					<div class="form-group">

								<div class="col-sm-2 pull-right">
								<a href="Blog-List" class="btn btn-primary block full-width m-b "><i class="fa fa-arrow-left"></i> BACK</a>
								</div>
								<div class="col-sm-2 pull-right">
								<button type="submit" name="addJobs" class="btn btn-primary block full-width m-b "><i class="fa fa-paper-plane"></i> POST</button>
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
	<!-- Chosen -->
    <script src="../assets/js/plugins/chosen/chosen.jquery.js"></script>
	<script>
       
        $('.chosen-select').chosen({width: "100%"});



    </script>
	<!-- Switchery -->
   <script src="../assets/js/plugins/switchery/switchery.js"></script>
	 <!-- Jasny -->
    <script src="../assets/js/plugins/jasny/jasny-bootstrap.min.js"></script>

    <!-- DROPZONE -->
    <script src="../assets/js/plugins/dropzone/dropzone.js"></script>

    <!-- CodeMirror -->
    <script src="../assets/js/plugins/codemirror/codemirror.js"></script>
    <script src="../assets/js/plugins/codemirror/mode/xml/xml.js"></script>


    <script>
        Dropzone.options.dropzoneForm = {
            paramName: "file", // The name that will be used to transfer the file
            maxFilesize: 2, // MB
            dictDefaultMessage: "<strong>Drop files here or click to upload. </strong></br> (This is just a demo dropzone. Selected files are not actually uploaded.)"
        };

        $(document).ready(function(){

            var editor_one = CodeMirror.fromTextArea(document.getElementById("code1"), {
                lineNumbers: true,
                matchBrackets: true
            });

            var editor_two = CodeMirror.fromTextArea(document.getElementById("code2"), {
                lineNumbers: true,
                matchBrackets: true
            });

            var editor_two = CodeMirror.fromTextArea(document.getElementById("code3"), {
                lineNumbers: true,
                matchBrackets: true
            });

       });
    </script>
	<!-- FooTable -->
    <script src="../assets/js/plugins/footable/footable.all.min.js"></script>
<!-- Data picker -->
    <script src="../assets/js/plugins/datapicker/bootstrap-datepicker.js"></script>
   <!-- Page-Level Scripts -->
    <script>
        $(document).ready(function() {

            $('.footable').footable();

            $('#dateadded').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true
            });

            $('#date_modified').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true
            });

        });

    </script>
</body>

</html>
