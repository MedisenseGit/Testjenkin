$(document).ready(function() {
	var s=window.location.href;
	var n = s.indexOf('Ophthal-EMR/?');
  s = s.substring(0, n != -1 ? n : s.length);
  var hostUrl = s;
  //alert(hostUrl);
	$("#view-trend-analysis").hide();
		$("#view-latest-reports").hide();
		$("#visit-details").hide();
		$("#medical-history").hide();
		$("#ReportSection").hide();
		$("#fundusImageSection").hide();
	
	$("#visitDetails").click(function(){
		
		$("#visit-details").show();
		$("#view-trend-analysis").hide();
		$("#view-latest-reports").hide();
		$("#medical-history").hide();
		$("#add-visit-dtails").hide();
		$("#edit_visist_details").hide();
		$("#view-fundus-image").hide();
		$('#fundus_message').css('display','none');
	});
	
	$("#medicalHistory").click(function(){
		
		$("#medical-history").show();
		$("#view-trend-analysis").hide();
		$("#view-latest-reports").hide();
		$("#visit-details").hide();
		$("#add-visit-dtails").hide();
		$("#edit_visist_details").hide();
		$("#view-fundus-image").hide();
		$('#fundus_message').css('display','none');
	});
	
	
	$("#addvisitDetails").click(function(){
		$("#add-visit-dtails").show();
		$("#view-trend-analysis").hide();
		$("#view-latest-reports").hide();
		$("#visit-details").hide();
		$("#medical-history").hide();
		$("#edit_visist_details").hide();
		$("#view-fundus-image").hide();
		$('#fundus_message').css('display','none');
	});
	
	$("#latestReports").click(function(){
		$("#view-latest-reports").show();
		$("#view-trend-analysis").hide();
		$("#add-visit-dtails").hide();
		$("#visit-details").hide();
		$("#medical-history").hide();
		$("#edit_visist_details").hide();
		$("#view-fundus-image").hide();
		$('#fundus_message').css('display','none');
	});
	$("#trendAnalysis").click(function(){
		$("#view-trend-analysis").show();
		$("#view-latest-reports").hide();
		$("#add-visit-dtails").hide();
		$("#visit-details").hide();
		$("#medical-history").hide();
		$("#edit_visist_details").hide();
		$("#view-fundus-image").hide();
		$('#fundus_message').css('display','none');
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
	
	//On click attach reports button in fundus image tab
	$("body").on("click", "#attachFundusImage", function() {
		$("#fundusImageSection").show();		
		$("#attachFundusImage").hide();
	});
	
	$("body").on("click", "#cancelFundusImage", function() {
		$("#fundusImageSection").hide();		
		$("#attachFundusImage").show();
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
			
				$("#employee-grid").hide();
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
			
				$("#employee-grid").hide();
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
		//console.log(examfindings,examid,url);
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
 
	$("#get_examination_res").on("blur", function(){
		var patientid = $(this).attr("data-patient-id");
		var examinationid = $(this).val();
		var episodeid = $(this).attr("data-episode-id");
		
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
			});
		} else if(examinationid!= "" && episodeid!= "0") {
			$("#beforeExaminationResult").remove();
			$.get(url, function(response) {
				//console.log(response);
				$("#editExaminationResult").html("").prepend(response);
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
			});
		} else if(examinationid!= "" && episodeid!= "0") {
			$("#beforeExaminationResult").remove();
			$.get(url, function(response) {
				//console.log(response);
				$("#editExaminationResult").html("").prepend(response);
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
		
		if(examinationid == "") {
			return false;
		} else {
			$.get(url, function(response){
				//console.log(response);
				$("#dispExamination").html("").prepend(response);
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
			});
		} else if(investid!= "" && episodeid!= "0") {
			$("#beforeEditInvest").remove();
			$.get(url, function(response) {
				//console.log(response);
				$("#dispEditInvest").html("").prepend(response);
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
			});
		} else if(investid!= "" && episodeid!= "0") {
			$("#beforeEditInvest").remove();
			$.get(url, function(response) {
				//console.log(response);
				$("#dispEditInvest").html("").prepend(response);
			});
		}
		
			$('.searchDiagnosTest').val(''); //Clear search field
			//$("#get_diagnosis_test").focus();	//focus search filed 
			
			return false;
		}
		//console.log(e);
	}); 
	
	$(".get_edit_diagnosis_test_prior").on("click", function() {
		var patientid = $(this).attr("data-patient-id");
		var episodeid = $(this).attr("data-episode-id");
		var investid = $(this).attr("data-main-test-id");		
		var url = hostUrl+"get_diagno_test_report.php?editinvestid="+investid+"&episodeid="+episodeid;
		//console.log(patientid, sympid, url);
		$("#beforeEditInvest").remove();
		if(investid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				$("#dispEditInvest").html("").prepend(response);
			});
		}
		

	});
	
	$(".get_diagnosis_test_prior").on("click", function() {
		var patientid = $(this).attr("data-patient-id");
		var investid = $(this).attr("data-main-test-id");		
		var url = hostUrl+"get_diagno_test_report.php?investid="+investid+"&patientid="+patientid;
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

	$("body").on("blur", ".editTestActualVal", function() {
		var editinvestid = $(this).attr("data-invest-id");
		var editinvestval = $(this).val();
		var url = hostUrl+"get_diagno_test_report.php?editinvestactual="+editinvestid+"&editinvestval="+editinvestval;
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
			$("#employee-grid").hide();
		
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
			$("#employee-grid").hide();
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
		//$("#employee-grid").hide();
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
		//$("#employee-grid").hide();
		if(prevepisode == "") {
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
		//$("#employee-grid").hide();
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
		var url = hostUrl+"add_medicine.php?medid="+medicineid+"&patientid="+patientid;
		//console.log(patientid, sympid, url);
		$("#employee-grid").hide();
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
	$("body").on("blur", ".medtiming", function() {
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
	$("body").on("blur", ".duration", function() {
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
		//console.log(freqmedid,url);
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
	
	//////////////////////////////// Update Spectacle Prescriptions //////////////////////////////////////////////
	$("body").on("change", "#slctDistVisionRE", function() {
		var spectacle_id = $(this).attr("data-spectacle-id");
		var slctDistVisionRE = $(this).val();

		var url = hostUrl+"Ophthal-EMR/my_patient_profile_ophthal_save.php?updateSpectacle=1&spectacle_id="+spectacle_id+"&slctDistVisionRE="+slctDistVisionRE;
		console.log(spectacle_id,url);
	
		//console.log(tradename,url);
		//$(this).parent("span").remove();
		if(spectacle_id == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	$("body").on("change", "#slctDistVisionLE", function() {
		var spectacle_id = $(this).attr("data-spectacle-id");
		var slctDistVisionLE = $(this).val();

		var url = hostUrl+"Ophthal-EMR/my_patient_profile_ophthal_save.php?updateSpectacle=1&spectacle_id="+spectacle_id+"&slctDistVisionLE="+slctDistVisionLE;
		console.log(spectacle_id,url);
	
		//console.log(tradename,url);
		//$(this).parent("span").remove();
		if(spectacle_id == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	$("body").on("change", "#slctNearVisionRE", function() {
		var spectacle_id = $(this).attr("data-spectacle-id");
		var slctNearVisionRE = $(this).val();

		var url = hostUrl+"Ophthal-EMR/my_patient_profile_ophthal_save.php?updateSpectacle=1&spectacle_id="+spectacle_id+"&slctNearVisionRE="+slctNearVisionRE;
		console.log(spectacle_id,url);
	
		//console.log(tradename,url);
		//$(this).parent("span").remove();
		if(spectacle_id == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	$("body").on("change", "#slctNearVisionLE", function() {
		var spectacle_id = $(this).attr("data-spectacle-id");
		var slctNearVisionLE = $(this).val();

		var url = hostUrl+"Ophthal-EMR/my_patient_profile_ophthal_save.php?updateSpectacle=1&spectacle_id="+spectacle_id+"&slctNearVisionLE="+slctNearVisionLE;
		console.log(spectacle_id,url);
	
		//console.log(tradename,url);
		//$(this).parent("span").remove();
		if(spectacle_id == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	$("body").on("blur", "#se_refractionRE_value1", function() {
		var spectacle_id = $(this).attr("data-spectacle-id");
		var se_refractionRE_value1 = $(this).val();

		var url = hostUrl+"Ophthal-EMR/my_patient_profile_ophthal_save.php?updateSpectacle=1&spectacle_id="+spectacle_id+"&se_refractionRE_value1="+se_refractionRE_value1;
		console.log(spectacle_id,url);
	
		//console.log(tradename,url);
		//$(this).parent("span").remove();
		if(spectacle_id == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	$("body").on("blur", "#se_refractionRE_value2", function() {
		var spectacle_id = $(this).attr("data-spectacle-id");
		var se_refractionRE_value2 = $(this).val();

		var url = hostUrl+"Ophthal-EMR/my_patient_profile_ophthal_save.php?updateSpectacle=1&spectacle_id="+spectacle_id+"&se_refractionRE_value2="+se_refractionRE_value2;
		console.log(spectacle_id,url);
	
		//console.log(tradename,url);
		//$(this).parent("span").remove();
		if(spectacle_id == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	$("body").on("blur", "#se_refractionLE_value1", function() {
		var spectacle_id = $(this).attr("data-spectacle-id");
		var se_refractionLE_value1 = $(this).val();

		var url = hostUrl+"Ophthal-EMR/my_patient_profile_ophthal_save.php?updateSpectacle=1&spectacle_id="+spectacle_id+"&se_refractionLE_value1="+se_refractionLE_value1;
		console.log(spectacle_id,url);
	
		//console.log(tradename,url);
		//$(this).parent("span").remove();
		if(spectacle_id == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	$("body").on("blur", "#se_refractionLE_value2", function() {
		var spectacle_id = $(this).attr("data-spectacle-id");
		var se_refractionLE_value2 = $(this).val();

		var url = hostUrl+"Ophthal-EMR/my_patient_profile_ophthal_save.php?updateSpectacle=1&spectacle_id="+spectacle_id+"&se_refractionLE_value2="+se_refractionLE_value2;
		console.log(spectacle_id,url);
	
		//console.log(tradename,url);
		//$(this).parent("span").remove();
		if(spectacle_id == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	$("body").on("blur", ".DvSpeherRE", function() {
		var spectacle_id = $(this).attr("data-spectacle-id");
		var DvSpeherRE = $(this).val();

		var url = hostUrl+"Ophthal-EMR/my_patient_profile_ophthal_save.php?updateSpectacle=1&spectacle_id="+spectacle_id+"&DvSpeherRE="+DvSpeherRE;
		console.log(spectacle_id,url);
	
		//console.log(tradename,url);
		//$(this).parent("span").remove();
		if(spectacle_id == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	$("body").on("blur", ".DvCylRE", function() {
		var spectacle_id = $(this).attr("data-spectacle-id");
		var DvCylRE = $(this).val();

		var url = hostUrl+"Ophthal-EMR/my_patient_profile_ophthal_save.php?updateSpectacle=1&spectacle_id="+spectacle_id+"&DvCylRE="+DvCylRE;
		console.log(spectacle_id,url);
	
		//console.log(tradename,url);
		//$(this).parent("span").remove();
		if(spectacle_id == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	$("body").on("blur", ".DvAxisRE", function() {
		var spectacle_id = $(this).attr("data-spectacle-id");
		var DvAxisRE = $(this).val();

		var url = hostUrl+"Ophthal-EMR/my_patient_profile_ophthal_save.php?updateSpectacle=1&spectacle_id="+spectacle_id+"&DvAxisRE="+DvAxisRE;
		console.log(spectacle_id,url);
	
		//console.log(tradename,url);
		//$(this).parent("span").remove();
		if(spectacle_id == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	$("body").on("blur", ".DvSpeherLE", function() {
		var spectacle_id = $(this).attr("data-spectacle-id");
		var DvSpeherLE = $(this).val();

		var url = hostUrl+"Ophthal-EMR/my_patient_profile_ophthal_save.php?updateSpectacle=1&spectacle_id="+spectacle_id+"&DvSpeherLE="+DvSpeherLE;
		console.log(spectacle_id,url);
	
		//console.log(tradename,url);
		//$(this).parent("span").remove();
		if(spectacle_id == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	$("body").on("blur", ".DvCylLE", function() {
		var spectacle_id = $(this).attr("data-spectacle-id");
		var DvCylLE = $(this).val();

		var url = hostUrl+"Ophthal-EMR/my_patient_profile_ophthal_save.php?updateSpectacle=1&spectacle_id="+spectacle_id+"&DvCylLE="+DvCylLE;
		console.log(spectacle_id,url);
	
		//console.log(tradename,url);
		//$(this).parent("span").remove();
		if(spectacle_id == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	$("body").on("blur", ".DvAxisLE", function() {
		var spectacle_id = $(this).attr("data-spectacle-id");
		var DvAxisLE = $(this).val();

		var url = hostUrl+"Ophthal-EMR/my_patient_profile_ophthal_save.php?updateSpectacle=1&spectacle_id="+spectacle_id+"&DvAxisLE="+DvAxisLE;
		console.log(spectacle_id,url);
	
		//console.log(tradename,url);
		//$(this).parent("span").remove();
		if(spectacle_id == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	$("body").on("blur", ".NvSpeherRE", function() {
		var spectacle_id = $(this).attr("data-spectacle-id");
		var NvSpeherRE = $(this).val();

		var url = hostUrl+"Ophthal-EMR/my_patient_profile_ophthal_save.php?updateSpectacle=1&spectacle_id="+spectacle_id+"&NvSpeherRE="+NvSpeherRE;
		console.log(spectacle_id,url);
	
		//console.log(tradename,url);
		//$(this).parent("span").remove();
		if(spectacle_id == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	$("body").on("blur", ".NvCylRE", function() {
		var spectacle_id = $(this).attr("data-spectacle-id");
		var NvCylRE = $(this).val();

		var url = hostUrl+"Ophthal-EMR/my_patient_profile_ophthal_save.php?updateSpectacle=1&spectacle_id="+spectacle_id+"&NvCylRE="+NvCylRE;
		console.log(spectacle_id,url);
	
		//console.log(tradename,url);
		//$(this).parent("span").remove();
		if(spectacle_id == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	$("body").on("blur", ".NvAxisRE", function() {
		var spectacle_id = $(this).attr("data-spectacle-id");
		var NvAxisRE = $(this).val();

		var url = hostUrl+"Ophthal-EMR/my_patient_profile_ophthal_save.php?updateSpectacle=1&spectacle_id="+spectacle_id+"&NvAxisRE="+NvAxisRE;
		console.log(spectacle_id,url);
	
		//console.log(tradename,url);
		//$(this).parent("span").remove();
		if(spectacle_id == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	$("body").on("blur", ".NvSpeherLE", function() {
		var spectacle_id = $(this).attr("data-spectacle-id");
		var NvSpeherLE = $(this).val();

		var url = hostUrl+"Ophthal-EMR/my_patient_profile_ophthal_save.php?updateSpectacle=1&spectacle_id="+spectacle_id+"&NvSpeherLE="+NvSpeherLE;
		console.log(spectacle_id,url);
	
		//console.log(tradename,url);
		//$(this).parent("span").remove();
		if(spectacle_id == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	$("body").on("blur", ".NvCylLE", function() {
		var spectacle_id = $(this).attr("data-spectacle-id");
		var NvCylLE = $(this).val();

		var url = hostUrl+"Ophthal-EMR/my_patient_profile_ophthal_save.php?updateSpectacle=1&spectacle_id="+spectacle_id+"&NvCylLE="+NvCylLE;
		console.log(spectacle_id,url);
	
		//console.log(tradename,url);
		//$(this).parent("span").remove();
		if(spectacle_id == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	$("body").on("blur", ".NvAxisLE", function() {
		var spectacle_id = $(this).attr("data-spectacle-id");
		var NvAxisLE = $(this).val();

		var url = hostUrl+"Ophthal-EMR/my_patient_profile_ophthal_save.php?updateSpectacle=1&spectacle_id="+spectacle_id+"&NvAxisLE="+NvAxisLE;
		console.log(spectacle_id,url);
	
		//console.log(tradename,url);
		//$(this).parent("span").remove();
		if(spectacle_id == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	$("body").on("blur", ".IpdRE", function() {
		var spectacle_id = $(this).attr("data-spectacle-id");
		var IpdRE = $(this).val();

		var url = hostUrl+"Ophthal-EMR/my_patient_profile_ophthal_save.php?updateSpectacle=1&spectacle_id="+spectacle_id+"&IpdRE="+IpdRE;
		console.log(spectacle_id,url);
	
		//console.log(tradename,url);
		//$(this).parent("span").remove();
		if(spectacle_id == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	$("body").on("blur", ".IpdLE", function() {
		var spectacle_id = $(this).attr("data-spectacle-id");
		var IpdLE = $(this).val();

		var url = hostUrl+"Ophthal-EMR/my_patient_profile_ophthal_save.php?updateSpectacle=1&spectacle_id="+spectacle_id+"&IpdLE="+IpdLE;
		console.log(spectacle_id,url);
	
		//console.log(tradename,url);
		//$(this).parent("span").remove();
		if(spectacle_id == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	
	$("body").on("blur", ".DvVisionRE", function() {
		var spectacle_id = $(this).attr("data-spectacle-id");
		var DvVisionRE = $(this).val();

		var url = hostUrl+"Ophthal-EMR/my_patient_profile_ophthal_save.php?updateSpectacle=1&spectacle_id="+spectacle_id+"&DvVisionRE="+DvVisionRE;
		console.log(spectacle_id,url);
	
		//console.log(tradename,url);
		//$(this).parent("span").remove();
		if(spectacle_id == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	$("body").on("blur", ".DvVisionLE", function() {
		var spectacle_id = $(this).attr("data-spectacle-id");
		var DvVisionLE = $(this).val();

		var url = hostUrl+"Ophthal-EMR/my_patient_profile_ophthal_save.php?updateSpectacle=1&spectacle_id="+spectacle_id+"&DvVisionLE="+DvVisionLE;
		console.log(spectacle_id,url);
	
		//console.log(tradename,url);
		//$(this).parent("span").remove();
		if(spectacle_id == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	$("body").on("blur", ".NvVisionRE", function() {
		var spectacle_id = $(this).attr("data-spectacle-id");
		var NvVisionRE = $(this).val();

		var url = hostUrl+"Ophthal-EMR/my_patient_profile_ophthal_save.php?updateSpectacle=1&spectacle_id="+spectacle_id+"&NvVisionRE="+NvVisionRE;
		console.log(spectacle_id,url);
	
		//console.log(tradename,url);
		//$(this).parent("span").remove();
		if(spectacle_id == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	
	$("body").on("blur", ".NvVisionLE", function() {
		var spectacle_id = $(this).attr("data-spectacle-id");
		var NvVisionLE = $(this).val();

		var url = hostUrl+"Ophthal-EMR/my_patient_profile_ophthal_save.php?updateSpectacle=1&spectacle_id="+spectacle_id+"&NvVisionLE="+NvVisionLE;
		console.log(spectacle_id,url);
	
		//console.log(tradename,url);
		//$(this).parent("span").remove();
		if(spectacle_id == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	
		$("body").on("change", ".slctIOP_RE", function() {
		var spectacle_id = $(this).attr("data-spectacle-id");
		var IopRE = $(this).val();

		var url = hostUrl+"Ophthal-EMR/my_patient_profile_ophthal_save.php?updateSpectacle=1&spectacle_id="+spectacle_id+"&IopRE="+IopRE;
		console.log(spectacle_id,url);
	
		//console.log(tradename,url);
		//$(this).parent("span").remove();
		if(spectacle_id == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	$("body").on("change", ".slctIOP_LE", function() {
		var spectacle_id = $(this).attr("data-spectacle-id");
		var IopLE = $(this).val();

		var url = hostUrl+"Ophthal-EMR/my_patient_profile_ophthal_save.php?updateSpectacle=1&spectacle_id="+spectacle_id+"&IopLE="+IopLE;
		console.log(spectacle_id,url);
	
		//console.log(tradename,url);
		//$(this).parent("span").remove();
		if(spectacle_id == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	
	//////////////////////////////// End of Update Spectacle Prescriptions //////////////////////////////////////////////
	
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
						
						
	//*******************************OPHTHAL EMR SCRIPTS STARTS HERE******************************************
	//Refer to Opticle Center
	$("body").on("change", "#selectOpticleCenter", function() {
		var patientid = $(this).attr("data-patient-id");
		var episodeid = $(this).attr("data-episode-id");
		var opticleid = $(this).val();
		 swal({
                        title: "Referring this case to a Opticle Center ?",
                        text: "They will only be able to see patient name and spectacle prescriptions ordered and not other details. ",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes, refer it!",
                        cancelButtonText: "No, cancel!",
                        closeOnConfirm: false,
                        closeOnCancel: false },
                    function (isConfirm) {
                        if (isConfirm) {
							var url = "refer_diagnosis.php?opticleid="+opticleid+"&patientid="+patientid+"&episodeid="+episodeid;
							$.get(url, function(response){
							console.log(response);	
                            swal("Referred Successfully!", "", "success");
							});
                        } else {
                            swal("Cancelled", "", "error");
                        }
                });
		
		
	});


	function getLidsData(patientid, lidsid, eyetype, eventtype, lidstatus=0) {
	    var url = "get_lids_data.php?lidsid="+lidsid+"&patientid="+patientid+"&eye_type="+eyetype;
	    if(eventtype == "priorClick") {
		url = url + "&lid_status=" + lidstatus;
	    }
	    //console.log(url);
	    $.get(url, function(response) {
		//console.log(response);
		var newInput = $("<input type='checkbox' style='width: 20px;height: 20px; vertical-align: bottom;margin-right:5px;' class='i-checks m-l'>");
		newInput.attr("name", response);
		newInput.attr("value", response);
		newInput.attr("data-patient-id", patientid);
		var rightInput = newInput.clone();
		rightInput.attr("data-lidsRE-id", response);
		rightInput.addClass("get_lids_priorRE");
		var leftInput = newInput.clone();
		leftInput.attr("data-lidsLE-id", response);
		leftInput.addClass("get_lids_priorLE");
		if(eyetype == 1) {
		    rightInput.attr("checked", "checked");
		} else if (eyetype == 2) {
		    leftInput.attr("checked", "checked");
		}
		if(eventtype != "priorClick") {
		    $("#lidsBeforeRE").prepend(rightInput.prop('outerHTML') + lidsid);
		    $("#lidsBefore_LE").prepend(leftInput.prop('outerHTML') + lidsid);
		}
	    });
	}
	
	
	// Updated Examination Sections - Lids Right Eye 14/06/2018
	$("#get_lids_RE").on("blur", function() {
		var patientid = $(this).attr("data-patient-id");
		var lidsid = $(this).val();
		if(lidsid == "") {
			return false;
		} else {
		    getLidsData(patientid, lidsid, 1, "blur");
		}
        $('.searchLidsRE').val(''); //Clear search field
		$("#get_lids_RE").focus();	//focus search filed 
	});
	
	$("#get_lids_RE").keydown(function(e) {
		if(e.originalEvent.keyCode == 13){
		var patientid = $(this).attr("data-patient-id");
		var lidsid = $(this).val();
		
		if(lidsid == "") {
			return false;
		} else {
		    getLidsData(patientid, lidsid, 1, "keyDown");
		}
		
			$('.searchLidsRE').val(''); //Clear search field
			$("#get_lids_RE").focus();	//focus search filed 
			return false;
		}
		//console.log(e);
	}); 
	
	$("body").on("click", ".get_lids_priorRE", function() {
		var patientid = $(this).attr("data-patient-id");
		var lidsid = $(this).attr("data-lidsRE-id");
		var lidstatus = 0;
		if($(this).is(":checked")) {
		    lidstatus = 1;    
		}
		
		if(lidsid == "") {
			return false;
		} else {
		    getLidsData(patientid, lidsid, 1, "priorClick", lidstatus);
		}
	});
	
	// Updated Examination Sections - Lids Left Eye
	$("#get_lids_LE").on("blur", function() {
		var patientid = $(this).attr("data-patient-id");
		var lidsid = $(this).val();
		
		if(lidsid == "") {
			return false;
		} else {
		    getLidsData(patientid, lidsid, 2, "blur");
		}
		
        $('.searchLidsLE').val(''); //Clear search field
		$("#get_lids_LE").focus();	//focus search filed 
	});
	
	$("#get_lids_LE").keydown(function(e) {
		if(e.originalEvent.keyCode == 13){
		var patientid = $(this).attr("data-patient-id");
		var lidsid = $(this).val();
		
		if(lidsid == "") {
			return false;
		} else {
		    getLidsData(patientid, lidsid, 2, "keyDown");
		}
		
			$('.searchLidsLE').val(''); //Clear search field
			$("#get_lids_LE").focus();	//focus search filed 
			return false;
		}
		//console.log(e);
	}); 
	
	$("body").on("click", ".get_lids_priorLE", function() {
		var patientid = $(this).attr("data-patient-id");
		var lidsid = $(this).attr("data-lidsLE-id");
		var lidstatus = 0;
		if($(this).is(":checked")) {
		    lidstatus = 1;    
		}
		
		if(lidsid == "") {
			return false;
		} else {
		    getLidsData(patientid, lidsid, 2, "priorClick", lidstatus);
		}
		

	});
	
	
	/******************* EXAMINATION CONJUCTIVA SECTION STARTS *********************************/
	
	function getConjuctivaData(patientid, conjuctivaid, eyetype, eventtype, conjuctivatatus=0) {
	    var url = "get_conjuctiva_data.php?conjuctivaid="+conjuctivaid+"&patientid="+patientid+"&eye_type="+eyetype;
	    if(eventtype == "priorClick") {
		url = url + "&conjuctiva_status=" + conjuctivatatus;
	    }
	    //console.log(url);
	    $.get(url, function(response) {
		//console.log(response);
		var newInput = $("<input type='checkbox' style='width: 20px;height: 20px; vertical-align: bottom;margin-right:5px;' class='i-checks m-l'>");
		newInput.attr("name", response);
		newInput.attr("value", response);
		newInput.attr("data-patient-id", patientid);
		var rightInput = newInput.clone();
		rightInput.attr("data-conjuctivaRE-id", response);
		rightInput.addClass("get_conjuctiva_priorRE");
		var leftInput = newInput.clone();
		leftInput.attr("data-conjuctivaLE-id", response);
		leftInput.addClass("get_conjuctiva_priorLE");
		if(eyetype == 1) {
		    rightInput.attr("checked", "checked");
		} else if (eyetype == 2) {
		    leftInput.attr("checked", "checked");
		}
		if(eventtype != "priorClick") {
		    $("#conjuctivaBeforeRE").prepend(rightInput.prop('outerHTML') + conjuctivaid);
		    $("#conjuctivaBefore_LE").prepend(leftInput.prop('outerHTML') + conjuctivaid);
		}
	    });
	}
	
	// Updated Conjutiva Right Eye 
	$("#get_conjuctiva_RE").on("blur", function() {
		var patientid = $(this).attr("data-patient-id");
		var conjuctivaid = $(this).val();
		if(conjuctivaid == "") {
			return false;
		} else {
		    getConjuctivaData(patientid, conjuctivaid, 1, "blur");
		}
        $('.searchConjuctivaRE').val(''); //Clear search field
		$("#get_conjuctiva_RE").focus();	//focus search filed 
	});
	
	$("#get_conjuctiva_RE").keydown(function(e) {
		if(e.originalEvent.keyCode == 13){
		var patientid = $(this).attr("data-patient-id");
		var conjuctivaid = $(this).val();
		
		if(conjuctivaid == "") {
			return false;
		} else {
		    getConjuctivaData(patientid, conjuctivaid, 1, "keyDown");
		}
		
			$('.searchConjuctivaRE').val(''); //Clear search field
			$("#get_conjuctiva_RE").focus();	//focus search filed 
			return false;
		}
		//console.log(e);
	}); 
	
	$("body").on("click", ".get_conjuctiva_priorRE", function() {
		var patientid = $(this).attr("data-patient-id");
		var conjuctivaid = $(this).attr("data-conjuctivaRE-id");
		var conjuctivastatus = 0;
		if($(this).is(":checked")) {
		    conjuctivastatus = 1;    
		}
		
		if(conjuctivaid == "") {
			return false;
		} else {
		    getConjuctivaData(patientid, conjuctivaid, 1, "priorClick", conjuctivastatus);
		}
	});
	
	// Updated Conjutiva Right Eye
	$("#get_conjuctiva_LE").on("blur", function() {
		var patientid = $(this).attr("data-patient-id");
		var conjuctivaid = $(this).val();
		
		if(conjuctivaid == "") {
			return false;
		} else {
		    getConjuctivaData(patientid, conjuctivaid, 2, "blur");
		}
		
        $('.searchConjuctivaLE').val(''); //Clear search field
		$("#get_conjuctiva_LE").focus();	//focus search filed 
	});
	
	$("#get_conjuctiva_LE").keydown(function(e) {
		if(e.originalEvent.keyCode == 13){
		var patientid = $(this).attr("data-patient-id");
		var conjuctivaid = $(this).val();
		
		if(conjuctivaid == "") {
			return false;
		} else {
		    getConjuctivaData(patientid, conjuctivaid, 2, "keyDown");
		}
		
			$('.searchConjuctivaLE').val(''); //Clear search field
			$("#get_conjuctiva_LE").focus();	//focus search filed 
			return false;
		}
		//console.log(e);
	}); 
	
	$("body").on("click", ".get_conjuctiva_priorLE", function() {
		var patientid = $(this).attr("data-patient-id");
		var conjuctivaid = $(this).attr("data-conjuctivaLE-id");
		var conjuctivastatus = 0;
		if($(this).is(":checked")) {
		    conjuctivastatus = 1;    
		}
		
		if(conjuctivaid == "") {
			return false;
		} else {
		    getConjuctivaData(patientid, conjuctivaid, 2, "priorClick", conjuctivastatus);
		}
	});
	/******************* EXAMINATION CONJUCTIVA SECTION ENDS *********************************/
	
	/******************* EXAMINATION SCLERA SECTION STARTS *********************************/
	function getScleraData(patientid, scleraid, eyetype, eventtype, scelrastatus=0) {
	    var url = "get_sclera_data.php?scleraid="+scleraid+"&patientid="+patientid+"&eye_type="+eyetype;
	    if(eventtype == "priorClick") {
		url = url + "&sclera_status=" + scelrastatus;
	    }
	    //console.log(url);
	    $.get(url, function(response) {
		//console.log(response);
		var newInput = $("<input type='checkbox' style='width: 20px;height: 20px; vertical-align: bottom;margin-right:5px;' class='i-checks m-l'>");
		newInput.attr("name", response);
		newInput.attr("value", response);
		newInput.attr("data-patient-id", patientid);
		var rightInput = newInput.clone();
		rightInput.attr("data-scleraRE-id", response);
		rightInput.addClass("get_sclera_priorRE");
		var leftInput = newInput.clone();
		leftInput.attr("data-scleraLE-id", response);
		leftInput.addClass("get_sclera_priorLE");
		if(eyetype == 1) {
		    rightInput.attr("checked", "checked");
		} else if (eyetype == 2) {
		    leftInput.attr("checked", "checked");
		}
		if(eventtype != "priorClick") {
		    $("#scleraBeforeRE").prepend(rightInput.prop('outerHTML') + scleraid);
		    $("#scleraBefore_LE").prepend(leftInput.prop('outerHTML') + scleraid);
		}
	    });
	}
	
	// Updated Sclera Right Eye
	$("#get_sclera_RE").on("blur", function() {
		var patientid = $(this).attr("data-patient-id");
		var scleraid = $(this).val();
		if(scleraid == "") {
			return false;
		} else {
		    getScleraData(patientid, scleraid, 1, "blur");
		}
        $('.searchScleraRE').val(''); //Clear search field
		$("#get_sclera_RE").focus();	//focus search filed 
	});
	
	$("#get_sclera_RE").keydown(function(e) {
		if(e.originalEvent.keyCode == 13){
		var patientid = $(this).attr("data-patient-id");
		var scleraid = $(this).val();
		
		if(scleraid == "") {
			return false;
		} else {
		    getScleraData(patientid, scleraid, 1, "keyDown");
		}
		
			$('.searchScleraRE').val(''); //Clear search field
			$("#get_sclera_RE").focus();	//focus search filed 
			return false;
		}
		//console.log(e);
	}); 
	
	$("body").on("click", ".get_sclera_priorRE", function() {
		var patientid = $(this).attr("data-patient-id");
		var scleraid = $(this).attr("data-scleraRE-id");
		var sclerastatus = 0;
		if($(this).is(":checked")) {
		    sclerastatus = 1;    
		}
		
		if(scleraid == "") {
			return false;
		} else {
		    getScleraData(patientid, scleraid, 1, "priorClick", sclerastatus);
		}
	});
	
	// Updated Sclera Left Eye
	$("#get_sclera_LE").on("blur", function() {
		var patientid = $(this).attr("data-patient-id");
		var scleraid = $(this).val();
		
		if(scleraid == "") {
			return false;
		} else {
		    getScleraData(patientid, scleraid, 2, "blur");
		}
		
        $('.searchScleraLE').val(''); //Clear search field
		$("#get_sclera_LE").focus();	//focus search filed 
	});
	
	$("#get_sclera_LE").keydown(function(e) {
		if(e.originalEvent.keyCode == 13){
		var patientid = $(this).attr("data-patient-id");
		var scleraid = $(this).val();
		
		if(scleraid == "") {
			return false;
		} else {
		    getScleraData(patientid, scleraid, 2, "keyDown");
		}
		
			$('.searchScleraLE').val(''); //Clear search field
			$("#get_sclera_LE").focus();	//focus search filed 
			return false;
		}
		//console.log(e);
	}); 
	
	$("body").on("click", ".get_sclera_priorLE", function() {
		var patientid = $(this).attr("data-patient-id");
		var scleraid = $(this).attr("data-scleraLE-id");
		var sclerastatus = 0;
		if($(this).is(":checked")) {
		    sclerastatus = 1;    
		}
		
		if(scleraid == "") {
			return false;
		} else {
		    getScleraData(patientid, scleraid, 2, "priorClick", sclerastatus);
		}
	});
	/******************* EXAMINATION SCLERA SECTION ENDS *********************************/
	
	/******************* EXAMINATION CORNEA ANTERIOR SECTION STARTS *********************************/
	function getCorneaAnteriorData(patientid, cornea_antid, eyetype, eventtype, corneaAntstatus=0) {
	    var url = "get_cornea_anterior_data.php?cornea_antid="+cornea_antid+"&patientid="+patientid+"&eye_type="+eyetype;
	    if(eventtype == "priorClick") {
		url = url + "&cornea_ant_status=" + corneaAntstatus;
	    }
	    //console.log(url);
	    $.get(url, function(response) {
		//console.log(response);
		var newInput = $("<input type='checkbox' style='width: 20px;height: 20px; vertical-align: bottom;margin-right:5px;' class='i-checks m-l'>");
		newInput.attr("name", response);
		newInput.attr("value", response);
		newInput.attr("data-patient-id", patientid);
		var rightInput = newInput.clone();
		rightInput.attr("data-corneaAntRE-id", response);
		rightInput.addClass("get_corneaAnt_priorRE");
		var leftInput = newInput.clone();
		leftInput.attr("data-corneaAntLE-id", response);
		leftInput.addClass("get_corneaAnt_priorLE");
		if(eyetype == 1) {
		    rightInput.attr("checked", "checked");
		} else if (eyetype == 2) {
		    leftInput.attr("checked", "checked");
		}
		if(eventtype != "priorClick") {
		    $("#corneatAntBefore_RE").prepend(rightInput.prop('outerHTML') + cornea_antid);
		    $("#corneaAntBefore_LE").prepend(leftInput.prop('outerHTML') + cornea_antid);
		}
	    });
	}
	
	// Updated Cornea Anterior Right Eye
	$("#get_corneaAnt_RE").on("blur", function() {
		var patientid = $(this).attr("data-patient-id");
		var corneaAntid = $(this).val();
		if(corneaAntid == "") {
			return false;
		} else {
		    getCorneaAnteriorData(patientid, corneaAntid, 1, "blur");
		}
        $('.searchCorneaAntRE').val(''); //Clear search field
		$("#get_corneaAnt_RE").focus();	//focus search filed 
	});
	
	$("#get_corneaAnt_RE").keydown(function(e) {
		if(e.originalEvent.keyCode == 13){
		var patientid = $(this).attr("data-patient-id");
		var corneaAntid = $(this).val();
		
		if(corneaAntid == "") {
			return false;
		} else {
		    getCorneaAnteriorData(patientid, corneaAntid, 1, "keyDown");
		}
		
			$('.searchCorneaAntRE').val(''); //Clear search field
			$("#get_corneaAnt_RE").focus();	//focus search filed 
			return false;
		}
		//console.log(e);
	}); 
	
	$("body").on("click", ".get_corneaAnt_priorRE", function() {
		var patientid = $(this).attr("data-patient-id");
		var corneaAntid = $(this).attr("data-corneaAntRE-id");
		var cantstatus = 0;
		if($(this).is(":checked")) {
		    cantstatus = 1;    
		}
		
		if(corneaAntid == "") {
			return false;
		} else {
		    getCorneaAnteriorData(patientid, corneaAntid, 1, "priorClick", cantstatus);
		}
	});
	
	// Updated Cornea Anterior Left Eye
	$("#get_corneaAnt_LE").on("blur", function() {
		var patientid = $(this).attr("data-patient-id");
		var corneaAntid = $(this).val();
		
		if(corneaAntid == "") {
			return false;
		} else {
		    getCorneaAnteriorData(patientid, corneaAntid, 2, "blur");
		}
		
        $('.searchCorneaAntLE').val(''); //Clear search field
		$("#get_corneaAnt_LE").focus();	//focus search filed 
	});
	
	$("#get_corneaAnt_LE").keydown(function(e) {
		if(e.originalEvent.keyCode == 13){
		var patientid = $(this).attr("data-patient-id");
		var corneaAntid = $(this).val();
		
		if(corneaAntid == "") {
			return false;
		} else {
		    getCorneaAnteriorData(patientid, corneaAntid, 2, "keyDown");
		}
		
			$('.searchCorneaAntLE').val(''); //Clear search field
			$("#get_corneaAnt_LE").focus();	//focus search filed 
			return false;
		}
		//console.log(e);
	}); 
	
	$("body").on("click", ".get_corneaAnt_priorLE", function() {
		var patientid = $(this).attr("data-patient-id");
		var corneaAntid = $(this).attr("data-corneaAntLE-id");
		var cantstatus = 0;
		if($(this).is(":checked")) {
		    cantstatus = 1;    
		}
		
		if(corneaAntid == "") {
			return false;
		} else {
		    getCorneaAnteriorData(patientid, corneaAntid, 2, "priorClick", cantstatus);
		}
	});
	/******************* EXAMINATION CORNEA ANTERIOR SECTION ENDS *********************************/
	
	/******************* EXAMINATION CORNEA POSTERIOR SECTION STARTS *********************************/
	function getCorneaPosteriorData(patientid, cornea_postid, eyetype, eventtype, corneaPoststatus=0) {
	    var url = "get_cornea_posterior_data.php?cornea_postid="+cornea_postid+"&patientid="+patientid+"&eye_type="+eyetype;
	    if(eventtype == "priorClick") {
		url = url + "&cornea_post_status=" + corneaPoststatus;
	    }
	    //console.log(url);
	    $.get(url, function(response) {
		//console.log(response);
		var newInput = $("<input type='checkbox' style='width: 20px;height: 20px; vertical-align: bottom;margin-right:5px;' class='i-checks m-l'>");
		newInput.attr("name", response);
		newInput.attr("value", response);
		newInput.attr("data-patient-id", patientid);
		var rightInput = newInput.clone();
		rightInput.attr("data-corneaPostRE-id", response);
		rightInput.addClass("get_corneaPost_priorRE");
		var leftInput = newInput.clone();
		leftInput.attr("data-corneaPostLE-id", response);
		leftInput.addClass("get_corneaPost_priorLE");
		if(eyetype == 1) {
		    rightInput.attr("checked", "checked");
		} else if (eyetype == 2) {
		    leftInput.attr("checked", "checked");
		}
		if(eventtype != "priorClick") {
		    $("#corneaPostBefore_RE").prepend(rightInput.prop('outerHTML') + cornea_postid);
		    $("#corneaPostBefore_LE").prepend(leftInput.prop('outerHTML') + cornea_postid);
		}
	    });
	}
	
	// Updated Cornea Posterior Right Eye
	$("#get_corneaPost_RE").on("blur", function() {
		var patientid = $(this).attr("data-patient-id");
		var corneaPostid = $(this).val();
		if(corneaPostid == "") {
			return false;
		} else {
		    getCorneaPosteriorData(patientid, corneaPostid, 1, "blur");
		}
        $('.searchCorneaPostRE').val(''); //Clear search field
		$("#get_corneaPost_RE").focus();	//focus search filed 
	});
	
	$("#get_corneaPost_RE").keydown(function(e) {
		if(e.originalEvent.keyCode == 13){
		var patientid = $(this).attr("data-patient-id");
		var corneaPostid = $(this).val();
		
		if(corneaPostid == "") {
			return false;
		} else {
		    getCorneaPosteriorData(patientid, corneaPostid, 1, "keyDown");
		}
		
			$('.searchCorneaPostRE').val(''); //Clear search field
			$("#get_corneaPost_RE").focus();	//focus search filed 
			return false;
		}
		//console.log(e);
	}); 
	
	$("body").on("click", ".get_corneaPost_priorRE", function() {
		var patientid = $(this).attr("data-patient-id");
		var corneaPostid = $(this).attr("data-corneaPostRE-id");
		var cpoststatus = 0;
		if($(this).is(":checked")) {
		    cpoststatus = 1;    
		}
		
		if(corneaPostid == "") {
			return false;
		} else {
		    getCorneaPosteriorData(patientid, corneaPostid, 1, "priorClick", cpoststatus);
		}
	});
	
	// Updated Cornea Posterior Left Eye
	$("#get_corneaPost_LE").on("blur", function() {
		var patientid = $(this).attr("data-patient-id");
		var corneaPostid = $(this).val();
		
		if(corneaPostid == "") {
			return false;
		} else {
		    getCorneaPosteriorData(patientid, corneaPostid, 2, "blur");
		}
		
        $('.searchCorneaPostLE').val(''); //Clear search field
		$("#get_corneaPost_LE").focus();	//focus search filed 
	});
	
	$("#get_corneaPost_LE").keydown(function(e) {
		if(e.originalEvent.keyCode == 13){
		var patientid = $(this).attr("data-patient-id");
		var corneaPostid = $(this).val();
		
		if(corneaPostid == "") {
			return false;
		} else {
		    getCorneaPosteriorData(patientid, corneaPostid, 2, "keyDown");
		}
		
			$('.searchCorneaPostLE').val(''); //Clear search field
			$("#get_corneaPost_LE").focus();	//focus search filed 
			return false;
		}
		//console.log(e);
	}); 
	
	$("body").on("click", ".get_corneaPost_priorLE", function() {
		var patientid = $(this).attr("data-patient-id");
		var corneaPostid = $(this).attr("data-corneaPostLE-id");
		var cpoststatus = 0;
		if($(this).is(":checked")) {
		    cpoststatus = 1;    
		}
		
		if(corneaPostid == "") {
			return false;
		} else {
		    getCorneaPosteriorData(patientid, corneaPostid, 2, "priorClick", cpoststatus);
		}
	});
	/******************* EXAMINATION CORNEA POSTERIOR SECTION ENDS *********************************/
	
	/******************* EXAMINATION ANTERIOR CHAMBER SECTION STARTS *********************************/
	function getChamberData(patientid, chamberid, eyetype, eventtype, chamberstatus=0) {
	    var url = "get_chamber_data.php?chamberid="+chamberid+"&patientid="+patientid+"&eye_type="+eyetype;
	    if(eventtype == "priorClick") {
		url = url + "&chamber_status=" + chamberstatus;
	    }
	    //console.log(url);
	    $.get(url, function(response) {
		//console.log(response);
		var newInput = $("<input type='checkbox' style='width: 20px;height: 20px; vertical-align: bottom;margin-right:5px;' class='i-checks m-l'>");
		newInput.attr("name", response);
		newInput.attr("value", response);
		newInput.attr("data-patient-id", patientid);
		var rightInput = newInput.clone();
		rightInput.attr("data-chamberRE-id", response);
		rightInput.addClass("get_chamber_priorRE");
		var leftInput = newInput.clone();
		leftInput.attr("data-chamberLE-id", response);
		leftInput.addClass("get_chamber_priorLE");
		if(eyetype == 1) {
		    rightInput.attr("checked", "checked");
		} else if (eyetype == 2) {
		    leftInput.attr("checked", "checked");
		}
		if(eventtype != "priorClick") {
		    $("#chamberBefore_RE").prepend(rightInput.prop('outerHTML') + chamberid);
		    $("#chamberBefore_LE").prepend(leftInput.prop('outerHTML') + chamberid);
		}
	    });
	}
	
	// Updated Anterior Chamber Right Eye
	$("#get_chamber_RE").on("blur", function() {
		var patientid = $(this).attr("data-patient-id");
		var chamberid = $(this).val();
		if(chamberid == "") {
			return false;
		} else {
		    getChamberData(patientid, chamberid, 1, "blur");
		}
        $('.searchChamberRE').val(''); //Clear search field
		$("#get_chamber_RE").focus();	//focus search filed 
	});
	
	$("#get_chamber_RE").keydown(function(e) {
		if(e.originalEvent.keyCode == 13){
		var patientid = $(this).attr("data-patient-id");
		var chamberid = $(this).val();
		
		if(chamberid == "") {
			return false;
		} else {
		    getChamberData(patientid, chamberid, 1, "keyDown");
		}
		
			$('.searchChamberRE').val(''); //Clear search field
			$("#get_chamber_RE").focus();	//focus search filed 
			return false;
		}
		//console.log(e);
	}); 
	
	$("body").on("click", ".get_chamber_priorRE", function() {
		var patientid = $(this).attr("data-patient-id");
		var chamberid = $(this).attr("data-chamberRE-id");
		var chamberstatus = 0;
		if($(this).is(":checked")) {
		    chamberstatus = 1;    
		}
		
		if(chamberid == "") {
			return false;
		} else {
		    getChamberData(patientid, chamberid, 1, "priorClick", chamberstatus);
		}
	});
	
	// Updated Anterior Chmaber Left Eye
	$("#get_chamber_LE").on("blur", function() {
		var patientid = $(this).attr("data-patient-id");
		var chamberid = $(this).val();
		
		if(chamberid == "") {
			return false;
		} else {
		    getChamberData(patientid, chamberid, 2, "blur");
		}
		
        $('.searchChamberLE').val(''); //Clear search field
		$("#get_chamber_LE").focus();	//focus search filed 
	});
	
	$("#get_chamber_LE").keydown(function(e) {
		if(e.originalEvent.keyCode == 13){
		var patientid = $(this).attr("data-patient-id");
		var chamberid = $(this).val();
		
		if(chamberid == "") {
			return false;
		} else {
		    getChamberData(patientid, chamberid, 2, "keyDown");
		}
		
			$('.searchChamberLE').val(''); //Clear search field
			$("#get_chamber_LE").focus();	//focus search filed 
			return false;
		}
		//console.log(e);
	}); 
	
	$("body").on("click", ".get_chamber_priorLE", function() {
		var patientid = $(this).attr("data-patient-id");
		var chamberid = $(this).attr("data-chamberLE-id");
		var chamberstatus = 0;
		if($(this).is(":checked")) {
		    chamberstatus = 1;    
		}
		
		if(chamberid == "") {
			return false;
		} else {
		    getChamberData(patientid, chamberid, 2, "priorClick", chamberstatus);
		}
	});
	/******************* EXAMINATION ANTERIOR CHAMBER SECTION ENDS *********************************/
	
	/******************* EXAMINATION IRIS SECTION STARTS *********************************/
	function getIrisData(patientid, irisid, eyetype, eventtype, irisstatus=0) {
	    var url = "get_iris_data.php?irisid="+irisid+"&patientid="+patientid+"&eye_type="+eyetype;
	    if(eventtype == "priorClick") {
		url = url + "&iris_status=" + irisstatus;
	    }
	    //console.log(url);
	    $.get(url, function(response) {
		//console.log(response);
		var newInput = $("<input type='checkbox' style='width: 20px;height: 20px; vertical-align: bottom;margin-right:5px;' class='i-checks m-l'>");
		newInput.attr("name", response);
		newInput.attr("value", response);
		newInput.attr("data-patient-id", patientid);
		var rightInput = newInput.clone();
		rightInput.attr("data-irisRE-id", response);
		rightInput.addClass("get_iris_priorRE");
		var leftInput = newInput.clone();
		leftInput.attr("data-irisLE-id", response);
		leftInput.addClass("get_iris_priorLE");
		if(eyetype == 1) {
		    rightInput.attr("checked", "checked");
		} else if (eyetype == 2) {
		    leftInput.attr("checked", "checked");
		}
		if(eventtype != "priorClick") {
		    $("#irisBefore_RE").prepend(rightInput.prop('outerHTML') + irisid);
		    $("#irisBefore_LE").prepend(leftInput.prop('outerHTML') + irisid);
		}
	    });
	}
	
	// Updated Iris Right Eye
	$("#get_iris_RE").on("blur", function() {
		var patientid = $(this).attr("data-patient-id");
		var irisid = $(this).val();
		if(irisid == "") {
			return false;
		} else {
		    getIrisData(patientid, irisid, 1, "blur");
		}
        $('.searchIrisRE').val(''); //Clear search field
		$("#get_iris_RE").focus();	//focus search filed 
	});
	
	$("#get_iris_RE").keydown(function(e) {
		if(e.originalEvent.keyCode == 13){
		var patientid = $(this).attr("data-patient-id");
		var irisid = $(this).val();
		
		if(irisid == "") {
			return false;
		} else {
		    getIrisData(patientid, irisid, 1, "keyDown");
		}
		
			$('.searchIrisRE').val(''); //Clear search field
			$("#get_iris_RE").focus();	//focus search filed 
			return false;
		}
		//console.log(e);
	}); 
	
	$("body").on("click", ".get_iris_priorRE", function() {
		var patientid = $(this).attr("data-patient-id");
		var irisid = $(this).attr("data-irisRE-id");
		var irisstatus = 0;
		if($(this).is(":checked")) {
		    irisstatus = 1;    
		}
		
		if(irisid == "") {
			return false;
		} else {
		    getIrisData(patientid, irisid, 1, "priorClick", irisstatus);
		}
	});
	
	// Updated Iris Left Eye
	$("#get_iris_LE").on("blur", function() {
		var patientid = $(this).attr("data-patient-id");
		var irisid = $(this).val();
		
		if(irisid == "") {
			return false;
		} else {
		    getIrisData(patientid, irisid, 2, "blur");
		}
		
        $('.searchIrisLE').val(''); //Clear search field
		$("#get_iris_LE").focus();	//focus search filed 
	});
	
	$("#get_iris_LE").keydown(function(e) {
		if(e.originalEvent.keyCode == 13){
		var patientid = $(this).attr("data-patient-id");
		var irisid = $(this).val();
		
		if(irisid == "") {
			return false;
		} else {
		    getIrisData(patientid, irisid, 2, "keyDown");
		}
		
			$('.searchIrisLE').val(''); //Clear search field
			$("#get_iris_LE").focus();	//focus search filed 
			return false;
		}
		//console.log(e);
	}); 
	
	$("body").on("click", ".get_iris_priorLE", function() {
		var patientid = $(this).attr("data-patient-id");
		var irisid = $(this).attr("data-irisLE-id");
		var irisstatus = 0;
		if($(this).is(":checked")) {
		    irisstatus = 1;    
		}
		
		if(irisid == "") {
			return false;
		} else {
		    getIrisData(patientid, irisid, 2, "priorClick", irisstatus);
		}
	});
	/******************* EXAMINATION IRIS SECTION ENDS *********************************/
	
	/******************* EXAMINATION PUPIL SECTION STARTS *********************************/
	function getPupilData(patientid, pupilid, eyetype, eventtype, pupilstatus=0) {
	    var url = "get_pupil_data.php?pupilid="+pupilid+"&patientid="+patientid+"&eye_type="+eyetype;
	    if(eventtype == "priorClick") {
		url = url + "&pupil_status=" + pupilstatus;
	    }
	    //console.log(url);
	    $.get(url, function(response) {
		//console.log(response);
		var newInput = $("<input type='checkbox' style='width: 20px;height: 20px; vertical-align: bottom;margin-right:5px;' class='i-checks m-l'>");
		newInput.attr("name", response);
		newInput.attr("value", response);
		newInput.attr("data-patient-id", patientid);
		var rightInput = newInput.clone();
		rightInput.attr("data-pupilRE-id", response);
		rightInput.addClass("get_pupil_priorRE");
		var leftInput = newInput.clone();
		leftInput.attr("data-pupilLE-id", response);
		leftInput.addClass("get_pupil_priorLE");
		if(eyetype == 1) {
		    rightInput.attr("checked", "checked");
		} else if (eyetype == 2) {
		    leftInput.attr("checked", "checked");
		}
		if(eventtype != "priorClick") {
		    $("#pupilBefore_RE").prepend(rightInput.prop('outerHTML') + pupilid);
		    $("#pupilBefore_LE").prepend(leftInput.prop('outerHTML') + pupilid);
		}
	    });
	}
	
	// Updated Pupil Right Eye 
	$("#get_pupil_RE").on("blur", function() {
		var patientid = $(this).attr("data-patient-id");
		var pupilid = $(this).val();
		if(pupilid == "") {
			return false;
		} else {
		    getPupilData(patientid, pupilid, 1, "blur");
		}
        $('.searchPupilRE').val(''); //Clear search field
		$("#get_pupil_RE").focus();	//focus search filed 
	});
	
	$("#get_pupil_RE").keydown(function(e) {
		if(e.originalEvent.keyCode == 13){
		var patientid = $(this).attr("data-patient-id");
		var pupilid = $(this).val();
		
		if(pupilid == "") {
			return false;
		} else {
		    getPupilData(patientid, pupilid, 1, "keyDown");
		}
		
			$('.searchPupilRE').val(''); //Clear search field
			$("#get_pupil_RE").focus();	//focus search filed 
			return false;
		}
		//console.log(e);
	}); 
	
	$("body").on("click", ".get_pupil_priorRE", function() {
		var patientid = $(this).attr("data-patient-id");
		var pupilid = $(this).attr("data-pupilRE-id");
		var pupilstatus = 0;
		if($(this).is(":checked")) {
		    pupilstatus = 1;    
		}
		
		if(pupilid == "") {
			return false;
		} else {
		    getPupilData(patientid, pupilid, 1, "priorClick", pupilstatus);
		}
	});
	
	// Updated Pupil Left Eye
	$("#get_pupil_LE").on("blur", function() {
		var patientid = $(this).attr("data-patient-id");
		var pupilid = $(this).val();
		
		if(pupilid == "") {
			return false;
		} else {
		    getPupilData(patientid, pupilid, 2, "blur");
		}
		
        $('.searchPupilLE').val(''); //Clear search field
		$("#get_pupil_LE").focus();	//focus search filed 
	});
	
	$("#get_pupil_LE").keydown(function(e) {
		if(e.originalEvent.keyCode == 13){
		var patientid = $(this).attr("data-patient-id");
		var pupilid = $(this).val();
		
		if(pupilid == "") {
			return false;
		} else {
		    getPupilData(patientid, pupilid, 2, "keyDown");
		}
		
			$('.searchPupilLE').val(''); //Clear search field
			$("#get_pupil_LE").focus();	//focus search filed 
			return false;
		}
		//console.log(e);
	}); 
	
	$("body").on("click", ".get_pupil_priorLE", function() {
		var patientid = $(this).attr("data-patient-id");
		var pupilid = $(this).attr("data-pupilLE-id");
		var pupilstatus = 0;
		if($(this).is(":checked")) {
		    pupilstatus = 1;    
		}
		
		if(pupilid == "") {
			return false;
		} else {
		    getPupilData(patientid, pupilid, 2, "priorClick", pupilstatus);
		}
	});
	/******************* EXAMINATION PUPIL SECTION ENDS *********************************/
	
	/******************* EXAMINATION ANGLE OF ANTERIOR CHAMBER SECTION STARTS *********************************/
	function getAngleData(patientid, angleid, eyetype, eventtype, anglestatus=0) {
	    var url = "get_angle_data.php?angleid="+angleid+"&patientid="+patientid+"&eye_type="+eyetype;
	    if(eventtype == "priorClick") {
		url = url + "&angle_status=" + anglestatus;
	    }
	    //console.log(url);
	    $.get(url, function(response) {
		//console.log(response);
		var newInput = $("<input type='checkbox' style='width: 20px;height: 20px; vertical-align: bottom;margin-right:5px;' class='i-checks m-l'>");
		newInput.attr("name", response);
		newInput.attr("value", response);
		newInput.attr("data-patient-id", patientid);
		var rightInput = newInput.clone();
		rightInput.attr("data-angleRE-id", response);
		rightInput.addClass("get_angle_priorRE");
		var leftInput = newInput.clone();
		leftInput.attr("data-angleLE-id", response);
		leftInput.addClass("get_angle_priorLE");
		if(eyetype == 1) {
		    rightInput.attr("checked", "checked");
		} else if (eyetype == 2) {
		    leftInput.attr("checked", "checked");
		}
		if(eventtype != "priorClick") {
		    $("#angleBefore_RE").prepend(rightInput.prop('outerHTML') + angleid);
		    $("#angleBefore_LE").prepend(leftInput.prop('outerHTML') + angleid);
		}
	    });
	}
	
	// Updated Angle of Anterior Chamber Right Eye
	$("#get_angle_RE").on("blur", function() {
		var patientid = $(this).attr("data-patient-id");
		var angleid = $(this).val();
		if(angleid == "") {
			return false;
		} else {
		    getAngleData(patientid, angleid, 1, "blur");
		}
        $('.searchAngleRE').val(''); //Clear search field
		$("#get_angle_RE").focus();	//focus search filed 
	});
	
	$("#get_angle_RE").keydown(function(e) {
		if(e.originalEvent.keyCode == 13){
		var patientid = $(this).attr("data-patient-id");
		var angleid = $(this).val();
		
		if(angleid == "") {
			return false;
		} else {
		    getAngleData(patientid, angleid, 1, "keyDown");
		}
		
			$('.searchAngleRE').val(''); //Clear search field
			$("#get_angle_RE").focus();	//focus search filed 
			return false;
		}
		//console.log(e);
	});
	
	$("body").on("click", ".get_angle_priorRE", function() {
		var patientid = $(this).attr("data-patient-id");
		var angleid = $(this).attr("data-angleRE-id");
		var anglestatus = 0;
		if($(this).is(":checked")) {
		    anglestatus = 1;    
		}
		
		if(angleid == "") {
			return false;
		} else {
		    getAngleData(patientid, angleid, 1, "priorClick", anglestatus);
		}
	});
	
	// Updated Angle of Anterior Chamber Left Eye
	$("#get_angle_LE").on("blur", function() {
		var patientid = $(this).attr("data-patient-id");
		var angleid = $(this).val();
		
		if(angleid == "") {
			return false;
		} else {
		    getAngleData(patientid, angleid, 2, "blur");
		}
		
        $('.searchAngleLE').val(''); //Clear search field
		$("#get_angle_LE").focus();	//focus search filed 
	});
	
	$("#get_angle_LE").keydown(function(e) {
		if(e.originalEvent.keyCode == 13){
		var patientid = $(this).attr("data-patient-id");
		var angleid = $(this).val();
		
		if(angleid == "") {
			return false;
		} else {
		    getAngleData(patientid, angleid, 2, "keyDown");
		}
		
			$('.searchAngleLE').val(''); //Clear search field
			$("#get_angle_LE").focus();	//focus search filed 
			return false;
		}
		//console.log(e);
	}); 
	
	$("body").on("click", ".get_angle_priorLE", function() {
		var patientid = $(this).attr("data-patient-id");
		var angleid = $(this).attr("data-angleLE-id");
		var anglestatus = 0;
		if($(this).is(":checked")) {
		    anglestatus = 1;    
		}
		
		if(angleid == "") {
			return false;
		} else {
		    getAngleData(patientid, angleid, 2, "priorClick", anglestatus);
		}
	});
	/******************* EXAMINATION ANGLE OF ANTERIOR CHAMBER SECTION ENDS *********************************/
	
	/******************* EXAMINATION LENS SECTION STARTS *********************************/
	function getLensData(patientid, lensid, eyetype, eventtype, lensstatus=0) {
	    var url = "get_lens_data.php?lensid="+lensid+"&patientid="+patientid+"&eye_type="+eyetype;
	    if(eventtype == "priorClick") {
		url = url + "&lens_status=" + lensstatus;
	    }
	    //console.log(url);
	    $.get(url, function(response) {
		//console.log(response);
		var newInput = $("<input type='checkbox' style='width: 20px;height: 20px; vertical-align: bottom;margin-right:5px;' class='i-checks m-l'>");
		newInput.attr("name", response);
		newInput.attr("value", response);
		newInput.attr("data-patient-id", patientid);
		var rightInput = newInput.clone();
		rightInput.attr("data-lensRE-id", response);
		rightInput.addClass("get_lens_priorRE");
		var leftInput = newInput.clone();
		leftInput.attr("data-lensLE-id", response);
		leftInput.addClass("get_lens_priorLE");
		if(eyetype == 1) {
		    rightInput.attr("checked", "checked");
		} else if (eyetype == 2) {
		    leftInput.attr("checked", "checked");
		}
		if(eventtype != "priorClick") {
		    $("#lensBefore_RE").prepend(rightInput.prop('outerHTML') + lensid);
		    $("#lensBefore_LE").prepend(leftInput.prop('outerHTML') + lensid);
		}
	    });
	}
	
	// Updated Lens Right Eye
	$("#get_lens_RE").on("blur", function() {
		var patientid = $(this).attr("data-patient-id");
		var lensid = $(this).val();
		if(lensid == "") {
			return false;
		} else {
		    getLensData(patientid, lensid, 1, "blur");
		}
        $('.searchLensRE').val(''); //Clear search field
		$("#get_lens_RE").focus();	//focus search filed 
	});
	
	$("#get_lens_RE").keydown(function(e) {
		if(e.originalEvent.keyCode == 13){
		var patientid = $(this).attr("data-patient-id");
		var lensid = $(this).val();
		
		if(lensid == "") {
			return false;
		} else {
		    getLensData(patientid, lensid, 1, "keyDown");
		}
		
			$('.searchLensRE').val(''); //Clear search field
			$("#get_lens_RE").focus();	//focus search filed 
			return false;
		}
		//console.log(e);
	});

	$("body").on("click", ".get_lens_priorRE", function() {
		var patientid = $(this).attr("data-patient-id");
		var lensid = $(this).attr("data-lensRE-id");
		var lensstatus = 0;
		if($(this).is(":checked")) {
		    lensstatus = 1;    
		}
		
		if(lensid == "") {
			return false;
		} else {
		    getLensData(patientid, lensid, 1, "priorClick", lensstatus);
		}
	});
	
	// Updated Lens Left Eye
	$("#get_lens_LE").on("blur", function() {
		var patientid = $(this).attr("data-patient-id");
		var lensid = $(this).val();
		
		if(lensid == "") {
			return false;
		} else {
		    getLensData(patientid, lensid, 2, "blur");
		}
		
        $('.searchLensLE').val(''); //Clear search field
		$("#get_lens_LE").focus();	//focus search filed 
	});
	
	$("#get_lens_LE").keydown(function(e) {
		if(e.originalEvent.keyCode == 13){
		var patientid = $(this).attr("data-patient-id");
		var lensid = $(this).val();
		
		if(lensid == "") {
			return false;
		} else {
		    getLensData(patientid, lensid, 2, "keyDown");
		}
		
			$('.searchLensLE').val(''); //Clear search field
			$("#get_lens_LE").focus();	//focus search filed 
			return false;
		}
		//console.log(e);
	}); 
	
	$("body").on("click", ".get_lens_priorLE", function() {
		var patientid = $(this).attr("data-patient-id");
		var lensid = $(this).attr("data-lensLE-id");
		var lensstatus = 0;
		if($(this).is(":checked")) {
		    lensstatus = 1;    
		}
		
		if(lensid == "") {
			return false;
		} else {
		    getLensData(patientid, lensid, 2, "priorClick", lensstatus);
		}
	});
	/******************* EXAMINATION LENS SECTION ENDS *********************************/
	
	/******************* EXAMINATION VITEROUS SECTION STARTS *********************************/
	function getViterousData(patientid, viterousid, eyetype, eventtype, viterousstatus=0) {
	    var url = "get_viterous_data.php?viterousid="+viterousid+"&patientid="+patientid+"&eye_type="+eyetype;
	    if(eventtype == "priorClick") {
		url = url + "&viterous_status=" + viterousstatus;
	    }
	    //console.log(url);
	    $.get(url, function(response) {
		//console.log(response);
		var newInput = $("<input type='checkbox' style='width: 20px;height: 20px; vertical-align: bottom;margin-right:5px;' class='i-checks m-l'>");
		newInput.attr("name", response);
		newInput.attr("value", response);
		newInput.attr("data-patient-id", patientid);
		var rightInput = newInput.clone();
		rightInput.attr("data-viterousRE-id", response);
		rightInput.addClass("get_viterous_priorRE");
		var leftInput = newInput.clone();
		leftInput.attr("data-viterousLE-id", response);
		leftInput.addClass("get_viterous_priorLE");
		if(eyetype == 1) {
		    rightInput.attr("checked", "checked");
		} else if (eyetype == 2) {
		    leftInput.attr("checked", "checked");
		}
		if(eventtype != "priorClick") {
		    $("#viterousBefore_RE").prepend(rightInput.prop('outerHTML') + viterousid);
		    $("#viterousBefore_LE").prepend(leftInput.prop('outerHTML') + viterousid);
		}
	    });
	}
	
	// Updated Viterous Right Eye 
	$("#get_viterous_RE").on("blur", function() {
		var patientid = $(this).attr("data-patient-id");
		var viterousid = $(this).val();
		if(viterousid == "") {
			return false;
		} else {
		    getViterousData(patientid, viterousid, 1, "blur");
		}
        $('.searchViterousRE').val(''); //Clear search field
		$("#get_viterous_RE").focus();	//focus search filed 
	});
	
	$("#get_viterous_RE").keydown(function(e) {
		if(e.originalEvent.keyCode == 13){
		var patientid = $(this).attr("data-patient-id");
		var viterousid = $(this).val();
		
		if(viterousid == "") {
			return false;
		} else {
		    getViterousData(patientid, viterousid, 1, "keyDown");
		}
		
			$('.searchViterousRE').val(''); //Clear search field
			$("#get_viterous_RE").focus();	//focus search filed 
			return false;
		}
		//console.log(e);
	}); 
	
	$("body").on("click", ".get_viterous_priorRE", function() {
		var patientid = $(this).attr("data-patient-id");
		var viterousid = $(this).attr("data-viterousRE-id");
		var viterousstatus = 0;
		if($(this).is(":checked")) {
		    viterousstatus = 1;    
		}
		
		if(viterousid == "") {
			return false;
		} else {
		    getViterousData(patientid, viterousid, 1, "priorClick", viterousstatus);
		}
	});
	
	// Updated Viterous Left Eye
	$("#get_viterous_LE").on("blur", function() {
		var patientid = $(this).attr("data-patient-id");
		var viterousid = $(this).val();
		
		if(viterousid == "") {
			return false;
		} else {
		    getViterousData(patientid, viterousid, 2, "blur");
		}
		
        $('.searchViterousLE').val(''); //Clear search field
		$("#get_viterous_LE").focus();	//focus search filed 
	});
	
	$("#get_viterous_LE").keydown(function(e) {
		if(e.originalEvent.keyCode == 13){
		var patientid = $(this).attr("data-patient-id");
		var viterousid = $(this).val();
		
		if(viterousid == "") {
			return false;
		} else {
		    getViterousData(patientid, viterousid, 2, "keyDown");
		}
		
			$('.searchViterousLE').val(''); //Clear search field
			$("#get_viterous_LE").focus();	//focus search filed 
			return false;
		}
		//console.log(e);
	}); 
	
	$("body").on("click", ".get_viterous_priorLE", function() {
		var patientid = $(this).attr("data-patient-id");
		var viterousid = $(this).attr("data-viterousLE-id");
		var viterousstatus = 0;
		if($(this).is(":checked")) {
		    viterousstatus = 1;    
		}
		
		if(viterousid == "") {
			return false;
		} else {
		    getViterousData(patientid, viterousid, 2, "priorClick", viterousstatus);
		}
	});
	/******************* EXAMINATION VITEROUS SECTION ENDS *********************************/
	
	/******************* EXAMINATION FUNDUS SECTION STARTS *********************************/
	function getFundusData(patientid, fundusid, eyetype, eventtype, fundusstatus=0) {
	    var url = "get_fundus_data.php?fundusid="+fundusid+"&patientid="+patientid+"&eye_type="+eyetype;
	    if(eventtype == "priorClick") {
		url = url + "&fundus_status=" + fundusstatus;
	    }
	    //console.log(url);
	    $.get(url, function(response) {
		//console.log(response);
		var newInput = $("<input type='checkbox' style='width: 20px;height: 20px; vertical-align: bottom;margin-right:5px;' class='i-checks m-l'>");
		newInput.attr("name", response);
		newInput.attr("value", response);
		newInput.attr("data-patient-id", patientid);
		var rightInput = newInput.clone();
		rightInput.attr("data-fundusRE-id", response);
		rightInput.addClass("get_fundus_priorRE");
		var leftInput = newInput.clone();
		leftInput.attr("data-fundusLE-id", response);
		leftInput.addClass("get_fundus_priorLE");
		if(eyetype == 1) {
		    rightInput.attr("checked", "checked");
		} else if (eyetype == 2) {
		    leftInput.attr("checked", "checked");
		}
		if(eventtype != "priorClick") {
		    $("#fundusBefore_RE").prepend(rightInput.prop('outerHTML') + fundusid);
		    $("#fundusBefore_LE").prepend(leftInput.prop('outerHTML') + fundusid);
		}
	    });
	}
	
	// Updated Fundus Right Eye 
	$("#get_fundus_RE").on("blur", function() {
		var patientid = $(this).attr("data-patient-id");
		var fundusid = $(this).val();
		if(fundusid == "") {
			return false;
		} else {
		    getFundusData(patientid, fundusid, 1, "blur");
		}
        $('.searchFundusRE').val(''); //Clear search field
		$("#get_fundus_RE").focus();	//focus search filed 
	});
	
	$("#get_fundus_RE").keydown(function(e) {
		if(e.originalEvent.keyCode == 13){
		var patientid = $(this).attr("data-patient-id");
		var fundusid = $(this).val();
		
		if(fundusid == "") {
			return false;
		} else {
		    getFundusData(patientid, fundusid, 1, "keyDown");
		}
		
			$('.searchFundusRE').val(''); //Clear search field
			$("#get_fundus_RE").focus();	//focus search filed 
			return false;
		}
		//console.log(e);
	});
	
	$("body").on("click", ".get_fundus_priorRE", function() {
		var patientid = $(this).attr("data-patient-id");
		var fundusid = $(this).attr("data-fundusRE-id");
		var fundusstatus = 0;
		if($(this).is(":checked")) {
		    fundusstatus = 1;    
		}
		
		if(fundusid == "") {
			return false;
		} else {
		    getFundusData(patientid, fundusid, 1, "priorClick", fundusstatus);
		}
	});
	
	// Updated Fundus Left Eye
	$("#get_fundus_LE").on("blur", function() {
		var patientid = $(this).attr("data-patient-id");
		var fundusid = $(this).val();
		
		if(fundusid == "") {
			return false;
		} else {
		    getFundusData(patientid, fundusid, 2, "blur");
		}
		
        $('.searchFundusLE').val(''); //Clear search field
		$("#get_fundus_LE").focus();	//focus search filed 
	});
	
	$("#get_fundus_LE").keydown(function(e) {
		if(e.originalEvent.keyCode == 13){
		var patientid = $(this).attr("data-patient-id");
		var fundusid = $(this).val();
		
		if(fundusid == "") {
			return false;
		} else {
		    getFundusData(patientid, fundusid, 2, "keyDown");
		}
		
			$('.searchFundusLE').val(''); //Clear search field
			$("#get_fundus_LE").focus();	//focus search filed 
			return false;
		}
		//console.log(e);
	}); 
	
	$("body").on("click", ".get_fundus_priorLE", function() {
		var patientid = $(this).attr("data-patient-id");
		var fundusid = $(this).attr("data-fundusLE-id");
		var fundusstatus = 0;
		if($(this).is(":checked")) {
		    fundusstatus = 1;    
		}
		
		if(fundusid == "") {
			return false;
		} else {
		    getFundusData(patientid, fundusid, 2, "priorClick", fundusstatus);
		}
	});
	/******************* EXAMINATION FUNDUS SECTION ENDS *********************************/
	
	//*******************Start Present Spectacle Prescription***********************
	$("body").on("blur", ".DvSpeherRE_Present", function() {
		var spectacle_id = $(this).attr("data-spectacle-id");
		var DvSpeherRE_Present = $(this).val();

		var url = hostUrl+"Ophthal-EMR/my_patient_profile_ophthal_save.php?updateSpectacle=1&spectacle_id="+spectacle_id+"&DvSpeherRE_Present="+DvSpeherRE_Present;
		console.log(spectacle_id,url);
	
		//console.log(tradename,url);
		//$(this).parent("span").remove();
		if(spectacle_id == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	$("body").on("blur", ".DvCylRE_Present", function() {
		var spectacle_id = $(this).attr("data-spectacle-id");
		var DvCylRE_Present = $(this).val();

		var url = hostUrl+"Ophthal-EMR/my_patient_profile_ophthal_save.php?updateSpectacle=1&spectacle_id="+spectacle_id+"&DvCylRE_Present="+DvCylRE_Present;
		console.log(spectacle_id,url);
	
		//console.log(tradename,url);
		//$(this).parent("span").remove();
		if(spectacle_id == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	$("body").on("blur", ".DvAxisRE_Present", function() {
		var spectacle_id = $(this).attr("data-spectacle-id");
		var DvAxisRE_Present = $(this).val();

		var url = hostUrl+"Ophthal-EMR/my_patient_profile_ophthal_save.php?updateSpectacle=1&spectacle_id="+spectacle_id+"&DvAxisRE_Present="+DvAxisRE_Present;
		console.log(spectacle_id,url);
	
		//console.log(tradename,url);
		//$(this).parent("span").remove();
		if(spectacle_id == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	$("body").on("blur", ".DvSpeherLE_Present", function() {
		var spectacle_id = $(this).attr("data-spectacle-id");
		var DvSpeherLE_Present = $(this).val();

		var url = hostUrl+"Ophthal-EMR/my_patient_profile_ophthal_save.php?updateSpectacle=1&spectacle_id="+spectacle_id+"&DvSpeherLE_Present="+DvSpeherLE_Present;
		console.log(spectacle_id,url);
	
		//console.log(tradename,url);
		//$(this).parent("span").remove();
		if(spectacle_id == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	$("body").on("blur", ".DvCylLE_Present", function() {
		var spectacle_id = $(this).attr("data-spectacle-id");
		var DvCylLE_Present = $(this).val();

		var url = hostUrl+"Ophthal-EMR/my_patient_profile_ophthal_save.php?updateSpectacle=1&spectacle_id="+spectacle_id+"&DvCylLE_Present="+DvCylLE_Present;
		console.log(spectacle_id,url);
	
		//console.log(tradename,url);
		//$(this).parent("span").remove();
		if(spectacle_id == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	$("body").on("blur", ".DvAxisLE_Present", function() {
		var spectacle_id = $(this).attr("data-spectacle-id");
		var DvAxisLE_Present = $(this).val();

		var url = hostUrl+"Ophthal-EMR/my_patient_profile_ophthal_save.php?updateSpectacle=1&spectacle_id="+spectacle_id+"&DvAxisLE_Present="+DvAxisLE_Present;
		console.log(spectacle_id,url);
	
		//console.log(tradename,url);
		//$(this).parent("span").remove();
		if(spectacle_id == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	
	$("body").on("blur", ".DvVisionRE_Present", function() {
		var spectacle_id = $(this).attr("data-spectacle-id");
		var DvVisionRE_Present = $(this).val();

		var url = hostUrl+"Ophthal-EMR/my_patient_profile_ophthal_save.php?updateSpectacle=1&spectacle_id="+spectacle_id+"&DvVisionRE_Present="+DvVisionRE_Present;
		console.log(spectacle_id,url);
	
		//console.log(tradename,url);
		//$(this).parent("span").remove();
		if(spectacle_id == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	$("body").on("blur", ".NvSpeherRE_Present", function() {
		var spectacle_id = $(this).attr("data-spectacle-id");
		var NvSpeherRE_Present = $(this).val();

		var url = hostUrl+"Ophthal-EMR/my_patient_profile_ophthal_save.php?updateSpectacle=1&spectacle_id="+spectacle_id+"&NvSpeherRE_Present="+NvSpeherRE_Present;
		console.log(spectacle_id,url);
	
		//console.log(tradename,url);
		//$(this).parent("span").remove();
		if(spectacle_id == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	$("body").on("blur", ".NvVisionRE_Present", function() {
		var spectacle_id = $(this).attr("data-spectacle-id");
		var NvVisionRE_Present = $(this).val();

		var url = hostUrl+"Ophthal-EMR/my_patient_profile_ophthal_save.php?updateSpectacle=1&spectacle_id="+spectacle_id+"&NvVisionRE_Present="+NvVisionRE_Present;
		console.log(spectacle_id,url);
	
		//console.log(tradename,url);
		//$(this).parent("span").remove();
		if(spectacle_id == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	
	$("body").on("blur", ".DvVisionLE_Present", function() {
		var spectacle_id = $(this).attr("data-spectacle-id");
		var DvVisionLE_Present = $(this).val();

		var url = hostUrl+"Ophthal-EMR/my_patient_profile_ophthal_save.php?updateSpectacle=1&spectacle_id="+spectacle_id+"&DvVisionLE_Present="+DvVisionLE_Present;
		console.log(spectacle_id,url);
	
		//console.log(tradename,url);
		//$(this).parent("span").remove();
		if(spectacle_id == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	
	$("body").on("blur", ".NvVisionLE_Present", function() {
		var spectacle_id = $(this).attr("data-spectacle-id");
		var NvVisionLE_Present = $(this).val();

		var url = hostUrl+"Ophthal-EMR/my_patient_profile_ophthal_save.php?updateSpectacle=1&spectacle_id="+spectacle_id+"&NvVisionLE_Present="+NvVisionLE_Present;
		console.log(spectacle_id,url);
	
		//console.log(tradename,url);
		//$(this).parent("span").remove();
		if(spectacle_id == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	
	$("body").on("blur", ".NvCylRE_Present", function() {
		var spectacle_id = $(this).attr("data-spectacle-id");
		var NvCylRE_Present = $(this).val();

		var url = hostUrl+"Ophthal-EMR/my_patient_profile_ophthal_save.php?updateSpectacle=1&spectacle_id="+spectacle_id+"&NvCylRE_Present="+NvCylRE_Present;
		console.log(spectacle_id,url);
	
		//console.log(tradename,url);
		//$(this).parent("span").remove();
		if(spectacle_id == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	$("body").on("blur", ".NvAxisRE_Present", function() {
		var spectacle_id = $(this).attr("data-spectacle-id");
		var NvAxisRE_Present = $(this).val();

		var url = hostUrl+"Ophthal-EMR/my_patient_profile_ophthal_save.php?updateSpectacle=1&spectacle_id="+spectacle_id+"&NvAxisRE_Present="+NvAxisRE_Present;
		console.log(spectacle_id,url);
	
		//console.log(tradename,url);
		//$(this).parent("span").remove();
		if(spectacle_id == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	$("body").on("blur", ".NvSpeherLE_Present", function() {
		var spectacle_id = $(this).attr("data-spectacle-id");
		var NvSpeherLE_Present = $(this).val();

		var url = hostUrl+"Ophthal-EMR/my_patient_profile_ophthal_save.php?updateSpectacle=1&spectacle_id="+spectacle_id+"&NvSpeherLE_Present="+NvSpeherLE_Present;
		console.log(spectacle_id,url);
	
		//console.log(tradename,url);
		//$(this).parent("span").remove();
		if(spectacle_id == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	$("body").on("blur", ".NvCylLE_Present", function() {
		var spectacle_id = $(this).attr("data-spectacle-id");
		var NvCylLE_Present = $(this).val();

		var url = hostUrl+"Ophthal-EMR/my_patient_profile_ophthal_save.php?updateSpectacle=1&spectacle_id="+spectacle_id+"&NvCylLE_Present="+NvCylLE_Present;
		console.log(spectacle_id,url);
	
		//console.log(tradename,url);
		//$(this).parent("span").remove();
		if(spectacle_id == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	$("body").on("blur", ".NvAxisLE_Present", function() {
		var spectacle_id = $(this).attr("data-spectacle-id");
		var NvAxisLE_Present = $(this).val();

		var url = hostUrl+"Ophthal-EMR/my_patient_profile_ophthal_save.php?updateSpectacle=1&spectacle_id="+spectacle_id+"&NvAxisLE_Present="+NvAxisLE_Present;
		console.log(spectacle_id,url);
	
		//console.log(tradename,url);
		//$(this).parent("span").remove();
		if(spectacle_id == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	$("body").on("blur", ".IpdRE_Present", function() {
		var spectacle_id = $(this).attr("data-spectacle-id");
		var IpdRE_Present = $(this).val();

		var url = hostUrl+"Ophthal-EMR/my_patient_profile_ophthal_save.php?updateSpectacle=1&spectacle_id="+spectacle_id+"&IpdRE_Present="+IpdRE_Present;
		console.log(spectacle_id,url);
	
		//console.log(tradename,url);
		//$(this).parent("span").remove();
		if(spectacle_id == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	$("body").on("blur", ".IpdLE_Present", function() {
		var spectacle_id = $(this).attr("data-spectacle-id");
		var IpdLE_Present = $(this).val();

		var url = hostUrl+"Ophthal-EMR/my_patient_profile_ophthal_save.php?updateSpectacle=1&spectacle_id="+spectacle_id+"&IpdLE_Present="+IpdLE_Present;
		console.log(spectacle_id,url);
	
		//console.log(tradename,url);
		//$(this).parent("span").remove();
		if(spectacle_id == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
				
				
				
			});
		}
	});
	//***************End Present Spectacle Prescription******************** 
						
	//******************************** OPTHAL EMR SCRIPTS ENDS HERE ***************************************					
});