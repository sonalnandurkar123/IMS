<?php
session_start();
require "conn.php";

$course_details_id=$_POST['course_details_id'];

    $stmt2 = $conn->prepare("SELECT * FROM batch_details WHERE course_details_id='".$course_details_id."'");
	$stmt2->execute();
	$row2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    if($row2)
    {
		?>
		<select name="batch_timing" id="batch_timing" required="required" class="form-control">
			<option>-----Select Timing-----</option>
			<?php
			for($j=0;$j<count($row2);$j++)
			{
			?>
				<option value='<?php echo $row2[$j]['batch_time'];?>'><?php echo $row2[$j]['batch_time'];?></option>
			<?php
			}
			?>
		
												
		</select>
		<?php
    }     
?>
<script>
										/*function get_student_list() 
										{	
										
											var course_name=document.getElementById("course_name").value;
											var batch_timing=document.getElementById("batch_timing").value;
											var date_of_attendance=document.getElementById("date_of_attendance").value;
											
											//alert(course_name);
											//alert(batch_timimg);
											$.ajax({
														type: "POST",
														url: "ajax_get_student_list.php",
														data: {course_name:course_name,batch_timing:batch_timing,date_of_attendance:date_of_attendance},
														cache: false,
														//dataType: 'json',
														success: function(html){
														//alert(html);
														if(html=='0')
														{
															alert("There is some error.");
														}
														else
														{
															$("#student_list_div").html(html);
														}					
													}
												   });
											
										}*/
									</script>