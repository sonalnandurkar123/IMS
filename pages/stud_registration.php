
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
$enquiry_id	=$_GET['id'];

if(isset($_POST['submit']))
{
	extract($_POST);
	//print_r($_FILES);exit;
	$file_size1=$_FILES['profile']['size'];
	$file_name1=$_FILES['profile']['name'];
	$file_tmp1=$_FILES['profile']['tmp_name'];
	$file_type1=$_FILES['profile']['type'];
	$errors1=array();
	$arr1=explode('.',$file_name1);
	$extension1=strtolower(end($arr1));
	//to check extension
	$allowed1=array('jpg','jpeg','png');
	
	//to check size
	
	
	//move_uploaded_file("location od temperory stored file","location where i want to store my file");
	
	$date1=$arr1[0]."_".date('dmYhis').".$extension1";
	if(empty($errors1))
	{
		if(move_uploaded_file($file_tmp1,"image/profile/".$date1))	
		{
			//2echo "File uploaded successfully.";
		}
		else
		{
			//echo "File not uploaded";
		}

	}

    
try{
$stmt = $conn->prepare("INSERT INTO `student_details`(`student_name`,`birth_date`,`sex`,`school`,`medium`,`who_suggest`,`course_name`,`batch_time`,`course_fees`,`paid_amount`,`balance_amount`,`student_number`,`student_address`,`profile`,`date_of_entry`)VALUES(:student_name,:birth_date,:sex,:school,:medium,:who_suggest,:course_name,:batch_time,:course_fees,:paid_amount,:balance_amount,:student_number,:student_address,:profile,:date_of_entry)");

		$executed=$stmt->execute(array(':student_name' => $student_name,'birth_date' => $birth_date,'sex' => $sex,':school' => $school,':medium' => $medium,':who_suggest' => $who_suggest,':course_name' => $course_name,':batch_time' => $batch_time,':course_fees' => $course_fees,':paid_amount' => $paid_amount,':balance_amount' => $balance_amount,':student_number' => $student_number,':student_address' => $student_address,':profile' => $date1,':date_of_entry' =>  $day."-".$month."-".$year,));
			   if($executed)
			   {
				   $stmt=$conn->prepare("SELECT * FROM daily_transaction ORDER BY daily_transaction_id");
					$stmt->execute();
					$row=$stmt->fetchAll(PDO::FETCH_ASSOC);
					if($row)
					{
						$old_amount=$row[0]['total_balance'];
						//echo $old_amount;exit;
						$new_amount=$old_amount+$paid_amount;
						//insert data in daily transaction
						$stmt_tran = $conn->prepare("INSERT INTO daily_transaction(income_or_expense,amount,total_balance,date_of_transaction)VALUES(:income_or_expense,:amount,:total_balance,:date_of_transaction)");
						$executed_tran=$stmt_tran->execute(array(':income_or_expense' => "income",':amount' => $paid_amount,':total_balance' => $new_amount,':date_of_transaction' => date('Y-m-d H:i:s')));
						if($executed_tran)
						{
						?>
							<script>alert("Added Successfully");</script>
						<?php
						}

					}
					else
					{
						//$old_amount=$row[0]['total_balance'];
						//$new_amount=$old_amount-$row[0]['total_balance'];
						//insert data in daily transaction
						$stmt_tran = $conn->prepare("INSERT INTO daily_transaction(income_or_expense,amount,total_balance,date_of_transaction)VALUES(:income_or_expense,:amount,:total_balance,:date_of_transaction)");
						$executed_tran=$stmt_tran->execute(array(':income_or_expense' => "expense",':amount' => $paid_amount,':total_balance' => $expense_amount,':date_of_transaction' => date('Y-m-d H:i:s')));
						if($executed_tran)
						{
						?>
							<script>alert("Added Successfully");</script>
						<?php
						}

					}
				   
					$stud_id = $conn->lastInsertId();
					for($p=0;$p<count($add_edu['class']);$p++)
					{
						$stmt1 = $conn->prepare("INSERT INTO `edu_performance`(`stud_id`,`class`,`school_clg`,`marks`,`percentage`)VALUES(:stud_id,:class,:school_clg,:marks,:percentage)");

						$executed1=$stmt1->execute(array('stud_id' => $stud_id,'class' => $add_edu['class'][$p],'school_clg' => $add_edu['school_clg'][$p],':marks' => $add_edu['marks'][$p],':percentage' => $add_edu['percentage'][$p]));
					}
					for($j=0;$j<count($add_family['name']);$j++)
					{
						$stmt2 = $conn->prepare("INSERT INTO `family_info`(`stud_id`,`name`,`education`,`occupation`,`contact_no`)VALUES(:stud_id,:name,:education,:occupation,:contact_no)");

						$executed2=$stmt2->execute(array('stud_id' => $stud_id,'name' => $add_family['name'][$j],'education' => $add_family['education'][$j],'occupation' => $add_family['occupation'][$j],':contact_no' => $add_family['contact_no'][$j]));
					}						
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
     <script type="text/javascript">
		var todaydate = new Date();
	 	/*var day = todaydate.getDate();
		var month = todaydate.getMonth() + 1;*/
		var year = todaydate.getFullYear();
		var datestring = year;
		window.onload = function(){
		 document.getElementById("lastname").value = datestring;
		}
     </script>
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
                <h3>Student Registration</h3>
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12 col-sm-12 ">
                <div class="x_panel">
                  <!--<div class="x_title">
                    <h2>Add Enquire Details</h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>-->
                  <div class="x_content">
                    <br/>
					
					<?php
					    $stmt_enquiry =$conn->prepare("SELECT * FROM enquiry_details WHERE enquiry_id=$enquiry_id");
						$stmt_enquiry->execute();
						$row=$stmt_enquiry->fetchAll(PDO::FETCH_ASSOC);
					?>
					<!--form start -->
                   <form class="form-horizontal" method="post" action="print_enq_receipt.php?id=<?php echo $enquiry_id; ?>" enctype="multipart/form-data" novalidate>
				   
						<div class="field item form-group">
							<label  class="col-form-label col-md-3 col-sm-3 label-align">Date Of Registration<span class="required">*</span>
							</label>
							<div class="col-md-2 col-sm-2 ">
								<input type="text" id="" name="day" required="required" class="form-control" placeholder="dd" maxlength="2">
							</div>
							<div class="col-md-2 col-sm-2 ">
								<input type="text" id="" name="month" required="required" class="form-control" placeholder="mm" maxlength="2">
							 </div>
							<div class="col-md-2 col-sm-2 ">
								<input type="text" id="lastname" name="year" required="required" class="form-control" placeholder="yyyy" maxlength="4">
							</div>
						</div>
				   
						<div class="field item form-group">
							<label class="col-form-label col-md-3 col-sm-3 label-align" for="">Name Of The Candidate<span class="required">*</span></label>
							<div class="col-md-6 col-sm-6 ">
							  <input type="text" id=""  name="student_name"  required="required" class="form-control" value="<?php echo $row[0]['enquiry_name']; ?>" data-validate-length-range="6" data-validate-words="2"/>
							</div>
						</div>
					 
						<div class="field item form-group">
							 <label  class="col-form-label col-md-3 col-sm-3 label-align">Date Of Birth<span class="required">*</span>
							 </label>
							 <div class="col-md-2 col-sm-2">
								<input type="text" id="" name="birth_date" required="required" class="form-control" placeholder="dd" maxlength="2">
							 </div>
							 <div class="col-md-2 col-sm-2 ">
								<input type="text" id="" name="birth_month" required="required" class="form-control" placeholder="mm" maxlength="2">
							 </div>
							 <div class="col-md-2 col-sm-2 ">
								<input type="text" id="" name="birth_year" required="required" class="form-control" placeholder="yyyy" maxlength="4">
							 </div>
						</div>
						
						<div class="field item form-group">
							<label class="col-form-label col-md-3 col-sm-3 label-align">Gender *:</label>
							<div class="col-md-6 col-sm-6 ">
								<p>
									M:
									<input type="radio" class="flat" name="sex" id="genderM" value="M" checked="" required /> 
									F:
									<input type="radio" class="flat" name="sex" id="genderF" value="F" />
								</p>
							</div>
						</div>
						
						<div class="field item form-group">
							<label class="col-form-label col-md-3 col-sm-3 label-align">School / College<span class="required">*</span>
							</label>
							<div class="col-md-6 col-sm-6 ">
								<input id="birthday" class="date-picker form-control"  name="school" required="required" type="text"  />
							</div>
						</div>
					
						<div class="field item form-group">
							<label class="col-form-label col-md-3 col-sm-3 label-align">Medium<span class="required">*</span>
							</label>
							<div class="col-md-6 col-sm-6 ">
								<input id="birthday" class="date-picker form-control" name="medium" required="required" type="text" onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)">
							</div>
						</div>
					  
						<div class="field item form-group">
							<label class="col-form-label col-md-3 col-sm-3 label-align">Address<span class="required">*</span>
							</label>
							<div class="col-md-6 col-sm-6 ">
								<input id="birthday" class="date-picker form-control" name="student_address" required="required" type="text" >
							</div>
                        </div>
                        
						<div class="field item form-group">
							<label class="col-form-label col-md-3 col-sm-3 label-align">Phone No<span class="required">*</span>
							</label>
							<div class="col-md-6 col-sm-6 ">
								<input id="" class="date-picker form-control" name="student_number" required="required" type="text" value="<?php echo $row[0]['enquiry_contact']; ?>" maxlength="10" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;">
							</div>
                        </div>
						             <!-- Integer number Validation -->
					   
					                          <span id="error"></span>
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
					    <div class="form-group">
							<label for="focusedinput" class="col-form-label col-md-3 col-sm-3 label-align">Educational Performance in last  year</label>
						</div>
							<div id="addnewrowedu">
								<div class="col-sm-offset-2">
									<div class="field item form-group">
										<div class="col-sm-2">
											<input type="text" class="form-control" name="add_edu[class][]" placeholder="Class" required="required">
										</div>
										<div class="col-sm-4">
											<input type="text" class="form-control" name="add_edu[school_clg][]" placeholder="School / College" required="required">
										</div>
										<div class="col-sm-2">
											<input type="text" class="form-control" name="add_edu[marks][]" placeholder="Marks" maxlength="3" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" required="required">
										</div>
										<div class="col-sm-2">
											<input type="text" class="form-control" name="add_edu[percentage][]" placeholder="Percentage" maxlength="2" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" required="required">
										</div>	
										<div class="col-sm-2">
											<input  class="btn btn-success" type="button" value="+" onClick="addMoreEdu();">	
										</div>
									</div>	
								</div>
							</div>
				  
								<script>
									function addMoreEdu() 
												{	
													var textboxno=($("input[name='add_edu[class][]']").length);
													$.ajax({
																type: "POST",
																url: "add_more_edu.php",
																data: {textboxno:textboxno},
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
																	$("#addnewrowedu").append(html);
																}					
															}
														   });
												}
								</script>
									
                     
						<div class="form-group">
							<label for="focusedinput" class="col-form-label col-md-3 col-sm-3 label-align">Information about family</label>
						</div>
							<div id="addnewrowfamily">
								<div class="col-sm-offset-2">
									<div class="field item form-group">
										<div class="col-sm-4">
											<input type="text" name="add_family[name][]" class="form-control" placeholder="Name" required="required">                         
										</div>
										<div class="col-sm-2">
											<input type="text" name="add_family[education][]" class="form-control" placeholder="Education" required="required">
										</div>
										<div class="col-sm-2">
											<input type="text" name="add_family[occupation][]" class="form-control" placeholder="Occupation" required="required">
										</div>
										<div class="col-sm-2">
											<input type="text" name="add_family[contact_no][]" class="form-control" placeholder="Contact No" maxlength="10" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" required="required">
										</div>		
										<div class="col-sm-2">
											<input  class="btn btn-success" type="button" value="+" onClick="addMoreFamily();">
										</div>							
									</div>
								</div>
							</div>
				
								<script>
									function addMoreFamily() 
									{	
										var textboxno=($("input[name='add[product_id][]']").length);
										$.ajax({
											type: "POST",
											url: "add_more_family.php",
											data: {textboxno:textboxno},
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
												$("#addnewrowfamily").append(html);
											}					
										}
									   });
									}
								</script>
									
									
						<div class="field item form-group">
							<label class="col-form-label col-md-3 col-sm-3 label-align">Who Suggest Joining The Coaching Classes<span class="required">*</span>
							</label>
							<div class="col-md-6 col-sm-6 ">
								<input id="" class="date-picker form-control" name="who_suggest" required="required" type="text" >
							</div>
						</div>
						<div class="field item form-group">
							<label class="col-form-label col-md-3 col-sm-3 label-align" for="">Course Name<span class="required">*</span>
							</label>
							<div class="col-md-6 col-sm-6 ">
								<!--<input type="text" id="first-name" name="course_name" required="required" class="form-control">-->    
								<select name="course_details_id" id="course_details_id"  required="required" class="form-control" onchange="get_batches_for_list(this.value);">
									<option value="">-----Select Course-----</option>
									<?php
									$stmt_course =$conn->prepare("SELECT * FROM course_details");
									$stmt_course->execute();
									$row_course=$stmt_course->fetchAll(PDO::FETCH_ASSOC);
									for($c=0;$c<count($row_course);$c++)
									{
										?>
										<option value="<?php echo $row_course[$c]['course_details_id'];?>"><?php echo $row_course[$c]['course_name']?></option>
										<?php
									}
									?>
								</select>
							</div>
						</div>
							<script>
								function get_batches_for_list(course_details_id) 
								{	
									//var textboxno=($("input[name='add[product_id][]']").length);
									$.ajax({
												type: "POST",
												url: "ajax_get_batches_for_list.php",
												data: {course_details_id:course_details_id},
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
													$("#batch_timing_div").html(html);
													
												}					
											}
										   });
								}
								/*function get_student_list() 
								{	
									var course_name=document.getElementById("course_name");
									var batch_timimg=document.getElementById("batch_timing");
									$.ajax({
												type: "POST",
												url: "ajax_get_student_list.php",
												data: {course_name:course_name,batch_timimg:batch_timimg},
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
													$("#student_list_div").html(html);
												}					
											}
										   });
								}*/
							</script>
						<div id="batch_timing_div">		
							<div class="field item form-group">
								<label class="col-form-label col-md-3 col-sm-3 label-align" for="">Batch Timing<span class="required">*</span></label>
								<div class="col-md-6 col-sm-6 ">
									<!--<input type="text" id="first-name" name="course_name" required="required" class="form-control">-->
									<select name="batch_time" id="test"  required="required" class="form-control">
									<!-- <option value="">-----Select Timing-----</option>-->
									</select>
								</div>
							</div>
							<div class="field item form-group">
								<label class="col-form-label col-md-3 col-sm-3 label-align">Course Fees<span class="required">*</span></label>
								<div class="col-md-6 col-sm-6 ">
									<input id="course_fees" class="date-picker form-control" name="course_fees" required="required" type="text">
								</div>
							</div>
						</div>
						<div class="item form-group">
							<label class="col-form-label col-md-3 col-sm-3 label-align">Next Installment Date<span class="required">*</span></label>
							<div class="col-md-6 col-sm-6 ">
								<input id="birthday" class="date-picker form-control" name="next_date" required="required" type="date">
							</div>
						</div>
								
									<!--<div class="item form-group">
									  <label class="col-form-label col-md-3 col-sm-3 label-align"  style="color:text-grey;">Passport Photo</label>
									  <div class="col-md-6 col-sm-6 ">
									   <label for="exampleInputFile">File Input</label>
										<input type="file" name="profile" id="exampleInputFile">
									  </div>
									</div>-->
									
									
									
						<div class="field item form-group">
							<label class="col-form-label col-md-3 col-sm-3 label-align" for="">Passport Photo <span class="required">*</span> </label>
							<div class="col-md-6 col-sm-6">
								<input type="file" name="profile" id="exampleInputFile" placeholder="" required="required" class="">
							</div>
						</div>
					 
						<div class="ln_solid"></div>
							<div class="col-md-6 col-sm-6 offset-md-3">
								<button type="submit" name="submit" class="btn btn-success" >Submit</button>				  
								<button class="btn-danger btn" href="javascript:history.back()" type="button">Cancel</button>
								<!--<button class="btn btn-primary" type="reset">Reset</button>-->
							</div>
						</div>

                    </form>
					</div>
					</div>
					</div>
					<!--form end -->
                  </div>
                </div>
              </div>
            </div>
		
        <!-- /page content -->
		
		
     
		
			             
		
	
<script>
	$(function() {
		$('#date_of_entry').datepicker();
		$( "#date_of_entry" ).on( "change", function() {
		  $( "#date_of_entry" ).datepicker( "option", "dateFormat", "dd-mm-yy" );
		});
	});
  </script>
  
  <script>
	$(function() {
		$('#birth_date').datepicker();
		$( "#birth_date" ).on( "change", function() {
		  $( "#birth_date" ).datepicker( "option", "dateFormat", "dd-mm-yy" );
		});
	});
  </script>
  <script>
	$(function() {
		$('#next_date').datepicker();
		$( "#next_date" ).on( "change", function() {
		  $( "#next_date" ).datepicker( "option", "dateFormat", "dd-mm-yy" );
		});
	});
  </script>
  
    <script>
$(document).ready(function() {
    
    $("#course_fees,#paid_amount").on("keyup", function() { 
	    //alert("hii");
        sum();
    });
   
    
});

function sum() {
//alert("in function");
            var num1 = document.getElementById('course_fees').value;
            //var num2 = document.getElementById('other_charges').value;
            var num3 = document.getElementById('paid_amount').value;
            var num4 = document.getElementById('balance_amount').value;
			
            
	    if(num1=="")
	    {
	        var num1=0.00;
	    }
	    /*if(num2=="")
	    {
	        var num2=0.00;
	    }*/
	    if(num3=="")
	    {
	        var num3=0.00;
	    }
	    //else
	    //{
	        var result1 = parseInt(num1) - parseInt(num3);
	    //}
            if (!isNaN(result1)) 
			{
				//alert("result1");
				document.getElementById('balance_amount').value = result1;
            }
        }
</script>
	<!--<script language="JavaScript">

    Webcam.set({

        width: 490,

        height: 390,

        image_format: 'jpeg',

        jpeg_quality: 90

    });

  

    Webcam.attach( '#my_camera' );

  

    function take_snapshot() {

        Webcam.snap( function(data_uri) {

            $(".image-tag").val(data_uri);

            document.getElementById('results').innerHTML = '<img src="'+data_uri+'"/>';

        } );

    }

</script>-->
<script src="js/jquery.nicescroll.js"></script>
<script src="js/scripts.js"></script>
<!-- Bootstrap Core JavaScript -->
   <script src="js/bootstrap.min.js"></script><script>
	$(function() {
		$('#date_of_entry').datepicker();
		$( "#date_of_entry" ).on( "change", function() {
		  $( "#date_of_entry" ).datepicker( "option", "dateFormat", "dd-mm-yy" );
		});
	});
  </script>
  
  <script>
	$(function() {
		$('#birth_date').datepicker();
		$( "#birth_date" ).on( "change", function() {
		  $( "#birth_date" ).datepicker( "option", "dateFormat", "dd-mm-yy" );
		});
	});
  </script>
  <script>
	$(function() {
		$('#next_date').datepicker();
		$( "#next_date" ).on( "change", function() {
		  $( "#next_date" ).datepicker( "option", "dateFormat", "dd-mm-yy" );
		});
	});
  </script>
  
    <script>
$(document).ready(function() {
    
    $("#course_fees,#paid_amount").on("keyup", function() { 
	    //alert("hii");
        sum();
    });
   
    
});

function sum() {
//alert("in function");
            var num1 = document.getElementById('course_fees').value;
            //var num2 = document.getElementById('other_charges').value;
            var num3 = document.getElementById('paid_amount').value;
            var num4 = document.getElementById('balance_amount').value;
			
            
	    if(num1=="")
	    {
	        var num1=0.00;
	    }
	    /*if(num2=="")
	    {
	        var num2=0.00;
	    }*/
	    if(num3=="")
	    {
	        var num3=0.00;
	    }
	    //else
	    //{
	        var result1 = parseInt(num1) - parseInt(num3);
	    //}
            if (!isNaN(result1)) 
			{
				//alert("result1");
				document.getElementById('balance_amount').value = result1;
            }
        }
</script>
	<!--<script language="JavaScript">

    Webcam.set({

        width: 490,

        height: 390,

        image_format: 'jpeg',

        jpeg_quality: 90

    });

  

    Webcam.attach( '#my_camera' );

  

    function take_snapshot() {

        Webcam.snap( function(data_uri) {

            $(".image-tag").val(data_uri);

            document.getElementById('results').innerHTML = '<img src="'+data_uri+'"/>';

        } );

    }

</script>-->
<script src="js/jquery.nicescroll.js"></script>
<script src="js/scripts.js"></script>
<!-- Bootstrap Core JavaScript -->
   <script src="js/bootstrap.min.js"></script>
  	
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

   <?php require "foot_link.php";?>	
   
  </body>
</html>
