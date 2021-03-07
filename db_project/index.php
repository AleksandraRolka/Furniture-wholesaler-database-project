<?php
$hostname = "localhost";
$dbname = "u8rolka";
$username = "u8rolka";
$pass = "8rolka";

session_start();
session_destroy();
session_start();

// Create connection
$db_conn = pg_connect(" host = $hostname dbname = $dbname user = $username password = $pass ");
pg_query($db_conn,'SET search_path TO hurtownia');


if (isset($_POST['submit1'])) {
    $login1 = $_POST['login1'];
    $password1 = md5($_POST['password1']);
	echo '<script>console.log("l='.$login1.'")</script>';	
	echo '<script>console.log("p='.$password1.'")</script>';	
    $result = pg_query($db_conn, "SELECT * FROM weryfikacja w, uprawnienia u, pracownik p WHERE w.login='$login1' AND w.haslo='$password1' AND u.nazwa_stanowiska='kierownik' AND w.id_pracownik=p.id_pracownik AND p.id_uprawnienia=u.id_uprawnienia;");
    $rows = pg_num_rows($result);
	if ( $rows > 0 ) {
		while ($row = pg_fetch_assoc($result)) {
			if( $row['login']==$login1 and $row['haslo']==$password1 ) {
				$_SESSION['role'] = 'kierownik';
				$_SESSION['name'] = $row['imie'];
				$_SESSION['surname'] = $row['nazwisko'];
				$_SESSION['emp_id'] = $row['id_pracownik'];
			}
		}	 
    }
	if(isset($_SESSION['role']) and isset($_SESSION['name']) and isset($_SESSION['surname']) and isset($_SESSION['emp_id']) )
	{
		$id = $_SESSION['emp_id'];
		$result = pg_query($db_conn, "SELECT * from pracownicy_zatrudnienie_okres WHERE id_pracownik=$id;");
		$rows = pg_num_rows($result);
		if ( $rows > 0 ) {
			while ($row = pg_fetch_assoc($result)) {
				$end = $row['koniec_zatrudnienia'];
				if($row['koniec_zatrudnienia'] != "")
				{
					$_SESSION['zatrudniony'] = false;
					echo "<script>alert('Nie jesteś już pracownikiem hurtowni!');
						window.location = 'http://pascal.fis.agh.edu.pl/~8rolka/db_project/index.php';</script>";
				}
				else
				{
					$_SESSION['zatrudniony'] = true;
					header('Location: main.php');
				}
			}	 
		}
	}
	else{
		$login1 = "";
		$password1 = "";
		echo "<script>
		alert('Podany login i hasło nie istnieje w bazie!');
		window.location = 'http://pascal.fis.agh.edu.pl/~8rolka/db_project/index.php';
		</script>";
	}
}
if (isset($_POST['submit2'])) {
    $login2 = $_POST['login2'];
    $password2 = md5($_POST['password2']);

    $result = pg_query($db_conn, "SELECT * FROM weryfikacja w, uprawnienia u, pracownik p WHERE w.login='$login2' AND w.haslo='$password2' AND u.nazwa_stanowiska='magazynier' AND w.id_pracownik=p.id_pracownik AND p.id_uprawnienia=u.id_uprawnienia;");
    $rows = pg_num_rows($result);
	if ( $rows > 0 ) {
		while ($row = pg_fetch_assoc($result)) {
			if( $row['login']==$login2 and $row['haslo']==$password2 ) {
				$_SESSION['role'] = 'magazynier';
				$_SESSION['name'] = $row['imie'];
				$_SESSION['surname'] = $row['nazwisko'];
				$_SESSION['emp_id'] = $row['id_pracownik'];
			}
		}	 
    }
	if(isset($_SESSION['role']) and isset($_SESSION['name']) and isset($_SESSION['surname']) and isset($_SESSION['emp_id']) )
	{
		$id = $_SESSION['emp_id'];
		$result = pg_query($db_conn, "SELECT * from pracownicy_zatrudnienie_okres WHERE id_pracownik=$id;");
		$rows = pg_num_rows($result);
		if ( $rows > 0 ) {
			while ($row = pg_fetch_assoc($result)) {
				$end = $row['koniec_zatrudnienia'];
				if($row['koniec_zatrudnienia'] != "")
				{
					$_SESSION['zatrudniony'] = FALSE;
					echo "<script>alert('Nie jesteś już pracownikiem hurtowni!');
						window.location = 'http://pascal.fis.agh.edu.pl/~8rolka/db_project/index.php';</script>";
				}
				else
				{
					$_SESSION['zatrudniony'] = TRUE;
					header('Location: main.php');
				}
			}	 
		}
	}
	else
	{
		$login2 = "";
		$password2 = "";
		echo "<script>
		alert('Podany login i hasło nie istnieje w bazie!');
		window.location = 'http://pascal.fis.agh.edu.pl/~8rolka/db_project/index.php';
		</script>";
	}
}
?>



<!DOCTYPE html>
<html lang="pl">
<head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
	<title>Hurtownia</title>
	<meta name="description" content="Obsługa hurtowni">
	<meta name="author" content="Aleksandra Rolka">
	
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="css/logPanel.css">

	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700&amp;subset=latin-ext" rel="stylesheet">
			<link rel="icon" href="img/favicon.png">
	
</head>

    <body>
		<div class="jumbotron ">
			<h1>Hurtownia meblowa</h1>
			<h5>Panel logowania</h5> 
		</div>
	
		<div class="container" id="main">
			<div class="row pt-5">
				<div class="col-md-5 pb-5 offset-md-1">
					<h2><b>Logowanie jako kierownik</b></h2>

					<form  method="POST" action="<?php echo $_SERVER["PHP_SELF"];?>">
						<br>Login:<br>
						<input  type="text"  name="login1" id="login1">
						<br>Hasło:<br>
						<input  type="password"  name="password1" id="password1">
						<br><br>
						<input  type="submit"  name="submit1" value="Zaloguj" id="submitButton1" class="btn btn-default" disabled>
					</form>
				</div>
				<div class="col-md-5 offset-md-1">
					<h2><b>Logowanie jako pracownik</b></h2>

					<form  method="POST"  action="<?php echo $_SERVER['PHP_SELF']; ?>">
						<br>Login:<br>
						<input  type="text"  name="login2" id="login2">
						<br>
						Hasło:<br>
						<input  type="password"  name="password2" id="password2">
						<br><br>
						<input  type="submit"  name="submit2" value="Zaloguj" id="submitButton2" class="btn btn-default " disabled>
					</form>
				</div>

			</div>
			<ul class="bubbles">
				<li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li>
			</ul>
		</div>
	  <script src="js/log.js"></script> 
    </body>
</html>
