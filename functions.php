<?php
use PHPMailer\PHPMailer\PHPMailer;
use ReCaptcha\ReCaptcha;

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
    $remote_ip = $_SERVER['REMOTE_ADDR'];
    $g_recapcha_res = $_REQUEST['g-recaptcha-responce'];
    $recapcha= new ReCaptcha('6LdT208UAAAAAPUesWOC38bSVs2YMAUMAMrFCz_X');
    $resp = $recapcha->verify($g_recapcha_res, $remote_ip);
    if ($resp->isSuccess()) {
        return true;
    }
    echo "капча не пройдена";
    return false;
}
function auth($name, $email, $phone, $pdo)
{
    $query = 'SELECT * FROM customers WHERE email = :email';
    $cat = $pdo -> prepare($query);
    $cat->execute(['email' => $email]);
    $result = $cat->fetchAll();
    if (count($result) === 0) {
        $query = 'INSERT INTO customers VALUE (:name, :email,:phone)';
        $add = $pdo->prepare($query);
        $add->execute(['name' => $name, 'email'=> $email, 'phone' => $phone]);
    }
}
function reg_order($adress, $details, $payment, $id, $pdo)
{
    $query = 'INSERT INTO orders VALUE (NULL , :adress, :details, :payment, :id)';
    $order =  $pdo->prepare($query);
    $order->execute(['adress' => $adress, 'details' => $details,
        'payment' => $payment, 'id' => $id]);
    $order_id = $pdo->lastInsertId();
    return $order_id;
}
function send_mail($id, $order_id, $adress, $pdo)
{
    $query = 'SELECT * FROM orders WHERE customer_id = :id';
    $orders = $pdo->prepare($query);
    $orders->execute(['id' => $id]);
    $orders = $orders -> fetchAll();
    $orders = count($orders);
    $orders = ($orders > 1) ? "Спасибо! Это уже {$orders} заказ" :'Спасибо - это ваш первый заказ';
    $body = "<h1> заказ № {$order_id}</h1><p>“Ваш заказ будет доставлен по адресу:{$adress}</p>
            <p>Содержимое заказа - DarkBeefBurger за 500 рублей, 1 шт, {$orders} </p>";
    $headers ='From:Заказ бургера'. "\r\n".
        'MIME-version: 1.0'."\r\n".
        'Content-Type: text/html; charset=UTF-8';
    $mail =new PHPMailer;
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->Host ='smtp.yandex.ru';
    $mail->Username = 'test45792@yandex.ru';
    $mail->Password ='halo45lz';
    $mail->SMTPSecure="ssl";
    $mail->Port = 465;
    $mail->setFrom('test45792@yandex.ru', 'test');
    $mail->addAddress('jedi59@yandex.ru');
    $mail->AddReplyTo('test45792@yandex.ru', 'First Last');
    $mail->CharSet = 'UTF-8';
    $mail->isHTML(true);
    $mail->Subject = "Order № $order_id";
    $mail->Body = $body;
    if ($mail->send()) {
        echo 'success';
    }
}