
  
<div class="wrapper">
  <div class="main_nav fl">
  <form name="frmNavigate" method="post" >
  <input type="hidden" name="cmdNavigate" value="">
  <input type="hidden" name="buttonVal" value="">
  
	<h3>
	<a href="Demo_main.php?disp=1"  <?php if($_GET['disp']==1){ echo "class=active"; } ?>>CAPTURE <?php if($_GET['disp']==1){ echo "(".$Total_Rslt[0]['count'].")"; } ?></a>
	<a href="Demo_main.php?disp=5"  <?php if($_GET['disp']==5){ echo "class=active"; } ?>>P-AWAITING <?php if($_GET['disp']==5){ echo "(".$Total_Rslt[0]['count'].")"; } ?></a>
	<a href="Demo_main.php?disp=6"  <?php if($_GET['disp']==6){ echo "class=active"; } ?>>ASSIGNED <?php if($_GET['disp']==6){ echo "(".$Total_Rslt[0]['count'].")"; } ?></a>
	<a href="Demo_main.php?disp=2" <?php if($_GET['disp']==2){ echo "class=active"; } ?>>REFER <?php if($_GET['disp']==2){ echo "(".$Total_Rslt[0]['count'].")"; } ?></a>
	<a href="Demo_main.php?disp=3" <?php if($_GET['disp']==3){ echo "class=active"; } ?>>RESPONDED <?php if($_GET['disp']==3){ echo "(".$Total_Rslt[0]['count'].")"; } ?></a>
	<a href="Demo_main.php?disp=7" <?php if($_GET['disp']==7){ echo "class=active"; } ?>>CONVERTED <?php if($_GET['disp']==7){ echo "(".$Total_Rslt[0]['count'].")"; } ?></a>
	<a href="Demo_main.php?disp=4" <?php if($_GET['disp']==4){ echo "class=active"; } ?>>CLOSED <?php if($_GET['disp']==4){ echo "(".$Total_Rslt[0]['count'].")"; } ?></a>
	</h3>
	</form>
</div>
<div class="rightNav">
<a href="Add-Patient.php?disp=<?php echo $_GET['disp']; ?>&assignId=<?php echo $_GET['assignId']; ?>&refid=<?php echo $_GET['refid']; ?>">Add Patient</a>
</div>
 </div>


  