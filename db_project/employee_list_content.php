<?php
$hostname = "localhost";
$dbname = "dbname";         // changed in orginal project
$username = "username";     // changed in orginal project
$pass = "pass";             // changed in orginal project

session_start();

// Create connection
$db_conn = pg_connect(" host = $hostname dbname = $dbname user = $username password = $pass ");
pg_query($db_conn,'SET search_path TO hurtownia');


if($_SESSION['role']=='kierownik')
{
	echo "<p class='ListHeader'><u>Lista pracowników</u></p>";
	echo "<div class='employee_list'>";

	// getting list of all employees
	$result = pg_query($db_conn, "SELECT * FROM pracownicy_lista ORDER BY nazwa_stanowiska;");

	$rows = pg_num_rows($result);
	if ( $rows > 0 ) {
		$i = 0;
		echo "<table style='width:100%'>";
		echo "<thead><tr class='c'><th class='lp'>Lp.</th><th>Imię</th><th>Nazwisko</th><th>Stanowisko</th><th>Magazyn</th><th>E-mail</th><th>Telefon</th><th colspan='4'>Adres<br><span id='det'>(ulica, nr bud., kod pocz., miasto)</span></th><th colspan='2'>Zatrudnienie<br><span id='det'>(od - do)</span></th></tr>";
		//echo "<tr class='secondTheadRow'><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th>od</th><th>do</th></tr></thead>";
		echo "<tbody>";
		while ($row = pg_fetch_assoc($result)) {
			$i += 1;
			echo "<tr ><td class='lp'>".$i."</td><td class='dec_cell'>".$row['imie']."</td><td class='dec_cell'>".$row['nazwisko']."</td><td class='c'>".$row['nazwa_stanowiska']."</td><td class='c'>".$row['id_magazyn']."</td><td class='c'>".$row['email']."</td><td class='c'>".$row['telefon']."</td><td class='c'>".$row['ulica']."</td><td class='c'>".$row['nr_budynku']."</td><td class='c'>".$row['kod_pocztowy']."</td><td class='c'>".$row['miasto']."</td><td class='startDate'>".$row['poczatek_zatrudnienia']."</td><td class='endDate'>".$row['koniec_zatrudnienia']."</td></tr>";
		}
		echo "</tbody>";
		echo "</table>";
	}

	echo "</div>"; 
}
?>