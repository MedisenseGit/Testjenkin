function ChangeAppStatus1(selctval,patientid){
	alert(patientid);
	if(confirm('Are you sure you want to change this current status?')){
	document.frmAppStatus.cmdAppStatus1.value="submit";
	document.frmAppStatus.slct_val1.value=selctval;
	document.frmAppStatus.patient_id1.value=patientid;
	document.frmAppStatus.submit();
	
	}else{
		return false;
	}
	
}