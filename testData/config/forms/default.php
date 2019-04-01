<?php

return [
    "validation" => [
        "name" => "required",
        "email" => "email|required",
        "file" => "required"
    ],
    "file" => ["file"],
    "templates" => ["index" => "index", "confirm" => "confirm"],
    "final-location" => "/"
];