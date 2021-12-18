function delDiagnostics(str,str1){
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
        xmlhttp.open("GET","delete_function.php?doc_diagno_id="+str+"&diagnostic_id="+str1,true);
        xmlhttp.send();
    }
	
	$("#allDiagnosis").hide();
} else 
{
		return false;
}
		
}

function delPharma(str,str1){
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
        xmlhttp.open("GET","delete_function.php?doc_pharma_id="+str+"&pharma_id="+str1,true);
        xmlhttp.send();
    }
	
	$("#allPharma").hide();
} else 
{
		return false;
}
		
}

function deletePartner(str){
		//alert(str);
if(confirm('Are you sure you want to delete this partner from this list?')){
		if (str == "") {
        document.getElementById("reloadPartner").innerHTML = "";
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
                document.getElementById("reloadPartner").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","delete_carepartner.php?partnerid="+str,true);
        xmlhttp.send();
    }
	
	$("#allPartner").hide();
}
else{
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
function ChangeAppStatus(selctval,patientid){
	//alert(patientid);
	if(confirm('Are you sure you want to change this current status?')){
	document.frmAppStatus.cmdAppStatus1.value="submit";
	document.frmAppStatus.slct_val.value=selctval;
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
