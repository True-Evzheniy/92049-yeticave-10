<?php
require_once __DIR__ . '/vendor/autoload.php';

function send_email_to_winners($winners, $domain)
{
    $transport = new Swift_SmtpTransport("phpdemo.ru", 25);
    $transport->setUsername("keks@phpdemo.ru");
    $transport->setPassword("htmlacademy");
    $mailer = new Swift_Mailer($transport);

    foreach ($winners as $winner) {
        $message = new Swift_Message();
        $message->setSubject("Ваша ставка победила");
        $message->setFrom(['keks@phpdemo.ru' => 'keks']);
        $message->setBcc([$winner['email'] => $winner['name']]);
        $messageBody = include_template('email.php', [
            'user_name' => $winner['name'],
            'lot_link' => "http://{$domain}/lot.php?id={$winner['lot_id']}",
            'lot_name' => $winner['lot_name'],
            'my_bets' => "http://{$domain}/my-bets.php"
        ]);

        $message->setBody($messageBody, 'text/html');
        $mailer->send($message);
    }
}
