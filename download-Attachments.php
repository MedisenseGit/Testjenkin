<?php
/*header('Content-disposition: attachment; filename=FORMAT_FOR_PAPER_SUBMISSION.pdf');
header('Content-type: application/pdf');
readfile('FORMAT_FOR_PAPER_SUBMISSION.pdf');*/


if(!empty($_GET['attach_id'])){
header('Content-disposition: attachment; filename=Attach/'.$_GET['attach_id'].'/'.$_GET['attach_name']);
header('Content-type: application/jpg');
header('Content-type: application/doc');
readfile('Attach/'.$_GET['attach_id'].'/'.$_GET['attach_name']);
}


?>