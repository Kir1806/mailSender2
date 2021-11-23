<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// $mail = new PHPMailer(true);
// $mail -> CharSet = 'UTF-8';
// $mail -> setLanguage('ru', 'PHPMailer/language/');
// $mail -> IsHTML(true);

// // от кого письмо
// $mail -> setFrom('kir1806@list.ru', 'Kirill Sh');
// // кому
// $mail -> addAddress('Shlyonkin.K@yandex.ru', 'Kirill');
// // тема письма
// $mail -> Subject = 'Hello, this is Kirill';

$mail = new PHPMailer(true);
$mail->isSMTP();                   // Отправка через SMTP
$mail -> CharSet = 'UTF-8';
$mail -> setLanguage('ru', 'PHPMailer/language/');
$mail->Host   = 'smtp.yandex.ru';  // Адрес SMTP сервера
$mail->SMTPAuth   = true;          // Enable SMTP authentication
$mail->Username   = 'Shlyonkin.K';       // ваше имя пользователя (без домена и @)
$mail->Password   = 'uphnlbztnqzfolma';    // ваш пароль
$mail->SMTPSecure = 'ssl';         // шифрование ssl
$mail->Port   = 465;               // порт подключения

// от кого письмо
$mail -> setFrom('Shlyonkin.K@yandex.ru', 'Kirill');
// кому
$mail -> addAddress('kir1806@list.ru', 'Kirill Sh');
// тема письма
$mail -> Subject = 'Hello, this is Kirill';

// hand
$hand = 'Правая';
if ($_POST['hand'] == 'left') {
    $hand = 'Левая';
}

// body letter

$body = '<h1> Встречайте письмо!</h1>';

if (trim(!empty($_POST['name']))) {
    $body.='<p><strong>Имя:</strong> '.$_POST['name'].'</p>';
}

if (trim(!empty($_POST['email']))) {
    $body.='<p><strong>E-mail:</strong> '.$_POST['email'].'</p>';
}

if (trim(!empty($_POST['hand']))) {
    $body.='<p><strong>рука:</strong> '.$hand.'</p>';
}

if (trim(!empty($_POST['age']))) {
    $body.='<p><strong>Возраст:</strong> '.$_POST['age'].'</p>';
}

if (trim(!empty($_POST['message']))) {
    $body.='<p><strong>Сообщение:</strong> '.$_POST['message'].'</p>';
}

// прикрепить файл

if (!empty($_FILES['image'] ['tmp_name'])) {
    
    // путь загрузки файла
    $filePath = __DIR__."/files/".$_FILES['image']['name'];
    
    // грузим файл
    if (copy($_FILES['image']['tmp_name'], $filePath)) {
        $fileAttach = $filePath;
        $body.='<p><strong>Фото в приложении</strong>';
        $mail->addAttachment($fileAttach);
    }
}

$mail -> Body = $body;

// отправка

if (!$mail->send()) {
    $message = 'Error';
} else {
    $message = 'Data sended';
}

$response = ['message' => $message];

header('Content-type: application/json');
echo json_encode($response);
?>