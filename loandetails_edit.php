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
<!DOCTYPE html>
	<head>
		<title>Loan Form</title>
		<script>
			function openTab(evnt, tab) {
				
				var i, tabcontent, tablinks;
				
				tabcontent = document.getElementsByClassName("tab-content");
				
				for (i = 0; i < tabcontent.length; i++) {
					tabcontent[i].style.display = "none";
				}
				
				tablinks = document.getElementsByClassName("tab-links");
				
				for (i = 0; i < tablinks.length; i++) {
					tablinks[i].className = tablinks[i].className.replace("active", "");
				}
				
				document.getElementById(tab).style.display = "block";
				
				evnt.currentTarget.className += " active";
				
			}

			function setFinalDate() {
				<?php echo "alert('Freaky');"; ?>
			}
		</script>
		<!--<script src="new_customer.js" type="text/javascript"></script>-->
		<script src="loanvalidate.js" type="text/javascript"></script>
		<link href="heading_styles.css" type="text/css" rel="stylesheet" />
		<link href="lists_styles.css" type="text/css" rel="stylesheet" />
		<link href="page_styles.css" type="text/css" rel="stylesheet" />
		<link href="form_styles.css" type="text/css" rel="stylesheet" />
		<link href="links_styles.css" type="text/css" rel="stylesheet" />
		<link href="text_styles.css" type="text/css" rel="stylesheet" />
		<style>
		/*hide the interactions div by default*/
			#interactions-content{
				display: none;
			}
		
		.form-tabs-container{
			border: 1px solid #ccc;
			overflow: hidden;
		}
		
		/*Styling tab buttons */
		.form-tabs-container button {
			float: left;
			padding: 16px 14px;
			border: none;
			cursor: pointer;
			outline: none;
			transition: 0.3s;
			width: 50%;
			
		}
		
		.form-tabs-container > :first-child {
			border-right: 1px solid #ccc;
		}
		
		.form-tabs-container button:hover{
			background-color: #ddd;
		}
		
		.tab-content-container{
			padding: 6px 12px;
			border: 1px solid #ccc
			border-top: none;
		}
		
		.tab-content{
			animation: tabFade 1s;
		}
		
		@keyframes tabFade	{
			from {opacity: 0;}
			to {opacity: 1;}
		}
		</style>
	</head>
	
	<body onload="removeProvElememt()">
		<div id="editModal" class="modal">
			<div class="modal-content">
				
			</div>
		</div>
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
				<div class="form-content-container">
					<div class="form-tabs-container">
						<button id="loanDetails" class="tab-links" onclick="openTab(event, 'loan-details-content')">Loan Details</button>
						<button id="loanInteractions" class="tab-links" onclick="openTab(event, 'interactions-content');injectInInteractions()">Interactions</button>
					</div>
					<div class="tab-content-container">
						<div id="loan-details-content" class="tab-content">
							<form name="loanform" id="NewLoanForm" action="loandetails_handler.php" method="post" onsubmit="return validateform()">
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
									<label id="prov_label">of</label>
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
								<button type="button" id="interact">Add Interaction</button>
								</div>
								</div> 
							</form>
						</div>
						<div id="interactions-content" class="tab-content">This is interactions</div>
					</div>
				</div>
			</div>
		</div>
		<!-- End of main form section -->
		
		<script>
		/* This script is for the customer_interactionsdata external page that is inserted
			by clicking the add interaction button. */
			
			var interact_btn = document.getElementById("interact");
			var interact_box = document.getElementById("editModal");
			var modal_content = document.getElementsByClassName("modal-content")[0];
			var content = <?php echo "'<h2>Nice</h2>'"; ?>;
			var loanNo = <?php echo $_SESSION['loan_no']; ?>;
			var user = <?php echo "'".$_SESSION['userlogin']."'"; ?>;
				
			interact_btn.onclick = function(){
				interact_box.style.display = "block";
				const form = new FormData(document.getElementById("NewLoanForm"));
				const params = {"loanNo":loanNo, "user":user};
				//On display, insert content from customer_interactionsdata page
					fetch("customer_interactionsdata.php", {
						method: 'POST',
						headers: {
							'Content-Type': 'application/json'
						},
						body: JSON.stringify(params),
					}).then(function(response){
						return response.text();
					}).then(function(data){
						modal_content.innerHTML = data;
						
						//create a DOMParser to parse the response
						
						/*const parser = new DOMParser();
						const html = parser.parseFromString(data, "text/html");//convert the text using parsefromstring method
						var script = html.documentElement.querySelector('script');
						console.log(script);
						script.parentNode.removeChild(script);
						
						/*The following piece is supposed to extract the script from the fetched page
						and add it to the end of the body*/
						//document.body.appendChild(script);//(not working)
						
						
						addModalScript();
					});
					
					//console.log(JSON.stringify(params));
			}
			
			//CLicking outside modal to close
			function load(url, el){
				fetch(url).then(function(res){
					return el.innerHTML = res.text();
				});
			}
			window.onclick = function(e){
				if(e.target == interact_box){
					interact_box.style.display = "none";
					removeAddedScript();
					
				}
			}
			function addModalScript(){
				/*
					-The function is called by the add interaction button on the loan details form.
					-The function creates a script tag
					-The function inserts scripts, as innerHTML, that are used to manupilate the the modal once it's loaded; these include, 
					 selecting the Interaction date when the modal loads, adding interaction data when user clicks the ADD button,
					 editing interaction data when user clicks the edit button, closing the modal when user clicks the cancel button or outside the modal
					-The function then adds the script tag to the DOM
				*/

				var script = document.createElement("script");
				
				
				//Use ` ` to create a string template
				script.innerHTML = `
					document.getElementById('add').onclick = function(){//script for the add button begins
				
				//var intr_date_conv = new Date(document.getElementsByName("interaction_date")[0].value);
				//var intr_nxt_conv = new Date(document.getElementsByName("next_interaction")[0].value);
				
				var intr_no = document.getElementsByName("interaction_no")[0].value;
				var intr_date = document.getElementsByName("interaction_date")[0].value;//intr_date_conv.toLocaleDateString('en-GB');
				
				/*var datetrym = intr_date_conv.getMonth();
				var datetryd = intr_date_conv.getDate();
				var datetryy = intr_date_conv.getFullYear();
				var supdate = new Date(datetryy,datetryd,datetrym);*/
				
				var intr_type = document.getElementsByName("interaction_type")[0].value;
				var intr_outcm = document.getElementsByName("interaction_outcome")[0].value;
				var intr_com = document.getElementsByName("comment")[0].value;
				var intr_nxt = document.getElementsByName("next_interaction")[0].value;//intr_nxt_conv.toLocaleDateString('en-GB');
				var user = document.getElementsByName("user")[0].value;
				
				
				
				const form_values = {"intr_no":intr_no, "intr_date":intr_date, "intr_type":intr_type, "intr_outcm":intr_outcm, "intr_com":intr_com, "intr_nxt":intr_nxt, "user":user,"loanNo":loanNo};
				//const intr_form = new FormData(document.getElementById("interaction_form")); //--NOT WORKING--(empty object
				//console.log(intr_date + "<br/>" + document.getElementsByName("interaction_date")[0].value);
				//console.log(datetrym+ " "+datetryd);
				//console.log("Date: "+ datetryd);
				//console.log("Month: "+ datetrym);
				//console.log("Year: "+ datetryy);
				//console.log(supdate.toLocaleDateString('en-GB'));
						/*if(checkForm()){
							alert(checkForm());
						}*/
						fetch("addinteractiondecision.php",{
							method: "POST",
							body: JSON.stringify(form_values)
						}).then(function(response){
							return response.json();
						}).then(function(data){
							document.getElementById("response").innerHTML = data.message;
							
							if(data.status == 1){
								document.getElementById("add").setAttribute("disabled", true);
								document.getElementById("add").style.cursor = "not-allowed";
							}
						});
					}//script for the add button ends
					
				
					document.getElementById('canc').onclick = function(){ //script for the cancel button begins
						document.getElementById('editModal').style.display = 'none';
						removeAddedScript();//script for the cancel button ends
						
					}`;
				//script.innerHTML = "document.getElementById('test').onclick = function(){alert('Okay');}";
				document.body.appendChild(script);
			
			}
			
			function removeAddedScript(){
				document.body.removeChild(document.body.lastChild);
			}
			
			function checkForm(){
				var intr_no = document.getElementsByName("interaction_no")[0];
				var intr_date = document.getElementsByName("interaction_date")[0];
				var intr_type = document.getElementsByName("interaction_type")[0];
				var intr_outcm = document.getElementsByName("interaction_outcome")[0];
				var intr_com = document.getElementsByName("comment")[0];
				var intr_nxt = document.getElementsByName("next_interaction")[0];
				var user = document.getElementsByName("user")[0];
				
				var err_no = document.getElementById("errorintr_no");
				var err_date = document.getElementById("errorintr_date");
				var err_type = document.getElementById("errorintr_type");
				var err_outcm = document.getElementById("errorintr_outcm");
				var err_com	= document.getElementById("errorintr_com");
				var err_nxt = document.getElementById("errorintr_nxt");
				var err_user = document.getElementById("errorintr_user");
				
				var isFormGood = true;
				
				//alert(user.value);
				
			/*---CONSIDER CHECKING---
				This section only considers the last two sections, comment and next interaction section 
				as the detemeinants of wether the validation passes or fails */
				
				if(intr_date.value == ""){
					isFormGood = false;
					err_date.innerHTML = "Please enter interaction date";
					intr_date.focus();
				}else if(!/^\d{2}\/\d{2}\/\d{4}$/.test(intr_date.value)){
					isFormGood = false;
					err_date.innerHTML = "Please enter date in dd/mm/yyyy format";
					intr_date.focus();
				}else{
					isFormGood = true;
					err_date.innerHTML = "";
				}					
				
				if(intr_type.value == ""){
					isFormGood = false;
					err_type.innerHTML = "Please choose an interaction type";
					//intr_type.focus();
				}else{
					isFormGood = true;
					err_type.innerHTML = "";
				}
				
				if(intr_outcm.value == ""){
					isFormGood = false;
					err_outcm.innerHTML = "Please choose an interaction outcome";
					//intr_outcm.focus();
				}else{
					isFormGood = true;
					err_outcm.innerHTML = "";
				}
				
				/*if(intr_com.value == ""){
					isFormGood = false;
					err_com.innerHTML = "Please enter interaction comment";
					intr_com.focus();
				}else{
					isFormGood = true;
					err_com.innerHTML = "";
				}
				
				if(intr_nxt.value == ""){
					isFormGood = false;
					err_nxt.innerHTML = "Please enter next interaction date";
					intr_nxt.focus();
				}else if(!/^\d{2}\/\d{2}\/\d{4}$/.test(intr_nxt.value)){
					isFormGood = false;
					err_nxt.innerHTML = "Please enter date in dd/mm/yyyy format";
					intr_nxt.focus();
				}else{
					isFormGood = true;
					err_nxt.innerHTML = "";
				}*/
				
				return isFormGood;
				
			}
			
			
			function changeInteractionType(){
				/*var options = "<option value=''>Select</option><option value='Phone off test' selected > Test</option>"; //document.createElement("option");
				//options.text = "Text";
				//options.value = "test";
				document.getElementsByName("interaction_outcome")[0].innerHTML = options;*/
				switch (document.getElementsByName("interaction_type")[0].value){
					case "Phone Call":
						var classes = ["letter", "visit"];
						
						for (cls in classes){
							const opts = document.getElementsByClassName(classes[cls]);
							
							for(var i=0; i<opts.length; i++){
								opts[i].setAttribute("hidden", true);
								
							}
						}
						
						var opts_shwn = document.getElementsByClassName("phone");
						for(var j=0; j<opts_shwn.length; j++){
							opts_shwn[j].removeAttribute("hidden");
						}
						
					break;
					case "Visit":
						var classes = ["phone", "letter"];
						
						for (cls in classes){
							const opts = document.getElementsByClassName(classes[cls]);
							
							for(var i=0; i<opts.length; i++){
								opts[i].setAttribute("hidden", true);
					
							}
						}
						
						var opts_shwn = document.getElementsByClassName("visit");
						for(var j=0; j<opts_shwn.length; j++){
							opts_shwn[j].removeAttribute("hidden");
						}
						
					break;
					case "Letter":
						var classes = ["phone", "visit"];
						
						for (cls in classes){
							const opts = document.getElementsByClassName(classes[cls]);
							
							for(var i=0; i<opts.length; i++){
								opts[i].setAttribute("hidden", true);
							}
						}
						
						var opts_shwn = document.getElementsByClassName("letter");
						for(var j=0; j<opts_shwn.length; j++){
							opts_shwn[j].removeAttribute("hidden");
						}
						
					break;
				}	
				//alert("It works");
			}
			
			function injectInInteractions(){
				fetch("interactions_tabledata.php?loanNo=" + loanNo).then(function(response){
					return response.text();
				}).then(function(data){
					document.getElementById("interactions-content").innerHTML = data;
				});
			}
		</script>
		
		<script>
			var status_el = document.getElementsByName("status")[0];
			
			status_el.onchange = function(){
				if(status_el.value == "Cleared"){
					document.getElementsByName("clearedby")[0].value = user;
				}else{
					document.getElementsByName("clearedby")[0].value = "";
				}
			}
			
			var prov_value = <?php if($prov_period == ""){echo '""';}else{echo $prov_period;} ?>;
			
			function removeProvElememt(){
				if(prov_value == ""){
					document.getElementsByName("prov_period")[0].style.display = "none";
					document.getElementById("prov_label").style.display = "none";
				}
			}
		</script>
	</body>
</html>