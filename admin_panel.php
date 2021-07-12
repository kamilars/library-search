<doctype html public "-//W3C//DTD HTML 4.01/EN" "http://www.w3.org/TR/html4/strict.dtd">
<?php 
session_start();
?>
<html>
	<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="stylesheet" type="text/css" href="registration.css">
	<link href="https://fonts.googleapis.com/css?family=Josefin+Sans:300|Poppins:600|Montserrat|Montserrat+Alternates" rel="stylesheet">
	<title> Главная </title>
	</head>
	<body>
	<?php
	echo '<div id="welcome">Добро пожаловать, ' . $_SESSION['firstname'].'</div>';
if ($_SESSION['role']=='admin'){
	echo '<div id="content">
			<a href="registration.php">Регистрация читателя</a><br>
			<a href="librarian_registration.php">Регистрация библиотекаря</a><br>
			<a href="index.php">Перейти на главную</a>
		</div>
	
	<div id="footer">
</div>';}
elseif ($_SESSION['role']=='reader' or $_SESSION['role']=='librarian') {echo 'У вас недостаточно прав для перехода на данную страницу. <br> Вы будете перенаправлены на главную страницу через 3 секунды';
header ("Refresh: 3; index.php");}
else {header ('Location: authorization.php');}
?>
	</body>
</html>