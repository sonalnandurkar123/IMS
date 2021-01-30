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

	$stmt_student =$conn->prepare("SELECT * FROM student_details ORDER BY student_id DESC");
	$stmt_student->execute();
	$row_student=$stmt_student->fetchAll(PDO::FETCH_ASSOC);

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
                <h3>Payment Details</h3>
              </div>
            </div>

            <div class="clearfix"></div>

		    <div class="col-md-12 col-sm-12 ">
				<div class="x_panel">
					  <div class="x_title">
						<h2>Payment Lists</h2>
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
