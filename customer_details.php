<?php include "login_session.inc"; ?>
<?php
			//connect to database
			require "dbconn.php";
			//if ($_REQUEST['customer_no'] != "" ) {
				//$_SESSION['customer_no'] = $_REQUEST['customer_no'];
			//}
			if (!isset($_SESSION['customer_no'])) {
				$datejoined = date("d-m-Y");
				$customerNo = $refNo = $surName = $firstName = $otherName = $marrystatus = "";
				$cont1 = $cont2 = $email = $fbName = $resd = $hmdetails = $bankname  = "";
				$bank_ac = $jobdetails = $jobtype = $employer = $jobloc = "";
			} else {
				$selectsql = "SELECT * FROM `Customer Details Table` WHERE CUSTOMER_NO =" . $_SESSION['customer_no'];
				$selectresult = mysqli_query($conn, $selectsql);
				
				if (mysqli_num_rows($selectresult) > 0 ) {
					$customer_row = mysqli_fetch_assoc($selectresult);
					//$d = strtotime($customer_row['DATE JOINED']);
					$datejoined = date("d-m-Y",strtotime($customer_row['DATE JOINED']));
					$refNo = $customer_row['REF_NO'];
					$surName = $customer_row['SUR_NAME'];
					$firstName = $customer_row['FIRST_NAME'];
					$otherName = $customer_row['OTHER_NAME'];
					$_SESSION['customername'] = $customer_row['SUR_NAME']. " ". $customer_row['FIRST_NAME'];
					$_SESSION['customernamefolder'] = $customer_row['SUR_NAME']. "_". $customer_row['FIRST_NAME'];
					$marrystatus = $customer_row['MARITAL_STATUS'];
					$cont1 = $customer_row['PRIMARY_CONTACT'];
					$cont2 = $customer_row['OTHER_CONTACT'];
					$email = $customer_row['EMAIL_ADDRESS'];
					$fbName = $customer_row['FACEBOOK_A/C'];
					$resd = $customer_row['RESIDENCE'];
					$hmdetails = $customer_row['HOME_DETAILS'];
					$bankname  = $customer_row['BANK'];
					$bank_ac = $customer_row['BANK A/C'];
					$jobdetails = $customer_row['OCCUPATION_TYPE'];
					$jobtype = $customer_row['JOB/BUSINESS_TYPE'];
					$employer = $customer_row['EMPLOYER'];
					$jobloc = $customer_row['LOCATION_OF_JOB/BUSINESS'];
					//$image = base64_decode($customer_row['DOCUMENTS']);
				}
			}
?>
<html>
	<head>
		<?php if (isset($_SESSION['customername'])) {
			echo "<title>". $_SESSION['customername']. "</title>"; 
			unset($_SESSION['customername']);
		} else {
			echo "<title>Customer Details</title>";
		} ?>
		<script src="new_customer.js" type="text/javascript"></script>
		<link href="heading_styles.css" type="text/css" rel="stylesheet" />
		<link href="lists_styles.css" type="text/css" rel="stylesheet" />
		<link href="page_styles.css" type="text/css" rel="stylesheet" />
		<link href="form_styles.css" type="text/css" rel="stylesheet" />
		<link href="links_styles.css" type="text/css" rel="stylesheet" />
		<link href="table_styles.css" type="text/css" rel="stylesheet" />
		<link href="text_styles.css" type="text/css" rel="stylesheet" />
		<style>
			tr:hover {
				color: initial;
			}
			
			.documents_link:hover {
				background-color: none;
			}
			
			.del:link{
				border: 1px solid gray;
				padding: 2px 6px 2px 6px;
				border-radius: 3px;
				text-decoration: none;
				color: black;
			}
		</style>
	</head>
		<body>
		
			<!-- Begining of header section -->
				<div id="header"> 
					<h1>ZERO INTEREST FINANCE LIMITED</h1>
					<div id="nav">
					<a href="main_page.php">Home</a>
					<a href="customers_page.php">Customers</a>
					<a href="reports.php">Reports</a>
					<a href="">Account</a>
					<!--<a href="loans_page.html">Loans Page</a>-->
					</div>
				</div>
			<!-- End of header section -->
			
			<!-- Begining of content section -->
			
				<div class="main_content">
					<!-- Begining of main form section -->
						<div id="formSection" class="container">
						<h2 class="form_heading">Customer Details</h2>
						<?php
								/*if (isset($_SESSION['add_customer'])) {
									echo "<span class='main_content' class='alert-response-success'>Customer Added</span>";
									unset($_SESSION['add_customer']);
								}*/
								
								if (isset($_SESSION['edit_customer'])) {
									echo "<span class='alert-response-success'>Customer Details Edited</span><br/>";
									unset($_SESSION['edit_customer']);
								}
								
								if (isset($_SESSION['noedit_customer'])) {
									echo "<span class='main_content' class='alert-response-failure'> No field was edited</span>";
									unset($_SESSION['noedit_customer']);
								}
								
								if (isset($_SESSION['add_image'])) {
									echo "<span class='alert-response-success'>Image Added</span>";
									unset($_SESSION['add_image']);
								}
								
								if (isset($_SESSION['noadd_image'])) {
									echo $_SESSION['noadd_image'];
									unset($_SESSION['noadd_image']);
								}
								
								if (isset($_SESSION['rejimgs'])) {
									echo "<span class='alert-response-error'>Alert: The following files were not uploaded because they are not images. Please 
									select only images<br/>";
									for($x=0; $x<count($_SESSION['rejimgs']); $x++){
										echo "- ".$_SESSION['rejimgs'][$x];
										echo "<br/>";
									//print_r( $_SESSION['noadd_image']);
									}
									unset($_SESSION['rejimgs']);
								}
								
								if (isset($_SESSION['rejdocs'])) {
									echo "<span class='alert-response-error'>Alert: The following files were not uploaded because they are not documents. Please 
									select only documents e.g (pdf, word or excel) files<br/>";
									for($x=0; $x<count($_SESSION['rejdocs']); $x++){
										echo "- ".$_SESSION['rejdocs'][$x];
										echo "<br/>";
									//print_r( $_SESSION['noadd_image']);
									}
									unset($_SESSION['rejdocs']);
								}
								
								if (isset($_SESSION['extfiles'])) {
									echo "<span class='alert-response-error'>Alert: The following files were not uploaded because they already exist<br/>";
									for($x=0; $x<count($_SESSION['extfiles']); $x++){
										echo "- ".$_SESSION['extfiles'][$x];
										echo "<br/>";
									//print_r( $_SESSION['noadd_image']);
									}
									unset($_SESSION['extfiles']);
								}
								
								if (isset($_SESSION['addedimgs'])) {
									echo $_SESSION['addedimgs'];
									unset($_SESSION['addedimgs']);
								}
								
								if (isset($_SESSION['add_document'])) {
									echo "<span class='alert-response-success'>Document Added</span>";
									unset($_SESSION['add_document']);
								}
								
								
								if (isset($_SESSION['noadd_document'])) {
									echo $_SESSION['noadd_document'];
									unset($_SESSION['noadd_document']);
								}
								
								if (isset($_SESSION['delete_file'])) {
									echo $_SESSION['delete_file'];
									unset($_SESSION['delete_file']);
								}
								
								if (isset($_SESSION['nodelete_file'])) {
									echo $_SESSION['nodelete_file'];
									unset($_SESSION['nodelete_file']);
								}
								?>
							<form name="custfrm" id="NewCustomerForm" action="customerdetails_handler.php" method="post" enctype="multipart/form-data" multiple="multiple"
								onsubmit="return validateform()" >
								<div id="GeneralTab">
									
									<div class="row">
									<div class="col-25" for="dj"><label>Date Joined:</label></div>
									<div class="col-75"><input class="main-form" type="text" value="<?php echo $datejoined; ?>" name="datejoined" /><!--required="required"--> 
									<br/><span class="form-error" id="errorDate"></span></div>
									</div>
									
									<div class="row">
									<div class="col-25"><label>Ref No:</label></div>
									<div class="col-75"><input class="main-form" type="text" value="<?php echo $refNo; ?>" name="refNo"/>
									<br/><span class="form-error" id="errorRefNo"></span></div>
									</div>
									
									<div class="row">
									<div class="col-25"><label>Sur Name:</label></div>
									<div class="col-75"><input class="main-form" type="text" value="<?php echo $surName; ?>" required="required" name="surname"/>
									<br/><span class="form-error" id="error_sName"></span></div>
									</div>
									
									<div class="row">
									<div class="col-25"><label>First Name:</label></div>
									<div class="col-75"><input class="main-form" type="text" value="<?php echo $firstName; ?>"  name="firstname"/>
									<br/><span class="form-error" id="error_fName"></span></div>
									</div>
									
									<div class="row">
									<div class="col-25"><label>Other Name:</label></div>
									<div class="col-75"><input class="main-form" type="text" value="<?php echo $otherName; ?>" name="othername"/>
									<br/><span class="form-error" id="error_oName"></span></div>
									</div>
									
									<div class="row">
									<div class="col-25"><label>Marital Status:</label></div>
									<div class="col-75"><select class="main-form" name="MarriageOptions">
										<option value="Married" <?php if($marrystatus == "Married") { echo "selected";}?>>Married</option>
										<option value="Single" <?php if($marrystatus == "Single") { echo "selected";}?> >Single</option>
										<option value="Engaged" <?php if($marrystatus == "Engaged") { echo "selected";}?>>Engaged</option>
										<option value="Widowed" <?php if($marrystatus == "Widowed") { echo "selected";}?>>Widowed</option>
									</select></div>
									</div>
									
								</div>
								
								<div id="ContactDetailsTab">
									<div class="row">
									<div class="col-25"><label>Primary Contact:</label></div>
									<div class="col-75"><input class="main-form" type="text" value="<?php echo $cont1; ?>" required="required" name="pricontact" onblur="checkPhone1()"/>
									<br/><span class="form-error" id="errorCont1"></span></div>
									</div>
									
									<div class="row">
									<div class="col-25"><label>Other Contact:</label></div>
									<div class="col-75"><input class="main-form" type="text" value="<?php echo $cont2; ?>" name="othcontact" onblur="checkPhone2()"/>
									<br/><span class="form-error" id="errorCont2"></span></div>
									</div>
									
									<div class="row">
									<div class="col-25"><label>Email:</label></div>
									<div class="col-75"><input class="main-form" type="email" value="<?php echo $email; ?>" name="emailaddr" onblur="checkEmail()"/>
									<br/><span class="form-error" id="errorEmail"></span></div>
									</div>
									
									<div class="row">
									<div class="col-25"><label>Facebook Name:</label></div>
									<div class="col-75"><input class="main-form" value="<?php echo $fbName; ?>" type="text" name="fb_ac"/></div>
									</div>
								</div>
								
								<div id="LocationTab">
									<div class="row">
									<div class="col-25"><label>Residence:</label></div>
									<div class="col-75"><input class="main-form" type="text" value="<?php echo $resd; ?>" required="required" name="resd"/><br/>
									<span class="form-error" id="errorResd"></span></div>
									</div>
									
									<div class="row">
									<div class="col-25"><label>Home Details:</label></div>
									<div class="col-75"><select class="main-form" name="HomeDetails">
										
										<option value="Home Owner" <?php if($hmdetails == "Home Owner") { echo "selected";}?>>Home Owner</option>
										<option value="Tenant" <?php if($hmdetails == "Tenant") { echo "selected";}?>>Tenant</option>
										<option value="Other" <?php if($hmdetails == "Other") { echo "selected";}?>>Other</option>
									</select><br/><span class="form-error" id="errorHome"></span></div>
									</div>
								</div>
								
								<div id="EmploymentDetailsTab">
									<div class="row">
									<div class="col-25"><label>Bank:</label></div>
									<div class="col-75"><input class="main-form" value="<?php echo $bankname; ?>" type="text" name="bankname"/></div>
									</div>
									
									<div class="row">
									<div class="col-25"><label>Bank A/C:</label></div>
									<div class="col-75"><input class="main-form" value="<?php echo $bank_ac; ?>" type="text" name="bank_ac" onblur="checkBankAc()"/><br/>
									<span class="form-error" id="errorBankA/c"></span>
									</div>
									
									<div class="row">
									<div class="col-25"><label>Occupation Type</label></div>
									<div class="col-75"><select name="JobDetails" multiple="multiple" >
										<option value="Employed" <?php if (stristr($jobdetails,"Employed") == "Employed"){echo "selected";} ?>>Employed</option>
										<option value="Business" <?php if (stristr($jobdetails,"Business") == "Business"){echo "selected";} ?>>Business</option>
										<option value="Proffessional" <?php if (stristr($jobdetails,"Proffessional") == "Proffessional"){echo "selected";} ?>>Proffessional</option>
									</select><br/><span>Hold down the CTRL key to choose more than one option</span><br/><span id="errorOcctype"></span></div>
									</div>
									
									<div class="row">
									<div class="col-25"><label>Job/Business Type:</label></div>
									<div class="col-75"><input class="main-form" type="text" value="<?php echo $jobtype; ?>" name="jobtype"/><br/>
									<span class="form-error" id="errorJobtype"></span></div>
									</div>
									
									<div class="row">
									<div class="col-25"><label>Employer:</label></div>
									<div class="col-75"><input class="main-form" type="text" value="<?php echo $employer; ?>" name="employer"/><br/>
									<span class="form-error" id="errorEmp"></span></div>
									</div>
									
									<div class="row">
									<div class="col-25"><label>Job/Business Location:</label></div>
									<div class="col-75"><input class="main-form" type="text" value="<?php echo $jobloc; ?>" name="job_loc"/>
									<br/><span class="form-error" id="errorJobloc"></span></div>
									</div>
								</div>
								
								
								<!--<div class="row">
									<div class="col-25"><label>Photos:</label></div>
									<div class="col-75"><input type="file" name="uploadedfile"/>
									<input type="submit" name="image" value="Add Image" /></div>
									</div>	
								
								<div class="row">
									<div class="col-25"><label>Documents:</label></div>
									<div class="col-75"><input type="file" name="uploadedfile"/>
									<input type="submit" name="document" value="Add Documents" /></div>
									</div>-->
								
								<div class="row" id="SubmitButton">
									<input type="submit" id="addbtn" value="Add Customer" name="add" <?php if (isset($_SESSION['customer_no'])) { echo "disabled"; } ?> />
									<input type="submit" id="editbtn" value="Save Changes" name="edit" <?php if (!isset($_SESSION['customer_no'])) { echo "disabled"; } ?> />
									<?php //if (isset($_SESSION['customer_no'])) { echo "<a class='form-link' href='addloandecision.php?customer_no={$_SESSION['customer_no']}'>Add Loan</a>"; } ?>
								</div>
								</div>
							</form>
						<!--	<a href="documents/chapter06-120827115400-phpapp01.pptx">pdf</a>
							<?php 
							if(isset($_SESSION['customer_no'])){
								$imagesql = "SELECT * FROM ImagesInfo WHERE CustomerNo = " . $_SESSION['customer_no'];
								$imagesqlresult = mysqli_query($conn, $imagesql);
								if (mysqli_num_rows($imagesqlresult)>0) {
									$imagepaths = array();
									while ($image_row = mysqli_fetch_assoc($imagesqlresult)){
									echo "<img src='images/".$image_row['Image_name'] . $image_row['Image_ID'] . $image_row['Image_type']. "' />";
									}
									//print_r( $imagepaths);
								} else {
									echo mysqli_error($conn);
								}
							}
							//foreach($imagepaths as $htmlpath) {
							//echo "<img src='images/$htmlpath' />";
							//}
							?>
						</div>
					<!-- End of main form section -->
				</div>
			</div>
			<!-- End of content section -->
			
			<!-- Begining of side section -->
			
			<div id="side_content">
				<div class="table">
					<table border="1" class="tab">
						<tr><th colspan="2">Photos</th></tr>
						<?php 
							if (isset($_SESSION['customer_no'])) {
								$imagesql = "SELECT * FROM ImagesInfo WHERE CustomerNo = " . $_SESSION['customer_no'];
								$imagesqlresult = mysqli_query($conn, $imagesql);
								if (mysqli_num_rows($imagesqlresult)>0) {
									echo "<tr><th>File Name</th><th>Delete</th></tr>";
									$imagepaths = array();
									while ($image_row = mysqli_fetch_assoc($imagesqlresult)){
										echo "<tr><td style='white-space: normal;'>".$image_row['Image_name']/* . $image_row['Image_ID'] */. $image_row['Image_type']. "</td><td style='text-align: center;'><a class='del' href='deletefile.php?image_id=".$image_row['Image_ID']."'>&times;</a></td></tr>";
									}
									echo "<tr><td colspan='2'><a href='customerphotos.php?customer_no={$_SESSION['customer_no']}'>View Photos</a></td></tr>";
								} else {
									echo "<tr><td colspan='2' style='white-space: normal;'>There are no photos added for this Customer</td></tr>";
								}
							}
						?>
			
						<tr>
							<td colspan="2" style="white-space: normal;">
								<form action="customerdetails_handler.php" method="post" enctype="multipart/form-data" multiple="multiple">
									<div><input type="text" name="custno_img" value="<?php if(isset($_SESSION['customer_no'])){echo $_SESSION['customer_no'];}?>" hidden />
									<input multiple type="file" name="uploadedfile[]" />
									<input type="submit" name="image" value="Add Image" 
									<?php 
										if (!isset($_SESSION['customer_no'])) { 
											echo "disabled "; 
											echo "title='First add customer to add images'";
										} else {
												echo "title='Click browse to select a photo to continue'";
											}
										?>/></div>
								</form>
							</td>
						</tr>
					</table>
				</div>
				
				<div class="table">
					<table border="1">
						<tr><th colspan="2">Documents</th></tr>
						<?php 
							if (isset($_SESSION['customer_no'])) {
								$documentsql = "SELECT * FROM DocumentsInfo WHERE CustomerNo = " . $_SESSION['customer_no'];
								$documentsqlresult = mysqli_query($conn, $documentsql);
								
								if (mysqli_num_rows($documentsqlresult)>0) {
									echo "<tr><th>File Name</th><th>Delete</th></tr>";
									while ($document_row = mysqli_fetch_assoc($documentsqlresult)){
										if (file_exists("./customerfiles/documents/{$_SESSION['customernamefolder']}/".$document_row['Document_name'] . $document_row['Document_ID'] . $document_row['Document_type'])){
										echo "<tr><td style='white-space: normal;'><a class='documents_link' style='color: blue; padding:0; border:none' href=".'"'."./customerfiles/documents/{$_SESSION['customernamefolder']}/".
										$document_row['Document_name'] . $document_row['Document_ID'] . $document_row['Document_type'].'"'.
										" target='_blank'>".
										$document_row['Document_name'] /*. $document_row['Document_ID']*/ . $document_row['Document_type']."</a></td><td style='text-align: center;'><a class='del' href='deletefile.php?document_id=".$document_row['Document_ID']."'>&times;</a></td></tr>";
										}
										if (!file_exists("./customerfiles/documents/{$_SESSION['customernamefolder']}/".$document_row['Document_name'] . $document_row['Document_ID'] . $document_row['Document_type'])){
											echo "<tr><td style='color: red;'>There's a missing file!</td><td style='color: red;'><a href='#'>&times;</a></td></tr>";
										}
									}
									
									
								} else {
									echo "<tr><td colspan='2' style='white-space: normal;'>There are no documents added for this Customer</td></tr>";
								}
							}
						?>
						
						<tr>
							<td colspan="2" style="white-space: normal;">
								<form action="customerdetails_handler.php" method="post" enctype="multipart/form-data" multiple="multiple">
									<input type="text" name="custno_doc" value="<?php if(isset($_SESSION['customer_no'])){echo $_SESSION['customer_no'];}?>" hidden />
									<input multiple type="file" name="uploadeddocument[]"/>
									<input type="submit" name="document" value="Add Documents" 
										<?php if (!isset($_SESSION['customer_no'])) { 
												echo "disabled "; 
												echo "title='First add customer to add documents'";
											} else {
												echo "title='Click browse to select a file to continue'";
											}
										?>/>
								</form>
							</td>
						</tr>
					</table>
				</div>
			</div>
			<!-- End of side section -->
			<?php include "footer.inc"; ?>

		</body>
</html>