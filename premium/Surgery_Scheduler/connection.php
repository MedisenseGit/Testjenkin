<?php
// DATABASE CONNECTION STRING
ob_start();
error_reporting(0); 
session_start();
$admin_id = $_SESSION['user_id'];
define('DOCID', $admin_id);
define('HOST', 'localhost');
define('USERNAME', 'root');
define('PASSWORD', 'qe9Ke9BcdMT4KQY9@25Nova');
define('DATABASE_NAME', 'nova_crm');

//Connect and select the database
$db = new mysqli(HOST, USERNAME, PASSWORD, DATABASE_NAME);

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

?>