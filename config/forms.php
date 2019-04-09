<?php

return [
    "default" => [
        "validation" => [
            "name" => "required",
            "email" => "email|required",
            "file" => ""
        ],
        "templates" => ["index" => "index", "confirm" => "confirm"],
        "final-location" => "/",
        "mail" => ["admin", "customer"]
    ]
];