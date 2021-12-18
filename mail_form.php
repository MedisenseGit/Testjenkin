<?php ob_start();

$mail_content1 .='<p>Hi Ambarish</p><table width="100%" border="1" cellpadding="5" cellspacing="0" bgcolor="#FFFFCC">
                       
						   <tr>
                           <td  valign="top" bgcolor="#E3DEDE"><p style="font-size:14px; line-height:20px;"><center><img src="http://www.indiamedicalholiday.com/Medisense-CRM/images/medisense_logo.jpg" width="150" height="50"/></center><br>
						   You have been refered successfully through our Medisense Health Assistance Team. Our refered doctors will contact you soon to provide the best solutions to your any health problem.</p>
						   <p><h3 style="color:#C51A1A; font-size:18px; font-weight:bold;">Medisense Healthcare Solutions, which has been working on patient centric healthcare initiatives in India and abroad.</h3></p>
						   
						   <p style="font-size:14px; line-height:20px;">Please feel free to reach out to us on <b>+91-70266 46022</b> anytime for any kind of health related queries. You might also want to take a quick look on a brief note on Medisense Healthcare Assistance below website link.<br><a href="http://www.medisensehealth.com" target="_blank">www.medisensehealth.com</a></p>
						   <p><b>Thank you,<br>
							The Team - Medisense Healthcare Solutions<b></p></td>
                           
                          </tr>
						</table>';
$ToEmail1 = 'ambarishbhat@gmail.com'; 
$mailheader1 = "" .
           "Reply-To:medical@medisense.me\r\n" .
           "From:medical@medisense.me\r\n" .
           "X-Mailer: PHP/" . phpversion();

$EmailSubject1 = 'Thank you for registered with us'; 
$mailheader1 .= 'MIME-Version: 1.0'."\r\n";
$mailheader1 .= "Content-type: text/html; charset=iso-8859-1\r\n"; 
mail($ToEmail1, $EmailSubject1, $mail_content1, $mailheader1) or die ("Failure");
		
?>