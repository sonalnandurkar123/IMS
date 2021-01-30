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


if(isset($_GET['del_id']))
	{		$del_id=$_GET['del_id'];	
			$stmt=$conn->prepare("DELETE FROM faculty_details WHERE faculty_id=$del_id");	
			$executed=$stmt->execute();
			//$stmt_salary=$conn->prepare("DELETE FROM staff_salary WHERE faculty_id=$del_id");	
			//$executed=$stmt_salary->execute();
		    //$stmt_transaction=$conn->prepare("DELETE FROM daily_transaction WHERE student_id=$del_id");	
			//$executed=$stmt_transaction->execute();
				if($executed)
                      {
						 header("Location:faculty_list");
					  }
	}

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
                <h3>Employee Details</h3>
              </div>
            </div>

            <div class="clearfix"></div>

		    <div class="col-md-12 col-sm-12 ">
				<div class="x_panel">
					  <div class="x_title">
						<a href="add_faculty" class="btn btn-primary submit" >Add Employee</a>
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
										$stmt=$conn->prepare("SELECT * FROM faculty_details");
										$stmt->execute();
										$row=$stmt->fetchAll(PDO::FETCH_ASSOC);
									?>
									 <table id="datatable-buttons" class="table table-bordered table-striped jambo_table bulk_action" cellspacing="0" width="100%">
									  <thead>
										<tr>
										  <th>Sr.No.</th>
										  <th>Name</th>
										  <th>Age</th>
										  <th>Mobile</th>
										  <th>Gender</th>
										  <th>Employee Type</th>
										  <th>Designation</th>
										  <th>Joining Date</th>
										  <th>Salary</th>
										  <th>Address</th>
										   <th>Action</th>
										</tr>
									  </thead>
									  <tbody>
											<?php
												for($i=0;$i<count($row);$i++)
												{
											?>
												<tr>
												
													<td><?php echo $i+1;?></td>
													<td><?php echo $row[$i]['faculty_name']; ?></td>
													<td><?php echo $row[$i]['faculty_age']; ?></td>
                                                    <td><?php echo $row[$i]['faculty_mobile']; ?></td>
													<td><?php echo $row[$i]['faculty_gender']; ?></td>
													<td><?php echo $row[$i]['emp_type']; ?></td>
													<td><?php echo $row[$i]['faculty_designation']; ?></td>
													<td><?php echo $row[$i]['faculty_date']."-".$row[$i]['faculty_month']."-" .$row[$i]['faculty_year'];?></td>
													<td><?php echo $row[$i]['faculty_salary']; ?></td>
													<td><?php echo $row[$i]['faculty_address']; ?></td>
													<td>
														<a href="update_faculty.php?faculty_id=<?php echo $row[$i]['faculty_id']; ?>"  data-toggle="tooltip" data-placement="bottom" title="View"><i class="fa fa-eye fa-1x text-info"></i></a>&nbsp;
														<a href="Faculty_list.php?del_id=<?php echo $row[$i]['faculty_id']; ?>"  onclick="return confirm('Are you sure you want to delete this?');" data-toggle="tooltip" data-placement="bottom" title="Delete"><i class="fa fa-trash-o fa-1x text-danger"></i></a>

													</td>											
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
