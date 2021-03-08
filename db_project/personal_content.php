<?php
$hostname = "localhost";
$dbname = "dbname";         // changed in orginal project
$username = "username";     // changed in orginal project
$pass = "pass";             // changed in orginal project

session_start();

// Create connection
$db_conn = pg_connect(" host = $hostname dbname = $dbname user = $username password = $pass ");
pg_query($db_conn,'SET search_path TO hurtownia');

if (isset($_SESSION['role']) || isset($_SESSION['name']) || isset($_SESSION['surname']) || isset($_SESSION['emp_id']) ) {

	$name = $_SESSION['name'];
	$surname = $_SESSION['surname'];
	$emp_id = $_SESSION['emp_id'];
		
	echo "<p class='hd'><u>Dane personalne</u></p>";
	echo "<div class='personal_list'>";

	// getting position in company of the logged user (from database)
	$result = pg_query($db_conn, "SELECT u.nazwa_stanowiska FROM Uprawnienia u, Pracownik p WHERE u.id_uprawnienia=p.id_uprawnienia AND p.imie='$name' AND p.nazwisko='$surname' AND p.id_pracownik='$emp_id';");

	$rows = pg_num_rows($result);
	if ( $rows > 0 ) {
		 while ($row = pg_fetch_assoc($result)) {
				echo "<p><span class='personal_title'>Uprawnienia (stanowisko): </span><span class='personal_data' id='position'>" .$row['nazwa_stanowiska']. "</span></p>";
		}
	}

	// getting position's short description
	$result = pg_query($db_conn, "SELECT u.opis FROM Uprawnienia u, Pracownik p WHERE u.id_uprawnienia=p.id_uprawnienia AND p.imie='$name' AND p.nazwisko='$surname' AND p.id_pracownik='$emp_id';");

	$rows = pg_num_rows($result);
	if ( $rows > 0 ) {
		 while ($row = pg_fetch_assoc($result)) {
				echo "<p><span class='personal_title'>Funkcja: </span><span class='personal_data'>" .$row['opis']. "</span></p>";
		}
	} 

	// getting user's name
	$result = pg_query($db_conn, "SELECT p.imie FROM Uprawnienia u, Pracownik p WHERE u.id_uprawnienia=p.id_uprawnienia AND p.imie='$name' AND p.nazwisko='$surname' AND p.id_pracownik='$emp_id';");

	$rows = pg_num_rows($result);
	if ( $rows > 0 ) {
		 while ($row = pg_fetch_assoc($result)) {
				echo "<p><span class='personal_title'>Imię: </span><span class='personal_data'>" .$row['imie']. "</span></p>";
		}
	}	 

	// getting user's surname
	$result = pg_query($db_conn, "SELECT p.nazwisko FROM Uprawnienia u, Pracownik p WHERE u.id_uprawnienia=p.id_uprawnienia AND p.imie='$name' AND p.nazwisko='$surname' AND p.id_pracownik='$emp_id';");

	$rows = pg_num_rows($result);
	if ( $rows > 0 ) {
		 while ($row = pg_fetch_assoc($result)) {
				echo "<p><span class='personal_title'>Nazwisko: </span><span class='personal_data'>" .$row['nazwisko']. "</span></p>";
		}
	}	

	// getting user's email
	$result = pg_query($db_conn, "SELECT p.email FROM Uprawnienia u, Pracownik p WHERE u.id_uprawnienia=p.id_uprawnienia AND p.imie='$name' AND p.nazwisko='$surname' AND p.id_pracownik='$emp_id';");

	$rows = pg_num_rows($result);
	if ( $rows > 0 ) {
		 while ($row = pg_fetch_assoc($result)) {
				echo "<p><span class='personal_title'>E-mail: </span><span class='personal_data'>" .$row['email']. "</span></p>";
		}
	}

	// getting user's phone number
	$result = pg_query($db_conn, "SELECT p.telefon FROM Uprawnienia u, Pracownik p WHERE u.id_uprawnienia=p.id_uprawnienia AND p.imie='$name' AND p.nazwisko='$surname' AND p.id_pracownik='$emp_id';");

	$rows = pg_num_rows($result);
	if ( $rows > 0 ) {
		 while ($row = pg_fetch_assoc($result)) {
				echo "<p><span class='personal_title'>Telefon: </span><span class='personal_data'>" .$row['telefon']. "</span></p>";
		}
	}	

	echo "</div>";
	echo "<br><br><br><p class='hd'><u>Dane logowania</u></p>";
	echo "<div class='personal_list'>";

	// getting user's login
	$result = pg_query($db_conn, "SELECT w.login FROM Weryfikacja w, Uprawnienia u, Pracownik p WHERE u.id_uprawnienia=p.id_uprawnienia AND w.id_pracownik=p.id_pracownik AND p.imie='$name' AND p.nazwisko='$surname' AND p.id_pracownik='$emp_id';");

	$rows = pg_num_rows($result);
	if ( $rows > 0 ) {
		 while ($row = pg_fetch_assoc($result)) {
				echo "<p><span class='personal_title'>Login: </span><span class='personal_data'>" .$row['login']. "</span></p>";
		}
	}	

	echo "<p><span class='personal_title'>Zmień hasło </span><span class='personal_data'><button onclick='showChangingPassForm()' class='btn btn-default' id='showFormButton'>Formularz</button></span></p>";
	
}
?>
		<div id="changePassDiv">
			<form  id="changePassForm" method="POST" action='<?php echo $_SERVER['PHP_SELF'] ?>'> 
					Nowe hasło:<br>
					<input  type="password"  name="newPass1" id="newPass1">
					<br>Powtórz nowe hasło:<br>
					<input  type="password"  name="newPass2" id="newPass2">
					<br><br>
					<input  type="submit"  name="submitNewPass" value="Zmień hasło" id="submitNewPass" class="btn btn-default" disabled>
			</form>
			<script type="text/javascript" src="js/personal.js"></script> 
		</div>
		<p id="info"></p>
		<?php include 'change_password.php';?>
	</div>
	
