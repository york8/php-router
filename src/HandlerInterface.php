<?php
/**
 * User: York <lianyupeng1988@126.com>
 * Date: 2017-07-09 22:20
 */

namespace York8\Router;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * 处理器接口，用来处理符合规则的请求
 * Interface HandlerInterface
 * @package router
 */
interface HandlerInterface
{
    /**
     * 处理请求
     * @param RequestInterface $request 请求对象
     * @param ResponseInterface $response 响应对象
     * @return ResponseInterface 返回经过处理的的响应对象
     */
    public function handle(RequestInterface $request, ResponseInterface $response): ResponseInterface;

    /**
     * 调用 handle 方法处理请求
     * @param RequestInterface $request 请求对象
     * @param ResponseInterface $response 响应对象
     * @return ResponseInterface 返回经过处理的的响应对象
     */
    public function __invoke(RequestInterface $request, ResponseInterface $response): ResponseInterface;
}
