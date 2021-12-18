<?php
 function con_min_days($mins)
    {

            $hours = str_pad(floor($mins /60),2,"0",STR_PAD_LEFT);
            $mins  = str_pad($mins %60,2,"0",STR_PAD_LEFT);

            if((int)$hours > 24){
            $days = str_pad(floor($hours /24),2,"0",STR_PAD_LEFT);
            $hours = str_pad($hours %24,2,"0",STR_PAD_LEFT);
            }
            if(isset($days)) { $days = $days." Day[s] ";}

            return $days.$hours." Hour[s] ".$mins." Min[s]";
    }
function hyphenize($string) {
    return 
    ## strtolower(
          preg_replace(
            array('#[\\s-]+#', '#[^A-Za-z0-9\. -]+#'),
            array('-', ''),
        ##     cleanString(
              urldecode($string)
        ##     )
        )
    ## )
    ;
}
//function used to fetch youtube video id from youtube url
function get_youtubeid($url)
{
		$parse = parse_url($url);
		if(!empty($parse['query'])) {
		  preg_match("/v=([^&]+)/i", $url, $matches);
		  return $matches[1];
		} else {
		  //to get basename
		  $info = pathinfo($url);
		  return $info['basename'];
		}
}

//function used to convert normal date & time to facebook like timeago
function timeAgo($datefrom,$dateto=-1)
	{
		// Defaults and assume if 0 is passed in that
		// its an error rather than the epoch
		
		if($datefrom<=0) { return "A long time ago"; }
		if($dateto==-1) { $dateto = time(); }
		
		// Calculate the difference in seconds betweeen
		// the two timestamps
		
		$difference = $dateto - $datefrom;
		
		// If difference is less than 60 seconds,
		// seconds is a good interval of choice
		
		if($difference < 60)
		{
		$interval = "s";
		}
		
		// If difference is between 60 seconds and
		// 60 minutes, minutes is a good interval
		elseif($difference >= 60 && $difference<60*60)
		{
		$interval = "n";
		}
		
		// If difference is between 1 hour and 24 hours
		// hours is a good interval
		elseif($difference >= 60*60 && $difference<60*60*24)
		{
		$interval = "h";
		}
		
		// If difference is between 1 day and 7 days
		// days is a good interval
		elseif($difference >= 60*60*24 && $difference<60*60*24*7)
		{
		$interval = "d";
		}
		
		// If difference is between 1 week and 30 days
		// weeks is a good interval
		elseif($difference >= 60*60*24*7 && $difference <
		60*60*24*30)
		{
		$interval = "ww";
		}
		
		// If difference is between 30 days and 365 days
		// months is a good interval, again, the same thing
		// applies, if the 29th February happens to exist
		// between your 2 dates, the function will return
		// the 'incorrect' value for a day
		elseif($difference >= 60*60*24*30 && $difference <
		60*60*24*365)
		{
		$interval = "m";
		}
		
		// If difference is greater than or equal to 365
		// days, return year. This will be incorrect if
		// for example, you call the function on the 28th April
		// 2008 passing in 29th April 2007. It will return
		// 1 year ago when in actual fact (yawn!) not quite
		// a year has gone by
		elseif($difference >= 60*60*24*365)
		{
		$interval = "y";
		}
		
		// Based on the interval, determine the
		// number of units between the two dates
		// From this point on, you would be hard
		// pushed telling the difference between
		// this function and DateDiff. If the $datediff
		// returned is 1, be sure to return the singular
		// of the unit, e.g. 'day' rather 'days'
		
		switch($interval)
		{
		case "m":
		$months_difference = floor($difference / 60 / 60 / 24 /
		29);
		while (mktime(date("H", $datefrom), date("i", $datefrom),
		date("s", $datefrom), date("n", $datefrom)+($months_difference),
		date("j", $dateto), date("Y", $datefrom)) < $dateto)
		{
		$months_difference++;
		}
		$datediff = $months_difference;
		
		// We need this in here because it is possible
		// to have an 'm' interval and a months
		// difference of 12 because we are using 29 days
		// in a month
		
		if($datediff==12)
		{
		$datediff--;
		}
		
		$res = ($datediff==1) ? "$datediff month ago" : "$datediff
		months ago";
		break;
		
		case "y":
		$datediff = floor($difference / 60 / 60 / 24 / 365);
		$res = ($datediff==1) ? "$datediff year ago" : "$datediff
		years ago";
		break;
		
		case "d":
		$datediff = floor($difference / 60 / 60 / 24);
		$res = ($datediff==1) ? "$datediff day ago" : "$datediff
		days ago";
		break;
		
		case "ww":
		$datediff = floor($difference / 60 / 60 / 24 / 7);
		$res = ($datediff==1) ? "$datediff week ago" : "$datediff
		weeks ago";
		break;
		
		case "h":
		$datediff = floor($difference / 60 / 60);
		$res = ($datediff==1) ? "$datediff hour ago" : "$datediff
		hours ago";
		break;
		
		case "n":
		$datediff = floor($difference / 60);
		$res = ($datediff==1) ? "$datediff minute ago" :
		"$datediff minutes ago";
		break;
		
		case "s":
		$datediff = $difference;
		$res = ($datediff==1) ? "$datediff second ago" :
		"$datediff seconds ago";
		break;
		}
		return $res;
}


function check_going($partner_id,$event_id) {
$query = mysql_query("SELECT * FROM event_visitors_tab WHERE going='$partner_id' and event_id='$event_id' LIMIT 1");
$likes = mysql_num_rows($query);
return $likes;
}

function going($event_id) {
$query = mysql_query("SELECT * FROM event_visitors_tab WHERE event_id='$event_id' and going!=0");
$num_goings = mysql_num_rows($query);
return $num_goings;
}

function check_maybe($partner_id,$event_id) {
$query = mysql_query("SELECT * FROM event_visitors_tab WHERE maybe='$partner_id' and event_id='$event_id' LIMIT 1");
$maybe = mysql_num_rows($query);
return $maybe;
}

function maybe($event_id) {
$query = mysql_query("SELECT * FROM event_visitors_tab WHERE event_id='$event_id' and maybe!=0");
$num_maybe = mysql_num_rows($query);
return $num_maybe;
}

function check_cannot($partner_id,$event_id) {
$query = mysql_query("SELECT * FROM event_visitors_tab WHERE cannotgo='$partner_id' and event_id='$event_id' LIMIT 1");
$cannot = mysql_num_rows($query);
return $cannot;
}

function cannot($event_id) {
$query = mysql_query("SELECT * FROM event_visitors_tab WHERE event_id='$event_id' and cannotgo!=0");
$num_cannot = mysql_num_rows($query);
return $num_cannot;
}

function check_bloglike($user_id,$cat_type,$post_id,$user_type) {
$query = mysql_query("SELECT * FROM home_post_like WHERE likes='$user_id' and category_id='$post_id' and category_type='$cat_type' and  user_type='$user_type' LIMIT 1");
$numlikes = mysql_num_rows($query);
return $numlikes;
}

function bloglike($post_id,$cat_type) {
$query = mysql_query("SELECT * FROM home_post_like WHERE category_id='$post_id' and category_type='$cat_type'");
$num_like = mysql_num_rows($query);
return $num_like;
}

//PAGINATION FUNCTION
function firstPaging($pag_result,$limit,$back,$next,$eu,$field,$type2){
				$nume = count($pag_result);
				if($nume>=500){
					$tot_num=500;
				}else {
					$tot_num=count($pag_result);
				}
				$this1 = $eu + $limit; 
				$strPaging = "";
				$strPaging.="<div class='doc_paging'>";
				if($back >=0){ 
					$strPaging.="<a href='$page_name?start=$back' class='doc_paging_prev_btn doc_paging_prev' onclick='ShowLoading()'><<</font></a>"; 
				}else{
					$strPaging.=""; 
				}
				$strPaging.="";
				$i=0;
				$l=1;
				$strPaging.="";
				for($i=0;$i < $tot_num;$i=$i+$limit){
					if($i <> $eu){
						$strPaging.="<a href='$page_name?start=$i' class='doc_paging_hide' onclick='ShowLoading()'>$l</a>";
					}else{
						$strPaging.="<span class='doc_paging_hide'>$l</span>";
					}
					$l=$l+1;
				}
				$strPaging.="";
				if($this1 < $nume) { 
					
					$strPaging.="<a href='$page_name?start=$next' class='doc_paging_next_btn doc_paging_next' onclick='ShowLoading()'>>></a>";
				}else{
					$strPaging.="";
				}

				$strPaging.="</div>";
				return $strPaging."-".$nume;
}

?>
