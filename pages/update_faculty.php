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
$faculty_id=$_GET['faculty_id'];
if(isset($_POST['submit']))
{
	extract($_POST);
	
	

    
try{
	//$faculty_joining_date=$join_date."-".$join_month."-".$join_year;
	    //echo $balance;exit;
	if(isset($date1))
	{
		
		 $stmt2=$conn->prepare("UPDATE `faculty_details` SET `faculty_name`=:faculty_name,`faculty_gender`=:faculty_gender,`faculty_age`=:faculty_age,`faculty_mobile`=:faculty_mobile,`emp_type`=:emp_type,`faculty_designation`=:faculty_designation,`faculty_date`=:faculty_date,`faculty_month`=:faculty_month,`faculty_year`=:faculty_year,`faculty_salary`=:faculty_salary,`faculty_address`=:faculty_address WHERE faculty_id=$faculty_id");
		
		
		
		$executed=$stmt2->execute(array(
		':faculty_id'=>$faculty_id));
	  }
	  else
	  {
		$stmt2=$conn->prepare("UPDATE `faculty_details` SET `faculty_name`=:faculty_name,`faculty_gender`=:faculty_gender,`faculty_age`=:faculty_age,`faculty_mobile`=:faculty_mobile,`emp_type`=:emp_type,`faculty_designation`=:faculty_designation,`faculty_date`=:faculty_date,`faculty_month`=:faculty_month,`faculty_year`=:faculty_year,`faculty_salary`=:faculty_salary,`faculty_address`=:faculty_address WHERE faculty_id=$faculty_id");
		
		$executed=$stmt2->execute(array(
		':faculty_name'=>$faculty_name,
		':faculty_gender'=>$faculty_gender,
		':faculty_age'=>$faculty_age,
		':faculty_mobile'=>$faculty_mobile,':emp_type'=>$emp_type,
		':faculty_designation'=>$faculty_designation,
		':faculty_date'=>$faculty_date,
		':faculty_month'=>$faculty_month,
		':faculty_year'=>$faculty_year,
		':faculty_salary'=>$faculty_salary,
		':faculty_address'=>$faculty_address));
	  }
	  
	   if($executed)
			   {
				
				
				
				?>
			<script type="text/javascript">
			alert("Updated Successfully");
			location="faculty_list";
			</script>


				<?php
				
			   }
	}catch(Exception $e) {
	  echo 'Message: ' .$e->getMessage();
	}
}


$stmt =$conn->prepare("SELECT * FROM faculty_details WHERE faculty_id=$faculty_id");
$stmt->execute();
$row=$stmt->fetchAll(PDO::FETCH_ASSOC);
if($row)
	{
		//print_r($row);exit;
		//extract($row[0]);
	}
	else
	{
		//echo "<script>alert('Aadhaar Number not found.');
		//window.location.href='select-ward';</script>";
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
                    <form action="" method="post" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" novalidate>

                      <div class="field item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Name <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                          <input type="text" id="first-name" name="faculty_name" required="required" class="form-control" value="<?php echo $row[0]['faculty_name'] ?>" onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)">
                        </div>
                      </div>
                      <div class="field item form-group">
                      <label class="col-form-label col-md-3 col-sm-3 label-align">Gender *:</label>
					  <div class="col-md-6 col-sm-6 ">
						<input type="radio" name="faculty_gender" id="genderM" value="M" <?php if(isset($row[0]['faculty_gender'])&& $row[0]['faculty_gender']=='M') {echo "checked";}?>>Male
						<input type="radio" name="faculty_gender" id="genderF" value="F" <?php if(isset($row[0]['faculty_gender'])&& $row[0]['faculty_gender']=='F') {echo "checked";}?>>Female
					  </div>
					 </div>				    
                      <div class="field item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align">Age<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                          <input type="text" id="birthday" class="date-picker form-control" name="faculty_age" required="required" type="text" value="<?php echo $row[0]['faculty_age'] ?>" maxlength="2" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;">
                        </div>
                      </div>
					   <div class="field item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="last-name">Mobile<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                          <input type="text" id="last-name" class="form-control" name="faculty_mobile" required="required"
						  value="<?php echo $row[0]['faculty_mobile'] ?>" maxlength="10" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;">
                        </div>
                      </div>
					  <div class="field item form-group">
						<label for="focusedinput" class="col-form-label col-md-3 col-sm-3 label-align">Employee Type<span class="required">*</span></label>
						<div class="col-md-6 col-sm-6 ">
						<select name="emp_type" class="form-control" required>
					    <option value="">-----Select-----</option>
						<option value="Faculty" <?php if($row[0]['emp_type']=='Faculty'){echo "selected";}?>>Faculty</option>
						<option value="Staff" <?php if($row[0]['emp_type']=='Staff'){echo "selected";}?>>staff</option>
						</select>
						</div>
					 </div>
					<div class="field item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="last-name">Designation<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                          <input type="text" id="last-name" class="form-control" name="faculty_designation" required="required" value="<?php echo $row[0]['faculty_designation'] ?>" onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)">
                        </div>
                      </div>

					  <div class="field item form-group">
					  
						    <label  class="col-form-label col-md-3 col-sm-3 label-align">Date<span class="required">*</span>
                            </label>
						    <div class="col-md-2 col-sm-2">
								<select name="faculty_date" class="form-control">
										<!--<option value="">Select Day</option>-->
										<?php
										  $stmt=$conn->prepare("SELECT * FROM date_details");
										  $stmt->execute();
										  $row1=$stmt->fetchAll(PDO::FETCH_ASSOC);
										  for($i=0;$i<count($row1);$i++)
									    {

										?>
									<option value="<?php echo $row1[$i]['date'];?>" <?php if($row1[$i]['date']==$row[0]['faculty_date']){echo "selected";} ?>><?php echo $row1[$i]['date'];?></option>
							            <?php
						                 }
					                    ?>
								</select>
							</div>
								
							<div class="col-md-2 col-sm-2">
								<select name="faculty_month" class="form-control">
									<!--<option value="">Select Month</option>-->
                                   <?php
									  $stmt=$conn->prepare("SELECT * FROM date_details WHERE `month`;");
									  $stmt->execute();
									  $row2=$stmt->fetchAll(PDO::FETCH_ASSOC);
									  for($i=0;$i<count($row2);$i++)
									{
									?>
									<option value="<?php echo $row2[$i]['month'];?>"<?php if ($row2[$i]['month']==$row[0]['faculty_month']) {echo "selected";}?>><?php echo $row2[$i]['month']?></option>

									<?php
									 }
									?>
								</select>
							</div>
								
							<div class="col-md-2 col-sm-2">
								<select name="faculty_year" class="form-control">
									<!--<option>Select Year</option>-->
									<?php
									  $stmt=$conn->prepare("SELECT * FROM date_details WHERE `year`");
									  $stmt->execute();
									  $row3=$stmt->fetchAll(PDO::FETCH_ASSOC);
									  for($i=0;$i<count($row3);$i++)

									{

                                      ?>
									<option value="<?php echo $row3[$i]['year'];?>"<?php if ($row3[$i]['year']==$row[0]['faculty_year']) {echo "selected";}?>><?php echo $row3[$i]['year']?></option>
									<?php
									 }
									?>
								</select>
							</div>
					    </div>
					  
					 <!--<div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="last-name">Joining Date<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                          <input type="text" id="last-name" class="form-control" name="faculty_joining_date" required="required" value="</*?php echo $row[0]['faculty_joining_date']?*/>">
                        </div>
                      </div>-->
					  <?php
                        $stmt =$conn->prepare("SELECT * FROM faculty_details WHERE faculty_id=$faculty_id");
                        $stmt->execute();
                        $row=$stmt->fetchAll (PDO::FETCH_ASSOC)
                       ?>
					  <div class="field item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="last-name">Salary<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                          <input type="text" id="last-name" class="form-control" name="faculty_salary" required="required" value="<?php echo $row[0]['faculty_salary'] ?>" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;">
                        </div>
                      </div>
					   <div class="field item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="last-name">Address<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                          <input type="text" id="last-name" class="form-control" name="faculty_address" required="required"
						  value="<?php echo $row[0]['faculty_address'] ?>">
                        </div>
                      </div>
                      <div class="ln_solid"></div>
                      <div class="item form-group">
                        <div class="col-md-6 col-sm-6 offset-md-3">
						  <button type="submit" class="btn btn-success" name="submit">Update</button>
                          <button class="btn-danger btn" href="javascript:history.back()" type="button">Cancel</button>
						  <button class="btn btn-primary" type="reset">Reset</button>
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
  </body>
</html>
