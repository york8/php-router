<?php
/**
 * User: York <lianyupeng1988@126.com>
 * Date: 2017-07-14 15:41
 */

namespace Tests\Matcher;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Tests\RequestFactory;
use York8\Router\Matcher\PathMatcher;

class PathMatcherTest extends TestCase
{
    /**
     * @param RequestInterface $request
     * @param string[] $patterns
     * @param string $prefix
     * @param bool $caseSensitive
     * @param bool $matched
     * @param string[] $attrs
     * @dataProvider dataProvider
     */
    function testMatch(RequestInterface $request, array $patterns, $prefix,
                       $caseSensitive = false, bool $matched = true, ?array $attrs = [])
    {
        $matcher = new PathMatcher($patterns, $prefix, $caseSensitive);
        $output = [];
        $this->assertEquals($matched, $matcher->match($request, $output));
        $this->assertEquals($attrs, $output);
    }

    public function dataProvider()
    {
        $params = [
            [
                '', ['/'], false,
                // 成功样本
                [
                    ['/'],
                    ['/foo'],
                    ['/foo/bar'],
                ],
                // 失败样本
                [
                    [''],
                ],
            ],
            [
                '', ['/foo'], false,
                // 成功样本
                [
                    ['/foo'],
                    ['/foo/bar'],
                ],
                // 失败样本
                [
                    ['/'],
                ],
            ],
            [
                '', ['/foo'], true,
                // 成功样本
                [
                    ['/foo'],
                    ['/foo/bar'],
                ],
                // 失败样本
                [
                    ['/bar'],
                    ['/Foo'],
                ],
            ],
            [
                '', ['/foo$'], false,
                // 成功样本
                [
                    ['/foo'],
                ],
                // 失败样本
                [
                    ['/foo/bar'],
                    ['/Foo/'],
                ],
            ],
            [
                '/foo', ['/:a(\\d+)/bar/:b(\\d+)-:c(\\d+)'], false,
                // 成功样本
                [
                    ['/foo/1/bar/2-3', ['a' => '1', 'b' => '2', 'c' => '3']],
                    ['/foo/11/bar/22-33.html', ['a' => '11', 'b' => '22', 'c' => '33']],
                    ['/FoO/11/bar/22-33.html', ['a' => '11', 'b' => '22', 'c' => '33']],
                ],
                // 失败样本
                [
                    ['/bar'],
                    ['/foo'],
                    ['/foo/1/bar/2.html'],
                ],
            ],
        ];

        $host = 'www.york8.org';
        $method = 'GET';
        $out = [];
        foreach ($params as $item) {
            $prefix = $item[0];
            $pattern = $item[1];
            $caseSensitive = $item[2];
            $suc = $item[3];
            $fal = $item[4];
            if (!empty($suc)) foreach ($suc as $v) {
                $path = $v[0];
                $attrs = $v[1] ?? [];
                $request = RequestFactory::createRequest($host, $method, $path);
                $out[] = [
                    $request, $pattern, $prefix, $caseSensitive, true, $attrs
                ];
            }
            if (!empty($fal)) foreach ($fal as $v) {
                $path = $v[0];
                $request = RequestFactory::createRequest($host, $method, $path);
                $out[] = [
                    $request, $pattern, $prefix, $caseSensitive, false, []
                ];
            }
        }
        return $out;
    }
}
