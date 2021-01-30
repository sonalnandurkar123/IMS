

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

  $stmt_student =$conn->prepare("SELECT * FROM enquiry_details ORDER BY enquiry_id DESC ");
  $stmt_student->execute();
  $row_student=$stmt_student->fetchAll(PDO::FETCH_ASSOC);
  
  if(isset($_GET['id']))
  {   
    $id=$_GET['id'];  
    $stmt=$conn->prepare("DELETE FROM enquiry_details WHERE enquiry_id=$id"); 
    $executed=$stmt->execute();
    if($executed)
    //$stmt->execute();
    //if(execute)
        {

       //header("Location:enquiry_list");
        echo "<script>window.location.href='enquiry_list.php';</script>";
        }
  }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
     <?php require "head_link.php";?>
     <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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
                <h3>Enquiry Details</h3>
              </div>
            </div>

            <div class="clearfix"></div>

        <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_title">
            <a href="add_enquiry" class="btn btn-primary submit"  >Add Enquiry</a>
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
                    <table id="datatable-buttons" class="table table-bordered table-striped jambo_table bulk_action" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                      <th>Sr.No.</th>
                      <th>Name</th>
                      <th>Contact</th>
                      <th>Purpose of Visit</th>
                      <th>Class</th>
                      <th>Feedback</th>
                      <th>Enquiry Date</th>
                      <th>Action</th>
                    </tr>
                    </thead>
                   <tbody>
                  <?php
                  for($c=0;$c<count($row_student);$c++)
                  {
                   ?>
                    <tr>
                      <td><?php echo $c+1;?></td>
                      <td><?php echo $row_student[$c]['enquiry_name'];?></td>
                      <td><?php echo $row_student[$c]['enquiry_contact'];?></td>
                      <td><?php echo $row_student[$c]['enquiry_visit'];?></td>
                      <td><?php echo $row_student[$c]['enquiry_class'];?></td>
                      <td><?php echo $row_student[$c]['enquiry_feedback'];?></td>
                      <td><?php echo $row_student[$c]['enquiry_date']."-".$row_student[$c]['enquiry_month']."-" .$row_student[$c]['enquiry_year'];?></td>
                      <td>
                        <?php if($row_student[$c]['enquiry_status']=='active'){ ?>
                      <a href="stud_registration?id=<?php echo $row_student[$c]['enquiry_id']?>" data-toggle="tooltip" data-placement="bottom" title="Register"><i class="material-icons text-info">person_add</i></a>
                       <?php }else { echo "<b>Registered</b>"; }?>&nbsp;
                        <a href="enquiry_list?id=<?php echo $row_student[$c]['enquiry_id'];?>" data-toggle="tooltip" data-placement="bottom" title="Delete" onclick="return confirm('Are you sure you want to delete this?');"><i class="fa fa-trash-o fa-1x text-danger"></i></a>
                      </td>
                    </tr>
                  <?php
                  }
                  ?>
                   </tbody>
                  </table>
                </div>
              </div>
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
