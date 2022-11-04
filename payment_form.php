<?php include "login_session.inc"; 

//connect to database
include "dbconn.php";

if(isset($_REQUEST['new_pymt'])){
	unset($_SESSION['paymentID']);
}

if (!isset($_SESSION['paymentID'])) {
	$p_date = $p_type = $p_amount = $p_confmd = " ";
	$inst_ID = $_REQUEST['instalment_ID'];
} else {
	$sql = "SELECT * FROM `Payment Details` WHERE Payment_ID =" . $_SESSION['paymentID'];
	$sqlresult = mysqli_query($conn, $sql);
	
	if (mysqli_num_rows($sqlresult) > 0 ) {
		$row = mysqli_fetch_assoc($sqlresult);
		$p_date = date("d-m-Y", strtotime($row['PAYMENT_DATE']));
		$p_type = $row['PAYMENT_TYPE'];
		$p_amount = number_format($row['PAID_AMOUNT']);
		$p_confmd = $row['PAYMENT_CONFIRMED'];
		$inst_ID = $row['InstalmentID'];
		//unset($_SESSION['paymentID']);
	}
}

?>

<html>
	<head>
	<title>Payment Form</title>
	<link href="form_styles.css" type="text/css" rel="stylesheet" />
	<link href="text_styles.css" type="text/css" rel="stylesheet" />
	<style>
		.pymt_btn{
			/*background-color: #52B2A0;*/
			padding: 7px 7px;
			border-radius: 4px;
			/*color: #ffffff;
			font-family: calibri;
			font-size: 14;
			margin-top: 12px;*/
			border: 1px solid #52B2A0;/*#ccc;*/
			cursor: pointer;
			outline: none;
		}					

		.pymt_btn:hover {
			background-color: rgba(27,233,179,0.9);
			color: white;
			border-color: #111;
		}
	</style>
	</head>
		<body>
			<?php 
				if (isset($_SESSION['add_payment'])) {
					echo "<p>Payment Added</p>";
					unset($_SESSION['add_payment']);
				}
				
				if (isset($_SESSION['noadd_payment'])) {
					echo "<p>Sorry, the record was not added, please try again. <br/> Contact Administrator if issue persists. </p>";
					echo "Error: ". $_SESSION['noadd_payment'];
					unset($_SESSION['noadd_payment']);
				}
				
				if (isset($_SESSION['edit_payment'])) {
					echo "<p>Payment Edited</p>";
					unset($_SESSION['edit_payment']);
				}
				
				if (isset($_SESSION['noedit_payment'])) {
					echo  $_SESSION['noedit_payment'];
					unset($_SESSION['noedit_payment']);
				}
				
				if (isset($_SESSION['update_payment_error'])) {
					echo  $_SESSION['update_payment_error'];
					unset($_SESSION['update_payment_error']);
				}
				
				if (isset($_SESSION['loanamount_updated'])) {
					echo  $_SESSION['loanamount_updated'];
					unset($_SESSION['loanamount_updated']);
				}
			?>
			<div id="payment_form_container">
				<form method="POST" action="paymentdetails_handler.php">
					<input type="text" name="inst_ID" value="<?php echo $inst_ID; ?>" hidden />
					<div class="row">
					<div class="col-25"><label>Date:</label></div>
					<div class="col-75"><input type="text" name="p_date" value="<?php echo $p_date; ?>"></div>
					</div>
					
					<div class="row">
					<div class="col-25"><label>Payment Type:</label></div>
					<div class="col-75"><select name="p_type">
					<option <?php if ($p_type=="Bank") {echo "selected";} ?>>Bank</option>
					<option <?php if ($p_type=="Cash") {echo "selected";} ?>>Cash</option>
					<option <?php if ($p_type=="Mobile Money") {echo "selected";} ?>>Mobile Money</option>
					</select>
					</div>
					</div>
					
					<div class="row">
					<div class="col-25"><label for="p_amount" >Amount:</label></div>
					<div class="col-75"><input type="text" name="p_amount" value="<?php echo $p_amount; ?>"></div>
					</div>
					
					<div class="row">
					<div class="col-25"><label>Payment Confirmed:</label></div>
					<div class="col-75"><select name="p_confmd">
					<option <?php if ($p_confmd==1) {echo "selected";} ?>>Yes</option>
					<option <?php if ($p_confmd==0) {echo "selected";} ?>>No</option>
					</select>
					</div>
					</div>
					
					<button type="button" class="pymt_btn" title="Click to confirm new payment" onclick="addPayment()" <?php if (isset($_SESSION['paymentID'])) { echo "disabled"; } ?> >Add Payment</button>
					<button type="button" class="pymt_btn" title="Click to confirm changes" onclick="editPayment()" <?php if (!isset($_SESSION['paymentID'])) { echo "disabled"; } ?> >Save Changes</button>
					<input type="button" value="Cancel" onclick="inject_inst_tabledata()"/>
					
				</form>
			</div>
		</body>
</html>