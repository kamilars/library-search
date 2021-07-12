<?php 
session_start();
?>
<html>
 <head>

  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>Поиск книги</title>
  <link rel="stylesheet" type="text/css" href="search.css">
   <link href="https://fonts.googleapis.com/css?family=Josefin+Sans:300|Poppins:600|Montserrat|Montserrat+Alternates" rel="stylesheet">
 </head>
 <body>
 <div id="header">
<?php
if ($_SESSION['role']=='librarian'){
echo "<div id='header_left'>Добро пожаловать, " . $_SESSION['firstname'].'</div><div id="header_right">
<form id="deauthd" action="deauthorization.php">
<input type="submit" id="deauth" value="Выйти из учетной записи">
</form></div></div>'; //приветствие
echo '<div id="search_line">	
	
	<div id="forma"><form name="search_by_keywords"  action="actions_with_books.php" method="post">
		<div id="search_inp"><input id="inp_search" type="text" class="search_inp" placeholder="Введите слова через пробел" title="Для поиска книги нужно вводить слова через пробел, Например, «Ielts English Английский язык». 
Порядок введения и регистр слов не имеет значения. 
Если вы не нашли нужную Вам книгу, проверьте правильность ввода или попробуйте убрать окончания в некоторых словах. 
Можно вводить такие данные как название, автор, язык книги (на русском языке, например, не «қазақ», а «казахский», либо оба варианта), тип («подготовка к экзаменам», «решебник» и т.д.), жанр или предмет, а также ключевые слова" name="keywords" /> 
</div>	<input type="submit" name="search_by_keywords" id="deauth" value="Искать"/>
	</form> </div>
	<div id="forma1">
	<form name="show_all_books"  action="actions_with_books.php" method="post">
	<input type="submit" name="show_all_books" id="deauth" value="Показать все">
	</form> 
	</div>
	<div id="forma2">	
	<form action="add_book.php">
	<input type="submit" id="deauth" name="add_book" value="Добавить новую книгу"></form>
</div>	</div>';?>

<?php	/*Поиск по ключевым словам*/
	function search($keywords) {
		$keywords = htmlspecialchars($keywords);
		if ($keywords === "") return false;
		$where_keywords = "";
		
		$arraywords = explode (" ", $keywords);
		foreach ($arraywords as $key => $value_keyword) {
			if (isset($arraywords[$key - 1]))
			$where_keywords .= ' OR';
			$where_keywords .= '`book_name` LIKE "%'.$value_keyword.'%" OR `book_code` LIKE "%'.$value_keyword.'%" OR `book_author` LIKE "%'.$value_keyword.'%" OR `book_language` LIKE "%'.$value_keyword.'%"';
		}
		
		$query_keywords = "SELECT * FROM lib_books WHERE $where_keywords";
		require_once 'connection.php';
		$link=new mysqli($host, $user, $password, $database);
		$result_set = $link->query($query_keywords);	
		$link->close();
		
		$i = 0;
		while ($row = $result_set->fetch_assoc()) {
			$results_keywords[$i] = $row;
			$i++;
		}
		return $results_keywords;
		
	}
if (isset($_POST['search_by_keywords']))	{
	$keywords = $_POST['keywords'];
	$results_keywords = search($keywords);
}


?>
<div id="search_results">
<?php
if (isset($_POST['search_by_keywords'])) {
	echo "<h2> Результаты поиска </h2>";	
	if ($results_keywords === false) {
		echo "<font color='white'>Вы задали пустой запрос";
	}
	else{
	$rows=count($results_keywords);
	echo "<font color='#BCFFBE'> Всего найденных книг: $rows</font><br>";
	echo '<br>';
	echo '<table>';
	echo '<thead>';
	echo '<tr>';
	echo '<th>Код книги</th>';
	echo '<th>Название книги</th>';
	echo '<th>Автор книги</th>';
	echo '<th>Язык книги</th>';
	echo '<th>Изменить</th>';
	echo '<th>Удалить</th>';
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
	for ($n=0; $n<count($results_keywords); $n++) 
			{	
		echo "<tr>";
		echo "<td>"; echo $results_keywords[$n]["book_code"]."</td>";
		echo "<td>"; echo $results_keywords[$n]["book_name"]."</td>";
		echo "<td>"; echo $results_keywords[$n]["book_author"]."</td>";
		echo "<td>"; echo $results_keywords[$n]["book_language"]."</td>";
		$book_code = $results_keywords[$n]["book_code"];
		$book_name = $results_keywords[$n]["book_name"];
		$book_author = $results_keywords[$n]["book_author"];
		$book_language = $results_keywords[$n]["book_language"];
		echo '<td><form action="edit_book.php" method="post">
		<input type="hidden" value="'.$book_code.'" name="book_code_copy">
		<input type="hidden" value="'.$book_name.'" name="book_name_copy">
		<input type="hidden" value="'.$book_author.'" name="book_author_copy">
		<input type="hidden" value="'.$book_language.'" name="book_language_copy">
		<input type="hidden" value="edit" name="action_with_book">
		<input type="submit" id="btn" name="edit_book" value="Изменить" title="Можно изменить любые данные о книге: код, название, автор, тип, жанр, описание и т.д.">
		</form> </td>';
		echo '<td><form action="actions_with_books.php" method="post">
		<input type="hidden" value="delete" name="action_with_book">
		<input type="hidden" value="'.$book_code.'" name="book_code">
		<input type="submit" name="delete_book" id="btn" value="Удалить">
		</form> </td>';
		echo "</tr>";	
			}
		echo '</table>';	
		echo '<td><br><a href="index.php" id="goout">Перейти на главную</a><br>
<a href="librarian_panel.php" id="goout">Перейти к панели библиотекаря</a>'
		; 
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
	echo '<th>Код книги</th>';
	echo '<th>Название книги</th>';
	echo '<th>Автор книги</th>';
	echo '<th>Язык книги</th>';
	echo '<th>Изменить</th>';
	echo '<th>Удалить</th>';
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
	while ($row=mysqli_fetch_row($result_query_show_all_books))
			{	
		echo "<tr>";
		echo "<td>"; echo $row[0]."</td>";
		echo "<td>"; echo $row[1]."</td>";
		echo "<td>"; echo $row[2]."</td>";
		echo "<td>"; echo $row[3]."</td>";
		$book_code = $row[0];
		$book_name = $row[1];
		$book_author = $row[2];
		$book_language = $row[3];
		echo '<td><form action="edit_book.php" method="post">
		<input type="hidden" value="'.$book_code.'" name="book_code_copy">
		<input type="hidden" value="'.$book_name.'" name="book_name_copy">
		<input type="hidden" value="'.$book_author.'" name="book_author_copy">
		<input type="hidden" value="'.$book_language.'" name="book_language_copy">
		<input type="hidden" value="edit" name="action_with_book">
		<input type="submit" id="btn" name="edit_book" value="Изменить" title="Можно изменить любые данные о книге: код, название, автор, тип, жанр, описание и т.д.">
		</form> </td>';
		echo '<td><form action="actions_with_books.php" method="post">
		<input type="hidden" value="delete" name="action_with_book">
		<input type="hidden" value="'.$book_code.'" name="book_code">
		<input type="submit" name="delete_book" id="btn" value="Удалить">
		</form> </td>';
		echo "</tr>";	
			}
		echo '</table><br><a href="index.php" id="goout">Перейти на главную</a><br>
<a href="librarian_panel.php" id="goout">Перейти к панели библиотекаря</a>' ;	}

	}
if ($_POST['action_with_book']=="delete") {
	/*echo '
	<script type="text/javascript">

if (confirm("Вы уверены?")) {
 var x = "<?php echo "Да"; ?>";
} else {
 var x = "<?php echo "Нет"; ?>";
}
     </script>';*/
	require_once 'connection.php';
	$link=new mysqli($host, $user, $password, $database);
	$query_delete_book="DELETE FROM lib_books WHERE book_code='".$_POST['book_code']."'";
	$result_query_delete_book=mysqli_query($link, $query_delete_book) or die ("Ошибка ".mysqli_error($link)); 
	if ($result_query_delete_book) {header("Refresh");
	mysqli_close($link);}
}
echo '</div>

</div>';
}
elseif ($_SESSION['role']=='reader' or $_SESSION['role']=='admin') {echo 'У вас недостаточно прав для перехода на данную страницу. <br> Вы будете перенаправлены на главную страницу через 3 секунды';
header ("Refresh: 3; index.php");}
else {header ('Location: authorization.php');}
?>

 </body>
</html>