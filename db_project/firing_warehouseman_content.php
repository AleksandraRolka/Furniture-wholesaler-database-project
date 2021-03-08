
<p class='ListHeader'><u>Zwalnianie pracownika (magazyniera)</u></p><br>

<div class='fireWarehousemanFormDiv'>
	<form  id="fireWarehousemanForm" method="POST" action="<?php echo $_SERVER["PHP_SELF"] ?>" >
	
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
			// gets to dropdown list all hired warehouseman
			
			$result = pg_query($db_conn, "SELECT * FROM pracownicy_lista WHERE nazwa_stanowiska='magazynier' AND koniec_zatrudnienia IS NULL;");
			$rows = pg_num_rows($result);
				
			if ( $rows > 0 )
			{	
				echo '<div class="form-group">';
				echo '<label for="warehouseman" class="selectLabel" >Pracownik: </label>';
				echo '	<select name="warehouseman" id="warehouseman"  class="form-control"  required>';
				echo '	<option value="" selected disabled hidden> - </option>';
				while ($row = pg_fetch_assoc($result)) 
				{
					echo '		<option value="'.$row["id_pracownik"].'">'.$row["imie"].' '.$row["nazwisko"]. ' ( id magazynu: '.$row["id_magazyn"].' )</option>';	
				}
				echo '	</select> </div>';
			}
		}
		?>

		<input  type="submit" name="submitFiring"  value="Zwalniam pracownika"   id="submitFiring" class="btn btn-default" >

	<p id="info"></p>
	</form>
</div>	
	
<?php

if($_SESSION['role']=='kierownik')
{
	if(isset($_POST['submitFiring']))
	{
		$id_emp = $_POST['warehouseman'];
		
		echo '<script type="text/javascript">console.log("id='.$id_emp.'")</script>';
		echo '<script type="text/javascript">console.log("ida='.$_POST['warehouseman'].'")</script>';
		
		if( isset($id_emp) )
		{
			
			$result = pg_query($db_conn, "SELECT zwolnijMagazyniera($id_emp);");
			$rows = pg_num_rows($result);
			
			if ( $rows > 0 ) {
				while ($row = pg_fetch_row($result)) 
				{					
					if( $row[0]=='t')
					{
						echo '<script type="text/javascript">document.getElementById("info").innerHTML = "Pracownik został dzisiaj zwolniony";</script>';
					}
					else
					{
						echo '<script type="text/javascript">document.getElementById("info").innerHTML = "Błąd";</script>';
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