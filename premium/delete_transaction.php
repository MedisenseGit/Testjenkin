<?php
ob_start();
error_reporting(0); 
session_start();


$admin_id = $_SESSION['user_id'];
$secretary_id = $_SESSION['secretary_id'];
if(empty($admin_id)){
	header("Location:index.php");
}
include('functions.php');
require_once("../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();

mysqlDelete('payment_transaction',"pay_trans_id='".$_GET['transid']."' and user_type=1");

			if(!isset($_GET['start'])) {
			$start = 0;
			}else{
			$start = $_GET['start'];
			}

			$eu = ($start - 0); 
			$limit = 50;         // No of records to be shown per page.
			$this1 = $eu + $limit; 
			$back = $eu - $limit; 
			$next = $eu + $limit;
			
			
$allRecord = mysqlSelect("*","payment_transaction","user_id='".$admin_id."' and user_type=1 and hosp_id='".$_SESSION['login_hosp_id']."'","pay_trans_id desc","","","$eu, $limit");
$pag_result = mysqlSelect("pay_trans_id","payment_transaction","user_id='".$admin_id."' and user_type=1 and hosp_id='".$_SESSION['login_hosp_id']."'","");

?>
	 <div class="row">
                <div class="col-lg-12">
                    <div class="ibox">
                        <div class="ibox-content" >

                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>

                                        
                                        <th>Patient </th>
										<th>Transaction Date</th>
										<th>Narration</th>
                                        <th>Amount (Rs.)</th>  
										<th>Payment mode</th>
                                        <th>Status</th>
										<?php if($secretary_id!=1) { ?><th>Delete</th><?php } ?>
                                    </tr>
                                    </thead>
                                    <tbody>
									<?php foreach($allRecord as $list)
									{ 									
									?>
                                    <tr>
                                       
                                        <td><?php echo $list['patient_name']; ?> </td>
										<td><?php echo date('d M Y H:i',strtotime($list['trans_date'])); ?></td>
										<td><?php echo $list['narration']; ?></td>
                                        <td><?php echo $list['amount']; ?></td> 
										<td><?php echo $list['pay_method']; ?></td>										
                                        <td>
										<?php if($list['payment_status']=="PENDING"){ ?>
										<a href="javascript:void(0)" onclick="return changePayStatus(<?php echo $list['pay_trans_id']; ?>);" class="btn btn-danger btn-bitbucket btn-xs">
                      <i class="fa fa-check"></i> TURN PAID</a></span>
										<?php } else { ?>
										<span class="fa fa-check text-navy"><?php echo $list['payment_status']; ?></span>
										<?php } ?>
										
										</td>
										<?php if($secretary_id!=1) { ?><td><a href="javascript:void(0)" onclick="return delPayment(<?php echo $list['pay_trans_id']; ?>);" class="btn btn-danger btn-bitbucket btn-xs">
                      <i class="fa fa-trash-o"></i> DELETE</a></td><?php } ?>
                                    </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>

                        </div>
							
                    </div>
					
                </div>
            </div>
					