<?php include "login_session.inc";

//connect to database
include "dbconn.php";
$json_post_file = file_get_contents('php://input');
$json_data = json_decode($json_post_file);

$json_response = new stdClass();

//Checks wether user wants to view payment details
if (!isset($json_data->pymt_add) and !isset($json_data->pymt_edit) ) {
	$_SESSION['paymentID'] = $_REQUEST['payment_ID'];
	header("Location: payment_form.php");
}

//Checks wether user has clicked the 'add' button on payment form
if (isset($json_data->pymt_add)) {
	
	//Change date format for mysql date compatibility
	$p_date = date("Y-m-d", strtotime($json_data->p_date));
	
	$p_type = $json_data->p_type;
	
	//Remove the thousand seperator to make value decimal
	$p_amount = str_replace(',','',$json_data->p_amount);
	
	if ($json_data->p_confmd == "Yes") {
		$p_confmd = 1;
	} else {
		$p_confmd = 0;
	}
	
	$inst_ID = $json_data->inst_id;
	
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
													{$p_confmd},
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
					
					$lnamount_sql = mysqli_query($conn, "SELECT AMOUNT_PAID FROM `Loan Details Table` WHERE LOAN_NO=$loanno");
					$row = mysqli_fetch_assoc($lnamount_sql);
					$oldvalue = $row['AMOUNT_PAID'];
					
					$loanamountupdatesql = "UPDATE `Loan Details Table` SET `AMOUNT_PAID` = $oldvalue + $amount WHERE LOAN_NO= $loanno";
					if (mysqli_query($conn, $loanamountupdatesql)) {
						
						$amountdueselectsql = "SELECT AmountDue FROM `Instalments` WHERE Instalment_ID = $inst_ID";
						$amountdueselectsqlresult = mysqli_query($conn, $amountdueselectsql);
						$amountduerow = mysqli_fetch_assoc($amountdueselectsqlresult);
						if ($amountduerow['AmountDue'] == 0 ) {
							$updt_inst = mysqli_query($conn, "UPDATE `Loan Instalments` SET Instalment_Status = 'Cleared' , Instalment_ClearedDate = '$p_date' WHERE Instalment_ID =$inst_ID");
							if($updt_inst){
								$_SESSION['cleared_inst'] = "<p class='alert-response-information'>Alert: Instalment has been cleared .</p>";
							}else{
								$_SESSION['cleared_inst'] = mysqli_error($conn);
							}
						}
						$_SESSION['loanamount_updated'] = "Alert: Loan amount has been updated";
					}
				}
				header("Location: instalment_payments.php?instalment_ID=$inst_ID");
			}
		} else {
			$_SESSION['noadd_payment'] = mysqli_error($conn);
			header("Location: payment_form.php");
		}		
}


//Checks wether user has clicked the 'edit' button on payment form
if (isset($json_data->pymt_edit)) {
	
	//find the currently edited record
	$editselectsql = "SELECT * FROM `Payment Details` WHERE PAYMENT_ID =" . $_SESSION['paymentID'];
	
	$editselectresult = mysqli_query($conn, $editselectsql);
	
	if (mysqli_num_rows($editselectresult) > 0 ) {
		$editloan_row = mysqli_fetch_assoc($editselectresult);
		$p_date = date("d-m-Y", strtotime($editloan_row['PAYMENT_DATE']));
		$p_amount = number_format($editloan_row['PAID_AMOUNT']);
		$p_type = $editloan_row['PAYMENT_TYPE'];
		$p_confmd = $editloan_row['PAYMENT_CONFIRMED'];
	}
	
	$editedfields = array();
	
	//checks wether a field was edited and if so adds it to the array 
	if ($json_data->p_date != $p_date) {
		//Change date format for mysql date compatibility
		$editedfields["PAYMENT_DATE"] = date("Y-m-d", strtotime($json_data->p_date));
	}
	
	if ($json_data->p_amount != $p_amount) {
		//Remove the thousand seperator to make value decimal
		$editedfields["PAID_AMOUNT"] = str_replace(',','',$json_data->p_amount);
	}
	
	if ($json_data->p_type != $p_type) {
		$editedfields["PAYMENT_TYPE"] = $json_data->p_type;
	}
	
	if (isset($json_data->p_confmd) and $json_data->p_confmd == "Yes") {
			$p_confmd2 = 1;
		} else {
			$p_confmd2 = 0;
		}
	
	if ($p_confmd2 != $p_confmd) {
		$editedfields["PAYMENT_CONFIRMED"] = $p_confmd2;
	}
	
	if (count($editedfields) > 0 ) {
		$inverted_array = array_reverse($editedfields);
		foreach($inverted_array as $fieldname => $value) {
			 $updatesql = "UPDATE `Payment Details` SET $fieldname = '$value' WHERE PAYMENT_ID =". $_SESSION['paymentID'];
			
			if (mysqli_query($conn, $updatesql)) {
				$updatedrecordsql = "SELECT * FROM `Payment Details` WHERE PAYMENT_ID =". $_SESSION['paymentID'];
				$updaterecordsqlresult = mysqli_query($conn, $updatedrecordsql);
				if (mysqli_num_rows($updaterecordsqlresult) > 0) {
					$updaterecordrow = mysqli_fetch_assoc($updaterecordsqlresult);
					$_SESSION['paymentID'] = $updaterecordrow["PAYMENT_ID"];
					$_SESSION['edit_payment'] = 1;
					if($fieldname == "PAYMENT_CONFIRMED"){
						updateloanamount($updaterecordrow['PAYMENT_CONFIRMED'], $updaterecordrow['PAID_AMOUNT'], $updaterecordrow['LOAN_NO'],$conn);
					}
					if($fieldname == "PAID_AMOUNT"){
						 checkAmountPaid($editloan_row['PAID_AMOUNT'], $value, $updaterecordrow['LOAN_NO'],$updaterecordrow['PAYMENT_CONFIRMED'],$conn);
						
					}
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

function updateloanamount($confmd, $amount, $loanno,$db_conn) {
	$lnamount_sql = mysqli_query($db_conn, "SELECT AMOUNT_PAID FROM `Loan Details Table` WHERE LOAN_NO=$loanno");
	$row = mysqli_fetch_assoc($lnamount_sql);
	$oldvalue = $row['AMOUNT_PAID'];
	
	if ($confmd == 1) {
		$loanamountupdatesql = "UPDATE `Loan Details Table` SET `AMOUNT_PAID` = $oldvalue + $amount WHERE LOAN_NO= $loanno";
		if (mysqli_query($db_conn, $loanamountupdatesql)) {
			$_SESSION['loanamount_updated'] = "Alert: Loan amount has been updated";
		}
	} else {
		$loanamountupdatesql = "UPDATE `Loan Details Table` SET `AMOUNT_PAID` = $oldvalue - $amount WHERE LOAN_NO= $loanno";
		if (mysqli_query($db_conn, $loanamountupdatesql)) {
			$_SESSION['loanamount_updated'] = "Alert: Loan amount has been updated";
		}
	}
}

function checkAmountPaid($oldvalue, $newvalue, $loanno, $confmd,$db_conn){
	$lnamount_sql = mysqli_query($db_conn, "SELECT AMOUNT_PAID FROM `Loan Details Table` WHERE LOAN_NO=$loanno");
	$row = mysqli_fetch_assoc($lnamount_sql);
	$loanamnt = $row['AMOUNT_PAID'];
	
	if($confmd == 1){
		if($oldvalue > $newvalue){
			$finalvalue = $oldvalue - $newvalue;
			$loanamountupdatesql = "UPDATE `Loan Details Table` SET `AMOUNT_PAID` = $loanamnt - $finalvalue WHERE LOAN_NO= $loanno";
			if (mysqli_query($db_conn, $loanamountupdatesql)) {
				$_SESSION['loanamount_updated'] = "Alert: Loan amount has been updated";
			}
		}elseif($oldvalue < $newvalue){
			$finalvalue = $newvalue - $oldvalue;
			$loanamountupdatesql = "UPDATE `Loan Details Table` SET `AMOUNT_PAID` = $loanamnt + $finalvalue WHERE LOAN_NO= $loanno";
			if (mysqli_query($db_conn, $loanamountupdatesql)) {
				$_SESSION['loanamount_updated'] = "Alert: Loan amount has been updated";
			}
		}
	}
}
?>