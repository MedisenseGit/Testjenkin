$(function() {
    $('#frmConfirm').validate({
        rules: {
            check_date: {
			required: {
                depends: function(element) {
                    return $("#check_date").val() == '';
						}
					}
			},
			check_time: {
			required: {
                depends: function(element) {
                    return $("#check_time").val() == '';
						}
					}
			}			
		
		},
        messages: {
            check_date: {
                required: "Please, select prefered date",
               
            },
			check_time: {
                required: "Please, select prefered time",
               
            },
					
        }
        
    });
});
