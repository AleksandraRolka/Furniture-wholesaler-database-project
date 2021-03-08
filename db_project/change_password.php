<?php
$hostname = "localhost";
$dbname = "dbname";         // changed in orginal project
$username = "username";     // changed in orginal project
$pass = "pass";             // changed in orginal project

session_start();

// Create connection
$db_conn = pg_connect(" host = $hostname dbname = $dbname user = $username password = $pass ");
pg_query($db_conn,'SET search_path TO hurtownia');


if(isset($_POST['submitNewPass'])) {
    $pass1 = $_POST['newPass1'];
    $pass2 = $_POST['newPass2'];

	if($pass1 == $pass2){
		
		$emp_id = $_SESSION['emp_id'];
		
		$result = pg_query($db_conn, "UPDATE weryfikacja SET haslo=md5('$pass1') WHERE id_pracownik='$emp_id';");
		
		if($result)
		{
			echo '<script type="text/javascript">document.getElementById("info").innerHTML = "Haslo zostalo zmienione";</script>';
		}
		else
		{
			echo '<script type="text/javascript">document.getElementById("info").innerHTML = "Błąd";</script>';
		}
	}
}