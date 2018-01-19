<?php
/**
 * User: York <lianyupeng1988@126.com>
 * Date: 2017-07-14 15:42
 */

namespace Tests\Matcher;

use PHPUnit\Framework\TestCase;
use York8\Router\Matcher\PathPattern;

class PathPatternTest extends TestCase
{
    /**
     * @param $pattern
     * @param $modifiers
     * @param $path
     * @param bool $matched
     * @param $attrs
     * @dataProvider dataProvider
     */
    public function testMatch($pattern, $modifiers, $path, bool $matched, array $attrs)
    {
        $matcher = new PathPattern($pattern, $modifiers);
        $output = [];
        $this->assertEquals($matched, $matcher->match($path, $output));
        $this->assertEquals($attrs, $output);
    }

    public function dataProvider()
    {
        $params = [
            [
                '/', 'i',
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
                '/foo', 'i',
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
                '/foo$', 'i',
                // 成功样本
                [
                    ['/foo'],
                    ['/Foo'],
                ],
                // 失败样本
                [
                    ['/foo/bar'],
                ],
            ],
            [
                '/foo', '',
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
                '/foo/:a(\\d+)/bar/:b(\\d+)-:c(\\d+)', 'i',
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
            [
                '/(\\w+)/account/:userId(\\d+)/:action(\\w+)/:param()', 'i',
                // 成功样本
                [
                    ['/foo/account/123/get/a', ['userId' => '123', 'action' => 'get', 'param' => 'a']],
                    ['/bar/account/123/set/york/kroy', ['userId' => '123', 'action' => 'set', 'param' => 'york']],
                    ['/bar/account/123/test/matcher', ['userId' => '123', 'action' => 'test', 'param' => 'matcher']],
                ],
                // 失败样本
                [
                    ['/bar'],
                    ['/foo/account/york/get/name'],
                    ['/foo-bar/account/123/get/a'],
                ],
            ],
        ];
        $out = [];
        foreach ($params as $item) {
            $pattern = $item[0];
            $modifiers = $item[1];
            $suc = $item[2];
            $fal = $item[3];
            if (!empty($suc)) foreach ($suc as $v) {
                $path = $v[0];
                $attrs = $v[1] ?? [];
                $out[] = [
                    $pattern, $modifiers, $path, true, $attrs
                ];
            }
            if (!empty($fal)) foreach ($fal as $v) {
                $path = $v[0];
                $out[] = [
                    $pattern, $modifiers, $path, false, []
                ];
            }
        }
        return $out;
    }
}
