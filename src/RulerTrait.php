<?php
/**
 * User: York <lianyupeng1988@126.com>
 * Date: 2017-07-09 16:26
 */

namespace York8\Router;

use Psr\Http\Message\RequestInterface;

trait RulerTrait
{
    /** @var MatcherInterface[] */
    private $matchers = [];

    /**
     * 添加匹配器
     * @param MatcherInterface $matcher
     * @return RulerInterface
     */
    public function addMatcher(MatcherInterface $matcher): RulerInterface
    {
        $this->matchers[] = $matcher;
        return $this;
    }

    /**
     * 判断请求是否与规则匹配
     * @param RequestInterface $request
     * @param string[]|null $attrs
     * @return bool
     */
    public function match(RequestInterface $request, array &$attrs = null): bool
    {
        if (empty($this->matchers)) {
            // 不存在匹配器时始终返回 false，便于路由器继续向下探查匹配
            return false;
        }
        foreach ($this->matchers as $matcher) {
            if (!$matcher->match($request, $attrs)) {
                return false;
            }
        }
        return true;
    }
}
