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
	//print_r($_POST);exit;
	/*$faculty_name = $_POST['faculty_name'];
	$faculty_gender = $_POST['faculty_gender'];
	$faculty_age = $_POST['faculty_age'];
	$faculty_mobile = $_POST['faculty_mobile'];
	$faculty_designation = $_POST['faculty_designation'];
	$faculty_joining_date = $_POST['faculty_joining_date'];
	$faculty_salary = $_POST['faculty_salary'];
	$faculty_address = $_POST['faculty_address'];*/
	
	//echo $bdate;
	//echo $adate;exit;

    
try{
	/*$stmt_check =$conn->prepare("SELECT * FROM upcoming_projects WHERE");
	$stmt_check->execute();
	$row_check=$stmt_check->fetchAll(PDO::FETCH_ASSOC);
	if($row_check)
	{
		echo "Aadhaar Number already exist";exit;
	}*/
		//$faculty_joining_date=$join_date."-".$join_month."-".$join_year;
		$stmt = $conn->prepare("INSERT INTO `faculty_details`(`faculty_name`,`faculty_gender`,`faculty_age`,`faculty_mobile`,`emp_type`,`faculty_designation`,`faculty_date`,`faculty_month`,`faculty_year`,`faculty_salary`,`faculty_address`)VALUES(:faculty_name,:faculty_gender,:faculty_age,:faculty_mobile,:emp_type,:faculty_designation,:faculty_date,:faculty_month,:faculty_year,:faculty_salary,:faculty_address)");

		$executed=$stmt->execute(array(':faculty_name' => $faculty_name,':faculty_gender' => $faculty_gender,':faculty_age' => $faculty_age,':faculty_mobile' => $faculty_mobile,':emp_type' => $emp_type,':faculty_designation' => $faculty_designation,'faculty_date' => $date,'faculty_month' => $month, 'faculty_year'=> $year,':faculty_salary' => $faculty_salary,':faculty_address' => $faculty_address));
			   if($executed)
			   {
				?>
				<script>alert("Added Successfully");
				window.location.href='faculty_list';</script>
				<?php
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

            <div class="row">
              <div class="col-md-12 col-sm-12 ">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Add Employee Details</h2>
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
                    <form action="" method="post" id="demo-form2"  class="form-horizontal form-label-left" novalidate>

                      <div class="field item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Name <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                          <input type="text" id="first-name" name="faculty_name" required="required" class="form-control " >
                        </div>
                      </div>
					  <div class="field item form-group">
                      <label class="col-form-label col-md-3 col-sm-3 label-align">Gender *:</label>
					  <div class="col-md-6 col-sm-6 ">
                      <p>
                        M:
                        <input type="radio" class="flat" name="faculty_gender" id="genderM" value="M" checked="" required />
						F:
                        <input type="radio" class="flat" name="faculty_gender" id="genderF" value="F" />
                      </p>
					  </div>
					 </div>
                      <div class="field item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align">Age<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                          <input type="text" id="birthday" class="date-picker form-control" name="faculty_age" required="required" type="text" maxlength="2" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;">
                        </div>
					  </div>
					   <div class="field item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="last-name">Mobile<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                          <input type="text" id="last-name" class="form-control" name="faculty_mobile" required="required" maxlength="10" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;">
                        </div>
					  </div>
					  <div class="field item form-group">
						<label for="focusedinput" class="col-form-label col-md-3 col-sm-3 label-align">Employee Type<span class="required">*</span></label>
						<div class="col-md-6 col-sm-6 ">
						<select name="emp_type" class="form-control" required>
					    <option value="">-----Select-----</option>
						<option value="Faculty">Faculty</option>
						<option value="Staff">Staff</option>
						</select>
						</div>
					 </div>
					<div class="field item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="last-name">Designation<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                          <input type="text" id="last-name" class="form-control" name="faculty_designation" required="required" >
                        </div>
                      </div>
					  <!--<div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="last-name">Joining Date<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                          <input type="date" id="last-name" class="form-control" name="faculty_joining_date" required="required">
                        </div>
                      </div>-->
					  
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
									<option>YYYY</option>
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
							 
					  <div class="field item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="last-name">Salary<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                          <input type="text" id="last-name" class="form-control" name="faculty_salary" required="required" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;">
                        </div>
                      </div>
					   <div class="field item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="last-name">Address<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                          <input type="text" id="last-name" class="form-control" name="faculty_address" required="required">
                        </div>
                      </div>
                      <div class="ln_solid"></div>
                      <div class="item form-group">
                        <div class="col-md-6 col-sm-6 offset-md-3">
						  <button type="submit" class="btn btn-success" name="submit">Submit</button>
                          <button class="btn-danger btn" href="javascript:history.back()" type="button">Cancel</button>
						  <!--<button class="btn btn-primary" type="reset">Reset</button>-->
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

        <!-- footer content -->
         <?php require "footer.php";?>
        <!-- /footer content -->
      </div>
    </div>
   <?php require "foot_link.php";?>	
   
		<!-- Integer number validation -->
						 <span id="error" ></span>
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

  <!-- jQuery -->
  <script src="../vendors/jquery/dist/jquery.min.js"></script>
  <!-- Bootstrap -->
  <script src="../vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <!-- FastClick -->
  <script src="../vendors/fastclick/lib/fastclick.js"></script>
  <!-- NProgress -->
  <script src="../vendors/nprogress/nprogress.js"></script>
  <!-- validator -->
  <!-- <script src="../vendors/validator/validator.js"></script> -->

  <!-- Custom Theme Scripts -->
  <script src="../build/js/custom.min.js"></script>

   
  </body>
</html>
