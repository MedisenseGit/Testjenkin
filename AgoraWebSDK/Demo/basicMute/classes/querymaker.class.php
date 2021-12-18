<?php
//DEFINE HOST URL
define('HEALTH_API_KEY', '408413e9-dd13-4fea-b6fe-f81b1f12f7eb');
define('HOST_URL_PREMIUM', 'https://medisensemd.com/premium/');
define('HOST_MAIN_URL', 'https://medisensemd.com/');
define('HOST_HEALTH_URL', 'https://medisense.me/');
define('HOST_VIDEO_URL', 'https://maayayoga.com/msvV2.0/');
require_once("db.class.php");
class CLSQueryMaker{
	var $db_last="";
	var $strHostLink;
	var $strUsername;
	var $strPassWord;
	var $strDatabaseName;
	var $mySql;	
	var $strErrorMessage;
	/**
		* CLSQueryMaker()			: Constructor of the class
		* 
		* @param $strpHost			: Host to connect
		* @param $strpUserName		: User name to connect
		* @param $strpUserName		: User name to connect	
		* @param $strpPassword		: User password to connect	
		* @param $strpDatabaseName	: Name of the database
		* @return 
	**/		
		function CLSQueryMaker(){
			$this->mySql =  new CLSMySql();
			
		}

		/**
		 * mysqlRawquery()			: make a mysql query
		 * 
		 * @param $db_base			: database to access
		 * @param $query			: query to launch
		 * @return 
		 */
		
		function mysqlRawquery($query) {
			//echo $query;
			if(!$this->mySql->dbConnect()){
				$this->strErrorMessage = $this->mySql->getErrorMessage();
				return false;
			}else{
			}
			if(!$this->mySql->dbExecuteQuery($query)){					
				$this->strErrorMessage = $this->mySql->getErrorMessage();
				return false;
			}
			if (strtoupper(substr($query,0,6))=="SELECT"){
				$db_result=$this->mySql->dbFetchResult();
				return $db_result;
			}
			return true;
		}
		
		/**
		 * mysqlSelect()     : make a mysql select

		 * @param $fields     : list of field to select
		 * @param $tables     : list names of the tables
		 * @param $where      : where condition
		 * @param $order_by   : fields to be ordered by
		 * @param $group_by   : fields to be groupered by
		 * @param $having     : having condition
		 * @param $limit      : limit clause
		 * @return 
		 */
		function mysqlSelect($fields,$tables,$where="",$order_by="",$group_by="",$having="",$limit="") {
			$sql="SELECT $fields FROM $tables ";
			if (!empty($where)) $sql.="WHERE $where ";  
			if (!empty($group_by)) $sql.="GROUP BY $group_by ";  
			if (!empty($order_by)) $sql.="ORDER BY $order_by ";  
			if (!empty($having)) $sql.="HAVING $having ";  
			if (!empty($limit)) $sql.="LIMIT $limit "; 
			//echo $sql;// exit;
			$result = $this->mysqlRawquery($sql);			
			return $result;
		}

		function mysqlAffected(){
			return $this->mySql->dbGetAffectedRows();
		}

		function mysqlCountRows(){
			return $this->mySql->dbGetNumRows();
		}

		/**
			 * mysqlInsert()				: make a mysql insert

			 * @param $table				: name of the table
			 * @param $liste_champs			: array of the field to insert
			 * @param $liste_valeur			: array of the valued of the field to insert
			 * @return 
			 */
		function mysqlInsert($table,$liste_champs,$liste_valeur) {
			$sql="INSERT INTO `$table` ";
			if (count($liste_champs)==count($liste_valeur)+1) // have to find next_id and insert in $liste_valeur
				array_unshift($liste_valeur,$this->mysqlNextIndex($liste_champs[0],$table));
			$temp1=implode("`,`",$liste_champs);
			$temp2=implode("','",$liste_valeur);
			$sql.="(`$temp1`) VALUES ('$temp2')";
			//echo $sql . '<br /><br /><br />';  
			return $this->mysqlRawquery($sql);
		}

		/**
			 * mysqlUpdate()                  : make a mysql update
			 * @param $table				  : name of the table
			 * @param $liste_champs	          : array of the field to update
			 * @param $liste_valeur	          : array of the valued of the field to update
			 * @param $where				  : where condition
			 * @return 
		 */
		function mysqlUpdate($table,$liste_champs,$liste_valeur,$where='') {
				$sql="UPDATE `$table` SET ";
				for ($i=0;$i<count($liste_champs);$i++)
					$sql.="`".$liste_champs[$i]."`='".$liste_valeur[$i]."'".(($i==count($liste_champs)-1)?"":" , ");
				if(!empty($where)){
				  $sql.=" WHERE ($where)";
				}
          // echo $sql; 
				return $this->mysqlRawquery($sql);			
		}
			
		/**
			* mysqlDelete()			: make a mysql delete query
			* @param $table			    : name of the table
			* @param $where			    : where condition
			* @return 
		 */
		function mysqlDelete($table,$where) {
			if ($where!="") {
				 "<br>".$sql="DELETE FROM `$table` WHERE ($where)";
				//echo $sql;
				return $this->mysqlRawquery($sql);
			}
		}
		
		
		/**
			 * mysqlNextIndex()		: find the most free little index of the table
			 * @param $index			: the name of the index column
			 * @param $table			: name of the table
			 * @return 
		 */
		function mysqlNextIndex($index,$table) {
			$tab=$this->mysqlRawquery("SELECT $index FROM $table ORDER BY $index DESC LIMIT 0,1");
			//echo $tab;
			if (count($tab)==0)
				return 0;
			else
				return $tab[0][$index]+1;
		}
		
		/**
			 * mysqlSelectDiff()	: make a select a,b,c,d from table1 where (a not in select a from table2 where ())and/or()

			 * @param $db_base			: database to access
			 * @param $query_plus		: select of the lines we want
			 * @param $query_moins: !! select of the lines we don't want (!! 1 column only)
			 * @return
		 * 
		 */
		function mysqlSelectDiff($query_plus,$query_moins) {
			$tab_plus=$this->mysqlRawquery($query_plus);
			if ($query_moins!="") {
				$tab_moins=$this->mysqlRawquery($query_moins);
				if (count($tab_moins)>0) {	
					$keys1=array_keys($tab_plus[0]);
					$keys2=array_keys($tab_moins[0]);
					for ($i=0,$res=array();$i<count($tab_plus);$i++) {
						for ($j=0,$find=false;$j<count($tab_moins);$j++)
							if ($tab_moins[$j][$keys2[0]]==$tab_plus[$i][$keys1[0]])
								$find=true;
						if (!$find)
							$res[]=$tab_plus[$i];
					}
					return $res;
				}
				else
					return $tab_plus;
			}
			else
				return $tab_plus;
		}
		/**
			 * mysqlSelectValue()

			 * @param $query      : la requete avec seuleument 1 colonne selectionn?!!
			 * @param $default    : default value to return if query return null result
			 * @return la valeur retourn? en ligne 0 de la requete.
		 */
		function mysqlSelectValue($query,$default="") {
			$tab=$this->mysqlRawquery($query." LIMIT 0,1");
			if (count($tab)==1) {
				$keys=array_keys($tab[0]);	
				return $tab[0][$keys[0]];
			}
			else
				return $default;
		}
		function mysqlFreeResult(){
			$this->mySql->dbFreeResult();
		}
		function mysqlLastId(){
			return $this->mySql->dbGetLastId();
		}
		function getErrorMessage(){
			return $this->strErrorMessage;
		}
}
#Class Ends Here
?>
