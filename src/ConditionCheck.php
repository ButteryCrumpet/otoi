<?php

namespace Otoi;

use Otoi\Models\MailConfig;

class ConditionCheck
{
    public function check(MailConfig $config, $fields)
    {
        $cond = $config->getCondition();
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
        if (strpos($cond, "=") !== false) {
            $exploded = explode("=", $cond);
            if (!isset($fields[$exploded[0]])) {
                return false;
            }
            $value = $fields[$exploded[0]]->getValue();
            $result =  $value === $exploded[1];
            return $result;
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
}