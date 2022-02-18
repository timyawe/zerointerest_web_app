<?php include "login_session.inc"; 

//connect to database
require "dbconn.php";

?>
<html>
	<head>
		<title>Customer Photos</title>
		<link href="heading_styles.css" type="text/css" rel="stylesheet" />
		<link href="lists_styles.css" type="text/css" rel="stylesheet" />
		<link href="page_styles.css" type="text/css" rel="stylesheet" />
		<link href="links_styles.css" type="text/css" rel="stylesheet" />
		<link href="text_styles.css" type="text/css" rel="stylesheet" />
		<script>
			var pics = [
						<?php //echo "'Am awesome'". ",'Am not awesome',";
							//echo "'".$_SESSION['customer_no']."'";
							$imagesql = "SELECT * FROM ImagesInfo WHERE CustomerNo = " . $_SESSION['customer_no'];
							
							$imagesqlresult = mysqli_query($conn, $imagesql);
							
							if (mysqli_num_rows($imagesqlresult)>0) {
								
								//$imagepaths = array();
								while ($image_row = mysqli_fetch_assoc($imagesqlresult)){
									//echo "'"."<img src='images/".$image_row['Image_name'] . $image_row['Image_ID'] . $image_row['Image_type']. "' />"."',";
									echo "'"."<img src=".'"'."images/".$image_row['Image_name']. $image_row['Image_ID']. $image_row['Image_type'].'"'.
									"alt=".'"'.$image_row['Image_name']. $image_row['Image_ID']. $image_row['Image_type'].'"'." />"."',";
									//echo "'Sweet',";
								}
							}else {
								echo "'".mysqli_error($conn)."'";	
							} 
						?>
						];
			var nbs = [1,2,3,4,5];
			var num = 0;
			
			function prev() {
				alert(pics.toString());
				alert(nbs.toString());
				/*num--;
				if (num < 0) {
					num = nbs.length-1;
				}
				document.getElementById("slider").innerHTML = nbs[num];*/
				
			}
			
			function next() {
				//alert("Next");
				
				if (num >= nbs.length) {
					num = 0;
				}
				document.getElementById("slider").innerHTML = pics[num];
				num++;
			}
			
			
		</script>
	</head>
	
	<body>
		<!-- Begining of header section -->
				<div id="header"> 
					<h1>ZERO INTEREST FINANCE LIMITED</h1>
					<div id="nav">
					<a href="main_page.php">Home</a>
					<a class="active-nav-link" href="#">Customers Page</a>
					<a href="loans_page.html">Loans Page</a>
					</div>
				</div>
			<!-- End of header section -->
				
			<div id="photos_container">	
				<h2 style="text-align: center; background-color: #4BB4B9; padding: 10px">Customer Photos</h2>
				<?php //echo "'Am awesome'". ",'Am not awesome',";
							//echo "'".$_SESSION['customer_no']."'";
							$imagesql = "SELECT * FROM ImagesInfo WHERE CustomerNo = " . $_SESSION['customer_no'];
							
							$imagesqlresult = mysqli_query($conn, $imagesql);
							
							if (mysqli_num_rows($imagesqlresult)>0) {
								
								//$imagepaths = array();
								while ($image_row = mysqli_fetch_assoc($imagesqlresult)){
									//echo "'"."<img src='images/".$image_row['Image_name'] . $image_row['Image_ID'] . $image_row['Image_type']. "' />"."',";
									echo "<img src=".'"'."./customerfiles/images/{$_SESSION['customernamefolder']}/".$image_row['Image_name']. $image_row['Image_ID']. $image_row['Image_type'].'"'.
									" alt=".'"'.$image_row['Image_name']. $image_row['Image_ID']. $image_row['Image_type'].'"'." /> ";
									//echo "'Sweet',";
								}
							}else {
								echo "'".mysqli_error($conn)."'";	
							} 
						?>
				<!--<button id="prev">Prev</button><span id="slider"></span>
				<!--<div id="slider">
					<img src="images/Che_Gevara062.jpg" height="200px" width="200px" />
				</div>
				
				<button id="next">Next</button>-->
			</div>
			<a class="photos_back_link" href="customer_details.php" title="Click to go back to Customer Details">Back</a>
		
	</body>
	
	<script>
	document.getElementById("prev").addEventListener("click", prev);
	document.getElementById("next").addEventListener("click", next);
	
	</script>

</html>

