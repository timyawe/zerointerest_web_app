function filterTable_name() {
	var input, filter, table, td, tr, y, txtValue;
	
	input = document.getElementById("search_field");
	filter = input.value.toUpperCase();
	table = document.getElementsByTagName("table");
	tr = document.getElementsByTagName("tr");
	
	/* Loop through table rows for the searched text */
	for (y = 0; y < tr.length; y++) {
		td = tr[y].getElementsByClassName("filter_customername")[0];
		if (td) {
			txtValue = td.textContent || td.innerText;
			if (txtValue.toUpperCase().indexOf(filter) > -1) {
				tr[y].style.display = "";
			} else {
				tr[y].style.display = "none";
			}
		}
	}
}

function clicked_row() {
	var table, td, tr, y, t, txtValue;
	
	table = document.getElementById("table");
	tr = document.getElementsByTagName("tr");
	
	window.open("instalment_form.php", "popup", "width=400,height=400");
	return false;
}

function displayFilterRow() {
	if (document.getElementById("filter_row").style.display == "none") {
		document.getElementById("filter_row").style.display = "";
	} else {
		document.getElementById("filter_row").style.display = "none";
	}
}

function filterPayments_type() {
	var input, filter, table, td, tr, y, txtValue;
	
	input = document.getElementById("filter_type");
	filter = input.value.toUpperCase();
	table = document.getElementsByTagName("table");
	tr = document.getElementsByTagName("tr");
	
	/* Loop through table rows for the searched text */
	for (y = 0; y < tr.length; y++) {
		td = tr[y].getElementsByTagName("td")[4];
		if (td) {
			txtValue = td.textContent || td.innerText;
			if (txtValue.toUpperCase().indexOf(filter) > -1) {
				tr[y].style.display = "";
			} else {
				tr[y].style.display = "none";
			}
		}
	}
}

function filterPayments_confirmed() {
	var input, filter, table, td, tr, y, txtValue;
	
	input = document.getElementById("filter_confirmed");
	filter = input.value.toUpperCase();
	table = document.getElementsByTagName("table");
	tr = document.getElementsByTagName("tr");
	
	/* Loop through table rows for the searched text */
	for (y = 0; y < tr.length; y++) {
		td = tr[y].getElementsByTagName("td")[5];
		if (td) {
			txtValue = td.textContent || td.innerText;
			if (txtValue.toUpperCase().indexOf(filter) > -1) {
				tr[y].style.display = "";
			} else {
				tr[y].style.display = "none";
			}
		}
	}
}

function filterPayments_date() {
	var input, filter, table, td, tr, y, txtValue;
	
	input = document.getElementById("filter_date");
	filter = input.value.toUpperCase();
	table = document.getElementsByTagName("table");
	tr = document.getElementsByTagName("tr");
	
	/* Loop through table rows for the searched text */
	for (y = 0; y < tr.length; y++) {
		td = tr[y].getElementsByTagName("td")[2];
		if (td) {
			txtValue = td.textContent || td.innerText;
			if (txtValue.toUpperCase().indexOf(filter) > -1) {
				tr[y].style.display = "";
			} else {
				tr[y].style.display = "none";
			}
		}
	}
}

function total_payments_amounts() {
	var input, sum, table, td, tr, y, txtValue;
	
	table = document.getElementsByTagName("table");
	tr = document.getElementsByTagName("tr");
	
	//create array to hold row values
	var row_values = [];
	
	/* Loop through table rows */
	for (y = 0; y < tr.length; y++) {
		td = tr[y].getElementsByClassName("payments_total")[0];
		if (td) {
			txtValue = parseInt(td.textContent.replace(/\,/g,''));
			if (!isNaN(txtValue)) {
				row_values.push(txtValue);
			}
			
		}
	}
	sum = row_values.reduce((a,b)=> a+b,0);//summing array values
	var formatted_sum = sum.toLocaleString('en-GB');//formatting value as currency
	document.getElementById("total").innerHTML = formatted_sum;	
	
}

function highlight_confirmed_payments () {
	var table, tr, td, txtValue, y;
	
	table = document.getElementsByTagName("table");
	tr = document.getElementsByTagName("tr");
	
	/* Loop through table rows */
	for (y = 0; y < tr.length; y++) {
		td = tr[y].getElementsByClassName("payments_confirmed")[0];
		if (td) {
			txtValue = td.innerHTML;
			if (txtValue == "No") {
				tr[y].style.backgroundColor = "Skyblue";
				tr[y].style.color = "Black";
			}
		}
	}
}

function highlight_clearedloans () {
	var table, tr, td, txtValue, y;
	
	table = document.getElementsByTagName("table");
	tr = document.getElementsByTagName("tr");
	
	/* Loop through table rows */
	for (y = 0; y < tr.length; y++) {
		td = tr[y].getElementsByClassName("cleared_loan")[0];
		if (td) {
			txtValue = td.innerHTML;
			if (txtValue != "") {
				tr[y].style.backgroundColor = "Red";
				tr[y].style.color = "Black";
			}
		}
	}
}

function total_principal_amounts() {
	var input, sum, table, td, tr, y, txtValue;
	
	table = document.getElementsByTagName("table");
	tr = document.getElementsByTagName("tr");
	
	//create array to hold row values
	var row_values = [];
	
	/* Loop through table rows */
	for (y = 0; y < tr.length; y++) {
		td = tr[y].getElementsByClassName("loan_principal")[0];
		if (td) {
			txtValue = parseInt(td.textContent.replace(/\,/g,''));
			if (!isNaN(txtValue)) {
				row_values.push(txtValue);
			}
			
		}
	}
	sum = row_values.reduce((a,b)=> a+b,0);//summing array values
	var formatted_sum = sum.toLocaleString('en-GB');//formatting value as currency
	document.getElementsByClassName("principal_total")[0].innerHTML = formatted_sum;	
	
}

function total_amountpaid() {
	var input, sum, table, td, tr, y, txtValue;
	
	table = document.getElementsByTagName("table");
	tr = document.getElementsByTagName("tr");
	
	//create array to hold row values
	var row_values = [];
	
	/* Loop through table rows */
	for (y = 0; y < tr.length; y++) {
		td = tr[y].getElementsByClassName("loan_amountpaid")[0];
		if (td) {
			txtValue = parseInt(td.textContent.replace(/\,/g,''));
			if (!isNaN(txtValue)) {
				row_values.push(txtValue);
			}
			
		}
	}
	sum = row_values.reduce((a,b)=> a+b,0);//summing array values
	var formatted_sum = sum.toLocaleString('en-GB');//formatting value as currency
	document.getElementsByClassName("amountpaid_total")[0].innerHTML = formatted_sum;	
	
}

function total_principalint_amounts() {
	var sum, table, td, tr, y, txtValue;
	
	table = document.getElementsByTagName("table");
	tr = document.getElementsByTagName("tr");
	
	//create array to hold row values
	var row_values = [];
	
	/* Loop through table rows */
	for (y = 0; y < tr.length; y++) {
		td = tr[y].getElementsByClassName("loan_principal&int")[0];
		if (td) {
			txtValue = parseInt(td.textContent.replace(/\,/g,''));
			if (!isNaN(txtValue)) {
				row_values.push(txtValue);
			}
			
		}
	}
	sum = row_values.reduce((a,b)=> a+b,0);//summing array values
	var formatted_sum = sum.toLocaleString('en-GB');//formatting value as currency
	document.getElementsByClassName("principal&int_total")[0].innerHTML = formatted_sum;	
	
}

function total_amountdue_amounts() {
	var sum, table, td, tr, y, txtValue;
	
	table = document.getElementsByTagName("table");
	tr = document.getElementsByTagName("tr");
	
	//create array to hold row values
	var row_values = [];
	
	/* Loop through table rows */
	for (y = 0; y < tr.length; y++) {
		td = tr[y].getElementsByClassName("loan_amountdue")[0];
		if (td) {
			txtValue = parseInt(td.textContent.replace(/\,/g,''));
			if (!isNaN(txtValue)) {
				row_values.push(txtValue);
			}
			
		}
	}
	sum = row_values.reduce((a,b)=> a+b,0);//summing array values
	var formatted_sum = sum.toLocaleString('en-GB');//formatting value as currency
	document.getElementsByClassName("amountdue_total")[0].innerHTML = formatted_sum;	
	
}

function total_loans_no() {
	var sum, table, td, tr, y, txtValue;
	
	table = document.getElementsByTagName("table");
	tr = document.getElementsByTagName("tr");
	
	
	document.getElementsByClassName("no_of_loans_total")[0].innerHTML = tr.length;	
	
}

function createFilterTable_amounts() {
	var input, table, td, tr, y, txtValue;

	input = document.getElementById("filter_amount");
	table = document.getElementById("this_table");
	tr = document.getElementsByTagName("tr");
	
	//create array with initial to hold select options with initial select option
	var row_values = ["<option value=''>Choose amount...</option>"];
	
	/* Loop through table rows */
	for(y = 0; y < tr.length; y++) {
		td = tr[y].getElementsByClassName("loan_principal")[0];
		if (td) {
			//.relpace is used remove comma from value
			//txtValue = "<option>" + parseInt(td.textContent.replace(/\,/g,'')) + "</option>";
			txtValue = "<option>" + td.textContent + "</option>";
			if (row_values.includes(txtValue) == false) {//check wether value exists in array then add it if not
				row_values.push(txtValue);
			}
			
		} 
	} input.innerHTML = row_values;
	/*var sum = row_values.reduce((a,b)=> a+b,0);
	var sum, i = 0;
	for (; i < row_values.length; i++) {
		sum += row_values;
		
	}
	//alert(sum);
	input.innerHTML = sum;//row_values;//row_values;//.sort(function(a, b){return b-a}); //alert(row_values);
	//alert("weee");*/
}

function filterTable_amount() {
	var input, table, td, tr, y, txtValue;
	
	input = document.getElementById("filter_amount");
	table = document.getElementsByTagName("table");
	tr = document.getElementsByTagName("tr");
	
	/* Loop through table rows for the searched text */
	for (y = 0; y < tr.length; y++) {
		td = tr[y].getElementsByClassName("loan_principal")[0];
		if (td) {
			txtValue = td.textContent || td.innerText;
			if (txtValue.indexOf(input.value) > -1) {
				tr[y].style.display = "";
			} else {
				tr[y].style.display = "none";
			}
		}
	}
}
	