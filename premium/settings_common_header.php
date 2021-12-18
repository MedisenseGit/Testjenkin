<?php
//Get the page name 
$request_uri = str_replace("/premium/","",$_SERVER['REQUEST_URI']);
$param = explode("/",$request_uri);
$page_name =$param[0]; //'Add-Diagnostics';//// changed 17/11/2021

?>


<ul class="nav nav-tabs">
<li <?php if($page_name=="Add-Diagnostics") { echo "class=active"; } ?>><a href="Add-Diagnostics"><i class="fa fa-thermometer-quarter"></i>Diagnostics</a></li>
<li <?php if($page_name=="Add-Pharmacy") { echo "class=active"; } ?>><a href="Add-Pharmacy"><i class="fa fa-medkit"></i>Pharmacy</a></li>
<li <?php if($page_name=="Refer-Out-Doctor") { echo "class=active"; } ?>><a href="Refer-Out-Doctor"><i class="fa fa-user-md"></i>Doctors</a></li>
<?php if($getDocEMR[0]['spec_group_id']==2) { ?><li <?php if($page_name=="Add-Opticals") { echo "class=active"; } ?>><a href="Add-Opticals"><i class="fa fa-medkit"></i>Opticals</a></li><?php } ?>
<?php if($secretary_id!=1) { ?><li <?php if($page_name=="Add-Receptionist") { echo "class=active"; } ?>><a href="Add-Receptionist"><i class="fa fa-user"></i>Add Receptionist</a></li>
<li <?php if($page_name=="Other-Settings") { echo "class=active"; } ?>><a href="Other-Settings"><i class="fa fa-cog"></i>Other Settings</a></li><?php } ?>
<li <?php if($page_name=="EMR-Settings") { echo "class=active"; } ?>><a href="EMR-Settings"><i class="fa fa-cog"></i>EMR Settings</a></li>
<li <?php if($page_name=="Patient-Eduction") { echo "class=active"; } ?>><a href="Patient-Eduction"><i class="fa fa-info"></i>Patient Eduction</a></li>
<li <?php if($page_name=="Drug-Database") { echo "class=active"; } ?>><a href="Drug-Database"><i class="fa fa-medkit"></i>Drug Database</a></li>
</ul>