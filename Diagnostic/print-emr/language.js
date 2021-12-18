$(document).ready(function() {

//SMS Prescription
	$("body").on("click", "#sendSMS", function() {
		var id = $(this).attr("data-id");
		var mobile = $(this).attr("data-mobile");
		//console.log(mobile,id);
		//var diagnoid = $(this).val();
		 swal({
                        title: "Do you want to share this prescription through SMS?",
                        text: "They will able to see patient EMR ",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes, send!",
                        cancelButtonText: "No, cancel!",
                        closeOnConfirm: false,
                        closeOnCancel: false },
                    function (isConfirm) {
                        if (isConfirm) {
							var url = "shareemail.php?pmobile="+mobile+"&pid="+id;
							$.get(url, function(response){
							console.log(response);	
                            swal("Message Sent Successfully!", "", "success");
							});
                        } else {
                            swal("Cancelled", "", "error");
                        }
                });
		
		
	});	
	
	//EMAIL Prescription
	$("body").on("click", "#sendEMAIL", function() {
		var id = $(this).attr("data-id");
		var email = $(this).attr("data-email");
		//console.log(mobile,id);
		//var diagnoid = $(this).val();
		 swal({
                        title: "Do you want to share this prescription through EMAIL?",
                        text: "They will able to see patient EMR ",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes, send!",
                        cancelButtonText: "No, cancel!",
                        closeOnConfirm: false,
                        closeOnCancel: false },
                    function (isConfirm) {
                        if (isConfirm) {
							var url = "shareemail.php?pemail="+email+"&pid="+id;
							$.get(url, function(response){
							console.log(response);	
                            swal("Email Sent Successfully!", "", "success");
							});
                        } else {
                            swal("Cancelled", "", "error");
                        }
                });
		
		
	});

	
});