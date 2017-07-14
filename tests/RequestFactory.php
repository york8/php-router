<?php
/**
 * User: York <lianyupeng1988@126.com>
 * Date: 2017-07-14 15:03
 */

namespace Tests;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;

class RequestFactory implements \Iterator
{
    /**
     * @var RequestInterface[]
     */
    private $arr = [];

    private $ind = 0;

    private $length = 0;

    /**
     * @var mixed[][]
     */
    private $requestParams = [
        ['www.york8.com', 'get', '', []],
        ['www.york8.org', 'get', '', []],
        ['github.com', 'get', '/york8', []],
    ];

    public function __construct(array $requests = null)
    {
        if (!empty($requests)) {
            $this->requestParams = $requests;
        }
        $this->length = count($this->requestParams);
    }

    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        if ($this->ind >= $this->length) {
            return null;
        }

        $request = $this->arr[$this->ind] ?? null;
        if (!$request) {
            $request = self::createRequest(...$this->requestParams[$this->ind]);
            $this->arr[$this->ind] = $request;
        }
        return $request;
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        if ($this->ind < $this->length) {
            $this->ind++;
        }
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        if ($this->ind < $this->length) {
            return $this->ind;
        } else {
            return null;
        }
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        return $this->ind > -1 && $this->ind < $this->length;
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        $this->ind = 0;
    }

    public static function createRequest($host, $method = 'GET', $path = '', ?array $headers = [])
    {
        $uri = "http://$host$path";
        return new Request($method, $uri, $headers);
    }
}
