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

						
$allRecord = mysqlSelect("a.freq_medicine_id as freq_medicine_id,a.pp_id as product_id, a.med_trade_name as med_trade_name, a.med_generic_name as med_generic_name","doctor_frequent_medicine as a left join pharma_products as b on a.pp_id=b.pp_id","a.doc_id='".$admin_id."' and a.doc_type=1 and b.pharma_brand IS NULL","a.freq_medicine_id desc","","","");

if(isset($_GET['id'])){
$getMedicine= mysqlSelect("freq_medicine_id, med_trade_name, med_generic_name","doctor_frequent_medicine","md5(freq_medicine_id)='".$_GET['id']."'","","","","");
	
}
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Manage Drug Database</title>
<?php include_once('support.php'); ?>
	<!-- FooTable -->
    <link href="../assets/css/plugins/footable/footable.core.css" rel="stylesheet">
	<link href="../assets/css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">	
	<link href="../assets/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">


	<link href="../assets/css/plugins/iCheck/custom.css" rel="stylesheet">

	
	<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery-ui.js"></script>
<link rel="stylesheet" href="css/jquery-ui.css">
<script type="text/javascript">
$(function() 
{
 $( "#medicine_name" ).autocomplete({
  source: 'get_medicine_name.php'
 });
 $( "#generic_name" ).autocomplete({
  source: 'get_generic_name.php'
 });
 
 
});
</script>

	<style>

.autocomplete {
  /*the container must be positioned relative:*/
  position: relative;
  display: inline-block;
}

.autocomplete-items {
  position: absolute;
  border: 1px solid #d4d4d4;
  border-bottom: none;
  border-top: none;
  z-index: 99;
  /*position the autocomplete items to be the same width as the container:*/
  top: 100%;
  left: 0;
  right: 0;
}
.autocomplete-items div {
  padding: 10px;
  cursor: pointer;
  background-color: #fff; 
  border-bottom: 1px solid #d4d4d4; 
}
.autocomplete-items div:hover {
  /*when hovering an item:*/
  background-color: #e9e9e9; 
}
.autocomplete-active {
  /*when navigating through the items using the arrow keys:*/
  background-color: DodgerBlue !important; 
  color: #ffffff; 
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
                     <h2>Manage Drug Database</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="Home">Home</a>
                        </li>
                        
                        <li class="active">
                            <strong>Manage Drug Database</strong>
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
              <form method="post" name="frmAddPayments" autocomplete="off" action="add_details.php">
			  <input type="hidden" name="medid" value="<?php echo $getMedicine[0]['freq_medicine_id']; ?>" />
				<div class="row">
				<div class="col-sm-12 m-t">
					<code>NOTE: Drugs added or edited here will be of effect only to this user. If any drugs needs to be added for all doctors, please mail the same at medical@medisense.me</code>
				</div>
				</div>
				
                <div class="row m-t">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="control-label" for="product_name">Medicine Name <span class="required">*</span></label>
                            <input type="text" id="medicine_name" name="medicine_name" value="<?php echo $getMedicine[0]['med_trade_name']; ?>" placeholder="Medicine Name" required class="form-control typeahead_1">
                        </div>
                    </div>
					<div class="col-sm-4">
                        <div class="form-group">
                            <label class="control-label" for="price">Generic Name <span class="required">*</span></label>
                            <input type="text" id="generic_name" name="generic_name" value="<?php echo $getMedicine[0]['med_generic_name']; ?>" placeholder="Generic Name" required class="form-control typeahead_2">
                        </div>
                    </div>
                   <div class="col-sm-4">
                        <div class="form-group">
						 <label class="control-label" for="status" ></label>
						 <?php if(isset($_GET['id'])) { ?>
                             <button type="submit" name="update_drug_list" id="update_drug_list" class="btn btn-outline btn-primary"><i class="fa fa-plus"></i> Update </button>
							<button type="submit" name="add_drug_list" id="add_drug_list" class="btn btn-outline btn-primary"><i class="fa fa-plus"></i> Add New</button>
						 
						 <?php } else { ?>
						 <button type="submit" name="add_drug_list" id="add_drug_list" class="btn btn-outline btn-primary"><i class="fa fa-plus"></i> Add </button>
						 <?php } ?>
						</div>
                    </div>
                   
					
                </div>
				
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

                                        
                                        <th>Medicine Name </th>
										<th>Generic Name</th>
										
                                    </tr>
                                    </thead>
                                    <tbody>
									
									<?php 
									if(!empty($allRecord)){
									foreach($allRecord as $list)
									{ 
										$checkDrugUsage = mysqlSelect("*","doc_patient_episode_prescriptions","pp_id='".$list['product_id']."'","","","","");
										if(COUNT($checkDrugUsage)>0)
										{
											$disableStatus = "disabled";
											$deleteCSS = "";
										}
										else
										{
											$disableStatus = "";
											$deleteCSS = "delete_drug";
										}
									?>
                                    <tr id="delete_drug_row<?php echo $list['freq_medicine_id'];?>">
                                       
                                        <td><strong><?php echo $list['med_trade_name']; ?></strong> </td>
										<td><?php echo $list['med_generic_name']; ?></td>
										<td><a href="Drug-Database?id=<?php echo md5($list['freq_medicine_id']); ?>" class="btn btn-danger btn-bitbucket btn-xs">
										 <i class="fa fa-pencil-square-o"></i> EDIT</a> | <a href="javascript:void(0)" data-row-id = "<?php echo $list['freq_medicine_id']; ?>" data-drug-id = "<?php echo md5($list['freq_medicine_id']); ?>" class="btn btn-danger btn-bitbucket btn-xs <?php echo $deleteCSS; ?>" <?php echo $disableStatus; ?>>
										 <i class="fa fa-trash-o"></i> DELETE</a></td>
                                       
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

    <script src="../assets/js/jquery-3.1.1.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="../assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
	
    <!-- Custom and plugin javascript -->
    <script src="../assets/js/inspinia.js"></script>
    <script src="../assets/js/plugins/pace/pace.min.js"></script>
	
	
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="../assets/js/custom.min.js"></script>
	<!-- iCheck -->
    <script src="../assets/js/plugins/iCheck/icheck.min.js"></script>
	

	<!-- Switchery -->
   <script src="../assets/js/plugins/switchery/switchery.js"></script>
  <script language="JavaScript" src="js/status_validationJs.js"></script>
</body>

</html>
