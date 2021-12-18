<?php

require_once("classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();
ob_start();

?>

<!DOCTYPE html>
<html lang="en">
   <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	  <meta name="description" content="">
      <meta name="keywords" content="">
		 <title>Medisense-Healthcare Solutions</title>
         <?php include_once("support.php"); ?>
		 
		 <link href="jquery-ui.css" rel="stylesheet">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  
<script>
function getstate(val) {
	$.ajax({
	type: "POST",
	url: "get_state.php",
	data:'country_id='+val,
	success: function(data){
		$("#se_state").html(data);
	}
	});
}
function getstate1(val) {
	$.ajax({
	type: "POST",
	url: "get_state.php",
	data:'country_id='+val,
	success: function(data){
		$("#person_state").html(data);
	}
	});
}

</script>
<script src="jquery.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	var maxField = 2; //Input fields increment limitation
	var addButton = $('.add_button'); //Add button selector
	var wrapper = $('.field_wrapper'); //Input field wrapper
	
	var fieldHTML = '<div class="field_wrapper"><div><label class="label">Consulting Hospital Address2: </label><textarea rows="4" name="doc_hosp2" id="doc_hosp2" placeholder=""></textarea><a href="javascript:void(0);" class="remove_button" title="Remove field"><i class="fa fa-minus-circle fa-2x"></i></a></div></div><br>'; //New input field html 
	var fieldHTML1 = '<div class="field_wrapper"><div><label class="label">Consulting Hospital Address3: </label><textarea rows="4" name="doc_hosp3" id="doc_hosp3" placeholder=""></textarea><a href="javascript:void(0);" class="remove_button1" title="Remove field"><i class="fa fa-minus-circle fa-2x"></i></a></div></div><br>'; 
	var x = 1; //Initial field counter is 1
	$(addButton).click(function(){ //Once add button is clicked
		if(x < maxField){ //Check maximum number of input fields
			x++; //Increment field counter
			$(wrapper).append(fieldHTML);
			$(wrapper).append(fieldHTML1);			// Add field html
		}
	});
	$(wrapper).on('click', '.remove_button', function(e){ //Once remove button is clicked
		e.preventDefault();
		$(this).parent('div').remove(); //Remove field html
		x--; //Decrement field counter
	});
	$(wrapper).on('click', '.remove_button1', function(e){ //Once remove button is clicked
		e.preventDefault();
		$(this).parent('div').remove(); //Remove field html
		x--; //Decrement field counter
	});
		
	
});
</script>

<style type="text/css">
.field_wrapper div{ float:left;width:100%;}
.add_button{ position:absolute;top:45px; margin-left:10px;vertical-align: text-top;}
.remove_button{ position:absolute;top:160px; margin-left:10px;vertical-align: text-top;}
.remove_button1{ position:absolute;top:270px; margin-left:10px;vertical-align: text-top;}


</style>
  
	 </head>
	
   <body >
   
    <div class="header">
     <div class="container ">
	 <div class="row ">
		<div class="col-sm-12 col-xs-12 ">
			<div class="center mar_top_10 img-responsive ">
				<img src="../assets/img/medisenselogo.jpg" class="" alt="Medisense-Healthcare">
				<ul class="social-widget ">

	<!--<li><a href="Register" class="dropdown-toggle" > Register</a>-->
  <li><a href="https://www.facebook.com/medisensehealthcom-1542369946078959/" class="facebook" title="Facebook" target="_blank">
  <i class="fa fa-facebook"></i>
  </a></li>
<li><a href="#" class="google-plus" title="Google-plus"><i class="fa fa-google-plus"></i></a></li>
 
 
 <li><a href="#" class="twitter" title="Twitter"><i class="fa fa-twitter"></i></a></li>
  
  </ul>
			</div>
		</div>
	 </div>
	 </div>
	</div>
<div class="home_slider">
		<!-- <div class="container pbg">
				<div class="bg-slider">
				<div class="col-sm-12 col-xs-12">
				<p class="camp_par  ">
				Here is a chance to save someone's life and may be yours, some day. Click below link and mention that great doctor's name who you worship, for having saved your or your loved ones's life.  Please fill the below form, We will consolidate and open it for patients to refer whenever they are down with that dreaded condition.
				</p>
				</div>
				
				</div>
		 </div>-->
		 
  <div class="container ">
		 
	<div class="sectionBox">
			<div class="row">
			<div class="col-md-12">
         
				<h3 class="life"><i class="fa fa-user-md red fa-2x"></i>
				<span>Volunteer Doctor</span></h3><p>
			</div>
			</div>
		<div><span class="sucess">
											
											<?php if(isset($_GET['respond'])){
												switch($_GET['respond']){
													case '0' : echo 'Thank you, We appreciate your continued support';
													break;
													case '1' : echo 'Failed to submit your response. Please click browser Go Back button and reload Captcha Code, then enter captcha code properly';
													break;
												}
											}
											?></span>
        </div>
        <form enctype="multipart/form-data" class="" action="send.php" method="post" id="vol_doctor" novalidate="novalidate">
        <div class="medisene-form">
        <div class="row">
		
                                                            
															 <div class="col-xs-12 col-md-4 col-sm-4">
															 											 
															 
                                                                <label class="label">Doctor name <span class="red">*</span></label>
                                                                <label class="input">
                                                                    <i class="icon-append fa fa-user"></i>
																	
                                                                    <input type="text" name="doc_name" id="autocomplete" value="" class="ui-autocomplete-input" autocomplete="off">
																	
                                                                </label>
                                                            </div>
															

                                                            <div class="col-xs-12 col-md-4 col-sm-4">
                                                                <div class="form-group">
                                                                    <label class="label">Specialization <span class="red">*</span></label>
                                                                     <label class="input">

                                                                        <input type="text" name="specialization" id="specialization" placeholder="">
                                                                        
                                                                    
                                                                    </label>
                                                                
                                                                </div>
                                                            </div>
															<div class="col-md-2 col-xs-12">
																<label class="label">Gender <span class="red">*</span></label>
																
															<label class="select">
                                                            <select name="doc_gender" id="doc_gender" class="select">
																 <option value="" selected="">Select</option>
																<option value="Male">Male</option>
																<option value="Female">Female</option>
																
															</select>
															 <i></i>
                                                        <!-- <input type="Text" name="se_status" id="se_status" placeholder="">
                                                        <b class="tooltip tooltip-bottom-right">Married/Unmarried/Widow</b>-->
                                                        </label>

															</div>
															<div class="col-md-2 col-xs-12">
																<label class="label">Age </label>
																<label class="input">
                                                     
																	<input type="text" name="doc_age" id="doc_age" placeholder="">
																</label>

															</div>
														                                                    
        </div>
		
		<div class="row">
															<div class="col-md-2 col-xs-12">
																<label class="label">Academic Qualification <span class="red">*</span></label>
															
                                                            <label class="input">
                                                     
																	<input type="text" name="doc_qual" id="doc_qual" placeholder="">
																</label>
															 <i></i>
                                                        <!-- <input type="Text" name="se_status" id="se_status" placeholder="">
                                                        <b class="tooltip tooltip-bottom-right">Married/Unmarried/Widow</b>-->
                                                        </label>

															</div>
															<div class="col-md-2 col-xs-12">
																<label class="label">Year of Exp. <span class="red">*</span></label>
																<label class="input">
                                                     
																	<input type="text" name="doc_exp" id="doc_exp" placeholder="">
																</label>

															</div>
															
															<div class="col-xs-12 col-md-4 col-sm-4">
                                                                <div class="form-group">
                                                                    <label class="label">Email ID <span class="red">*</span></label>
                                                                     <label class="input">

                                                                        <input type="text" name="doc_mail" id="doc_mail" placeholder="">
                                                                        
                                                                    
                                                                    </label>
                                                                
                                                                </div>
                                                            </div>
															
															<div class="col-xs-12 col-md-4 col-sm-4">
                                                                <div class="form-group">
                                                                    <label class="label">Contact No.<span class="red">*</span></label>
                                                                     <label class="input">

                                                                        <input type="text" name="doc_contact" id="doc_contact" placeholder="">
                                                                        
                                                                    
                                                                    </label>
                                                                
                                                                </div>
                                                            </div>
															
															
															
                                                           
															 
		</div>
		<div class="row">
													   
															<div class="col-md-4 col-sm-4 col-xs-12">
                                                                <div class="form-group">
                                                                    <label class="label">Doctor's Country <span class="red">*</span></label>
                                                                    <label class="select">
																		<select name="doc_country" id="doc_country" onchange="return getstate(this.value);">
																		 	
																		<option value="Afghanistan">Afghanistan</option>
																		<option value="Albania">Albania</option>
																		<option value="Algeria">Algeria</option>
																		<option value="American Samoa">American Samoa</option>
																		<option value="Andorra">Andorra</option>
																		<option value="Angola">Angola</option>
																		<option value="Anguilla">Anguilla</option>
																		<option value="Antartica">Antarctica</option>
																		<option value="Antigua and Barbuda">Antigua and Barbuda</option>
																		<option value="Argentina">Argentina</option>
																		<option value="Armenia">Armenia</option>
																		<option value="Aruba">Aruba</option>
																		<option value="Australia">Australia</option>
																		<option value="Austria">Austria</option>
																		<option value="Azerbaijan">Azerbaijan</option>
																		<option value="Bahamas">Bahamas</option>
																		<option value="Bahrain">Bahrain</option>
																		<option value="Bangladesh">Bangladesh</option>
																		<option value="Barbados">Barbados</option>
																		<option value="Belarus">Belarus</option>
																		<option value="Belgium">Belgium</option>
																		<option value="Belize">Belize</option>
																		<option value="Benin">Benin</option>
																		<option value="Bermuda">Bermuda</option>
																		<option value="Bhutan">Bhutan</option>
																		<option value="Bolivia">Bolivia</option>
																		<option value="Bosnia and Herzegowina">Bosnia and Herzegowina</option>
																		<option value="Botswana">Botswana</option>
																		<option value="Bouvet Island">Bouvet Island</option>
																		<option value="Brazil">Brazil</option>
																		<option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
																		<option value="Brunei Darussalam">Brunei Darussalam</option>
																		<option value="Bulgaria">Bulgaria</option>
																		<option value="Burkina Faso">Burkina Faso</option>
																		<option value="Burundi">Burundi</option>
																		<option value="Cambodia">Cambodia</option>
																		<option value="Cameroon">Cameroon</option>
																		<option value="Canada">Canada</option>
																		<option value="Cape Verde">Cape Verde</option>
																		<option value="Cayman Islands">Cayman Islands</option>
																		<option value="Central African Republic">Central African Republic</option>
																		<option value="Chad">Chad</option>
																		<option value="Chile">Chile</option>
																		<option value="China">China</option>
																		<option value="Christmas Island">Christmas Island</option>
																		<option value="Cocos Islands">Cocos (Keeling) Islands</option>
																		<option value="Colombia">Colombia</option>
																		<option value="Comoros">Comoros</option>
																		<option value="Congo">Congo</option>
																		<option value="Congo">Congo, the Democratic Republic of the</option>
																		<option value="Cook Islands">Cook Islands</option>
																		<option value="Costa Rica">Costa Rica</option>
																		<option value="Cota D'Ivoire">Cote d'Ivoire</option>
																		<option value="Croatia">Croatia (Hrvatska)</option>
																		<option value="Cuba">Cuba</option>
																		<option value="Cyprus">Cyprus</option>
																		<option value="Czech Republic">Czech Republic</option>
																		<option value="Denmark">Denmark</option>
																		<option value="Djibouti">Djibouti</option>
																		<option value="Dominica">Dominica</option>
																		<option value="Dominican Republic">Dominican Republic</option>
																		<option value="East Timor">East Timor</option>
																		<option value="Ecuador">Ecuador</option>
																		<option value="Egypt">Egypt</option>
																		<option value="El Salvador">El Salvador</option>
																		<option value="Equatorial Guinea">Equatorial Guinea</option>
																		<option value="Eritrea">Eritrea</option>
																		<option value="Estonia">Estonia</option>
																		<option value="Ethiopia">Ethiopia</option>
																		<option value="Falkland Islands">Falkland Islands (Malvinas)</option>
																		<option value="Faroe Islands">Faroe Islands</option>
																		<option value="Fiji">Fiji</option>
																		<option value="Finland">Finland</option>
																		<option value="France">France</option>
																		<option value="France Metropolitan">France, Metropolitan</option>
																		<option value="French Guiana">French Guiana</option>
																		<option value="French Polynesia">French Polynesia</option>
																		<option value="French Southern Territories">French Southern Territories</option>
																		<option value="Gabon">Gabon</option>
																		<option value="Gambia">Gambia</option>
																		<option value="Georgia">Georgia</option>
																		<option value="Germany">Germany</option>
																		<option value="Ghana">Ghana</option>
																		<option value="Gibraltar">Gibraltar</option>
																		<option value="Greece">Greece</option>
																		<option value="Greenland">Greenland</option>
																		<option value="Grenada">Grenada</option>
																		<option value="Guadeloupe">Guadeloupe</option>
																		<option value="Guam">Guam</option>
																		<option value="Guatemala">Guatemala</option>
																		<option value="Guinea">Guinea</option>
																		<option value="Guinea-Bissau">Guinea-Bissau</option>
																		<option value="Guyana">Guyana</option>
																		<option value="Haiti">Haiti</option>
																		<option value="Heard and McDonald Islands">Heard and Mc Donald Islands</option>
																		<option value="Holy See">Holy See (Vatican City State)</option>
																		<option value="Honduras">Honduras</option>
																		<option value="Hong Kong">Hong Kong</option>
																		<option value="Hungary">Hungary</option>
																		<option value="Iceland">Iceland</option>
																		<option value="India" selected="">India</option>
																		<option value="Indonesia">Indonesia</option>
																		<option value="Iran">Iran (Islamic Republic of)</option>
																		<option value="Iraq">Iraq</option>
																		<option value="Ireland">Ireland</option>
																		<option value="Israel">Israel</option>
																		<option value="Italy">Italy</option>
																		<option value="Jamaica">Jamaica</option>
																		<option value="Japan">Japan</option>
																		<option value="Jordan">Jordan</option>
																		<option value="Kazakhstan">Kazakhstan</option>
																		<option value="Kenya">Kenya</option>
																		<option value="Kiribati">Kiribati</option>
																		<option value="Democratic People's Republic of Korea">Korea, Democratic People's Republic of</option>
																		<option value="Korea">Korea, Republic of</option>
																		<option value="Kuwait">Kuwait</option>
																		<option value="Kyrgyzstan">Kyrgyzstan</option>
																		<option value="Lao">Lao People's Democratic Republic</option>
																		<option value="Latvia">Latvia</option>
																		<option value="Lebanon">Lebanon</option>
																		<option value="Lesotho">Lesotho</option>
																		<option value="Liberia">Liberia</option>
																		<option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
																		<option value="Liechtenstein">Liechtenstein</option>
																		<option value="Lithuania">Lithuania</option>
																		<option value="Luxembourg">Luxembourg</option>
																		<option value="Macau">Macau</option>
																		<option value="Macedonia">Macedonia, The Former Yugoslav Republic of</option>
																		<option value="Madagascar">Madagascar</option>
																		<option value="Malawi">Malawi</option>
																		<option value="Malaysia">Malaysia</option>
																		<option value="Maldives">Maldives</option>
																		<option value="Mali">Mali</option>
																		<option value="Malta">Malta</option>
																		<option value="Marshall Islands">Marshall Islands</option>
																		<option value="Martinique">Martinique</option>
																		<option value="Mauritania">Mauritania</option>
																		<option value="Mauritius">Mauritius</option>
																		<option value="Mayotte">Mayotte</option>
																		<option value="Mexico">Mexico</option>
																		<option value="Micronesia">Micronesia, Federated States of</option>
																		<option value="Moldova">Moldova, Republic of</option>
																		<option value="Monaco">Monaco</option>
																		<option value="Mongolia">Mongolia</option>
																		<option value="Montserrat">Montserrat</option>
																		<option value="Morocco">Morocco</option>
																		<option value="Mozambique">Mozambique</option>
																		<option value="Myanmar">Myanmar</option>
																		<option value="Namibia">Namibia</option>
																		<option value="Nauru">Nauru</option>
																		<option value="Nepal">Nepal</option>
																		<option value="Netherlands">Netherlands</option>
																		<option value="Netherlands Antilles">Netherlands Antilles</option>
																		<option value="New Caledonia">New Caledonia</option>
																		<option value="New Zealand">New Zealand</option>
																		<option value="Nicaragua">Nicaragua</option>
																		<option value="Niger">Niger</option>
																		<option value="Nigeria">Nigeria</option>
																		<option value="Niue">Niue</option>
																		<option value="Norfolk Island">Norfolk Island</option>
																		<option value="Northern Mariana Islands">Northern Mariana Islands</option>
																		<option value="Norway">Norway</option>
																		<option value="Oman">Oman</option>
																		<option value="Pakistan">Pakistan</option>
																		<option value="Palau">Palau</option>
																		<option value="Panama">Panama</option>
																		<option value="Papua New Guinea">Papua New Guinea</option>
																		<option value="Paraguay">Paraguay</option>
																		<option value="Peru">Peru</option>
																		<option value="Philippines">Philippines</option>
																		<option value="Pitcairn">Pitcairn</option>
																		<option value="Poland">Poland</option>
																		<option value="Portugal">Portugal</option>
																		<option value="Puerto Rico">Puerto Rico</option>
																		<option value="Qatar">Qatar</option>
																		<option value="Reunion">Reunion</option>
																		<option value="Romania">Romania</option>
																		<option value="Russia">Russian Federation</option>
																		<option value="Rwanda">Rwanda</option>
																		<option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option> 
																		<option value="Saint LUCIA">Saint LUCIA</option>
																		<option value="Saint Vincent">Saint Vincent and the Grenadines</option>
																		<option value="Samoa">Samoa</option>
																		<option value="San Marino">San Marino</option>
																		<option value="Sao Tome and Principe">Sao Tome and Principe</option> 
																		<option value="Saudi Arabia">Saudi Arabia</option>
																		<option value="Senegal">Senegal</option>
																		<option value="Seychelles">Seychelles</option>
																		<option value="Sierra">Sierra Leone</option>
																		<option value="Singapore">Singapore</option>
																		<option value="Slovakia">Slovakia (Slovak Republic)</option>
																		<option value="Slovenia">Slovenia</option>
																		<option value="Solomon Islands">Solomon Islands</option>
																		<option value="Somalia">Somalia</option>
																		<option value="South Africa">South Africa</option>
																		<option value="South Georgia">South Georgia and the South Sandwich Islands</option>
																		<option value="Span">Spain</option>
																		<option value="SriLanka">Sri Lanka</option>
																		<option value="St. Helena">St. Helena</option>
																		<option value="St. Pierre and Miguelon">St. Pierre and Miquelon</option>
																		<option value="Sudan">Sudan</option>
																		<option value="Suriname">Suriname</option>
																		<option value="Svalbard">Svalbard and Jan Mayen Islands</option>
																		<option value="Swaziland">Swaziland</option>
																		<option value="Sweden">Sweden</option>
																		<option value="Switzerland">Switzerland</option>
																		<option value="Syria">Syrian Arab Republic</option>
																		<option value="Taiwan">Taiwan, Province of China</option>
																		<option value="Tajikistan">Tajikistan</option>
																		<option value="Tanzania">Tanzania, United Republic of</option>
																		<option value="Thailand">Thailand</option>
																		<option value="Togo">Togo</option>
																		<option value="Tokelau">Tokelau</option>
																		<option value="Tonga">Tonga</option>
																		<option value="Trinidad and Tobago">Trinidad and Tobago</option>
																		<option value="Tunisia">Tunisia</option>
																		<option value="Turkey">Turkey</option>
																		<option value="Turkmenistan">Turkmenistan</option>
																		<option value="Turks and Caicos">Turks and Caicos Islands</option>
																		<option value="Tuvalu">Tuvalu</option>
																		<option value="Uganda">Uganda</option>
																		<option value="Ukraine">Ukraine</option>
																		<option value="United Arab Emirates">United Arab Emirates</option>
																		<option value="United Kingdom">United Kingdom</option>
																		<option value="United States">United States</option>
																		<option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
																		<option value="Uruguay">Uruguay</option>
																		<option value="Uzbekistan">Uzbekistan</option>
																		<option value="Vanuatu">Vanuatu</option>
																		<option value="Venezuela">Venezuela</option>
																		<option value="Vietnam">Viet Nam</option>
																		<option value="Virgin Islands (British)">Virgin Islands (British)</option>
																		<option value="Virgin Islands (U.S)">Virgin Islands (U.S.)</option>
																		<option value="Wallis and Futana Islands">Wallis and Futuna Islands</option>
																		<option value="Western Sahara">Western Sahara</option>
																		<option value="Yemen">Yemen</option>
																		<option value="Yugoslavia">Yugoslavia</option>
																		<option value="Zambia">Zambia</option>
																		<option value="Zimbabwe">Zimbabwe</option>
																	</select>
																	<i></i>
																</label>

                                                                </div>
                                                            </div>
															
															<div class="col-md-4 col-sm-4 col-xs-12">
                                                                <div class="form-group">
                                                                    <label class="label">Doctor's State <span class="red">*</span></label>
                                                                    <label class="select">

                                                                       <select name="doc_state" id="doc_state" placeholder="State">
                                                                            <option value="" selected="">Select</option>
                                                                            <option value="Others">Others</option>
                                                                            <option value="Andaman and Nicobar Islands">Andaman and Nicobar Islands</option>
                                                                            <option value="Andhra Pradesh">Andhra Pradesh</option>
                                                                            <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                                                                            <option value="Assam">Assam</option>
                                                                            <option value="Bihar">Bihar</option>
                                                                            <option value="Chandigarh">Chandigarh</option>
                                                                            <option value="Chhattisgarh">Chhattisgarh</option>
                                                                            <option value="Dadra and Nagar Haveli">Dadra and Nagar Haveli</option>
                                                                            <option value="Daman and Diu">Daman and Diu</option>
                                                                            <option value="Delhi">Delhi</option>
                                                                            <option value="Goa">Goa</option>
                                                                            <option value="Gujarat">Gujarat</option>
                                                                            <option value="Haryana">Haryana</option>
                                                                            <option value="Himachal Pradesh">Himachal Pradesh</option>
                                                                            <option value="Jammu and Kashmir">Jammu and Kashmir</option>
                                                                            <option value="Jharkhand">Jharkhand</option>
                                                                            <option value="Karnataka">Karnataka</option>
                                                                            <option value="Kerala">Kerala</option>
                                                                            <option value="Lakshadweep">Lakshadweep</option>
                                                                            <option value="Madhya Pradesh">Madhya Pradesh</option>
                                                                            <option value="Maharashtra">Maharashtra</option>
                                                                            <option value="Manipur">Manipur</option>
                                                                            <option value="Meghalaya">Meghalaya</option>
                                                                            <option value="Mizoram">Mizoram</option>
                                                                            <option value="Nagaland">Nagaland</option>
                                                                            <option value="Odisha">Odisha</option>
                                                                            <option value="Puducherry">Puducherry</option>
                                                                            <option value="Punjab">Punjab</option>
                                                                            <option value="Rajasthan">Rajasthan</option>
                                                                            <option value="Sikkim">Sikkim</option>
                                                                            <option value="Tamil Nadu">Tamil Nadu</option>
                                                                            <option value="Telengana">Telengana</option>
                                                                            <option value="Tripura">Tripura</option>
                                                                            <option value="Uttar Pradesh">Uttar Pradesh</option>
                                                                            <option value="Uttarakhand">Uttarakhand</option>
                                                                            <option value="West Bengal">West Bengal</option>
                                                                        </select>
                                                                        <i></i> <span id="se_state_err" class="error"></span>
                                                                    </label>

                                                                </div>
                                                            </div>
															<div class="col-xs-12 col-md-4 col-sm-4">
                                                                <label class="label">Doctor's City <span class="red">*</span></label>
                                                                <label class="input">
                                                                 
                                                                    <input type="text" name="doc_city" id="doc_city" placeholder="">
                                                                </label>
                                                            </div>
        </div>
		<div class="row">
		<div class="col-md-2 col-xs-12">
															 											 
															 
                                                                <label class="label">Specialist </label>
                                                                <label class="input">
                                                                   																	
                                                                    <input type="text" name="doc_desig" id="doc_desig" value="" class="ui-autocomplete-input" autocomplete="off">
																	
                                                                </label>
        </div>
			<div class="col-md-2 col-xs-12">
                                                                <label class="label">Online Opinion Charge </label>
                                                                <label class="input">
                                                                 
                                                                    <input type="text" name="online_charge" id="online_charge" placeholder="Rs.">
                                                                </label>
                                                            </div>
			<div class="col-md-2 col-xs-12">
                                                                <label class="label">Inperson Opinion Charge </label>
                                                                <label class="input">
                                                                 
                                                                    <input type="text" name="inper_charge" id="inper_charge" placeholder="Rs.">
                                                                </label>
                                                            </div>
			<div class="col-md-2 col-xs-12">
                                                                <label class="label">Consultation Charge </label>
                                                                <label class="input">
                                                                 
                                                                    <input type="text" name="cons_charge" id="cons_charge" placeholder="Rs.">
                                                                </label>
                                                            </div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-md-8 col-sm-8">
								 <label for="file" class="textarea">
									<div class="field_wrapper"><div><label class="label">Consulting Hospital Address1: <span class="red">*</span></label><textarea rows="4" name="doc_hosp1" id="doc_hosp1" placeholder=""></textarea><a href="javascript:void(0);" class="add_button" title="Add more attachments"><i class="fa fa-plus-circle fa-2x"></i></a></div></div>
								
								</label>
			</div>
		
		</div>
		<div class="row">
															<div class="col-xs-12 col-md-8 col-sm-8">
                                                                <label class="label">Area's of Interest, or Expertise <span class="red">*</span></label>
                                                                 <label for="file" class="textarea">
                                                                    <textarea rows="4" name="doc_expert" id="doc_expert" placeholder=""></textarea>

                                                                </label>
                                                            </div>
															
                                                            															 
        </div>
		<div class="row"></div>
		
		<div class="row">
															<div class="col-xs-12 col-md-8 col-sm-8">
                                                                <label class="label">Professional Contributions <span class="red">*</span></label>
                                                                 <label for="file" class="textarea">
                                                                    <textarea rows="4" name="doc_contrubute" id="doc_contrubute" placeholder=""></textarea>

                                                                </label>
                                                            </div>
		</div>
		<div class="row">
															<div class="col-xs-12 col-md-8 col-sm-8">
                                                                <label class="label">Research Details </label>
                                                                 <label for="file" class="textarea">
                                                                    <textarea rows="4" name="doc_research" id="doc_research" placeholder=""></textarea>

                                                                </label>
                                                            </div>
		</div>
		<div class="row">
															<div class="col-xs-12 col-md-8 col-sm-8">
                                                                <label class="label">Publications </label>
                                                                 <label for="file" class="textarea">
                                                                    <textarea rows="4" name="doc_publication" id="doc_publication" placeholder=""></textarea>

                                                                </label>
                                                            </div>
		</div>
		<!--<div class="row">
												<div class="col-xs-12 col-md-12 col-sm-12">
													<p class="note"><b>Note: Above information may be shared with other patients</b></p>
												</div>
		</div>
		<hr>
		<div class="row">
											<div class="col-xs-12 col-md-12 col-sm-12">
											<p class="note"><b>Note: Below information is not shared with other patients but required for validation</b></p>
											</div>
		</div>-->
		
		<div class="row">
			<div class="col-xs-12 col-md-8 col-sm-8">
                                                                <label class="label">Add Profile Photo </label>
                                                                 <label for="file" class="textarea">
                                                                    <input type="file" name="txtPhoto">

                                                                </label>
            </div>								
															
		                                               
		</div>
		<div class="row">
																											
												
		 
													<div class="col-sm-4 pull-right">
                                                        <input type="submit" value="Send" name="submit" id="sumbit" class="form-control submit">
													</div>
		</div>
												
												
		
		</div></form>
	</div>
					
											
		
  </div>
</div>

	
	
<footer class="main-footer">
	<div class="copyright">
					
						<p>Copyrights © 2016 Medisense Healthcare Solutions</p>
						
	</div>
				
</footer>	
   
    <!--<script src="../assets/js/jquery-1.10.2.min.js"></script>
    <script src="../assets/js/jquery.js"></script>-->
    <script src="../assets/js/bootstrap.min.js"></script>
 <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
    <script type="text/javascript" src="../assets/js/validation.js"></script>
	
	
	
</body>
</html>