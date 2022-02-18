<?php 
include "login_session.inc"; 

//Connect to database
include "dbconn.php";

//Checks wether user wants to view instalment details
if (!isset($_POST['add']) and !isset($_POST['edit']) ) {
	$_SESSION['InstalmentID'] = $_REQUEST['instalment_ID'];
	header("Location: instalment_form.php");
}

//Checks wether user has clicked the 'add' button on instalemts form

if (isset($_POST['add'])) {
	
	/*/Arrays to collect fields which have been filled
	the arrays are to be used to add a record in the database in a FIELDS LIST/VALUE LIST format*/ 
	$addedfields = array();
	$addedvalues = array();
	
	//Assign fileds to variables and add to the arrays
	if ($_POST['instalmenttype'] != "") {
		$instalmenttype = $_POST['instalmenttype'];
		$addedfields[] = "`Instalment_Type`";
		$addedvalues[] = "'".$instalmenttype."'";
	}
	
	if ($_POST['startdate'] != "") {
		//Change date format for mysql date compatibility
		$startdate = date("Y-m-d", strtotime($_POST['startdate']));
		$addedfields[] = "`Instalment_StartDate`";
		$addedvalues[] = "'".$startdate."'";
	}
		
	if ($_POST['period'] != "") {
		$period = $_POST['period'];
		$addedfields[] = "`Instalment_Period`";
		$addedvalues[] = $period;
	}
	
	if ($_POST['enddate'] != "") {
		//Change date format for mysql date compatibility
		$enddate = date("Y-m-d", strtotime($_POST['enddate']));
		$addedfields[] = "`Instalment_EndDate`";
		$addedvalues[] = "'".$enddate."'";
	}
	
	if ($_POST['instalmentprincipal'] != "") {
		//Remove the thousand seperator to make value decimal
		$instalmentprincipal = str_replace(',','',$_POST['instalmentprincipal']);
		$addedfields[] = "`Instalment_Amount`";
		$addedvalues[] = $instalmentprincipal;
	}
	
	if ($_POST['status'] != "") {
		$status = $_POST['status'];
		$addedfields[] = "`Instalment_Status`";
		$addedvalues[] = "'".$status."'";
	}
	
	if ($_POST['interestamount'] != "") {
		$interestamount = $_POST['interestamount'];
		$addedfields[] = "`Interest_Amount`";
		$addedvalues[] = $interestamount;
	}
	
	if ($_POST['totalamount'] != "") {
		//Remove the thousand seperator to make value decimal
		$totalamount = str_replace(',','',$_POST['totalamount']);
		$addedfields[] = "`TotalAmount`";
		$addedvalues[] = $totalamount;
	}
	
	if ($_POST['comment'] != "") {
		$comment = $_POST['comment'];
		$addedfields[] = "`Comment`";
		$addedvalues[] = "'".$comment."'";
	}
	
	if (isset($_SESSION['inst_no'])) {
		$addedfields[] = "`Instalment_No`";
		$addedvalues[] = $_SESSION['inst_no'];
	}
	
		$addedfields[] = "`LoanNo`";
		$addedvalues[] = $_SESSION['loan_no'];
	
	//$penalty = $_POST['penalty'];
	//$cleareddate = $_POST['cleareddate'];

//echo "(".implode(",",$addedfields).")" . "VALUES" . "(".implode(",",$addedvalues).")" ;
	// remember instalment no
	//Check if array has elements
	if (count($addedfields) > 0 && count($addedvalues) > 0 ) {
		//Insert data in database
		$insertsql = "INSERT INTO `Loan Instalments`". "(".implode(",",$addedfields).")" . "VALUES" . "(".implode(",",$addedvalues).")" ;
	}
		
	if (mysqli_query($conn, $insertsql)) {
		//Get the last inserted record
		$lastID = mysqli_insert_id($conn);
		
		//select last inserted record
		$lastIDsql = "SELECT * FROM `Loan Instalments` WHERE Instalment_ID =" . $lastID;
		
		$lastIDresult = mysqli_query($conn, $lastIDsql);
		
		if (mysqli_num_rows($lastIDresult) > 0 ) {
			/*GLOBAL*/ $lastIDrow = mysqli_fetch_assoc($lastIDresult);
			
			//Check wether another instalment is being added to update loan period and end date details.
			if (isset($_SESSION['new_instalment'])) {
				
				$inst_period = $lastIDrow['Instalment_Period'];
				$inst_enddate = $lastIDrow['Instalment_EndDate'];
				
				$updateloansql = "UPDATE LOW_PRIORITY `Loan Details Table` SET `PROVISIONAL_PERIOD` = PROVISIONAL_PERIOD + 
				$inst_period, `FINAL_PAYMENT_DATE` = $inst_enddate WHERE LOAN_NO =" . $_SESSION['loan_no'];
				
				if (mysqli_query($conn, $updateloansql)) {
					$_SESSION['loan_updated_by_instalment'] = 1;
					$_SESSION['InstalmentID'] = $lastIDrow['Instalment_ID'];
					$_SESSION['add_instalment'] = 1;
					header ("Location: instalment_form.php");
				} 
			}
			
			$_SESSION['InstalmentID'] = $lastIDrow['Instalment_ID'];
			$_SESSION['add_instalment'] = 1;
			header ("Location: instalment_form.php");
		}
	} else {
		$_SESSION['noadd_instalment'] = "<span class='alert-response-error'>". mysqli_error($conn)."</span>";
		header ("Location: instalment_form.php");
	}
	
}


//Checks wether user has clicked the 'edit' button on instalemts form
if (isset($_POST['edit'])) {
	
	//find the currently edited record
	$editselectsql = "SELECT * FROM `qry Loan Instalments` WHERE Instalment_ID =" . $_SESSION['InstalmentID'];
	
	$editselectresult = mysqli_query($conn, $editselectsql);
	
	if (mysqli_num_rows($editselectresult) > 0 ) {
		$editloan_row = mysqli_fetch_assoc($editselectresult);
		
		$instalmenttype = $editloan_row['Instalment_Type'];
		$startdate = $editloan_row['Instalment_StartDate'];
		$instalmentprincipal = $editloan_row['Instalment_Amount'];
		$period = $editloan_row['Instalment_Period'];
		$enddate = $editloan_row['Instalment_EndDate'];
		$interestamount = $editloan_row['InterestAmount'];
		$totalamount = $editloan_row['TotalAmount'];
		$penalty = $editloan_row['Penalty'];
		$cleareddate = $editloan_row['Instalment_ClearedDate'];
		$comment = $editloan_row['Comment'];
		$status = $editloan_row['Instalment_Status'];
		
	}
	
	$editedfields = array();
	
	//checks wether a field was edited and if so adds it to the array 
	if ($_POST['instalmenttype'] != $instalmenttype) {
		$editedfields["Instalment_Type"] = $_POST['instalmenttype'];
	}
	
	if ($_POST['startdate'] != $startdate) {
		//Change date format for mysql date compatibility
		$editedfields["Instalment_StartDate"] = date("Y-m-d",strtotime($_POST['startdate'])) ;
	}
	
	if ($_POST['period'] != $period) {
		$editedfields["Instalment_Period"] = $_POST['period'];
	}
	
	if ($_POST['enddate'] != $enddate) {
		//Change date format for mysql date compatibility
		$editedfields["Instalment_EndDate"] = date("Y-m-d",strtotime($_POST['enddate'])) ;
	}
	
	if ($_POST['instalmentprincipal'] != $instalmentprincipal) {
		//Remove the thousand seperator to make value decimal
		$editedfields["Instalment_Amount"] = str_replace(',','',$_POST['instalmentprincipal']);
	}
	
	//Do not require update because they are generated by 'qry Loan Instalments' view
	/*if ($_POST['interestamount'] != $interestamount) {
		$editedfields["Interest_Amount"] = $_POST['interestamount'];
	}
	
	if ($_POST['totalamount'] != $totalamount) {
		//Remove the thousand seperator to make value decimal
		$editedfields["TotalAmount"]  = str_replace(',','',$_POST['totalamount']);
	}*/
	
	if ($_POST['comment'] != $comment) {
		$editedfields["Comment"] = trim($_POST['comment']);
	}
	
	//Check if user is editing instalment to "Cleared" without payments
	switch ($_POST['status']) {
		case "Rescheduled":
		$paymentsselectsql = "SELECT PaidAmount FROM `Instalment Payments` WHERE ID =". $_SESSION['InstalmentID'];
		$paymentsselectsqlresult = mysqli_query($conn, $paymentsselectsql);
		if (mysqli_num_rows($paymentsselectsqlresult) > 0 ) {
			$paymentsrow = mysqli_fetch_assoc($paymentsselectsqlresult);
			if ($paymentsrow['PaidAmount'] <= 0) {
				$_SESSION['no_payments'] = "<span class='alert-response-information'>Alert: This instalment cannot 
				be rescheduled at this time because it has no payments.</span>";
			}
		} else {
			$_SESSION['no_payments'] = "<span class='alert-response-information'>Alert: This instalment cannot 
				be rescheduled at this time because it has no payments.</span>";
		}
		break;
		
		case "Cleared":
		$amountdueselectsql = "SELECT AmountDue FROM `Instalments` WHERE Instalment_ID = " .$_SESSION['InstalmentID'];
		$amountdueselectsqlresult = mysqli_query($conn, $amountdueselectsql);
		$amountduerow = mysqli_fetch_assoc($amountdueselectsql);
		if ($amountduerow['AmountDue'] > 0 ) {
			$_SESSION['no_payments'] = "<span class='alert-response-information'>Alert: This instalment cannot be cleared 
				at this time because it has amount due.</span>";
		}
		break;
		
		default:
		if ($_POST['status'] != $status) {
			$editedfields["Instalment_Status"] =  $_POST['status'];
		}
	}
	
	if ($_POST['cleareddate'] != $cleareddate) {
		//Change date format for mysql date compatibility
		$editedfields["Instalment_ClearedDate"] = date("Y-m-d",strtotime($_POST['cleareddate'])) ;
	}
	
	if ($_POST['penalty'] != $penalty) {
		//Remove the thousand seperator to make value decimal
		$editedfields["Penalty"] = str_replace(',','',$_POST['penalty']);
	}
	
	if (count($editedfields) > 0 ) {
		foreach($editedfields as $fieldname => $value) {
			$updatesql = "UPDATE `Loan Instalments` SET $fieldname = '$value' WHERE Instalment_ID =" . $_SESSION['InstalmentID'];
			
			if (mysqli_query($conn, $updatesql)) {
				//echo $fieldname . "=" . $value;
				//return updated record
				$updatedrecordsql = "SELECT Instalment_ID FROM `qry Loan Instalments` WHERE Instalment_ID =" . $_SESSION['InstalmentID'];
				$updaterecordsqlresult = mysqli_query($conn, $updatedrecordsql);
				if (mysqli_num_rows($updaterecordsqlresult) > 0) {
					$updaterecordrow = mysqli_fetch_assoc($updaterecordsqlresult);
					$_SESSION['InstalmentID'] = $updaterecordrow["Instalment_ID"];
					$_SESSION['edit_instalment'] = 1;
				header ("Location: instalment_form.php");
				}
			} else {
				echo mysqli_error($conn);
			}
		}
	} else {
		//echo "No edits";
		$_SESSION['noedit_instalment'] = 1;
		header ("Location: instalment_form.php");
	}
	
}


?>