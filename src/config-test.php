<?php

return [
    "template-dir" => "templates",
    "cache-dir" => "cache",
    "view-actions" => [
        "input" => "input.php",
        "confirm" => "confirm.php",
        "mail" => "mail.php",
        "mail-temp" => "mail-temp.php"
    ],
    "mail" => [
        [
            'to' => 'test',
            'from' => ['crumpetybumpety@gmail.com', 'Simon'],
            'subject' => "Test",
            'template' => 'mail-temp',
            'cc' => 'test2',
            'attachments' => ['file']
        ]
    ],
    "validation" => [
        "test" => "required|email",
        "phone" => "required|phone",
        "test2" => "email",
        "file" => "pdf"
    ],
    "error" => []
];