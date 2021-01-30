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

$stmt_student =$conn->prepare("SELECT * FROM student_details WHERE birth_date LIKE '%".date('d-m')."%'");
	$stmt_student->execute();
	$row_student=$stmt_student->fetchAll(PDO::FETCH_ASSOC);

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
                <h3>Student Details</h3>
				<?php
					//get total fees, balance and paid_amount
					$stmt_student_total =$conn->prepare("SELECT SUM(course_fees) AS course_fees, SUM(paid_amount) AS paid_amount, SUM(balance_amount) AS balance_amount FROM student_details ORDER BY student_id DESC");
					$stmt_student_total->execute();
					$row_student_total=$stmt_student_total->fetchAll(PDO::FETCH_ASSOC);
					//echo "Total Course Fees:".$row_student_total[0]['course_fees']."     Total Paid Amount:".$row_student_total[0]['paid_amount']."     Total Balance Amount:".$row_student_total[0]['balance_amount'];
				?>
              </div>
            </div>

            <div class="clearfix"></div>

		    <div class="col-md-12 col-sm-12 ">
				<div class="x_panel">
					<div class="x_title">
						<h2>Student List</h2>
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
							
								<div class="card-box table-responsive" >
									<table id="datatable-responsive" class="table table-bordered" cellspacing="0" width="100%">
									   <thead>
											<tr class="warning">
												<th>Sr. No.</th>
												<th>Student Name</th>
												<th>Birth Date</th>
												<th>Course Name</th>
												<th>Contact</th>
												<!--<th>Action</th>-->
											</tr>
										</thead>
										<tbody>
											<?php
											for($c=0;$c<count($row_student);$c++)
											{
											?>
												<tr>
													<td><?php echo $c+1;?></td>
													<td><?php echo $row_student[$c]['student_name'];?></td>
													<td><?php echo $row_student[$c]['birth_date'];?></td>
													<td><?php 
														$stmt_get_course_details=$conn->prepare("SELECT * FROM course_details WHERE course_details_id=".$row_student[$c]['course_details_id']);
														$stmt_get_course_details->execute();
														$row_get_course_details=$stmt_get_course_details->fetchAll(PDO::FETCH_ASSOC);
														echo $row_get_course_details[0]['course_name'];?></td>
													<td><?php echo $row_student[$c]['student_number'];?></td>
												
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
   
  <script src="js/jquery.nicescroll.js"></script>
<script src="js/scripts.js"></script>
<!-- Bootstrap Core JavaScript -->
   <script src="js/bootstrap.min.js"></script>
   
	   <script type="text/javascript">
        function get_test_list(sid) 
        {
			//alert('in get list');
			var sid2=sid.value;
			//alert(sid.value);
            $.ajax({
                type: "POST",
                url: "ajaxpage.php",
                data: {class_name:sid2},
                cache: false,
                //dataType: 'json',
                success: function(html){
                //alert(html);
                if(html=='0')
                {
                    alert("There is some error.");
                }
                else
                {
                    $("#test").html(html);
                }
                                        
            }

           });
        }
        </script>   
 <script src="js/datatables/jquery.dataTables.min.js"></script>
        <script src="js/datatables/dataTables.bootstrap.js"></script>
        <script src="js/datatables/dataTables.buttons.min.js"></script>
        <script src="js/datatables/buttons.bootstrap.min.js"></script>
        <script src="js/datatables/jszip.min.js"></script>
        <script src="js/datatables/pdfmake.min.js"></script>
        <script src="js/datatables/vfs_fonts.js"></script>
        <script src="js/datatables/buttons.html5.min.js"></script>
        <script src="js/datatables/buttons.print.min.js"></script>
        <script src="js/datatables/dataTables.fixedHeader.min.js"></script>
        <script src="js/datatables/dataTables.keyTable.min.js"></script>
        <script src="js/datatables/dataTables.responsive.min.js"></script>
        <script src="js/datatables/responsive.bootstrap.min.js"></script>
        <script src="js/datatables/dataTables.scroller.min.js"></script>


        <!-- pace -->
        <script src="js/pace/pace.min.js"></script>
        <script>
          var handleDataTableButtons = function() {
              "use strict";
              0 !== $("#datatable-buttons").length && $("#datatable-buttons").DataTable({
                dom: "Bfrtip",
                buttons: [{
                  extend: "copy",
                  className: "btn-sm"
                }, {
                  extend: "csv",
                  className: "btn-sm"
                }, {
                  extend: "excel",
                  className: "btn-sm"
                }, {
                  extend: "pdf",
                  className: "btn-sm"
                }, {
                  extend: "print",
                  className: "btn-sm"
                }],
                responsive: !0
              })
            },
            TableManageButtons = function() {
              "use strict";
              return {
                init: function() {
                  handleDataTableButtons()
                }
              }
            }();
        </script>
        <script type="text/javascript">
          $(document).ready(function() {
            $('#datatable').dataTable();
            $('#datatable-keytable').DataTable({
              keys: true
            });
            $('#datatable-responsive').DataTable();
            $('#datatable-scroller').DataTable({
              ajax: "js/datatables/json/scroller-demo.json",
              deferRender: true,
              scrollY: 380,
              scrollCollapse: true,
              scroller: true
            });
            var table = $('#datatable-fixed-header').DataTable({
              fixedHeader: true
            });
          });
          TableManageButtons.init();
        </script>		
  </body>
</html>

