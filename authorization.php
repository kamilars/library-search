<doctype html public "-//W3C//DTD HTML 4.01/EN" "http://www.w3.org/TR/html4/strict.dtd">
<?php 
session_start();
?>
<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title> Вход в систему </title>
	<link rel="stylesheet" type="text/css" href="registration.css">
	<link href="https://fonts.googleapis.com/css?family=Josefin+Sans:300|Poppins:600|Montserrat|Montserrat+Alternates" rel="stylesheet">	
	</head>
	<body>
	<?php
	if ($_SESSION['role']=='admin' or $_SESSION['role']=='reader' or $_SESSION['role']=='librarian'){ //проверка на то, что пользователь авторизован
	echo  $_SESSION['firstname'].', вы уже авторизованы <br>
	<form action="deauthorization.php">
	<input type="submit" id="reg" value="Выйти из учетной записи">
	</form><br>';
	/*отображение кнопки для перехода на панель пользователя  в зависимости от роли*/
	if ($_SESSION['role']=='admin') { 
	echo '<a href="admin_panel.php">Панель администратора</a><br><br>
	<a href="index.php">Перейти на главную</a>';}
	elseif ($_SESSION['role']=='reader') {
	echo '<a href="reader_panel.php">Панель читателя</a><br><br>
	<a href="index.php">Перейти на главную</a>';}
	elseif ($_SESSION['role']=='librarian') {
	echo '<a href="librarian_panel.php">Панель библиотекаря</a><br><br>
	<a href="index.php">Перейти на главную</a>';}
	}
	/*Если пользователь не авторизован*/
	else{
			if(isset($_POST['authorize'])) //если была нажата кнопка "авторизоваться"
			{
				require_once 'connection.php';
				$link=mysqli_connect($host, $user, $password, $database) or die("Ошибка".mysqli_error($link)); //подключение к БД
				$hash_password=md5($_POST['password']);
				$query_authorize="Select * from lib_users WHERE login='".$_POST['login']."'	AND password='".$hash_password."'"; //формирование запроса на выбор строки, где поля логин и пароль соответствуют введенным
				$result = mysqli_query ($link, $query_authorize) or die("Ошибка". mysqli_error($link)); //выполнение запроса
				$rows = mysqli_num_rows($result); //подсчет количества строк, выбранных по запросу
				if ($rows == 1) // если такая строка есть
				{
					$row = mysqli_fetch_row($result); // занести в массив $row значения из строки, выбранной при запросе
					$_SESSION['firstname']=$row[0]; //запись в сессию имени 
					$_SESSION['surname']=$row[1]; // запись фамилии в сессию
					$_SESSION['login']=$row[2]; // запись логина в сессиб
					if ($row[3]=='librarian') //если значение поля "роль" в выбранной строке равно "библиотекарь"
					{
					$_SESSION['role'] = 'librarian'; //запись роли библиотекаря в сессию
					header("Location: librarian_panel.php"); //переход на панель библиотекаря
					mysqli_close($link);
					exit;
					}
					elseif ($row[3]=='reader') //если значение поля "роль" в выбранной строке равно "читатель"
					{
					$_SESSION['role'] = 'reader'; //запись роли читателя в сессию
					header("Location: reader_panel.php"); //переход на панель читателя
					mysqli_close($link);
					exit;
					}
					elseif ($row[3]=='admin') //если значение поля "роль" в выбранной строке равно "админ"
					{
					$_SESSION['role'] = 'admin';
					header("Location: admin_panel.php");
					mysqli_close($link);
					exit;
					}					
				}
				/*если количество выбранных строк не равно 1, то есть равно 0*/
				else
				{
				
				echo 'Неверный логин или пароль';
				echo '<br> Страница будет обновлена через 3 секунды';
				header ('Refresh: 3; authorization.php'); //обновить страницу авторизации
				}
			}
			else
			{ /*отображение полей ввода, если пользователь не нажимал на кнопку*/
				echo(' 
				<form action="authorization.php" method="post">
				<legend><b> Введите данные для авторизации </b></legend>
				<br> Ваш логин:
				<br><input type="text" name="login" placeholder="Введите Ваш логин" required>
				<br>
				<br> Пароль:
				<br><input type="password" name="password" placeholder="Пароль"  required> <br>
				<br><input type="submit" value="Войти" id="reg" name="authorize">
				</form><br>
				<a href="index.php">Перейти на главную</a><br>	<br>
				<a href="registration.php">Зарегистрироваться</a>');
	}}	
?>

	</body>
</html>