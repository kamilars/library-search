<?php 
session_start();
?>
<html>
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>Редактировать книгу</title>
   <link href="https://fonts.googleapis.com/css?family=Josefin+Sans:300|Poppins:600|Montserrat|Montserrat+Alternates" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="registration.css">
 </head>
 <body>
 <div id="header">
<?php
if ($_SESSION['role']=='librarian'){
echo "Добро пожаловать, " . $_SESSION['firstname']; //приветствие
?>
</div>
<div>
<?php $book_code_copy=$_POST['book_code_copy'];?>

	<form name="edit_book_form"  action="edit_book.php" method="post">
		<legend>Данные книги</legend>
		Код книги: <br>
		<input type="text" placeholder="Код книги" name="book_code" value="<?php echo $_POST['book_code_copy']; ?>"/> 
		<br><br>
		Название книги:<br>
		<input type="text" placeholder="Название" name="book_name" value="<?php echo $_POST['book_name_copy']; ?>"/> 
		<br><br>
		Автор:<br>
		<input type="text" placeholder="Автор" name="book_author" value="<?php echo $_POST['book_author_copy']; ?>"/> 
		<br> <br>
		Язык:<br>
		<select name="book_language">
			<option value="<?php echo $_POST['book_language_copy']; ?>" selected ><?php echo $_POST['book_language_copy']; ?></option>
			<option value="Русский">Русский</option>
			<option value="Казахский">Казахский</option>
			<option value="Английский">Английский</option>
			<option value="Немецкий">Немецкий</option>
			<option value="Китайский">Китайский</option>
		</select>
		<br>
	
		<br><br>
		<input type="hidden" name="book_code_copy" value="<?php echo $book_code_copy; ?>">
		<input type="submit" name="edit_book_form" value="Отправить"/>
	</form><br>
<?php 
	if (isset($_POST['edit_book_form']))
	{
	require_once 'connection.php';

	$link=mysqli_connect ($host, $user, $password, $database)
	or die("Ошибка" . mysqli_error($link));
	$query_edit_book="UPDATE lib_books
	SET book_code = '".$_POST['book_code']."', 
	book_name = '".$_POST['book_name']."', 
	book_author = '".$_POST['book_author']."', 
	book_language = '".$_POST['book_language']."'
	WHERE book_code='".$_POST['book_code_copy']."'";
	$result_query_edit_book=mysqli_query($link, $query_edit_book) or die ("Ошибка" . mysqli_error($link));
	if($result_query_edit_book)
{
	header ("Location:actions_with_books.php");
		}
	mysqli_close($link);
	} echo '<a href="actions_with_books.php">Вернуться к поиску</a>';	}
elseif ($_SESSION['role']=='reader' or $_SESSION['role']=='admin') {echo 'У вас недостаточно прав для перехода на данную страницу. <br> Вы будете перенаправлены на главную страницу через 3 секунды';
header ("Refresh: 3; index.php");}
else {header ('Location: authorization.php');}
?>	

</div>
</body>
</html>