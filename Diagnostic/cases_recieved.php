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
$allRecord = mysqlSelect("DISTINCT(a.patient_id) as Patient_Id","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id","b.ref_id='".$admin_id."'","a.patient_id desc","","","$eu, $limit");
$pag_result = mysqlSelect("DISTINCT(a.patient_id) as Patient_Id","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id","b.ref_id='".$admin_id."'","a.patient_id desc");
$pageing = firstPaging($pag_result,$limit,$back,$next,$eu,$field,$type2);
$arrPage = explode("-",$pageing);   
              
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cases Recieved</title>

   <?php include_once('support.php'); ?>
	<link href="../assets/css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">	
	<link href="../assets/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">


</head>

<body>

    <div id="wrapper">

    <?php include_once('sidemenu.php'); ?>

        <div id="page-wrapper" class="gray-bg">
        <?php include_once('header_top.php'); ?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>Cases Recieved</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="Home">Home</a>
                        </li>
                        
                        <li class="active">
                            <strong>Cases Recieved</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-2">

                </div>
            </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Recieved Cases List</h5>
                        
                    </div>
                    <div class="ibox-content">

                        <table class="table table-striped">
                            <thead>
                            <tr>
								<th>Name</th>
                                <th>Patient ID</th>
                                <th>Reg. Date</th>
                               <th>Referred By</th>
								<th>Status</th>
								<th>Response Time</th>
                            </tr>
                            </thead>
                            <tbody>
							 <?php foreach($allRecord as $list){ 
										
										$getPatDetails = mysqlSelect("a.patient_name as Patient_Name,a.TImestamp as Reg_Date,a.patient_id as Patient_Id,c.source_name as Ref_By,b.ref_id as Doc_Id,a.transaction_status as Pay_Status,b.status2 as status2,b.response_time as Response_Time,b.response_status as Auto_Response","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join source_list as c on c.source_id=a.patient_src","a.patient_id='".$list['Patient_Id']."' and b.ref_id='".$admin_id."'","","","","");
										$getCurrentStatus = mysqlSelect("*","patient_referal","patient_id='".$list['patient_id']."'","","","","");
										$getReferredBy = mysqlSelect("*","patient_referal","patient_id='".$list['patient_id']."'","","","","");	
										$getDocResponse = mysqlSelect("TImestamp as Chat_Date,chat_note as Chat_Note","chat_notification","patient_id='".$list['patient_id']."'and ref_id='".$admin_id."'","","","","");
																				
										if($getPatDetails[0]['status2']=="2"){ $patient_status="<span class='label label-warning'>PENDING</span>"; ?>
										<?php } else if($getPatDetails[0]['status2']=="3"){  $patient_status="<span class='label label-danger'>P-AWAITING</span>";?>
										<?php } else if($getPatDetails[0]['status2']=="5"){  $patient_status="<span class='label label-success'>RESPONDED</span>";?>
										<?php } else if($getPatDetails[0]['status2']=="6"){  $patient_status="<span class='label label-info'>RESPONSE-PATIENT-FAILED</span>";?>
										<?php } else if($getPatDetails[0]['status2']=="7"){  $patient_status="<span class='label label-info'>STAGED</span>";?>
										<?php } else if($getPatDetails[0]['status2']=="8"){  $patient_status="<span class='label label-warning'>OP-DESIRED</span>";?>
										<?php } else if($getPatDetails[0]['status2']=="9"){  $patient_status="<span class='label label-success'>IP-CONVERTED</span>";?>
										<?php } else if($getPatDetails[0]['status2']=="10"){  $patient_status="<span class='label label-danger'>NOT-CONVERTED</span>";?>
										<?php } else if($getPatDetails[0]['status2']=="11"){  $patient_status="<span class='label label-success'>INVOICED</span>";?>
										<?php } else if($getPatDetails[0]['status2']=="12"){  $patient_status="<span class='label label-success'>PAYMENT RECEIVED</span>";?>
										<?php } else if($getPatDetails[0]['status2']=="13"){  $patient_status="<span class='label label-success'>OP-CONVERTED</span>";?>
										<?php } else if($getPatDetails[0]['status2']=="1"){  $patient_status="<span class='label label-primary'>NEW</span>"; } ?>
										
                            <a href="Home"><tr>
							<td><a href="patient-history?p=<?php echo md5($getPatDetails[0]['Patient_Id']);  ?>"><?php echo $getPatDetails[0]['Patient_Name'];  ?></a></td>
								<td><?php echo $getPatDetails[0]['Patient_Id'];  ?></td>
                                <td><?php echo date('M d, Y',strtotime($getPatDetails[0]['Reg_Date']));  ?></td>
                                <td><?php echo $getPatDetails[0]['Ref_By'];  ?></td>
                                <td> <?php echo $patient_status; ?></td>
								<td>
											 <?php if($getPatDetails[0]['Auto_Response']=="1") 
											{ ?>
											<?php echo "NE";
											 } else if(!empty($getPatDetails[0]['Response_Time'])){ ?>
											<i class="fa fa-clock-o"></i> <?php  echo con_min_days($getPatDetails[0]['Response_Time']); ?>
											<?php } ?>
								</td>
                            </tr></a>
                            <?php } ?>
                            </tbody>
                        </table>
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

    <!-- Peity -->
    <script src="../assets/js/plugins/peity/jquery.peity.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="../assets/js/inspinia.js"></script>
    <script src="../assets/js/plugins/pace/pace.min.js"></script>

    <!-- iCheck -->
    <script src="../assets/js/plugins/iCheck/icheck.min.js"></script>

    <!-- Peity -->
    <script src="../assets/js/demo/peity-demo.js"></script>

    <script>
        $(document).ready(function(){
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
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
</body>

</html>
