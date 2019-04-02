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
            $data["final-location"],
            $data["mail"]
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
            throw new \RuntimeException(_("Form config must be an array"));
        }
        if (!isset($data["validation"])) {
            throw new \RuntimeException(_("Form config must contain validation attribute"));
        }
        if (!is_array($data["validation"])) {
            throw new \RuntimeException(_("Form validation config must be an array"));
        }
        if (!isset($data["templates"])) {
            throw new \RuntimeException(_("Form config must contain templates attribute"));
        }
        if (!isset($data["templates"]["index"])) {
            throw new \RuntimeException(_("Form template config must contain index attribute"));
        }
        if (!isset($data["templates"]["confirm"])) {
            throw new \RuntimeException(_("Form template config must contain confirm attribute"));
        }
        if (!isset($data["mail"])) {
            throw new \RuntimeException(_("Form config must mail confirm attribute"));
        }
        if (!is_array($data["mail"])) {
            throw new \RuntimeException(_("Form mail config must be an array"));
        }
        if (!isset($data["final-location"])) {
            throw new \RuntimeException(_("Form config must contain a final-location"));
        }
    }
}