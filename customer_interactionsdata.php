<?php 
//connect to database
require "dbconn.php";

if(isset($_REQUEST['intID'])){
	$int_row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM CustomerInteractions WHERE ID =".$_REQUEST['intID']));
	if($int_row){
		$intr_no = $int_row['Interaction_No'];
		$intr_date = date("d/m/Y", strtotime($int_row['Interaction_Date']));
		$intr_type = $int_row['Interaction_Type'];
		$intr_outcm = $int_row['Interaction_Outcome'];
		$intr_com = $int_row['Outcome_Comment'];
		$intr_nxt = date("d/m/Y", strtotime($int_row['Next_Interaction']));
		$user = $int_row['Entered_By'];
	}
	}else{
		$json_post_file = file_get_contents('php://input');//recieves JSON type data
		$json_data = json_decode($json_post_file);
		$loanNo = $json_data->loanNo;
		$user = $json_data->user;
		$int_row_num = mysqli_query($conn, "SELECT * FROM CustomerInteractions WHERE LoanNo = $loanNo");
		
		
		if(mysqli_num_rows($int_row_num) >= 1){
			$intr_no = mysqli_num_rows($int_row_num)+1;
		}else{
			$intr_no = 1;
		}
		$intr_date = "";
		$intr_type = "";
		$intr_outcm = "";
		$intr_com = "";
		$intr_nxt = "";
	
}
?>
<h2>Add Interaction</h2>
<div id="response"></div>
<div>
	<form id="interaction_form">
		<div class="row">
			<div class="col-25"><label>Interaction #</label></div>
			<div class="col-75"><input type="text" id="test" name="interaction_no" value="<?php echo $intr_no ?>" readonly />
			<br/><span class="form-error" id="errorintr_no"></span></div>
		</div>
		<div class="row">
			<div class="col-25"><label>Interaction Date</label></div>
			<div class="col-75"><input type="text" name="interaction_date" value="<?php echo $intr_date ?>" placeholder="dd/mm/yyyy"/>
			<br/><span class="form-error" id="errorintr_date"></span></div>
		</div>
		<div class="row">
			<div class="col-25"><label>Interaction Type</label></div>
			<div class="col-75">
				<select name="interaction_type" onchange="changeInteractionType()">
					<option value="">Select</option>
					<option value="Phone Call" <?php if($intr_type == "Phone Call"){echo "selected";} ?>>Phone Call</option>
					<option value="Visit" <?php if($intr_type == "Visit"){echo "selected";} ?>>Visit</option>
					<option value="Letter" <?php if($intr_type == "Letter"){echo "selected";} ?>>Letter</option>
				</select>
				<br/><span class="form-error" id="errorintr_type"></span>
			</div>
		</div>
		<div class="row">
			<div class="col-25"><label>Outcome Type</label></div>
			<div class="col-75">
				<select name="interaction_outcome">
					<option value="">Select</option>
					<option value="Phone Off" class="phone" <?php if($intr_outcm == "Phone Off"){echo "selected";} ?>>Phone Off</option>
					<option value="Not Picking" class="phone" <?php if($intr_outcm == "Not Picking"){echo "selected";} ?>>Not Picking</option>
					<option value="Bad Network" class="phone" <?php if($intr_outcm == "Bad Network"){echo "selected";} ?>>Bad Network</option>
					<option value="Answered" class="phone" <?php if($intr_outcm == "Answered"){echo "selected";} ?>>Answered</option>
					<option value="Not Delivered" class="letter" <?php if($intr_outcm == "Not Delivered"){echo "selected";} ?>>Not Delivered</option>
					<option value="Delivered: Customer Not Recieved" class="letter" <?php if($intr_outcm == "Delivered: Customer Not Recieved"){echo "selected";} ?>>Delivered: Customer Not Recieved</option>
					<option value="Delivered: Customer Recieved" class="letter" <?php if($intr_outcm == "Delivered: Customer Recieved"){echo "selected";} ?>>Delivered: Customer Recieved</option>
					<option value="Office Visit" class="visit"<?php if($intr_outcm == "Office Visit"){echo "selected";} ?>>Office Visit</option>
					<option value="Customer Home/Business:Not Found" class="visit"<?php if($intr_outcm == "Customer Home/Business:Not Found"){echo "selected";} ?>>Customer Home/Business:Not Found</option>
					<option value="Customer Home/Business:Found" class="visit" <?php if($intr_outcm == "Customer Home/Business:Found"){echo "selected";} ?>>Customer Home/Business:Found</option>
				</select>
				<br/><span class="form-error" id="errorintr_outcm"></span>
			</div>
		</div>
		<div class="row">
			<div class="col-25"><label>Outcome Comment</label></div>
			<div class="col-75"><textarea rows="5" name="comment"><?php echo $intr_com ?></textarea>
			<br/><span class="form-error" id="errorintr_com"></span></div>
		</div>
		<div class="row">
			<div class="col-25"><label>Next Interaction</label></div>
			<div class="col-75"><input type="text" name="next_interaction" value="<?php echo $intr_nxt ?>" placeholder="dd/mm/yyyy" />
			<br/><span class="form-error" id="errorintr_nxt"></span></div>
		</div>
		<div class="row">
			<div class="col-25"><label>Entered By</label></div>
			<div class="col-75"><input type="text" name="user" value="<?php echo $user ?>" readonly />
			<br/><span class="form-error" id="erroruser"></span></div>
		</div>
		<div>All fields are required</div>
		<div class="forms-buttons">
			<div class="row">
				<input type="button" id="add" value="Add" />
				<input type="button" id="edit" value="Edit" />
				<input type="button" id="canc" value="Cancel" />
			</div>
		</div>
	</form>
</div>
<!--<script>
	document.getElementById('add').onclick = function(){
						alert('added');
					}
</script>-->