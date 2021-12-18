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
function delFavour(docid,userid){
	//alert(id);
	 if(confirm('Are you sure you want to remove this doctor from your favourite list?')){
		document.frmDelFavourite.cmdDel.value="submit";
		document.frmDelFavourite.Doc_id.value=docid;
		document.frmDelFavourite.user_id.value=userid;
		document.frmDelFavourite.submit();
	}else{
		return false;
	}
}
function filterBy(val){
		//alert(val);
		document.frmFilter.filterStatus.value="submit";
		document.frmFilter.filter_val.value=val;
		document.frmFilter.submit();
		
}