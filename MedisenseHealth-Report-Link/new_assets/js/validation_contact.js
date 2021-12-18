$(function() {
    $('#app_form').validate({
        rules: {
            m_hsp_name: {
              required: true,
            },
			m_department: {
            required: {
                depends: function(element) {
                    return $("#m_department").val() == '';
						}
					}
			},				  					
			m_doctor_name:{
				required: true,	
			},		
			check_date:{
			required: {
                depends: function(element) {
                    return $("#check_date").val() == '';
						}
					}
			},
			apt_time:{
			required: {
                depends: function(element) {
                    return $("#apt_time").val() == '';
						}
					}
			},
			check_name:{
			required: true,
			},
			check_age:{
			required: true,
			},
			chek_gen:{
			required: {
                depends: function(element) {
                    return $("#chek_gen").val() == '';
						}
					}
			},
			check_mobile:{
			required: true,
			},
			check_email:{
			required: true,
			},
			
			check_country:{
			required: {
                depends: function(element) {
                    return $("#check_country").val() == '';
						}
					}
			},
			check_state:{
			required: {
                depends: function(element) {
                    return $("#check_state").val() == '';
						}
					}
			},
			check_city:{
			required: true,
			},
			check_address:{
			required: true,
			},
			letters_code:{
				required: true,
				
			}
		
		},
        messages: {
            m_hsp_name: {
                required: "Please, enter hospital name",
               
            },
			m_department:{
			     required: "Please, select department",	
			},
			m_doctor_name:{
				required: "Please, enter doctor name",
			},
			check_date:{
			 required: "Please, select prefered date",
			
			},
			apt_time:{
				required: "Please, select prefered time",
			},
			check_name:{
				required: "Please, enter patient name",
			},
			check_age:{
				required: "Please, enter age",
			},
			chek_gen:{
				required: "Please, select gender",
			},
			check_mobile:{
				required: "Please, enter mobile no.",
			},
			check_email:{
				required: "Please, enter email id",
			},
			check_country:{
				required: "Please, select country",
			},
			check_state:{
				required: "Please, select state",
			},
			check_city:{
				required: "Please, enter your city",
			},
			check_address:{
				required: "Please, enter address",
			},
			letters_code:{
				required: "Please, enter captcha code",
			},
            
        }
        
    });
});



$(function() {
    $('#make_pay').validate({
        rules: {
            pay_pat_id: {
              required: true,
            },
			service: {
            required: {
                depends: function(element) {
                    return $("#service").val() == '';
						}
					}
			},				  					
			pay_email:{
				required: true,	
			},		
			pay_mobile:{
			required: true,
			},
			pay_op_cost:{
			required: true,
			}
		
		},
        messages: {
            pay_pat_id: {
                required: "Please, enter Patient ID",
               
            },
			service:{
			     required: "Please, select service type",	
			},
			pay_email:{
				required: "Please, enter valid email address"
			},
			pay_mobile:{
			 required: "Please, enter your mobile no.",
			 minlength: "Please, enter valid phone number"
			},
			pay_op_cost:{
				required: "Please, enter amount",
			},
            
        }
        
    });
});




$(function() {
    $('#vol_doctor').validate({
        rules: {
            doc_name: {
              required: true,
            },
			
           specialization: {
              required: true,
			},
			doc_gender: {
            required: {
                depends: function(element) {
                    return $("#doc_gender").val() == '';
						}
					}
			},
			
			doc_qual: {
              required: true,
			},
			doc_exp: {
              required: true,
			},
			doc_country: {
            required: {
                depends: function(element) {
                    return $("#doc_country").val() == '';
						}
					}
			},
			doc_state: {
            required: {
                depends: function(element) {
                    return $("#doc_state").val() == '';
						}
					}
			},
        					
			doc_mail:{
				required: true,	
			},
			
			doc_contact:{
				required: true,	
			},
			doc_city:{
			required: true,
			},
			doc_expert:{
			required: true,
			},
			doc_contrubute:{
			required: true,
			}
		},
        messages: {
            doc_name: {
                required: "Please, enter doctor name",
               
            },
			specialization:{
			     required: "Enter your specialization",	
			},
			doc_gender:{
				required: "Select gender",
			},
			
			doc_qual:{
				required: "Enter your qualification",
			},
			doc_exp:{
				required: "Enter year of experience",
			},
			doc_country:{
			 required: "Select Country",
						 
			},
			doc_state:{
			 required: "Select State",
						 
			},
			doc_city:{
			 required: "Enter City",
						 
			},
			doc_mail:{
			 required: "Enter Email Id",
						 
			},
			doc_contact:{
			 required: "Please, enter your mobile no.",
			 minlength: "Please, enter valid phone number"
			},
			doc_expert:{
			 required: "Enter your expertise",						 
			},
			doc_contrubute:{
			 required: "Enter your medical contibution",						 
			}
            
        }
        
    });
});

$(function() {
    $('#med_campaign').validate({
        rules: {
            doc_name: {
              required: true,
            },
			
           city: {
              required: true,
			},
			se_state: {
            required: {
                depends: function(element) {
                    return $("#se_state").val() == '';
						}
					}
			},
        					
			doc_treated:{
				required: true,	
			},
			patient_gender:{
				required: {
                depends: function(element) {
                    return $("#patient_gender").val() == '';
						}
					}	
			},
			patient_age:{
				required: true,	
			},
			more_info:{
			required: true,
			},
			person_name:{
			required: true,
			},
			person_mobile:{
			required: true,
			},
		
			letters_code:{
				required: true,
				
			}
		},
        messages: {
            doc_name: {
                required: "Please, enter doctor name.",
               
            },
			city:{
			     required: "Please, enter doctor city.",	
			},
			se_state:{
				required: "Please, select the state"
			},
			doc_treated:{
				required: "Please, enter Condition which he treated"
			},
			patient_gender:{
				required: "Please, select patient gender"
			},
			patient_age:{
				required: "Please, enter patient age"
			},
			more_info:{
			 required: "Please, enter story",
						 
			},
			person_name:{
			 required: "Please, enter your name",
						 
			},
			person_mobile:{
			 required: "Please, enter your mobile no.",
			 minlength: "Please, enter valid phone number"
			},
			
			letters_code:{
				required: "Please, enter code here",
				
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
		se_depart: {
            required: {
                depends: function(element) {
                    return $("#se_depart").val() == '';
                }
            }
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
		looking_for:{
			required: true,
		},
		letters_code:{
				required: true,
				
		},
		new_terms_condition: {
            required: {
                depends: function(element) {
                    return $("#new_terms_condition").val() == 0;
						}
					}
			
            
       
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
			
			se_depart:{
		required:"Please, select specialization",	
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
		looking_for:{
			required: "Please, choose one of the option",
			},	
		letters_code:{
				required: "Please, enter code here",
				
		},
		new_terms_condition:{
				required: "Please Read the term and condition and accept",
				
		}
            
        },
		errorPlacement: function(error, element) 
{
if ( element.is(":radio") ) 
{
error.appendTo( element.parents('.form-group') );
}
else
{ // This is the default behavior 
error.insertAfter( element );
}
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
		letters_code:{
			required: true,
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
		letters_code:{
				required: "Please, enter code here",
				
		}
		
            
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
		letters_code:{
				required: true,
				
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
		letters_code:{
				required: "Please, enter code here",
				
		}
            
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
		letters_code:{
				required: true,
				
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
        
		letters_code:{
				required: "Please, enter code here",
				
		}		
        }
        
    });
});



$(function() {
    $('#subscrption-form').validate({
        rules: {
       
		sub_name:{
              required: true,
               
            },
		

        sub_email: {
              required: true,
			  email:true
               
            },
        
		sub_age:{
			 required: true,
			   min:0,
			   max:100
			 
		},
		sub_phone_no:{
			 required: true,
			 minlength: 10
			 
		},
		sub_country:{
            required: {
                depends: function(element) {
                    return $("#sub_country").val() == '';
                }
            }
        },
		sub_state:{
            required: {
                depends: function(element) {
                    return $("#sub_state").val() == '';
                }
            }
        },
		sub_city:{
			required: true,
		},
		
		sub_address:{
		required: true,	
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
       
		
		sub_name:{ required: "Please, enter subscriber name.",},
			
		sub_email: {
				required: "Please, enter your email-ID.",
				email: "Please, enter valid Email-ID"
			},
			
		sub_age:{
			 required: "Please, enter your age",
			 max : "Age should be less than 100"
			 
			},
			sub_phone_no:{
			 required: "Please, enter phone number",
			 minlength: "Please, enter valid phone number"	 
			 
			},
			sub_country:{required: "Please, select the country.",
			},
		sub_state:{  required: "Please, select the state.",
			},
	sub_city:{	required: "Please, enter city.",
			},
		sub_address:{
		required:"Please, enter address.",	
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
    $('#mke_appoitment').validate({
        rules: {
			m_place:{
				required: true,
			},
			m_hsp_name:{
				 required: {
                depends: function(element) {
                    return $("#m_hsp_name").val() == '';
                }
				}
			},
			h_hsp_name:{
				required: true,
			},
			m_Speciality:{
				required: {
                depends: function(element) {
                    return $("#m_Speciality").val() == '';
                }
				}
				},
							m_doc_name:{
				 required: {
                depends: function(element) {
                    return $("#m_doc_name").val() == '';
                }
				 }
				},
				
			/*m_pat_name:{
				 required: {
                depends: function(element) {
                    return $("#m_pat_name").val() == '';
                }
				}
			},*/
			apt_date:{
				required:true
				
			},
			apt_time:{
				required:true
			},
			m_pat_f_name:{
				required:true
			},
			m_pat_l_name:{
				required:true
			},
m_number:{
	 required: true,
			 minlength: 10

},
m_h_number:{
	 required: true,

},
m_pat_f_name1:{
	required: true,

},
m_pat_l_name1:{
				required:true
			},
m_number1:{
	 required: true,
			 minlength: 10

},
m_pat_fat_name:{
	required: true,

},
m_pat_mot_name:{
				required:true
			},
m_age:{
	 required: true,
	 min:0,
max:100

},
m_rel:{
				 required: {
                depends: function(element) {
                    return $("#m_rel").val() == '';
                }
				}
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
m_package_name:{
	required: true,	
},
pack_code1:{
	required:true,
}	
		},
	 messages: {
			m_place:{
				required: "Please, Enter place name."
				
			},
			m_hsp_name:{
				required: "Please, select the hospital."
			},
			h_hsp_name:{
				required: "Please, Enter the Hospital name."
			},
			m_Speciality:{
				required: "Please, select the Specialisation."
			},
			m_doc_name:{
				required: "Please, Enter the Doctor's name."
			},
/*m_pat_name:{
	required: "Please, select the patient name."
}	,*/
apt_date:{
	required: "Please, select the Date."
	
},

apt_time:{
	required: "Please, select the Time."
	
},
m_pat_f_name:{
	required: "Please, Enter Patient first name."
	
},
m_pat_l_name:{
	required: "Please, Enter Patient last name."
},
m_number:{
		required: "Please, enter phone number",
			 minlength: "Please, enter valid phone number"
	
},
m_h_number:{
	required: "Please, enter hospital number",
},
m_pat_f_name1:{
	required: "Please, Enter Patient first name."
	
},
m_pat_l_name1:{
	required: "Please, Enter Patient last name."
},
m_number1:{
		required: "Please, enter phone number",
			 minlength: "Please, enter valid phone number"
	
},
m_pat_fat_name:{
	required: "Please, Enter Patient father's name."
	
},
m_pat_mot_name:{
	required: "Please, Enter Patient mother's name."
},
m_age:{
		required: "Please, enter age",
			min :"Age should be greter than 0",
			  max : "Age should be less than 100"
	
},
m_rel:{
            required: "Please, select the Religion"
        },
 m_country:{required: "Please, select the country.",
			},
		m_state:{  required: "Please, select the state.",
			},
	m_city:{	required: "Please, enter city.",
			},
		check_address:{
		required:"Please, enter address.",	
			},
			m_package_name:{
				required:"Please, enter package name.",	
			},
			pack_code1:{
				required:"Please, enter code.",	
			}
	
		}
	});
});

$(function() {
    $('#pha_form').validate({
        rules: {
			ph_state: {
            required: {
                depends: function(element) {
                    return $("#ph_state").val() == '';
                }
            }
        },
		 ph_patient_name: {
              required: true,
               
            },
			ph_person_name:{
			 required: true,
					 
		},
		ph_mobile_no:{
			 required: true,
			 minlength: 10
			 
		},
		ph_email_id: {
              required: true,
			  email:true
               
            },
			ph_city:{
			required: true,
		},
		ph_address:{
		required: true,	
		},
		letters_code:{
				required: true,
				
		}
		},
        messages: {
			ph_state:{
            required: "Please, select the state"
        },
		ph_patient_name: {
                required: "Please, enter patient name.",
               
            },
				ph_person_name:{
			 required: "Please, enter contact person name.",
			 
			 
			},
			ph_mobile_no:{
			 required: "Please, enter phone number",
			 minlength: "Please, enter valid phone number"
			 
			 
			},
			ph_email_id: {
				required: "Please, enter your email-ID.",
				email: "Please, enter valid Email-ID"
			},
			ph_city:{
			required: "Please, enter city",
			
			},
			ph_address:{
		required:"Please, enter address",	
			},
			letters_code:{
				required: "Please, enter code here",
				
		}
			}
        
    });
});