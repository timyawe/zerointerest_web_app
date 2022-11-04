<?php include "login_session.inc"; ?>
<html>
	<head>
		<title>Loan Details</title>
		<script src="table_filters.js" type="text/javascript"></script>
		<link href="page_styles.css" type="text/css" rel="stylesheet" />
		<link href="heading_styles.css" type="text/css" rel="stylesheet" />
		<link href="form_styles.css" type="text/css" rel="stylesheet" />
		<link href="links_styles.css" type="text/css" rel="stylesheet" />
		<link href="table_styles.css" type="text/css" rel="stylesheet" />
		<link href="text_styles.css" type="text/css" rel="stylesheet" />
		<style>
			.col-75 {
				/*padding: 5px;*/color: blue;
			}
		</style>
		<script>
			function back() {
				window.history.back();
			}
		</script>
	</head>
		<body onload="total_principal_amounts(), total_principalint_amounts(), total_amountpaid(), 
		highlight_confirmed_payments (), total_payments_amounts(), total_amountdue_amounts()">
			<!-- Begining of header section -->
				<div id="header"> 
					<h1>ZERO INTEREST FINANCE LIMITED</h1>
					<div id="nav">
					<a href="main_page.php">Home</a>
					<a href="customers_page.php">Customers Page</a>
					<a href="loans_page.html">Loans Page</a>
					</div>
				</div>
			<!-- End of header section -->
			<div id="loan-view-section">
			<?php
			//connect to database
			include "dbconn.php";
			
			$loan_sql = "SELECT * FROM `Customers with Loans` WHERE LOAN_NO =" . $_REQUEST['loan_no'] ;
			
			$loan_result = mysqli_query($conn, $loan_sql);
			
			//Display results in a form
			if (mysqli_num_rows($loan_result) > 0) {
				$loan_row = mysqli_fetch_assoc($loan_result);
				
				$_SESSION['loan_no'] = $loan_row['LOAN_NO'];
				$_SESSION['customer_name'] = $loan_row['CUSTOMER_NAME'];
				$principal = number_format($loan_row['PRINCIPAL']);
				$f_prinint = number_format($loan_row['FINAL_PRINCIPAL_&_INTEREST']);
				$startdate = date("d/m/Y", strtotime($loan_row['START_DATE']));
				$enddate = date("d/m/Y", strtotime($loan_row['FINAL_PAYMENT_DATE']));
				
$loan_display_form =<<<FORM
		<button style="float: right" onclick="back()">Back</button>
		<h2 style="margin-bottom: 0px; padding-left: 10px; text-align: center;">Loan Details of {$loan_row['CUSTOMER_NAME']} 
		</h2>
		<div id="loan-view-form">
			<form>
				<div id="loan-view-left">
					<div class="row">
						<div class="col-25"><label>LoanNo:</label></div>
						<div class="col-75"><input type="text" style="text-align:right;" value="{$loan_row['LOAN_NO']}"></div>
					</div>
					
					<div class="row">
						<div class="col-25"><label>Loan RefNo:</label></div>
						<div class="col-75"><input type="text" style="text-align:right;" value="{$loan_row['LOAN_REF_NO']}"></div>
					</div>
					
					<div class="row">
						<div class="col-25"><label>Customer RefNo:</label></div>
						<div class="col-75"><input type="text" style="text-align:right;" value="{$loan_row['REF_NO']}"></div>
					</div>
					
					<div class="row">
						<div class="col-25"><label>Customer Name:</label></div>
						<div class="col-75"><input type="text" style="text-align:right;" value="{$loan_row['CUSTOMER_NAME']}"></div>
					</div>
					
					<div class="row">
						<div class="col-25"><label>Principal:</label></div>
						<div class="col-75"><input type="text" style="text-align:right;" value="{$principal}"></div>
					</div>
					
					<div class="row">
						<div class="col-25"><label>Interest Rate:</label></div>
						<div class="col-75"><input type="text" style="text-align:right;" value="{$loan_row['INTERST_RATE']}"></div>
					</div>
					
					<div class="row">
						<div class="col-25"><label>Final Principal & Interest:</label></div>
						<div class="col-75"><input type="text" style="text-align:right;" value="{$f_prinint}"></div>
					</div>
				</div>
				
				<div id="loan-view-right">
					<div class="row">
						<div class="col-25"><label>Start Date:</label></div>
						<div class="col-75"><input type="text" style="text-align:right;" value="{$startdate}"></div>
					</div>
					
					<div class="row">
						<div class="col-25"><label>Period:</label></div>
						<div class="col-75"><input type="text" style="text-align:right;" value="{$loan_row['PERIOD']}"></div>
					</div>
					
					<div class="row">
						<div class="col-25"><label>Final Date</label></div>
						<div class="col-75"><input type="text" style="text-align:right;" value="{$enddate}"></div>
					</div>
					
					<div class="row">
						<div class="col-25"><label>Loan Status</label></div>
						<div class="col-75"><input type="text" style="text-align:right;" value="{$loan_row['STATUS']}"></div>
					</div>
				
				</div>
				
			</form>
			
		</div>
FORM;
			} else {
				die ("No records to display");
			}
			
			
			echo $loan_display_form;
			
			
			$instalment_sql = "SELECT * FROM Instalments WHERE LoanNo = " . $_REQUEST['loan_no'];
			
			$instalment_result = mysqli_query($conn, $instalment_sql);
			$instalment_tablehead = <<<TABLE
						<div class="instalment-table">
						<hr>
						
						<table border="1">
						<caption style="text-align: center;">Instalments</caption>
							<tr>
							<th>No</th>
							<th>Start Date</th>
							<th>Amount</th>
							<th>End Date</th>
							<th>Elapsed Days</th>
							<th>Penalty</th>
							<th>Accum. Penalty</th>
							<th>Amount Payable</th>
							<th>Paid</th>
							<th>Amount Due</th>
							<th>Instalment Type</th>
							<th>Status</th>
							<th>Cleared Date</th>
							</tr>
TABLE;
			if (mysqli_num_rows($instalment_result) > 0 ) {
				
				echo $instalment_tablehead;

				while ($instalment_row=mysqli_fetch_assoc($instalment_result)) {
					
					$_SESSION['InstalmentID'] = $instalment_row['Instalment_ID'];
					if ($instalment_row['Instalment_ClearedDate'] == "" ) {
						$cleareddate = "";
					} else {
						$cleareddate = date("d/m/Y", strtotime($instalment_row['Instalment_ClearedDate']));
					}
					
					

			
					//$instalment_tabledetails .=<<<TABLE
					echo	"<tr>";
					echo	"<td>{$instalment_row['Instalment_No']}</td>";
					echo	"<td>".date("d/m/Y", strtotime($instalment_row['Instalment_StartDate']))."</td>";
					echo	"<td class='loan_principal'>".number_format($instalment_row['TotalAmount'])."</td>";
					echo	"<td>".date("d/m/Y", strtotime($instalment_row['Instalment_EndDate']))."</td>";
					echo	"<td style='text-align: center'>{$instalment_row['ElapsedDays']}</td>";
					echo	"<td style='text-align: right'>".number_format($instalment_row['Penalty'])."</td>";
					echo	"<td style='text-align: right'>".number_format($instalment_row['AccumulatedPenalty'])."</td>";
					echo	"<td class='loan_principal&int'>".number_format($instalment_row['AmountPayable'])."</td>";
					echo	"<td class='loan_amountpaid'>".number_format($instalment_row['PaidAmount'])."</td>";
					echo	"<td class='loan_amountdue' style='text-align: right'>".number_format($instalment_row['AmountDue'])."</td>";
					echo	"<td>{$instalment_row['Instalment_Type']}</td>";
					echo	"<td>{$instalment_row['Instalment_Status']}</td>";
					echo	"<td>".$cleareddate."</td>";
					/*echo	"<td><a href='loaninstalments_handler.php?instalment_ID={$_SESSION['InstalmentID']}'
						onclick=".'"'."window.open('loaninstalments_handler.php?instalment_ID={$_SESSION['InstalmentID']}', 
								'popup', 'width=640, height=540', 'location=center'); return false;".'"'.
								">Edit</a></td>";*/
					echo	"</tr>";
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
						 </tr>";
					//$instalment_tablefooter = "</table>";
			} else {
				echo mysqli_error($conn);
				echo $instalment_tablehead;
				echo "<tr><td colspan='15' style='text-align: center'>This loan has no instalments</td></tr>";
			}
//TABLE;
		//	echo $instalment_tabledetails;
			echo "</table>";//$instalment_tablefooter;
			echo "</div>";
			
				
			$payments_sql = "SELECT PAYMENT_DATE, PAID_AMOUNT, PAYMENT_TYPE, PAYMENT_CONFIRMED, LOAN_NO from `Payments with Customers`
			WHERE LOAN_NO = " . $_REQUEST['loan_no'];
			
			$payments_result = mysqli_query($conn, $payments_sql);
			
			
				
					
					$payments_tablehead = <<<TABLE
						<div class="payment-table">
						<hr>
						<h2 style="text-align: center;">Payments</h2>
						
						<table border="1">
							<tr>
							<th>Payment Date</th>
							<th>Amount</th>
							<th>Payment Type</th>
							<th>Comfirmed</th>
							</tr>
TABLE;
				
					$payments_tablefooter = "</table>"."</div>";
			
			if (mysqli_num_rows($payments_result) > 0 ) {
				echo $payments_tablehead;
			while ($payments_row=mysqli_fetch_assoc($payments_result)) {
				//$payments_tabledetails .=<<<TABLE
					echo	"<tr>";
					echo	"<td>".date("d/m/Y", strtotime($payments_row['PAYMENT_DATE']))."</td>";
					echo	"<td class='payments_total' style='text-align: right'>".number_format($payments_row['PAID_AMOUNT'])."</td>";
					echo	"<td>{$payments_row['PAYMENT_TYPE']}</td>";
					if ($payments_row['PAYMENT_CONFIRMED'] == 1) { $paymentconf = "Yes"; } else {$paymentconf = "No";}
					echo	"<td class='payments_confirmed'>{$paymentconf}</td>";
					echo	"</tr>";
					
//TABLE;
				//echo $payments_tabledetails;
			}
			echo "<tr class='totals_row'>
					<td>Total</td>
					<td id='total' style='text-align: right'></td>
					<td></td>
					<td></td>
				</tr>";
			} else {
				echo "<div class='payment-table'><h2 style='text-align: center;'>Payments</h2><table border='1'>
				<tr><th>No Payments</th></tr><tr><td style='text-align: center'>This loan has no payments</td></tr>";
				//mysqli_error($conn);
			}
			echo $payments_tablefooter;			
			
			?>
			</div>
		</body>
</html>