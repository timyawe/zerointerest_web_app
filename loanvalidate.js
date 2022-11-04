function validateform() {
	var checkedValue = true;
	var provperiod = document.loanform.prov_period.value;
	var provcheckbox = document.loanform.proInstmts;
	var startdate = document.loanform.startdate.value;
	var principal = document.loanform.principal.value;
	var refNo = document.loanform.loanRefNo.value;
	
	if (startdate == ""  || !/^\d{2}-\d{2}-\d{4}$/i.test(startdate)) {
		checkedValue = false;
		document.getElementById("errorSDate").innerHTML = "Please enter date in 'dd-mm-yyyy' format";
		document.loanform.startdate.focus();
	} else {
		document.getElementById("errorSDate").innerHTML = "";
	}
	
	if (refNo != "" && isNaN(refNo) == true) {
		checkedValue = false;
		document.getElementById("errorRefno").innerHTML = "Please enter numbers only";
		document.loanform.loanRefNo.focus();
	} else {
		document.getElementById("errorRefno").innerHTML = "";
	}
	
	if (provperiod == "" && provcheckbox.checked == true) {
		checkedValue = false;
		alert("Please indicate wether this loan has provisional instalments by ticking (Provisional Instalments?)");
		provcheckbox.checked = false;
	}
	
	if (provperiod != "" && finalperiod != "" && provperiod > finalperiod) {
		checkedValue = false;
		document.getElementById("errordaysperiod").innerHTML = "Provisional Period cannot be greater than Final Period";
	} else {
		document.getElementById("errordaysperiod").innerHTML = "";
	}
	
	if (principal == 0 && principal != "") {
		checkedValue = false;
		document.getElementById("errorPrincipal").innerHTML = "Please enter a value greater than zero";
	} else {
		document.getElementById("errorPrincipal").innerHTML = "";
	}
	
	return checkedValue;
}


function setPeriod() {
	var periodinmonths = document.loanform.periodInMonths.value;
	var provperiod = document.loanform.prov_period.value;
	var finalperiod = document.loanform.final_period.value;
	
	if (provperiod != "" && finalperiod != "") {
		
		document.loanform.periodInMonths.value = provperiod/30 + " of " + finalperiod/30 + " month(s)";
		var enddate = new Date();
		alert(parse(enddate.setDate(enddate.getDate() + finalperiod)));
		enddate.setDate(enddate.getDate() + finalperiod);
		alert(document.loanform.PaymentEndDate.value = enddate);
	}
	
	if (provperiod == "" && finalperiod != "") {
		if(Number.isInteger(finalperiod/30) == false){
			alert("Please enter period in 30 day range");
		}else{
			document.loanform.periodInMonths.value = finalperiod/30 + " month(s)";
			//const enddate = new Date();
			//alert(parse(enddate.setDate(enddate.getDate() + finalperiod)));
			//parse(enddate.setDate(enddate.getDate() + finalperiod));
			//document.loanform.FinalPaymentDate.value = new Date(enddate.setDate(enddate.getDate() + parseInt(finalperiod)));
		}
	}
	
}

function checkPeriodInDays() {
	var provperiod = document.loanform.prov_period.value;
	var finalperiod = document.loanform.final_period.value;
	
	if (provperiod != "" && finalperiod != "" && provperiod > finalperiod) {
		document.getElementById("errordaysperiod").innerHTML = "Provisional Period cannot be greater than Final Period";
	} else {
		document.getElementById("errordaysperiod").innerHTML = "";
	}
}


function setFinalPrincInt() {
	var principal = document.loanform.principal.value;
	var interest = document.loanform.interestrate.value;
	
	if (principal != "" && interest != "") {
		//alert("Arrg");
		//alert((interest/100*principal).toLocaleString();
		document.loanform.f_prin_int.value = (parseInt(interest/100*principal) + parseInt(principal)) ;
	}
}

//document.loanform.principal.addEventListener("onblur", setFinalPrincInt());


function checkProvPeriod() {
	var provperiod = document.loanform.prov_period.value;
	var provcheckbox = document.loanform.proInstmts;
	
	if (provcheckbox.checked == false) {
		alert("Before updating this field indicate wether this loan has provisional instalments by ticking (Provisional Instalments?)");
		provperiod = document.loanform.prov_period.value = "";
	}
}