
<p class='ListHeader'><u>Rejestracja nowego magazynu</u></p><br>

<div class='newWarehouseFormDiv'>
	<form  id="newWarehouseForm" method="POST" action="<?php echo $_SERVER["PHP_SELF"] ?>" >

		<div class="form-group">
			<label for="ifMainWarehouse" class="selectLabel" >Czy jest jednym z głównych?</label>
		
		<!-- selecting if warehouse will be a main one -->
			<select name="ifMainWarehouse" id="ifMainWarehouse"  class="form-control" required>
				<option value="" selected disabled hidden> - </option>
				<option value="true">tak</option>
				<option value="false">nie</option>
			</select>
		</div>
		
		<!-- 
			address imput 
		-->
		<p class="addressH"> Adres</p>
		
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

		<input  type="submit" name="submitAddWarehouse"  value="Dodaj do bazy"   id="submitAddWarehouse" class="btn btn-default" >

	</form>
</div>		
	
<?php
$hostname = "localhost";
$dbname = "u8rolka";
$username = "u8rolka";
$pass = "8rolka";

session_start();

// Create connection
$db_conn = pg_connect(" host = $hostname dbname = $dbname user = $username password = $pass ");
pg_query($db_conn,'SET search_path TO hurtownia');


if($_SESSION['role']=='kierownik')
{
	if(isset($_POST['submitAddWarehouse']))
	{
		$main = $_POST['ifMainWarehouse'];
		$street = $_POST['street'];
		$nr = $_POST['nr'];
		$postcode = $_POST['postcode'];
		$city = $_POST['city'];
		// echo '<script type="text/javascript">console.log("'.$main.'");</script>';
		// echo '<script type="text/javascript">console.log("'.$street.'");</script>';
		// echo '<script type="text/javascript">console.log("'.$nr.'");</script>';
		// echo '<script type="text/javascript">console.log("'.$postcode.'");</script>';
		// echo '<script type="text/javascript">console.log("'.$city.'");</script>';
		
		if( isset($main) && isset($street) && isset($nr) && isset($postcode) && isset($city) )
		{
			
			$result = pg_query($db_conn, "SELECT dodajMagazyn('$main','$street','$nr','$postcode','$city');");
			$rows = pg_num_rows($result);
			
			if ( $rows > 0 ) {
				while ($row = pg_fetch_row($result)) 
				{	
					echo '<script type="text/javascript">console.log("'.$row[0].'");</script>';
					
					if( $row[0]=='t')
					{
						echo '<script type="text/javascript">document.getElementById("info").innerHTML = "Magazyn dodany do bazy";</script>';
					}
					else
					{
						echo '<script type="text/javascript">document.getElementById("info").innerHTML = "Magazyn o takich danych już istnieje w bazie";</script>';
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