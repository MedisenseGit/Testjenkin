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

$objQuery = new CLSQueryMaker();
date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d H:i:s');
$cur_Date=date('Y-m-d',strtotime($Cur_Date));
$time_aa = time();

$user = $objQuery->mysqlSelect("*","login_user","md5(login_id)='".$loginId."' ","","","","");
$family_names = $objQuery->mysqlSelect("*","user_family_member","md5(user_id)='".$loginId."' ","member_id ASC","","","");
$user_addresses = $objQuery->mysqlSelect("*","user_address","md5(user_id)='".$loginId."' ","","","","");

if(isset($_POST['cdmMedicineOrder'])){

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
        $patName = addslashes($_POST['patName']);
        $patMobile = $_POST['patMobile'];
        $patEmail = $_POST['patEmail'];
        $patAddress = addslashes($_POST['patAddress']);
        $patCity = addslashes($_POST['patCity']);
        $patState = addslashes($_POST['patState']);
        $patCountry = addslashes($_POST['patCountry']);
        $patPincode = addslashes($_POST['patPincode']);

        $arrFields_medicine = array();
        $arrValues_medicine = array();
        
        $arrFields_medicine[] = 'login_id';
        $arrValues_medicine[] = $user[0]['login_id'];

        $arrFields_medicine[] = 'customer_name';
        $arrValues_medicine[] = $patName;
        $arrFields_medicine[] = 'customer_mobile';
        $arrValues_medicine[] = $patMobile;
        $arrFields_medicine[] = 'customer_email';
        $arrValues_medicine[] = $patEmail;
        $arrFields_medicine[] = 'customer_address';
        $arrValues_medicine[] = $patAddress;
        $arrFields_medicine[] = 'customer_city';
        $arrValues_medicine[] = $patCity;
        $arrFields_medicine[] = 'customer_state';
        $arrValues_medicine[] = $patState;
        $arrFields_medicine[] = 'customer_country';
        $arrValues_medicine[] = $patCountry;
        $arrFields_medicine[] = 'customer_pincode';
        $arrValues_medicine[] = $patPincode;
        
        $arrFields_medicine[] = 'created_date';
        $arrValues_medicine[] = $Cur_Date;

        if(isset($_POST['addressCheckBox'])){ //if delivery address same as cabove address check box is ticked
            $arrFields_medicine[] = 'shipping_address';
            $arrValues_medicine[] = $patAddress;
            $arrFields_medicine[] = 'shipping_city';
            $arrValues_medicine[] = $patCity;
            $arrFields_medicine[] = 'shipping_state';
            $arrValues_medicine[] = $patState;
            $arrFields_medicine[] = 'shipping_country';
            $arrValues_medicine[] = $patCountry;
            $arrFields_medicine[] = 'shipping_pincode';
            $arrValues_medicine[] = $patPincode;
        }else{ // if any of the address selected
            $addressID = $_POST['addressRadio'];
            
            $arrFields_medicine[] = 'shipping_address';
            $arrValues_medicine[] = $_POST['shipAddress-'.$addressID];
            $arrFields_medicine[] = 'shipping_city';
            $arrValues_medicine[] = $_POST['shipCity-'.$addressID];
            $arrFields_medicine[] = 'shipping_state';
            $arrValues_medicine[] = $_POST['ShipState-'.$addressID];
            $arrFields_medicine[] = 'shipping_country';
            $arrValues_medicine[] = $_POST['shipCountry-'.$addressID];
            $arrFields_medicine[] = 'shipping_pincode';
            $arrValues_medicine[] = $_POST['shipPincode-'.$addressID];                
        }
        
        $add_medicine = $objQuery->mysqlInsert('health_pharma_request',$arrFields_medicine,$arrValues_medicine);
        $medicine_id = mysql_insert_id();

        if($medicine_id){
            foreach($_FILES['file-3']['tmp_name'] as $key => $tmp_name ){
                $file_name = $_FILES['file-3']['name'][$key];
                $file_size = $_FILES['file-3']['size'][$key];
                $file_tmp = $_FILES['file-3']['tmp_name'][$key];
                $file_type = $_FILES['file-3']['type'][$key];
            
                if(!empty($file_name)){
                    $Photo1  = $file_name;
                    $arrFields_attach = array();
                    $arrValues_attach = array();
            
                    $arrFields_attach[] = 'customer_id';
                    $arrValues_attach[] = $medicine_id;
                    
                    $arrFields_attach[] = 'login_id';
                    $arrValues_attach[] = $user[0]['login_id'];
            
                    $arrFields_attach[] = 'attachments';
                    $arrValues_attach[] = $file_name;
            
                    $report_attach = $objQuery->mysqlInsert('health_pharma_request_attachments',$arrFields_attach,$arrValues_attach);
                    $attachid = mysql_insert_id();
            
                    //Uploading image file
                    $uploaddirectory = realpath("HealthPharmaAttachments");
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
        }
        
        $submitted = true;
        $resultNum = 2;
    }else{
        $resultNum = 1;
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

        <link href="assets/css/bootstrap.min.css" rel="stylesheet">
        <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet">
        <link href="assets/css/animate.css" rel="stylesheet">
        <link href="assets/css/style.css" rel="stylesheet">

        <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
        <link href="premium/fileUpload/css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>
        <link href="premium/fileUpload/themes/explorer-fa/theme.css" media="all" rel="stylesheet" type="text/css"/>
        <script src="premium/fileUpload/js/plugins/sortable.js" type="text/javascript"></script>
        <script src="premium/fileUpload/js/fileinput.js" type="text/javascript"></script>
        <script src="premium/fileUpload/js/locales/fr.js" type="text/javascript"></script>
        <script src="premium/fileUpload/js/locales/es.js" type="text/javascript"></script>
        <script src="premium/fileUpload/themes/explorer-fa/theme.js" type="text/javascript"></script>
        <script src="premium/fileUpload/themes/fa/theme.js" type="text/javascript"></script>
        <!-- Sweet alert -->
        <script src="assets/js/plugins/sweetalert/sweetalert.min.js"></script>
        <!-- Sweet Alert -->
        <link href="assets/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
        <!-- Toastr style -->
        <link href="assets/css/plugins/toastr/toastr.min.css" rel="stylesheet">
        
        <style>
            .table-actions-bar .table-action-btn:hover {
                color: #98a6ad;
            }
            .tabcontent {
                padding: 6px 12px;
                border: 1px solid #ccc;
                border-top: none;
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
                background-color: white; 
                padding: 100px; 
                padding-top:30px; 
                border-radius: 25px;
            }
            .wrapper{
                padding: 70px;
                background-color: #ebeff2;
            }
            #row {
                padding: 100px;
            }
            .requiredStar:after {
                content:" *";
                color: red;
            }

            .multiAdressField{
                background-color: #e0e6eb;
                padding: 20px;
                border-radius: 15px;
            }

            @media only screen and (max-width: 850px) {                
                .wrapper{
                    padding: 20px;
                }
                #row{
                    padding: 50px;
                }
                .cutomCss{
                    padding: 40px; 
                    padding-top: 30px;
                }
            }
            @media only screen and (max-width: 450px) {                
                .wrapper{
                    padding: 20px;
                }
                #row{
                    padding: 20px;
                }
                .cutomCss{
                    padding: 20px; 
                    padding-top: 30px;
                }
            }
		</style>
        
    </head>

    <body >

        <div class="wrapper">
            <div class="row" id="row">
                
                <!-- Health Reports report attatch form -->
                <?php 
                    if($submitted){
                ?>
                    <div class="col-lg-12 cutomCss" style="height: 45vh;">

                        <div>
                            <span class="sucess">
                                <?php if(!empty($resultNum)){
                                    switch($resultNum){
                                        case '2':
                                ?>
                                            <div class="alert alert-success alert-dismissable">
                                                <button aria-hidden="true" data-dismiss="alert" class="close"  type="button">×</button>
                                                <strong>Medicine Order successfully placed.</strong>
                                            </div>
                                <?php
                                        break;
                                        case '1' :
                                ?>								
                                            <div class="alert alert-danger alert-dismissable">
                                                    <button aria-hidden="true" data-dismiss="alert" class="close"  type="button">×</button>
                                                    <strong>Order failed!! Please upload images only...</strong>
                                            </div>
                                <?php
                                    }
                                }
                                ?>
                            </span>
                        </div>

                        <div class="page-title-box">
                            <h2 class="page-title">Medicine Order Placed.</h2>
                            <p>Please close the tab and return to main page.</p>

                            <p><a href="<?php echo HOST_HEALTH_URL; ?>Medisense-Patient-Care/home" class="link-primary">Return to Dahsboard</a></p>
                        </div>
                    </div>
                <?php }else { ?>
                    <div class="col-lg-12 cutomCss" style=""><br>

                        <div>
                            <span class="sucess">
                                <?php if(!empty($resultNum)){
                                    switch($resultNum){
                                        case '2':
                                ?>
                                            <div class="alert alert-success alert-dismissable">
                                                <button aria-hidden="true" data-dismiss="alert" class="close"  type="button">×</button>
                                                <strong>Medicine Order successfully placed.</strong>
                                            </div>
                                <?php
                                        break;
                                        case '1' :
                                ?>								
                                            <div class="alert alert-danger alert-dismissable">
                                                    <button aria-hidden="true" data-dismiss="alert" class="close"  type="button">×</button>
                                                    <strong>Order failed!! Please upload images only...</strong>
                                            </div>
                                <?php
                                    }
                                }
                                ?>
                            </span>
                        </div>

                        <h2 >Order Medicine</h2><br>

                        <h3 style="color: gray;">Customer Information</h3>

                        <form enctype="multipart/form-data" method="post" name="formAddReports">
                            <input type="hidden" id="loginId" name="loginId" value="<?php echo $loginId; ?>">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="patName" class="control-label requiredStar">Name</label>
                                        <select name="patName" class="form-control" id="patName" aria-label="Default select example" required>
                                            <?php
                                                foreach($family_names as $person){
                                            ?>
                                                <option <?php echo $person['member_name']; ?>><?php echo $person['member_name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                                                
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="patMobile" class="control-label requiredStar">Mobile No.</label>
                                        <input type="text" name="patMobile" class="form-control" value="<?php echo $user[0]['sub_contact']; ?>" id="patMobile" placeholder="Mobile Number" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="patEmail" class="control-label">Email (Optional)</label>
                                        <input type="email" name="patEmail" class="form-control" value="<?php echo $user[0]['sub_email']; ?>" id="patEmail" placeholder="Email">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="patAddress" class="control-label requiredStar">Address</label>
                                        <input type="text" name="patAddress" class="form-control" value="<?php echo $user[0]['sub_address']; ?>" id="patAddress" placeholder="Adress" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="patCity" class="control-label requiredStar">City</label>
                                        <input type="text" name="patCity" class="form-control" value="<?php echo $user[0]['sub_city']; ?>" id="patCity" placeholder="City" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="patState" class="control-label requiredStar">State</label>
                                        <input type="text" name="patState" class="form-control" value="<?php echo $user[0]['sub_state']; ?>" id="patState" placeholder="State" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="patCountry" class="control-label requiredStar">Country</label>
                                        <input type="text" name="patCountry" class="form-control" value="<?php echo $user[0]['sub_country']; ?>" id="patCountry" placeholder="Country" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="patPincode" class="control-label requiredStar">Pincode</label>
                                        <input type="text" name="patPincode" class="form-control" value="" id="patPincode" placeholder="Pincode" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <br>
                                    <h3>Delivery Address</h3>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input class="form-check-input" type="checkbox" name="addressCheckBox" value="0" id="addressCheckBox">
                                        <label class="form-check-label" for="addressCheckBox">
                                            Shipping address same as billing
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row multiAdressField" id="multiAdressField">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4>
                                            Choose from below address 
                                            <a href="#custom-modal" class="btn btn-md waves-effect waves-light m-b-30" data-animation="fadein" data-toggle="modal" data-target="#con-close-modal" data-overlaySpeed="200" data-overlayColor="#36404a"><i class="fa fa-plus"></i> New</a>
                                        </h4>
                                    </div>
                                    <?php
                                        if(!empty($user_addresses)){
                                            foreach($user_addresses as $address){
                                    ?>
                                                <div class="form-group">
                                                    <input type="hidden" id="shipAddress-<?php echo $address['address_id']; ?>" name="shipAddress-<?php echo $address['address_id']; ?>" value="<?php echo $address['address']; ?>">
                                                    <input type="hidden" id="shipCity-<?php echo $address['address_id']; ?>"    name="shipCity-<?php echo $address['address_id']; ?>"    value="<?php echo $address['city']; ?>">
                                                    <input type="hidden" id="ShipState-<?php echo $address['address_id']; ?>"   name="ShipState-<?php echo $address['address_id']; ?>"   value="<?php echo $address['state']; ?>">
                                                    <input type="hidden" id="shipCountry-<?php echo $address['address_id']; ?>" name="shipCountry-<?php echo $address['address_id']; ?>" value="<?php echo $address['country']; ?>">
                                                    <input type="hidden" id="shipPincode-<?php echo $address['address_id']; ?>" name="shipPincode-<?php echo $address['address_id']; ?>" value="<?php echo $address['pincode']; ?>">

                                                    <input class="form-check-input addressRadioButtons" type="radio" name="addressRadio" id="addressRadio-<?php echo $address['address_id']; ?>" value="<?php echo $address['address_id']; ?>" required>
                                                    <label class="form-check-label" for="addressRadio-<?php echo $address['address_id']; ?>" style="margin-left: 10px;" >
                                                        <p>
                                                            <?php echo $address['address']; ?><br>
                                                            <?php echo $address['city']; ?>,<?php echo $address['state']; ?><br>
                                                            <?php echo $address['country']; ?>,<?php echo $address['pincode']; ?>
                                                        </p>
                                                    </label>
                                                </div>
                                    <?php
                                            }
                                        }
                                    ?>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12"><br>
                                    <p style="color: red;"><b>Note: All the above fileds are mandotory before you order your Medicine</b></p>
                                </div>
                            </div><br>

                            <div class="row" style="margin: 10px;">
                                <label for="reportFiles" class="control-label requiredStar">Attach Prescriptions ( Allowed file types: jpg, jpeg, png )</label>            
                                <div class="form-group col-lg-12">
                                    <div class="file-loading">
                                        <input accept=".jpg,.jpeg,.png" id="file-3" name="file-3[]" class="file" type="file" id="attachFile" multiple data-preview-file-type="any" data-upload-url="#" tabindex="7" required />
                                    </div>
                                </div>
                            </div><br>

							<div class="row" id="image_preview" style="margin: 10px;"></div>

                            <div class="row" style="margin: 10px;">
                                <button type="submit" id="cdmMedicineOrder" name="cdmMedicineOrder" class="btn btn-primary block full-width m-b " style="border-radius: 20px;">Order medicine</button>     
                            </div>
                        </form>

                        <div id="con-close-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h2 class="modal-title" style="font-weight: bold;">Add New Delivery Adress<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button></h2>
                                    </div>
                                    
                                    <div class="modal-body">
                                        <form enctype="multipart/form-data" method="post" name="frmAddMember" action="add_form_details.php">

                                            <input type="hidden" name="medicine" value="medicine">
                                            <input type="hidden" name="login_id" value="<?php echo $user[0]['login_id']; ?>">

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="newAddress" class="control-label requiredStar">Address</label>
                                                        <input type="text" name="newAddress" class="form-control" id="newAddress" placeholder="Enter Adress" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="newCity" class="control-label requiredStar">City</label>
                                                        <input type="text" name="newCity" class="form-control" id="newCity" placeholder="City" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="newState" class="control-label requiredStar">State</label>
                                                        <input type="text" name="newState" class="form-control" id="newState" placeholder="State" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="newCountry" class="control-label requiredStar">Country</label>
                                                        <input type="text" name="newCountry" class="form-control" id="newCountry" placeholder="Country" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="newPincode" class="control-label requiredStar">Pincode</label>
                                                        <input type="text" name="newPincode" class="form-control" id="newPincode" placeholder="Pincode" required>
                                                    </div>
                                                </div>
                                            </div>
                                        
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                                                <button type="submit" name="addNewAddress" class="btn btn-info waves-effect waves-light">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div><!-- /.modal -->
                        </div>

                    </div>
                <?php } ?>
                <div id="imagePreview"></div>
            </div>
        </div>
        <!-- end wrapper -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

        <!-- Mainly scripts -->
        <script src="assets/js/jquery-3.1.1.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
        <script src="assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

        <!-- Custom and plugin javascript -->
        <script src="assets/js/inspinia.js"></script>
        <script src="assets/js/plugins/pace/pace.min.js"></script>

        <!-- Flot -->
        <script src="assets/js/plugins/flot/jquery.flot.js"></script>
        <script src="assets/js/plugins/flot/jquery.flot.tooltip.min.js"></script>
        <script src="assets/js/plugins/flot/jquery.flot.resize.js"></script>

        <!-- ChartJS-->
        <script src="assets/js/plugins/chartJs/Chart.min.js"></script>

        <!-- Peity -->
        <script src="assets/js/plugins/peity/jquery.peity.min.js"></script>
        <!-- Peity demo -->
        <script src="assets/js/demo/peity-demo.js"></script>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

        <!-- Toastr -->
        <script src="assets/js/plugins/toastr/toastr.min.js"></script>

        <script>
            $(document).ready(function(){
                $('.datepicker').datepicker({
                    format: 'dd-mm-yyyy',
                    endDate: '+0d',
                    autoclose: true
                }).datepicker("setDate", "0");
            });
            
            $(document).ready(function () {
                $('#addressCheckBox').change(function () {
                    if(!this.checked){
                        $('.addressRadioButtons').prop('required',true);
                        $('#multiAdressField').fadeIn('slow');
                    }else{
                        $('.addressRadioButtons').prop('required',false);
                        $('#multiAdressField').fadeOut('slow');
                    }
                });
            });
        </script>
    </body>
</html>