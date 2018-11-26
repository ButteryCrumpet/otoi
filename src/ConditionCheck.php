<?php

namespace Otoi;

class ConditionCheck
{
    public function check($cond, $fields)
    {
        if ($cond === "always") {
            return true;
        }
        return $this->conditionMet($cond, $fields);
    }

    private function conditionMet($cond, $fields)
    {
        if (is_string($cond)) {
            return $this->singleCond($cond, $fields);
        }
        if (is_array($cond)) {
            return $this->multipleCond($cond, $fields);
        }
        return false;
    }

    private function singleCond($cond, $fields)
    {
        if (strpos($cond,"==") !== false) {
            return $this->eq($cond, $fields);
        }

        if (strpos($cond, "[x]")) {
            return $this->in($cond, $fields);
        }
    }

    private function multipleCond($cond, $fields)
    {
        $type = $cond["relation"];
        $results = array_map(function ($test) use ($fields) {
            return $this->singleCond($test, $fields);
        }, $cond["tests"]);
        if ($type === "AND") {
            return !in_array(false, $results, true);
        }
        if ($type === "OR") {
            return in_array(true, $results, true);
        }

        return false;
    }

    private function eq($cond, $fields)
    {
        $exploded = explode("==", $cond);
        if (!isset($fields[$exploded[0]])) {
            return false;
        }
        $value = $fields[$exploded[0]]->getValue();
        $result = $value === $exploded[1];
        return $result;
    }

    private function in($cond, $fields)
    {
        $exploded = explode("[x]", $cond);
        if (!isset($fields[$exploded[0]])) {
            return false;
        }
        $value = $fields[$exploded[0]]->getValue();
        if (!is_array($value)) {
            return false;
        }
        return in_array($exploded[1], $value);
    }
}