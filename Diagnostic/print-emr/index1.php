<?php
ob_start();
error_reporting(0); 

require_once("../../classes/querymaker.class.php");


		
	$patient_tab = mysqlSelect("*","doc_my_patient","md5(patient_id)='".$_GET['pid']."'","","","","");
	//print_r($patient_tab);
	if(COUNT($patient_tab)==0){
	echo "<h2>Error!!!!!!!</h2>";
	}
	else{
			
			echo $patient_id = $patient_tab[0]['patient_id']; //Patient ID			
			echo $patient_name = $patient_tab[0]['patient_name']; //Patient Name			
			echo $patient_age = $patient_tab[0]['patient_age']; //Patient Age	
			echo $patient_mob = $patient_tab[0]['patient_mob']; //Patient Mobile No.				
			$patient_loc = $patient_tab[0]['patient_loc']; //Patient City	
			$patient_state = $patient_tab[0]['pat_state']; //Patient State
			$patient_country = $patient_tab[0]['pat_country']; //Patient Country
			$patient_address = $patient_tab[0]['patient_addrs']; //Patient Country			
			
			if($patient_tab[0]['patient_gen']=="1"){
				$patient_gender="Male";
			}
			else if($patient_tab[0]['patient_gen']=="2"){   //Patient Gender
				$patient_gender="Female";
			}
			else if($patient_tab[0]['patient_gen']=="3"){
				$patient_gender="Other";
			}			

			if($patient_tab[0]['hyper_cond']=="2"){
				$hyperStatus="No";								//Patient Hyper Status
			}
			else if($patient_tab[0]['hyper_cond']=="1"){
				$hyperStatus="Yes";
			}
			
			
			if($patient_tab[0]['diabetes_cond']=="2"){
				$diabetesStatus="No";							//Patient Diabetes Status
			}
			else if($patient_tab[0]['diabetes_cond']=="1"){
				$diabetesStatus="Yes";
			}
		
		//Episode Details
			$patient_id = $patient_tab[0]['patient_id'];

			$patient_episodes = mysqlSelect("episode_id,date_time,admin_id","doc_patient_episodes","patient_id = '". $patient_id ."' and md5(episode_id)='".$_GET['episode']."'","","","","");
			
			$episode_created_date=date('d M Y, H:i',strtotime($patient_episodes[0]['date_time']));   //Prescription Date
			
			
			$get_medical_complaint = mysqlSelect("b.symptoms as symptoms","doc_patient_symptoms_active as a left join chief_medical_complaints as b on a.symptoms=b.complaint_id","a.episode_id='".$patient_episodes[0]['episode_id']."'","","","","");
				if(!empty($get_medical_complaint)){
					while(list($key_symp, $value_symp) = each($get_medical_complaint)){
						echo $value_symp['symptoms'].", ";      //Patient Chief medical complaints
					} //end while
				} //endif
				
			$get_diagnosis = mysqlSelect("b.icd_code as icd_code","patient_diagnosis as a left join icd_code as b on a.icd_id=b.icd_id","a.episode_id='".$patient_episodes[0]['episode_id']."'","","","","");
				if(!empty($get_diagnosis)){
					while(list($key_diagno, $value_diagno) = each($get_diagnosis)){
						echo $value_diagno['icd_code'].", <br>";  //Patient Diagnosis Conditions
					} //end while
				} //endif
			
		//Doctors Details	
			$get_doc_details = mysqlSelect("ref_name","referal","ref_id='".$patient_episodes[0]['admin_id']."'","","","","");
			echo $Doctor_name = $get_doc_details[0]['ref_name'];  //Doctor Name
			
		//Doctors Specialization
			$get_doc_spec = mysqlSelect("a.spec_name as specialization","specialization as a inner join doc_specialization as b on a.spec_id=b.spec_id","b.doc_id='".$patient_episodes[0]['admin_id']."'","","","","");
			
			while(list($key_spec, $value_spec) = each($get_doc_spec)){
				echo $value_spec['specialization'].", ";   //Doctor Specialization				
			}
			
		//Doctors Clinic Details
			$get_doc_clinic = mysqlSelect("a.hosp_name as Hospital,a.hosp_addrs as Hosp_address,a.hosp_city as hosp_city,a.hosp_state as hosp_state,a.hosp_country as hosp_country","hosp_tab as a inner join doctor_hosp as b on a.hosp_id=b.hosp_id","b.doc_id='".$patient_episodes[0]['admin_id']."'","","","","");
			
			echo $Clinic_name = $get_doc_clinic[0]['Hospital'];  //Clinic Name
			echo $Clinic_address = $get_doc_clinic[0]['Hosp_address'];  //Clinic Address
			echo $Clinic_City = $get_doc_clinic[0]['hosp_city'];  //Clinic Address
			$Clinic_State= $get_doc_clinic[0]['hosp_state'];  //Clinic State
			$Clinic_Country = $get_doc_clinic[0]['hosp_country'];  //Clinic Country
		

		//Prescription Details
		$doc_patient_episode_prescriptions = mysqlSelect("*","doc_patient_episode_prescriptions","episode_id = '". $patient_episodes[0]['episode_id'] ."' "," prescription_seq ASC","","","");
			if (COUNT($doc_patient_episode_prescriptions) > 0)
				{
		?>	
		<table class="table table-bordered">
				<thead>
					<tr>
						<th>Medicine</th>
						<th>Generic Name</th>
						<th>Frequency</th>
						<th>Timing</th>
						<th>Duration</th>
					</tr>
				</thead>
					<tbody>
					<?php
						while (list($patient_episode_prescription_key, $patient_episode_prescription_val) = each($doc_patient_episode_prescriptions))
							{
					?>
						<tr>
							<td><?php echo $patient_episode_prescription_val['prescription_trade_name'] ?></td>
							<td><?php echo $patient_episode_prescription_val['prescription_generic_name'] ?></td>
							<td><?php echo $patient_episode_prescription_val['prescription_frequency'] ?></td>
							<td><?php echo $patient_episode_prescription_val['timing'] ?></td>
							<td><?php echo $patient_episode_prescription_val['duration'] ?></td>
						</tr>
					<?php } //end while ?>
					</tbody>
		</table>
			<?php } //endif
	
	} //endif
?>