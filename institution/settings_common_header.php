<?php
//Get the page name 
$request_uri = str_replace("/institution/","",$_SERVER['REQUEST_URI']);
$param = explode("/",$request_uri);
$page_name = $param[0];

?>


<ul class="nav nav-tabs">
                            <li <?php if($page_name=="Add-Diagnostics") { echo "class=active"; } ?>><a href="Add-Diagnostics"><i class="fa fa-thermometer-quarter"></i>Diagnostics</a></li>
                            <li <?php if($page_name=="Add-Pharmacy") { echo "class=active"; } ?>><a href="Add-Pharmacy"><i class="fa fa-medkit"></i>Pharmacy</a></li>
							
                        </ul>