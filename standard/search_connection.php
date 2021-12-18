<?php
ob_start();
error_reporting(0); 
session_start();


$admin_id = $_SESSION['user_id'];
if(empty($admin_id)){
	header("Location:user-login");
}
require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

if($_GET['val']==555){
		$getMappedFeature = $objQuery->mysqlSelect("partner_id","mapping_hosp_referrer","partner_id='".$admin_id."'","","","","");
					
		if($getMappedFeature==true){
		$getSerConnection = $objQuery->mysqlSelect("a.ref_id as Ref_Id,a.ref_name as Ref_Name,a.doc_type_val as Doc_Type,a.ref_exp as Ref_Exp,a.ref_address as Ref_Address,a.doc_state as Doc_State,a.doc_photo as Doc_Image,b.spec_name as Specialization","referal as a inner join specialization as b on b.spec_id=a.doc_spec inner join doctor_hosp as c on c.doc_id=a.ref_id inner join mapping_hosp_referrer as d on d.hosp_id=c.hosp_id","a.anonymous_status!=1 and d.partner_id='".$admin_id."'","","","","");
		}
		else{
		$getSerConnection = $objQuery->mysqlSelect("a.ref_id as Ref_Id,a.ref_name as Ref_Name,a.doc_type_val as Doc_Type,a.ref_exp as Ref_Exp,a.ref_address as Ref_Address,a.doc_state as Doc_State,a.doc_photo as Doc_Image,b.spec_name as Specialization","referal as a inner join specialization as b on a.doc_spec=b.spec_id","a.anonymous_status!=1","","","","");
			
		}
}
else{
		$getMappedFeature = $objQuery->mysqlSelect("partner_id","mapping_hosp_referrer","partner_id='".$admin_id."'","","","","");
					
		if($getMappedFeature==true){
		$getSerConnection = $objQuery->mysqlSelect("a.ref_id as Ref_Id,a.ref_name as Ref_Name,a.doc_type_val as Doc_Type,a.ref_exp as Ref_Exp,a.ref_address as Ref_Address,a.doc_state as Doc_State,a.doc_photo as Doc_Image,b.spec_name as Specialization","referal as a inner join specialization as b on a.doc_spec=b.spec_id inner join doctor_hosp as c on c.doc_id=a.ref_id inner join mapping_hosp_referrer as d on d.hosp_id=c.hosp_id","a.anonymous_status!=1 and d.partner_id='".$admin_id."' and a.doc_spec='".$_GET['val']."'","","","","");
		
		}
		else{
		$getSerConnection = $objQuery->mysqlSelect("a.ref_id as Ref_Id,a.ref_name as Ref_Name,a.doc_type_val as Doc_Type,a.ref_exp as Ref_Exp,a.ref_address as Ref_Address,a.doc_state as Doc_State,a.doc_photo as Doc_Image,b.spec_name as Specialization","referal as a inner join specialization as b on a.doc_spec=b.spec_id inner join doctor_hosp as c on c.doc_id=a.ref_id","a.anonymous_status!=1 and a.doc_spec='".$_GET['val']."'","","","","");
		}
}	

                     
?>

			<div class="row">
				<?php foreach($getSerConnection as $getList){ 
			if(!empty($getList)){ ?>
           <div class="col-lg-3" style="padding-bottom:30px;">
                <div class="contact-box center-version m-b-xs">

                    <a href="Doctor-Profile?d=<?php echo md5($getList['Ref_Id']); ?>&start=<?php echo $_GET['start']; ?>">
						 <?php if(!empty($getList['Doc_Image'])){ ?>
                        <img alt="image" class="img-circle" src="../Doc/<?php echo $getList['Ref_Id']; ?>/<?php echo $getList['Doc_Image']; ?>">
						<?php }  else { ?>	
						<img alt="image" class="img-circle" src="../assets/img/anonymous-profile.png">
						<?php  } ?>

                        <h4 class="m-b-xs"><strong><?php echo $getList['Ref_Name']; ?></strong></h4>

                        <div class="font-bold"><small><?php echo substr($getList['Specialization'],0,21); ?></small></div>
                        <address class="m-t-md m-b-xs">
                            <strong><?php echo $getList['Ref_Exp']; ?> Years Exp.</strong><br>
                            <small><?php echo $getList['Ref_Address']; ?></small>
                            <!--<abbr title="Phone">P:</abbr> (123) 456-7890-->
                        </address>

                    </a>
                    <div class="contact-box-footer">
                        <div class="m-t-xs btn-group">
						<div class="tooltip-demo">
                           <!-- <a class="btn btn-xs btn-white"><i class="fa fa-phone"></i> Call </a>-->
                            <a href="Doctor-Profile?d=<?php echo md5($getList['Ref_Id']); ?>&start=<?php echo $_GET['start']; ?>" class="btn btn-xs btn-white" data-toggle="tooltip" data-placement="top" title="Send case to doctor"><i class="fa fa-wheelchair"></i> Send Case</a>
                            <a href="javascript:void(0)" onclick="return addFavour(<?php echo $getList['Ref_Id']; ?>);" class="btn btn-xs btn-white" data-toggle="tooltip" data-placement="top" title="Add to favorite list"><i class="fa fa-user-md"></i> Favorite</a>
                        </div>
						</div>
                    </div>

                </div>
            </div>
            <?php }
			
			
			}	
			
			if(empty($getSerConnection)){ ?>
				<div class="col-lg-12">
				
					<h3>No result found</h3>
				</div>
			<?php }
			?>
			</div>	
					