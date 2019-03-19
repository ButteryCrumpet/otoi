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
    /**
     * @return string[]
     */
    public function generateCsrfToken();

    /**
     * @param ServerRequestInterface $request
     * @return bool
     * @throws InvalidCsrfException
     */
    public function validateRequest(ServerRequestInterface $request);

    /**
     * @param ServerRequestInterface $request
     * @return ServerRequestInterface
     */
    public function removeCsrfData(ServerRequestInterface $request);
}