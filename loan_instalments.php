<?php include "login_session.inc"; ?>
<html>
	<head>
	<title>Loan Instalments</title>
	<link href="table_styles.css" type="text/css" rel="stylesheet" />
	<link href="links_styles.css" type="text/css" rel="stylesheet" />
	<link href="text_styles.css" type="text/css" rel="stylesheet" />
	</head>
		<body>
			<?php
				
				//Connect to database
				include "dbconn.php";
				
				$sql = "SELECT * FROM Instalments WHERE LoanNo =" . $_SESSION['loan_no'];
				$customer_namerow = mysqli_fetch_assoc(mysqli_query($conn,"SELECT CUSTOMER_NAME FROM `Customers with Loans` WHERE LOAN_NO=".$_SESSION['loan_no']));
					$customer_name = $customer_namerow['CUSTOMER_NAME'];
				
				$result = mysqli_query($conn, $sql);
				
				if (mysqli_num_rows($result) > 0) {
					
echo "<h1> Loan Instalments of " . $customer_name . ", Loan #".$_SESSION['loan_no']."</h1>";						
				$tablehead = <<<TABLE
					
					<table border="1">
						<tr>
							<th>No</th>
							<th>Start Date</th>
							<th>Amount</th>
							<th>End Date</th>
							<th>Elapsed Days</th>
							<th>Penalty</th>
							<th>Accumulated Penalty</th>
							<th>Amount Payable</th>
							<th>Amount Paid</th>
							<th>Amount Due</th>
							<th>Instalment Type</th>
							<th>Status</th>
							<th>Cleared Date</th>
							<th>Add Payment</th>
							<th>View Payments</th>
						</tr>
TABLE;
echo $tablehead;

				while ($row = mysqli_fetch_assoc($result)) {
						$totalamount = number_format($row['TotalAmount']);
						$penalty = number_format($row['Penalty']);
						$acc_penalty = number_format($row['AccumulatedPenalty']);
						$amountpayable = number_format($row['AmountPayable']);
						$paidamount = number_format($row['PaidAmount']);
						$amountdue = number_format($row['AmountDue']);
						$_SESSION['instalment_ID'] = $row['Instalment_ID'];
						
					echo "<tr>";
					echo 	"<td>".$row['Instalment_No']."</td>";
					echo 	"<td>".date("d/m/Y", strtotime($row['Instalment_StartDate']))."</td>";
					echo 	"<td>".$totalamount."</td>";
					echo 	"<td>".date("d/m/Y", strtotime($row['Instalment_EndDate']))."</td>";
					echo 	"<td>".$row['ElapsedDays']."</td>";
					echo 	"<td>".$penalty."</td>";
					echo 	"<td>".$acc_penalty."</td>";
					echo 	"<td>".$amountpayable."</td>";
					echo	"<td>".$paidamount."</td>";
					echo 	"<td>".$amountdue."</td>";
					echo 	"<td>".$row['Instalment_Type']."</td>";
					echo 	"<td>".$row['Instalment_Status']."</td>";
					echo 	"<td>".$row['Instalment_ClearedDate']."</td>";
					echo 	"<td><a href='payment_form.php'
					onclick=".'"'."window.open('payment_form.php','popup','width=360,height=300');
					return false;".'"'.">Add Payment</a></td>";
					echo 	"<td><a href='instalment_payments.php?instalment_ID={$row['Instalment_ID']}'>View Payments</a></td>";
					echo "</tr>";
					}
					
					echo "<tr class='totals_row'>
							<td colspan='2'>Total</td>
							<td class='principal_total'></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td class='principal&int_total'></td>
							<td class='amountpaid_total'></td>
							<td class='amountdue_total' style='text-align: right'></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						 </tr>";
					echo "</table>";
				} else {
					echo "No Instalments";
				}

				
			?>
		</body>
</html>