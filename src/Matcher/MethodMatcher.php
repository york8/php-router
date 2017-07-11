<?php
/**
 * User: York <lianyupeng1988@126.com>
 * Date: 2017-07-09 15:49
 */

namespace York8\Router\Matcher;

use York8\Router\MatcherInterface;
use York8\Router\MatcherTrait;
use Psr\Http\Message\RequestInterface;

/**
 * 请求方法匹配器
 * @package York8\Router
 */
class MethodMatcher implements MatcherInterface
{
    use MatcherTrait;

    /** @var string[] */
    private $methods;

    /**
     * @param string[] $methods 需要匹配的请求方法列表
     */
    public function __construct(array $methods)
    {
        $this->methods = $methods;
    }

    public function match(RequestInterface $request, array &$attrs = null): bool
    {
        if (empty($this->methods)) {
            return true;
        }
        $method = $request->getMethod();
        foreach ($this->methods as $m) {
            if (substr_compare($method, $m, 0, null, true) === 0) {
                return true;
            }
        }
        return false;
    }
}
