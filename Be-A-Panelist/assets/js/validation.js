

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
			hosp_name:{
			required: true,
			},
			tele_contact:{
			required: true,
			},
			video_contact:{
			required: true,
			},
			available_time:{
			required: true,
			},
			doc_expert:{
			required: true,
			},
			doc_passwd:{
			required: true,
			},
			/*doc_contrubute:{
			required: true,
			},
			
			council_name:{
			required: true,
			},
			reg_num:{
			required: true,
			},
			date_registration:{
			required: true,
			},
			uploadCertificate:{
			required: true,
			},*/
			doc_passwd_confirm:{
			required: true,	
			equalTo:"#doc_passwd",
			},
			new_terms_condition: {
            required: {
                depends: function(element) {
                    return $("#new_terms_condition").val() == 0;
						}
					}
			},
			new_terms_condition2: {
            required: {
                depends: function(element) {
                    return $("#new_terms_condition2").val() == 0;
						}
					}
			},
			new_terms_condition3: {
            required: {
                depends: function(element) {
                    return $("#new_terms_condition3").val() == 0;
						}
					}
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
			hosp_name:{
			 required: "Enter your Hospital/Clinic Name",
						 
			},
			tele_contact:{
			 required: "Enter Tele Opinion contact number",
						 
			},
			video_contact:{
			 required: "Enter skype ID / Whatsapp video call number",
						 
			},
			available_time:{
			 required: "Enter Available timings",
						 
			},
			doc_contact:{
			 required: "Please, enter your mobile no.",
			 minlength: "Please, enter valid phone number"
			},
			doc_expert:{
			 required: "Enter your expertise",						 
			},
			/*doc_contrubute:{
			 required: "Enter your medical contibution",						 
			},
			council_name:{
			 required: "Enter medical council name",						 
			},
			reg_num:{
			 required: "Enter Registration no.",						 
			},
			date_registration:{
			 required: "Enter date of registration",						 
			},
			uploadCertificate:{
			 required: "Please upload registration certificate",						 
			},*/
			
			doc_passwd:{ required: "Please, enter new password.",
			},
			doc_passwd_confirm:{ required: "Please, re-enter password.",
					 equalTo: "Password mismatch",	},
			new_terms_condition:{
			 required: "Please Read the term and condition and accept",						 
			},
			new_terms_condition2:{
			 required: "Please check this condition",						 
			},
			new_terms_condition3:{
			 required: "Please check this condition",						 
			}
            
        }
        
    });
});

