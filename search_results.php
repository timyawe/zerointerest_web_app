<?php include "login_session.inc";
if(isset($_SESSION['caption'])){
	$caption = $_SESSION['caption'];
}

if(isset($_SESSION['pymt_filt_json'])){
	$pymt_filt_json_resp = json_decode($_SESSION['pymt_filt_json']);
}
?>

<!doctype html>
<html>
	<head>
		<title>Search Results</title>
		<!--<meta name="viewport" content="width=device-width initial-scale=1.0"/>-->
		<link href="page_styles.css" type="text/css" rel="stylesheet" />
		<link href="heading_styles.css" type="text/css" rel="stylesheet" />
		<link href="links_styles.css" type="text/css" rel="stylesheet" />
		<link href="lists_styles.css" type="text/css" rel="stylesheet" />
		<link href="text_styles.css" type="text/css" rel="stylesheet" />
		<link href="table_styles.css" type="text/css" rel="stylesheet" />
		
		<style>
			.left_margin {
				margin-left: 5px;
			}
			
			.left_margin_extra {
				margin-left: 30px;
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
				
				<div id="user">
					<p class="user-welcome">Welcome <?php echo $_SESSION["userlogin"]; ?></p>
				</div>
			</div>
		<!-- End of header section -->
		
		<div id="main_content">
			<h2>Search Results</h2>
			<?php
				//connect to database
				require "dbconn.php";
				$counter = 1;
				
				/*$json_response = file_get_contents('php://input');
				$json_data = json_decode($json_response);
				$json_response = new stdClass();
				echo $json_data->cust_filter;*/
				
				//Displays customer filters
				if(isset($_SESSION['cust_filter'])){
					$cust_result = mysqli_query($conn, $_SESSION['cust_filter']);
					
					echo "<table id='this_table' border = '1' cellpadding='0' cellspacing='0' style='margin-top:0px'>
							<caption>$caption</caption>
						<tr>
							<th>#</th>
							<th>NAME</th>
							<th>CONTACT #1</th>
							<th>CONTACT #2</th>
							<!--<th>EMAIL ADDRESS</th>-->
							<th>MARITAL STATUS</th>
							<th>RESIDENCE</th>
							<th>HOME DETAILS</th>
							<!--<th>BANK NAME</th>
							<th>BANK A/C</th>-->
							<th>OCCUPATION</th>
							<!--<th>JOB/BUSINESS</th>
							<th>EMPLOYER</th>
							<th>JOB/BUSINESS LOCATION</th>-->
							<th>DATE JOINED</th>
							<th>FILES</th>
							<th></th>
							<th></th>
						</tr>";
						
					if(mysqli_num_rows($cust_result)>0){
						while($row = mysqli_fetch_assoc($cust_result)){
							$customer_no = $row['CUSTOMER_NO'];
							$customerName = $row['SUR_NAME']. " ". $row['FIRST_NAME'];
							echo	"</tr>";
							echo 	"<td hidden>".$row['CUSTOMER_NO']."</td>";
									echo	"<td>$counter</td>";
									echo	"<td class='filter_customername'>".$customerName."</td>";
									echo 	"<td>".$row['PRIMARY_CONTACT']."</td>";
									echo 	"<td>".$row['OTHER_CONTACT']."</td>";
									//echo 	"<td>".$row['EMAIL_ADDRESS']."</td>";
									echo 	"<td>".$row['MARITAL_STATUS']."</td>";
									echo 	"<td>".$row['RESIDENCE']."</td>";
									echo 	"<td>".$row['HOME_DETAILS']."</td>";
									//echo 	"<td>".$row['BANK']."</td>";
									//echo 	"<td>".$row['BANK A/C']."</td>";
									echo 	"<td>".$row['OCCUPATION_TYPE']."</td>";
									//echo 	"<td>".$row['JOB/BUSINESS_TYPE']."</td>";
									//echo 	"<td>".$row['EMPLOYER']."</td>";
									//echo 	"<td>".$row['LOCATION_OF_JOB/BUSINESS']."</td>";
									echo 	"<td>".date("d/m/Y", strtotime($row['DATE JOINED']))."</td>";
									echo 	"<td style='text-align:center'>(".$row['Attachments'].")</td>";
									echo 	"<td><a href='customerdetails_handler.php?customer_no=$customer_no'>Edit</a></td>";
									echo 	"<td><button type='button' class='tbl_btn' onclick='view_loans(this)'>Loans</button></td>";
							echo	"</tr>";
							$counter++;
						}
					}else{
						echo "<tr><td colspan='13' style='text-align:center'>No Records</td></tr>";
					}
					echo "</table>";
					//unset($_SESSION['cust_filter']);
				}/*else{
					echo "<p>Page has expired, please choose a new search criteria <a href='reports.php'>here</a></p>";
				}*/
				
				//Displays laon filters
				if(isset($_SESSION['lns_filter'])){
					$lns_result = mysqli_query($conn, $_SESSION['lns_filter']);
					
					echo "<table id='this_table' border='1' cellpadding='0' cellspacing='0' style='margin-top:0px;'>
							<caption>$caption</caption>
							<tr>
								<th>#</th>
								<!--<th>REF NO</th>-->
								<th>CUSTOMER NAME</th>
								<!--<th>LOAN_REF NO</th>-->
								<th>START</th>
								<th>PERIOD</th>
								<th>END</th>
								<th>PRINCIPAL</th>
								<th>INTEREST</th>
								<th>TOTAL</th>
								<th>PAID</td>
								<th>BALANCE</th>
								<th>STATUS</th>
								<th>CLEARED</th>
								<th>EDIT</th>
								<th>DETAILS</th>
							</tr>";
							
					if(mysqli_num_rows($lns_result)>0){
						$sum_princp= $sum_int= $sum_princp_int= $sum_paid= $sum_bal = 0;
						while ($row = mysqli_fetch_assoc($lns_result)){
							
							$principal = $row['PRINCIPAL'];
							$int = $row['INTEREST'];
							$princp_int = $row['FINAL_PRINCIPAL_&_INTEREST'];
							$paid = $row['AMOUNT_PAID'];
							$bal = $row['BALANCE'];
							$rows = mysqli_num_rows($lns_result);
							if ($row['DATE_CLEARED'] == "" ) {
								$cleareddate = "";
							} else {
								$cleareddate = date("d/m/Y", strtotime($row['DATE_CLEARED']));
							}

							echo	"<td>$counter</td>";
							//echo	"<td>".$row['LOAN_REF_NO']."</td>";
							echo	"<td>".$row['CUSTOMER_NAME']."</td>";
							echo	"<td>".date("d/m/Y", strtotime($row['START_DATE']))."</td>";
							echo	"<td>".$row['PERIOD(in_days)']." days </td>";
							echo	"<td>".date("d/m/Y", strtotime($row['FINAL_PAYMENT_DATE']))."</td>";
							echo	"<td class='loan_principal' style='text-align:right;'>".number_format($principal)."</td>";
							echo	"<td style='text-align:center;'>".number_format($int)."</td>";
							echo	"<td class='loan_principal&int' style='text-align:right;'>".number_format($princp_int)."</td>";
							echo	"<td>".number_format($paid)."</td>";
							echo	"<td>".number_format($bal)."</td>";
							echo	"<td>".$row['STATUS']."</td>";
							echo	"<td class='cleared_loan'>".$cleareddate."</td>";
							echo	"<td><a href='loandetails_handler.php?loan_no=".$row['LOAN_NO']."'>Edit</a></td>";
							echo	"<td><a href='loandetails_view.php?loan_no=".$row['LOAN_NO']."&customer_name=".$row['CUSTOMER_NAME']."'>View Details</a></td>";
							echo "</tr>";
							$counter++;
							$sum_princp += $principal;
							$sum_int += $int;
							$sum_princp_int += $princp_int;
							$sum_paid += $paid;
							$sum_bal += $bal;
						}
						
						echo "<tr id='totals_row'>
									<td>Total</td>
									<td></td>
									<td class='no_of_loans_total'>$rows</td>
									<td></td>
									<td></td>
									<td class='principal_total'>".number_format($sum_princp)."</td>
									<td>".number_format($sum_int)."</td>
									<td class='principal&int_total'>".number_format($sum_princp_int)."</td>
									<td>".number_format($sum_paid)."</td>
									<td>".number_format($sum_bal)."</td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								 </tr>";
					}else{
						echo "<tr><td colspan='14' style='text-align: center'>No Records</td></tr>";
					}
					echo "</table>";
					//unset($_SESSION['lns_filter']);
				}
					
				if(isset($_SESSION['pymt_filter'])){
					$pymt_result = mysqli_query($conn, $_SESSION['pymt_filter']);
					
					echo"<div id='pymt_filt_frm'>
							<form>
								<label>Filter Type</label><select id='type' class='left_margin'><option value=''></option><option value='Bank'>Bank</option><option value='Mobile Money'>Mobile Money</option><option value='Cash'>Cash</option></select>
								<label class='left_margin'>FIlter Confirmed</label><select class='left_margin' id='confd'><option value=''></option><option value='1'>Yes</option><option value='0'>No</option></select>
								<span class='left_margin_extra'><input type='button' id='filt_btn' value='Filter' /><input class='left_margin' type='button' id='reset_filt_btn' value='Reset' /></span>
							</form>
						</div>
						<div id='pymt_filt_tbl'>
							<table id='this_table' border='1' cellpadding='0' cellspacing='0' style='margin-top:0px;'>
							
								<caption>$caption</caption>
								<tr>
									<th>#</th>
									<th>CUSTOMER NAME</th>
									<th>LOAN NO</th>
									<th>DATE</th>
									<th>AMOUNT</th>
									<th>TYPE</th>
									<th>CONFIRMED?</th>
									<!--<th>EDIT</th>-->
								</tr>";
							
							if(mysqli_num_rows($pymt_result)>0){
								$total_paid =0;
								while ($row = mysqli_fetch_assoc($pymt_result)) {
									$paid = $row['PAID_AMOUNT'];
									
									echo "<tr>";
									echo 	"<td>$counter</td>";
									echo 	"<td class='filter_customername'>".$row['CUSTOMER_NAME']."</td>";
									echo 	"<td style='text-align:center;'>".$row['LOAN_NO']."</td>";
									echo 	"<td>".date("d/m/Y", strtotime($row['PAYMENT_DATE']))."</td>";
									echo 	"<td class='payments_total' style='text-align:right;'>".number_format($paid)."</td>";
									echo 	"<td>".$row['PAYMENT_TYPE']."</td>";
										if ($row['PAYMENT_CONFIRMED'] == 1 ) {
									echo 	"<td class='payments_confirmed'>Yes</td>";
										}else {
									echo 	"<td class='payments_confirmed'>No</td>";
										}
									/*echo "<td>Edit<</td>";*/
									echo "</tr>";	
									$counter++;
									$total_paid = $paid + $total_paid;
								}
									
								echo"<tr>
										<td colspan='2'>Total</td>
										<td></td>
										<td></td>
										<td>".number_format($total_paid)."</td>
										<td></td>
										<td></td>
									</tr>";
							}else{
								echo "<tr><td colspan='7' style='text-align:center'>No Records</td></tr>";
							}
							echo "</table>";
				echo 	"</div>";
				}
			?>
		</div>
		
		<script>
			var from_date = <?php echo "'".$pymt_filt_json_resp->from_date ."'"; ?>;
			var to_date = <?php echo "'".$pymt_filt_json_resp->to_date ."'"; ?>;
			var rec_src = <?php echo "'". $pymt_filt_json_resp->rec_src . "'"; ?>;
			var dt_scope = "range";
			
			document.getElementById("filt_btn").addEventListener('click', function(){
				var criteria = "varies";
				var type = document.getElementById("type").value;
				var confd = document.getElementById("confd").value;
				
				if(type == "" && confd == ""){
					alert("Please choose one of the filters to continue");
				}else{
					const values = {"from_date": from_date, "to_date":to_date, "rec_src":rec_src, "criteria":criteria, "dt_scope":dt_scope, "type":type, "confd":confd}
					fetch("filter_analysis.php",{
						method: "POST",
						body: JSON.stringify(values)
					}).then(function(response){
						return response.text();
					}).then(function(data){
						document.getElementById("pymt_filt_tbl").innerHTML = data;
					});
				}
			});
			
			document.getElementById("reset_filt_btn").addEventListener('click', function(){
				location.reload();
			});
		</script>
	</body>
</html>