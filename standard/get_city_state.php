<?php
ob_start();
error_reporting(0); 
session_start();


$admin_id = $_SESSION['user_id'];
if(empty($admin_id)){
	header("Location:index.php");
}
require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();


$pincode=$_GET['pincode'];


$getDetails= $objQuery->mysqlSelect("*","all_india_postal_code","pincode='".$pincode."'","","","","");



?>

								<div class="form-group">
								<label class="col-sm-2 control-label">City <span class="required">*</span></label>
                                    <div class="col-sm-10">
									<input type="text" name="se_city" value="<?php echo $getDetails[0]['suburb']; ?>" class="form-control">
									</div>
								</div>
								<div class="form-group"><label class="col-sm-2 control-label">State <span class="required">*</span></label>

                                    <div class="col-sm-10"><select data-placeholder="Choose a State..." class="form-control chosen-select" required="required" name="se_state" id="se_state" tabindex="2">
											<?php
													$GetState = $objQuery->mysqlSelect("*","countries as a left join states as b on a.country_id=b.country_id", "a.country_id=100", "b.state_name asc", "", "", "");
												
													?><option value="<?php echo $getDetails[0]['state']; ?>"><?php echo $getDetails[0]['state']; ?></option>
													<?php 
													
													foreach ($GetState as $StateList) {
													?>
													<option value="<?php echo $StateList["state_name"];	?>"><?php echo $StateList["state_name"]; ?></option>
													
													<?php
													}
													?>
										</select>
									</div>
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">Country <span class="required">*</span></label>

                                    <div class="col-sm-10"><select data-placeholder="Choose a Country..." class="form-control chosen-select" name="se_country"  tabindex="2">
											<option value="India" selected>India</option>
												<?php 
												$getCountry= $objQuery->mysqlSelect("*","countries","","country_name asc","","","");
													$i=30;
													foreach($getCountry as $CountryList){
												?> 
														
														<option value="<?php echo stripslashes($CountryList['country_name']); ?>" />
														<?php echo stripslashes($CountryList['country_name']);?></option>
													
													
													<?php 
														$i++;
													}?> 
										</select>
									</div>
                                </div>
								
							
					