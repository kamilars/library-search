<doctype html public "-//W3C//DTD HTML 4.01/EN" "http://www.w3.org/TR/html4/strict.dtd">
<?php session_start(); ?>
<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title> Регистрация библиотекаря </title>
	<link rel="stylesheet" type="text/css" href="registration.css">
	<link href="https://fonts.googleapis.com/css?family=Josefin+Sans:300|Poppins:600|Montserrat|Montserrat+Alternates" rel="stylesheet">
	</head>
	<body>
<?php
	if ($_SESSION['role']=='admin' or $_SESSION['role']=='librarian') {
	if (isset($_POST['register']))	//если кнопка "Зарегистрироваться" нажата
	{
		if (($_POST[password]) == ($_POST[password1])) //сравниваются пароли для подтверждения правильности
	{
	require_once 'connection.php'; //подключение к скрипту с данными для подкюлчения к БД
	$link=mysqli_connect ($host, $user, $password, $database) //подключение к БД
	or die("Ошибка" . mysqli_error($link));
	$hash_password=md5($_POST['password']);
	$query_reg="INSERT INTO lib_users (firstname, surname, login, role, password) values ('".$_POST['firstname']."', '".$_POST['surname']."', '".$_POST['login']."', 'librarian', '".$hash_password."')";
	$result_reg=mysqli_query($link, $query_reg) or die ("Ошибка" . mysqli_error($link));
	if($result_reg)
		{
		echo 'Вы успешно зарегистрировали библиотекаря! 
		<br>';
		if ($_SESSION['role']=='librarian') echo '<a href="librarian_panel.php"> Перейти к панели библиотекаря </a><br>';
		if ($_SESSION['role']=='admin') echo '<a href="admin_panel.php"> Перейти к панели админа </a>';
		}
	mysqli_close($link);
	}
	else{			
		
		echo 'Пароли не совпадают, заполните форму еще раз
			<br>			
			<form action="librarian_registration.php" method="post">
			
			<legend><b>Регистрация библиотекаря</b></legend>
			<br>Фамилия:
			<br><input type="text" name="surname" placeholder="Например, Иванов" minlength="2" maxlength="30" required>
			<br>
			<br>Имя:
			<br><input type="text" name="firstname" placeholder="Например, Иван" minlength="2" maxlength="20" required>
			<br>
			<br>Логин:
			<br><input type="text" name="login" placeholder="Например, Ivan123" minlength="4" maxlength="15" required>
			<br>
			<br>Пароль:
			<br><input type="password" name="password" placeholder="Придумайте пароль" title="Введите не менее 6 и не более 30 символов" minlength="6" maxlnegth="30" required>
			<br>
			<br>Подтвердите пароль:
			<br><input type="password" name="password1" placeholder="Введите пароль еще раз" title="повторный ввод пароля требуется для подтверждения правильности ввода" minlength="6" maxlength="30" required> <br>
			<br><input id="reg" type="submit" value="Регистрация" name="register">
			</form><br>';
			if ($_SESSION['role']=='admin') echo '<a href="admin_panel.php"> Перейти к панели админа </a><br><br>';
			if ($_SESSION['role']=='librarian') echo '<a href="librarian_panel.php"> Перейти к панели библиотекаря </a><br>';
			echo '<a href="index.php"> Вернуться на главную </a> ';
		}

	
	}
	else
	{
		echo '<form action="librarian_registration.php" method="post">
		
			<legend><b>Регистрация библиотекаря</b></legend>
			<br>Фамилия:
						<br><input type="text" name="surname" placeholder="Например, Иванов" minlength="2" maxlength="30" required>
			<br>
			<br>Имя:
			<br><input type="text" name="firstname" placeholder="Например, Иван" minlength="2" maxlength="20" required>
			<br>
			<br>Логин:
			<br><input type="text" name="login" placeholder="Например, Ivan123" minlength="4" maxlength="15" required>
			<br>
			<br>Пароль:
			<br><input type="password" name="password" placeholder="Придумайте пароль" title="Введите не менее 6 и не более 30 символов" minlength="6" maxlnegth="30" required>
			<br>
			<br>Подтвердите пароль:
			<br><input type="password" name="password1" placeholder="Введите пароль еще раз" title="повторный ввод пароля требуется для подтверждения правильности ввода" minlength="6" maxlength="30" required> <br>
			<br><input type="submit" id="reg" value="Регистрация" name="register">
			</form><br>';
			if ($_SESSION['role']=='admin') echo '<a href="admin_panel.php"> Перейти к панели админа </a><br><br>';
			if ($_SESSION['role']=='librarian') echo '<a href="librarian_panel.php"> Перейти к панели библиотекаря </a><br>';
			echo '<a href="index.php"> Вернуться на главную </a> ';	
	}}
	elseif ($_SESSION['role']=='reader') {echo 'У вас недостаточно прав для перехода на данную страницу. <br> Вы будете перенаправлены на главную страницу через 3 секунды';
	header ("Refresh: 3; index.php");}
	else header ('Location: authorization.php');
?>
	</body>
</html>