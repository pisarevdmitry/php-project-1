<?php
require_once __DIR__ ."/vendor/autoload.php";
require_once 'functions.php';


if (validate($_POST)) {
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=cd35590_admin', 'cd35590_admin', 'qwerty');
        //  echo " соединение с базой данных";
    } catch (PDOException $e) {
        echo "Невозможно установить соединение с базой данных";
    }
    auth($_POST['name'], $_POST['email'], $_POST['phone'], $pdo);
    $adress = "Улица: {$_POST['street']} Дом: {$_POST['home']} Корпус: {$_POST['part']} Квартира:{$_POST['appt']}
               Этаж:{$_POST['floor']}";
    $payment =  ($_POST['payment'] === '1') ? 'Потребуется сдача' : 'Оплата по карте';
    $order_id = reg_order($adress, $_POST['comment'], $payment, $_POST['email'], $pdo);
    send_mail($_POST['email'], $order_id, $adress, $pdo);

}

