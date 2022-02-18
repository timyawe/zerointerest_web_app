<?php include "login_session.inc";

//Connect to database
include "dbconn.php";

$selectsql = "SELECT * FROM `Instalments` WHERE LoanNo =" .$_REQUEST['loan_no']. " ORDER BY Instalment_ID DESC LIMIT 1";

$selectsqlresult = mysqli_query($conn, $selectsql);

if (mysqli_num_rows($selectsqlresult) == 0 ) { //means no instalment hence adding the first one
	$_SESSION['inst_type'] = "Initial";
	$_SESSION['startdate'] = $_REQUEST['loan_startdate'];
	$_SESSION['inst_principal'] = $_REQUEST['principal'];
	$_SESSION['inst_no'] = 1;
	header ("Location: instalment_form.php");
} else {
	$row = mysqli_fetch_assoc($selectsqlresult);
	$inst_enddate = date("d-m-Y",strtotime($row['Instalment_EndDate']));
	$inst_no = $row['Instalment_No'];
	$inst_amountdue = $row['AmountDue'];
	$inst_status = $row['Instalment_Status'];
	
	if($inst_enddate == $_REQUEST['loan_enddate']) {//means the instalmets have matched the loan period
		$_SESSION['all_instalments_added'] = "Alert: It seems like all instalments have been added. <br/> 
		If you require to add a reschedule instalment, click 'Reschedule Loan' below.";
		header ("Location: loandetails_edit.php");
	} 
	
	if ($inst_status == "Rescheduled") {
		$_SESSION['inst_type'] = "Initial";
		$_SESSION['startdate'] = $inst_enddate;
		$_SESSION['inst_no'] = $inst_no + 1;
		$_SESSION['inst_principal'] = $inst_amountdue;
		$_SESSION['new_instalment'] = 1;
		header ("Location: instalment_form.php");
	} else {
		$_SESSION['InstalmentID'] = $row['Instalment_ID'];
		$_SESSION['not_rescheduled'] = "Alert: You cannot add another instalment before this one is rescheduled.";
		header ("Location: instalment_form.php");
	}
}

?>