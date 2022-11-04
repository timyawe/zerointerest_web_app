<?php include "login_session.inc"; ?>
<html>
	<head>
		<title>On-going Loans</title>
		<script src="table_filters.js" type="text/javascript"></script>
		<link href="heading_styles.css" type="text/css" rel="stylesheet" />
		<link href="lists_styles.css" type="text/css" rel="stylesheet" />
		<link href="table_styles.css" type="text/css" rel="stylesheet" />
		<link href="page_styles.css" type="text/css" rel="stylesheet" />
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
					<li class="side-list"><a href="defaulters.php">Defaulters</a></li>
					<li class="side-list"><a class="active-sidebar-link" href="#">On-going Loans</a></li>
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
			//connect to database
			include "dbconn.php";
			
			$sql = "SELECT * FROM `On-going Loans`";
			
			$result = mysqli_query($conn, $sql);
			
$table_header =<<<EOD
		<h1 class='table_heading'>On-going Loans<input type=text id="search_field" placeholder="Search by name...">
		<span id="filter_icon" style="float:right">Filter Table</span></h1>
		
		<div id="table_content">
		<table id="this_table" border = "1" style="margin-top:19px;">
			<tr>
				<th>LOAN NO</th>
				<th>REF NO</th>
				<th>LOAN_REF NO</th>
				<th>CUSTOMER NAME</th>
				<th>START DATE</th>
				<th>PERIOD</th>
				<!--<th>INSTALMENT END DATE</th>-->
				<th>FINAL PAYMENT DATE</th>
				<th>PRINCIPAL</th>
				<th>INTEREST RATE</th>
				<!--<th>INSTALMENT BALANCE</th>-->
				<th>PRINCIPAL & INTEREST</th>
				<!--<th>COMMENT</th>-->
				<th>STATUS</th>
				<th>AMOUNT PAID</th>
				<th>EDIT</th>
				<th>VIEW DETAILS</th>
			</tr>
EOD;
echo $table_header;

			if (mysqli_num_rows($result) > 0){
				while ($row = mysqli_fetch_assoc($result)) {

			echo "<tr  title='".$row['CUSTOMER_NAME']."'>";
			echo 	"<td>".$row['LOAN_NO']."</td>";
			echo 	"<td>".$row['REF_NO']."</td>";
			echo	"<td>".$row['LOAN_REF_NO']."</td>";
			echo 	"<td class='filter_customername'>".$row['CUSTOMER_NAME']."</td>";
			echo 	"<td>".date("d/m/Y", strtotime($row['START_DATE']))."</td>";
			echo 	"<td>".$row['PERIOD']."</td>";
			//echo 	"<td>".date("d/m/Y", strtotime($row['INSTALMENT_END_DATE']))."</td>";
			echo 	"<td>".date("d/m/Y", strtotime($row['FINAL_PAYMENT_DATE']))."</td>";
			echo 	"<td class='loan_principal' style='text-align:right;'>".number_format($row['PRINCIPAL'])."</td>";
			echo 	"<td style='text-align:center;'>".$row['INTERST_RATE']."</td>";
			//echo 	"<td style='text-align:right;'>".number_format($row['INSTALMENT_BALANCE'])."</td>";
			echo 	"<td class='loan_principal&int' style='text-align:right;'>".number_format($row['FINAL_PRINCIPAL_&_INTEREST'])."</td>";
			//echo 	"<td><div class='loan-comment-field'>".$row['COMMENT']."</div></td>";
			echo 	"<td>".$row['STATUS']."</td>";
			echo 	"<td class='loan_amountpaid' style='text-align:right;'>".number_format($row['AMOUNT_PAID'])."</td>";
			echo 	"<td><a href='loandetails_handler.php?loan_no={$row['LOAN_NO']}'>Edit Details</a></td>";
			echo	"<td><a href='loandetails_view.php?loan_no={$row['LOAN_NO']}'>View Details</a></td>";
			echo "</tr>";

			}
			}
			echo "<tr id='totals_row'>
									<td>Total</td>
									<td></td>
									<td></td>
									<td class='no_of_loans_total'></td>
									<td></td>
									<td></td>
									<td></td>
									<!--<td></td>-->
									<td  class='principal_total'></td>
									<td></td>
									<!--<td></td>-->
									<td class='principal&int_total'></td>
									<!--<td></td>-->
									<td></td>
									<td class='amountpaid_total'></td>
									<td></td>
									<td></td>
								 </tr>";
echo "</table>";
echo "</div>"
		
		?>
		</div>
		<!-- End of content section -->
		</body>
		
		<script>
			document.getElementById("search_field").addEventListener("keyup", filterTable_name);
			
		</script>
</html>