<?php include "login_session.inc"; ?>
<html>
	<head>
		<title>All Customers</title>
		<!--<meta name="viewport" content="width=device-width initial-scale=1.0"/>-->
		<script src="assets/js/jquery-3.6.0.min.js"></script>
		<script src="table_filters.js" type="text/javascript"></script>
		<link href="heading_styles.css" type="text/css" rel="stylesheet" />
		<link href="lists_styles.css" type="text/css" rel="stylesheet" />
		<link href="page_styles.css" type="text/css" rel="stylesheet" />
		<link href="table_styles.css" type="text/css" rel="stylesheet" />
		<link href="links_styles.css" type="text/css" rel="stylesheet" />
		<link href="text_styles.css" type="text/css" rel="stylesheet" />
		
		<style>
			.tbl_btn{
				/*background-color: #52B2A0;*/
				padding: 5px 5px;
				border-radius: 4px;
				/*color: #ffffff;
				font-family: calibri;
				font-size: 14;
				margin-top: 12px;*/
				border: 1px solid #52B2A0;/*#ccc;*/
				cursor: pointer;
				outline: none;
			}	
			
			/* Styling the Customer Label */
			.cust_lbl{
				font-weight: bold;
				font-family: Calibri;
				font-size: 1.5em;
				letter-spacing: 0.03em;
			}
			
			.form-link {
				/*background-color: #52B2A0;*/
				padding: 5px 5px;
				border-radius: 4px;
				border: 1px solid #52B2A0;/*#ccc;*/
				text-decoration: none;
				color: black;
			}

			.form-link:hover {
				background-color: rgba(27,233,179,0.9);
				color: white;
				border-color: #111;
			}
			
		</style>
	</head>
		<body>
			<!-- Begining of header section -->
				<div id="header"> 
					<h1>ZERO INTEREST FINANCE LIMITED</h1>
					<div id="nav">
					<a href="main_page.php">Home</a>
					<a class="active-nav-link" href="#">Customers Page</a>
					<a href="loans_page.html">Loans Page</a>
				</div>
				</div>
				
			<!-- End of header section -->
			
			<!-- Begining of sidebar navigation -->
				<div >
				<ul class="side-bar">
					<li class="side-list"><a href="customer_details.php">Add Customer</a></li>
					<li class="side-list"><a class="active-sidebar-link" href="#">View Customers List</a></li>
					<li class="side-list"><a href="customer_report.php">Customer Report</a></li>
				</ul>
				</div>
			<!-- End of sidebar navigation -->
			
			<!-- Begining of content section -->
				<div id="main_content_table">
			<?php
			unset($_SESSION['customer_no']);
			//connect to database
			include "dbconn.php";
			
			//get data
			$sql = "SELECT * FROM `Customers_with_attachments`";
			
			$result = mysqli_query($conn, $sql);
			
			
$table_header =<<<EOD
	
	<div id="table_content">
		<h1 class='_table_heading' style="margin:0 0 0 4px">Customer Details List<input type='text' id="search_field" placeholder="Search by name..." style="float:right">
	<span id="filter_icon" style="float:right">Filter Table</span></h1>
		<!--<caption>This</caption>
		<div id="table-heading-row">-->
		<table id="this_table" border = '1' cellpadding='0' cellspacing='0' style="margin-top:0px">
		<tr>
			<th>REF NO</th>
			<th>CUSTOMER NAME</th>
			<th>PRIMARY CONTACT</th>
			<th>OTHER CONTACT</th>
			<th>EMAIL ADDRESS</th>
			<th>MARITAL STATUS</th>
			<th>RESIDENCE</th>
			<th>HOME DETAILS</th>
			<th>BANK NAME</th>
			<th>BANK A/C</th>
			<th>OCCUPATION</th>
			<th>JOB/BUSINESS</th>
			<th>EMPLOYER</th>
			<th>JOB/BUSINESS LOCATION</th>
			<th>DATE JOINED</th>
			<th>FILES ATTACHED </th>
			<th></th>
			<th></th>
		</tr>
		<!--</table>
		</div>-->
EOD;
echo $table_header;

		if (mysqli_num_rows($result) > 0) {
				
			while($row = mysqli_fetch_assoc($result)){
				
				$customer_no = $row['CUSTOMER_NO'];
				$customerName = $row['SUR_NAME']. " ". $row['FIRST_NAME'];
				
				echo "<tr title='$customerName'>";
				echo 	"<td hidden>".$row['CUSTOMER_NO']."</td>";
				echo	"<td>".$row['REF_NO']."</td>";
				echo	"<td class='filter_customername'>".$customerName."</td>";
				echo 	"<td>".$row['PRIMARY_CONTACT']."</td>";
				echo 	"<td>".$row['OTHER_CONTACT']."</td>";
				echo 	"<td>".$row['EMAIL_ADDRESS']."</td>";
				echo 	"<td>".$row['MARITAL_STATUS']."</td>";
				echo 	"<td>".$row['RESIDENCE']."</td>";
				echo 	"<td>".$row['HOME_DETAILS']."</td>";
				echo 	"<td>".$row['BANK']."</td>";
				echo 	"<td>".$row['BANK A/C']."</td>";
				echo 	"<td>".$row['OCCUPATION_TYPE']."</td>";
				echo 	"<td>".$row['JOB/BUSINESS_TYPE']."</td>";
				echo 	"<td>".$row['EMPLOYER']."</td>";
				echo 	"<td>".$row['LOCATION_OF_JOB/BUSINESS']."</td>";
				echo 	"<td>".date("d/m/Y", strtotime($row['DATE JOINED']))."</td>";
				echo 	"<td style='text-align:center'>(".$row['Attachments'].")</td>";
				echo 	"<td><a href='customerdetails_handler.php?customer_no=$customer_no'>Edit Details</a></td>";
				echo 	"<td><button type='button' class='tbl_btn' onclick='view_loans(this)'>Loans</button></td>";
				echo "</tr>";
			}
		}
echo "</table>";
echo "</div>";
include "footer.inc";
			
			?>
			</div>
		<!-- End of content section -->
		</body>
		<script>
			document.getElementById("search_field").addEventListener("keyup", filterTable_name);
			
			var _row = null;
			function view_loans(btn) {
				_row = $(btn).parents("tr");
				var col = _row.children("td");
				var cust_no = $(col[0]).text();
				var cust_name = $(col[2]).text();
				var cust_phone = $(col[3]).text();
				
				$("#main_content_table").load('customer_loans.php', {'cust_no':cust_no, "cust_name":cust_name, "cust_phone":cust_phone});
			}
			
			function inject_cust_list(){
				location.reload();
			}
		</script>
</html>