<?php 

/* -- Connection -- Class for communicating with database using PDO and mysql
*	
* 	@Author: Jesper Westerberg 
*	@Date: 2013-09-22
*	@Version 1.2
*
*/

include('constants.php'); /* include file with constants which defines host, database, tables etc. */

class Connection{ 
	
	private $db_connection;  /* The connection to the database */ 
	
	function __construct(){  
	
		/*Creates new PDO connection to the database */
		try{
			$pdo_connection = $this->db_connection = new PDO('mysql:host='.DB_SERVER.';dbname='.DB_NAME, DB_USER, DB_PASSWORD); 
			
			/* set attribute to use buffered query */
			$pdo_connection->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, 1); 
		
			/* set attribute for errors. We want to display if not commented. Constructor will always display excep regardless this */
			$pdo_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		}catch(PDOException $exception){
			echo 'Connection failed due to: ' . $exception->getMessage();	
		}
		
	}  
	/* -- INSERT_ROW -- Insert row in given table. 
	 *	Input Params :
	 *		$table : Name of database table 
	 *		$field_array : Array of fields which shall be assigned.
	 *		$value_array : Array of values corresponding to fields in $field_array.
	 *
	 *		Returns -1 on error and 1 on success.
	 */
	public function insert_row($table, $field_array, $value_array){
		
		/* Check if input is valid */
		if(!is_array($field_array) || !is_array($value_array) || count($field_array) != count($value_array) || func_num_args() > 3){
			return -1;
		} 
		/* Make string out of arrays and add a "," between elements */
		$field_string = implode(', ', $field_array); 
		$value_string = implode("','",$value_array);
		
		$this->db_connection->beginTransaction();
		
		try{ 
			$result = $this->db_connection->exec("INSERT INTO ".$table." (".$field_string.") VALUES ('".$value_string."')");	
		}catch(PDOException $exception){
			/* roll back changes to before beginTransaction */
			$this->db_connection->rollBack();
			/* return error code */
			return -1;
		 }
		 
		$this->db_connection->commit();
		return $result;
	} 
	
	/* -- DELETE_ROW -- Delete row in given table. 
	 *	Input Params :
	 *		$table : Name of database table 
	 *		$field_array : Array of fields which shall be used in the sql "WHERE" statement.
	 *		$value_array : Array of values corresponding to fields in $field_array. 
	 */
	public function delete_row($table, $field_array, $value_array){	

		/* Check if input is valid */
		if(count($field_array) != count($value_array) || !is_array($field_array) || !is_array($value_array) || func_num_args() > 3){
			return -1;
		} 
		
		/* make a string that matches the sql syntax for 'where' statements. */
		$where_statements = $this->combine_query_arrays($field_array, $value_array, "and");
		
		try{
			$result = $this->db_connection->exec("DELETE FROM ".$table." WHERE ".$where_statements); 
		}catch(PDOException $exception){
			/* return error code for, "could not delete row": -11 */
			return -1;
		 }
		return $result;
	}
	
	/* -- UPDATE_ROW -- Update specific row.
	 *	Input Params : 
	 *		$table : Name of database table 
	 *		$update_field_array : Array of fields which shall be used in "WHERE" statement to find row which shall be updated
	 *		$update_value_array : Array of values which correspond to $update_field_array
	 *		$array_fields : Array of fields which shall be updated
	 *		$array_values : Array of values corresponding to $array_fields
	 *
	 *		returns -1 on error and otherwise N where N = 0,1,2,.. which corresponds to rows changed
	 */
	public function update_row($table, $update_field_array, $update_value_array, $array_fields, $array_values){
		
		/* Check valid input */
		if(!is_array($array_fields) || !is_array($array_values) || 
			!is_array($update_field_array) || !is_array($update_value_array) ||
			count($array_fields) != count($array_values) || 
			count($update_field_array) != count($update_value_array) || func_num_args() > 5){
				return -1;
		}
		
		/* create a string that matches the sql syntax for 'where' statements. */
		$where_statements = $this->combine_query_arrays($update_field_array, $update_value_array, " AND ");
		$set_statements = $this->combine_query_arrays($array_fields, $array_values, ",");
		
		$this->db_connection->beginTransaction();
		
		
		try{
			$result = $this->db_connection->exec("UPDATE ".$table." SET ".$set_statements." WHERE ".$where_statements); 
		}catch(PDOException $exception){
		
			/* roll back changes to before beginTransaction */
			$this->db_connection->rollBack();
			/* return error code for this error */
			return -1;
		}
		/* Commit transaction, everything seems to be correct */
		$this->db_connection->commit();
		
		return $result;
	}
	
	/* -- NUM_ROWS -- Get number of rows matching statement. 
	 *	Input Params : 
	 *		$table : Name of database table
	 *		$count_column : column which shall be counted 
	 *		$where_field_array : 
	 *		$where_value_array : 
	 *
	 *		returns -1 on error and otherwise N where N = 0,1,2,.. which corresponds to number of rows matched
	 */
	public function num_rows($table, $count_column, $where_field_array, $where_value_array){
		
		/* check if input is valid */
		if($count_column == NULL || !is_array($where_field_array) || !is_array($where_value_array) || count($where_field_array) != count($where_value_array)){
			return -1;
		}
		
		/* we want to count all rows in table */
		if(count($where_field_array)==0 && count($where_value_array)==0){
			$query = "SELECT COUNT(".$count_column.") FROM ".$table;
		/* we want to count specific rows only */
		}else{
			$where_statements = $this->combine_query_arrays($where_field_array, $where_value_array, "and");
			$query = "SELECT COUNT(".$count_column.") FROM ".$table." WHERE ".$where_statements;
		}
		
		$this->db_connection->beginTransaction();
		
		try{
			$statement = $this->db_connection->prepare($query); 
			$statement->execute(); 
			$num_rows = $statement->fetchColumn(); 
		}catch(PDOException $exception){
			$this->db_connection->rollBack();
			return -1;
		}
		$this->db_connection->commit();
		return $num_rows;
	} 
	
	/* -- GET_VALUE -- returns the requested values from a specific row in a associative array
	 *	Input Params :
	 *		$table : Name of database table
	 *		$array_select_fields :
	 *		$array_where_fields :
	 *		$array_where_values :
	 *		$order_by :
	 *		$limit :
	 *
	 *		returns -1 on error, otherwise the associative array
	 */
	public function get_value($table, $array_select_fields, $array_where_fields, $array_where_values, $order_by, $limit){
	
		/* Check if input is valid */
		if(!is_array($array_where_fields) || !is_array($array_where_values) || !is_array($array_select_fields) || count($array_where_fields) != count($array_where_values) || count($array_select_fields)==0){
			return -1;
		} 
	
		/* if there are multiple fields to select you want to implode the array and and a ',' between 
		elements to achieve the correct syntax for the SQL query */
		$select_field_string = implode(', ', $array_select_fields); 
	
		/* user wants to retrieve all rows and columns in array */
		if($select_field_string[0] == '*'){
		
			/* check if user wants ordered or not */
			if($order_by == NULL || $limit == NULL){
				$query = "SELECT * FROM ".$table;
			}else{
				$query = "SELECT * FROM ".$table." ORDER BY " .$order_by." DESC LIMIT ".$limit;
			}
		
		/* user wants to filter rows */
		}else{
			
			/* create string that matches sql 'where' statement */
			$where_statements = $this->combine_query_arrays($array_where_fields, $array_where_values,"and");
			
			/* check if user wants ordered or not */
			if($order_by == NULL || $limit == NULL){
				$query = "SELECT ".$select_field_string." FROM ".$table." WHERE ".$where_statements;
			}else{
				$query = "SELECT ".$select_field_string." FROM ".$table." WHERE ".$where_statements." ORDER BY ".$order_by." DESC LIMIT ".$limit;
			}		
		}
		
		/* start transaction with database. Could reverse to this point if query fails */
		$this->db_connection->beginTransaction();
		
		try{
			$statement = $this->db_connection->prepare($query); 
			$statement->execute(); 
			$result = $statement->fetchAll(PDO::FETCH_ASSOC); 
		}catch(PDOException $exception){
			$this->db_connection->rollBack();
			return -1;
		}
		$this->db_connection->commit();
		return $result;
	} 
	
	
	/*
	 * PRIVATE FUNCTIONS 
	 */
	
	
	/* -- COMBINE_QUERY_ARRAYS -- function used to combine arrays of fields and values
			to match the query statements */
	private function combine_query_arrays($field_array, $value_array, $combine_string){
		
		/* Not same size. This wont work, return error for wrong input */
		if(count($field_array) != count($value_array)){
			return -1;
		}
		
		/* declare return string as an empty string */
		$combined_string = '';
		
		for($i = 0; $i<count($field_array);$i++){
			$combined_string = $combined_string.$field_array[$i]."='".$value_array[$i]."'";  
			
			/* we dont want to add a and at the end of the string. Check if we have added the last element */
			if((count($field_array)-1) != 0 && (($i+1) != count($field_array))){
				$combined_string = $combined_string." ".$combine_string." ";
			}
		} 
		return $combined_string;
	}
}


?>