<?php

namespace Otoi\Parsers;

use Otoi\Mail\DriverInterface;
use Otoi\Mail\EmailAddress;
use Otoi\Mail\Mail;
use Otoi\Mail\PlaceholderEmailAddress;
use Otoi\Templates\TemplateInterface;

class ArrayMailParser implements ParserInterface
{
    static private $necessary = ["to", "from", "subject"];

    private $templator;
    private $condChecker;
    private $driver;

    public function __construct(DriverInterface $driver, TemplateInterface $templator, $condChecker)
    {
        $this->templator = $templator;
        $this->condChecker = $condChecker;
        $this->driver = $driver;
    }

    public function parse($input)
    {
        $this->checkSchema($input);

        $to = is_array($input["to"][0]) ? $input["to"] : [$input["to"]];
        $to = array_map([$this, "parseEmail"], $to);

        $files = isset($input['files'])
            ? is_array($input["files"])
                ? $input['files']
                : [$input['files']]
            : [];

        $mail = new Mail(
            $this->driver,
            $to,
            $this->parseEmail($input["from"]),
            $input["subject"],
            $files
        );

        if (isset($input["cc"])) {
            $ccs = is_array($input["cc"]) ? $input["cc"] : [$input["cc"]];
            foreach ($ccs as $cc) {
                $mail->addCC($this->parseEmail($cc));
            }
        }

        if (isset($input["bcc"])) {
            $bccs = is_array($input["bcc"]) ? $input["bcc"] : [$input["bcc"]];
            foreach ($bccs as $bcc) {
                $mail->addBCC($this->parseEmail($bcc));
            }
        }

        if (isset($input["template"])) {
            $mail->isTemplated($input["template"], $this->templator);
        }

        if (isset($input["condition"])) {
            $mail->isConditional($input["condition"], $this->condChecker);
        }

        return $mail;
    }

    private function parseEmail($in)
    {
        if (empty($in)) {
            throw new ParseException(_("Must be valid email or array [email, name]"));
        }

        if (!is_array($in)) {
            $in = [$in, ""];
        }

        if (count($in) < 2) {
            $in[1] = "";
        }

        $placeholders = bindec(
            (int)$this->isPlaceholder($in[0]) . (int)$this->isPlaceholder($in[1])
        );

        if ($placeholders > 0) {
            return new PlaceholderEmailAddress(
                $this->extractPlaceholderName($in[0]),
                $this->extractPlaceholderName($in[1]),
                $placeholders
            );
        }

        try {
            return new EmailAddress(
                $in[0],
                $in[1]
            );
        } catch (\Exception $e) {
            throw new ParseException(
                sprintf(_("Must be valid email or placeholder (input name prepended with @), %s was given"), $in[0])
            );
        }

    }

    private function isPlaceholder($str)
    {
        return mb_substr($str, 0, 1) === "@";
    }

    private function extractPlaceholderName($str)
    {
        return mb_substr($str, 1);
    }

    private function checkSchema($array)
    {
        if (!is_array($array)) {
            throw new ParseException(
                sprintf(_("Mail config must be array. %s was given."), gettype($array)));
        }

        foreach (static::$necessary as $key) {
            if (!isset($array[$key])) {
                throw new ParseException(sprintf(_("Mail config must have key of %s"), $key));
            }
        }

        if (empty($array["to"])) {
            throw new ParseException(_("Mail config To must not be empty"));
        }

        if (!(isset($array["template"]) && !isset($array["body"]))) {
            throw new ParseException(_("Mail config must have either body or template"));
        }
    }
}