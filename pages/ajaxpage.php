<?php
require_once("conn.php");
$sid=$_POST['class_name'];

    $stmt2 = $conn->prepare("SELECT * FROM subjects WHERE class_name='$sid'");
	$stmt2->execute();
	$row2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    if($row2)
    {
		?>
		<option value="">--Select Subject--</option>
		<?php
        for($j=0;$j<count($row2);$j++)
        {
		?>
			<option value='<?php echo $row2[$j]['subject_name'];?>'><?php echo $row2[$j]['subject_name'];?></option>
		<?php
        }
    }     
?>