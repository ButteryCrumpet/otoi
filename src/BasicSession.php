<?php

namespace Otoi;


class BasicSession implements Interfaces\SessionInterface
{
    private $condemned = false;

    public function start($args = []): bool
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

    public function condemn()
    {
        $this->condemned = true;
    }

    public function condemned(): bool
    {
        return $this->condemned;
    }

    public function forceDestroy(): bool
    {
        return \session_destroy();
    }
}