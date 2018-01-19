<?php
/**
 * User: York <lianyupeng1988@126.com>
 * Date: 2017-07-09 22:38
 */

namespace York8\Router;

use UnexpectedValueException;
use York8\Router\Handler\CallbackHandler;

trait RouterTrait
{
    /** @var array */
    private $rulerHandlerMap = [];

    /**
     * 添加规则：将规则 rule 与 处理器 handler 绑定起来
     * @param RulerInterface $ruler 规则对象
     * @param callable $handler 处理器对象
     * @return RouterInterface
     */
    public function addRuler(RulerInterface $ruler, callable $handler): RouterInterface
    {
        if (is_null($ruler) || is_null($handler)) {
            throw new UnexpectedValueException('the ruler and handler could not be null');
        }
        $handler = $this->wrapHandler($handler);
        foreach ($this->rulerHandlerMap as $item) {
            $r = $item[0];
            if ($r === $ruler) {
                $item[1] = $handler;
                return $this;
            }
        }
        $this->rulerHandlerMap[] = [$ruler, $handler];
        return $this;
    }

    /**
     * 路由 GET 请求 $path 到 $handler
     * @param string $path
     * @param callable $handler
     * @return RouterInterface
     */
    public function get($path, callable $handler): RouterInterface
    {
        $ruler = $this->createRuler('GET', $path);
        return $this->addRuler($ruler, $handler);
    }

    /**
     * 路由 POST 请求 $path 到 $handler
     * @param string $path
     * @param callable $handler
     * @return RouterInterface
     */
    public function post($path, callable $handler): RouterInterface
    {
        $ruler = $this->createRuler('POST', $path);
        return $this->addRuler($ruler, $handler);
    }

    /**
     * 路由 PUT 请求 $path 到 $handler
     * @param string $path
     * @param callable $handler
     * @return RouterInterface
     */
    public function put($path, callable $handler): RouterInterface
    {
        $ruler = $this->createRuler('PUT', $path);
        return $this->addRuler($ruler, $handler);
    }

    /**
     * 路由 HEAD 请求 $path 到 $handler
     * @param string $path
     * @param callable $handler
     * @return RouterInterface
     */
    public function head($path, callable $handler): RouterInterface
    {
        $ruler = $this->createRuler('HEAD', $path);
        return $this->addRuler($ruler, $handler);
    }

    /**
     * 路由 OPTION 请求 $path 到 $handler
     * @param string $path
     * @param callable $handler
     * @return RouterInterface
     */
    public function option($path, callable $handler): RouterInterface
    {
        $ruler = $this->createRuler('OPTION', $path);
        return $this->addRuler($ruler, $handler);
    }

    /**
     * 路由 DELETE 请求 $path 到 $handler
     * @param string $path
     * @param callable $handler
     * @return RouterInterface
     */
    public function delete($path, callable $handler): RouterInterface
    {
        $ruler = $this->createRuler('DELETE', $path);
        return $this->addRuler($ruler, $handler);
    }

    /**
     * 简单根据 method 和 path 创建规则对象
     * @param string|string[] $methods
     * @param string|string[] $paths
     * @return RulerInterface
     */
    private function createRuler($methods, $paths): RulerInterface
    {
        if (!is_array($methods)) {
            $methods = [$methods];
        }
        if (!is_array($paths)) {
            $paths = [$paths];
        }
        return new Ruler($paths, null, $methods);
    }

    /**
     * 将 handler 包装成 HandlerInterface 对象
     * @param callable $handler
     * @return callable
     */
    private function wrapHandler(callable $handler): callable
    {
        if (!is_object($handler)) {
            $handler = new CallbackHandler($handler);
        }
        return $handler;
    }
}