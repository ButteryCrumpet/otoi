<?php

namespace Otoi\Factories;

use SuperSimpleForms\Form;
use SuperSimpleForms\Field;

class SuperSimpleFormFactory
{

    public function build($config)
    {
        $fields = [];
        foreach ($config as $name => $props) {
            $props = $this->orDefaults($props, $name);
            $fields[] = new Field(
                $name,
                $props["type"],
                $props["required"],
                $props["label"],
                $props["defaultValue"]
            );
        }
        return new Form($fields);
    }

    private function orDefaults($props, $name)
    {
        $props["type"] = isset($props["type"]) ? $props["type"] : "text";
        $props["label"] = isset($props["label"]) ? $props["label"] : $name;
        $props["required"] = isset($props["required"]) ? $props["required"] : false;
        $props["defaultValue"] = isset($props["defaultValue"]) ? $props["defaultValue"] : "";
        
        return $props;
    }
}