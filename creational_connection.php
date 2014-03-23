<?php

/* -- Creational_connection -- Class including easy functions for handling database tables.
*	
* 	@Author: Jesper Westerberg 
*	@Date: 2014-02-04
*	@Version 1.2.1
*
*	Changes 1.2.1 : Added functionality for create_table
*
*/

include('constants.php'); /* include file with constants which defines host, database, tables etc. */

class Creational_connection{ 
	
	private $db_connection;  /* The connection to the database */ 
	
	function __construct(){  
	
		/*Creates new PDO connection to the mysql database */
		try{
			$pdo_connection = $this->db_connection = new PDO('mysql:host='.DB_SERVER.';dbname='.DB_NAME, DB_USER, DB_PASSWORD); 
			
			/* set attribute to use buffered query */
			$pdo_connection->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, 1); 
		
			/* set attribute for errors. We want to display if not commented. Constructor will always display excep regardless this */
			$pdo_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		}catch(PDOException $exception){
			echo 'Connection to database failed due to: ' . $exception->getMessage();	
		}
		
	}

	/* -- CREATE_TABLE -- */
	public function create_table($name, $array_fields){
	
		if($name == null || !is_array($array_fields) || func_num_args() != 2){
			return -1;
		}
		
		$sql = "CREATE TABLE IF NOT EXISTS ".$name."(";
		
		/* loop through the fields and add them to sql statement */
		for($i = 0; $i< count($array_fields); $i++){
			$sql .= $array_fields[$i];
			if($i != count($array_fields)-1){
				$sql .= ", ";
			}
		}
		
		$sql .= ")";
		
		echo $sql;
		
		$this->db_connection->beginTransaction();
		
		try{
			$result = $this->db_connection->exec($sql);
		}catch(PDOException $e){
			
			$this->db_connection->rollBack();
			return -1;
		}
		$this->db_connection->commit();
		return $result;
	}
	
	/* -- DELETE_TABLE -- */
	public function delete_table(){
	
	}
	
	/* -- COPY_TABLE -- */
	public function copy_table(){
	
	}
	
}
	
	