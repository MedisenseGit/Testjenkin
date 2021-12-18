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

function ChangeAppStatus(typeval,transid){
	if(confirm('Are you sure you want to change this current status?')){
	document.frmAppStatus.cmdAppStatus.value="submit";
	document.frmAppStatus.slct_val.value=typeval;
	document.frmAppStatus.trans_id.value=transid;
	document.frmAppStatus.submit();
	
	}else{
		return false;
	}
	
}
function srchText(srchText){
		document.frmSrchBox.postTextSrchCmd.value="submit";
		document.frmSrchBox.postTextSrch.value=srchText;
		document.frmSrchBox.submit();		
}
function cancelTrans(appid){
	//alert(id);
	 if(confirm('Are you sure you want to delete this appointment from current list?')){
		document.frmAppointment.cmdDelStatus.value="submit";
		document.frmAppointment.appoint_id.value=appid;
		document.frmAppointment.submit();
	}else{
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

function cmdWantContributor(partid){
	//alert(id);
	 if(confirm('Your request is submitted to pixelcare team. We will get back within 24 hrs')){
		document.frmWantContribute.cmdContribute.value="submit";
		document.frmWantContribute.partner_id.value=partid;
			
	}else{
		return false;
	}
}

						

function filterSearch(){
		
		var serText = document.getElementsByTagName("input");
		alert(serText.val);
		if (serText == "") {
        document.getElementById("serConnection").innerHTML = "";
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
                document.getElementById("serConnection").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","search_connection.php?ser="+serText,true);
        xmlhttp.send();
    }
	
	$("#allConnection").hide();
		
}
function filterBy(str){
		//alert(str);
		if (str == "") {
        document.getElementById("serConnection").innerHTML = "";
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
                document.getElementById("serConnection").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","search_connection.php?val="+str,true);
        xmlhttp.send();
    }
	
	$("#allConnection").hide();
		
}