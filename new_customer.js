//Functions to check wether user has typed required input

//Function to validate form
//function validateForm() {
//	var checkedForm = true;
//	if document.custfrm.


function validateform() {
	var checkedValue = true;
	
	var phone1 = document.custfrm.pricontact.value;
	var phone2 = document.custfrm.othcontact.value;
	var email = document.custfrm.emailaddr.value;
	var bank_ac = document.custfrm.bank_ac.value;
	var datejoined = document.custfrm.datejoined.value;
	
	//validate start date
	if (datejoined != "" && !/^\d{2}-\d{2}-\d{4}$/i.test(datejoined) ) {
		checkedValue = false;
		document.getElementById("errorDate").innerHTML = "Please enter date in 'dd-mm-yyyy' format";
		document.custfrm.datejoined.focus();
	}else {
		document.getElementById("errorDate").innerHTML = "";
	}
	
	//validate phone numbers
	if (checkedValue == true) {
		document.getElementById("errorCont1").innerHTML = "";
		document.getElementById("errorCont2").innerHTML = "";
	}

	if (phone1.length > 10) {
		checkedValue = false;
		document.getElementById("errorCont1").innerHTML = "Phone number is more than 10 characters";
		document.custfrm.pricontact.focus();
	}
	
	if (isNaN(phone1) == true) {
		checkedValue = false;
		document.getElementById("errorCont1").innerHTML = "Please enter numbers only";
		document.custfrm.pricontact.focus();
	}
	
	if (phone1 != "" && phone1.length < 10) {
		checkedValue = false;
		document.getElementById("errorCont1").innerHTML = "Phone number is less than 10 characters";
		document.custfrm.pricontact.focus();
	}
	
	
	if (phone2.length > 10) {
		checkedValue = false;
		document.getElementById("errorCont2").innerHTML = "Phone number is more than 10 characters";
		document.custfrm.othcontact.focus();
	}
	
	if (phone2 != "" && phone2.length < 10) {
		checkedValue = false;
		document.getElementById("errorCont2").innerHTML = "Phone number is less than 10 characters";
		document.custfrm.othcontact.focus();
	}
	
	if (isNaN(phone2) == true) {
		checkedValue = false;
		document.getElementById("errorCont2").innerHTML = "Please enter numbers only";
		document.custfrm.othcontact.focus();
	}
	
	//validate email
	if (!/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i.test(email) && email != "" ) {
		checkedValue = false;
		document.getElementById("errorEmail").innerHTML = "This email is not valid. \n e.g: example@gmail.com";
		document.custfrm.emailaddr.focus();
	} else {
		document.getElementById("errorEmail").innerHTML = "";
	}
	
	//check bank details
	if (document.custfrm.bankname.value != "" && bank_ac == "") {
		checkedValue = false;
		document.getElementById("errorBankA/c").innerHTML = "Bank account is required if bank name is provided";
		document.custfrm.bank_ac.focus();
	}else {
		document.getElementById("errorBankA/c").innerHTML ="";
	}
	
	if (isNaN(bank_ac) == true && bank_ac != "") {
		checkedValue = false;
		document.getElementById("errorBankA/c").innerHTML = "Please enter only numbers";
		document.custfrm.bank_ac.focus();
	} else {
		document.getElementById("errorBankA/c").innerHTML ="";
	}
	var provcheckbox = document.loanform.proInstmts;
	if (/*provperiod != "" &&*/ provcheckbox.checked == true) {
		checkedValue = false;
		alert("Please indicate wether this loan has provisional instalments by ticking (Provisional Instalments?)");
	}
	
	return checkedValue;
}



//Assign buttons
var addBtn = document.getElementById("addbtn");

//Assign event listeners
addBtn.addEventListener("Click", checkPhone1);
addBtn.addEventListener("Click", checkPhone2);
addBtn.addEventListener("Click", checkEmail);
addBtn.addEventListener("Click", checkBankAc);


//Get button element
document.getElementsByTagName("form").addEventListener("submit", checkUsername());
	