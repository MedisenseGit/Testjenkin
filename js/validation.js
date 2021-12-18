$(document).ready(function() {
	$("body").on("blur", ".changeURL", function() {
		
		var chgurl = $(this).val();	
		var docid = $(this).attr("data-doc-id");
		var url = "manage_doctor_domain.php?updatecon=1&chnageUrl="+chgurl+"&docid="+docid;
		console.log(chgurl,docid,url);
		//$(this).parent("span").remove();
		
		if(chgurl == "") {
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
                toastr.success('', 'URL updated Successfully');

            }, 100);	
				
			});
		}
	
	});
					$(".search_cancel").hide();
					
						$('.search_analytic').click(function(){
							var genericname = $('#generic_name');
							var company = $('#company');
							//alert(genericname.val());
							//alert(company.val());
							if(genericname.val()==""){
								$("#searchGeneric").hide();
							}else if(company.val()=="")
							{
								$("#searchCompany").hide();
							}
							$("#before-status").hide();
							$(".search_cancel").show(); 
								var url = "add_details.php?genericname="+genericname.val()+"&company="+company.val();
		
								$.get(url, function(response){
									//console.log(response);
									$("#after-status").html("").prepend(response);
									
								});
								$('#generic_name').val(''); //Clear search field
								$('#company').val(''); //Clear search field
							
						});
						$('.search_cancel').click(function(){
							$("#after-status").hide();
							$("#before-status").show();
														
						});
	
});

function chkLogin(){	

	var frm = document.loginForm;
	
	if(frm.txtuserId.value==""){
		alert("Please enter User Name");
		frm.txtuserId.focus();
		return false;
	}

	if(frm.txtpasswd.value==""){
		alert("Please enter password");
		frm.txtpasswd.focus();
		return false;
	}

}	
function createPatient(){
var frm = document.frmPatient;
if(frm.txtname.value==""){
alert("Please Enter Patient Name");
		frm.txtname.focus();
		return false;	
}
if(frm.txtMail.value==""){
alert("Please Enter Patient Email Address");
		frm.txtMail.focus();
		return false;	
}


}


function createSignUp(){
var frm = document.frmSignUp;
if(frm.txtCmpny.value==""){
		alert("Please Enter Company Name");
		frm.txtCmpny.focus();
		return false;
	}
if(frm.txtName.value==""){
		alert("Please Enter Name");
		frm.txtName.focus();
		return false;
	}
if(frm.txtAddrss.value==""){
		alert("Please Enter Company Address");
		frm.txtAddrss.focus();
		return false;
	}
	if(frm.txtUser.value==""){
		alert("Please Enter User Name");
		frm.txtUser.focus();
		return false;
	}
	if(frm.txtPassword.value==""){
		alert("Please Enter Password");
		frm.txtPassword.focus();
		return false;
	}
if(frm.txtRePass.value==""){
		alert("Please Enter Re-type Password field");
		frm.txtRePass.focus();
		return false;
	}
	if(frm.txtPassword.value!=frm.txtRePass.value){
		alert("Password is mismatch!!!!");
		frm.txtRePass.focus();
		frm.txtRePass.value="";
		return false;
	}
}


function createProvider(){
var frm = document.frmProvide;
if(frm.txtProvider.value==""){
		alert("Please Enter Provider Name");
		frm.txtProvider.focus();
		return false;
	}
	if(frm.txtMail.value==""){
		alert("Please Enter Provider Email");
		frm.txtMail.focus();
		return false;
	}
}

function createUser(){
var frm = document.frmCreateuser;
if(frm.txtuser.value==""){
		alert("Please Enter User Name");
		frm.txtuser.focus();
		return false;
	}
if(frm.txtNewPass.value==""){
		alert("Please Enter Password");
		frm.txtNewPass.focus();
		return false;
	}
if(frm.txtVerifyPass.value==""){
		alert("Please Enter Re-type Password field");
		frm.txtVerifyPass.focus();
		return false;
	}
	if(frm.txtNewPass.value!=frm.txtVerifyPass.value){
		alert("Password is mismatch!!!!");
		frm.txtVerifyPass.focus();
		return false;
	}
}
function addAttach(){
var frm = document.frmAttach;
if(frm.txtphoto1.value==""){
		alert("Please Select Attachments");
		frm.txtphoto1.focus();
		return false;
	}
}
function createMedInt(){	

	var frm = document.frmMedInt;
	
	if(frm.medInt.value==""){
		alert("Please enter Text");
		frm.medInt.focus();
		return false;
	}

}
function createPro1Int(){	

	var frm = document.frmProvider1;
	
	if(frm.txtpro1.value==""){
		alert("Please enter Text");
		frm.txtpro1.focus();
		return false;
	}

}
function createPro2Int(){	

	var frm = document.frmProvider2;
	
	if(frm.txtpro2.value==""){
		alert("Please enter Text");
		frm.txtpro2.focus();
		return false;
	}

}
function createPro3Int(){	

	var frm = document.frmProvider3;
	
	if(frm.txtpro3.value==""){
		alert("Please enter Text");
		frm.txtpro3.focus();
		return false;
	}

}
function createPro5Int(){	

	var frm = document.frmProvider5;
	
	if(frm.txtpro4.value==""){
		alert("Please enter Text");
		frm.txtpro4.focus();
		return false;
	}

}

function createFeedback(){
var frm = document.frmFeedback;

	if(frm.txtHosp.value==""){
		alert("Please enter Hospital Name");
		frm.txtHosp.focus();
		return false;
	}

}

function chngPay(transid,val){
	
	if(confirm('Are you sure you want to confirm this transaction?')){
	document.frmCheckIn.cmdDoc.value="submit";
	document.frmCheckIn.transId.value=transid;
	document.frmCheckIn.confirmStatus.value=val;
	document.frmCheckIn.submit();
	
	}else{
		return false;
	}
	
}
function cancelTrans(transid,val){
	if(confirm('Are you sure you want to confirm this transaction?')){
	document.frmCheckIn.cmdCancel.value="submit";
	document.frmCheckIn.transId.value=transid;
	document.frmCheckIn.cancelStatus.value=val;
	document.frmCheckIn.submit();
	
	}else{
		return false;
	}
	
}

function docInfo(docId){
	if(confirm('Are you sure you want to send need info mail to this panelist?')){
	document.frmDocNeddInfo.cmdDoc.value="submit";
	document.frmDocNeddInfo.doc_id.value=docId;
	document.frmDocNeddInfo.submit();
	
	}else{
		return false;
	}
	
}

function changeListPublish(pub_status,story_id,story_date){
	
	document.frmList.cmdList.value="submit";
	document.frmList.pub_state.value=pub_status;
	document.frmList.Prov_Id.value=story_id;
	document.frmList.datetime.value=story_date;
	document.frmList.submit();
	alert(pub_status);
}


function changePublish(pub_status){
	
	document.frmPublish.cmdPublish.value="submit";
	document.frmPublish.pub_state.value=pub_status;
	document.frmPublish.submit();
	
}

function chnageAnonymous(anonyid){
	
	document.frmAnonymous.cmdAnonymous.value="submit";
	document.frmAnonymous.anonymous_id.value=anonyid;
	document.frmAnonymous.submit();

}

function getSpec(specid){
	
	document.frmProvide.cmdEmailStatus.value="submit";
	document.frmProvide.spec_id.value=specid;
	document.frmProvide.submit();
}
function reportNotAttach(repoid){
	
	document.reportFrmState.cmdRepoStatus.value="submit";
	document.reportFrmState.repoState.value=repoid;
	document.reportFrmState.submit();
}

function mailActive(patid,refid){
	if(confirm('Are you sure you want to forward this Patient Information to Provider?')){
		document.mailfrmstatus.cmdEmailStatus.value="submit";
		document.mailfrmstatus.mail_pat_id.value=patid;
		document.mailfrmstatus.mail_ref_id.value=refid;
		document.mailfrmstatus.submit();
		alert(patid);
		alert(refid);
	}else{
		return false;
	}
}

function patmailActive(patid){
	if(confirm('Are you sure!! you need more info of Patient 00'+patid+'?')){
		document.patmailfrmstatus.cmdEmailPatient.value="submit";
		document.patmailfrmstatus.pat_id.value=patid;
		document.patmailfrmstatus.submit();
		//alert(patid);
	
	}else{
		return false;
	}
}

function patQualify(patid){
	if(confirm('Are you sure you want to send the Does not qualify mail Patient Id 00'+patid+'?')){
		document.patqualifyfrmstatus.cmdqualifyPatient.value="submit";
		document.patqualifyfrmstatus.pat_id.value=patid;
		document.patqualifyfrmstatus.submit();
		//alert(patid);
	
	}else{
		return false;
	}
}

function NavigateSubmit(disp_id){
		alert(disp_id);
		document.frmNavigate.cmdNavigate.value="submit";
		document.frmNavigate.buttonVal.value=disp_id;
		document.frmNavigate.submit();
	
}


function Assignsubmit(status_id,patid){
	
		document.frmstatus.cmdStatus.value="submit";
		document.frmstatus.status_id.value=status_id;
		document.frmstatus.pat_id.value=patid;
		document.frmstatus.submit();
	
}

function changeState(state,patid){
	
		document.frmstatus.cmdStatus2.value="submit";
		document.frmstatus.state_name.value=state;
		document.frmstatus.pat_id.value=patid;
		document.frmstatus.submit();
	
}
function Assignsubmit1(status_id,patid){
	
		document.frmMedInt.assignStatus.value="submit";
		document.frmMedInt.assign_id.value=status_id;
		document.frmMedInt.pat_id1.value=patid;
		document.frmMedInt.submit();
	
}


function delRef(refid){
	if(confirm('Are you sure you want to delete this Referrals?')){
		//alert(refid);
		document.frmProvider1.cmdStatus.value="delete";
		document.frmProvider1.Del_Ref_Id.value=refid;
		document.frmProvider1.submit();
		
	}else{
		return false;
	}
}
function reminderActive(refid){
	if(confirm('Are you sure!! you want to send reminder to this Doctor?')){
		//alert(refid);
		document.frmReminder.cmdSendReminder.value="submit";
		document.frmReminder.reminder_id.value=refid;
		document.frmReminder.submit();
		
	}else{
		return false;
	}
}
function reminderActive1(refid){
	if(confirm('Are you sure!! you want to send reminder to this Doctor?')){
		//alert(refid);
		document.frmReminder2.cmdSendReminder.value="submit";
		document.frmReminder2.reminder_id.value=refid;
		document.frmReminder2.submit();
		
	}else{
		return false;
	}
}
function mailStatus(statusid,refid){
	if(statusid==1){
	if(confirm('Are you sure!! you want to send reminder to this Doctor?')){
		//alert(refid);
		//alert(statusid);
		document.frmReminder.cmdSendReminder.value="submit";
		document.frmReminder.reminder_id.value=refid;
		document.frmReminder.status_id.value=statusid;
		document.frmReminder.submit();
		}else{
		return false;
	}
	}
	if(statusid==2)	{
	if(confirm('Are you sure!! you want to send response mail to this Doctor?')){
		//alert(refid);
		//alert(statusid);
		document.frmReminder.cmdSendReminder.value="submit";
		document.frmReminder.reminder_id.value=refid;
		document.frmReminder.status_id.value=statusid;
		document.frmReminder.submit();
		}else{
		return false;
	}	
		
	}
	
}
function delPhotoe(attachid){
	if(confirm('Are you sure you want to delete this Attachments?')){
		document.frmAttach.cmdStatus.value="delete";
		document.frmAttach.Del_Att_Id.value=attachid;
		document.frmAttach.submit();
		
	}else{
		return false;
	}
}
function chgPatStatus(id){
		document.frmPatStat.cmdPatStatus.value="submit";
		document.frmPatStat.pat_status_id.value=id;
		document.frmPatStat.submit();
		
}

function chgStatus(id){
		document.frmProvider1.cmdStatus.value="submit";
		document.frmProvider1.status_id.value=id;
		document.frmProvider1.submit();
}
function chgStatus1(id){
		document.frmProvider2.cmdStatus1.value="submit";
		document.frmProvider2.status_id1.value=id;
		document.frmProvider2.submit();
}
function chgStatus2(id){
		document.frmProvider3.cmdStatus2.value="submit";
		document.frmProvider3.status_id2.value=id;
		document.frmProvider3.submit();
}
function chgStatus3(id){
		document.frmProvider5.cmdStatus3.value="submit";
		document.frmProvider5.status_id3.value=id;
		document.frmProvider5.submit();
}