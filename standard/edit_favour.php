<?php
ob_start();
error_reporting(0); 
session_start();

$favour_id=$_GET['favour_id'];

$admin_id = $_SESSION['user_id'];
if(empty($admin_id)){
	header("Location:user-login");
}
require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

 $objQuery->mysqlDelete('add_favourite_doctor',"favourite_id='".$favour_id ."'");

$getFavour = $objQuery->mysqlSelect("a.ref_id as Ref_Id,a.ref_name as Ref_Name,a.doc_type_val as Doc_Type,a.ref_exp as Ref_Exp,a.ref_address as Ref_Address,a.doc_state as Doc_State,a.doc_photo as Doc_Image,b.spec_name as Specialization,c.favourite_id as Favour_Id","referal as a inner join specialization as b on a.doc_spec=b.spec_id inner join add_favourite_doctor as c on c.doc_id=a.ref_id","(a.doc_spec!=555 and a.anonymous_status!=1) and (c.user_id='".$admin_id."' and c.user_type=1)","a.doc_type_val asc","","","");


                     
?>

				<?php foreach($getFavour as $getList){ 
			if(!empty($getList)){ ?>
            <div class="col-lg-3">
                <div class="contact-box center-version">

                    <a href="Doctor-Profile?d=<?php echo md5($getList['Ref_Id']); ?>&start=<?php echo $_GET['start']; ?>">
						 <?php if(!empty($getList['Doc_Image'])){ ?>
                        <img alt="image" class="img-circle" src="https://medisensecrm.com/Doc/<?php echo $getList['Ref_Id']; ?>/<?php echo $getList['Doc_Image']; ?>">
						<?php }  else { ?>	
						<img alt="image" class="img-circle" src="../assets/img/anonymous-profile.png">
						<?php  } ?>

                        <h3 class="m-b-xs"><strong><?php echo $getList['Ref_Name']; ?></strong></h3>

                        <div class="font-bold"><?php echo $getList['Specialization']; ?></div>
                        <address class="m-t-md">
                            <strong>Twitter, Inc.</strong><br>
                            <?php echo $getList['Ref_Address'].", ".$getList['Doc_State']; ?><br>
                            <!--<abbr title="Phone">P:</abbr> (123) 456-7890-->
                        </address>

                    </a>
                    <div class="contact-box-footer">
                        <div class="m-t-xs btn-group">
						<div class="tooltip-demo">
                            <a href="Doctor-Profile?d=<?php echo md5($getList['Ref_Id']); ?>&start=<?php echo $_GET['start']; ?>" class="btn btn-xs btn-white" data-toggle="tooltip" data-placement="top" title="Send case to doctor"><i class="fa fa-wheelchair"></i> Send Case</a>
                            <a href="javascript:void(0)" onclick="return showFavour(<?php echo $getList['Favour_Id']; ?>);" class="btn btn-xs btn-white" data-toggle="tooltip" data-placement="top" title="Remove from your favorite list"><i class="fa fa-trash-o"></i> Remove</a>
                        </div>
						</div>
                    </div>

                </div>
            </div>
            <?php }
			}	?>
				
		