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
	<title> Панель читателя </title>
	</head>
	<body>
	<?php
	echo '<div id="welcome">Добро пожаловать, ' . $_SESSION['firstname'].'</div>';
	if ($_SESSION['role']=='reader') {
		echo	'<div id="content_panel">
			<a href="search.php" title="Можно проверить наличие книги, а также заказать найденную книгу" id="panel_btn">Найти книгу</a><br>
			<a href="reserve_book.php" title="Заказ книги с ручным заполнением данных о книге" id="panel_btn">Заказать книгу</a><br>
			<a href="readers_reservations.php" title="Просмотр заказанных мною книг и отмена заказов"  id="panel_btn">Мои заказы</a><br>
			<a href="index.php"  id="panel_btn">Перейти на главную</a>
	</div>';}
	elseif ($_SESSION['role']=='librarian' or $_SESSION['role']=='admin') {echo 'У вас недостаточно прав для перехода на данную страницу. <br> Вы будете перенаправлены на главную страницу через 3 секунды';
header ("Refresh: 3; index.php");}
	else header ('Location: authorization.php');?>
	
	<div id="footer">
	</div>
	</body>
</html>