<?php
/**
 * User: York <lianyupeng1988@126.com>
 * Date: 2017-07-09 16:48
 */

namespace York8\Router;

use Psr\Http\Message\RequestInterface;

/**
 * 路由器接口
 * @package York8\Router
 */
interface RouterInterface
{
    /**
     * 路由请求，成功时返回对应的处理器，请求不匹配任何规则时返回 false
     * @param RequestInterface $request 请求对象
     * @param string[]|null $attrs 规则匹配成功时用来接收匹配到的属性值，具体参见 MatcherInterface
     * @return callable 失败时返回 false，否则返回对应的 handler
     */
    public function route(RequestInterface $request, array &$attrs = null): callable;

    /**
     * 添加规则：将规则 rule 与 处理器 handler 绑定起来
     * @param RulerInterface $ruler 规则对象
     * @param callable $handler 处理器对象
     * @return RouterInterface
     */
    public function addRuler(RulerInterface $ruler, callable $handler): RouterInterface;

    /**
     * 路由 GET 请求 $path 到 $handler
     * @param string $path
     * @param callable $handler
     * @return RouterInterface
     */
    public function get($path, callable $handler): RouterInterface;

    /**
     * 路由 POST 请求 $path 到 $handler
     * @param string $path
     * @param callable $handler
     * @return RouterInterface
     */
    public function post($path, callable $handler): RouterInterface;

    /**
     * 路由 PUT 请求 $path 到 $handler
     * @param string $path
     * @param callable $handler
     * @return RouterInterface
     */
    public function put($path, callable $handler): RouterInterface;

    /**
     * 路由 HEAD 请求 $path 到 $handler
     * @param string $path
     * @param callable $handler
     * @return RouterInterface
     */
    public function head($path, callable $handler): RouterInterface;

    /**
     * 路由 OPTION 请求 $path 到 $handler
     * @param string $path
     * @param callable $handler
     * @return RouterInterface
     */
    public function option($path, callable $handler): RouterInterface;

    /**
     * 路由 DELETE 请求 $path 到 $handler
     * @param string $path
     * @param callable $handler
     * @return RouterInterface
     */
    public function delete($path, callable $handler): RouterInterface;
}
