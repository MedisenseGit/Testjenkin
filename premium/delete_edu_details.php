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
//$objQuery = new CLSQueryMaker();

mysqlDelete('patient_education',"edu_id='".$_GET['eduid']."'");

					
$allRecord = mysqlSelect("*","patient_education","doc_id='".$admin_id."' and doc_type=1","edu_id desc","","","");

?>
	 <div class="row" id="allEducation">
                <div class="col-lg-12">
                    <div class="ibox">
                        <div class="ibox-content" >

                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>

                                        
                                        <th>Title </th>
										<th>Description</th>
										
                                    </tr>
                                    </thead>
                                    <tbody>
									
									<?php 
									if(!empty($allRecord)){
									foreach($allRecord as $list)
									{ 									
									?>
                                    <tr>
                                       
                                         <td><strong><?php echo $list['edu_title']; ?></strong> </td>
										<td><?php echo $list['edu_description']; ?></td>
										<td><td><a href="javascript:void(0)" onclick="return delEducation(<?php echo $list['edu_id']; ?>);" class="btn btn-danger btn-bitbucket btn-xs">
										 <i class="fa fa-trash-o"></i> DELETE</a></td></td>
                                       
                                    </tr>
                                    <?php } 
									} else { 
									?>
									<tr>
                                       
                                        <td colspan="2" class="text-center">No record found </td>
										                                       
                                    </tr>
									<?php } ?>
                                    </tbody>
                                </table>
                            </div>

                        </div>
							
                    </div>
					
                </div>
            </div>
					