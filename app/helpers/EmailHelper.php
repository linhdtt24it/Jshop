<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../libs/PHPMailer/src/Exception.php';
require_once __DIR__ . '/../libs/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../libs/PHPMailer/src/SMTP.php';

class EmailHelper {
    
    /**
     * @param string $to
     * @param string $subject
     * @param string $body
     * @param array $options Optional options like 'from_email', 'from_name', 'reply_to_email', 'reply_to_name'
     * @return bool
     */
    public static function send($to, $subject, $body, $options = []) {
        $config = require __DIR__ . '/../../config/email.php';
        
        $mail = new PHPMailer(true);
        
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = $config['host'];
            $mail->SMTPAuth   = $config['smtp_auth'];
            $mail->Username   = $config['username'];
            $mail->Password   = $config['password'];
            $mail->SMTPSecure = $config['smtp_secure'];
            $mail->Port       = $config['port'];
            $mail->CharSet = 'UTF-8';

            // Recipients
            $fromEmail = $options['from_email'] ?? $config['from_email'];
            $fromName = $options['from_name'] ?? $config['from_name'];
            $mail->setFrom($fromEmail, $fromName);
            $mail->addAddress($to);

            // Reply-To
            if (isset($options['reply_to_email'])) {
                $replyToName = $options['reply_to_name'] ?? $options['reply_to_email'];
                $mail->addReplyTo($options['reply_to_email'], $replyToName);
            }
            
            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = nl2br($body);
            $mail->AltBody = $body;

            $mail->send();
            return true;
        } catch (Exception $e) {
            // Log error: "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"
            return false;
        }
    }
}