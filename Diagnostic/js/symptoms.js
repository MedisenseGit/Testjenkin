$(document).ready(function() {
	
	$("#view-trend-analysis").hide();
		$("#view-latest-reports").hide();
		//$("#visit-details").hide();
		$("#medical-history").hide();
		$("#ReportSection").hide();
		$("#add-visit-dtails").hide();
		
	$("#visitDetails").click(function(){
		
		$("#visit-details").show();
		$("#view-trend-analysis").hide();
		$("#view-latest-reports").hide();
		$("#medical-history").hide();
		$("#add-visit-dtails").hide();
	});
	
	$("#medicalHistory").click(function(){
		
		$("#medical-history").show();
		$("#view-trend-analysis").hide();
		$("#view-latest-reports").hide();
		$("#visit-details").hide();
		$("#add-visit-dtails").hide();
	});
	
	$("#addvisitDetails").click(function(){
		$("#add-visit-dtails").show();
		$("#view-trend-analysis").hide();
		$("#view-latest-reports").hide();
		$("#visit-details").hide();
		$("#medical-history").hide();
	});
	
	$("#latestReports").click(function(){
		$("#view-latest-reports").show();
		$("#view-trend-analysis").hide();
		$("#add-visit-dtails").hide();
		$("#visit-details").hide();
		$("#medical-history").hide();
	});
	$("#trendAnalysis").click(function(){
		$("#view-trend-analysis").show();
		$("#view-latest-reports").hide();
		$("#add-visit-dtails").hide();
		$("#visit-details").hide();
		$("#medical-history").hide();
	});
	
	//On click attach reports button
	$("body").on("click", "#attachReport", function() {
		$("#ReportSection").show();		
		$("#attachReport").hide();
	});
	$("body").on("click", "#cancel", function() {
		$("#ReportSection").hide();		
		$("#attachReport").show();
	});
	
	
	$("body").on("blur", ".actualVal", function() {
		
		var actualval = $(this).val();	
		var diagnoinvestid = $(this).attr("data-diagno-invest-id");
		var url = "my_patient_profile_save.php?diagnoinvestid="+diagnoinvestid+"&actualval="+actualval;
		//console.log(tradename,url);
		//$(this).parent("span").remove();
		if(actualval == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	
	$("body").on("click", ".del_Invest", function() {
		var delinvestid = $(this).attr("data-invest-id");
		var url = "get_diagno_test_report.php?delsubtestid="+delinvestid;
		console.log(delinvestid, url);
		//$("#beforeSymptom").remove();
		$("#delInvestRow"+delinvestid).remove();
		if(delinvestid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	//For Add Investigations scripts
	
	$("#get_diagnosis_test").on("change", function(){
		var patientid = $(this).attr("data-patient-id");
		var investid = $(this).val();
		var url = "get_diagno_test_report.php?investid="+investid+"&patientid="+patientid;
		//console.log(url,investid,patientid);
		
		if(investid == ""){
			return false;
		}
		else{
			$.get(url, function(response){
				//console.log(response);
				$("#dispDignoTest").html("").prepend(response);
			});
		}
			
		$('.searchDiagnosTest').val(''); //Clear search field
		$("#get_diagnosis_test").focus();	//focus search filed 
	});
	
	$("#get_diagnosis_test").keydown(function(e) {
		if(e.originalEvent.keyCode == 13){ //If User press enter key
		var patientid = $(this).attr("data-patient-id");
		var investid = $(this).val();
		var url = "get_diagno_test_report.php?investid="+investid+"&patientid="+patientid;
		//console.log(patientid, investid, url);
		
		if(investid == "") {
			return false;
		} else {
			$.get(url, function(response){
				//console.log(response);
				$("#dispDignoTest").html("").prepend(response);
			});
		}
		
			$('.searchDiagnosTest').val(''); //Clear search field
			$("#get_diagnosis_test").focus();	//focus search filed 
			
			return false;
		}
		//console.log(e);
	}); 
	
	$(".get_diagnosis_test_prior").on("click", function() {
		var patientid = $(this).attr("data-patient-id");
		var investid = $(this).attr("data-main-test-id");		
		var url = "get_diagno_test_report.php?investid="+investid+"&patientid="+patientid;
		//console.log(patientid, investid, url);
		
		if(investid == "") {
			return false;
		} else {
			$.get(url, function(response){
				//console.log(response);
				$("#dispDignoTest").html("").prepend(response);
			});
		}
		

	});
	
	$("body").on("click",".delete_all_diagnosis_test", function() {
		var patientid = $(this).attr("data-patient-id");
		var docid = $(this).attr("data-doctor-id");		
		var url = "get_diagno_test_report.php?delallInvest=1&docid="+docid+"&patid="+patientid;
		//console.log(patientid, docid, url);
		
		if(docid == "") {
			return false;
		} else {
			$.get(url, function(response){
				//console.log(response);
				$("#dispDignoTest").html("").prepend(response);
			});
		}
		

	});
	
	$("body").on("click",".del_diagnosis_test", function() {
		var deleaxaminationid = $(this).attr("data-diagnosis-id");		
		var url = "get_examination.php?deleaxaminationid="+deleaxaminationid;
		//console.log(patientid, docid, url);
		$(this).parent("span").remove();
		if(deleaxaminationid == "") {
			return false;
		} else {
			$.get(url, function(response){
				//console.log(response);
			});
		}
		

	});
	//For Add Examination scripts
 
	$("#get_examination_res").on("change", function(){
		var patientid = $(this).attr("data-patient-id");
		var examinationid = $(this).val();
		var url = "get_examination.php?examinationid="+examinationid+"&patientid="+patientid;
		console.log(url,examinationid,patientid);
		
		if(examinationid == ""){
			return false;
		}
		else{
			$.get(url, function(response){
				//console.log(response);
				$("#dispExamination").html("").prepend(response);
			});
		}
			
		$('.searchExamination').val(''); //Clear search field
			$("#get_examination_res").focus();	//focus search filed 
	});
	
	$("#get_examination_res").keydown(function(e) {
		if(e.originalEvent.keyCode == 13){ //If User press enter key
		var patientid = $(this).attr("data-patient-id");
		var examinationid = $(this).val();
		var url = "get_examination.php?examinationid="+examinationid+"&patientid="+patientid;
		//console.log(patientid, investid, url);
		
		if(examinationid == "") {
			return false;
		} else {
			$.get(url, function(response){
				//console.log(response);
				$("#dispExamination").html("").prepend(response);
			});
		}
		
			$('.searchExamination').val(''); //Clear search field
			$("#get_examination_res").focus();	//focus search filed 
			
			return false;
		}
		//console.log(e);
	}); 
	
	$(".get_examination_res_prior").on("click", function() {
		var patientid = $(this).attr("data-patient-id");
		var examinationid = $(this).attr("data-examination_id");		
		var url = "get_examination.php?examinationid="+examinationid+"&patientid="+patientid;
		//console.log(patientid, investid, url);
		
		if(examinationid == "") {
			return false;
		} else {
			$.get(url, function(response){
				//console.log(response);
				$("#dispExamination").html("").prepend(response);
			});
		}
		

	});
	
	$("body").on("click",".delete_all_examination", function() {
		var patientid = $(this).attr("data-patient-id");
		var docid = $(this).attr("data-doctor-id");		
		var url = "get_examination.php?delallExam=1&docid="+docid+"&patid="+patientid;
		console.log(patientid, docid, url);
		
		if(docid == "") {
			return false;
		} else {
			$.get(url, function(response){
				//console.log(response);
				$("#dispExamination").html("").prepend(response);
			});
		}
		

	});
	
	$("body").on("click",".del_examination", function() {
		var deleaxaminationid = $(this).attr("data-examination-id");		
		var url = "get_examination.php?deleaxaminationid="+deleaxaminationid;
		//console.log(patientid, docid, url);
		//$(this).parent("span").remove();
		$("#del_examination_row"+deleaxaminationid).remove();
		if(deleaxaminationid == "") {
			return false;
		} else {
			$.get(url, function(response){
				//console.log(response);
			});
		}
		

	});
	
	$("body").on("blur", ".exam_res", function() {
		
		var examres = $(this).val();	
		var examid = $(this).attr("data-examination-id");
		var url ="get_examination.php?updateexamid="+examid+"&examres="+examres;
		//console.log(examres,examid,url);
		//$(this).parent("span").remove();
		$("#findings").focus();
		if(examid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				$("#findings").focus();
				
				
			});
		}
	});
	$("body").on("blur", ".findings", function() {
		
		var examfindings = $(this).val();	
		var examid = $(this).attr("data-examination-id");
		var url = "get_examination.php?updateexamid="+examid+"&examfindings="+examfindings;
		//console.log(examfindings,examid,url);
		//$(this).parent("span").remove();
		if(examid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	
	$("body").on("blur", ".addTestActualVal", function() {
		var editinvestid = $(this).attr("data-diagno-invest-id");
		var dateadded = $(this).attr("data-date-added");
		var editinvestval = $(this).val();
		var url = "get_diagno_test_report.php?editinvestactual="+editinvestid+"&editinvestval="+editinvestval+"&dateadded="+dateadded;
		console.log(editinvestid,editinvestval,url);
	
		if(editinvestid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
									
			});
			
		}
	});
	
	
});