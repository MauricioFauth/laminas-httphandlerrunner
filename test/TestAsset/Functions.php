<?php
/**
 * Zend Framework (https://framework.zend.com/)
 *
 * This file exists to allow overriding the various output-related functions
 * in order to test what happens during the `Server::listen()` cycle.
 *
 * These functions include:
 *
 * - headers_sent(): we want to always return false so that headers will be
 *   emitted, and we can test to see their values.
 * - header(): we want to aggregate calls to this function.
 *
 * The HeaderStack class then aggregates that information for us, and the test
 * harness resets the values pre and post test.
 *
 * @see       https://github.com/zendframework/zend-serverhandler-runnder for the canonical source repository
 * @copyright Copyright (c) 2018 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-serverhandler-runnder/blob/master/LICENSE.md New BSD License
 */

namespace ZendTest\HttpHandlerRunner\TestAsset;

/**
 * Store output artifacts
 */
class HeaderStack
{
    /**
     * @var string[][]
     */
    private static $data = [];

    /**
     * Reset state
     */
    public static function reset()
    {
        self::$data = [];
    }

    /**
     * Push a header on the stack
     *
     * @param string[] $header
     */
    public static function push(array $header)
    {
        self::$data[] = $header;
    }

    /**
     * Return the current header stack
     *
     * @return string[][]
     */
    public static function stack()
    {
        return self::$data;
    }

    /**
     * Verify if there's a header line on the stack
     *
     * @param string $header
     *
     * @return bool
     */
    public static function has($header)
    {
        foreach (self::$data as $item) {
            if ($item['header'] === $header) {
                return true;
            }
        }

        return false;
    }
}

/**
 * Have headers been sent?
 *
 * @return false
 */
function headers_sent()
{
    return false;
}

/**
 * Emit a header, without creating actual output artifacts
 *
 * @param string   $string
 * @param bool     $replace
 * @param int|null $statusCode
 */
function header($string, $replace = true, $statusCode = null)
{
    HeaderStack::push(
        [
            'header'      => $string,
            'replace'     => $replace,
            'status_code' => $statusCode,
        ]
    );
}
