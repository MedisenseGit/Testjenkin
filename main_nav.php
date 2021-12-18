
  
<div class="wrapper">
  <div class="main_nav fl">
  <form name="frmNavigate" method="post" >
  <input type="hidden" name="cmdNavigate" value="">
  <input type="hidden" name="buttonVal" value="">
  
	<h3>
	<?php
	 $user_flag = $_SESSION['flag_id'];
	if($user_flag==0 || $user_flag==1) { ?><a href="main.php?disp=1"  <?php if($_GET['disp']==1){ echo "class=active"; } ?>>CAPTURE <?php if($_GET['disp']==1){ echo "(".$Total_Rslt[0]['count'].")"; } ?></a><?php } ?>
	<?php if($user_flag==0 || $user_flag==1) { ?><a href="main.php?disp=5"  <?php if($_GET['disp']==5){ echo "class=active"; } ?>>P-AWAITING <?php if($_GET['disp']==5){ echo "(".$Total_Rslt[0]['count'].")"; } ?></a><?php } ?>
	<a href="main.php?disp=6"  <?php if($_GET['disp']==6){ echo "class=active"; } ?>>ASSIGNED <?php if($_GET['disp']==6){ echo "(".$Total_Rslt[0]['count'].")"; } ?></a>
	<?php if($user_flag==0 || $user_flag==1) { ?><a href="main.php?disp=2" <?php if($_GET['disp']==2){ echo "class=active"; } ?>>REFER <?php if($_GET['disp']==2){ echo "(".$Total_Rslt[0]['count'].")"; } ?></a><?php } ?>
	<?php if($user_flag==0 || $user_flag==1) { ?><a href="main.php?disp=3" <?php if($_GET['disp']==3){ echo "class=active"; } ?>>RESPONDED <?php if($_GET['disp']==3){ echo "(".$Total_Rslt[0]['count'].")"; } ?></a><?php } ?>
	<?php if($user_flag==0 || $user_flag==1) { ?><a href="main.php?disp=8" <?php if($_GET['disp']==8){ echo "class=active"; } ?>>AUTO-RESPONDED <?php if($_GET['disp']==8){ echo "(".$Total_Rslt[0]['count'].")"; } ?></a><?php } ?>
	<?php if($user_flag==0 || $user_flag==1) { ?><a href="main.php?disp=7" <?php if($_GET['disp']==7){ echo "class=active"; } ?>>CONVERTED <?php if($_GET['disp']==7){ echo "(".$Total_Rslt[0]['count'].")"; } ?></a><?php } ?>
	<?php if($user_flag==0 || $user_flag==1) { ?><a href="main.php?disp=9" <?php if($_GET['disp']==9){ echo "class=active"; } ?>>INVOICE <?php if($_GET['disp']==9){ echo "(".$Total_Rslt[0]['count'].")"; } ?></a><?php } ?>
	<?php if($user_flag==0 || $user_flag==1) { ?><a href="main.php?disp=4" <?php if($_GET['disp']==4){ echo "class=active"; } ?>>CLOSED <?php if($_GET['disp']==4){ echo "(".$Total_Rslt[0]['count'].")"; } ?></a><?php } ?>
	
	</h3>
	</form>
</div>
<div class="rightNav">
<a href="Add-Patient.php?<?php if(!empty($_GET['disp'])) { ?>&disp=<?php echo $_GET['disp']; } if(!empty($_GET['assignId'])) { ?>&assignId=<?php echo $_GET['assignId']; } if(!empty($_GET['refid'])){ ?>refid=<?php echo $_GET['refid']; } ?>">Add Patient</a>
</div>
 </div>


  