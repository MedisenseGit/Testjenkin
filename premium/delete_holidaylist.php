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

mysqlDelete('doc_holidays',"holiday_id='".$_GET['holidayid']."'");
?>

								<div class="ibox-content">
										<table class="table">
                                <thead>
                                <tr>
                                    <th>
                                        Holiday Date
                                    </th>
                                    <th>
                                        Reason
                                    </th>
									<th>
                                        Delete
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
								<?php
								$get_Holidaylist = mysqlSelect("*","doc_holidays","doc_id='".$admin_id."'","holiday_id desc","","","");

								foreach($get_Holidaylist as $holidayList)
								{
								?>
                                <tr>
                                    <td><code><?php echo date('d-M-Y',strtotime($holidayList['holiday_date'])); ?></code></td>
                                    <td><span class="text-muted"><?php echo $holidayList['reason']; ?></span></td>
									<td><a href="#" onclick="return deleteHoliday(<?php echo $holidayList['holiday_id']; ?>);"><span class="label label-danger">REMOVE</span></a></td>
                                </tr>
                                <?php } ?>
                                </tbody>
								
                            </table>
							</div>
					