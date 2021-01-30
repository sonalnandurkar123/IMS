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
$enquiry_id=$_GET['id'];
if(isset($_POST['submit']))
{
	extract($_POST);
	//print_r($_FILES);exit;
	if($_FILES['profile']['name']!="")
	{
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
	/*elseif($_POST['image']!="")
	{
		$img = $_POST['image'];
		$folderPath = "image/profile/";
		$image_parts = explode(";base64,", $img);
		$image_type_aux = explode("image/", $image_parts[0]);
		$image_type = $image_type_aux[1];
		$image_base64 = base64_decode($image_parts[1]);
		$fileName = date('dmYhis').'.'.$image_type;
		$file = $folderPath . $fileName;
		file_put_contents($file, $image_base64);
		//print_r($fileName);
		$date1=$fileName;
	}*/
	else
	{
		$date1="-";
	}
try{
		$stmt = $conn->prepare("INSERT INTO `student_details`(`enquiry_id`,`student_name`,`birth_date`,`sex`,`school`,`medium`,`who_suggest`,`course_details_id`,`batch_time`,`course_fees`,`paid_amount`,`balance_amount`,`student_number`,`student_address`,`profile`,`date_of_entry`,`next_date`)VALUES(:enquiry_id,:student_name,:birth_date,:sex,:school,:medium,:who_suggest,:course_details_id,:batch_time,:course_fees,:paid_amount,:balance_amount,:student_number,:student_address,:profile,:date_of_entry,:next_date)");

		$executed=$stmt->execute(array(':enquiry_id' => $enquiry_id,':student_name' => $student_name,'birth_date' => $birth_date,'sex' => $sex,':school' => $school,':medium' => $medium,':who_suggest' => $who_suggest,':course_details_id' => $course_details_id,':batch_time' => $batch_timing,':course_fees' => $course_fees,':paid_amount' => $paid_amount,':balance_amount' => $balance_amount,':student_number' => $student_number,':student_address' => $student_address,':profile' => $date1,':date_of_entry' => date('Y-m-d',strtotime($day."-".$month."-".$year)),'next_date' => $next_date));
		
		$stmt = $conn->prepare("UPDATE enquiry_details SET enquiry_status=:enquiry_status WHERE enquiry_id=$enquiry_id");

		$executed=$stmt->execute(array(':enquiry_status' => 'inactive'));
			   if($executed)
			   {
				    $student_id = $conn->lastInsertId();
				    $stmt=$conn->prepare("SELECT * FROM daily_transaction ORDER BY daily_transaction_id DESC");
					$stmt->execute();
					$row=$stmt->fetchAll(PDO::FETCH_ASSOC);
					if($row)
					{
						$old_amount=$row[0]['total_balance'];
						//echo $old_amount;exit;
						$new_amount=$old_amount+$paid_amount;
						//insert data in daily transaction
						$date_of_transaction=date('Y-m-d',strtotime($day."-".$month."-".$year))." ".date('H:i:s');
						$stmt_tran = $conn->prepare("INSERT INTO daily_transaction( 	student_id,income_or_expense,amount,total_balance,date_of_transaction,daily_date,mode_of_payment,cheque_no,cheque_date,bank_name,paid_entry)VALUES(:student_id,:income_or_expense,:amount,:total_balance,:date_of_transaction,:daily_date,:mode_of_payment,:cheque_no,:cheque_date,:bank_name,:paid_entry)");
						$executed_tran=$stmt_tran->execute(array(':student_id' => $student_id,':income_or_expense' => "Income",':amount' => $paid_amount,':total_balance' => $new_amount,':date_of_transaction' => $date_of_transaction,':daily_date' => date('d-m-Y'),':mode_of_payment' => $mode_of_payment,':cheque_no' => empty($cheque_no)?'-':$cheque_no,':cheque_date' => empty($cheque_date)?'-':$cheque_date,':bank_name' => empty($bank_name)?'-':$bank_name,':paid_entry' => $paid_entry));
						if($executed_tran)
						{
							$stmt_payment_details = $conn->prepare("INSERT INTO student_payment_details(student_id,pay_amount,payment_date,mode_of_payment,cheque_no,cheque_date,bank_name,paid_entry)VALUES(:student_id,:pay_amount,:payment_date,:mode_of_payment,:cheque_no,:cheque_date,:bank_name,:paid_entry)");
							$executed_payment_details=$stmt_payment_details->execute(array(':student_id' => $student_id,':pay_amount' => $paid_amount,':payment_date' => $day."-".$month."-".$year,':mode_of_payment' => $mode_of_payment,':cheque_no' => empty($cheque_no)?'-':$cheque_no,':cheque_date' => empty($cheque_date)?'-':$cheque_date,':bank_name' => empty($bank_name)?'-':$bank_name,':paid_entry' => $paid_entry));
							if($executed_payment_details)
							{
							?>
								<script>alert("Added Successfully");</script>
							<?php
								///$mob_no=$student_number;
									
								//Code written on 06-09-2019 to check if sms sending is truned on then only the SMS will be sent
								//$stmt_check_sms_on = $conn->prepare("SELECT * FROM login_details WHERE login_id=1");//1 because we want the first record
								//$stmt_check_sms_on->execute();
								//$row_check_sms_on = $stmt_check_sms_on->fetchAll(PDO::FETCH_ASSOC);
								//if($row_check_sms_on[0]['sms_on_off']=="on")
								//{
					
					
									//$sendsms = new sendsms("158a876i3v5ej39q3g5892kz2kj1f80mo0x","PARAMT");  //API key, Sender
									//$sendsms->send_sms($mob_no,"Thanks,For joining PARAM TUTORIALS And Welcome in Param Family.");
									//func_sms_count();
								//}
							}
						}

					}
					else
					{
						//$old_amount=$row[0]['total_balance'];
						//$new_amount=$old_amount-$row[0]['total_balance'];
						//insert data in daily transaction
						$date_of_transaction=date('Y-m-d',strtotime($date_of_entry))." ".date('H:i:s');
						$stmt_tran = $conn->prepare("INSERT INTO daily_transaction(student_id,income_or_expense,amount,total_balance,date_of_transaction,daily_date,mode_of_payment,cheque_no,cheque_date,bank_name,paid_entry)VALUES(:student_id,:income_or_expense,:amount,:total_balance,:date_of_transaction,:daily_date,:mode_of_payment,:cheque_no,:cheque_date,:bank_name,:paid_entry)");
						$executed_tran=$stmt_tran->execute(array(':student_id' => $student_id,':income_or_expense' => "Income",':amount' => $paid_amount,':total_balance' => $paid_amount,':date_of_transaction' => $date_of_transaction,':daily_date' => date('d-m-Y'),':mode_of_payment' => $mode_of_payment,':cheque_no' => empty($cheque_no)?'-':$cheque_no,':cheque_date' => empty($cheque_date)?'-':$cheque_date,':bank_name' => empty($bank_name)?'-':$bank_name,':paid_entry' => $paid_entry));
						if($executed_tran)
						{
							$stmt_payment_details = $conn->prepare("INSERT INTO student_payment_details(student_id,pay_amount,payment_date,mode_of_payment,cheque_no,cheque_date,bank_name,paid_entry)VALUES(:student_id,:pay_amount,:payment_date,:mode_of_payment,:cheque_no,:cheque_date,:bank_name,:paid_entry)");
							$executed_payment_details=$stmt_payment_details->execute(array(':student_id' => $student_id,':pay_amount' => $paid_amount,':payment_date' => $date_of_entry,':mode_of_payment' => $mode_of_payment,':cheque_no' => empty($cheque_no)?'-':$cheque_no,':cheque_date' => empty($cheque_date)?'-':$cheque_date,':bank_name' => empty($bank_name)?'-':$bank_name,':paid_entry' => $paid_entry));
							if($executed_payment_details)
							{
							?>
								<script>alert("Added Successfully");</script>
							<?php
							$mob_no=$student_number;
									
								//Code written on 06-09-2019 to check if sms sending is truned on then only the SMS will be sent
								$stmt_check_sms_on = $conn->prepare("SELECT * FROM login_details WHERE login_id=1");//1 because we want the first record
								$stmt_check_sms_on->execute();
								$row_check_sms_on = $stmt_check_sms_on->fetchAll(PDO::FETCH_ASSOC);
								if($row_check_sms_on[0]['sms_on_off']=="on")
								{
					
					
									//$sendsms = new sendsms("","PARAMT");  //API key, Sender
									//$sendsms->send_sms($mob_no,"Thanks,For joining PARAM TUTORIALS And Welcome in Param Family.");
									//func_sms_count();
								}
							}
						}

					}
				   
					//$stud_id = $conn->lastInsertId();
					for($p=0;$p<count($add_edu['class']);$p++)
					{
						$stmt1 = $conn->prepare("INSERT INTO `edu_performance`(`stud_id`,`class`,`school_clg`,`marks`,`percentage`)VALUES(:stud_id,:class,:school_clg,:marks,:percentage)");

						$executed1=$stmt1->execute(array('stud_id' => $student_id,'class' => $add_edu['class'][$p],'school_clg' => $add_edu['school_clg'][$p],':marks' => $add_edu['marks'][$p],':percentage' => $add_edu['percentage'][$p]));
					}
					for($j=0;$j<count($add_family['name']);$j++)
					{
						$stmt2 = $conn->prepare("INSERT INTO `family_info`(`stud_id`,`name`,`education`,`occupation`,`contact_no`)VALUES(:stud_id,:name,:education,:occupation,:contact_no)");

						$executed2=$stmt2->execute(array('stud_id' => $student_id,'name' => $add_family['name'][$j],'education' => $add_family['education'][$j],'occupation' => $add_family['occupation'][$j],':contact_no' => $add_family['contact_no'][$j]));
					}						
			   }
	}catch(Exception $e) {
	  echo 'Message: ' .$e->getMessage();
	}
}//exit;
?>
<!DOCTYPE html>
<html>
<head id="Head1">
 <title></title>
 <style type="text/css">
     body,html
     {
         height: 100%; 
         width: 100%;
     }
 </style>
 <style>
     th
     {
        text-align:center;            
     }
 </style>
 <script>
	$(document).ready(function () {
	//var a=123456.25;
//var b=number2text(a);
//alert(b);
  //grossfunc();
});
//grossfunc();
function grossfunc(a) 
	{
			//var a=123456.25;
var b=number2text(a);
//alert(b);
document.getElementById('grossinwords').innerHTML = b;
	}
	function number2text(value) 
	{
    var fraction = Math.round(frac(value)*100);
    var f_text  = "";

    if(fraction > 0) {
        f_text = "AND "+convert_number(fraction)+" PAISE";
    }

    return convert_number(value)+" RUPEE "+f_text+" ONLY";
	
   }

function frac(f) {
    return f % 1;
}

function convert_number(number)
{
    if ((number < 0) || (number > 999999999)) 
    { 
        return "NUMBER OUT OF RANGE!";
    }
    var Gn = Math.floor(number / 10000000);  /* Crore */ 
    number -= Gn * 10000000; 
    var kn = Math.floor(number / 100000);     /* lakhs */ 
    number -= kn * 100000; 
    var Hn = Math.floor(number / 1000);      /* thousand */ 
    number -= Hn * 1000; 
    var Dn = Math.floor(number / 100);       /* Tens (deca) */ 
    number = number % 100;               /* Ones */ 
    var tn= Math.floor(number / 10); 
    var one=Math.floor(number % 10); 
    var res = ""; 

    if (Gn>0) 
    { 
        res += (convert_number(Gn) + " CRORE"); 
    } 
    if (kn>0) 
    { 
            res += (((res=="") ? "" : " ") + 
            convert_number(kn) + " LAKH"); 
    } 
    if (Hn>0) 
    { 
        res += (((res=="") ? "" : " ") +
            convert_number(Hn) + " THOUSAND"); 
    } 

    if (Dn) 
    { 
        res += (((res=="") ? "" : " ") + 
            convert_number(Dn) + " HUNDRED"); 
    } 


    var ones = Array("", "ONE", "TWO", "THREE", "FOUR", "FIVE", "SIX","SEVEN", "EIGHT", "NINE", "TEN", "ELEVEN", "TWELVE", "THIRTEEN","FOURTEEN", "FIFTEEN", "SIXTEEN", "SEVENTEEN", "EIGHTEEN","NINETEEN"); 
var tens = Array("", "", "TWENTY", "THIRTY", "FOURTY", "FIFTY", "SIXTY","SEVENTY", "EIGHTY", "NINETY"); 

    if (tn>0 || one>0) 
    { 
        if (!(res=="")) 
        { 
            res += " AND "; 
        } 
        if (tn < 2) 
        { 
            res += ones[tn * 10 + one]; 
        } 
        else 
        { 

            res += tens[tn];
            if (one>0) 
            { 
                res += ("-" + ones[one]); 
            } 
        } 
    }

    if (res=="")
    { 
        res = "zero"; 
    } 
    return res;
	
}

</script>
</head>
<body>
<script type="text/javascript">
	function PrintDiv() {
		var divContents = document.getElementById("dvContents").innerHTML;
		var printWindow = window.open('', '', 'height=600,width=900');
		printWindow.document.write('<html><head><title></title>');
		printWindow.document.write('</head><body >');
		printWindow.document.write(divContents);
		printWindow.document.write('</body></html>');
		printWindow.document.close();
		printWindow.print();
	}
</script>
<div style="text-align:center">
	<input type="button" onclick="PrintDiv();" value="Print" />
</div>

<div id="dvContents" style="position:relative;height:100%;width:100%;font-weight:bold; font-family:Arial">
	<div style="border:1px solid #000;padding:10px;background:#ccc;">
		<div style="width:100%;text-align:center">
		   Receipt
		</div>
		<?php 
		 $stmt_company=$conn->prepare("SELECT * FROM company_details");
         $stmt_company->execute();
	     $row_company=$stmt_company->fetchAll(PDO::FETCH_ASSOC);
		?>
		<div style="width:100%;display:inline-flex">
			<div style="width:70%;text-align:left;line-height:1px;">
				<h2 style="letter-spacing:1px;font-weight:900;font-size:30px;"><?php echo $row_company[0]['company_name']; ?> </h2>
				<h4><?php echo $row_company[0]['institue_for']; ?></h4>
			</div>
			<div style="width:30%;text-align:right;line-height:1px;">
				<p>M.: <?php echo $row_company[0]['mobile']; ?></p>
				<p>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $row_company[0]['alt_mobile']; ?></p>
			</div>
		</div>
		<div style="width:100%;display:inline-flex">
			<div style="width:33.3%;float:left">
			<?php $num1 = $conn->query("select count(student_id) from student_details")->fetchColumn();?>
				<div style="border:1px solid #000;padding:10px;width:80%;">
					Receipt No. :&nbsp;<?php echo $num1 ;?>
				</div>
			</div>
			<div style="width:33.3%;text-align:left;">
			    	<?php 
						//echo $row[$i]['course_name']; 
						//get course name using course id
						$stmt_course=$conn->prepare("SELECT * FROM course_details WHERE course_details_id=".$course_details_id);
						$stmt_course->execute();
						$row_course=$stmt_course->fetchAll(PDO::FETCH_ASSOC);
						//echo $row_course[0]['course_name']; 
					?>
				<div style="border:1px solid #000;padding:10px;width:80%;">
					Course :&nbsp;<?php echo $row_course[0]['course_name']; ?>
				</div>
			</div>
			<div style="width:33.3%;float:right;">
				<div style="border:1px solid #000;padding:10px;width:80%;">
					Date :&nbsp;<?php echo $day."-".$month."-".$year ?>
				</div>
			</div>
		</div>
		<div style="width:100%;margin:20px 0;line-height:25px;">
			<div style="display:inline-flex;width:100%;">
				<div style="width:26%">
					Received with thanks from 
				</div>
				<div style="width:75%;border-bottom:1px solid #000;">
					<?php echo $student_name; ?>
				</div>
			</div>
			<div style="display:inline-flex;width:100%;">
				<div style="width:18%">
					the sum of Rupees 
				</div>
				<div style="width:82%;border-bottom:1px solid #000;">
					<div id="grossinwords" >
						<?php echo "<script>grossfunc($paid_amount);</script>";?>
					</div>
				</div>
			</div>
			<div style="display:inline-flex;width:100%;">
				<div style="width:75%;border-bottom:1px solid #000;">
					<?php echo $mode_of_payment;?>
				</div>
				<div style="width:26%">
					only by Cheque/Cash/DD 
				</div>
			</div>
			<div style="display:inline-flex;width:100%;">
				<div style="width:5%">
					No.: 
				</div>
				<div style="width:15%;border-bottom:1px solid #000;">
					<?php echo $cheque_no;?>
				</div>
				<div style="width:7%">
					Date: 
				</div>
				<div style="width:15%;border-bottom:1px solid #000;">
					<?php echo $cheque_date;?>
				</div>
				<div style="width:15%">
					Name of Bank:
				</div>
				<div style="width:43%;border-bottom:1px solid #000;">
					<?php echo $bank_name;?>
				</div>
			</div>
			<div style="display:inline-flex;width:100%;">
				<div style="width:40%">
					in Part/Full/Advance Payment on A/C of
				</div>
				<div style="width:60%;border-bottom:1px solid #000;">
					<?php echo $paid_entry;?>
				</div>
			</div>
		</div>
		<div style="width:100%;display:inline-flex">
			<div style="width:33.3%">
				<div style="border:1px solid #000;padding:10px;width:100px;">
					Rs.&nbsp;<?php echo $paid_amount; ?>
				</div>
				<p style="font-size:7pt">Cheque Subject to realization</p>
			</div>
			<div style="width:33.3%;text-align:center;">
				<p style="font-size:10pt;font-weight:800;margin-top:30px">Counter Signature</p>
			</div>
			<div style="width:33.3%;text-align:center;">
				<p style="font-size:10pt;font-weight:800;margin-top:30px">Authorized Signature</p>
			</div>
		</div>
		<div style="width:100%;display:inline-flex">
			<div style="width:33.3%">
				Next Installment:
			</div>
			<div style="width:33.3%;display:inline-flex">
				Date:&nbsp;<?php echo $next_date; ?>
			</div>
			<div style="width:33.3%">
			    Amount:&nbsp;<?php echo $balance_amount; ?>
			</div>
		</div>
		<div style="width:100%;display:inline-flex">
				<p style="font-size:9pt;">Note: <span style="font-size:7pt;padding:0px;margin:5px;"> Received amount not transferable/refundable in any circumstances.</span></p>
		
		</div>
	</div>
</div>

</body>
</html>