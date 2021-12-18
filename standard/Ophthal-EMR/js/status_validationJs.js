/*function delExamination(deleaxaminationid){
	//alert(subtestid);	
		if (deleaxaminationid == "") {
        document.getElementById("editExaminationResult").innerHTML = "";
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
                document.getElementById("editExaminationResult").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","get_examination.php?deleditexaminationid="+deleaxaminationid,true);
        xmlhttp.send();
    }

	$("#editExaminationResult").show();	
}
*/
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