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
		if($executed_checkbox)
	   {
		echo "<script>alert('Marks Updated Successfully');window.location.href='exam_conducted';</script>";
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
  <title>Marks List</title>
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
      
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Exam Details</h3>
              </div>
            </div>
           <div class="right_col" role="main">
            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12 col-sm-12 ">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Marks List</h2>
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
                    <form action="" method="post" id="demo-form2" data-parsley-validate class="form-horizontal">
                        <div class="grid_3 grid_5">
							<div class="form-group row">
							   <?php 
										  $stmt_course1 =$conn->prepare("SELECT * FROM course_details WHERE course_details_id=".$row_exam[0]['course_details_id']."");
										  $stmt_course1->execute();
										  $row_course1=$stmt_course1->fetchAll(PDO::FETCH_ASSOC);
									?>
								<label class="col-sm-1 control-label">Course Name<span class="required">*</span></label>
								<div class="col-sm-3">
								<?php
								 $stmt_course =$conn->prepare("SELECT * FROM course_details");
								 $stmt_course->execute();
								 $row_course=$stmt_course->fetchAll(PDO::FETCH_ASSOC);
								 for($c=0;$c<count($row_course);$c++)
								?>
								<input type="text" name="course_name" required="required" class="form-control"  value="<?php echo $row_course1[0]['course_name'];?>" readonly>
								
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
								<div class="col-sm-3">
										<input type="text" name="faculty_name" class="form-control"  placeholder="" value="<?php echo $row_exam[0]['faculty_name'];?>" readonly>
							   </div>
						 </div>
				  
						 <div class="form-group row">
							<label for="selector1" class="col-sm-1 control-label">Batch Timing<span class="required">*</span></label>
							<div class="col-sm-3" id="batch_timing_div">
									<input type="text" name="batch_timing" class="form-control"  placeholder="" value="<?php echo $row_exam[0]['batch_timing'];?>" readonly>
						    </div>
						  
						    <label class="col-sm-1 control-label" for="selector1">Date<span class="required">*</span></label>
						    <div class="col-sm-3">
							  <input type="text" name="date_of_exam" required="required" class="form-control col-sm-6" placeholder="Ex : 01-01-2001" id="date_of_attendance" value="<?php echo $row_exam[0]['date_of_exam'];?>" readonly>
						    </div>
						    <label for="selector1" class="col-sm-1 control-label">Time <span class="required">*</span></label>
					        <div class="col-sm-1">
							    <input type="text" name="time_in" required="required" class="form-control col-sm-8"  placeholder="In" value="<?php echo $row_exam[0]['time_in'];?>" readonly>
							    </div>
								<div class="col-sm-1">
								<input type="text" name="time_out" required="required" class="form-control col-sm-8"  placeholder="Out" value="<?php echo $row_exam[0]['time_out'];?>" readonly>
							</div>
						</div>
						

						<div class="form-group row">
						  <label class="col-sm-1 control-label" for="subject_name">Subject Name<span class="required">*</span>
						  </label>
						  <div class="col-sm-3">
							<input type="text" class="form-control"  name="subject_name" placeholder="Enter Subject Name" value="<?php echo $row_exam[0]['subject_name'];?>" readonly>
						  </div>
						
							  <label class="col-sm-1 control-label" for="total_marks">Total Marks<span class="required">*</span>
							  </label>
							  <div class="col-sm-3">
								<input type="text" class="form-control"  name="total_marks" placeholder="Enter Total Marks" value="<?php echo $row_exam[0]['total_marks'];?>" readonly>
							  </div>
							</div>
					  <div class="ln_solid"></div>	
                      
		    <div class="col-md-12 col-sm-12 ">
				<div class="x_panel">
					  <div class="x_title">
                      </div>
					<div class="x_content">
						<div class="row">
							<div class="col-sm-12">
								<div class="card-box table-responsive">
								
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
																	<input type="text" class="form-control" name="student_marks[]" value="<?php echo $row_exam_marks[$a]['student_marks'];?>">
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

										?>
							
								</div>
							</div>
						</div>
					</div>
                </div>
            </div>

					   
				<div class="panel-footer">									
							<!--<div class="col-sm-8 col-sm-offset-3">-->
							<div class="col-md-6 col-sm-6 offset-md-3">
							<button class="btn btn-success" type="submit" name="update">Update</button>
							<button class="btn-danger btn" type="reset">Cancel</button>
							</div>
				</div>
                    </form>
					<!--form end -->
                
                </div>
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
