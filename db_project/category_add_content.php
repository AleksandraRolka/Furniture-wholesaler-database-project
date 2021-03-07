
<p class='ListHeader'><u>Dodaj nową kategorię</u></p><br>


<div class='newCategoryFormDiv'>
	<form  id="newCategoryForm" method="POST" action="<?php echo $_SERVER["PHP_SELF"] ?>" >
	
		<!-- category's name imput -->
		<div class="form-group">
			<label for="cname" class="inputLabel" >Nazwa:</label>
			<input type="text" class="form-control" id="cname" name="cname"  pattern="^.{0,35}" title="max. 35 znaków"required>
		</div>
		<!-- category's input imput -->
		<div class="form-group">
			<label for="description" class="inputLabel" >Opis:</label>
			<input type="text" class="form-control" id="description" name="description" pattern="^.{0,120}" title="max. 160 znaków" required>
		</div>


		<input  type="submit" name="submitAddCategory"  value="Dodaj do bazy"   id="submitAddCategory" class="btn btn-default" >

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
	if(isset($_POST['submitAddCategory']))
	{
		$cname = $_POST['cname'];
		$description = $_POST['description'];

		if( isset($cname) && isset($description))
			{
			$result = pg_query($db_conn, "SELECT dodajKategorie('$cname', '$description');");
			
			$rows = pg_num_rows($result);
			
			if ( $rows > 0 ) {
				while ($row = pg_fetch_row($result)) 
				{						
					if( $row[0]=='t')
					{
						echo '<script type="text/javascript">document.getElementById("info").innerHTML = "Kategoria dodana do bazy";</script>';
					}
					else
					{
						echo '<script type="text/javascript">document.getElementById("info").innerHTML = "Taka kategoria już istnieje w bazie";</script>';
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