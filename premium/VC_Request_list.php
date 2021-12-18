<?php 

$GetVCRequests = mysqlSelect("a.id as acc_id, a.doc_id as doc_id, a.patient_id as patient_id, a.unique_trans_id as unique_trans_id, a.appoint_trans_id as appoint_trans_id, c.patient_name as patient_name, b.address as patient_addrs, b.state as pat_state, b.country as pat_country, b.doc_agora_link as doc_video_link, b.pay_status as pay_status", "appointment_accept_reject as a inner join patients_transactions as b on b.transaction_id = a.appoint_trans_id 
inner join patients_appointment as c  on c.patient_id=b.patient_id", "a.doc_id='".$admin_id."' AND (a.consult_status=1 OR a.consult_status=2)", "a.id DESC", "", "", "0,15");

$GetVCRequests_count = mysqlSelect("count(a.id) as count_num", "appointment_accept_reject as a inner join patients_transactions as b on b.transaction_id = a.appoint_trans_id inner join patients_appointment as c  on c.patient_id=b.patient_id", "a.doc_id='".$admin_id."' AND (a.consult_status=1 OR a.consult_status=2)", "a.id DESC", "", "", "0,15");

?>
<li class="dropdown">
	<a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
		<i class="fa fa-bell"> VC Requests&nbsp<span style="color:#1ab394;"><?php echo $GetVCRequests_count[0]['count_num'];?></span></i> 
	</a>
	<ul class="dropdown-menu dropdown-alerts">
		<?php foreach ($GetVCRequests as $GetGetVCRequestsList) { ?>   
		<li>
			<div class="row">
					<input type="hidden" name="row_id" id="row_id<?php echo $GetGetVCRequestsList['acc_id']; ?>" value="<?php echo $GetGetVCRequestsList["acc_id"]; ?>" />
					
					<input type="hidden" name="acc_doc_id" id="acc_doc_id<?php echo $GetGetVCRequestsList['acc_id']; ?>" value="<?php echo $GetGetVCRequestsList["doc_id"]; ?>" />
					
					<input type="hidden" name="acc_pat_id" id="acc_pat_id<?php echo $GetGetVCRequestsList['acc_id']; ?>" value="<?php echo $GetGetVCRequestsList["patient_id"]; ?>" />
					
					<input type="hidden" name="acc_pat_id" id="acc_pat_id<?php echo $GetGetVCRequestsList['acc_id']; ?>" value="<?php echo $GetGetVCRequestsList["pat_id"]; ?>" />
					
					<input type="hidden" name="acc_unique_trans_id" id="acc_unique_trans_id<?php echo $GetGetVCRequestsList['acc_id']; ?>" value="<?php echo $GetGetVCRequestsList["unique_trans_id"]; ?>" />
					
					<input type="hidden" name="acc_appoint_trans_id" id="acc_appoint_trans_id<?php echo $GetGetVCRequestsList['acc_id']; ?>" value="<?php echo $GetGetVCRequestsList["appoint_trans_id"]; ?>" />
					
					<div class="col-lg-12">
						<div class="row" style="padding:5px;">
							<div class="col-lg-6">
								<span><strong><?php echo $GetGetVCRequestsList["patient_name"] ?></strong><br>
										<strong><?php echo $GetGetVCRequestsList["unique_trans_id"] ?></strong>
								</span>
							</div>
							<div class="col-lg-6">
									<button  class="request_accept<?php echo $GetGetVCRequestsList['acc_id']; ?>" id="request_accept<?php echo $GetGetVCRequestsList['acc_id']; ?>"  style="width: 50px;font-size: 10px;text-align: center; background-color: #04AA6D;color: white;border: none;"> Accept </button> 
									<button  class="request_reject<?php echo $GetGetVCRequestsList['acc_id']; ?>" id="request_reject<?php echo $GetGetVCRequestsList['acc_id']; ?>" style="width: 50px;font-size: 10px;text-align: center; background-color: #FF0000;color: white;border: none;"> Reject </button> 
							</div>
							
							
						</div>
					</div>
			</div>
			<div class="row">
					<div class="col-lg-6">
								<i class="fa fa-user fa-fw"></i>
								<label> <?php echo $GetGetVCRequestsList["patient_addrs"]. "".$GetGetVCRequestsList["pat_state"]. "".$GetGetVCRequestsList["pat_country"]; ?></label>
							</div>
					<div class="col-lg-4">
						<?php if( $GetGetVCRequestsList['pay_status'] == 'VC Confirmed') { ?>
							<a style="width: 20px;font-size: 10px;text-align: center; background-color: #21B6A8;color: white;border: none;" target="_blank" href="<?php echo $GetGetVCRequestsList['doc_video_link']; ?>"> <i class="fa fa-video-camera" aria-hidden="true"></i> </a> 
						<?php } ?>
					</div>
			</div>
			<hr style="margin:0px;">
		</li>
						
						<script type="text/javascript">
								$('.request_accept<?php echo $GetGetVCRequestsList['acc_id']; ?>').click(function() {
										console.log("id: "+<?php echo $GetGetVCRequestsList['acc_id']; ?>);
										var acc_id 		= $('#row_id<?php echo $GetGetVCRequestsList['acc_id']; ?>').val();
										var acc_doc_id 	= $('#acc_doc_id<?php echo $GetGetVCRequestsList['acc_id']; ?>').val();
										var acc_pat_id 	= $('#acc_pat_id<?php echo $GetGetVCRequestsList['acc_id']; ?>').val();
										var acc_unique_trans_id  = $('#acc_unique_trans_id<?php echo $GetGetVCRequestsList['acc_id']; ?>').val();
										var acc_appoint_trans_id = $('#acc_appoint_trans_id<?php echo $GetGetVCRequestsList['acc_id']; ?>').val();
										console.log("acc_id: "+acc_id);
										console.log("acc_doc_id: "+acc_doc_id);
										console.log("acc_pat_id: "+acc_pat_id);
										console.log("acc_unique_trans_id: "+acc_unique_trans_id);
										console.log("acc_appoint_trans_id: "+acc_appoint_trans_id);
										var status_id = 2;				// 1 - Request Sent from patient, 2 - ACCEPT, 3-DECLINE
										
										$.ajax({
											type: "POST",
											url: "teleconsult_status_update.php",
										
											data: 'act=add-status&acc_id='+acc_id+'&acc_doc_id='+acc_doc_id+'&acc_pat_id='+acc_pat_id+'&acc_unique_trans_id='+acc_unique_trans_id+'&acc_appoint_trans_id='+acc_appoint_trans_id+'&status_id='+status_id,
											success: function(data)
											{
												console.log(data);
											 	const obj = JSON.parse(data);
											 	if(obj.status == 'true') 
												{
												 swal({
													title: "Accepted !!!",
													text: "Your response has been updated successfully!",
													type: "success"
												});
												window.location = "<?php echo $navigateLink; ?>?p=<?php echo md5($GetGetVCRequestsList['patient_id']); ?>";
												}
												else 
												{
													swal({
													title: "Already accepted !!!",
													text: "This request is already accepted !!!",
													type: "success"
													});
													window.location = "<?php echo $navigateLink; ?>?p=<?php echo md5($GetGetVCRequestsList['patient_id']); ?>";
												}
											}  
										});
									});
								
								$('.request_reject<?php echo $GetGetVCRequestsList['acc_id']; ?>').click(function() {
										console.log("id: "+<?php echo $GetGetVCRequestsList['acc_id']; ?>);
										
										var acc_id = $('#row_id<?php echo $GetGetVCRequestsList['acc_id']; ?>').val();
										var acc_doc_id = $('#acc_doc_id<?php echo $GetGetVCRequestsList['acc_id']; ?>').val();
										var acc_pat_id = $('#acc_pat_id<?php echo $GetGetVCRequestsList['acc_id']; ?>').val();
										var acc_unique_trans_id = $('#acc_unique_trans_id<?php echo $GetGetVCRequestsList['acc_id']; ?>').val();
										var acc_appoint_trans_id = $('#acc_appoint_trans_id<?php echo $GetGetVCRequestsList['acc_id']; ?>').val();
										console.log("acc_id: "+acc_id);
										console.log("acc_doc_id: "+acc_doc_id);
										console.log("acc_pat_id: "+acc_pat_id);
										console.log("acc_unique_trans_id: "+acc_unique_trans_id);
										console.log("acc_appoint_trans_id: "+acc_appoint_trans_id);
										var status_id = 3;				// 1 - Request Sent from patient, 2 - ACCEPT, 3-DECLINE
										
										$.ajax({
											type: "POST",
											url: "teleconsult_status_update.php",
											data: 'act=add-status&acc_id='+acc_id+'&acc_doc_id='+acc_doc_id+'&acc_pat_id='+acc_pat_id+'&acc_unique_trans_id='+acc_unique_trans_id+'&acc_appoint_trans_id='+acc_appoint_trans_id+'&status_id='+status_id,
											success: function(data)
											{
												const obj = JSON.parse(data);
											 	if(obj.status == 'true' && obj.type == 'accept') 
												{
												 swal({
													title: "Accepted !!!",
													text: "Your response has been updated successfully!",
													type: "success"
												});
												}
												else if(obj.status == 'true' && obj.type == 'decline') 
												{
													swal({
														title: "Declined !!!",
														text: "Your response has been updated successfully!",
														type: "warning"
													});
												} 
												else 
												{
													swal({
														title: "Already accepted !!!",
														text: "This request is already accepted !!!",
														type: "success"
													});
												}
											}  
										});
									});
								</script>
					<?php } ?>
	</ul>
</li>
           


		
		