<?php
ob_start();
error_reporting(0); 
session_start();

$admin_id = $_SESSION['user_id'];
$secretary_id = $_SESSION['secretary_id'];
//Get the page name 
//$request_uri  = str_replace("", "", $_SERVER['REQUEST_URI']);
include('functions.php');
if(empty($admin_id))
{
	header("Location:index.php");
}

date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d H:i:s');
$cur_Date=date('Y-m-d',strtotime($Cur_Date));
	
$curYear = date('Y');
$curMonth = date('M');
require_once("../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();

if(isset($_POST['cmdSearch']))
{
	$params     	= split("-", $_POST['search']);
	$patientid 		= $params[0];
	$patient_tab 	= mysqlSelect("a.patient_id as patient_id,a.patient_name as patient_name,a.patient_mobile as patient_mob,a.patient_gender as patient_gen,b.city as patient_loc ,b.state as pat_state,b.country as pat_country,b.address as patient_addrs,b.hyper_cond as hyper_cond,b.diabetes_cond as diabetes_cond,b.smoking as smoking,b.drug_abuse as drug_abuse,b.prev_intervention as prev_inter,b.other_details as other_details,b.diabetes_cond as diabetes_cond,b.alcoholic as alcoholic,b.family_history as family_history,b.neuro_issue as neuro_issue,b.kidney_issue as kidney_issue,b.height_cms as height,b.weight as weight,b.patient_age as patient_age,a.patient_email as patient_email","patients_appointment as a inner join patients_transactions as b on a.patient_id=b.patient_id","a.patient_id='".$patientid."'","","","","");
	if($patient_tab[0]['patient_gen']==1)
	{
		$gender ="Male";
	}
	else if($patient_tab[0]['patient_gen']==2)
	{
		$gender ="Female";
	}
}
if(isset($_POST['cmdFreq']))
{
	$patient_tab = mysqlSelect("a.patient_id as patient_id,a.patient_name as patient_name,a.patient_mobile as patient_mob,a.patient_gender as patient_gen,b.city as patient_loc ,b.state as pat_state,b.country as pat_country,b.address as patient_addrs,b.hyper_cond as hyper_cond,b.diabetes_cond as diabetes_cond,b.smoking as smoking,b.drug_abuse as drug_abuse,b.prev_intervention as prev_inter,b.other_details as other_details,b.diabetes_cond as diabetes_cond,b.alcoholic as alcoholic,b.family_history as family_history,b.neuro_issue as neuro_issue,b.kidney_issue as kidney_issue,b.height_cms as height,b.weight as weight,b.patient_age as patient_age,a.patient_email as patient_email","patients_appointment as a inner join patients_transactions as b on a.patient_id=b.patient_id","a.patient_id='".$_POST['patient_id']."'","","","","");
	if($patient_tab[0]['patient_gen']==1)
	{
		$gender ="Male";
	}
	else if($patient_tab[0]['patient_gen']==2)
	{
		$gender ="Female";
	}
	$getSummaryDetails = mysqlSelect("discharge_summary","patient_discharge_summaray","doc_id='".$admin_id."' and template_id='".$_POST['disch_temp_id']."'","","","","");
	
		
}
if(isset($_POST['cmdUpdateDischarge']))
{
	$patient_tab = mysqlSelect("a.patient_id as patient_id,a.patient_name as patient_name,a.patient_mobile as patient_mob,a.patient_gender as patient_gen,b.city as patient_loc ,b.state as pat_state,b.country as pat_country,b.address as patient_addrs,b.hyper_cond as hyper_cond,b.diabetes_cond as diabetes_cond,b.smoking as smoking,b.drug_abuse as drug_abuse,b.prev_intervention as prev_inter,b.other_details as other_details,b.diabetes_cond as diabetes_cond,b.alcoholic as alcoholic,b.family_history as family_history,b.neuro_issue as neuro_issue,b.kidney_issue as kidney_issue,b.height_cms as height,b.weight as weight,b.patient_age as patient_age,a.patient_email as patient_email","patients_appointment as a inner join patients_transactions as b on a.patient_id=b.patient_id","a.patient_id='".$_POST['patient_id']."'","","","","");
	if($patient_tab[0]['patient_gen']==1)
	{
		$gender ="Male";
	}
	else if($patient_tab[0]['patient_gen']==2)
	{
		$gender ="Female";
	}
	$getSummaryDetails = mysqlSelect("discharge_id,discharge_summary","patient_discharge_summaray","doc_id='".$admin_id."' and discharge_id='".$_POST['discharge_id']."'","","","","");
	
		
}
				

?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Discharge Summary</title>

   <?php include_once('support.php'); ?>
		<!-- FooTable -->
    <link href="../assets/css/plugins/footable/footable.core.css" rel="stylesheet">
	<link href="../assets/css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">	
	<link href="../assets/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">
	<!-- orris -->
    <link href="../assets/css/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">
	<link href="../assets/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
	<link href="../assets/css/plugins/jsTree/style.min.css" rel="stylesheet">
	

<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery-ui.js"></script>
<link rel="stylesheet" href="css/jquery-ui.css">
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
  <script src="js/jquery-1.12.4.min.js"></script>
    <script type="text/javascript" src="date-time-picker.min.js"></script>
</head>

<body>

    <div id="wrapper">

    <?php include_once('sidemenu.php'); ?>

        <div id="page-wrapper" class="gray-bg">
        <?php include_once('header_top.php'); ?>
		<div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-12 mgTop">
					<div class="search-form">
                                <form method="post" autocomplete="off">
								<input type="hidden" name="curURI" value="Discharge-Summary" />
                                    <div class="input-group">
				
                                       <input type="text" id="serPatient" placeholder="Enter patient name or mobile number to search an existing patient" name="search" value="" class="form-control input-lg typeahead_1">
                                        <div class="input-group-btn">
                                            <button class="btn btn-lg btn-primary m-r" name="cmdSearch" type="submit">
                                                <i class="fa fa-search"></i> Search
                                            </button>&nbsp;&nbsp;&nbsp;
											
											
                                        </div>
                                    </div>

                                </form>
                    </div>  

				
							
							
							
			   </div>
		</div>
        <div class="wrapper wrapper-content animated fadeInRight">
            
			<div class="row">
			
                        <div class="col-md-6">
                            <div class="ibox-content">
							 <form class="form-horizontal" method="post" action="convert_pdf.php">
                                <div class="form-group"><label class="col-lg-4 control-label">Patient Name:</label>

                                    <div class="col-lg-8 m-t-xs"> <?php echo $patient_tab[0]['patient_name']; ?></div>
                                </div>
								<div class="form-group"><label class="col-lg-4 control-label">Age: </label>

                                    <div class="col-lg-8 m-t-xs"><?php echo $patient_tab[0]['patient_age']; ?></div>
                                </div>
								<div class="form-group"><label class="col-lg-4 control-label">Gender: </label>

                                    <div class="col-lg-8 m-t-xs"><?php echo $gender; ?></div>
                                </div>
								<div class="form-group"><label class="col-lg-4 control-label">Contact No. : </label>

                                    <div class="col-lg-8 m-t-xs"><?php echo $patient_tab[0]['patient_mob']; ?></div>
                                </div>
								<div class="form-group"><label class="col-lg-4 control-label">Address : </label>

                                    <div class="col-lg-8 m-t-xs"><?php echo $patient_tab[0]['patient_addrs']."<br>".$patient_tab[0]['pat_state']; ?></div>
                                </div>
                               </form>
								
                             </div>         
                            
                        </div>
                        <!--<div class="col-md-8">
                            <div class="ibox-content">
                                <form class="form-horizontal" method="post" action="convert_pdf.php">
									 <div class="form-group"><label class="col-lg-2 control-label">MR No:</label>

                                    <div class="col-lg-6"><input type="text" placeholder="MR No" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group"><label class="col-lg-2 control-label">IP No:</label>

                                    <div class="col-lg-6"><input type="text" placeholder="IP No" class="form-control"></div>
                                </div>
								 <div class="form-group"><label class="col-lg-2 control-label">Date of Admission:</label>

                                    <div class="col-lg-6"><input id="J-demo-01" name="J-demo-01" type="text" placeholder="YYYY/MM/DD H:M:S" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group"><label class="col-lg-2 control-label">Date of Discharge:</label>

                                    <div class="col-lg-6"><input id="J-demo-02" name="J-demo-02" type="text" placeholder="YYYY/MM/DD H:M:S" class="form-control"></div>
									<script type="text/javascript">
									$('#J-demo-01').dateTimePicker({
											mode: 'dateTime'
										});
										$('#J-demo-02').dateTimePicker({
											mode: 'dateTime'
										});
									</script>
                                </div>
								
								</form>
                            </div>
                        </div>-->
                    </div>
					<div class="row">
					<div class="col-lg-12 m-t-xl">				
								<?php $last_summary = mysqlSelect("*","doc_discharge_summary_templates","doc_id='".$admin_id."'","disch_temp_id DESC","","","10");
								
								if(COUNT($last_summary)>0) { ?>
								<label class="pull-left">Frequently used:  </label>
								<?php 
								while(list($key_summary, $value_summary) = each($last_summary))
								{
								
								?>
								<form method="post" class="pull-left" >
								<input type="hidden" name="disch_temp_id" value="<?php echo $value_summary['disch_temp_id']; ?>" />
								<input type="hidden" name="patient_id" value="<?php echo $patient_tab[0]['patient_id']; ?>" />
								<button type="submit" class="btn btn-xs btn-white m-l" name="cmdFreq"  title="<?php echo $value_summary['template_name']; ?>" ><code> <?php echo $value_summary['template_name']; ?></code></button>
								</form>
								<?php }
								} ?>
					</div>
					</div>
					<form class="form-horizontal" method="post" action="add_details.php">
					<input type="hidden" name="patient_id" value="<?php echo $patient_tab[0]['patient_id']; ?>" />
					<input type="hidden" name="discharge_id" value="<?php echo $getSummaryDetails[0]['discharge_id']; ?>" />
					<div class="row">
						 <div class="col-lg-12">
							<div class="form-group ">
								<div class="col-sm-12  m-t-xl"><textarea class="form-control"  name="descr" rows="35"><?php echo $getSummaryDetails[0]['discharge_summary']; ?></textarea></div>
								
							</div>	
                         </div>
					</div><br>
					<?php if(isset($_POST['cmdSearch']) || isset($_POST['cmdFreq'])){ ?>
					<div class="row">
						<div class="col-lg-6">
								
									<dl>
									 <dt><label> <input type="checkbox" class="i-checks" name="chkSaveTemplate" id="chkSaveTemplate" value="1"> Save this as template</label></dt><br> <dd><input type="text" name="template_name" id="template_name" placeholder="Template Name" style="display: none;" class="form-control"></dd><br>
									</dl>
									
								</div>
					</div>
					<?php } ?>
					<div class="row">
					<div class="form-group">
								
								<div class="col-sm-2 pull-right">
								<?php if(isset($_POST['cmdSearch']) || isset($_POST['cmdFreq'])){ ?>
								<button type="submit" name="cmdSaveDischarge" class="btn btn-primary block full-width m-b "><i class="fa fa-paper-plane"></i> SAVE</button>
								
								<?php } else if(isset($_POST['cmdUpdateDischarge'])){ ?>
								<button type="submit" name="cmdUpdateDischarge" class="btn btn-primary block full-width m-b "><i class="fa fa-paper-plane"></i> UPDATE</button>
									
								<?php } ?>
									</div>
								
					</div>
					</div>
					</form>
                       
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
$get_PatientDetails = mysqlSelect("a.patient_id as patient_id,a.patient_name as patient_name,a.patient_mobile as patient_mob","patients_appointment as a inner join patients_transactions as b on a.patient_id=b.patient_id","doc_id='".$admin_id."'","","","","");
					
?>
$('.typeahead_1').typeahead({
source: [<?php foreach($get_PatientDetails as $listPat){ echo "'".$listPat['patient_id']."-".$listPat['patient_name']."-".$listPat['patient_mob']."',"; }?>]
});


});

</script>
	
<script language="JavaScript" src="js/status_validationJs.js"></script>
<!-- SUMMERNOTE -->
<script src="../assets/js/plugins/summernote/summernote.min.js"></script>
<script>
$(document).ready(function(){

$('#chkSaveTemplate').click(function() {
$("#template_name").toggle();
});

});


</script>
	
</body>
<script src="js/symptoms.js"></script>
</html>
