<?php include "login_session.inc";

//Connect to database
include "dbconn.php";

unset($_SESSION['InstalmentID'],$_SESSION['inst_type'],$_SESSION['startdate'],$_SESSION['inst_principal']);
$selectsql = "SELECT * FROM `Instalments` WHERE LoanNo =" .$_REQUEST['loanNo']. " ORDER BY Instalment_ID DESC LIMIT 1";

$selectsqlresult = mysqli_query($conn, $selectsql);

if (mysqli_num_rows($selectsqlresult) == 0 ) { //means no instalment hence adding the first one
	$_SESSION['inst_type'] = "Initial";
	$_SESSION['startdate'] = $_REQUEST['loan_startdate'];
	$_SESSION['inst_principal'] = $_REQUEST['principal'];
	$_SESSION['inst_no'] = 1;
	header ("Location: instalment_form.php");
} else {
	$row = mysqli_fetch_assoc($selectsqlresult);
	$instID = $row['Instalment_ID'];
	
	//Check if user is adding new instalment when previous instalment has no payments
	$paymentsselectsql = "SELECT PaidAmount FROM `Instalment Payments` WHERE ID = $instID";
	$paymentsselectsqlresult = mysqli_query($conn, $paymentsselectsql);
	if (mysqli_num_rows($paymentsselectsqlresult) == 0 ) {
			$_SESSION['no_payments'] = "<span class='alert-response-information'>Alert: Cannot reschedule loan because current instalment one has no 
			payments.</span>";
			header ("Location: loan_instalments.php");
	} else {
		$_SESSION['inst_type'] = "Reschedule";
		if($row['Penalty'] > 0){
			$_SESSION['penalties'] = 1;
			$_SESSION['startdate'] = date("d-m-Y");
		}else{
			$_SESSION['startdate'] = date("d-m-Y",strtotime($row['Instalment_EndDate']));
		}
		$inst_no = $row['Instalment_No'];
		$_SESSION['inst_no'] = $inst_no + 1;
		$_SESSION['inst_principal'] = number_format($row['AmountDue']);
		$inst_status = $row['Instalment_Status'];
		$_SESSION['prev_inst'] = $instID;
		header ("Location: instalment_form.php");
	}
	
	/*if($inst_enddate == $_REQUEST['loan_enddate']) {//means the instalmets have matched the loan period
		$_SESSION['all_instalments_added'] = "Alert: It seems like all instalments have been added. <br/> 
		If you require to add a reschedule instalment, click 'Reschedule Loan' below.";
		header ("Location: loandetails_edit.php");
	} */	
}

?>