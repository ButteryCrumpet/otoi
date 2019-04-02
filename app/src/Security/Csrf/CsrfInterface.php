<?php

namespace Otoi\Security\Csrf;


use Psr\Http\Message\ServerRequestInterface;

interface CsrfInterface
{
    /**
     * @return string[]
     */
    public function generateCsrfToken();

    /**
     * @param ServerRequestInterface $request
     * @return bool
     * @throws InvalidCsrfException
     */
    public function validateRequest(ServerRequestInterface $request);

    /**
     * @param ServerRequestInterface $request
     * @return ServerRequestInterface
     */
    public function removeCsrfData(ServerRequestInterface $request);
}