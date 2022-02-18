<?php
session_start();
if (!isset($_SESSION["userlogin"])){
	header("Location: index.php");
	exit();
	}
?>