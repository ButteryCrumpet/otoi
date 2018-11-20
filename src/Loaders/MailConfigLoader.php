<?php

namespace Otoi\Loaders;

use Otoi\Interfaces\MailConfigLoaderInterface;
use Otoi\Interfaces\ParserInterface;
use Otoi\Interfaces\StrategyInterface;

class MailConfigLoader implements MailConfigLoaderInterface
{
    private $strategy;
    private $parser;

    public function __construct(StrategyInterface $strategy, ParserInterface $configParser)
    {
        $this->strategy = $strategy;
        $this->parser = $configParser;
    }

    public function load($name): array
    {
        $loaded = $this->strategy->single($name);
        $configs = [];
        foreach ($loaded as $config) {
            $configs[] = $this->parser->parse($config);
        }
        return $configs;
    }

    public function all(): array
    {
        $configs = [];
        foreach ($this->list() as $name) {
            $configs[$name] = $this->load($name);
        }
        return $configs;
    }

    public function list(): array
    {
        return $this->strategy->list();
    }
}