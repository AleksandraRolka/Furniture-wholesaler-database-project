
<p class='ListHeader'><u>Uzupełnij stan magazynu o nowe egzemplarze</u></p><br>


<div class='restockFormDiv'>
	<form  id="restockForm" method="POST" action="<?php echo $_SERVER["PHP_SELF"] ?>" >

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

if($_SESSION['role']=='magazynier')
{
	
	// gets to dropdown list all products from catalog
	
	$result = pg_query($db_conn, "SELECT * FROM produkty_lista;");
	$rows = pg_num_rows($result);
	
	echo '<div class="form-group">';
	echo '<label for="product" class="selectLabel" >Produkt: </label>';
	echo '	<select id="product" name="product"  class="form-control"  required>';
	echo '	<option value="" selected disabled hidden> - </option>';
		
	if ( $rows > 0 ){
		
		while ($row= pg_fetch_assoc($result))
		{
			echo '<option value="'.$row["id_produkt"].'">'.$row["nazwa"].'</option>';
		}
	}
	echo '	</select></div>';	
}
	
?>

		<!-- product's quantity imput -->
		<div class="form-group">
			<label for="quantity" class="inputLabel" >Ilość:</label>
			<input type="text" class="form-control" id="quantity" name="quantity" pattern="[0-9]{1,10}"  required>
		</div>

		<input  type="submit" name="submitRestock"  value="Dodaj do bazy"   id="submitRestock" class="btn btn-default" >

	<p id="info"></p>
	</form>
</div>	



<?php

if($_SESSION['role']=='magazynier')
{
	if(isset($_POST['submitRestock']))
	{
		$id_product = $_POST['product'];	
		$quantity = $_POST['quantity'];

		if( isset($id_product) && isset($quantity) )
		{
			$result = pg_query($db_conn, "SELECT dodajAsortyment('$id_wh', '$id_product', '$quantity');");
			$rows = pg_num_rows($result);
			
			if ( $rows > 0 ) {
				while ($row = pg_fetch_row($result)) 
				{						
					if( $row[0]=='t')
					{
						echo '<script type="text/javascript">document.getElementById("info").innerHTML = "Zaktualizowano stan";</script>';
					}
					else
					{
						echo '<script type="text/javascript">document.getElementById("info").innerHTML = "Błąd przy dodawaniu do bazy";</script>';
					}
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