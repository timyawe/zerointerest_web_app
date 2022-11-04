<?php 
//error_reporting(E_ALL & ~E_NOTICE);

//connect to database
include "dbconn.php";
$customers = mysqli_query($conn, "SELECT * FROM `Customers_with_attachments` ORDER BY `DATE JOINED` DESC");

?>
<!doctype html>
<html>
	<head>
	<title>Customers Page</title>
	<!--<meta name="viewport" content="width=device-width initial-scale=1.0"/>-->
	<script src="assets/js/jquery-3.6.0.min.js"></script>
	<script src="table_filters.js" type="text/javascript"></script>
	<link href="heading_styles.css" type="text/css" rel="stylesheet" />
	<link href="lists_styles.css" type="text/css" rel="stylesheet" />
	<link href="page_styles.css" type="text/css" rel="stylesheet" />
	<link href="links_styles.css" type="text/css" rel="stylesheet" />
	<link href="table_styles.css" type="text/css" rel="stylesheet" />
	<link href="text_styles.css" type="text/css" rel="stylesheet" />
	
	<style>
		#banner>p{
			font-weight: bold;
			font-size: 20pt;
			letter-spacing: 0.05em;
			
		}
		
		.cust_lbl{
			font-weight: bold;
			font-family: Calibri;
			letter-spacing: 0.03em;
			font-size: 13pt;
		}
	</style>
	</head>
		<body>
			<!--Begining of header section -->
				<div id="header"> 
					<h1>ZERO INTEREST FINANCE LIMITED</h1>
					<div id="nav">
					<a class="nav-link" href="main_page.php">Home</a>
					<a class="active-nav-link" href="#">Customers</a>
					<a href="reports.php">Reports</a>
					<a href="">Account</a>
					<!--<a href="loans_page.html">Loans Page</a>-->
					</div>
				</div>
			<!-- End of header section -->
			
			<!-- Begining of sidebar navigation -->
				<!--<div id="sidebarNav">
				<ul class="side-bar">
					<li class="side-list"><a href="customer_details.php">Add Customer</a></li>
					<li class="side-list"><a href="viewcustomers.php">View Customers List</a></li>
					<li class="side-list"><a href="customer_report.php">Customer Report</a></li>
				</ul>
				</div>-->
			<!-- End of sidebar navigation -->
			
			<!--Begining of main content-->
				<div id="main_content">
					<div id="banner" style="margin: 1.5% 0 5% 0.5%">
						<a class="link_btn" href="customerdetails_handler.php?id=new">Add Customer</a>
					</div>
					<div id="content_container">
						<?php
							if (isset($_SESSION['add_customer'])) {
								echo "<p class='alert-response-success'>Customer Added</p>";
								unset($_SESSION['add_customer']);
							}
						
							if(mysqli_num_rows($customers)>0){
								$counter = 1;
								echo "<table id='this_table' border = '1' cellpadding='0' cellspacing='0' style='margin-top:0px'>
										<caption>Customer Details List<input type='text' id='search_field' placeholder='Search by name...' style='float:right'></caption>
										<tr>
											<th>#</th>
											<th>CUSTOMER NAME</th>
											<th>CONTACT #1</th>
											<th>CONTACT #2</th>
											
											<th>MARITAL STATUS</th>
											<th>RESIDENCE</th>
											<th>HOME DETAILS</th>
											<th>OCCUPATION</th>
											<!--<th>BANK NAME</th>
											<th>BANK A/C</th>
											
											<th>JOB/BUSINESS</th>
											<th>EMPLOYER</th>
											<th>JOB/BUSINESS LOCATION</th>-->
											<th>DATE JOINED</th>
											<th>FILES</th>
											<th></th>
											<th></th>
										</tr>";
								
								while($row = mysqli_fetch_assoc($customers)){
					
									$customer_no = $row['CUSTOMER_NO'];
									$customerName = $row['SUR_NAME']. " ". $row['FIRST_NAME'];
									
									echo "<tr title='$customerName'>";
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
									echo "</tr>";
									$counter++;
								}
								echo "</table>";
							}
						?>
						<p>Other things too</p>
					</div>
				</div>
			<!--End of main content-->
			
			<script>
				document.getElementById("search_field").addEventListener('keyup', filterTable_name);
				
				function view_loans(btn){ 
					var _row = null;
					_row = $(btn).parents("tr");
					var col = _row.children("td");
					var cust_no = $(col[0]).text();
					var cust_name = $(col[2]).text();
					var cust_phone = $(col[3]).text();
					
					var values = {'cust_no':cust_no, "cust_name":cust_name, "cust_phone":cust_phone};
					
					fetch("customer_loans.php?cust_no=" + cust_no + "&cust_name=" + cust_name + "&cust_phone=" + cust_phone , {
						method: "POST",
						//body: JSON.stringify(values)
					}).then(function(response){
						return response.text();
					}).then(function(data){
						document.getElementById("content_container").innerHTML = data;
						//document.getElementsByClassName("link_btn")[0].style.display = "none";
						document.getElementById("banner").innerHTML = "<p>Customer Loans</p>";
					});
					//$("#content_container").load('customer_loans.php', {'cust_no':cust_no, "cust_name":cust_name, "cust_phone":cust_phone});
					
				}
				
				function inject_cust_list(){
					location.reload();
				}
				/*var nav_link = document.getElementsByClassName("nav-link");
				for(i=0; i<nav_link.length; i++){
					nav_link[i].addEventListener('click', function() {
						//nav_link.setAttribute("class", 'active-nav-link');
						//alert("Nice");
						//nav_link.classList.add('active-nav-link');
						var curr = document.getElementsByClassName("active-nav-link");
						if(curr.length >0){
							curr[0].className = curr[0].className.replace(" active-nav-link", "");
						}
						this.className += " active-nav-link";
					});
				}*/
			</script>
		</body>
</html>