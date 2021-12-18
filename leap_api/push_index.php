<!DOCTYPE html>
 
<html>
 <head>
 <title>GCM Push Notification</title>
 </head>
 
 <body>
 
 <h1>Android Push Notification using GCM</h1>
 
 <form method='post' action='push_send.php'>
 
 <input type='text' name='apikey' placeholder='Enter API Key' />
 <br> </br>
 <input type='text' name='regtoken' placeholder='Enter Token ID' />
 <br> </br>
 <textarea name='message' placeholder='Enter a message'></textarea>
 <br> </br>
 
 <textarea name='title' placeholder='Enter Title'></textarea>
 <br> </br>
 
 <button>Send Notification</button>
 </form>
 <p>
 <?php
 //if success request came displaying success message 
 if(isset($_REQUEST['success'])){
 echo "<strong>Cool!</strong> Message sent successfully check your device...";
 }
 //if failure request came displaying failure message 
 if(isset($_REQUEST['failure'])){
 echo "<strong>Oops!</strong> Could not send message check API Key and Token...";
 }
 ?>
 </p>
 <div>Total Views <br /><span><?php echo $count; ?></span></div>
 

 </body>
 
</html>