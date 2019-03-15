<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2019-03-15
 * Time: 16:10
 */

namespace Otoi\Csrf;


use Psr\Http\Message\ServerRequestInterface;

interface CsrfInterface
{
    public function generateCsrfToken();

    public function validateRequest(ServerRequestInterface $request);

    public function removeCsrfData(ServerRequestInterface $request);
}