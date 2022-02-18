<?php include "login_session.inc"; ?>
<html>
	<head>
		<title>Defaulters List</title>
		<meta name="viewport" content="width=device-width initial-scale=1.0"/>
		<script src="table_filters.js" type="text/javascript"></script>
		<link href="heading_styles.css" type="text/css" rel="stylesheet" />
		<link href="lists_styles.css" type="text/css" rel="stylesheet" />
		<link href="page_styles.css" type="text/css" rel="stylesheet" />
		<link href="table_styles.css" type="text/css" rel="stylesheet" />
		<link href="links_styles.css" type="text/css" rel="stylesheet" />
		<link href="text_styles.css" type="text/css" rel="stylesheet" />
	</head>
		<body onload="total_principal_amounts(), total_principalint_amounts(), total_loans_no(), total_amountpaid()">
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
					<li class="side-list"><a class="active-sidebar-link" href="#">Defaulters</a></li>
					<li class="side-list"><a href="on-goingloans.php">On-going Loans</a></li>
					<li class="side-list"><a href="clearedloans.php">Cleared Loans</a></li>
					<li class="side-list"><a href="payments_list.php">Loan Payments</a></li>
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
				
				$sql = "SELECT * FROM `Defaulters List`";
				
				$result = mysqli_query($conn, $sql);
				
				if (mysqli_num_rows($result) > 0 ) {
					
					$tablehead = <<<TABLE
							<h1 class='table_heading'>Defaulters List
							<input type=text id="search_field" placeholder="Search by name...">
								<span id="filter_icon" style="float:right">Filter Table</span><h1>
								
						<div id="table_content">
							<table id="this_table" border="1">
								<tr>
									<th>LOAN NO</th>
									<th>REF_NO</th>
									<th>CUSTOMER NAME</th>
									<th>PRIMARY CONTACT</th>
									<th>OTHER CONTACT</th>
									<th>START DATE</th>
									<th>PERIOD</th>
									<th>INSTALMENT END DATE</th>
									<th>FINAL DATE</th>
									<th>ELAPSED DAYS</th>
									<th>PRINCIPAL</th>
									<th>INTEREST</th>
									<th>INSTALMENT BALANCE</th>
									<th>PRINCIPAL & INTEREST</th>
									<th>AMOUNT PAID</th>
									<th>COMMENT</th>
									<th>STATUS</th>
									<th>EDIT LOAN</th>
									<th>VIEW DETAILS</th>
								</tr>
TABLE;
echo $tablehead;
					while ($row = mysqli_fetch_assoc($result)) {

							echo	"<tr title='".$row['CUSTOMER_NAME']."'>";
							echo	"<td>".$row['LOAN_NO']."</td>";
							echo	"<td>".$row['REF_NO']."</td>";
							echo	"<td class='filter_customername'>".$row['CUSTOMER_NAME']."</td>";
							echo	"<td>".$row['PRIMARY_CONTACT']."</td>";
							echo	"<td>".$row['OTHER_CONTACT']."</td>";
							echo	"<td>".date("d/m/Y", strtotime($row['START_DATE']))."</td>";
							echo	"<td>".$row['PERIOD']."</td>";
							echo	"<td>".date("d/m/Y", strtotime($row['Instalment_EndDate']))."</td>";
							echo	"<td>".date("d/m/Y", strtotime($row['FINAL_PAYMENT_DATE']))."</td>";
							echo	"<td>".$row['ELAPSED_DAYS']."</td>";
							echo	"<td class='loan_principal' style='text-align:right;'>".number_format($row['PRINCIPAL'])."</td>";
							echo	"<td style='text-align:center;'>".$row['INTERST_RATE']."</td>";
							echo	"<td style='text-align:right;'>".number_format($row['INSTALMENT_BALANCE'])."</td>";
							echo	"<td class='loan_principal&int' style='text-align:right;'>".number_format($row['FINAL_PRINCIPAL_&_INTEREST'])."</td>";
							echo	"<td class='loan_amountpaid' style='text-align:right;'>".number_format($row['AMOUNT_PAID'])."</td>";
							echo	"<td><div class='loan-comment-field'>".$row['COMMENT']."</div></td>";
							echo	"<td>".$row['STATUS']."</td>";
							echo	"<td><a href='loandetails_handler.php?loan_no=".$row['LOAN_NO']."'>Edit Loan</a></td>";
		echo	"<td><a href='loandetails_view.php?loan_no=".$row['LOAN_NO']."&customer_name=".$row['CUSTOMER_NAME']."' target='blank'>View Details</a></td>";
		echo "<td><a href='customerdetails_handler.php?customer_no=".$row['CustomerNo']."'>Customer Details</a></td>";
							echo	"</tr>";
					}
							echo "<tr id='totals_row'>
									<td>Total</td>
									<td></td>
									<td class='no_of_loans_total'></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td class='principal_total'></td>
									<td></td>
									<td></td>
									<td class='principal&int_total'></td>
									<td class='amountpaid_total'></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								 </tr>";
					echo "</table>";
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
			
		</script>
</html>