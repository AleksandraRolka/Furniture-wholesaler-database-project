<?php
$hostname = "localhost";
$dbname = "u8rolka";
$username = "u8rolka";
$pass = "8rolka";

session_start();

// Create connection
$db_conn = pg_connect(" host = $hostname dbname = $dbname user = $username password = $pass ");
pg_query($db_conn,'SET search_path TO hurtownia');

echo "<p class='ListHeader'><u>Lista kategorii</u></p>";
echo "<div class='list'>";
echo "<table style='width:100%'>";
echo "<thead><tr class='c'><th class='lp'>Lp.</th><th>Nazwa</th><th>Id kategorii</th><th>Opis</th></tr>";
echo "<tbody>";
	
if($_SESSION['role']=='magazynier')
{

	// getting list of all products category
	$result = pg_query($db_conn, "SELECT * FROM kategoria;");
	$rows = pg_num_rows($result);
	if ( $rows > 0 ) 
	{
		$i = 0;
		while ($row = pg_fetch_assoc($result)) {
			$i += 1;
			echo "<tr ><td class='lp'>".$i."</td><td class='dec_cell2'>".$row['nazwa']."</td><td class='dec_cell2'>".$row['id_kategoria']."</td><td class='c'>".$row['opis']."</td></tr>";
		}
	}
	echo "</tbody>";
	echo "</table>";

	echo "</div>"; 
}
?>