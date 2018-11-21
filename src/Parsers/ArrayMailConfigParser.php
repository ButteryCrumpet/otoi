<?php

namespace Otoi\Parsers;


use Otoi\Interfaces\ParserInterface;
use Otoi\Models\EmailAddress;
use Otoi\Models\MailConfig;
use Otoi\StringStore;

class ArrayMailConfigParser implements ParserInterface
{
    static private $necessary = ["to", "from", "subject", "template"];

    private $store;

    public function __construct(StringStore $store)
    {
        $this->store = $store;
    }

    public function parse($input): MailConfig
    {
        $this->checkSchema($input);

        $cc = array_map([$this, "parseEmail"], isset($input["cc"]) ? $input["cc"] : []);
        $bcc = array_map([$this, "parseEmail"], isset($input["bcc"]) ? $input["bcc"] : []);

        return new MailConfig(
            $this->parseEmail($input["to"]),
            $this->parseEmail($input["from"]),
            $input["subject"],
            $input["template"],
            $cc,
            $bcc,
            isset($input["condition"]) ? $input["condition"] : null
        );
    }

    private function parseEmail($in)
    {
        if (!is_array($in)) {
            return new EmailAddress($this->maybePlaceholder($in));
        }

        return new EmailAddress(
            $this->maybePlaceholder($in[0]),
            $this->maybePlaceholder($in[1])
        );
    }

    private function maybePlaceholder($in)
    {
        $first = mb_substr($in, 0, 1);
        if ($first !== "@") {
            return $in;
        }
        return $this->store->makePlaceholder(mb_substr($in, 1));
    }


    private function checkSchema($array)
    {
        if (!is_array($array)) {
            throw new \InvalidArgumentException(
                "Input must be array. " . gettype($array) . "was given."
            );
        }

        foreach (self::$necessary as $key) {
            if (!isset($array[$key])) {
                throw new \DomainException("Input must have key of $key");
            }
        }
    }
}