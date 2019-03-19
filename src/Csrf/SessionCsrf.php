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

    /**
     * @return string[]
     */
    public function generateCsrfToken()
    {
        $id = $this->makeId();
        $token = \uniqid($this->salt, true);
        $this->session->flash($id, $token);
        return [$id, $token];
    }

    /**
     * @param ServerRequestInterface $request
     * @param bool $assert
     * @return bool
     * @throws InvalidCsrfException
     */
    public function validateRequest(ServerRequestInterface $request, $assert = true)
    {
        $id = $this->makeId();
        $token = $this->session->getFlash($this->makeId());

        if (is_null($token)) {
            $this->except();
        }

        $body = $request->getParsedBody();

        if (!isset($body[$id])) {
            $this->except();
        }

        $passed = $token === $body[$id];

        if (!$passed && $assert) {
            $this->except();
        }

        return $passed;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ServerRequestInterface
     */
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

    private function except()
    {
        throw new InvalidCsrfException();
    }
}