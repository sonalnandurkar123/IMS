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
	//echo "SELECT * FROM daily_transaction where str_to_date(`daily_date`, '%d-%m-%Y')>=".date('d-m-Y', strtotime($start_date))." AND str_to_date(`daily_date`, '%d-%m-%Y')<=".date('d-m-Y', strtotime($end_date)) ." ORDER BY daily_transaction_id DESC";exit;
	$stmt_search=$conn->prepare("SELECT * FROM daily_transaction where str_to_date(`daily_date`, '%d-%m-%Y')>='".date('Y-m-d', strtotime($start_date))."' AND str_to_date(`daily_date`, '%d-%m-%Y')<='".date('Y-m-d', strtotime($end_date))."' ORDER BY daily_transaction_id DESC");
	$stmt_search->execute();
	$row_search=$stmt_search->fetchAll(PDO::FETCH_ASSOC);
	//print_r($row_search);exit;
}
else
{
	$stmt_student_details =$conn->prepare("SELECT * FROM daily_transaction ORDER BY daily_transaction_id DESC");
	$stmt_student_details->execute();
	$row_student_details=$stmt_student_details->fetchAll(PDO::FETCH_ASSOC);
	
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
  <title>Ledger List</title>
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
						<h3>Search Date Wise Ledger</h3><hr>
								<form class="form-horizontal" method="post" action=" " enctype="multipart/form-data">
								  <div class="form-group">
										<label for="focusedinput" class="col-sm-2 control-label">From<span class="required">*</span></label>
										<div class="col-sm-3">
											<input type="date" class="form-control" name="start_date" id="start_date" placeholder="Start Date" required>
										</div>
										<label for="focusedinput" class="col-sm-2 control-label">To<span class="required">*</span></label>
										<div class="col-sm-3">
											<input type="date" class="form-control" name="end_date" id="end_date" placeholder="End Date" required>
										</div>
										<div class="col-sm-2">
										<button type="submit" name="submit" class="btn-success btn">Search</button>
									    </div>
								  </div>
								</form>
									
					</div>
						<h3>Ledger List</h3><hr>
					<div class="x_content">
						<div class="row">
							<div class="col-sm-12">
								<div class="card-box table-responsive">
									 <table  id="datatable-buttons" class="table table-bordered table-striped jambo_table bulk_action" cellspacing="0" width="100%">
									   <thead>
												<tr class="warning">
												    <th>Date</th>
													<th>Particular</th>
													<th>Transaction Type</th>
													<th>Debit</th>
													<th>Credit</th>
												</tr>
											</thead>
											
										<?php 
										  if(isset($_POST['submit'])){
										  $stmt_student =$conn->prepare("SELECT * FROM daily_transaction WHERE str_to_date(`daily_date`, '%d-%m-%Y')>='".date('Y-m-d', strtotime($start_date))."' AND str_to_date(`daily_date`, '%d-%m-%Y')<='".date('Y-m-d', strtotime($end_date))."' ORDER BY daily_transaction_id DESC");
										  $stmt_student->execute();
										  $row_student=$stmt_student->fetchAll(PDO::FETCH_ASSOC);
										  $total_credit=0;
										  $total_debit=0;
										?>
										<tbody>
												<?php
												for($c=0;$c<count($row_student);$c++)
												{
													if($row_student[$c]['income_or_expense']=="Income"){
												?>
												<tr>
														<td><?php echo date('d-m-Y', strtotime($row_student[$c]['date_of_transaction']));?></td>
														<td><?php 
																$stmt_course_details =$conn->prepare("SELECT * FROM student_details WHERE   student_id='".$row_student[$c]['student_id']."' OR student_id='".$row_student[$c]['student_id']."'");
																$stmt_course_details->execute();
																$row_course_details=$stmt_course_details->fetchAll(PDO::FETCH_ASSOC);
																echo $row_course_details[0]['student_name'];
															?>	 	
														</td>
														<td><?php echo $row_student[$c]['income_or_expense'];?></td>
														<td><?php if($row_student[$c]['income_or_expense']=="Income"){echo "-";}?></td>
														<td><?php if($row_student[$c]['income_or_expense']=="Income"){echo $row_student[$c]['amount'];}?></td>
		
												</tr>
											        <?php 
											        }
													elseif($row_student[$c]['income_or_expense']=="Expense"){
													?>
													 <tr>
														<td><?php echo date('d-m-Y', strtotime($row_student[$c]['date_of_transaction']));?></td>
														<td><?php  
															  $stmt_expense_details =$conn->prepare("SELECT * FROM expense_details WHERE expense_id='".$row_student[$c]['student_id']."'");
															  $stmt_expense_details->execute();
															  $row_expense_details=$stmt_expense_details->fetchAll(PDO::FETCH_ASSOC);
															  echo $row_expense_details[0]['expense_name'];		 															  
														?></td>
														<td><?php echo $row_student[$c]['income_or_expense'];?></td>
														<td><?php if($row_student[$c]['income_or_expense']=="Expense"){echo $row_student[$c]['amount'];}?></td>
														 <td><?php if($row_student[$c]['income_or_expense']=="Expense"){echo "-";}?></td>
														</tr>
													
												<?php }
													elseif($row_student[$c]['income_or_expense']=="Payment"){
													?>
													   <tr>
														<td><?php echo date('d-m-Y', strtotime($row_student[$c]['date_of_transaction']));?></td>
														<td><?php  
															  $stmt_salary_details =$conn->prepare("SELECT * FROM faculty_details WHERE faculty_id='".$row_student[$c]['student_id']."'");
															  $stmt_salary_details->execute();
															  $row_salary_details=$stmt_salary_details->fetchAll(PDO::FETCH_ASSOC);
															  echo $row_salary_details[0]['faculty_name'];		 															  
														?></td>
														<td><?php echo $row_student[$c]['income_or_expense'];?></td>
														<td><?php if($row_student[$c]['income_or_expense']=="Payment"){echo $row_student[$c]['amount'];}?></td>
														<td><?php if($row_student[$c]['income_or_expense']=="Payment"){echo "-";}?></td>
														</tr>
													

												
												<?php
												}else{
												?>
													<tr>
														<td><?php echo date('d-m-Y', strtotime($row_student[$c]['date_of_transaction']));?></td>
														<td><?php echo $row_student[$c]['student_id'];?></td>
														<td><?php echo $row_student[$c]['income_or_expense'];?></td>
														<td><?php echo $row_student[$c]['amount'];?></td>
													</tr>
												<?php
												}
												if($row_student[$c]['income_or_expense']=="Income"){
												  $total_credit=$total_credit+$row_student[$c]['amount'];
												}else{
												$total_debit=$total_debit+$row_student[$c]['amount'];}
												}
												?>
										    </tbody>
										    <?php } else{?>
											<?php
											  $stmt_student =$conn->prepare("SELECT * FROM daily_transaction ORDER BY daily_transaction_id DESC");
											  $stmt_student->execute();
											  $row_student=$stmt_student->fetchAll(PDO::FETCH_ASSOC);
											  $total_credit=0;
											  $total_debit=0;
											?>
											<tbody>
												<?php
												for($c=0;$c<count($row_student);$c++)
												{
													if($row_student[$c]['income_or_expense']=="Income"){
												?>
												<tr>
														<td><?php echo date('d-m-Y', strtotime($row_student[$c]['date_of_transaction']));?></td>
														<td><?php 
																$stmt_course_details =$conn->prepare("SELECT * FROM student_details WHERE   student_id='".$row_student[$c]['student_id']."' OR student_id='".$row_student[$c]['student_id']."'");
																$stmt_course_details->execute();
																$row_course_details=$stmt_course_details->fetchAll(PDO::FETCH_ASSOC);
																echo $row_course_details[0]['student_name'];
															?>	 	
														</td>
														<td><?php echo $row_student[$c]['income_or_expense'];?></td>
														<td><?php if($row_student[$c]['income_or_expense']=="Income"){echo "-";}?></td>
														<td><?php if($row_student[$c]['income_or_expense']=="Income"){echo $row_student[$c]['amount'];}?></td>
		
												</tr>
											  <?php }
													elseif($row_student[$c]['income_or_expense']=="Expense"){
													?>
													 <tr>
														<td><?php echo date('d-m-Y', strtotime($row_student[$c]['date_of_transaction']));?></td>
														<td><?php  
															  $stmt_expense_details =$conn->prepare("SELECT * FROM expense_details WHERE expense_id='".$row_student[$c]['student_id']."'");
															  $stmt_expense_details->execute();
															  $row_expense_details=$stmt_expense_details->fetchAll(PDO::FETCH_ASSOC);
															  echo $row_expense_details[0]['expense_name'];		 															  
														?></td>
														<td><?php echo $row_student[$c]['income_or_expense'];?></td>
														<td><?php if($row_student[$c]['income_or_expense']=="Expense"){echo $row_student[$c]['amount'];}?></td>
														 <td><?php if($row_student[$c]['income_or_expense']=="Expense"){echo "-";}?></td>
														</tr>
													
												<?php }
													elseif($row_student[$c]['income_or_expense']=="Payment"){
													?>
													   <tr>
														<td><?php echo date('d-m-Y', strtotime($row_student[$c]['date_of_transaction']));?></td>
														<td><?php  
															  $stmt_salary_details =$conn->prepare("SELECT * FROM faculty_details WHERE faculty_id='".$row_student[$c]['student_id']."'");
															  $stmt_salary_details->execute();
															  $row_salary_details=$stmt_salary_details->fetchAll(PDO::FETCH_ASSOC);
															  echo $row_salary_details[0]['faculty_name'];		 															  
														?></td>
														<td><?php echo $row_student[$c]['income_or_expense'];?></td>
														<td><?php if($row_student[$c]['income_or_expense']=="Payment"){echo $row_student[$c]['amount'];}?></td>
														<td><?php if($row_student[$c]['income_or_expense']=="Payment"){echo "-";}?></td>
														</tr>
													

												
												<?php
												}else{
												?>
													<tr>
														<td><?php echo date('d-m-Y', strtotime($row_student[$c]['date_of_transaction']));?></td>
														<td><?php echo $row_student[$c]['student_id'];?></td>
														<td><?php echo $row_student[$c]['income_or_expense'];?></td>
														<td><?php echo $row_student[$c]['amount'];?></td>
													</tr>
												<?php
												}
												if($row_student[$c]['income_or_expense']=="Income"){
												  $total_credit=$total_credit+$row_student[$c]['amount'];
												}else{
												$total_debit=$total_debit+$row_student[$c]['amount'];}  
												}
                                            	?>
										    </tbody>
											<?php }?>
									    
									</table></br></br>
										<h3 style="color:black;">Total Credit Amount:&nbsp;<i class="fa fa-rupee"></i>&nbsp;<?php echo $total_credit; ?></h3>
										<h3 style="color:black;">Total Debit Amount:&nbsp;<i class="fa fa-rupee"></i>&nbsp;<?php echo $total_debit; ?></h3>
								 </div>
							</div>
						</div>
					</div>
                </div>
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
   
  
   
  
  </body>
</html>

