<?php

namespace Otoi\Interfaces;

use Otoi\Entities\MailConfig;

/**
 * Interface MailConfigLoaderInterface
 * @package Otoi\Interfaces
 */
interface MailConfigLoaderInterface extends LoaderInterface
{
    /**
     * @param $name
     * @return MailConfig[]
     */
    public function load($name);
}