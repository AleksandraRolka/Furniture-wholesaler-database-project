
<p class='ListHeader'><u>Nowe zamówienie</u></p><br>


<div class='newOrderFormDiv'>
	<form  id="newOrderForm" method="POST" action="<?php echo $_SERVER["PHP_SELF"] ?>" >

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


	// gets to dropdown list of all clients that already bought something 
			
	$result = pg_query($db_conn, "SELECT * FROM lista_klientow_z_magazynami WHERE id_magazyn=$id_wh;");
	
	$rows = pg_num_rows($result);
		
	if ( $rows > 0 )
	{	
		echo '<div class="form-group">';
		echo '<label for="existingClient" class="selectLabel" >Klient: </label>';
		echo '<select name="existingClient" id="existingClient"  class="form-control"  required>';
		echo '	<option value="" selected disabled hidden>Wybierz z listy istniejących lub wprowadź nowego</option>';
		while ($row = pg_fetch_assoc($result)) 
		{
			echo '		<option value="'.$row["id_firma"].'">'.$row["nazwa"].','.$row["nip"]. ','.$row["email"].','.$row["telefon"].','.$row["ulica"].','.$row["nr_budynku"].','.$row["kod_pocztowy"].','.$row["miasto"].'</option>';	
		}
		echo '	</select> </div>';
	}	
}
	
?>

	<!-- client's name imput -->
	<div class="form-group">
		<label for="client_name" class="inputLabel" >Nazwa firmy:</label>
		<input type="text" class="form-control" id="client_name" name="client_name">
	</div>
	<!-- NIP imput -->
	<div class="form-group">
		<label for="nip" class="inputLabel" >NIP:</label>
		<input type="text" class="form-control" id="nip" name="nip" pattern="[0-9]{10}" title="10 cyfr">
	</div>
	
	<!-- email imput -->
	<div class="form-group">
		<label for="email" class="inputLabel" >Email:</label>
		<input type="email" class="form-control" id="email" name="email">
	</div>
	
	<!-- phone number imput -->
	<div class="form-group">
		<label for="phone" class="inputLabel" >Nr telefonu:</label>
		<input type="text" class="form-control" id="phone" name="phone" pattern="[0-9]{9}">
	</div>
	
	<div class="form-group">
		<label for="street" class="inputLabel" >Ulica:</label>
		<input type="text" class="form-control" id="street" name="street" required>
	</div>
	<div class="form-group">
		<label for="nr" class="inputLabel" >Nr budynku:</label>
		<input type="text" class="form-control" id="nr" name="nr" pattern="[0-9]{1,3}[a-zA-Z]{0,1}" required>
	</div>
	<div class="form-group">
		<label for="postcode" class="inputLabel" >Kod pocztowy:</label>
		<input type="text" class="form-control" id="postcode" name="postcode" pattern="[0-9]{2}[-][0-9]{3}" placeholder="00-000" required>
	</div>
	<div class="form-group">
		<label for="city" class="inputLabel" >Miasto:</label>
		<input type="text" class="form-control" id="city" name="city" required>
	</div>

	<p class="productsHeader"> Dodaj produkty do zamówienia</p>	
<?php

if($_SESSION['role']=='magazynier')
{

	$result = pg_query($db_conn, "SELECT * FROM pelny_stan_magazynow WHERE id_magazyn=$id_wh AND ilosc > 0 ORDER BY kategoria, nazwa_produktu;");
	$rows = pg_num_rows($result);

	
	echo'<div class="order_products">';
	
	if ( $rows > 0 )
	{	
		while ($row = pg_fetch_assoc($result)) 
		{
			$max = $row['ilosc'];
			
			echo 
			'<div class="form-group" id="prod_item">
				<label for='.$row["id_produkt"].' class="inputLabel" >'.$row["nazwa_produktu"].'</label>
				<input type="number" class="form-control" id="form-control2" name='.$row["id_produkt"].' min="0" max="'.$max.'" value="0" required> ('.$max.')
			</div>';
		}
	}	
	echo '</div>';
}
	
?>
	<input type="submit" name="submitNewOrder"  value="Zatwierdź zamówienie"   id="submitNewOrder" class="btn btn-default" >
	<p id="info"></p>
	
	</form>
</div>	



<?php

if($_SESSION['role']=='magazynier')
{
	if(isset($_POST['submitNewOrder']))
	{
		$name = $_POST['client_name'];
		$nip = $_POST['nip'];
		$email = $_POST['email'];
		$phone = $_POST['phone'];

		$street = $_POST['street'];
		$nr = $_POST['nr'];
		$postcode = $_POST['postcode'];
		$city = $_POST['city'];


		if( isset($name) && isset($nip) && isset($email) && isset($phone) && isset($street) && isset($nr) && isset($postcode) && isset($city) )
		{
			
			$result = pg_query($db_conn, "SELECT * FROM pelny_stan_magazynow WHERE id_magazyn=$id_wh AND ilosc > 0 ORDER BY kategoria, nazwa_produktu;");
			$rows = pg_num_rows($result);
			$id_new_order;
			
			if ( $rows > 0 )
			{	
				$res = pg_query($db_conn, "SELECT noweZamowienie('$name', '$nip', '$email', '$phone', '$street', '$nr', '$postcode', '$city');");
				$rs = pg_num_rows($res);
			
				if ( $rs > 0 ) {
					while($r = pg_fetch_row($res)) 
					{						
						$id_new_order=$r[0];
					}
				}
				else
				{
					echo '<script type="text/javascript">document.getElementById("info").innerHTML = "Błąd przy tworzeniu nowego zamówienia";</script>';
				}
				
				if($id_new_order!=null)
				{
					while ($row = pg_fetch_assoc($result)) 
					{
						$id_pr = $row['id_produkt'];
						$val = $_POST[$id_pr];
						pg_query($db_conn, "SELECT dodajDoZamowienia($id_new_order, $id_pr, $id_wh, $val);");
					}
					echo '<script type="text/javascript">document.getElementById("info").innerHTML = "Zamówienie zostało zarejestrowane";</script>';
				}
				else
				{
					echo '<script type="text/javascript">document.getElementById("info").innerHTML = "Błąd przy tworzeniu nowego zamówienia";</script>';
				}
				
			}	
			else
			{
				echo '<script type="text/javascript">document.getElementById("info").innerHTML = "Błąd przy dodawaniu do bazy";</script>';
			}
		}
		else
		{
			echo '<script type="text/javascript">document.getElementById("info").innerHTML = "Błąd danych";</script>';
		}

	}
}
?>