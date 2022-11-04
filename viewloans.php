<?php include "login_session.inc"; ?>
<html>
	<head>
		<title>All Loans</title>
		<!--<meta name="viewport" content="width=device-width initial-scale=1.0"/>-->
		<script src="table_filters.js" type="text/javascript"></script>
		<link href="heading_styles.css" type="text/css" rel="stylesheet" />
		<link href="lists_styles.css" type="text/css" rel="stylesheet" />
		<link href="page_styles.css" type="text/css" rel="stylesheet" />
		<link href="table_styles.css" type="text/css" rel="stylesheet" />
		<link href="links_styles.css" type="text/css" rel="stylesheet" />
		<link href="text_styles.css" type="text/css" rel="stylesheet" />
		
		<script>
			window.onload = total_principal_amounts, total_principalint_amounts;
			//window.onload = total_principalint_amounts;
			//document.getElementsByTagName("body").addEventListener("load", total_principalint_amounts);
		</script>
	</head>
		<body onload="total_principal_amounts(), total_principalint_amounts(), total_loans_no(), highlight_clearedloans()">
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
					<li class="side-list"><a class="active-sidebar-link" href="#">View Loans</a></li>
					<li class="side-list"><a href="defaulters.php">Defaulters</a></li>
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
			//connect to database
			include "dbconn.php";
			
			//Select the data
			$sql = "SELECT * FROM `Customers with Loans`";
			
			//Put data in variable result
			$result = mysqli_query($conn, $sql);
			
			//Check wether the query returned any data
			if (mysqli_num_rows($result) > 0) {
				
				//create the table with heredoc
$table_header=<<<EOD
	<h1 class='table_heading'>Loan Details List <input type=text id="search_field" placeholder="Search by name...">
	<span id="filter_icon" style="float:right">Filter Table</span></h1>
	
	<div id="table_content">
	<table id="this_table" border="1" style="margin-top:19px;">
		<tr  class="fixed_header" style="position: sticky; margin-top: 30%;">
			<th>LOAN NO</th>
			<th>REF NO</th>
			<th>CUSTOMER NAME</th>
			<th>LOAN_REF NO</th>
			<th>START DATE</th>
			<th>PERIOD</th>
			<th>END DATE</th>
			<th>PRINCIPAL</th>
			<th>INTEREST RATE</th>
			<th>PRINCIPAL & INTEREST</th>
			<!--<th>COMMENT</th>-->
			<th>STATUS</th>
			<th>DATE CLEARED</th>
			<th>EDIT LOAN</th>
			<th>VIEW DETAILS</th>
		</tr>
		
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td>
				<select id="filter_amount">
				
				</select>
			</td>
			<td></td>
			<td></td>
			<!--<td></td>-->
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
EOD;
echo $table_header;
	while ($row = mysqli_fetch_assoc($result)){
					$_SESSION['loan_no'] = $row['LOAN_NO'];
					$principal = number_format($row['PRINCIPAL']);
					if ($row['DATE_CLEARED'] == "" ) {
						$cleareddate = "";
					} else {
						$cleareddate = date("d/m/Y", strtotime($row['DATE_CLEARED']));
					}
//$table_details .=<<<EOD
		echo "<tr title='".$row['CUSTOMER_NAME']."' class='table_data'>";
		echo	"<td>".$row['LOAN_NO']."</td>";
		echo	"<td>".$row['REF_NO']."</td>";
		echo	"<td class='filter_customername total_loans'>".$row['CUSTOMER_NAME']."</td>";
		echo	"<td>".$row['LOAN_REF_NO']."</td>";
		echo	"<td>".date("d/m/Y", strtotime($row['START_DATE']))."</td>";
		echo	"<td>".$row['PERIOD']."</td>";
		echo	"<td>".date("d/m/Y", strtotime($row['FINAL_PAYMENT_DATE']))."</td>";
		echo	"<td class='loan_principal' style='text-align:right;'>".$principal."</td>";
		echo	"<td style='text-align:center;'>".$row['INTERST_RATE']."</td>";
		echo	"<td class='loan_principal&int' style='text-align:right;'>".number_format($row['FINAL_PRINCIPAL_&_INTEREST'])."</td>";
		//echo	"<td><form><textarea style='border-style: none; resize: none;'>".$row['COMMENT']."</textarea></form></td>";
		echo	"<td>".$row['STATUS']."</td>";
		echo	"<td class='cleared_loan'>".$cleareddate."</td>";
		echo	"<td><a href='loandetails_handler.php?loan_no=".$row['LOAN_NO']."'>Edit Loan</a></td>";
		echo	"<td><a href='loandetails_view.php?loan_no=".$row['LOAN_NO']."&customer_name=".$row['CUSTOMER_NAME']."'>View Details</a></td>";
		echo "</tr>";

			}
		echo "<tr id='totals_row'>
				<td>Total</td>
				<td></td>
				<td class='no_of_loans_total'></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td class='principal_total'></td>
				<td></td>
				<td class='principal&int_total'></td>
				<!--<td></td>-->
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			 </tr>";
echo "</table>";

echo "</div>";
		    } else{
				die("No Records to display");
			}
			
			?>
			</div>
			
			<!-- End of content section -->
		</body>
		
		<script>
			document.getElementById("search_field").addEventListener("keyup", filterTable_name);
			document.getElementById("filter_icon").addEventListener("click", createFilterTable_amounts);
			document.getElementById("filter_amount").addEventListener("change", filterTable_amount);
			
			var t = document.getElementsByClassName("fixed_header");
			for (var y = 0; y<t.length; y++){
			t[y].addEventListener("click", clicked_row);
			}
			
		</script>
</html>