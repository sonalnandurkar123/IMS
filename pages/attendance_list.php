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
                <h3>Attendance list</h3>
              </div>
            </div>

            <div class="clearfix"></div>

		    <div class="col-md-12 col-sm-12 ">
				<div class="x_panel">
					<div class="x_title">
						<a href="attendance_entry" class="btn btn-primary submit" >Add Attendance</a>
						<ul class="nav navbar-right panel_toolbox">
						  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
						  </li>
						  <li><a class="close-link"><i class="fa fa-close"></i></a>
						  </li>
						</ul>
						<div class="clearfix"></div>
					</div>
					
					
					<div class="x_content">
					 <div class="form-group col-sm-12 row">
					<div class="form-group col-sm-6 row">
                        <label class="control-label col-sm-8 col-xs-12" for="first-name" style="color:black;">Course Name<span class="required">*</span>
                        </label>
                        <div class="col-lg-8 col-md-6">
                        <!--<input type="text" id="first-name" name="course_name" required="required" class="form-control">-->
                        
					     <select name="course_details_id" id="course_details_id" required="required" class="form-control" onchange="get_batches_for_list(this.value);">
												<option value="">-----Select Course----</option>
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
							<div class="form-group col-sm-6 row">
								<label class="control-label col-sm-9 col-xs-12"for="first-name" style="color:black;">Faculty Name<span class="required">*</span>
								</label>
								<div class="col-lg-8 col-md-6">
                        <!--<input type="text" id="first-name" name="course_name" required="required" class="form-control">-->
                                 <select name="faculty_name" required="required" class="form-control">
												<option value="">-----Select Faculty----</option>
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
							  
					               <div class="form-group col-sm-12 row">
									 <div class="form-group col-sm-6 row">
									  <label class="control-label col-sm-8 col-xs-12" style="color:black;">Batch Timing</label>
									  <div class="col-lg-8 col-md-6" id="batch_timing_div">
									   <select name="batch_timing" id="batch_timing" required="required" class="form-control">
												<option value="">-----Select Timing----</option>
												
											</select>
									  </div>
									  </div>
										 <div class="form-group col-sm-6 row">
										<label class="control-label col-sm-8 col-xs-12" style="color:black;">Date</label>
										<div class="col-lg-8 col-md-6">
										  <input type="date" name="attendance_date" required="required" class="form-control" id="date_of_attendance" placeholder="Ex : 01-01-2001">
										 	</div>
									   </div>
								     </div>	
									 
                                 <div class="form-group col-sm-6 row">
											<button class="btn-success btn" onclick="get_student_list();">Search</button>
								</div>
                                <script>
									
									function get_student_list() 
									{
										var course_details_id=document.getElementById("course_details_id").value;
										var batch_timing=document.getElementById("batch_timing").value;
										var date_of_attendance=document.getElementById("date_of_attendance").value;	
										if(course_details_id=="")
										{
											$("#student_list_div").html("Please Enter Course Name");
										}
										else if(batch_timing=="")
										{
											$("#student_list_div").html("Please Enter Batch Timing");
										}
										else if(date_of_attendance=="")
										{
											$("#student_list_div").html("Please Enter Attendance Date");
										}
										else
										{
										//alert(course_name);
										//alert(batch_timimg);
										$.ajax({
												type: "POST",
												url: "ajax_get_batchwise_attendance.php",
												data: {course_details_id:course_details_id,batch_timing:batch_timing,date_of_attendance:date_of_attendance},
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
									}
								</script>

                                         <div class="bs-example4" data-example-id="contextual-table" id="student_list_div">
									
										   <table id="datatable-buttons" class="table table-bordered table-striped jambo_table bulk_action" cellspacing="0" width="100%">
											<thead>
												<tr class="warning">
													<th>Sr. No.</th>
													<th>Student Name</th>
													<th>Course Name</th>
													<th>Batch Timing</th>
													<th>Present</th>
													<!--<th>Action</th>-->
												</tr>
											</thead>
											<tbody>
											
												<tr>
													<!--<td>1</td>
													<td>C++</td>
													<th>Salman Khan</th>
													<th>Morning Batch</th>
													<th><label><input type="checkbox"></label></th>
													<td>
														<a href="" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Update</a>
														<a href="" class="btn btn-primary" onclick="return confirm('Are you sure you want to delete this?');">Delete</a>
													</td>-->
												</tr>
											
											</tbody>
										</table>
										
										<!-- Modal -->
										<div id="myModal" class="modal fade" role="dialog">
										  <div class="modal-dialog">

											<!-- Modal content-->
											<div class="modal-content">
											  <div class="modal-header">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
												<h4 class="modal-title">Attendenance Update</h4>
											  </div>
											  <div class="modal-body">
												<div class="form-group">
													<label for="focusedinput" class="col-sm-3 control-label">Date</label>
													<div class="col-sm-4">
														<input type="text" class="form-control1" placeholder="Fetch Date">
													</div>
												</div>
												<div class="form-group">
													<label for="focusedinput" class="col-sm-3 control-label">Course Name</label>
													<div class="col-sm-4">
														<input type="text" class="form-control1"  placeholder="Fetch Course">
													</div>
												</div>
												<div class="form-group">
													<label for="focusedinput" class="col-sm-3 control-label">Student Name</label>
													<div class="col-sm-4">
														<input type="text" class="form-control1"  placeholder="Fetch Student">
													</div>
												</div>
												<div class="form-group">
													<label for="focusedinput" class="col-sm-3 control-label">Batch Timing</label>
													<div class="col-sm-4">
														<input type="text" class="form-control1"  placeholder="Fetch Batch">
													</div>
												</div>
												<div class="form-group">
													<label for="focusedinput" class="col-sm-3 control-label">Attendenance</label>
													<div class="col-sm-2">
														<label><input type="radio" value="Present"> Present</label>
													</div>
													<div class="col-sm-2">
														<label><input type="radio" value="Absent"> Absent</label>
													</div>
												</div>
											  </div>
											  <div class="modal-footer">
												<button type="button" class="btn btn-primary" data-dismiss="modal">Update</button>
												<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
											  </div>
											</div>

										  </div>
										</div>
										<!-- End modal-->
										
								   </div>									
					
						
					</div>
                </div>
            </div>	
          </div>
        </div>
        <!-- /page content -->

        <!-- footer content -->
         <?php require "footer.php";?>
        <!-- /footer content -->
      </div>
    </div>
   <?php require "foot_link.php";?>	
    <script>
	$(function() {
		$('#date_of_attendance').datepicker();
		$( "#date_of_attendance" ).on( "change", function() {
		  $( "#date_of_attendance" ).datepicker( "option", "dateFormat", "dd-mm-yy" );
		});
	});
  </script>
	
<script src="js/jquery.nicescroll.js"></script>
<script src="js/scripts.js"></script>
<!-- Bootstrap Core JavaScript -->
   <script src="js/bootstrap.min.js"></script>
   
<script type="text/javascript">
	function get_test_list(sid) 
	{
		//alert('in get list');
		var sid2=sid.value;
		//alert(sid.value);
		$.ajax({
			type: "POST",
			url: "ajaxpage.php",
			data: {class_name:sid2},
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
				$("#test").html(html);
			}
									
		}

	   });
	}
	</script>
	 
  </body>
</html>
