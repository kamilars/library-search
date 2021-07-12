<?php 
session_start();
?>
<html>
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>Заказ книги</title>
<link rel="stylesheet" type="text/css" href="registration.css">
	<link href="https://fonts.googleapis.com/css?family=Josefin+Sans:300|Poppins:600|Montserrat|Montserrat+Alternates" rel="stylesheet">
 </head>
 <body>
 <div id="header">

<?php
date_default_timezone_set('Asia/Almaty');
 $mindate = date('Y-m-d');
 $maxdate = date('Y-m-d');
$date = date('Y-m-d', strtotime($maxdate. ' + 7 days'));
 
if (($_SESSION['role']=='admin') or ($_SESSION['role']=='librarian')) 
{echo 'Недоступно для библиотекаря или администратора. <br> Вы будете перенаправлены на главную страницу через 3 секунды';
header ("Refresh: 3; index.php");}
else{ 
if ($_SESSION['role']=='reader'){
echo "Добро пожаловать, " . $_SESSION['firstname']; //приветствие
echo '<br><br>
<form action="deauthorization.php">
<input type="submit" value="Выйти из учетной записи">
</form>';}
else {
echo "Вы не авторизованы. Если вы закажете книгу, вы не сможете отслеживать статус подготовки.";
echo '<a href="authorization.php">Войти в систему</a>
	<a href="registration.php">Регистрация</a>';
}
echo 
'</div>
<div>
<form name="reserve_book"  action="reserve_book.php" method="post">';
		if (($_SESSION['role']!='reader')) {
		echo '
		Фамилия, имя:
		<br>
		<input type="text" placeholder="Например, Иванов Иван" name="reader_name" required> 
		<br><br>';} 
		echo 
		'<legend><b>Данные о заказе</b></legend> <br>
		Название <br>
		<input required type="text" placeholder="Например, Гарри Поттер и Дары Смерти" name="book_name" value="'.$_POST['book_name_copy'].'"/> 
		<br><br>
		Автор <br>
		<input required type="text" placeholder="Например, Джоан Роулинг" name="book_author" value="'.$_POST['book_author_copy'].'"/> 
		<br> <br>
		Язык <br>
		<select required name="book_language" >';
		if($_POST['book_language_copy']!='')
		{ echo '<option value="'. $_POST['book_language_copy'].'" selected >'; 
		echo $_POST['book_language_copy'].'</option>'; }
		else { echo '
		<option selected disabled>Выберите язык</option>
		<option value="Русский">Русский</option>
		<option value="Казахский">Казахский</option>
		<option value="Английский">Английский</option>
		<option value="Немецкий">Немецкий</option>
		<option value="Китайский">Китайский</option>';
		}
		echo '</select><br><br>
		Я приду за книгой: <br>';
		echo'<input requred type="date" id="datepicker" name="reservation_date" min="'.$mindate.'" max="'.$date.'">';
		echo'<input required type="time" min="09:00" max="17:00" name="reservation_time"> 
		<br><br>
		Дополнительные комментарии <br>
		<textarea rows="10" cols="45" name="additional" maxlength="1000" placeholder="Любые пожелания, уточнения к заказу"></textarea>
		<br><br>
		<input type="submit" name="reserve_book" value="Отправить"/>
	</form>';
	if (isset($_POST['reserve_book']))
	{
	require_once 'connection.php';

	$link=mysqli_connect ($host, $user, $password, $database)
	or die("Ошибка" . mysqli_error($link));
	$reader_login=$_SESSION['login'];
	if (($_SESSION['role']=='admin') or ($_SESSION['role']=='librarian') or ($_SESSION['role']=='reader')) $reader_name=$_SESSION['firstname']." ".$_SESSION['surname'];
	else $reader_name=$_POST['reader_name'];
	$query="INSERT INTO lib_orders (reader_name, book_name, reader_login, book_author, book_language, reservation_date, reservation_time, additional)
	values ('".$reader_name."', '".$_POST['book_name']."', '".$reader_login."', '".$_POST['book_author']."', '".$_POST['book_language']."', '".$_POST['reservation_date']."', '".$_POST['reservation_time']."','".$_POST['additional']."')";
	$result=mysqli_query($link, $query) or die ("Ошибка" . mysqli_error($link));
	if($result)
{	
	echo "Вы успешно совершили заказ!";
	header ("Location:search.php");
		}
	mysqli_close($link);
	
	}	
	echo 
	'<a href="search.php">Вернуться к поиску</a><br>';
	if ($_SESSION['role']=='admin') {
		echo '<a href="admin_panel.php">Перейти к панели администратора</a><br>';}
		
		elseif ($_SESSION['role']=='librarian') {
		echo '<a href="librarian_panel.php">Перейти к панели библиотекаря</a><br>';}
		
		elseif ($_SESSION['role']=='reader') {
		echo '<a href="reader_panel.php">Перейти к панели читателя</a><br>';}		
echo '<a href="index.php">Перейти на главную</a><br><br>';}
?>

</div>
</body>
</html>