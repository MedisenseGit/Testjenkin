<?php
ob_start();
session_start();
error_reporting(0);  

require_once("../../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();
if(empty($_GET['d'])){
	echo "<h2>Error!!!!!!</h2>";
}
$checkPatient= mysqlSelect("*","doc_my_patient","md5(patient_id)='".$_GET['d']."'","","","","");
$getReferredDetails= mysqlSelect("*","diagnostic_referrals","md5(patient_id)='".$_GET['d']."'","referred_date desc","","","");

$getEpisode= mysqlSelect("*","doc_patient_episodes","md5(episode_id)='".$_GET['e']."'","","","","");
if($checkPatient[0]['patient_gen']=="1"){
		$gender="Male";
	}
	else if($checkPatient[0]['patient_gen']=="2"){
		$gender="Female";
	}
	else if($checkPatient[0]['patient_gen']=="3"){
		$gender="Other";
	}

	if($checkPatient[0]['hyper_cond']=="2"){
		$hyperStatus="No";
	}
	else if($checkPatient[0]['hyper_cond']=="1"){
		$hyperStatus="Yes";
	}
	if($checkPatient[0]['diabetes_cond']=="2"){
		$diabetesStatus="No";
	}
	else if($checkPatient[0]['diabetes_cond']=="1"){
		$diabetesStatus="Yes";
	}
	
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Diagnostic Center</title>

     <?php include_once('support.php'); ?>
	<!-- FooTable -->
    <link href="../assets/css/plugins/footable/footable.core.css" rel="stylesheet">
	<link href="../assets/css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">	
	<link href="../assets/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">
	<!-- orris -->
    <link href="../assets/css/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">
	<link href="../assets/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
	<link href="../assets/css/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet">
	
	<link href="fileUpload/css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>
    <link href="fileUpload/themes/explorer-fa/theme.css" media="all" rel="stylesheet" type="text/css"/>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="fileUpload/js/plugins/sortable.js" type="text/javascript"></script>
    <script src="fileUpload/js/fileinput.js" type="text/javascript"></script>
    <script src="fileUpload/js/locales/fr.js" type="text/javascript"></script>
    <script src="fileUpload/js/locales/es.js" type="text/javascript"></script>
    <script src="fileUpload/themes/explorer-fa/theme.js" type="text/javascript"></script>
    <script src="fileUpload/themes/fa/theme.js" type="text/javascript"></script>
	<script language="JavaScript" src="js/status_validationJs.js"></script>
	<script src="js/Chart.bundle.js"></script>
	<script src="js/utils.js"></script>
	<style>
	canvas {
		-moz-user-select: none;
		-webkit-user-select: none;
		-ms-user-select: none;
	}
	</style>
	<script>
	$(document).ready(function() {
    $("#hideSerMed").hide();
	});
	
	function printContent(el){
		var restorepage=document.body.innerHTML;
		var printcontent=document.getElementById(el).innerHTML;
		document.body.innerHTML=printcontent;
		window.print();
		document.body.innerHTML=restorepage;
		
	}
	</script>
	
	<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery-ui.js"></script>
<link rel="stylesheet" href="css/jquery-ui.css">
<script type="text/javascript">
$(function() 
{

 $( "#add_diagnosis_test" ).autocomplete({
  source: 'get_diagnosis_test.php'
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
                <div class="col-md-12">
                    <div class="ibox-content p-md">

                   <div class="row m-b-lg m-t-lg">
                <div class="col-md-5">

                    <div class="profile-image">
                        <img src="../assets/img/anonymous-profile.png" class="img-circle circle-border m-b-md" alt="profile">
                    </div>
                    <div class="profile-info">
                        <div class="">
                            <div>
                                <h2 class="no-margins">
                                    <?php echo $checkPatient[0]['patient_name']; ?>
                                </h2>
                                <h4><i class="fa fa-mobile"></i> <?php echo $checkPatient[0]['patient_mob']; ?></h4>
								 <h4><i class="fa fa-stack-exchange"></i> <?php echo $checkPatient[0]['patient_email']; ?></h4>
                                <small>
                                    <?php echo $checkPatient[0]['patient_addrs'].", ".$checkPatient[0]['patient_loc'].", ".$checkPatient[0]['pat_state']; ?>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <table class="table small m-b-xs">
                        <tbody>
                        <tr>
                            <td>
                                <strong>GENDER</strong> <?php echo $gender; ?>
                            </td>
                            <td>
                                <strong>AGE</strong> <?php echo $checkPatient[0]['patient_age']; ?>
                            </td>

                        </tr>
                        <tr>
                            <td>
                                <strong>HYPERTENSION</strong> <?php echo $hyperStatus; ?>
                            </td>
                            <td>
                                <strong>DIABETES</strong> <?php echo $diabetesStatus; ?>
                            </td>
                        </tr>
                        
                        </tbody>
                    </table>
                </div>
				
                            <span class="pull-right">
                                <b>Referred on: - <i class="fa fa-clock-o"></i> <?php echo date('d.M.Y H:i a',strtotime($getReferredDetails[0]['referred_date']));?></b>
                            </span>
                            
                      
		
            </div>


                </div>
                </div>
				
				 <div class="col-md-12">
				   <div class="ibox-content p-md">
				 <?php
				 	$doc_patient_spectacle_prescriptions = mysqlSelect("*","examination_opthal_spectacle_prescription","episode_id = '". $getEpisode[0]['episode_id'] ."' ","","","","");
										
				 ?>
				 
				 <table cellpadding="2" cellspacing="2" border="1" class="table table-bordered" width="90%">
																			<thead>
																				
																				<th colspan="4" class="text-center">Right Eye</th>
																				<th colspan="4" class="text-center">Left Eye</th>
																				
																			</thead>
																			<thead>
																				<th></th>
																				<th>Sphere</th>
																				<th>Cyl</th>
																				<th>Axis</th>
																				<th>Sphere</th>
																				<th>Cyl</th>
																				<th>Axis</th>
																			</thead>
																			<tbody>
																			<tr><td>D.V</td>
																			<td><?php echo $doc_patient_spectacle_prescriptions[0]['dvSphereRE']; ?></td>
																			<td><?php echo $doc_patient_spectacle_prescriptions[0]['DvCylRE']; ?></td>
																			<td><?php echo $doc_patient_spectacle_prescriptions[0]['DvAxisRE']; ?></td>
																			<td><?php echo $doc_patient_spectacle_prescriptions[0]['DvSpeherLE']; ?></td>
																			<td><?php echo $doc_patient_spectacle_prescriptions[0]['DvCylLE']; ?></td>
																			<td><?php echo $doc_patient_spectacle_prescriptions[0]['DvAxisLE']; ?></td>
																			</tr>
																			
																			<tr><td>N.V</td>
																			<td><?php echo $doc_patient_spectacle_prescriptions[0]['NvSpeherRE']; ?></td>
																			<td><?php echo $doc_patient_spectacle_prescriptions[0]['NvCylRE']; ?></td>
																			<td><?php echo $doc_patient_spectacle_prescriptions[0]['NvAxisRE']; ?></td>
																			<td><?php echo $doc_patient_spectacle_prescriptions[0]['NvSpeherLE']; ?></td>
																			<td><?php echo $doc_patient_spectacle_prescriptions[0]['NvCylLE']; ?></td>
																			<td><?php echo $doc_patient_spectacle_prescriptions[0]['NvAxisLE']; ?></td>
																			</tr>
																			
																			<tr><td>IPD</td>
																			<td colspan="3"><?php echo $doc_patient_spectacle_prescriptions[0]['IpdRE']; ?></td>
																			<td colspan="3"><?php echo $doc_patient_spectacle_prescriptions[0]['IpdLE']; ?></td>
																			</tr>
																			</tbody>
																			
																		</table>
	
                </div>
               </div>
            </div>
          
        <div class="footer">
            
            <div>
                <strong>Copyright</strong> Medisense Healthcare Solutions Pvt. Ltd. &copy; <?php echo date('Y'); ?>
            </div>
        </div>

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

	
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

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


</script>

</body>
<script src="js/symptoms.js"></script>

</html>
<script type="text/javascript">

	
			.autocomplete("instance")._renderItem = function(ul, item) {
				return $("<li>")
					.append('<a href="' + item.value + '">' + item.label + '</a>')
					.appendTo(ul);
			};


	

</script>
