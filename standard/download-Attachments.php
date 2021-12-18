<?php
if(!empty($_GET['attach_name'])){
header('Content-disposition: attachment; filename='.$_GET['attach_name']);
header('Content-type: application/jpg');
header('Content-type: application/doc');
header('Content-type: application/pdf');
readfile('../Contributors/Jobdescription/'.$_GET['comp_id'].'/'.$_GET['attach_name']);
}	
if(!empty($_GET['attach_name']) && $_GET['type']=="event"){
header('Content-disposition: attachment; filename='.$_GET['attach_name']);
header('Content-type: application/jpg');
header('Content-type: application/doc');
header('Content-type: application/pdf');
readfile('../Contributors/EventAttachments/'.$_GET['event_id'].'/'.$_GET['attach_name']);
}

if(!empty($_GET['appid'])){
header('Content-disposition: attachment; filename='.$_GET['resume']);
header('Content-type: application/jpg');
header('Content-type: application/doc');
header('Content-type: application/pdf');
readfile('Resume/'.$_GET['appid'].'/'.$_GET['resume']);
}
if(!empty($_GET['episode_attach'])){
header('Content-disposition: attachment; filename='.$_GET['episode_attach']);
header('Content-type: application/jpg');
header('Content-type: application/doc');
header('Content-type: application/pdf');
readfile('episodeAttach/'.$_GET['attach_id'].'/'.$_GET['episode_attach']);
}		
?>