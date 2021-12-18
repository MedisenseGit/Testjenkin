<link rel="icon" href="../assets/img/favicon_icon.png">
<link href="../assets/css/bootstrap.min.css" rel="stylesheet">
<link href="../assets/font-awesome/css/font-awesome.css" rel="stylesheet">
<link href="../assets/css/plugins/slick/slick.css" rel="stylesheet">
<link href="../assets/css/plugins/slick/slick-theme.css" rel="stylesheet">
<link href="../assets/css/animate.css" rel="stylesheet">
<link href="../assets/css/style.css" rel="stylesheet">
<!-- Bootstrap Tour -->
<link href="../assets/css/plugins/bootstrapTour/bootstrap-tour.min.css" rel="stylesheet">
<!-- Sweet Alert -->
<link href="../assets/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
<script>
function showMoreJobs(str)
{
	//alert(str.val);
	if (str == "")
	{
		document.getElementById("showMoreJobs").innerHTML = "";
		return;
	} 
	else
	{ 
		if (window.XMLHttpRequest) 
		{
			// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp = new XMLHttpRequest();
		} 
		else
		{
			// code for IE6, IE5
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() 
		{
			if (this.readyState == 4 && this.status == 200)
			{
				document.getElementById("showMoreJobs").innerHTML = this.responseText;
			}
		};
		xmlhttp.open("GET","show_more_jobs.php?load_id="+str,true);
		xmlhttp.send();
	}
	$("#loadMoreVideo").hide();
}
function showMoreVideo(str)
{
	if (str == "") 
	{
        document.getElementById("showMoreVideo").innerHTML = "";
        return;
    } 
	else
	{ 
        if (window.XMLHttpRequest)
		{
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } 
		else 
		{
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) 
			{
                document.getElementById("showMoreVideo").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","show_more_videos.php?load_id="+str,true);
        xmlhttp.send();
    }
	$("#loadMoreVideo").hide();
}
function showMoreEvents(str)
{
	if (str == "") 
	{
        document.getElementById("showMoreEvents").innerHTML = "";
        return;
    } 
	else 
	{ 
        if (window.XMLHttpRequest) 
		{
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } 
		else 
		{
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200)
			{
                document.getElementById("showMoreEvents").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","show_more_events.php?load_id="+str,true);
        xmlhttp.send();
    }
	$("#loadMoreEvents").hide();
}
function showmoreBlogs(str)
{
	if (str == "")
	{
        document.getElementById("showMoreBlogs").innerHTML = "";
        return;
    } 
	else 
	{ 
        if (window.XMLHttpRequest) 
		{
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } 
		else 
		{
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("showmoreBlogs").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","show_more_blogs.php?load_id="+str,true);
        xmlhttp.send();
    }
	$("#loadMoreBlogs").hide();
}
function showmoreFeed(str)
{
	
    if (str == "")
	{
        document.getElementById("showMore").innerHTML = "";
        return;
    } 
	else 
	{ 
        if (window.XMLHttpRequest) 
		{
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } 
		else 
		{
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) 
			{
                document.getElementById("showMore").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","show_more.php?load_id="+str,true);
        xmlhttp.send();
    }
	$("#loadMore").hide();
}
</script>