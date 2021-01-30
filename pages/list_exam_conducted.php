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

	$stmt_student =$conn->prepare("SELECT * FROM enquiry_details ORDER BY enquiry_id DESC ");
	$stmt_student->execute();
	$row_student=$stmt_student->fetchAll(PDO::FETCH_ASSOC);
	
	if(isset($_GET['id']))
	{		
		$id=$_GET['id'];	
		$stmt=$conn->prepare("DELETE FROM exam_details WHERE exam_details_id=$id");	
		$executed=$stmt->execute();
		if($executed)
		//$stmt->execute();
		//if(execute)
		    {

			 //header("Location:enquiry_list");
			  echo "<script>window.location.href='list_exam_conducted.php';</script>";
		    }
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
	<title>List of Exam Conducted</title>
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

		    <div class="col-md-12 col-sm-12 ">
				<div class="x_panel">
					  <div class="x_title">
						<a href="add_exam_conducted.php" class="btn btn-primary submit" >Add Exam</a>
						<a href="exam_conducted.php" class="btn btn-primary submit" >Exam list with Marks</a>
						<ul class="nav navbar-right panel_toolbox">
						  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
						  </li>
						  <li><a class="close-link"><i class="fa fa-close"></i></a>
						  </li>
						</ul>
						<div class="clearfix"></div>
					  </div>
					<div class="x_content">
						<div class="row">
							<div class="col-sm-12">
								<div class="card-box table-responsive">
									    <table id="datatable-buttons" class="table table-bordered table-striped jambo_table bulk_action" cellspacing="0" width="100%">
									  <thead>
										<tr>
										    <th>Sr. No.</th>
											<th>Exam Date</th>
											<th>Exam Time</th>
											<th>Course Name</th>
											<th>Batch Timing</th>
											<th>Subject</th>
											<th>Total Marks</th>
											<th>Faculty</th>
											<th>Action</th>
										</tr>
									  </thead>
								<tbody>
									<?php
									
									$stmt1 =$conn->prepare("SELECT * FROM exam_details ORDER BY exam_details_id DESC ");
									$stmt1->execute();
									$row_exam=$stmt1->fetchAll(PDO::FETCH_ASSOC);
												for($f=0;$f<count($row_exam);$f++)
												{
												    $stmt_marks =$conn->prepare("SELECT * FROM exam_marks WHERE     exam_details_id=".$row_exam[$f]['exam_details_id']);
												    $stmt_marks->execute();
													$row_mark=$stmt_marks->fetchAll(PDO::FETCH_ASSOC);
													if(empty($row_mark)){
												?>
												<tr>
													<td><?php echo $f+1;?></td>
													<td><?php echo $row_exam[$f]['date_of_exam'];?></td>
													<td><?php echo $row_exam[$f]['time_in']."-".$row_exam[$f]['time_out'];?></td>
													<td>
													<?php 
													//echo $row[$i]['course_name']; 
													//get course name using course id
													$stmt_course=$conn->prepare("SELECT * FROM course_details WHERE course_details_id=".$row_exam[$f]['course_details_id']);
													$stmt_course->execute();
													$row_course=$stmt_course->fetchAll(PDO::FETCH_ASSOC);
													echo $row_course[0]['course_name']; 
												?>
													</td>
													<td><?php echo $row_exam[$f]['batch_timing'];?></td>
													<td><?php echo $row_exam[$f]['subject_name'];?></td>
													<td><?php echo $row_exam[$f]['total_marks'];?></td>
													<td><?php echo $row_exam[$f]['faculty_name'];?></td>
													<td>
														<a href="update_exam_conducted?id=<?php echo $row_exam[$f]['exam_details_id'];?>" data-toggle="tooltip" data-placement="Bottom" title="View"><i class="fa fa-eye fa-1x text-info"></i></a>&nbsp;&nbsp;
														<a href="list_exam_conducted?id=<?php echo $row_exam[$f]['exam_details_id'];?>"  data-toggle="tooltip" data-placement="Bottom" title="Delete" onclick="return confirm('Are you sure you want to delete this?');"><i class="fa fa-trash-o fa-1x text-danger"></i></a>
														<a href="exam_details?id=<?php echo $row_exam[$f]['exam_details_id'];?>" data-toggle="tooltip" data-placement="bottom" title="Add"><i class="fa fa-plus-square fa-1x text-primary"></i></a>
													</td>
												</tr>
												<?php
													}
												}
												?>	
								</tbody>
							</table>
								</div>
							</div>
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
  </body>
</html>
