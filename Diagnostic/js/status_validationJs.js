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
function deleteTempTest(str){
		//alert(str);
	if(confirm('Are you sure you want to delete?')){	
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
        xmlhttp.open("GET","add_temp_test.php?deltestid="+str,true);
        xmlhttp.send();
    }
	
	$("#test-grid").hide();
	}
	else
	{
		return false;
	}
}
function addTempTest(str){
		alert(str);
		
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
        xmlhttp.open("GET","add_temp_test.php?testid="+str,true);
        xmlhttp.send();
    }
	
	$("#test-grid").hide();
}
function deleteMedicine(str,patid){
		//alert(str);
		//alert(patid);
	if(confirm('Are you sure you want to delete?')){
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
        xmlhttp.open("GET","delete_medicine.php?delprescid="+str+"&patientid="+patid,true);
        xmlhttp.send();
    }

} else 
{
		return false;
}
}
function addTest(str,patid,episodeid){
		//alert(str);
		//alert(patid);
		//alert(episodeid);
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
        xmlhttp.open("GET","add_test.php?testid="+str+"&patientid="+patid+"&episodeid="+episodeid,true);
        xmlhttp.send();
    }
	
	$("#test-grid").hide();
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
