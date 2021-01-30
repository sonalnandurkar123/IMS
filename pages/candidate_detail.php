
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
//require_once("sendsms.php");
date_default_timezone_set("Asia/Kolkata");
$student_id=$_GET['id'];
if(isset($_POST['send_fees_sms']))
{
	/*$stu_no=$_POST['student_number'];
	$sendsms = new sendsms("","PARAMT");  //API key, Sender
	$sendsms->send_sms($stu_no,"Dear Parent, From PARAM TUTORIALS, gentle reminder. Kindly pay remaining fees due amount to avoid discontinuity of tuition. If already paid ignore this message.");
	func_sms_count();*/
}

$stmt_stud =$conn->prepare("SELECT * FROM student_details WHERE student_id=$student_id");
$stmt_stud->execute();
$row_stud=$stmt_stud->fetchAll(PDO::FETCH_ASSOC);
//update student 
if(isset($_POST['submit']))
{
	extract($_POST);
	//print_r($_POST);exit;
	if($_FILES['profile']['name']!="")
	{
		$file_size1=$_FILES['profile']['size'];
		$file_name1=$_FILES['profile']['name'];
		$file_tmp1=$_FILES['profile']['tmp_name'];
		$file_type1=$_FILES['profile']['type'];
		$errors1=array();
		$arr1=explode('.',$file_name1);
		$extension1=strtolower(end($arr1));
		
		$allowed1=array('jpg','jpeg','png');
		
		$date1=$arr1[0]."_".date('dmYhis').".$extension1";
		if(empty($errors1))
		{
			if(move_uploaded_file($file_tmp1,"images/profile/".$date1))	
			{
				//2echo "File uploaded successfully.";
			}
			else
			{
				//echo "File not uploaded";
			}

		}
	}
	else
	{
		$date1=$row[0]['profile'];
	}

    
try{
		$stmt = $conn->prepare("UPDATE student_details SET student_name=:student_name,birth_date=:birth_date,sex=:sex,school=:school,medium=:medium,who_suggest=:who_suggest,student_number=:student_number,student_address=:student_address,profile=:profile,date_of_entry=:date_of_entry WHERE student_id=$student_id");

		$executed=$stmt->execute(array(':student_name' => $student_name,'birth_date' => $birth_date,'sex' => $sex,':school' => $school,':medium' => $medium,':who_suggest' => $who_suggest,':student_number' => $student_number,':student_address' => $student_address,':profile' => $date1,':date_of_entry' => date('Y-m-d',strtotime($date_of_entry))));
			   if($executed)
			   {
					for($p=0;$p<count($add_edu['class']);$p++)
					{
						
						
						$stmt1 = $conn->prepare("UPDATE edu_performance SET class=:class,school_clg=:school_clg,marks=:marks,percentage=:percentage WHERE edu_id=".$add_edu['edu_id'][$p]);

						$executed1=$stmt1->execute(array('class' => $add_edu['class'][$p],'school_clg' => $add_edu['school_clg'][$p],':marks' => $add_edu['marks'][$p],':percentage' => $add_edu['percentage'][$p]));
					}
					for($j=0;$j<count($add_family['name']);$j++)
					{
						
						
						$stmt2 = $conn->prepare("UPDATE family_info SET name=:name,education=:education,occupation=:occupation,contact_no=:contact_no WHERE info_id=".$add_family['info_id'][$j]);

						$executed2=$stmt2->execute(array('name' => $add_family['name'][$j],'education' => $add_family['education'][$j],'occupation' => $add_family['occupation'][$j],':contact_no' => $add_family['contact_no'][$j]));
					}
						echo "<script>alert('Updated Successfully!!!')</script>";
						echo "<script>window.location.href='student_list';</script>";
			   }
	}
	catch(Exception $e) { echo 'Message: ' .$e->getMessage();	}
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
                <h3>User Profile</h3>
              </div>

              <div class="title_right">
                <div class="col-md-5 col-sm-5  form-group pull-right top_search">
                  <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search for...">
                    <span class="input-group-btn">
                      <button class="btn btn-secondary" type="button">Go!</button>
                    </span>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12 col-sm-12 ">
                <div class="x_panel">
                  <div class="x_title">
                    <h2> Activity report</h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="#">Settings 1</a>
                            <a class="dropdown-item" href="#">Settings 2</a>
                          </div>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <div class="col-md-3 col-sm-3  profile_left">
                      <div class="profile_img">
                        <div id="crop-avatar">
                          <!-- Current avatar -->
                          <img src="images/profile/<?php echo $row_stud[0]['profile'];?>" style="height:200px;width:200px;">
                        </div>
                      </div>
                      <h3><?php echo $row_stud[0]['student_name'];?> </h3>
					  <ul class="list-unstyled user_data">
						<h2><li><i class="fa fa-mobile"></i> <?php echo $row_stud[0]['student_number'];?></li></h2>
                        <h2><li><i class="fa fa-map-marker user-profile-icon"></i> <?php echo $row_stud[0]['student_address'];?></h2>
                        </li>
					  </ul>
`					<br/>
			
                    </div>
                    <div class="col-md-9 col-sm-9 ">
						<div class="" role="tabpanel" data-example-id="togglable-tabs">
                        <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist" >
                          <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Profile</a>
                          </li>
                          <li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Fees</a>
                          </li>
                          <li role="presentation" class=""><a href="#tab_content3" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">Attendance</a>
						  </li>
						  <li role="presentation" class=""><a href="#tab_content4" role="tab" id="profile-tab3" data-toggle="tab" aria-expanded="false">Marks</a>
						  </li>
                        </ul>
                        <div id="myTabContent" class="tab-content">
						
						<!-- start recent activity -->
						<!-- PERSONAL INFO -->
						 
							<div role="tabpanel" class="tab-pane active " id="tab_content1" aria-labelledby="home-tab">
								<div class="col-sm-12" style="border: 1px solid #000;padding-top:10px;padding-bottom:10px;">
								<div id="page-wrapper">
									<div class="graphs">
										<h3 class="blank1"></h3>
										<div class="tab-content">
											<div class="tab-pane active" id="horizontal-form">
												<form class="form-horizontal" method="post" action="" data-parsley-validate enctype="multipart/form-data"  novalidate>
													<div class="grid_3 grid_5">
														<h3>Candidate Info</h3><hr>
														
														<div class="field item form-group">
															<label for="focusedinput" class="col-form-label col-md-3 col-sm-3 label-align">Date of Registration </label>
																<div class="col-sm-8">
																	<input type="text" class="form-control" name="date_of_entry" id="date_of_entry" placeholder="Ex : 01-01-2001" value="<?php echo date('d-m-Y', strtotime($row_stud[0]['date_of_entry']));?>" readonly>
																</div>
														</div>
														<div class="field item form-group">
															<label for="focusedinput" class="col-form-label col-md-3 col-sm-3 label-align">Name of the Candidate</label>
															<div class="col-sm-8">
																<input type="text" name="student_name" class="form-control"  placeholder="" value="<?php echo $row_stud[0]['student_name'];?> ">
															</div> 
														</div>
														<div class="field item form-group">
															<label for="focusedinput" class="col-form-label col-md-3 col-sm-3 label-align">Date of Birth</label>
															<div class="col-sm-8">
																<input type="text" class="form-control"  placeholder="" name="birth_date" id="birth_date" value="<?php echo $row_stud[0]['birth_date'];?>">
															</div>
														</div>
														
														<div class="field item form-group">
															<label for="focusedinput" class="col-form-label col-md-3 col-sm-3 label-align">Sex</label>
															<div class="col-sm-8">
																<div class="radio block">
																<label><input type="radio" name="sex" value='M' <?php if($row_stud[0]['sex']=='M')echo "checked";?>> Male</label> 
																
																<label><input type="radio" name="sex" value='F' <?php if($row_stud[0]['sex']=='F')echo "checked";?>> Female</label>
																</div>
																
															</div>
														</div>
														
														<div class="field item form-group">
															<label for="focusedinput" class="col-form-label col-md-3 col-sm-3 label-align">School / College</label>
															<div class="col-sm-8">
																<input type="text" class="form-control"  placeholder="" name="school" value="<?php echo $row_stud[0]['school'];?>">
															</div>
														</div>
														
														<div class="field item form-group">
															<label for="focusedinput" class="col-form-label col-md-3 col-sm-3 label-align">Medium</label>
															<div class="col-sm-8">
																<input type="text" class="form-control"  placeholder="" name="medium" value="<?php echo $row_stud[0]['medium'];?>">
															</div>
														</div>
														
														<div class="field item form-group">
															<label for="focusedinput" class="col-form-label col-md-3 col-sm-3 label-align">Address</label>
															<div class="col-sm-8">
																<textarea type="text" class="form-control"  placeholder=""  name="student_address"><?php echo $row_stud[0]['student_address'];?></textarea>
															</div>
														</div>
														
														
														<div class="field item form-group">
															<label for="focusedinput" class="col-form-label col-md-3 col-sm-3 label-align">Phone No</label>
															<div class="col-sm-8">
																<input type="text" class="form-control"  placeholder="" name="student_number" value="<?php echo $row_stud[0]['student_number'];?>">
															</div>
														</div>
														<div class="field item form-group">
															<label for="focusedinput" class="col-form-label col-md-3 col-sm-3 label-align">Eductional Performance in last two year</label>
															<div id="addnewrowedu">
															<div class="col-sm-offset-2">
																<?php
																	$stmt_edu =$conn->prepare("SELECT * FROM edu_performance WHERE stud_id=".$row_stud[0]['student_id']);
																	$stmt_edu->execute();
																	$row_edu=$stmt_edu->fetchAll(PDO::FETCH_ASSOC);
																	for($e=0;$e<count($row_edu);$e++)
																	{
																?>
																	<div class="field item form-group">
																		<div class="col-sm-2">
																			<input type="hidden" name="add_edu[edu_id][]" value="<?php echo $row_edu[$e]['edu_id']; ?>">
																			<input type="text" class="form-control" name="add_edu[class][]" placeholder="Class" value="<?php echo $row_edu[$e]['class']; ?>">
																		</div>
																		<div class="col-sm-4">
																			<input type="text" class="form-control" name="add_edu[school_clg][]" placeholder="School / College" value="<?php echo $row_edu[$e]['school_clg']; ?>">
																		</div>
																		<div class="col-sm-2">
																			<input type="text" class="form-control"  placeholder="Default Input" name="add_edu[marks][]" value="<?php echo $row_edu[$e]['marks']; ?>">
																		</div>
																		<div class="col-sm-2">
																			<input type="text" class="form-control"  placeholder="Default Input" name="add_edu[percentage][]" value="<?php echo $row_edu[$e]['percentage']; ?>">
																		</div>
																		<div class="col-sm-2">
											                                <input  class="btn btn-success" type="button" value="+" onClick="addMoreEdu();">	
																		</div>
																	</div>
																<?php
																	}
																?>
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
														</div>
														<div class="field item form-group">
															<label for="focusedinput" class="col-form-label col-md-3 col-sm-3 label-align">Information about family</label>
															<div id="addnewrowfamily">
															<div class="col-sm-offset-2">
																<?php
																	$stmt_family =$conn->prepare("SELECT * FROM family_info WHERE stud_id=".$row_stud[0]['student_id']);
																	$stmt_family->execute();
																	$row_family=$stmt_family->fetchAll(PDO::FETCH_ASSOC);
																	
																	for($f=0;$f<count($row_family);$f++)
																	{
																?>
																	<div class="field item form-group">
																		<div class="col-sm-4">
																			<input type="hidden" name="add_family[info_id][]" value="<?php echo $row_family[$f]['info_id']; ?>">
																			<input type="text" class="form-control"  placeholder="Name" name="add_family[name][]" value="<?php echo $row_family[$f]['name']; ?>">
																		</div>
																		<div class="col-sm-2">
																			<input type="text" class="form-control"  placeholder="Education" name="add_family[education][]" value="<?php echo $row_family[$f]['education']; ?>">
																		</div>
																		<div class="col-sm-2">
																			<input type="text" class="form-control"  placeholder="Occupation" name="add_family[occupation][]" value="<?php echo $row_family[$f]['occupation']; ?>">
																		</div>
																		<div class="col-sm-2">
																			<input type="text" class="form-control"  placeholder="Contact No" name="add_family[contact_no][]" value="<?php echo $row_family[$f]['contact_no']; ?>">
																		</div>
																		<div class="col-sm-2">
											                                <input  class="btn btn-success" type="button" value="+" onClick="addMoreFamily();">	
																		</div>
																	</div>
																<?php
																	}
																?>
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
																	
														</div>														
														<div class="field item form-group">
															<label for="focusedinput" class="col-form-label col-md-3 col-sm-3 label-align">Who Suggested ?</label>
															<div class="col-sm-8">
																<input type="text" class="form-control"  placeholder="" name="who_suggest" value="<?php echo $row_stud[0]['who_suggest'];?>">
															</div>
														</div>
														<div class="field item form-group">
															<label for="focusedinput" class="col-form-label col-md-3 col-sm-3 label-align">Course Name</label>
															<div class="col-sm-8">
															<?php 
																$stmt_get_course_details=$conn->prepare("SELECT * FROM course_details WHERE course_details_id=".$row_stud[0]['course_details_id']);
																$stmt_get_course_details->execute();
																$row_get_course_details=$stmt_get_course_details->fetchAll(PDO::FETCH_ASSOC);
																//echo $row_get_course_details[0]['course_name'];
															?>
																<input type="text" class="form-control"  placeholder="" name="course_name" value="<?php echo $row_get_course_details[0]['course_name'];?>" readonly>
															</div>
														</div>
														<div class="field item form-group">
															<label for="focusedinput" class="col-form-label col-md-3 col-sm-3 label-align">Batch Timing</label>
															<div class="col-sm-8">
																	<input type="text" class="form-control"  placeholder="" name="course_name" value="<?php echo $row_stud[0]['batch_time'];?>" readonly>
															</div>
														</div>
														<!--<div class="form-group">
															<label for="focusedinput" class="col-sm-2 control-label">Course Amount</label>
															<div class="col-sm-8">
																<input type="text" class="form-control1"  placeholder="" name="course_fees" id="course_fees" value="<?php echo $row_stud[0]['course_fees'];?>" readonly>
															</div>
														</div>
														<div class="form-group">
															<label for="focusedinput" class="col-sm-2 control-label">Paid Amount</label>
															<div class="col-sm-8">
																<input type="text" class="form-control1"  placeholder="" name="paid_amount" id="paid_amount" value="<?php echo $row_stud[0]['paid_amount'];?>" readonly>
															</div>
														</div>
														<div class="form-group">
															<label for="focusedinput" class="col-sm-2 control-label">Balance Amount</label>
															<div class="col-sm-8">
																<input type="text" class="form-control1"  placeholder="" name="balance_amount" id="balance_amount" value="<?php echo $row_stud[0]['balance_amount'];?>" readonly>
															</div>
														</div>-->
														<div class="field item form-group">
														<label for="focusedinput" class="col-form-label col-md-3 col-sm-3 label-align">Update Profile</label>
														<div class="col-sm-8">
															<input type="file" name="profile" id="exampleInputFile">
															<input id="" class=" col-md-7 col-xs-12" type="hidden" name="profile" value="<?php echo $row_stud[0]['profile']; ?>" >
														</div>
													</div>
														<div class="panel-footer">									
															<div class="pull-center text-center">
																<button type="submit" name="submit" class="btn-success btn" >Update</button>
															</div>
														</div>
													</div>
												</form>
											</div>
										</div>
									</div>
								</div>
								</div>
							</div>
								<!-- end recent activity -->
								
							<!--  FEES DETAIL  -->	
								 
								 <!-- start user projects -->
							<div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">
								<div class="col-sm-12" style="border: 1px solid #000;padding-top:10px;padding-bottom:10px;">
										<div class="row">
											<div class="col-sm-8">
												<div class="row">
													<div class="col-sm-3 ">
														<div class="img-thumbnail" style="background:#99ccff">
															<h5>Total</h5>
															<h4><i class="fa fa-inr"></i> <?php echo $row_stud[0]['course_fees'];?></h4>
														</div>
													</div>
													<div class="col-sm-3 ">
														<div class="img-thumbnail" style="background:#b3ffd9">
															<h5>Received</h5>
															<h4><i class="fa fa-inr"></i> <?php echo $row_stud[0]['paid_amount'];?></h4>
														</div>
													</div>
													<div class="col-sm-3 ">
														<div class="img-thumbnail" style="background:#ffcce6">
															<h5>Balance</h5>
															<h4><i class="fa fa-inr"></i> <?php echo $row_stud[0]['balance_amount'];?></h4>
														</div>
													</div>
												</div>	
											</div>
											<div class="col-sm-4"  style="background:#f8f8f8;">
												<div class="container-fluid">
													<div class="row">
														<h4 class="green">Paid</h4><hr>
														<h5><i class="fa fa-inr"></i> <?php echo $row_stud[0]['paid_amount'];?></h5>
													</div>
													<div class="row">
														<h4 class="red">Unpaid</h4><hr>
														<h5><i class="fa fa-inr"></i> <?php echo $row_stud[0]['balance_amount'];?></h5>
														<?php
															if($row_stud[0]['balance_amount']>0)
															{
																?>
																<!--<form method="post" action="">
																	<input type="hidden" name="student_number" value="<?php echo $row_stud[0]['student_number'];?>">
																	<button type="submit" name="send_fees_sms">Send Fees Reminder SMS</button>
																</form>-->
																<?php
															}
														?>
													</div>
												</div>
											</div>
										</div><br>
										<div class="col-sm-12 table-responsive">
											<table id="datatable-buttons" class="table table-bordered table-striped jambo_table bulk_action" cellspacing="0" width="100%">
												<thead>
												<tr class="warning">
													<th class="th1">Sr. No.</th>
													<th class="th1">Payment Date</th>
													<th class="th1">Amount</th>
													<th class="th1">Action</th>
												</tr>	
												</thead>
												<tbody>
												<?php 
												$stmt_date =$conn->prepare("SELECT * FROM student_payment_details WHERE student_id=$student_id ORDER BY student_id");
												$stmt_date->execute();
												$row_date=$stmt_date->fetchAll(PDO::FETCH_ASSOC);
												for($d=0;$d<count($row_date);$d++)
												{
												?>
												<tr>
													<td class="td1"><?php echo $d+1;?></td>
													<td class="td1"><?php echo date('d-m-Y', strtotime($row_date[$d]['payment_date']));?></td>
													<td class="td1"><?php echo $row_date[$d]['pay_amount']; ?></td>
													<td class="td1"><a href="print_student_receipt?id=<?php echo $row_date[$d]['payment_id'];?>" class="btn btn-primary btn-xs">Print</a></td>
												</tr>
												<?php 
												}
												?>
												</tbody>
										</table>
										</div>
									
									<!--<div class="col-sm-4"  style="background:#f8f8f8;"><br>
										<!--<h2><?php //echo $row_stud[0]['course_name'];?></h2><hr>
										<div class="inline summary">
											<h4 class="col-sm-6 left">Admission Date</h4>
											<div class="col-sm-6 right">
												<h5><i class="fa fa-date"></i> <?php echo date('d-m-Y', strtotime($row_stud[0]['date_of_entry']));?></h5>
											</div>
										</div>
										<div class="inline summary">
											<h4 class="col-sm-6 left">Fees</h4>
											<div class="col-sm-6 right">
												<h5><i class="fa fa-inr"></i> <?php echo $row_stud[0]['course_fees'];?></h5>
											</div>
										</div>
										<!--<div class="inline summary">
											<h4 class="col-sm-6 left">Discount</h4>
											<div class="col-sm-6 right">
												<h5><i class="fa fa-inr"></i> </h5>
											</div>
										</div>
										<div class="inline summary ttl">
											<h4 class="col-sm-6 left">Total</h4>
											<div class="col-sm-6 right">
												<h5><i class="fa fa-inr"></i> <?php echo $row_stud[0]['course_fees'];?></h5>
											</div>
										</div>
										<!--<h6>Note :</h6>
										<p></p>
										
										
									</div>-->
								</div>
							</div>
							
								<!-- end user projects -->

								<!-- ATTENDANCE DETAIL  -->
							 
							<div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="profile-tab" >
							<div class="col-sm-12" style="border: 1px solid #000;padding-top:10px;padding-bottom:10px;">
							   <div class="form-group"><br>
									<label for="focusedinput" class="col-form-label col-md-3 col-sm-3 label-align"> Month :</label>
									<div class="col-sm-4">
									<?php
									//$course_name=$row_stud[0]['course_name'];
									$course_details_id=$row_get_course_details[0]['course_details_id'];
									$batch_time=$row_stud[0]['batch_time'];
									?>
										<select name="subject_name" id="test" required="required" class="form-control" onchange="get_monthwise_attendance(this.value,<?php echo $row_stud[0]['student_id'];?>,'<?php echo $course_details_id;?>','<?php echo $batch_time;?>');">
											<option>-----Select Month-----</option>
											<option value="01">January</option>
											<option value="02">February</option>
											<option value="03">March</option>
											<option value="04">April</option>
											<option value="05">May</option>
											<option value="06">June</option>
											<option value="07">July</option>
											<option value="08">August</option>
											<option value="09">September</option>
											<option value="10">October</option>
											<option value="11">November</option>
											<option value="12">December</option>
										</select>
									</div>
								</div>
										<script>
											function get_monthwise_attendance(month_no,student_id,course_details_id,batch_time) 
											{	
											//alert(month_no);
											//alert(batch_time);
												$.ajax({
															type: "POST",
															url: "ajax_monthwise_attendance.php",
															data: {month_no:month_no,student_id:student_id,course_details_id:course_details_id,batch_time:batch_time},
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
																$("#monthwise_attendance_div").html(html);
																
															}					
														}
													   });
											}
										</script><br><br><br>
								<div class="bs-example4 table-responsive" data-example-id="contextual-table" id="monthwise_attendance_div">
									<table class="table table-bordered table-striped jambo_table bulk_action" cellspacing="0" width="100%">
										<thead>
											<tr class="warning">
												<th>Sr. No.</th>
												<th>Course Name</th>
												<th>Student Name</th>
												<th>Batch Timing</th>
												<th>Present</th>
												<!--<th>Action</th>-->
											</tr>
										</thead>
										<tbody>								
																			
										</tbody>
									</table>
								</div>
								</div>
							</div>
							
							<!-- MARKS DETAIL  -->
							
							<div role="tabpanel" class="tab-pane fade" id="tab_content4" aria-labelledby="profile-tab">
								<div class="col-sm-12 table-responsive" style="border: 1px solid #000;padding-top:10px;padding-bottom:10px;">
									<table id="datatable-responsive" class="table table-bordered table-striped jambo_table bulk_action" cellspacing="0" width="100%" style="width:400px;">
										<thead >
											<tr class="warning">
												<th>Sr. No.</th>
												<th>Subject Name</th>
												<th>Faculty Name</th>
												<th>Date</th>
												<th>Time</th>
												<th>Present/Absent</th>
												<th>Total Marks</th>
												<th>Marks Scored</th>
											</tr>
										</thead>
										<tbody>							
											<?php
											$stmt_exam_marks =$conn->prepare("SELECT * FROM exam_marks WHERE student_id=$student_id ORDER BY student_id");
											$stmt_exam_marks->execute();
											$row_exam_marks=$stmt_exam_marks->fetchAll(PDO::FETCH_ASSOC);
											//print_r($row_exam_marks);
											if($row_exam_marks)
											{
											for($m=0;$m<count($row_exam_marks);$m++)
											{
												$stmt_exam_details =$conn->prepare("SELECT * FROM exam_details WHERE exam_details_id=".$row_exam_marks[$m]['exam_details_id']);
												$stmt_exam_details->execute();
												$row_exam_details=$stmt_exam_details->fetchAll(PDO::FETCH_ASSOC);
												if($row_exam_details)
												{
											?>
												<tr>
													<th><?php echo $m+1; ?></th>
													<th><?php echo $row_exam_details[0]['subject_name']; ?></th>
													<th><?php echo $row_exam_details[0]['faculty_name']; ?></th>
													<th><?php echo date('d-m-Y', strtotime($row_exam_details[0]['date_of_exam'])); ?></th>
													<th><?php echo $row_exam_details[0]['time_in']."-".$row_exam_details[0]['time_out']; ?></th>
													<th><label><input type="checkbox" <?php if($row_exam_marks[$m]['present_or_absent']=='P'){echo "checked";} ?>></label></th>
													<th><?php echo $row_exam_details[0]['total_marks']; ?></th>
													<th><?php echo $row_exam_marks[$m]['student_marks']; ?></th>
												</tr>
											<?php
												}
												else {}
											}
											}
											else
											{}
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
            </div>
          </div>
        </div>
        <!-- /page content -->

        <!-- footer content -->
        
        <!-- /footer content -->
      </div>
    </div>

    
<!-- Bootstrap Core JavaScript -->



  
	
	<?php require "footer.php";?>
   <?php require "foot_link.php";?> 
  </body>
</html>