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
//insert
if(isset($_POST['submit']))
{
	extract($_POST);
try{
		$stmt = $conn->prepare("INSERT INTO `company_details`(`company_name`,`institue_for`,`mobile`,`alt_mobile`,`address`)VALUES(:company_name,:institue_for,:mobile,:alt_mobile,:address)");

		$executed=$stmt->execute(array(':company_name' => $company_name,':institue_for' => $institue_for,':mobile' => $mobile,':alt_mobile' => $alt_mobile,':address' => $address));
			   if($executed)
			   {
				?>
				<script>alert("Added Successfully");
				window.location.href='institute_details';</script>
				<?php
			   }
	}catch(Exception $e) {
	  echo 'Message: ' .$e->getMessage();
	}
}
//select
$stmt_select=$conn->prepare("SELECT * FROM company_details");
$stmt_select->execute();
$row_select=$stmt_select->fetchAll(PDO::FETCH_ASSOC);
//update
if(isset($_POST['update']))
{
	extract($_POST);  
try{
		 $stmt2=$conn->prepare("UPDATE `company_details` SET `company_name`=:company_name,`institue_for`=:institue_for,`mobile`=:mobile,`alt_mobile`=:alt_mobile,`address`=:address
		 WHERE company_details_id=1");
		$executed=$stmt2->execute(array(
		':company_name'=>$company_name,':institue_for'=>$institue_for,
		':mobile'=>$mobile,
		':alt_mobile'=>$alt_mobile,
		':address'=>$address));
	   if($executed)
		{
			?>
			<script type="text/javascript">
			alert("Updated Successfully");
			window.location.href="institute_details";
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
                <h3>Institute Details</h3>
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12 col-sm-12 ">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Add Institute Details</h2>
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
                   <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" action="" method="post" novalidate>

                      <!--update form-->
					   <?php if($row_select){ ?>
					  <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Institute Name <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                          <input type="text" id="first-name" name="company_name" value="<?php echo $row_select[0]['company_name'];?>" class="form-control" required="required" >
                        </div>
                      </div>
                      <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="last-name">Institute Tagline<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                          <input type="text" id="last-name" name="institue_for" value="<?php echo $row_select[0]['institue_for'];?>"  class="form-control" required="required" >
                        </div>
                      </div>
                      <div class="item form-group">
                        <label for="middle-name" class="col-form-label col-md-3 col-sm-3 label-align">Mobile No</label>
                        <div class="col-md-6 col-sm-6 ">
                          <input id="middle-name" class="form-control" type="text" name="mobile" value="<?php echo $row_select[0]['mobile'];?>" required="required" >
                        </div>
                      </div>
                      <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align">Alternate Mobile No<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                          <input id="birthday" name="alt_mobile" class="date-picker form-control" value="<?php echo $row_select[0]['alt_mobile'];?>" type="text">
                        </div>
                      </div>
					  <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align">Address<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                          <input id="birthday" name="address" class="date-picker form-control" value="<?php echo $row_select[0]['address'];?>" type="text" required="required">
                        </div>
                      </div>
                      <div class="ln_solid"></div>
                      <div class="item form-group">
                        <div class="col-md-6 col-sm-6 offset-md-3">
						<!-- <button class="btn btn-info" type="reset">Reset</button> -->
						
						 <button type="submit" class="btn btn-success" name="update">Update</button>
			
                          <button class="btn btn-primary" href="javascript:history.back()" type="button">Cancel</button>
						   
						 <!-- <button class="btn btn-primary" type="reset">Reset</button>-->
                        </div>
                      </div>
					   <?php } else{ ?>
                      <div class="field item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Institute Name <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                          <input type="text" id="first-name" name="company_name"  class="form-control" required="required" >
                        </div>
                      </div>
                      <div class="field item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="last-name">Institute Tagline<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                          <input type="text" id="last-name" name="institue_for" class="form-control" required="required" >
                        </div>
                      </div>
                      <div class="field item form-group">
                        <label for="middle-name" class="col-form-label col-md-3 col-sm-3 label-align">Mobile No</label>
                        <div class="col-md-6 col-sm-6 ">
                          <input id="middle-name" class="form-control" type="text" name="mobile" required="required" >
                        </div>
                      </div>
                      <div class="field item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align">Alternate Mobile No<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                          <input id="birthday" name="alt_mobile" class="date-picker form-control" type="text">
                        </div>
                      </div>
					   <div class="field item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align">Address<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                          <input id="birthday" name="address" class="date-picker form-control" type="text" required="required">
                        </div>
                      </div>
                      <div class="ln_solid"></div>
                      <div class="field item form-group">
                        <div class="col-md-6 col-sm-6 offset-md-3">
						<button class="btn btn-info" type="reset">Reset</button>
						
						<button type="submit" class="btn btn-success" name="submit">submit</button>
						
                        <button class="btn btn-primary" href="javascript:history.back()" type="button">Cancel</button>
						   
						 <!-- <button class="btn btn-primary" type="reset">Reset</button>-->
                        </div>
                      </div>
					  <?php }?>
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
