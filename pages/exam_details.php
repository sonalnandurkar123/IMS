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
date_default_timezone_set("Asia/Kolkata");
$exam_details_id=$_GET['id'];
if(isset($_POST['submit']))
{
	//print_r($_POST);exit;
	extract($_POST);
	$stmt2 = $conn->prepare("SELECT * FROM student_details WHERE course_details_id='$course_details_id' AND batch_time='$batch_timing'");
	$stmt2->execute();
	$row2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

	//get present students list from checkbox
	//$present_students_array=array_keys($checkbox_present);
	//print_r($present_students_array);exit;
	for($a=0;$a<count($row2);$a++)
	{
		//check if student is present
		if(in_array($row2[$a]['student_id'],$checkbox_present))
		{
			if($student_marks[$a]=="")
			{
				$student_marks[$a]=0;
			}
			$stmt_checkbox = $conn->prepare("INSERT INTO exam_marks(exam_details_id,student_id,present_or_absent,student_marks)VALUES(:exam_details_id,:student_id,:present_or_absent,:student_marks)");

			$executed_checkbox=$stmt_checkbox->execute(array(':exam_details_id' => $exam_details_id,':student_id' => $row2[$a]['student_id'],':present_or_absent' => 'P',':student_marks' => $student_marks[$a]));
			
			//get details using exam_details_id
			$stmt_exam =$conn->prepare("SELECT * FROM exam_details WHERE exam_details_id=$exam_details_id");
			$stmt_exam->execute();
			$row_exam=$stmt_exam->fetchAll(PDO::FETCH_ASSOC);
			
			//$mob_no=$row2[$a]['student_number'];
			//$class="PARAM Tutorials";
			//$r_no=$row2[$a]['student_id'];
			//$name=$row2[$a]['student_name'];
			//$exam=$row_exam[0]['subject_name'];
			//$marks_got=$student_marks[$a];
			//$out_of=$row_exam[0]['total_marks'];
			//$date_of_exam=$row_exam[0]['date_of_exam'];
								
			//Code written on 06-09-2019 to check if sms sending is truned on then only the SMS will be sent
			//$stmt_check_sms_on = $conn->prepare("SELECT * FROM login_details WHERE login_id=1");//1 because we want the first record
			//$stmt_check_sms_on->execute();
			//$row_check_sms_on = $stmt_check_sms_on->fetchAll(PDO::FETCH_ASSOC);
			//if($row_check_sms_on[0]['sms_on_off']=="on")
			//{
				
				//$sendsms = new sendsms("158a876i3v5ej39q3g5892kz2kj1f80mo0x","PARAMT");  //API key, Sender
				//$sendsms->send_sms($mob_no,"PARAM TUTORIALS: Roll number:$r_no Name of student:$name Exam conducted:$exam Mark Obtained:$marks_got/$out_of Exam Date:$date_of_exam");
				//exit;
			//}
		}
		else
		{
			$stmt_checkbox = $conn->prepare("INSERT INTO exam_marks(exam_details_id,student_id,present_or_absent,student_marks)VALUES(:exam_details_id,:student_id,:present_or_absent,:student_marks)");

			$executed_checkbox=$stmt_checkbox->execute(array(':exam_details_id' => $exam_details_id,':student_id' => $row2[$a]['student_id'],':present_or_absent' => 'A',':student_marks' => 0));
		}
		if($executed_checkbox)
		{
			echo "<script>alert('Marks Added Successfully!!');window.location.href='list_exam_conducted';</script>";
		}
	}
}
if(isset($_POST['update']))
{
	extract($_POST);
	$stmt_exam_marks =$conn->prepare("SELECT * FROM exam_marks WHERE exam_details_id=$exam_details_id ORDER BY student_id");
	$stmt_exam_marks->execute();
	$row_exam_marks=$stmt_exam_marks->fetchAll(PDO::FETCH_ASSOC);
	//print_r($_POST);exit;
	//get present students list from checkbox
	//$present_students_array=array_keys($checkbox_present);
	//print_r($present_students_array);exit;
	for($m=0;$m<count($row_exam_marks);$m++)
	{
		//check if student is present
		if(in_array($row_exam_marks[$m]['student_id'],$checkbox_present))
		{
			if($row_exam_marks[$m]['student_marks']=="")
			{
				$row_exam_marks[$m]['student_marks']=0;
			}
			$stmt_checkbox = $conn->prepare("UPDATE exam_marks SET exam_details_id=:exam_details_id,present_or_absent=:present_or_absent,student_marks=:student_marks WHERE exam_marks_id=".$row_exam_marks[$m]['exam_marks_id']);

			$executed_checkbox=$stmt_checkbox->execute(array(':exam_details_id' => $exam_details_id,':present_or_absent' => 'P',':student_marks' => $student_marks[$m]));
		}
		else
		{
			$stmt_checkbox = $conn->prepare("UPDATE exam_marks SET exam_details_id=:exam_details_id,present_or_absent=:present_or_absent,student_marks=:student_marks WHERE exam_marks_id=".$row_exam_marks[$m]['exam_marks_id']);

			$executed_checkbox=$stmt_checkbox->execute(array(':exam_details_id' => $exam_details_id,':present_or_absent' => 'A',':student_marks' => 0));
		}
	}
}
$stmt_exam =$conn->prepare("SELECT * FROM exam_details WHERE exam_details_id=$exam_details_id");
$stmt_exam->execute();
$row_exam=$stmt_exam->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
  <title>Mark List</title>
     <?php require "head_link.php";?>
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <?php require "sidebar.php";?>
        </div>

        <!-- top navigation -->
       <?php require "topbar.php"; ?>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Exam Details</h3>
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12 col-sm-12 ">
                <div class="x_panel">
                  <div class="x_title">
                    <a href="add_exam_conducted" class="btn btn-primary">Add Exam</a>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br/>
					<!--form start -->
                    <form action="" method="post" id="demo-form2" data-parsley-validate class="form-horizontal" novalidate>
                        <div class="grid_3 grid_5">
							<div class="form-group row">
							      <?php 
										//echo $row[$i]['course_name']; 
										//get course name using course id
										$stmt_course=$conn->prepare("SELECT * FROM course_details WHERE course_details_id=".$row_exam[0]['course_details_id']);
										$stmt_course->execute();
										$row_course=$stmt_course->fetchAll(PDO::FETCH_ASSOC);
										//echo $row_course[0]['course_name']; 
									?>
							
								<label class="col-sm-1 control-label">Course Name<span class="required">*</span></label>
								<div class="col-lg-4">
								    <input type="hidden" name="course_details_id" class="form-control"  placeholder="" value="<?php echo $row_course[0]['course_details_id'];?>" readonly>
									<input type="text" name="course_name" class="form-control"  placeholder="" value="<?php echo $row_course[0]['course_name'];?>" readonly>
							   </div>
							
							  
							  <script>
											function get_batches_for_list(course_details_id) 
											{	
												//var textboxno=($("input[name='add[product_id][]']").length);
												$.ajax({
															type: "POST",
															url: "ajax_get_batches_for_list_for_exam.php",
															data: {course_details_id:course_details_id},
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
																$("#batch_timing_div").html(html);
																
															}					
														}
													   });
											}
											/*function get_student_list() 
											{	
												var course_name=document.getElementById("course_name");
												var batch_timimg=document.getElementById("batch_timing");
												$.ajax({
															type: "POST",
															url: "ajax_get_student_list.php",
															data: {course_name:course_name,batch_timimg:batch_timimg},
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
					  
							 
								<label class="col-sm-1 control-label">Faculty<span class="required">*</span></label>
								<div class="col-lg-4">
								<input type="text" name="faculty_name" class="form-control"  placeholder="" value="<?php echo $row_exam[0]['faculty_name'];?>" readonly>
							   </div>
						 </div>
				  
						 <div class="form-group row">
							<label for="selector1" class="col-sm-1 control-label">Batch Timing<span class="required">*</span></label>
							<div class="col-md-4" id="batch_timing_div">
								<input type="text" name="batch_timing" class="form-control"  placeholder="" value="<?php echo $row_exam[0]['batch_timing'];?>" readonly>
						    </div>
						  
						    <label class="col-sm-1 control-label" for="selector1">Date<span class="required">*</span></label>
						    <div class="col-lg-3">
							   <input type="text" name="date_of_exam" class="form-control"  placeholder="" value="<?php echo $row_exam[0]['date_of_exam'];?>" readonly>
						    </div>
						    <label for="selector1" class="col-sm-1 control-label">Time <span class="required">*</span></label>
					        <div class="col-sm-1">
							   <input type="text" name="time_in" class="form-control"  placeholder="" value="<?php echo $row_exam[0]['time_in'];?>" readonly>
							    </div>
								<div class="col-sm-1">
								<input type="text" name="time_out" class="form-control"  placeholder="" value="<?php echo $row_exam[0]['time_out'];?>" readonly>
							</div>
						</div>
						

						<div class="form-group row">
						  <label class="col-sm-1 control-label" for="subject_name">Subject Name<span class="required">*</span>
						  </label>
						  <div class="col-lg-4">
							<input type="text" name="subject_name" class="form-control"  placeholder="" value="<?php echo $row_exam[0]['subject_name'];?>" readonly>
						  </div>
						
							  <label class="col-sm-1 control-label" for="total_marks">Total Marks<span class="required">*</span>
							  </label>
							  <div class="col-lg-4">
								<input type="text" name="total_marks" class="form-control"  placeholder="" value="<?php echo $row_exam[0]['total_marks'];?>" readonly>
							  </div>
							</div>
						<div class="x_content">
						<div class="row">
							<div class="col-sm-12">
								<div class="card-box table-responsive"></br>
								<p style="word-spacing: 5px;color: #000;">Note : If  Present  then check <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;  else  uncheck <i class="fa fa-square-o"></i></p>
									<?php
										//check if the data is already present
										$stmt_exam_marks =$conn->prepare("SELECT * FROM exam_marks WHERE exam_details_id=$exam_details_id");
										$stmt_exam_marks->execute();
										$row_exam_marks=$stmt_exam_marks->fetchAll(PDO::FETCH_ASSOC);
										//print_r($row_exam_marks);exit;
										if($row_exam_marks)
										{
											//if present then we will update data
											?>
											 <table id="datatable-responsive" class="table table-bordered table-striped jambo_table bulk_action" cellspacing="0" width="100%">
												<thead>
													<tr class="warning">
														<th>Sr. No.</th>
														<th>Student Name</th>
														<th>Present/Absent</th>
														<th>Marks</th>
													</tr>
												</thead>
												<tbody>
												<?php
												for($a=0;$a<count($row_exam_marks);$a++)
												{
													$stmt2 = $conn->prepare("SELECT * FROM student_details WHERE student_id=".$row_exam_marks[$a]['student_id']);
													$stmt2->execute();
													$row2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
												?>
													<tr>
														<td><?php echo $a+1;?></td>
														<td><?php echo $row2[0]['student_name'] ?></td>
														<td>
															<label>
																<input name="checkbox_present[]" type="checkbox" value="<?php echo $row_exam_marks[$a]['student_id'];?>" <?php if($row_exam_marks[$a]['present_or_absent']=="P"){echo "checked";} ?>>
															</label>
														</td>
														<td>
															<label>
																<input type="text" class="form-control" name="student_marks[]"  value="<?php echo $row_exam_marks[$a]['student_marks'];?>">
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
										?>
										
																										
	                               <!-- Integer number Validation -->
									<span id="error" style="color: Red; display: none; padding-right:10px; font-size: 40px;">Enter the Marks </span>
										<script type="text/javascript">
											var specialKeys = new Array();
											specialKeys.push(8); //Backspace
											function IsNumeric(e) {
												var keyCode = e.which ? e.which : e.keyCode
												var ret = ((keyCode >= 48 && keyCode <= 57) || specialKeys.indexOf(keyCode) != -1);
												document.getElementById("error").style.display = ret ? "none" : "inline";
												return ret;
											}
										</script>
							
						
											 <table id="datatable-buttons" class="table table-bordered table-striped jambo_table bulk_action" cellspacing="0" width="100%">
												<thead>
													<tr class="warning">
														<th>Sr. No.</th>
														<th>Student Name</th>
														<th>Present/Absent</th>
														<th>Marks</th>
													</tr>
												</thead>
												<tbody>
												<?php
												$stmt2 = $conn->prepare("SELECT * FROM student_details WHERE course_details_id='".$row_exam[0]['course_details_id']."' AND batch_time='".$row_exam[0]['batch_timing']."'");
												$stmt2->execute();
												$row2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
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
														<td>
															<label>
																<input type="text" class="form-control"  name="student_marks[]" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;">
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
									
								</div>
							</div>
						</div>
					</div>
					     <div class="ln_solid"></div>
							<div class="form-group">
							  <div class="col-md-6 col-sm-6 offset-md-3">
								<button type="submit" class="btn btn-success" name="submit">Submit</button>
								<button type="submit" class="btn btn-danger">Cancel</button>
							  </div>
							</div>
                    </form>
					<!--form end -->
                
                </div>
              </div>
            </div>
			
          </div>
        </div>
        <!-- /page content -->

      </div>
    </div>
	</div>

        <!-- footer content -->
         <?php require "footer.php";?>
        <!-- /footer content -->
   <?php require "foot_link.php";?>	
  </body>
</html>
