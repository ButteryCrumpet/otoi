<?php

return [
    "default" => [
        "validation" => [
            "name" => "required",
            "email" => "email|required",
            "phone" => "required|phone",
            "inquiry" => "required"
        ],
        "templates" => ["index" => "index", "confirm" => "confirm"],
        "final-location" => "/",
        "mail" =>  []// e.g ["admin", "customer"]
    ]
];