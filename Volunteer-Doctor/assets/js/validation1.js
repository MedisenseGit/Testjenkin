$(function() {
    $('#second_opinion').validate({
        rules: {
            se_pat_name: {
              required: true,
               
            },
           se_email: {
              required: true,
			  email:true
               
            },
        
		se_pat_age:{
			 required: true,
			   min:0,
			   max:100
			 
		},
		se_con_per:{
			 required: true,
					 
		},
		se_phone_no:{
			 required: true,
			 minlength: 10
			 
		},
		se_country: {
            required: {
                depends: function(element) {
                    return $("#se_country").val() == '';
                }
            }
        },
		se_state: {
            required: {
                depends: function(element) {
                    return $("#se_state").val() == '';
                }
            }
        },
		se_city:{
			required: true,
		},
		
		se_address:{
		required: true,	
		},
		se_info:{
			required: true,
		},
		
		se_des:{
			required: true,
		}
	      
        
		},
        messages: {
            se_pat_name: {
                required: "Please, enter patient name.",
               
            },
			se_email: {
				required: "Please, enter your email-ID.",
				email: "Please, enter valid Email-ID"
			},
			se_pat_age:{
			 required: "Please, Enter patient age",
			  min :"Age should be greter than 0",
			  max : "Age should be less than 100"
			 
			},
		se_con_per:{
			 required: "Please, enter contact person name.",
			 
			 
			},
		se_phone_no:{
			 required: "Please, enter phone number",
			 minlength: "Please, enter valid phone number"
			 
			 
			},
			se_country:{
            required: "Please, select the country"
        },
		se_state:{
            required: "Please, select the state"
        },
		se_city:{
			required: "Please, enter city",
			
			},
		se_address:{
		required:"Please, enter address",	
			},
		se_info:{
			required: "Please, enter chief medical complaints",
			},
		se_des:{
			required: "Please, enter brief description",
			},
            	hiddencode: {
					required: "Please, enter brief description",
				}
        }
        
    });
});
$(function() {
    $('#mke_appoitment').validate({
        rules: {
        m_hsp_name: {
              required: true,
               
            },
		m_doctor_name:{
              required: true,
               
            },
		m_pat_name:{
 required: true,
			},
m_phone_no:{
			 required: true,
			 minlength: 10
			 
		},
        m_pat_email: {
              required: true,
			  email:true
               
            },
        
		
		
		m_country: {
            required: {
                depends: function(element) {
                    return $("#m_country").val() == '';
                }
            }
        },
		m_state: {
            required: {
                depends: function(element) {
                    return $("#m_state").val() == '';
                }
            }
        },
		m_city:{
			required: true,
		},
		
		m_address:{
		required: true,	
		},
		"hiddenRecaptcha": {
     required: function() {
         if(grecaptcha.getResponse() == '') {
             return true;
         } else {
             return false;
         }
     }
}
		},
        messages: {
        m_hsp_name: {
                required: "Please, enter Hospital name.",
               
            },
		m_doctor_name:{
              required:"Please, enter Doctor name.",
               
            },
		m_pat_name:{ required: "Please, enter Patient name.",},
			m_phone_no:{
			 required: "Please, enter phone number",
			 minlength: "Please, enter valid phone number"
			 
			 
			},
		m_pat_email: {
				required: "Please, enter your email-ID.",
				email: "Please, enter valid Email-ID"
			},
			
		
		m_country:{
            required: "Please, select the country",
        },
		m_state:{
		required: "Please, select the state",
        },
		m_city:{
			required: "Please, enter city",
			
			},
		m_address:{
		required:"Please, enter address .",	
			},
		
            
        }
        
    });
});
$(function() {
    $('#hsp_form').validate({
        rules: {
        vh_hsp_name: {
              required: true,
               
            },
		vh_doc_name:{
              required: true,
               
            },
		
vh_phone_no:{
			 required: true,
			 minlength: 10
			 
		},
        vh_email: {
              required: true,
			  email:true
               
            },
        
		
		
		vh_country: {
            required: {
                depends: function(element) {
                    return $("#vh_country").val() == '';
                }
            }
        },
		vh_state: {
            required: {
                depends: function(element) {
                    return $("#vh_state").val() == '';
                }
            }
        },
		vh_city:{
			required: true,
		},
		
		vh_address:{
		required: true,	
		},
		"hiddenRecaptcha1": {
     required: function() {
         if(grecaptcha.getResponse() == '') {
             return true;
         } else {
             return false;
         }
     }
}
		},
        messages: {
       vh_hsp_name: {
                required: "Please, enter Hospital name.",
               
            },
		
		vh_doc_name:{ required: "Please, enter Doctor name.",},
			vh_phone_no:{
			 required: "Please, enter phone number",
			 minlength: "Please, enter valid phone number"
			 
			 
			},
		vh_email: {
				required: "Please, enter your email-ID.",
				email: "Please, enter valid Email-ID"
			},
			
		
		vh_country:{
            required: "Please, select the country",
        },
		vh_state:{
		required: "Please, select the state",
        },
		vh_city:{
			required: "Please, enter city",
			
			},
		vh_address:{
		required:"Please, enter address .",	
			},
		
            
        }
        
    });
});

$(function() {
    $('#doctor_frm').validate({
        rules: {
       
		vd_doctor_name:{
              required: true,
               
            },
		
vd_phone_no:{
			 required: true,
			 minlength: 10
			 
		},
        vd_doc_email: {
              required: true,
			  email:true
               
            },
        
		
		
		vd_country: {
            required: {
                depends: function(element) {
                    return $("#vh_country").val() == '';
                }
            }
        },
		vd_state: {
            required: {
                depends: function(element) {
                    return $("#vh_state").val() == '';
                }
            }
        },
		vd_city:{
			required: true,
		},
		
		vd_address:{
		required: true,	
		},
		"hiddenRecaptcha1": {
     required: function() {
         if(grecaptcha.getResponse() == '') {
             return true;
         } else {
             return false;
         }
     }
}
		},
        messages: {
       
		
		vd_doctor_name:{ required: "Please, enter Doctor name.",},
			vd_phone_no:{
			 required: "Please, enter phone number",
			 minlength: "Please, enter valid phone number"
			 
			 
			},
		vd_doc_email: {
				required: "Please, enter your email-ID.",
				email: "Please, enter valid Email-ID"
			},
			
		
		vd_country:{
            required: "Please, select the country",
        },
		vd_state:{
		required: "Please, select the state",
        },
		vd_city:{
			required: "Please, enter city",
			
			},
		vd_address:{
		required:"Please, enter address .",	
			},
		
            
        }
        
    });
});

$(function() {
    $('#feedback_form').validate({
        rules: {
       
		f_pat_name:{
              required: true,
               
            },
		
f_phone_no:{
			 required: true,
			 minlength: 10
			 
		},
        f_email: {
              required: true,
			  email:true
               
            },
        
		
		
		f_feedback_on: {
            required: {
                depends: function(element) {
                    return $("#f_feedback_on").val() == '';
                }
            }
        },
		
		f_doc_name:{
			required: true,
		},
		
		f_address:{
		required: true,	
		},
			f_feedback:{
		required: true,	
		},
		"hiddenRecaptcha1": {
     required: function() {
         if(grecaptcha.getResponse() == '') {
             return true;
         } else {
             return false;
         }
     }
}
		},
        messages: {
       
		
		f_pat_name:{ required: "Please, enter Patient name.",},
			f_phone_no:{
			 required: "Please, enter phone number",
			 minlength: "Please, enter valid phone number"
			 
			 
			},
		f_email: {
				required: "Please, enter your email-ID.",
				email: "Please, enter valid Email-ID"
			},
			
		
		f_feedback_on:{
            required: "Please, select ",
        },
		
		f_doc_name:{
			required: "Please, enter the name",
			
			},
		f_address:{
		required:"Please, enter address .",	
			},
		f_feedback:{
		required:"Please, enter your feedback .",	
		},
            
        }
        
    });
});





