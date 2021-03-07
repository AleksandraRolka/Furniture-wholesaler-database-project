<?php

session_start();


if($_SESSION['role']=='magazynier')
{	
	echo '<h1 class="ml11">
			<span class="text-wrapper">
				<span class="line line1"></span>
				<span class="letters">Klienci</span>
			</span>
		</h1>';
}
?>