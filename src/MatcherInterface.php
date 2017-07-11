<?php
/**
 * User: York <lianyupeng1988@126.com>
 * Date: 2017-07-09 13:51
 */

namespace York8\Router;

use Psr\Http\Message\RequestInterface;

/**
 * 匹配器，用来验证某一请求是否满足条件
 * @package York8\Router
 */
interface MatcherInterface
{
    /**
     * 判断请求是否满足匹配要求
     * @param RequestInterface $request 待进行匹配的请求对象
     * @param string[]|null $attrs 引用数组，用来接收匹配到的属性值，默认为 null 不接收
     * @return bool
     */
    public function match(RequestInterface $request, array &$attrs = null): bool;

    /**
     * 匹配器必须实现 __invoke 方法来钓用 match 方法
     * @param RequestInterface $request
     * @param string[]|null $attrs
     * @return bool
     */
    public function __invoke(RequestInterface $request, array &$attrs = null): bool;
}
