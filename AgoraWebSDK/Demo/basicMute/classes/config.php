<?php

function getdb(){
$servername = "localhost";
$username = "root";
$password = "qe9Ke9BcdMT4KQY9@25Nova";
//$password = "";
$db = "nova_crm";
 
try {
   
    $conn = mysqli_connect($servername, $username, $password, $db);
     //echo "Connected successfully"; 
    }
catch(exception $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }
    return $conn;
}
?>