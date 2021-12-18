<?php
ob_start();
error_reporting(0); 
session_start();

require_once("classes/querymaker.class.php");

$loginId = $_GET['id'];
//$admin_id = $_SESSION['user_id'];

if(empty($loginId)){
	header("Location: ".HOST_HEALTH_URL."Medisense-Patient-Care/Login");
}

$memberId = $_GET['p'];


$objQuery = new CLSQueryMaker();
date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d H:i:s');
$cur_Date=date('Y-m-d',strtotime($Cur_Date));
$time_aa = time();

$user = $objQuery->mysqlSelect("*","login_user","md5(login_id)='".$loginId."' ","","","","");
$member = $objQuery->mysqlSelect("*","user_family_member","md5(member_id)='".$memberId."' ","","","","");

if(isset($_POST['cmdReportAdd'])){

    $errorMessage = false;
    $allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG);
    foreach($_FILES['file-3']['tmp_name'] as $key => $tmp_name ){
        $detectedType = exif_imagetype($_FILES['file-3']['tmp_name'][$key]);
        $error = !in_array($detectedType, $allowedTypes);
        if($error){
            $errorMessage = true;
        }
    }

    if(!$errorMessage){
        $title = addslashes($_POST['reportTitle']);
        $description = addslashes($_POST['reportDescription']);
        $tmp_date = $_POST['reportDate'];
        //echo date("Y-m-d", strtotime($temp_date) );
        $report_date = date("Y-m-d", strtotime($tmp_date) );
        $timeStampNum = strtotime($Cur_Date);

        $arrFields_report[] = 'login_id';
        $arrValues_report[] = $user[0]['login_id'];
        $arrFields_report[] = 'member_id';
        $arrValues_report[] = $member[0]['member_id'];
        $arrFields_report[] = 'title';
        $arrValues_report[] = $title;
        $arrFields_report[] = 'description';
        $arrValues_report[] = $description;

        $arrFields_report[] = 'report_date';
        $arrValues_report[] = $report_date;    
        $arrFields_report[] = 'timeStampNum';
        $arrValues_report[] = $timeStampNum;
        $arrFields_report[] = 'created_date';
        $arrValues_report[] = $Cur_Date;
        
        $add_report = $objQuery->mysqlInsert('health_app_healthfile_reports',$arrFields_report,$arrValues_report);
        $report_id = mysql_insert_id();

        foreach($_FILES['file-3']['tmp_name'] as $key => $tmp_name ){
            $file_name = $_FILES['file-3']['name'][$key];
            $file_size = $_FILES['file-3']['size'][$key];
            $file_tmp = $_FILES['file-3']['tmp_name'][$key];
            $file_type = $_FILES['file-3']['type'][$key];
        
            if(!empty($file_name)){
                $Photo1  = $file_name;
                $arrFields_attach = array();
                $arrValues_attach = array();
        
                $arrFields_attach[] = 'report_id';
                $arrValues_attach[] = $report_id;
                
                $arrFields_attach[] = 'login_id';
                $arrValues_attach[] = $user[0]['login_id'];
                
                $arrFields_attach[] = 'member_id';
                $arrValues_attach[] = $member[0]['member_id'];
        
                $arrFields_attach[] = 'attachment_name';
                $arrValues_attach[] = $file_name;
        
                $report_attach = $objQuery->mysqlInsert('health_app_healthfile_report_attachments',$arrFields_attach,$arrValues_attach);
                $attachid = mysql_insert_id();
        
                //Uploading image file
                $uploaddirectory = realpath("HealthFilesReports");
                $uploaddir = $uploaddirectory . "/" .$attachid;
                $dotpos = strpos($fileName, '.');
                $Photo1 = str_replace(substr($Photo1, 0, $dotpos), $attachid, $Photo1);
                $uploadfile = $uploaddir . "/" . $Photo1;
        
                //Checking whether folder with category id already exist or not.
                if (file_exists($uploaddir)) {
                    //echo "The file $uploaddir exists";
                } else {
                    $newdir = mkdir($uploaddirectory . "/" . $attachid, 0777);
                }
        
                // Moving uploaded file from temporary folder to desired folder.
                if(move_uploaded_file ($file_tmp, $uploadfile)) {
                    $successAttach="";
                } else {
                    //echo "File cannot be uploaded";
                }
            }
        }
        
        $submitted = true;
        $errorNum = 2;
    }else{
        $errorNum = 1;
    }
}


?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Upload Files</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta content="" name="description" />
        <meta content="" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <!-- Stylesheet -->
        <!-- <link href="assets/forForm/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href="assets/forForm/jquery-ui.min.css" rel="stylesheet" type="text/css">
        <link href="assets/forForm/animate.css" rel="stylesheet" type="text/css">
        <link href="assets/forForm/css-plugin-collections.css" rel="stylesheet"/> -->

        <link href="assets/css/bootstrap.min.css" rel="stylesheet">
        <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet">
        <link href="assets/css/animate.css" rel="stylesheet">
        <link href="assets/css/style.css" rel="stylesheet">

        <!-- CSS | Main style file -->
        <link href="assets/forForm/style-main.css" rel="stylesheet" type="text/css">
        <!-- CSS | Responsive media queries -->
        <link href="assets/forForm/responsive.css" rel="stylesheet" type="text/css">

        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    
        <script src="assets/forForm/jquery-2.2.4.min.js"></script>
        <script src="assets/forForm/jquery-ui.min.js"></script>
        <script src="assets/forForm/jquery-plugin-collection.js"></script>       
        <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet">
        <link href="assets/css/animate.css" rel="stylesheet">
        <link href="assets/css/style.css" rel="stylesheet">
        <script src="assets/forForm/bootstrap.min.js"></script>

        <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
        <link href="premium/fileUpload/css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>
        <link href="premium/fileUpload/themes/explorer-fa/theme.css" media="all" rel="stylesheet" type="text/css"/>
        <script src="premium/fileUpload/js/plugins/sortable.js" type="text/javascript"></script>
        <script src="premium/fileUpload/js/fileinput.js" type="text/javascript"></script>
        <script src="premium/fileUpload/js/locales/fr.js" type="text/javascript"></script>
        <script src="premium/fileUpload/js/locales/es.js" type="text/javascript"></script>
        <script src="premium/fileUpload/themes/explorer-fa/theme.js" type="text/javascript"></script>
        <script src="premium/fileUpload/themes/fa/theme.js" type="text/javascript"></script>
        
        <style>
            .table-actions-bar .table-action-btn:hover {
                color: #98a6ad;
            }
            .tabcontent {
                padding: 6px 12px;
                border: 1px solid #ccc;
                border-top: none;
            }
            .splide__slide img {
                width : 100%;
	            height: auto;
            }
            .cutomCss{
                box-shadow:
                0 2.8px 2.2px rgba(0, 0, 0, 0.034),
                0 6.7px 5.3px rgba(0, 0, 0, 0.048),
                0 12.5px 10px rgba(0, 0, 0, 0.06),
                0 22.3px 17.9px rgba(0, 0, 0, 0.072),
                0 41.8px 33.4px rgba(0, 0, 0, 0.086),
                0 100px 80px rgba(0, 0, 0, 0.12)
                ;
            }
            .container-fluid{
                padding: 100px 200px 100px 200px;
            }
            @media only screen and (max-width: 800px) {                
                .container-fluid{
                    padding: 100px ;
                }
            }
            @media only screen and (max-width: 600px) {                
                .container-fluid{
                    padding: 30px ;
                }
            }
		</style>
        
        <script>
            
        </script>
        
    </head>

    <body>

        <div class="wrapper" style="padding: 0;">
            <div class="container-fluid" style=" background-color: #ebeff2; ">

                <div class="row cutomCss" style="margin: 0px;">

                    <!-- Page-Title -->
                    <div class="col-sm-12" style="background-color: white; padding: 20px; padding-bottom:0px;">
                        <div class="page-title-box">
                            <div>
                                <span class="sucess">
                                    <?php if(!empty($errorNum)){
                                        switch($errorNum){
                                            case '2':
                                    ?>
                                                <div class="alert alert-success alert-dismissable">
                                                    <button aria-hidden="true" data-dismiss="alert" class="close"  type="button">×</button>
                                                    <strong>Images successfully uploaded.</strong>
                                                </div>
                                    <?php
                                            break;
                                            case '1' :
                                    ?>								
                                                <div class="alert alert-danger alert-dismissable">
                                                        <button aria-hidden="true" data-dismiss="alert" class="close"  type="button">×</button>
                                                        <strong>Upload failed!! Please upload images only...</strong>
                                                </div>
                                    <?php
                                        }
                                    }
                                    ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Health Reports report attatch form -->
                    <?php 
                        if($submitted){
                    ?>
                        <div class="col-sm-12" style="background-color: white; padding: 20px; padding-bottom:50px;">
                            <div class="page-title-box">
                                <h2 class="page-title">Reports Submitted.</h2>
                                <p>Please close the tab</p>
                                <p><a href="<?php echo HOST_HEALTH_URL; ?>Medisense-Patient-Care/home" class="link-primary">Return to Dahsboard</a></p>
                            </div>
                        </div>
                    <?php }else { ?>
                        <div class="col-lg-12" style="background-color: white; padding: 30px; padding-top:0px; "><br>

                            <h2 class="page-title">Upload Reports</h2>
                            <h4>Name: <?php echo $user[0]['sub_name']; ?></h4>

                            <form enctype="multipart/form-data" method="post" name="formAddReports">
                                <input type="hidden" id="memberId" name="memberId" value="<?php echo $memberId; ?>">
                                <input type="hidden" id="loginId" name="loginId" value="<?php echo $loginId; ?>">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="reportTitle" class="control-label">Title</label>
                                            <input type="text" name="reportTitle" class="form-control" id="reportTitle" placeholder="Report title">
                                        </div>
                                    </div>
                                </div>
                                                    
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="reportDescription" class="control-label">Description</label>
                                            <input type="text" name="reportDescription" class="form-control" id="reportDescription" placeholder="description...">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="reportDate" class="control-label">Report Date</label>
                                            <input type="text" name="reportDate" class="form-control datepicker" id="datepicker" placeholder="dd-mm-yyyy">
                                        </div>
                                    </div>
                                </div>
                            
                                <!-- <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="reportFiles" class="control-label">Report Files (<small style="color: red;">*select images only*</small>)</label>
                                            <div>
                                                <div class="input-group">
                                                    <input accept=".jpg,.jpeg,.png" id="file-3" name="file-3[]" class="file" type="file" id="attachFile" multiple data-preview-file-type="any" data-upload-url="#" tabindex="7" required />
                                                </div>
                                            </div>
                                        </div>
                                    </div>										 
                                </div> -->

                                <div class="row" style="margin: 5px;">
                                    <label for="reportFiles" class="control-label requiredStar">Report Files ( Allowed file types: jpg, jpeg, png )</label>            
                                    <div class="form-group col-lg-12">
                                        <div class="file-loading">
                                            <input accept=".jpg,.jpeg,.png" id="file-3" name="file-3[]" class="file" type="file" id="attachFile" multiple data-preview-file-type="any" data-upload-url="#" tabindex="7" required />
                                        </div>
                                    </div>
                                </div><br>

							    <div class="row" id="image_preview" style="margin: 10px;"></div>

                                <div class="row" style="margin: 10px;">
                                    <button type="submit" id="cmdReportAdd" name="cmdReportAdd" class="btn btn-primary block full-width m-b " style="border-radius: 20px;">Submit</button>     
                                </div>
                            </form>
                        </div>
                    <?php } ?>
                    <div id="imagePreview"></div>
                </div>

            </div> <!-- end container -->
        </div><br/>
        <!-- end wrapper -->

        <script>

            $(document).ready(function(){
                $('.datepicker').datepicker({
                    format: 'dd-mm-yyyy',
                    endDate: '+0d',
                    autoclose: true
                }).datepicker("setDate", "0");
            });
        </script>
    </body>
</html>