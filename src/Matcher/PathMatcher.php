<?php
/**
 * User: York <lianyupeng1988@126.com>
 * Date: 2017-07-09 15:53
 */

namespace York8\Router\Matcher;

use Psr\Http\Message\RequestInterface;
use York8\Router\MatcherInterface;
use York8\Router\MatcherTrait;

/**
 * 请求路径匹配器
 * @package York8\Router
 */
class PathMatcher implements MatcherInterface
{
    use MatcherTrait;

    /** @var string 路径前缀 */
    private $prefix;
    /** @var string[] 需要匹配的路径 */
    private $paths;
    /** @var bool 是否大小写敏感，默认忽略大小写 */
    private $isCaseSensitive = false;
    /** @var PathPattern[] 路径编译的正则表达式列表 */
    private $patterns;

    /**
     * @param \string[] $paths 需要匹配的路径列表，路径规则如下：
     * <p> /path/to/target
     * <p> /path/to/(pattern)/target 括号内为正则表达式
     * <p> /path/:attr1(pattern)/to/:attr2(pattern) 命名的正则表达式，匹配到值将设置到请求对象的 attributes 中
     * @param string $prefix
     * @param bool $isCaseSensitive 是否匹配大小写，默认忽略大小写
     */
    public function __construct(array $paths, $prefix = null, bool $isCaseSensitive = false)
    {
        $this->paths = $paths;
        $this->prefix = $prefix;
        $this->isCaseSensitive = $isCaseSensitive;
    }

    public function match(RequestInterface $request, array &$attrs = null): bool
    {
        if (empty($this->paths)) {
            return true;
        }
        $path = $request->getUri()->getPath();
        foreach ($this->paths as $ind => $p) {
            if (!isset($this->patterns[$ind])) {
                $this->patterns[$ind] = new PathPattern($this->prefix . $p, $this->isCaseSensitive ? '' : 'i');
            }
            $pattern = $this->patterns[$ind];
            if ($pattern->match($path, $attrs)) {
                return true;
            }
        }
        return false;
    }
}
