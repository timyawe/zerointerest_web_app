<?php include "login_session.inc"; ?>
<?php

	//connect to database
	require "dbconn.php";
	
	if (!isset($_SESSION['loan_no'])) {
		$startdate = date("d-m-Y");
		$loanRefNo = $prov_period = $final_period = $periodInMonths = $FinalPaymentDate = $clearedby = "";
		$principal = $interestrate = $f_prin_int = $datecleared = $comment = $status = $proInstmts = "";
	}
	GLOBAL $FinalPaymentDate, $datecleared; 
	if (isset($_SESSION['loan_no'])) {
		$sql = "SELECT * FROM `Loan Details Table` WHERE LOAN_NO =" . $_SESSION['loan_no'];
		
		$sqlresult = mysqli_query($conn, $sql);
		
		if (mysqli_num_rows($sqlresult) > 0 ) {
			$loan_row = mysqli_fetch_assoc($sqlresult);
			$startdate = date("d-m-Y",strtotime($loan_row['START_DATE']));
			$loanRefNo = $loan_row['LOAN_REF_NO'];
			$prov_period = $loan_row['PROVISIONAL_PERIOD'];
			$final_period = $loan_row['FINAL_PERIOD'];
			$periodInMonths = $loan_row['PERIOD'];
			if ($loan_row['FINAL_PAYMENT_DATE'] != "") {
			$FinalPaymentDate = date("d-m-Y", strtotime($loan_row['FINAL_PAYMENT_DATE']));
			}
			$clearedby = $loan_row['CLEARED_BY'];
			$principal = number_format($loan_row['PRINCIPAL']);
			$interestrate = $loan_row['INTERST_RATE'];
			$f_prin_int = number_format($loan_row['FINAL_PRINCIPAL_&_INTEREST']);
			If ($loan_row['DATE_CLEARED'] != "") {
			$datecleared = date("d-m-Y", strtotime($loan_row['DATE_CLEARED']));
			}
			$comment = $loan_row['COMMENT'];
			$status = $loan_row['STATUS'];
			$proInstmts = $loan_row['PROVISIONAL_INSTALMENTS?'];
		}
	}
?>
<html>
	<head>
		<title>Loan Form</title>
		<script>
			function setFinalDate() {
				<?php echo "alert('Freaky');"; ?>
			}
		</script>
		<script src="new_customer.js" type="text/javascript"></script>
		<script src="loanvalidate.js" type="text/javascript"></script>
		<link href="heading_styles.css" type="text/css" rel="stylesheet" />
		<link href="lists_styles.css" type="text/css" rel="stylesheet" />
		<link href="page_styles.css" type="text/css" rel="stylesheet" />
		<link href="form_styles.css" type="text/css" rel="stylesheet" />
		<link href="links_styles.css" type="text/css" rel="stylesheet" />
		<link href="text_styles.css" type="text/css" rel="stylesheet" />
		<style>
			
		</style>
	</head>
	
	<body>
		<!-- Begining of header section -->
			<div id="header">
					<h1>ZERO INTEREST FINANCE LIMITED</h1>
					<div id="nav">
					<a href="main_page.php">Home</a>
					<a href="customers_page.php">Customers Page</a>
					<a class="active-nav-link" href="#">Loans Page</a>
					</div>
			
			</div>
		<!-- End of header section -->
		
		<!-- Begining of sidebar Navigation section -->
			<div id="SidebarNav">
				<ul class="side-bar">
				<?php 
					if (isset($_SESSION['loan_no'])) {
						echo "<li class='side-list'><a class='active-sidebar-link' href='#'>Edit Loan</a></li>";
					} else {
						echo "<li class='side-list'><a class='active-sidebar-link' href='#'>Add Loan</a></li>";
					}
				?>
					<li class="side-list"><a href="viewloans.php">View Loans</a></li>
					<li class="side-list"><a href="defaulters.php">Defaulters</a></li>
					<li class="side-list"><a href="on-goingloans.php">On-going Loans</a></li>
					<li class="side-list"><a href="clearedloans.php">Cleared Loans</a></li>
					<li class="side-list"><a href="payments_list.php">Loan Payments</a></li>
					<li class="side-list"><a href="defaulters_report.php">Defaulters Report</a></li>
					<li class="side-list"><a href="clearedloans_report.php">Cleared Loans Report</a></li>
				</ul>
			
			</div>
		<!-- End of sidebar Navigation section -->
		
		<!-- Begining of Main form section -->
		<div class="main_content">
			<div id="LoanFormSection" class="container">
			<h2 class="form_heading">Loan Details</h2>
			<?php 
			
				if (isset($_SESSION['add_loan'])) {
					echo "<span class='alert-response-success'>Loan Added</span>";
					unset($_SESSION['add_loan']);
				}
				
				if (isset($_SESSION['noadd_loan'])) {
					echo "<span class='alert-response-error'>Alert: Sorry, the record was not added, please try again. <br/> 
							Contact Administrator if issue persists. ". $_SESSION['noadd_loan']. "</span>";
					unset($_SESSION['noadd_loan']);
				}
				
				if (isset($_SESSION['edit_loan'])) {
					echo "<span class='alert-response-success'>Loan Edited</span>";
					unset($_SESSION['edit_loan']);
				}
				
				if (isset($_SESSION['noedit_loan'])) {
					echo "<span class='alert-response-failure'>No field was edited</span>";
					unset($_SESSION['noedit_loan']);
				}
				
				if (isset($_SESSION['all_instalments_added'])) {
					echo $_SESSION['all_instalments_added'];
					unset($_SESSION['all_instalments_added']);
				}
			?>
				<form name="loanform" id="NewLoanForm" action=" " method="post" onsubmit="return validateform()">
					<!--<p> -->
					<!--<label>Loan No:</label> -->
					<!--<input type="text" required="required"/> -->
					<!--</p> -->
					
					<div id="left">
						<div class="left">
						<div class="row">
						<div class="col-25"><label>Start Date:</label></div>
						<div class="col-75"><input class="main-formloan" type="text" value="<?php echo $startdate; ?>" required="required" name="startdate" onblur="validateform()"/>
						<br/><span class="form-error" id="errorSDate"></span></div>
						</div>
						</div>
					
						<div class="left">
						<div class="row">
						<div class="col-25"><label>Period in Days:</label></div>
						<div class="col-75"><input class="main-formloan" style="width:42%" type="text" value="<?php echo $prov_period; ?>" name="prov_period" onblur="checkPeriodInDays(), checkProvPeriod()" />
						<label>of</label>
						<input class="main-formloan" style="width:42%" type="text" value="<?php echo $final_period; ?>"
						required="required" name="final_period" onblur="setPeriod()" /><br/><span class="form-error" id="errordaysperiod"></span></div>
						</div>
						</div>
					
						<div class="left">
						<div class="row">
						<div class="col-25"><label>Period in Months:</label></div>
						<div class="col-75"><input class="main-formloan" type="text" value="<?php echo $periodInMonths; ?>"	required="required" name="periodInMonths"/></div>
						</div>
						</div>
					
						<div class="left">
						<div class="row">
						<div class="col-25"><label>Final Payment Date:</label></div>
						<div class="col-75"><input class="main-formloan" type="text" value="<?php echo $FinalPaymentDate; ?>" name="FinalPaymentDate" onblur="setFinalDate()" />
						<br/><span class="form-error" id="errorEDate"></span></div>
						</div>
						</div>
					
						<div class="left">
						<div class="row">
						<div class="col-25"><label>Status</label></div>
						<div class="col-75"><select style="width: 100%" class="main-formloan" name="status">
							<option value="On-going" <?php if($status == "On-going") { echo "selected";}?>>On-going</option>
							<option value="Defaulting" <?php if($status == "Defaulting") { echo "selected";}?>>Defaulting</option>
							<option value="Cleared" <?php if($status == "Cleared") { echo "selected";}?>>Cleared</option>
						</select></div>
						</div>
						</div>
						
						<div class="left">
						<div class="row">
						<div class="col-25"><label>Cleared By:</label></div>
						<div class="col-75"><input class="main-formloan" type="text" value="<?php echo $clearedby; ?>" name="clearedby"
						<?php if (!isset($_SESSION['loan_no'])) { echo "disabled"; } ?>/></div>
						</div>
						</div>
						
					</div>
					
					<div id="right">
						<div class="right">
						<div class="row">
						<div class="col-25"><label>Loan Ref No:</label></div>
						<div class="col-75"><input class="main-formloan" type="text" value="<?php echo $loanRefNo; ?>" name="loanRefNo"/>
						<br/><span class="form-error" id="errorRefno"></span></div>
						</div>
						</div>
						
						<div class="right">
						<div class="row">
						<div class="col-25"><label>Principal:</label></div>
						<div class="col-75"><input class="main-formloan" type="text" value="<?php echo $principal; ?>" required="required" name="principal" onblur="validateform(), setFinalPrincInt()"/>
						<br/><span class="form-error" id="errorPrincipal"></span></div>
						</div>
						</div>
						
						<div class="right">
						<div class="row">
						<div class="col-25"><label>Interest Rate:</label></div>
						<div class="col-75"><input class="main-formloan" type="text" value="<?php echo $interestrate; ?>" required="required" name="interestrate" onblur="setFinalPrincInt()"/></div>
						</div>
						</div>
					
						<div class="right">
						<div class="row">
						<div class="col-25"><label>Final Principal & Interest:</label></div>
						<div class="col-75"><input class="main-formloan" type="text" value="<?php echo $f_prin_int; ?>" required="required" name="f_prin_int"/>
						<br/><span class="form-error" id="errorPrinInt"></span></div>
						</div>
						</div>
					
						<div class="right">
						<div class="row">
						<div class="col-25"><label>Provisional Instalments:</label></div>
						<input style="float: none" type="checkbox" name="proInstmts" value="Yes" 
						<?php if ($proInstmts == 1){ echo "checked"; } ?> />
						</div>
						</div>
					
						<div class="right">
						<div class="row">
						<div class="col-25"><label>Date Cleared:</label></div>
						<div class="col-75"><input class="main-formloan" type="text" value="<?php echo $datecleared; ?>" name="datecleared"
						<?php if (!isset($_SESSION['loan_no'])) { echo "disabled"; } ?>/></div>
						</div>
						</div>
						
					</div>
					
					<div class="bottom">
					<div class="row">
					<div class="25-left"><label>Comment:</label></div>
					<div class="75-right"><textarea name="comment" rows="8"><?php echo $comment ?></textarea></div>
					</div>
					</div>
					
					
					<div class="form-buttons">
					<div class="row">
					<input type="submit" value="Add Loan" name="add" title="Click to confirm new loan details" <?php if(isset($_SESSION['loan_no'])) {echo "disabled"; } ?> />
					<input type="submit" value="Save Changes" name="edit" title="Click to confirm changes made to the loan details" <?php if(!isset($_SESSION['loan_no'])){ echo "disabled"; } ?> />
					<?php if (isset($_SESSION['loan_no'])) 
					{ echo "<a class='form-link' href='addinstalmentdecision.php?loan_no={$_SESSION['loan_no']}&principal=$principal&loan_startdate=$startdate&loan_enddate=$FinalPaymentDate'>Add Instalment</a>"; } ?>
					<?php if (isset($_SESSION['loan_no'])) { echo "<a class='form-link' href='loan_instalments.php'>View Instalments</a>"; } ?>
					</div>
					</div> 
				</form>
			</div>
		</div>
		<!-- End of main form section -->
	</body>
</html>