<?php ob_start();
 error_reporting(0);
 session_start(); 

 
//connect to the DB
require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

	$_SESSION['new_terms_condition']=0;
?>

<!DOCTYPE html>
<html>
<head>


   <link href="jquery.multiselect.css" rel="stylesheet" type="text/css">


</head>

<body>

<h2>A demo of using multi-select with checkboxes</h2>
    

<select name="multicheckbox[]" multiple="multiple" class="form-control">

<!--select id="specialization" name="specialization" class="form-control"  onchange="return getSubSpecific(this.value);" -->	
											<option value="" >Select Specialization</option>

											<?php 
											$SrcName= $objQuery->mysqlSelect("*","specialization","","spec_name asc","","","");
											$i=30;
											foreach($SrcName as $srcList){ ?>

											<option value="<?php echo stripslashes($srcList['spec_id']);?>" />
											<?php echo stripslashes($srcList['spec_name']);?></option>


											<?php 	$i++;
											}?>   
											</select>



<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> 
<script src="jquery.multiselect.js"></script>
<script>
$('select[multiple]').multiselect({
    columns: 1,
    placeholder: 'Select options'
});
</script>
</body>
</html>
