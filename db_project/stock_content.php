<?php
$hostname = "localhost";
$dbname = "u8rolka";
$username = "u8rolka";
$pass = "8rolka";

session_start();

// Create connection
$db_conn = pg_connect(" host = $hostname dbname = $dbname user = $username password = $pass ");
pg_query($db_conn,'SET search_path TO hurtownia');

$id_wh;
$id = $_SESSION['emp_id'];

// get id of warehouse managed by a logged un warehouseman
$result = pg_query($db_conn, "SELECT id_magazyn FROM pracownik WHERE id_pracownik=$id;");
$rows = pg_num_rows($result);
if ( $rows > 0 ) 
{
	while ($row = pg_fetch_assoc($result)) {
		$id_wh = $row['id_magazyn'];
	}
}

echo "<p class='ListHeader' style='margin-bottom: 2px;'><u>Stan magazynu</u></p>";
echo "<p style='text-align: center;'>( id magazynu: $id_wh. )</p><br>";

echo "<div class='list'>";
echo "<table style='width:100%'>";
	echo "<thead><tr class='c'><th class='lp'>Lp.</th><th>Nazwa produktu</th><th>Ilość</th><th>Cena j.</th><th class='c'>Id produktu </th><th>Kategoria</th><th>Producent</th></tr>";
	echo "<tbody>";
	
if($_SESSION['role']=='magazynier')
{


	// getting stock listed
	$result = pg_query($db_conn, "SELECT * FROM pelny_stan_magazynow WHERE id_magazyn=$id_wh;");
	$rows = pg_num_rows($result);
	if ( $rows > 0 ) 
	{
		$i = 0;
		while ($row = pg_fetch_assoc($result)) {
			$i += 1;
			echo "<tr ><td class='lp'>".$i."</td><td class='dec_cell2'>".$row['nazwa_produktu']."</td><td class='dec_cell2'>".$row['ilosc']."</td><td class='c'>".$row['cena']."</td><td class='c'>".$row['id_produkt']."</td><td class='dec_cell2'>".$row['kategoria']."</td><td class='c'>".$row['producent']."</td></tr>";
		}
	}
	echo "</tbody>";
	echo "</table>";

	echo "</div>"; 
}
?>