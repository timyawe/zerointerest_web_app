<?php unset($_SESSION['customer_no']); ?>
<html>
	<head>
	<title>Customers Page</title>
	<meta name="viewport" content="width=device-width initial-scale=1.0"/>
	<link href="heading_styles.css" type="text/css" rel="stylesheet" />
	<link href="lists_styles.css" type="text/css" rel="stylesheet" />
	<link href="page_styles.css" type="text/css" rel="stylesheet" />
	<link href="links_styles.css" type="text/css" rel="stylesheet" />
	<link href="text_styles.css" type="text/css" rel="stylesheet" />
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
				<div id="sidebarNav">
				<ul class="side-bar">
					<li class="side-list"><a href="customer_details.php">Add Customer & Loan</a></li>
					<li class="side-list"><a href="viewcustomers.php">View Customers List</a></li>
					<li class="side-list"><a href="customer_report.php">Customer Report</a></li>
				</ul>
				</div>
			<!-- End of sidebar navigation -->
			
			<div id="footer">
				<p> "Today is my lucky day" </p> <?php echo $_SESSION['customer_no']; ?>
			</div>
		</body>
</html>