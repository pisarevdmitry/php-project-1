<?php
function validate($POST)
{
    if ($POST['name'] === '') {
        echo "Введите имя";
        return false;
    } elseif ($POST['phone'] === '') {
        echo "Введите телефон";
        return false;
    } elseif ($POST['email'] === '') {
        echo "Введите email";
        return false;
    } elseif ($POST['street'] === ''||$POST['home'] ==='') {
        echo "Введите Адресс";
        return false;
    } elseif (!$POST['payment']) {
        echo "Укажите форму оплаты";
        return false;
    }
    return true;
}
function auth($name, $email, $phone, $pdo)
{
    $query = 'SELECT * FROM customers WHERE email = :email';
    $cat = $pdo -> prepare($query);
    $cat -> execute(['email' => $email]);
    $result = $cat -> fetchAll();
    if (count($result) === 0) {
        $query = 'INSERT INTO customers VALUE (:name, :email,:phone)';
        $add = $pdo -> prepare($query);
        $add -> execute(['name' => $name, 'email'=> $email, 'phone' => $phone]);
    }
}
function reg_order($adress, $details, $payment, $id, $pdo)
{
    $query = 'INSERT INTO orders VALUE (NULL , :adress, :details, :payment, :id)';
    $order =  $pdo -> prepare($query);
    $order -> execute(['adress' => $adress, 'details' => $details,
        'payment' => $payment, 'id' => $id]);
    $order_id = $pdo -> lastInsertId();
    return $order_id;
}
function send_mail($id, $order_id, $adress, $pdo)
{
    $query = 'SELECT * FROM orders WHERE customer_id = :id';
    $orders = $pdo -> prepare($query);
    $orders -> execute(['id' => $id]);
    $orders = $orders -> fetchAll();
    $orders = count($orders);
    $orders = ($orders > 1) ? "Спасибо! Это уже {$orders} заказ" :'Спасибо - это ваш первый заказ';
    $body = "<h1> заказ № {$order_id}</h1><p>“Ваш заказ будет доставлен по адресу:{$adress}</p>
            <p>Содержимое заказа - DarkBeefBurger за 500 рублей, 1 шт, {$orders} </p>";
    $headers ='From:Заказ бургера'. "\r\n".
        'MIME-version: 1.0'."\r\n".
        'Content-Type: text/html; charset=UTF-8';
    $mail = mail($_POST['email'], "Заказ", $body, $headers);
    if ($mail) {
        echo 'Письмо успешно отрпавлено';
    }
}