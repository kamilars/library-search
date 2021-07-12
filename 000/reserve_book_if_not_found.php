<?php 
session_start();
?>
<html>
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>Поиск книги</title>
  <link rel="stylesheet" type="text/css" href="css/reserve_book.css">
 </head>
 <body>
 <div id="header">
<?php
echo "Добро пожаловать, " . $_SESSION['firstname']; //приветствие
?>
<br>
</div>
<div>
	<form name="search_by_keywords"  action="reserve_book.php" method="post">
		<fieldset>
		<legend>Данные о заказываемой книге</legend>
		<input type="text" placeholder="Название" name="book_name" /> 
		<br><br>
		<input type="text" placeholder="Автор" name="book_author"/> 
		<br> <br>
		<select name="book_language">
			<option selected disabled>Выберите язык</option>
			<option value="Русский">Русский</option>
			<option value="Казахский">Казахский</option>
			<option value="Английский">Английский</option>
			<option value="Немецкий">Немецкий</option>
			<option value="Китайский">Китайский</option>
			</select><br><br>
		Я приду за книгой: 
		<input type="date" name="reservation_date">
		<input type="time" name="reservation_time"> 
		<br><br>
		<textarea rows="10" cols="45" name="additional" maxlength="1000" placeholder="Дополнительно по заказу"></textarea>
		<br><br>
		<input type="submit" name="reserve_book" value="Отправить"/>
		</fieldset>
	</form>
<?php 
	if (isset($_POST['reserve_book']))
	{
	require_once 'connection.php';

	$link=mysqli_connect ($host, $user, $password, $database)
	or die("Ошибка" . mysqli_error($link));
	
	$query="INSERT INTO lib_orders (reader_name, book_name, book_author, book_language, reservation_datetime, additional)
	values ('".$reader_name."', '".$_POST['book_name']."', '".$_POST['book_author']."', '".$_POST['book_language']."', '".$reservation_datetime."', '".$_POST['additional']."')";
	$result=mysqli_query($link, $query) or die ("Ошибка" . mysqli_error($link));
	if($result)
{	
	echo "Уведомление о заказе отправлено библиотекарю!";
	echo ('<a href="search.php"> Вернуться к поиску </a>');
	}
	mysqli_close($link);
	}	
?>	
<a href="search.php">Вернуться к поиску</a>
</div>
</body>
</html>