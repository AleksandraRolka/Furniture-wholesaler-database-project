
<!DOCTYPE html>
<html lang="pl">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Hurtownia</title>
	<meta name="description" content="Obsługa hurtowni">
	<meta name="author" content="Aleksandra Rolka">
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="css/main.css">
	<link rel="stylesheet" href="css/personal.css">
	<link rel="stylesheet" href="css/content00.css">
	<link rel="stylesheet" href="css/list.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700&amp;subset=latin-ext" rel="stylesheet">
	<link rel="icon" href="img/favicon.png">
</head>

    <body>
		<div class="jumbotron ">
			<h1>Hurtownia meblowa</h1> 
		</div>
		<div class="container" id="main">
			<div class="sidenav" id="sidenav">
				<a class="role" id="role">Zalogowany jako: <span id="roleName">  </span></a>
				<a class="navA" id="personalInfo"  href="personal.php">Informacje personalne</a>
					<div class="dropdownContent"></div>
				<?php
					include 'is_logged_employed_manager.php';
					include 'nav.php';
				?>
				<a class="navLogOut" href="logout.php">Wyloguj</a>
			</div>
			<script type="text/javascript" src="js/navbar_warehouses.js"></script> 
			<div  id="contentBox" >
					<?php include 'warehouses_header.php';?>				
			</div>
			<ul class="bubbles">
				<li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li>
			</ul>
		</div>


		<script type="text/javascript" src="js/jquery/jquery-3.5.1.min.js"></script>
		<script type="text/javascript" src="js/jquery/jquery-3.5.1.js"></script>
		<script type="text/javascript"> 
			var tab = document.getElementById("warehouses");
			tab.style.backgroundColor = '#38a2ad';
			tab.nextElementSibling.style.display = "block";
		</script>
    </body>
</html>