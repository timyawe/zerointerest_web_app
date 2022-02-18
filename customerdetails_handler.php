<?php
include "login_session.inc";
//connect to database
require "dbconn.php";

//Checks wether user wants to view customer details
if (!isset($_POST['add']) and !isset($_POST['edit']) and !isset($_POST['image']) and !isset($_POST['document'])) {
	$_SESSION['customer_no'] = $_REQUEST['customer_no'];
	header("Location: customer_details.php");
}

//Checks wether user has clicked the 'add customer' button
if (isset($_POST['add'])){
		
	/*/Arrays to collect fields which have been filled
	the arrays are to be used to add a record in the database in a FIELDS LIST/VALUE LIST format*/ 
	$addedfields = array();
	$addedvalues = array();
	
	//Assign fileds to variables and add to the array
	
	if ($_POST['datejoined'] != "") {
		//Change date format for mysql date compatibility
		$datejoined = date("Y-m-d", strtotime($_POST['datejoined']));
		$addedfields[] = "`DATE JOINED`";
		$addedvalues[] = "'".$datejoined."'";
	}
	
	if ($_POST['surname']!= "") {
		$surname = trim($_POST["surname"]);
		$addedfields[] = "`SUR_NAME`";
		$addedvalues[] = "'".$surname."'";
	}
	
	if ($_POST['firstname']!= "") {
		$firstname = trim($_POST["firstname"]);
		$addedfields[] = "`FIRST_NAME`";
		$addedvalues[] = "'".$firstname."'";
	}
	
	if ($_POST['othername']!= "") {
		$othername = trim($_POST["othername"]);
		$addedfields[] = "`OTHER_NAME`";
		$addedvalues[] = "'".$othername."'";
	}
	
	if ($_POST['pricontact']!= "") {
		$cont1 = $_POST["pricontact"];
		$addedfields[] = "`PRIMARY_CONTACT`";
		$addedvalues[] = "'".$cont1."'";
	}
	
	if ($_POST['othcontact']!= "") {
		$cont2 = $_POST["othcontact"];
		$addedfields[] = "`OTHER_CONTACT`";
		$addedvalues[] = "'".$cont2."'";
	}
	
	if ($_POST['emailaddr']!= "") {
		$email = $_POST["emailaddr"];
		$addedfields[] = "`EMAIL_ADDRESS`";
		$addedvalues[] = "'".$email."'";
	}
	
	if ($_POST['fb_ac']!= "") {
		$fb_ac = trim($_POST["fb_ac"]);
		$addedfields[] = "`FACEBOOK_A/C`";
		$addedvalues[] = "'".$fb_ac."'";
	}
	
	if ($_POST['MarriageOptions']!= "") {
		$marital = trim($_POST["MarriageOptions"]);
		$addedfields[] = "`MARITAL_STATUS`";
		$addedvalues[] = "'".$marital."'";
	}
	
	if ($_POST['resd']!= "") {
		$resd = $_POST["resd"];
		$addedfields[] = "`RESIDENCE`";
		$addedvalues[] = "'".$resd."'";
	}
	
	if ($_POST['HomeDetails']!= "") {
		$home = $_POST["HomeDetails"];
		$addedfields[] = "`HOME_DETAILS`";
		$addedvalues[] = "'".$home."'";
	}
	
	if ($_POST['bankname']!= "") {
		$bankname = $_POST["bankname"];
		$addedfields[] = "`BANK`";
		$addedvalues[] = "'".$bankname."'";
	}
	
	if ($_POST['bank_ac']!= "") {
		$bank_ac = $_POST["bank_ac"];
		$addedfields[] = "`BANK A/C`";
		$addedvalues[] = "'".$bank_ac."'";
	}
	
	if ($_POST['JobDetails']!= "") {
		$occ_type = $_POST["JobDetails"];
		$addedfields[] = "`OCCUPATION_TYPE`";
		$addedvalues[] = "'".$occ_type."'";
	}
	
	if ($_POST['jobtype']!= "") {
		$jobtype = $_POST["jobtype"];
		$addedfields[] = "`JOB/BUSINESS_TYPE`";
		$addedvalues[] = "'".$jobtype."'";
	}
	
	if ($_POST['employer']!= "") {
		$employer = $_POST["employer"];
		$addedfields[] = "`EMPLOYER`";
		$addedvalues[] = "'".$employer."'";
	}
	
	if ($_POST['job_loc']!= "") {
		$job_loc = $_POST["job_loc"];
		$addedfields[] = "`LOCATION_OF_JOB/BUSINESS`";
		$addedvalues[] = "'".$job_loc."'";
	}

//echo "(".implode(",",$addedfields).")" . "VALUES" . "(".implode(",",$addedvalues).")" ;
 
//Check if array has elements
	if (count($addedfields) > 0 && count($addedvalues) > 0 ) {
		//$sql =<<<SQL
		//	INSERT INTO `Customer Details Table` (
//SQL;
		//foreach ($addedfields as $fieldname => $value) {
			//echo $fieldname. "=>" .$value . "<br/>";
			//echo "INSERT INTO `Customer Details Table` (`$fieldname`) VALUES ('$value') <br/>";
			
		//	$fields =<<<SQL
		//		`$fieldname`
//SQL;

		//	$values .=<<<SQL
		//		'$value',
//SQL;
		//}	
		//Insert data in database
		$insertsql = "INSERT INTO `Customer Details Table` "."(".implode(",",$addedfields).")" . "VALUES" . "(".implode(",",$addedvalues).")" ;
		
		//Check wether record was added
		if(mysqli_query($conn, $insertsql)) {
			//Get the last inserted record
			$lastID = mysqli_insert_id($conn);
	
			//select last inserted record
			$selectsql = "SELECT * FROM `Customer Details Table` WHERE CUSTOMER_NO =" . $lastID;
	
			$selectresult = mysqli_query($conn, $selectsql);
	
			if (mysqli_num_rows($selectresult) > 0 ) {
				$customer_row = mysqli_fetch_assoc($selectresult);
				$_SESSION['customername'] = $customer_row['SUR_NAME']." ". $customer_row['FIRST_NAME'];										
				$_SESSION['customer_no'] = $customer_row['CUSTOMER_NO'];
				$_SESSION['add_customer'] = 1;
				header ("Location: customer_details.php");
			} else {
				echo "Select Error:".  mysqli_error($conn);
			}
		} else {
			echo "Insert Error:". mysqli_error($conn)."<br/>";
		}
		
	}
	//$insertsql = $sql . $fields . ") VALUES ". "(" . $values .")";
	//echo $insertsql;*/
}


//Checks for edited form
if (isset($_POST['edit'])) {
	$dt = date("Y/m/d",strtotime($_POST['datejoined']));
	$dj = date_format(date_create($_POST['datejoined']),"Y-m-d");
	//find the currently edited record
	$editSelectsql = "SELECT * FROM `Customer Details Table` WHERE CUSTOMER_NO =" . $_SESSION['customer_no'];
	$editSelectresult = mysqli_query($conn, $editSelectsql);
	
	if (mysqli_num_rows($editSelectresult) > 0 ) {
		$customer_row = mysqli_fetch_assoc($editSelectresult);
		//$d = strtotime($customer_row['DATE JOINED']);
		$datejoined = $customer_row['DATE JOINED'];
		$refNo = $customer_row['REF_NO'];
		$surName = $customer_row['SUR_NAME'];
		$firstName = $customer_row['FIRST_NAME'];
		$otherName = $customer_row['OTHER_NAME'];
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
	}
	
	//Array to collect fields which have been edited
	$editedfields = array();
	
	//checks wether a field was edited and if so adds it to the array
	if ($_POST["datejoined"] != $datejoined) {
		//Change date format for mysql date compatibility
		$editedfields["DATE JOINED"] = date("Y-m-d", strtotime($_POST['datejoined']));
	}
	
	if ($_POST['refNo'] != $refNo) {
		$editedfields["REF_NO"] = $_POST['refNo'];
		//updateRecord($refNo, $_POST['refNo']);
	}
	
	if ($_POST["surname"] != $surName) {
		$editedfields["SUR_NAME"] = $_POST["surname"];
		//updateRecord($customerName, $_POST["customername"]);
	}
	
	if ($_POST["firstname"] != $firstName) {
		$editedfields["FIRST_NAME"] = $_POST["firstname"];
		//updateRecord($customerName, $_POST["customername"]);
	}
	
	if ($_POST["othername"] != $otherName) {
		$editedfields["OTHER_NAME"] = $_POST["othername"];
		//updateRecord($customerName, $_POST["customername"]);
	}
	
	if ($_POST["pricontact"] != $cont1) {
		$editedfields["PRIMARY_CONTACT"] = $_POST["pricontact"];
		//updateRecord($cont1, $_POST["pricontact"]);
	}
	
	if ($_POST["othcontact"] != $cont2) {
		$editedfields["OTHER_CONTACT"] = $_POST["othcontact"];
		//updateRecord($cont2, $_POST["othcontact"]);
	}
	
	if ($_POST["emailaddr"] != $email) {
		$editedfields["EMAIL_ADDRESS"] = $_POST["emailaddr"];
		//updateRecord($email, $_POST["emailaddr"]);
	}
	
	if ($_POST["fb_ac"] != $fbName) {
		$editedfields["`FACEBOOK_A/C`"] = $_POST["fb_ac"];
		//updateRecord($fbName, $_POST["fb_ac"]);
	}
	
	if ($_POST["MarriageOptions"] != $marrystatus) {
		$editedfields["MARITAL_STATUS"] = $_POST["MarriageOptions"];
		//updateRecord($fbName, $_POST["fb_ac"]);
	}
	
	if ($_POST["resd"] != $resd) {
		$editedfields["RESIDENCE"] = $_POST["resd"];
	}
	
	if ($_POST["HomeDetails"] != $hmdetails) {
		$editedfields["HOME_DETAILS"] = $_POST["HomeDetails"];
	}
	
	if ($_POST["bankname"] != $bankname) {
		$editedfields["BANK"] = $_POST["bankname"];
	}
	
	if ($_POST["bank_ac"] != $bank_ac) {
		$editedfields["BANK A/C"] = $_POST["bank_ac"];
	}
	
	if ($_POST["JobDetails"] != $jobdetails) {
		$editedfields["OCCUPATION_TYPE"] = $_POST["JobDetails"];
	}
	
	if ($_POST["jobtype"] != $jobtype) {
		$editedfields["JOB/BUSINESS_TYPE"] = $_POST["jobtype"];
	}

	if ($_POST["employer"] != $employer) {
		$editedfields["EMPLOYER"] = $_POST["employer"];
	}
	
	if ($_POST["job_loc"] != $jobloc) {
		$editedfields["`LOCATION_OF_JOB/BUSINESS`"] = $_POST["job_loc"];
	}
	
		//$files = $_FILES['uploadedfile']['name'];
		//$editedfields["DOCUMENTS"] = $files;
	
	//Check if array has elements
	if (count($editedfields) > 0 ) {
		foreach ($editedfields as $fieldname => $value) {
			//Update database record
			$updatesql = "UPDATE `Customer Details Table` SET `$fieldname`  = '$value' WHERE CUSTOMER_NO =" . $_SESSION['customer_no'];
		/*echo $fieldname ."=" . $value . "<br/>";
		echo $datejoined;
		echo "dj=".$dj;
		echo "dt=".$dt;
		if ($value == $dj){ echo "We're equal dummy";}else{echo "Think again loser";}
		if ($dj == $datejoined){ echo "You're lucky this time dummy";}else{echo "Just give up already loser";}*/
			//check wether record was updated
			if (mysqli_query($conn, $updatesql)) {
				//echo "<h2>The record has been updated</h2>";
				
				//return updated record
				$updatedrecordsql = "SELECT CUSTOMER_NO FROM `Customer Details Table` WHERE CUSTOMER_NO =" . $_SESSION['customer_no']; 
				$updaterecordsqlresult = mysqli_query($conn, $updatedrecordsql);
				if (mysqli_num_rows($updaterecordsqlresult) > 0) {
					$updaterecordrow = mysqli_fetch_assoc($updaterecordsqlresult);
					$_SESSION['customer_no'] = $updaterecordrow["CUSTOMER_NO"];
					$_SESSION['edit_customer'] = 1;
					header ("Location: customer_details.php");
				}
			} else {
				echo mysqli_error($conn);
			}
		}
	} else {
		//echo "<h2>No record was edited</h2>";
		$_SESSION['noedit_customer'] = 1;
		header ("Location: customer_details.php");
	}

	

	//function updateRecord($fieldname, $value) {
		//$updatesql = "UPDATE `Customer Details Table` SET" . $fieldname . "=" . $value . "WHERE CUSTOMER_NO =" . $_SESSION['customer_no'];
		//}
	//}
}

if (isset($_POST['image'])){
	
	if(count($_FILES['uploadedfile']['name'])==0){
		$_SESSION['noadd_image'] = "<span class='alert-response-error'>Alert: There was an error in uploading your file. 
		Make sure to select a file before clicking the Add Image buttons </span>";
		header("Location: customer_details.php");
	} else {
		if (!file_exists("./customerfiles/images/{$_SESSION['customernamefolder']}/")){
			mkdir("./customerfiles/images/{$_SESSION['customernamefolder']}/"); //"c:/wamp/www/zerointerestfinance_site/images/";
			//if(chdir("c:/wamp/www/zerointerestfinance_site/images/")){
				//$imagedir = getcwd();
				//echo $imagedir;
			//	}
		}
	
		//Array to collect rejected files
		$rejfiles = array();
		
		//Array to collect existing files
		$existingfiles = array();
		
		//Array to collect added files
		$addedfiles = array();
		
		for($x=0; $x < count($_FILES['uploadedfile']['name']); $x++){
			$image_tmpname = $_FILES['uploadedfile']['name'][$x];
			// else {
				//echo "it exists you fool";
			//	die("it exists you fool");
			//}
			
			$imagesdir = "./customerfiles/images/{$_SESSION['customernamefolder']}/";
			$imagename = $imagesdir . $image_tmpname;
			$image_name = trim(substr_replace($image_tmpname, "" , strripos($image_tmpname, ".")));
			$type = "." . pathinfo($imagename, PATHINFO_EXTENSION);
		
			if(pathinfo($image_tmpname, PATHINFO_EXTENSION)!= "jpg" && pathinfo($image_tmpname, PATHINFO_EXTENSION)!= "jpeg" ){
				array_push($rejfiles,$image_tmpname);
			//	$imageadded = false;
				//echo $image_tmpname;
			}elseif(mysqli_num_rows(mysqli_query($conn, "SELECT Image_name FROM ImagesInfo WHERE Image_name='$image_name' AND Image_type='$type' AND CustomerNo=".$_POST['custno_img']))>0){
				array_push($existingfiles,$image_tmpname);
				//$_SESSION['noadd_image'] = "<span class='alert-response-error'>Aler: The file already exists for this customer!</span>";
				//header("Location: customer_details.php");
			} else {
				if (move_uploaded_file($_FILES['uploadedfile']['tmp_name'][$x], $imagename)) {
					//echo $imagename . "<br/>";
					//insert info into ImagesInfo table
					//$image_name = trim(substr_replace($image_tmpname, "" , strripos($image_tmpname, ".")));
					//$type = "." . pathinfo($imagename, PATHINFO_EXTENSION);
					//echo $image_name. $type;
					$insertimageinfosql = "INSERT INTO ImagesInfo (Image_name, Image_type, CustomerNo) VALUES ('$image_name', '$type', {$_SESSION['customer_no']})";
					
					if (mysqli_query($conn, $insertimageinfosql)){
						$lastID = mysqli_insert_id($conn);
						
						$selectimageinfosql = "SELECT * FROM ImagesInfo WHERE Image_ID =" . $lastID;
						$selectresult = mysqli_query($conn, $selectimageinfosql);
						
						if (mysqli_num_rows($selectresult) > 0 ) {
							$imageinfo_row = mysqli_fetch_assoc($selectresult);
							$newimagename = $imagesdir . $imageinfo_row['Image_name'] . $imageinfo_row['Image_ID'] . $imageinfo_row['Image_type'];
							rename($imagename, $newimagename);
							//$imageadded = true;
							array_push($addedfiles, $image_tmpname);
						}
					}
				}
			}
		}
		if(count($rejfiles)>0){
			$_SESSION['rejimgs'] = $rejfiles;
		}
		
		if(count($existingfiles)>0){
			$_SESSION['extfiles'] = $existingfiles;
		}
		
		if(count($addedfiles)>0){
			$_SESSION['addedfiles'] = "<span class='main_content' class='alert-response-success'>".count($addedfiles)." Image(s) Added Successfully</span>";
		}
		
		header("Location: customer_details.php");
	}
}


if (isset($_POST['document'])){
		
		if (!file_exists("c:/wamp64/www/zerointerestfinance_site/customerfiles/documents/{$_SESSION['customernamefolder']}/")){
			mkdir("c:/wamp64/www/zerointerestfinance_site/customerfiles/documents/{$_SESSION['customernamefolder']}/"); //"c:/wamp/www/zerointerestfinance_site/images/";
			//if(chdir("c:/wamp/www/zerointerestfinance_site/images/")){
				//$imagedir = getcwd();
				//echo $imagedir;
			//	}
		}
		//Array to collect rejected files
			$rejfiles = array();
			
			//Array to collect existing files
			$existingfiles = array();
			
			//Array to collect added files
			$addedfiles = array();
			
		for($y=0; $y < count($_FILES['uploadeddocument']['name']); $y++){
			$document_tmpname = $_FILES['uploadeddocument']['name'][$y];
			// else {
				//echo "it exists you fool";
			//	die("it exists you fool");
			//}
			
			$documentsdir = "c:/wamp64/www/zerointerestfinance_site/customerfiles/documents/{$_SESSION['customernamefolder']}/";
			$documentname = $documentsdir . $document_tmpname;
			$document_name = trim(substr_replace($document_tmpname, "" , strripos($document_tmpname, ".")));
			$type = "." . pathinfo($documentname, PATHINFO_EXTENSION);
			
			if(pathinfo($document_tmpname, PATHINFO_EXTENSION)!= "pdf" && pathinfo($document_tmpname, PATHINFO_EXTENSION)!= "doc" && pathinfo($document_tmpname, PATHINFO_EXTENSION)!= "docx" && pathinfo($document_tmpname, PATHINFO_EXTENSION)!= "xls" && pathinfo($document_tmpname, PATHINFO_EXTENSION)!= "xlsx"){
				array_push($rejfiles,$document_tmpname);
			//	$imageadded = false;
				//echo $image_tmpname;
			}elseif(mysqli_num_rows(mysqli_query($conn, "SELECT Document_name FROM DocumentsInfo WHERE Document_name='$document_name' AND Document_type='$type' AND CustomerNo=".$_POST['custno_doc']))>0){
				array_push($existingfiles,$document_tmpname);
			}else{
				if (move_uploaded_file($_FILES['uploadeddocument']['tmp_name'][$y], $documentname)) {
					//echo $documentname . "<br/>";
					
					
					//insert info into ImagesInfo table
					//$document_name = trim(substr_replace($document_tmpname, "" , strripos($document_tmpname, ".")));
					//$type = "." . pathinfo($documentname, PATHINFO_EXTENSION);
					//echo $image_name. $type;
					$insertdocumentinfosql = "INSERT INTO DocumentsInfo (Document_name, Document_type, CustomerNo) VALUES (".'"'.$document_name.'"'.", '$type', {$_SESSION['customer_no']})";
					
					//Check if insert was successful
					if (mysqli_query($conn, $insertdocumentinfosql)){
						$lastID = mysqli_insert_id($conn);
						
						$selectdocumentinfosql = "SELECT * FROM DocumentsInfo WHERE Document_ID =" . $lastID;
						$selectresult = mysqli_query($conn, $selectdocumentinfosql);
						
						if (mysqli_num_rows($selectresult) > 0 ) {
							$documentinfo_row = mysqli_fetch_assoc($selectresult);
							$newdocumentname = $documentsdir . $documentinfo_row['Document_name'] . $documentinfo_row['Document_ID'] . $documentinfo_row['Document_type'];
							rename($documentname, $newdocumentname);
							$documentadded = true;
							array_push($addedfiles, $document_tmpname);
						}
					}
				}
			}
		}
		
		if(count($rejfiles)>0){
		$_SESSION['rejdocs'] = $rejfiles;
		}
		
		if(count($existingfiles)>0){
			$_SESSION['extfiles'] = $existingfiles;
		}
		
		if(count($addedfiles)>0){
			$_SESSION['addedfiles'] = "<span class='main_content' class='alert-response-success'>".count($addedfiles)." Document(s) Added Successfully</span>";
		}
		
		header("Location: customer_details.php");

}
	

?>