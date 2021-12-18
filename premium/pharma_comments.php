<?php
			ob_start();
			error_reporting(0); 
			session_start();

			$admin_id 	= $_SESSION['user_id'];
			$patient_id	=$_GET['p'];
			$episode_id	=$_GET['r'];
			//echo $patient_id; 

			//echo $episode_id;
			//Get the page name 
			//$request_uri  = str_replace("", "", $_SERVER['REQUEST_URI']);
			include('functions.php');
			if(empty($admin_id)){
				header("Location:index.php");
			}
			$curdate=date('Y-m-d');
			require_once("../classes/querymaker.class.php");
			//$objQuery = new CLSQueryMaker();

			$Doc_comments  =$_POST['Doc_comments'];
			$type  =$_POST['type'];
			$pr_id  =$_POST['pr_id'];
			
			$arrFields = array();
			$arrValues = array();
								
			$arrFields[] = 'acceptance_status';
			$arrValues[] = $type;
			
				
			if(!empty($Doc_comments))
			{
				$arrFields[] = 'accept_doc_comments';
				$arrValues[] = $Doc_comments;
			}
			
			$usercraete1=mysqlUpdate('pharma_referrals',$arrFields,$arrValues,"pr_id='".$pr_id."'");
			
			
?>