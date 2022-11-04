<?php include "login_session.inc"; ?>
<html>
	<head>
		<title>Payment Details List</title>
		<!--<meta name="viewport" content="width=device-width initial-scale=1.0"/>-->
		<script src="table_filters.js" type="text/javascript"></script>
		<link href="heading_styles.css" type="text/css" rel="stylesheet" />
		<link href="lists_styles.css" type="text/css" rel="stylesheet" />
		<link href="table_styles.css" type="text/css" rel="stylesheet" />
		<link href="page_styles.css" type="text/css" rel="stylesheet" />
		<link href="links_styles.css" type="text/css" rel="stylesheet" />
		<link href="text_styles.css" type="text/css" rel="stylesheet" />
		
		<script>
			//window.onload = total_payments_amounts;
		</script>
	</head>
		<body onload="total_payments_amounts(), highlight_confirmed_payments()">
			<!-- Begining of header section -->
				<div id="header"> 
					<h1>ZERO INTEREST FINANCE LIMITED</h1>
					<div id="nav">
					<a href="main_page.php">Home</a>
					<a href="customers_page.php">Customers Page</a>
					<a class="active-nav-link" href="#">Loans Page</a>
					</div>
				</div>
			<!-- End of header section -->
			
			<!-- Begining of sidebar navigation -->
				<div id="sidebarNav">
				<ul class="side-bar">
					<li class="side-list"><a href="viewloans.php">View Loans</a></li>
					<li class="side-list"><a href="defaulters.php">Defaulters</a></li>
					<li class="side-list"><a href="on-goingloans.php">On-going Loans</a></li>
					<li class="side-list"><a href="clearedloans.php">Cleared Loans</a></li>
					<li class="side-list"><a class="active-sidebar-link" href="#">Loan Payments</a></li>
					<li class="side-list"><a href="defaulters_report.php">Defaulters Report</a></li>
					<li class="side-list"><a href="clearedloans_report.php">Cleared Loans Report</a></li>
				</ul>
				</div>
			<!-- End of sidebar navigation -->
			
			<!-- Begining of main content section -->
				<div id="main_content_table">
			<?php
				//Connect to database
				include "dbconn.php";
				
				$sql = "SELECT * FROM `Payments with Customers`";
				
				$result = mysqli_query($conn, $sql);
				
				if (mysqli_num_rows($result) > 0 ) {
					/*function paymentConfirmed() {
							if ($row['PAYMENT_CONFIRMED'] == 1 ) {
								$returnedValue = "Yes";
							}else {
								$returnedValue = "No";
							}
						return $returnedValue;
						}*/
					$tablehead = <<<TABLE
							<!--<div id="top"><a href="#bottom">End of Table</a></div>-->
							<div>
								<h1 class='table_heading'>Payment Details List
								<input type=text id="search_field" placeholder="Search by name...">
								<span id="filter_icon" style="float:right">
								<label><input type='radio'/>Filter Table</label></span></h1>
							</div>
							<div id="table_content">
							<thead>
							<table id="this_table" border="1">
								<tr>
									<th>CUSTOMER NAME</th>
									<th>LOAN NO</th>
									<th>PAYMENT DATE</th>
									<th>AMOUNT</th>
									<th>PAYMENT TYPE</th>
									<th>PAYMENT CONFIRMED</th>
									<!--<th>EDIT</th>-->
								</tr>
								<tr id="filter_row" style="display:none">
									<td></td>
									<td></td>
									<td><input id="filter_date" type="text" placeholder="Type date..."></td>
									<td></td>
									<td>
										<select id="filter_type">
											<option value="">Select option...</option>
											<option>Bank</option>
											<option>Cash</option>
											<option>Mobile Money</option>
										</select>
									</td>
									<td>
										<select id="filter_confirmed">
											<option value="">Select option...</option>
											<option>No</option>
											<option>Yes</option>
										</select>
									</td>
									<td></td>
									
								</tr></thead><tbody>
TABLE;
echo $tablehead;
					while ($row = mysqli_fetch_assoc($result)) {
						
								echo "<tr>";
								echo 	"<td class='filter_customername'>".$row['CUSTOMER_NAME']."</td>";
								echo 	"<td style='text-align:center;'>".$row['LOAN_NO']."</td>";
								echo 	"<td>".date("d/m/Y", strtotime($row['PAYMENT_DATE']))."</td>";
								echo 	"<td class='payments_total' style='text-align:right;'>".number_format($row['PAID_AMOUNT'])."</td>";
								echo 	"<td>".$row['PAYMENT_TYPE']."</td>";
									if ($row['PAYMENT_CONFIRMED'] == 1 ) {
								echo 	"<td class='payments_confirmed'>Yes</td>";
									}else {
								echo 	"<td class='payments_confirmed'>No</td>";
									}
								/*echo "<td><a href='payment_form.php'
										onclick=".'"'.
										"window.open('payment_form.php','popup','width=360,height=300');
										return false;".'"'.">Edit</a></td>";*/
								echo "</tr>";							

					}
					echo "</tbody><tfoot><tr><td>Total</td><td></td><td></td><td id='total'></td><td></td>
					<td></td></tr></tfoot>";
					echo "</table>";
					echo "<div id='bottom'><a href='#top'>Top</a></div>";
					echo "</div>";
				}
									else {
										echo "No Records";
									}
									
			?>
			</div>
			<!-- End of content section -->
		</body>
		<script>
			document.getElementById("search_field").addEventListener("keyup", filterTable_name);
			document.getElementById("filter_icon").addEventListener("click", displayFilterRow);
			document.getElementById("filter_type").addEventListener("change", filterPayments_type);
			document.getElementById("filter_confirmed").addEventListener("change", filterPayments_confirmed);
			document.getElementById("filter_date").addEventListener("change", filterPayments_date);
			//document.getElementsByClassName("table_heading")[0].addEventListener("click", highlight_confirmed_payments);
			
		</script>
</html>