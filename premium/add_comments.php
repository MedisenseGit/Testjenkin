<?php
ob_start();
error_reporting(0); 
session_start();


$admin_id = $_SESSION['user_id'];
if(empty($admin_id))
{
	header("Location:user-login");
}
require_once("../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();

date_default_timezone_set('Asia/Kolkata');
$curDate=date('Y-m-d H:i:s');

extract($_POST);
if($_POST['act'] == 'add-com'):
	$postCom = htmlentities($postCom);
    $postType = htmlentities($postType);
    $postId = htmlentities($postId);
	$userId = $userId;
    $userType = $userType;
   	
	$arrFields = array();
	$arrValues = array();
	if(!empty($userId))
	{
		$arrFields[]= 'login_id';
		$arrValues[]= $userId;
	}
	
	$arrFields[]= 'login_User_Type';
	$arrValues[]= $userType;
	
	if(!empty($postId))
	{
		$arrFields[]= 'topic_id';
		$arrValues[]= $postId;
	}
	
	
	$arrFields[]= 'topic_type';
	$arrValues[]= $postType;
	$arrFields[]= 'comments';
	$arrValues[]= $postCom;
	$arrFields[]= 'post_date';
	$arrValues[]= time();
	
	$addComment	=	mysqlInsert('home_post_comments',$arrFields,$arrValues);
    $comment_id = 	$addComment;//mysqli_insert_id();                
	$getComment = 	mysqlSelect("*","home_post_comments","comment_id='".$comment_id."'","","","","");
	
	if($getComment[0]['login_User_Type']=="1")
	{  //For Partner
		$getUser = mysqlSelect("partner_name,doc_photo","our_partners","partner_id='".$getComment[0]['login_id']."'","","","","");
		$userName=$getUser[0]['partner_name'];
	
		//Profile Pic
		if(!empty($getUser[0]['doc_photo']))
		{
			$userimg="../Partners/partnerProfilePic/".$getComment[0]['login_id']."/".$getUser[0]['doc_photo']; 
		}
		else
		{
			$userimg="../assets/img/anonymous-profile.png";
		}
	}
	else if($getComment[0]['login_User_Type']=="2")
	{ //For Doctor
		$getUser = mysqlSelect("ref_name,doc_photo","referal","ref_id='".$getComment[0]['login_id']."'","","","","");
		$userName=$getUser[0]['ref_name'];
	
		//Profile Pic
		if(!empty($getUser[0]['doc_photo']))
		{ 
			$userimg="docProfilePic/".$getComment[0]['login_id']."/".$getUser[0]['doc_photo']; 
		}
		else
		{
			$userimg="../assets/img/anonymous-profile.png";
		}
	}	
	else if($getComment[0]['login_User_Type']=="3")
	{  //For Hospital
		$getUser = mysqlSelect("company_name,company_logo","compny_tab","company_id='".$getComment[0]['login_id']."'","","","","");
		$userName=$getUser[0]['company_name'];
	
		//Profile Pic
		if(!empty($getUser[0]['company_logo']))
		{
			$userimg="Company_Logo/".$getComment[0]['login_id']."/".$getUser[0]['company_logo']; 
		}
		else
		{
			$userimg="../assets/img/anonymous-profile.png";
		}
	}
	
	if($addComment==true){
	?>							
							<div class="social-comment">
                                <a href="" class="pull-left">
                                    <img alt="image" src="<?php echo $userimg; ?>">
                                </a>
                                <div class="media-body">
                                    <a href="#">
                                        <?php echo $userName; ?>
                                    </a>
                                   
                                    <br/>
                                    <!--<a href="#" class="small"><i class="fa fa-thumbs-up"></i> 26 Like this!</a> --->
                                    <small class="text-muted"><?php echo date('d M Y H:i',strtotime($getComment[0]['post_date'])); ?></small>
                                </div>
                            </div>

	<?php }
	endif; ?>            
				