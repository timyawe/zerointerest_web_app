<?php 
//connect to database
require "dbconn.php";

$intrs_sql = mysqli_query($conn, "SELECT * FROM CustomerInteractions WHERE LoanNo=". $_REQUEST['loanNo']. " ORDER BY Interaction_No DESC");

if(mysqli_num_rows($intrs_sql) > 0){
	
	echo <<<TABLE
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
	while($intrs_row = mysqli_fetch_assoc($intrs_sql)){
		echo "<tr>
				<td>".$intrs_row['Interaction_No']."</td>".
				"<td>".date("d/m/Y", strtotime($intrs_row['Date_Occured']))."</td>".
				"<td>".$intrs_row['Interaction_Type']."</td>".
				"<td>".$intrs_row['Outcome_Type']."</td>".
				"<td>".$intrs_row['Outcome_Comment']."</td>".
				"<td>".date("d/m/Y", strtotime($intrs_row['Next_Interaction']))."</td>".
				"<td>".$intrs_row['Entered_By']."</td>".
				"<td>Edit</td>".
			"</tr>";
	}
	
	echo "</table>";
	
}else{
	echo "This loan has no interactions yet";
}


?>