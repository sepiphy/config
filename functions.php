<?php declare(strict_types=1);

/*
 * This file is part of the Sepiphy package.
 *
 * (c) Quynh Xuan Nguyen <seriquynh@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

if (! function_exists('env')) {
    /**
     * @param string $key
     * @param mixed $fallback
     * @return mixed
     */
    function env(string $key, $fallback = null)
    {
        if (array_key_exists($key, $_SERVER)) {
            $value = $_SERVER[$key];
        } elseif (array_key_exists($key, $_ENV)) {
            $value = $_ENV[$key];
        } else {
            return $fallback;
        }

        if (! is_string($value)) {
            return $value;
        }

        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return null;
            default:
                return $value;
        }
    }
}
