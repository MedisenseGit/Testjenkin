 <!-- Favicons================================================== -->
<link rel="shortcut icon" href="../attachments/new_assets/img/favicon.ico">
    <!-- Bootstrap -->
    <link href="../Hospital/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../Hospital/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../Hospital/vendors/nprogress/nprogress.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="../Hospital/build/css/custom.min.css" rel="stylesheet">
    <!-- END PAGE LEVEL  STYLES -->
      
	  <script>
	  
function showHospital(str) {
    if (str == "") {
        document.getElementById("editContent").innerHTML = "";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("editContent").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","edit_hospital.php?hosp_id="+str,true);
        xmlhttp.send();
    }
	
	$("#addHospSection").hide();
}
function showDoctor(str) {
    if (str == "") {
        document.getElementById("editContent").innerHTML = "";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("editContent").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","edit_hosp_doctor.php?doc_id="+str,true);
        xmlhttp.send();
    }
	
	$("#addDoctorSection").hide();
}

function showMarketTeam(str) {
    if (str == "") {
        document.getElementById("editContent").innerHTML = "";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("editMarketContent").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","edit_marketing_person.php?person_id="+str,true);
        xmlhttp.send();
    }
	
	$("#addMarketSection").hide();
}
function showRefPartner(str) {
    if (str == "") {
        document.getElementById("editContent").innerHTML = "";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("editContent").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","edit_referring_partner.php?partner_id="+str,true);
        xmlhttp.send();
    }
	
	$("#addPartnerSection").hide();
}

</script> 

 <script>
function getState(val) {
	$.ajax({
	type: "POST",
	url: "get_state.php",
	data:{"country_name":val},
	success: function(data){
		$("#slctState").html(data);
	}
	});
}
</script>