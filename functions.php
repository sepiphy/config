<?php declare(strict_types=1);

/*
 * This file is part of the Sepiphy package.
 *
 * (c) Quynh Xuan Nguyen <seriquynh@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Sepiphy\PHPTools\Config\Config;

if (! function_exists('env')) {
    /**
     * @see Config::env()
     */
    function env(string $key, $fallback = null)
    {
        return Config::env($key, $fallback);
    }
}
