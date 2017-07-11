<?php
/**
 * User: York <lianyupeng1988@126.com>
 * Date: 2017-07-09 16:21
 */

namespace York8\Router;

use Psr\Http\Message\RequestInterface;

/**
 * 规则，匹配器的集合
 * @package York8\Router
 */
interface RulerInterface
{
    /**
     * 添加匹配器
     * @param MatcherInterface $matcher
     * @return RulerInterface
     */
    public function addMatcher(MatcherInterface $matcher): RulerInterface;

    /**
     * 判断请求是否与规则匹配
     * @param RequestInterface $request
     * @param string[]|null $attrs 接收匹配到的属性值
     * @return bool
     */
    public function match(RequestInterface $request, array &$attrs = null): bool;
}
