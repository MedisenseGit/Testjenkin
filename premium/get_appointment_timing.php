<?php
ob_start();
error_reporting(0); 
session_start();


$admin_id = $_SESSION['user_id'];
if(empty($admin_id)){
	header("Location:index.php");
}
require_once("../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();

$GetTimeSlot = mysqlSelect("*", "doc_appointment_slots", "doc_id='".$admin_id."' and doc_type='1' and hosp_id='".$_GET["hospid"]."'", "", "", "", "");
											


?>
							<form action="add_details.php" method="post" class="form-horizontal">
								<div class="form-group"><label class="col-sm-2 control-label">No. of Patient per hour </label>

                                    <div class="col-sm-4"><input type="number" name="num_slot" value="<?php echo $GetTimeSlot[0]['num_patient_hour']; ?>"  class="form-control"></div>
                                </div>
										<table border="1" width="100%">
									<tr>
									<td style="text-align:center; font-weight:bold;">Schedule</td>
									<?php
									$getDays = mysqlSelect("*","seven_days ","","","","","");


										foreach($getDays as $daysList) 
										{
											?>
											<td style="text-align:center; font-weight:bold;"><?php echo $daysList['da_name']; ?></td>
											<?php
										}

									?>
									</tr>
									<?php

									$getTimings = mysqlSelect("*","timings ","","","","","");
										$i=0;
										foreach($getTimings as $timeList) 
										{
											
											
											$i++;
											$j=0;
											?>
											<tr>
											<td style="text-align:center; "><input type="hidden" name="<?php echo "time_id" .$i ?>" value="<?php echo $timeList['Timing_id']?>" /><?php echo $timeList['Timing']?></td>
											<?php
											$getDaycount = mysqlSelect("*","seven_days ","","","","","");
											foreach($getDaycount as $countList) 
												{
													
													$j++;
													 $chkDay = mysqlSelect("*","doc_time_set","doc_id='".$admin_id."' and hosp_id='".$_GET["hospid"]."' and time_set='1' and day_id=".$j." and time_id=".$i,"","","","");
													?> 	
														 <td style="text-align:center;">
														
														 <input type="hidden" size="4" value="<?php echo $countList['day_id'] ?>" name="<?php echo "day_id" . $i . $j ?>">
															<?php if($chkDay==true){ ?>
															<div class="checkbox checkbox-success checkbox-inline">
																<input type="checkbox" id="inlineCheckbox<?php echo $i . $j; ?>" checked="true" value="1" name="<?php echo "time". $i . $j ?>">
																<label for="inlineCheckbox<?php echo $i . $j; ?>"></label>
															</div>
															
															<?php } else { ?>
															<div class="checkbox checkbox-success checkbox-inline">
																<input type="checkbox" id="inlineCheckbox<?php echo $i . $j; ?>" value="1" name="<?php echo "time". $i . $j ?>">
																<label for="inlineCheckbox<?php echo $i . $j; ?>"></label>
															</div>
															
															<?php } ?>
															<input type="hidden" name="limit_j" value="<?php echo $j; ?>" size="4">
															</td>
														
													<?php
												}
												
												
										
											?></tr>
											<input type="hidden" name="limit_i" value="<?php echo $i; ?>" size="4">
											<?php
											

										}


									?>
									</table>
									<br>
									<div class="form-group">
								<div class="col-sm-4 pull-right">
								<button type="submit" name="edit_timings" id="edit_timings" class="btn btn-primary block full-width m-b ">UPDATE</button>
								</div>
								</div>
								</form>
								
							
					