<?php
session_start();
include 'db.php';
$admin_id = $_SESSION['user_id'];

if(isset($_POST["Import"])){
	
 $file_extension = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
	 if ($file_extension == "csv") {	

		echo $filename=$_FILES["file"]["tmp_name"];
		

		 if($_FILES["file"]["size"] > 0)
		 {

		  	$file = fopen($filename, "r");
	         while (($emapData = fgetcsv($file, 10000, ",")) !== FALSE)
	         {
	    
	          //It wiil insert a row to our subject table from our csv file`
	           $sql = "INSERT into  campaign_contact_lists (`Name`, `Mobile_Number`, `Email_ID`, `Address`, `City`, `State`, `Country`, `doc_id`) 
	            	values('$emapData[0]','$emapData[1]','$emapData[2]','$emapData[3]','$emapData[4]','$emapData[5]','$emapData[6]','".$admin_id."')";
	         //we are using mysql_query function. it returns a resource on true else False on error
	          $result = mysqli_query( $sql, $conn );
				if(! $result )
				{
					echo "<script type=\"text/javascript\">
							alert(\"Invalid File: Please Upload CSV File.\");
							window.location = \"../import_contacts.php\"
						</script>";
				
				}

	         }
	         fclose($file);
	         //throws a message if data successfully imported to mysql database from excel file
	         echo "<script type=\"text/javascript\">
						alert(\"CSV File has been successfully Imported.\");
						window.location = \"../import_contacts.php\"
					</script>";
	        
			 

			 //close of connection
			mysqli_close($conn); 
				
		 	
			
		 }
	}	
	else {
		echo "<script type=\"text/javascript\">
								alert(\"Invalid File: Please Upload CSV File.\");
								window.location = \"../import_contacts.php\"
							</script>";
	}	
}
?>		 