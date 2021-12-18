$(function() {
    $('#medi-login').validate({
        rules: {
       
		txtuser:{
              required: true,
               
            },
	
		txtpassword:{
		required: true,	
	
		}
		
		},
        messages: {
       
		
		txtuser:{ required: "Please, enter user name.",},
				
		txtpassword:{
		required:"Please, enter password.",	
			}
        }
        
    });
});


$(function() {
    $('#medi-signup').validate({
        rules: {
       
		sub_name:{
              required: true,
               
            },
		

        sub_email: {
              required: true,
			  email:true
               
            },
        
		
		sub_phone_no:{
			 required: true,
			 minlength: 10
			 
		},
		
	
		sub_password:{
		required: true,	
	
		},
		sub_repassword:{
		required: true,
		equalTo:"#sub_password",
		},

		letters_code:{
				required: true,
				
		}
		
		},
        messages: {
       
		
		sub_name:{ required: "Please, enter your name.",},
			
		sub_email: {
				required: "Please, enter your email-ID.",
				email: "Please, enter valid Email-ID"
			},
			
		sub_phone_no:{
			 required: "Please, enter phone number",
			 minlength: "Please, enter valid phone number"	 
			 
			},
			
		sub_password:{
		required:"Please, enter password.",	
			},
			sub_repassword:{
			required:"Please, retype password.",
			equalTo: "Password mismatch"
			},
		letters_code:{
				required: "Please, enter code here",
				
		}
        }
        
    });
});
$(function() {
    $('#f-password').validate({
        rules:{
            email:{
              required: true,
            }
		},
        messages:{
          email:{
                required:"Please, enter Email ID."
               
            }
            
        }
        
    });
});
$(function() {
    $('#frmRecover').validate({
        rules: {
       
		txtpassword:{
        required: true,
        },
		repassword:{
		required: true,	
		equalTo:"#txtpassword",
		}
		
		},
        messages: {
       
		txtpassword:{ required: "Please, enter new password.",},
		repassword:{ required: "Please, re-enter password.",
					 equalTo: "Password mismatch"	},
        }
        
    });
});

