<?php
/**
 * User: York <lianyupeng1988@126.com>
 * Date: 2017-07-09 14:06
 */

namespace York8\Router;

use Psr\Http\Message\RequestInterface;

trait MatcherTrait
{
    public function match(RequestInterface $request, array &$attrs = null): bool
    {
        return false;
    }

    public function __invoke(RequestInterface $request, array &$attrs = null): bool
    {
        return $this->match($request, $attrs);
    }
}