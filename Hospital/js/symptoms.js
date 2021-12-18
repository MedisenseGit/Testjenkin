$(document).ready(function() {
	var s=window.location.href;
	var n = s.indexOf('My-Patient-Details?');
	var m = s.indexOf('Quick-Prescription?');
  s = s.substring(0, n != -1 ? n : s.length);
  s = s.substring(0, m != -1 ? m : s.length);
  var hostUrl = s;
  //alert(hostUrl);
  //$("#afterTempEdit").hide();
 $("#cancelTempExam").hide();
 $("#cancelTempInvestigation").hide();
 $("#cancelTempPrescription").hide();
 
	$("#view-trend-analysis").hide();
		$("#view-latest-reports").hide();
		//$("#visit-details").hide();
		$("#medical-history").hide();
		$("#ReportSection").hide();
		$("#custom-view-trend-analysis").css("display","none");
	
	$("#visitDetails").click(function(){
		
		$("#visit-details").show();
		$("#view-trend-analysis").hide();
		$("#view-latest-reports").hide();
		$("#medical-history").hide();
		$("#add-visit-dtails").hide();
		$("#edit_visist_details").hide();
		$("#custom-view-trend-analysis").css("display","none");
	});
	
	$("#medicalHistory").click(function(){
		
		$("#medical-history").show();
		$("#view-trend-analysis").hide();
		$("#view-latest-reports").hide();
		$("#visit-details").hide();
		$("#add-visit-dtails").hide();
		$("#edit_visist_details").hide();
		$("#custom-view-trend-analysis").css("display","none");
	});
	
	
	$("#addvisitDetails").click(function(){
		$("#add-visit-dtails").show();
		$("#view-trend-analysis").hide();
		$("#view-latest-reports").hide();
		$("#visit-details").hide();
		$("#medical-history").hide();
		$("#edit_visist_details").hide();
		$("#custom-view-trend-analysis").css("display","none");
	});
	
	$("#latestReports").click(function(){
		$("#view-latest-reports").show();
		$("#view-trend-analysis").hide();
		$("#add-visit-dtails").hide();
		$("#visit-details").hide();
		$("#medical-history").hide();
		$("#edit_visist_details").hide();
		$("#custom-view-trend-analysis").css("display","none");
	});
	$("#trendAnalysis").click(function(){
		$("#view-trend-analysis").show();
		$("#view-latest-reports").hide();
		$("#add-visit-dtails").hide();
		$("#visit-details").hide();
		$("#medical-history").hide();
		$("#edit_visist_details").hide();
		$("#custom-view-trend-analysis").css("display","none");
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
	
	
	
	//Live update patient medical history section start here
	
	$("body").on("click", ".hyperCondition", function() {
		
		var pathyper = $(this).val();	
		var patientid = $(this).attr("data-patient-id");
		var url = hostUrl+"upadte_medical_history.php?updatecon=1&patientid="+patientid+"&pathyper="+pathyper;
		console.log(pathyper,url);
		//$(this).parent("span").remove();
		
		if(pathyper == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
					 setTimeout(function() {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    showMethod: 'slideDown',
                    timeOut: 1500
                };
                toastr.success('', 'Hypertension Updated Successfully');

            }, 100);
				
			});
		}
	});
	$("body").on("change", ".smokeCondition", function() {
		
		var patsmoke = $(this).val();	
		var patientid = $(this).attr("data-patient-id");
		var url = hostUrl+"upadte_medical_history.php?updatecon=1&patientid="+patientid+"&patsmoke="+patsmoke;
		console.log(patsmoke,url);
		//$(this).parent("span").remove();
		
		if(patsmoke == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				 setTimeout(function() {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    showMethod: 'slideDown',
                    timeOut: 1500
                };
                toastr.success('', 'Patient smoking condition updated Successfully');

            }, 100);	
				
			});
		}
	
	});
	$("body").on("click", ".diabetesCondition", function() {
		
		var patdiabetes = $(this).val();	
		var patientid = $(this).attr("data-patient-id");
		var url = hostUrl+"upadte_medical_history.php?updatecon=1&patientid="+patientid+"&patdiabetes="+patdiabetes;
		//console.log(examres,examid,url);
		//$(this).parent("span").remove();
		
		if(patdiabetes == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				setTimeout(function() {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    showMethod: 'slideDown',
                    timeOut: 1500
                };
                toastr.success('', 'Patient diabetes status updated Successfully');

            }, 100);
				
			});
		}
	});
	$("body").on("change", ".alcoholCondtion", function() {
		
		var patalcohol = $(this).val();	
		var patientid = $(this).attr("data-patient-id");
		var url = hostUrl+"upadte_medical_history.php?updatecon=1&patientid="+patientid+"&patalcohol="+patalcohol;
		//console.log(examres,examid,url);
		//$(this).parent("span").remove();
		
		if(patalcohol == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				setTimeout(function() {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    showMethod: 'slideDown',
                    timeOut: 1500
                };
                toastr.success('', 'Patient alcoholic status updated successfully');

            }, 100);
				
			});
		}
	});
	
	$("body").on("blur", ".prevIntervent", function() {
		
		var previntervent = $(this).val();	
		var patientid = $(this).attr("data-patient-id");
		var url = hostUrl+"upadte_medical_history.php?updatecon=1&patientid="+patientid+"&previntervent="+previntervent;
		//console.log(examres,examid,url);
		//$(this).parent("span").remove();
		
		if(previntervent == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				setTimeout(function() {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    showMethod: 'slideDown',
                    timeOut: 1500
                };
                toastr.success('', 'Previous Interventions Updated Successfully');

            }, 100);
				
			});
		}
	});
	$("body").on("blur", ".otherDetail", function() {
		
		var otherdetail = $(this).val();	
		var patientid = $(this).attr("data-patient-id");
		var url = hostUrl+"upadte_medical_history.php?updatecon=1&patientid="+patientid+"&otherdetail="+otherdetail;
		//console.log(examres,examid,url);
		//$(this).parent("span").remove();
		
		if(otherdetail == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				setTimeout(function() {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    showMethod: 'slideDown',
                    timeOut: 1500
                };
                toastr.success('', 'Other Details Updated Successfully');

            }, 100);
				
			});
		}
	});
	$("body").on("blur", ".neuroIssue", function() {
		
		var neuroissue = $(this).val();	
		var patientid = $(this).attr("data-patient-id");
		var url = hostUrl+"upadte_medical_history.php?updatecon=1&patientid="+patientid+"&neuroissue="+neuroissue;
		//console.log(examres,examid,url);
		//$(this).parent("span").remove();
		
		if(neuroissue == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				setTimeout(function() {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    showMethod: 'slideDown',
                    timeOut: 1500
                };
                toastr.success('', 'Neurological Issues Updated Successfully');

            }, 100);
				
			});
		}
	});
	$("body").on("blur", ".kidneyIssue", function() {
		
		var kedneyissue = $(this).val();	
		var patientid = $(this).attr("data-patient-id");
		var url = hostUrl+"upadte_medical_history.php?updatecon=1&patientid="+patientid+"&kedneyissue="+kedneyissue;
		//console.log(examres,examid,url);
		//$(this).parent("span").remove();
		
		if(kedneyissue == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				setTimeout(function() {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    showMethod: 'slideDown',
                    timeOut: 1500
                };
                toastr.success('', 'Kidney Issues Updated Successfully');

            }, 100);
				
			});
		}
	});
	
	//update patient medical history section ends here
	
		
	//Scripts for drug allergy
	$("#get_allergy").on("blur", function() {
		var patientid = $(this).attr("data-patient-id");
		var generic = $(this).val();
		var url = hostUrl+"get_allergy.php?generic="+generic+"&patientid="+patientid;
		//console.log(patientid, drugid, url);
		$("#drugAllergyBefore").hide();
		if(generic == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				$("#drugAllergyAfter").html("").prepend(response);
				 setTimeout(function() {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    showMethod: 'slideDown',
                    timeOut: 1500
                };
                toastr.success('', 'Drug allergy updated Successfully');

            }, 100);
			});
		}
		
        $('.searchAllergy').val(''); //Clear search field
		$("#get_allergy").focus();	//focus search filed 

	});
	$("#get_allergy").keydown(function(e) {
		if(e.originalEvent.keyCode == 13){
		var patientid = $(this).attr("data-patient-id");
		var generic = $(this).val();
		var url = hostUrl+"get_allergy.php?generic="+generic+"&patientid="+patientid;
		//console.log(patientid, drugid, url);
		$("#drugAllergyBefore").hide();
		if(generic == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				$("#drugAllergyAfter").html("").prepend(response);
				 setTimeout(function() {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    showMethod: 'slideDown',
                    timeOut: 1500
                };
                toastr.success('', 'Drug allergy updated Successfully');

            }, 100);
			
			});
		}
		
			$('.searchAllergy').val(''); //Clear search field
			$("#get_allergy").focus();	//focus search filed  
			return false;
		}
		//console.log(e);
	}); 
	
	$("body").on("click", ".del_allergy", function() {
		var allergyid = $(this).attr("data-drug-allergy-id");
		var url = hostUrl+"get_allergy.php?allergyid="+allergyid;
		//console.log(treatid, url);
		$(this).parent("span").remove();
		if(allergyid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				 setTimeout(function() {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    showMethod: 'slideDown',
                    timeOut: 1500
                };
                toastr.success('', 'Drug allergy updated Successfully');

            }, 100);
			});
		}
		

	});
	
	
	
	/*
	
	
	
	
	
	//When press remove button in treatment it will triggers following function
	$("body").on("click", ".del_treatment", function() {
		var sympid = $(this).attr("data-symptom-id");
		var url = hostUrl+"get_symptoms.php?delsympid="+sympid;
		//console.log(sympid, url);
		$(this).parent("span").remove();
		if(sympid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
		

	}); */
	
	//Clear All Prescription
	
	$("body").on("click", ".clear_all", function() {
		
		var url = hostUrl+"add_medicine.php?clearall=true";
		//console.log(tradename,url);
		//$(this).parent("table").remove();
		if(url == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//$("table").remove();
				$("#dispMedTable").html("").prepend(response);
				//$("#employee-grid").show();
				
			});
		}
	});
	
	//Refer to diagnostic center
	$("body").on("change", "#selectDignosticCenter", function() {
		var patientid = $(this).attr("data-patient-id");
		var episodeid = $(this).attr("data-episode-id");
		var diagnoid = $(this).val();
		 swal({
                        title: "Referring this case to a diagnostic ?",
                        text: "They will only be able to see patient name and tests ordered and not other details. ",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes, refer it!",
                        cancelButtonText: "No, cancel!",
                        closeOnConfirm: false,
                        closeOnCancel: false },
                    function (isConfirm) {
                        if (isConfirm) {
							var url = hostUrl+"refer_diagnosis.php?diagnoid="+diagnoid+"&patientid="+patientid+"&episodeid="+episodeid;
							$.get(url, function(response){
							console.log(response);	
                            swal("Referred Successfully!", "", "success");
							});
                        } else {
                            swal("Cancelled", "", "error");
                        }
                });
		
		
	});
	
	//Refer to pharma
	$("body").on("change", "#selectPharma", function() {
		var patientid = $(this).attr("data-patient-id");
		var episodeid = $(this).attr("data-episode-id");
		var pharmaid = $(this).val();
		 swal({
                        title: "Referring this case to a Pharmacy ?",
                        text: "They will only be able to see patient name and Prescription and not other details.",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes, refer it!",
                        cancelButtonText: "No, cancel!",
                        closeOnConfirm: false,
                        closeOnCancel: false },
                    function (isConfirm) {
                        if (isConfirm) {
							var url = hostUrl+"refer_diagnosis.php?pharmaid="+pharmaid+"&patientid="+patientid+"&episodeid="+episodeid;
							$.get(url, function(response){
							console.log(response);	
                            swal("Referred Successfully!", "", "success");
							});
                        } else {
                            swal("Cancelled", "", "error");
                        }
                });
		
		
	});
	

	
	//Get Load Template Scripts
		$(".load-template").on("click", function() {
		var templateid = $(this).attr("data-template-id");
		
		var url = hostUrl+"add_medicine.php?loadtemplate="+templateid;
		//console.log(prevepisode, url);
		
		if(templateid == "") {
			return false;
		} else{
			$.get(url, function(response){
				//console.log(response);
				$("#dispMedTable").html("").prepend(response);
			
				$("#prescription-template1").hide();$("#prescription-template2").hide();
			});
		}
		

	});
	
	//Get Previous prescription medicine
		$(".prev_prescription").on("click", function() {
		var prevepisode = $(this).attr("data-prev-episode-id");
		
		var url = hostUrl+"add_medicine.php?prevprescid="+prevepisode;
		//console.log(prevepisode, url);
		
		if(prevepisode == "") {
			return false;
		} else{
			$.get(url, function(response){
				//console.log(response);
				$("#dispMedTable").html("").prepend(response);
			
				$("#prescription-template1").hide();$("#prescription-template2").hide();
			});
		}
		

	});
	

	$("body").on("click", "#editTempPrescription", function() {
		var patientid = $(this).attr("data-patient-id");		
		var url = hostUrl+"add_medicine.php?gettemplateval=1&gettempEdit=1&patientid="+patientid;
		//console.log(patientid, sympid, url);
		$("#beforePrescTempEdit").hide();
		$("#editTempPrescription").hide();
		$("#cancelTempPrescription").show();
		if(patientid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				$("#afterPrescTempEdit").html("").prepend(response);
			});
		}
	});
	$("body").on("click", "#cancelTempPrescription", function() {
		var patientid = $(this).attr("data-patient-id");		
		var url = hostUrl+"add_medicine.php?gettemplateval=1&gettempEdit=0&patientid="+patientid;
		//console.log(patientid, sympid, url);
		$("#beforePrescTempEdit").hide();
		$("#editTempPrescription").show();
		$("#cancelTempPrescription").hide();
		if(patientid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				$("#afterPrescTempEdit").html("").prepend(response);
			});
		}
	});
	
	$("body").on("click", ".del_prescription_template", function() {
		var presctempid = $(this).attr("data-template-id");
		var url = hostUrl+"add_medicine.php?delpresctemp="+presctempid;
		console.log(presctempid, url);
		$(this).parent("span").remove();
		if(presctempid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
		

	});
	
	
	$("body").on("blur", ".exam_res", function() {
		
		var examres = $(this).val();	
		var examid = $(this).attr("data-examination-id");
		var url = hostUrl+"get_examination.php?updateexamid="+examid+"&examres="+examres;
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
		var url = hostUrl+"get_examination.php?updateexamid="+examid+"&examfindings="+examfindings;
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
	
	
	
	$("body").on("blur", ".right_eye", function() {
		
		var righteye = $(this).val();	
		var investid = $(this).attr("data-investigation-id");
		var url = hostUrl+"get_diagno_test_report.php?updateinvestid="+investid+"&righteye="+righteye;
		//console.log(examres,examid,url);
		//$(this).parent("span").remove();
		if(investid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	$("body").on("blur", ".left_eye", function() {
		
		var lefteye = $(this).val();	
		var investid = $(this).attr("data-investigation-id");
		var url = hostUrl+"get_diagno_test_report.php?updateinvestid="+investid+"&lefteye="+lefteye;
		console.log(investid,lefteye,url);
		//$(this).parent("span").remove();
		if(investid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	
	//Drug Abuse Scripts
	$("#get_drug_abuse").on("blur", function() {
		var patientid = $(this).attr("data-patient-id");
		var drugid = $(this).val();
		var url = hostUrl+"get_drug_abuse_res.php?drugid="+drugid+"&patientid="+patientid;
		//console.log(patientid, drugid, url);
		$("#drugAbuseBefore").hide();
		if(drugid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				//$("#sympBefore .bootstrap-tagsinput span").remove();
				//$("#sympBefore .bootstrap-tagsinput").prepend(response);
				$("#drugAbuseAfter").html("").prepend(response);
				 setTimeout(function() {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    showMethod: 'slideDown',
                    timeOut: 1500
                };
                toastr.success('', 'Drug Abuse updated Successfully');

            }, 100);
			});
		}
		
        $(':text').val(''); //Clear search field
		$("#get_drug_abuse").focus();	//focus search filed 

	});
	
	$("#get_drug_abuse").keydown(function(e) {
		if(e.originalEvent.keyCode == 13){
		var patientid = $(this).attr("data-patient-id");
		var drugid = $(this).val();
		var url = hostUrl+"get_drug_abuse_res.php?drugid="+drugid+"&patientid="+patientid;
		//console.log(patientid, drugid, url);
		$("#drugAbuseBefore").hide();
		if(drugid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				//$("#sympBefore .bootstrap-tagsinput span").remove();
				//$("#sympBefore .bootstrap-tagsinput").prepend(response);
				$("#drugAbuseAfter").html("").prepend(response);
				setTimeout(function() {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    showMethod: 'slideDown',
                    timeOut: 1500
                };
                toastr.success('', 'Drug Abuse updated Successfully');

            }, 100);
				
			});
		}
		
			$(':text').val(''); //Clear search field
			$("#get_drug_abuse").focus();	//focus search filed 
			return false;
		}
		//console.log(e);
	}); 
	$(".get_drug_prior").on("click", function() {
		var patientid = $(this).attr("data-patient-id");
		var drugid = $(this).attr("data-drug-abuse-id");
		var url = hostUrl+"get_drug_abuse_res.php?drugid="+drugid+"&patientid="+patientid;
		//console.log(patientid, drugid, url);
		$("#drugAbuseBefore").hide();
		if(drugid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				//$("#sympBefore .bootstrap-tagsinput span").remove();
				//$("#sympBefore .bootstrap-tagsinput").prepend(response);
				$("#drugAbuseAfter").html("").prepend(response);
				setTimeout(function() {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    showMethod: 'slideDown',
                    timeOut: 1500
                };
                toastr.success('', 'Drug Abuse updated Successfully');

            }, 100);
			});
		}
		

	});
	$("body").on("click", ".del_drugs", function() {
		var drugid = $(this).attr("data-drug-abuse-id");
		var url = hostUrl+"get_drug_abuse_res.php?deldrugid="+drugid;
		//console.log(sympid, url);
		$(this).parent("span").remove();
		if(drugid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				setTimeout(function() {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    showMethod: 'slideDown',
                    timeOut: 1500
                };
                toastr.success('', 'Drug Abuse updated Successfully');

            }, 100);
				
				
			});
		}
	});
	

	//Family History Scripts
	$("#get_history_abuse").on("blur", function() {
		var patientid = $(this).attr("data-patient-id");
		var historyid = $(this).val();
		var url = hostUrl+"get_family_history_res.php?historyid="+historyid+"&patientid="+patientid;
		//console.log(patientid, historyid, url);
		$("#familyHistoryBefore").hide();
		if(historyid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				//$("#sympBefore .bootstrap-tagsinput span").remove();
				//$("#sympBefore .bootstrap-tagsinput").prepend(response);
				$("#familyHistoryAfter").html("").prepend(response);
				 setTimeout(function() {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    showMethod: 'slideDown',
                    timeOut: 1500
                };
                toastr.success('', 'Family History updated Successfully');

            }, 100);
			});
		}
		
        $(':text').val(''); //Clear search field
		$("#get_history_abuse").focus();	//focus search filed 

	});
	
	$("#get_history_abuse").keydown(function(e) {
		if(e.originalEvent.keyCode == 13){
		var patientid = $(this).attr("data-patient-id");
		var historyid = $(this).val();
		var url = hostUrl+"get_family_history_res.php?historyid="+historyid+"&patientid="+patientid;
		//console.log(patientid, drugid, url);
		$("#familyHistoryBefore").hide();
		if(historyid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				//$("#sympBefore .bootstrap-tagsinput span").remove();
				//$("#sympBefore .bootstrap-tagsinput").prepend(response);
				$("#familyHistoryAfter").html("").prepend(response);
				 setTimeout(function() {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    showMethod: 'slideDown',
                    timeOut: 1500
                };
                toastr.success('', 'Family History updated Successfully');

            }, 100);
			});
		}
		
			$(':text').val(''); //Clear search field
			$("#get_history_abuse").focus();	//focus search filed 
			return false;
		}
		//console.log(e);
	}); 
	$(".get_histoy_prior").on("click", function() {
		var patientid = $(this).attr("data-patient-id");
		var historyid = $(this).attr("data-history-id");
		var url = hostUrl+"get_family_history_res.php?historyid="+historyid+"&patientid="+patientid;
		//console.log(patientid, historyid, url);
		$("#familyHistoryBefore").hide();
		if(historyid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				//$("#sympBefore .bootstrap-tagsinput span").remove();
				//$("#sympBefore .bootstrap-tagsinput").prepend(response);
				
				$("#familyHistoryAfter").html("").prepend(response);
				 setTimeout(function() {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    showMethod: 'slideDown',
                    timeOut: 1500
                };
                toastr.success('', 'Family History updated Successfully');

            }, 100);
			});
		}
		

	});
	$("body").on("click", ".del_history", function() {
		var historyid = $(this).attr("data-history-id");
		var url = hostUrl+"get_family_history_res.php?delhistoryid="+historyid;
		//console.log(sympid, url);
		$(this).parent("span").remove();
		if(historyid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				 setTimeout(function() {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    showMethod: 'slideDown',
                    timeOut: 1500
                };
                toastr.success('', 'Family History updated Successfully');

            }, 100);
				
				
			});
		}
	});	
		
	////////////////////////////////Start chief medical complaints scripts/////////////////////////////////////////////////
	$("#get_complaints").on("blur", function() {
		var patientid = $(this).attr("data-patient-id");
		var sympid = $(this).val();
		var episodeid = $(this).attr("data-episode-id");
		
		if(sympid!= "" && episodeid!= "0"){
		var url = hostUrl+"get_symptoms.php?editsympid="+sympid+"&episodeid="+episodeid;
		}
		else{
		var url = hostUrl+"get_symptoms.php?sympid="+sympid+"&patientid="+patientid;
		}
		//console.log(patientid, sympid, url);
		
		if(sympid == "" && episodeid == "") {
			return false;
		} else if(sympid!= "" && episodeid == "0") {
			$.get(url, function(response) {
				//console.log(response);
				//$("#sympBefore .bootstrap-tagsinput span").remove();
				//$("#sympBefore .bootstrap-tagsinput").prepend(response);
				$("#sympBefore").html("").prepend(response);
				
			});
		}
		else if(sympid!= "" && episodeid!= "0") {
			$("#beforeSymptom").remove();
			$.get(url, function(response) {
				//console.log(response);
				$("#editSymptomResult").html("").prepend(response);
			});
		}
		
        $('.searchSymptoms').val(''); //Clear search field
		//$("#get_complaints").focus();	//focus search filed 

	});
	
	$("#get_complaints").keydown(function(e) {
		if(e.originalEvent.keyCode == 13){
		var patientid = $(this).attr("data-patient-id");
		var sympid = $(this).val();
		var episodeid = $(this).attr("data-episode-id");
		
		if(sympid!= "" && episodeid!= "0"){
		var url = hostUrl+"get_symptoms.php?editsympid="+sympid+"&episodeid="+episodeid;
		}
		else{
		var url = hostUrl+"get_symptoms.php?sympid="+sympid+"&patientid="+patientid;
		}
		//console.log(patientid, sympid, url);
		
		if(sympid == "" && episodeid == "") {
			return false;
		} else if(sympid!= "" && episodeid == "0") {
			$.get(url, function(response) {
				//console.log(response);
				//$("#sympBefore .bootstrap-tagsinput span").remove();
				//$("#sympBefore .bootstrap-tagsinput").prepend(response);
				$("#sympBefore").html("").prepend(response);
				
			});
		}
		else if(sympid!= "" && episodeid!= "0") {
			$("#beforeSymptom").remove();
			$.get(url, function(response) {
				//console.log(response);
				$("#editSymptomResult").html("").prepend(response);
			});
		}
		
			$('.searchSymptoms').val(''); //Clear search field
			//$("#get_complaints").focus();	//focus search filed 
			return false;
		}
		//console.log(e);
	}); 
	
	
	
	$(".get_complaints_prior").on("click", function() {
		var patientid = $(this).attr("data-patient-id");
		var sympid = $(this).attr("data-symptom-id");
		var url = hostUrl+"get_symptoms.php?sympid="+sympid+"&patientid="+patientid;
		//console.log(patientid, sympid, url);
		
		if(sympid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				//$("#sympBefore .bootstrap-tagsinput span").remove();
				//$("#sympBefore .bootstrap-tagsinput").prepend(response);
				$("#sympBefore").html("").prepend(response);
			});
		}
		

	});
	
	$(".get_edit_complaints_prior").on("click", function() {
		var episodeid = $(this).attr("data-episode-id");
		var sympid = $(this).attr("data-symptom-id");
		var url = hostUrl+"get_symptoms.php?editsympid="+sympid+"&episodeid="+episodeid;
		//console.log(patientid, sympid, url);
		$("#beforeSymptom").remove();
		if(sympid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				//$("#sympBefore .bootstrap-tagsinput span").remove();
				//$("#sympBefore .bootstrap-tagsinput").prepend(response);
				$("#editSymptomResult").html("").prepend(response);
			});
		}
		

	});
	
	
	
	//When press remove button in chief medical it will triggers following function
	$("body").on("click", ".del_complaints", function() {
		var sympid = $(this).attr("data-symptom-id");
		var url = hostUrl+"get_symptoms.php?delsympid="+sympid;
		//console.log(sympid, url);
		$(this).parent("span").remove();
		if(sympid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
		

	});
	$("body").on("click", ".del_edit_complaints", function() {
		var sympid = $(this).attr("data-symptom-id");
		var url = hostUrl+"get_symptoms.php?delsympid="+sympid;
		//console.log(sympid, url);
		//$("#beforeSymptom").remove();
		$(this).parent("span").remove();
		if(sympid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
		

	});
	
	////////////////////////////////End chief medical complaints scripts/////////////////////////////////////////////////
	
	////////////////////////////////Start Examination scripts/////////////////////////////////////////////////
 
	//Get Examination Load Template Scripts
		$(".exam_load_template").on("click", function() {
		var examtemplateid = $(this).attr("data-exam-template-id");
		var patientid = $(this).attr("data-patient-id");
		var editstatus = $(this).attr("data-edit-status");
		var episodeid = $(this).attr("data-episode-id");

		if(editstatus == 0){
		var url = hostUrl+"get_examination.php?loadtemplate="+examtemplateid+"&patientid="+patientid;
		console.log(examtemplateid,patientid,editstatus);
		$("#dispTempExamination").hide();
		} else if(editstatus == 1){
		var url = hostUrl+"get_examination.php?editloadtemplate="+examtemplateid+"&patientid="+patientid+"&episodeid="+episodeid+"&editstatus="+editstatus;
		$("#beforeExaminationResult").hide();
		}
		console.log(examtemplateid,patientid,url);
		
		
		
		if(examtemplateid == "") {
			return false;
		} else{
			$.get(url, function(response){
				//console.log(response);
				if(editstatus == 0){
					$("#dispExamination").html("").prepend(response);			
					$("#examp_temp_section").show();
				} else if(editstatus == 1){
					$("#editExaminationResult").html("").prepend(response);			
					$("#examp_temp_section").show();
				}
				
			});
		}
		

	});
	
	$("#get_examination_res").on("blur", function(){
		var patientid = $(this).attr("data-patient-id");
		var examinationid = $(this).val();
		var episodeid = $(this).attr("data-episode-id");
		$("#dispTempExamination").hide();
		
		if(examinationid!= "" && episodeid!= "0"){
		var url = hostUrl+"get_examination.php?editexaminationid="+examinationid+"&episodeid="+episodeid;
		console.log(examinationid,episodeid,url);
		}
		else{
		var url = hostUrl+"get_examination.php?examinationid="+examinationid+"&patientid="+patientid;
		console.log(examinationid,patientid,url);
		}
			
		
		if(examinationid == "" && episodeid == "") {
			return false;
		} else if(examinationid!= "" && episodeid == "0") {
			$.get(url, function(response){
				//console.log(response);
				$("#dispExamination").html("").prepend(response);
				$("#examp_temp_section").show();
				
			});
		} else if(examinationid!= "" && episodeid!= "0") {
			$("#beforeExaminationResult").remove();
			$.get(url, function(response) {
				//console.log(response);
				$("#editExaminationResult").html("").prepend(response);
				$("#examp_temp_section").show();
			});
		}
			
			$('.searchExamination').val(''); //Clear search field
			//$("#get_examination_res").focus();	//focus search filed 
	});
	
	$("#get_examination_res").keydown(function(e) {
		if(e.originalEvent.keyCode == 13){ //If User press enter key
		var patientid = $(this).attr("data-patient-id");
		var examinationid = $(this).val();
		var episodeid = $(this).attr("data-episode-id");
		
		$("#dispTempExamination").hide();
		
		if(examinationid!= "" && episodeid!= "0"){
		var url = hostUrl+"get_examination.php?editexaminationid="+examinationid+"&episodeid="+episodeid;
		}
		else{
		var url = hostUrl+"get_examination.php?examinationid="+examinationid+"&patientid="+patientid;
		}
	
		if(examinationid == "" && episodeid == "") {
			return false;
		} else if(examinationid!= "" && episodeid == "0") {
			$.get(url, function(response){
				//console.log(response);
				$("#dispExamination").html("").prepend(response);
				$("#examp_temp_section").show();
				
			});
		} else if(examinationid!= "" && episodeid!= "0") {
			$("#beforeExaminationResult").remove();
			$.get(url, function(response) {
				//console.log(response);
				$("#editExaminationResult").html("").prepend(response);
				$("#examp_temp_section").show();
			});
		}
		
			$('.searchExamination').val(''); //Clear search field
			//$("#get_examination_res").focus();	//focus search filed 
			
			return false;
		}
		//console.log(e);
	}); 
	
	
	$(".get_examination_res_prior").on("click", function() {
		var patientid = $(this).attr("data-patient-id");
		var examinationid = $(this).attr("data-examination_id");		
		var url = hostUrl+"get_examination.php?examinationid="+examinationid+"&patientid="+patientid;
		//console.log(patientid, investid, url);
		$("#dispTempExamination").hide();
		
		if(examinationid == "") {
			return false;
		} else {
			$.get(url, function(response){
				//console.log(response);
				$("#dispExamination").html("").prepend(response);
				$("#examp_temp_section").show();
				
			});
		}
		

	});
	
	$("body").on("click", ".get_edit_examination_res_prior", function() {
		var episodeid = $(this).attr("data-episode-id");
		var examinationid = $(this).attr("data-examination_id");		
		var url = hostUrl+"get_examination.php?editexaminationid="+examinationid+"&episodeid="+episodeid;
		//console.log(patientid, sympid, url);
		$("#beforeExaminationResult").remove();
		if(examinationid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				$("#editExaminationResult").html("").prepend(response);
				$("#examp_temp_section").show();
			});
		}
		

	});
	
	$("body").on("click", ".del_examination", function() {
		var deleaxaminationid = $(this).attr("data-examination-id");
		var url = hostUrl+"get_examination.php?deleaxaminationid="+deleaxaminationid;
		//console.log(sympid, url);
		//$("#beforeSymptom").remove();
		$("#del_examination_row"+deleaxaminationid).remove();
		if(deleaxaminationid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});	
	
	$("body").on("click", ".del_editexamination", function() {
		var deleaxaminationid = $(this).attr("data-examination-id");
		var url = hostUrl+"get_examination.php?deleditexaminationid="+deleaxaminationid;
		//console.log(deleaxaminationid, url);
		//$("#beforeSymptom").remove();
		$("#del_editexamination_row"+deleaxaminationid).remove();
		if(deleaxaminationid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
			
				
			});
		}
	});
	
	$("body").on("click",".delete_all_examination", function() {
		var patientid = $(this).attr("data-patient-id");
		var docid = $(this).attr("data-doctor-id");		
		var url = hostUrl+"get_examination.php?delallExam=1";
		//console.log(patientid, docid, url);
		
		if(docid == "") {
			return false;
		} else {
			$.get(url, function(response){
				//console.log(response);
				$("#dispExamination").html("").prepend(response);
			});
		}
		

	});
	
	$("body").on("click",".delete_all_edit_examination", function() {
		var episodeid = $(this).attr("data-episode-id");
		var url = hostUrl+"get_examination.php?delallEditExam="+episodeid;
		//console.log(patientid, docid, url);
		//$("#beforeExaminationResult").remove();
		//$("#editExaminationResult").remove();
		if(episodeid == "") {
			return false;
		} else {
			$.get(url, function(response){
				//console.log(response);
				
			});
		}
		

	});
	
	$("body").on("click", "#editTempExam", function() {
		var patientid = $(this).attr("data-patient-id");		
		var url = hostUrl+"get_examination.php?gettemplateval=1&gettempEdit=1&patientid="+patientid;
		//console.log(patientid, sympid, url);
		$("#beforeTempEdit").hide();
		$("#editTempExam").hide();
		$("#cancelTempExam").show();
		if(patientid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				$("#afterTempEdit").html("").prepend(response);
			});
		}
	});
	$("body").on("click", "#cancelTempExam", function() {
		var patientid = $(this).attr("data-patient-id");		
		var url = hostUrl+"get_examination.php?gettemplateval=1&gettempEdit=0&patientid="+patientid;
		//console.log(patientid, sympid, url);
		$("#beforeTempEdit").hide();
		$("#editTempExam").show();
		$("#cancelTempExam").hide();
		if(patientid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				$("#afterTempEdit").html("").prepend(response);
			});
		}
	});
	
	$("body").on("click", ".del_exam_template", function() {
		var examtempid = $(this).attr("data-exam-template-id");
		var url = hostUrl+"get_examination.php?delexamtemp="+examtempid;
		//console.log(treatid, url);
		$(this).parent("span").remove();
		if(examtempid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
		

	});
	////////////////////////////////End Examination scripts/////////////////////////////////////////////////
	
	////////////////////////////////Delete Patient Attachments scripts//////////////////////////////////////////////////////////////////////////////////////////////////
	$("body").on("click",".delAttachments", function() {
		var reportid = $(this).attr("data-report-id");
		var reportfolder = $(this).attr("data-report-folder");
		var url = hostUrl+"my_patient_profile_save.php?reportid="+reportid+"&reportfolder="+reportfolder;
		console.log(reportfolder, reportid, url);
		//$("#beforeExaminationResult").remove();
		//$("#editExaminationResult").remove();
		if(reportid == "") {
			return false;
		} else {
			$.get(url, function(response){
				//console.log(response);
				
			});
		}
		

	});
	////////////////////////////////End Delete Patient Attachments scripts/////////////////////////////////////////////////
	
	////////////////////////////////Start Investigations scripts/////////////////////////////////////////////////
	
	$("#add_diagnosis_test").on("blur", function(){
		var patientid = $(this).attr("data-patient-id");
		var addinvestid = $(this).val();
		var episodeid = $(this).attr("data-episode-id");
		var docid = $(this).attr("data-doc-id");
		var url = hostUrl+"get_diagno_test_report.php?addinvestid="+addinvestid+"&patientid="+patientid+"&episodeid="+episodeid+"&docid="+docid;
		//console.log(url,investid,patientid);
		
		if(addinvestid == ""){
			return false;
		}
		else{
			$.get(url, function(response){
				//console.log(response);
				$("#dispDignoTest").html("").prepend(response);
				
			});
		}
		$("#dispOld").hide();
		$('.searchDiagnosTest').val(''); //Clear search field
			$("#add_diagnosis_test").focus();	//focus search filed 
	});
 
	$("#get_diagnosis_test").on("blur", function(){
		var patientid = $(this).attr("data-patient-id");
		var investid = $(this).val();
		var episodeid = $(this).attr("data-episode-id");
		$("#dispTempInvestigation").hide();
		if(investid!= "" && episodeid!= "0"){
		var url = hostUrl+"get_diagno_test_report.php?editinvestid="+investid+"&episodeid="+episodeid;
		}
		else{
		var url = hostUrl+"get_diagno_test_report.php?investid="+investid+"&patientid="+patientid;
		}
	
		if(investid == "" && episodeid == "") {
			return false;
		} else if(investid!= "" && episodeid == "0") {
			$.get(url, function(response){
				//console.log(response);
				$("#dispDignoTest").html("").prepend(response);
				$("#invest_temp_section").show();
			});
		} else if(investid!= "" && episodeid!= "0") {
			$("#beforeEditInvest").remove();
			$.get(url, function(response) {
				//console.log(response);
				$("#dispEditInvest").html("").prepend(response);
				$("#invest_temp_section").show();
			});
		}
		
			
		$('.searchDiagnosTest').val(''); //Clear search field
			//$("#get_diagnosis_test").focus();	//focus search filed 
	});
	
	$("#get_diagnosis_test").keydown(function(e) {
		if(e.originalEvent.keyCode == 13){ //If User press enter key
		var patientid = $(this).attr("data-patient-id");
		var investid = $(this).val();
		var episodeid = $(this).attr("data-episode-id");
		$("#dispTempInvestigation").hide();
		
		if(investid!= "" && episodeid!= "0"){
		var url = hostUrl+"get_diagno_test_report.php?editinvestid="+investid+"&episodeid="+episodeid;
		}
		else{
		var url = hostUrl+"get_diagno_test_report.php?investid="+investid+"&patientid="+patientid;
		}
	
		if(investid == "" && episodeid == "") {
			return false;
		} else if(investid!= "" && episodeid == "0") {
			$.get(url, function(response){
				//console.log(response);
				$("#dispDignoTest").html("").prepend(response);
				$("#invest_temp_section").show();
			});
		} else if(investid!= "" && episodeid!= "0") {
			$("#beforeEditInvest").remove();
			$.get(url, function(response) {
				//console.log(response);
				$("#dispEditInvest").html("").prepend(response);
				$("#invest_temp_section").show();
			});
		}
		
			$('.searchDiagnosTest').val(''); //Clear search field
			//$("#get_diagnosis_test").focus();	//focus search filed 
			
			return false;
		}
		//console.log(e);
	}); 
	
	$(".get_edit_diagnosis_test_prior").on("click", function() {
		var episodeid = $(this).attr("data-episode-id");
		var investid = $(this).attr("data-main-test-id");		
		var url = hostUrl+"get_diagno_test_report.php?editinvestid="+investid+"&episodeid="+episodeid;
		//console.log(patientid, sympid, url);
		$("#beforeEditInvest").remove();
		$("#dispTempInvestigation").hide();
		
		if(investid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				$("#dispEditInvest").html("").prepend(response);
			});
		}
		

	});
	
	//Get Investigation Load Template Scripts
		$(".invest_load_template").on("click", function() {
		var investtemplateid = $(this).attr("data-invest-template-id");
		var patientid = $(this).attr("data-patient-id");
		var editstatus = $(this).attr("data-edit-status");
		var episodeid = $(this).attr("data-episode-id");
		if(editstatus == 0){
		var url = hostUrl+"get_diagno_test_report.php?loadtemplate="+investtemplateid+"&patientid="+patientid;
		$("#dispTempInvestigation").hide();
		}
		else if(editstatus == 1){
		var url = hostUrl+"get_diagno_test_report.php?editloadtemplate="+investtemplateid+"&patientid="+patientid+"&episodeid="+episodeid+"&editstatus="+editstatus;
		$("#beforeEditInvest").hide();
		}
		console.log(investtemplateid,patientid,url);
		
		if(investtemplateid == "") {
			return false;
		} else{
			$.get(url, function(response){
				//console.log(response);
				
				if(editstatus == 0){
				$("#dispDignoTest").html("").prepend(response);
				$("#invest_temp_section").show();
				} else if(editstatus == 1){
				$("#dispEditInvest").html("").prepend(response);
				$("#invest_temp_section").show();	
				}
			});
		}
		

	});
	
	$(".get_diagnosis_test_prior").on("click", function() {
		var patientid = $(this).attr("data-patient-id");
		var investid = $(this).attr("data-main-test-id");		
		var url = hostUrl+"get_diagno_test_report.php?investid="+investid+"&patientid="+patientid;
		//console.log(patientid, investid, url);
		$("#dispTempInvestigation").hide();
		if(investid == "") {
			return false;
		} else {
			$.get(url, function(response){
				//console.log(response);
				$("#dispDignoTest").html("").prepend(response);
				$("#invest_temp_section").show();
			});
		}
		

	});
	
	$("body").on("click", ".del_Invest", function() {
		var delinvestid = $(this).attr("data-invest-id");
		var url = hostUrl+"get_diagno_test_report.php?delinvestid="+delinvestid;
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
	$("body").on("blur", ".addTestActualVal", function() {
		var editinvestid = $(this).attr("data-invest-id");
		var dateadded = $(this).attr("data-date-added");
		var editinvestval = $(this).val();
		var url = hostUrl+"get_diagno_test_report.php?editinvestactual="+editinvestid+"&editinvestval="+editinvestval+"&dateadded="+dateadded;
		console.log(editinvestid,editinvestval,url);
	
		if(editinvestid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
									
			});
			
		}
	});
	
	$("body").on("blur", ".editTestActualVal", function() {
		var editinvestid = $(this).attr("data-invest-id");
		var dateadded = $(this).attr("data-date-added");
		var editinvestval = $(this).val();
		var url = hostUrl+"get_diagno_test_report.php?editinvestactual="+editinvestid+"&editinvestval="+editinvestval+"&dateadded="+dateadded;
		console.log(editinvestid,editinvestval,url);
	
		if(editinvestid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
					 setTimeout(function() {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    showMethod: 'slideDown',
                    timeOut: 1500
                };
                toastr.success('', 'Investigations Updated Successfully');

            }, 100);
				
			});
			
		}
	});
	
	$("body").on("click", ".del_editInvest", function() {
		var delinvestid = $(this).attr("data-invest-id");
		var url = hostUrl+"get_diagno_test_report.php?deleditinvestid="+delinvestid;
		//console.log(deleaxaminationid, url);
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
	
	$("body").on("click", "#editTempInvestigation", function() {
		var patientid = $(this).attr("data-patient-id");		
		var url = hostUrl+"get_diagno_test_report.php?gettemplateval=1&gettempEdit=1&patientid="+patientid;
		//console.log(patientid, sympid, url);
		$("#beforeInvestTempEdit").hide();
		$("#editTempInvestigation").hide();
		$("#cancelTempInvestigation").show();
		if(patientid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				$("#afterInvestTempEdit").html("").prepend(response);
			});
		}
	});
	$("body").on("click", "#cancelTempInvestigation", function() {
		var patientid = $(this).attr("data-patient-id");		
		var url = hostUrl+"get_diagno_test_report.php?gettemplateval=1&gettempEdit=0&patientid="+patientid;
		//console.log(patientid, sympid, url);
		$("#beforeInvestTempEdit").hide();
		$("#editTempInvestigation").show();
		$("#cancelTempInvestigation").hide();
		if(patientid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				$("#afterInvestTempEdit").html("").prepend(response);
			});
		}
	});
	
	$("body").on("click", ".del_invest_template", function() {
		var investtempid = $(this).attr("data-invest-template-id");
		var url = hostUrl+"get_diagno_test_report.php?delinvesttemp="+investtempid;
		//console.log(treatid, url);
		$(this).parent("span").remove();
		if(investtempid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
		

	});
	////////////////////////////////End Investigations scripts/////////////////////////////////////////////////
	
	////////////////////////////////Start Diagnosis scripts/////////////////////////////////////////////////
	$(".get_edit_diagnosis_prior").on("click", function() {
		var episodeid = $(this).attr("data-episode-id");
		var icdid = $(this).attr("data-icd-id");
		var url = hostUrl+"get_diagno_test_report.php?editicdid="+icdid+"&episodeid="+episodeid;
		//console.log(patientid, icdid, url);
		$("#beforeICDTest").remove();
		if(icdid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				$("#editICDTest").html("").prepend(response);
			});
		}
		

	});
	
	$(".get_diagnosis_prior").on("click", function() {
		var patientid = $(this).attr("data-patient-id");
		var icdid = $(this).attr("data-icd-id");
		var url = hostUrl+"get_diagno_test_report.php?icdid="+icdid+"&patientid="+patientid;
		//console.log(patientid, icdid, url);
		
		if(icdid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				$("#dispICDTest").html("").prepend(response);
			});
		}
		

	});
	
	//When press remove button in icd list it will triggers following function
	$("body").on("click", ".del_diagnosis", function() {
		var diagnosisid = $(this).attr("data-diagnosis-id");
		var url = hostUrl+"get_diagno_test_report.php?delicdid="+diagnosisid;
		//console.log(sympid, url);
		$(this).parent("span").remove();
		if(diagnosisid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
		

	});
	//When press remove button in icd list it will triggers following function
	$("body").on("click", ".del_editdiagnosis", function() {
		var diagnosisid = $(this).attr("data-diagnosis-id");
		var url = hostUrl+"get_diagno_test_report.php?delicdid="+diagnosisid;
		//console.log(sympid, url);
		$(this).parent("span").remove();
		if(diagnosisid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
		

	});
	$("#get_diagnosis").on("blur", function(){
		var patientid = $(this).attr("data-patient-id");
		var diagnoid = $(this).val();
		var episodeid = $(this).attr("data-episode-id");
		
		if(diagnoid!= "" && episodeid!= "0")
		{
		var url = hostUrl+"get_diagno_test_report.php?editicdid="+diagnoid+"&episodeid="+episodeid;
		console.log(url,diagnoid,episodeid);
		}
		else
		{
		var url = hostUrl+"get_diagno_test_report.php?icdid="+diagnoid+"&patientid="+patientid;
		console.log(url,diagnoid,patientid);
		}
		
		
		if(diagnoid == "" && episodeid == ""){
			return false;
		}
		else if(diagnoid!= "" && episodeid == "0"){
			$.get(url, function(response){
				//console.log(response);
				$("#dispICDTest").html("").prepend(response);
			});
		}
		else if(diagnoid!= "" && episodeid!= "0"){
			$("#beforeICDTest").remove();
			$.get(url, function(response){
				//console.log(response);
				$("#editICDTest").html("").prepend(response);
			});
		}
			
		$('.searchDiagnosis').val(''); //Clear search field
		//$("#get_diagnosis").focus();	//focus search filed
	});
	
	$("#get_diagnosis").keydown(function(e) {
		if(e.originalEvent.keyCode == 13){ //If User press enter key
		var patientid = $(this).attr("data-patient-id");
		var diagnoid = $(this).val();
		var episodeid = $(this).attr("data-episode-id");
		
		if(diagnoid!= "" && episodeid!= "0")
		{
		var url = hostUrl+"get_diagno_test_report.php?editicdid="+diagnoid+"&episodeid="+episodeid;
		}
		else
		{
		var url = hostUrl+"get_diagno_test_report.php?icdid="+diagnoid+"&patientid="+patientid;
		}
		//console.log(url,diagnoid,patientid);
		
		if(diagnoid == "" && episodeid == ""){
			return false;
		}
		else if(diagnoid!= "" && episodeid == "0"){
			$.get(url, function(response){
				//console.log(response);
				$("#dispICDTest").html("").prepend(response);
			});
		}
		else if(diagnoid!= "" && episodeid!= "0"){
			$("#beforeICDTest").remove();
			$.get(url, function(response){
				//console.log(response);
				$("#editICDTest").html("").prepend(response);
			});
		}
		
			$('.searchDiagnosis').val(''); //Clear search field
			//$("#get_diagnosis").focus();	//focus search filed 
			return false;
		}
		//console.log(e);
	}); 
	
	$("body").on("blur", "#diagnosis_details", function() {
		var episodeid = $(this).attr("data-episode-id");
		var diagnodetail = $(this).val();	
			
		var url = hostUrl+"my_patient_profile_save.php?episodeid="+episodeid+"&diagnodetail="+diagnodetail;
		
		//console.log(diagnodetail,episodeid,url);
		//$(this).parent("span").remove();
		if(diagnodetail == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	
	$("body").on("blur", "#suffering_since", function() {
		var episodeid = $(this).attr("data-episode-id");
		var suffersincedetail = $(this).val();	
			
		var url = hostUrl+"my_patient_profile_save.php?episodeid="+episodeid+"&suffersincedetail="+suffersincedetail;
		
		//console.log(diagnodetail,episodeid,url);
		//$(this).parent("span").remove();
		if(suffersincedetail == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	////////////////////////////////End Diagnosis scripts/////////////////////////////////////////////////

	////////////////////////////////Start Treatment Advise scripts/////////////////////////////////////////////////	
	$("#get_treatment_res").on("blur", function() {
		var patientid = $(this).attr("data-patient-id");
		var treatid = $(this).val();
		var episodeid = $(this).attr("data-episode-id");
		
		if(treatid!= "" && episodeid!= "0")
		{
		var url = hostUrl+"get_treatment.php?edittreatid="+treatid+"&episodeid="+episodeid;
		}
		else
		{
		var url = hostUrl+"get_treatment.php?treatid="+treatid+"&patientid="+patientid;
		}
		
		//console.log(treatid,patientid);
		if(treatid == "" && episodeid == ""){
			return false;
		}
		else if(treatid!= "" && episodeid == "0"){
			$.get(url, function(response) {
				//console.log(response);
				$("#dispTreatment").html("").prepend(response);
				
			});
		}
		else if(treatid!= "" && episodeid!= "0"){
			$("#beforeeditTreatment").remove();
			$.get(url, function(response){
				//console.log(response);
				$("#editTreatment").html("").prepend(response);
			});
		}
		
		
        $('.searchTreatment').val(''); //Clear search field
		//$("#get_treatment_res").focus();	//focus search filed 
	
	});
	
	$("#get_treatment_res").keydown(function(e) {
		if(e.originalEvent.keyCode == 13){
			var patientid = $(this).attr("data-patient-id");
			var treatid = $(this).val();
			var episodeid = $(this).attr("data-episode-id");
		
		if(treatid!= "" && episodeid!= "0")
		{
		var url = hostUrl+"get_treatment.php?edittreatid="+treatid+"&episodeid="+episodeid;
		}
		else
		{
		var url = hostUrl+"get_treatment.php?treatid="+treatid+"&patientid="+patientid;
		}
		
		//console.log(treatid,patientid);
		if(treatid == "" && episodeid == ""){
			return false;
		}
		else if(treatid!= "" && episodeid == "0"){
			$.get(url, function(response) {
				//console.log(response);
				$("#dispTreatment").html("").prepend(response);
				
			});
		}
		else if(treatid!= "" && episodeid!= "0"){
			$("#beforeeditTreatment").remove();
			$.get(url, function(response){
				//console.log(response);
				$("#editTreatment").html("").prepend(response);
			});
		}
			
			$('.searchTreatment').val(''); //Clear search field
			//$("#get_treatment_res").focus();
			return false;
		}
		//console.log(e);
	});
	
	$(".get_treatment_prior").on("click", function() {
		var patientid = $(this).attr("data-patient-id");
		var treatid = $(this).attr("data-treatment-id");
		var url = hostUrl+"get_treatment.php?treatid="+treatid+"&patientid="+patientid;
		//console.log(patientid, sympid, url);
		
		if(treatid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				$("#dispTreatment").html("").prepend(response);
			});
		}
		

	});
	$(".edit_get_treatment_prior").on("click", function() {
		var episodeid = $(this).attr("data-episode-id");
		var treatid = $(this).attr("data-treatment-id");
		var url = hostUrl+"get_treatment.php?edittreatid="+treatid+"&episodeid="+episodeid;
		//console.log(patientid, sympid, url);
		$("#beforeeditTreatment").remove();
		if(treatid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				$("#editTreatment").html("").prepend(response);
			});
		}
		

	});
	
	$("body").on("click", ".del_treatment", function() {
		var treatid = $(this).attr("data-treatment-id");
		var url = hostUrl+"get_treatment.php?deltreament="+treatid;
		//console.log(treatid, url);
		$(this).parent("span").remove();
		if(treatid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
		

	});
	
	
	
	$("body").on("click", ".edit_del_treatment", function() {
		var treatid = $(this).attr("data-treatment-id");
		var url = hostUrl+"get_treatment.php?deltreament="+treatid;
		//console.log(treatid, url);
		$(this).parent("span").remove();
		if(treatid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
		

	});
	
	$("body").on("blur", "#treatment_details", function() {
		var episodeid = $(this).attr("data-episode-id");
		var treatmentdetail = $(this).val();	
			
		var url = hostUrl+"my_patient_profile_save.php?episodeid="+episodeid+"&treatmentdetail="+treatmentdetail;
		
		//console.log(treatmentdetail,episodeid,url);
		//$(this).parent("span").remove();
		if(treatmentdetail == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	
	$("body").on("blur", "#presc_note", function() {
		var episodeid = $(this).attr("data-episode-id");
		var prescnote = $(this).val();	
			
		var url = hostUrl+"my_patient_profile_save.php?episodeid="+episodeid+"&prescnote="+prescnote;
		
		console.log(prescnote,episodeid,url);
		//$(this).parent("span").remove();
		if(prescnote == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	
	////////////////////////////////End Treatment Advise scripts/////////////////////////////////////////////////
	
	
	////////////////////////////////Start Add Prescriptions scripts/////////////////////////////////////////////////	
	$("body").on("click", ".del_medicine", function() {
		var medicineid = $(this).attr("data-medicine-id");
		var url = hostUrl+"add_medicine.php?delprescid="+medicineid;
		//console.log(treatid, url);
		$("#medRow"+medicineid).remove();
		$("#medRow1"+medicineid).remove();
		if(medicineid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
		

	});
	
	$("body").on("click", ".edit_del_medicine", function() {
		var medicineid = $(this).attr("data-medicine-id");
		var url = hostUrl+"add_medicine.php?deleditprescid="+medicineid;
		//console.log(treatid, url);
		$("#medRow"+medicineid).remove();
		$("#medRow1"+medicineid).remove();
		if(medicineid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
		

	});
	
	
	$("#coding_language").on("blur", function(){
		var patientid = $(this).attr("data-patient-id");
		var medicineid = $(this).val();
		var episodeid = $(this).attr("data-episode-id");
		
		if(medicineid!= "" && episodeid!= "0")
		{
		var url = hostUrl+"add_medicine.php?editmedid="+medicineid+"&episodeid="+episodeid;
		console.log(url,medicineid,episodeid);
		}
		else
		{
		var url = hostUrl+"add_medicine.php?medid="+medicineid+"&patientid="+patientid;
		console.log(url,medicineid,patientid);
		}
		
		if(medicineid == "" && episodeid == ""){
			return false;
		}
		else if(medicineid!= "" && episodeid == "0"){
			$.get(url, function(response){
				//console.log(response);
				$("#dispMedTable").html("").prepend(response);
			});
		}
		else if(medicineid!= "" && episodeid!= "0"){
			$("#beforeEditMedTable").remove();
			$.get(url, function(response){
				//console.log(response);
				$("#editMedTable").html("").prepend(response);
			});
		}
		
			//$(':text').val(''); //Clear search field
			$('.searchMedicine').val('');
			//$("#coding_language").focus();	//focus search filed 
			$("#prescription-template1").hide();$("#prescription-template2").hide();
		
	});
	
	$("#coding_language").keydown(function(e) {
		if(e.originalEvent.keyCode == 13){ //If User press enter key
		var patientid = $(this).attr("data-patient-id");
		var medicineid = $(this).val();
		var episodeid = $(this).attr("data-episode-id");
		
		if(medicineid!= "" && episodeid!= "0")
		{
		var url = hostUrl+"add_medicine.php?editmedid="+medicineid+"&episodeid="+episodeid;
		}
		else
		{
		var url = hostUrl+"add_medicine.php?medid="+medicineid+"&patientid="+patientid;
		}
		
		if(medicineid == "" && episodeid == ""){
			return false;
		}
		else if(medicineid!= "" && episodeid == "0"){
			$.get(url, function(response){
				//console.log(response);
				$("#dispMedTable").html("").prepend(response);
			});
		}
		else if(medicineid!= "" && episodeid!= "0"){
			$("#beforeEditMedTable").remove();
			$.get(url, function(response){
				//console.log(response);
				$("#editMedTable").html("").prepend(response);
			});
		}
		
			$('.searchMedicine').val(''); //Clear search field
			//$("#coding_language").focus();	//focus search filed 
			$("#prescription-template1").hide();$("#prescription-template2").hide();
			return false;
		}
		//console.log(e);
	});
	
	
	$(".edit_load_template").on("click", function() {
		var episodeid = $(this).attr("data-episode-id");
		var templateid = $(this).attr("data-template-id");
		var url = hostUrl+"add_medicine.php?editloadtemplate="+templateid+"&episodeid="+episodeid;
		console.log(episodeid, templateid, url);
		$("#beforeEditMedTable").remove();
		//$("#prescription-template1").hide();$("#prescription-template2").hide();
		if(templateid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				$("#editMedTable").html("").prepend(response);
			});
		}
		

	});
	
	$("body").on("click",".edit_prev_prescription", function(){
		var episodeid = $(this).attr("data-episode-id");
		var prevepisode = $(this).attr("data-prev-episode-id");
		var url = hostUrl+"add_medicine.php?editprevprescid="+prevepisode+"&episodeid="+episodeid;
		console.log(episodeid, prevepisode, url);
		$("#beforeEditMedTable").remove();
		//$("#prescription-template1").hide();$("#prescription-template2").hide();
		if(prevepisode == "") {
			return false;
		} else {
			$.get(url, function(response) {
				$("#editMedTable").html("").prepend(response);
			});
		}
		

	});
	$(".edit_recent_medicine").on("click", function() {
		var episodeid = $(this).attr("data-episode-id");
		var medicineid = $(this).attr("data-medicine-id");
		var productid = $(this).attr("data-product-id");
		var url = hostUrl+"add_medicine.php?editmedid="+medicineid+"&productid="+productid+"&episodeid="+episodeid+"&recent=1";
		//console.log(patientid, sympid, url);
		$("#beforeEditMedTable").remove();
		//$("#prescription-template1").hide();$("#prescription-template2").hide();
		if(medicineid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				$("#editMedTable").html("").prepend(response);
			});
		}
		

	});
	$(".edit_frequent_medicine").on("click", function() {
		var episodeid = $(this).attr("data-episode-id");
		var medicineid = $(this).attr("data-medicine-id");
		var url = hostUrl+"add_medicine.php?editmedid="+medicineid+"&episodeid="+episodeid;
		//console.log(patientid, sympid, url);
		$("#beforeEditMedTable").remove();
		//$("#prescription-template1").hide();$("#prescription-template2").hide();
		if(medicineid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				$("#editMedTable").html("").prepend(response);
			});
		}
		

	});
	$(".frequent_medicine").on("click", function() {
		var patientid = $(this).attr("data-patient-id");
		var medicineid = $(this).attr("data-medicine-id");
		var productid = $(this).attr("data-product-id");
		var url = hostUrl+"add_medicine.php?medid="+medicineid+"&productid="+productid+"&patientid="+patientid+"&recent=1";
		//console.log(patientid, sympid, url);
		$("#prescription-template1").hide();$("#prescription-template2").hide();
		if(medicineid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				//$("#sympBefore .bootstrap-tagsinput span").remove();
				//$("#sympBefore .bootstrap-tagsinput").prepend(response);
				$("#dispMedTable").html("").prepend(response);
			});
		}
		

	});
	
	//GET Medicine on blur value scripts
	$("body").on("blur", ".tradename", function() {
		var episodeid = $(this).attr("data-episode-id");
		var tradename = $(this).val();
		var freqmedid = $(this).attr("data-freq-medicine-id");
		if(freqmedid!= "" && episodeid!= "0")
		{
		var url = hostUrl+"add_medicine.php?editfreqmedid="+freqmedid+"&tradename="+tradename+"&episodeid="+episodeid;
		}
		else
		{
		var url = hostUrl+"add_medicine.php?updatefreqmedid="+freqmedid+"&tradename="+tradename;
		}
		
		//console.log(tradename,url);
		//$(this).parent("span").remove();
		if(freqmedid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	$("body").on("blur", ".genericname", function() {
		var episodeid = $(this).attr("data-episode-id");
		var genericname = $(this).val();	
		var freqmedid = $(this).attr("data-freq-medicine-id");
		if(freqmedid!= "" && episodeid!= "0")
		{
		var url = hostUrl+"add_medicine.php?editfreqmedid="+freqmedid+"&genericname="+genericname+"&episodeid="+episodeid;
		}
		else
		{
		var url = hostUrl+"add_medicine.php?updatefreqmedid="+freqmedid+"&genericname="+genericname;
		}
		//console.log(tradename,url);
		//$(this).parent("span").remove();
		if(freqmedid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	$("body").on("blur", ".frequency", function() {
		var episodeid = $(this).attr("data-episode-id");
		var frequency = $(this).val();	
		var freqmedid = $(this).attr("data-freq-medicine-id");
		if(freqmedid!= "" && episodeid!= "0")
		{
		var url = hostUrl+"add_medicine.php?editfreqmedid="+freqmedid+"&frequency="+frequency+"&episodeid="+episodeid;
		}
		else
		{
		var url = hostUrl+"add_medicine.php?updatefreqmedid="+freqmedid+"&frequency="+frequency;
		}
		//console.log(tradename,url);
		//$(this).parent("span").remove();
		if(freqmedid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	$("body").on("change", ".medtiming", function() {
		var episodeid = $(this).attr("data-episode-id");
		var medtiming = $(this).val();	
		var freqmedid = $(this).attr("data-freq-medicine-id");
		if(freqmedid!= "" && episodeid!= "0")
		{
		var url = hostUrl+"add_medicine.php?editfreqmedid="+freqmedid+"&medtiming="+medtiming+"&episodeid="+episodeid;
		}
		else
		{
		var url = hostUrl+"add_medicine.php?updatefreqmedid="+freqmedid+"&medtiming="+medtiming;
		}
		//console.log(tradename,url);
		//$(this).parent("span").remove();
		if(freqmedid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	$("body").on("change", ".duration", function() {
		var episodeid = $(this).attr("data-episode-id");
		var duration = $(this).val();	
		var freqmedid = $(this).attr("data-freq-medicine-id");
		if(freqmedid!= "" && episodeid!= "0")
		{
		var url = hostUrl+"add_medicine.php?editfreqmedid="+freqmedid+"&duration="+duration+"&episodeid="+episodeid;
		//console.log(freqmedid,url);
		}
		else if(freqmedid!= "" && episodeid == "0")
		{
		var url = hostUrl+"add_medicine.php?updatefreqmedid="+freqmedid+"&duration="+duration;
		console.log(freqmedid,duration,url);
		}
		//console.log(tradename,url);
		//$(this).parent("span").remove();
		if(freqmedid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	
	
	$("body").on("change", ".slctFreqMorning", function() {
		var episodeid = $(this).attr("data-episode-id");
		var FreqMorning = $(this).val();	
		var freqmedid = $(this).attr("data-freq-medicine-id");
		if(freqmedid!= "" && episodeid!= "0")
		{
		var url = hostUrl+"add_medicine.php?editfreqmedid="+freqmedid+"&FreqMorning="+FreqMorning+"&episodeid="+episodeid;
		}
		else
		{
		var url = hostUrl+"add_medicine.php?updatefreqmedid="+freqmedid+"&FreqMorning="+FreqMorning;
		}
		//console.log(tradename,url);
		//$(this).parent("span").remove();
		if(freqmedid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	$("body").on("change", ".slctFreqAfternoon", function() {
		var episodeid = $(this).attr("data-episode-id");
		var FreqAfternoon = $(this).val();	
		var freqmedid = $(this).attr("data-freq-medicine-id");
		if(freqmedid!= "" && episodeid!= "0")
		{
		var url = hostUrl+"add_medicine.php?editfreqmedid="+freqmedid+"&FreqAfternoon="+FreqAfternoon+"&episodeid="+episodeid;
		}
		else
		{
		var url = hostUrl+"add_medicine.php?updatefreqmedid="+freqmedid+"&FreqAfternoon="+FreqAfternoon;
		}
		//console.log(tradename,url);
		//$(this).parent("span").remove();
		if(freqmedid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	$("body").on("change", ".slctFreqNight", function() {
		var episodeid = $(this).attr("data-episode-id");
		var FreqNight = $(this).val();	
		var freqmedid = $(this).attr("data-freq-medicine-id");
		if(freqmedid!= "" && episodeid!= "0")
		{
		var url = hostUrl+"add_medicine.php?editfreqmedid="+freqmedid+"&FreqNight="+FreqNight+"&episodeid="+episodeid;
		}
		else
		{
		var url = hostUrl+"add_medicine.php?updatefreqmedid="+freqmedid+"&FreqNight="+FreqNight;
		}
		//console.log(tradename,url);
		//$(this).parent("span").remove();
		if(freqmedid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	$("body").on("change", ".duration_type", function() {
		var episodeid = $(this).attr("data-episode-id");
		var durationType = $(this).val();	
		var freqmedid = $(this).attr("data-freq-medicine-id");
		if(freqmedid!= "" && episodeid!= "0")
		{
		var url = hostUrl+"add_medicine.php?editfreqmedid="+freqmedid+"&durationType="+durationType+"&episodeid="+episodeid;
		}
		else
		{
		var url = hostUrl+"add_medicine.php?updatefreqmedid="+freqmedid+"&durationType="+durationType;
		}
		//console.log(tradename,url);
		//$(this).parent("span").remove();
		if(freqmedid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	$("body").on("blur", ".instructions", function() {
		var episodeid = $(this).attr("data-episode-id");
		var instructions = $(this).val();	
		var freqmedid = $(this).attr("data-freq-medicine-id");
		if(freqmedid!= "" && episodeid!= "0")
		{
		var url = hostUrl+"add_medicine.php?editfreqmedid="+freqmedid+"&instructions="+instructions+"&episodeid="+episodeid;
		}
		else
		{
		var url = hostUrl+"add_medicine.php?updatefreqmedid="+freqmedid+"&instructions="+instructions;
		}
		//console.log(tradename,url);
		//$(this).parent("span").remove();
		if(freqmedid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	////////////////////////////////End Add Prescriptions scripts/////////////////////////////////////////////////
	
	
	$("body").on("click",".delete_all_diagnosis_test", function() {
		var patientid = $(this).attr("data-patient-id");
		var docid = $(this).attr("data-doctor-id");		
		var url = hostUrl+"get_diagno_test_report.php?delallInvest=1&docid="+docid+"&patid="+patientid;
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
	
		
	// on attach report share link button click 
						$('.share_link_report').click(function(){
							var theUserMobile = $('#pat_mobile');
							var patientid = $('#pat_id');
							if(!theUserMobile.val()){ 
								//alert('Email or Mobile No. are must required'); 
								swal({
										title: "Required!",
										text: "Patient mobile no. are must required!!",
										type: "warning",
										confirmButtonColor: "#DD6B55",
										confirmButtonText: "Ok, got it!"
									});
								
								
							}else{ 
								$.ajax({
									type: "POST",
									url: "add_details.php",
									data: 'act=share&patientid='+patientid.val()+'&userMobile='+theUserMobile.val(),
									success: function(html){
										
									//alert('Link Sent Successfully');
									swal({
											title: "Shared the link with patient successfully",
											text: "Share a link with the patients to upload their old reports",
											type: "success"
										});
									}  
								});
							}
						});
						
						
			
});