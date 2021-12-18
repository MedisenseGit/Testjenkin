$(document).ready(function() {
	var s=window.location.href;
	var n = s.indexOf('Patient-Profile-Details?');
	var m = s.indexOf('Quick-Prescription?');
	s = s.substring(0, n != -1 ? n : s.length);
	s = s.substring(0, m != -1 ? m : s.length);
	var hostUrl = s;
	console.log(hostUrl);
	//$("#afterTempEdit").hide();

	//Live update patient medical history section start here
	
	$("body").on("click", ".hyperCondition", function() {
		
		var pathyper = $(this).val();	
		var patientid = $(this).attr("data-patient-id");
		var url = hostUrl+"patient_page_add_details.php?updatecon=1&patientid="+patientid+"&pathyper="+pathyper;
		//console.log(pathyper,url);
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
		var url = hostUrl+"patient_page_add_details.php?updatecon=1&patientid="+patientid+"&patsmoke="+patsmoke;
		//console.log(patsmoke,url);
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
		var url = hostUrl+"patient_page_add_details.php?updatecon=1&patientid="+patientid+"&patdiabetes="+patdiabetes;
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
		var url = hostUrl+"patient_page_add_details.php?updatecon=1&patientid="+patientid+"&patalcohol="+patalcohol;
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
		var url = hostUrl+"patient_page_add_details.php?updatecon=1&patientid="+patientid+"&previntervent="+previntervent;
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
		var url = hostUrl+"patient_page_add_details.php?updatecon=1&patientid="+patientid+"&otherdetail="+otherdetail;
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
		var url = hostUrl+"patient_page_add_details.php?updatecon=1&patientid="+patientid+"&neuroissue="+neuroissue;
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
		var url = hostUrl+"patient_page_add_details.php?updatecon=1&patientid="+patientid+"&kedneyissue="+kedneyissue;
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

	$("body").on("change", ".pat_blood_type", function() {
		
		var pat_blood = $(this).val();
		var patientid = $(this).attr("data-patient-id");
		var url = hostUrl+"patient_page_add_details.php?updatecon=1&patientid="+patientid+"&pat_blood="+pat_blood;
		//console.log("Blood type",pat_blood);
		//$(this).parent("span").remove();
		
		if(pat_blood == "") {
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
                toastr.success('', 'Patient blood type updated successfully');

            }, 100);
				
			});
		}
	});

	$("body").on("click", ".bpCondition", function() {
		
		var pat_bp = $(this).val();	
		var patientid = $(this).attr("data-patient-id");
		var url = hostUrl+"patient_page_add_details.php?updatecon=1&patientid="+patientid+"&pat_bp="+pat_bp;
		
		if(pat_bp == "") {
			return false;
		} else {
			$.get(url, function(response) {
				setTimeout(function() {
					toastr.options = {
						closeButton: true,
						progressBar: true,
						showMethod: 'slideDown',
						timeOut: 1500
					};
					toastr.success('', 'BP Updated Successfully');					
				}, 100);
				
			});
		}
	});
	
	$("body").on("click", ".thyroidCondition", function() {
		
		var pat_thyroid = $(this).val();
		var patientid = $(this).attr("data-patient-id");
		var url = hostUrl+"patient_page_add_details.php?updatecon=1&patientid="+patientid+"&pat_thyroid="+pat_thyroid;
		
		if(pat_thyroid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				setTimeout(function() {
					toastr.options = {
						closeButton: true,
						progressBar: true,
						showMethod: 'slideDown',
						timeOut: 1500
					};
					toastr.success('', 'Thyroid Condition Updated Successfully');			
				}, 100);
				
			});
		}
	});

	$("body").on("click", ".asthamaCondition", function() {
		
		var pat_asthama = $(this).val();
		var patientid = $(this).attr("data-patient-id");
		var url = hostUrl+"patient_page_add_details.php?updatecon=1&patientid="+patientid+"&pat_asthama="+pat_asthama;
		
		if(pat_asthama == "") {
			return false;
		} else {
			$.get(url, function(response) {
				setTimeout(function() {
					toastr.options = {
						closeButton: true,
						progressBar: true,
						showMethod: 'slideDown',
						timeOut: 1500
					};
					toastr.success('', 'Asthama Condition Updated Successfully');	
				}, 100);
				
			});
		}
	});

	$("body").on("click", ".cholestrolCondition", function() {
		
		var pat_cholestrole = $(this).val();
		var patientid = $(this).attr("data-patient-id");
		var url = hostUrl+"patient_page_add_details.php?updatecon=1&patientid="+patientid+"&pat_cholestrole="+pat_cholestrole;
		
		if(pat_cholestrole == "") {
			return false;
		} else {
			$.get(url, function(response) {
				setTimeout(function() {
					toastr.options = {
						closeButton: true,
						progressBar: true,
						showMethod: 'slideDown',
						timeOut: 1500
					};
					toastr.success('', 'Cholestrol Condition Updated Successfully');
				}, 100);
				
			});
		}
	});

	$("body").on("click", ".epilepsyCondition", function() {
		
		var pat_epilepsy = $(this).val();
		var patientid = $(this).attr("data-patient-id");
		var url = hostUrl+"patient_page_add_details.php?updatecon=1&patientid="+patientid+"&pat_epilepsy="+pat_epilepsy;
		
		if(pat_epilepsy == "") {
			return false;
		} else {
			$.get(url, function(response) {
				setTimeout(function() {
					toastr.options = {
						closeButton: true,
						progressBar: true,
						showMethod: 'slideDown',
						timeOut: 1500
					};
					toastr.success('', 'Epilepsy Condition Updated Successfully');
				}, 100);
				
			});
		}
	});

	//update patient medical history section ends
	
		
	//Scripts for drug allergy
	$("#get_allergy").on("blur", function() {
		var patientid = $(this).attr("data-patient-id");
		var generic = $(this).val();
		var docid = $(this).attr("data-doc-id");
		var url = hostUrl+"patient_page_add_details.php?generic="+generic+"&patientid="+patientid+"&docid="+docid;
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
		var docid = $(this).attr("data-doc-id");
		var generic = $(this).val();
		var url = hostUrl+"patient_page_add_details.php?generic="+generic+"&patientid="+patientid+"&docid="+docid;
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
		var url = hostUrl+"patient_page_add_details.php?allergyid="+allergyid;
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

	//Family History Scripts
	$("#get_history_abuse").on("blur", function() {
		var patientid = $(this).attr("data-patient-id");
		var historyid = $(this).val();
		var docid = $(this).attr("data-doc-id");
		var url = hostUrl+"patient_page_add_details.php?historyid="+historyid+"&patientid="+patientid+"&docid="+docid;
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
		var docid = $(this).attr("data-doc-id");
		var url = hostUrl+"patient_page_add_details.php?historyid="+historyid+"&patientid="+patientid+"&docid="+docid;
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
		var docid = $(this).attr("data-doc-id");
		var url = hostUrl+"patient_page_add_details.php?historyid="+historyid+"&patientid="+patientid+"&docid="+docid;
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
		var url = hostUrl+"patient_page_add_details.php?delhistoryid="+historyid;
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
	
});