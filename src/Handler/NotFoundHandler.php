<?php
/**
 * User: York <lianyupeng1988@126.com>
 * Date: 2017-07-10 16:17
 */

namespace York8\Router\Handler;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use York8\Router\HandlerInterface;

class NotFoundHandler implements HandlerInterface
{
    /** {@inheritdoc} */
    public function handle(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $response = $response->withStatus(404);
        $body = $response->getBody();
        $body->write($response->getReasonPhrase());
        return $response;
    }

    /** {@inheritdoc} */
    public function __invoke(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $this->handle($request, $response);
    }
}
