<?php
session_start();
require "conn.php";

date_default_timezone_set("Asia/Kolkata");
if(isset($_POST['submit']))
{
	extract($_POST);
	//print_r($_POST);exit;
	
	
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
	
		$stmt = $conn->prepare("INSERT INTO `enquiry_details`(`enquiry_name`,`enquiry_contact`,`enquiry_visit`,`enquiry_class`,`enquiry_feedback`,`enquiry_date`,`enquiry_month`,`enquiry_year`,`enquiry_status`)VALUES(:enquiry_name,:enquiry_contact,:enquiry_visit,:enquiry_class,:enquiry_feedback,:enquiry_date,:enquiry_month,:enquiry_year,:enquiry_status)");

		$executed=$stmt->execute(array(':enquiry_name' => $enquiry_name,':enquiry_contact' => $enquiry_contact,':enquiry_visit' => $enquiry_visit,':enquiry_class' => $enquiry_class,':enquiry_feedback' => $enquiry_feedback,'enquiry_date' => $date,'enquiry_month' => $month, 'enquiry_year'=> $year,':enquiry_status' => 'active'));
			   if($executed)
			   {
				?>
				<script>alert("Added Successfully");
				window.location.href='enquiry_list.php';</script>
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
                <h3>Enquire Details</h3>
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12 col-sm-12 ">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Add Enquire Details</h2>
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
                    <form action="" name="f" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" novalidate>
					 <div class="field item form-group">
						    <label  class="col-form-label col-md-3 col-sm-3 label-align">Date<span class="required">*</span>
                            </label>
						    <div class="col-md-2 col-sm-2">
								<select name="date" class="form-control">
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
								<select name="month" class="form-control">
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
								<select name="year" class="form-control">
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
							 
                      <!--<div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Date<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                          <input type="date" id="first-name" name="enquiry_date" required="required" class="form-control ">
                        </div>
                      </div>-->
                      <div class="field item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="last-name">Name<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                          <input type="text" id="last-name" name="enquiry_name" required="required" class="form-control" > 
						</div>
                      </div>
					  <div class="field item form-group">
                        <label for="middle-name" class="col-form-label col-md-3 col-sm-3 label-align">Contact</label>
                        <div class="col-md-6 col-sm-6 ">
                          <input id="middle-name" required="required" class="form-control" type="text" name="enquiry_contact" maxlength="10" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;">
                        
						 <!-- Integer number Validation -->
					   
	                        <span id="error" style="color: Red; display: none; padding-right:10px; font-size:17px;">Enter only Mobile number</span>
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
                        <label class="col-form-label col-md-3 col-sm-3 label-align">Purpose Of Visit<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                          <input id="birthday" class="date-picker form-control" name="enquiry_visit" required="required" type="text" >
                        </div>
                      </div>
					  <div class="field item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align">Class<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                         <input id="birthday" class="date-picker form-control" name="enquiry_class" required="required" type="text" >
                        </div>
                      </div>
					  <div class="field item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align">Feedback<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                        <input id="birthday" class="date-picker form-control" name="enquiry_feedback" required="required" type="text" >
                        </div>
                      </div>
                      <div class="ln_solid"></div>
                      <div class="item form-group">
                        <div class="col-md-6 col-sm-6 offset-md-3">
						 <button type="submit" name="submit" class="btn btn-success">Submit</button>
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


        <!-- footer content -->
         <?php require "footer.php";?>
        <!-- /footer content -->
      </div>
    </div>
   <?php require "foot_link.php";?>	
  </body>
</html>
