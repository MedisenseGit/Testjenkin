<?php
// Insert this file on server and go to the path from browser.

set_time_limit(0); //Unlimited max execution time

/* Source File URL */
$remote_file_url = 'https://medisensemd.com/DigitalOceanSpaces/sales_app_login_bg.jpg';
 
/* New file name and path for this file */
$local_file = 'sales_app_login_bg.jpg';
 
/* Copy the file from source url to server */
$copy = copy( $remote_file_url, $local_file );
 
/* Add notice for success/failure */
if( !$copy ) {
    echo "Doh! failed to copy $file...\n";
}
else{
    echo "WOOT! success to copy $file...\n";
}

?>