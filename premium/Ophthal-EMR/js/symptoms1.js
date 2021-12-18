$(document).ready(function() {
	$("#view-trend-analysis").hide();
		$("#view-latest-reports").hide();
		$("#visit-details").hide();
		$("#medical-history").hide();
		$("#ReportSection").hide();
	
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
	
		
	
});
