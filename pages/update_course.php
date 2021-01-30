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
$course_details_id=$_GET['id'];
if(isset($_POST['submit']))
{
	extract($_POST);
	
	

    
try{
		 $stmt2=$conn->prepare("UPDATE `course_details` SET 
		`course_name`=:course_name,
		`course_fees`=:course_fees
		WHERE course_details_id=$course_details_id");
		$executed=$stmt2->execute(array(
		':course_name'=>$course_name,
		':course_fees'=>$course_fees));
	   if($executed)
		{
			?>
			<script type="text/javascript">
			alert("Updated Successfully");
			window.location.href="course_list";
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
                <h3>Course Details</h3>
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12 col-sm-12 ">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Update Course</h2>
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
                    <form action="" method="post" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
							<?php
							
							    
$stmt =$conn->prepare("SELECT * FROM course_details WHERE course_details_id=$course_details_id");
$stmt->execute();
$row=$stmt->fetchAll(PDO::FETCH_ASSOC);
							?>
                     
					  
					 <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Course Name<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                          <input type="text" name="course_name" required="required" class="form-control" value="<?php echo $row[0]['course_name']; ?>" >
                        </div>
                      </div>
					   <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="last-name">Course Fees<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                          <input type="number" min="100" step="100" id="last-name" name="course_fees" required="required" class="form-control"  value="<?php echo $row[0]['course_fees']; ?>" >
                        </div>
                      </div>
									
                      <div class="ln_solid"></div>
                      <div class="item form-group">
                        <div class="col-md-6 col-sm-6 offset-md-3">
						  <button type="submit" class="btn btn-success" name="submit">Update</button>
                          <button class="btn-danger btn" href="javascript:history.back()" type="button">Cancel</button>
						<!-- <button class="btn btn-primary" type="reset">Reset</button> -->
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
  </body>
</html>
