<?php 
ob_start();
error_reporting(0); 
session_start();

require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
?>

<!DOCTYPE HTML>
<html>

<head>  
	<script type="text/javascript">
	window.onload = function () {
		var chart = new CanvasJS.Chart("chartContainer", {

			title:{
				text:"Top Responded Hospital"				

			},
                        animationEnabled: true,
			axisX:{
				interval: 1,
				gridThickness: 0,
				labelFontSize: 10,
				labelFontStyle: "normal",
				labelFontWeight: "normal",
				labelFontFamily: "Lucida Sans Unicode"

			},
			axisY2:{
				interlacedColor: "rgba(1,77,101,.2)",
				gridColor: "rgba(1,77,101,.1)"

			},

			data: [
			{     
				type: "bar",
                name: "doctors",
				axisYType: "secondary",
				color: "#014D65",				
				dataPoints: [
				<?php $Top_responded_hosp= $objQuery->mysqlSelect("DISTINCT(d.hosp_id) as Hosp_Id","referal as a inner join doctor_hosp as c on c.doc_id=a.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","a.ref_id='".$_SESSION['user_id']."' and a.Total_Referred!=0","a.Tot_responded desc","","","0,10");

				foreach($Top_responded_hosp as $TopList){
				$get_Hosp = $objQuery->mysqlSelect("hosp_name as Hosp_Name","hosp_tab","hosp_id='".$TopList['Hosp_Id']."'","","","","");
				$Total_Responded_Hosp = $objQuery->mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","d.hosp_id='".$TopList['Hosp_Id']."' and b.response_status=2","","","","");

				?>

				{y: <?php echo $Total_Responded_Hosp[0]['Total_count']; ?>, label: "<?php echo $get_Hosp[0]['Hosp_Name']; ?>"  },
				<?php } ?>
				
				]
			}
			
			]
		});

chart.render();
}
</script>
<script type="text/javascript" src="canvasjs.min.js"></script>
</head>
<body>
	<div id="chartContainer" style="height: 300px; width: 100%;">
	</div>
</body>
</html>