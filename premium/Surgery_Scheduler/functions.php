<?php
ob_start();
error_reporting(0); 
session_start();

$admin_id = $_SESSION['user_id'];
if(empty($admin_id)){
	header("Location:../index.php");
}
/*
 * This function create By Jignesh Patel	 
 * Function requested by Ajax
 */
if(isset($_REQUEST['fun_type']) && !empty($_REQUEST['fun_type'])){
	switch($_REQUEST['fun_type']){
		case 'get_calender_full':
			get_calender_full($_REQUEST['year'],$_REQUEST['month']);
			break;
		case 'get_Surgery_information':
			get_Surgery_information($_REQUEST['date']);
			break;
		//For Add Surgery with date wise.
		case 'add_event_information':
			add_event_information($_REQUEST['date'],$_REQUEST['title']);
			break;
		default:
			break;
	}
}

/*
 * Get Full calendar in html
 */
function get_calender_full($year = '',$month = '')
{
	$date_Year = ($year != '')?$year:date("Y");
	$date_Month = ($month != '')?$month:date("m");
	$date = $date_Year.'-'.$date_Month.'-01';
	$current_Month_First_Day = date("N",strtotime($date));
	$total_Days_ofMonth = cal_days_in_month(CAL_GREGORIAN,$date_Month,$date_Year);
	$total_Days_ofMonthDisplay = ($current_Month_First_Day == 7)?($total_Days_ofMonth):($total_Days_ofMonth + $current_Month_First_Day);
	$boxDisplay = ($total_Days_ofMonthDisplay <= 35)?35:42;
?>

 	<div id="calender_section">
		<h2>
        	<a href="javascript:void(0);" onclick="get_calendar_data('calendar_div','<?php echo date("Y",strtotime($date.' - 1 Month')); ?>','<?php echo date("m",strtotime($date.' - 1 Month')); ?>');">&lt;</a>
            <select name="month_dropdown" class="month_dropdown dropdown"><?php echo get_all_months__of_year($date_Month); ?></select>
			<select name="year_dropdown" class="year_dropdown dropdown"><?php echo get_year($date_Year); ?></select>
            <a href="javascript:void(0);" onclick="get_calendar_data('calendar_div','<?php echo date("Y",strtotime($date.' + 1 Month')); ?>','<?php echo date("m",strtotime($date.' + 1 Month')); ?>');">&gt;</a>
        </h2>
        <!-- event_list is used for view event with popup -->
		<div id="event_list" class="modal"></div>
		<!-- End of event list popup -->

        <!--Below Code for Event Add-->

        <!-- Popup div start here -->
		<div id="event_add" class="modal">
		  <div class="modal-content">
		    <span class="close"><a href="#" onclick="close_popup('event_add')">×</a></span>
		    		<p>Add Surgery on <span id="eventDateView"></span></p>
		            <p><b>Surgery Title: </b><input type="text" id="eventTitle" value=""/></p>
		            <input type="hidden" id="eventDate" value=""/>
					<input type="hidden" id="docId" value="<?php echo $admin_id; ?>"/>
		            <input type="button" id="add_event_informationBtn" value="Add"/>
		  </div>
		</div>
		<!-- Popup hmmt end. -->

        <div id="calender_section_top">
			<ul>
				<li>SUN<?php echo $admin_id; ?></li>
				<li>MON</li>
				<li>TUE</li>
				<li>WED</li>
				<li>THU</li>
				<li>FRI</li>
				<li>SAT</li>
			</ul>
		</div>
		<div id="calender_section_bot">
			<ul>
			<?php 

			// this is for create calendra and view Add Surgery and view event and number of Surgery

				$dayCount = 1; 
				for($cb=1;$cb<=$boxDisplay;$cb++){
					if(($cb >= $current_Month_First_Day+1 || $current_Month_First_Day == 7) && $cb <= ($total_Days_ofMonthDisplay)){
						
						// Below javascript code for get current date
						
						$currentDate = $date_Year.'-'.$date_Month.'-'.$dayCount;
						$eventNum = 0;
							
						// Below line for including file of database connection file
						include 'connection.php';

						// Below query useing for getting number of Surgery in current date

						$result = $db->query("SELECT title FROM ot_scheduler WHERE date = '".$currentDate."' AND doc_id = '".DOCID."' AND doc_type='1'");
						$eventNum = $result->num_rows;
						
						//Define date cell color
						if(strtotime($currentDate) == strtotime(date("Y-m-d"))){
							echo '<li date="'.$currentDate.'" class="grey date_cell">';
						}elseif($eventNum > 0){
							echo '<li date="'.$currentDate.'" class="light_sky date_cell">';
						}else{
							echo '<li date="'.$currentDate.'" class="date_cell">';
						}
						//Date cell
						echo '<span>';
						echo '['.$dayCount.']';
						echo '</span>';
						
						//Hover event popup
						echo '<div id="date_popup_'.$currentDate.'" class="date_popup_wrap none">';
						echo '<div class="date_window">';
						echo '<div class="popup_event">Surgery ('.$eventNum.')</div>';
						echo ($eventNum > 0)?'<a href="javascript:;" onclick="get_Surgery_information(\''.$currentDate.'\');">View Surgery</a><br/>':'';
						//For Add Surgery
						//echo '<a href="javascript:;" onclick="add_event_information(\''.$currentDate.'\');">Add Surgery</a>';
						echo '</div></div>';
						
						echo '</li>';
						$dayCount++;
			?>
			<?php }else{ ?>
				<li><span>&nbsp;</span></li>
			<?php } } ?>
			</ul>
		</div>
	</div>

	<script type="text/javascript">
	// ajax call to get event detail from database.
		function get_calendar_data(target_div,year,month){
			$.ajax({
				type:'POST',
				url:'functions.php',
				data:'fun_type=get_calender_full&year='+year+'&month='+month,
				success:function(html){
					$('#'+target_div).html(html);
				}
			});
		}
		
		function get_Surgery_information(date){
			$.ajax({
				type:'POST',
				url:'functions.php',
				data:'fun_type=get_Surgery_information&date='+date,
				success:function(html){
					$('#event_list').html(html);
					$('#event_add').slideUp('slow');
					$('#event_list').slideDown('slow');
				}
			});
		}
		
		/*
		* function name add_event_information
		* Description :- Add Surgery inforation as per date wise
		* parameter :- date
		*/
		function add_event_information(date){
			$('#eventDate').val(date);
			$('#eventDateView').html(date);
			$('#event_list').slideUp('slow');
			$('#event_add').slideDown('slow');
		}

		/*
		*  below code used for save event information into databse. and set message event created successfully.
		*/
		$(document).ready(function(){

			$('#add_event_informationBtn').on('click',function(){
				var date = $('#eventDate').val();
				var title = $('#eventTitle').val();
				var docid = $('#docId').val();
				alert(docid);
				$.ajax({
					type:'POST',
					url:'functions.php',
					data:'fun_type=add_event_information&date='+date+'&title='+title,
					success:function(msg){
						if(msg == 'ok'){
							var dateSplit = date.split("-");
							$('#eventTitle').val('');
							alert('Event Created.');
							get_calendar_data('calendar_div',dateSplit[0],dateSplit[1]);
						}else{
							alert('Sorry some issues please try again later.');
						}
					}
				});
			});
		});
		
		$(document).ready(function(){
			$('.date_cell').mouseenter(function(){
				date = $(this).attr('date');
				$(".date_popup_wrap").fadeOut();
				$("#date_popup_"+date).fadeIn();	
			});
			$('.date_cell').mouseleave(function(){
				$(".date_popup_wrap").fadeOut();		
			});
			$('.month_dropdown').on('change',function(){
				get_calendar_data('calendar_div',$('.year_dropdown').val(),$('.month_dropdown').val());
			});
			$('.year_dropdown').on('change',function(){
				get_calendar_data('calendar_div',$('.year_dropdown').val(),$('.month_dropdown').val());
			});
			$(document).click(function(){
				$('#event_list').slideUp('slow');
			});

		});

		
		// Closed popup string	
		function close_popup(event_id)
		{
			$('#'+event_id).css('display','none');
		}
	</script>
<?php
}

/*
 * below function with get all month  list
 * optional parameter >> $selected
 */

function get_all_months__of_year($selected = ''){
	$options = '';
	for($i=1;$i<=12;$i++)
	{
		$value = ($i < 01)?'0'.$i:$i;
		$selectedOpt = ($value == $selected)?'selected':'';
		$options .= '<option value="'.$value.'" '.$selectedOpt.' >'.date("F", mktime(0, 0, 0, $i+1, 0, 0)).'</option>';
	}
	return $options;
}

/*
 * below function with get all year list
 * optional parameter >> $selected
 */
function get_year($selected = ''){
	$options = '';
	for($i=2015;$i<=2025;$i++)
	{
		$selectedOpt = ($i == $selected)?'selected':'';
		$options .= '<option value="'.$i.'" '.$selectedOpt.' >'.$i.'</option>';
	}
	return $options;
}

/********************************************
 * below function used for display event as per date
 * optional parameter is date.
 *******************************************/

function get_Surgery_information($date = ''){
	
		//below line for including file of database connection file

	include 'connection.php';
	$eventListHTML = '';
	$date = $date?$date:date("Y-m-d");
	//Get Surgery based on the current date
	$result = $db->query("SELECT title,time,patient_id FROM ot_scheduler WHERE date = '".$date."' AND doc_id = '".DOCID."' AND doc_type='1'");
	if($result->num_rows > 0){
		$eventListHTML .= '<div class="modal-content">';
		$eventListHTML .= '<span class="close"><a href="#" onclick="close_popup("event_list")">×</a></span>';
		$eventListHTML .= '<h2>Surgery on '.date("l, d M Y",strtotime($date)).'</h2>';
		$eventListHTML .= '<ul>';
		while($row = $result->fetch_assoc()){ 
            //$patName = $db->query("SELECT patient_name FROM doc_my_patient WHERE patient_id = '".$row['patient_id']."' AND doc_id = '".DOCID."'");
			$patName = $db->query("SELECT a.patient_name as patient_name FROM patients_appointment as a inner join patients_transactions as b on a.patient_id=b.patient_id WHERE a.patient_id = '".$row['patient_id']."' AND b.doc_id = '".DOCID."'");
			
		  while($rowName = $patName->fetch_assoc()){
            $eventListHTML .= '<li>'.$row['title'].'  (<b>To</b> '.$rowName['patient_name'].' <b>at</b> '.$row['time'].')</li>';
		 }
        }
		$eventListHTML .= '</ul>';
		$eventListHTML .= '</div>';
	}
	echo $eventListHTML;
}

/**********************************************************
 * below function is used for Add Surgery in paraticular date
 * parameter is >>> date , title
 **********************************************************/
function add_event_information($date,$title){
	
		//below line for including file of database connection file
	
	include 'connection.php';
	$currentDate = date("Y-m-d H:i:s");
	//Insert the event data into database
	$insert = $db->query("INSERT INTO ot_scheduler (doc_id,doc_type,title,status,date,created,modified) VALUES ('".DOCID."','1','".$title."','Scheduled','".$date."','".$currentDate."','".$currentDate."')");
	if($insert){
		echo 'ok';
	}else{
		echo 'err';
	}
}
?>