<?php 
session_start();
?>
<html>
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Поиск книги</title>
  <link rel="stylesheet" type="text/css" href="search.css">
   <link href="https://fonts.googleapis.com/css?family=Josefin+Sans:300|Poppins:600|Montserrat|Montserrat+Alternates" rel="stylesheet">
 </head>
 <body>
 
 <div id="header_inp"><div id="header">
<?php
if (($_SESSION['role']=='admin') or ($_SESSION['role']=='librarian') or ($_SESSION['role']=='reader')){ //проверка, под какой ролью зашел пользователь
echo '<div id="header_left">Добро пожаловать, ' . $_SESSION['firstname']; //приветствие
echo '
</div>
<div id="header_right">
<form id="deauthd" action="deauthorization.php">
<input type="submit" id="deauth" value="Выйти из учетной записи">
</form>';}
else {
	echo '<div id="not_auth">Вы не авторизованы. Если вы закажете книгу, вы не сможете отслеживать статус подготовки.</div>';
	echo '<div id="header_right_na"><a href="authorization.php" id="auth">Войти в систему</a>
		<a href="registration.php" id="reg">Регистрация</a>';
}


echo ' </div></div>
<div id="search_line">	
	
	<div id="forma"><form name="search_by_keywords"  action="search.php" method="post">
		<div id="search_inp"><input id="inp_search" type="text" class="search_inp" placeholder="Введите слова через пробел" title="Для поиска книги нужно вводить слова через пробел, Например, «Ielts English Английский язык». 
Порядок введения и регистр слов не имеет значения. 
Если вы не нашли нужную Вам книгу, проверьте правильность ввода или попробуйте убрать окончания в некоторых словах. 
Можно вводить такие данные как название, автор, язык книги (на русском языке, например, не «қазақ», а «казахский», либо оба варианта)" name="keywords" /> 
</div>	<input type="submit" name="search_by_keywords" id="deauth" value="Искать"/>
	</form> </div>
	<div id="forma1">
	<form name="show_all_books"  action="search.php" method="post">
	<input type="submit" name="show_all_books" id="deauth" value="Показать все">
	</form> 
	</div>
	<div class="not_found1"><div id="not_found_text">Не нашли, что хотели?</div><div id="not_found_button"><a href="reserve_book.php" title="После нажатия на эту кнопку можно вручную записать данные о книге. Возможно, книга найдется библиотекарем" >Перейти к заказу</a></div></div></div></div>';

	/*Поиск по ключевым словам*/
	function search($keywords) { //начало функции search
		$keywords = htmlspecialchars($keywords); //преобразование спец-символов в html сущности
		if ($keywords === "") return false; //возвращаем ложь, если введено пустое значение
		$where_keywords = "";
		
		$arraywords = explode (" ", $keywords); // разделить введенные через пробел слова в массив, каждый элемент которого содержит по одному слову
		foreach ($arraywords as $key => $value_keyword) { //перебираются слова в массиве
			if (isset($arraywords[$key - 1])) 
			$where_keywords .= ' OR'; //формирование запроса через ИЛИ
			$where_keywords .= '`book_name` LIKE "%'.$value_keyword.'%" OR `book_author` LIKE "%'.$value_keyword.'%" OR `book_language` LIKE "%'.$value_keyword.'%"'; //формирование части запроса на содержание ключевых слов в строках
		}
		
		$query_keywords = "SELECT * FROM lib_books WHERE $where_keywords"; //формирование полного запроса на выборку из таблицы, где содержатся ключевые слова
		require_once 'connection.php'; //подключение к скрипту, в котором содержатся данные для подключения к БД
		$link=new mysqli($host, $user, $password, $database);//подключение к бд
		$result_set = $link->query($query_keywords); //отправка запроса
		$link->close(); //закрытие под
		
		$i = 0;
		while ($row = $result_set->fetch_assoc()) { //перебор из выборки БД пока есть результат
			$results_keywords[$i] = $row; //присвоение элементу массива с результатами значение найденной строки
			$i++;
		}
		return $results_keywords;
		
	}
if (isset($_POST['search_by_keywords']))	{
	$keywords = $_POST['keywords'];
	$results_keywords = search($keywords); //присвоить значение функции search переменной results_keywords 
}

echo '<div id="search_results">';

if (isset($_POST['search_by_keywords'])) {
	echo "<h2> Результаты поиска </h2>";	
	if ($results_keywords === false) { //проверка, было ли возвращено false, то есть был ли задан пустой запрос
		echo '<font color="#BCFFBE">Вы задали пустой запрос</font>';
	}
	else{
	$rows=count($results_keywords);
	echo "<font color='#BCFFBE'> Всего найденных книг: $rows</font><br>";
	echo '<br>';
	echo '<table >';
	echo '<thead>';
	echo '<tr>';
	echo '<th>Название книги</th>';
	echo '<th>Автор книги</th>';
	echo '<th>Язык книги</th>';
	echo '<th>Действие</th>';
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
	for ($n=0; $n<count($results_keywords); $n++) 
			{	
		echo "<tr>";
		echo "<td>"; echo $results_keywords[$n]["book_name"]."</td>";
		echo "<td>"; echo $results_keywords[$n]["book_author"]."</td>";
		echo "<td>"; echo $results_keywords[$n]["book_language"]."</td>";
		$book_name = $results_keywords[$n]["book_name"];
		$book_author = $results_keywords[$n]["book_author"];
		$book_language = $results_keywords[$n]["book_language"];
		echo '<td><form action="reserve_book.php" method="post">
		<input type="hidden" value="'.$book_name.'" name="book_name_copy">
		<input type="hidden" value="'.$book_author.'" name="book_author_copy">
		<input type="hidden" value="'.$book_language.'" name="book_language_copy">
		<input type="submit" name="reserve_book_btn" id="btn" value="Заказать">
		</form> </td>';
		echo "</tr>";	
			}
		echo '</table>';	

	}
}
elseif (isset($_POST['show_all_books'])) {
	$query_show_all_books= "SELECT * FROM lib_books order by book_name";
	require_once 'connection.php';
	$link=new mysqli($host, $user, $password, $database);
	$result_query_show_all_books=mysqli_query ($link, $query_show_all_books) or die ("Ошибка ".mysqli_error($link));
	$rows=mysqli_num_rows($result_query_show_all_books);
		if($result_query_show_all_books){
	echo "<h2> Все книги </h2>";	
	echo "<font color='#BCFFBE'> Всего найденных книг: $rows</font><br>";
	echo '<br>';
	echo '<table >';
	echo '<thead>';
	echo '<tr>';
	echo '<th>Название книги</th>';
	echo '<th>Автор книги</th>';
	echo '<th>Язык книги</th>';
	echo '<th>Действие</th>';
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
	while ($row=mysqli_fetch_row($result_query_show_all_books))
			{	
		echo "<tr>";
		echo "<td>"; echo $row[1]."</td>";
		echo "<td>"; echo $row[2]."</td>";
		echo "<td>"; echo $row[3]."</td>";
		$book_name = $row[1];
		$book_author = $row[2];
		$book_language = $row[3];
		echo '<td><form action="reserve_book.php" method="post">
		<input type="hidden" value="'.$book_name.'" name="book_name_copy">
		<input type="hidden" value="'.$book_author.'" name="book_author_copy">
		<input type="hidden" value="'.$book_language.'" name="book_language_copy">
		<input type="submit" name="reserve_book_btn" id="btn" value="Заказать">
		</form> </td>';
		echo "</tr>";	
			}
		echo '</table>';	}

	}

echo '<br>';
		if ($_SESSION['role']=='admin') {
		echo '<a id="goout" href="admin_panel.php">Перейти к панели администратора</a><br>';}
		
		elseif ($_SESSION['role']=='librarian') {
		echo '<a id="goout" href="librarian_panel.php">Перейти к панели библиотекаря</a><br>';}
		
		elseif ($_SESSION['role']=='reader') {
		echo '<a id="goout" href="reader_panel.php">Перейти к панели читателя</a><br>';}
		echo '<a id="goout" href="index.php">Перейти на главную</a></div><br></div>';
?>

 </body>
</html>