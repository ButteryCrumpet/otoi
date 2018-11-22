<?php

namespace Otoi\Parsers;

use Otoi\Interfaces\ParserInterface;
use Otoi\Models\Field;

class ArrayFieldParser implements ParserInterface
{
    public function parse($input): Field
    {
        if (!is_array($input)) {
            throw new \InvalidArgumentException(
                "Input must be array. " . gettype($input) . "was given."
            );
        }

        if (!isset($input["name"])) {
            throw new \DomainException("Input array must have name field");
        }

        $defaults = [
            "validation" => null,
            "type" => "text",
            "placeholder" => null,
            "defaultValue" => null,
            "label" => null
        ];

        $config = array_merge($defaults, $input);
        return new Field(
            $input["name"],
            $config["label"],
            $config["type"],
            $config["placeholder"],
            $config["defaultValue"],
            $config["validation"]
        );
    }
}