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
try{
		//$dt=date('Y-m-d');
		$stmt = $conn->prepare("INSERT INTO `expense_details`(`expense_name`,`expense_detail`,`expense_approve`,`expense_amount`,`expense_date`,`expense_month`,`expense_year`)VALUES(:expense_name,:expense_detail,:expense_approve,:expense_amount,:expense_date,:expense_month,:expense_year)");

		$executed=$stmt->execute(array(':expense_name' => $expense_name,':expense_detail' => $expense_detail,':expense_approve' => $expense_approve,':expense_amount' => $expense_amount,'expense_date' => $date,'expense_month' => $month,'expense_year'=> $year));
					   if($executed)
			   {
				 $expense_id = $conn->lastInsertId();
			//get previous balance
			$stmt=$conn->prepare("SELECT * FROM daily_transaction ORDER BY daily_transaction_id DESC");
			$stmt->execute();
			$row=$stmt->fetchAll(PDO::FETCH_ASSOC);
			if($row)
			{
				$old_amount=$row[0]['total_balance'];
				$new_amount=$old_amount-$expense_amount;
				//insert data in daily transaction
				$date_of_transaction=date('Y-m-d',strtotime($date."-".$month."-".$year))." ".date('H:i:s');
		
				$stmt_tran = $conn->prepare("INSERT INTO daily_transaction(student_id,income_or_expense,amount,total_balance,date_of_transaction,daily_date)VALUES(:student_id,:income_or_expense,:amount,:total_balance,:date_of_transaction,:daily_date)");
				$executed_tran=$stmt_tran->execute(array(':student_id' => $expense_id,':income_or_expense' => "Expense",':amount' => $expense_amount,':total_balance' => $new_amount,':date_of_transaction' => $date_of_transaction,':daily_date' => date('d-m-Y')));
				if($executed_tran)
				{
				?>
					<script>alert("Added Successfully");</script>
				<?php
					echo "<script>window.location.href='expense_list';</script>";
				}

			}
			else
			{
				//$old_amount=$row[0]['total_balance'];
				//$new_amount=$old_amount-$row[0]['total_balance'];
				//insert data in daily transaction
				$stmt_tran = $conn->prepare("INSERT INTO daily_transaction(student_id,income_or_expense,amount,total_balance,date_of_transaction,daily_date)VALUES(:student_id,:income_or_expense,:amount,:total_balance,:date_of_transaction,:daily_date)");
				$executed_tran=$stmt_tran->execute(array(':student_id' => $expense_id,':income_or_expense' => "Expense",':amount' => $expense_amount,':total_balance' => -$expense_amount,':date_of_transaction' => $date."-".$month."-".$year,':daily_date' => date('d-m-Y')));
				if($executed_tran)
				{
				?>
					<script>alert("Added Successfully");</script>
				<?php
				     echo "<script>window.location.href='expense_list';</script>";
				}

			}
			   }
	}catch(Exception $e) {
	  echo 'Message: ' .$e->getMessage();
	}
}
		
			/* $expense_id = $conn->lastInsertId();
			//get previous balance
			$stmt=$conn->prepare("SELECT * FROM daily_transaction ORDER BY daily_transaction_id DESC");
			$stmt->execute();
			$row=$stmt->fetchAll(PDO::FETCH_ASSOC);
			if($row)
			{
				$old_amount=$row[0]['total_balance'];
				$new_amount=$old_amount-$expense_amount;
				//insert data in daily transaction
				$date_of_transaction=date('Y-m-d',strtotime($date_of_entry))." ".date('H:i:s');
				$stmt_tran = $conn->prepare("INSERT INTO daily_transaction(student_id,income_or_expense,amount,total_balance,date_of_transaction,daily_date)VALUES(:student_id,:income_or_expense,:amount,:total_balance,:date_of_transaction,:daily_date)");
				$executed_tran=$stmt_tran->execute(array(':student_id' => $expense_id,':income_or_expense' => "Expense",':amount' => $expense_amount,':total_balance' => $new_amount,':date_of_transaction' => $date_of_transaction,':daily_date' => date('d-m-Y')));
				if($executed_tran)
				{
				?>
					<script>alert("Added Successfully");</script>
				<?php
					echo "<script>window.location.href='expense_list';</script>";
				}

			}
			else
			{
				//$old_amount=$row[0]['total_balance'];
				//$new_amount=$old_amount-$row[0]['total_balance'];
				//insert data in daily transaction
				$stmt_tran = $conn->prepare("INSERT INTO daily_transaction(student_id,income_or_expense,amount,total_balance,date_of_transaction,daily_date)VALUES(:student_id,:income_or_expense,:amount,:total_balance,:date_of_transaction,:daily_date)");
				$executed_tran=$stmt_tran->execute(array(':student_id' => $expense_id,':income_or_expense' => "Expense",':amount' => $expense_amount,':total_balance' => -$expense_amount,':date_of_transaction' => date('Y-m-d H:i:s'),':daily_date' => date('d-m-Y')));
				if($executed_tran)
				{
				?>
					<script>alert("Added Successfully");</script>
				<?php
				     echo "<script>window.location.href='expense_list';</script>";
				}

			}
		}
	}catch(Exception $e) {
	  echo 'Message: ' .$e->getMessage();
	}
}
*/

?>
<!DOCTYPE html>
<html lang="en">
  <head>
     <title> Add Expences </title>
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
                <h3>Expense Details</h3>
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12 col-sm-12 ">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Add Expense Details</h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br/>
					<!--form start -->
                    <form action="" method="post" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" novalidate>

                      <div class="field item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Expense Name <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                          <input type="text" id="first-name" name="expense_name" required="required" class="form-control " onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)">
                        </div>
                      </div>
                      <div class="field item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="last-name">Expense Detail<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                          <input type="text" id="last-name" name="expense_detail" required="required" class="form-control" onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)">
                        </div>
                      </div>
                      <div class="field item form-group">
                        <label for="middle-name" class="col-form-label col-md-3 col-sm-3 label-align">Approval By</label>
                        <div class="col-md-6 col-sm-6 ">
                          <input id="middle-name" class="form-control" type="text" name="expense_approve" required="required" onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)">
                        </div>
                      </div>
                      <div class="field item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align">Expense Amount<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                          <input id="birthday" name="expense_amount" class="date-picker form-control" required="required" type="text" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;">
                        
						          <!-- iNTEGER NUMBER VALIDATION -->
					                            <span id="error" style="color: Red; display: none; padding-right:10px; font-size:20px;">Enter only Expense Amount</span>
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
						</div>
                      </div>
					  <div class="field item form-group">
						    <label  class="col-form-label col-md-3 col-sm-3 label-align">Date<span class="required">*</span>
                            </label>
						    <div class="col-md-2 col-sm-2">
								<select name="date" class="form-control" required="required">
										<option value="">DD</option>
										<?php
										  $stmt=$conn->prepare("SELECT * FROM date_details");
										  $stmt->execute();
										  $row=$stmt->fetchAll(PDO::FETCH_ASSOC);
										  for($i=0;$i<count($row);$i++)
									    {
										?>
										<option value="<?php echo $row[$i]['date'];?>"><?php echo $row[$i]['date'];?></option>
							            <?php
						                 }
					                    ?>
								</select>
							</div>
								
							<div class="col-md-2 col-sm-2">
								<select name="month" class="form-control" required="required">
									<option value="">MM</option>
									<?php
									  $stmt=$conn->prepare("SELECT * FROM date_details WHERE `month`;");
									  $stmt->execute();
									  $row1=$stmt->fetchAll(PDO::FETCH_ASSOC);
									  for($i=0;$i<count($row1);$i++)
									{
									?>
									<option value="<?php echo $row1[$i]['month'];?>"><?php echo $row1[$i]['month'];?></option>
									<?php
									 }
									?>
								</select>
							</div>
								
							<div class="col-md-2 col-sm-2">
								<select name="year" class="form-control" required="required">
									<option>YY</option>
									<?php
									  $stmt=$conn->prepare("SELECT * FROM date_details WHERE `year`");
									  $stmt->execute();
									  $row2=$stmt->fetchAll(PDO::FETCH_ASSOC);
									  for($i=0;$i<count($row2);$i++)
									{

                                      ?>
									<option value="<?php echo $row2[$i]['year'];?>"><?php echo $row2[$i]['year'];?></option>
									<?php
									 }
									?>
									
								</select>
							</div>
					    </div>
							 
					  <!--<div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align">Date<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                          <input id="birthday" name="date_of_entry" class="date-picker form-control" required="required" type="date">
                        </div>
                      </div>-->
                      <div class="ln_solid"></div>
                      <div class="item form-group">
                        <div class="col-md-6 col-sm-6 offset-md-3">
						  <button type="submit" class="btn btn-success" name="submit">Submit</button>
                          <button class="btn-danger btn" href="javascript:history.back()" type="button">Cancel</button>
						 <!-- <button class="btn btn-primary" type="reset">Reset</button>-->                          
                        </div>
                      </div>

                    </form>
					<!--form end -->
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
  </body>
</html>
