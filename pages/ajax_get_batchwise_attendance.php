<?php
require "conn.php";
$course_details_id=$_POST['course_details_id'];
$batch_timing=$_POST['batch_timing'];
$date_of_attendance=date('Y-m-d',strtotime($_POST['date_of_attendance']));

    $stmt2 = $conn->prepare("SELECT * FROM student_details WHERE course_details_id='$course_details_id' AND batch_time='$batch_timing'");
	$stmt2->execute();
	$row2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
	
	$stmt_attend = $conn->prepare("SELECT * FROM attendance_entry WHERE attendance_date='$date_of_attendance' AND course_details_id='$course_details_id' AND batch_timing='$batch_timing'");
	$stmt_attend->execute();
	$row_attend = $stmt_attend->fetchAll(PDO::FETCH_ASSOC);
	//echo "SELECT * FROM attendance_entry WHERE attendance_date='$date_of_attendance' AND course_details_id='$course_details_id' AND batch_timing='$batch_timing'";
    if($row2 && $row_attend)
    {
		?>
		
		<p style="word-spacing: 5px;color: #000;">Note : If  Present  then check <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;  else  uncheck <i class="fa fa-square-o"></i>
		<table id="datatable-buttons" class="table table-bordered table-striped jambo_table bulk_action" cellspacing="0" width="100%">
			<thead>
				<tr class="warning">
					<th>Sr. No.</th>
					<th>Student Name</th>
					<th>Course Name</th>
					<th>Batch Timing</th>
					<th>Present / Absent</th>
				</tr>
			</thead>
			<tbody>
			<?php
			for($a=0;$a<count($row2);$a++)
			{
				$stmt_attend_details = $conn->prepare("SELECT * FROM attendance_details WHERE attendance_entry_id=".$row_attend[0]['attendance_entry_id']." AND student_id=".$row2[$a]['student_id']);
				$stmt_attend_details->execute();
				$row_attend_details = $stmt_attend_details->fetchAll(PDO::FETCH_ASSOC);
				
			?>
				<tr>
					<td><?php echo $a+1;?></td>
					<td><?php echo $row2[$a]['student_name'] ?></td>
					<td>
					<?php 
						$stmt_get_course_details=$conn->prepare("SELECT * FROM course_details WHERE course_details_id=".$row2[$a]['course_details_id']);
						$stmt_get_course_details->execute();
						$row_get_course_details=$stmt_get_course_details->fetchAll(PDO::FETCH_ASSOC);
						echo $row_get_course_details[0]['course_name'];
						?>
					</td>
					<td><?php echo $row2[$a]['batch_time'] ?></td>
					<td>
						<label>
						<?php
						if(empty($row_attend_details[0]['present_or_absent']))
							{	
								echo "Admitted Later" ;
							}
						else
						{
						?>
							<input name="checkbox_present[]" class="flat" type="checkbox" <?php if($row_attend_details[0]['present_or_absent']=='P'){echo "checked";} ?> >
							<?php 
							}
							?>
						</label>
					</td>
				</tr>
			<?php
				
			}
			?>
			
			</tbody>
		</table>
	<?php
    }
	else
	{
			echo '<script>alert("No Data found")</script>'; 
		
	}  
?>