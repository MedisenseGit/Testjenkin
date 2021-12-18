<?php
//DEFINE HOST URL
define('HEALTH_API_KEY', '408413e9-dd13-4fea-b6fe-f81b1f12f7eb');
define('HOST_VIDEO_URL', 'https://maayayoga.com/msvV2.0/');
define('IMG_URL_VIEW', 'https://medisensemd.fra1.digitaloceanspaces.com/');

// Local URLs
// define('HOST_URL_PREMIUM', 'http://localhost/Medisensemd-CRM/premium/');
// define('HOST_MAIN_URL', 'http://localhost/Medisensemd-CRM/');
// define('HOST_HEALTH_URL', 'http://localhost/Medisense.me/');

//Server URLs
define('HOST_URL_PREMIUM', 'http://128.199.205.151/premium/');
define('HOST_MAIN_URL', 'http://128.199.205.151/');
define('HOST_HEALTH_URL', 'http://159.223.34.89/');

//require_once("db.class.php");
global $strHost;
    global $strUserName;
    global $strPassword;
    global $strDatabaseName;
    global $dbConnection;
    global $result;
    global $arrRows;
    global $strErrorMessage;
    global $insert_id;
    
    function mysqlRawquery($strpQuery){

		// For Local Setup
        // $strHost = "localhost";
        // $strUserName ='root';
        // $strPassword = '';
        // $strDatabaseName = 'nova_crm';  
		
		// For Server Setup
		$strHost = "localhost";
        $strUserName ='root';
        $strPassword = 'K6sT2H29MKWdNe2S@17Global';
        $strDatabaseName = 'nova_crm';		

        $dbConnection = mysqli_connect($strHost,$strUserName,$strPassword,$strDatabaseName);
        if (mysqli_connect_errno()) {
            $strErrorMessage = "Failed to connect to MySQL: " . mysqli_connect_error();
            return false;
        }
        // Perform query
        //$strResultSet = mysqli_query($dbConnection,$strpQuery);
        if (!$strResultSet = mysqli_query($dbConnection,$strpQuery)) {
            $strErrorMessage = "Error description: " . mysqli_error($dbConnection);
            return false;
        }

        $insert_id = mysqli_insert_id($dbConnection);

        mysqli_close($dbConnection);
        if (strtoupper(substr($strpQuery,0,6))=="SELECT"){
            $arrRows = array();
            while($rows = $strResultSet -> fetch_array(MYSQLI_ASSOC))
            {
                $arrRows[] = $rows;
            }
            return $arrRows;
        }
        if (strtoupper(substr($strpQuery,0,6))=="INSERT"){
            return $insert_id;
        }

        return true;
    }
    
    function mysqlSelect($fields,$tables,$where="",$order_by="",$group_by="",$having="",$limit="") {
        $sql="SELECT $fields FROM $tables ";
        if (!empty($where)) $sql.="WHERE $where ";  
        if (!empty($group_by)) $sql.="GROUP BY $group_by ";
        if (!empty($order_by)) $sql.="ORDER BY $order_by ";  	
        if (!empty($having)) $sql.="HAVING $having ";  
        if (!empty($limit)) $sql.="LIMIT $limit ";
         //echo $sql;
        $result = mysqlRawquery($sql);
        return $result;
    }

    function mysqlInsert($table,$liste_champs,$liste_valeur) {
        $sql="INSERT INTO `$table` ";
        if (count($liste_champs)==count($liste_valeur)+1) // have to find next_id and insert in $liste_valeur
				array_unshift($liste_valeur,mysqlNextIndex($liste_champs[0],$table));
        $temp1=implode("`,`",$liste_champs);
        $temp2=implode("','",$liste_valeur);
        $sql.="(`$temp1`) VALUES ('$temp2')";
         //echo $sql;
        $result = mysqlRawquery($sql);
        return $result;
    }

    function mysqlNextIndex($index,$table) {
        $tab = mysqlRawquery("SELECT $index FROM $table ORDER BY $index DESC LIMIT 0,1");
        if (count($tab)==0)
            return 0;
        else
            return $tab[0][$index]+1;
    }

    function mysqlUpdate($table,$liste_champs,$liste_valeur,$where='') {
        $sql="UPDATE `$table` SET ";
        for ($i=0;$i<count($liste_champs);$i++)
            $sql.="`".$liste_champs[$i]."`='".$liste_valeur[$i]."'".(($i==count($liste_champs)-1)?"":" , ");
        if(!empty($where)){
          $sql.=" WHERE ($where)";
        }
        // echo $sql;
        return mysqlRawquery($sql);	
    }

    function mysqlDelete($table,$where) {
        if ($where!="") {
             "<br>".$sql="DELETE FROM `$table` WHERE ($where)";
            //echo $sql;
            return mysqlRawquery($sql);
        }
    }

?>
