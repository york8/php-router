<?php
/**
 * User: York <lianyupeng1988@126.com>
 * Date: 2017-07-10 15:27
 */

namespace York8\Router\Handler;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use York8\Router\HandlerInterface;

/**
 * Class CallbackHandler
 * 回调处理器，构造函数接收一个回调函数，用户可以简单直接的使用这个类来创建处理器而不需要实现 HandlerInterface 接口
 * @package York8\Router\Handler
 */
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
