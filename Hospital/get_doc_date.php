<?php
ob_start();
error_reporting(0);
session_start();

$admin_id = $_SESSION['user_id'];
//echo "hello"; exit;

require_once("../classes/querymaker.class.php");

ob_start();

//$_SESSION['visit_date']=$_POST["day_val"];
if(!empty($_POST["doc_id"]) && empty($_POST["hosp_id"])) 
{ ?>
	<option disabled="disabled" selected value="">Select Date</option>
                                     <?php 										 
										for($i=1; $i<=20; $i++) { ?>
                                        
                                    <?php $date = strtotime('+' . $i . 'day');
									$chkdate=date('D', $date);
									$getDocDays= mysqlSelect("DISTINCT(b.day_id) as DayId","doc_time_set as a left join seven_days as b on a.day_id=b.day_id","a.doc_id='".$_POST["doc_id"]."' and a.hosp_id='".$admin_id."'","","","","");
									  
									   $current_date=date('d-m-Y', $date);
									   $date_1 = new DateTime($current_date);
									   $current_time_stamp=$date_1->format("U"); 
									  $checkHoliday= mysqlSelect("holiday_id","doc_holidays","doc_id='".$_POST["doc_id"]."' and doc_type='1' and DATE_FORMAT(holiday_date,'%d-%m-%Y')='".$current_date."'","","","","");
									

									   $check_holiday=0; 
									 
									
									   foreach($getDocDays as $daylist) { 
									   $getDayName= mysqlSelect("*","seven_days","day_id='".$daylist['DayId']."'","","","","");
									
									   ?>

									<?php 
									if(date('D', $date)==$getDayName[0]['da_name'] && COUNT($checkHoliday)==0){ ?>
                                     <option value="<?php echo date('Y-m-d', $date);?>" >
                                         
                                         <?php
                                             if($i==0) { echo "Today";} else if($i==1){ echo "Tomorrow";} else { echo date('D d-m-Y', $date);}
                                         
                                         ?>
                                         </option>
                                     <?php 
									}
									   }
									 } 
									 
									 $curdate=date('Y-m-d');
									 $day_val=date('D', strtotime($curdate));
								$chkDocTimeSlot= mysqlSelect("*","seven_days as a left join doc_time_set as b on a.day_id=b.day_id","b.doc_id='".$_POST["doc_id"]."' and b.hosp_id='".$admin_id."' and a.da_name='".$day_val."'","","","","");
								
								$docNumPatientHour= mysqlSelect("num_patient_hour","doc_appointment_slots","doc_id='".$_POST["doc_id"]."' and doc_type='1' and hosp_id='".$admin_id."'","","","","");
								$totalPatientToday = count($chkDocTimeSlot)*$docNumPatientHour[0]['num_patient_hour'];
		
								$curBookingCount= mysqlSelect("*","appointment_token_system","app_date='".$curdate."' and doc_id='".$_POST["doc_id"]."' and doc_type='1' and hosp_id='".$admin_id."'","","","","");
								if((count($chkDocTimeSlot)==0) || (count($curBookingCount)>$totalPatientToday)){ ?>
								
							<option  value="" style="display:none" id="timeSlotCount"><?php echo "true";?></option>	
							<option  value="" style="display:none" id="doctimeSlotCount"><?php echo count($chkDocTimeSlot);?></option>								
								<?php	}		
								else{ ?>
									<option  value="" style="display:none" id="timeSlotCount"><?php echo "false";?></option>
									<option  value="" style="display:none" id="doctimeSlotCount"><?php echo count($chkDocTimeSlot);?></option>										
							<?php	}								
}

if(!empty($_POST["doc_id"]) && !empty($_POST["hosp_id"]))
{
	$doc_id		=	$_POST["doc_id"];
	$hosp_id	=	$_POST["hosp_id"];
	$getDocDay	= mysqlSelect("DISTINCT(b.day_id) as DayId","doc_time_set as a left join seven_days as b on a.day_id=b.day_id","a.doc_id='".$doc_id."' and a.hosp_id='".$hosp_id."'","","","","");
	//if(!empty($getDocDay))
	//{
		$str	=	"";
		$str=$str.'<div class="row well well-lg">';
		$str =$str.'<div class="scrolling-wrapper">';
		for($i=1; $i<=20; $i++)
		{ 
			$date 		= 	strtotime('+' . $i . 'day');
			$chkdate	=	date('D', $date);
			
			$getDocDays	= mysqlSelect("DISTINCT(b.day_id) as DayId","doc_time_set as a left join seven_days as b on a.day_id=b.day_id","a.doc_id='".$doc_id."' and a.hosp_id='".$hosp_id."'","","","","");
			
			
			$current_date	=	date('d-m-Y', $date);
			
			$checkHoliday	= 	mysqlSelect("holiday_id","doc_holidays","doc_id='".$doc_id."' and doc_type='1' and DATE_FORMAT(holiday_date,'%d-%m-%Y')='".$current_date."'","","","","");
			
			$date_1 			=	 new DateTime($current_date);
			$current_time_stamp	=	$date_1->format("U"); 
			$check_holiday		=	0; 
			 
			foreach($getDocDays as $daylist)
			{ 
				$getDayName	= mysqlSelect("*","seven_days","day_id='".$daylist['DayId']."'","","","","");
				if((date('D', $date)==$getDayName[0]['da_name']) && COUNT($checkHoliday)==0)
				{ 
					$selected_date 	=	'"'.date('Y/m/d', $date).'"';//	date('Y/m/d', $date);
					$val_date		=	date('D, d M', $date); //	date('D, d M', $date);
					$val_date1		=	'"'.date('D', $date).'"'; //date('D', $date);
					
					$str =$str."<input type='button' id='check_date' name='check_date' value=' $val_date 'style='min-width: 120px;background-color:rgba(0, 0, 0, .03); border:solid 1px; border-radius:5px; margin-right:15px;padding-bottom:5px; padding-top:5px;margin-bottom:10px;' onclick='getDateFun(this.value,$val_date1,$selected_date);' readonly />";
		
					 
				}
			}
		} 
		$str =$str."</div>";
		$str =$str."</div>";
	//}	
}
echo $str;
?>
