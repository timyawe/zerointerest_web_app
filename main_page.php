<?php include "login_session.inc"; 
unset($_SESSION['customer_no']);

if (isset($_POST['chgpwd'])) {
	//connect to database
	require "dbconn.php";

	$updatesql = "UPDATE Users SET Password =" ."'".$_POST['cfmpwd']."'" . "WHERE Username =". "'".$_POST['username']."'";
	
	if (mysqli_query($conn, $updatesql)) {
		$_SESSION['pwd_success'] = "<span class='alert-response-success'>
		Your password was successfuly changed. Please login with your new password</span>";
		unset($_SESSION['userlogin']);
		unset($_SESSION['userpassword']);
		header("Location: index.php");
	} else {
		$_SESSION['pwd_failure'] = "<span class='alert-response-failure'>Alert: An error occurred ".
		mysqli_error($conn)."</span>";
	}
}
?>
<html>
	<head>
		<title>Home</title>
		<meta name="viewport" content="width=device-width initial-scale=1.0"/>
		<script src="popup_windows.js" type="text/javascript"></script>
		<link href="heading_styles.css" type="text/css" rel="stylesheet" />
		<link href="lists_styles.css" type="text/css" rel="stylesheet" />
		<link href="page_styles.css" type="text/css" rel="stylesheet" />
		<link href="form_styles.css" type="text/css" rel="stylesheet" />
		<link href="links_styles.css" type="text/css" rel="stylesheet" />
		<link href="text_styles.css" type="text/css" rel="stylesheet" />
		
		<script>
		function logoutvalidate() {
			var checkedform = true;
			var username = document.logoutfrm.username.value;
			var currpwd = document.logoutfrm.currpwd.value;
			var newpwd = document.logoutfrm.newpwd.value;
			var cfmpwd = document.logoutfrm.cfmpwd.value;
			
			if (username == "") {
				checkedform = false;
				document.getElementById("erroruname").innerHTML = "This field is required";
				document.logoutfrm.username.focus();
			} else {
				document.getElementById("erroruname").innerHTML = "";
			}
			
			if (currpwd == "") {
				checkedform = false;
				document.getElementById("errorcurrpwd").innerHTML = "This field is required";
				document.logoutfrm.currpwd.focus();
			} else {
				document.getElementById("errorcurrpwd").innerHTML = "";
			}
			
			if (currpwd != "" && currpwd != "<?php echo $_SESSION['userpassword']; ?>"){
				checkedform = false;
				document.getElementById("errorcurrpwd").innerHTML = "This password is incorrect";
				document.logoutfrm.currpwd.focus();
			} else {
				document.getElementById("errorcurrpwd").innerHTML = "";
			}
			
			if (newpwd != "" && cfmpwd != "" && newpwd != cfmpwd) {
				checkedform = false;
				document.getElementById("errorcfmpwd").innerHTML = "The passwords do not match";
				document.logoutfrm.newpwd.value = "";
				document.logoutfrm.cfmpwd.value = "";
				document.logoutfrm.newpwd.focus();
			} else {
				document.getElementById("errorcfmpwd").innerHTML = "";
			}
			
			return checkedform;
		}
		
		function disabletext() {
			if (document.logoutfrm.currpwd.value != "") {
				document.logoutfrm.newpwd.disabled = false;
				document.logoutfrm.cfmpwd.disabled = false;
				document.logoutfrm.newpwd.focus();
			}
		}
		//document.logoutfrm.currpwd.AddEventListener("onblur", disabletext());
			
	</script>
	
	<?php 
		if (isset($_SESSION['pwd_failure'])) {
			echo <<<STYLE
				<style>
					#logout_container {
						display: block;
					}
				</style>
STYLE;
		}
?>
	</head>
		<body>
			<!-- Begining of header section -->
				<div id="header"> 
					<h1>ZERO INTEREST FINANCE LIMITED</h1>
					
					<div id="nav">
					<a class="active-nav-link" href="#">Home</a>
					<a href="customers_page.php">Customers Page</a>
					<a href="loans_page.html">Loans Page</a>
					</div>
					
					<div id="user">
					<p class="user-welcome">Welcome <?php echo $_SESSION["userlogin"]; ?></p>
						<ul class="user">
							<li class="user-link"><a href="#" onclick="openChangePassword(); return false;"
							>Change Password</a></li>
							<li class="user-link"><a href="logout.php">LogOut</a></li>
						</ul>
					</div>
				</div>
			<!-- End of header section -->
			
			<!-- Begining of sidebar navigation -->
				<div id="sidebarNav">
				<ul class="side-bar">
					<li class="side-list"><a href="customer_details.php">Add Customer & Loan</a></li>
					<li class="side-list"><a href="viewcustomers.php">View Customers List</a></li>
					<li class="side-list"><a href="customer_report.php">Customer Report</a></li>
					<li class="side-list"><a href="viewloans.php">View Loans</a></li>
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
				<div id="main_content">
					<p>Welcome to the Zero Interest Finance Ltd program. Click any of the links to go to your disired section</p>
				
				<?php include "footer.inc"; ?>
				</div>
			
				<div id="side_content">
					<form>
						<p><label>Loans Expiring Today:</label><input class="side_content" type="text"></p>
						<p><label>Loans Expiring Tommorrow:</label><input class="side_content" type="text"></p>
						<p><label>Current Number of Customers:</label><input class="side_content" type="text"></p>
						<p><label>Current Number of Loans:</label><input class="side_content" type="text"></p>
					</form>
				</div>
				
				<div id="logout_container">
			<h2>Change Password</h2>
			<?php if (isset($_SESSION['pwd_failure'])){echo $_SESSION['pwd_failure']; unset($_SESSION['pwd_failure']);} ?>
			<form class="form" name="logoutfrm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" onsubmit="return logoutvalidate()">
				<div class="row">
					<div class="col-25"><label>Username</label></div>
					<div class="col-75"><input type="text" name="username" value="<?php echo $_SESSION['userlogin']; ?>"/>
					<br/><span class="form-error" id="erroruname"></span></div>
				<div>
				
				<div class="row">
					<div class="col-25"><label>Current Password</label></div>
					<div class="col-75"><input type="password" name="currpwd" onblur="disabletext()"/>
					<br/><span class="form-error" id="errorcurrpwd"></span></div>
				</div>
				<br/>
				
				<div class="row">
					<div class="col-25"><label>New Password</label></div>
					<div class="col-75"><input type="password" name="newpwd" disabled />
					<br/><span class="form-error" id="errornewpwd"></span></div></div>
				</div>
				
				<div class="row">
					<div class="col-25"><label>Confirm Password</label></div>
					<div class="col-75"><input type="password" name="cfmpwd" disabled />
					<br/><span class="form-error" id="errorcfmpwd"></span></div></div>
				</div>
				
				<div class="chgpwd">
					<input type="submit" name="chgpwd" value="Change Password"/>
				</div>
				
				<div class="cancel">
					<input type="button" name="cancel" value="Cancel" onclick="closeChangePassword()"/>
				</div>
				
			</form>
		</div>
			
			<!-- End of content section -->
			
		</body>
		<script>
			document.logoutfrm.username.addEventListener("blur", logoutvalidate);
			
			window.onclick = function(event) {
				if (event.target == document.getElementById("logout_container")) {
					document.getElementById("logout_container").style.display = "none";
				}
			}
		</script>
</html>