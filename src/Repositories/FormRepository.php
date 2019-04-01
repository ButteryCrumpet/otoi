<?php

namespace Otoi\Repositories;


use Otoi\Drivers\DriverInterface;
use Otoi\Form;

class FormRepository implements RepositoryInterface
{
    private $driver;

    public function __construct(DriverInterface $driver) {
        $this->driver = $driver;
    }

    public function load($name)
    {
        $data = $this->driver->single($name);
        if (is_null($data)) {
            return null;
        }
        $this->validateSchema($data);

        $form = new Form(
            $name,
            $data["templates"]["index"],
            $data["templates"]["confirm"],
            $data["final-location"]
        );

        foreach ($data["validation"] as $key => $validation) {
            $form[$key] = $validation;
        }

        return $form;
    }

    public function all()
    {
        $forms = [];
        foreach ($this->listing() as $form) {
            $forms[] = $this->load($form);
        }
        return $forms;
    }

    public function listing()
    {
        return $this->driver->listing();
    }

    private function validateSchema($data)
    {
        if (!is_array($data)) {
            throw new \RuntimeException("Form config must be an array");
        }
        if (!isset($data["validation"])) {
            throw new \RuntimeException("Form config must contain validation attribute");
        }
        if (!is_array($data["validation"])) {
            throw new \RuntimeException("Form validation config must be an array");
        }
        if (!isset($data["templates"])) {
            throw new \RuntimeException("Form config must contain templates attribute");
        }
        if (!isset($data["templates"]["index"])) {
            throw new \RuntimeException("Form template config must contain index attribute");
        }
        if (!isset($data["templates"]["confirm"])) {
            throw new \RuntimeException("Form template config must contain confirm attribute");
        }
        if (!isset($data["final-location"])) {
            throw new \RuntimeException("Form config must contain a final-location");
        }
    }
}