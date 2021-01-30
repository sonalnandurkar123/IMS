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
	//echo "SELECT * FROM student_payment_details where str_to_date(`payment_date`, '%d-%m-%Y')>=".date('d-m-Y', strtotime($start_date))." AND str_to_date(`payment_date`, '%d-%m-%Y')<=".date('d-m-Y', strtotime($end_date)) ." ORDER BY payment_id DESC";exit;
	$stmt_search=$conn->prepare("SELECT * FROM student_payment_details where str_to_date(`payment_date`, '%d-%m-%Y')>='".date('Y-m-d', strtotime($start_date))."' AND str_to_date(`payment_date`, '%d-%m-%Y')<='".date('Y-m-d', strtotime($end_date))."' ORDER BY payment_id DESC");
	$stmt_search->execute();
	$row_search=$stmt_search->fetchAll(PDO::FETCH_ASSOC);
	//print_r($row_search);exit;
}
else
{
	$stmt_student =$conn->prepare("SELECT * FROM student_payment_details WHERE payment_date='".date('d-m-Y')."' ORDER BY payment_id DESC");
	$stmt_student->execute();
	$row_student=$stmt_student->fetchAll(PDO::FETCH_ASSOC);
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
  <title> Student Fees List </title>
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
						<h3>Search Date Wise Student Fees List</h3><hr>
								<form class="form-horizontal" method="post" action=" " enctype="multipart/form-data" novalidate>
								  <div class="item form-group">
										<label for="focusedinput" class="col-sm-2 control-label">From<span class="required">*</span></label>
										<div class="col-sm-3">
											<input type="date" class="form-control" name="start_date" id="start_date" placeholder="Start Date" required="required">
										</div>
										<label for="focusedinput" class="col-sm-2 control-label">To<span class="required">*</span></label>
										<div class="col-sm-3">
											<input type="date" class="form-control" name="end_date" id="end_date" placeholder="End Date" required="required">
										</div>
										<div class="col-sm-2">
										<button type="submit" name="submit" class="btn-success btn">Search</button>
									    </div>
								  </div>
								</form>
								<div class="clearfix"></div>
					</div>
					<h2> Student Fees List </h2><hr>
					<div class="x_content">
						<div class="row">
							<div class="col-sm-12">
								<div class="card-box table-responsive">
		                            <table id="datatable-buttons" class="table table-bordered table-striped jambo_table bulk_action" cellspacing="0" width="100%">
										   <thead>
											<tr class="warning">
												<th>Sr. No.</th>
												<th>Name</th>
												<th>Payment Date</th>
												<th>Total Amount</th>
												<th>Paid Amount</th>
												<th>Balance Amount</th>
												<th>Mode Of Payment</th>
												<th>Cheque No.</th>
											</tr>
										</thead>
									    <?php if(isset($_POST['submit'])){?>
											<tbody>
											<?php
											$stmt_student =$conn->prepare("SELECT * FROM student_payment_details WHERE str_to_date(`payment_date`, '%d-%m-%Y')>='".date('Y-m-d', strtotime($start_date))."' AND str_to_date(`payment_date`, '%d-%m-%Y')<='".date('Y-m-d', strtotime($end_date))."' ORDER BY payment_id DESC");
											$stmt_student->execute();
											$row_student=$stmt_student->fetchAll(PDO::FETCH_ASSOC);
											$total_course_fees=0;
					                        $total_pay_amount=0;
					                        $total_balance_amount=0;
											for($c=0;$c<count($row_student);$c++)
											{
											
											?>
												<tr>
													<td><?php echo $c+1;?></td>
													<td><?php 
														  $stmt_stud_details =$conn->prepare("SELECT * FROM student_details WHERE   student_id='".$row_student[$c]['student_id']."' OR student_id='".$row_student[$c]['student_id']."'");
														  $stmt_stud_details->execute();
														  $row_stud_details=$stmt_stud_details->fetchAll(PDO::FETCH_ASSOC);
														  echo $row_stud_details[0]['student_name'];	 															  
													?></td>
													
						                            <td><?php echo $row_student[$c]['payment_date'];?></td>
													<td><?php echo $row_stud_details[0]['course_fees'];?></td>
													<td><?php echo $row_student[$c]['pay_amount'];?></td>
													<td><?php echo $row_stud_details[0]['balance_amount'];?></td>
													<td><?php echo $row_student[$c]['mode_of_payment'];?></td>
													<td><?php echo $row_student[$c]['cheque_no'];?></td>
													
												</tr>
											<?php
											$total_course_fees=$total_course_fees+$row_stud_details[0]['course_fees'];
										
											$total_pay_amount=$total_pay_amount+$row_student[$c]['pay_amount'];
										
											$total_balance_amount=$total_balance_amount+$row_stud_details[0]['balance_amount'];
											
											}
											?>
											</tbody>
											<?php }else{?>
											<tbody>
											<?php
											$stmt_student =$conn->prepare("SELECT * FROM student_payment_details WHERE payment_date='".date('d-m-Y')."' ORDER BY payment_id DESC");
											$stmt_student->execute();
											$row_student=$stmt_student->fetchAll(PDO::FETCH_ASSOC);
											$total_course_fees=0;
					                        $total_pay_amount=0;
					                        $total_balance_amount=0;
											for($c=0;$c<count($row_student);$c++)
											{
											?>
												<tr>
													<td><?php echo $c+1;?></td>
													<td><?php 
														  $stmt_stud_details =$conn->prepare("SELECT * FROM student_details WHERE   student_id='".$row_student[$c]['student_id']."' OR student_id='".$row_student[$c]['student_id']."'");
														  $stmt_stud_details->execute();
														  $row_stud_details=$stmt_stud_details->fetchAll(PDO::FETCH_ASSOC);
														  echo $row_stud_details[0]['student_name'];	 															  
													?></td>
													
						                            <td><?php echo $row_student[$c]['payment_date'];?></td>
													<td><?php echo $row_stud_details[0]['course_fees'];?></td>
													<td><?php echo $row_student[$c]['pay_amount'];?></td>
													<td><?php echo $row_stud_details[0]['balance_amount'];?></td>
													<td><?php echo $row_student[$c]['mode_of_payment'];?></td>
													<td><?php echo $row_student[$c]['cheque_no'];?></td>
													
												</tr>
												
											<?php
											$total_course_fees=$total_course_fees+$row_stud_details[0]['course_fees'];
										
										
											$total_pay_amount=$total_pay_amount+$row_student[$c]['pay_amount'];
										
									
											$total_balance_amount=$total_balance_amount+$row_stud_details[0]['balance_amount'];
											}
											?>
											</tbody>
											<?php }?>
									</table>
								     <h3 style="color:black;">Total Course Fees:&nbsp;<i class="fa fa-rupee"></i>&nbsp;<?php echo $total_course_fees; ?></h3>
										<h3 style="color:black;">Total Paid Amount:&nbsp;<i class="fa fa-rupee"></i>&nbsp;<?php echo $total_pay_amount; ?></h3>
										<h3 style="color:black;">Total Balance Amount:&nbsp;<i class="fa fa-rupee"></i>&nbsp;<?php echo $total_balance_amount; ?></h3>
								 </div>
							</div>
						</div>
					</div>
                </div>
            </div>	
          </div>
        </div>
        <!-- /page content -->
		
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
      </div>
    </div>
   <?php require "foot_link.php";?>	
   
  
  <!--Validation script code -->
  
  
  
  </body>
</html>

