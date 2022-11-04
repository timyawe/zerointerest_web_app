<?php include "login_session.inc" ?>
<?php
			//Connect to database
			include "dbconn.php";
			
			if (!isset($_SESSION['InstalmentID'])) {
				$instalmenttype = $_SESSION['inst_type'];
				$startdate = $_SESSION['startdate'];
				$instalmentprincipal = $_SESSION['inst_principal'];
				$status = "On-going";
				$period = $enddate =  $interestamount = $totalamount = $penalty = "";
				$cleareddate = $comment = "";
				$form_heading = "<h2 class='form_heading'>Add Instalment</h2>";
			}
			GLOBAL $cleareddate;
			if (isset($_SESSION['InstalmentID'])) {
				$form_heading = "<h2 class='form_heading'>Edit Instalment</h2>";
				$sql = "SELECT * FROM `qry Loan Instalments` WHERE Instalment_ID =" . $_SESSION['InstalmentID'];
					
				$result = mysqli_query($conn, $sql);
				
				if (mysqli_num_rows($result) > 0) {
					$instalment_row = mysqli_fetch_assoc($result);
					
					$instalmenttype = $instalment_row['Instalment_Type'];
					$startdate = date("d-m-Y", strtotime($instalment_row['Instalment_StartDate']));
					$instalmentprincipal = number_format($instalment_row['Instalment_Amount']);
					$period = $instalment_row['Instalment_Period'];
					$enddate = date("d-m-Y", strtotime($instalment_row['Instalment_EndDate']));
					$interestamount = number_format($instalment_row['InterestAmount']);
					$totalamount = number_format($instalment_row['TotalAmount']);
					$penalty = number_format($instalment_row['Penalty']);
					if ($instalment_row['Instalment_ClearedDate'] != "") {
					$cleareddate = date("d-m-Y", strtotime($instalment_row['Instalment_ClearedDate']));
					}
					$comment = $instalment_row['Comment'];
					$status = $instalment_row['Instalment_Status'];
				}
			}
			
			?>
			
<html>
	<head><title>Reschedule Instalment Form</title>
	<script src="instalmentvalidate.js" type="text/javascript"></script>
	<!--<link href="heading_styles.css" type="text/css" rel="stylesheet" />
	<link href="form_styles.css" type="text/css" rel="stylesheet" />
	<link href="text_styles.css" type="text/css" rel="stylesheet" />-->
	<style>
		.inst_btns{
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

		.inst_btns:hover {
			background-color: rgba(27,233,179,0.9);
			color: white;
			border-color: #111;
		}
		
		.penalties_lbl{
			color: red;
			padding-left: 5px;
		}
	</style>
	</head>
		<body>
		<div class="instalment_container">
			
			<?php
			echo $form_heading;
			if (isset($_SESSION['penalties'])) {
				echo "<p class='penalties_lbl'>This loan has penalties from the previous instalment(s), if you wish to continue and reschedule,
				</br>the penalties will be added to the current instalment.</p>";
				unset($_SESSION['penalties']);
			}
			
			if (isset($_SESSION['add_instalment'])) {
				echo "<span class='alert-response-success'>Instalment Added</span>";
				unset($_SESSION['add_instalment']);
			}
			
			if (isset($_SESSION['noadd_instalment'])) {
				echo "<span class='alert-response-error'>Alert: Instalment Not Added" . "Error: " . $_SESSION['noadd_instalment']. "</span>";
				unset($_SESSION['noadd_instalment']);
			}
			
			if (isset($_SESSION['edit_instalment'])) {
				echo "<span class='alert-response-success'>Instalment Edited</span>";
				unset($_SESSION['edit_instalment']);
			}
				
			if (isset($_SESSION['noedit_instalment'])) {
				echo "<span class='alert-response-failure'>No fields were edited</span>";
				unset($_SESSION['noedit_instalment']);
			}
			
			if (isset($_SESSION['not_rescheduled'])) {
				echo $_SESSION['not_rescheduled'];
				unset($_SESSION['not_rescheduled']);
			}
			
			if (isset($_SESSION['loan_updated_by_instalment'])) {
				echo "<span class='alert-response-information'>This instalment has updated loan provisional period and end date details</span>";
				unset($_SESSION['loan_updated_by_instalment']);
			}
			
			if (isset($_SESSION['no_payments'])) {
				echo $_SESSION['no_payments'];
				unset($_SESSION['no_payments']);
			}
				
			?>
			<form action="loaninstalments_handler.php" method="post">
				
				<div id="left">
					<div class="row">
						<div class="col-25"><label>Start Date:</label></div>
						<div class="col-75"><input class="main-formloan" type="text" name="inst_startdate" value="<?php echo $startdate; ?>" required readonly />
						<br/><span class="form-error" id="errorSDate"></span></div>
					</div>
					
					<div class="row">
						<div class="col-25"><label>Period:</label></div>
						<div class="col-75"><input class="main-formloan" type="text" name="inst_period" value="<?php echo $period; ?>" required <?php if (isset($_SESSION['InstalmentID'])) { echo "readonly"; } ?>>
						<br/><span class="form-error" id="errorperiod"></span></div>
					</div>
					
					<div class="row">
						<div class="col-25"><label>End Date:</label></div>
						<div class="col-75"><input class="main-formloan" type="text" name="inst_enddate" value="<?php echo $enddate; ?>" readonly />
						<br/><span class="form-error" id="errorEDate"></span></div>
					</div>
					
					<div class="row">
						<div class="col-25"><label>Status</label></div>
						<div class="col-75"><input class="main-formloan" type="text" name="inst_status" value="<?php echo $status; ?>" readonly >
					</div>
					</div>
					
					<div class="row">
						<div class="col-25"><label>Reschedule/Cleared Date:</label></div>
						<div class="col-75"><input class="main-formloan" type="text" name="inst_cleareddate" value="<?php echo $cleareddate; ?>" readonly >
						<br/><span class="form-error" id="errorCDate"></span></div>
					</div>
				</div>
				
				<div id="right">
					<div class="row">
						<div class="col-25"><label>Instalment Type:</label></div>
						<div class="col-75"><input class="main-formloan" type="text" name="instalmenttype" value="<?php echo $instalmenttype; ?>" readonly /></div>
					</div>
					
					<div class="row">
						<div class="col-25"><label>Instalment Principal:</label></div>
						<div class="col-75"><input class="main-formloan" type="text" name="instalmentprincipal" required value="<?php echo $instalmentprincipal; ?>" readonly />
						<br/><span class="form-error" id="errorinstprinc"></span></div>
					</div>
					
					<div class="row">
						<div class="col-25"><label>Interest Amount:</label></div>
						<div class="col-75"><input class="main-formloan" type="text" name="interestamount" value="<?php echo $interestamount; ?>" disabled />
						<br/><span class="form-error" id="errorinterest"></span></div>
					</div>
					
					<div class="row">
						<div class="col-25"><label>Total Amount:</label></div>
						<div class="col-75"><input class="main-formloan" type="text" name="totalamount" value="<?php echo $totalamount; ?>" disabled />
						<br/><span class="form-error" id="errortotalamount"></span></div>
					</div>
					
					<div class="row">
						<div class="col-25"><label>Penalty:</label></div>
						<div class="col-75"><input class="main-formloan" type="text" name="penalty" value="<?php echo $penalty; ?>"
						<?php if (!isset($_SESSION['InstalmentID'])) { echo "disabled"; } ?> ><br/><span class="form-error" id="errorpenalty"></span></div>
					</div>
				</div>
				
				<div>
				<label>Comment:</label>
				<textarea name="inst_comment"><?php echo $comment; ?></textarea>
				</div>
				
				
				<button type="button" class="inst_btns" title= "Click to confirm new instalment" onclick="addInstalment()"  <?php if (isset($_SESSION['InstalmentID'])) { echo "disabled"; } ?>/>Add Instalment</button>
				<button type="button" class="inst_btns" title="Click to confirm changes made to the instalment details" onclick="editInstalment()" <?php if (!isset($_SESSION['InstalmentID'])) { echo "disabled"; } ?>/>Save Changes</button>
				<button type="button" class="inst_btns" onclick="inject_inst_tabledata()">Cancel</button>
				
			</form>
		</div>

		</body>
</html>