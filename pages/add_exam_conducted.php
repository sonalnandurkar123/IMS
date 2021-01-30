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
if(isset($_POST['submit']))
{
	extract($_POST);
try{
	
		$stmt = $conn->prepare("INSERT INTO exam_details(course_details_id,batch_timing,faculty_name,subject_name,date_of_exam,time_in,time_out,total_marks)VALUES(:course_details_id,:batch_timing,:faculty_name,:subject_name,:date_of_exam,:time_in,:time_out,:total_marks)");

		$executed=$stmt->execute(array(':course_details_id' => $course_details_id,':batch_timing' => $batch_timing,':faculty_name' => $faculty_name,':subject_name' => $subject_name,':date_of_exam' => $date_of_exam,':time_in' => $time_in,':time_out' => $time_out,':total_marks' => $total_marks));
			   if($executed)
			   {
				   $last_inserted_id = $conn->lastInsertId();
				?>
				<script>alert("Added Successfully"); window.location.href="add_exam_conducted.php";</script>
				<?php
				echo "<script>window.location.href='exam_details?id=".$last_inserted_id."';</script>";
			   }
	}catch(Exception $e) {
	  echo 'Message: ' .$e->getMessage();
	}
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
  <title>Add Exam</title>
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
                    <h2>Add Exam Conducted</h2>
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
								<label class="col-sm-1 control-label">Course Name<span class="required">*</span></label>
								<div class="col-md-4">
								<select name="course_details_id"  required="required" class="form-control" onchange="get_batches_for_list(this.value);">
								<option value="">---Select Course---</option>
								<?php
								 $stmt_course =$conn->prepare("SELECT * FROM course_details");
								 $stmt_course->execute();
								 $row_course=$stmt_course->fetchAll(PDO::FETCH_ASSOC);
								 for($c=0;$c<count($row_course);$c++)
								 {
								?>
								<option value="<?php echo $row_course[$c]['course_details_id'];?>"><?php echo $row_course[$c]['course_name']?></option>
								<?php
								}
								?>
								 </select>
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
								<div class="col-md-4">
								<select name="faculty_name"  required="required" class="form-control">
								<option value="">-----Select Faculty-----</option>
								<?php
								$stmt2 = $conn->prepare("SELECT * FROM faculty_details WHERE emp_type='Faculty'");
								$stmt2->execute();
								$row2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
								for($f=0;$f<count($row2);$f++)
								{
								?>
								<option value="<?php echo $row2[$f]['faculty_name'];?>"><?php echo $row2[$f]['faculty_name'];?></option>
								<?php
								}
								?>
								 </select>
							   </div>
						 </div>
				  
						 <div class="field item form-group row">
							<label for="selector1" class="col-sm-1 control-label">Batch Timing<span class="required">*</span></label>
							<div class="col-md-4" id="batch_timing_div">
								<select name="batch_timing" id="batch_timing" required="required" class="form-control">
								<option value="">-----Select Timing-----</option>
								</select>
						    </div>
						  
						    <label class="col-sm-1 control-label" for="selector1">Date<span class="required">*</span></label>
						    <div class="col-lg-3">
							   <input type="date" id="focusedinput" name="date_of_exam" required="required" class="form-control">
						    </div>
						    <label for="selector1" class="col-md-1 control-label">Time <span class="required">*</span></label>
					          <div class="col-sm-1">
							    <input type="text" name="time_in" required="required" class="form-control"  placeholder="In" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;">
							    </div>
								<div class="col-md-1">
								<input type="text" name="time_out" required="required" class="form-control"  placeholder="Out" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;">
							</div>
						</div>
						

						<div class="field item form-group row">
						  <label class="col-sm-1 control-label" for="subject_name">Subject Name<span class="required">*</span>
						  </label>
						  <div class="col-lg-4">
							<input type="text" name="subject_name" id="focusedinput" required="required" placeholder="Enter Subject Name" class="form-control" onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)">
						  </div>
						
							  <label class="col-sm-1 control-label" for="total_marks">Total Marks<span class="required">*</span>
							  </label>
							  <div class="col-sm-4">
								<input type="text" id="focusedinput" placeholder="Enter Total Marks" name="total_marks" required="required" class="form-control" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;">
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
       </div>
     <!-- /page content -->
  </div>
    </div>
		<!-- Validation code  -->

					  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
					  <script src="../vendors/validator/multifield.js"></script>
					  <script src="../vendors/validator/validator.js"></script>

					  <script>
						// initialize a validator instance from the "FormValidator" constructor.
						// A "<form>" element is optionally passed as an argument, but is not a must
						var validator = new FormValidator({ "events": ['blur', 'input', 'change'] }, document.forms[0]);
						// on form "submit" event
						document.forms[0].onsubmit = function (e) {
						  var submit = true,
							validatorResult = validator.checkAll(this);
						  console.log(validatorResult);
						  return !!validatorResult.valid;
						};
						// on form "reset" event
						document.forms[0].onreset = function (e) {
						  validator.reset();
						};
						// stuff related ONLY for this demo page:
						$('.toggleValidationTooltips').change(function () {
						  validator.settings.alerts = !this.checked;
						  if (this.checked)
							$('form .alert').remove();
						}).prop('checked', false);
					  </script>
					 
		<!-- End Validation code  -->	
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
