$(document).ready(function() {
	
	$("body").on("blur",".webDomain", function() {
		var docId = $(this).attr("data-doc-id");
		var webUrl = $(this).val();
		var url = "add_details.php?docid="+docId+"&webUrl="+webUrl;
		//console.log(patientid, docid, url);
		//$("#beforeExaminationResult").remove();
		//$("#editExaminationResult").remove();
		if(webUrl == "") {
			return false;
		} else {
			$.get(url, function(response){
				//console.log(response);
				
			});
		}
		

	});
	
$(".custom-status").on("click", function(){
		var statusId = $(this).attr("data-status-id");
		var request_id = $(this).attr("data-request-id");
		var url = "add_details.php?requestid="+request_id+"&statusId="+statusId;
		//console.log(statusId,request_id,url);

		
		if(request_id == ""){
			return false;
		}
		else
		{
			var statusIds = {		    
			    "1": ["INITIATED", "btn-warning"], 
				"2": ["IN-PROGRESS", "btn-success"],
			    "3": ["ON-HOLD", "btn-warning"], 
			    "4": ["COMPLETED", "btn-primary"], 
			    "5": ["PENDING", "btn-danger"] 
			};
			//console.log(statusIds[statusId]);
			var btn = $(this).parent().parent().prev('.btn');
			var container = btn.parent();
			    
			$.get(url, function(response){
			    
				var classes = btn.attr('class').split(/\s+/);
				$.each(classes, function(index, item) {
				    if(item.substring(0, 4) === "btn-" && item != "btn-xs") {
					btn.removeClass(classes[index]);
				    }
				});
				btn.addClass(statusIds[statusId][1]).html(statusIds[statusId][0]).append(' <span class="caret"></span>');
				container.removeClass("open");
			    
			});
			
		}
		return false;
	});
});

function srchDoctor(doc_type){
		//alert(doc_type);
		document.frmSrch.docType.value="submit";
		document.frmSrch.doc_type.value=doc_type;
		document.frmSrch.submit();
		
}
function srchRef(ref_id,disp){
		//alert(disp);
		document.frmSrch.refStatus.value="submit";
		document.frmSrch.refer_id.value=ref_id;
		document.frmSrch.disp_val.value=disp;
		document.frmSrch.submit();
		
}
function srchAssign(user_id){
	
		document.frmSrchAssign.assignStatus.value="submit";
		document.frmSrchAssign.assign_id.value=user_id;
		document.frmSrchAssign.submit();
		
}
function srchText(srch_text){
		document.frmSrchBox.postTextSrchCmd.value="submit";
		document.frmSrchBox.postTextSrch.value=srch_text;
		document.frmSrchBox.submit();
		
}
function srchDate(date_fl){
	
		document.frmDate.dateStatus.value="submit";
		document.frmDate.Date_fld.value=date_fl;
		document.frmDate.submit();
				
}
