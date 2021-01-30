<?php
require "conn.php";
date_default_timezone_set("Asia/Kolkata");
$student_id=$_POST['student_id'];
if($student_id == "all")
{
	$stmt_stud =$conn->prepare("SELECT * FROM student_details");	
	$stmt_stud->execute();
	$row_stud=$stmt_stud->fetchAll(PDO::FETCH_ASSOC);
}
else 
{
	$stmt_stud =$conn->prepare("SELECT * FROM student_details WHERE student_id=$student_id");
	$stmt_stud->execute();
	$row_stud=$stmt_stud->fetchAll(PDO::FETCH_ASSOC);
	
	if(isset($_POST['payment']))
{
	//print_r($_POST);exit;
	extract($_POST);
	
try{
	$new_paid_amount=$paid_amount+$paying_now;
	//echo $new_paid_amount;exit;
	$new_balance_amount=$balance_amount-$paying_now;
	//update students table
	$stmt_update = $conn->prepare("UPDATE student_details SET paid_amount=:new_paid_amount,balance_amount=:new_balance_amount WHERE student_id=$student_id");

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

}


//print_r($row_stud);
?>
 <table id="datatable-responsive" class="table table-bordered table-striped jambo_table bulk_action" cellspacing="0" width="100%">
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
		for($c=0;$c<count($row_stud);$c++)
		{
		?>
			<tr>
				<td><?php echo $c+1;?></td>
				<td><?php echo $row_stud[$c]['student_name'];?></td>
				<td>
				<?php 
					$stmt_get_course_details=$conn->prepare("SELECT * FROM course_details WHERE course_details_id=".$row_stud[$c]['course_details_id']);
					$stmt_get_course_details->execute();
					$row_get_course_details=$stmt_get_course_details->fetchAll(PDO::FETCH_ASSOC);
					echo $row_get_course_details[0]['course_name'];?></td>
				<td><?php echo $row_stud[$c]['course_fees'];?></td>
				<td><?php echo $row_stud[$c]['balance_amount'];?></td>
				<td><?php echo $row_stud[$c]['paid_amount'];?></td>
				<td><?php echo $row_stud[$c]['student_number'];?></td>
				<td>
					<a href="candidate_detail?id=<?php echo $row_stud[$c]['student_id']?>" target="_blank" data-toggle="tooltip" data-placement="bottom" title="View"><i class="fa fa-eye fa-1x text-info"></i></a>
					<a href=""  data-toggle="modal" data-target="#myModal<?php echo $row_stud[$c]['student_id'];?>" data-toggle="tooltip" data-placement="bottom" title="Payment"><i class="fa fa-credit-card fa-1x text-primary"></i></a>	
				</td>
			</tr>
		<div id="myModal<?php echo $row_stud[$c]['student_id'];?>" class="modal fade" role="dialog">
    <div class="modal-dialog">

	<form method="post" action="print_receipt_update">
	<!-- Modal content-->
	<div class="modal-content">
	 <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>

	  </div>
	  <div class="modal-body">
		<div class="item form-group">
			<label for="focusedinput" class="col-sm-3 control-label">Date</label>
			<div class="col-sm-6">
				<input type="text" class="form-control" value="<?php echo date('d-m-Y');?>" placeholder="Fetch Date" name="date_of_transaction" id="date_of_payment">
			</div>
		</div>
		<div class="item form-group">
			<label for="focusedinput" class="col-sm-3 control-label">Student Name</label>
			<div class="col-sm-6">
				<input type="hidden" value="<?php echo $row_stud[$c]['student_id'];?>" name="student_id">
				<input type="text" class="form-control"  placeholder="Enter Student Name" value="<?php echo $row_stud[$c]['student_name'];?>" name="student_name" readonly>
			</div>
		</div>
		<div class="item form-group">
			<label for="focusedinput" class="col-sm-3 control-label">Course Name</label>
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
			<label for="focusedinput" class="col-sm-3 control-label">Course Fees</label>
			<div class="col-sm-6">
				<input type="text" class="form-control"  placeholder="Enter Course Fees" value="<?php echo $row_stud[$c]['course_fees'];?>" name="course_fees" readonly>
			</div>
		</div>
		<div class="item form-group">
			<label for="focusedinput" class="col-sm-3 control-label">Paid Fees</label>
			<div class="col-sm-6">
				<input type="text" class="form-control"  placeholder="Enter Paid Fees" name="paid_amount" value="<?php echo $row_stud[$c]['paid_amount'];?>" readonly>
			</div>
		</div>
		<div class="item form-group">
			<label for="focusedinput" class="col-sm-3 control-label">Balance Fees</label>
			<div class="col-sm-6">
				<input type="text" class="form-control"  placeholder="Enter Balance Fees" value="<?php echo $row_stud[$c]['balance_amount'];?>" name="balance_amount" readonly>
			</div>
		</div>
		<div class="item form-group">
			<label for="focusedinput" class="col-sm-3 control-label">Paying Now</label>
			<div class="col-sm-6">
				<input type="text" class="form-control"  placeholder="Enter Amount" name="paying_now">
			</div>
		</div>
		<div class="item form-group">
			<label for="focusedinput" class="col-sm-3 control-label">Mode Of Payment</label>
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
				<label for="focusedinput" class="col-sm-3 control-label">Cheque No <span class="required">*</span></label>
				<div class="col-sm-6">
					<input type="text" class="form-control" name="cheque_no" id="cheque_no" onkeypress="return event.keyCode != 13;">
				</div>
			</div>
			<div class="item form-group">
			 <label for="focusedinput" class="col-sm-3 control-label">Cheque Date<span class="required">*</span></label>
				<div class="col-sm-6">
					<input type="text" class="form-control" name="cheque_date" id="cheque_date" onkeypress="return event.keyCode != 13;">
				</div>
			</div>
			<div class="item form-group">
				<label for="focusedinput" class="col-sm-3 control-label">Bank Name<span class="required">*</span></label>
				<div class="col-sm-6">
					<input type="text" class="form-control" name="bank_name" id="cheque_no" onkeypress="return event.keyCode != 13;">
				</div>
			</div>
	    </div>
		<div class="item form-group">
			<label for="focusedinput" class="col-sm-3 control-label">Pay Amount In<span class="required">*</span></label>
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
		  <label for="focusedinput" class="col-sm-3 control-label">Next Installment Date<span class="required">*</span></label>
		  <div class="col-sm-6">
				<input type="date" class="form-control" name="next_date" id="next_date" placeholder="Ex : 01-01-2001" required>
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
</table>
<script>
	$(function() {
		$('#cheque_date').datepicker();
		$( "#cheque_date" ).on( "change", function() {
		  $( "#cheque_date" ).datepicker( "option", "dateFormat", "dd-mm-yy" );
		});
	});
  </script>
  <script>
	$(function() {
		$('#next_date').datepicker();
		$( "#next_date" ).on( "change", function() {
		  $( "#next_date" ).datepicker( "option", "dateFormat", "dd-mm-yy" );
		});
	});
  </script>	
