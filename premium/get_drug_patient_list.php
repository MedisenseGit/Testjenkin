<?php
ob_start();
session_start();
error_reporting(0);  

$admin_id = $_SESSION['user_id'];

$drug_id = $_POST['drug_id'];
//$drug_id = 5;
require_once("../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();	
$getPatientList = mysqlSelect("DISTINCT(a.patient_id) as patient_id","doc_patient_episodes as a left join doc_patient_episode_prescriptions as b on a.episode_id=b.episode_id","b.pp_id='".$drug_id."' and a.admin_id='".$admin_id."'","a.patient_id desc","","","");
$getDrug = mysqlSelect("prescription_trade_name,prescription_generic_name","doc_patient_episode_prescriptions","pp_id='".$drug_id."'","","","","");

?>
<div class="col-lg-6">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                      <h5> Given Medicine: <span class="text-navy pull-right m-l"><?= $getDrug[0]['prescription_trade_name']." - ".$getDrug[0]['prescription_generic_name']; ?></span></h5>
                        
                    </div>
                    <div class="ibox-content">
					  <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Patient Name</th>
                                <th>City</th>  
								<th>Tot. Prescribed</th>
								
                               	
                            </tr>
                            </thead>
                            <tbody>
							<?php while(list($key,$value) = each($getPatientList)){ 
							$getPatDetails = mysqlSelect("patient_name,patient_loc,pat_state","doc_my_patient","patient_id='".$value['patient_id']."'","","","","");
							$getPrescTime = mysqlSelect("COUNT(b.pp_id) as prescCount","doc_patient_episodes as a left join doc_patient_episode_prescriptions as b on a.episode_id=b.episode_id","a.patient_id = '".$value['patient_id']."' and b.pp_id='".$drug_id."'","","","","");

							?>
								<tr>
								<td><?= $getPatDetails[0]['patient_name'];  ?></td>
								<td><?= $getPatDetails[0]['patient_loc'].", ".$getPatDetails[0]['pat_state'];  ?></td>
								<td><span class="text-navy"><?= $getPrescTime[0]['prescCount']; ?></span></td>
								</tr>
							<?php } ?>
							</tbody>
						</table>
				
                    </div>
                </div>
            </div>