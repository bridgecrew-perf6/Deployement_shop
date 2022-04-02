<?php

namespace App\Classe;

use Mailjet\Client;
use Mailjet\Resources;



class Mail
{
    private $api_key = 'fe9f5ed3e4f9f13543e310bb95d6afc3';
    private $api_key_secret = 'e8351fb5064141846715566c11270fe4';

    public function send($to_email, $to_name, $subject, $content)
    {
        $mj = new Client($this->api_key, $this->api_key_secret,true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "contact.haircaire@gmail.com",
                        'Name' => "HairCare"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID' => 3568050,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        'content' => $content,
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);//envoyer l'email
        $response->success() && dd($response->getData());
    }
}