<?php
//connect to database
include "dbconn.php";

//Select table data
$sql = "SELECT * FROM `Customers with Loans` WHERE CustomerNo =". $_REQUEST['cust_no'];

//Adding new loan decsion
$loandesc_selectsql = "SELECT LOAN_NO FROM `Loan Details Table` WHERE CustomerNo =" .$_REQUEST['cust_no']. " AND STATUS <> 'Cleared' ORDER BY LOAN_NO DESC LIMIT 1";
$loandesc_selectsqlresult = mysqli_query($conn, $loandesc_selectsql);

$cust_no = $_REQUEST['cust_no'];
$cust_name = $_REQUEST['cust_name'];
$cust_phone = $_REQUEST['cust_phone'];

//Put data in variable result
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($loandesc_selectsqlresult) == 0 ) { //means no loan hence adding the first one
	$new_loan_display_el = "<a class='link_btn' href='addloandecision.php?cust_no=$cust_no'>New Loan</a>";
}else{
	$new_loan_display_el = "<span style='background-color: red; color: white'>Please clear all loans to add a new one.</span>";
}

//create the table with heredoc
$table_header=<<<EOD
	
	<div id="table_content">
		<div style="margin:0 0 0 10px">
			<p style='margin-bottom: 0px' class='cust_lbl'>Customer Name: $cust_name,</p>
			<p style='margin: 0 0 5px 0' class='cust_lbl'>Phone Number: $cust_phone</p>
			<div id="response" style='margin: 0 0 5px 0'></div>
			<div style='margin-top: 30px'>
				<input type="text" id="cust_no" value="$cust_no" hidden />
				<button type="button" class="cust_lns_bk_btn link_btn" onclick="inject_cust_list()">Back</button>
				$new_loan_display_el
			</div>
		</div>
	<table id="this_table" border="1">
		<tr  class="fixed_header">
			<th>#</th>
			<th>LOAN_REF</th>
			<th>START DATE</th>
			<th>PERIOD</th>
			<th>END DATE</th>
			<th>PRINCIPAL</th>
			<th>INTEREST</td>
			<th>TOTAL</th>
			<th>PAID</td>
			<th>BALANCE</th>
			<th>STATUS</th>
			<th>EDIT</th>
			<th>DETAILS</th>
		</tr>
EOD;

//Check wether the query returned any data
if (mysqli_num_rows($result) > 0) {
	$counter = 1;
	$sum_princp= $sum_int= $sum_princp_int= $sum_paid= $sum_bal = 0;
	echo $table_header;
		while ($row = mysqli_fetch_assoc($result)){
			$_SESSION['loan_no'] = $row['LOAN_NO'];
			$principal = $row['PRINCIPAL'];
			$int = $row['INTEREST'];
			$princp_int = $row['FINAL_PRINCIPAL_&_INTEREST'];
			$paid = $row['AMOUNT_PAID'];
			$bal = $row['BALANCE'];
			$rows = mysqli_num_rows($result);
			if ($row['DATE_CLEARED'] == "" ) {
				$cleareddate = "";
			} else {
				$cleareddate = date("d/m/Y", strtotime($row['DATE_CLEARED']));
			}

			echo	"<td>$counter</td>";
			echo	"<td>".$row['LOAN_REF_NO']."</td>";
			echo	"<td>".date("d/m/Y", strtotime($row['START_DATE']))."</td>";
			echo	"<td>".$row['PERIOD']."</td>";
			echo	"<td>".date("d/m/Y", strtotime($row['FINAL_PAYMENT_DATE']))."</td>";
			echo	"<td class='loan_principal' style='text-align:right;'>".number_format($principal)."</td>";
			echo	"<td style='text-align:center;'>".number_format($int)."</td>";
			echo	"<td class='loan_principal&int' style='text-align:right;'>".number_format($princp_int)."</td>";
			echo	"<td>".number_format($paid)."</td>";
			echo	"<td>".number_format($bal)."</td>";
			echo	"<td>".$row['STATUS']."</td>";
			echo	"<td><a href='loandetails_handler.php?loan_no=".$row['LOAN_NO']."'>Edit</a></td>";
			echo	"<td><a href='loandetails_view.php?loan_no=".$row['LOAN_NO']."&customer_name=".$row['CUSTOMER_NAME']."'>Details</a></td>";
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
		 </tr>";
		 
} else{
	echo $table_header;
	echo "<tr><td colspan='13' style='text-align:center'>No Loans Yet</td></tr>";
}
echo 	"</table>";
echo "</div>";
?>
	
	<!-- End of content section -->
</body>

<script>
	document.getElementById("search_field").addEventListener("keyup", filterTable_name);
	document.getElementById("filter_icon").addEventListener("click", createFilterTable_amounts);
	document.getElementById("filter_amount").addEventListener("change", filterTable_amount);
	
	var t = document.getElementsByClassName("fixed_header");
	for (var y = 0; y<t.length; y++){
	t[y].addEventListener("click", clicked_row);
	}
	
</script>
</html>