$(function()
{ 
						// on send appointment button click 
						$('.bt-reschedule').click(function(){
							var theAppDate = $('#dateadded');
							var theAppTime = $('#selectTime');
							var thePatTransId = $('#patTransId');
						
							if( !theAppDate.val() && theAppTime.val()==0){ 
								//alert('Email or Mobile No. are must required'); 
								swal({
										title: "Required!",
										text: "Please enter appointment date & time",
										type: "warning",
										confirmButtonColor: "#DD6B55",
										confirmButtonText: "Ok, got it!"
									});
								
								
							}else{ 
								$.ajax({
									type: "POST",
									url: "add_details.php",
									data: 'act=add-reschedule&appDate='+theAppDate.val()+'&appTime='+theAppTime.val()+'&patTransId='+thePatTransId.val(),
									success: function(html){
										theAppDate.val('');
										theAppTime.val('');
									//alert('Link Sent Successfully');
									swal({
											title: "Success!",
											text: "Appointrment has been rescheduled successfully!",
											type: "success"
										});
									}  
								});
							}
						});
						
						// on share blog link click 
						$('.errorshare').click(function(){
													
								swal({
                title: "Have you update your profile??",
                text: "Please update your profile including appointment timings before sharing your profile. The patients can view your profile, book appointment request opinion & also share your profile.",
                type: "warning",
                showCancelButton: false,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Update Profile",
                closeOnConfirm: false
				}, function () {
					window.location.assign("https://medisensecrm.com/standard/Profile");
				});	
							
						});
						
						// on send appointment button click 
						$('.bt-send-link').click(function(){
							var theUserEmail = $('#txtEmail');
							var theUserMobile = $('#txMobile');
						
							if( !theUserEmail.val() && !theUserMobile.val()){ 
								//alert('Email or Mobile No. are must required'); 
								swal({
										title: "Required!",
										text: "Email or Mobile No. are must required!!",
										type: "warning",
										confirmButtonColor: "#DD6B55",
										confirmButtonText: "Ok, got it!"
									});
								
								
							}else{ 
								$.ajax({
									type: "POST",
									url: "send_appointment.php",
									data: 'act=add-com&userMail='+theUserEmail.val()+'&userMobile='+theUserMobile.val(),
									success: function(html){
										theUserEmail.val('');
										theUserMobile.val('');
									//alert('Link Sent Successfully');
									swal({
											title: "Good job!",
											text: "Your Appointment Link Sent Successfully!",
											type: "success"
										});
									}  
								});
							}
						});
						// on share blog link click 
						$('#cmdshareinner').click(function(){
							var shareLink = $('#shareLink');
							var mailSub = $('#mailsub');
							var recEmail = $('#receiverMail');
												
							if( !recEmail.val()){ 
								//alert('Please enter recipient email id'); 
								swal({
										title: "Required!",
										text: "Please enter recipient email id!!!",
										type: "warning",
										confirmButtonColor: "#DD6B55",
										confirmButtonText: "Ok, got it!"
									});
								
							}else{ 
								$.ajax({
									type: "POST",
									url: "https://medisensecrm.com/standard/add_details.php",
									data: 'act=cmdshareinner&shareLink='+shareLink.val()+'&receiverMail='+recEmail.val()+'&mailsub='+mailSub.val(),
									success: function(html){
										recEmail.val('');
										
									//alert('Post has been successfully shared to recipient email address');
									swal({
											title: "Good job!",
											text: "Post has been successfully shared to recipient email address!",
											type: "success"
										});
									}  
								});
							}
						});


						
});