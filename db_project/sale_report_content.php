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


if($_SESSION['role']=='magazynier')
{

	// get id of warehouse managed by a logged warehouseman
	$result = pg_query($db_conn, "SELECT id_magazyn FROM pracownik WHERE id_pracownik=$id;");
	$rows = pg_num_rows($result);
	if ( $rows > 0 ) 
	{
		while ($row = pg_fetch_assoc($result)) {
			$id_wh = $row['id_magazyn'];
		}
	}	

	echo "<p class='ListHeader'><u>Raport sprzedaży produktów</u></p>";
	echo "<p style='text-align: center;'>( id magazynu: $id_wh. )</p><br>";
	echo "<div class='list'>";
	echo "<table style='width:100%'>";
	echo "<thead><tr class='c'><th class='lp'>Lp.</th><th>Kategoria</th><th>Nazwa</th><th>Producent</th><th>Id produktu</th><th>Ilość</th></tr>";
	
	
	$result = pg_query($db_conn, "SELECT sz.id_magazyn, sz.kategoria, sz.id_produkt, sz.nazwa_produktu, sz.producent, SUM(sz.ilosc) as ilosc FROM pelny_widok_szczegoly_zamowienia sz, zamowienie z WHERE sz.id_magazyn=$id_wh AND sz.id_zamowienie=z.id_zamowienie AND z.status='zakończone' GROUP BY id_produkt, kategoria, id_magazyn, nazwa_produktu, producent;");
	$rows = pg_num_rows($result);
	if ( $rows > 0 ) 
	{
		$i = 0;
		echo "<tbody>";
		while ($row = pg_fetch_assoc($result)) {
			$i += 1;
			echo "<tr ><td class='lp'>".$i."</td><td class='dec_cell2'>".$row['kategoria']."</td><td class='dec_cell2'>".$row['nazwa_produktu']."</td><td class='c'>".$row['producent']."</td><td class='dec_cell2'>".$row['id_produkt']."</td><td class='c'>".$row['ilosc']."</td></tr>";
		}
		echo "</tbody>";
	}
	echo "</table>";
	echo "</div>"; 
}
?>