<?php
	session_start();
	if( isset($_SESSION['role']) ){
		if( $_SESSION['role']=='kierownik' ){
			echo    '<a class="navA" id="employee" href="employee.php" >Pracownicy</a>
					<div class="dropdownContent" id="dropdownContentEmployee">
						<a id="employeeList" href="employee_list.php" >Lista</a>
						<a id="employeeAdd" href="employee_add.php" >Dodaj</a>
						<a id="employeeEdit" href="employee_edit.php" >Edytuj dane</a>
						<a id="employeeEdit" href="employee_edit_address.php" >Edytuj adres</a>
						<a id="employeeFire" href="firing_warehouseman.php">Zwolnij magazyniera</a>
					</div>
					<a class="navA" id="warehouses" href="warehouses.php" >Magazyny</a>
					<div class="dropdownContent" id="dropdownContentWarehouse">
						<a id="warehouseList" href="warehouses_list.php" >Lista</a>
						<a id="warehouseAdd" href="warehouses_add.php" >Dodaj</a>
						<a id="warehouseEditAddress" href="warehouses_edit_address.php" >Edytuj adres</a>
					</div>';
		}
		if( $_SESSION['role']=='magazynier' ){
			echo   '<a class="navA" id="clients" href="clients.php">Klienci</a>
					<div class="dropdownContent">
						<a id="clients" href="clients_list.php">Lista firm</a>
					</div>
					<a class="navA" id="producers" href="producers.php">Producenci</a>
					<div class="dropdownContent">
						<a href="producers_list.php">Lista</a>
						<a href="producers_add.php">Dodaj</a>
					</div>
					<a class="navA" id="products" href="products.php">Katalog produktów</a>
					<div class="dropdownContent">
						<a href="category_list.php">Lista kategorii</a>
						<a href="category_add.php">Dodaj kategorie</a>
						<a href="products_list.php">Lista produktów</a>
						<a href="products_add.php">Dodaj produkt</a>
					</div>
					<a class="navA" id="warehouse" href="warehouse.php">Magazyn</a>
					<div class="dropdownContent">
						<a href="stock.php">Stan</a>
						<a href="restock.php">Uzupełnij asortyment</a>
						<a href="sale_report.php">Sprzedaż</a>
					</div>
					<a class="navA" id="orders" href="orders.php">Zamówienia</a>
					<div class="dropdownContent">
						<a href="new_order.php">Nowe zamównienie</a>
						<a href="orders_list.php">Lista</a>
						<a href="orders_list_ended.php">Zrealizowane</a>
						<a href="orders_list_in_process.php">Oczekujace na wpłatę</a>
						<a href="orders_details.php">Szczegóły zamów.</a>
					</div>
					<a class="navA" id="payments" href="payments.php">Płatności</a>
					<div class="dropdownContent">
						<a href="payments_list.php">Lista</a>
						<a href="payments_list_ended.php">Zrealizowane</a>
						<a href="payments_list_in_process.php">Oczekujace</a>
						<a href="payments_update.php">Zaktualizuj status</a>
					</div>';
		}
	}
	else{
		include 'logout.php';
	}
?>