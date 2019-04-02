<?php

namespace Otoi\Security\Honeypot;


use Psr\Http\Message\ServerRequestInterface;

interface HoneypotInterface
{
    /**
     * @param ServerRequestInterface $request
     * @param bool $assert
     * @return bool
     * @throws HoneypotTrapException
     */
    public function validateRequest(ServerRequestInterface $request, $assert);

    /**
     * @param bool $echo
     * @return string
     */
    public function html($echo = true);

    /**
     * @return string
     */
    public function name();
}