function formValidation() {
    "use strict";
    /*----------- BEGIN validationEngine CODE -------------------------*/
    $('#popup-validation').validationEngine();
    /*----------- END validationEngine CODE -------------------------*/

    /*----------- BEGIN validate CODE -------------------------*/
    $('#frm-login').validate({
        rules: {
            txtuser: {
              required: true,
            },
					
			txtpassword:{
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
            txtuser: {
                required: "Please enter user name",
               
            },
			txtpassword:{
			     required: "Please, enter your password",	
			},
			
			new_terms_condition:{
			 required: "Please Read the term and condition and accept",						 
			},
        }
        
    });


	
	/* Referring Partner Form Validation */
		
	$('#frm-Doc-register').validate({
        rules: {
            txtDocName: {
              required: true,
            },
			slctCountry: {
            required: {
                depends: function(element) {
                    return $("#slctCountry").val() == '';
						}
					}
			},
			slctState: {
            required: {
                depends: function(element) {
                    return $("#slctState").val() == '';
						}
					}
			},
			txtCity: {
              required: true,
            },
			slctSpec: {
            required: {
                depends: function(element) {
                    return $("#slctSpec").val() == '';
						}
					}
			},
			txtHosp: {
              required: true,
            },
			txtQual: {
              required: true,
            },
			txtMob: {
              required: true,
			  minlength:10
            },
			txtEmail: {
              required: true,
            },
			
			txtMedCouncil: {
              required: true,
            },
			txtMedRegnum: {
              required: true,
            },
					
			passwd:{
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
			txtDocName: {
                required: "Enter name",
               
            },
            slctCountry: {
                required: "Select Country",
               
            },
			slctState: {
                required: "Select State",
               
            },
			txtCity: {
                required: "Enter city",
               
            },
			slctSpec: {
                required: "Select Specialization",
               
            },
			txtHosp: {
                required: "Enter Hospital Name",
               
            },
			txtQual: {
                required: "Enter qualification",
               
            },
			txtMob: {
                 required: "Please enter mobile no.",
				minlength: "Invalid mobile no."
               
            },
			txtEmail: {
                required: "Enter email address",
               
            },
			txtMedCouncil: {
                required: "Enter medical council name",
               
            },
			txtMedRegnum: {
                required: "Enter Medical Reg. No.",
               
            },
			
			passwd:{
			     required: "Please, enter your password",	
			},
			
			letters_code:{
			     required: "Please, enter captcha code",	
			},
			new_terms_condition:{
			 required: "Please Read the term and condition and accept",						 
			},
			
        }
       
    });
	
	$('#frmAddRecord').validate({
        rules: {
            txtDate: {
              required: true,
            },
					
			txtName:{
			required: true,
			},
			
			txtContact:{
			required: true,
			minlength: 10
			},
			txtLoc:{
			required: true,
			},
			txtAddress:{
			required: true,
			},
			txtSubject:{
			required: true,
			},
			txtDesc:{
			required: true,
			}
				
		},
        messages: {
            txtDate: {
                required: "Please enter date",
               
            },
			 txtName: {
                required: "Please enter Name",
               
            },
			txtContact: {
                required: "Please enter mobile no.",
				minlength: "Invalid mobile no."
            },
			
			txtLoc:{
			     required: "Please, enter Location",
			},
			txtAddress:{
			     required: "Please, enter address",
			},
			txtSubject:{
			     required: "Please, enter subject",
			},
			txtDesc:{
			     required: "Please, enter description",
			},
			
        }
       
    });
	
	$('#frmActivity').validate({
        rules: {
          					
			slctCom: {
            required: {
                depends: function(element) {
                    return $("#slctCom").val() == '';
						}
					}
			},
			
			txtRef:{
			required: true,
			},
			txtDesc:{
			required: true,
			}
				
		},
        messages: {
            slctCom: {
                required: "Please select",
               
            },
			 txtRef: {
                required: "Please enter Referer Name",
               
            },
			txtDesc: {
                required: "Please enter description",
				
            },
			
        }
       
    });
	
	//Referrer Validation
	$('#frmrefer').validate({
        rules: {
          					
			selectref: {
            required: {
                depends: function(element) {
                    return $("#selectref").val() == '';
						}
					}
			}				
		},
        messages: {
            selectref: {
                required: "Please select",
               
            },
			 			
        }
       
    });
	
	

	$('#frmAddHosp').validate({
        rules: {
          					
			
			txtHospName:{
			required: true,
			},
			txtAddress:{
			required: true,
			},
			slctState: {
            required: {
                depends: function(element) {
                    return $("#slctState").val() == '';
						}
					}
			},	
			txtCity:{
			required: true,
			},
			txtMobile:{
			required: true,
			},
			txtEmail:{
			required: true,
			},
			slctComm: {
            required: {
                depends: function(element) {
                    return $("#slctComm").val() == '';
						}
					}
			},
		},
        messages: {
            txtHospName: {
                required: "Enter hospital name",
               
            },
			txtAddress: {
                required: "Enter Address",
               
            },
			slctState: {
                required: "Select State",
               
            },
			txtCity: {
                required: "Select City",
               
            },
			txtMobile: {
                required: "Enter contact number",
               
            },
			txtEmail: {
                required: "Enter Email Address",
               
            },
			slctComm: {
                required: "Select Communication Status",
               
            }
			 			
        }
       
    });
	
	$('#frmAddAmenity').validate({
        rules: {
          					
			
			slctHosp: {
            required: {
                depends: function(element) {
                    return $("#slctHosp").val() == '';
						}
					}
			},	
			txtServices:{
			required: true,
			},
			
		},
        messages: {
            slctHosp: {
                required: "Select Hospital",
               
            },
			 txtServices: {
                required: "Select Hospital Services",
               
            }
			
			 			
        }
       
    });
	
	$('#frmAddDoctor').validate({
        rules: {
          					
			
			txtDoc:{
			required: true,
			},
			slctState: {
            required: {
                depends: function(element) {
                    return $("#slctState").val() == '';
						}
					}
			},
			txtCity:{
			required: true,
			},
			selectHosp: {
            required: {
                depends: function(element) {
                    return $("#selectHosp").val() == '';
						}
					}
			},
			slctSpec: {
            required: {
                depends: function(element) {
                    return $("#slctSpec").val() == '';
						}
					}
			},
			txtQual:{
			required: true,
			},
			txtExp:{
			required: true,
			},
			txtMobile:{
			required: true,
			},
			txtEmail:{
			required: true,
			},
			inopcost:{
			required: true,
			},
			onopcost:{
			required: true,
			},
			conscharge:{
			required: true,
			},
			
		},
        messages: {
            txtDoc: {
                required: "Enter name",
               
            },
			slctState: {
                required: "Select State",
               
            },
			txtCity: {
                required: "Select City",
               
            },
			selectHosp: {
                required: "Select Hospital",
               
            },
			slctSpec: {
                required: "Select Specification",
               
            },
			txtQual: {
                required: "Enter qualification",
               
            },
			txtExp: {
                required: "Enter year of experience",
               
            },
			txtQual: {
                required: "Enter qualification",
               
            },		
			txtMobile: {
                required: "Enter contact number",
               
            },
			txtEmail: {
                required: "Enter Email Address",
               
            },
			inopcost: {
                required: "Enter inperson opinion cost",
               
            },
			onopcost: {
                required: "Enter online opinion cost",
               
            },
			conscharge: {
                required: "Enter consultation charge",
               
            }
			 			
        }
       
    });
    
    /*----------- END validate CODE -------------------------*/
}