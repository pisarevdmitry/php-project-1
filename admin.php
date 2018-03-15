<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ADMIN
    </title>
</head>
<body>
    <h1>Административнея панель</h1>
    <h3>Список зарегистрированных пользователей</h3>
    <?php
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=cd35590_admin', 'cd35590_admin', 'qwerty');
        //  echo " соединение с базой данных";
    } catch (PDOException $e) {
        echo "Невозможно установить соединение с базой данных";
    }
    $query = 'SELECT * FROM customers';
    $req = $pdo->query($query);
    $users = $req->fetchAll(PDO::FETCH_ASSOC);
    $users_list = '<ol>';
    foreach ($users as $key => $value) {
        $users_list .= "<li>Имя: {$value['name']}</br>
                            Email: {$value['email']}</br> 
                            Телефон: {$value['phone']}</li></br></br>";
    }
    $users_list .= '</ol>';
    echo  $users_list;
    ?>
    <h3>Список заказов</h3>
    <?php
    $query = 'SELECT * FROM orders JOIN customers ON orders.customer_id = customers.email';
    $req = $pdo->query($query);
    $orders = $req->fetchAll(PDO::FETCH_ASSOC);
    $orders_list = '<ol>';
    foreach ($orders as $key => $value) {
        $orders_list .= "<li>Заказ № {$value['id']}</br> 
                          Адресс доставки: {$value['address']}</br>
                          Оплата: {$value['payment']}</br>
                          Имя покупателя: {$value['name']}</br>
                          Телефон для связи: {$value['phone']}</br>
                          Комментарий к доставке: {$value['details']}</li></br></br>";
    }
    $orders_list .= '</ol>';
    echo $orders_list;
    ?>
</body>

