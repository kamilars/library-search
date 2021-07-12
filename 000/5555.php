<?php 
session_start();
?>
<html>
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>Заказанные книги</title>
   <link rel="stylesheet" type="text/css" href="books_reservations.css">
  <link href="https://fonts.googleapis.com/css?family=Josefin+Sans:300|Poppins:600|Montserrat|Montserrat+Alternates" rel="stylesheet">
 </head>
 <body>
 <div id="header">
 <div id="header_left">
<?php
if ($_SESSION['role']=='reader'){
echo "Добро пожаловать, " . $_SESSION['firstname']; //приветствие
echo ' <div id="header_right">
<form action="deauthorization.php">
<input id="deauth" type="submit" value="Выйти из учетной записи">
</form></div></div>'
?>
</div>
<div id="readers_reservations">


<?php 
/*Показать все книги*/
$query_show_all_reserved_books= "SELECT * FROM lib_orders WHERE reader_login='".$_SESSION['login']."' ORDER BY reservation_date, reservation_time ";
	require_once 'connection.php';
	$link=new mysqli($host, $user, $password, $database);
	$result_query_show_all_reserved_books=mysqli_query ($link, $query_show_all_reserved_books) or die ("Ошибка ".mysqli_error($link));
	$rows=mysqli_num_rows($result_query_show_all_reserved_books);
		if($result_query_show_all_reserved_books)
	{
	echo "<br><font color='white' align='center'> Всего заказано книг: $rows</font><br>";
	
	while ($row=mysqli_fetch_row($result_query_show_all_reserved_books))
	{	$order_number=$row[0];
		echo '<table><tr>';
		if ($row[9]=="not_seen") echo '<td rowspan="4" id="status" class="not_seen"><b>Статус:</b> не просмотрено</td>';
		elseif ($row[9]=="not_prepared") echo '<td rowspan="4" id="status" class="not_prepared">Подготавливается</td>';
		elseif ($row[9]=="not_found") echo '<td rowspan="4" id="status" class="not_found">Книга не найдена</td>';		
		elseif ($row[9]=="prepared") echo '<td rowspan="4" id="status" class="prepared">Готово к выдаче</td>';
	echo '<td rowspan="2" id="datetime"><b>Дата:';
	echo $row[6];
	echo'</b><br></td><td id="book_name">';
	echo $row[3];
	echo '</td><td></td></tr><tr><td id="author"><b>Автор: </b>';
	echo $row[4];
	echo '</td><td></td></tr><tr><td rowspan="2" id="datetime"><b>Время:</b><br>';
	echo $row[7];
	echo '</td><td colspan="2"><b>Язык:</b> ';
	echo $row[5];
	echo '</td></tr><tr><td id="reader"><b>Читатель:</b>';
	echo $row[1];
	echo '	</td><td id="action"><form method="post" name="delete_reservation" action="readers_reservations.php">
			<input type="hidden" value="1" name="x">
			<input type="hidden" value="'.$order_number.'" name="order_number">
			<input type="submit" name="'.$order_number.'" id="btn" value="Отменить заказ">
			</form></td></tr>
	<tr><td colspan="4" id="additional"><b>Дополнительно:</b>';
	echo $row[8];
	echo '</td></tr></table>';
	/*
<td rowspan="4" id="status" class="not_seen"><b>Статус:</b> не просмотрено</td> //**
		<td rowspan="2" id="datetime"><b>Дата:</b><br>
		</td><td id="book_name">
		название
		</td>
		<td id="action"><form method="post" name="change_status_to_not_prepared" action="books_reservations.php">
			<input type="hidden" value="1" name="x">
			<input type="hidden" value="not_seen" name="status">
			<input type="hidden" value="not_prepared" name="new_status">
			<input type="hidden" value="'.$order_number.'" name="order_number">
			</form></td>
	</tr><tr><td id="author"><b>Автор: </b>Автор</td><td></td></tr><tr><td rowspan="2" id="datetime"><b>Время:</b><br>
	</td><td colspan="2"><b>Язык:</b> 
</td></tr><tr><td id="reader"><b>Читатель:</b>
	
	</td><td id="action"><form method="post" name="delete_reservation" action="books_reservations.php">
			<input type="hidden" value="2" name="x">
			<input type="hidden" value="'.$order_number.'" name="order_number">
			<input type="submit" name="'.$order_number.'" value="Отменить заказ" class="btn">
			</form></td></tr>
	<tr><td colspan="4" id="additional"><b>Дополнительно:</b> 
	</td></tr></table>


		/*echo "<tr>";
		$order_number=$row[0];
		$j=1;
		echo "<td>$row[$j]</td>";
		for ($j=3; $j<9; ++$j) echo "<td>$row[$j]</td>";
		
		if ($row[9]=="not_seen") echo '<td class="not_seen">Не просмотрено</td>';
		elseif ($row[9]=="not_prepared") echo '<td class="not_prepared">Подготавливается</td>';
		elseif ($row[9]=="not_found") echo '<td class="not_found">Книга не найдена</td>';		
		elseif ($row[9]=="prepared") echo '<td class="prepared">Готово к выдаче</td>';
		echo '<td><form method="post" name="delete_reservation" action="readers_reservations.php">
			<input type="hidden" value="1" name="x">
			<input type="hidden" value="'.$order_number.'" name="order_number">
			<input type="submit" name="'.$order_number.'" id="btn" value="Отменить заказ">
			</form></td>';	
			echo "</tr>";
	}
	echo '</table><br>';*/
}}
if ($_POST['x']==1) {
	require_once 'connection.php';
	$link=new mysqli($host, $user, $password, $database);
	$query_delete_reservation="DELETE FROM lib_orders WHERE order_number='".$_POST['order_number']."'";
	$result_query_delete_reservation=mysqli_query($link, $query_delete_reservation) or die ("Ошибка ".mysqli_error($link)); 
	if ($result_query_delete_reservation) {header("Refresh:5;readers_reservations.php");
mysqli_close($link);}}
			
	echo'	<style>
.not_seen {
	background:#FF8065;
}
.not_prepared{
	background: #FFFCB2;
}

.prepared{
	background: #98DF99;
}
	
.not_found{
	background: #AEDBE3;
}	
	</style>

</div>
<a id="goout" href="index.php">Перейти на главную</a><br>
<a id="goout" href="reader_panel.php">Перейти к панели читателя</a>'; }
elseif ($_SESSION['role']=='librarian' or $_SESSION['role']=='admin') {echo 'Доступно только читателям. <br> Вы будете перенаправлены на главную страницу через 3 секунды';
header ("Refresh: 3; index.php");}
else {header ('Location: authorization.php');}?> 
</body>
</html>