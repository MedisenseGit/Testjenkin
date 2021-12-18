<?php
		$Cmpny_result= $objQuery->mysqlSelect("*","compny_tab as a left join chckin_user as b on a.company_id=b.cmpny_id","a.company_id='".$_SESSION['comp_id']."'","","","","");
?>

<div class="header">
  <div class="clearfix">
    <div class="wrapper rel">
     <h1 class="logo"><span><?php echo $Cmpny_result[0]['company_name']; ?></span></h1>
      
 		<div class="click-nav fr">
			<ul>
				<li>
					<a class="clicker"><img src="images/i-8.png" alt="Icon">Hi <?php echo  substr($_SESSION['user'],0,10); ?></a>
					<ul>
						<li><a href="manage_account.php"><img src="images/i-4.png" alt="Icon">Manage Accounts</a></li>
						<li><a href="logout.php"><img src="images/i-6.png" alt="Icon">Sign out</a></li>
					</ul>
				</li>
			</ul>
		</div>
		<!-- /Clickable Nav -->
	  	  
    </div> 
  </div>
</div>