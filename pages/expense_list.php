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
	{		
			$del_id=$_GET['del_id'];	
			$stmt_exp=$conn->prepare("DELETE FROM expense_details WHERE expense_id=$del_id");	
			$executed=$stmt_exp->execute();
			/*$stmt_trans=$conn->prepare("DELETE FROM daily_transaction WHERE student_id=$del_id");	
			$executed=$stmt_trans->execute();*/
			if($executed)
				  {
					 //header("Location:expense_list");
					  echo "<script>window.location.href='expense_list';</script>";
				  }
	}
	

?>
<!DOCTYPE html>
<html lang="en">
  <head>
     <title> Expences List </title>
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
                <h3>Expenses Details</h3>
              </div>
            </div>

            <div class="clearfix"></div>

		    <div class="col-md-12 col-sm-12 ">
				<div class="x_panel">
					  <div class="x_title">
						<a href="add_expense" class="btn btn-primary submit" >Add Expenses</a>
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
										  <th>Sr.No.</th>
										  <th>Expense</th>
										  <th>Details</th>
										  <th>Approvals</th>
										  <th>Expense Amount</th>
										  <th>Date</th>
										  <th>Action</th>
										</tr>
									  </thead>
									  <tbody>
										<?php
											$stmt_date =$conn->prepare("SELECT * FROM expense_details ORDER BY expense_id DESC");
											$stmt_date->execute();
											$row_date=$stmt_date->fetchAll(PDO::FETCH_ASSOC);
											for($m=0;$m<count($row_date);$m++)
										{
										?>
										
										<tr>
											<td><?php echo $m+1;?></td>
											<td><?php echo $row_date[$m]['expense_name'];?></td>
											<td><?php echo $row_date[$m]['expense_detail'];?></td>
											<td><?php echo $row_date[$m]['expense_approve'];?></td>
											<td><?php echo $row_date[$m]['expense_amount'];?></td>
											<td><?php echo $row_date[$m]['expense_date']."-".$row_date[$m]['expense_month']."-" .$row_date[$m]['expense_year'];?></td>
										<!--<td>
											<a href="" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Update</a>
											<a href="" class="btn btn-primary" onclick="return confirm('Are you sure you want to delete this?');">Delete</a>
									    </td>-->
										
										<td>
										<a href="expense_list?del_id=<?php echo $row_date[$m]['expense_id'];?>" data-toggle="tooltip" data-placement="Bottom" title=" Delete" onclick="return confirm('Are you sure you want to delete this?');"><i class="fa fa-trash-o fa-1x text-danger"></i></a>
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
