<?php
ob_start();
error_reporting(0);
session_start();

require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
ob_start();

$doc_id = $_SESSION['docid']



?>
	<option selected value="0">Select Date</option>
                                     <?php 
									 for($i=0; $i<=20; $i++) { ?>
                                        
                                    <?php $date = strtotime('+' . $i . 'day');
									$chkdate=date('D', $date);
									$getDocDays= $objQuery->mysqlSelect("DISTINCT(b.day_id) as DayId","doc_time_set as a left join seven_days as b on a.day_id=b.day_id","a.doc_id='".$doc_id."' and hosp_id = '".$_POST['hosp_id']."'","","","","");
									
									   $current_date=date('d-m-Y', $date);
									   $date_1 = new DateTime($current_date);
									   $current_time_stamp=$date_1->format("U"); 
									  

									   $check_holiday=0; 
									 
									
									   foreach($getDocDays as $daylist) { 
									   $getDayName= $objQuery->mysqlSelect("*","seven_days","day_id='".$daylist['DayId']."'","","","","");
									
									   ?>

									<?php 
									if(date('D', $date)==$getDayName[0]['da_name']){ ?>
                                     <option value="<?php echo date('Y-m-d', $date);?>" >
                                         
                                         <?php
                                            echo date('D d-m-Y', $date);
                                         ?>
                                         </option>
                                     <?php 
									}
									   }
									 }
									
?>
