<?php
require "conn.php";
$course_details_id=$_POST['course_details_id'];
$batch_timing=$_POST['batch_timing'];

    $stmt2 = $conn->prepare("SELECT * FROM student_details WHERE course_details_id='$course_details_id' AND batch_time='$batch_timing'");
	$stmt2->execute();
	$row2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
	//echo "SELECT * FROM student_details WHERE course_name='$course_name' AND batch_time='$batch_timing'";
    if($row2)
    {
		?>
		
										<p style="word-spacing: 5px;color: #000;">Note : If  Present  then check <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;  else  uncheck <i class="fa fa-square-o"></i>
										 <table id="datatable-responsive" class="table table-bordered table-striped jambo_table bulk_action" cellspacing="0" width="100%">
											<thead>
												<tr class="warning">
													<th>Sr. No.</th>
													<th>Student Name</th><th>Present/Absent</th>
												</tr>
											</thead>
											<tbody>
											<?php
											for($a=0;$a<count($row2);$a++)
											{
											?>
												<tr>
													<td><?php echo $a+1;?></td>
													<td><?php echo $row2[$a]['student_name'] ?></td>
													<td>
														<label>
															<input name="checkbox_present[]" type="checkbox" value="<?php echo $row2[$a]['student_id'];?>" checked>
														</label>
													</td>
												</tr>
											<?php
	;										}
											?>
											
											</tbody>
										</table>
	<?php
    }     
?>