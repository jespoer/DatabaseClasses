<?php

/* -- Creational_connection -- Class including easy functions for handling database tables.
*	
* 	@Author: Jesper Westerberg 
*	@Date: 2014-02-04
*	@Version 1.2
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
			echo 'Connection failed due to: ' . $exception->getMessage();	
		}
		
	}

	/* -- CREATE_TABLE -- */
	public function create_table($name){
		
	
	}
	
	/* -- DELETE_TABLE -- */
	public function delete_table(){
	
	}
	
	/* -- COPY_TABLE -- */
	public function copy_table(){
	
	}
	
	