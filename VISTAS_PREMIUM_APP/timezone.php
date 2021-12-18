<?php

/* // https://www.worldtimebuddy.com/
$the_date = strtotime("2010-01-19 09:15:43");
echo(date_default_timezone_get() . "\n");
echo(date("Y-d-mTG:i:sz",$the_date) . "\n");
echo(date_default_timezone_set("UTC") . "\n");
echo(date("Y-d-mTG:i:sz", $the_date) . "\n");
echo(date("G:i:s", $the_date) . "\n");   */
/*
$tz = 'Europe/Amsterdam';
$utctime = '2018-12-06 09:04:55';
date_default_timezone_set($tz);
if (!date_default_timezone_set($tz)) {
    echo "Setting default timezone to " . $tz . " failed";
    die;
}
*/

function dateToTimestamp($date, $format, $timezone='Europe/Belgrade')
{
    //returns an array containing day start and day end timestamps
    date_default_timezone_set($timezone);
    $date= date("Y-d-mTG:i:sz", $date);
    
    return $date;
}  

$timestamps1 = strtotime("2021-08-12 01:30:00");
//echo(date_default_timezone_get() . "\n");
date_default_timezone_set('Asia/Kolkata');
echo "\n";
echo(date_default_timezone_get() ." : ". date("Y-d-m T h:i:s a",$timestamps1));
echo "\n";

date_default_timezone_set('Europe/Amsterdam');
echo "\n";
echo "Europe/Amsterdam timestamps1: ".date("Y-d-m T h:i:s a",$timestamps1);
echo "\n";


date_default_timezone_set('Asia/Baghdad');
echo "\n";
echo "Asia/Baghdad timestamps1: ".date("Y-d-m T h:i:s a",$timestamps1);
echo "\n";

date_default_timezone_set('Europe/London');
echo "\n";
echo "Europe/London timestamps1: ".date("Y-d-m T h:i:s a",$timestamps1);
echo "\n";

date_default_timezone_set('UTC');
echo "\n";
echo "UTC timestamps1: ".date("Y-d-m T h:i:s a",$timestamps1);
echo "\n";

/*$timestamps=dateToTimestamp($timestamps1, 'Y-d-mTG:i:sz', 'Europe/London');
echo "\n";
echo $timestamps;
echo "\n"; */

?>