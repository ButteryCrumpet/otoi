<?php

namespace Otoi;

class FormHelper
{
    private $validator;
    private $prettyNames;
    private $messages;
    private $wrapperBefore = "";
    private $wrapperAfter = "";
    private $tag;
    private $values
    ;

    private static $defaults = [
        "error-messages" => [],
        "pretty-names" => [],
        "error-wrapper" => "",
        "error-tag" => "p"
    ];

    public function __construct(ValidationInterface $validator, array $config = [])
    {
        $this->validator = $validator;
        $this->values = [];

        $config = array_merge(self::$defaults, $config);

        $this->messages = $config['error-messages'];
        $this->tag = $config['error-tag'];
        $this->prettyNames = $config['pretty-names'];
        $wrapper = $config['error-wrapper'];

        // handle no {errors} properly
        if (is_string($wrapper)) {
            $wrapper = mb_split("{errors}", $wrapper);
            $this->wrapperBefore = $wrapper[0];
            $this->wrapperAfter = isset($wrapper[1]) ? $wrapper[1] : "";
        }
    }

    public function loadValues($values)
    {
        $this->values = $values;
    }

    public function valueFor($field, $default = "")
    {
        if (isset($this->values[$field])) {
            return $this->values[$field];
        }
        return $default;
    }

    public function hasError($fieldName)
    {
        return $this->validator->hasError($fieldName) || !isset($this->values[$fieldName]);
    }

    public function renderErrors($fieldName, $echo = false)
    {
        if (!$this->hasError($fieldName)) {
            return "";
        }

        $str = $this->wrapperBefore;
        foreach ($this->validator->errorsOf($fieldName) as $error) {
            $str .= $this->renderError($fieldName, $error);
        }
        $str .= $this->wrapperAfter;
        if ($echo) {
            echo $str;
        }
        return $str;
    }

    public function renderError($fieldName, $error, $echo = false)
    {
        $name = isset($this->prettyNames[$fieldName]) ? $this->prettyNames[$fieldName] : $fieldName;
        $message = $this->getMessage($error);
        $message = str_replace("{name}", $name, $message);
        $str = sprintf('<%1$s>%2$s</%1$s>', $this->tag, $message);
        if ($echo) {
            echo $str;
        }
        return $str;
    }

    private function getMessage($error)
    {
        if (isset($this->messages[$error])) {
            return $this->messages[$error];
        }
        if (isset($this->messages["default"])) {
            return $this->messages["default"];
        }
        return $error;
    }
}