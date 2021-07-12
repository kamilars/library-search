<html>
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>Поиск книги</title>
  <link rel="stylesheet" type="text/css" href="css/search.css">
 </head>
 <body>
 <div id="header">

</div>
<div>
 <div id="search_parameters">	
<?php/*	<form name="search_by_parameters" action="search_not_authorized.php" method="post">
		<fieldset>
		<h2>Параметры поиска</h2><br>
		<input type="checkbox" name="fields_not_required" value="fields_not_required"/>Я не уверен в том, что поля должны быть заполнены именно так (мог ошибиться в правописании/ не помню точно)
		<h4>Название:</h4>
		<input type="text" name="book_name" /> <br>
		<h4>Автор:</h4>
		<input type="text" name="book_author" /> <br>		
		<br><h4>Язык книги:</h4>
		<select name="book_language"> 
		<option value="Русский">Русский</option>
		<option value="Казахский">Казахский</option>
		<option value="Английский">Английский</option>
		<option value="Немецкий">Немецкий</option>
		<option value="Китайский">Китайский</option>
		</select>
		<br><br>
		<input type="submit" name="search_by_parameters" value="Искать" />
		</fieldset>
	</form>	*/?>
	
	<form name="search_by_keywords"  action="search_not_authorized.php" method="post">
		<fieldset>
		<legend>Поиск по ключевым словам</legend>
		<input type="text" placeholder="Введите слова через пробел. Например: IELTS Гарри Поттер роман" name="keywords" /> 
		<br>
		<input type="submit" name="search_by_keywords" value="Искать"/>
		</fieldset>
	</form>

	</form>
</div>	
<?php	/*
if(isset($_POST['search_by_parameters']))
			{
		require_once 'connection.php';
		$link=mysqli_connect($host, $user, $password, $database) or die("Ошибка".mysqli_error($link));
		$query="Select * from lib_books WHERE login='".$_POST['log']."'
		AND password='".$_POST['pass']."'";
		$result = mysqli_query ($link, $query) or die("Ошибка". mysqli_error($link));
		$rows = mysqli_num_rows($result); 
		if ($rows == 1)
		{


		function search_book_name($book_name) {
		$book_name = htmlspecialchars($book_name);
		if ($book_name === "") return false;
		$where_book_name = "";
		
		$array_book_name = explode (" ", $book_name);
		foreach ($array_book_name as $bn => $value_bn) {
			if (isset($array_book_name[$bn - 1]))
			$where_book_name .= ' OR';
			$where_book_name .= '`book_name` LIKE "%'.$value_bn.'%" ';
		}
		
		$query_book_name = "SELECT * FROM lib_books WHERE $where_book_name";
		require_once 'connection.php';
		$link=new mysqli($host, $user, $password, $database);
		$result_set = $link->query($query_book_name);
		$link->close();
		
		$i = 0;
		while ($row = $result_set->fetch_assoc()) {
			$results[$i] = $row;
			$i++;
		}
		return $results;
		echo $query_book_name;
	}
if (isset($_POST['search_by_parameters']))	{
	$book_name = $_POST['book_name'];
	$results = search_book_name($book_name);
}*/
?>


<?php	/*Поиск по ключевым словам*/
	function search($keywords) {
		$keywords = htmlspecialchars($keywords);
		if ($keywords === "") return false;
		$where_keywords = "";
		
		$arraywords = explode (" ", $keywords);
		foreach ($arraywords as $key => $value_keyword) {
			if (isset($arraywords[$key - 1]))
			$where_keywords .= ' OR';
			$where_keywords .= '`book_name` LIKE "%'.$value_keyword.'%" OR `book_author` LIKE "%'.$value_keyword.'%" OR `book_language` LIKE "%'.$value_keyword.'%" OR `book_type` LIKE "%'.$value_keyword.'%" OR `book_genre` LIKE "%'.$value_keyword.'%" OR `book_description` LIKE "%'.$value_keyword.'%"';
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

  /*function addWhere($where, $add, $and=true) {
    if ($where) {
      if ($and) $where .= " AND $add";
      else $where .= " OR $add";
    }
    else $where = $add;
    return $where;
  }
  if (!empty($_POST["search_by_keywords"])) {
    $where = "";
    if ($_POST["book_name_required"]) $where = addWhere($where, "`book_name` = '".htmlspecialchars($_POST["book_name"]))."'";
	if ($_POST["book_author_required"]) $where = addWhere($where, "`book_author` = '".htmlspecialchars($_POST["book_author"]))."'";
	if ($_POST["book_language_required"]) $where = addWhere($where, "`book_language` = '".htmlspecialchars($_POST["book_language"]))."'";
    $sql = "SELECT * FROM `lib_books`";
    if ($where) $sql .= " WHERE $where";
    echo $sql;
  }*/
?>
<div id="search_results">
<?php
if (isset($_POST['search_by_keywords'])) {
	echo "<h2> Результаты поиска </h2>";	
	if ($results_keywords === false) echo "Вы задали пустой запрос";
	
	/*if (count($results_keywords)==0) echo "Ничего не найдено";
	if (count($results_keywords)!=0)*/
	else{
	$rows=count($results_keywords);
	echo "<font color='Blue'> Всего найденных книг: $rows</font><br>";
	echo '<br>';
	echo '<table bordercolor="Violet" border="1" bgcolor="pink">';
	echo '<thead>';
	echo '<tr>';
	echo '<th>Название книги</th>';
	echo '<th>Автор книги</th>';
	echo '<th>Язык книги</th>';
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
	for ($n=0; $n<count($results_keywords); $n++) 
			{
		
		echo "<tr>";
		echo "<td>"; echo $results_keywords[$n]["book_name"]."</td>";
		echo "<td>"; echo $results_keywords[$n]["book_author"]."</td>";
		echo "<td>"; echo $results_keywords[$n]["book_language"]."</td>";
		echo "</tr>";
	}
	echo '</table>';
	
/*	echo $results_keywords[$i]["book_name"]."<br />";*/
	}
}

/*
else	{if (isset($_POST['search_by_parameters'])) {
	echo "<h2> Результаты поиска </h2>";
	if ($results === false) echo "Вы задали пустой запрос";
	if (count($results)==0) echo "Ничего не найдено";
	else
		for ($i=0; $i<count($results_keywords); $i++)
		echo $results[$i]["book_name"]."<br />";
		echo $query_book_name;
}}*/
?>
<a href="authorization.php">Авторизоваться</a>
<a href="index.php">Перейти на главную</a>
</div>
 </body>
</html>