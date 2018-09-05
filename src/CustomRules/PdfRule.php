<?php

namespace Otoi\CustomRules;

use SuperSimpleValidation\RuleInterface;
use SuperSimpleValidation\Rules\FileExtension;
use SuperSimpleValidation\Rules\FileSignature;
use SuperSimpleValidation\Validator;

class PdfRule implements RuleInterface
{
    private $validator;

    function __construct()
    {
        $this->validator = new Validator([
            "extension" => new FileExtension("pdf", "Extension must be .pdf"),
            "signature" => new FileSignature(["25", "50", "44", "46"], "File does not match PDF file signature")
        ]);
    }

    function assert($data)
    {
        return $this->validator->assert($data);
    }

    public function validate($data)
    {
        return $this->validator->validate($data);
    }

    public function getErrorMessages()
    {
        return $this->validator->getErrorMessages();
    }
}