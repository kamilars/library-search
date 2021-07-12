<doctype html public "-//W3C//DTD HTML 4.01/EN" "http://www.w3.org/TR/html4/strict.dtd">
<?php session_start(); ?>
<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title> Регистрация </title>
	<link rel="stylesheet" type="text/css" href="registration.css">
	<link href="https://fonts.googleapis.com/css?family=Josefin+Sans:300|Poppins:600|Montserrat|Montserrat+Alternates" rel="stylesheet">
	</head>
	<body>
<?php
	if (isset($_POST['register']))	//если кнопка "Зарегистрироваться" нажата
	{
		if (($_POST[password]) == ($_POST[password1])) //сравниваются пароли для подтверждения правильности
		{
		require_once 'connection.php'; //подключение к скрипту с данными для подкюлчения к БД
		$link=mysqli_connect ($host, $user, $password, $database) //подключение к БД
		or die("Ошибка" . mysqli_error($link));
		/*формирование запроса на добавление пользователя в базу данных*/
		$hash_password = md5($_POST['password']);
		$query_reg="INSERT INTO lib_users (firstname, surname, login, password) values ('".$_POST['firstname']."', '".$_POST['surname']."', '".$_POST['login']."', '".$hash_password."')";
		$result_reg=mysqli_query($link, $query_reg) or die ("Ошибка: пользователь с таким логином уже существует"/* . mysqli_error($link)*/); //выполнение запроса на добавление
		if($result_reg and $_SESSION['role']!='admin') //если пользователь регистрировался сам, а не с помощью администратора
		{
			echo 'Регистрация прошла успешно! <br>';
			if ($_SESSION['role']=='reader') {echo '<a href="reader_panel.php"> Перейти к панели читателя </a>';}
			elseif ($_SESSION['role']=='librarian') {echo '<a href="reader_panel.php"> Перейти к панели библиотекаря </a>';}
			echo '<a href="search.php"> Перейти к поиску </a>';
			if ($_SESSION['role']!='admin' and $_SESSION['role']!='librarian' and $_SESSION['role']!='reader') {
				/*Выполнение авторизации сразу после регистрации*/
				$query_auth="Select * from lib_users WHERE login='".$_POST['login']."'";//формирование запроса на выборку
				$result_auth=mysqli_query($link, $query_auth) or die ("Ошибка" . mysqli_error($link)); //выполнение этого запроса
				$row = mysqli_fetch_row($result_auth); //Заносятся данные из строки в массив $row
				/*запись в сессию значений фамилии, имени, логина и роли*/
				$_SESSION['firstname']=$row[0];
				$_SESSION['surname']=$row[1];
				$_SESSION['login']=$row[2];
				$_SESSION['role'] = 'reader';
			}
			mysqli_close($link);
		}
		else header("Location:admin_panel.php");} //если регистрировал админ
		else{			
		
		echo 'Пароли не совпадают, заполните форму еще раз
			<br>			
			<form action="registration.php" method="post">
			<legend><b>Регистрация читателя</b></legend>
			<br>Фамилия:
			<br><input type="text" name="surname" placeholder="Например, Иванов" minlength="2" maxlength="30" required title="длина символов от 2 до 30">
			<br>
			<br>Имя:
			<br><input type="text" name="firstname" placeholder="Например, Иван" minlength="2" maxlength="20" required title="длина символов от 2 до 20" >
			<br>
			<br>Логин:
			<br><input type="text" name="login" placeholder="Например, Ivan123" minlength="4" maxlength="25" required title="длина символов от 4 до 25">
			<br>
			<br>Пароль:
			<br><input type="password" name="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}" placeholder="Придумайте пароль" title="Пароль вводится на латинском алфавите, должен содержать как минимум 1 заглавную букву и 1 цифру. Минимальная длина пароля составляет 6 символов, максимальная - 30" maxlnegth="30" required>
			<br>
			<br>Подтвердите пароль:
			<br><input type="password" name="password1" placeholder="Введите пароль еще раз" title="повторный ввод пароля требуется для подтверждения правильности ввода" minlength="6" maxlength="30" required> <br>
			<br><input id="reg" type="submit" value="Регистрация" name="register"><br>
			</form> <br>
			<a href="index.php"> Вернуться на главную </a> <br>
			<br>'; 
			if ($_SESSION['role']=='admin') {echo '<a href="admin_panel.php"> Перейти к панели админа </a><br>';}
			else {echo '<a href="authorization.php" title="перейти к авторизации"> У меня уже есть аккаунт </a>';}
		}

	
	}
	else
	{
		echo '<form action="registration.php" method="post">
		
			<legend><b>Регистрация читателя</b></legend>
			<br>Фамилия:
			<br><input type="text" name="surname" placeholder="Например, Иванов" minlength="2" maxlength="30" required>
			<br>
			<br>Имя:
			<br><input type="text" name="firstname" placeholder="Например, Иван" minlength="2" maxlength="20" required>
			<br>
			<br>Логин:
			<br><input type="text" name="login" placeholder="Например, Ivan123" minlength="4" maxlength="25" required>
			<br>
			<br>Пароль:
			<br><input type="password" name="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}" placeholder="Придумайте пароль" placeholder="Придумайте пароль" title="Пароль вводится на латинском алфавите, должен содержать как минимум 1 заглавную букву и 1 цифру. Минимальная длина пароля составляет 6 символов, максимальная - 30" maxlength="30" required>
			<br>
			<br>Подтвердите пароль:
			<br><input type="password" name="password1" placeholder="Введите пароль еще раз" title="повторный ввод пароля требуется для подтверждения правильности ввода" minlength="6" maxlength="30"  required> <br>
			<br><input id="reg" type="submit" value="Регистрация" name="register"><br>
			
			</form>			<br>
			<a href="index.php"> Вернуться на главную </a> <br>
			<br>';
			if ($_SESSION['role']=='admin') {echo '<a href="admin_panel.php"> Перейти к панели админа </a><br>';}
			else {echo '<a href="authorization.php"  title="перейти к авторизации"> У меня уже есть аккаунт </a>';}
	}
?>
	</body>
</html>