<?php 
session_start();
?>
<html>
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>Заказанные книги</title>
  <link rel="stylesheet" type="text/css" href="css/reserve_book.css">
 </head>
 <body>
<?php
if ($_SESSION['role']=='librarian'){
echo '<div id="header"> Добро пожаловать, ' . $_SESSION['firstname']. '</div>'; //приветствие

echo '<div id="panel">
<form name="reserved_books" action="books_reservations.php" method="post">
<input type="submit" name="show_all_reserved_books" value="Показать все заказы">
<br><br>
<input type="submit" name="show_not_seen_reservations" value="Показать непросмотренные заказы"><br>
<input type="submit" name="show_not_prepared_books" value="Показать неподготовленные заказы"><br>
<input type="submit" name="show_prepared_books" value="Показать подготовленные заказы"><br>
<input type="submit" name="show_not_found_books" value="Показать ненайденные книги">
</form></div><div id="books_reservations">';
/*Показать непросмотренные заказы*/
if(isset($_POST['show_not_seen_reservations'])){
	$query_show_not_seen_reservations= "SELECT * FROM lib_orders WHERE status='not_seen'";
	require_once 'connection.php';
	$link=new mysqli($host, $user, $password, $database);
	$result_query_show_not_seen_reservations=mysqli_query ($link, $query_show_not_seen_reservations) or die ("Ошибка ".mysqli_error($link));
	$rows=mysqli_num_rows($result_query_show_not_seen_reservations);
	if($result_query_show_not_seen_reservations)
	{
	echo "<br><font color='Blue'> Всего непросмотренных заказов: $rows</font>";
	echo '<br>';
	echo '<table border="1">';
	echo '<thead>';
	echo '<tr>';
	echo '<th>Читатель</th>';
	echo '<th>Название книги</th>';
	echo '<th>Автор</th>';
	echo '<th>Язык</th>';
	echo '<th>Желаемое время выдачи</th>';
	echo '<th>Дополнительно</th>';
	echo '<th>Статус</th>';
	echo '<th>Действие</th>';
	echo '<th>Отменить заказ</th>';
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
	
	while ($row=mysqli_fetch_row($result_query_show_not_seen_reservations))
	{	
		echo "<tr>";
		$order_number=$row[0];
		$j=1;
		echo "<td>$row[$j]</td>";
		for ($j=3; $j<8; ++$j) echo "<td>$row[$j]</td>";
		
		if ($row[8]=="not_seen") {
			echo '<td class="not_seen">Не просмотрено</td>';
			echo '<td>
			<form method="post" name="change_status_to_not_prepared" action="books_reservations.php">
			<input type="hidden" value="1" name="x">
			<input type="hidden" value="not_seen" name="status">
			<input type="hidden" value="not_prepared" name="new_status">
			<input type="hidden" value="'.$order_number.'" name="order_number">
			<input type="submit" name="'.$order_number.'" value="Отметить как просмотренное">
			</form>
			</td>';
			}
					echo '<td><form method="post" name="delete_reservation" action="books_reservations.php">
			<input type="hidden" value="2" name="x">
			<input type="hidden" value="'.$order_number.'" name="order_number">
			<input type="submit" name="'.$order_number.'" value="Отменить заказ">
			</form></td>';	
			echo "</tr>";
	}
	echo '</table>';
	}
}


/*Показать неподготовленные заказы*/
elseif(isset($_POST['show_not_prepared_books'])){
	$query_show_not_prepared_books= "SELECT * FROM lib_orders WHERE status='not_prepared'";
	require_once 'connection.php';
	$link=new mysqli($host, $user, $password, $database);
	$result_query_show_not_prepared_books=mysqli_query ($link, $query_show_not_prepared_books) or die ("Ошибка ".mysqli_error($link));
	$rows=mysqli_num_rows($result_query_show_not_prepared_books);
	if($result_query_show_not_prepared_books)
	{
	echo "<br><font color='Blue'> Всего неподготовленных заказов: $rows</font>";
	echo '<br>';
	echo '<table border="1">';
	echo '<thead>';
	echo '<tr>';
	echo '<th>Читатель</th>';
	echo '<th>Название книги</th>';
	echo '<th>Автор</th>';
	echo '<th>Язык</th>';
	echo '<th>Желаемое время выдачи</th>';
	echo '<th>Дополнительно</th>';
	echo '<th>Статус</th>';
	echo '<th>Действие</th>';
	echo '<th>Книга не найдена</th>';
	echo '<th>Отменить заказ</th>';
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
	
	while ($row=mysqli_fetch_row($result_query_show_not_prepared_books))
	{	
		echo "<tr>";
		$order_number=$row[0];
		$j=1;
		echo "<td>$row[$j]</td>";

		for ($j=3; $j<8; ++$j) echo "<td>$row[$j]</td>";
		
		if ($row[8]=="not_prepared") {
			echo '<td class="not_prepared">Не подготовлен</td>';
			echo '<td><form method="post" name="change_status_to_prepared" action="books_reservations.php">
			<input type="hidden" value="1" name="x">
			<input type="hidden" value="not_prepared" name="status">
			<input type="hidden" value="prepared" name="new_status">
			<input type="hidden" value="'.$order_number.'" name="order_number">
			<input type="submit" name="'.$order_number.'" value="Отметить как подготовленное">
			</form></td>
			<td><form method="post" name="change_status_to_not_found" action="books_reservations.php">
			<input type="submit" name="change_status_to_not_found" value="Книга не найдена">
			<input type="hidden" value="1" name="x">
			<input type="hidden" value="not_prepared" name="status">
			<input type="hidden" value="not_found" name="new_status">
			<input type="hidden" value="'.$order_number.'" name="order_number">
			</form></td>';
			}
				echo '<td><form method="post" name="delete_reservation" action="books_reservations.php">
			<input type="hidden" value="2" name="x">
			<input type="hidden" value="'.$order_number.'" name="order_number">
			<input type="submit" name="'.$order_number.'" value="Отменить заказ">
			</form></td>';		
			echo "</tr>";
	}
	echo '</table>';
	}
}		

/*Показать подготовленные заказы*/
elseif(isset($_POST['show_prepared_books'])) {
	$query_show_prepared_books= "SELECT * FROM lib_orders WHERE status='prepared'";
	require_once 'connection.php';
	$link=new mysqli($host, $user, $password, $database);
	$result_query_show_prepared_books=mysqli_query ($link, $query_show_prepared_books) or die ("Ошибка ".mysqli_error($link));
	$rows=mysqli_num_rows($result_query_show_prepared_books);
	if($result_query_show_prepared_books)
	{
	echo "<br><font color='Blue'> Всего подготовленных заказов: $rows</font>";
	echo '<br>';
	echo '<table border="1">';
	echo '<thead>';
	echo '<tr>';
	echo '<th>Читатель</th>';
	echo '<th>Название книги</th>';
	echo '<th>Автор</th>';
	echo '<th>Язык</th>';
	echo '<th>Желаемое время выдачи</th>';
	echo '<th>Дополнительно</th>';
	echo '<th>Статус</th>';
	echo '<th>Действие</th>';
	echo '<th>Отменить заказ</th>';
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
	
	while ($row=mysqli_fetch_row($result_query_show_prepared_books))
	{	
		echo "<tr>";
		$order_number=$row[0];
		$j=1;
		echo "<td>$row[$j]</td>";
		for ($j=3; $j<8; ++$j) echo "<td>$row[$j]</td>";
		
		if ($row[8]=="prepared") {
			echo '<td class="prepared">Готово к выдаче</td>';
			echo '<td><form method="post" name="delete_reservation" action="books_reservations.php">
			<input type="hidden" value="2" name="y">
			<input type="hidden" value="prepared" name="status">
			<input type="hidden" value="'.$order_number.'" name="order_number">
			<input type="submit" name="'.$order_number.'" value="Отметить, что читатель забрал книгу">
			</form></td>';
			}	
		echo '<td><form method="post" name="delete_reservation" action="books_reservations.php">
		<input type="hidden" value="2" name="x">
		<input type="hidden" value="'.$order_number.'" name="order_number">
		<input type="submit" name="'.$order_number.'" value="Отменить заказ">
		</form></td>';		
		echo "</tr>";
	}
	echo '</table>';
	}
}

/*Показать ненайденные заказы*/
elseif(isset($_POST['show_not_found_books'])) {
	$query_show_not_found_books= "SELECT * FROM lib_orders WHERE status='not_found'";
	require_once 'connection.php';
	$link=new mysqli($host, $user, $password, $database);
	$result_query_show_not_found_books=mysqli_query ($link, $query_show_not_found_books) or die ("Ошибка ".mysqli_error($link));
	$rows=mysqli_num_rows($result_query_show_not_found_books);
	if($result_query_show_not_found_books)
	{
	echo "<br><font color='Blue'> Всего ненайденных заказов: $rows</font>";
	echo '<br>';
	echo '<table border="1">';
	echo '<thead>';
	echo '<tr>';
	echo '<th>Читатель</th>';
	echo '<th>Название книги</th>';
	echo '<th>Автор</th>';
	echo '<th>Язык</th>';
	echo '<th>Желаемое время выдачи</th>';
	echo '<th>Дополнительно</th>';
	echo '<th>Статус</th>';
	echo '<th>Действие</th>';
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
	
	while ($row=mysqli_fetch_row($result_query_show_not_found_books))
	{	
		echo "<tr>";
		$order_number=$row[0];
		$j=1;
		echo "<td>$row[$j]</td>";
		for ($j=3; $j<8; ++$j) echo "<td>$row[$j]</td>";
		
		if ($row[8]=="not_found") echo '<td class="not_found">Книга не найдена</td>';
		echo '<td><form method="post" name="delete_reservation" action="books_reservations.php">
		<input type="hidden" value="2" name="x">
		<input type="hidden" value="'.$order_number.'" name="order_number">
		<input type="submit" name="'.$order_number.'" value="Отменить заказ">
		</form></td>';		
		echo "</tr>";
	}
	echo '</table>';
	}
}

/*Показать все книги*/
else {$query_show_all_reserved_books= "SELECT * FROM lib_orders ORDER BY reservation_datetime";
	require_once 'connection.php';
	$link=new mysqli($host, $user, $password, $database);
	$result_query_show_all_reserved_books=mysqli_query ($link, $query_show_all_reserved_books) or die ("Ошибка ".mysqli_error($link));
	$rows=mysqli_num_rows($result_query_show_all_reserved_books);
		if($result_query_show_all_reserved_books)
	{
	echo "<br><font color='Blue'> Всего заказано книг: $rows</font>";
	echo '<br>';
	echo '<table border="1">';
	echo '<thead>';
	echo '<tr>';
	echo '<th>Читатель</th>';
	echo '<th>Название книги</th>';
	echo '<th>Автор</th>';
	echo '<th>Язык</th>';
	echo '<th>Желаемое время выдачи</th>';
	echo '<th>Дополнительно</th>';
	echo '<th>Статус</th>';
	echo '<th>Действие</th>';
	echo '<th>Книга не найдена</th>';
	echo '<th>Отменить заказ</th>';
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
	
	while ($row=mysqli_fetch_row($result_query_show_all_reserved_books))
	{	
		echo "<tr>";
		$order_number=$row[0];
		$j=1;
		echo "<td>$row[$j]</td>";
		for ($j=3; $j<8; ++$j) echo "<td>$row[$j]</td>";
		
		if ($row[8]=="not_seen") {
			echo '<td class="not_seen">Не просмотрено</td>';
			echo '<td><form method="post" name="change_status_to_not_prepared" action="books_reservations.php">
			<input type="hidden" value="1" name="x">
			<input type="hidden" value="not_seen" name="status">
			<input type="hidden" value="not_prepared" name="new_status">
			<input type="hidden" value="'.$order_number.'" name="order_number">
			<input type="submit" name="'.$order_number.'" value="Отметить как просмотренное">
			</form></td><td></td>';
			}
		elseif ($row[8]=="not_prepared") {
			echo '<td class="not_prepared">Подготавливается</td>';
			echo '<td><form method="post" name="change_status_to_prepared" action="books_reservations.php">
			<input type="hidden" value="not_prepared" name="status">
			<input type="hidden" value="prepared" name="new_status">
			<input type="hidden" value="1" name="x">
			<input type="hidden" value="'.$order_number.'" name="order_number">
			<input type="submit" name="'.$order_number.'" value="Отметить как подготовленное">
			</form></td>
			<td><form method="post" name="change_status_to_not_found" action="books_reservations.php">
			<input type="submit" name="change_status_to_not_found" value="Книга не найдена">
			<input type="hidden" value="1" name="x">
			<input type="hidden" value="not_prepared" name="status">
			<input type="hidden" value="not_found" name="new_status">
			<input type="hidden" value="'.$order_number.'" name="order_number">
			</form></td>';
			}
		elseif ($row[8]=="prepared") {
			echo '<td class="prepared">Готово к выдаче</td>';
			echo '<td><form method="post" name="delete_reservation" action="books_reservations.php">
			<input type="hidden" value="2" name="x">
			<input type="hidden" value="prepared" name="status">
			<input type="hidden" value="'.$order_number.'" name="order_number">
			<input type="submit" name="'.$order_number.'" value="Отметить, что читатель забрал книгу">
			</form></td>
			<td></td>';
			}	
		elseif ($row[8]=="not_found") {
			echo '<td class="not_found">Книга не найдена</td>
			<td></td>
			<td></td>';
			}		
		echo '<td><form method="post" name="delete_reservation" action="books_reservations.php">
			<input type="hidden" value="2" name="x">
			<input type="hidden" value="'.$order_number.'" name="order_number">
			<input type="submit" name="'.$order_number.'" value="Отменить заказ">
			</form></td>';	
			echo "</tr>";
	}
	echo '</table>';
	}}

if ($_POST['x']==1) {
	require_once 'connection.php';
	$link=new mysqli($host, $user, $password, $database);
	$query_change_status="UPDATE lib_orders SET status='".$_POST['new_status']."' WHERE order_number='".$_POST['order_number']."' AND status='".$_POST['status']."'";
	$result_query_change_status=mysqli_query($link, $query_change_status) or die ("Ошибка ".mysqli_error($link));
	if($result_query_change_status) {header("Refresh:5;books_reservations.php");
	mysqli_close($link);}}
elseif ($_POST['x']==2) {
	require_once 'connection.php';
	$link=new mysqli($host, $user, $password, $database);
	$query_delete_reservation="DELETE FROM lib_orders WHERE order_number='".$_POST['order_number']."'";
	$result_query_delete_reservation=mysqli_query($link, $query_delete_reservation) or die ("Ошибка ".mysqli_error($link)); 
	if ($result_query_delete_reservation) {header("Refresh:5;books_reservations.php");
	mysqli_close($link);}}
			?> 
		
	<style>
   .not_seen {
	background:red;
	}
	.not_prepared
	{
	background: orange;
	}
	.prepared
	{
	background: green;
	}
	</style>
<?php
echo '</div>
<a href="index.php">Перейти на главную</a><br>
<a href="librarian_panel.php">Перейти к панели библиотекаря</a><br><br>';}
elseif ($_SESSION['role']=='reader' or $_SESSION['role']=='admin') {echo 'У вас недостаточно прав для перехода на данную страницу. <br> Вы будете перенаправлены на главную страницу через 3 секунды';
header ("Refresh: 3; index.php");}
else {header ('Location: authorization.php');} ?>
</body>
</html>