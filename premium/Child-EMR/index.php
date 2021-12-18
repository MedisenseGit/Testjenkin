<?php
ob_start();
error_reporting(0); 
session_start();

$admin_id = $_SESSION['user_id'];
$secretary_id = $_SESSION['secretary_id'];
$secretary_name = $_SESSION['user_name'];
include('../functions.php');
if(empty($admin_id)){
	header("Location:../index.php");
}

date_default_timezone_set('Asia/Kolkata');
	$Cur_Date=date('Y-m-d H:i:s');
	$cur_Date=date('Y-m-d',strtotime($Cur_Date));
	$add_days = 3;
	$Follow_Date = date('d-m-Y',strtotime($cur_Date) + (24*3600*$add_days));
	
require_once("../../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();
       

$patient_tab = mysqlSelect("*","doc_my_patient","md5(patient_id)='".$_GET['p']."'","","","","");
	$_SESSION['patient_id']=$patient_tab[0]['patient_id'];
	if($patient_tab[0]['patient_gen']=="1"){
		$gender="Male";
	}
	else if($patient_tab[0]['patient_gen']=="2"){
		$gender="Female";
	}

	if($patient_tab[0]['hyper_cond']=="2"){
		$hyperStatus="No";
	}
	else if($patient_tab[0]['hyper_cond']=="1"){
		$hyperStatus="Yes";
	}
	if($patient_tab[0]['diabetes_cond']=="2"){
		$diabetesStatus="No";
	}
	else if($patient_tab[0]['diabetes_cond']=="1"){
		$diabetesStatus="Yes";
	}
	
	
$child_tab = mysqlSelect("*","child_tab","md5(patient_id)='".$_GET['p']."'","","","","");
$parent_tab = mysqlSelect("*","parents_tab","parent_id='".$child_tab[0]['parent_id']."'","","","","");
	$patient_id = $patient_tab[0]['patient_id'];
	$get_doc_details = mysqlSelect("*","referal","ref_id='".$admin_id."'","","","","");	
	$checkSetting= mysqlSelect("*","doctor_settings","doc_id='".$admin_id."' and doc_type='1'","","","","");
	
	if(isset($_POST['cmdchangePatient']))
	{
		$params     = split("-", $_POST['slct_valPat']);
		if($params[0]!=0){
		$patientid = $params[0];
		header("Location:".$_SESSION['EMR_URL'].md5($patientid));
		}
		else
		{
		$patientid = "0";
				
		header("Location:".$_SESSION['EMR_URL'].$patientid."&n=".$params[0]);
		}
	}
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="refresh" content="1800"/>
    <title>My Patient Profile</title>

    <link rel="icon" href="../../assets/img/favicon_icon.png">
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/font-awesome/css/font-awesome.css" rel="stylesheet">
	  <link href="../../assets/css/plugins/slick/slick.css" rel="stylesheet">
    <link href="../../assets/css/plugins/slick/slick-theme.css" rel="stylesheet">
    <link href="../../assets/css/animate.css" rel="stylesheet">
    <link href="../../assets/css/style.css" rel="stylesheet">
	
	<!-- Bootstrap Tour -->
    <link href="../../assets/css/plugins/bootstrapTour/bootstrap-tour.min.css" rel="stylesheet">
	<!-- Sweet Alert -->
    <link href="../../assets/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
	<!-- FooTable -->
    <link href="../../assets/css/plugins/footable/footable.core.css" rel="stylesheet">
	<link href="../../assets/css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">	
	<link href="../../assets/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">
	<!-- orris -->
    <link href="../../assets/css/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">
	<link href="../../assets/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
	<link href="../../assets/css/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet">
	<!-- Toastr style -->
    <link href="../../assets/css/plugins/toastr/toastr.min.css" rel="stylesheet">
	
	<link href="../fileUpload/css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>
    <link href="../fileUpload/themes/explorer-fa/theme.css" media="all" rel="stylesheet" type="text/css"/>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="../fileUpload/js/plugins/sortable.js" type="text/javascript"></script>
    <script src="../fileUpload/js/fileinput.js" type="text/javascript"></script>
    <script src="../fileUpload/js/locales/fr.js" type="text/javascript"></script>
    <script src="../fileUpload/js/locales/es.js" type="text/javascript"></script>
    <script src="../fileUpload/themes/explorer-fa/theme.js" type="text/javascript"></script>
    <script src="../fileUpload/themes/fa/theme.js" type="text/javascript"></script>
	<script language="JavaScript" src="../js/status_validationJs.js"></script>
	<script src="../js/Chart.bundle.js"></script>
	<script src="../js/utils.js"></script>
	<link href="../../assets/css/plugins/iCheck/custom.css" rel="stylesheet">
	
	<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/jquery-ui.js"></script>
<link rel="stylesheet" href="../css/jquery-ui.css">
	<?php if($_GET['p']==="0"){?>
<script type="text/javascript">
    $(window).on('load',function(){
        $('#myModal').modal('show');
    });
</script>
<?php } if($_GET['w']==="1"){?>
<script type="text/javascript">
    $(window).on('load',function(){
        $('#myModaWaiting').modal('show');
    });
</script>
<?php } ?>
<script>

    $(window).on('load',function(){
		
		 $('#serPatient').focus();
		
	});
	
	 function getPatientDet(srchText){
	     var params     = srchText.split("-");
	if(!isNaN(params[0])){
		document.frmchangePatient.cmdchangePatient.value="submit";
		document.frmchangePatient.slct_valPat.value=srchText;
		document.frmchangePatient.submit();
	}		
}
	
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

//thanks: http://javascript.nwbox.com/cursor_position/
function getSelectionStart(o) {
	if (o.createTextRange) {
		var r = document.selection.createRange().duplicate()
		r.moveEnd('character', o.value.length)
		if (r.text == '') return o.value.length
		return o.value.lastIndexOf(r.text)
	} else return o.selectionStart
}
</script>
<script src="../js/jquery-1.12.4.min.js"></script>
<script type="text/javascript" src="../date-time-picker.min.js"></script>
</head>

<body>
<div id="wrapper">

   
         <?php include_once('sidemenu.php'); ?>
    

        <div id="page-wrapper" class="gray-bg">
        <?php include_once('header_top.php'); 
		
		include_once('patient_detail_section.php'); 
		
		?>
		<!--<div class="row m-t">
                <div class="col-lg-1">
				<a href="#" id="vaccineDetails">
                    <div class="widget style1 navy-bg">
                        <div class="row vertical-align">
                            <div class="col-xs-3">
                                <i class="fa fa-hospital-o fa-2x"></i>
                            </div>
                            <div class="col-xs-9 text-left">
                                <h3 class="font-bold">VACCINE</h3>
                            </div>
                        </div>
                    </div>
					</a>
                </div>
                <div class="col-lg-1">
				<a href="#" id="vitalDetails">
                    <div class="widget style1 lazur-bg">
                        <div class="row vertical-align">
                            <div class="col-xs-3">
                                <i class="fa fa-wheelchair fa-2x"></i>
                            </div>
                            <div class="col-xs-9 text-left">
                                <h3 class="font-bold">VITALS</h3>
                            </div>
                        </div>
                    </div>
					</a>
                </div>
			
                <div class="col-lg-1">
				<a href="#" id="growthDetails">
                    <div class="widget style1 blue-bg">
                        <div class="row vertical-align">
                            <div class="col-xs-3">
                                <i class="fa fa-h-square fa-2x"></i>
                            </div>
                            <div class="col-xs-9 text-left">
                                <h3 class="font-bold">GROWTH</h3>
                            </div>
                        </div>
                    </div>
					</a>
                </div>
                <div class="col-lg-1">
				<a href="#" id="chartDetails">
                    <div class="widget style1 red-bg">
                        <div class="row vertical-align">
                            <div class="col-xs-3">
                                <i class="fa fa-line-chart fa-2x"></i>
                            </div>
                            <div class="col-xs-9 text-left">
                                <h3 class="font-bold">CHART</h3>
                            </div>
                        </div>
                    </div>
					</a>
                </div>
                <div class="col-lg-1">
					<a href="#" id="developDetails">
                    <div class="widget style1 yellow-bg">
                        <div class="row vertical-align">
                            <div class="col-xs-3">
                                <i class="fa fa-copy fa-2x"></i>
                            </div>
                            <div class="col-xs-9 text-left">
                                <h3 class="font-bold">DEVELOPMENT</h3>
                            </div>
                        </div>
                    </div>
					</a>
                </div>
				 <div class="col-lg-1">
					<a href="#" id="feedingDetails">
                    <div class="widget style1 yellow-bg">
                        <div class="row vertical-align">
                            <div class="col-xs-3">
                                <i class="fa fa-copy fa-2x"></i>
                            </div>
                            <div class="col-xs-9 text-left">
                                <h3 class="font-bold">FEEDING</h3>
                            </div>
                        </div>
                    </div>
					</a>
                </div>
				 <div class="col-lg-1">
					<a href="#" id="trackerDetails">
                    <div class="widget style1 yellow-bg">
                        <div class="row vertical-align">
                            <div class="col-xs-3">
                                <i class="fa fa-copy fa-2x"></i>
                            </div>
                            <div class="col-xs-9 text-left">
                                <h3 class="font-bold">H-TRACKER</h3>
                            </div>
                        </div>
                    </div>
					</a>
                </div>
				 <div class="col-lg-1">
					<a href="#" id="dataDetails">
                    <div class="widget style1 yellow-bg">
                        <div class="row vertical-align">
                            <div class="col-xs-3">
                                <i class="fa fa-copy fa-2x"></i>
                            </div>
                            <div class="col-xs-9 text-left">
                                <h3 class="font-bold">DATA</h3>
                            </div>
                        </div>
                    </div>
					</a>
                </div>
				
            </div>-->
		  <?php include_once('../footer.php'); ?>
		</div>
		</div>
		<script src="../../assets/js/plugins/sweetalert/sweetalert.min.js"></script>

    <script src="../../assets/js/jquery-3.1.1.min.js"></script>
    <script src="../../assets/js/bootstrap.min.js"></script>
    <script src="../../assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="../../assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="../../assets/js/inspinia.js"></script>
    <script src="../../assets/js/plugins/pace/pace.min.js"></script>
	 <!-- FooTable -->
    <script src="../../assets/js/plugins/footable/footable.all.min.js"></script>
    <!-- Page-Level Scripts -->
    <script>
        $(document).ready(function() {

            $('.footable').footable();
            $('.footable2').footable();

        });

    </script>
	 <!-- Toastr -->
    <script src="../../assets/js/plugins/toastr/toastr.min.js"></script>
	
    <!-- Tags Input -->
    <script src="../../assets/js/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js"></script>
<script>
        $(document).ready(function(){

            $('.tagsinput1').tagsinput({
                tagClass: 'label label-primary'
            });
			
           
        });
		$(document).ready(function(){

            $('.tagsinput').tagsinput({
                tagClass: 'label label-primary'
            });
			
           
        });


    </script>
	
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <!-- Custom Theme Scripts -->
    <!--<script src="../../assets/js/custom.min.js"></script>-->
	<!-- iCheck -->
    <script src="../../assets/js/plugins/iCheck/icheck.min.js"></script>
	
    <!-- Chosen -->
    <script src="../../assets/js/plugins/chosen/chosen.jquery.js"></script>
	<script>
       
        $('.chosen-select').chosen({width: "100%"});



    </script>
	
	<!-- Switchery -->
   <script src="../../assets/js/plugins/switchery/switchery.js"></script>
   <!-- FooTable -->
    <script src="../../assets/js/plugins/footable/footable.all.min.js"></script>

	<!-- Data picker -->
    <script src="../../assets/js/plugins/datapicker/bootstrap-datepicker.js"></script>
	
	<script>
	 $(document).ready(function() {
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
	 });
	</script>
	 <script src="../../assets/js/plugins/typehead/bootstrap3-typeahead.min.js"></script>
	 <script>
function autocomplete(inp, arr) {
  /*the autocomplete function takes two arguments,
  the text field element and an array of possible autocompleted values:*/
  var currentFocus;
  /*execute a function when someone writes in the text field:*/
  inp.addEventListener("input", function(e) {
      var a, b, i, val = this.value;
      /*close any already open lists of autocompleted values*/
      closeAllLists();
      if (!val) { return false;}
      currentFocus = -1;
      /*create a DIV element that will contain the items (values):*/
      a = document.createElement("DIV");
      a.setAttribute("id", this.id + "autocomplete-list");
      a.setAttribute("class", "autocomplete-items");
      /*append the DIV element as a child of the autocomplete container:*/
      this.parentNode.appendChild(a);
      /*for each item in the array...*/
      for (i = 0; i < arr.length; i++) {
        /*check if the item starts with the same letters as the text field value:*/
        if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
          /*create a DIV element for each matching element:*/
          b = document.createElement("DIV");
          /*make the matching letters bold:*/
          b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
          b.innerHTML += arr[i].substr(val.length);
          /*insert a input field that will hold the current array item's value:*/
          b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
          /*execute a function when someone clicks on the item value (DIV element):*/
          b.addEventListener("click", function(e) {
              /*insert the value for the autocomplete text field:*/
              inp.value = this.getElementsByTagName("input")[0].value;
              /*close the list of autocompleted values,
              (or any other open lists of autocompleted values:*/
              closeAllLists();
          });
          a.appendChild(b);
        }
      }
  });
  /*execute a function presses a key on the keyboard:*/
  inp.addEventListener("keydown", function(e) {
      var x = document.getElementById(this.id + "autocomplete-list");
      if (x) x = x.getElementsByTagName("div");
      if (e.keyCode == 40) {
        /*If the arrow DOWN key is pressed,
        increase the currentFocus variable:*/
        currentFocus++;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 38) { //up
        /*If the arrow UP key is pressed,
        decrease the currentFocus variable:*/
        currentFocus--;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 13) {
        /*If the ENTER key is pressed, prevent the form from being submitted,*/
        e.preventDefault();
        if (currentFocus > -1) {
          /*and simulate a click on the "active" item:*/
          if (x) x[currentFocus].click();
        }
      }
  });
  function addActive(x) {
    /*a function to classify an item as "active":*/
    if (!x) return false;
    /*start by removing the "active" class on all items:*/
    removeActive(x);
    if (currentFocus >= x.length) currentFocus = 0;
    if (currentFocus < 0) currentFocus = (x.length - 1);
    /*add class "autocomplete-active":*/
    x[currentFocus].classList.add("autocomplete-active");
  }
  function removeActive(x) {
    /*a function to remove the "active" class from all autocomplete items:*/
    for (var i = 0; i < x.length; i++) {
      x[i].classList.remove("autocomplete-active");
    }
  }
  function closeAllLists(elmnt) {
    /*close all autocomplete lists in the document,
    except the one passed as an argument:*/
    var x = document.getElementsByClassName("autocomplete-items");
    for (var i = 0; i < x.length; i++) {
      if (elmnt != x[i] && elmnt != inp) {
        x[i].parentNode.removeChild(x[i]);
      }
    }
  }
  /*execute a function when someone clicks in the document:*/
  document.addEventListener("click", function (e) {
      closeAllLists(e.target);
      });
}
  $(document).ready(function(){
		<?php 
	$get_PatientDetails = mysqlSelect("patient_id,patient_name,patient_mob","doc_my_patient","doc_id='".$admin_id."'","","","","");
											
	?>
            $('.typeahead_1').typeahead({
               source: [<?php foreach($get_PatientDetails as $listPat){ echo "'".$listPat['patient_id']."-".$listPat['patient_name']."-".$listPat['patient_mob']."',"; }?>]
            });
			
	
  });

</script>

</body>