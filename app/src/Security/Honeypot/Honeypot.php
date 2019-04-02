<?php

namespace Otoi\Security\Honeypot;


use Psr\Http\Message\ServerRequestInterface;

class Honeypot implements HoneypotInterface
{
    private $name;

    /**
     * Honeypot constructor.
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @param ServerRequestInterface $request
     * @param bool $assert
     * @return bool
     * @throws HoneypotTrapException
     */
    public function validateRequest(ServerRequestInterface $request, $assert = true)
    {
        if ($request->getMethod() !== "POST") {
            return true;
        }

        $body = $request->getParsedBody();

        $passed = !isset($body[$this->name]) || $body[$this->name] === "";

        if (!$passed && $assert) {
            throw new HoneypotTrapException(_("Honeypot triggered"));
        }

        return $passed;
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @param bool $echo
     * @return string
     */
    public function html($echo = true)
    {
        $html = "<label style='display: none'>%s<input name='%s' aria-hidden='true' tabindex='-1'></label>";
        $html = sprintf($html, _("Leave blank"), $this->name);

        if ($echo) {
            echo $html;
        }

        return $html;
    }

}