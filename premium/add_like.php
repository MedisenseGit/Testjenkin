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
if($_POST['act']=="add-like"):
$postType 	= htmlentities($postType);
$postId 	= htmlentities($postId);
$userId 	= $userId;
$userType 	= $userType;

$arrFields = array();
$arrValues = array();

$arrFields[]= 'category_id';
$arrValues[]= $postId;
$arrFields[]= 'category_type';
$arrValues[]= $postType;
$arrFields[]= 'likes';
$arrValues[]= $userId;
$arrFields[]= 'user_type';
$arrValues[]= $userType;
$arrFields[]= 'like_date';
$arrValues[]= time();
	
	
     
	//$comment_id= mysql_insert_id();                
	$chkLike = mysqlSelect("likes","home_post_like","category_id='".$postId."' and category_type='".$postType." and likes='".$userId."' and user_type='".$userType."'","","","","");
	
	if($chkLike[0]['likes']==$postId){
		//This user has already liked this post before
	?>	<a href='javascript:void();' class='liked' data-toggle='tooltip' data-placement='bottom' title='Like'><button class="btn btn-danger btn-circle" disabled type="submit"><i class="fa fa-heart"></i>
                            </button></a>
	<?php } else {
		
	$addLike=mysqlInsert('home_post_like',$arrFields,$arrValues);
	?>							
							
		<a href='javascript:void();' class='liked' data-toggle='tooltip' data-placement='bottom' title='Like'><button class="btn btn-danger btn-circle" disabled type="submit"><i class="fa fa-heart"></i>
                            </button></a>
							

	<?php }
	endif; ?>            
				