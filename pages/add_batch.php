<?php
session_start();
require "conn.php";

date_default_timezone_set("Asia/Kolkata");
if(isset($_POST['submit']))
{
	extract($_POST);
try{
		$stmt = $conn->prepare("INSERT INTO `batch_details`(`course_details_id`,`batch_time`)VALUES(:course_details_id,:batch_time)");

		$executed=$stmt->execute(array(':course_details_id' => $course_details_id,':batch_time' => $batch_time));
			   if($executed)
			   {
				?>
				<script>
					alert("Added Successfully");
					window.location.href='batch_list.php';
				</script>
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
                <h3>Batch Details</h3>
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12 col-sm-12 ">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Add Batch Details</h2>
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
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Course Name<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                        <!--<input type="text" id="first-name" name="course_name" required="required" class="form-control">-->
                        
					     <select name="course_details_id" id="selector1"  required="required" class="form-control" onchange="get_batches_for_list(this.value);">
					      <option value="">-----Select Course-----</option>
						 <?php
						  $stmt=$conn->prepare("SELECT * FROM course_details");
						  $stmt->execute();
						  $row=$stmt->fetchAll(PDO::FETCH_ASSOC);
						  for($i=0;$i<count($row);$i++)
						   {
							?>
							<option value="<?php echo $row[$i]['course_details_id'];?>"><?php echo $row[$i]['course_name'];?></option>
							<?php
						   }
					     ?>
                      </select>
					  </div>
                      </div>
                      <div class="field item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="last-name">Batch Time<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6">
                          <input type="text" id="last-name" name="batch_time" required="required" class="form-control">
                        </div>
                      </div>
                      <div class="ln_solid"></div>
                      <div class="item form-group">
                        <div class="col-md-6 col-sm-6 offset-md-3">
                          <button type="submit" class="btn btn-success" name="submit">Submit</button>
                          <button class="btn-danger btn" href="javascript:history.back()"  type="button">Cancel</button>
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
