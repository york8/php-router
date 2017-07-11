<?php
/**
 * User: York <lianyupeng1988@126.com>
 * Date: 2017-07-09 14:03
 */

namespace York8\Router\Matcher;

use York8\Router\MatcherInterface;
use York8\Router\MatcherTrait;
use Psr\Http\Message\RequestInterface;

/**
 * 请求 host 匹配器
 * @package York8\Router
 */
class HostMatcher implements MatcherInterface
{
    use MatcherTrait;

    /** @var string[] */
    private $hosts;

    /**
     * @param string[] $hosts 需要匹配的 host 列表
     */
    public function __construct(array $hosts)
    {
        $this->hosts = $hosts;
    }

    public function match(RequestInterface $request, array &$attrs = null): bool
    {
        if (empty($this->hosts)) {
            return true;
        }
        $host = $request->getUri()->getHost();
        foreach ($this->hosts as $h) {
            if (substr_compare($host, $h, 0, null, true) === 0) {
                return true;
            }
        }
        return false;
    }
}
