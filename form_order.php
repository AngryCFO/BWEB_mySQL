<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <title>Оформление заказа</title>
  </head>
  <body>

<?php
// Вывод данных формы для отладки
echo "<pre>";
print_r($_REQUEST);
echo "</pre>";

if($_REQUEST['submit']){
	//подключаемся к БД
	$DB_HOST='localhost:3306'; 
	$DB_USER='ВВЕДИТЕ_ЛОГИН'; //  Вводим логин от базы данных (*пока данные не предоставлены, пишу код в общем виде*)
	$DB_PASS='ВВЕДИТЕ_ПАРОЛЬ'; // Вводим пароль от базы данных (*пока данные не предоставлены, пишу код в общем виде*)
	$DB_NAME='ВВЕДИТЕ_НАЗВАНИЕ_БД'; // Вводим название базы данных (*пока данные не предоставлены, пишу код в общем виде*)
	
	$link = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
	if (!$link) {
 		echo "Ошибка: Невозможно установить соединение с MySQL.";
 		echo "Код ошибки errno: " . mysqli_connect_errno();
 		echo "Текст ошибки error: " . mysqli_connect_error();
	} else {
	    // Экранирование введённых данных для предотвращения SQL-инъекций
	    $user_name = mysqli_real_escape_string($link, $_REQUEST['user_name']);
	    $user_second_name = mysqli_real_escape_string($link, $_REQUEST['user_second_name']);
	    $user_last_name = mysqli_real_escape_string($link, $_REQUEST['user_last_name']);
	    $city = mysqli_real_escape_string($link, $_REQUEST['user_address_city']);
	    $street = mysqli_real_escape_string($link, $_REQUEST['user_address_street']);
	    $house = mysqli_real_escape_string($link, $_REQUEST['user_address_house']);
	    $flat = mysqli_real_escape_string($link, $_REQUEST['user_address_flat']);
	    
	    // Добавление записи в таблицу contacts
	    $sql_contact = "INSERT INTO contacts (Name, SecondName, LastName) VALUES ('$user_name', '$user_second_name', '$user_last_name')";
	    $result = mysqli_query($link, $sql_contact);
	    $contact_id = mysqli_insert_id($link);
	    
	    // Добавление записи в таблицу orders
	    $sql_order = "INSERT INTO orders (ContactId, City, Street, House, Flat) VALUES ('$contact_id', '$city', '$street', '$house', '$flat')";
	    $result2 = mysqli_query($link, $sql_order);
	    $order_id = mysqli_insert_id($link);
	    
	    if ($result == false || $result2 == false) {
   		    echo '<div class="container alert alert-danger mt-3">Произошла ошибка при выполнении запроса</div>';
	    } else {
	        // Формирование подробного сообщения об успешном оформлении заказа
	        echo '<div class="container alert alert-success mt-3">';
	        echo "<h4>Спасибо за ваш заказ! Ему присвоен номер $order_id</h4>";
	        echo "<p><strong>Данные получателя:</strong><br>";
	        echo "$user_last_name $user_name $user_second_name</p>";
	        echo "<p><strong>Адрес доставки:</strong><br>";
	        echo "г. $city, ул. $street, д. $house, кв. $flat</p>";
	        echo '</div>';
	    }
    }
}
?>
<div class="container">
<h2>Оформление заказа</h2>
<form action="" method="POST">
<fieldset>
	<legend>Контактная информация</legend>
	<div class="mb-3">
		<label class="form-label">Ваше имя<span class="mf-req text-danger">*</span></label>
		<input type="text" name="user_name" id="user_name" class="form-control" value="" required>
	</div>
	<div class="mb-3">
		<label class="form-label">Ваше отчество<span class="mf-req text-danger">*</span></label>
		<input type="text" name="user_second_name" id="user_second_name" class="form-control" value="" required>
	</div>
	<div class="mb-3">
		<label class="form-label">Ваша фамилия<span class="mf-req text-danger">*</span></label>
		<input type="text" name="user_last_name" id="user_last_name" class="form-control" value="" required>
	</div>
</fieldset>
<fieldset>
	<legend>Адрес доставки</legend>
	<div class="mb-3">
		<label class="form-label">Город<span class="mf-req text-danger">*</span></label>
		<input type="text" name="user_address_city" id="user_address_city" class="form-control" value="" required>
	</div>
	<div class="mb-3">
		<label class="form-label">Улица<span class="mf-req text-danger">*</span></label>
		<input type="text" name="user_address_street" id="user_address_street" class="form-control" value="" required>
	</div>
	<div class="mb-3">
		<label class="form-label">Дом и корпус<span class="mf-req text-danger">*</span></label>
		<input type="text" name="user_address_house" id="user_address_house" class="form-control" value="" required>
	</div>
	<div class="mb-3">
		<label class="form-label">Квартира<span class="mf-req text-danger">*</span></label>
		<input type="text" name="user_address_flat" id="user_address_flat" class="form-control" value="" required>
	</div>
</fieldset>
	<button type="submit" name="submit" class="btn btn-primary" value="submit">Заказать</button>
</form>
<div id="result"></div>
</div>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>
