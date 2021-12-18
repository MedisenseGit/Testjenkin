<?php
ob_start();
session_start();
error_reporting(0);  

require_once("../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();


//$request  = str_replace("/Product-Details-Page/", "", $_SERVER['REQUEST_URI']);
#split the path by '/'
//$params     = split("/", $request);
//$productid = $params[1];
$productid = $_GET['sp_id'];
$getSponsoresProduct= mysqlSelect("*","sponsores_products","sp_id='".$productid."'","","","","");

?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Product Details</title>

     <link rel="icon" href="../assets/img/favicon_icon.png">
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/font-awesome/css/font-awesome.css" rel="stylesheet">
	  <link href="../assets/css/plugins/slick/slick.css" rel="stylesheet">
    <link href="../assets/css/plugins/slick/slick-theme.css" rel="stylesheet">
    <link href="../assets/css/animate.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
	<!-- FooTable -->
    <link href="../assets/css/plugins/footable/footable.core.css" rel="stylesheet">
	<link href="../assets/css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">	
	<link href="../assets/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">
	<!-- orris -->
    <link href="../assets/css/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">
	<link href="../assets/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
	<link href="../assets/css/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet">
	
	
</head>

<body class="top-navigation">

    <div id="wrapper">
        <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom white-bg">
        <nav class="navbar navbar-static-top" role="navigation">
            <div class="navbar-header">
                
                <a href="#" class="navbar-brand"><img alt="image" class="img" src="../assets/img/Practice_premium.png" width="80"/></a>
            </div>
            
        </nav>
        </div>
        <div class="wrapper wrapper-content">
            <div class="container">
            <div class="row">
                <div class="col-lg-12">

                    <div class="ibox product-detail">
                        <div class="ibox-content">

                            <div class="row">
                                <div class="col-md-5">


                                    <div class="product-images">

                                        <div>
                                            <div class="image-imitation">
                                                <img src="sponsors_products/<?php echo $getSponsoresProduct[0]['sp_id']; ?>/<?php echo $getSponsoresProduct[0]['sp_image']; ?>" width="350"/>
                                            </div>
                                        </div>
                                       

                                    </div>

                                </div>
                                <div class="col-md-7">

                                    <h2 class="font-bold m-b-xs">
                                        <?php echo $getSponsoresProduct[0]['sp_title']; ?>
                                    </h2>
                                    <!--<small>Many desktop publishing packages and web page editors now.</small>
                                    <div class="m-t-md">
                                        <h2 class="product-main-price">$406,602 <small class="text-muted">Exclude Tax</small> </h2>
                                    </div>-->
                                    <hr>

                                    <h4>Product description</h4>

                                    <div class="small text-muted">
                                       <?php echo $getSponsoresProduct[0]['sp_description']; ?>
                                    </div>
                                    
                                    <hr>

                                    <div>
                                        <div class="btn-group">
                                            <!--<button class="btn btn-primary btn-sm"><i class="fa fa-cart-plus"></i> Add to cart</button>
                                            <button class="btn btn-white btn-sm"><i class="fa fa-star"></i> Add to wishlist </button>-->
                                            <button class="btn btn-white btn-sm"><i class="fa fa-envelope"></i> Contact with distributors </button>
                                        </div>
                                    </div>



                                </div>
                            </div>

                        </div>
                        <div class="ibox-footer">
                           
                            Copyright</strong> Medisense Healthcare Solutions Pvt. Ltd. &copy; <?php echo date('Y'); ?>
                        </div>
                    </div>

                </div>
            </div>
          
       

        </div>
        </div>


    <script src="../assets/js/jquery-3.1.1.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="../assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="../assets/js/inspinia.js"></script>
    <script src="../assets/js/plugins/pace/pace.min.js"></script>

	

</body>
</html>

