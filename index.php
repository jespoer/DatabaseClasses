<html>
<head>
	<title>IvarDBConnections</title>
</head>
<body>
	hej
	
	<?php
		include_once('connection.php');
		
		$db = new Connection();
		
	/*	echo "1: ".$db->insert_row("user", array("username"), array("jwwww"))."<br>";
		echo "2: ".$db->insert_row(TBL_IDB, array("usname"), array("jwwww"))."<br>";
		echo "3: ".$db->insert_row(TBL_IDB, array("username"), array(null))."<br>";
		echo "4: ".$db->insert_row(array("jwwww"))."<br>";
		echo "5: ".$db->insert_row(array())."<br>";
		echo "6: ".$db->insert_row(TBL_IDB)."<br>";
		echo "7: ".$db->insert_row()."<br>";
		echo "8: ".$db->insert_row(null)."<br>";
		echo "9: ".$db->insert_row(null, null, null)."<br>";
		echo "10: ".$db->insert_row("apa", array(), array())."<br>";
		echo "11: ".$db->insert_row(TBL_IDB, array(), array())."<br>";
		echo "12: ".$db->insert_row(TBL_IDB, array(), array("ooj"))."<br>";
		echo "13: ".$db->insert_row(TBL_IDB, array("username"), array("ha"), "aa")."<br>";
		echo "14: ".$db->insert_row("users",TBL_IDB, array("username"), array(null))."<br>";
		
		*/
		
		//echo "Insert row: ".$db->insert_row(TBL_IDB, array('username', 'password', 'id', 'date'), array('jew', 'apa123', 1, date()))."<br>";
		
		echo "Update 1: ".$db->update_row(TBL_IDB, array(), array(), array(), array())."<br>";
		echo "Update 2: ".$db->update_row(TBL_IDB, array(), array(), array())."<br>";
		echo "Update 3: ".$db->update_row(TBL_IDB, array(), array())."<br>";
		echo "Update 4: ".$db->update_row(TBL_IDB, array())."<br>";
		echo "Update 5: ".$db->update_row(TBL_IDB)."<br>";
		echo "Update 6: ".$db->update_row()."<br>";
		echo "Update 7: ".$db->update_row(null)."<br>";
		echo "Update 8: ".$db->update_row(array())."<br>";
		echo "Update 9: ".$db->update_row(TBL_IDB, array('aa'), array(), array(), array())."<br>";
		echo "Update 10: ".$db->update_row(TBL_IDB, array('username'), array('jew'), array(), array())."<br>";
		echo "Update 11: ".$db->update_row(TBL_IDB, array('username'), array('jew'), array('id'), array())."<br>";
		echo "Update 12: ".$db->update_row(TBL_IDB, array('username'), array('jew'), array('id'), array(3))."<br>";
		echo "Update 13: ".$db->update_row(TBL_IDB, array('username'), array('jew'), array('password'), array('aa'))."<br>";
		echo "Update 14: ".$db->update_row(TBL_IDB, array('username'), array('jew'), array('password'), array('aaa'), array('a'))."<br>";
		
		
		
		echo "aa";
		//echo $db->delete_row(TBL_IDB, array("name"), array("je"));
		
		//echo $db->update_row(TBL_IDB, array("password"), array("apa"), array("id"), array(17));
		
		//echo $db->num_rows(TBL_IDB, "name", array("id"), array(1335));
		
	//	print_r($db->get_value(TBL_IDB, array("name"), array("date"), array("2014-01-01"), "id", 3));
		
		echo "php";
	?>


</body>
</html>