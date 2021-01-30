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
		$stmt=$conn->prepare("DELETE FROM enquiry_details WHERE enquiry_id=$id");	
		$executed=$stmt->execute();
		if($executed)
		//$stmt->execute();
		//if(execute)
		    {

			 //header("Location:enquiry_list");
			  echo "<script>window.location.href='enquiry_list.php';</script>";
		    }
	}
	
	
if(isset($_POST['payment']))
{
	//print_r($_POST);exit;
	extract($_POST);
	
try{
	$new_paid_amount=$paid_amount+$paying_now;
	//echo $new_paid_amount;exit;
	$new_balance_amount=$balance_amount-$paying_now;
	//update students table
	$stmt_update = $conn->prepare("UPDATE student_details SET paid_amount=:paid_amount,balance_amount=:balance_amount WHERE student_id=$student_id");

	$execute_update=$stmt_update->execute(array(':paid_amount' => $new_paid_amount,':balance_amount' => $new_balance_amount));
	if($execute_update)
	{
		$stmt=$conn->prepare("SELECT * FROM daily_transaction ORDER BY daily_transaction_id DESC");
		$stmt->execute();
		$row=$stmt->fetchAll(PDO::FETCH_ASSOC);
		if($row)
		{
			$old_amount=$row[0]['total_balance'];
			//echo $old_amount;exit;
			$new_amount=$old_amount+$paying_now;
			//insert data in daily transaction
			$stmt_tran = $conn->prepare("INSERT INTO daily_transaction(income_or_expense,amount,total_balance,date_of_transaction)VALUES(:income_or_expense,:amount,:total_balance,:date_of_transaction)");
			$executed_tran=$stmt_tran->execute(array(':income_or_expense' => "income",':amount' => $paying_now,':total_balance' => $new_amount,':date_of_transaction' => date('Y-m-d H:i:s')));
			if($executed_tran)
			{
			?>
				<script>alert("Added Successfully");</script>
			<?php
			}

		}
	}		
		
	}catch(Exception $e) {
	  echo 'Message: ' .$e->getMessage();
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
                <h3>Payment Details</h3>
              </div>
            </div>

            <div class="clearfix"></div>

		    <div class="col-md-12 col-sm-12 ">
				<div class="x_panel">
					  <div class="x_title">
						<h2>Receipt list</h2>
						<?php
							//get total fees, balance and paid_amount
							$stmt_student_total =$conn->prepare("SELECT SUM(course_fees) AS course_fees, SUM(paid_amount) AS paid_amount, SUM(balance_amount) AS balance_amount FROM student_details ORDER BY student_id DESC");
							$stmt_student_total->execute();
							$row_student_total=$stmt_student_total->fetchAll(PDO::FETCH_ASSOC);
							//echo "Total Course Fees:".$row_student_total[0]['course_fees']."     Total Paid Amount:".$row_student_total[0]['paid_amount']."     Total Balance Amount:".$row_student_total[0]['balance_amount'];
						?>
									<ul class="nav navbar-right panel_toolbox">
						  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
						  </li>
						  <li><a class="close-link"><i class="fa fa-close"></i></a>
						  </li>
						</ul>
						<div class="clearfix"></div>
					  </div>
					  
					  <!-- //search-scripts -->
					  </br>
									<div class="item form-group">
									<label for="selector1" class="col-form-label col-md-3 col-sm-3 label-align">Student Name </label>
										<div class="col-sm-8 wrapper">
											<select name="subject_name" id="test" required="required" class="form-control" onchange="get_student_details(this.value);this.blur();">
												<option value="all">-----Select Student-----</option>
												<?php
												$stmt_student =$conn->prepare("SELECT * FROM student_details");
												$stmt_student->execute();
												$row_student=$stmt_student->fetchAll(PDO::FETCH_ASSOC);
												for($s=0;$s<count($row_student);$s++)
												{
													?>
													<option value="<?php echo $row_student[$s]['student_id'];?>"><?php echo $row_student[$s]['student_name']?></option>
													<?php
												}
												?>
											</select>
										</div>
									</div>
										
									<script>
										function get_student_details(student_id) 
										{	
											//var textboxno=($("input[name='add[product_id][]']").length);
											$.ajax({
														type: "POST",
														url: "ajax_get_student_details.php",
														data: {student_id:student_id},
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
															$("#student_details_div").html(html);
															
														}					
													}
												   });
										}
									</script>
				<div class="bs-example4" data-example-id="contextual-table" id="student_details_div">
				    <div class="x_content">
						<div class="row">
							<div class="col-sm-12">
								<div class="card-box table-responsive">	 </br>
									 <table id="datatable-buttons" class="table table-bordered table-striped jambo_table bulk_action" cellspacing="0" width="100%">
									  <thead>
												<tr class="warning">
													<th>Sr. No.</th>
													<th>Student Name</th>
													<th>Course Name</th>
													<th>Course Fees</th>
													<th>Balance Amount</th>
													<th>Paid Amount</th>
													<th>Contact</th>
													<th>Action</th>
												</tr>
											</thead>
								<tbody>
													<?php
													$stmt_stud =$conn->prepare("SELECT * FROM student_details ORDER BY student_id DESC");	
													$stmt_stud->execute();
													$row_stud=$stmt_stud->fetchAll(PDO::FETCH_ASSOC);
													?>
													<?php
														for($c=0;$c<count($row_stud);$c++)
														{
													?>
													<tr>
														<td><?php echo $c+1;?></td>
														<td><?php echo $row_stud[$c]['student_name'];?></td>
														<td>
														<?php 
															//echo $row[$i]['course_name']; 
															//get course name using course id
															$stmt_course=$conn->prepare("SELECT * FROM course_details WHERE course_details_id=".$row_stud[$c]['course_details_id']);
															$stmt_course->execute();
															$row_course=$stmt_course->fetchAll(PDO::FETCH_ASSOC);
															//echo $row_course[0]['course_name']; 
														?>
														<?php echo $row_course[0]['course_name'];?>
													</td>
														<td><?php echo $row_stud[$c]['course_fees'];?></td>
														<td><?php echo $row_stud[$c]['balance_amount'];?></td>
														<td><?php echo $row_stud[$c]['paid_amount'];?></td>
														<td><?php echo $row_stud[$c]['student_number'];?></td>
														<td>
															<a href="candidate_detail?id=<?php echo $row_stud[$c]['student_id']?>" data-toggle="tooltip" data-placement="bottom" title="View"  target="_blank"><i class="fa fa-eye fa-1x text-info"></i></a>&nbsp;
															<?php if($row_stud[$c]['balance_amount']!=0){?>
															<a href=""  data-toggle="modal" data-target="#myModal<?php echo $row_stud[$c]['student_id'];?>" data-toggle="tooltip" data-placement="bottom" title="Payment" ><i class="fa fa-credit-card fa-1x text-primary"></i></a>	<?php } ?>			
															<!--<a href="" class="btn btn-primary" onclick="return confirm('Are you sure you want to delete this?');">Delete</a>-->
														</td>
													</tr>
													
										
													<div id="myModal<?php echo $row_stud[$c]['student_id'];?>" class="modal fade" role="dialog">
													<div class="modal-dialog ">

													<form method="post" action="print_receipt_update">
													<!-- Modal content-->
													<div class="modal-content">
													 <div class="modal-header">
														<button type="button" class="close" data-dismiss="modal">&times;</button>
														
													  </div>
													  <div class="modal-body">
														<div class="item form-group">
															<label for="focusedinput" class="col-form-label col-md-3 col-sm-3 label-align">Date</label>
															<div class="col-sm-6">
																<input type="text" class="form-control" value="<?php echo date('d-m-Y');?>" placeholder="Fetch Date" name="date_of_transaction" id="date_of_payment">
															</div>
														</div>
														<div class="item form-group">
															<label for="focusedinput" class="col-form-label col-md-3 col-sm-3 label-align">Student Name</label>
															<div class="col-sm-6">
																<input type="hidden" value="<?php echo $row_stud[$c]['student_id'];?>" name="student_id">
																<input type="text" class="form-control"  placeholder="Enter Student Name" value="<?php echo $row_stud[$c]['student_name'];?>" name="student_name" readonly>
															</div>
														</div>
														<div class="item form-group">
															<label for="focusedinput" class="col-form-label col-md-3 col-sm-3 label-align">Course Name</label>
															<div class="col-sm-6">
															<?php 
																$stmt_course=$conn->prepare("SELECT * FROM course_details WHERE course_details_id=".$row_stud[$c]['course_details_id']);
																$stmt_course->execute();
																$row_course=$stmt_course->fetchAll(PDO::FETCH_ASSOC);
																//echo $row_course[0]['course_name']; 
															?>
																<input type="text" class="form-control"  placeholder="Enter Course Fees" value="<?php echo $row_course[0]['course_name'];?>" name="course_name" readonly>
																<input type="hidden" class="form-control"  placeholder="Enter Course Fees" value="<?php echo $row_stud[$c]['course_details_id'];?>" name="course_details_id" readonly>
															</div>
														</div>
														<div class="item form-group">
															<label for="focusedinput" class="col-form-label col-md-3 col-sm-3 label-align">Course Fees</label>
															<div class="col-sm-6">
																<input type="text" class="form-control"  placeholder="Enter Course Fees" value="<?php echo $row_stud[$c]['course_fees'];?>" name="course_fees" readonly>
															</div>
														</div>
														<div class="item form-group">
															<label for="focusedinput" class="col-form-label col-md-3 col-sm-3 label-align">Paid Fees</label>
															<div class="col-sm-6">
																<input type="text" class="form-control"  placeholder="Enter Paid Fees" name="paid_amount" value="<?php echo $row_stud[$c]['paid_amount'];?>" readonly>
															</div>
														</div>
														<div class="item form-group">
															<label for="focusedinput" class="col-form-label col-md-3 col-sm-3 label-align">Balance Fees</label>
															<div class="col-sm-6">
																<input type="text" class="form-control"  placeholder="Enter Balance Fees" value="<?php echo $row_stud[$c]['balance_amount'];?>" name="balance_amount" readonly>
															</div>
														</div>
														<div class="item form-group">
															<label for="focusedinput" class="col-form-label col-md-3 col-sm-3 label-align">Paying Now</label>
															<div class="col-sm-6">
																<input type="text" class="form-control"  placeholder="Enter Amount" name="paying_now" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;">
															</div>
														</div>
														<div class="item form-group">
															<label for="focusedinput" class="col-form-label col-md-3 col-sm-3 label-align">Mode Of Payment</label>
															<div class="col-sm-6">
																<select name="mode_of_payment" class="form-control" required onchange="get_cheque_no(this.value)">
																	<option>-----Select-----</option>
																	<option value="Cash">Cash</option>
																	<option value="Cheque">Cheque</option>
																	<option value="Online">Online</option>
																	<option value="Other">Other</option>
																</select>
															</div>
														</div>
														<div id="div_cheque_no" style="display:none">
															<div class="item form-group">
																<label for="focusedinput" class="col-form-label col-md-3 col-sm-3 label-align">Cheque No <span class="required">*</span></label>
																<div class="col-sm-6">
																	<input type="text" class="form-control" name="cheque_no" id="cheque_no"  onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;">
																</div>
															</div>
															<div class="item form-group">
															 <label for="focusedinput" class="col-form-label col-md-3 col-sm-3 label-align">Cheque Date<span class="required">*</span></label>
																<div class="col-sm-6">
																	<input type="date" class="form-control" name="cheque_date" id="cheque_date" onkeypress="return event.keyCode != 13;">
																</div>
															</div>
															<div class="item form-group">
																<label for="focusedinput" class="col-form-label col-md-3 col-sm-3 label-align">Bank Name<span class="required">*</span></label>
																<div class="col-sm-6">
																	<input type="text" class="form-control" name="bank_name" id="cheque_no" onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)" >
																</div>
															</div>
														</div>
														<div class="item form-group">
															<label for="focusedinput" class="col-form-label col-md-3 col-sm-3 label-align">Pay Amount In<span class="required">*</span></label>
															<div class="col-sm-6">
																<select name="paid_entry" class="form-control" required>
																	<option value="">-----Select-----</option>
																	<option value="Part Payment">Part Payment</option>
																	<option value="Full Payment">Full Payment</option>
																	<option value="Advance Payment">Advance Payment</option>
																</select>
															</div>
														</div>
														<div class="item form-group">
														  <label for="focusedinput" class="col-form-label col-md-3 col-sm-3 label-align">Next Installment Date</label>
														  <div class="col-sm-6">
																<input type="date" class="form-control" name="next_date" id="next_date" placeholder="Ex : 01-01-2001">
														   </div>
														</div>
														<script>
															function get_cheque_no(mode_of_payment)
															{
																if(mode_of_payment=='Cheque')
																{
																	document.getElementById("div_cheque_no").style.display = "block";
																}
																else
																{
																	document.getElementById("div_cheque_no").style.display = "none";
																}
															}
													    </script>
													  </div>
													  <div class="modal-footer">
														<button type="submit" name="payment" class="btn btn-primary">Payment</button>
														<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
													  </div>
													</div>
													</form>
													</div>
													</div>
													<?php
													}
													?>										
											</tbody>
							       </table></br></br>
								   <h3 style="color:black;">Total Course Fees:&nbsp;<i class="fa fa-rupee"></i>&nbsp;<?php if(!empty($row_student_total[0]['course_fees'])) {echo $row_student_total[0]['course_fees'];} else {echo "0";}?></h3>
										<h3 style="color:black;">Total Paid Amount:&nbsp;<i class="fa fa-rupee"></i>&nbsp;<?php if(!empty($row_student_total[0]['paid_amount'])){echo $row_student_total[0]['paid_amount'];} else {echo "0";}?></h3>
										<h3 style="color:black;">Total Balance Amount:&nbsp;<i class="fa fa-rupee"></i>&nbsp;<?php if(!empty($row_student_total[0]['balance_amount'])){echo $row_student_total[0]['balance_amount'];}else {echo "0";}?></h3>										
								  
								</div>
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
             
						 <!-- Integer number Validation -->
					   
					                            <span id="error" style="color: Red; display: none; padding-right:10px; font-size:20px;">Enter only Mobile number</span>
					                            <script type="text/javascript">
													var specialKeys = new Array();
													specialKeys.push(8); //Backspace
													function IsNumeric(e) {
														var keyCode = e.which ? e.which : e.keyCode
														var ret = ((keyCode >= 48 && keyCode <= 57) || specialKeys.indexOf(keyCode) != -1);
														document.getElementById("error").style.display = ret ? "none" : "inline";
														return ret;
													}
												</script>
  </body>
</html>
