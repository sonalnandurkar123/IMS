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
date_default_timezone_set("Asia/Kolkata");
if(isset($_POST['submit']))
{
	extract($_POST);
try{
		$stmt = $conn->prepare("INSERT INTO `course_details`(`course_name`,`course_fees`)VALUES(:course_name,:course_fees)");

		$executed=$stmt->execute(array(':course_name' => $course_name,':course_fees' => $course_fees));
			   if($executed)
			   {
				?>
				<script>alert("Added Successfully");
				window.location.href='course_list.php';</script>
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
                <h3>Course Details</h3>
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12 col-sm-12 ">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Add Course Details</h2>
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
                  
					<form class="" action="" method="post" novalidate>
                      <div class="field item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Course Name<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                          <input type="text" id="first-name" name="course_name" required="required" class="form-control " onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)">
                        </div>
                      </div>
                      <div class="field item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="last-name">Course Fees<span class="required">*<i class="fa fa-inr fa-1x"></i></span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                          <input type="number" min="100" step="100" id="last-name" name="course_fees" required="required" class="form-control" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;">
                       
					   
					               <!-- Integer number Validation -->
					   
					                            <span id="error" style="color: Red; display: none; padding-right:10px;">Enter only fees in integer</span>
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
