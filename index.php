<?php
session_start();
?>
<html>
	<head>
		<title>Login</title>
		<script src="loginvalidate.js" type="text/javascript"></script>
		<link href="form_styles.css" type="text/css" rel="stylesheet" />
		<link href="text_styles.css" type="text/css" rel="stylesheet" />
		<style type="text/css">
			body {
				background-color: #EBEBEB;
			}
		</style>
		
		<script>
		
	</script>
	</head>

	<body>
		
		
		<div class="login_container">
		<?php 
			if (isset($_SESSION['login_failure'])) {
				echo $_SESSION['login_failure'];
				unset($_SESSION['login_failure']);
			}
			
			if (isset($_SESSION['pwd_success'])) {
				echo $_SESSION['pwd_success'];
				unset($_SESSION['pwd_success']);
			}
		?>
		<h3> User Login </h3>
		
			<form class="form" name="lfm" action = "login.php" method="post" onsubmit="return validateform()">
				<p>Username:
					<input class="login-input" id="uname" type="text" name="username" >
					<br/><span class="form-error" id="erroruname"></span>
				</p>
				
				<p>Password:
					<input class="login-input" id="pword" type="password" name="password">
					<br/><span class="form-error" id="errorpword"></span>
				</p>
				
				<p><input type="submit" id="loginbtn" name="login" value="Login">

			</form>
		</div>
	
	
	</body>
</html>