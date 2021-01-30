<?php
session_start();
require "conn.php";
//require_once("sendsms.php");
if(isset($_SESSION['admin_username']))
{
  
}
else
{
	echo "<script>window.location.href='loginfile';</script>";
}
//require_once("sendsms.php");
date_default_timezone_set("Asia/Kolkata");
if(isset($_POST['submit']))
{
	extract($_POST);
	//print_r($_POST);exit;
	//echo date('Y-m-d',strtotime('$attendance_date'));exit;
try{
		$stmt = $conn->prepare("INSERT INTO attendance_entry(course_details_id,faculty_name,batch_timing,attendance_date,subject)VALUES(:course_details_id,:faculty_name,:batch_timing,:attendance_date,:subject_name)");

		$executed=$stmt->execute(array(':course_details_id' => $course_details_id,':faculty_name' => $faculty_name,':batch_timing' => $batch_timing,':attendance_date' => date('Y-m-d',strtotime($attendance_date)),':subject_name' => $subject_name));
			   if($executed)
			   {
					$attendance_entry_id = $conn->lastInsertId();
					//get all students in this batch_timimg
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
							$stmt_checkbox = $conn->prepare("INSERT INTO attendance_details(attendance_entry_id,student_id,present_or_absent)VALUES(:attendance_entry_id,:student_id,:present_or_absent)");

							$executed_checkbox=$stmt_checkbox->execute(array(':attendance_entry_id' => $attendance_entry_id,':student_id' => $row2[$a]['student_id'],':present_or_absent' => 'P'));
						}
						else
						{
							$stmt_checkbox = $conn->prepare("INSERT INTO attendance_details(attendance_entry_id,student_id,present_or_absent)VALUES(:attendance_entry_id,:student_id,:present_or_absent)");

							$executed_checkbox=$stmt_checkbox->execute(array(':attendance_entry_id' => $attendance_entry_id,':student_id' => $row2[$a]['student_id'],':present_or_absent' => 'A'));
							if($executed_checkbox)
							{
								$mob_no=$row2[$a]['student_number'];
								
								//Code written on 06-09-2019 to check if sms sending is truned on then only the SMS will be sent
								$stmt_check_sms_on = $conn->prepare("SELECT * FROM login_details WHERE login_id=1");//1 because we want the first record
								$stmt_check_sms_on->execute();
								$row_check_sms_on = $stmt_check_sms_on->fetchAll(PDO::FETCH_ASSOC);
								if($row_check_sms_on[0]['sms_on_off']=="on")
								{
									//sms code
									//$sendsms = new sendsms("","PARAMT");  //API key, Sender
									//$sendsms->send_sms($mob_no,"Dear Parent, From PARAM TUTORIALS, gentle reminder that your son / daughter was absent today.");
								}
							}
						}
					}
					
					?>
				<script>alert("Added Successfully");
				window.location.href='attendance_list';</script>
				<?php
			   }
			   
			   
	}catch(Exception $e) {
	  echo 'Message: ' .$e->getMessage();
	}
}
//exit;
?>
<!DOCTYPE html>
<html lang="en">
  <head>
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
                <h3>Attendance Entry</h3>
              </div>
            </div>

            <div class="clearfix"></div>

		    <div class="col-md-12 col-sm-12 ">
				<div class="x_panel">
                   <!--<div class="x_title">
						<a href="add_batch" class="btn btn-primary submit" >Add Batch</a>
						<ul class="nav navbar-right panel_toolbox">
						  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
						  </li>
						  <li><a class="close-link"><i class="fa fa-close"></i></a>
						  </li>
						</ul>
						<div class="clearfix"></div>
					  </div>-->
				  	<form class="form-horizontal" action="" method="post" novalidate>
					   <div class="field item form-group col-sm-12 row">
						    <div class="field item form-group col-sm-6 row">
						     	<label class="control-label col-sm-8 col-xs-12" style="color:black;">Course</label>
						     	<div class="col-lg-8 col-md-6">
							     	<select name="course_details_id" id="course_details_id" required="required" class="form-control" onchange="get_batches(this.value);">
										<option>-----Select Course-----</option>
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
						 	</div>
						 	<script>
								function get_batches(course_details_id) 
								{	
									//var textboxno=($("input[name='add[product_id][]']").length);
									$.ajax({
												type: "POST",
												url: "ajax_get_batches.php",
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
								function get_student_list() 
								{	
									var course_details_id=document.getElementById("course_details_id");
									var batch_timimg=document.getElementById("batch_timing");
									$.ajax({
												type: "POST",
												url: "ajax_get_student_list.php",
												data: {course_details_id:course_details_id,batch_timimg:batch_timimg},
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
								}
							</script>
							<div class="field item form-group col-sm-6 row">
							  	<label class="control-label col-sm-9 col-xs-12" style="color:black;">Faculty</label>
							  	<div class="col-lg-8 col-md-6">
							   		<select name="faculty_name" id="test" required="required" class="form-control">
										<option>-----Select Faculty-----</option>
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
						</div>	

						<div class="field item form-group col-sm-12 row">
							<div class="field item form-group col-sm-6 row">
							  	<label class="control-label col-sm-8 col-xs-12" style="color:black;">Batch Timing</label>
							  	<div class="col-lg-8 col-md-6" id="batch_timing_div">
							  		<select name="batch_timing" id="batch_timing" required="required" class="form-control">
										<option>---select timing-----</option>
									</select>
							  	</div>
							</div>
						 	<div class="field item form-group col-sm-6 row">
								<label class="control-label col-sm-8 col-xs-12" style="color:black;">Date</label>
								<div class="col-lg-8 col-md-6">
									<input type="date" name="attendance_date" required="required" class="form-control" id="date_of_attendance" placeholder="Ex : 01-01-2001">
								</div>
						   	</div>
						</div>
                        <div class="field item form-group col-sm-12 row">
                           <div class="field item form-group col-sm-6 row">
							<label for="focusedinput" class="control-label col-sm-8 col-xs-12" style="color:black;">Subject Name</label>
							<div class="col-lg-8 col-sm-8">
							 <input type="text" class="form-control"  name="subject_name" placeholder="Enter Subject Name" required="required" onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)">
							</div>
						  </div>									 
	                    </div>
					  
						<div class="x_content">
							<div class="row">
								<div class="col-sm-12">
									<div class="card-box table-responsive" data-example-id="contextual-table" id="student_list_div"></br>
										<p style="word-spacing: 5px;color: #000;">Note : If  Present  then check <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;  else  uncheck <i class="fa fa-square-o"></i></p>
									 	<table id="datatable-buttons" class="table table-bordered table-striped jambo_table bulk_action" cellspacing="0" width="100%">
											<thead>
												<tr class="warning">
													<th>Sr. No.</th>
													<th>Student Name</th>
													<th>Present/Absent</th>
												</tr>
											</thead>
											<tbody>
											
											
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
						<div class="panel-footer">
							<!--<div class="col-sm-8 col-sm-offset-3">-->
							<div class="col-md-6 col-sm-6 offset-md-3">
								<button class="btn btn-success" type="submit" name="submit">Submit</button>
								<button class="btn-danger btn" type="submit">Cancel</button>
							</div>
						</div>
	            	</form>
            	</div>
                       			
         	</div>
		 </div>
        <!-- /page content -->
      </div>
    </div>
	<!-- footer content -->
         <?php require "footer.php";?>
        <!-- /footer content -->
   <?php require "foot_link.php";?>	
   
   

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

  <!-- jQuery -->
  <script src="../vendors/jquery/dist/jquery.min.js"></script>
  <!-- Bootstrap -->
  <script src="../vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <!-- FastClick -->
  <script src="../vendors/fastclick/lib/fastclick.js"></script>
  <!-- NProgress -->
  <script src="../vendors/nprogress/nprogress.js"></script>
  <!-- validator -->
  <!-- <script src="../vendors/validator/validator.js"></script> -->

  <!-- Custom Theme Scripts -->
  <script src="../build/js/custom.min.js"></script>

   
  </body>
</html>
