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

	$stmt= $conn->prepare("SELECT * FROM student_details");
	$stmt->execute();
	$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
								
	$stmt1= $conn->prepare("SELECT * FROM student_details Where enquiry_id='-' ");
	$stmt1->execute();
	$row1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);
					
		$direct_admission = count($row1);
		$user3=count($row);
		$enquiry_admission = $user3 - $direct_admission;
		
		$direct_admission= $direct_admission*100/$user3;
		$enquiry_admission= $enquiry_admission*100/$user3;
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
		
	     <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12">
                <div class="">
                  <div class="x_content">
                    <div class="row">
					
                       <div class="animated flipInY col-lg-3 col-md-3 col-sm-6">
					    <a href="enquiry_list.php">
	                        <div class="tile-stats">
	                          <div class="icon" ><i class="fa fa-users fa-1g" style="color:green;"></i>
	                          </div>
	                          <div class="count" style="font-size:35px;">
							   <?php
							   $num = $conn->query("select count(*) from enquiry_details")->fetchColumn();
							   echo  $num;
							   ?>
							  </div>
	                          <h3>Total</br>Enquiry</h3>
	                          <p></p>
	                        </div>
							</a>
                      	</div>
						
					   <div class="animated flipInY col-lg-3 col-md-3 col-sm-6">
					     <a href="student_list.php">
                        <div class="tile-stats">
                          <div class="icon"><i class="fa fa-users fa-1g"  style="color:green;"></i>
                          </div>
                          <div class="count" style="font-size:35px;">
						   <?php
						   $num5 = $conn->query("select count(*) from  student_details")->fetchColumn();
						   echo  $num5;
						  ?>
						  </div>
                          <h3>Total</br>Students</h3>
                          <p></p>
                        </div>
						</a>
						</div>
						
						 <div class="animated flipInY col-lg-3 col-md-3 col-sm-6">
                          <div class="tile-stats">
                          <div class="icon"><i class="fa fa-inr fa-1g" style="color:blue;"></i>
                          </div>
                          <div class="count" style="font-size:35px;">
						   <?php 
							$num1 = $conn->query("select sum(course_fees) from  student_details")->fetchColumn();
							if(empty($num1))
							{ echo 0; }
							else
							{echo  $num1;}
						    ?>
						  </div>
                          <h3>Total</br>Fees</h3>
                          <p></p>
                          </div>
						</div>
						
						 <div class="animated flipInY col-lg-3 col-md-3 col-sm-6">
                        <div class="tile-stats">
                          <div class="icon" ><i class="fa fa-inr fa-1g" style="color:yellow;"></i>
                          </div>
                          <div class="count" style="font-size:35px;">
						   <?php 
							$num2 = $conn->query("select sum(paid_amount) from  student_details")->fetchColumn();
							//$num3 = $conn->query("select sum(pay_amount) from  payment_details")->fetchColumn();
							//echo  $num2 + $num3;
							
							if(empty($num2))
							{ echo 0; }
							else
							{echo  $num2;}
                            ?>
						  </div>
                          <h3>Paid</br>Fees</h3>
                          <p></p>
                        </div>
                      </div>
					</div>
                  </div>
				</div>
              </div>
            </div>
			
			<div class="row">
              <div class="col-md-12">
                <div class="">
                  <div class="x_content">
                    <div class="row">
					
                       <div class="animated flipInY col-lg-3 col-md-3 col-sm-6">
	                        <div class="tile-stats">
	                          <div class="icon" ><i class="fa fa-inr fa-1g" style="color:red;"></i>
	                          </div>
	                          <div class="count" style="font-size:35px;">
							    <?php 
									$num7 = $conn->query("select sum(balance_amount) from  student_details")->fetchColumn();
									//$num3 = $conn->query("select sum(pay_amount) from  payment_details")->fetchColumn();
									//echo  $num2 + $num3;
									
									if(empty($num7))
									{ echo 0; }
									else
									{echo  $num7;}
								?>
							  </div>
	                          <h3>Balance</br>fees</h3>
	                          <p></p>
	                        </div>
                      	</div>
						
					   <div class="animated flipInY col-lg-3 col-md-3 col-sm-6">
					      <a href="present_student_list.php">
                        <div class="tile-stats">
                          <div class="icon"><i class="fa fa-users fa-1g"  style="color:blue;"></i>
                          </div>
                          <div class="count" style="font-size:35px;">
						  <?php
								//get details of what classes were conducted today
								$stmt_att_entry = $conn->prepare("SELECT * FROM `attendance_entry` WHERE attendance_date='".date('Y-m-d')."'");
								$stmt_att_entry->execute();
								$row_att_entry = $stmt_att_entry->fetchAll(PDO::FETCH_ASSOC);
								$final_total_present=0;//initilising variable
								//loop through all classes
								for($ae=0;$ae<count($row_att_entry);$ae++)
								{
									//get attendance of individual classes
									$stmt_att_details = $conn->prepare("SELECT COUNT(attendance_entry_id) AS total_present FROM `attendance	_details` WHERE attendance_entry_id=".$row_att_entry[$ae]['attendance_entry_id']." AND present_or_absent='P'");
									$stmt_att_details->execute();
									$row_att_details = $stmt_att_details->fetchAll(PDO::FETCH_ASSOC);
									$final_total_present=$final_total_present+$row_att_details[0]['total_present'];
								}
								echo $final_total_present;
					    	?>	
						  </div>
                          <h3>Total</br>Present</h3>
                          <p></p>
                        </div>
						</a>
						</div>
						
						 <div class="animated flipInY col-lg-3 col-md-3 col-sm-6">
						  <a href="absent_student_list.php">
                          <div class="tile-stats">
                          <div class="icon"><i class="fa fa-users fa-1g" style="color:red;"></i>
                          </div>
                          <div class="count" style="font-size:35px;">
						   <?php
								$final_total_absent=0;//initilising variable
								//loop through all classes
								for($ae=0;$ae<count($row_att_entry);$ae++)
								{
									//get attendance of individual classes
									$stmt_att_details = $conn->prepare("SELECT COUNT(attendance_entry_id) AS total_absent FROM `attendance_details` WHERE attendance_entry_id=".$row_att_entry[$ae]['attendance_entry_id']." AND present_or_absent='A'");
									$stmt_att_details->execute();
									$row_att_details = $stmt_att_details->fetchAll(PDO::FETCH_ASSOC);
									$final_total_absent=$final_total_absent+$row_att_details[0]['total_absent'];
								}
								echo $final_total_absent;
						   ?>	
						  </div>
                          <h3>Total</br>Absent</h3>
                          <p></p>
                          </div>
						  </a>
						</div>
						
						 <div class="animated flipInY col-lg-3 col-md-3 col-sm-6">
						 <a href="birthday_list.php">	
                        <div class="tile-stats">
                          <div class="icon" ><i class="fa fa-users fa-1g" style="color:yellow;"></i>
                          </div>
                          <div class="count" style="font-size:35px;">
						   <?php
								//get attendance of individual classes
								//echo "SELECT COUNT(student_id) AS total_birthday FROM `student_details` WHERE birth_date='%".date('d-m')."%'";exit;
								$stmt_att_details = $conn->prepare("SELECT COUNT(student_id) AS total_birthday FROM `student_details` WHERE birth_date LIKE '%".date('d-m')."%'");
								$stmt_att_details->execute();
								$row_att_details = $stmt_att_details->fetchAll(PDO::FETCH_ASSOC);
								echo $row_att_details[0]['total_birthday'];
                            ?>	
						  </div>
                          <h3>Total</br>Birthdays</h3>
                          <p></p>
                        </div>
						</a>
                      </div>
				
					</div>
                    </div>
				  </div>
               </div>
            </div>
			<br/>
	
			 		   <!--  Graph -->

			   <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-6 col-sm-6  ">
                <div class="x_panel">
                  <div class="x_title">
                    <h2><b>Student Details in Percentage</b></h2>
                  <!--  <ul class="nav navbar-right panel_toolbox">
                     <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    </ul>  -->
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <canvas id="canvas1"></canvas>
                  </div>
                </div>
              </div>

             <div class="col-md-6 col-sm-6  ">
                <div class="x_panel">
                  <div class="x_title">
                    <h2><b>Payment Details in Percentage</b></h2>
                    <ul class="nav navbar-right panel_toolbox">
                       <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                     <canvas id="canvas2" ></canvas>
                  </div>
                </div>
              </div>
            </div>
					<!-- End Graph -->
					
		
           </div></br>

        <!-- /page content -->

        <!-- footer content -->
         <?php require "footer.php";?>
        <!-- /footer content -->
      </div>
    </div>
    <?php require "foot_link.php";?>	

<script src="js/bootstrap.min.js"></script>

  <!-- gauge js -->
  <script type="text/javascript" src="js/gauge/gauge.min.js"></script>
  <!--<script type="text/javascript" src="js/gauge/gauge_demo.js"></script>-->
  <!-- bootstrap progress js -->
  <script src="js/progressbar/bootstrap-progressbar.min.js"></script>
  <!-- icheck -->
  <script src="js/icheck/icheck.min.js"></script>
  <!-- daterangepicker -->
  <script type="text/javascript" src="js/moment/moment.min.js"></script>
  <script type="text/javascript" src="js/datepicker/daterangepicker.js"></script>
  <!-- chart js -->
  <script src="js/chartjs/chart.min.js"></script>

  <script src="js/custom.js"></script>
  <script>
    $('document').ready(function() {
      var options = {
        legend: false,
        responsive: false
      };
      new Chart(document.getElementById("canvas1"), {
        type: 'pie',
        tooltipFillColor: "rgba(51, 51, 51, 0.55)",
        data: {
          labels: [
            "Enquiry Student",
            "Admission Student"
          ],
          datasets: [{
            data: [<?php echo $enquiry_admission; ?>, <?php echo $direct_admission; ?>],
            backgroundColor: [
              "#26B99A",
              "#9B59B6"
            ],
            hoverBackgroundColor: [
              "#36CAAB",
              "#B370CF"
            ]
          }]
        },
        options: options
      });
    });
  </script>
    <script>
    $('document').ready(function() {
      var options = {
        legend: false,
        responsive: false
      };
	  	<?php  $num9=$num2 + $num7;
			$num2=$num2*100/$num9;
			$num7=$num7*100/$num9;   
		?>
      new Chart(document.getElementById("canvas2"), {
        type: 'doughnut',
        tooltipFillColor: "rgba(51, 51, 51, 0.55)",
        data: {
          labels: [
            "Paid Fees",
            "Balance Fees"
          ],
          datasets: [{
            data: [<?php echo $num2; ?>, <?php echo $num7; ?>],
            backgroundColor: [
              "#26B99A",
              "#9B59B6"
            ],
            hoverBackgroundColor: [
              "#36CAAB",
              "#B370CF"
            ]
          }]
        },
        options: options
      });
    });
  </script>
  </body>
</html>
