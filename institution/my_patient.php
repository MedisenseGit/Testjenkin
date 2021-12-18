<?php
ob_start();
error_reporting(0); 
session_start();

$admin_id = $_SESSION['user_id'];
$secretary_id = $_SESSION['secretary_id'];
//Get the page name 
//$request_uri  = str_replace("", "", $_SERVER['REQUEST_URI']);
include('functions.php');
if(empty($admin_id)){
	header("Location:index.php");
}

date_default_timezone_set('Asia/Kolkata');
	$Cur_Date=date('Y-m-d H:i:s');
	$cur_Date=date('Y-m-d',strtotime($Cur_Date));
	
$curYear = date('Y');
$curMonth = date('M');
require_once("../classes/querymaker.class.php");

				if(!isset($_GET['start'])) {
			$start = 0;
			}else{
			$start = $_GET['start'];
			}

			$eu = ($start - 0); 
			$limit = 10;         // No of records to be shown per page.
			$this1 = $eu + $limit; 
			$back = $eu - $limit; 
			$next = $eu + $limit;
			
			
// $allRecord = mysqlSelect("d.episode_id,d.patient_id,d.admin_id","doc_my_patient as a inner join doctor_hosp as b on a.doc_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id inner join doc_patient_episodes as d on d.admin_id=a.doc_id","c.company_id='".$admin_id."' and d.chkPatConsent='1'","","d.episode_id","","$eu, $limit");

//$allRecord = mysqlSelect("d.episode_id,d.patient_id,d.admin_id","doc_my_patient as a inner join doctor_hosp as b on a.doc_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id inner join doc_patient_episodes as d on d.admin_id=a.doc_id","d.chkPatConsent='1'","","d.episode_id","","$eu, $limit");

$allRecord = mysqlSelect("e.episode_id,e.patient_id,e.admin_id","patients_appointment  as a inner join  patients_transactions as b on a.patient_id=b.patient_id
INNER JOIN doctor_hosp as c on c.doc_id=b.doc_id INNER join hosp_tab as d on c.hosp_id=c.hosp_id  INNER join doc_patient_episodes as e on e.admin_id=b.doc_id","e.chkPatConsent='1' ","","e.episode_id","","$eu, $limit");



//$pag_result = mysqlSelect("DISTINCT d.episode_id","doc_my_patient as a inner join doctor_hosp as b on a.doc_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id inner join doc_patient_episodes as d on d.admin_id=a.doc_id","d.chkPatConsent=1","");

$pag_result = mysqlSelect("DISTINCT e.episode_id","patients_appointment as a INNER JOIN patients_transactions as b ON a.patient_id = b.patient_id inner join doctor_hosp as c on c.doc_id=b.doc_id  inner join hosp_tab as d on d.hosp_id=c.hosp_id  inner join doc_patient_episodes as e on e.admin_id=b.doc_id","e.chkPatConsent=1","");






$nume = count($pag_result);  
			

?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Patients</title>

   <?php include_once('support.php'); ?>
		<!-- FooTable -->
    <link href="../assets/css/plugins/footable/footable.core.css" rel="stylesheet">
	<link href="../assets/css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">	
	<link href="../assets/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">
	<!-- orris -->
    <link href="../assets/css/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">
	<link href="../assets/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
	<link href="../assets/css/plugins/jsTree/style.min.css" rel="stylesheet">
	

<script type="text/javascript" src="../premium/js/jquery.js"></script>
<script type="text/javascript" src="../premium/js/jquery-ui.js"></script>
<link rel="stylesheet" href="../premium/css/jquery-ui.css">
<script type="text/javascript">
$(function() 
{
 $( "#coding_language" ).autocomplete({
  source: 'get_pincode.php'
 });
});

function validateFloatKeyPress(el, evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    var number = el.value.split('.');
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    //just one dot
    if(number.length>1 && charCode == 46){
         return false;
    }
    //get the carat position
    var caratPos = getSelectionStart(el);
    var dotPos = el.value.indexOf(".");
    if( caratPos > dotPos && dotPos>-1 && (number[1].length > 1)){
        return false;
    }
    return true;
}

function getSelectionStart(o) {
	if (o.createTextRange) {
		var r = document.selection.createRange().duplicate()
		r.moveEnd('character', o.value.length)
		if (r.text == '') return o.value.length
		return o.value.lastIndexOf(r.text)
	} else return o.selectionStart
}
</script>

</head>

<body>

    <div id="wrapper">

    <?php include_once('sidemenu.php'); ?>

        <div id="page-wrapper" class="gray-bg">
        <?php include_once('header_top.php'); ?>
		 <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>Referred EMR</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="Home">Home</a>
                        </li>
                        
                        <li class="active">
                            <strong>Referred EMR</strong>
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
                        <h5><i class="fa fa-wheelchair"></i> Referred EMR Visits</h5>
                       <span class="text-navy pull-right">Total EMR Count: <?php echo $nume; ?></span>
                    </div>
                    <div class="ibox-content">
					 
					<div id="allPatient">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Referred Doctor</th>                           
								<th>Contact Details</th>
								<th>Diagnosis Status</th>
								<th>Pharmacy Status</th>
                            </tr>
                            </thead>
                            <tbody>
							 <?php foreach($allRecord as $list){ 
							 //$patDetails = mysqlSelect("*","doc_my_patient","patient_id='".$list['patient_id']."'","","","","");
							 $patDetails = mysqlSelect("a.patient_id as patient_id,a.patient_name as patient_name,a.patient_mobile as patient_mob,a.patient_gender as patient_gen,b.city as patient_loc ,b.state as pat_state,b.country as pat_country,b.address as patient_addrs,b.hyper_cond as hyper_cond,b.diabetes_cond as diabetes_cond,b.smoking as smoking,b.drug_abuse as drug_abuse,b.prev_intervention as prev_inter,b.other_details as other_details,b.diabetes_cond as diabetes_cond,b.alcoholic as alcoholic,b.family_history as family_history,b.neuro_issue as neuro_issue,b.kidney_issue as kidney_issue,a.patient_email as patient_email,a.patient_mobile as patient_mob","patients_appointment as a inner join patients_transactions as b on a.patient_id=b.patient_id","a.patient_id='".$list['patient_id']."'","","","","");
							 
							 $docDetails = mysqlSelect("*","referal","ref_id='".$list['admin_id']."'","","","","");
							 
?>
										
										
                            <a href="Home"><tr id="myTableRow<?php echo $patDetails[0]['patient_id']; ?>">
                               <td><a href="<?php echo $_SESSION['EMR_URL']; ?>?p=<?php echo md5($list['patient_id']); ?>&e=<?php echo md5($list['episode_id']); ?>"><?php echo $patDetails[0]['patient_name'];  ?></a></td>
								<td><?php echo $docDetails[0]['ref_name']; ?></td>
                                <td><?php if(!empty($patDetails[0]['patient_email'])){ ?><i class="fa fa-envelope"></i> <?php echo $patDetails[0]['patient_email'];  ?><br><?php } ?>
											<?php if(!empty($patDetails[0]['patient_mob'])){ ?><i class="fa fa-mobile"></i> <?php echo $patDetails[0]['patient_mob'];  ?><?php } ?></td>
                               <td>
							    <?php 
								$getChatStatus= mysqlSelect("*,MAX(status) as status1","emr_referred_notifications","patient_id='".$list['patient_id']."' and episode_id='".$list['episode_id']."' and (type='1')","chat_id desc","refer_id","","");
							 $getChatStatusPharma= mysqlSelect("*,MAX(status) as status1","emr_referred_notifications","patient_id='".$list['patient_id']."' and episode_id='".$list['episode_id']."' and (type='2')","chat_id desc","refer_id","","");
								
							foreach($getChatStatus as $getChatlist){
								$getDiagno= mysqlSelect("*","Diagnostic_center","diagnostic_id='".$getChatlist['refer_id']."'");
							 if($getChatlist['status1']=="1"){ $patient_status="<span class='label label-warning'>PENDING</span>"; ?>
										<?php } else if($getChatlist['status1']=="2"){  $patient_status="<span class='label label-info'>Sent To Diagnosis</span>";?>
										<?php } else if($getChatlist['status1']=="3"){  $patient_status="<span class='label label-info'>Sent to Pharmacy</span>";?>
										<?php } else if($getChatlist['status1']=="4"){  $patient_status="<span class='label label-info'>PAYMENT LINK SENT</span>";?>
										<?php } else if($getChatlist['status1']=="5"){  $patient_status="<span class='label label-success'>PAYMENT SUCCESS</span>";?>
										<?php } else if($getChatlist['status1']=="6"){  $patient_status="<span class='label label-warning'>ORDERED</span>";?>
										<?php } else if($getChatlist['status1']=="7"){  $patient_status="<span class='label label-success'>COMPLETED</span>";?>
										<?php }
										if($getChatlist['status1']=="1"){ ?>  <?php echo $patient_status .'<br/><br/>'; ?><?php } ?>
									   <?php if($getChatlist['type']=="1"){ ?>  <?php echo $getDiagno[0]['diagnosis_name'].' : '.$patient_status.'<br/><br/>'; ?><?php } 
							} ?>
										
										
										
									  
							   <td>
							   <?php  foreach($getChatStatusPharma as $getChatPharmalist){
								   $getDiagno= mysqlSelect("*","pharma","pharma_id='".$getChatPharmalist['refer_id']."'");
								   if($getChatPharmalist['status1']=="3"){  $patient_status1="<span class='label label-info'>Sent to Pharmacy</span>";?>
								<?php } else if($getChatPharmalist['status1']=="4"){  $patient_status1="<span class='label label-info'>PAYMENT LINK SENT</span>";?>
								<?php } else if($getChatPharmalist['status1']=="5"){  $patient_status1="<span class='label label-success'>PAYMENT SUCCESS</span>";?>
								<?php } else if($getChatPharmalist['status1']=="6"){  $patient_status1="<span class='label label-warning'>ORDERED</span>";?>
								<?php } else if($getChatPharmalist['status1']=="7"){  $patient_status1="<span class='label label-success'>COMPLETED</span>";?>
								<?php } 
							   ?> <?php if($getChatPharmalist['type']=="2"){ ?><?php echo $getDiagno[0]['pharma_name'].' : '.$patient_status1.'<br/><br/>'; ?><?php } } ?></td></td>
                            </tr></a>
                            <?php } ?>
                            </tbody>
                        </table>
						</div>
						<div id="afterDel"></div>
						<?php if($nume>=$limit){ ?>
						<div class="row">
					 
					<?php  
				if($nume>=500){
					$tot_num=500;
				}else {
					$tot_num=count($pag_result);
				}
				$this1 = $eu + $limit; 
				
				if($back >=0){ ?>
				<a href='<?php echo $_SERVER['PHP_SELF'];?>?start=<?php echo $back; ?>' class='btn btn-white'><i class='fa fa-chevron-left'></i></a> 
				<?php }else{
				 
				}
				
				$i=0;
				$l=1;
				
				for($i=0;$i < $tot_num;$i=$i+$limit){
					if($i <> $eu){ ?>
				<a href='<?php echo $_SERVER['PHP_SELF'];?>?start=<?php echo $i; ?>'><button class='btn btn-white' ><?php echo $l; ?></button></a>
				<?php	} else { ?>
				<button class='btn btn-white active' ><?php echo  $l; ?></button>
				<?php	}
					$l=$l+1;
				}
				
				if($this1 < $nume) { ?>					
				<a href='<?php echo $_SERVER['PHP_SELF'];?>?start=<?php echo $next; ?>' class='btn btn-white'><i class='fa fa-chevron-right'></i></button></a>
				<?php } else 
				{
				
				}
				?>
				
                    </div>
						<?php } ?>					
					
                    </div>
                </div>
            </div>
          
            </div>
                       
        </div>
         <?php include_once('footer.php'); ?>

        </div>
        </div>

<!-- Sweet alert -->
	<script src="../assets/js/plugins/sweetalert/sweetalert.min.js"></script>
<script src="../assets/js/jquery-3.1.1.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="../assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="../assets/js/inspinia.js"></script>
    <script src="../assets/js/plugins/pace/pace.min.js"></script>
	 <!-- FooTable -->
    <script src="../assets/js/plugins/footable/footable.all.min.js"></script>
    <!-- Page-Level Scripts -->
    <script>
        $(document).ready(function() {

            $('.footable').footable();
            $('.footable2').footable();

        });

    </script>
	
	
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="../assets/js/custom.min.js"></script>


<!-- Chosen -->
    <script src="../assets/js/plugins/chosen/chosen.jquery.js"></script>
	<script>
       
        $('.chosen-select').chosen({width: "100%"});

	$(document).ready(function() {
	$(".oceanIn").keyup(function() {
  	var total = 0.0;
    $.each($(".oceanIn"), function(key, input) {
      if(input.value && !isNaN(input.value)) {
        total += parseFloat(input.value);
      }
    });
    $("#oceanTotal").html("Total: " + total);
  });
});

    </script>
	<!-- Switchery -->
   <script src="../assets/js/plugins/switchery/switchery.js"></script>
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
			
			$('#dateadded1').datepicker({
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
 <!-- Typehead -->
    <script src="../assets/js/plugins/typehead/bootstrap3-typeahead.min.js"></script>

	
<script>
$(document).ready(function(){
<?php 
$get_PatientDetails = mysqlSelect("a.patient_id as patient_id,a.patient_name as patient_name,a.patient_mobile as patient_mob","patients_appointment as a inner join patients_transactions as b on a.patient_id=b.patient_id","b.doc_id='".$admin_id."'","","","","");
?>
$('.typeahead_1').typeahead({
   source: [<?php foreach($get_PatientDetails as $listPat){ echo "'".$listPat['patient_id']."-".$listPat['patient_name']."-".$listPat['patient_mob']."',"; }?>]
});
			  
});
		
</script>
	<script src="../assets/js/plugins/jsTree/jstree.min.js"></script>

<style>
    .jstree-open > .jstree-anchor > .fa-folder:before {
        content: "\f07c";
    }

    .jstree-default .jstree-icon.none {
        width: 0;
    }
</style>

<script>
    $(document).ready(function(){

        $('#jstree1').on('click', '.jstree-anchor', function (e) {
    $('#jstree1').jstree(true).toggle_node(e.target);
  }).jstree({
            'core' : {
                'check_callback' : true
            },
            'plugins' : [ 'types', 'dnd' ],
            'types' : {
                'default' : {
                    'icon' : 'fa fa-folder'
                },
                'html' : {
                    'icon' : 'fa fa-file-code-o'
                },
                'svg' : {
                    'icon' : 'fa fa-file-picture-o'
                },
                'css' : {
                    'icon' : 'fa fa-file-code-o'
                },
                'img' : {
                    'icon' : 'fa fa-file-image-o'
                },
                'js' : {
                    'icon' : 'fa fa-file-text-o'
                }

            }
        });

        $('#using_json').jstree({
            'core' : {
            'data' : [
                'Empty Folder',
                {
                    'text': 'Resources',
                    'state': {
                        'opened': true
                    },
                    'children': [
                        {
                            'data': 'css',
                            'children': [
                                {
                                    "data":"Facebook", "metadata":{"href":"http://www.fb.com"}
                                },
                                {
                                    'text': 'bootstrap.css', 'icon': 'none'
                                },
                                {
                                    'text': 'main.css', 'icon': 'none'
                                },
                                {
                                    'text': 'style.css', 'icon': 'none'
                                }
                            ],
                            'state': {
                                'opened': true
                            }
                        },
                        {
                            'text': 'js',
                            'children': [
                                {
                                    'text': 'bootstrap.js', 'icon': 'none'
                                },
                                {
                                    'text': 'inspinia.min.js', 'icon': 'none'
                                },
                                {
                                    'text': 'jquery.min.js', 'icon': 'none'
                                },
                                {
                                    'text': 'jsTree.min.js', 'icon': 'none'
                                },
                                {
                                    'text': 'custom.min.js', 'icon': 'none'
                                }
                            ],
                            'state': {
                                'opened': true
                            }
                        },
                        {
                            'text': 'html',
                            'children': [
                                {
                                    'text': 'layout.html', 'icon': 'none'
                                },
                                {
                                    'text': 'navigation.html', 'icon': 'none'
                                },
                                {
                                    'text': 'navbar.html', 'icon': 'none'
                                },
                                {
                                    'text': 'footer.html', 'icon': 'none'
                                },
                                {
                                    'text': 'sidebar.html', 'icon': 'none'
                                }
                            ],
                            'state': {
                                'opened': true
                            }
                        }
                    ]
                },
                'Fonts',
                'Images',
                'Scripts',
                'Templates',
            ]
        } });

    });
</script>
<script language="JavaScript" src="js/status_validationJs.js"></script>
</body>

</html>
