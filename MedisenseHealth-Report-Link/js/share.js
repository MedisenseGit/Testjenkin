$(function()
{ 
						
						//send call back request 
						$('.request_help').click(function(){
							var theUserName = $('#user_name');
							var theUserEmail = $('#user_email');
							var theUserPhone = $('#user_phone');
							var theUserMsg = $('#user_msg');
						
							if( !theUserName.val() || !theUserPhone.val() || !theUserEmail.val()){ 
								//alert('Email or Mobile No. are must required'); 
								/*swal({
										title: "Required!",
										text: "Your name & Mobile No. are must required!!",
										type: "warning",
										confirmButtonColor: "#DD6B55",
										confirmButtonText: "Ok, got it!"
									});
								*/
								//return false;
								
							}else{ 
								$.ajax({
									type: "POST",
									url: "add_details.php",
									data: 'act=callBack&userName='+theUserName.val()+'&userMail='+theUserEmail.val()+'&userMobile='+theUserPhone.val()+'&userMessage='+theUserMsg.val(),
									success: function(html){
										//$("#message").show();
										
										theUserName.val('');
										theUserPhone.val('');
										theUserEmail.val('');
										theUserMsg.val('');
									//alert('Link Sent Successfully');
									swal({
											title: "Call back request has been sent successfully!",
											text: "",
											type: "success"
										});
										
									}  
								});
							}
						});
						
						//send call back request 
						$('.app_link').click(function(){
							var theusermobile = $('#usermobile');
							var theusermail = $('#usermail');
													
							if( !theusermobile.val() && !theusermail.val()){ 
								//alert('Email or Mobile No. are must required'); 
								/*swal({
										title: "Required!",
										text: "Your name & Mobile No. are must required!!",
										type: "warning",
										confirmButtonColor: "#DD6B55",
										confirmButtonText: "Ok, got it!"
									});
								*/
								//return false;
								
							}else{ 
								$.ajax({
									type: "POST",
									url: "add_details.php",
									data: 'act=shareAppLink&userMail='+theusermail.val()+'&userMobile='+theusermobile.val(),
									success: function(html){
										//$("#message").show();
										
										theusermail.val('');
										theusermobile.val('');
										
									//alert('Link Sent Successfully');
									swal({
											title: "App link has been sent successfully!",
											text: "",
											type: "success"
										});
										
									}  
								});
							}
						});
						


						
});