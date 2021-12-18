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
				text:"Top Responded Doctors"				

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
				<?php $Top_responded_doc= $objQuery->mysqlSelect("a.ref_id as Ref_Id,a.ref_name as Ref_Name,a.Tot_responded as Tot_Responded,a.Total_Referred as TotalReferred,d.hosp_name as Hopital_Name,a.doc_spec as Specialization","referal as a inner join doctor_hosp as c on c.doc_id=a.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","a.ref_id='".$_SESSION['user_id']."' and a.Total_Referred!=0","","","","0,10");
				foreach($Top_responded_doc as $TopList){
				$Total_Responded_Doc = $objQuery->mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","b.ref_id='".$TopList['Ref_Id']."' and b.response_status=2","","","","");

				?>

				{y: <?php echo $Total_Responded_Doc[0]['Total_count']; ?>, label: "<?php echo $TopList['Ref_Name']; ?>"  },
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