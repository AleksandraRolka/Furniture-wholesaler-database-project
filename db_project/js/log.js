
document.getElementById('login1').addEventListener('input', function() {
	var loginInput1 = document.getElementById('login1').value;
	var passwInput1 = document.getElementById('password1').value;
	if (loginInput1 != "" && passwInput1 != "") {
		document.getElementById('submitButton1').removeAttribute("disabled");
	} else {
		document.getElementById('submitButton1').setAttribute("disabled", null);
	}
  }
);
document.getElementById('password1').addEventListener('input', function() {
	var loginInput1 = document.getElementById('login1').value;
	var passwInput1 = document.getElementById('password1').value;
	if (loginInput1 != "" && passwInput1 != "") {
		document.getElementById('submitButton1').removeAttribute("disabled");
	} else {
		document.getElementById('submitButton1').setAttribute("disabled", null);
	}
  }
);
document.getElementById('login2').addEventListener('input', function() {
	var loginInput2 = document.getElementById('login2').value;
	var passwInput2 = document.getElementById('password2').value;
	if (loginInput2 != "" && passwInput2 != "") {
		document.getElementById('submitButton2').removeAttribute("disabled");
	} else {
		document.getElementById('submitButton2').setAttribute("disabled", null);
	}
  }
);
document.getElementById('password2').addEventListener('input', function() {
	var loginInput2 = document.getElementById('login2').value;
	var passwInput2 = document.getElementById('password2').value;
	if (loginInput2 != "" && passwInput2 != "") {
		document.getElementById('submitButton2').removeAttribute("disabled");
	} else {
		document.getElementById('submitButton2').setAttribute("disabled", null);
	}
  }
);