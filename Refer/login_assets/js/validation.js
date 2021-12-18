$(function() {
    $('#medical-query').validate({
        rules: {
			desc:{required: true,},
            medical_comment: {
              required: true,
            }
			
		},
        messages: {desc:
		{required: "Please enter title"},
            medical_comment: {
                required: "Please write your Medical Query"
               
            }
			
			
            
        }
        
    });
});
$(function() {
    $('#medical_story').validate({
        rules: {
			desc:{required: true,},
            story_comment: {
              required: true,
            }
			
		},
        messages: {
			desc:
		{required: "Please enter title"},
            story_comment: {
                required: "Please write your story"
               
            }
			
			
            
        }
        
    });
});
$(function() {
    $('#review_doc').validate({
        rules: {
            doc_name: {
              required: true,
            },
			 doc_loc: {
              required: true,
            },
			 doc_spel:{
              required:{
                depends:function(element) {
                    return $("#doc_spel").val() == '';
						}
					}
            },
			 doc_hos: {
              required: true,
            },
			 doc_comment: {
              required: true,
            }
			
		},
        messages: {
            doc_name: {
                required: "Please Enter Doctor Name"
               
            },
			 doc_loc: {
              required: "Please Enter Doctor Location",
            },
			 doc_spel: {
              required: "Please Enter Doctor Specialization ",
            },
			 doc_hos: {
              required: "Please Enter Hospital",
            },
			 doc_comment: {
              required: "Please Enter your Review",
            }
			
			
            
        }
        
    });
});

$(function() {
    $('#hos_review').validate({
        rules: {
            
			 hos_name: {
              required: true,
            },
			 hos_loc: {
              required: true,
            },
			 review_hos: {
              required: true,
            }
			
		},
        messages: {
          
			 hos_name: {
              required: "Please Enter Hospital Name ",
            },
			 hos_loc: {
              required: "Please Enter Location",
            },
			 review_hos: {
              required: "Please Enter your Review",
            }
			
			
            
        }
        
    });
});
$(function() {
    $('#blog_edit').validate({
        rules: {desc:{required: true,},
            	summernote: {
              required: true,
            }
			
		},
        messages: {
          desc:
		{required: "Please enter blog title"},
			summernote: {
              required: "Please Write your Blog",
            }
			
            
        }
        
    });
});
$(function() {
    $('#story_edit').validate({
        rules: {
			story_title:{required: true,},
            	summernote: {
              required: true,
            }
			
		},
        messages: {
          story_title:
		{required: "Please enter story title"},
			summernote: {
              required: "Please Write your story",
            }
			
            
        }
        
    });
});
$(function() {
    $('#medical_cmnt').validate({
        rules: {
            	medical_cmnt_txt: {
              required: true,
            }
			
		},
        messages: {
          
			medical_cmnt_txt: {
              required: "Please Write your comment",
            }
			
            
        }
        
    });
});
$(function() {
    $('#story_cmnt').validate({
        rules: {
            	story_cmnt_txt: {
              required: true,
            }
			
		},
        messages: {
          
			story_cmnt_txt: {
              required: "Please Write your comment",
            }
			
            
        }
        
    });
});
$(function() {
    $('#review_cmnt').validate({
        rules: {
            	review_cmnt_txt: {
              required: true,
            }
			
		},
        messages: {
          
			review_cmnt_txt: {
              required: "Please Write your comment",
            }
			
            
        }
        
    });
});
$(function() {
    $('#blog_cmnt').validate({
        rules: {
            	blog_cmnt_txt: {
              required: true,
            }
			
		},
        messages: {
          
			blog_cmnt_txt: {
              required: "Please Write your comment",
            }
			
            
        }
        
    });
});
$(function() {
    $('#medical-answer').validate({
        rules: {
            medical_ans: {
              required: true,
            }
			
		},
        messages: {
            medical_ans: {
                required: "Please write your Answer"
               
            }
			
			
            
        }
        
    });
});
