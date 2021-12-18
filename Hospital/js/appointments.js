$(function()
{ 
		// on search by date range
						$('.searchByDate').click(function(){
							var fromDate = $('#fromDate');
							var toDate = $('#toDate');
							//alert(fromDate.val());
							//alert(toDate.val());
							$("#before-status").hide();
							if( fromDate.val()=='')
							{ 
								fromDate.val('');
								return false;
							}
							else if( toDate.val()=='')
							{
								toDate.val('');
								return false;
							}
							else{ 
								var url = "add_details.php?frmDate="+fromDate.val()+"&toDate="+toDate.val();
		
								$.get(url, function(response){
									//console.log(response);
									$("#after-status").html("").prepend(response);
									
								});
								/*$.ajax({
									type: "POST",
									url: "add_details.php",
									data: 'act=serAppointment&frmDate='+fromDate.val()+'&toDate='+toDate.val(),
									success: function(html){
										fromDate.val('');
										toDate.val('');
										$("#after-status").show();
									}  
								});*/
							}
						});

}
);
$(document).ready(function() {
	$("#todayAppList").show();
	$("#tokenSlot").show();
	$("#futureAppList").hide();
	$("#allAppList").hide();
	$("#data_5").hide();
	
	$("#date_filter").on("click", function(){
		$("#data_5").show();
	});
	
	$("#canceldatepicker").on("click", function(){
		$("#data_5").hide();		
	});
	$("#today_app").on("click", function(){
		$("#todayAppList").show();
		$("#tokenSlot").show();
		$("#futureAppList").hide();
		$("#allAppList").hide();
		$("#after-status").hide();
	});
	$("#future_app").on("click", function(){
		$("#futureAppList").show();
		$("#todayAppList").hide();
		$("#allAppList").hide();
		$("#tokenSlot").hide();
		$("#after-status").hide();
	});
	$("#all_app").on("click", function(){
		$("#allAppList").show();
		$("#todayAppList").hide();
		$("#futureAppList").hide();
		$("#tokenSlot").hide();
		$("#after-status").hide();
	});
	
	$(".patient-status").on("click", function(){
		var statusId = $(this).attr("data-status-id");
		var transId = $(this).attr("data-appoint-transid");
		var url = "change_appointment_status.php?transid="+transId+"&statusId="+statusId;
		console.log(statusId,transId,url);

		
		if(transId == ""){
			return false;
		}
		else
		{
			var statusIds = {		    
			    "1": ["Confirmed", "btn-warning"], 
				"6": ["At reception", "btn-warning"],
			    "2": ["Consulted", "btn-primary"], 
			    "3": ["Cancelled", "btn-default"], 
			    "4": ["Pending", "btn-danger"], 
			    "5": ["Missed", "btn-danger"],
				"7": ["VC Ready", "btn-primary"],
				"8": ["VC Confirmed", "btn-info"]
			};
			console.log(statusIds[statusId]);
			var btn = $(this).parent().parent().prev('.btn');
			var container = btn.parent();
			    
			$.get(url, function(response){
			    if(statusId == 3) {
				container.parent().parent().remove();
			    } else {
				var classes = btn.attr('class').split(/\s+/);
				$.each(classes, function(index, item) {
				    if(item.substring(0, 4) === "btn-" && item != "btn-xs") {
					btn.removeClass(classes[index]);
				    }
				});
				btn.addClass(statusIds[statusId][1]).html(statusIds[statusId][0]).append(' <span class="caret"></span>');
				container.removeClass("open");
			    }
			});
			
		}
		return false;
	});

	$("body").on("change","#pincode_gen", function(){
		
		var pincode = $(this).val();
		var url = "get_city_state.php?pincode="+pincode;
		console.log(pincode,url);
		if(pincode == ""){
			return false;
		}
		else{
			$.get(url, function(response){
				//console.log(response);
				$("#dispCity").html("").prepend(response);
				
			});
		}
		$("#beforeLoad").hide();
	});
	
	
	$("#get_direct_corporate_appointment_details").on("change", function(){  
		var hospid = $(this).attr("data-hosp-id");
		var patientid = $(this).val();
		var url = "get_direct_corporate_appointment_result.php?getAppSer=true&patientid="+patientid+"&hospid="+hospid;
		//alert(url);
		console.log(hospid,patientid);
		$("#before-search").hide();
		if(patientid == ""){
			return false;
		}
		else{
			$.get(url, function(response){
				//console.log(response);
				$("#after-search").html("").prepend(response);
				$(".chosen-select").chosen();
				$('.chosen-select').trigger("chosen:updated");
			});
		}
			
		$(':text').val(''); //Clear search field
		$("#get_direct_corporate_appointment_details").focus();	//focus search filed
	});

	$("#get_direct_corporate_appointment_details").keydown(function(e) {
		if(e.originalEvent.keyCode == 13){ //If User press enter key
		var hospid = $(this).attr("data-hosp-id");
		var patientid = $(this).val();
		var url = "get_direct_corporate_appointment_result.php?getAppSer=true&patientid="+patientid+"&hospid="+hospid;
		console.log(hospid,patientid);
		$("#before-search").hide();
		if(patientid == "") {
			return false;
		} else {
			$.get(url, function(response){
				//console.log(response);
				$("#after-search").html("").prepend(response);
				$(".chosen-select").chosen();
			});
		}
		
			$(':text').val(''); //Clear search field
			$("#get_direct_corporate_appointment_details").focus();	//focus search filed 
			return false;
		}
		//console.log(e);
	}); 
	
	
	
	
	$("#get_direct_appointment_details").on("change", function(){  
		var hospid = $(this).attr("data-hosp-id");
		var patientid = $(this).val();
		var url = "get_direct_appointment_result.php?getAppSer=true&patientid="+patientid+"&hospid="+hospid;
		//alert(url);
		console.log(hospid,patientid);
		$("#before-search").hide();
		if(patientid == ""){
			return false;
		}
		else{
			$.get(url, function(response){
				//console.log(response);
				$("#after-search").html("").prepend(response);
				$(".chosen-select").chosen();
			});
		}
			
		$(':text').val(''); //Clear search field
		$("#get_direct_appointment_details").focus();	//focus search filed
	});
	
	$("#get_direct_appointment_details").keydown(function(e) {
		if(e.originalEvent.keyCode == 13){ //If User press enter key
		var hospid = $(this).attr("data-hosp-id");
		var patientid = $(this).val();
		var url = "get_direct_appointment_result.php?getAppSer=true&patientid="+patientid+"&hospid="+hospid;
		console.log(hospid,patientid);
		$("#before-search").hide();
		if(patientid == "") {
			return false;
		} else {
			$.get(url, function(response){
				//console.log(response);
				$("#after-search").html("").prepend(response);
				$(".chosen-select").chosen();
			});
		}
		
			$(':text').val(''); //Clear search field
			$("#get_direct_appointment_details").focus();	//focus search filed 
			return false;
		}
		//console.log(e);
	}); 
	
	$(".chkTime").on("change", function(){
		var chkTime = $(this).val();
		var url = "add_details.php?chkTime="+chkTime;
		console.log(url,chkTime);
	
		//$("#date-time-section").hide();
		if(chkTime == ""){
			return false;
		}
		else{
			$.get(url, function(response){
			
			});
		}
			
		
	});

});
