<?php

	/*****************************************************************
	 
	 MYSQL DB ACCESS TOOL VERSION 1.1
	 
	 Created By
	 ---------------------------------
	 David Green
	 dave@adhost.com
	 Adhost Internet
	 888.234.6781
	 	
	 These two classes (db and query) were created to simplify the
	 process of interacting and querying mysql databases in php4.
	 
	 Variables for class "db":
	 ---------------------------------
	  $host = localhost
	  $user = username
	  $pass = password
	  $linkID = link id to connection
	  $dbID = link id to database
	  $tables = list of all tables contained in database
	  $dbs = list of all databases contained in connection
	  
	 Variables for class "query":
	 ---------------------------------
	  $result = link to query
	  $empty_result = the number of rows affected by the result
	  $numrows = number of rows contained in query
	  $numfields = number of fields contained in query
	  $row_data = array of data
	  $row_obj object containing properties of row
	  $field_name = name of designated field
	  $field_type = type of designated field
	  $field_len = length of designated field
	  $field_flags = flags of designated field
	  
	  
	 Example Code:
	 ---------------------------------
	  $db = new db("username", "password");             +++ CONNECT TO MYSQL +++
	  $db->set_db("name of table");                     +++ SET THE CURRENT DATABASE +++
	  $query1 = new query($db, "SELECT * FROM table1"); +++ MAKE QUERY #1 +++
	  $query2 = new query($db, "SELECT * FROM table2"); +++ MAKE QUERY #2 +++
	  echo $query1->numrows."<br>";                     +++ PRINT THE NUMBER OF ROWS IN QUERY #1 +++
	  echo $query1->numfields."<br>";                   +++ PRINT THE NUMBER OF COLUMNS IN QUERY #1 +++
	  $query1->fetch_row();                             +++ FETCH DATA FROM ROW AS ARRAY+++
	  $query1->fetch_array();                           +++ FETCH DATA FROM ROW AS ASSOC. ARRAY +++
	  $query1->fetch_object();                          +++ FETCH DATA FROM ROW AS OBJECT +++
	  echo $query1->row_data[0]."<br>";                 +++ PRINT FIRST ELEMENT OF ROW ARRAY +++
	  echo $query1->$row_obj->id."<br>";                +++ PRINT VALUE OF "ID" FROM ROW OBJECT +++
	  $query1->field_info(0);                           +++ GET INFO FOR FIRST FIELD OF QUERY +++
	  echo $query1->field_name."<br>";                  +++ PRINT NAME OF FIELD FROM "field_info" FUNCTION +++
	  $query1->close();                                 +++ FREE MEMORY ASSOCIATED WITH QUERY #1 +++
	  $db->close();                                     +++ CLOSE CONNECTION TO DATABASE;
	
	*****************************************************************/

	class db
	{
		/*////////////// DEFINE THE VARIABLES THAT ARE USED WITHIN THE CLASS //////////////*/
		var $host = "localhost";
		var $user;
		var $pass;
		var $linkID;
		var $dbID;
		var $numrows;
		var $numfields;
		var $tables;
		var $dbs;
		var $db;
		
		/*////////////// DEFINE CONSTRUCTOR FUNCTION //////////////*/
		function db($host,$user,$pass) //milsoft
		{
			$this->$host = $this->set_host($host);
			$this->$user = $this->set_user($user);
			$this->$pass = $this->set_pass($pass);
			$this->connect();
		}
		
		/*////////////// DEFINE METHOD FUNCTIONS //////////////*/
		function set_host($value)
		{
			return $this->host = $value;
		}

		function set_user($value)
		{
			return $this->user = $value;
		}
		
		function set_pass($value)
		{
			return $this->pass = $value;
		}
		
		function get_user()
		{
			return $this->user;
		}
		
		function connect()
		{
			if (!$this->linkID = @mysql_connect($this->host,$this->user,$this->pass))
			{
				$this->printError(mysql_errno($this->linkID), mysql_error($this->linkID));		
			}
		}
		
		function close()
		{
			if (empty($this->linkID))
			{
				$this->printError("NA","There are no current mysql connections to close.");
			}
			mysql_close($this->linkID);
		}
		
		function list_dbs()
		{
			if (!$this->dbs = mysql_list_dbs($this->linkID))
			{
				$this->printError(mysql_errno($this->linkID), mysql_error($this->linkID));
			}
		}
		
		function set_db($value)
		{	
			if (!$this->dbID = mysql_select_db($value,$this->linkID))
			{
				$this->printError(mysql_errno($this->linkID), mysql_error($this->linkID));
			}
			$this->db = $value;
		}
		
		function list_tables()
		{
			if (!$this->tables = mysql_list_tables($this->db))
			{
				$this->printError(mysql_errno($this->linkID), mysql_error($this->linkID));
			}
		}
		
		function last_row($tablename)
		{
			if (!$max_query = mysql_query("SELECT max(id) AS max_row FROM $tablename"))
			{
				$this->printError(mysql_errno($this->linkID), mysql_error($this->linkID));
			}
			$temp = mysql_fetch_row($max_query);
			return ($temp[0]);
			unset($temp);
		}
		
		function printError($errNum, $errText)
		{
			echo(sprintf("Error (%s): %s<p>", $errNum, $errText));
			//exit();
		}
	};


	class query
	{
		/*////////////// DEFINE THE VARIABLES THAT ARE USED WITHIN THE CLASS //////////////*/
		var $result;
		var $affected_rows = 0;
		var $numrows;
		var $numfields;
		var $row_data = array();
		var $row_obj;
		var $field_name;
		var $field_type;
		var $field_len;
		var $field_flags;
		var $last_query = array();
		var $lastError;
		
		/*////////////// DEFINE CONSTRUCTOR FUNCTION //////////////*/
		function query($db,$value)
		{
			if (!isset($db) || empty($db)) $this->killClass();
			else
			{
				$this->last_query["start_time"] = $this->getmicrotime();
				if (!$this->result = mysql_query($value))
				{
					$db->printError(mysql_errno($db->linkID), mysql_error($db->linkID));
				}
				$lastError = mysql_errno($db->linkID);
   				if ( $lastError > 0) {
     				$lastError = $lastError;
     				}
				
				$this->last_query["end_time"] = $this->getmicrotime();
				
				$this->affected_rows = mysql_affected_rows($db->linkID);
				$this->numrows = 0;
				$this->numfields = 0;	
				
				//if (eregi("^SELECT", $value) || eregi("^SHOW", $value)) //milsoft changed to allow show commands as queries
                if (preg_match("/\bSELECT\b/i", $value) || preg_match("/\bSHOW\b/i", $value) ) //milsoft changed to allow show commands as queries
				{
					$this->numrows = @mysql_num_rows($this->result);
					$this->numfields = @mysql_num_fields($this->result);
				}
				$this->last_query["sql"] = $value;
			}
		}
		
		/*////////////// DEFINE METHOD FUNCTIONS //////////////*/
		function fetch_array()
		{
			if ($this->affected_rows <> 0)
			{
				$this->row_data = mysql_fetch_array($this->result);		
			}
		}
		
		function fetch_row()
		{
			if ($this->affected_rows <> 0)
			{
				$this->row_data = mysql_fetch_row($this->result);
			}
		}
		
		function fetch_object()
		{
			if ($this->affected_rows <> 0)
			{
				$this->row_obj = mysql_fetch_object($this->result);
			}
		}
		
		function field_info($id)
		{
			if (empty($this->result)) $this->printError("NA","A query has not been specified.");
			$this->field_name = mysql_field_name($this->result,$id);
			$this->field_type = mysql_field_type($this->result,$id);
			$this->field_len = mysql_field_len($this->result,$id);
			$this->field_flags = mysql_field_flags($this->result,$id);
		}
		
		/* THIS FUNCTION IS FOR TESTING PURPOSES ONLY */
		function query_info()
		{
			echo "<u>Your Previous Query Consisted of:</u><br>";
			echo "SQL = '".$this->last_query["sql"]."'<br>";
			$temp = ($this->last_query["end_time"] - $this->last_query["start_time"]);
			$temp *= 1000;
			$temp = number_format($temp, 3);
			echo "Time Elapsed: ".$temp."(ms)<br>";
			echo "Number of Records: ".$this->numrows."<br>";
			echo "Number of Rows Affected: ".$this->affected_rows;
		}
		
		function print_results()
		{
			if ($this->affected_rows == 0) return;
				
			for ($i = 0; $i < $this->numrows; $i++)
			{
				$this->fetch_row();
				echo "<tr>\n";
				for ($j = 0; $j < $this->numfields; $j++)
				{
					echo "<td>".$this->row_data[$j]."</td>\n";
				}
				echo "</tr>\n";
			}	
		}
		
		function getmicrotime()
		{ 
    		list($usec, $sec) = explode(" ",microtime()); 
    		return ((float)$usec + (float)$sec); 
    	}
		
		function close()
		{
			mysql_free_result($this->result);
		}
		
		function killClass()
		{
			echo "You cannot make a query without first creating a database connection.";
			//exit();
		}
			
	};


?>
	