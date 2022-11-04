<?php include "login_session.inc"; ?>
<!doctype html>
<html>
	<head>
		<title>Search Filter</title>
		<!--<meta name="viewport" content="width=device-width initial-scale=1.0"/>-->
		<link href="page_styles.css" type="text/css" rel="stylesheet" />
		<link href="heading_styles.css" type="text/css" rel="stylesheet" />
		<link href="links_styles.css" type="text/css" rel="stylesheet" />
		<link href="lists_styles.css" type="text/css" rel="stylesheet" />
		<link href="text_styles.css" type="text/css" rel="stylesheet" />
		
		<style>
			label{
				color:#52B2A0;
				font-family: "Times New Roman";
			}
			
			input{
				font-family: "Cambria";
				padding: 4px;
			}
			.left_margin {
				margin-left: 5px;
			}
			
			.left_margin_extra {
				margin-left: 50px;
			}
			
			.discr{
				color:rgba(27,233,179,0.9);
				font-weight: bold;
				font-family: "Times New Roman";
				font-size: 1.3em;
				text-decoration: underline;
				letter-spacing: 0.05em;
			}
			
			#response{
				box-sizing: border-box;
				background-color: red;
				color: white;
				width: fit-content;
				margin: 10px 0;
				padding: 0 5px 0 5px;
			}
		</style>
	</head>
	<body>
		
		<?php
				switch($_REQUEST['criteria']){
					case "cust":
						$placeholder = "Customers Joined";
					break;
					
					case "all":
						$placeholder = "Loans";
					break;
					
					case "due":
						$placeholder = "Due Loans";
					break;
					
					case "clrd":
						$placeholder = "Cleared Loans";
					break;
					
					case "pymt-date":
						$placeholder = "Payments";
					break;
					
					case "default":
						$placeholder = "Loans In Default";
					break;
				}
		?>
		<!-- Begining of header section -->
			<div id="header"> 
				<h1>ZERO INTEREST FINANCE LIMITED</h1>
				
				<div id="nav">
				<a href="main_page.php">Home</a>
				<a href="customers_page.php">Customers</a>
				<a href="reports.php">Reports</a>
				<a href="">Account</a>
				<!--<a href="loans_page.html">Loans Page</a>-->
				</div>
				
				<div id="user">
					<p class="user-welcome">Welcome <?php echo $_SESSION["userlogin"]; ?></p>
				</div>
			</div>
		<!-- End of header section -->
		<div id="main_content">
			<h2>Search Filter</h2>
			<div>
				<p class="discr">Filter <?php echo $placeholder; ?> by Date Range</p>
				<form id="search_frm" method="POST" action="filter_analysis.php">
					<div id="response"></div>
					<input type="text" name="rec_src" value="<?php echo $_REQUEST['rec_src'] ?>" hidden />
					<input type="text" name="dt_scope" value="<?php echo $_REQUEST['dt_scope'] ?>" hidden />
					<input type="text" name="criteria" value="<?php if(isset($_REQUEST['criteria'])){echo $_REQUEST['criteria'];} ?>" hidden />
					<label for="from">From Date</label><input class="left_margin" type="date" id="from" name="from" placeholder="now"/>
					<span class="left_margin_extra"><label for="to">To Date</label><input class="left_margin" type="date" id="to" name="to" /></span>
					<div style="margin-top: 30px"><button type="button" onclick="search()">Done</button></div>
				</form>
			</div>
		</div>
		
		<script>
			function search(){
				//var rec_src = ;
				//var dt_scope = ;
				var from_date = /*new Date(*/document.getElementById("from").value/*)*/;
				var to_date = /*new Date(*/document.getElementById("to").value/*)*/;
				
				if(from_date/*.getTime()*/ > to_date/*.getTime()*/){
					document.getElementById("response").innerHTML = "From date cannot be after To date";
				}else if(from_date == "" || to_date == ""){
					document.getElementById("response").innerHTML = "Please enter both dates to search";
				}else{
					document.getElementById("response").innerHTML = "";
					document.getElementById("search_frm").submit();
					/*fetch("filter_analysis.php?rec_src="+rec_src+"&dt_scope="+dt_scope+"&from_date="+from_date+"&to_date="+to_date, {
						method: "POST"
					}).then(function(response){
						return response.text();
					}).then(function(data){
						document.getElementById("response").innerHTML = data;
						//console.log("Fuck");
					});*/
					/*
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function(){
						if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
							document.getElementById("response").innerHTML = xmlhttp.responseText;
						}
					}
					xmlhttp.open('POST', 'filter_analysis.php?rec_src="+rec_src+"&dt_scope="+dt_scope+"&from_date="+from_date+"&to_date="+to_date', true);
					xmlhttp.send();*/
				}
			}
		</script>
	</body>
</html>	