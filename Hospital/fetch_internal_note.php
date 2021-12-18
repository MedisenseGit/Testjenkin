<?php
ob_start();
session_start();
error_reporting(0);  

$admin_id = $_SESSION['user_id'];

require_once("../classes/querymaker.class.php");
	
$getpatient = mysqlSelect("*","doc_my_patient","patient_id='".$_POST['patient_id']."'","","","","");
$getNotes= mysqlSelect("*","patient_internal_notes","doc_id='".$getpatient[0]['doc_id']."' and patient_id='".$_POST['patient_id']."'","internal_note_id desc","","","");
				
$output = '';
$i=0;
foreach($getNotes as $row)
	
{
	
	if($i%2==0){
		$commentSide="left";
	}
	else
	{
		$commentSide="right";
	}
 $output .= '
 <div class="'.$commentSide.'">
                    <div class="author-name">
                        '.$row["comment_sender_name"].' <small class="chat-date">
                        '.date('d M Y H:i', strtotime($row["date_time"])).'
                    </small>
                    </div>
                    <div class="chat-message active">
                        '.$row["notes"].'
                    </div>

                </div>';
 $i++;
}

echo $output;


?>