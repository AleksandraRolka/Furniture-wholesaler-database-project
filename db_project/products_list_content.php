<?php
$hostname = "localhost";
$dbname = "dbname";         // changed in orginal project
$username = "username";     // changed in orginal project
$pass = "pass";             // changed in orginal project

session_start();

// Create connection
$db_conn = pg_connect(" host = $hostname dbname = $dbname user = $username password = $pass ");
pg_query($db_conn,'SET search_path TO hurtownia');

echo "<p class='ListHeader'><u>Lista produktów</u></p>";
echo "<div class='list'>";
echo "<table style='width:100%'>";
	echo "<thead><tr class='c'><th class='lp'>Lp.</th><th>Nazwa produktu</th><th>Id produktu </th><th>Kategoria</th><th>Producent</th><th>Cena</th></tr>";
		
	echo "<tbody>";
	
if($_SESSION['role']=='magazynier')
{

	// getting list of all products category
	$result = pg_query($db_conn, "SELECT * FROM produkty_lista ORDER BY kategoria;");
	$rows = pg_num_rows($result);
	if ( $rows > 0 ) 
	{
		$i = 0;
		while ($row = pg_fetch_assoc($result)) {
			$i += 1;
			echo "<tr ><td class='lp'>".$i."</td><td class='dec_cell2'>".$row['nazwa']."</td><td class='dec_cell2'>".$row['id_produkt']."</td><td class='dec_cell2'>".$row['kategoria']."</td><td class='dec_cell2'>".$row['producent']."</td><td class='c'>".$row['cena']."</td></tr>";
		}
	}
	echo "</tbody>";
	echo "</table>";

	echo "</div>"; 
}
?>