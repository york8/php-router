<?php
/**
 * User: York <lianyupeng1988@126.com>
 * Date: 2018-01-09 17:14
 */

namespace Matcher;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Tests\RequestFactory;
use York8\Router\Matcher\HeaderMatcher;

class HeaderMathcerTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     * @param RequestInterface $request
     */
    public function testMatch(RequestInterface $request)
    {
        $matcher = new HeaderMatcher([
            'Host' => @$request->getHeader('host')[0],
            'Content-Type' => @$request->getHeader('content-type')[0],
        ]);
        $this->assertTrue($matcher->match($request));
        $matcher = new HeaderMatcher([
            'Host' => 'no-match',
            'Content-Type' => 'no-match',
        ]);
        $this->assertFalse($matcher->match($request));
    }

    public function dataProvider()
    {
        $factory = new RequestFactory();
        $arr = [];
        foreach ($factory as $request) {
            $arr[] = [$request];
        }
        return $arr;
    }
}