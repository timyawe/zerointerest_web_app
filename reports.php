<?php include "login_session.inc"; ?>
<!doctype html>
<html lang="en">
	<head>
		<title>Reports</title>
		<!--<meta name="viewport" content="width=device-width initial-scale=1.0"/>-->
		<link href="page_styles.css" type="text/css" rel="stylesheet" />
		<link href="heading_styles.css" type="text/css" rel="stylesheet" />
		<link href="links_styles.css" type="text/css" rel="stylesheet" />
		<link href="lists_styles.css" type="text/css" rel="stylesheet" />
		<link href="text_styles.css" type="text/css" rel="stylesheet" />
	</head>
	
	<body>
		<!-- Begining of header section -->
			<div id="header"> 
				<h1>ZERO INTEREST FINANCE LIMITED</h1>
				
				<div id="nav">
				<a href="main_page.php">Home</a>
				<a href="customers_page.php">Customers</a>
				<a class="active-nav-link" href="">Reports</a>
				<a href="">Account</a>
				<!--<a href="loans_page.html">Loans Page</a>-->
				</div>
				
				<div id="user">
					<p class="user-welcome">Welcome <?php echo $_SESSION["userlogin"]; ?></p>
				</div>
			</div>
		<!-- End of header section -->
		<div id="main_content">
			<h2>Reports</h2>
			<div>
				<div class="rep_grp">
					<p>Customers joined <a class="rep_link" href="filter_analysis.php?dt_scope=curr&rec_src=cust-tbl&criteria=tdy">today</a></p>
					<p>Customers joined in <a class="rep_link" href="search_filter.php?dt_scope=range&rec_src=cust-tbl&criteria=cust">date range</a></p>
				</div>
				<div  class="rep_grp">
					<p>Loans <a class="rep_link" href="filter_analysis.php?dt_scope=curr&rec_src=lns-tbl&criteria=tdy">today</a></p>
					<p><a class="rep_link" href="filter_analysis.php?dt_scope=curr&rec_src=lns-tbl&criteria=ong">On-going Loans</a></p>
					<p>Loans in <a class="rep_link" href="search_filter.php?dt_scope=range&rec_src=lns-tbl&criteria=all">date range</a></p>
					<p>Loans due <a class="rep_link" href="filter_analysis.php?dt_scope=curr&rec_src=lns-tbl&criteria=due-tdy">today</a></p>
					<p>Loans due in <a class="rep_link" href="search_filter.php?dt_scope=range&rec_src=lns-tbl&criteria=due">date range</a></p>
					<p>Loans cleared <a class="rep_link" href="filter_analysis.php?dt_scope=curr&rec_src=lns-tbl&criteria=clrd-tdy">today</a></p>
					<p>Loans cleared in <a class="rep_link" href="search_filter.php?dt_scope=range&rec_src=lns-tbl&criteria=clrd">date range</a></p>
					<p>Loans in default by <a class="rep_link" href="search_filter.php?dt_scope=range&rec_src=lns-tbl&criteria=default">date range</a></p>
				</div>
				<div  class="rep_grp">
					<p><a  class="rep_link" href="search_filter.php?dt_scope=range&rec_src=pymt-tbl&criteria=pymt-date">Repayments</a></p>
				</div>
			</div>
		</div>
	</body>
</html>