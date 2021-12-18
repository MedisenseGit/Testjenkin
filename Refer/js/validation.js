function getPatient(patientid){
	//alert(patientid);
	document.frmTable.cmdGetId.value="submit";
	document.frmTable.patient_id.value=patientid;
	document.frmTable.submit();
}
function editUser(userid){
	
	document.frmUser.cmdGetId.value="submit";
	document.frmUser.user_id.value=userid;
	document.frmUser.submit();
}
function changeStatus(userid,state){
	
	document.frmStatus.cmdStateId.value="submit";
	document.frmStatus.user_id1.value=userid;
	document.frmStatus.status_id.value=state;
	document.frmStatus.submit();
	
}
function changeStatus1(userid1,state1){
	
	document.frmStatus1.cmdStateId1.value="submit";
	document.frmStatus1.user_id1.value=userid1;
	document.frmStatus1.status_id1.value=state1;
	document.frmStatus1.submit();
	
}

function delCat(id){
	 if(confirm('Are you sure you want to delete this category?')){
		document.frmCategory.cmdDelState.value="delete";
		document.frmCategory.catid1.value=id;
		document.frmCategory.submit();
		
	}else{
		return false;
	}
}
