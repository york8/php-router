<?php
/**
 * User: York <lianyupeng1988@126.com>
 * Date: 2017-07-10 10:30
 */

namespace York8\Router\Matcher;

const PREG_ERROR_MAP = [
    PREG_NO_ERROR => 'no error',
    PREG_INTERNAL_ERROR => 'there was an internal PCRE error.',
    PREG_BACKTRACK_LIMIT_ERROR => 'backtrack limit was exhausted',
    PREG_RECURSION_LIMIT_ERROR => 'recursion limit was exhausted',
    PREG_BAD_UTF8_ERROR => 'the last error was caused by malformed UTF-8 data',
    PREG_JIT_STACKLIMIT_ERROR => 'the last PCRE function failed due to limited JIT stack space'
];

/**
 * 路径模式
 * @package York8\Router\Matcher
 */
class PathPattern
{
    /** @var string 路径模式 */
    private $pathPattern;
    /** @var string 正则表达式修饰符 */
    private $modifiers;
    /** @var string 编译后的正则表达式 */
    private $pattern;
    /** @var string[] */
    private $attrNames = [];

    /**
     * @param string $pathPattern 路径模式
     * @param string $modifiers 正则表达式模式修饰符，默认值 'i' 忽略大小写
     * @throws \TypeError | \UnexpectedValueException
     */
    public function __construct($pathPattern, $modifiers = 'i')
    {
        if (empty($pathPattern)) {
            throw new \UnexpectedValueException('the path pattern could not be empty');
        }
        if (!is_string($pathPattern) || !is_string($modifiers)) {
            throw new \TypeError('the path pattern or modifiers type error: need string');
        }
        $this->pathPattern = $pathPattern;
        $this->modifiers = $modifiers;
    }

    /**
     * @param string $path
     * @param string[]|null $attrs
     * @return bool
     * @throws \RuntimeException
     */
    public function match($path, array &$attrs = null): bool
    {
        $pattern = $this->buildPattern();
        $matched = null;
        if (!is_null($attrs)) $matched = [];
        $result = preg_match($pattern, $path, $matched);
        if ($result === false) {
            $code = preg_last_error();
            $msg = PREG_ERROR_MAP[$code];
            throw new \RuntimeException($msg, $code);
        }
        if ($result < 1) {
            return false;
        }
        if (!empty($matched) && !empty($this->attrNames)) {
            $length = count($matched);
            for ($i = 1; $i < $length; $i++) {
                $n = $this->attrNames[$i - 1];
                $v = $matched[$i];
                $attrs[$n] = $v;
            }
        }
        return true;
    }

    public function __invoke($path, array &$matched = null): bool
    {
        return $this->match($path, $matched);
    }

    private function buildPattern()
    {
        if ($this->pattern) {
            return $this->pattern;
        }

        $pattern = '#(:\w*)?\(([^\(\)]*)\)#';
        $matched = preg_split($pattern, $this->pathPattern, -1, PREG_SPLIT_DELIM_CAPTURE);
        $parts = [];
        $attrs = [];
        $length = count($matched);
        for ($i = 0; $i < $length; $i++) {
            $p = $matched[$i];
            if ($p === '') {
                continue;
            } else if ($p[0] === ':') {
                $attrs[] = substr($p, 1);
                $reg = $matched[++$i];
                if (empty($reg)) {
                    $reg = '[^/]+';
                }
                $parts[] = '(' . $reg . ')';
            } else {
                $parts[] = str_replace('.', '\.', $p);
            }
        }
        $this->pattern = $pattern = '#^' . implode('', $parts) . '#' . $this->modifiers;
        $this->attrNames = $attrs;
        return $this->pattern;
    }
}
