<?php include "login_session.inc"; ?>
<html>
	<head>
	<title>Instalment Payments</title>
	<script src="table_filters.js" type="text/javascript"></script>
	<link href="table_styles.css" type="text/css" rel="stylesheet" />
	<link href="links_styles.css" type="text/css" rel="stylesheet" />
	<link href="text_styles.css" type="text/css" rel="stylesheet" />
	</head>
		<body onload="total_payments_amounts(), highlight_confirmed_payments ()">
			<?php
				//Connect to database
				include "dbconn.php";
				
				$sql = "SELECT * FROM `Payment Details` WHERE InstalmentID =" . $_REQUEST['instalment_ID'];
				
				$result = mysqli_query($conn, $sql);
				
				if (mysqli_num_rows($result) > 0) {
					$tablehead = <<<TABLE
							<h1>Instalment Payments</h1>
							<table border="1">
								<tr>
									<th>PAYMENT ID</th>
									<th>PAYMENT DATE</th>
									<th>AMOUNT</th>
									<th>PAYMENT TYPE</th>
									<th>PAYMENT CONFIRMED</th>
									<th>LOAN NO</th>
									<th>EDIT PAYMENT</th>
								</tr>
TABLE;
echo $tablehead;
					while ($row = mysqli_fetch_assoc($result)) {
						$p_date = date("d/m/Y", strtotime($row['PAYMENT_DATE']));
						$p_amount = number_format($row['PAID_AMOUNT']);
						if ($row['PAYMENT_CONFIRMED'] == 1) {
							$p_confmd = "Yes";
						} else {
							$p_confmd = "No";
						}

							echo "<tr>";
							echo 	"<td>".$row['PAYMENT_ID']."</td>";
							echo 	"<td>".$p_date."</td>";
							echo 	"<td class='payments_total' style='text-align: right'>".$p_amount."</td>";
							echo 	"<td>".$row['PAYMENT_TYPE']."</td>";
							echo 	"<td class='payments_confirmed'>".$p_confmd."</td>";
							echo 	"<td style='text-align:center;'>".$row['LOAN_NO']."</td>";
							echo 	"<td><a href='paymentdetails_handler.php?payment_ID={$row['PAYMENT_ID']}' 
								onclick=".'"'."window.open('paymentdetails_handler.php?payment_ID={$row['PAYMENT_ID']}', 
								'popup', 'width=360, height=300', 'location=center'); return false;".'"'.
								">Edit Payment</a></td>";
							echo "</tr>";
						
					}
					echo "<tr class='totals_row'>
							<td colspan='2'>Total</td>
							<td id='total' style='text-align: right'></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						 </tr>";
					echo "</table>";
				} else {
					echo "No Records";
				}
			?>
		</body>
</html>
