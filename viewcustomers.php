<?php include "login_session.inc"; ?>
<html>
	<head>
		<title>All Customers</title>
		<meta name="viewport" content="width=device-width initial-scale=1.0"/>
		<script src="table_filters.js" type="text/javascript"></script>
		<link href="heading_styles.css" type="text/css" rel="stylesheet" />
		<link href="lists_styles.css" type="text/css" rel="stylesheet" />
		<link href="page_styles.css" type="text/css" rel="stylesheet" />
		<link href="table_styles.css" type="text/css" rel="stylesheet" />
		<link href="links_styles.css" type="text/css" rel="stylesheet" />
		<link href="text_styles.css" type="text/css" rel="stylesheet" />
	</head>
		<body>
			<!-- Begining of header section -->
				<div id="header"> 
					<h1>ZERO INTEREST FINANCE LIMITED</h1>
					
				</div>
				<div id="nav">
					<a href="main_page.php">Home</a>
					<a class="active-nav-link" href="#">Customers Page</a>
					<a href="loans_page.html">Loans Page</a>
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
	<h1 class='table_heading'>Customer Details List<input type=text id="search_field" placeholder="Search by name...">
	<span id="filter_icon" style="float:right">Filter Table</span></h1>
	<div id="table_content">
	
		<!--<caption>This</caption>
		<div id="table-heading-row">-->
		<table id="this_table" border = '1' cellpadding='0' cellspacing='0'>
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
			<th>EDIT DETAILS</th>
		</tr>
		<!--</table>
		</div>-->
EOD;
echo $table_header;

		if (mysqli_num_rows($result) > 0) {
				
			while($row = mysqli_fetch_assoc($result)){
				
				$_SESSION['customerNo'] = $row['CUSTOMER_NO'];
				$_SESSION['refNo'] = $row['REF_NO'];
				$_SESSION['customerName'] = $row['SUR_NAME']. " ". $row['FIRST_NAME'];
				$_SESSION['contact1'] = $row['PRIMARY_CONTACT'];
				$_SESSION['contact2'] = $row['OTHER_CONTACT'];
				$_SESSION['email'] = $row['EMAIL_ADDRESS'];
				$_SESSION['marital'] = $row['MARITAL_STATUS'];
				$_SESSION['resd'] = $row['RESIDENCE'];
				$_SESSION['home'] = $row['HOME_DETAILS'];
				$_SESSION['bank'] = $row['BANK'];
				$_SESSION['bank_a/c'] = $row['BANK A/C'];
				$_SESSION['occupationType'] = $row['OCCUPATION_TYPE'];
				$_SESSION['jobType'] = $row['JOB/BUSINESS_TYPE'];
				$_SESSION['jobLocation'] = $row['LOCATION_OF_JOB/BUSINESS'];
				$_SESSION['dateJoined'] = $row['DATE JOINED'];
				
				//echo "<table>";
				echo "<tr title='".$_SESSION['customerName']."'>";
				echo "<td>".$row['REF_NO']."</td>";
				echo "<td class='filter_customername'>".$_SESSION['customerName']."</td>";
				echo "<td>".$row['PRIMARY_CONTACT']."</td>";
				echo "<td>".$row['OTHER_CONTACT']."</td>";
				echo "<td>".$row['EMAIL_ADDRESS']."</td>";
				echo "<td>".$row['MARITAL_STATUS']."</td>";
				echo "<td>".$row['RESIDENCE']."</td>";
				echo "<td>".$row['HOME_DETAILS']."</td>";
				echo "<td>".$row['BANK']."</td>";
				echo "<td>".$row['BANK A/C']."</td>";
				echo "<td>".$row['OCCUPATION_TYPE']."</td>";
				echo "<td>".$row['JOB/BUSINESS_TYPE']."</td>";
				echo "<td>".$row['EMPLOYER']."</td>";
				echo "<td>".$row['LOCATION_OF_JOB/BUSINESS']."</td>";
				echo "<td>".date("d/m/Y", strtotime($row['DATE JOINED']))."</td>";
				echo "<td style='text-align:center'>(".$row['Attachments'].")</td>";
				echo "<td><a href='customerdetails_handler.php?customer_no={$_SESSION['customerNo']}'>Edit Details</a></td>";
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
			
		</script>
</html>