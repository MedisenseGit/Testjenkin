 <?php  $request  = str_replace("/premium/", "", $_SERVER['REQUEST_URI']);
	?>
 <div class="row border-bottom">
        <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
           <!--
                <div class="form-group col-sm-10">
                     <a href="#" ><img src="drag-list-down.png" width="30" class="m-l"/></a>
                </div>
           
			-->
			<?php if($request=="Home"){ ?>
                 <a href="#" class="minimalize-styl-2 btn btn-primary startTour"><i class="fa fa-play"></i> Start Tour</a>
			<?php } ?>
        </div>
            <ul class="nav navbar-top-links navbar-right">
                <li>
                    <span class="m-r-sm text-muted welcome-message">Welcome to Practice Premium.</span>
                </li>

                <li class="dropdown">
                    <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                        <i class="fa fa-bell"></i>  <span class="label label-danger">0</span>
                    </a>
                    <ul class="dropdown-menu dropdown-alerts">
                        <li>
                            <a href="#">
                                <div>
                                    <i class="fa fa-envelope fa-fw"></i> No notification
                                   
                                </div>
                            </a>
                        </li>
                     
                    </ul>
                </li>


                <li>
                    <a href="../logout.php">
                        <i class="fa fa-sign-out"></i> Log out
                    </a>
                </li>
            </ul>

        </nav>
        </div>