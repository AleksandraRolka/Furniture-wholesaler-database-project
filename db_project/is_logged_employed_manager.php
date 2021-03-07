<?php
$hostname = "localhost";
$dbname = "u8rolka";
$username = "u8rolka";
$pass = "8rolka";

// Create connection
$db_conn = pg_connect(" host = $hostname dbname = $dbname user = $username password = $pass ");
pg_query($db_conn,'SET search_path TO hurtownia');

session_start();

$id = $_SESSION['emp_id'];
$result = pg_query($db_conn, "SELECT * from pracownicy_zatrudnienie_okres WHERE id_pracownik=$id;");
$rows = pg_num_rows($result);
if ( $rows > 0 ) {
	while ($row = pg_fetch_assoc($result)) {
		$end = $row['koniec_zatrudnienia'];
		if($row['koniec_zatrudnienia'] != "")
		{
			$_SESSION['zatrudniony'] = FALSE;
		}
		else
		{
			$_SESSION['zatrudniony'] = TRUE;
		}
	}
}

if (!isset($_SESSION['role']) || !isset($_SESSION['name']) || !isset($_SESSION['surname']) || !isset($_SESSION['emp_id']) || $_SESSION['zatrudniony'] == FALSE) {
	header('Location: index.php');
}
else if($_SESSION['name'] and $_SESSION['surname'] and $_SESSION['emp_id'] ) {
	if(  $_SESSION['role']=='kierownik')
		echo " 		<script> document.getElementById('roleName').innerHTML = 'kierownik'; </script>";
	else 
		header('Location: index.php');
}
?>