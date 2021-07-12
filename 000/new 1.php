<html>
<body>
<table border="1"><tr><td rowspan="4" id="status" class="not_seen"><b>Статус:</b> не просмотрено</td>
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
	</body>
	</html>