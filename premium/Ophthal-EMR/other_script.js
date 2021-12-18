$(document).ready(function() {
		$("#view-trend-analysis1").hide();
		$("#view-latest-reports1").hide();
		$("#visit-details1").hide();
		$("#medical-history1").hide();
		$("#ReportSection1").hide();
		$("#add-visit-dtails1").hide();
		
	$("#visitDetails1").click(function(){
		
		$("#visit-details1").show();
		$("#view-trend-analysis1").hide();
		$("#view-latest-reports1").hide();
		$("#medical-history1").hide();
		$("#add-visit-dtails1").hide();
	});
	
	$("#medicalHistory1").click(function(){
		
		$("#medical-history1").show();
		$("#view-trend-analysis1").hide();
		$("#view-latest-reports1").hide();
		$("#visit-details1").hide();
		$("#add-visit-dtails1").hide();
	});
	
	$("#addvisitDetails1").click(function(){
		$("#add-visit-dtails1").show();
		$("#view-trend-analysis1").hide();
		$("#view-latest-reports1").hide();
		$("#visit-details1").hide();
		$("#medical-history1").hide();
	});
	
	$("#latestReports1").click(function(){
		alert("Test");
		$("#view-latest-reports1").show();
		$("#view-trend-analysis1").hide();
		$("#add-visit-dtails1").hide();
		$("#visit-details1").hide();
		$("#medical-history1").hide();
	});
	$("#trendAnalysis1").click(function(){
		$(".view-trend-analysis1").show();
		$("#view-latest-reports1").hide();
		$("#add-visit-dtails1").hide();
		$("#visit-details1").hide();
		$("#medical-history1").hide();
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