
<p class='ListHeader'><u>Zatrudnianie nowego pracownika</u></p><br>

<div class='newEmployeeFormDiv'>
	<form  id="newEmployeeForm" method="POST" action="<?php echo $_SERVER["PHP_SELF"] ?>" >


<?php
$hostname = "localhost";
$dbname = "dbname";         // changed in orginal project
$username = "username";     // changed in orginal project
$pass = "pass";             // changed in orginal project

session_start();

// Create connection
$db_conn = pg_connect(" host = $hostname dbname = $dbname user = $username password = $pass ");
pg_query($db_conn,'SET search_path TO hurtownia');


if($_SESSION['role']=='kierownik')
{
	
	// gets to dropdown list all available job positions
	
	$result_position = pg_query($db_conn, "SELECT * FROM uprawnienia ORDER BY id_uprawnienia;");
	$row_position = pg_num_rows($result_position);
		
	if ( $row_position > 0 ){
		
		echo '<div class="form-group">';
		echo '<label for="empPosition" class="selectLabel" >Stanowisko: </label>';
		echo '	<select id="empPosition" name="empPosition"  class="form-control"  required>';
		echo '	<option value="" selected disabled hidden> - </option>';
		while ($row_position = pg_fetch_assoc($result_position)) 
		{		
			echo '<option value="'.$row_position["nazwa_stanowiska"].'">'.$row_position["nazwa_stanowiska"].'</option>';
		}		 
		echo '	</select></div>';
		
	}
	
		echo '<!-- input start day of employment -->
		<div class="form-group">
			<label for="startDate" class="inputLabel" >Początek zatrudnienia:</label>
			<input type="text" class="form-control" id="startDate" name="startDate" pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])" placeholder="np. 2021-01-18" required>
		</div>';
	
	
	// gets to dropdown list all available warehouses ( those that don't have warehouseman assigned)
	
	$result_warehouse = pg_query($db_conn, "SELECT * from magazyny_lista WHERE imie IS NULL AND nazwisko IS NULL;");
	$row_warehouse = pg_num_rows($result_warehouse);
		
	if ( $row_warehouse > 0 ){
		
		$mainWarehouse = $row_warehouse['glowny']=='t' ? 'tak' : 'nie';
		
		echo '<div class="form-group">';
		echo '<label for="warehouseNo" class="selectLabel" >Magazyn: </label>';
		echo '	<select name="warehouseNo" id="warehouseNo"  class="form-control" required>';
		echo '	<option value="" selected disabled hidden> Wybierz jeśli stanowisko: magazynier </option>';
		while ($row_warehouse = pg_fetch_assoc($result_warehouse)) 
		{
			echo '		<option value="'.$row_warehouse["id_magazyn"].'" >id: '.$row_warehouse["id_magazyn"].' | główny: '.$mainWarehouse.' | adres: '.$row_warehouse["ulica"].' '.$row_warehouse["nr_budynku"].', '.$row_warehouse["kod_pocztowy"].' '.$row_warehouse["miasto"].'</option>';	
		}
		echo '	</select> </div>';
		
	}

}
?>
	<!-- name imput -->
	<div class="form-group">
		<label for="name" class="inputLabel" >Imię:</label>
		<input type="text" class="form-control" id="name" name="name">
	</div>
	
	<!-- surname imput -->
	<div class="form-group">
		<label for="surname" class="inputLabel" >Nazwisko:</label>
		<input type="text" class="form-control" id="surname" name="surname">
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
		
		<br><br>
		<div class="form-group">
			<label for="log" class="inputLabel" >Login:</label>
			<input type="text" class="form-control" id="log" name="log" required>
		</div>
		
		<div class="form-group">
			<label for="pass" class="inputLabel" >Hasło:</label>
			<input type="password" class="form-control" id="pass" name="pass" required>
		</div>
		
		<input  type="submit" name="submitAddEmployee"  value="Dodaj do bazy"   id="submitAddEmployee" class="btn btn-default">

	</form>
	<p id="info"></p>
</div>

		
<?php

if($_SESSION['role']=='kierownik')
{
	if(isset($_POST['submitAddEmployee']))
	{
		$position = $_POST['empPosition'];
		$startDate = $_POST['startDate'];
		$warehouseNo = $_POST['warehouseNo'];
		
		$name = $_POST['name'];
		$surname = $_POST['surname'];
		$email = $_POST['email'];
		$phone = $_POST['phone'];

		$street = $_POST['street'];
		$nr = $_POST['nr'];
		$postcode = $_POST['postcode'];
		$city = $_POST['city'];
		$log = $_POST['log'];
		$pass = $_POST['pass'];
		
		
		if( isset($position) && isset($startDate) && isset($name) && isset($surname) && isset($email) && isset($phone) && isset($street) && isset($nr) && isset($postcode) && isset($city) && isset($log) && isset($pass) )
		{
			if( isset($warehouseNo) )
			{
				$result = pg_query($db_conn, "SELECT dodajPracownika('$position', '$startDate', $warehouseNo, '$name', '$surname', '$email', '$phone', '$street', '$nr', '$postcode', '$city', '$log', '$pass');");
			}
			else
			{
				$result = pg_query($db_conn, "SELECT dodajPracownika('$position', '$startDate', null, '$name', '$surname', '$email', '$phone', '$street', '$nr', '$postcode', '$city', '$log', '$pass');");
			}
			
			$rows = pg_num_rows($result);
			
			if ( $rows > 0 ) {
				while ($row = pg_fetch_row($result)) 
				{	
					echo '<script type="text/javascript">console.log("'.$row[0].'");</script>';
					
					if( $row[0]=='t')
					{
						echo '<script type="text/javascript">document.getElementById("info").innerHTML = "Pracownik dodany do bazy";</script>';
					}
					else
					{
						echo '<script type="text/javascript">document.getElementById("info").innerHTML = "Pracownik o takich danych już istnieje w bazie";</script>';
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