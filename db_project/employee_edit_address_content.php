
<p class='ListHeader'><u>Edytuj adres pracownika</u></p>
<p class='header_info'>( możesz edytować swój adres oraz adres magazynierów )</p><br>

<div class='editAddressEmployeeFormDiv'>
	<form  id="editAddressEmployeeForm" method="POST" action="<?php echo $_SERVER["PHP_SELF"] ?>" >


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
	// gets to dropdown data list of all warehouseman and logged manager)
			
	$result = pg_query($db_conn, "SELECT * FROM pracownicy_lista WHERE (nazwa_stanowiska='magazynier' OR id_pracownik=".$_SESSION['emp_id'].") AND koniec_zatrudnienia IS NULL;");
	
	$rows = pg_num_rows($result);
		
	if ( $rows > 0 )
	{	
		echo '<div class="form-group">';
		echo '<label for="employeeAddressToEdit" class="selectLabel" >Pracownik: </label>';
		echo '<select name="employeeAddressToEdit" id="employeeAddressToEdit"  class="form-control"  required>';
		echo '	<option value="" selected disabled hidden> Wybierz pracownika </option>';
		while ($row = pg_fetch_assoc($result)) 
		{
			echo '		<option value="'.$row["id_pracownik"].'">'.$row["imie"].','.$row["nazwisko"]. ','.$row["ulica"].','.$row["nr_budynku"].','.$row["kod_pocztowy"].','.$row["miasto"].'</option>';	
		}
		echo '	</select> </div>';
	}

}
?>
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
	
	<input  type="submit" name="submitEditAddressEmployee"  value="Zatwierdź"   id="submitEditAddressEmployee" class="btn btn-default">

	</form>
	<p id="info"></p>
</div>
	
<?php

if($_SESSION['role']=='kierownik')
{
	
	if(isset($_POST['submitEditAddressEmployee']))
	{
		$id_emp = $_POST['employeeAddressToEdit'];
		$street = $_POST['street'];
		$nr = $_POST['nr'];
		$postcode = $_POST['postcode'];
		$city = $_POST['city'];
		
		
		if( isset($id_emp) && isset($street) && isset($nr) && isset($postcode) && isset($city) )
		{
			$result = pg_query($db_conn, "SELECT edytujAdresPracownika($id_emp,'$street','$nr','$postcode','$city');");
			$rows = pg_num_rows($result);
			echo '<script type="text/javascript">console.log("num='.$rows.'");</script>';
			if ( $rows > 0 ) {
				while ($row = pg_fetch_row($result)) 
				{	
					if( $row[0]=='t')
					{
						echo '<script type="text/javascript">document.getElementById("info").innerHTML = "Dane zaktualizowane";</script>';
					}
					else
					{
						echo '<script type="text/javascript">document.getElementById("info").innerHTML = "Nieprawidłowe dane / podany login już istnieje";</script>';
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