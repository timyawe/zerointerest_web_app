<?php include "login_session.inc";

//Connect to database
include "dbconn.php";

$selectsql = "SELECT * FROM `Loan Details Table` WHERE CustomerNo =" .$_REQUEST['customer_no']. " ORDER BY LOAN_NO DESC LIMIT 1";

$selectsqlresult = mysqli_query($conn, $selectsql);

if (mysqli_num_rows($selectsqlresult) == 0 ) { //means no loan hence adding the first one
	unset($_SESSION['loan_no']);
	header("Location: loandetails_edit.php");
}

?>