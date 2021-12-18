<?php
ob_start();
session_start();
error_reporting(0);  


require_once("../classes/querymaker.class.php");

if(empty($_GET['d'])){
	echo "<h2>Error!!!!!!</h2>";
}
$gatewayUrl = "../PaytmKit/pgRedirect.php"; 

//$checkPatient= mysqlSelect("*","doc_my_patient","md5(patient_id)='".$_GET['d']."'","","","","");

$checkPatient	= mysqlSelect("a.patient_id as patient_id,a.patient_name as patient_name,a.patient_mobile as patient_mob,a.patient_gender as patient_gen,b.city as patient_loc ,b.state as pat_state,b.country as pat_country,b.address as patient_addrs,b.hyper_cond as hyper_cond,b.diabetes_cond as diabetes_cond,b.smoking as smoking,b.drug_abuse as drug_abuse,b.prev_intervention as prev_inter,b.other_details as other_details,b.diabetes_cond as diabetes_cond,b.alcoholic as alcoholic,b.family_history as family_history,b.neuro_issue as neuro_issue,b.kidney_issue as kidney_issue,a.patient_email as patient_email,a.patient_mobile as patient_mob","patients_appointment as a inner join patients_transactions as b on a.patient_id=b.patient_id","md5(a.patient_id)='".$_GET['d']."'","","","","");

$getReferredDetails	= mysqlSelect("*","diagnostic_referrals","md5(patient_id)='".$_GET['d']."'","referred_date desc","","","");

$getChatStatus		= mysqlSelect("*","emr_referred_notifications","md5(patient_id)='".$_GET['d']."' and md5(episode_id)='".$_GET['e']."' and status!='3' and type='1' and refer_id='".$getReferredDetails[0]['diagnostic_id']."'","chat_id desc","","","");

$getScheduled		= mysqlSelect("*","emr_referred_orderDetail","md5(patient_id)='".$_GET['d']."' and md5(episode_id)='".$_GET['e']."' and type='1' and refer_id='".$getReferredDetails[0]['diagnostic_id']."'","","","","");

$getEpisode		= mysqlSelect("*","doc_patient_episodes","md5(episode_id)='".$_GET['e']."'","","","","");
if($checkPatient[0]['patient_gen']=="1")
{
		$gender="Male";
}
else if($checkPatient[0]['patient_gen']=="2")
{
	$gender="Female";
}
else if($checkPatient[0]['patient_gen']=="3")
{
	$gender="Other";
}

if($checkPatient[0]['hyper_cond']=="2")
{
	$hyperStatus="No";
}
else if($checkPatient[0]['hyper_cond']=="1")
{
	$hyperStatus="Yes";
}
if($checkPatient[0]['diabetes_cond']=="2")
{
	$diabetesStatus="No";
}
else if($checkPatient[0]['diabetes_cond']=="1")
{
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
	<script src="../premium/js/Chart.bundle.js"></script>
	<script src="../premium/js/utils.js"></script>
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
	
	function printContent(el)
	{
		var restorepage=document.body.innerHTML;
		var printcontent=document.getElementById(el).innerHTML;
		document.body.innerHTML=printcontent;
		window.print();
		document.body.innerHTML=restorepage;
		
	}
</script>
<script type="text/javascript" src="../premium/js/jquery.js"></script>
<script type="text/javascript" src="../premium/js/jquery-ui.js"></script>
<link rel="stylesheet" href="../premium/css/jquery-ui.css">

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
			<?php if($getChatStatus[0]['status']=="6"){ ?>
			<div class="row m-b-lg m-t-lg">
			<div class="col-md-12">
			<strong>Scheduled Date: <?php echo $getScheduled[0]['date'].'  '.$getScheduled[0]['time']; ?></strong>
			</div>
			</div>
			<?php } ?>
                </div>
               
				</div>
				 
				 
				 <form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  action="add_details.php"  name="frmAddPatient" id="frmAddPatient">
					<input type="hidden" name="patient_id" value="<?php echo $checkPatient[0]['patient_id']; ?>">
					<input type="hidden" name="patient_name" value="<?php echo $checkPatient[0]['patient_name']; ?>">
					<input type="hidden" name="refer_date" value="<?php echo date('d-M-Y H:i',strtotime($getReferredDetails[0]['referred_date'])); ?>">
					<input type="hidden" name="diagno_id" value="<?php echo $getReferredDetails[0]['diagnostic_id']; ?>">
					<input type="hidden" name="doc_id" value="<?php echo $checkPatient[0]['doc_id']; ?>">
					<input type="hidden" name="episode_id" value="<?php echo $getEpisode[0]['episode_id']; ?>">
					<input type="hidden" name="company_id" value="<?php echo $getChatStatus[0]['company_id']; ?>">
					<input type="hidden" name="diagno_url" value="<?php echo $getChatStatus[0]['url']; ?>">
                    
						<!--<div class="input-group">
										
                                       <input type="text" id="add_diagnosis_test" placeholder="Enter test here..." data-episode-id="<?php echo $getEpisode[0]['episode_id']; ?>" data-doc-id="<?php echo $getEpisode[0]['admin_id']; ?>" data-patient-id="<?php echo $checkPatient[0]['patient_id']; ?>" name="searchDiagnosTest" value="" class="form-control input-lg searchDiagnosTest" tabindex="2">
                                        <div class="input-group-btn">
                                            <button class="btn btn-lg btn-primary"  name="" type="button">
                                                ADD
                                            </button>
                                        </div>
										
                                    </div><br><br>-->
									
							<div class="row">
							<div class="ibox-content text-center p-md">
						<?php if($_GET['response']=="update-investigation"){ ?>
						<div class="alert alert-success alert-dismissable">
								<button aria-hidden="true" data-dismiss="alert" class="close"  type="button">×</button>
								<strong>Patient diagnosis test updated successfully </strong>
						</div>
						<?php }
						 $get_invest = mysqlSelect("*","patient_temp_investigation","patient_id = '". $checkPatient[0]['patient_id'] ."' and md5(episode_id)='". $_GET['e']."'","","","","");
								
									?>
										
									<div class="table-responsive">	
										<table class="table table-bordered" id="dispOld">
											<thead>
											<tr>
											<th>Test</th>
											<th>Normal Value</th>
											<?php if($getChatStatus[0]['status']=="6" || $getChatStatus[0]['status']=="7"){ ?><th>Actual Value</th><?php } ?>
											<?php if($getChatStatus[0]['status']=="2" || $getChatStatus[0]['status']=="4" || $getChatStatus[0]['status']=="5" || $getChatStatus[0]['status']=="6" || $getChatStatus[0]['status']=="7"){ ?><th>Price (Rs. )</th><?php } ?>											
											</tr>
											<thead>
											<tbody>
											
													
												<?php 
												$totalPrice=0;
												while(list($key_invest, $value_invest) = each($get_invest))	
												{  
												 $totalPrice = $totalPrice+$value_invest['test_price_val'];
											
												?>
												<tr>
												<td>
												<input type="hidden" name="main_test_id[]" value="<?php echo $value_invest['main_test_id']; ?>"/>
												<input type="hidden" name="investigation_id[]" value="<?php echo $value_invest['pti_id']; ?>"/><?php echo $value_invest['test_name']; ?></td>
												<td><?php echo $value_invest['normal_range']; ?></td>
												<?php if($getChatStatus[0]['status']=="6"){ ?><td><input type="text" name="actualVal[]"  value="<?php echo $value_invest['test_actual_value']; ?>" placeholder="" style="width:100px;"></td><?php } ?>
												<?php if($getChatStatus[0]['status']=="7"){ ?><td><?php echo $value_invest['test_actual_value']; ?></td><?php } ?>
												<?php if($getChatStatus[0]['status']=="2"){ ?><td><input type="number" name="priceVal[]"  value="<?php echo $value_invest['test_price_val']; ?>" placeholder="" style="width:100px;"></td><?php } ?>
												<?php if($getChatStatus[0]['status']=="4" || $getChatStatus[0]['status']=="5" || $getChatStatus[0]['status']=="6" || $getChatStatus[0]['status']=="7"){ ?><td><?php echo $value_invest['test_price_val']; ?></td><?php } ?>
												</tr>
												
												<?php }
												?>
												<?php if($getChatStatus[0]['status']=="4"){ ?><tr><td><strong>Total</strong></td><td></td>
												<td><strong>Rs <?php echo $totalPrice; ?></strong></td></tr><?php } ?>
											   </tbody>
										</table>
									</div>
										<div id="dispDignoTest"></div>
										<br>
									
					</div>
					</div>
					<div class="row">
					
					<?php if($getChatStatus[0]['status']=="6"){ ?> <div class="ibox-content text-center p-md">
					<label><i class="fa fa-file-medical"></i> Attach Reports here ( Allowed file types: jpg, jpeg, png)</label>
                   
									<div class="form-group col-lg-12">
										<div class="file-loading">
											<input id="file-5" name="file-5[]" class="file" type="file" multiple data-preview-file-type="any" data-upload-url="#" tabindex="7">
										</div>
									</div>
                    
					
					<div class="row" id="image_preview"></div>
				
					    <textarea class="form-control" name="txtDesc" id="txtDesc"  placeholder="Enter the comments here"></textarea><br/><br/>
						
						<button type="submit" name="updateDiagnoInvestigation" class="btn btn-primary block full-width m-b ">CLICK HERE TO COMPLETE DIAGNOSIS</button>
					</div> <?php } ?>
					
					<?php if($getChatStatus[0]['status']=="2"){ ?> <div class="ibox-content text-center p-md">
					
					
						<button type="submit" name="updateDiagnoPrice" class="btn btn-primary block full-width m-b ">CLICK HERE TO SEND PAYMENT LINK</button>
					</div> <?php } ?>
					
					
					</div>
				</form>
				<?php if($getChatStatus[0]['status']=="4"){
	date_default_timezone_set('Asia/Kolkata');
	$curDate=date('Y-m-d H:i:s');
	$TransId=time();	
                $arrFields_arr1 = array();
				$arrValues_arr1 = array();	
				$arrFields_arr1[] = 'patient_name';
				$arrValues_arr1[] = $checkPatient[0]['patient_name']; //booking id from booking table.
				
				$arrFields_arr1[] = 'patient_id';
				$arrValues_arr1[] = $checkPatient[0]['patient_id'];
				$arrFields_arr1[] = 'episode_id';
				$arrValues_arr1[] = $getEpisode[0]['episode_id'];
				$arrFields_arr1[] = 'trans_date';
				$arrValues_arr1[] = $curDate;
				$arrFields_arr1[] = 'amount';
				$arrValues_arr1[] = $totalPrice;
				$arrFields_arr1[] = 'company_id';
				$arrValues_arr1[] = $getChatStatus[0]['company_id'];
				$arrFields_arr1[] = 'payment_status';
				$arrValues_arr1[] = "Pending";
				$arrFields_arr1[] = 'pay_method';
				$arrValues_arr1[] = 'PAYTM';
				/*$arrFields_arr1[] = 'Payment_id';
				$arrValues_arr1[] = "";*/
				$arrFields_arr1[] = 'transaction_id';
				$arrValues_arr1[] = $TransId;
				$arrFields_arr1[] = 'type';
				$arrValues_arr1[] = '1';
				$arrFields_arr1[] = 'refer_id';
				$arrValues_arr1[] = $getReferredDetails[0]['diagnostic_id'];

		$usercreate1=mysqlInsert('emr_referred_transaction',$arrFields_arr1,$arrValues_arr1);
		
}	 ?>
				<?php if($getChatStatus[0]['status']=="4"){ ?>
				<form method="post" action="<?php echo $gatewayUrl; ?>">
                        <div class="">
                            <div>
							
							<input type="hidden" id="CUST_ID" name="CUST_ID" value="<?php echo $checkPatient[0]['patient_id']; ?>">
							<input type="hidden" id="ORDER_ID" name="ORDER_ID" value="<?php echo $TransId; ?>">
							<input type="hidden" id="INDUSTRY_TYPE_ID" name="INDUSTRY_TYPE_ID" value="Retail120">
							<input type="hidden" id="CHANNEL_ID" name="CHANNEL_ID" value="WEB">
							<input type="hidden" id="TXN_AMOUNT" name="TXN_AMOUNT" value="<?php echo $totalPrice; ?>">
							<input type="hidden" name="MOBILE_NO" value="<?php echo $checkPatient[0]['patient_mob']; ?>">
							<input type="hidden" name="EMAIL" value="<?php echo $checkPatient[0]['patient_email']; ?>">										
							<input type="hidden" name="CALLBACK_URL" value="<?php echo HOST_MAIN_URL;?>institution/payment_success.php">
							
							
                            </div>
							
							<div class="row"><div class="ibox-content text-center p-md">
							
                                <input type="submit" class="btn btn-warning block full-width m-b " value="Pay Now">
						</div>
                        </div>	
						
                        </div>
						</form>
               <?php } ?>
               
            
          
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


</html>
<script type="text/javascript">

	
			/*.autocomplete("instance")._renderItem = function(ul, item) {
				return $("<li>")
					.append('<a href="' + item.value + '">' + item.label + '</a>')
					.appendTo(ul);
			};*/


	

</script>
