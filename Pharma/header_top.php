 <?php  //$countFavour = mysqlSelect("COUNT(a.ref_id) as Count_Favour","referal as a inner join specialization as b on a.doc_spec=b.spec_id inner join add_favourite_doctor as c on c.doc_id=a.ref_id","(a.doc_spec!=555 and a.anonymous_status!=1) and (c.user_id='".$admin_id."' and c.user_type=1)","","","","");
	$request  = str_replace("/Pharma/", "", $_SERVER['REQUEST_URI']);
	?>
 <div class="row border-bottom">
        <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
            <!--<form role="search" class="navbar-form-custom" action="">
                <div class="form-group">
                     <input type="text" placeholder="Search for something..." class="form-control" name="top-search" id="top-search">
                </div>
            </form>-->
			
			<?php if($request=="Home"){ ?>
                 <a href="#" class="minimalize-styl-2 btn btn-primary startTour"><i class="fa fa-play"></i> Start Tour</a>
			<?php } ?>
        </div>
            <ul class="nav navbar-top-links navbar-right">
                <li>
                    <span class="m-r-sm text-muted welcome-message">Welcome to Practice Pharma.</span>
                </li>
                <!--<li class="dropdown">
                    <a class="dropdown-toggle count-info"  href="Favorites">
                        <i class="fa fa-bookmark"></i>  <span class="label label-warning"><?php echo $countFavour[0]['Count_Favour']; ?></span>
                    </a>
                   
                </li>-->
                


                <li>
                    <a href="logout.php">
                        <i class="fa fa-sign-out"></i> Log out
                    </a>
                </li>
            </ul>

        </nav>
        </div>