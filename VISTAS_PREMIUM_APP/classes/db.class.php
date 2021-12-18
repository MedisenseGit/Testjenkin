<?php
 
	class CLSMySql
	{
		#initialize the variables
		var $strHost;
		var $strUserName;
		var $strPassword;
		var $strDatabaseName;
		var $dbConnection;
		var $strResultSet;
		var $arrRows;
		var $strErrorMessage;
		#call the constructor
		function CLSMySql()
		{
			
			

			$this->strHost = "localhost";
			$this->strUserName ='root';
			$this->strPassword = 'qe9Ke9BcdMT4KQY9@25Nova';
			$this->strDatabaseName = 'nova_crm';
			
			/*$this->strHost = "localhost";
			$this->strUserName ='shashi_medisense';
			$this->strPassword = '}(-^E~y.+O2M';			
			$this->strDatabaseName = 'shashi_medisense_crm';*/
		}
		#function to connect
		function dbConnect()
		{
			$this->dbConnection = mysql_connect($this->strHost, $this->strUserName, $this->strPassword);
			if($this->dbConnection){
				if(!mysql_select_db($this->strDatabaseName, $this->dbConnection)){
					$this->strErrorMessage = ERR_NO_DATABASE ." ".mysql_error();
					return false;
				}
			}else{
				$this->strErrorMessage = ERR_CONNECT_SERVER." ".mysql_error();
				return false;
			}
			return true;
		}
		#Function to execute the Query
		function dbExecuteQuery($strpQuery)
		{	//echo $strpQuery;
			if(strtoupper(substr($strpQuery,0,6))=="SELECT"){
				$this->strResultSet = mysql_query($strpQuery);
				if(!$this->strResultSet){
					$this->strErrorMessage = ERR_BAD_QUERY." ".mysql_error();
					return false;
				}
				return true;	
			}else{
				if(mysql_query($strpQuery)){
					return true;
				}else{
					$this->strErrorMessage = "Unable to perform the request ".mysql_error();
					return false;
				}
			}
		}
		#function to fetch the result
		function dbFetchResult()
		{
			$this->arrRows = array();
			while($rows = mysql_fetch_array($this->strResultSet, MYSQL_ASSOC))
			{
				$this->arrRows[] = $rows;
			}
			return $this->arrRows;
		}
		#function to get the number of rows
		function dbGetNumRows()
		{
			return mysql_num_rows($this->strResultSet);
		}
		#function to get the affected rows
		function dbGetAffectedRows()
		{
			return mysql_affected_rows($this->dbConnection);	
		}
		#function to get the Last generated id from an insert statement
		function dbGetLastId()
		{
			return mysql_insert_id();
		}
		#function to close the Database connection
		function dbClose()
		{
			mysql_close($this->dbConnection);
			return true;
		}
		function dbFreeResult(){
			mysql_free_result ($this->strResultSet);
			return true;
		}
		function getErrorMessage()
		{
			return $this->strErrorMessage;	
		}
		
	}
?>
