<html>
<head>
	<title>DatabaseClasses</title>
</head>
<body>
	hej
	
	<?php
		include_once('creational_connection.php');
		
		$db = new Creational_connection();
		
		/*
		*
		* used for testing database class
		*
		*/
		
		echo $db->create_table('customers', array('name VARCHAR(128)', 'id INT(16) NOT NULL', 'address VARCHAR(128)', 'area VARCHAR(128)', 'type VARCHAR(128)', 'square_meter VARCHAR(128)', 'phone VARCHAR(64)', 'email VARCHAR(128) NOT NULL', 'session_id VARCHAR(64)', 'session_address VARBINARY(16)', 'offer_level INT(8) NOT NULL', 'UNIQUE(id)'));
	?>


</body>
</html>