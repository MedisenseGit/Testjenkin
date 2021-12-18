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
	if(confirm('Are you sure you want to change this current status?')){
	document.frmAppStatus.cmdAppStatus.value="submit";
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
