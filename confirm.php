<?php
include "vendor/autoload.php";

$contact = new \Otoi\Otoi();
$response = $contact->confirm();

foreach ($response->getHeaders() as $name => $values) {
    header($name . ":" . implode(', ', $values));
}

echo $response->getBody();