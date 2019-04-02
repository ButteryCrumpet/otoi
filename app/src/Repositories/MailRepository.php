<?php

namespace Otoi\Repositories;

use Otoi\Mail\Mail;
use Otoi\Parsers\ParserInterface;
use Otoi\Drivers\DriverInterface;

class MailRepository implements RepositoryInterface
{
    private $strategy;
    private $parser;

    public function __construct(DriverInterface $strategy, ParserInterface $configParser)
    {
        $this->strategy = $strategy;
        $this->parser = $configParser;
    }

    /**
     * @param $name
     * @return Mail
     */
    public function load($name)
    {
        return $this->parser->parse($this->strategy->single($name));
    }

    public function all()
    {
        $configs = [];
        foreach ($this->listing() as $name) {
            $configs[$name] = $this->load($name);
        }
        return $configs;
    }

    public function listing()
    {
        return $this->strategy->listing();
    }
}