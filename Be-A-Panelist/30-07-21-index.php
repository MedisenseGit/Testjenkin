!DOCTYPE html>    
<html lang="en">    
<head>    
  <meta charset="utf-8">    
  <title>Append and Remove</title>
  <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">      
  <style> 
    .user_data{background:#F0F0F0;width:500px;padding:10px;margin-bottom:5px;position:relative;} 
    .user_data .form-control{margin-bottom:10px;}
    .control-label{width:200px;float: left;}
    .remove-btn{position:absolute;right:0;bottom:10%;border:none;font-size:22px;}
  </style>    
    
</head>    
<body>    
<div class="user-details">
          <div class="user_data">
            <div class="form-group">
              <label class="control-label">Student Name</label>
              <input name="name" class="form-control"  autocomplete="false" type="text">
            </div>
            <div class="form-group">
              <label class="control-label">Permanent Address</label>
             <input name="address" class="form-control" autocomplete="false" type="text">
            </div>
            <div class="form-group">
              <label class="control-label">Phone No.</label>
             <input name="phone" class="form-control" autocomplete="false" type="text">
            </div>
           
            
          </div>
</div>
<div class="form-group">
          <input value="Add More" class="add_details" autocomplete="false" type="button">
</div>
 <script>    
  $(".add_details").click(function(){
    
      //the below code will append a new user_data div inside user-details container
   
        $(".user-details").append(' <div class="user_data"><div class="form-group"><label class="control-label">Student Name</label><input name="name" class="form-control"  autocomplete="false" type="text"></div><div class="form-group"><label class="control-label">Permanent Address</label><input name="address" class="form-control" autocomplete="false" type="text"></div><div class="form-group"><label class="control-label">Phone No.</label><input name="phone" class="form-control" autocomplete="false" type="text"></div><button class="remove-btn" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash-o" aria-hidden="true"></i></button></div>');
    
          
  });
  $("body").on("click",".remove-btn",function(e){
       $(this).parents('.user_data').remove();
      //the above method will remove the user_data div
  });
</script>    
 </body>    
</html>  