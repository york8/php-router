<?php
/**
 * User: York <lianyupeng1988@126.com>
 * Date: 2017-07-09 22:38
 */

namespace York8\Router;

use UnexpectedValueException;

trait RouterTrait
{
    /** @var array */
    private $rulerHandlerMap = [];

    /**
     * 添加规则：将规则 rule 与 处理器 handler 绑定起来
     * @param RulerInterface $ruler 规则对象
     * @param HandlerInterface $handler 处理器对象
     * @return RouterInterface
     */
    public function addRuler(RulerInterface $ruler, HandlerInterface $handler): RouterInterface
    {
        if (is_null($ruler) || is_null($handler)) {
            throw new UnexpectedValueException('the ruler and handler could not be null');
        }
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
     * @param HandlerInterface $handler
     * @return RouterInterface
     */
    public function get($path, HandlerInterface $handler): RouterInterface
    {
        $ruler = $this->createRuler('GET', $path);
        return $this->addRuler($ruler, $handler);
    }

    /**
     * 路由 POST 请求 $path 到 $handler
     * @param string $path
     * @param HandlerInterface $handler
     * @return RouterInterface
     */
    public function post($path, HandlerInterface $handler): RouterInterface
    {
        $ruler = $this->createRuler('POST', $path);
        return $this->addRuler($ruler, $handler);
    }

    /**
     * 路由 PUT 请求 $path 到 $handler
     * @param string $path
     * @param HandlerInterface $handler
     * @return RouterInterface
     */
    public function put($path, HandlerInterface $handler): RouterInterface
    {
        $ruler = $this->createRuler('PUT', $path);
        return $this->addRuler($ruler, $handler);
    }

    /**
     * 路由 HEAD 请求 $path 到 $handler
     * @param string $path
     * @param HandlerInterface $handler
     * @return RouterInterface
     */
    public function head($path, HandlerInterface $handler): RouterInterface
    {
        $ruler = $this->createRuler('HEAD', $path);
        return $this->addRuler($ruler, $handler);
    }

    /**
     * 路由 OPTION 请求 $path 到 $handler
     * @param string $path
     * @param HandlerInterface $handler
     * @return RouterInterface
     */
    public function option($path, HandlerInterface $handler): RouterInterface
    {
        $ruler = $this->createRuler('OPTION', $path);
        return $this->addRuler($ruler, $handler);
    }

    /**
     * 路由 DELETE 请求 $path 到 $handler
     * @param string $path
     * @param HandlerInterface $handler
     * @return RouterInterface
     */
    public function delete($path, HandlerInterface $handler): RouterInterface
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
}