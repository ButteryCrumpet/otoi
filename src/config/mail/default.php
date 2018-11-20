<?php

return [
    [
        "to" => "sleigh@hotmail.com",
        "from" => ["@email", "@name"],
        "subject" => "SUBJECT",
        "template" => "mail.twig.html",
        "condition" => [
            "relation" => "AND",
            "tests" => ["name=simon", "email=abc@123.com"]
        ]
    ]
];