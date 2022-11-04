<?php 
include "login_session.inc"; 

//Connect to database
include "dbconn.php";

$json_post_file = file_get_contents('php://input');//recieves JSON type data
$json_data = json_decode($json_post_file);

$json_response = new stdClass();


//Checks wether user wants to view instalment details
if (!isset($json_data->inst_add) and !isset($json_data->inst_edit) ) {
	$_SESSION['InstalmentID'] = $_REQUEST['instalment_ID'];
	header("Location: instalment_form.php");
}

//Checks wether user has clicked the 'add' button on instalemts form
if (isset(/*$_POST['add']*/$json_data->inst_add)) {
	
	/*/Arrays to collect fields which have been filled
	the arrays are to be used to add a record in the database in a FIELDS LIST/VALUE LIST format*/ 
	$addedfields = array();
	$addedvalues = array();
	
	//Assign fileds to variables and add to the arrays
	if ($json_data->inst_type != "") {
		$inst_type = $json_data->inst_type;
		$addedfields[] = "`Instalment_Type`";
		$addedvalues[] = "'".$inst_type."'";
	}
	
	if ($json_data->inst_startdate != "") {
		//Change date format for mysql date compatibility
		$inst_startdate = date("Y-m-d", strtotime($json_data->inst_startdate));
		$addedfields[] = "`Instalment_StartDate`";
		$addedvalues[] = "'".$inst_startdate."'";
	}
		
	if ($json_data->inst_period != "") {
		$period = $json_data->inst_period;
		$addedfields[] = "`Instalment_Period`";
		$addedvalues[] = $period;
	}
	
	if ($json_data->inst_startdate != "" && $json_data->inst_period != "") {
		$st_date = date_create($json_data->inst_startdate);
		$pd = $json_data->inst_period;
		$gen_date = date_add($st_date, date_interval_create_from_date_string("$pd days"));
		//Change date format for mysql date compatibility
		$inst_enddate = date_format($gen_date,"Y-m-d");
		$addedfields[] = "`Instalment_EndDate`";
		$addedvalues[] = "'".$inst_enddate."'";
	}
	
	if ($json_data->inst_principal != "") {
		//Remove the thousand seperator to make value decimal
		$instalmentprincipal = str_replace(',','',$json_data->inst_principal);
		$addedfields[] = "`Instalment_Amount`";
		$addedvalues[] = $instalmentprincipal;
	}
	
	if ($json_data->inst_status != "") {
		$status = $json_data->inst_status;
		$addedfields[] = "`Instalment_Status`";
		$addedvalues[] = "'".$status."'";
	}
	
	/*if ($_POST['interestamount'] != "") {
		$interestamount = $_POST['interestamount'];
		$addedfields[] = "`Interest_Amount`";
		$addedvalues[] = $interestamount;
	}
	
	if ($_POST['totalamount'] != "") {
		//Remove the thousand seperator to make value decimal
		$totalamount = str_replace(',','',$_POST['totalamount']);
		$addedfields[] = "`TotalAmount`";
		$addedvalues[] = $totalamount;
	}*/
	
	if ($json_data->inst_comment != "") {
		$comment = $json_data->inst_comment;
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
		
			//Check wether another instalment is being added to update previous instalment, loan period and end date details.
			if ($inst_type == "Rescheduled") {
				$updt_prev_inst = mysqli_query($conn, "UPDATE `Loan Instalments` SET Instalment_Status = '$inst_type' , Instalment_ClearedDate = '$inst_startdate' WHERE Instalment_ID =".$_SESSION['prev_inst']);
				
				if(!$updt_prev_inst){//failed to update previous instalemt hence delete current
					$_SESSION['noadd_instalment'] = "Failed to add new record due to an update failure <br>". mysqli_error($conn); 
					mysqli_query($conn, "DELETE FROM `Loan Instalments` WHERE Instalment_ID = $lastID LIMIT 1");
					header("Location: instalment_form.php");
				}else{
					$fn_per_row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT FINAL_PERIOD FROM `Loan Details Table` WHERE LOAN_NO =" . $_SESSION['loan_no']));
					$updateloansql = "UPDATE LOW_PRIORITY `Loan Details Table` SET `FINAL_PERIOD` =". $fn_per_row['FINAL_PERIOD']. " + 
					$period, `FINAL_PAYMENT_DATE` = '$inst_enddate', STATUS = 'On-going' WHERE LOAN_NO =" . $_SESSION['loan_no'];
					
					if (mysqli_query($conn, $updateloansql)) {
						$_SESSION['loan_updated_by_instalment'] = 1;
						$_SESSION['InstalmentID'] = $lastID;
						$_SESSION['add_instalment'] = 1;
						header ("Location: loan_instalments.php");
					} 
				}
			}else{
				$_SESSION['InstalmentID'] = $lastID;
				$_SESSION['add_instalment'] = 1;
				header ("Location: loan_instalments.php");
			}
			
	} else {
		$_SESSION['noadd_instalment'] = "<span class='alert-response-error'>". mysqli_error($conn)."</span>";
		header ("Location: instalment_form.php");
	}
	
}


//Checks wether user has clicked the 'edit' button on instalments form
if (isset($json_data->inst_edit)) {

	//find the currently edited record
	$editselectsql = "SELECT * FROM `qry Loan Instalments` WHERE Instalment_ID =" . $_SESSION['InstalmentID'];
	
	$editselectresult = mysqli_query($conn, $editselectsql);
	
	if (mysqli_num_rows($editselectresult) > 0 ) {
		$editloan_row = mysqli_fetch_assoc($editselectresult);
		
		//$instalmenttype = $editloan_row['Instalment_Type'];
		//$startdate = date("d-m-Y",strtotime($editloan_row['Instalment_StartDate']));
		//$instalmentprincipal = number_format($editloan_row['Instalment_Amount']);
		//$period = $editloan_row['Instalment_Period'];
		//$enddate = date("d-m-Y", strtotime($editloan_row['Instalment_EndDate']));
		//$interestamount = $editloan_row['InterestAmount'];
		//$totalamount = $editloan_row['TotalAmount'];
		$penalty = number_format($editloan_row['Penalty']);
		if(is_null($editloan_row['Instalment_ClearedDate'])){//strtotime function interprets NULL date values as 0 hence creating an invalid date
			$cleareddate = "";
		}else{
			$cleareddate = date("d-m-Y", strtotime($editloan_row['Instalment_ClearedDate']));
		}
		$comment = $editloan_row['Comment'];
		//$status = $editloan_row['Instalment_Status'];
		
	}
	
	$editedfields = array();
	
	//checks wether a field was edited and if so adds it to the array 
	/*if ($json_data->inst_type != $instalmenttype) {
		$editedfields["Instalment_Type"] = $json_data->inst_type;
	}
	
	if ($json_data->inst_startdate != $startdate) {
		
		$editedfields["Instalment_StartDate"] = date("Y-m-d",strtotime($json_data->inst_startdate)) ;
	}
	
	if ($json_data->inst_period != $period) {
		$editedfields["Instalment_Period"] = $json_data->inst_period;
	}
	
	if ($json_data->inst_enddate != $enddate) {
		
		$editedfields["Instalment_EndDate"] = date("Y-m-d",strtotime($json_data->inst_enddate)) ;
	}
	
	if ($json_data->inst_principal != $instalmentprincipal) {
		
		$editedfields["Instalment_Amount"] = str_replace(',','',$json_data->inst_principal);
	}*/
	
	//Do not require update because they are generated by 'qry Loan Instalments' view
	/*if ($_POST['interestamount'] != $interestamount) {
		$editedfields["Interest_Amount"] = $_POST['interestamount'];
	}
	
	if ($_POST['totalamount'] != $totalamount) {
		//Remove the thousand seperator to make value decimal
		$editedfields["TotalAmount"]  = str_replace(',','',$_POST['totalamount']);
	}*/

	if ($json_data->inst_comment != $comment) {
		$editedfields["Comment"] = trim($json_data->inst_comment);
	}
	
	//Check if user is editing instalment to "Cleared" without payments
	/*switch ($json_data->inst_status) {
		case "Rescheduled":
		$paymentsselectsql = "SELECT PaidAmount FROM `Instalment Payments` WHERE ID =". $_SESSION['InstalmentID'];
		$paymentsselectsqlresult = mysqli_query($conn, $paymentsselectsql);
		if (mysqli_num_rows($paymentsselectsqlresult) == 0 ) {
				$_SESSION['no_payments'] = "<span class='alert-response-information'>Alert: This instalment cannot 
				be rescheduled at this time because it has no payments.</span>";
		
		} else {
			if($json_data->inst_status != $status){
				$editedfields["Instalment_Status"] = $json_data->inst_status;
			}
		}
		break;
		
		case "Cleared":
		$amountdueselectsql = "SELECT AmountDue FROM `Instalments` WHERE Instalment_ID = " .$_SESSION['InstalmentID'];
		$amountdueselectsqlresult = mysqli_query($conn, $amountdueselectsql);
		$amountduerow = mysqli_fetch_assoc($amountdueselectsqlresult);
		if ($amountduerow['AmountDue'] > 0 ) {
			$_SESSION['no_payments'] = "<span class='alert-response-information'>Alert: This instalment cannot be cleared 
				at this time because it has amount due.</span>";
		}else{
			if($json_data->inst_status != $status){
				$editedfields["Instalment_Status"] = $json_data->inst_status;
			}
		}
		break;
		
		default:
		if ($json_data->inst_status != $status) {
			$editedfields["Instalment_Status"] =  $json_data->inst_status;
		}
	}*/
	
	/*if ($json_data->inst_cleareddate != $cleareddate) {
		
		$editedfields["Instalment_ClearedDate"] = date("Y-m-d",strtotime($json_data->inst_cleareddate)) ;
	}*/
	
	if ($json_data->inst_penalty != $penalty) {
		//Remove the thousand seperator to make value decimal
		$editedfields["Penalty"] = str_replace(',','',$json_data->inst_penalty);
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
				header ("Location: loan_instalments.php");
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