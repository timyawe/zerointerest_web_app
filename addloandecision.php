<?php include "login_session.inc";

//Connect to database
include "dbconn.php";

$selectsql = "SELECT LOAN_NO FROM `Loan Details Table` WHERE CustomerNo =" .$_REQUEST['cust_no']. " AND STATUS <> 'Cleared' ORDER BY LOAN_NO DESC LIMIT 1";
$selectsqlresult = mysqli_query($conn, $selectsql);

if (mysqli_num_rows($selectsqlresult) == 0 ) { //means no loan hence adding the first one
	unset($_SESSION['loan_no']);
	$_SESSION['customer_no'] = $_REQUEST['cust_no'];
	header("Location: loandetails_edit.php");
}

?>