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

if(isset($_POST["save"]))
{
	//$row=$_POST;
	 extract($_POST);
	//print_r($row);exit;
	//print_r($_POST);
	try{
	$dt=date('Y-m-d');
	    $stmt = $conn->prepare("INSERT INTO `staff_salary`(`faculty_id`,`salary`, `deduction`, `bonus`, `total_salary`, `date_of_salary`) VALUES(:faculty_id,:salary,:deduction,:bonus, :total_salary,:date_of_salary)");
		    
		$executed=$stmt->execute(array(':faculty_id' => $faculty_id, ':salary' => $salary, ':deduction' => $deduction, ':bonus' => $bonus, ':total_salary' => $total_salary,':date_of_salary' => $date_of_salary));
		//$affected_rows = $stmt->rowCount();
		
		if($executed)
		{
			
			//get previous balance
			$stmt=$conn->prepare("SELECT * FROM daily_transaction ORDER BY daily_transaction_id DESC");
			$stmt->execute();
			$row=$stmt->fetchAll(PDO::FETCH_ASSOC);
			if($row)
			{
				$old_amount=$row[0]['total_balance'];
				$new_amount=$old_amount-$total_salary;
				//insert data in daily transaction
				$date_of_transaction=date('Y-m-d',strtotime($date_of_entry))." ".date('H:i:s');
				$stmt_tran = $conn->prepare("INSERT INTO daily_transaction(student_id,income_or_expense,amount,total_balance,date_of_transaction,daily_date)VALUES(:student_id,:income_or_expense,:amount,:total_balance,:date_of_transaction,:daily_date)");
				$executed_tran=$stmt_tran->execute(array(':student_id' => $faculty_id,':income_or_expense' => "Payment",':amount' => $total_salary,':total_balance' => $new_amount,':date_of_transaction' => date('Y-m-d H:i:s'),':daily_date' => date('d-m-Y')));
				if($executed_tran)
				{
				?>
					<script>alert("Added Successfully");
					 window.location.href='staff_salary_list';</script>
				<?php
				}

			}
			else
			{
				//$old_amount=$row[0]['total_balance'];
				//$new_amount=$old_amount-$row[0]['total_balance'];
				//insert data in daily transaction
				$stmt_tran = $conn->prepare("INSERT INTO daily_transaction(student_id,income_or_expense,amount,total_balance,date_of_transaction)VALUES(:student_id,:income_or_expense,:amount,:total_balance,:date_of_transaction)");
				$executed_tran=$stmt_tran->execute(array(':student_id' => $faculty_id,':income_or_expense' => "Payment",':amount' => $total_salary,':total_balance' => -$new_amount,':date_of_transaction' => date('Y-m-d H:i:s'),':daily_date' => date('d-m-Y')));
				if($executed_tran)
				{
				?>
					<script>alert("Added Successfully");
					 window.location.href='staff_salary_list';</script>
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
                <h3>Employee Details</h3>
              </div>
            </div>

            <div class="clearfix"></div>
            <form class="form-horizontal" action="" method="post" novalidate>
		    <div class="col-md-12 col-sm-12 ">
				<div class="x_panel">
					  <div class="x_title">
						<h2>Employee salary</h2>
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
										try {
											$stmt_doc_list = $conn->prepare("SELECT * FROM `faculty_details`");
											$stmt_doc_list->execute();
											$row_doc_list = $stmt_doc_list->fetchAll(PDO::FETCH_ASSOC);
													  
													  if($row_doc_list)
													  {
														  //print_r($row_doc_list);
														  
													  }
													}
										catch(PDOException $e2) {
											echo "Error: " . $e2->getMessage();
										}
									?>
									<div class="field item form-group">
										<label for="selector1" class="col-form-label col-md-3 col-sm-3 label-align">Employee Name </label>
										<div class="col-sm-6">
											<select name="faculty_id" id="faculty_id" required="required" class="form-control">
												<option>-----Select Employee-----</option>
												<?php
													for($d=0;$d<count($row_doc_list);$d++)
													{
												?>
												<option value="<?php echo $row_doc_list[$d]['faculty_id']; ?>"><?php echo $row_doc_list[$d]['faculty_name']." (".$row_doc_list[$d]['emp_type'].")"; ?></option>
												<?php 
													}
												?>
											</select>
										</div>
									</div>
									
									<div class="field item form-group">
										<label for="focusedinput" class="col-form-label col-md-3 col-sm-3 label-align">Salary</label>
										<div class="col-sm-6">
											<input type="text" name="salary" id="staff_salary" required="required" class="form-control" id="" placeholder="Salary" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" >
										</div>
									</div>
									
									<div class="field item form-group">
										<label for="focusedinput" class="col-form-label col-md-3 col-sm-3 label-align">Deduction</label>
										<div class="col-sm-6">
											<input type="text" name="deduction" required="required" class="form-control" id="salary_deduction" placeholder="Deduction" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;">
										</div>
									</div>
									
									<div class="field item form-group">
										<label for="focusedinput" class="col-form-label col-md-3 col-sm-3 label-align">Bonus</label>
										<div class="col-sm-6">
											<input type="text" name="bonus" required="required" class="form-control" id="salary_bonus" placeholder="Bonus" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;">
										</div>
									</div>
									
									<div class="field item form-group">
										<label for="focusedinput" class="col-form-label col-md-3 col-sm-3 label-align">Total</label>
										<div class="col-sm-6">
											<input type="text" name="total_salary" required="required" class="form-control" id="salary_total" onkeypress="return event.keyCode != 13;" placeholder="Deduction">
										</div>
									</div>
									<div class="field item form-group">
										<label for="focusedinput" class="col-form-label col-md-3 col-sm-3 label-align">Payment Date<span class="required">*</span></label>
										<div class="col-sm-6">
											<input type="date" name="date_of_salary"  class="form-control" id="date_of_salary" placeholder="Ex : 01-01-2001" required="required" >
										</div>
									</div>
									 <div class="ln_solid"></div>
									<div class="panel-footer">									
										<div class="col-md-6 col-sm-6 offset-md-3">
											<button class="btn-success btn" type="submit" name="save">Submit</button>
											<button class="btn-danger btn" type="submit">Cancel</button>
										</div>
									</div>
									<hr>
								</div>
							</div>
						</div>
					</div>
                </div>
            </div>	
			</form>
          </div>
        </div>
        <!-- /page content -->

        <!-- footer content -->
         <?php require "footer.php";?>
        <!-- /footer content -->
      </div>
    </div>
   <?php require "foot_link.php";?>	
   
   
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
												
 <!-- Validation Script code -->												

<script src="js/jquery.nicescroll.js"></script>
<script src="js/scripts.js"></script>
<!-- Bootstrap Core JavaScript -->
   <script src="js/bootstrap.min.js"></script>
   <script>
	$(function() {
		$('#date_of_salary').datepicker();
		$( "#date_of_salary" ).on( "change", function() {
		  $( "#date_of_salary" ).datepicker( "option", "dateFormat", "dd-mm-yy" );
		});
	});
  </script>	
 
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
	
  <script>
	$('#faculty_id').change(function(){
		//alert($('#faculty_id').val());
	 $.getJSON(
	 'find_staff_details.php',
	 'sid='+$('#faculty_id').val(),
	 function(result){
		 //alert(result[0]);
		 $('#staff_salary').val(result);
	 /*$('#item').empty();
	 $.each(result.result, function(){
	 $('#item').append('<option>'+this['item']+'</option>');
	 });*/
	 }
	 );
	});
  </script>
  <script>
$(document).ready(function() {
    
    $("#staff_salary, #salary_deduction, #salary_bonus").on("keyup", function() { 
	    //alert("hii");
        sum();
    });
   
    
});

function sum() {
//alert("in function");
            var num1 = document.getElementById('staff_salary').value;
            var num2 = document.getElementById('salary_deduction').value;
            var num3 = document.getElementById('salary_bonus').value;
            var num4 = document.getElementById('salary_total').value;
			
            
	    if(num1=="")
	    {
	        var num1=0.00;
	    }
	    else if(num2=="")
	    {
	        var num2=0.00;
	    }
	    else if(num3=="")
	    {
	        var num3=0.00;
	    }
	    else
	    {
	       var result1 = parseInt(num1) - parseInt(num2) + parseInt(num3);
	    }
            if (!isNaN(result1)) 
			{
				document.getElementById('salary_total').value = result1;
            }
        }
</script>

  </body>
</html>
