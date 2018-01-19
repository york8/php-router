<?php
/**
 * User: York <lianyupeng1988@126.com>
 * Date: 2017-07-09 17:10
 */

namespace York8\Router;

use Psr\Http\Message\RequestInterface;

/**
 * 默认请求路由器实现
 * @package York8\Router
 */
class Router implements RouterInterface
{
    use RouterTrait;

    /**
     * @var callable
     */
    private $notFoundHandler;

    function __construct(callable $notFoundHandler)
    {
        $this->setNotFoundHandler($notFoundHandler);
    }

    /** {@inheritdoc} */
    public function route(RequestInterface $request, array &$attrs = null): callable
    {
        foreach ($this->rulerHandlerMap as $item) {
            /** @var RulerInterface $ruler */
            $ruler = $item[0];
            if ($ruler->match($request, $attrs)) {
                return $item[1];
            }
        }
        return $this->notFoundHandler;
    }

    /**
     * 获取路由失败时的默认处理器
     * @return callable
     */
    public function getNotFoundHandler(): callable
    {
        return $this->notFoundHandler;
    }

    /**
     * 设置路由失败时的默认处理器
     * @param callable $notFoundHandler
     */
    public function setNotFoundHandler(callable $notFoundHandler)
    {
        $this->notFoundHandler = $notFoundHandler;
    }
}
