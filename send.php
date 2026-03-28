<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    // Настройки сервера
    $mail->isSMTP();
    $mail->Host       = getenv('SMTP_HOST'); 
    $mail->SMTPAuth   = true;
    $mail->Username   = getenv('SMTP_USER');
    $mail->Password   = getenv('SMTP_PASS');
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    $mail->CharSet    = 'UTF-8';

    // Отримувачі
    $mail->setFrom(getenv('SMTP_USER'), 'Сайт Обратной Связи');
    $mail->addAddress(getenv('ADMIN_EMAIL')); // Куда придет письмо

    // Контент з форми
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    $mail->isHTML(true);
    $mail->Subject = "Новое сообщение от $name";
    $mail->Body    = "<b>Имя:</b> $name <br> <b>Email:</b> $email <br> <b>Сообщение:</b> $message";

    $mail->send();
    echo 'Сообщение отправлено!';
} catch (Exception $e) {
    echo "Ошибка отправки: {$mail->ErrorInfo}";
}

$token = getenv('TELEGRAM_TOKEN');
$chat_id = getenv('TELEGRAM_CHAT_ID');
$txt = "Новая заявка! Имя: $name, Сообщение: $message";
file_get_contents("https://api.telegram.org/bot$token/sendMessage?chat_id=$chat_id&parse_mode=html&text=" . urlencode($txt));