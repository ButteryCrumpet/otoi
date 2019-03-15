<?php

namespace Otoi\Sessions;


class BasicSession implements SessionInterface
{
    private $condemned = false;

    public function start($args = [])
    {
        if (\headers_sent()) {
            return false;
        }

        $status = session_status();
        if ($status === PHP_SESSION_DISABLED) {
            return false;
        }
        if ($status === PHP_SESSION_ACTIVE) {
            return true;
        }

        return \session_start($args);
    }

    public function get($key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function forget($key)
    {
        $_SESSION[$key] = null;
    }

    public function condemn()
    {
        $this->condemned = true;
    }

    public function condemned()
    {
        return $this->condemned;
    }

    public function forceDestroy()
    {
        return \session_destroy();
    }

    public function flash($key, $value)
    {
        $fd = $this->get("_flash.new", []);
        $fd[$key] = $value;
        $this->set("_flash.new", $fd);
    }

    public function getFlash($key = null, $default = null)
    {
        $data = $this->get("_flash.old", $default);

        if (is_null($key)) {
            return $data;
        }

        return isset($data[$key]) ? $data[$key] : $default;

    }

    public function ageFlash()
    {
        $fd = $this->get("_flash.new", []);
        $this->forget("_flash.new");
        $this->set("_flash.old", $fd);
    }

    public function removeFlash()
    {
        $this->forget("_flash.old");
    }
}