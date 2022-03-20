<?php include "login_session.inc";

//connect to database
include "dbconn.php";

//Checks wether user wants to view payment details
if (!isset($_POST['add']) and !isset($_POST['edit']) ) {
	$_SESSION['paymentID'] = $_REQUEST['payment_ID'];
	header("Location: payment_form.php");
}

//Checks wether user has clicked the 'add' button on payment form
if (isset($_POST['add'])) {
	
	//Change date format for mysql date compatibility
	$p_date = date("Y-m-d", strtotime($_POST['p_date']));
	
	$p_type = $_POST['p_type'];
	
	//Remove the thousand seperator to make value decimal
	$p_amount = str_replace(',','',$_POST['p_amount']);
	
	if ($_POST['p_confmd'] == "Yes") {
		$p_confmd = 1;
	} else {
		$p_confmd = 0;
	}
	
	$inst_ID = $_POST['inst_ID'];
	
	//insert data into database
	$insertsql = "INSERT INTO `Payment Details` (LOAN_NO, 
													PAYMENT_DATE, 
													PAID_AMOUNT, 
													PAYMENT_TYPE,
													PAYMENT_CONFIRMED,
													InstalmentID)".
													"VALUES ({$_SESSION['loan_no']},
													'{$p_date}',
													{$p_amount},
													'{$p_type}',
													'{$p_confmd}',
													{$inst_ID})";
		
		if (mysqli_query($conn, $insertsql)) {
			//Get last inserted record
			$lastID = mysqli_insert_id($conn);
			
			$lastIDsql = "SELECT * FROM `Payment Details` WHERE PAYMENT_ID =" . $lastID;
			
			$lastIDresult = mysqli_query($conn, $lastIDsql);
			
			if (mysqli_num_rows($lastIDresult) > 0 ) {
				$lastIDrow = mysqli_fetch_assoc($lastIDresult);
				$_SESSION['paymentID'] = $lastIDrow['PAYMENT_ID'];
				$confmd = $lastIDrow['PAYMENT_CONFIRMED'];
				$amount = $lastIDrow['PAID_AMOUNT'];
				$loanno = $lastIDrow['LOAN_NO'];
				$_SESSION['add_payment'] =1;
				//update loan amount with PAID_AMOUNT value if the payment is confirmed
				if ($confmd == 1) {
					$loanamountupdatesql = "UPDATE `Loan Details Table` SET `AMOUNT_PAID` = AMOUNT_PAID + $amount WHERE LOAN_NO= $loanno";
					if (mysqli_query($conn, $loanamountupdatesql)) {
						$_SESSION['loanamount_updated'] = "Alert: Loan amount has been updated";
					}
				}
				header("Location: payment_form.php");
			}
		} else {
			$_SESSION['noadd_payment'] = mysqli_error($conn);
			header("Location: payment_form.php");
		}
		
}


//Checks wether user has clicked the 'edit' button on payment form
if (isset($_POST['edit'])) {
	
	//find the currently edited record
	$editselectsql = "SELECT * FROM `Payment Details` WHERE PAYMENT_ID =" . $_SESSION['paymentID'];
	
	$editselectresult = mysqli_query($conn, $editselectsql);
	
	if (mysqli_num_rows($editselectresult) > 0 ) {
		$editloan_row = mysqli_fetch_assoc($editselectresult);
		$p_date = $editloan_row['PAYMENT_DATE'];
		$p_amount = $editloan_row['PAID_AMOUNT'];
		$p_type = $editloan_row['PAYMENT_TYPE'];
		$p_confmd = $editloan_row['PAYMENT_CONFIRMED'];
	}
	
	$editedfields = array();
	
	//checks wether a field was edited and if so adds it to the array 
	if ($_POST['p_date'] != $p_date) {
		//Change date format for mysql date compatibility
		$editedfields["PAYMENT_DATE"] = date("Y-m-d", strtotime($_POST['p_date']));
	}
	
	if ($_POST['p_amount'] != $p_amount) {
		//Remove the thousand seperator to make value decimal
		$editedfields["PAID_AMOUNT"] = str_replace(',','',$_POST['p_amount']);
	}
	
	if ($_POST['p_type'] != $p_type) {
		$editedfields["PAYMENT_TYPE"] = $_POST['p_type'];
	}
	
	if (isset($_POST['p_confmd']) and $_POST['p_confmd'] == "Yes") {
			$p_confmd2 = 1;
		} else {
			$p_confmd2 = 0;
		}
	
	if ($p_confmd2 != $p_confmd) {
		$editedfields["PAYMENT_CONFIRMED"] = $p_confmd2;
	}
	
	if (count($editedfields) > 0 ) {
		foreach($editedfields as $fieldname => $value) {
			$updatesql = "UPDATE `Payment Details` SET $fieldname = '$value' WHERE PAYMENT_ID =". $_SESSION['paymentID'];
			if (mysqli_query($conn, $updatesql)) {
				$updatedrecordsql = "SELECT * FROM `Payment Details` WHERE PAYMENT_ID =". $_SESSION['paymentID'];
				$updaterecordsqlresult = mysqli_query($conn, $updatedrecordsql);
				if (mysqli_num_rows($updaterecordsqlresult) > 0) {
					$updaterecordrow = mysqli_fetch_assoc($updaterecordsqlresult);
					$_SESSION['paymentID'] = $updaterecordrow["PAYMENT_ID"];
					$_SESSION['edit_payment'] = 1;
					updateloanamount($updaterecordrow['PAYMENT_CONFIRMED'], $updaterecordrow['PAID_AMOUNT'], $updaterecordrow['LOAN_NO']);
					header("Location: payment_form.php");
				}
			} else {
				$_SESSION['update_payment_error'] = mysqli_error($conn);
				header("Location: payment_form.php");
			}
		}
	} else {
		$_SESSION['noedit_payment'] = "No field was edited";
		header("Location: payment_form.php");
	}
}

function updateloanamount($confmd, $amount, $loanno) {
	if ($confmd == 1) {
		$loanamountupdatesql = "UPDATE `Loan Details Table` SET `AMOUNT_PAID` = AMOUNT_PAID + $amount WHERE LOAN_NO= $loanno";
		if (mysqli_query($conn, $loanamountupdatesql)) {
			$_SESSION['loanamount_updated'] = "Alert: Loan amount has been updated";
		}
	} else {
		$loanamountupdatesql = "UPDATE `Loan Details Table` SET `AMOUNT_PAID` = AMOUNT_PAID - $amount WHERE LOAN_NO= $loanno";
		if (mysqli_query($conn, $loanamountupdatesql)) {
			$_SESSION['loanamount_updated'] = "Alert: Loan amount has been updated";
		}
	}
}
?>