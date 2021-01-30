
<?php
session_start();
require "conn.php";
if(isset($_SESSION['admin_username']))
{
  
}
else
{
	echo "<script>window.location.href='loginfile';</script>";
}



$sid=$_GET['sid'];

			$stmt2 = $conn->prepare("SELECT * FROM `faculty_details` WHERE faculty_id=$sid");
			$stmt2->execute();
			$row2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
			if($row2)
			{
				//print_r($row2);exit;
									  
			}
		
		
		//echo json_encode(array($row2[0]['staff_salary']));
		echo json_encode($row2[0]['faculty_salary']);
		//echo json_encode($row2[0]['emp_type']);
?>