<?php

return [
    "contactUri" => "/contact/",
    "thanksUri" => "/contact/thanks",
    "template-dir" => $_SERVER['DOCUMENT_ROOT'] . "/contact/templates",
    "cache-dir" => $_SERVER['DOCUMENT_ROOT'] . "/contact/cache",
    "view-actions" => [
        "input" => "input.php",
        "confirm" => "confirm.php",
    ],
    "validation" => [
        "test" => "required",
        "test2" => "required|email",
        "file" => "pdf",
        "phone" => 'required|phone',
    ],
    "mail" => [
        [
            'to' => 'test2',
            'from' => ['sleigh@produce-pro.co.jp', 'Test'],
            'subject' => "test mail",
            'template' => 'mail-temp.php',
            'cc' => '',
        ],
    ],
];