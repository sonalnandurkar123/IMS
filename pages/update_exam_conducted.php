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
	extract($_POST);
try{
	    
		$stmt = $conn->prepare("UPDATE exam_details SET faculty_name=:faculty_name,subject_name=:subject_name,date_of_exam=:date_of_exam,time_in=:time_in,time_out=:time_out,total_marks=:total_marks WHERE exam_details_id=$exam_details_id");
		$executed=$stmt->execute(array(':faculty_name' => $faculty_name,':subject_name' => $subject_name,':date_of_exam' => $date_of_exam,':time_in' => $time_in,':time_out' => $time_out,':total_marks' => $total_marks));
			   if($executed)
			   {
				?>
				<script>alert("Updated Successfully");</script>
				<?php
				echo "<script>window.location.href='list_exam_conducted';</script>";
			   
			   }
	}catch(Exception $e) {
	  echo 'Message: ' .$e->getMessage();
	}
}
$stmt_exam =$conn->prepare("SELECT * FROM exam_details WHERE exam_details_id=$exam_details_id");
$stmt_exam->execute();
$row_exam=$stmt_exam->fetchAll(PDO::FETCH_ASSOC);//exit;



?>
<!DOCTYPE html>
<html lang="en">
  <head>
  <title>Update Exam</title>
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
                    <h2>Mark List</h2>
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
							<div class="field item form-group row">
							   <?php 
										  $stmt_course1 =$conn->prepare("SELECT * FROM course_details WHERE course_details_id=".$row_exam[0]['course_details_id']."");
										  $stmt_course1->execute();
										  $row_course1=$stmt_course1->fetchAll(PDO::FETCH_ASSOC);
									?>
								<label class="col-sm-1 control-label">Course Name<span class="required">*</span></label>
								<div class="col-lg-4">
						
								
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
								<div class="col-lg-4">
								<select name="faculty_name"  required="required" class="form-control">
								<option value="">-----Select Faculty-----</option>
								<?php
								$stmt2 = $conn->prepare("SELECT * FROM faculty_details WHERE emp_type='Faculty'");
								$stmt2->execute();
								$row2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
								for($f=0;$f<count($row2);$f++)
								{
								?>
								<option value="<?php echo $row2[$f]['faculty_name'];?>" <?php if($row_exam[0]['faculty_name']==$row2[$f]['faculty_name'])echo "selected";?>><?php echo $row2[$f]['faculty_name'];?></option>
								<?php
								}
								?>
								 </select>
							   </div>
						 </div>
				  
						 <div class="field item form-group row">
							<label for="selector1" class="col-sm-1 control-label">Batch Timing<span class="required">*</span></label>
							<div class="col-lg-4" id="batch_timing_div">
								<select name="batch_timing" id="batch_timing" class="form-control" value="<?php echo $row_exam[0]['batch_time'];?>">
											
							                     <?php
        											$stmt=$conn->prepare("SELECT * FROM batch_details WHERE course_details_id=".$row_exam[0]['course_details_id']."");
        											$stmt->execute();
        											$row=$stmt->fetchAll(PDO::FETCH_ASSOC);
        											for($i=0;$i<count($row);$i++)
        											{
        												$oid=$row[$i]['batch_time '];
        										?>
        										<option value="<?php echo $row[$i]['batch_time'];?>"><?php echo $row[$i]['batch_time'];?></option>
        										<?php
        											}
        										?>
									
											</select>
						    </div>
						  
						    <label class="col-sm-1 control-label" for="selector1">Date<span class="required">*</span></label>
						    <div class="col-sm-3">
							  <input type="date" name="date_of_exam" required="required" class="form-control" placeholder="Ex : 01-01-2001" id="date_of_attendance" value="<?php echo $row_exam[0]['date_of_exam'];?>">
						    </div>
						    <label for="selector1" class="col-sm-1 control-label">Time <span class="required">*</span></label>
					        <div class="col-sm-1">
							    <input type="text" name="time_in" required="required" class="form-control"  placeholder="In" value="<?php echo $row_exam[0]['time_in'];?>" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;">
							    </div>
								<div class="col-sm-1">
								<input type="text" name="time_out" required="required" class="form-control"  placeholder="Out" value="<?php echo $row_exam[0]['time_out'];?>" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;">
							</div>
						</div>
						

						<div class="field item form-group row">
							<label class="col-sm-1 control-label" for="subject_name">Subject Name<span class="required">*</span></label>
							<div class="col-md-4">
								<input type="text" class="form-control" required="required"  name="subject_name" placeholder="Enter Subject Name" value="<?php echo $row_exam[0]['subject_name'];?>" onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)">
							</div>
							
							<label class="col-sm-1 control-label" for="total_marks">Total Marks<span class="required">*</span></label>
							<div class="col-md-4">
							<input type="text" class="form-control" required="required" name="total_marks" placeholder="Enter Total Marks" value="<?php echo $row_exam[0]['total_marks'];?>" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;">
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
   <!-- Integer number validation -->
						 <span id="error" ></span>
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
  </body>
</html>
