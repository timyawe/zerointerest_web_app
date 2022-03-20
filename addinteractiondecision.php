<?php include "login_session.inc";


//Connect to database
include "dbconn.php";
$json_post_file = file_get_contents('php://input');//recieves JSON type data
$json_data = json_decode($json_post_file);
$missing_values = array();

foreach($json_data as $field => $value){
	if($value == ""){
		array_push($missing_values, $field);
	}
}

$json_response = new stdClass();
if(count($missing_values) > 0){
	$json_response->status = 0;
	$json_response->message = "Please fill all fields";
	echo json_encode($json_response);
}else{
	$intr_no = $json_data->intr_no;
	$intr_date = "'".date("Y-m-d", strtotime($json_data->intr_date))."'";
	$intr_type = "'".$json_data->intr_type ."'";
	$intr_outcm = "'".$json_data->intr_outcm ."'";
	$intr_com = "'".mysqli_real_escape_string($conn, $json_data->intr_com) ."'";
	$intr_nxt = "'".date("Y-m-d", strtotime($json_data->intr_nxt))."'";
	$user = "'".$json_data->user ."'";
	$loanNo = $json_data->loanNo;
	
	$ins_sql = mysqli_query($conn, "INSERT INTO CustomerInteractions VALUES (NULL,$intr_no, $intr_date, $intr_type, $intr_outcm, $intr_com, $intr_nxt, $user, $loanNo)");

	if($ins_sql){
		$json_response->status = 1;
		$json_response->message = "Customer Interaction added successfully";
		echo json_encode($json_response);
	}else{
		$json_response->status = 0;
		$json_response->message = mysqli_error($conn);
		echo json_encode($json_response);
	}
}

?>