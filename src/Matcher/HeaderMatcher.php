<?php
/**
 * User: York <lianyupeng1988@126.com>
 * Date: 2018-01-07 23:32
 */

namespace York8\Router\Matcher;

use Psr\Http\Message\RequestInterface;
use York8\Router\MatcherInterface;

/**
 * 请求头匹配器
 * Class HeaderMatcher
 * @package Matcher
 */
class HeaderMatcher implements MatcherInterface
{
    private $headers = [];

    function __construct(array $headers)
    {
        foreach ($headers as $name => $target) {
            $this->headers[] = function (RequestInterface $request) use ($name, $target) : bool {
                $values = $request->getHeader($name);
                if (empty($target)) {
                    return count($values) > 0;
                } else if (is_callable($target)) {
                    foreach ($values as $v) {
                        if ($target($v) === true) {
                            return true;
                        }
                    }
                    return false;
                } else if (is_array($target)) {
                    foreach ($values as &$v) {
                        foreach ($target as &$t) {
                            if (strcasecmp($t, $v) === 0) return true;
                        }
                    }
                    return false;
                } else {
                    foreach ($values as &$v) {
                        if (strcasecmp($target, $v) === 0) return true;
                    }
                    return false;
                }
            };
        }
    }

    /**
     * 判断请求是否满足匹配要求
     * @param RequestInterface $request 待进行匹配的请求对象
     * @param string[]|null $attrs 引用数组，用来接收匹配到的属性值，默认为 null 不接收
     * @return bool
     */
    public function match(RequestInterface $request, array &$attrs = null): bool
    {
        foreach ($this->headers as $checkFunc) {
            if (!$checkFunc($request)) return false;
        }
        return true;
    }

    /**
     * 匹配器必须实现 __invoke 方法来钓用 match 方法
     * @param RequestInterface $request
     * @param string[]|null $attrs
     * @return bool
     */
    public function __invoke(RequestInterface $request, array &$attrs = null): bool
    {
        return $this->match($request, $attrs);
    }
}
