<?php
session_start();
require "conn.php";
if(isset($_SESSION['admin_username']))
{
  
}
else
{
	echo "<script>window.location.href='login.php';</script>";
}

	
$stmt_exam =$conn->prepare("SELECT * FROM exam_details");
$stmt_exam->execute();
$row_exam=$stmt_exam->fetchAll(PDO::FETCH_ASSOC);

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
                <h3>Student Details</h3>
              </div>
            </div>

            <div class="clearfix"></div>

		    <div class="col-md-12 col-sm-12 ">
				<div class="x_panel">
					  <div class="x_title">
						<h2>Student Lists</h2>
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
								  <?php
									$stmt=$conn->prepare("SELECT * FROM batch_details ORDER BY batch_details_id DESC");
									$stmt->execute();
									$row=$stmt->fetchAll(PDO::FETCH_ASSOC);
						           ?>
								 <table id="datatable-buttons" class="table table-bordered table-striped jambo_table bulk_action" cellspacing="0" width="100%">
									  	<thead>
											<tr class="warning">
												<th>Sr. No.</th>
												<th>Course Name</th>
												<th>Batch Timing</th>
												<th>Faculty</th>
												<th>Subject</th>
												<th>Date</th>
												<th>Time</th>
												<th>Total Marks</th>
												
											</tr>
										</thead>
										<tbody>
											
											<?php
											for($f=0;$f<count($row_exam);$f++)
											{
												?>
											<tr>
												<td><?php echo $f+1;?></td>
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
												<td><?php echo $row_exam[$f]['faculty_name'];?></td>
												<td><?php echo $row_exam[$f]['subject_name'];?></td>
												<td><?php echo $row_exam[$f]['date_of_exam'];?></td>
												<td><?php echo $row_exam[$f]['time_in']."-".$row_exam[$f]['time_out'];?></td>
												<td><?php echo $row_exam[$f]['total_marks'];?></td>
												
											</tr>
												<?php
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
