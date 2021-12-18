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
$Cur_Date=date('Y-m-d H:i:s');
$cur_Date=date('Y-m-d',strtotime($Cur_Date));

$curYear = date('Y');
$curMonth = date('M');
require_once("../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();
if(!isset($_GET['start'])) 
{
	$start = 0;
}
else
{
	$start = $_GET['start'];
}

$eu = ($start - 0); 
$limit = 50;         // No of records to be shown per page.
$this1 = $eu + $limit; 
$back = $eu - $limit; 
$next = $eu + $limit;
			
			
$get_scheduler 	= 	mysqlSelect("*","ot_scheduler","doc_id='".$admin_id."' and doc_type='1'","date desc","","","");
$pag_result 	= 	mysqlSelect("scheduler_id","ot_scheduler","doc_id='".$admin_id."' and doc_type='1'","date desc");
$nume 			= 	count($pag_result);  
$status_val		=	array("Scheduled"=>"1","Cancelled"=>"2","Postponed"=>"3","Preponed"=>"4","Completed"=>"5");	

?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>My Patients</title>
	<?php include_once('support.php'); ?>
	<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <link href="../assets/css/plugins/iCheck/custom.css" rel="stylesheet">
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/jquery-ui.js"></script>
	<link rel="stylesheet" href="css/jquery-ui.css">
	<script type="text/javascript">
$(function() 
{
 
 $( "#get_treatment_res" ).autocomplete({
  source: 'get_surgery_res.php'
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

<script src="../search/jquery-1.11.1.min.js"></script>
	<script src="../search/jquery-ui.min.js"></script>
	<script src="../search/jquery.select-to-autocomplete.js"></script>
	<script>
	  (function($){
	    $(function(){
	      $('#txtref').selectToAutocomplete();
	      
	    });
	  })(jQuery);
	  function call_login_hospital(){
		   //alert("Logging in.........");
		   var user=document.getElementById('txtref').value;
		   		   
		   if(user==""){
		     alert("Enter investigation name");
			 return false;
		   }
		   
		 }
		 
	</script>

	<style>
	
    .ui-autocomplete {
      padding: 10px;
	  font-size:12px;
      list-style: none;
      background-color: #fff;
      width: 658px;
      border: 1px solid #B0BECA;
      max-height: 350px;
      overflow-x: hidden;
	   white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  max-width: 658px;
    }
    .ui-autocomplete .ui-menu-item {
      border-top: 1px solid #B0BECA;
      display: block;
      padding: 4px 6px;
      color: #353D44;
      cursor: pointer;
    }
    .ui-autocomplete .ui-menu-item:first-child {
      border-top: none;
    }
    .ui-autocomplete .ui-menu-item.ui-state-focus {
      background-color: #D5E5F4;
      color: #161A1C;
    }
	
	</style>
	<script src="js/jquery-1.12.4.min.js"></script>
    <script type="text/javascript" src="date-time-picker.min.js"></script>
</head>

<body>

    <div id="wrapper">

    <?php include_once('sidemenu.php'); ?>

        <div id="page-wrapper" class="gray-bg">
        <?php include_once('header_top.php'); ?>
		
        <div class="wrapper wrapper-content animated fadeInRight">
		<div class="row">
                <div class="col-lg-6">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Surgery Scheduler</h5>
                        
                    </div>
                    <div class="ibox-content">
					<iframe src="Surgery_Scheduler/" width="100%" height="758px"  style="border:none; background:none;"></iframe>					
					
					</div>
				</div>
				</div>
				
				<div class="col-lg-6">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Manage Surgery Scheduler</h5>
                       
                    </div>
                    <div class="ibox-content">
					<div class="search-form">
                                <form method="post" action="Surgery-Scheduler" autocomplete="off">
                                    <div class="input-group">
				
                                       <input type="text" placeholder="Search Patient" name="patient_details" value="" class="form-control input-lg typeahead_1">
                                        <div class="input-group-btn">
                                            <button class="btn btn-lg btn-primary" name="cmdSchedule" type="submit">
                                                Schedule
                                            </button>
                                        </div>
                                    </div>

                                </form>
                    </div>
					<?php if(isset($_POST['cmdSchedule'])){ 
					$params     = split("-", $_POST['patient_details']);
					$patientId = $params[0];
					$patientName = $params[1];
					$patientMobile = $params[2];
					?>
								<form enctype="multipart/form-data" method="post" action="add_details.php"  name="frmScheduleSurgery" autocomplete="off" >
								<input type="hidden" name="patient_id" value="<?php echo $patientId; ?>" />
								<div class="row m-t">
								<div class="form-group">
									<label class="col-sm-2 control-label">Patient Name <span class="required">*</span></label>

                                    <div class="col-sm-4"><input type="text" name="se_pat_name" value="<?php echo $patientName; ?>" required="required" class="form-control" required=""></div>
                                
									<label class="col-sm-2 control-label">Contact No. <span class="required">*</span></label>

                                    <div class="col-sm-4"><input type="text" name="se_pat_mobile" value="<?php echo $patientMobile;?>" class="form-control" minlength=10 maxlength=10 pattern="[0-9]{1}[0-9]{9}" title="Mobile Number must be 10 digits" required="required"></div>
                                </div>	
								</div>
								<div class="row m-t">
								<div class="form-group">
									<label class="col-sm-2 control-label">Surgery Name <span class="required">*</span></label>

                                    <div class="col-sm-10"><input type="text" name="surgery_name" id="get_treatment_res" name="get_treatment_res" value="" class="form-control searchTreatment" required=""></div>
                                
								</div>
								</div>
								
								<div class="row m-t">
								<label class="col-sm-2 control-label">Surgery Date & Time <span class="required">*</span></label>
									<div class="col-sm-10">
											<input id="J-demo-02" name="dateadded2" type="text" placeholder="YYYY-MM-DD" value="<?php echo $Cur_Date;?>" class="form-control" required="" />
										</div>
									<script type="text/javascript">
										$('#J-demo-02').dateTimePicker({
											mode: 'dateTime'
										});
									</script>
								</div>
								
								<div class="search-form">
								<div class="col-sm-10 input-group">
				
                                      <div class="input-group-btn pull-right">
                                            <button class="btn btn-lg btn-primary" name="bookSurgery" type="submit">
                                                BOOK
                                            </button>
                                        </div>
                                    </div>
								</div>
								</form>	
					<?php } ?>		
                                <table class="table table-striped">
                                    <thead>
                                    <tr>

                                        
                                        <th>Date</th>
										<th>Surgery Type</th>
										<th >Status</th>
										<th>Delete</th>
										
                                    </tr>
                                    </thead>
                                    <tbody>
									
									<?php 
									if(!empty($get_scheduler)){
									foreach($get_scheduler  as $list)
									{ 									
									?>
                                    <tr id="delete_scheduler_row<?php echo $list['scheduler_id'];?>">
                                       
                                        <td><strong><?php echo date('d-M-Y',strtotime($list['date'])); ?></strong> </td>
										<td width="200"><strong><?php echo $list['title']; ?></strong> </td>
										<td><div class="btn-group">
										
								<?php 
								if($list['status']=="Cancelled"){
									$btn_type= "btn-danger";
								}else if($list['status']=="Postponed"){
									$btn_type= "btn-warning";
								}else if($list['status']=="Scheduled"){
									$btn_type= "btn-primary";
								}
								else if($list['status']=="Preponed"){
									$btn_type= "btn-success";
								}
								else if($list['status']=="Completed"){
									$btn_type= "btn-primary";
								}
								?>
                                        <button data-toggle="dropdown" class="btn <?php echo $btn_type;?> btn-xs dropdown-toggle"><?php echo $list['status']; ?> <span class="caret"></span></button>
										<ul class="dropdown-menu">
										
									  <?php foreach($status_val as $key=>$value){ ?>
									   <li><a href="#" class="surgery-status" data-status-id="<?php echo $value; ?>" data-scheduler-id="<?php echo $list['scheduler_id']; ?>"><?php echo $key; ?></a></li>
									  <?php } ?>
										</ul>
									</div>
								</td>
										
										<td><a href="javascript:void(0)" data-row-id = "<?php echo $list['scheduler_id']; ?>" data-scheduler-id = "<?php echo md5($list['scheduler_id']); ?>" class="btn btn-danger btn-bitbucket btn-xs delete_scheduler">
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
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<!-- Custom Theme Scripts -->
<script src="../assets/js/custom.min.js"></script>
<!-- Typehead -->
<script src="../assets/js/plugins/typehead/bootstrap3-typeahead.min.js"></script>

<script>
$(document).ready(function(){
<?php 
//$get_PatientDetails = mysqlSelect("patient_id,patient_name,patient_mob","doc_my_patient","doc_id='".$admin_id."'","","","","");
$get_PatientDetails = mysqlSelect("a.patient_id as patient_id,a.patient_name as patient_name,a.patient_mobile as patient_mob","patients_appointment as a inner join patients_transactions as b on a.patient_id=b.patient_id","b.doc_id='".$admin_id."'","","","","");

?>
$('.typeahead_1').typeahead({
source: [<?php foreach($get_PatientDetails as $listPat){ echo '"'.$listPat['patient_id'].'-'.$listPat['patient_name'].'-'.$listPat['patient_mob'].'",'; }?>]
});


});
</script>
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
<script language="JavaScript" src="js/status_validationJs.js"></script>
</body>
</html>
