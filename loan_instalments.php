<?php 
	include "login_session.inc"; 
	//Connect to database
	include "dbconn.php";
	
	$sql = "SELECT * FROM Instalments WHERE LoanNo =" . $_SESSION['loan_no'];
	$customer_namerow = mysqli_fetch_assoc(mysqli_query($conn,"SELECT CUSTOMER_NAME FROM `Customers with Loans` WHERE LOAN_NO=".$_SESSION['loan_no']));
	$no_of_cleared_insts = mysqli_num_rows(mysqli_query($conn, "SELECT LoanNo FROM Instalments WHERE Instalment_Status = 'Cleared' AND LoanNo =" . $_SESSION['loan_no']." ORDER BY Instalment_ID DESC LIMIT 1"));
		$customer_name = $customer_namerow['CUSTOMER_NAME'];
	
	$result = mysqli_query($conn, $sql);
	
	$tablehead = <<<TABLE
						
						<table border="1">
							<tr>
								<th>No</th>
								<th>Start Date</th>
								<th>Amount</th>
								<th>End Date</th>
								<th class='empty_hide' hidden>Elapsed Days</th>
								<th class='empty_hide' hidden>Penalty</th>
								<th class='empty_hide' hidden>Accumulated Penalty</th>
								<th>Payable</th>
								<th>Paid</th>
								<th>Due</th>
								<th>Type</th>
								<th>Status</th>
								<th class='empty_hide' hidden>Cleared Date</th>
								<th>Edit</th>
								<th>Add Payment</th>
								<th>View Payments</th>
							</tr>
TABLE;
	
?>
<html>
	<head>
	<title>Loan Instalments</title>
	<script src="table_filters.js" type="text/javascript"></script>
	<link href="table_styles.css" type="text/css" rel="stylesheet" />
	<link href="links_styles.css" type="text/css" rel="stylesheet" />
	<link href="form_styles.css" type="text/css" rel="stylesheet" />
	<link href="text_styles.css" type="text/css" rel="stylesheet" />
	<style>
		#new_inst, .tbl_btn{
			/*background-color: #52B2A0;*/
			padding: 7px 7px;
			border-radius: 4px;
			/*color: #ffffff;
			font-family: calibri;
			font-size: 14;
			margin-top: 12px;*/
			border: 1px solid #52B2A0;/*#ccc;*/
			cursor: pointer;
			outline: none;
		}					

		#new_inst:hover {
			background-color: rgba(27,233,179,0.9);
			color: white;
			border-color: #111;
		}
	</style>
	
	</head>
		<body onload="total_principal_amounts(), total_principalint_amounts(), total_amountpaid(), 
		total_amountdue_amounts()" >
			<?php
			
				echo "<div id='inst_content'>";
					echo "<div style='margin-bottom: 0px'>";
							if (isset($_SESSION['add_instalment'])) {
								echo "<p class='alert-response-success'>Instalment Added</p>";
								unset($_SESSION['add_instalment']);
							}
							
							if (isset($_SESSION['noadd_instalment'])) {
								echo "<span class='alert-response-error'>Alert: Instalment Not Added" . "Error: " . $_SESSION['noadd_instalment']. "</span>";
								unset($_SESSION['noadd_instalment']);
							}
							
							if (isset($_SESSION['edit_instalment'])) {
								echo "<span class='alert-response-success'>Instalment Edited</span>";
								unset($_SESSION['edit_instalment']);
							}
								
							if (isset($_SESSION['noedit_instalment'])) {
								echo "<span class='alert-response-failure'>No fields were edited</span>";
								unset($_SESSION['noedit_instalment']);
							}
							
							if (isset($_SESSION['not_rescheduled'])) {
								echo $_SESSION['not_rescheduled'];
								unset($_SESSION['not_rescheduled']);
							}
							
							if (isset($_SESSION['loan_updated_by_instalment'])) {
								echo "<p class='alert-response-information'>This instalment has updated loan provisional period and end date details</p>";
								unset($_SESSION['loan_updated_by_instalment']);
							}
							
							if (isset($_SESSION['no_payments'])) {
								echo $_SESSION['no_payments'];
								unset($_SESSION['no_payments']);
							}
					echo "</div>";
					echo "<div>";	
							if($no_of_cleared_insts == 0){
								echo "<button type='button' id='new_inst' onclick='inject_inst_Form()'>Add Instalment</button>"; 
							}else{
								echo "<span style='background-color: red; color:white;'>You cannot add new instalments because loan is cleared</span>";
							}
					echo "</div>";
					if (mysqli_num_rows($result) > 0) {
						
						//echo "<h1> Loan Instalments of " . $customer_name . ", Loan #".$_SESSION['loan_no']."</h1>";						
					
						echo $tablehead;

						while ($row = mysqli_fetch_assoc($result)) {
								$totalamount = number_format($row['TotalAmount']);
								$penalty = number_format($row['Penalty']);
								$acc_penalty = number_format($row['AccumulatedPenalty']);
								$amountpayable = number_format($row['AmountPayable']);
								$paidamount = number_format($row['PaidAmount']);
								$amountdue = number_format($row['AmountDue']);
								//$_SESSION['instalment_ID'] = $row['Instalment_ID'];
								
						echo "<tr>";
						echo	"<td hidden>".$row['Instalment_ID']."</td>";
						echo 	"<td>".$row['Instalment_No']."</td>";
						echo 	"<td>".date("d/m/Y", strtotime($row['Instalment_StartDate']))."</td>";
						echo 	"<td class='loan_principal'>".$totalamount."</td>";
						echo 	"<td>".date("d/m/Y", strtotime($row['Instalment_EndDate']))."</td>";
						echo 	"<td class='empty_hide' hidden>".$row['ElapsedDays']."</td>";
						echo 	"<td class='empty_hide' hidden>".$penalty."</td>";
						echo 	"<td class='empty_hide' hidden>".$acc_penalty."</td>";
						echo 	"<td class='loan_principal&int'>".$amountpayable."</td>";
						echo	"<td class='loan_amountpaid'>".$paidamount."</td>";
						echo 	"<td class='loan_amountdue'>".$amountdue."</td>";
						echo 	"<td>".$row['Instalment_Type']."</td>";
						echo 	"<td>".$row['Instalment_Status']."</td>";
						echo 	"<td class='empty_hide' hidden>".$row['Instalment_ClearedDate']."</td>";
						if($row['Instalment_Status'] == "Cleared" || $row['Instalment_Status'] == "Rescheduled"){
							echo	"<td><button type='button' class='tbl_btn' onclick='edit_inst(this)' disabled>Edit</button></td>";
							echo	"<td><button type='button' class='add_btn tbl_btn' onclick='inject_new_payment_form(this)' disabled>Payment</button></td>";
						}else{
							echo	"<td><button type='button' class='tbl_btn' onclick='edit_inst(this)'>Edit</button></td>";
							echo	"<td><button type='button' class='add_btn tbl_btn' onclick='inject_new_payment_form(this)'>Payment</button></td>";
						}
						//echo 	"<td><a href='payment_form.php'
						//onclick=".'"'."window.open('payment_form.php?instalment_ID={$row['Instalment_ID']}','popup','width=360,height=300');
						//return false;".'"'.">Add Payment</a></td>";
						//echo 	"<td><a href='instalment_payments.php?instalment_ID={$row['Instalment_ID']}'>View Payments</a></td>";
						echo	"<td><button type='button' class='tbl_btn' onclick='view_inst_pymts(this)'>View Payments</button></td>";
						echo "</tr>";
						}
						
						echo "<tr class='totals_row'>
								<td colspan='2'>Total</td>
								<td class='principal_total'></td>
								<td class='empty_hide' hidden></td>
								<td class='empty_hide' hidden></td>
								<td class='empty_hide' hidden></td>
								<td></td>
								<td class='principal&int_total'></td>
								<td class='amountpaid_total'></td>
								<td class='amountdue_total' style='text-align: right'></td>
								<td></td>
								<td></td>
								<td class='empty_hide' hidden></td>
								<td></td>
								<td></td>
								<td></td>
							 </tr>";
						
					} else {
						echo $tablehead;
						echo "<tr><td colspan='15' style='text-align:center'>No Instalments yet</td></tr>";
					}

					echo "</table>";
				echo "</div>";
			?>
		</body>
		
		<script>
			function injectForm(){
				/*fetch("instalment_form.php").then(function(response){
					return response.text();
				}).then(function(data){
					document.getElementById("inst_content").innerHTML = data;
				});*/
				alert("Okay");
			}
		</script>
</html>