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
				
				$sql = "SELECT * FROM `Payment Details` WHERE InstalmentID =" . $_REQUEST['instalment_ID'] . " ORDER BY PAYMENT_DATE ASC";
				
				$result = mysqli_query($conn, $sql);
				$tablehead = <<<TABLE
							<h3>Instalment Payments</h3>
							<table border="1">
								<tr>
									<th>ID</th>
									<th>DATE</th>
									<th>AMOUNT</th>
									<th>TYPE</th>
									<th>CONFIRMED</th>
									<th>LOAN NO</th>
									<th>EDIT</th>
								</tr>
TABLE;
				if (mysqli_num_rows($result) > 0) {
					echo $tablehead;
					$sum_pymt = 0;
					if (isset($_SESSION['add_payment'])) {
						echo "<p>Payment Added</p>";
						unset($_SESSION['add_payment']);
					}
				
					if (isset($_SESSION['noadd_payment'])) {
						echo "<p>Sorry, the record was not added, please try again. <br/> Contact Administrator if issue persists. </p>";
						echo "Error: ". $_SESSION['noadd_payment'];
						unset($_SESSION['noadd_payment']);
					}
					
					if (isset($_SESSION['update_payment_error'])) {
						echo  $_SESSION['update_payment_error'];
						unset($_SESSION['update_payment_error']);
					}
				
					if (isset($_SESSION['loanamount_updated'])) {
						echo  $_SESSION['loanamount_updated'];
						unset($_SESSION['loanamount_updated']);
					}
					
					if (isset($_SESSION['cleared_inst'])) {
						echo  $_SESSION['cleared_inst'];
						unset($_SESSION['cleared_inst']);
					}
					
				
					while ($row = mysqli_fetch_assoc($result)) {
						$p_date = date("d/m/Y", strtotime($row['PAYMENT_DATE']));
						$p_amount = $row['PAID_AMOUNT'];
						if ($row['PAYMENT_CONFIRMED'] == 1) {
							$p_confmd = "Yes";
						} else {
							$p_confmd = "No";
						}

							echo "<tr>";
							echo 	"<td>".$row['PAYMENT_ID']."</td>";
							echo 	"<td>".$p_date."</td>";
							echo 	"<td class='payments_total' style='text-align: right'>".number_format($p_amount)."</td>";
							echo 	"<td>".$row['PAYMENT_TYPE']."</td>";
							echo 	"<td class='payments_confirmed'>".$p_confmd."</td>";
							echo 	"<td style='text-align:center;'>".$row['LOAN_NO']."</td>";
							/*echo 	"<td><a href='paymentdetails_handler.php?payment_ID={$row['PAYMENT_ID']}' 
								onclick=".'"'."window.open('paymentdetails_handler.php?payment_ID={$row['PAYMENT_ID']}', 
								'popup', 'width=360, height=300', 'location=center'); return false;".'"'.
								">Edit Payment</a></td>";*/
							echo	"<td><button type='button' class='tbl_btn' onclick='inject_pymt_edit_form(this)'>Edit</button></td>";
							echo "</tr>";
						$sum_pymt += $p_amount;
					}
					echo "<tr class='totals_row'>
							<td colspan='2'>Total</td>
							<td id='total' style='text-align: right'>".number_format($sum_pymt)."</td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						 </tr>";
				} else {
					echo $tablehead;
					echo "<tr><td colspan='7' style='text-align: center'>No Payments Yet</td></tr>";
				}
				echo "</table>";
			?>
		</body>
</html>
