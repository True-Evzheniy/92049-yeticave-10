<?php
require_once __DIR__ . '/../vendor/autoload.php';

function send_email_to_winners($winners, $domain, $config)
{
    $transport = new Swift_SmtpTransport($config['host'], $config['port']);
    $transport->setUsername($config['user']);
    $transport->setPassword($config['password']);
    $mailer = new Swift_Mailer($transport);

    foreach ($winners as $winner) {
        $message = new Swift_Message();
        $message->setSubject("Ваша ставка победила");
        $message->setFrom(['keks@phpdemo.ru' => 'keks']);
        $message->setBcc([$winner['email'] => $winner['name']]);
        $messageBody = include_template('email.php', [
            'user_name' => $winner['name'],
            'lot_link' => "http://{$domain}/pages/lot.php?id={$winner['lot_id']}",
            'lot_name' => $winner['lot_name'],
            'my_bets' => "http://{$domain}/pages/my-bets.php"
        ]);

        $message->setBody($messageBody, 'text/html');
        $mailer->send($message);
    }
}
