<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<!-- <script src="js/jquery-3.5.1.min.js"></script> -->
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script> -->
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<!-- Slick Slider Js -->
<script src="js/slick.min.js"></script>
<!-- Magnific popup Js -->
<script src="js/jquery.magnific-popup.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
<!-- Script Js -->
<script src="js/script.js"></script>
<script src="js/bootstrap-drawer.js"></script>
<script type="text/javascript">
    $('#my-drawer').drawer('toggle');

    $(document).ready(function(){
		var multipleCancelButton = new Choices('#choices-multiple-remove-button', {
			removeItemButton: true,
			maxItemCount:5,
			searchResultLimit:5,
			renderChoiceLimit:5
		});

		var multipleCancelButton1 = new Choices('#specialization', {
            removeItemButton: true,
            //maxItemCount:5,
            //searchResultLimit:5,
            renderChoiceLimit:15 
			
        });

        var multipleCancelButton2 = new Choices('#consult_lang', {
            removeItemButton: true,
            //maxItemCount:5,
            //searchResultLimit:5,
            renderChoiceLimit:15          
        });
        
	});

	// function emailvalidation(){	
	// 	var doc_email=$('#doc_email').val();
	// 	var Contact_num=$('#Contact_num').val();	
	// 	$.ajax({
	// 		type: "POST",
	// 		url: "get_docemail.php",
	// 		data:{"email": doc_email,"contact": Contact_num},
	// 		success: function(data){				
	// 			if(data!=""){
	// 				//$('#doc_email').val("");
	// 				return fasle;
	// 			}
	// 		}
				
	// 	});
	// }

	function getState(val) {
		var data_val = $("#doc_country option:selected").attr("myTag");

		$('#sel_country_id').val(data_val);
		$('#selected_country_id').val(data_val);

		//alert(data_val);
		$.ajax({
			type: "POST",
			url: "get_state.php",
			data:{"country_name":data_val},
			success: function(data){
				//alert(data);
				var val=data.split("@");
				$("#doc_state").html(val[0]);
				$("#Country_code").html(val[1]);
				$("#alt_Country_code").html(val[1]);
			}
		});
	}

	function validate(){
		var password = $('#Password').val();//document.getElementById("password")
		var  confirm_password = $('#Confirm_Password').val();//document.getElementById("confirm_password");

		if(password != confirm_password){
			$('.error_pass').html("<span class='text-danger'><i class='fas fa-times'></i> Passwords don't match</span>");
			//class="error"
			return false;
		}
		$('.error_pass').html("<span class='text-success'><i class='fas fa-check'></i> Passwords Match</span>");
	}

	// APPEND WORK DETAILS
	$(".add_work_his_details").click(function(){

		$(".user-details1").append('<div class="work_his_data"><div class="row"><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">Institution Name</label><input type="text" class="form-control" id="Institution_Name"  name="Institution_Name[]" placeholder="Institution Name"></div></div><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="inputState">Work Type</label><select id="work_type"  name="work_type[]" class="form-control"><option selected>Work Type</option><option>Clinic</option><option>Hospital</option></select></div></div><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">Communication Address (Institution)</label><input type="text" class="form-control" id="Communication_Address"  name="Communication_Address[]" placeholder="Communication Address"></div></div></div><div class="row"><div class="col-xs-12 col-md-4 col-sm-4"><label for="formGroupExampleInput2">Phone Number (Institution)<span class="red">*</span></label><div class="row"><div class="col-xs-4 col-md-4 col-sm-4" style="padding-right:5px;"><select id="Phone_Country_code"  name="Phone_Country_code[]" class="form-control" ><option value="" ></option><?php $SrcName1= $objQuery->mysqlSelect("*","countries","","","","","");$i=30; foreach($SrcName1 as $srcList){ ?><option value="<?php echo stripslashes($srcList["ph_extn"]);?>" > <?php echo stripslashes($srcList["ph_extn"]);?></option><?php $i++; } ?>   </select></div><div class="col-xs-8 col-md-8 col-sm-8" style="padding-left:0px;"><input type="number" class="form-control no-spinner" id="Phone_Number" name="Phone_Number[]" placeholder="Contact Number">  </div></div></div><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">Start Date</label><input type="date" class="form-control" id="work_Start_Date"  name="work_Start_Date[]" placeholder="Start Date"></div></div><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">End Date</label><input type="date" class="form-control" id="work_End_Date" name="work_End_Date[]" placeholder="End Date"></div></div><button class="remove-btn btn btn-danger" data-toggle="tooltip" data-placement="top" title="Delete" style="float: right;"><i class="fas fa-trash" style="color:red" aria-hidden="true"></i></button></div>');
	});
	$("body").on("click",".remove-btn",function(e){
		$(this).parents('.work_his_data').remove();
		//the above method will remove the user_data div
	});

	//APPEND DETAILS
	$(".add_details").click(function(){		
		$(".user-details").append('<div class="use_data"><div class="row"><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">Medical Council Registered with</label><input type="text" class="form-control" id="Medical_Council_reg" name="Medical_Council_reg[]" placeholder="Medical Council Registered with"></div></div><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">Registration Number</label><input type="number" class="form-control" id="Reg_Num" name="Reg_Num[]"  placeholder="Registration Number"></div></div><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="exampleFormControlFile1">Upload Registration Certificate</label><input type="file" class="form-control-file" id="Upload_Reg_cer" name="txtUpload_Reg_cer[]"></div></div></div><div class="row"><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">Registration Date<span class="red">*</span></label><input   type="date" class="form-control" id="Registration_Date"  name="Registration_Date[]" placeholder="Registration Date" value=""></div></div></div><button class="remove-btn btn btn-danger" data-toggle="tooltip" data-placement="top" title="Delete" style="float: right;"><i class="fas fa-trash" style="color:red" aria-hidden="true"></i></button></div>');
	});

	$("body").on("click",".remove-btn",function(e){
		$(this).parents('.use_data').remove();
		//the above method will remove the user_data div
	});

	//APPEND ACADEMIC DETAILS
	$(".academic_add_details").click(function(){
		$(".academic_user-details").append('<div class="academic_use_data"><div class="row"><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">Type of Qualification</label><input type="text" class="form-control" id="Type_of_qualification" name="Type_of_qualification[]" placeholder="Type of qualification"></div></div><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">Country</label><select name="acd_doc_country[]" class="form-control" id="doc_country" onchange="return getState(this.value);"><option value="India" myTag="100"  selected>Select</option><?php $CntName = $objQuery->mysqlSelect("*", "countries", "", "country_name asc", "", "", "");$i= 30; foreach ($CntName as $CntNameList) {?> <option   myTag="<?php echo stripslashes($CntNameList["country_id"]); ?>" value="<?php echo stripslashes($CntNameList["country_name"]); ?>" ><?php echo stripslashes($CntNameList["country_name"]); ?></option><?php $i++; } ?></select><i></i></div></div><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">City</label><input type="text" class="form-control" id="acd_City" name="acd_City[]" placeholder="City"></div></div></div><div class="row"><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">Start Date</label><input type="date" class="form-control" id="Start_Date" name="acd_Start_Date[]" placeholder="Start Date"></div></div><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">End Date</label><input type="date" class="form-control" id="End_Date" name="acd_End_Date[]" placeholder="End Date"></div></div></div><button class="remove-btn btn btn-danger" data-toggle="tooltip" data-placement="top" title="Delete" style="float: right;"><i class="fas fa-trash" style="color:red" aria-hidden="true"></i></button></div>');
	});

	$("body").on("click",".remove-btn",function(e){
		
		$(this).parents('.academic_use_data').remove();
		//the above method will remove the user_data div
	});

</script>