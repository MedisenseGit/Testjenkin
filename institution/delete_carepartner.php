<?php
ob_start();
error_reporting(0); 
session_start();


$admin_id = $_SESSION['user_id'];
if(empty($admin_id)){
	header("Location:index.php");
}
require_once("../classes/querymaker.class.php");


mysqlDelete('mapping_hosp_referrer',"partner_id='".$_GET['partnerid']."' and company_id='".$admin_id."'");
$carepartners = mysqlSelect("DISTINCT(a.partner_id) as Partner_Id,a.contact_person as Partner_name,d.hosp_name as Hosp_Name,a.login_status as Login_Status,b.market_person_id as Person_Id","our_partners as a inner join mapping_hosp_referrer as b on a.partner_id=b.partner_id inner join hosp_tab as d on d.hosp_id=b.hosp_id","d.company_id='".$admin_id."'","a.partner_id desc","","","");
                     
?>

						<div class="ibox-content table-responsive">

                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th style="width:200px;">Partner Name</th>
								<th style="width:200px;">Marketing Person</th>
								<th style="width:200px;">Hospital</th>
								<th style="width:200px;">Status</th>
								<th style="width:200px;">Remove</th>
											
                            </tr>
                            </thead>
                            <tbody>
						<?php foreach($carepartners as $list){ 
									$getMarketPerson = mysqlSelect("person_name","hosp_marketing_person","person_id='".$list['Person_Id']."'","","","","");

									?>	
				
                            <tr>
                                <td><?php echo $list['Partner_name'];  ?></td> 
											 <td><?php echo $getMarketPerson[0]['person_name'];  ?></td> 
											<td><?php echo $list['Hosp_Name'];  ?></td> 
											<td><?php if($list['Login_Status']==0){ ?><span class='label label-danger' style="text-transform:uppercase;">Pending</span>
											<?php } else { ?><span class='label label-success' style="text-transform:uppercase;">Paired</span><?php } ?></td> 
								<td><a href="#" onclick="return deletePartner(<?php echo $list['Partner_Id']; ?>);" class="label label-danger"><i class="fa fa-trash"></i> Remove</a></td> 
							</tr>
                           <?php }  ?>
                            </tbody>
							
                        </table>
                    </div>	
					