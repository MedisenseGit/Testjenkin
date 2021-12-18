$(function() {
    $('#appointment_form').validate({
        rules: {
            check_date: {
              required: true,
            },
			
           check_time: {
              required: true,
			},
			        					
			se_pat_name:{
				required: true,	
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
			},
			se_email:{
				required: true,	
			},
			se_country:{
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
			}
		},
        messages: {
            check_date: {
                required: "Please, select preferred date",
               
            },
			check_time:{
			     required: "Please, select preferred time",	
			},
			se_pat_name:{
				required: "Please, enter patient name"
			},
			se_pat_age:{
				required: "Please, enter age"
			},
			se_con_per:{
				required: "Please, enter contact person"
			},
			se_phone_no:{
			 required: "Please, enter your mobile no.",
			 minlength: "Please, enter valid phone number"
			},
			se_email:{
			 required: "Please, enter email address",
						 
			},
			se_state:{
			 required: "Please, select the state",
						 
			},
			se_city:{
			 required: "Please, enter city",
						 
			},
			se_address:{
				required: "Please, enter address",
				
			}
            
        }
        
    });
});

$(function() {
    $('#second_opinion').validate({
        rules: {
            se_pat_name: {
              required: true,
               
            },
			se_pat_name_list:{
				            required: {
                depends: function(element) {
                    return $("#se_pat_name_list").val() == '';
                }
            }
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
		se_treat_doc:{
		required: true,	
		},
		se_treat_hosp:{
		required: true,	
		},
		se_info:{
			required: true,
		},
		
		se_des:{
			required: true,
		},
		se_query:{
			required: true,
		},
		letters_code:{
				required: true,
				
		}
		},
        messages: {
            se_pat_name: {
                required: "Please, enter patient name.",
               
            },
			se_pat_name_list:{
			                required: "Please, select patient name.",	
			},
			se_email: {
				required: "Please, enter your email-ID.",
				email: "Please, enter valid Email-ID"
			},
			se_pat_age:{
			 required: "Please, Enter patient age"
			 			 
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
		se_treat_doc:{
		required: "Please, enter current treating doctor",	
		},
		se_treat_hosp:{
		required: "Please, enter current treating hospital",	
		},
		se_info:{
			required: "Please, enter chief medical complaints",
			},
		se_des:{
			required: "Please, enter brief description",
			},
		se_query:{
			required: "Please, enter your query to the doctor",
			},
		letters_code:{
				required: "Please, enter code here",
				
		}
            
        }
        
    });
});
