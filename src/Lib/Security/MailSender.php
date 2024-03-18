<?php

namespace App\Lib\Security;

use Exception;

class MailSender
{

    private string $server;
    private int $port;
    private string $username;
    private string $password;

    public function __construct()
    {
        $this->server = getenv('SMTP_SERVER');
        $this->port = getenv('SMTP_PORT');
        $this->username = getenv('SMTP_USERNAME');
        $this->password = getenv('SMTP_PASSWORD');
    }


    public function send_mail(array $email, string $subject, string $message): bool
    {
        if (count($email) === 0) return true;
        $headers = "MIME-Version: 1.0" . "\r\n"
            . "Content-type:text/html;charset=UTF-8" . "\r\n"
            . 'From: ' . $this->username . "\r\n";

        foreach ($email as $to) {
            if (is_null($to) || $to === "") continue;
            try {
                $smtpConn = fsockopen($this->server, $this->port, $errno, $errstr, 5);

                if (!$smtpConn) throw new Exception("SMTP Connection Failed: $errstr ($errno)");

                self::sendCommand($smtpConn, "EHLO example.com");
                $response = fgets($smtpConn, 512);

                if (str_contains($response, '250-STARTTLS')) {
                    self::sendCommand($smtpConn, "STARTTLS");
                    stream_socket_enable_crypto($smtpConn, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
                }

                self::sendCommand($smtpConn, "AUTH LOGIN");
                self::sendCommand($smtpConn, base64_encode($this->username));
                self::sendCommand($smtpConn, base64_encode($this->password));
                self::sendCommand($smtpConn, "MAIL FROM:<" . $this->username . ">");
                self::sendCommand($smtpConn, "RCPT TO:<$to>");
                self::sendCommand($smtpConn, "DATA");
                self::sendCommand($smtpConn, "Subject: $subject");
                self::sendCommand($smtpConn, "To: $to");
                self::sendCommand($smtpConn, "$headers");
                self::sendCommand($smtpConn, "$message");
                self::sendCommand($smtpConn, ".");

                fputs($smtpConn, "QUIT\r\n");
                fclose($smtpConn);

            } catch
            (Exception) {
                return false;
            }
        }
        return true;
    }

    private static function sendCommand($smtpConn, string $command): void
    {
        fputs($smtpConn, $command . "\r\n");
        fgets($smtpConn, 512);
    }
}