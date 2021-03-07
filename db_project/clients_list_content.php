<?php
$hostname = "localhost";
$dbname = "u8rolka";
$username = "u8rolka";
$pass = "8rolka";

session_start();

// Create connection
$db_conn = pg_connect(" host = $hostname dbname = $dbname user = $username password = $pass ");
pg_query($db_conn,'SET search_path TO hurtownia');

echo "<p class='ListHeader'><u>Lista klientów</u></p>";
echo "<div class='list'>";
echo "<table style='width:100%'>";
echo "<thead><tr class='c'><th class='lp'>Lp.</th><th>Nazwa</th><th>Id firmy</th><th>NIP</th><th>E-mail</th><th>Telefon</th><th colspan='4'>Adres<br><span id='det'>(ulica, nr bud., kod pocz., miasto)</span></th></tr>";


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
	
	// getting list of all companies that ever order from managed warehouse
	$result = pg_query($db_conn, "SELECT * FROM lista_klientow_z_magazynami WHERE id_magazyn=$id_wh;;");
	$rows = pg_num_rows($result);
	if ( $rows > 0 ) 
	{
		$i = 0;
		echo "<tbody>";
		while ($row = pg_fetch_assoc($result)) {
			$i += 1;
			echo "<tr ><td class='lp'>".$i."</td><td class='dec_cell'>".$row['nazwa']."</td><td class='dec_cell2'>".$row['id_firma']."</td><td class='c'>".$row['nip']."</td><td class='c'>".$row['email']."</td><td class='c'>".$row['telefon']."</td><td class='c'>".$row['ulica']."</td><td class='c'>".$row['nr_budynku']."</td><td class='c'>".$row['kod_pocztowy']."</td><td class='c'>".$row['miasto']."</td></tr>";
		}
		echo "</tbody>";
	}
}

echo "</table>";
echo "</div>"; 
?>