<?php
session_start();

$host = "127.0.0.1";
$user = "root";
$password = "";
$database = "CUSTOMER DATABASE";

//Database Connection
$conn = new mysqli($host, $user, $password, $database);

//Verify Connection
if ($conn -> connect_error){
	die("Cannot connect to the database at this time, pleease try again later");
	}

//Check if user has submitted form
if ($_POST["login"] != "" ) {
	$CurrentUser = $_POST["username"];
	$UserPassword = $_POST["password"];
	
	//Select user and password from database
	$sql = "SELECT UserName, Password FROM Users WHERE UserName ='" . $CurrentUser . "'";
	$result = mysqli_query($conn, $sql);

	//Verifying login details
	if (mysqli_num_rows($result) == 0){
		$_SESSION['login_failure'] = "<span class='alert-response-error'>
		Sorry, user doesnot exist, please try again!</span>";
		header ("Location: index.php");
		} else {
			$row = mysqli_fetch_assoc($result);
			if ($row["Password"] == $UserPassword){
				$_SESSION["userlogin"] = $CurrentUser;
				$_SESSION["userpassword"] = $UserPassword;
				header("Location: main_page.php");
				//exit();
				} else {
					$_SESSION['login_failure'] = "<span class='alert-response-error'>
					Your password is incorrect, please try again!</span>";
					header("Location: index.php");
					}
				}
}
?>