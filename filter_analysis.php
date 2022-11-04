<?php include "login_session.inc"; 
//unset($_SESSION['cust_filter'], $_SESSION['lns_filter'],$_SESSION['pymt_filter'], $_SESSION['caption']);

$json_post_file = file_get_contents('php://input');//recieves JSON type data
$json_data = json_decode($json_post_file);

$json_response = new stdClass();

if(isset($_POST['rec_src']) && isset($_POST['dt_scope']) && isset($_POST['criteria'])){//checks wether data has been submitted by filter form or from link
	$data_loc_tbl = $_POST['rec_src'];
	$data_loc_scp = $_POST['dt_scope'];
	$data_loc_ctr = $_POST['criteria'];
}else if(isset($_REQUEST['rec_src']) && isset($_REQUEST['dt_scope']) && isset($_REQUEST['criteria'])){
	$data_loc_tbl = $_REQUEST['rec_src'];
	$data_loc_scp = $_REQUEST['dt_scope'];
	$data_loc_ctr = $_REQUEST['criteria'];
}else{
	$data_loc_tbl = $json_data->rec_src;
	$data_loc_scp = $json_data->dt_scope;
	$data_loc_ctr = $json_data->criteria;
}

if($data_loc_tbl == "cust-tbl"){
	unset($_SESSION['lns_filter'],$_SESSION['pymt_filter']);
	$rec_src = "`Customers_with_attachments`";
	
	if($data_loc_scp == "curr"){
		$criteria = "`DATE JOINED` = current_date()";
		$tbl_cptn = "Customers joined Today";
	}else{
		if(isset($_POST['from']) && isset($_POST['to'])){
			$from_date = date("Y-m-d", strtotime($_POST['from']));
			$to_date = date("Y-m-d", strtotime($_POST['to']));
			$criteria = "`DATE JOINED` BETWEEN '$from_date' AND '$to_date' ORDER BY `DATE JOINED` DESC";
			
			if(strtotime($_POST['from']) == strtotime($_POST['to'])){
				$tbl_cptn = "Customers joined Today";
			}else{
				$tbl_cptn = "Customers joined Between ". date("d/m/Y", strtotime($_POST['from'])) . " And ". date("d/m/Y", strtotime($_POST['to']));
			}
		}
	}
	
	$sql_placeholder = "SELECT * FROM $rec_src WHERE $criteria";
	//$dataobj->name = "John";
	//$dataobj->age = 30;
	
	//$json_data = json_encode($dataobj);
	$_SESSION['caption'] = $tbl_cptn;
	$_SESSION['cust_filter'] = $sql_placeholder;
	header("Location: search_results.php");
}

if($data_loc_tbl == "lns-tbl"){
	unset($_SESSION['cust_filter'],$_SESSION['pymt_filter']);
	$rec_src = "`Customers with Loans`";
	
	if($data_loc_scp == "curr"){
		switch($data_loc_ctr){
			case "tdy":
				$criteria = "`START_DATE` = current_date()";
				$tbl_cptn = "Loans Today";
			break;
			
			case "due-tdy":
				$criteria = "`FINAL_PAYMENT_DATE` = current_date()";
				$tbl_cptn = "Loans due Today";
			break;
			
			case "clrd-tdy":
				$criteria = "`DATE_CLEARED` = current_date()";
				$tbl_cptn = "Loans Cleared Today";
			break;

			case "ong":
				$criteria = "STATUS = 'On-going' ORDER BY FINAL_PAYMENT_DATE ASC";
				$tbl_cptn = "On-going Loans";
			break;
		}
	}else{
		if(isset($_POST['from']) && isset($_POST['to'])){
			$from_date = date("Y-m-d", strtotime($_POST['from']));
			$to_date = date("Y-m-d", strtotime($_POST['to']));
			
			switch($data_loc_ctr){
				case "all":
					$criteria = "`START_DATE` BETWEEN '$from_date' AND '$to_date' ORDER BY START_DATE DESC";
					
					if(strtotime($_POST['from']) == strtotime($_POST['to'])){
						$tbl_cptn = "Loans Today";
					}else{
						$tbl_cptn = "Loans From ". date("d/m/Y", strtotime($_POST['from'])) . " To ". date("d/m/Y", strtotime($_POST['to']));
					}
				break;
				
				case "due":
					$criteria = "`FINAL_PAYMENT_DATE` BETWEEN '$from_date' AND '$to_date' ORDER BY FINAL_PAYMENT_DATE DESC";
					
					if(strtotime($_POST['from']) == strtotime($_POST['to'])){
						$tbl_cptn = "Loans due Today";
					}else{
						$tbl_cptn = "Loans due From ". date("d/m/Y", strtotime($_POST['from'])) . " To ". date("d/m/Y", strtotime($_POST['to']));
					}
				break;
				
				case "clrd":
					$criteria = "`DATE_CLEARED` BETWEEN '$from_date' AND '$to_date' ORDER BY DATE_CLEARED DESC";
					
					if(strtotime($_POST['from']) == strtotime($_POST['to'])){
						$tbl_cptn = "Loans Cleared Today";
					}else{
						$tbl_cptn = "Loans Cleared Between ". date("d/m/Y", strtotime($_POST['from'])) . " And ". date("d/m/Y", strtotime($_POST['to']));
					}
				break;
				
				case "default":
					$criteria = "STATUS = 'Defaulting' AND `FINAL_PAYMENT_DATE` BETWEEN '$from_date' AND '$to_date' ORDER BY FINAL_PAYMENT_DATE DESC";
					
					$tbl_cptn = "Loans In Default Between ". date("d/m/Y", strtotime($_POST['from'])) . " And ". date("d/m/Y", strtotime($_POST['to']));
				break;
			} 
		}
	}
	
	$sql_placeholder = "SELECT * FROM $rec_src WHERE $criteria";
	
	$_SESSION['lns_filter'] = $sql_placeholder;
	$_SESSION['caption'] = $tbl_cptn;
	header("Location: search_results.php");
}

if($data_loc_tbl == "pymt-tbl"){
	unset($_SESSION['cust_filter'], $_SESSION['lns_filter']);
	$rec_src = "`Payments with Customers`";
	
	if($data_loc_ctr == "pymt-date"){
		if(isset($_POST['from']) && isset($_POST['to'])){
			$from_date = date("Y-m-d", strtotime($_POST['from']));
			$to_date = date("Y-m-d", strtotime($_POST['to']));
			
			$criteria = "`PAYMENT_DATE` BETWEEN '$from_date' AND '$to_date' ORDER BY PAYMENT_DATE DESC";
			
			if(strtotime($_POST['from']) == strtotime($_POST['to'])){
				$tbl_cptn = "Payments Today";
			}else{
				$tbl_cptn = "Payments From ". date("d/m/Y", strtotime($_POST['from'])) . " To ". date("d/m/Y", strtotime($_POST['to']));
			}
		}
		
		$sql_placeholder = "SELECT * FROM $rec_src WHERE $criteria";
		$pymt_filt_obj->from_date = $from_date;
		$pymt_filt_obj->to_date = $to_date;
		$pymt_filt_obj->rec_src = "pymt-tbl";
		
		$pymt_filt_json = json_encode($pymt_filt_obj);
		
		$_SESSION['pymt_filter'] = $sql_placeholder;
		$_SESSION['caption'] = $tbl_cptn;
		$_SESSION['pymt_filt_json'] = $pymt_filt_json;
		header("Location: search_results.php");
	}else{
		
		$from_date = $json_data->from_date;
		$to_date = $json_data->to_date;
		$type = $json_data->type;
		$confd = $json_data->confd;
		
		if($type != "" && $confd != ""){
			$criteria = "`PAYMENT_DATE` BETWEEN '$from_date' AND '$to_date' AND PAYMENT_TYPE = '$type' AND PAYMENT_CONFIRMED = $confd ORDER BY PAYMENT_DATE DESC";
			if($confd == 1){
				$filt_caption = "Confirmed $type Payments Between $from_date And $to_date";
			}else{
				$filt_caption = "Unconfirmed $type Payments Between $from_date And $to_date";
			}
			
		}elseif($type != "" && $confd == ""){
			$criteria = "`PAYMENT_DATE` BETWEEN '$from_date' AND '$to_date' AND PAYMENT_TYPE = '$type' ORDER BY PAYMENT_DATE DESC";
			$filt_caption = "$type Payments Between ". date("d/m/Y", strtotime($from_date)). " And ". date("d/m/Y", strtotime($from_date));
		}else{
			$criteria = "`PAYMENT_DATE` BETWEEN '$from_date' AND '$to_date' AND PAYMENT_CONFIRMED = $confd ORDER BY PAYMENT_DATE DESC";
			if($confd == 1){
				$filt_caption = "Confirmed Payments Between ". date("d/m/Y", strtotime($from_date)). " And ". date("d/m/Y", strtotime($from_date));
			}else{
				$filt_caption = "Unconfirmed Payments Between ". date("d/m/Y", strtotime($from_date)). " And ". date("d/m/Y", strtotime($from_date));
			}
		}
		
		//connect to database
		require "dbconn.php";
		$counter = 1;
		
		$pymt_result = mysqli_query($conn, "SELECT * FROM $rec_src WHERE $criteria");
		
		echo "<table id='this_table' border='1' cellpadding='0' cellspacing='0' style='margin-top:0px;'>
				<caption>$filt_caption</caption>
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
	}
	
}
?>