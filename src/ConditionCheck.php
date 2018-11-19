<?php

namespace Otoi;

class ConditionCheck
{
    public function getConfigs($fields)
    {
        $default = array();
        $always = array();
        $conditional = array();

        foreach ($this->configs as $config) {
            if (!isset($config['cond'])) {
                $default[] = $config;
                continue;
            }
            $cond = $config['cond'];
            if ($cond === "always") {
                $always[] = $config;
                continue;
            }

            if ($this->conditionMet($cond, $fields)) {
                $conditional[] = $config;
            }
        }

        if (empty($conditional)) {
            return array_merge($default, $always);
        }

        return array_merge($conditional, $always);
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
            $value = $fields[$exploded[0]];
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