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

$Getpermission = mysqlSelect("*", "receptionist_permission", "reception_id='".$_GET['receptionid']."' and doc_id='".$admin_id."'", "", "", "", "");
											


?>
						<form method="post" name="frmEMRPermission" class="form-horizontal" action="add_details.php">
							<table class="table table-striped">
                                    <thead>
                                    <tr>

                                        <th>Sr. No.</th>
                                        <th>Feature Name </th>
										<th>Permission </th>
										
										
                                    </tr>
                                    </thead>
                                    <tbody>
										<tr><th>1</th><td>Chief medical complaint</td><td><div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio1" value="1" name="check_chief_medical" <?php if($Getpermission[0]['medical_complaint']=="1"){ echo "checked"; } ?>>
                                            <label for="inlineRadio1"> Yes </label>
                                        </div>
										<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="0" name="check_chief_medical" <?php if($Getpermission[0]['medical_complaint']=="0"){ echo "checked"; } ?>>
                                            <label for="inlineRadio2"> No </label>
                                        </div></td></tr>
										<tr><th>2</th><td>Examination</td><td><div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio3" value="1" name="check_exam" <?php if($Getpermission[0]['examination']=="1"){ echo "checked"; } ?>>
                                            <label for="inlineRadio3"> Yes </label>
                                        </div>
										<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio4" value="0" name="check_exam" <?php if($Getpermission[0]['examination']=="0"){ echo "checked"; } ?>>
                                            <label for="inlineRadio4"> No </label>
                                        </div></td></tr>
										<tr><th>3</th><td>Investigations</td><td><div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio5" value="1" name="check_invest" <?php if($Getpermission[0]['investigations']=="1"){ echo "checked"; } ?>>
                                            <label for="inlineRadio5"> Yes </label>
                                        </div>
										<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio6" value="0" name="check_invest" <?php if($Getpermission[0]['investigations']=="0"){ echo "checked"; } ?>>
                                            <label for="inlineRadio6"> No </label>
                                        </div></td></tr>
										<tr><th>4</th><td>Diagnosis</td><td><div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio7" value="1" name="check_diagno" <?php if($Getpermission[0]['diagnosis']=="1"){ echo "checked"; } ?>>
                                            <label for="inlineRadio7"> Yes </label>
                                        </div>
										<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio8" value="0" name="check_diagno" <?php if($Getpermission[0]['diagnosis']=="0"){ echo "checked"; } ?>>
                                            <label for="inlineRadio8"> No </label>
                                        </div></td></tr>
										<tr><th>5</th><td>Treatment Advise</td><td><div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio9" value="1" name="check_treatment" <?php if($Getpermission[0]['treatment_advise']=="1"){ echo "checked"; } ?>>
                                            <label for="inlineRadio9"> Yes </label>
                                        </div>
										<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio10" value="0" name="check_treatment" <?php if($Getpermission[0]['treatment_advise']=="0"){ echo "checked"; } ?>>
                                            <label for="inlineRadio10"> No </label>
                                        </div></td></tr>
										<tr><th>6</th><td>Add Prescriptions</td><td><div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio11" value="1" name="check_presc" <?php if($Getpermission[0]['prescriptions']=="1"){ echo "checked"; } ?>>
                                            <label for="inlineRadio11"> Yes </label>
                                        </div>
										<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio12" value="0" name="check_presc" <?php if($Getpermission[0]['prescriptions']=="0"){ echo "checked"; } ?>>
                                            <label for="inlineRadio12"> No </label>
                                        </div></td></tr>
										
									
									</tbody>
									
								</table>
						</form>		
							
					