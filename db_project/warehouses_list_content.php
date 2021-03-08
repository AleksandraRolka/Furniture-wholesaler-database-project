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
	echo "<p class='ListHeader'><u>Lista magazynów</u></p>";
	echo "<div class='list'>";

	// getting list of all warehouses
	$result = pg_query($db_conn, "SELECT * FROM magazyny_lista order by id_magazyn;");

	$rows = pg_num_rows($result);
	if ( $rows > 0 ) {
		$i = 0;
		echo "<table style='width:100%'>";
		echo "<thead><tr class='c'><th class='lp'>Lp.</th><th>Główny</th><th>Id magazynu</th><th colspan='2'>Osoba odpowiedzialna</th><th colspan='4'>Adres<br><span id='det'>(ulica, nr bud., kod pocz., miasto)</span></th></tr>";
		//echo "<tr class='secondTheadRow'><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th>od</th><th>do</th></tr></thead>";
		echo "<tbody>";
		while ($row = pg_fetch_assoc($result)) {
			$i += 1;
			$mainWarehouse = $row['glowny']=='t' ? 'tak' : 'nie';
			echo "<tr><td class='lp'>".$i."</td><td class='c'>$mainWarehouse</td><td class='dec_cell2'>".$row['id_magazyn']."</td><td class='c'>".$row['imie']."</td><td class='c'>".$row['nazwisko']."</td><td class='c'>".$row['ulica']."</td><td class='c'>".$row['nr_budynku']."</td><td class='c'>".$row['kod_pocztowy']."</td><td class='c'>".$row['miasto']."</td></tr>";
		}
		echo "</tbody>";
		echo "</table>";
	}

	echo "</div>"; 
}
?>
