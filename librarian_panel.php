<doctype html public "-//W3C//DTD HTML 4.01/EN" "http://www.w3.org/TR/html4/strict.dtd">
<?php 
session_start();
?>
<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="stylesheet" type="text/css" href="librarian_panel.css">
	<link href="https://fonts.googleapis.com/css?family=Josefin+Sans:300|Poppins:600|Montserrat|Montserrat+Alternates" rel="stylesheet">
	<title> Меню библиотекаря</title>
</head>
<body>
 <div id="header">
<?php
if ($_SESSION['role']=='librarian'){
echo 'Добро пожаловать, ' . $_SESSION['firstname']; //приветствие
echo '
<div id="content_panel">
<form action="books_reservations.php" name="books_reservations" method="post">
<input type="submit" value="Заказы книг" title="Просмотр списка заказов и смена статуса" name="books_reservations">
</form>
<a href="actions_with_books.php" title="Добавление, удаление и редактирование книг" >Действия над книгами</a> <br>
<a href="librarian_registration.php" >Зарегистрировать библиотекаря</a><br>
<a href="index.php">Перейти на главную</a></div>';}
elseif ($_SESSION['role']=='reader' or $_SESSION['role']=='admin') {echo 'У вас недостаточно прав для перехода на данную страницу. <br> Вы будете перенаправлены на главную страницу через 3 секунды';
header ("Refresh: 3; index.php");}
else {header ('Location: authorization.php');}
?>
</form>
</body>
</html>