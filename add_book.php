<doctype html public "-//W3C//DTD HTML 4.01/EN" "http://www.w3.org/TR/html4/strict.dtd">
<?php 
session_start();
?>
<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title> Добавление книги</title>
	<link rel="stylesheet" type="text/css" href="registration.css">
	<link href="https://fonts.googleapis.com/css?family=Josefin+Sans:300|Poppins:600|Montserrat|Montserrat+Alternates" rel="stylesheet">	
	</head>
	<body>
<?php
if ($_SESSION['role']=='librarian'){
	if (isset($_POST['but_input']))
	{
	require_once 'connection.php';

	$link=mysqli_connect ($host, $user, $password, $database)
	or die("Ошибка" . mysqli_error($link));
	
	$query="INSERT INTO lib_books (book_code, book_name, book_author, book_language) values ('".$_POST['book_code']."', '".$_POST['book_name']."', '".$_POST['book_author']."', '".$_POST['book_language']."')";
	$result=mysqli_query($link, $query) or die ("Ошибка. Книга с таким кодом уже существует"/* . mysqli_error($link)*/);
	if($result)
	{
	echo "Книга добавлена";
	echo ('<a href="add_book.php"> Добавить еще одну книгу </a><br>');
	}
	mysqli_close($link);
	}
	else
	{
		echo('
			<form action="add_book.php" method="post">
			<legend>Добавление книги:</legend>
			<br>Код книги:
			<br><input type="text" name="book_code" placeholder="12345678" required>
			<br>
			<br>Название книги:
			<br><input type="text" name="book_name" placeholder="Название книги" required>
			<br>
			<br>Автор книги:
			<br><input type="text" name="book_author" placeholder="Имя Автора" required>
			<br>
			<br>Язык книги:
			<br><select name="book_language">
			<option value="Русский">Русский</option>
			<option value="Казахский">Казахский</option>
			<option value="Английский">Английский</option>
			<option value="Немецкий">Немецкий</option>
			<option value="Китайский">Китайский</option>
			</select><br>
			<br><input type="submit" value="Добавить книгу" name="but_input">
		</form>
		<form action="exit.php">
		<input type="submit" value="Выйти из учетной записи">
		</form>');
	}
}
elseif ($_SESSION['role']=='reader' or $_SESSION['role']=='admin') {echo 'У вас недостаточно прав для перехода на данную страницу. <br> Вы будете перенаправлены на главную страницу через 3 секунды';
header ("Refresh: 3; index.php");}
else {header ('Location: authorization.php');}
?>
<a href="actions_with_books.php">Вернуться на предыдущую</a><br>
<a href="index.php">Перейти на главную</a>
</body>
</html>