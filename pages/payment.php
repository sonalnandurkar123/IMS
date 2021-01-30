               <?php
require_once("conn.php");
$course_details_id=$_POST['course_details_id'];
 
    $stmt2 = $conn->prepare("SELECT * FROM batch_details WHERE course_details_id='".$course_details_id."'");
	$stmt2->execute();
	$row2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
	//get fees
	$stmt_course_fees =$conn->prepare("SELECT * FROM course_details WHERE course_details_id='".$course_details_id."'");
	$stmt_course_fees->execute();
	$row_course_fees=$stmt_course_fees->fetchAll(PDO::FETCH_ASSOC);
    if($row2)
    {
		?>
		<div class="item form-group">
			<label for="focusedinput" class="col-form-label col-md-3 col-sm-3 label-align">Batch<span class="required">*</span></label>
			<div class="col-md-6 col-sm-6">
				<select name="batch_timing" id="batch_timing" required="required" class="form-control" onchange="get_course_(this.value);">
					<option>-----Select-----</option>
					<?php
					for($j=0;$j<count($row2);$j++)
					{
					?>
						<option value='<?php echo $row2[$j]['batch_time'];?>'><?php echo $row2[$j]['batch_time'];?></option>
					<?php
					}
					?>
				
														
				</select>
			</div>
		</div>

			<div class="item form-group">
				<label for="focusedinput" class="col-form-label col-md-3 col-sm-3 label-align">Course Amount <span class="required">*</span></label>
				<div  class="col-md-6 col-sm-6">
					<input type="text" class="form-control" name="course_fees" id="course_fees" placeholder="Enter Course Amount" onkeypress="return event.keyCode != 13;" required value="<?php echo $row_course_fees[0]['course_fees'];?>">
				</div>
			</div>
									<div class="form-group">
										<label for="focusedinput" class="control-label col-md-3 col-sm-3 col-xs-12">Paid Amount <span class="required">*</span></label>
										<div  class="col-md-6 col-sm-6 col-xs-12">
											<input type="text" class="form-control col-md-7 col-xs-12" name="paid_amount" id="paid_amount" placeholder="Enter Paid Amount" onkeypress="return event.keyCode != 13;" required>
										</div>
									</div>
									<div class="form-group">
										<label for="focusedinput"class="control-label col-md-3 col-sm-3 col-xs-12">Balance Amount <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<input type="text" class="form-control col-md-7 col-xs-12" name="balance_amount" id="balance_amount" placeholder="Enter Balance Amount" onkeypress="return event.keyCode != 13;" required>
										</div>
									</div>
									<div class="form-group">
										<label for="focusedinput" class="control-label col-md-3 col-sm-3 col-xs-12">Mode Of Payment <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<select name="mode_of_payment" class="form-control" onchange="get_cheque_no(this.value)" required>
												<option value="">-----Select-----</option>
												<option value="Cash">Cash</option>
												<option value="Cheque">Cheque</option>
												<option value="Online">Online</option>
												<option value="Other">Other</option>
											</select>
										</div>
									</div>
									<div id="div_cheque_no" style="display:none">
									<div class="form-group">
										<label for="focusedinput" class="control-label col-md-3 col-sm-3 col-xs-12">Cheque No <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<input type="text" class="form-control" name="cheque_no" id="cheque_no" onkeypress="return event.keyCode != 13;">
										</div>
									</div>
									<div class="form-group">
										<label for="focusedinput" class="control-label col-md-3 col-sm-3 col-xs-12">Cheque Date <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<input type="text" class="form-control" name="cheque_date" id="cheque_date" onkeypress="return event.keyCode != 13;">
										</div>
									</div>
									<div class="form-group">
										<label for="focusedinput" class="control-label col-md-3 col-sm-3 col-xs-12">Bank Name<span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<input type="text" class="form-control" name="bank_name" id="cheque_no" onkeypress="return event.keyCode != 13;">
										</div>
									</div>
									</div>
									<div class="form-group">
										<label for="focusedinput" class="control-label col-md-3 col-sm-3 col-xs-12">Pay Amount In<span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<select name="paid_entry" class="form-control" required>
												<option value="">-----Select-----</option>
												<option value="Part Payment">Part Payment</option>
												<option value="Full Payment">Full Payment</option>
												<option value="Advance Payment">Advance Payment</option>
											</select>
										</div>
									</div>
										<script>
											function get_cheque_no(mode_of_payment)
											{
												if(mode_of_payment=='Cheque')
												{
													document.getElementById("div_cheque_no").style.display = "block";
												}
												else
												{
													document.getElementById("div_cheque_no").style.display = "none";
												}
											}
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
            var num2 = document.getElementById('other_charges').value;
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
	    else
	    {
	        var result1 = parseInt(num1) - parseInt(num3);
	    }
           if (!isNaN(result1)) 
			{
				//alert("result1");
				document.getElementById('balance_amount').value = result1;
            }
        }
</script>


		<?php
    }     
	else
	{
		echo "<h3 style='text-align:center'>No Batches Found. Please Add Batches For the Course First.</h3>";
	}
?>
<script>
	function get_student_list() 
	{	
	
		var course_name=document.getElementById("course_name").value;
		var batch_timing=document.getElementById("batch_timing").value;
		var date_of_attendance=document.getElementById("date_of_attendance").value;
		
		//alert(course_name);
		//alert(batch_timimg);
		$.ajax({
					type: "POST",
					url: "ajax_get_student_list.php",
					data: {course_name:course_name,batch_timing:batch_timing,date_of_attendance:date_of_attendance},
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
		
	}
</script>
<script>
	$(function() {
		$('#cheque_date').datepicker();
		$( "#cheque_date" ).on( "change", function() {
		  $( "#cheque_date" ).datepicker( "option", "dateFormat", "dd-mm-yy" );
		});
	});
  </script>