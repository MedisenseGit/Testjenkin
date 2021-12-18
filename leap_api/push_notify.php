<!DOCTYPE html>
 
<html>
 <head>
 <title>Medisense Leap</title>
 </head>
 
 <body>
 
 <h2>Send Push Notification</h2>
 
 <form method='post' action='push_notification.php'>
 
 <input type='text' name='apikey' placeholder='Enter API Key' />
 <br> Server Key: AAAAND37GLM:APA91bGkB_ULfstuJsYUvZf7NdBddBKAiUdjaDT_tRRSc5X1jvRFOH8dmjbgw59xzx5MwMmYZD1LLbMo_EisdvxFdopedCq3MQda1VGTD8_AvOJwBv3gXiNCHLZa5h5tbbTnmVIU-xCD
 <br> </br> 
 <input type='text' name='regtoken' placeholder='Enter Token ID' />
 <br>  Token ID: em_qvxwB5Cc:APA91bFwdPCVXWLb3Ng6AcLmMCwMFh9Moexo-uVEJqjz-w4HHDrr8-FSBs9n8L61g6i4h-VwVWURZwusc3ArXox1bZidp8fGNVMLyepmNx4mhWNyQm5VRc4kaJ7ixFniIkBaRtsgmONa
 <br> </br> 
 
 <textarea name='title' placeholder='Enter Title'></textarea>
 <br> </br>
 
 <textarea name='message' placeholder='Enter a message'></textarea>
 <br> </br>
 
 <textarea name='type' placeholder='Enter type'></textarea>
  Enter 1 for Blogs, 2 for Offers/Events
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