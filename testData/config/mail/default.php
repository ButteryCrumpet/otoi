<?php

return [
    [
        "subject" => "SUBJECT",
        "to" => "test@test.com",
        "from" => ["no-reply@server.com", "Test Server"],
        "template" => "mail/default",
        "files" => "file"
    ], [
        "subject" => "CUSTOMER SUBJECT",
        "to" => ["@email", "@name"],
        "from" => ["no-reply@server.com", "Test Server"],
        "template" => "mail/default"
    ]
];