<?php
/**
 * User: York <lianyupeng1988@126.com>
 * Date: 2017-07-09 16:30
 */

namespace York8\Router;

use York8\Router\Matcher\HostMatcher;
use York8\Router\Matcher\MethodMatcher;
use York8\Router\Matcher\PathMatcher;

/**
 * 默认规则实现，支持 path、host、method 三个匹配器
 * @package York8\Router
 */
class Ruler implements RulerInterface
{
    use RulerTrait;

    /** @var string[] */
    private $hosts;
    /** @var string[] */
    private $methods;
    /** @var string */
    private $prefix;
    /** @var string[] */
    private $paths;
    /** @var bool */
    private $isCaseSensitive;

    /**
     * @param string[] $paths
     * @param string $prefix
     * @param string[] $methods
     * @param string[] $hosts
     * @param bool $isCaseSensitive
     */
    public function __construct(array $paths, $prefix = null, array $methods = [], array $hosts = [], $isCaseSensitive = false)
    {
        $this->hosts = $hosts;
        if (!empty($hosts)) {
            $this->addMatcher(new HostMatcher($hosts));
        }
        $this->methods = $methods;
        if (!empty($methods)) {
            $this->addMatcher(new MethodMatcher($methods));
        }
        $this->prefix = $prefix;
        $this->paths = $paths;
        $this->isCaseSensitive = $isCaseSensitive;
        if (!empty($paths)) {
            $this->addMatcher(new PathMatcher($paths, $prefix, $isCaseSensitive));
        }
    }
}
