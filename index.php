<doctype html public "-//W3C//DTD HTML 4.01/EN" "http://www.w3.org/TR/html4/strict.dtd">
<?php session_start(); ?>
<html>
	<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title> Главная </title>
	<link rel="stylesheet" type="text/css" href="index.css">
	<link href="https://fonts.googleapis.com/css?family=Josefin+Sans:300|Poppins:600|Montserrat|Montserrat+Alternates" rel="stylesheet">
	</head>
	<body>
	<div id="content">
			<table border="0">
			<tr><td><div id="wtl">Welcome<br>to the <br>Library</div></td></tr>
			<tr><td><div id="wtl_search" valign="center" align="center">Search <img src="lense.png"></h1></div></td></tr>
			<tr><td><div id="search_div" title="Можно проверить наличие книги, а также заказать найденную книгу даже неавторизованному пользователю"><a href="search.php">Найти книгу</a></div><br>
			<div id="reserve_div" title="Заказ книги с ручным заполнением данных о книге. Неавторизованный пользователь не сможет отслеживать статус заказа"><a href="reserve_book.php">Заказать книгу</a></div></td></tr>
		<?php	
		if ($_SESSION['role']!='admin' and $_SESSION['role']!='librarian' and $_SESSION['role']!='reader') { //проверка того, что в сессию не записана никакая роль, то есть пользователь неавторизован
			echo '<tr ><td title="если пользователь авторизуется, то будут доступны функции для той роли, под которой зашел пользователь. Авторизованный читатель сможет просматривать список своих заказов и следить за их статусом."><br><br><div id="more" >Открыть больше возможностей</div></td></tr>
			<tr><td title="если пользователь авторизуется, то будут доступны функции для той роли, под которой зашел пользователь. Авторизованный читатель сможет просматривать список своих заказов и следить за их статусом."><br><div id="auth"><a href="authorization.php">Войти в систему</a></div><br>
			<div id="reg"><a href="registration.php">Регистрация</a></div></td></tr>
			';} // отображение кнопок авторизации и регистрации для неавторизованного пользователя
			
		elseif ($_SESSION['role']=='admin') { // если в сессии записана роль админа, то будет отображаться приветствие с администратором, а также кнопки "панель админа" и "выйти из аккаунта"
		echo '<tr><td><div id="more"><br>Добро пожаловать, ' . $_SESSION['firstname'] .'</div></td></tr>';
		echo '<tr><td><a href="admin_panel.php"><div id="auth">Панель администратора</div></a>
		<br>
		<form action="deauthorization.php">
		<input id="deauth" type="submit" value="Выйти из учетной записи">
		</form></td></tr>';}
		
		elseif ($_SESSION['role']=='librarian') { // если в сессии записана роль библиотекаря, то будет отображаться приветствие с библиотекарем, а также кнопки "панель библиотекаря" и "выйти из аккаунта"
		echo '<tr><td><div id="more"><br>Добро пожаловать, ' . $_SESSION['firstname'] .'</div></td></tr>';
		echo '<tr><td><a href="librarian_panel.php"><div id="auth">Панель библиотекаря</div></a>
		<br>
		<form action="deauthorization.php"> 
		<input id="deauth" type="submit" value="Выйти из учетной записи">
		</form></td></tr>';}
		
		elseif ($_SESSION['role']=='reader') { // если в сессии записана роль читателя, то будет отображаться приветствие с читателем, а также кнопки "панель читателя" и "выйти из аккаунта"
		echo '	<tr><td><div id="more"><br>Добро пожаловать, ' . $_SESSION['firstname'] .'</div></td></tr>';
		echo ' <tr><td>
		<a href="reader_panel.php"><div id="auth">Панель читателя</div></a>
		<br>
		<form action="deauthorization.php">
		<input id="deauth" type="submit" value="Выйти из учетной записи">
		</form></td></tr>';}	
		echo '</table>';
			?>
	</div>
	</body>
</html>