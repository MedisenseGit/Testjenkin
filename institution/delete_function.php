<?php
ob_start();
error_reporting(0); 
session_start();


$admin_id = $_SESSION['user_id'];
if(empty($admin_id)){
	header("Location:index.php");
}
include('functions.php');
require_once("../classes/querymaker.class.php");


if(isset($_GET['doc_diagno_id'])){
mysqlDelete('doc_diagnostics',"doc_diagno_id='".$_GET['doc_diagno_id']."'");
mysqlDelete('Diagnostic_center',"diagnostic_id='".$_GET['diagnostic_id']."'");			
$get_diagnoInfo = mysqlSelect("*","Diagnostic_center as a left join doc_diagnostics as b on a.diagnostic_id=b.diagnostic_id","b.company_id='".$admin_id."'","b.doc_diagno_id desc","","","");
}

if(isset($_GET['doc_pharma_id'])){
mysqlDelete('doc_pharma',"doc_pharma_id='".$_GET['doc_pharma_id']."'");
mysqlDelete('pharma',"pharma_id='".$_GET['pharma_id']."'");			
$get_pharmaInfo = mysqlSelect("*","pharma as a left join doc_pharma as b on a.pharma_id=b.pharma_id","b.company_id='".$admin_id."'","b.doc_pharma_id desc","","","");
 }


if(isset($_GET['doc_diagno_id'])){ ?>
	<div class="row">
                <div class="col-lg-12">
                    <div class="ibox">
                        <div class="ibox-content" >

                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>

                                        
                                        <th>Diagnostics </th>
										<th>Email Id</th>
										<th>Mobile</th>
                                        <th>City</th>  
										<th>Delete</th>
                                    </tr>
                                    </thead>
                                    <tbody>
									<?php
									if(count($get_diagnoInfo)==0){
									?>
									<tr><td colspan="4" class="text-center">No records found</td></tr>	
									<?php
									}
									else
									{ 
									foreach($get_diagnoInfo as $list)
									{ 									
									?>
                                    <tr>
                                       
                                        <td><?php echo $list['diagnosis_name']; ?> </td>
										<td><?php echo $list['diagnosis_email']; ?></td>
                                        <td><?php echo $list['diagnosis_contact_num']; ?></td> 
										<td><?php echo $list['diagnosis_city']; ?></td>										
                                       	<td><a href="javascript:void(0)" onclick="return delDiagnostics(<?php echo $list['doc_diagno_id']; ?>);" class="btn btn-danger btn-bitbucket btn-xs">
                      <i class="fa fa-trash-o"></i> DELETE</a></td>
                                    </tr>
                                    <?php }
									}
									?>
                                    </tbody>
                                </table>
                            </div>

                        </div>
							
                    </div>
					
                </div>
            </div>
<?php } 

if(isset($_GET['doc_pharma_id'])){
?>
<div class="row">
                <div class="col-lg-12">
                    <div class="ibox">
                        <div class="ibox-content" >

                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>

                                        
                                        <th>Pharmacy </th>
										<th>Email Id</th>
										<th>Mobile</th>
                                        <th>City</th>  
										<th>Delete</th>
                                    </tr>
                                    </thead>
                                    <tbody>
									
									<?php
									if(count($get_pharmaInfo)==0){
									?>
									<tr><td colspan="4" class="text-center">No records found</td></tr>	
									<?php
									}
									else
									{
									foreach($get_pharmaInfo as $list)
									{ 									
									?>
                                    <tr>
                                       
                                         <td><?php echo $list['pharma_name']; ?> </td>
										<td><?php echo $list['pharma_email']; ?></td>
                                        <td><?php echo $list['pharma_contact_num']; ?></td> 
										<td><?php echo $list['pharma_city']; ?></td>										
                                       	<td><a href="javascript:void(0)" onclick="return delPharma(<?php echo $list['doc_pharma_id']; ?>);" class="btn btn-danger btn-bitbucket btn-xs">
                      <i class="fa fa-trash-o"></i> DELETE</a></td>
                                    </tr>
                                    <?php } 
									}
									?>
                                    </tbody>
                                </table>
                            </div>

                        </div>
							
                    </div>
					
                </div>
            </div>
<?php } ?>
					