<?php
require "conn.php";
date_default_timezone_set("Asia/Kolkata");
$month_no=$_POST['month_no'];
$student_id=$_POST['student_id'];
$course_details_id=$_POST['course_details_id'];
$batch_time=$_POST['batch_time'];

//$attendance_entry_id=$_POST['attendance_entry_id'];
/*$stmt_month =$conn->prepare("SELECT * FROM attendance_details WHERE student_id='$student_id' AND attendance_entry_id=(SELECT attendance_entry_id FROM attendance_entry WHERE attendance_date LIKE '%-$month_no-%' LIMIT 0,1)");*/
/*echo "SELECT * FROM attendance_entry WHERE attendance_date LIKE '%-$month_no-%' AND course_name='$course_name' AND batch_timing='$batch_time'";*/
$stmt_month = $conn->prepare("SELECT * FROM attendance_entry WHERE attendance_date LIKE '%-$month_no-%' AND course_details_id='$course_details_id' AND batch_timing='$batch_time'");
$stmt_month->execute();
$row_month=$stmt_month->fetchAll(PDO::FETCH_ASSOC);

//get student details
$stmt_stud =$conn->prepare("SELECT * FROM student_details WHERE student_id=$student_id");
$stmt_stud->execute();
$row_stud=$stmt_stud->fetchAll(PDO::FETCH_ASSOC);
?>
<table id="datatable-responsive" class="table table-bordered table-striped jambo_table bulk_action" cellspacing="0" width="100%">
	<thead>
		<tr class="warning">
			<th>Sr. No.</th>
			<th>Student Name</th>
			<th>Course Name</th>
			<th>Batch Timing</th>
			<th>Date</th>
			<th>Present</th>
			<!--<th>Action</th>-->
		</tr>
	</thead>
	<tbody>	
	<?php
	for($m=0;$m<count($row_month);$m++)
	{

$stmt_date = $conn->prepare("SELECT * FROM attendance_details WHERE student_id='$student_id' AND attendance_entry_id=".$row_month[$m]['attendance_entry_id']);
$stmt_date->execute();
$row_attend_date=$stmt_date->fetchAll(PDO::FETCH_ASSOC);
		/*$stmt_date =$conn->prepare("SELECT * FROM attendance_entry WHERE attendance_entry_id=".$row_month[$m]['attendance_entry_id']);
		$stmt_date->execute();
		$row_date=$stmt_date->fetchAll(PDO::FETCH_ASSOC);*/
	?>	
		<tr>
			<td><?php echo $m+1;?></td>
			<td><?php echo $row_stud[0]['student_name'];?></td>
			<td>
			<?php 
				$stmt_get_course_details=$conn->prepare("SELECT * FROM course_details WHERE course_details_id=".$row_stud[0]['course_details_id']);
				$stmt_get_course_details->execute();
				$row_get_course_details=$stmt_get_course_details->fetchAll(PDO::FETCH_ASSOC);
				echo $row_get_course_details[0]['course_name'];
			?>
			</td>
			<td><?php echo $row_stud[0]['batch_time'];?></td>
			<td><?php echo date('d-m-Y', strtotime($row_month[$m]['attendance_date']));?></td>
			<td><label>
														<?php
														if(empty($row_attend_date[0]['present_or_absent']))
															{	
																echo "Admitted Later" ;
															}
														else
														{
														?>
															<input name="checkbox_present[]" type="checkbox" <?php if($row_attend_date[0]['present_or_absent']=='P'){echo "checked";} ?>>
														<?php 
														}
														?>
														</label></td>
			<!--<td>
				<a href="" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Update</a>
				<a href="" class="btn btn-primary" onclick="return confirm('Are you sure you want to delete this?');">Delete</a>
			</td>-->
		</tr>	
	<?php
	}
	?>
	</tbody>
</table>