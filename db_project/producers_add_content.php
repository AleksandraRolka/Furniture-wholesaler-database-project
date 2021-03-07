
<p class='ListHeader'><u>Rejestracja nowego producenta</u></p><br>


<div class='newProducersFormDiv'>
	<form  id="newProducersForm" method="POST" action="<?php echo $_SERVER["PHP_SELF"] ?>" >
	
		<!-- producer's name imput -->
		<div class="form-group">
			<label for="pname" class="inputLabel" >Nazwa:</label>
			<input type="text" class="form-control" id="pname" name="pname" required>
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

		<input  type="submit" name="submitAddProducers"  value="Dodaj do bazy"   id="submitAddProducers" class="btn btn-default" >

	<p id="info"></p>
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


if($_SESSION['role']=='magazynier')
{
	if(isset($_POST['submitAddProducers']))
	{
		$pname = $_POST['pname'];
		$email = $_POST['email'];
		$phone = $_POST['phone'];		

		if( isset($pname) && isset($email) && isset($phone) )
		{
	
			$result = pg_query($db_conn, "SELECT dodajProducenta('$pname', '$email', '$phone');");
			
			$rows = pg_num_rows($result);
			
			if ( $rows > 0 ) {
				while ($row = pg_fetch_row($result)) 
				{						
					if( $row[0]=='t')
					{
						echo '<script type="text/javascript">document.getElementById("info").innerHTML = "Producent dodany do bazy";</script>';
					}
					else
					{
						echo '<script type="text/javascript">document.getElementById("info").innerHTML = "Producent o takich danych już istnieje w bazie";</script>';
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