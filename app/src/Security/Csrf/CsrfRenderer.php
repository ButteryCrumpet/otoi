<?php

namespace Otoi\Security\Csrf;


class CsrfRenderer
{
    private $csrf;

    public function __construct(CsrfInterface $csrf)
    {
        $this->csrf = $csrf;
    }

    public function __invoke($echo = true)
    {
        $tokens = $this->csrf->generateCsrfToken();
        $input = sprintf("<input  type='hidden' name='%s' value='%s' >", $tokens[0], $tokens[1]);

        if ($echo) {
            echo $input;
        }

        return $input;
    }
}