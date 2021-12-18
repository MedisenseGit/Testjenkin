<?php

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

function check_bloglike($user_id,$post_id) {
$query = mysql_query("SELECT * FROM home_post_like WHERE likes='$user_id' and category_id='$post_id' and user_type=2 LIMIT 1");
$numlikes = mysql_num_rows($query);
return $numlikes;
}

function bloglike($post_id) {
$query = mysql_query("SELECT * FROM home_post_like WHERE category_id='$post_id'");
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
