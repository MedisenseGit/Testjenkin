<?php 
ob_start();
session_start();
error_reporting(0);  
require_once("../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();

//$getClientIp=$_SERVER['REMOTE_ADDR'];


$admin_id = $_SESSION['user_id'];
if(empty($admin_id)){
	header("Location:login");
}


$SQLSELECT = mysqlSelect("*","campaign_contact_lists","","","");


?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Import Contacts</title>

    <?php include_once('support.php'); ?>
	 <link href="../assets/css/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet">
<script src="https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=k4bskbhavbopqhldtcaifwjar0xo7yxzkbb902mi84dto3rj"></script>

<style>
.span6 {
    width: 570px;
  }
  .span5 {
    width: 470px;
  }
  .span4 {
    width: 370px;
  }
  .span3 {
    width: 270px;
  }
   .row-fluid .span6 {
    width: 48.717948717948715%;
    *width: 48.664757228587014%;
  }
  .row-fluid .span5 {
    width: 40.17094017094017%;
    *width: 40.11774868157847%;
  }
  .row-fluid .span4 {
    width: 31.623931623931625%;
    *width: 31.570740134569924%;
  }
  .row-fluid .span3 {
    width: 23.076923076923077%;
    *width: 23.023731587561375%;
  }
   input.span6,
  textarea.span6,
  .uneditable-input.span6 {
    width: 556px;
  }
  input.span5,
  textarea.span5,
  .uneditable-input.span5 {
    width: 456px;
  }
  input.span4,
  textarea.span4,
  .uneditable-input.span4 {
    width: 356px;
  }
  input.span3,
  textarea.span3,
  .uneditable-input.span3 {
    width: 256px;
  }
   .span3 {
    width: 166px;
  }
   .row-fluid .span3 {
    width: 22.92817679558011%;
    *width: 22.87498530621841%;
  }
  .row-fluid .span6 {
    width: 48.61878453038674%;
    *width: 48.56559304102504%;
  }
  .form-horizontal .control-label {
    float: none;
    width: auto;
    padding-top: 0;
    text-align: left;
  }
  .form-horizontal .controls {
    margin-left: 0;
  }
  .form-horizontal .control-list {
    padding-top: 0;
  }
  .form-horizontal .form-actions {
    padding-right: 10px;
    padding-left: 10px;
  }

</style>


</head>

<body>

    <div id="wrapper">

    <?php include_once('sidemenu.php'); ?>

        <div id="page-wrapper" class="gray-bg">
        <?php include_once('header_top.php'); ?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>Import Contacts</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="Home">Home</a>
                        </li>
                        <li>
                            <a href="Blog-Surgical-List">Blog Surgical List</a>
                        </li>
                        <li class="active">
                            <strong>Import Contacts</strong>
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
				 
				 <div class="container">
		<div class="row">
			<div class="span3 hidden-phone"></div>
			<div class="span6" id="form-login">
				<form class="form-horizontal well" action="import-excel/import.php" method="post" name="upload_excel" enctype="multipart/form-data">
					<fieldset>
						<legend>Import CSV/Excel file</legend>
						<div class="control-group">
							<div class="control-label">
								<label>CSV/Excel File:</label>
								
								<a href="import-excel/compaign_contacts.csv" target="_blank" style="float:right;"> <button type="button" class="btn btn-w-m btn-success"><i class="fa fa-upload"></i> Download Template</button></a>
							</div>
							
							
							  
							<div class="controls">
								<input type="file" name="file" id="file" class="input-large">
							</div>
						</div>
						
						<br />
						<div class="control-group">
							<div class="controls">
							<button type="submit" id="submit" name="Import" class="btn btn-primary button-loading" data-loading-text="Loading...">Upload</button>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
			<div class="span3 hidden-phone"></div>
		</div>
		

		<table class="table table-bordered">
			<thead>
				  	<tr>
				  		
				  		<th>Name</th>
				  		<th>Mobile No.</th>
				  		<th>Email ID</th>
				  		<th>Address</th>
						<th>City</th>
						<th>State</th>
						<th>Country</th>
				 		
				 
				  	</tr>	
				  </thead>
			<?php
				//$SQLSELECT = "SELECT * FROM campaign_contact_lists";
				//$result_set =  mysql_query($SQLSELECT, $conn);
				//while($row = mysql_fetch_array($SQLSELECT))
				while(list($key_examtemp, $value_examtemp) = each($SQLSELECT))
				{
				?>
			
					<tr>
						
						<td><?php echo $value_examtemp['Name']; ?></td>
						<td><?php echo $value_examtemp['Mobile_Number']; ?></td>
						<td><?php echo $value_examtemp['Email_ID']; ?></td>
						<td><?php echo $value_examtemp['Address']; ?></td>
						<td><?php echo $value_examtemp['City']; ?></td>
						<td><?php echo $value_examtemp['State']; ?></td>
						<td><?php echo $value_examtemp['Country']; ?></td>
					

					</tr>
				<?php
				}
			?>
		</table>
	</div>
				 
					
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
