<?php
	include "login_session.inc";
	//connect to database
	require "dbconn.php";
	
	if(isset($_REQUEST['document_id'])){
		$documentsqlresult = mysqli_query($conn, "SELECT * FROM DocumentsInfo WHERE Document_ID = " . $_REQUEST['document_id']);
		if($documentsqlresult){
			$document_row = mysqli_fetch_assoc($documentsqlresult);
			$docfilename = "./customerfiles/documents/{$_SESSION['customernamefolder']}/".$document_row['Document_name'].$document_row['Document_ID'].$document_row['Document_type'];
			if (file_exists($docfilename)){
				if(mysqli_query($conn, "DELETE FROM DocumentsInfo WHERE Document_ID=".$_REQUEST['document_id']." LIMIT 1")){
					if(unlink($docfilename)){
						$_SESSION['delete_file'] = "<span class='alert-response-success'>The file was deleted successfully</span>";
						header("Location: customer_details.php");
					}
				}else{
					$_SESSION['nodelete_file'] = "<span class='alert-response-error'>Alert: ". mysqli_error($conn)."</span>";
					header("Location: customer_details.php");
				}
			}else{
				$_SESSION['nodelete_file'] = "<span class='alert-response-error'>Alert: The file does not exist</span>";
				header("Location: customer_details.php");
			}
		}else{
			$_SESSION['nodelete_file'] = "<span class='alert-response-error'>Alert: ". mysqli_error($conn)."</span>";
			header("Location: customer_details.php");
		}
		
	}
	
	if(isset($_REQUEST['image_id'])){
		$imagesqlresult = mysqli_query($conn, "SELECT * FROM ImagesInfo WHERE Image_ID = " . $_REQUEST['image_id']);
		if($imagesqlresult){
			$image_row = mysqli_fetch_assoc($imagesqlresult);
			$docfilename = "./customerfiles/images/{$_SESSION['customernamefolder']}/".$image_row['Image_name'].$image_row['Image_ID'].$image_row['Image_type'];
			if (file_exists($docfilename)){
				if(mysqli_query($conn, "DELETE FROM ImagesInfo WHERE Image_ID=".$_REQUEST['image_id']." LIMIT 1")){
					if(unlink($docfilename)){
						$_SESSION['delete_file'] = "<span class='alert-response-success'>The file was deleted successfully</span>";
						header("Location: customer_details.php");
					}
				}else{
					$_SESSION['nodelete_file'] = "<span class='alert-response-error'>Alert:". mysqli_error($conn)."</span>";
					header("Location: customer_details.php");
				}
			}else{
				$_SESSION['nodelete_file'] = "<span class='alert-response-error'>Alert: The file does not exist</span>";
				header("Location: customer_details.php");
			}
		}else{
			$_SESSION['nodelete_file'] = "<span class='alert-response-error'>Alert: ". mysqli_error($conn)."</span>";
			header("Location: customer_details.php");
		}
		
	}
?>