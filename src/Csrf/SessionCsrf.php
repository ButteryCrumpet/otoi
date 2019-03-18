<?php

namespace Otoi\Csrf;

use Otoi\Sessions\SessionInterface;
use Psr\Http\Message\ServerRequestInterface;

class SessionCsrf implements CsrfInterface
{
    private $session;
    private $salt;

    public function __construct($salt, SessionInterface $session)
    {
        $this->salt = $salt;
        $this->session = $session;
    }

    public function generateCsrfToken()
    {
        $id = $this->makeId();
        $token = \uniqid($this->salt, true);
        $this->session->flash($id, $token);
        return [$id, $token];
    }

    public function validateRequest(ServerRequestInterface $request)
    {
        $id = $this->makeId();
        $token = $this->session->getFlash($this->makeId());

        if (is_null($token)) {
            return false;
        }

        $body = $request->getParsedBody();

        if (!isset($body[$id])) {
            return false;
        }
        return $token === $body[$id];
    }

    public function removeCsrfData(ServerRequestInterface $request)
    {
        $body = $request->getParsedBody();
        unset($body[$this->makeId()]);
        return $request->withParsedBody($body);
    }

    private function makeId()
    {
        return "_csrf_$this->salt";
    }
}