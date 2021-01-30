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
$batch_details_id=$_GET['id'];
if(isset($_POST['submit']))
{
	extract($_POST);
	
	

    
try{
		
		 $stmt2=$conn->prepare("UPDATE `batch_details` SET 
		`course_details_id`=:course_details_id,
		`batch_time`=:batch_time
		WHERE batch_details_id=$batch_details_id");
		
		$executed=$stmt2->execute(array(
		':course_details_id'=>$course_details_id,
		':batch_time'=>$batch_time
		));
	   if($executed)
			   {
				?>
			<script type="text/javascript">
			alert("Updated Successfully");
			location="batch_list";
			</script>


				<?php
				
			   }
	}catch(Exception $e) {
	  echo 'Message: ' .$e->getMessage();
	}
}


$stmt_batch =$conn->prepare("SELECT * FROM batch_details WHERE batch_details_id=$batch_details_id");
$stmt_batch->execute();
$row_batch=$stmt_batch->fetchAll(PDO::FETCH_ASSOC);
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
                    <h2>Update Batch</h2>
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

                      <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Course Name<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                        <!--<input type="text" id="first-name" name="course_name" required="required" class="form-control">-->
                        
					     <select name="course_details_id" id="selector1"  required="required" class="form-control" onchange="get_batches_for_list(this.value);">
					      <!--<option value="">-----Select Course-----</option>-->
                             <?php
						    	$stmt=$conn->prepare("SELECT * FROM course_details");
								$stmt->execute();
								$row=$stmt->fetchAll(PDO::FETCH_ASSOC);
								for($i=0;$i<count($row);$i++)
								{
									$oid=$row[$i]['course_details_id'];
								?>
								<option value="<?php echo $row[$i]['course_details_id'];?>" <?php if($row[$i]['course_details_id']==$row_batch[0]['course_details_id']){echo "selected";} ?>><?php echo $row[$i]['course_name'];?></option>
								<?php
								}	
							?>
                      </select>
					  </div>
                      </div>
					  <?php
						$stmt =$conn->prepare("SELECT * FROM batch_details WHERE batch_details_id=$batch_details_id");
						$stmt->execute();
						$row=$stmt->fetchAll(PDO::FETCH_ASSOC);
				    	?>
                      <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="last-name">Batch Time<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6">
                         <input type="text" id="last-name" name="batch_time" required="required" class="form-control" value="<?php echo $row[0]['batch_time'];?>">
                        </div>
                      </div>
                      <div class="ln_solid"></div>
                      <div class="item form-group">
                        <div class="col-md-6 col-sm-6 offset-md-3">
                          <button type="submit" class="btn btn-success" name="submit">Update</button>
                          <button class="btn-danger btn" href="javascript:history.back()"  type="button">Cancel</button>
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
