<?php
include "login_session.inc";

if (isset($_POST['chgpwd'])) {
	//connect to database
	require "dbconn.php";

	$updatesql = "UPDATE Users SET Password =" ."'".$_POST['cfmpwd']."'" . "WHERE Username =". "'".$_POST['username']."'";
	
	if (mysqli_query($conn, $updatesql)) {
		$_SESSION['pwd_success'] = "Your password was successfuly changed. Please login with your new password";
		unset($_SESSION['userlogin']);
		unset($_SESSION['userpassword']);
		header("Location: index.php");
	} else {
		echo mysqli_error($conn);
	}
}

if (isset($_POST['cancel'])) {
	header("Location: main_page.php");
}
?>
<html>
	<head>
	<title>Change Password</title>
	<link href="form_styles.css" type="text/css" rel="stylesheet" />
	<script src="popup_windows.js" type="text/javascript"></script>
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
	
	<style>
		input[type=text] {
			
		}
		
	</style>
	</head>
	
	<body>
		
		<div class="logout_container">
			<h2>Change Password</h2>
			
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
	
	</body>
	<script>
		document.logoutfrm.username.addEventListener("blur", logoutvalidate);
	</script>
</html>

