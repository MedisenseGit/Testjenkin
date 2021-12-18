$(function() {
    $('#partner-login').validate({
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
    $('#partner-signup').validate({
        rules: {
       
		comp_type:{
              required: true,
			  required: {
                depends: function(element) {
                    return $("#comp_type").val() == '';
						}
					}
               
            },
		
		businessName:{
			 required: true,
				 
		},
		
	
		contact_person:{
		required: true,	
	
		},
		contact_mobile:{
			required: true,
		},
		primaryEmail:{
			required: true,
		},
		slctcountry:{
			required: {
                depends: function(element) {
                    return $("#slctcountry").val() == '';
						}
					}
		},
		slctstate:{
			required: {
                depends: function(element) {
                    return $("#slctstate").val() == '';
						}
					}
		},
		txt_city:{
			required: true,
		},
		txt_address:{
			required: true,
		},
		
		txtpassword:{
		required: true,
		},
		txtrepassword:{
		required: true,
		equalTo:"#txtpassword",
		},

		letters_code:{
				required: true,
				
		}
		
		},
        messages: {
       
		
		comp_type:{ required: "select one of the option",},
		
		businessName:{ required: "Enter your business name",},
		contact_person:{ required: "Enter your business name",},
		
		primaryEmail: {
				required: "Please, enter primary email-ID.",
				email: "Please, enter valid Email-ID"
			},
			
		contact_mobile:{
			 required: "Please, enter mobile number",
			 minlength: "Please, enter valid mobile number"	 
			 
			},
		slctcountry:{ required: "Select Country",},
		slctstate:{ required: "Select State",},
		txt_city:{ required: "Enter City",},
		txt_address:{ required: "Enter Address",},		
		txtpassword:{
		required:"Please, enter password.",	
			},
		txtrepassword:{
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

