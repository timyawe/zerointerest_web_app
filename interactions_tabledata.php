<?php 
//connect to database
require "dbconn.php";

$intrs_sql = mysqli_query($conn, "SELECT * FROM CustomerInteractions WHERE LoanNo=". $_REQUEST['loanNo']. " ORDER BY Interaction_No DESC");
$tablehead = <<<TABLE
		<table border="1">
			<tr>
				<th>#</th>
				<th>Date</th>
				<th>Type</th>
				<th>Outcome Type</th>
				<th>Comment</th>
				<th>Next Interaction</th>
				<th>Entered By</th>
				<th></th>
			</tr>
TABLE;

if(mysqli_num_rows($intrs_sql) > 0){
	$counter = 1;
	echo $tablehead;
	while($intrs_row = mysqli_fetch_assoc($intrs_sql)){
		echo "<tr>
				<td>$counter</td>".
				"<td>".date("d/m/Y", strtotime($intrs_row['Date_Occured']))."</td>".
				"<td>".$intrs_row['Interaction_Type']."</td>".
				"<td>".$intrs_row['Outcome_Type']."</td>".
				"<td>".$intrs_row['Outcome_Comment']."</td>".
				"<td>".date("d/m/Y", strtotime($intrs_row['Next_Interaction']))."</td>".
				"<td>".$intrs_row['Entered_By']."</td>".
				"<td>Edit</td>".
			"</tr>";
		$counter++;
	}
	
}else{
	echo $tablehead;
	echo "<tr><td colspan='8' style='text-align: center'>This loan has no interactions yet</td></tr>";
}
echo "</table>";
//echo "<button type='button' id='interact'>Add Interaction</button>";

?>