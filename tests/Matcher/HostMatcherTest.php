<?php
/**
 * User: York <lianyupeng1988@126.com>
 * Date: 2017-07-14 11:25
 */

namespace Tests\Matcher;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Tests\RequestFactory;
use York8\Router\Matcher\HostMatcher;

class HostMatcherTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     * @param RequestInterface $request
     */
    public function testMatch(RequestInterface $request) {
        $host = $request->getUri()->getHost();
        $matcher = new HostMatcher([$host, strtoupper($host)]);
        $this->assertTrue($matcher->match($request));
        $matcher = new HostMatcher(['www.nomatch.com']);
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
