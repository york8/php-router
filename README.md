# PHP-Router

[![Latest Stable Version](https://poser.pugx.org/york8/router/v/stable)](https://packagist.org/packages/york8/router) 
[![Total Downloads](https://poser.pugx.org/york8/router/downloads)](https://packagist.org/packages/york8/router) 
[![Latest Unstable Version](https://poser.pugx.org/york8/router/v/unstable)](https://packagist.org/packages/york8/router) 
[![License](https://poser.pugx.org/york8/router/license)](https://packagist.org/packages/york8/router)

一个简单的基于 [PSR 7](http://www.php-fig.org/psr/psr-7/) 的 PHP 路由器，良好的分层和扩展结构

* 基于 [PSR 7](http://www.php-fig.org/psr/psr-7/) HTTP 消息接口
* 面向接口编程，灵活扩展
* 支持

### Authors

- [York](https://github.com/york8)

### Usage

#### How to use：

##### 1. 创建请求处理器
```php
$handler = new CallbackHandler(...);
```
通过实现 **[HandlerInterface](src/HandlerInterface.php)** 接口来实现自己的处理器。

[这里](src/Handler)提供了几个简单的实现：
* [Callback](src/Handler/CallbackHandler.php)处理器：用户可以通过直接传递回调函数来创建处理器对象，不需要实现接口就可以直接使用
* [NotFound](src/Handler/NotFoundHandler.php)处理器：路由失败时返回的默认处理器

##### 2. 构建路由规则，将规则与处理器关联起来
```php
$router = new Router();
$router->get(...);
$router->post(...);
$router->put(...);
$router->head(...);
$router->option(...);
$router->delete(...);
$router->addRuler(...);
```
通过实现 **[MatcherInterface](src/MatcherInterface.php)** 接口来实现自己的规则匹配器，
然后实现 **[Ruler](src/RulerInterface.php)** 接口来组合不同的[匹配器](src/Matcher)从而定制自己的规则，
也可以使用 **$rule->addMatcher** 方法来给默认的规则实现(**[Ruler](src/Ruler.php)**)添加额外的匹配器，
最后使用 **$router->addRuler** 来添加规则。

已实现的匹配器可以在[这里](src/Matcher)找到：
* [Host](src/Matcher/HostMatcher.php)匹配器：用来匹配请求头 Host
* [Method](src/Matcher/MethodMatcher.php)匹配器：用来匹配请求方法
* [Path](src/Matcher/PathMatcher.php)匹配器：路径匹配器，用来匹配请求路径

##### 3. 初始化请求和响应对象
使用实现了 [PSR 7](http://www.php-fig.org/psr/psr-7/) 规范的第三方库来初始化构建请求对象和响应对象，如：
* [zend-diactoros](https://github.com/zendframework/zend-diactoros)
* [guzzle](https://github.com/guzzle/psr7)
* ...

##### 4. 路由请求获取到对应的处理器
```php
$attrs = [];
$handler = $router->route($request, $attrs);
```
**attrs** 用来接收路由过程中捕获到的参数信息，如路径规则中定义的的参数，具体返回值由使用的路由规则和匹配器决定。

##### 5. 使用处理器处理请求
```php
$response = $handler->handle($request, $response);
```

#### An Example Route
```php
// 1. create request handler
$handler = new CallbackHandler(function (ServerRequestInterface $request, ResponseInterface $response) {
    $username = $request->getAttribute('username');
    $body = $response->getBody();
    $body->write("Hello, $username!");
    return $response;
});

// 2. build the router rules
$router = new Router();
$router->get('/account/:username()', $handler);

// 3. initialize the request
$request = ServerRequest::fromGlobals();

// use the 'php://output' stream for the response body directly
$body = fopen('php://output', 'w+');
// 3. initialize the response
$response = new Response(200, [], $body);

// 4. route the request and get the handler
$attrs = [];
$handler = $router->route($request, $attrs);

// set the request attributes with the 'attrs' of route result
foreach ($attrs as $attrName => $attrValue) {
    $request = $request->withAttribute($attrName, $attrValue);
}

// 5. handle the request
$response = $handler->handle($request, $response);
```

### License
This project is licensed under the MIT License.

License can be found [here](LICENSE).
