function validateform() {
		var checkedValue = true;
			if (document.getElementById("uname").value == "") {
				checkedValue = false;
				document.getElementById("erroruname").innerHTML = "This field shouldn't be blank";
				document.getElementById("uname").focus();
			} else {
				document.getElementById("erroruname").innerHTML = "";
			}
			
			var checkedValue = true;
			if (document.getElementById("pword").value == "") {
				checkedValue = false;
				document.getElementById("errorpword").innerHTML = "This field shouldn't be blank";
				document.getElementById("pword").focus();
			} else {
				document.getElementById("errorpword").innerHTML = "";
			}
		
		return checkedValue;
}