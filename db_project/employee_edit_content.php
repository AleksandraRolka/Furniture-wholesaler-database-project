
<p class='ListHeader'><u>Edytuj dane pracownika</u></p>
<p class='header_info'>( możesz edytować swoje dane oraz dane magazynierów )</p><br>

<div class='editEmployeeFormDiv'>
	<form  id="editEmployeeForm" method="POST" action="<?php echo $_SERVER["PHP_SELF"] ?>" >


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
	// gets to dropdown data list of all warehouseman and logged manager)
			
	$result = pg_query($db_conn, "SELECT * FROM pracownicy_lista WHERE (nazwa_stanowiska='magazynier' OR id_pracownik=".$_SESSION['emp_id'].") AND koniec_zatrudnienia IS NULL;");
	
	$rows = pg_num_rows($result);
		
	if ( $rows > 0 )
	{	
		echo '<div class="form-group">';
		echo '<label for="employeeToEdit" class="selectLabel" >Pracownik: </label>';
		echo '<select name="employeeToEdit" id="employeeToEdit"  class="form-control"  required>';
		echo '	<option value="" selected disabled hidden> Wybierz pracownika </option>';
		while ($row = pg_fetch_assoc($result)) 
		{
			echo '		<option value="'.$row["id_pracownik"].'">'.$row["imie"].','.$row["nazwisko"]. ','.$row["email"].','.$row["telefon"].','.$row["login"].'</option>';	
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
	
	<div class="form-group">
		<label for="log" class="inputLabel" >Login:</label>
		<input type="text" class="form-control" id="log" name="log" required>
	</div>
	
	<input  type="submit" name="submitEditEmployee"  value="Zatwierdź"   id="submitEditEmployee" class="btn btn-default">

	</form>
	<p id="info"></p>
</div>
	
<?php

if($_SESSION['role']=='kierownik')
{
	
	if(isset($_POST['submitEditEmployee']))
	{
		$id_emp = $_POST['employeeToEdit'];
		$name = $_POST['name'];
		$surname = $_POST['surname'];
		$email = $_POST['email'];
		$phone = $_POST['phone'];
		$log = $_POST['log'];
		
		
		if( isset($id_emp) && isset($name) && isset($surname) && isset($email) && isset($phone) && isset($log) )
		{
			$result = pg_query($db_conn, "SELECT edytujDanePracownika($id_emp,'$name','$surname','$email','$phone','$log');");
			$rows = pg_num_rows($result);
			
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