<?php
$hostname = "localhost";
$dbname = "u8rolka";
$username = "u8rolka";
$pass = "8rolka";

session_start();

// Create connection
$db_conn = pg_connect(" host = $hostname dbname = $dbname user = $username password = $pass ");
pg_query($db_conn,'SET search_path TO hurtownia');


if($_SESSION['role']=='magazynier')
{
	echo "<p class='ListHeader'><u>Lista producentów</u></p>";
	echo "<div class='list'>";

	echo "<table style='width:100%'>";
	echo "<thead><tr class='c'><th class='lp'>Lp.</th><th>Nazwa</th><th>Id producenta</th><th>E-mail</th><th>Telefon</th></tr>";
	
	// getting list of all producers
	$result = pg_query($db_conn, "SELECT * FROM producent;");
	$rows = pg_num_rows($result);
	if ( $rows > 0 ) 
	{
		$i = 0;
		echo "<tbody>";
		while ($row = pg_fetch_assoc($result)) {
			$i += 1;
			echo "<tr ><td class='lp'>".$i."</td><td class='dec_cell'>".$row['nazwa']."</td><td class='dec_cell2'>".$row['id_producent']."</td><td class='c'>".$row['email']."</td><td class='c'>".$row['telefon']."</td></tr>";
		}
		echo "</tbody>";
	}
	echo "</table>";
	echo "</div>"; 
}
?>