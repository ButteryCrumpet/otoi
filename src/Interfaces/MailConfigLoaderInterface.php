<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2018/11/20
 * Time: 9:59
 */

namespace Otoi\Interfaces;


interface MailConfigLoaderInterface extends LoaderInterface
{
    public function load($name);
}