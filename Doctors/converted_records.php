<?php
ob_start();
error_reporting(0); 
session_start();

$admin_id = $_SESSION['user_id'];
//Get the page name 
$request_uri  = str_replace("", "", $_SERVER['REQUEST_URI']);
if(empty($admin_id)){
	header("Location:index.php");
}
include('functions.php');
require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

if(isset($_POST['cmdGetId'])){
	$_SESSION['patient_id']=$_POST['patient_id'];
	$_SESSION['request_uri']=$request_uri;
	header('location:patient-history');	
}
			if(!isset($_GET['start'])) {
			$start = 0;
			}else{
			$start = $_GET['start'];
			}

			$eu = ($start - 0); 
			$limit = 50;         // No of records to be shown per page.
			$this1 = $eu + $limit; 
			$back = $eu - $limit; 
			$next = $eu + $limit;
$uniqueRecord = $objQuery->mysqlSelect("DISTINCT(a.patient_id) as Patient_Id","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.status2=8 or b.status2=7 or b.status2=9 or b.status2=11 or b.status2=12 or b.status2=13) and (b.ref_id='".$_SESSION['user_id']."')","a.patient_id desc","","","");
$pag_result = $objQuery->mysqlSelect("DISTINCT(a.patient_id) as Patient_Id","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.status2=8 or b.status2=7 or b.status2=9 or b.status2=11 or b.status2=12 or b.status2=13) and (b.ref_id='".$_SESSION['user_id']."')","a.patient_id desc","");
$pageing = firstPaging($pag_result,$limit,$back,$next,$eu,$field,$type2);
$arrPage = explode("-",$pageing);                  
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Converted Patient Records</title>

     <!-- Bootstrap -->
    <link href="../Hospital/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../Hospital/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../Hospital/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="../Hospital/vendors/iCheck/skins/flat/green.css" rel="stylesheet">
    <!-- Datatables -->
    <link href="../Hospital/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="../Hospital/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
    <link href="../Hospital/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="../Hospital/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="../Hospital/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">
	<link href="../Hospital/css/pagination.css" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="../Hospital/build/css/custom.min.css" rel="stylesheet">
	<link type="text/css" rel="stylesheet" href="../Hospital/jsPopup/popModal.css">
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
	  
		<!--Side Menu & Top Navigation -->
        <?php include_once('side_menu.php'); ?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <?php include_once('header_top_nav.php'); ?>

            <div class="clearfix"></div>

            <div class="row">
             

              

              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Converted Patient Records<small>Total Count:<?php echo $_SESSION['tot_converted_count']; ?></small></h2>
                     <ul class="nav navbar-right panel_toolbox">
					<li>Displaying results 1 - <?php echo $pages; ?> of <?php echo $_GET['start']; ?> </li>
                      <li>&nbsp;&nbsp;</li>
					  <li><?php echo $arrPage[0];?>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <!--<p class="text-muted font-13 m-b-30">
                      The Buttons extension for DataTables provides a common set of options, API methods and styling to display buttons on a page that will interact with a DataTable. The core library provides the based framework upon which plug-ins can built.
                    </p>-->
                    <!--<table id="datatable-buttons" class="table table-striped table-bordered">-->
					<table id="" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th style="width:100px;">Patient Id</th>
											<th style="width:100px;">Ref.Date</th>
                                            <th style="width:200px;">Patient Name</th>
											<th style="width:200px;">Referred By</th>
                                            <th style="width:200px;">Referred To</th>
                                            <th style="width:200px;">Hospital</th>
											<th style="width:200px;">Status</th>
											<th style="width:200px;">View Response</th>
                        </tr>
                      </thead>


                      <tbody>
					  <?php foreach($uniqueRecord as $list){ 
								
								$convertedRecord = $objQuery->mysqlSelect("b.timestamp as Ref_Date,a.transaction_status as Trans_status,a.patient_id as Patient_Id,a.patient_name as Patient_Name,a.patient_src as Ref_By,b.ref_id as Doc_Id,b.status2 as Patient_status,b.response_time as Response_Time,b.response_status as Auto_Response,d.hosp_name as Hosp_Name,d.hosp_city as Hosp_City,d.hosp_state as Hosp_State","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(a.patient_id='".$list['Patient_Id']."') and (b.status2=8 or b.status2=7 or b.status2=9 or b.status2=11 or b.status2=12 or b.status2=13) and (b.ref_id='".$_SESSION['user_id']."')","a.patient_id desc","","","");
		
									//RETREIVE DOCTOR DETAILS
                                        $getDoc = $objQuery->mysqlSelect("*","referal","ref_id='".$convertedRecord[0]['Doc_Id']."'","","","","");
										//RETREIVE DOCTOR'S RESPONSE
										$getDocResponse = $objQuery->mysqlSelect("TImestamp as Chat_Date,chat_note as Chat_Note","chat_notification","patient_id='".$convertedRecord[0]['Patient_Id']."'and ref_id='".$convertedRecord[0]['Doc_Id']."'","","","","");
										//Get Referrer info
										 $getRefBy = $objQuery->mysqlSelect("source_name as ref_by","source_list","source_id='".$convertedRecord[0]['Ref_By']."'","","","","");
										if(!empty($getRefBy[0]['ref_by'])){
											$referBy=$getRefBy[0]['ref_by'];
										}
										else{
											$referBy="Unknown";
										}
										
										if($convertedRecord[0]['Patient_status']=="2"){ $patient_status="<span class='label label-warning'>REFERRED</span>"; ?>
										<?php } else if($convertedRecord[0]['Patient_status']=="3"){  $patient_status="<span class='label label-danger'>P-AWAITING</span>";?>
										<?php } else if($convertedRecord[0]['Patient_status']=="5"){  $patient_status="<span class='label label-success'>RESPONDED</span>";?>
										<?php } else if($convertedRecord[0]['Patient_status']=="6"){  $patient_status="<span class='label label-info'>RESPONSE-PATIENT-FAILED</span>";?>
										<?php } else if($convertedRecord[0]['Patient_status']=="7"){  $patient_status="<span class='label label-info'>STAGED</span>";?>
										<?php } else if($convertedRecord[0]['Patient_status']=="8"){  $patient_status="<span class='label label-warning'>OP-DESIRED</span>";?>
										<?php } else if($convertedRecord[0]['Patient_status']=="9"){  $patient_status="<span class='label label-success'>IP-CONVERTED</span>";?>
										<?php } else if($convertedRecord[0]['Patient_status']=="10"){  $patient_status="<span class='label label-danger'>NOT-CONVERTED</span>";?>
										<?php } else if($convertedRecord[0]['Patient_status']=="11"){  $patient_status="<span class='label label-success'>INVOICED</span>";?>
										<?php } else if($convertedRecord[0]['Patient_status']=="12"){  $patient_status="<span class='label label-success'>PAYMENT RECEIVED</span>";?>
										<?php } else if($convertedRecord[0]['Patient_status']=="13"){  $patient_status="<span class='label label-success'>OP-CONVERTED</span>"; } ?>
										
																			
										<tr>
											<td><?php echo $convertedRecord[0]['Patient_Id'];  ?><?php if($convertedRecord[0]['Trans_status']=="TXN_SUCCESS"){ ?><input type="hidden" name="txtPaid" value="paid" /><img src="../images/paid_icon.png"/><?php }?></td>
                                            <td><?php echo date('M d, Y',strtotime($convertedRecord[0]['Ref_Date']));  ?></td> 
                                            <td><a href="patient-history?p=<?php echo md5($convertedRecord[0]['Patient_Id']); ?>&c=<?php echo md5($admin_id); ?>" target="_blank"><?php echo $convertedRecord[0]['Patient_Name'];  ?></a></td>
											<td><?php echo $referBy;  ?></td>
											<td><?php echo $getDoc[0]['ref_name'];  ?></td>
											<td><?php echo $convertedRecord[0]['Hosp_Name'].', '.$convertedRecord[0]['Hosp_City'].', '.$convertedRecord[0]['Hosp_State'];  ?></td>
											<td><?php echo $patient_status; ?></td>
										
											<td><a href="javascript:void(0);" id="popModal_ex<?php echo $convertedRecord[0]['Patient_Id'];  ?>" style="text-decoration:none;">
											<?php 
											$datetime1 = new DateTime($getDocResponse[0]['Chat_Date']);
											$datetime2 = new DateTime($getDocResponse[1]['Chat_Date']);
											$interval = $datetime1->diff($datetime2);
											if($convertedRecord[0]['Auto_Response']=="1"){ ?>											
											<span class="label label-danger">Auto-Response</span>
											<?php } else {?>
											<span class="label label-danger">Response</span>
											<?php } ?>

											</a>
											<br>
											 <?php if($convertedRecord[0]['Auto_Response']=="1") 
											{ ?>
											<?php //If autoresponse true then Dispay nothing
											 } else if(!empty($convertedRecord[0]['Response_Time'])){ ?>
											<i class="fa fa-clock-o"></i> <?php  echo con_min_days($convertedRecord[0]['Response_Time']); ?>
											<?php } ?>										
											</td>
                                           <div style="display:none">
											<div id="content<?php echo $convertedRecord[0]['Patient_Id'];  ?>" style="width:370px;">
												
												<div>
												<?php for($i = 0;$i<COUNT($getDocResponse);$i++) { ?>
													<p><?php echo date('M d, Y h:i:s',strtotime($getDocResponse[$i]['Chat_Date']))."<br>".$getDocResponse[$i]['Chat_Note']; ?></p>
												<?php } ?>	
													
												</div>
											</div>
											</div>
											
                                        </tr>
										<!--	Script useful for comment window-->
										<script src="https://cdn.jsdelivr.net/jquery/3.0.0-beta1/jquery.min.js"></script>
										<script src="jsPopup/popModal.js"></script>
										<script>
										$(function(){
											$('#popModal_ex<?php echo $convertedRecord[0]['Patient_Id'];  ?>').click(function(){
												$('#popModal_ex<?php echo $convertedRecord[0]['Patient_Id'];  ?>').popModal({
													html : $('#content<?php echo $convertedRecord[0]['Patient_Id'];  ?>'),
													placement : 'bottomLeft',
													showCloseBut : true,
													onDocumentClickClose : true,
													onDocumentClickClosePrevent : '',
													overflowContent : false,
													inline : true,
													asMenu : false,
													beforeLoadingContent : 'Please, wait...',
													onOkBut : function() {},
													onCancelBut : function() {},
													onLoad : function() {},
													onClose : function() {}
												});
											});
											
											/* tab */
										(function($) {
											$.fn.tab = function(method){
											
												var methods = {
													init : function(params) {

														$('.tab').click(function() {
															var curPage = $(this).attr('data-tab');
															$(this).parent().find('> .tab').each(function(){
																$(this).removeClass('active');
															});
															$(this).parent().find('+ .page_container > .page').each(function(){
																$(this).removeClass('active');
															});
															$(this).addClass('active');
															$('.page[data-page="' + curPage + '"]').addClass('active');
														});
													
													}
												};

												if (methods[method]) {
													return methods[method].apply( this, Array.prototype.slice.call(arguments, 1));
												} else if (typeof method === 'object' || ! method) {
													return methods.init.apply(this, arguments);
												}
												
											};
											$('html').tab();
											
										})(jQuery);
											
										});
										</script>
										
										
									<?php } ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

              
              
          </div>
        </div>
        <!-- /page content -->

       <?php include_once('footer.php'); ?>
      </div>
    </div>

     <!-- jQuery -->
    <script src="../Hospital/vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../Hospital/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="../Hospital/vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="../Hospital/vendors/nprogress/nprogress.js"></script>
    <!-- iCheck -->
    <script src="../Hospital/vendors/iCheck/icheck.min.js"></script>
    <!-- Datatables -->
    <script src="../Hospital/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="../Hospital/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="../Hospital/vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="../Hospital/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
    <script src="../Hospital/vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="../Hospital/vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="../Hospital/vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="../Hospital/vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
    <script src="../Hospital/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
    <script src="../Hospital/vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="../Hospital/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
    <script src="../Hospital/vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
    <script src="../Hospital/vendors/jszip/dist/jszip.min.js"></script>
    <script src="../Hospital/vendors/pdfmake/../Hospital/build/pdfmake.min.js"></script>
    <script src="../Hospital/vendors/pdfmake/../Hospital/build/vfs_fonts.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="../Hospital/build/js/custom.min.js"></script>

  </body>
</html>