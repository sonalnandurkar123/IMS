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
			$stmt=$conn->prepare("DELETE FROM staff_salary WHERE salary_id=$del_id");
			$stmt->execute();
				if(execute)
                      {
						 header("Location:staff_salary_list");
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
						<a href="staff_salary.php" class="btn btn-primary submit" >Add Employee Payment</a>
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
										$stmt=$conn->prepare("SELECT * FROM staff_salary");
										$stmt->execute();
										$row=$stmt->fetchAll(PDO::FETCH_ASSOC);
									?>

									 <table id="datatable-buttons" class="table table-bordered table-striped jambo_table bulk_action" cellspacing="0" width="100%">
									  <thead>
												<tr class="warning">
													<th>Sr. No.</th>
													<th>Name</th>
													<th>Salary</th>
													<th>Deduction</th>
													<th>Bonus</th>
													<th>Total</th>
													<th>Payment Date</th>
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
													<td><?php //echo $row[$i]['faculty_name']; ?>
													    <?php
                                    						$stmt2=$conn->prepare("SELECT * FROM faculty_details WHERE faculty_id=".$row[$i]['faculty_id']);
                                    						$stmt2->execute();
                                    						$row2=$stmt2->fetchAll(PDO::FETCH_ASSOC);
                                    						echo $row2[0]['faculty_name'];?>
													</td>
													<td><?php echo $row[$i]['salary']; ?></td>
													<td><?php echo $row[$i]['deduction']; ?></td>
													<td><?php echo $row[$i]['bonus']; ?></td>
													<td><?php echo $row[$i]['total_salary']; ?></td>
													<td><?php echo $row[$i]['date_of_salary']; ?></td>
													
													<td>
														<!--<a href="update_faculty?faculty_id=<?php echo $row[$i]['faculty_id']; ?>" class="btn btn-primary">Update</a>-->
														<a href="staff_salary_list?del_id=<?php echo $row[$i]['faculty_id']; ?>" data-toggle="tooltip" data-placement="bottom" title="Delete"   onclick="return confirm('Are you sure you want to delete this?');"><i class="fa fa-trash-o fa-1x text-danger"></i></a>
													</td>
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
