# PHP-Router

[![Latest Stable Version](https://poser.pugx.org/york8/router/v/stable)](https://packagist.org/packages/york8/router) 
[![Total Downloads](https://poser.pugx.org/york8/router/downloads)](https://packagist.org/packages/york8/router) 
[![Latest Unstable Version](https://poser.pugx.org/york8/router/v/unstable)](https://packagist.org/packages/york8/router) 
[![License](https://poser.pugx.org/york8/router/license)](https://packagist.org/packages/york8/router)

一个简单的基于 [PSR 7](http://www.php-fig.org/psr/psr-7/) 的 PHP 路由器，良好的分层和扩展结构

* 基于 [PSR 7](http://www.php-fig.org/psr/psr-7/) HTTP 消息接口
* 面向接口编程，灵活扩展
* RESTful 风格路由
* PCRE 正则匹配

### Authors

- [York](https://github.com/york8)

### Easy to install with composer
```sh
$ composer require york8/router
```

### Usage

#### How to use：

##### 1. 创建带默认路由失败处理器的路由器对象，
```php
$router = new Router(function notFound() { ... });
```

##### 2. 构建路由规则，将规则与处理器关联起来
```php
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

路径匹配器支持正则匹配，定义如下：
```php
class PathMatcher implements MatcherInterface
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
        // ...
    }

    /**
     * @param string $path
     * @param string[]|null $attrs
     * @return bool
     * @throws \RuntimeException
     */
    public function match($path, array &$attrs = null): bool
    {
        // ...
    }

    // ...
}
```

使用说明：
1. 正则表达式必须使用圆括号括起来“**(patther)**”，括号内的内容可以为空，表示匹配任意字符
2. 命名正则表达式，冒号开头，形如"**:name**"开始的单词命名紧跟的正则表达式，路径匹配成功后，
   命名正则匹配到的路径值将返回给属性结果，可以通过 attrs[name] 捕获
3. 路径默认是前缀匹配的，可以在路径末尾添加 "$" 来变成全路径匹配，如 "/foo" 可以匹配 "/foo/bar"，但 "/foo$" 则不能能匹配 "/foo/bar"

格式示例如下：
```
/path/to/foo
```
普通路径前缀匹配；
```
path/to/(\\w+)
```
非命名的正则表达式，不捕获任何路径值；
```
/path/to/:name/list
```
使用命名的空正则表达式，将捕获到路径中的某一节并将其赋值给 "name" 属性，通过 attrs 返回；
```
/paht/to/:foo(\\w+)-:bar(\\d+)/account/:name
```
使用命名的表达式，捕获匹配到的路径值并赋值给 "foo"、"bar" 和 "name" 属性，通过 attrs 返回。

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

// 1. create Router with default not found handler
$router = new Router(function (ServerRequestInterface $request, ResponseInterface $response) {
    $response = $response->withStatus(404);
    $body = $response->getBody();
    $body->write('Not Found: ' . $request->getUri()->getPath());
    return $response;
});
// 2. build the router rules
$router->get(
    '/account/:username',
    function (ServerRequestInterface $request, ResponseInterface $response) {
        $username = $request->getAttribute('username');
        $body = $response->getBody();
        $body->write("Hello, $username!");
        return $response;
    }
);

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
