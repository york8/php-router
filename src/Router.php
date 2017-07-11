<?php
/**
 * User: York <lianyupeng1988@126.com>
 * Date: 2017-07-09 17:10
 */

namespace York8\Router;

use Psr\Http\Message\RequestInterface;
use York8\Router\Handler\NotFoundHandler;

/**
 * 默认请求路由器实现
 * @package York8\Router
 */
class Router implements RouterInterface
{
    use RouterTrait;

    /** {@inheritdoc} */
    public function route(RequestInterface $request, array &$attrs = null): HandlerInterface
    {
        foreach ($this->rulerHandlerMap as $item) {
            /** @var RulerInterface $ruler */
            $ruler = $item[0];
            if ($ruler->match($request, $attrs)) {
                return $item[1];
            }
        }
        return new NotFoundHandler();
    }
}
