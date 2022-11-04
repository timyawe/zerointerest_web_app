<?php
include "login_session.inc";
//connect to database
require "dbconn.php";

//Get customer No
//unset($_SESSION['customer_no']);

/*if(!isset($_SESSION['loan_no'])){
	$cust_sqlresult = mysqli_query($conn, "SELECT CustomerNo FROM `Customers with loans` WHERE LOAN_NO =" .$_REQUEST['loan_no']);
}else{
	$cust_sqlresult = mysqli_query($conn, "SELECT CustomerNo FROM `Customers with loans` WHERE LOAN_NO =" .$_SESSION['loan_no']);
}*/


//Checks wether user wants to view loan details
if (!isset($_POST['add']) and !isset($_POST['edit']) ) {
	$_SESSION['loan_no'] = $_REQUEST['loan_no'];
	$cust_sqlresult = mysqli_query($conn, "SELECT CustomerNo FROM `Customers with loans` WHERE LOAN_NO =" .$_REQUEST['loan_no']);
	if(mysqli_num_rows($cust_sqlresult)>0){
		$custrow = mysqli_fetch_assoc($cust_sqlresult);
		$_SESSION['customer_no'] = $custrow['CustomerNo'];
	}
	header("Location: loandetails_edit.php");
}

//Check if user has clicked the 'add loan' button
if (isset($_POST['add'])){
	
	/*/Arrays to collect fields which have been filled
	the arrays are to be used to add a record in the database in a FIELDS LIST/VALUE LIST format*/ 
	$addedfields = array();
	$addedvalues = array();
	
	//Assign fileds to variables and add to the arrays
	
	if ($_POST['loanRefNo'] != "") {
		$loanRefNo = $_POST['loanRefNo'];
		$addedfields[] = "`LOAN_REF_NO`";
		$addedvalues[] = "'".$loanRefNo."'";
	}
	
	if ($_POST['startdate']!= "") {
		//Change date format for mysql date compatibility
		$startdate = date("Y-m-d",strtotime($_POST['startdate'])) ;
		$addedfields[] = "`START_DATE`";
		$addedvalues[] = "'".$startdate."'";
	}
	
	if ($_POST['periodInMonths']!= "") {
		$period = $_POST['periodInMonths'];
		$addedfields[] = "`PERIOD`";
		$addedvalues[] = "'".$period."'";
	}
	
	if ($_POST['prov_period'] != "") {
		$prov_period = $_POST['prov_period'];
		$addedfields[] = "`PROVISIONAL_PERIOD`";
		$addedvalues[] = "'".$prov_period."'";
	}
	
	if ($_POST['final_period']!= "") {
		$final_period = $_POST['final_period'];
		$addedfields[] = "`FINAL_PERIOD`";
		$addedvalues[] = "'".$final_period."'";
	}
	
	if ($_POST['final_period']!= "" && $_POST['startdate']!= "") {
		$stdate= date_create($_POST['startdate']);
		$pd = $_POST['final_period'];
		$gen_date = date_add($stdate,date_interval_create_from_date_string("$pd days"));
		//Change date format for mysql date compatibility
		$final_paymentdate = date_format($gen_date,"Y-m-d");
		
		$addedfields[] = "`FINAL_PAYMENT_DATE`";
		$addedvalues[] = "'".$final_paymentdate."'";
	}

	if ($_POST['principal']!= "") {
		//Remove the thousand seperator to make value decimal
		$principal = str_replace(',','',$_POST['principal']);
		$addedfields[] = "`PRINCIPAL`";
		$addedvalues[] = $principal;
	}
	
	if ($_POST['interestrate']!= "") {
		$interest = $_POST['interestrate'];
		$addedfields[] = "`INTERST_RATE`";
		$addedvalues[] = $interest;
	}
	
	if ($_POST['f_prin_int']!= "") {
		//Remove the thousand seperator to make value decimal
		$f_prin_int = str_replace(',','',$_POST['f_prin_int']);
		$addedfields[] = "`FINAL_PRINCIPAL_&_INTEREST`";
		$addedvalues[] = $f_prin_int;
	}
	
	if ($_POST['proInstmts'] == "Yes") {
		$proInstmts = 1;
	} else {
		$proInstmts = 0;
	}
		$addedfields[] = "`PROVISIONAL_INSTALMENTS?`";
		$addedvalues[] = $proInstmts;
	
	if ($_POST['status']!= "") {
		$status = $_POST['status'];
		$addedfields[] = "`STATUS`";
		$addedvalues[] = "'".$status."'";
	}
	
		$addedfields[] = "`CustomerNo`";
		$addedvalues[] = $_SESSION['customer_no'];

//echo "(".implode(",",$addedfields).")" . "VALUES" . "(".implode(",",$addedvalues).")" ;
	//Check if customer session is set to indicate that a particular customer has been selected
	if (!isset($_SESSION['customer_no'])) {
		header ("Location: viewcustomers.php");
	} else {
		//Check if array has elements
		if (count($addedfields) > 0 && count($addedvalues) > 0 ) {
			//Insert data in database
			$sql = "INSERT INTO `Loan Details Table` "."(".implode(",",$addedfields).")" . "VALUES" . "(".implode(",",$addedvalues).")";
		}	

				if (mysqli_query($conn, $sql)) {
					//echo "<h2>Loan Added</h2>";
					//Get the last inserted record
					$lastID = mysqli_insert_id($conn);

					//select last inserted record
					$selectsql = "SELECT * FROM `Loan Details Table` WHERE LOAN_NO =" . $lastID;

					$selectresult = mysqli_query($conn, $selectsql);	
					
					if (mysqli_num_rows($selectresult) > 0 ) {
						$loan_row = mysqli_fetch_assoc($selectresult);
						$_SESSION['loan_no'] = $loan_row['LOAN_NO'];
						$_SESSION['add_loan'] = 1;
						header ("Location: loandetails_edit.php");
					} else {
						echo "No loan". mysqli_error($conn);
					}
				} else {
					//echo mysqli_error($conn);
					$_SESSION['noadd_loan'] = "Insert Error: ". mysqli_error($conn);
					header ("Location: loandetails_edit.php");
					
				}
	}
}

if (isset($_POST['edit'])) {
	
	//find the currently edited record
	$editselectsql = "SELECT * FROM `Loan Details Table` WHERE LOAN_NO =" . $_SESSION['loan_no'];
	
	$editselectresult = mysqli_query($conn, $editselectsql);
	
	if (mysqli_num_rows($editselectresult) > 0 ) {
		$editloan_row = mysqli_fetch_assoc($editselectresult);
		$startdate = date("d-m-Y", strtotime($editloan_row['START_DATE']));
		$loanRefNo = $editloan_row['LOAN_REF_NO'];
		$period = $editloan_row['PERIOD'];
		$prov_period = $editloan_row['PROVISIONAL_PERIOD'];
		$final_period = $editloan_row['FINAL_PERIOD'];
		$enddate = date("d-m-Y", strtotime($editloan_row['FINAL_PAYMENT_DATE']));
		$principal = number_format($editloan_row['PRINCIPAL']);
		$interest = $editloan_row['INTERST_RATE'];
		$f_prin_int = number_format($editloan_row['FINAL_PRINCIPAL_&_INTEREST']);
		$comment = strip_tags($editloan_row['COMMENT']);
		$status = $editloan_row['STATUS'];
		if(is_null($editloan_row['DATE_CLEARED'])){//strtotime function interprets NULL date values as 0 hence creating an invalid date
			$cleareddate = "";
		}else{
			$cleareddate = date("d-m-Y", strtotime($editloan_row['DATE_CLEARED']));
		}
		$proInstmts = $editloan_row['PROVISIONAL_INSTALMENTS?'];
		$clearedby = $editloan_row['CLEARED_BY'];
		
	}
	
	$editedfields = array();
	
	//checks wether a field was edited and if so adds it to the array
	if ($_POST['loanRefNo'] != $loanRefNo) {
		$editedfields["LOAN_REF_NO"] = $_POST['loanRefNo'];
	}
	
	if ($_POST['startdate'] != $startdate) {
		//Change date format for mysql date compatibility
		$editedfields["START_DATE"] = date("Y-m-d", strtotime($_POST['startdate']));
	}
	
	if ($_POST['periodInMonths'] != $period) {
		$editedfields["PERIOD"] = $_POST['periodInMonths'];
	}
	
	if ($_POST['prov_period'] != $prov_period) {
		$editedfields["PROVISIONAL_PERIOD"] = $_POST['prov_period'];
	}
	
	if ($_POST['final_period'] != $final_period) {
		$editedfields["FINAL_PERIOD"] = $_POST['final_period'];
	}
	
	if ($_POST['FinalPaymentDate'] != $enddate) {
		//Change date format for mysql date compatibility
		$editedfields["FINAL_PAYMENT_DATE"] = date("Y-m-d", strtotime($_POST['FinalPaymentDate']));
	}
	
	if ($_POST['principal'] != $principal) {
		//Remove the thousand seperator to make value decimal
		$editedfields["PRINCIPAL"] = str_replace(',','',$_POST['principal']);
	}
	
	if ($_POST['interestrate'] != $interest) {
		$editedfields["INTERST_RATE"] = $_POST['interestrate'];
	}
	
	if ($_POST['f_prin_int'] != $f_prin_int) {
		//Remove the thousand seperator to make value decimal
		$editedfields["FINAL_PRINCIPAL_&_INTEREST"] = str_replace(',','',$_POST['f_prin_int']);
	}
	
	if ($_POST['status'] != $status) {
		$editedfields["STATUS"] =  $_POST['status'];
	}
	
	if ($_POST['datecleared'] != $cleareddate) {
		//Change date format for mysql date compatibility
		$editedfields["DATE_CLEARED"] = date("Y-m-d", strtotime($_POST['datecleared']));
	}
	
	if (isset($_POST['proInstmts']) and $_POST['proInstmts'] == "Yes") {
			$proInstmts2 = 1;
		} else {
			$proInstmts2 = 0;
		}
	
	if ($proInstmts2 != $proInstmts) {
		$editedfields["`PROVISIONAL_INSTALMENTS?`"] = $proInstmts2;
	}
	
	if($clearedby != $_POST['clearedby']){
		$editedfields["CLEARED_BY"] = $_POST['clearedby'];
	}
	
	/*if(mysqli_num_rows($cust_sqlresult)>0){
		$custrow = mysqli_fetch_assoc($cust_sqlresult);
		$_SESSION['customer_no'] = $custrow['CustomerNo'];
	}*/
		
	if (count($editedfields) > 0 ) {
		foreach($editedfields as $fieldname => $value) {
			$updatesql = "UPDATE `Loan Details Table` SET $fieldname = '$value' WHERE LOAN_NO =" . $_SESSION['loan_no'];
			
			if (mysqli_query($conn, $updatesql)) {
				//echo $fieldname . "=" . $value;
				//return updated record
				$updatedrecordsql = "SELECT LOAN_NO FROM `Loan Details Table` WHERE LOAN_NO =" . $_SESSION['loan_no']; 
				$updaterecordsqlresult = mysqli_query($conn, $updatedrecordsql);
				if (mysqli_num_rows($updaterecordsqlresult) > 0) {
					$updaterecordrow = mysqli_fetch_assoc($updaterecordsqlresult);
					$_SESSION['loan_no'] = $updaterecordrow["LOAN_NO"];
					$_SESSION['edit_loan'] = 1;
				header ("Location: loandetails_edit.php");
				}
			} else {
				echo mysqli_error($conn);
			}
		}
	} else {
		//echo "No edits";
		$_SESSION['noedit_loan'] = 1;
		header ("Location: loandetails_edit.php");
	}
	
	
}
	
	
?>