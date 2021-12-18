$(document).ready(function() {

$("body").on("click", "#delEpisode", function() {
		var episodeid = $(this).attr("data-episode-id");
		var printcontent = $(this).attr("data-print-content");
		var rowid = $(this).attr("data-row-id");
		console.log(printcontent,episodeid);
		 swal({
                        title: "Are you sure you want to delete "+printcontent+" detail of this patient?",
                        text: "You can't get this patient visit details in future",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes, delete it!",
                        cancelButtonText: "No, cancel!",
                        closeOnConfirm: false,
                        closeOnCancel: false },
                    function (isConfirm) {
                        if (isConfirm) {
							$('#myTableRow'+rowid).remove();
							var url = "delete_mypatient.php?episodeid="+episodeid;
							$.get(url, function(response){
							console.log(response);	
                            swal("Deleted Successfully!", "", "success");
							});
                        } else {
                            swal("Cancelled", "", "error");
                        }
                });
		
		
	});

$("body").on("click", ".delPatient", function() {
		var patientid = $(this).attr("data-patient-id");
		console.log(patientid);
		 swal({
                        title: "Are you sure you want to delete this patient?",
                        text: "You can't get this patient details in future",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes, delete it!",
                        cancelButtonText: "No, cancel!",
                        closeOnConfirm: false,
                        closeOnCancel: false },
                    function (isConfirm) {
                        if (isConfirm) {
							$('#myTableRow'+patientid).remove();
							var url = "delete_mypatient.php?patid="+patientid;
							$.get(url, function(response){
							console.log(response);	
                            swal("Deleted Successfully!", "", "success");
							});
                        } else {
                            swal("Cancelled", "", "error");
                        }
                });
		
		
	});
	
	$("body").on("click", ".delete_reception", function() {
		if(confirm('Are you sure you want to delete?')){
		var delreceptionid = $(this).attr("data-reception-id");
		var delrowid = $(this).attr("data-row-id");
		var url = "add_details.php?delreceptionid="+delreceptionid;
		//console.log(deleaxaminationid, url);
		//$("#beforeSymptom").remove();
		$("#delete_reception_row"+delrowid).remove();
		if(delreceptionid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
			
				
			});
		}
		
	}
	else
	{
		return false;
	}
	});
	
	$("body").on("click", ".delete_drug", function() {
		if(confirm('Are you sure you want to delete?')){
		var deldrugid = $(this).attr("data-drug-id");
		var delrowid = $(this).attr("data-row-id");
		var url = "add_details.php?deldrugdata="+deldrugid;
		//console.log(deleaxaminationid, url);
		//$("#beforeSymptom").remove();
		$("#delete_drug_row"+delrowid).remove();
		if(deldrugid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
			
				
			});
		}
		
	}
	else
	{
		return false;
	}
	});
	
	$(".surgery-status").on("click", function(){
		var statusId = $(this).attr("data-status-id");
		var schedulerId = $(this).attr("data-scheduler-id");
		var url = "add_details.php?schedulerId="+schedulerId+"&statusId="+statusId;
		console.log(statusId,schedulerId,url);

		
		if(schedulerId == ""){
			return false;
		}
		else
		{
			var statusIds = {		    
			    "1": ["Scheduled", "btn-primary"], 
				"2": ["Cancelled", "btn-danger"],
			    "3": ["Postponed", "btn-warning"], 
			    "4": ["Preponed", "btn-success"], 
				"5": ["Completed", "btn-primary"]
			   
			};
			console.log(statusIds[statusId]);
			var btn = $(this).parent().parent().prev('.btn');
			var container = btn.parent();
			    
			$.get(url, function(response){
			    if(statusId == 6) {
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
	
	$("body").on("click", ".delete_scheduler", function() {
		if(confirm('Are you sure you want to delete?')){
		var delschedulerid = $(this).attr("data-scheduler-id");
		var delrowid = $(this).attr("data-row-id");
		var url = "add_details.php?delschedulerid="+delschedulerid;
		//console.log(deleaxaminationid, url);
		//$("#beforeSymptom").remove();
		$("#delete_scheduler_row"+delrowid).remove();
		if(delschedulerid == "") {
			return false;
		} else {
			$.get(url, function(response) {
				//console.log(response);
			
				
			});
		}
		
	}
	else
	{
		return false;
	}
	});
	
});
function ChangeAppointStatus(selctval){
	//alert(selctval);
	document.changeAppType.cmdAppStatus.value="submit";
	document.changeAppType.slct_val.value=selctval;
	document.changeAppType.submit();
	
	
}
//Scripts for change receptionist permission
function getPermitTab(str){
		//alert(str);

		if (str == "") {
        document.getElementById("afterPermit").innerHTML = "";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("afterPermit").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","get_reception_permit.php?receptionid="+str,true);
        xmlhttp.send();
    }
	
	$("#beforePermit").hide();
		
}

//Scripts for change appointment timings for multiple hopital
function getTimeSlot(str){
		//alert(str);

		if (str == "") {
        document.getElementById("dispTimeSlotAfter").innerHTML = "";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("dispTimeSlotAfter").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","get_appointment_timing.php?hospid="+str,true);
        xmlhttp.send();
    }
	
	$("#dispTimeSlotDefault").hide();
		
}

function delDoctor(str){
		//alert(str);
if(confirm('Are you sure you want to delete?')){
		
		if (str == "") {
        document.getElementById("afterDelDoctor").innerHTML = "";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("afterDelDoctor").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","add_details.php?docid="+str,true);
        xmlhttp.send();
    }
	
	$("#allDoctors").hide();
} else 
{
		return false;
}
		
}

function delDiagnostics(str){
		//alert(str);
if(confirm('Are you sure you want to delete?')){
		if (str == "") {
        document.getElementById("afterDelDiagno").innerHTML = "";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("afterDelDiagno").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","delete_function.php?doc_diagno_id="+str,true);
        xmlhttp.send();
    }
	
	$("#allDiagnosis").hide();
} else 
{
		return false;
}
		
}

function delPharma(str){
		//alert(str);
if(confirm('Are you sure you want to delete?')){
		if (str == "") {
        document.getElementById("afterDelPharma").innerHTML = "";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("afterDelPharma").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","delete_function.php?doc_pharma_id="+str,true);
        xmlhttp.send();
    }
	
	$("#allPharma").hide();
} else 
{
		return false;
}
		
}
function delOptical(str){
		//alert(str);
if(confirm('Are you sure you want to delete?')){
		if (str == "") {
        document.getElementById("afterDelOptical").innerHTML = "";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("afterDelOptical").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","delete_function.php?doc_optical_id="+str,true);
        xmlhttp.send();
    }
	
	$("#allOptical").hide();
} else 
{
		return false;
}
		
}

	
	
	
function deleteMyPatient(str){
		//alert(str);
		
if(confirm('Are you sure you want to delete this patient?')){
		if (str == "") {
        document.getElementById("afterDel").innerHTML = "";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("afterDel").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","delete_mypatient.php?patid="+str,true);
        xmlhttp.send();
    }
	
	$("#allPatient").hide();
} else 
{
		return false;
}
		
}
function delInvestAll(docid,patid){
	//alert(docid);	
	//alert(patid);
if(confirm('Are you sure you want to delete?')){
	if (docid == "") {
       // document.getElementById("dispDignoTest").innerHTML = "";
        return;
    } else {	
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("dispDignoTest").innerHTML = this.responseText;
            }
        };
	xmlhttp.open("GET","get_diagno_test_report.php?delallInvest=true&docid="+docid+"&patid="+patid,true);
        xmlhttp.send();
	}
} 
else 
{
		return false;
}
		
}
function deleteAll(docid,patid){
	//alert(docid);	
	//alert(patid);
if(confirm('Are you sure you want to delete?')){
	if (docid == "") {
        document.getElementById("dispMedTable").innerHTML = "";
        return;
    } else {	
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("dispMedTable").innerHTML = this.responseText;
            }
        };
	xmlhttp.open("GET","add_medicine.php?delall=true&docid="+docid+"&patid="+patid,true);
        xmlhttp.send();
	}
	$("#employee-grid").show();
} 
else 
{
		return false;
}
		
}
function delICD(icdid){
	//alert(icdid);	
if(confirm('Are you sure you want to delete?')){
		if (icdid == "") {
        document.getElementById("dispICDTest").innerHTML = "";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("dispICDTest").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","get_diagno_test_report.php?delicdid="+icdid,true);
        xmlhttp.send();
    }
} 
else 
{
		return false;
}
		
}
function delInvest(subtestid,patid){
	//alert(subtestid);	
if(confirm('Are you sure you want to delete?')){
		if (subtestid == "") {
        document.getElementById("dispDignoTest").innerHTML = "";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("dispDignoTest").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","get_diagno_test_report.php?delsubtestid="+subtestid+"&patientid="+patid,true);
        xmlhttp.send();
    }
} 
else 
{
		return false;
}
		
}
/*
function addICD(icdid,patid){
		//alert(investid);
		//alert(patid);
		if (icdid == "") {
        //document.getElementById("dispICDTest").innerHTML = "";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("dispICDTest").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","get_diagno_test_report.php?icdid="+icdid+"&patientid="+patid,true);
        xmlhttp.send();
    }
	document.getElementById("get_diagnosis").value="";
	$(".diagnosisString").focus();
}
*/
function getSymptoms_old(sympid,patientid){
		//alert(investid);
		//alert(patid);
		if (sympid == "") {
        //document.getElementById("dispDignoTest").innerHTML = "";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("sympBefore").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","get_symptoms.php?sympid="+sympid+"&patientid="+patientid,true);
        xmlhttp.send();
    }
	
	$("#sympBefore").hide();
	document.getElementById("get_complaints").value = "";
	$(".searchSymptoms").focus();
}
function getInvestigationFreq(investid,patid){
		//alert(investid);
		//alert(patid);
		if (investid == "") {
        //document.getElementById("dispDignoTest").innerHTML = "";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("dispDignoTest").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","get_diagno_test_report.php?freqinvestid="+investid+"&patientid="+patid,true);
        xmlhttp.send();
    }
	//document.getElementById("hideInvest").options.length = 0;
	//document.getElementById("hideInvest").value = "";
	$("#hideInvest").focus();
}
function getInvestigation(investid,patid){
		//alert(investid);
		//alert(patid);
		if (investid == "") {
        //document.getElementById("dispDignoTest").innerHTML = "";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("dispDignoTest").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","get_diagno_test_report.php?investid="+investid+"&patientid="+patid,true);
        xmlhttp.send();
    }
	//document.getElementById("hideInvest").options.length = 0;
	//document.getElementById("hideInvest").value = "";
	$("#hideInvest").focus();
}

function getCityState(pin){
		//alert(patid);
		//alert(pin);
		if (pin == "") {
        document.getElementById("dispCity").innerHTML = "";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("dispCity").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","get_city_state.php?pincode="+pin,true);
        xmlhttp.send();
    }
	
	$("#beforeLoad").hide();
}

function changePayStatus(str){
		//alert(str);
	
if(confirm('Are you sure you want to change the transaction status?')){
		if (str == "") {
        document.getElementById("afterDelTrans").innerHTML = "";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("afterDelTrans").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","change_pay_status.php?transid="+str,true);
        xmlhttp.send();
    }
	
	$("#allPay").hide();
} 
else 
{
		return false;
}
		
}

function delEducation(str){
		//alert(str);
	
if(confirm('Are you sure you want to delete?')){
		if (str == "") {
        document.getElementById("afterDelEdu").innerHTML = "";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("afterDelEdu").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","delete_edu_details.php?eduid="+str,true);
        xmlhttp.send();
    }
	
	$("#allEducation").hide();
} 
else 
{
		return false;
}
		
}

function delPayment(str){
		//alert(str);
	
if(confirm('Are you sure you want to delete this transaction?')){
		if (str == "") {
        document.getElementById("afterDelTrans").innerHTML = "";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("afterDelTrans").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","delete_transaction.php?transid="+str,true);
        xmlhttp.send();
    }
	
	$("#allPay").hide();
} 
else 
{
		return false;
}
		
}
function addBlankrow(patid){
		//alert(patid);
		//alert(patid);
		if (str == "") {
        document.getElementById("dispMedTable").innerHTML = "";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("dispMedTable").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","add_medicine.php?blankrow=1&patientid="+patid,true);
        xmlhttp.send();
    }
	
	$("#employee-grid").hide();
}
function loadPrevTemplate(str,patid){
		//alert(str);
		//alert(patid);
		if (str == "") {
        document.getElementById("dispMedTable").innerHTML = "";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("dispMedTable").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","add_medicine.php?prevprescid="+str+"&patientid="+patid,true);
        xmlhttp.send();
    }
	$("#employee-grid").hide();

}

function loadTemplate(str,patid){
		//alert(patid);
		//alert(patid);
		if (str == "") {
       // document.getElementById("dispMedTable").innerHTML = "";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("dispMedTable").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","load_template.php?tempid="+str+"&patientid="+patid,true);
        xmlhttp.send();
    }
	$("#employee-grid").hide();

}
function deleteMedicine(str,patid){

	if (str == "") {
        document.getElementById("dispMedTable").innerHTML = "";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("dispMedTable").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","add_medicine.php?delprescid="+str+"&patientid="+patid,true);
        xmlhttp.send();
    }


}
function addFreqMedicine(frqmed,patid){
		//alert(frqmed);

		if (frqmed == "") {
       // document.getElementById("dispMedTable").innerHTML = "";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("dispMedTable").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","add_medicine.php?freqmedid="+frqmed+"&patientid="+patid,true);
        xmlhttp.send();
    }
	
	$("#employee-grid").hide();

	$(".searchString").focus();
}
function addMedicine(str,patid){
	
	if (str == "") {
      return;
    } 
	else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("dispMedTable").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","add_medicine.php?medid="+str+"&patientid="+patid,true);
        xmlhttp.send();
    }
	
	$("#employee-grid").hide();
	document.getElementById("coding_language").value="";
	//$("#searchMedicine").hide();
	//$("#hideSerMed").show();
	$(".searchString").focus();
}
function deleteHoliday(str){
		//alert(str);
	if(confirm('Are you sure you want to delete?')){
		if (str == "") {
        document.getElementById("afterHList").innerHTML = "";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("afterHList").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","delete_holidaylist.php?holidayid="+str,true);
        xmlhttp.send();
    }
	
	$("#allHList").hide();
} else 
{
		return false;
}
}


function cmdRegister(partid,eventid){
	//alert(eventid);
	//alert(partid);
	 if(confirm('Are you sure you want to register? Your details will be sent to organising committee? ')){
		document.frmRegister.cmdReg.value="submit";
		document.frmRegister.partner_id.value=partid;
		document.frmRegister.event_id.value=eventid;
	
	}else{
		return false;
	}
}
function sendAptLink(id,docid){
	
	 if(confirm('Are you sure you want send appointment link to this Patient?')){
		document.frmApp1.cmdAppt.value="submit";
		document.frmApp1.patient_id.value=id;
		document.frmApp1.ref_id.value=docid;
		document.frmApp1.submit();
	}else{
		return false;
	}
}
function sendPayLink(id,docid){
	//alert(id);
	 if(confirm('Are you sure you want send payment link to this Patient?')){
		document.frmPayment.cmdPay.value="submit";
		document.frmPayment.patient_id.value=id;
		document.frmPayment.ref_id.value=docid;
		document.frmPayment.submit();
	}else{
		return false;
	}
}
function ChangeAppStatus1(selctval,patientid){
	//alert(patientid);
	if(confirm('Are you sure you want to change this current status?')){
	document.frmAppStatus.cmdAppStatus1.value="submit";
	document.frmAppStatus.slct_val1.value=selctval;
	document.frmAppStatus.patient_id.value=patientid;
	document.frmAppStatus.submit();
	
	}else{
		return false;
	}
	
}
function getPatient(selctval){
	//alert(selctval);
	document.frmchangeStatus.cmdchangeStatus.value="submit";
	document.frmchangeStatus.slct_val.value=selctval;
	document.frmchangeStatus.submit();
	
}

function ChangeStatus(selctval,patientid,docid){
	if(confirm('Are you sure you want to change this current status?')){
	document.frmchangeStatus.cmdchangeStatus.value="submit";
	document.frmchangeStatus.slct_val.value=selctval;
	document.frmchangeStatus.patient_id.value=patientid;
	document.frmchangeStatus.doc_id.value=docid;
	document.frmchangeStatus.submit();
	
	}else{
		return false;
	}
	
}
function srchText(srchText){
		document.frmSrchBox.postTextSrchCmd.value="submit";
		document.frmSrchBox.postTextSrch.value=srchText;
		document.frmSrchBox.submit();		
}

function delOfferEvent(id){
	 if(confirm('Are you sure you want to delete this row?')){
		document.frmOffer.cmdDelStatus.value="delete";
		document.frmOffer.event_id.value=id;
		document.frmOffer.submit();
	}else{
		return false;
	}
}



function aaaagetSymptoms_old(sympid,patientid){
		//alert(investid);
		//alert(patid);
		if (sympid == "") {
        //document.getElementById("dispDignoTest").innerHTML = "";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("sympBefore").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","get_symptoms.php?sympid="+sympid+"&patientid="+patientid,true);
        xmlhttp.send();
    }
	
	$("#sympBefore").hide();
	document.getElementById("get_complaints").value = "";
	$(".searchSymptoms").focus();
}