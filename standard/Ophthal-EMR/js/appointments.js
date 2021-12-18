$(document).ready(function() {
	$("#todayAppList").show();
	$("#tokenSlot").show();
	$("#futureAppList").hide();
	$("#allAppList").hide();
	
	$("#today_app").on("click", function(){
		$("#todayAppList").show();
		$("#tokenSlot").show();
		$("#futureAppList").hide();
		$("#allAppList").hide();
	});
	$("#future_app").on("click", function(){
		$("#futureAppList").show();
		$("#todayAppList").hide();
		$("#allAppList").hide();
		$("#tokenSlot").hide();
	});
	$("#all_app").on("click", function(){
		$("#allAppList").show();
		$("#todayAppList").hide();
		$("#futureAppList").hide();
		$("#tokenSlot").hide();
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
			    "5": ["Missed", "btn-danger"]
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
	
	
	
	$("#get_appointment_details").on("change", function(){
		var docid = $(this).attr("data-doc-id");
		var patientid = $(this).val();
		var url = "get_appointment_result.php?patientid="+patientid+"&docid="+docid;
		console.log(docid,patientid);
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
		$("#get_appointment_details").focus();	//focus search filed
	});
	
	$("#get_appointment_details").keydown(function(e) {
		if(e.originalEvent.keyCode == 13){ //If User press enter key
		var docid = $(this).attr("data-doc-id");
		var patientid = $(this).val();
		var url = "get_appointment_result.php?patientid="+patientid+"&docid="+docid;
		console.log(docid,patientid);
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
			$("#get_appointment_details").focus();	//focus search filed 
			return false;
		}
		//console.log(e);
	}); 

});
