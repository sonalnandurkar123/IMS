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
if(isset($_POST['payment']))
{
	//print_r($_POST);exit;
	extract($_POST);
	
try{
	$new_paid_amount=$paid_amount+$paying_now;
	//echo $new_paid_amount;exit;
	$new_balance_amount=$balance_amount-$paying_now;
	//update students table
	$stmt_update = $conn->prepare("UPDATE student_details SET paid_amount=:paid_amount,balance_amount=:balance_amount,next_date=:next_date WHERE student_id=$student_id");

	$execute_update=$stmt_update->execute(array(':paid_amount' => $new_paid_amount,':balance_amount' => $new_balance_amount,':next_date' => $next_date));
	if($execute_update)
	{
		$stmt=$conn->prepare("SELECT * FROM daily_transaction ORDER BY daily_transaction_id DESC");
		$stmt->execute();
		$row=$stmt->fetchAll(PDO::FETCH_ASSOC);
		if($row)
		{
			$old_amount=$row[0]['total_balance'];
			//echo $old_amount;exit;
			$new_amount=$old_amount+$paying_now;
			//insert data in daily transaction
			$new_date_of_transaction=date('Y-m-d',strtotime($date_of_transaction))." ".date('H:i:s');
			//echo $new_date_of_transaction;exit;
			$stmt_tran = $conn->prepare("INSERT INTO daily_transaction(student_id,income_or_expense,amount,total_balance,date_of_transaction,daily_date,mode_of_payment,cheque_no,cheque_date,bank_name,paid_entry)VALUES(:student_id,:income_or_expense,:amount,:total_balance,:date_of_transaction,:daily_date,:mode_of_payment,:cheque_no,:cheque_date,:bank_name,:paid_entry)");
			$executed_tran=$stmt_tran->execute(array(':student_id' => $student_id,':income_or_expense' => "Income",':amount' => $paying_now,':total_balance' => $new_amount,':date_of_transaction' => $new_date_of_transaction,':daily_date' => date('d-m-Y'),':mode_of_payment' => $mode_of_payment,':cheque_no' => empty($cheque_no)?'-':$cheque_no,':cheque_date' => empty($cheque_date)?'-':$cheque_date,':bank_name' => empty($bank_name)?'-':$bank_name,':paid_entry' => $paid_entry));
			if($executed_tran)
			{
				$stmt_payment_details = $conn->prepare("INSERT INTO student_payment_details(student_id,pay_amount,payment_date,mode_of_payment,cheque_no,cheque_date,bank_name,paid_entry)VALUES(:student_id,:pay_amount,:payment_date,:mode_of_payment,:cheque_no,:cheque_date,:bank_name,:paid_entry)");
				$executed_payment_details=$stmt_payment_details->execute(array(':student_id' => $student_id,':pay_amount' => $paying_now,':payment_date' => date('d-m-Y'),':mode_of_payment' => $mode_of_payment,':cheque_no' => empty($cheque_no)?'-':$cheque_no,':cheque_date' => empty($cheque_date)?'-':$cheque_date,':bank_name' => empty($bank_name)?'-':$bank_name,':paid_entry' => $paid_entry));
				if($executed_payment_details)
				{
				?>
					<script>alert("Added Successfully");</script>
				<?php
				}
			}

		}
	}
	}catch(Exception $e) {
	  echo 'Message: ' .$e->getMessage();
	}
}

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
				<h2 style="letter-spacing:1px;font-weight:900;font-size:30px;"><?php echo $row_company[0]['company_name']; ?></h2>
				<h4><?php echo $row_company[0]['institue_for']; ?></h4>
			</div>
			<div style="width:30%;text-align:right;line-height:1px;">
				<p>M.: <?php echo $row_company[0]['mobile']; ?></p>
				<p>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $row_company[0]['alt_mobile']; ?></p>
			</div>
		</div>
		<div style="width:100%;display:inline-flex">
			<div style="width:33.3%;float:left">
			<?php $num = $conn->query("select count(payment_id) from student_payment_details")->fetchColumn();?>
				<div style="border:1px solid #000;padding:10px;width:80%;">
					Receipt No. :&nbsp;<?php echo $num ;?>
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
					Date :&nbsp;<?php echo $date_of_transaction ?>
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
					<div id="grossinwords" style="border-bottom: 1px solid #000;">
						<?php echo "<script>grossfunc($paying_now);</script>";?>
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
					Rs.&nbsp;<?php echo $paying_now; ?>
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
				Date:&nbsp;<?php echo $next_date;?>
			</div>
			<div style="width:33.3%">
			   Amount:&nbsp;<?php echo $new_balance_amount; ?>
			</div>
		</div>
		<div style="width:100%;display:inline-flex">
				<p style="font-size:9pt;">Note: <span style="font-size:7pt;padding:0px;margin:5px;"> Received amount not transferable/refundable in any circumstances.</span></p>
		
		</div>
	</div>
</div>

</body>
</html>