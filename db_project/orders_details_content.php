<p class='ListHeader'><u>Wyświetl szczegóły zamówienia</u></p><br>


<div class='orderDetailsFormDiv'>
	<form id="orderDetailsForm" class="orderDetailsForm" method="POST" action="<?php echo $_SERVER["PHP_SELF"] ?>" >

<?php

$hostname = "localhost";
$dbname = "dbname";         // changed in orginal project
$username = "username";     // changed in orginal project
$pass = "pass";             // changed in orginal project

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
	
	// gets to dropdown list all pending payments
	$result = pg_query($db_conn, "SELECT * FROM zamowienia_lista WHERE id_magazyn=$id_wh");
	$rows = pg_num_rows($result);
	
	echo '<div class="form-group">';
	echo '<label for="id_order" class="selectLabel" >Wybierz zamówienie: </label>';
	echo '<select id="id_order" name="id_order"  class="form-control"  required>';
	echo '	<option value="" selected disabled hidden> - </option>';
		
	if ( $rows > 0 ){
		while ($row= pg_fetch_assoc($result))
		{		
			echo '<option value="'.$row["id_zamowienie"].','.$row["data_zamowienia"].','.$row["nazwa"].','.$row["nip"].','.$row["kwota"].','.$row["status"].'">-- id: '.$row["id_zamowienie"].' -- '.$row["nazwa"].' -- NIP: '.$row["nip"].' -- kwota: ' .$row["kwota"].' --</option>';
		}
	}
	echo '	</select></div>';		
}
?>
	<input  type="submit" name="submitOrdersDetails"  value="Pokaż szczegóły"   id="submitOrdersDetails" class="btn btn-default" >
	</form>	

	<br><br>
	<p><b><span>Id zamówienia: </span></b><span id="p1"> </span></p>
	<p><b><span>Data zamówienia: </span></b><span id="p2"> </span></p>
	<p><b><span>Klient: </span></b><span id="p3"> </span></p>
	<p><b><span>Kwota: </span></b><span id="p4"> </span></p>
	<p><b><span>Status: </span></b><span id="p5"> </span></p>
	<br>	
</div>	



<?php

if($_SESSION['role']=='magazynier')
{
	
	echo "<div class='list'>";
	echo "<table style='width:100%'>";
	echo "<thead><tr class='c'><th class='lp'>Lp.</th><th>Kategoria</th><th>Nazwa</th><th>Id produktu</th><th>Producent</th> <th>Cena</th><th>Ilość</th></tr>";
	
	
	if(isset($_POST['submitOrdersDetails']))
	{
		$all_info = $_POST['id_order'];
		$all_info = explode(",",$all_info);
		
		$id_order = $all_info[0];
		$order_date = $all_info[1];
		$client = $all_info[2];
		$nip = $all_info[3];
		$amount = $all_info[4];
		$state = $all_info[5];
		
		echo '<script type="text/javascript">document.getElementById("p1").innerHTML += " '.$id_order.'";</script>';
		echo '<script type="text/javascript">document.getElementById("p2").innerHTML += " '.$order_date.'";</script>';
		echo '<script type="text/javascript">document.getElementById("p3").innerHTML += " '.$client.', '.$nip.'";</script>';
		echo '<script type="text/javascript">document.getElementById("p4").innerHTML += " '.$amount.'";</script>';
		echo '<script type="text/javascript">document.getElementById("p5").innerHTML += " '.$state.'";</script>';
	
		// getting list of all orders from managed warehouse
		$result = pg_query($db_conn, "SELECT * FROM pelny_widok_szczegoly_zamowienia WHERE id_magazyn=$id_wh AND id_zamowienie=$id_order ORDER BY kategoria;");
		$rows = pg_num_rows($result);
		if ( $rows > 0 ) 
		{
			$i = 0;
			
			echo "<tbody id='tb'>";
			while ($row = pg_fetch_assoc($result)) 
			{
				$i += 1;
				echo "<tr ><td class='lp'>".$i."</td><td class='c'>".$row['kategoria']."</td><td class='dec_cell2'>".$row['nazwa_produktu']."</td><td class='dec_cell2'>".$row['id_zamowienie']."</td><td class='c'>".$row['producent']."</td><td class='c'>".$row['cena']."</td><td class='dec_cell2'>".$row['ilosc']."</td></tr>";
			}
			
			echo "</tbody>";
		}
	}
	echo "</table>";
	echo "</div>"; 
}
?>