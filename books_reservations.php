<?php 
session_start();
?>
<html>
 <head>
 <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>Заказанные книги</title>
  <link rel="stylesheet" type="text/css" href="books_reservations.css">
  <link href="https://fonts.googleapis.com/css?family=Josefin+Sans:300|Poppins:600|Montserrat|Montserrat+Alternates" rel="stylesheet">
 </head>
 <body>
<?php
$arr = [
  'январь',
  'февраль',
  'март',
  'апрель',
  'май',
  'июнь',
  'июль',
  'август',
  'сентябрь',
  'октябрь',
  'ноябрь',
  'декабрь'
];
if ($_SESSION['role']=='librarian'){
echo '<div id="header"> <div id="header_left">Добро пожаловать, ' . $_SESSION['firstname']. '</div><div id="header_right"><form action="deauthorization.php">
<input id="deauth" type="submit" value="Выйти из учетной записи">
</form><a id="btn" href="index.php">Перейти на главную</a>
<a id="btn" href="librarian_panel.php">Перейти к панели библиотекаря</a></div> </div><br>'; //приветствие

echo '<div id="panel">
<br>
<form name="reserved_books" action="books_reservations.php" method="post">
<input type="submit" name="show_all_reserved_books" value="Показать все заказы" id="btn">
<input type="submit" name="show_not_seen_reservations" value="Показать непросмотренные заказы" id="btn">
<input type="submit" name="show_not_prepared_books" value="Показать неподготовленные заказы" id="btn">
<input type="submit" name="show_prepared_books" value="Показать подготовленные заказы" id="btn">
<input type="submit" name="show_not_found_books" value="Показать не найденные книги" id="btn">
</form></div><br><div id="books_reservations">';
/*Показать непросмотренные заказы*/
if(isset($_POST['show_not_seen_reservations'])){
	$query_show_not_seen_reservations= "SELECT * FROM lib_orders WHERE status='not_seen'";
	require_once 'connection.php';
	$link=new mysqli($host, $user, $password, $database);
	$result_query_show_not_seen_reservations=mysqli_query ($link, $query_show_not_seen_reservations) or die ("Ошибка ".mysqli_error($link));
	$rows=mysqli_num_rows($result_query_show_not_seen_reservations);
	if($result_query_show_not_seen_reservations)
	{
	echo '<br><div id="counter">Всего непросмотренных заказов: <b>'.$rows. '</b></div>';
	echo '<br>';	
	while ($row=mysqli_fetch_row($result_query_show_not_seen_reservations))
	{	$order_number=$row[0];
		echo '<table><tr><td rowspan="4" id="status" class="not_seen"><b>Статус:</b> не просмотрено</td>';
		echo '<td rowspan="2" id="datetime"><b>Дата:</b><br>';
		$date = DateTime::createFromFormat('Y-m-d', $row[6]);
		echo $date->format('d F Y');;
		echo '</td><td id="book_name">';
		echo $row[3];
		echo '</td>';
		echo '<td id="action"><form method="post" name="change_status_to_not_prepared" action="books_reservations.php">
			<input type="hidden" value="1" name="x">
			<input type="hidden" value="not_seen" name="status">
			<input type="hidden" value="not_prepared" name="new_status">
			<input type="hidden" value="'.$order_number.'" name="order_number">
			<input type="submit" name="'.$order_number.'" value="Отметить как просмотренное" class="btn">
			</form></td>';
		echo '</tr><tr><td id="author"><b>Автор: </b>'.$row[4].'</td><td></td></tr><tr><td rowspan="2" id="datetime"><b>Время:</b><br>';
	$time = DateTime::createFromFormat('H:i:s', $row[7]);
		echo $time->format('H:i');
	echo '</td><td colspan="2"><b>Язык:</b> ';
	echo $row[5];
	echo '</td></tr><tr><td id="reader"><b>Читатель:</b> ';
	echo $row[1];
	echo '</td><td id="action"><form method="post" name="delete_reservation" action="books_reservations.php">
			<input type="hidden" value="2" name="x">
			<input type="hidden" value="'.$order_number.'" name="order_number">
			<input type="submit" name="'.$order_number.'" value="Отменить заказ" class="btn">
			</form></td></tr>
	<tr><td colspan="4" id="additional"><b>Дополнительно:</b> ';
	echo $row[8];
	echo '</td></tr></table>';
	}
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
	echo '<br> <div id="counter">Всего неподготовленных заказов:'. $rows.'</div>';
	echo '<br>';
	while ($row=mysqli_fetch_row($result_query_show_not_prepared_books))
	{	$order_number=$row[0];
		echo '<table><tr><td rowspan="4" id="status" class="not_prepared"><b>Статус:</b><br> на стадии подготовки</td>';
		echo '<td rowspan="2" id="datetime"><b>Дата:</b><br>';
		$date = DateTime::createFromFormat('Y-m-d', $row[6]);
		echo $date->format('d F Y');
		echo '</td><td id="book_name">';
		echo $row[3];
		echo '</td>';
		echo '<td id="action"><form method="post" name="change_status_to_prepared" action="books_reservations.php">
			<input type="hidden" value="1" name="x">
			<input type="hidden" value="not_prepared" name="status">
			<input type="hidden" value="prepared" name="new_status">
			<input type="hidden" value="'.$order_number.'" name="order_number">
			<input type="submit" name="'.$order_number.'" value="Отметить как подготовленное" class="btn">
			</form></td>';
		echo '<tr><td id="author"><b>Автор:</b> '.$row[4].'</td><td id="action"><form method="post" name="change_status_to_not_found" action="books_reservations.php">
			<input type="submit" name="change_status_to_not_found" value="Книга не найдена" class="btn">
			<input type="hidden" value="1" name="x">
			<input type="hidden" value="not_prepared" name="status">
			<input type="hidden" value="not_found" name="new_status">
			<input type="hidden" value="'.$order_number.'" name="order_number">
			</form></td></tr><tr><td rowspan="2" id="datetime"><b>Время:</b><br>';
	$time = DateTime::createFromFormat('H:i:s', $row[7]);
		echo $time->format('H:i');
	echo '</td><td colspan="2"><b>Язык:</b> ';
	echo $row[5];
	echo '</td></tr><tr><td id="reader"><b>Читатель:</b> ';
	echo $row[1];
	echo '</td><td id="action"><form method="post" name="delete_reservation" action="books_reservations.php">
			<input type="hidden" value="2" name="x">
			<input type="hidden" value="'.$order_number.'" name="order_number">
			<input type="submit" name="'.$order_number.'" value="Отменить заказ" class="btn">
			</form></td></tr>
	<tr><td colspan="4" id="additional"><b>Дополнительно:</b> ';
	echo $row[8];
	echo '</td></tr></table>';
	}
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
	echo '<br><div id="counter"> Всего подготовленных заказов: '.$rows.'</div>';
	while ($row=mysqli_fetch_row($result_query_show_prepared_books))
	{	$order_number=$row[0];
		echo '<table>';
		echo '<tr><td rowspan="4" id="status" class="prepared"><b>Статус:</b> <br>готово</td>';		
		echo '<td rowspan="2" id="datetime"><b>Дата:</b><br>';
		$date = DateTime::createFromFormat('Y-m-d', $row[6]);
		echo $date->format('d F Y');
		echo '</td><td id="book_name">';
		echo $row[3];
		echo '</td>';
		echo '<td id="action"><form method="post" name="delete_reservation" action="books_reservations.php">
			<input type="hidden" value="2" name="x">
			<input type="hidden" value="prepared" name="status">
			<input type="hidden" value="'.$order_number.'" name="order_number">
			<input type="submit" name="'.$order_number.'" value="Отметить, что читатель забрал книгу" class="btn">
			</form></td>';
		echo '<tr><td id="author"><b>Автор:</b> '.$row[4].'</td><td></td></tr><tr><td rowspan="2" id="datetime"><b>Время:</b><br>';
	$time = DateTime::createFromFormat('H:i:s', $row[7]);
		echo $time->format('H:i');
	echo '</td><td colspan="2"><b>Язык:</b> ';
	echo $row[5];
	echo '</td></tr><tr><td id="reader"><b>Читатель:</b> ';
	echo $row[1];
	echo '</td><td id="action"><form method="post" name="delete_reservation" action="books_reservations.php">
			<input type="hidden" value="2" name="x">
			<input type="hidden" value="'.$order_number.'" name="order_number">
			<input type="submit" name="'.$order_number.'" value="Отменить заказ" class="btn">
			</form></td></tr>
	<tr><td colspan="4" id="additional"><b>Дополнительно:</b> ';
	echo $row[8];
	echo '</td></tr>';
	}
	echo '</table>';
	}
}

/*Показать не найденные заказы*/
elseif(isset($_POST['show_not_found_books'])) {
	$query_show_not_found_books= "SELECT * FROM lib_orders WHERE status='not_found'";
	require_once 'connection.php';
	$link=new mysqli($host, $user, $password, $database);
	$result_query_show_not_found_books=mysqli_query ($link, $query_show_not_found_books) or die ("Ошибка ".mysqli_error($link));
	$rows=mysqli_num_rows($result_query_show_not_found_books);
	if($result_query_show_not_found_books)
	{
	echo '<br><div id="counter"> Всего не найденных заказов: '. $rows.'</div>';
	echo '<br>';
	
	while ($row=mysqli_fetch_row($result_query_show_not_found_books))
		{
		$order_number=$row[0];
		echo '<table><tr><td rowspan="4" id="status" class="not_found"><b>Статус:</b><br> не найдено</td>';
		echo '<td rowspan="2" id="datetime"><b>Дата:</b><br>';
		$date = DateTime::createFromFormat('Y-m-d', $row[6]);
		echo $date->format('d F Y');
		echo '</td><td id="book_name">';
		echo $row[3];
		echo '</td>';
		echo '<td></td>';
		echo '<tr><td id="author"><b>Автор: </b>'.$row[4].'</td><td></td></tr><tr><td rowspan="2" id="datetime"><b>Время:</b><br>';
	$time = DateTime::createFromFormat('H:i:s', $row[7]);
		echo $time->format('H:i');
	echo '</td><td colspan="2"><b>Язык:</b> ';
	echo $row[5];
	echo '</td></tr><tr><td id="reader"><b>Читатель:</b> ';
	echo $row[1];
	echo '</td><td id="action"><form method="post" name="delete_reservation" action="books_reservations.php">
			<input type="hidden" value="2" name="x">
			<input type="hidden" value="'.$order_number.'" name="order_number">
			<input type="submit" name="'.$order_number.'" value="Отменить заказ" class="btn">
			</form></td></tr>
	<tr><td colspan="4" id="additional"><b>Дополнительно:</b> ';
	echo $row[8];
	echo '</td></tr></table>';}	
	}
	}


/*Показать все книги*/ /* ( ORDER BY reservation_date ASC) ORDER BY reservation_time ASC*/
else {$query_show_all_reserved_books= "SELECT * FROM lib_orders order by reservation_date, reservation_time";
	require_once 'connection.php';
	$link=new mysqli($host, $user, $password, $database);
	$result_query_show_all_reserved_books=mysqli_query ($link, $query_show_all_reserved_books) or die ("Ошибка ".mysqli_error($link));
	$rows=mysqli_num_rows($result_query_show_all_reserved_books);
		if($result_query_show_all_reserved_books)
	{
	echo '<br><div id="counter"> Всего заказано книг: '.$rows.'</div>';
	echo '<br>';
	
	while ($row=mysqli_fetch_row($result_query_show_all_reserved_books))
	{	
		$order_number=$row[0];
		if ($row[9]=="not_seen") {echo '<table><tr><td rowspan="4" id="status" class="not_seen"><b>Статус:</b> <br> не просмотрено</td>';
		echo '<td rowspan="2" id="datetime"><b>Дата:</b><br>';
		$date = DateTime::createFromFormat('Y-m-d', $row[6]);
		echo $date->format('d F Y');
		/*echo $row[6];*/
		echo '</td><td id="book_name">';
		echo $row[3];
		echo '</td>';
		echo '<td id="action"><form method="post" name="change_status_to_not_prepared" action="books_reservations.php">
			<input type="hidden" value="1" name="x">
			<input type="hidden" value="not_seen" name="status">
			<input type="hidden" value="not_prepared" name="new_status">
			<input type="hidden" value="'.$order_number.'" name="order_number">
			<input type="submit" name="'.$order_number.'" value="Отметить как просмотренное" class="btn">
			</form></td>';
		echo '</tr><tr><td id="author"><b>Автор: </b>'.$row[4].'</td><td></td></tr><tr><td rowspan="2" id="datetime"><b>Время:</b><br>';
		$time = DateTime::createFromFormat('H:i:s', $row[7]);
		echo $time->format('H:i');
	/*echo $row[7];*/
	echo '</td><td colspan="2"><b>Язык:</b> ';
	echo $row[5];
	echo '</td></tr><tr><td id="reader"><b>Читатель:</b> ';
	echo $row[1];
	echo '</td><td id="action"><form method="post" name="delete_reservation" action="books_reservations.php">
			<input type="hidden" value="2" name="x">
			<input type="hidden" value="'.$order_number.'" name="order_number">
			<input type="submit" name="'.$order_number.'" value="Отменить заказ" class="btn">
			</form></td></tr>
	<tr><td colspan="4" id="additional"><b>Дополнительно:</b> ';
	echo $row[8];
	echo '</td></tr></table>';
			}
			
		elseif ($row[9]=="not_prepared") {
			echo '<table><tr><td rowspan="4" id="status" class="not_prepared"><b>Статус:</b><br> на стадии подготовки</td>';
		
		echo '<td rowspan="2" id="datetime"><b>Дата:</b><br>';
		$date = DateTime::createFromFormat('Y-m-d', $row[6]);
		echo $date->format('d F Y');
		echo '</td><td id="book_name">';
		echo $row[3];
		echo '</td>';
		echo '<td id="action"><form method="post" name="change_status_to_prepared" action="books_reservations.php">
			<input type="hidden" value="1" name="x">
			<input type="hidden" value="not_prepared" name="status">
			<input type="hidden" value="prepared" name="new_status">
			<input type="hidden" value="'.$order_number.'" name="order_number">
			<input type="submit" name="'.$order_number.'" value="Отметить как подготовленное" class="btn">
			</form></td>';
		echo '<tr><td id="author"><b>Автор:</b> '.$row[4].'</td><td id="action"><form method="post" name="change_status_to_not_found" action="books_reservations.php">
			<input type="submit" name="change_status_to_not_found" value="Книга не найдена" class="btn">
			<input type="hidden" value="1" name="x">
			<input type="hidden" value="not_prepared" name="status">
			<input type="hidden" value="not_found" name="new_status">
			<input type="hidden" value="'.$order_number.'" name="order_number">
			</form></td></tr><tr><td rowspan="2" id="datetime"><b>Время:</b><br>';
	$time = DateTime::createFromFormat('H:i:s', $row[7]);
		echo $time->format('H:i');
	echo '</td><td colspan="2"><b>Язык:</b> ';
	echo $row[5];
	echo '</td></tr><tr><td id="reader"><b>Читатель:</b> ';
	echo $row[1];
	echo '</td><td id="action"><form method="post" name="delete_reservation" action="books_reservations.php">
			<input type="hidden" value="2" name="x">
			<input type="hidden" value="'.$order_number.'" name="order_number">
			<input type="submit" name="'.$order_number.'" value="Отменить заказ" class="btn">
			</form></td></tr>
	<tr><td colspan="4" id="additional"><b>Дополнительно:</b> ';
	echo $row[8];
	echo '</td></tr></table>';}
	
		elseif ($row[9]=="prepared") {
			echo '<table><tr><td rowspan="4" id="status" class="prepared"><b>Статус:</b> <br> готово</td>';
		
		echo '<td rowspan="2" id="datetime"><b>Дата:</b><br>';
		$date = DateTime::createFromFormat('Y-m-d', $row[6]);
		echo $date->format('d F Y');
		echo '</td><td id="book_name">';
		echo $row[3];
		echo '</td>';
		echo '<td id="action"><form method="post" name="delete_reservation" action="books_reservations.php">
			<input type="hidden" value="2" name="x">
			<input type="hidden" value="prepared" name="status">
			<input type="hidden" value="'.$order_number.'" name="order_number">
			<input type="submit" name="'.$order_number.'" value="Отметить, что читатель забрал книгу" class="btn">
			</form></td>';
		echo '<tr><td id="author"><b>Автор:</b> '.$row[4].'</td><td></td></tr><tr><td rowspan="2" id="datetime"><b>Время:</b><br>';
	$time = DateTime::createFromFormat('H:i:s', $row[7]);
		echo $time->format('H:i');
	echo '</td><td colspan="2"><b>Язык:</b> ';
	echo $row[5];
	echo '</td></tr><tr><td id="reader"><b>Читатель: </b>';
	echo $row[1];
	echo '</td><td id="action"><form method="post" name="delete_reservation" action="books_reservations.php">
			<input type="hidden" value="2" name="x">
			<input type="hidden" value="'.$order_number.'" name="order_number">
			<input type="submit" name="'.$order_number.'" value="Отменить заказ" class="btn">
			</form></td></tr>
	<tr><td colspan="4" id="additional"><b>Дополнительно:</b> ';
	echo $row[8];
	echo '</td></tr></table>';
			
	} 
	
		if ($row[9]=="not_found") 
		{echo '<table><tr><td rowspan="4" id="status" class="not_found"><b>Статус:</b> <br>не найдено</td>';
		
		echo '<td rowspan="2" id="datetime"><b>Дата:</b><br>';
		$date = DateTime::createFromFormat('Y-m-d', $row[6]);
		echo $date->format('d F Y');
		echo '</td><td id="book_name">';
		echo $row[3];
		echo '</td>';
		echo '<td></td>';
		echo '<tr><td id="author"><b>Автор:</b> '.$row[4].'</td><td></td></tr><tr><td rowspan="2" id="datetime"><b>Время:</b><br>';
	$time = DateTime::createFromFormat('H:i:s', $row[7]);
		echo $time->format('H:i');
	echo '</td><td colspan="2"><b>Язык:</b> ';
	echo $row[5];
	echo '</td></tr><tr><td id="reader"><b>Читатель:</b> ';
	echo $row[1];
	echo '</td><td id="action"><form method="post" name="delete_reservation" action="books_reservations.php">
			<input type="hidden" value="2" name="x">
			<input type="hidden" value="'.$order_number.'" name="order_number">
			<input type="submit" name="'.$order_number.'" value="Отменить заказ" class="btn">
			</form></td></tr>
	<tr><td colspan="4" id="additional"><b>Дополнительно:</b> ';
	echo $row[8];
	echo '</td></tr></table>';}		
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
<?php
echo '</div>';}}
elseif ($_SESSION['role']=='reader' or $_SESSION['role']=='admin') {echo 'У вас недостаточно прав для перехода на данную страницу. <br> Вы будете перенаправлены на главную страницу через 3 секунды';
header ("Refresh: 3; index.php");}
else {header ('Location: authorization.php');} ?>
</body>
</html>