<?php
include "vendor/autoload.php";

$contact = new \Otoi\Otoi();
$response = $contact->mail();

foreach ($response->getHeaders() as $name => $values) {
    header($name . ":" . implode(', ', $values));
}

echo $response->getBody();