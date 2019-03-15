<?php

namespace Otoi\Repositories;

use Otoi\Parsers\ParserInterface;
use Otoi\Drivers\DriverInterface;

class MailConfigRepository implements RepositoryInterface
{
    private $strategy;
    private $parser;

    public function __construct(DriverInterface $strategy, ParserInterface $configParser)
    {
        $this->strategy = $strategy;
        $this->parser = $configParser;
    }

    public function load($name)
    {
        $loaded = $this->strategy->single($name);
        $configs = [];
        foreach ($loaded as $config) {
            $configs[] = $this->parser->parse($config);
        }
        return $configs;
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