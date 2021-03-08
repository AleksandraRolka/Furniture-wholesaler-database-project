
<p class='ListHeader'><u>Dodaj nowy produkt do katalogu</u></p><br>


<div class='newProductsFormDiv'>
	<form  id="newProductsForm" method="POST" action="<?php echo $_SERVER["PHP_SELF"] ?>" >

<?php

$hostname = "localhost";
$dbname = "dbname";         // changed in orginal project
$username = "username";     // changed in orginal project
$pass = "pass";             // changed in orginal project

session_start();

// Create connection
$db_conn = pg_connect(" host = $hostname dbname = $dbname user = $username password = $pass ");
pg_query($db_conn,'SET search_path TO hurtownia');


if($_SESSION['role']=='magazynier')
{
	// gets to dropdown list all available categories
	
	$result = pg_query($db_conn, "SELECT * FROM kategoria;");
	$rows = pg_num_rows($result);
	
	echo '<div class="form-group">';
	echo '<label for="category" class="selectLabel" >Kategoria: </label>';
	echo '	<select id="category" name="category"  class="form-control"  required>';
	echo '	<option value="" selected disabled hidden> - </option>';	
		
	if ( $rows > 0 )
	{
		while ($row= pg_fetch_assoc($result)) 
		{
			echo '<option value="'.$row["id_kategoria"].'">'.$row["nazwa"].'</option>';
		}
	}
	echo '	</select></div>';
	
	// gets to dropdown list all available producers
	
	$result = pg_query($db_conn, "SELECT * FROM producent;");
	$rows = pg_num_rows($result);
		
	if ( $rows > 0 ){
		
		echo '<div class="form-group">';
		echo '<label for="producer" class="selectLabel" >Producent: </label>';
		echo '	<select id="producer" name="producer"  class="form-control"  required>';
		echo '	<option value="" selected disabled hidden> - </option>';
		while ($row= pg_fetch_assoc($result))
		{
			echo '<option value="'.$row["id_producent"].'">'.$row["nazwa"].'</option>';
		}
		echo '	</select></div>';	
	}
}
	
?>


		<!-- product's name imput -->
		<div class="form-group">
			<label for="pname" class="inputLabel" >Nazwa:</label>
			<input type="text" class="form-control" id="pname" name="pname" required>
		</div>
		<!-- product's price imput -->
		<div class="form-group">
			<label for="price" class="inputLabel" >Cena:</label>
			<input type="text" class="form-control" id="price" name="price" pattern="[1-9][0-9]+\.[0-9][0-9]" placeholder="0.00" required>
		</div>

		<input  type="submit" name="submitAddProducts"  value="Dodaj do bazy"   id="submitAddProducts" class="btn btn-default" >

	<p id="info"></p>
	</form>
</div>	



<?php

if($_SESSION['role']=='magazynier')
{
	if(isset($_POST['submitAddProducts']))
	{
		$id_category = $_POST['category'];	
		$id_producer = $_POST['producer'];	
		$pname = $_POST['pname'];
		$price = $_POST['price'];	

		if( isset($id_category) && isset($id_producer) && isset($pname) && isset($price) )
		{
			$result = pg_query($db_conn, "SELECT dodajProdukt('$id_category', '$id_producer', '$pname', '$price');");
			$rows = pg_num_rows($result);
			
			if ( $rows > 0 ) {
				while ($row = pg_fetch_row($result)) 
				{						
					if( $row[0]=='t')
					{
						echo '<script type="text/javascript">document.getElementById("info").innerHTML = "Produkt dodany do bazy";</script>';
					}
					else
					{
						echo '<script type="text/javascript">document.getElementById("info").innerHTML = "Taki produkt już istnieje w bazie";</script>';
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