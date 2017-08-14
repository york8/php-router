<?php
/**
 * User: York <lianyupeng1988@126.com>
 * Date: 2017-07-10 15:27
 */

namespace York8\Router\Handler;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use York8\Router\HandlerInterface;

class CallbackHandler implements HandlerInterface
{
    /** @var callable */
    private $func;

    /**
     * CallbackHandler constructor.
     * @param callable $func function (RequestInterface $request, ResponseInterface $response)
     */
    public function __construct(callable $func)
    {
        $this->func = $func;
    }

    /** {@inheritdoc} */
    public function handle(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return call_user_func($this->func, $request, $response);
    }

    /** {@inheritdoc} */
    public function __invoke(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $this->handle($request, $response);
    }
}
