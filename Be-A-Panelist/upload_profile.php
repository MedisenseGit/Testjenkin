
<?php

require_once("../config_api.php");
include('../classes/config.php');
$conn = new mysqli($servername, $username, $password, $db);
// Check connection
if ($conn->connect_error) 
{
die("Connection failed: " . $conn->connect_error);
}
//echo"gfffff";
$profile_photo = $_FILES['file']['name']; 
//echo"gfdgh";// $profile_photo;

$tmp  = explode('.',$profile_photo);
$file_ext  = strtolower(end($tmp));

$extensions= array("jpeg","jpg","png","xls","xlsx","doc","docx","odt","txt","pdf","pptx","ppt","rtf");
$file_size =$_FILES['file']['size'];
	
	
	
	
	
		$uploaddirectory = realpath("../Doc");

	   if(basename($_FILES['file']['name']!="") && in_array($file_ext,$extensions)!== false && $file_size <= 2097152){
		$fname1=time();	
		$uploaddir = $uploaddirectory;
		$location = $uploaddir. "/" . $fname1."_1.".$file_ext;
		
				 if(move_uploaded_file($_FILES['file']['tmp_name'], $location))
				 { 
					 echo $location; 
				 }else{ 
					  echo 0; 
				 }
	   }
	?>
	
	
	