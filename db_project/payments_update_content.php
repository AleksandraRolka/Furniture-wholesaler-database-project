
<p class='ListHeader'><u>Zaktualizuj status płatności</u></p><br>


<div class='updatePaymentStatusFormDiv'>
	<form id="updatePaymentStatusForm" class="updatePaymentStatusForm" method="POST" action="<?php echo $_SERVER["PHP_SELF"] ?>" >

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
	
	// gets to dropdown list all pending payments
	
	$result = pg_query($db_conn, "SELECT * FROM platnosci_lista WHERE id_magazyn=$id_wh AND status_platnosci='oczekująca' ORDER BY id_platnosc;");
	$rows = pg_num_rows($result);
	
	echo '<div class="form-group">';
	echo '<label for="payment" class="selectLabel" >Wybierz płatność: </label>';
	echo '<select id="payment" name="payment"  class="form-control"  required>';
	echo '	<option value="" selected disabled hidden> - </option>';
		
	if ( $rows > 0 ){
		while ($row= pg_fetch_assoc($result))
		{
			echo '<option value="'.$row["id_platnosc"].','.$row["id_zamowienie"].','.$row["id_firma"].'">-- id płat.: '.$row["id_platnosc"].' -- id zamów.: '.$row["id_zamowienie"].' -- '.$row["nazwa"].' -- NIP: '.$row["nip"].' -- kwota: ' .$row["kwota"].' --</option>';
		}
	}
	echo '	</select></div>';	
	
}
	
?>
	<br><div class="form-group">
		<label for="newStatus" class="selectLabel">Wybierz nowy status:</label>
		<select name="newStatus" id="newStatus"  class="form-control" required>
			<option value="" selected disabled hidden> - </option>
			<option value="zrealizowana">zrealizowana</option>
			<option value="nieudana">nieudana</option>
		</select>
	</div>

	<input  type="submit" name="submitPaymentStatus"  value="Zaktualizuj status płatności"   id="submitPaymentStatus" class="btn btn-default" >

	<p id="info"></p>
	</form>	
	
</div>	



<?php

if($_SESSION['role']=='magazynier')
{
	if(isset($_POST['submitPaymentStatus']))
	{
		$all_info = $_POST['payment'];	
		$newStatus = $_POST['newStatus'];

		if( isset($all_info) && isset($newStatus))
		{
			$all_info = explode(",",$all_info);
			$id_payment = $all_info[0];
			$id_order = $all_info[1];
			$id_client = $all_info[2];		
			
			$result = pg_query($db_conn, "SELECT zaktualizujplatnosc('$id_payment', '$id_order', '$id_client', '$newStatus');");
			$rows = pg_num_rows($result);
			
			if ( $rows > 0 ) {
				while ($row = pg_fetch_row($result)) 
				{						
					if( $row[0]=='t')
					{
						echo '<script type="text/javascript">document.getElementById("info").innerHTML = "Zaktualizowano status płatności";</script>';

					}
					else
					{
						echo '<script type="text/javascript">document.getElementById("info").innerHTML = "Błąd przy aktualizowaniu danych w bazie";</script>';
					}
				}
			}
			else
			{
				echo '<script type="text/javascript">document.getElementById("info").innerHTML = "Błąd bazy danych";</script>';
			}
		}
		else
		{
			echo '<script type="text/javascript">document.getElementById("info").innerHTML = "Błąd";</script>';
		}
		header( "refresh:5;url=payments_update.php" );

	}
}
?>