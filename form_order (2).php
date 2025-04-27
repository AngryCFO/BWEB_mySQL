<!doctype html>
<html lang="ru">
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
// Параметры подключения к базе данных
define('DB_HOST', 'localhost');
define('DB_USER', 'userbx-stud53');
define('DB_PASS', 'gV%kLbWR2LX3');
define('DB_NAME', 'dbbx-stud53');

// Обработка формы
if(isset($_POST['submit'])){
    // Подключаемся к БД
    $link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if (!$link) {
        echo '<div class="container alert alert-danger mt-3">';
        echo "Ошибка: Невозможно установить соединение с MySQL.<br>";
        echo "Код ошибки errno: " . mysqli_connect_errno() . "<br>";
        echo "Текст ошибки error: " . mysqli_connect_error();
        echo '</div>';
    } else {
        // Устанавливаем кодировку
        mysqli_set_charset($link, "utf8");
        
        // Экранирование введённых данных
        $user_name = mysqli_real_escape_string($link, $_POST['user_name']);
        $user_second_name = mysqli_real_escape_string($link, $_POST['user_second_name']);
        $user_last_name = mysqli_real_escape_string($link, $_POST['user_last_name']);
        $city = mysqli_real_escape_string($link, $_POST['user_address_city']);
        $street = mysqli_real_escape_string($link, $_POST['user_address_street']);
        $house = mysqli_real_escape_string($link, $_POST['user_address_house']);
        $flat = mysqli_real_escape_string($link, $_POST['user_address_flat']);
        
        // Начинаем транзакцию для атомарности операций
        mysqli_begin_transaction($link);
        
        try {
            // Добавление записи в таблицу contacts
            $sql_contact = "INSERT INTO contacts (Name, SecondName, LastName) VALUES ('$user_name', '$user_second_name', '$user_last_name')";
            $result = mysqli_query($link, $sql_contact);
            
            if(!$result) {
                throw new Exception(mysqli_error($link));
            }
            
            $contact_id = mysqli_insert_id($link);
            
            // Добавление записи в таблицу orders
            $sql_order = "INSERT INTO orders (ContactId, City, Street, House, Flat) VALUES ('$contact_id', '$city', '$street', '$house', '$flat')";
            $result2 = mysqli_query($link, $sql_order);
            
            if(!$result2) {
                throw new Exception(mysqli_error($link));
            }
            
            $order_id = mysqli_insert_id($link);
            
            // Фиксируем транзакцию
            mysqli_commit($link);
            
            // Выводим сообщение об успехе
            echo '<div class="container alert alert-success mt-3">';
            echo "<h4>Спасибо за ваш заказ! Номер заказа: $order_id</h4>";
            echo "<p><strong>Данные получателя:</strong><br>";
            echo "$user_last_name $user_name $user_second_name</p>";
            echo "<p><strong>Адрес доставки:</strong><br>";
            echo "г. $city, ул. $street, д. $house, кв. $flat</p>";
            echo '</div>';
            
        } catch (Exception $e) {
            // Откатываем транзакцию при ошибке
            mysqli_rollback($link);
            echo '<div class="container alert alert-danger mt-3">';
            echo "Произошла ошибка при оформлении заказа: " . $e->getMessage();
            echo '</div>';
        }
        
        // Закрываем соединение
        mysqli_close($link);
    }
}
?>
<div class="container mt-4">
<h2 class="mb-4">Оформление заказа</h2>
<form action="" method="POST">
<fieldset class="mb-4">
    <legend class="fs-5">Контактная информация</legend>
    <div class="mb-3">
        <label for="user_name" class="form-label">Ваше имя<span class="text-danger">*</span></label>
        <input type="text" name="user_name" id="user_name" class="form-control" value="<?= isset($_POST['user_name']) ? htmlspecialchars($_POST['user_name']) : '' ?>" required>
    </div>
    <div class="mb-3">
        <label for="user_second_name" class="form-label">Ваше отчество<span class="text-danger">*</span></label>
        <input type="text" name="user_second_name" id="user_second_name" class="form-control" value="<?= isset($_POST['user_second_name']) ? htmlspecialchars($_POST['user_second_name']) : '' ?>" required>
    </div>
    <div class="mb-3">
        <label for="user_last_name" class="form-label">Ваша фамилия<span class="text-danger">*</span></label>
        <input type="text" name="user_last_name" id="user_last_name" class="form-control" value="<?= isset($_POST['user_last_name']) ? htmlspecialchars($_POST['user_last_name']) : '' ?>" required>
    </div>
</fieldset>
<fieldset class="mb-4">
    <legend class="fs-5">Адрес доставки</legend>
    <div class="mb-3">
        <label for="user_address_city" class="form-label">Город<span class="text-danger">*</span></label>
        <input type="text" name="user_address_city" id="user_address_city" class="form-control" value="<?= isset($_POST['user_address_city']) ? htmlspecialchars($_POST['user_address_city']) : '' ?>" required>
    </div>
    <div class="mb-3">
        <label for="user_address_street" class="form-label">Улица<span class="text-danger">*</span></label>
        <input type="text" name="user_address_street" id="user_address_street" class="form-control" value="<?= isset($_POST['user_address_street']) ? htmlspecialchars($_POST['user_address_street']) : '' ?>" required>
    </div>
    <div class="mb-3">
        <label for="user_address_house" class="form-label">Дом и корпус<span class="text-danger">*</span></label>
        <input type="text" name="user_address_house" id="user_address_house" class="form-control" value="<?= isset($_POST['user_address_house']) ? htmlspecialchars($_POST['user_address_house']) : '' ?>" required>
    </div>
    <div class="mb-3">
        <label for="user_address_flat" class="form-label">Квартира<span class="text-danger">*</span></label>
        <input type="text" name="user_address_flat" id="user_address_flat" class="form-control" value="<?= isset($_POST['user_address_flat']) ? htmlspecialchars($_POST['user_address_flat']) : '' ?>" required>
    </div>
</fieldset>
    <button type="submit" name="submit" class="btn btn-primary" value="submit">Заказать</button>
</form>
</div>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>