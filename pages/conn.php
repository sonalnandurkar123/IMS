   <?php 
	$servername="localhost";
	$dbname="ims_db";
	$username="root";
	$password="";
		   $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		   $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	?>
	
<?php
	//functions list
	
	/*function func_sms_count()
	{
		global $conn;
		$stmt = $conn->prepare("SELECT * FROM login_details WHERE login_id=1");//1 because we want the first record
		$stmt->execute();
		$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$new_sms_count=$row[0]['sms_count']+1;
		
		$stmt_checkbox = $conn->prepare("UPDATE login_details SET sms_count=:sms_count WHERE login_id=1");
		$executed_checkbox=$stmt_checkbox->execute(array(':sms_count' => $new_sms_count));
	}*/
?>