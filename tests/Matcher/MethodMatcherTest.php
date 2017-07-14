<?php
/**
 * User: York <lianyupeng1988@126.com>
 * Date: 2017-07-14 15:01
 */

namespace Tests\Matcher;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Tests\RequestFactory;
use York8\Router\Matcher\MethodMatcher;

class MethodMatcherTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     * @param RequestInterface $request
     */
    public function testMatch(RequestInterface $request) {
        $method = $request->getMethod();
        $matcher = new MethodMatcher([$method, strtoupper($method)]);
        $this->assertTrue($matcher->match($request));
        $matcher = new MethodMatcher([ '_' . $method]);
        $this->assertFalse($matcher->match($request));
    }

    public function dataProvider() {
        $factory = new RequestFactory();
        $arr = [];
        foreach ($factory as $request) {
            $arr[] = [$request];
        }
        return $arr;
    }
}
