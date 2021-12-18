<?php 
ob_start();
error_reporting(0); 
session_start();

$admin_id = $_SESSION['user_id'];
if(empty($admin_id)){
	header("Location:user-login");
}

$curDate=date('d-m-Y');
require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
$getloginDetails = $objQuery->mysqlSelect("*","compny_tab","company_id='".$admin_id."'","","","","");

?>

<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="UTF-8" />
    <title>Admin Dashboard</title>
     <meta content="width=device-width, initial-scale=1.0" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
    <?php include('support_file.php'); ?>
</head>

    <!-- END HEAD -->

    <!-- BEGIN BODY -->
<body class="padTop53 " >

    <!-- MAIN WRAPPER -->
    <div id="wrap" >
        

        <?php include_once('header-section.php'); ?>



        <?php include_once('side-menu.php'); ?>



        <!--PAGE CONTENT -->
        <div id="content">
             
            <div class="inner">
                
				

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="box">
                                <header>
                                    <div class="icons"><i class="icon-th-large"></i></div>
                                    <h5>Change Your Login Credentials</h5>
                                    <div class="toolbar">
                                        <ul class="nav">
                                            <li>
                                             
                                            </li>
                                        </ul>
                                    </div>

                                </header>
                                <div class="accordion-body collapse in body">
									<div class="text-center">
										<img src="assets/img/users.png" class="fa fa-user" style="margin-bottom:10px;"/>
									</div>
									<hr>
									<div class="text-muted text-center"><span class="sucess">
											<?php if(isset($_GET['response'])){
												switch($_GET['response']){
													case '1' : echo '<font color=green>Profile has been updated successfully</font>';
													break;
													
												}
											}
											?></span>
								</div>
                                    <form enctype="multipart/form-data" action="check_credentials.php" method="post" class="form-horizontal" id="frmChangePassword" id="frmChangePassword">
									<input type="hidden" name="user_id" value="<?php echo $admin_id; ?>" />	
										<div class="form-group" >
											<?php if(!empty($getloginDetails[0]['company_logo'])) { ?>
											<label class="control-label col-lg-4">Company Logo</label>
                                            <div class="col-lg-8"><img src="../Company_Logo/<?php echo $getloginDetails[0]['company_id']; ?>/<?php echo $getloginDetails[0]['company_logo']; ?>" width="80" title="logo" />
											</div><?php } ?>
										</div>
										<div class="form-group">
                                            
											 <label class="control-label col-lg-4">Change Logo here </label>
											 <div class="col-lg-8"><input type="file" name="txtLogo"  value="" />
                                            </div>
                                        </div>
										<div class="form-group">
                                            <label class="control-label col-lg-4">Organisation name</label>

                                            <div class="col-lg-4">
                                                <input type="text" id="txtUSer" name="txtOrg" value="<?php echo $getloginDetails[0]['company_name']; ?>" class="form-control" />
                                            </div>
                                        </div>
										<div class="form-group">
                                            <label class="control-label col-lg-4">User Name</label>

                                            <div class="col-lg-4">
                                                <input type="text" id="txtUSer" name="txtUSer" value="<?php echo $getloginDetails[0]['owner_name']; ?>" class="form-control" />
                                            </div>
                                        </div>
										<div class="form-group">
                                            <label class="control-label col-lg-4">Email Id</label>

                                            <div class="col-lg-4">
                                                <input type="text" id="txtUSer" name="txtEmail" value="<?php echo $getloginDetails[0]['email_id']; ?>" class="form-control" />
                                            </div>
                                        </div>
										<div class="form-group">
                                            <label class="control-label col-lg-4">Mobile No.</label>

                                            <div class="col-lg-4">
                                                <input type="text" id="txtUSer" name="txtMobile" value="<?php echo $getloginDetails[0]['mobile']; ?>" class="form-control" />
                                            </div>
                                        </div>
										
                                        <div class="form-group">
                                            <label class="control-label col-lg-4">New Password</label>
                                            <div class="col-lg-4">
                                                <input type="password" id="txtnewpasswd" name="txtnewpasswd" class="form-control" />
                                            </div>
                                        </div>
										<div class="form-group">
                                            <label class="control-label col-lg-4">Re-type Password</label>
                                            <div class="col-lg-4">
                                                <input type="password" id="txtrepasswd" name="txtrepasswd" class="form-control" />
                                            </div>
                                        </div>
										                                      
										<div class="form-actions no-margin-bottom" style="text-align:center;">
                                            <input type="submit" value="UPDATE" name="changepassword" id="changepassword" class="btn btn-primary btn-lg " />
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
					
				
                    </div>
                    
                    
                    

                </div>
          <!--END PAGE CONTENT -->
				
		
    </div>

    <!--END MAIN WRAPPER -->
	<?php include('footer.php'); ?>
</body>

    <!-- END BODY -->
</html>
