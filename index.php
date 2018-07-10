<?php

include "vendor/autoload.php";

$contact = new \Otoi\Otoi();

$response = $contact->input();
echo $response->getBody();
