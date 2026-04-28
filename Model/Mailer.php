<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

class Mailer {

    public static function send($to, $subject, $body) {

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;

            // 🔥 TON EMAIL
            $mail->Username = 'tonemail@gmail.com';
            $mail->Password = 'mot_de_passe_app'; // ⚠️ pas ton vrai mdp

            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('tonemail@gmail.com', 'RH Entreprise');
            $mail->addAddress($to);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;

            $mail->send();

        } catch (Exception $e) {
            // debug optionnel
        }
    }
}