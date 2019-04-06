<?php declare(strict_types=1);

/*
 * This file is part of the Sepiphy package.
 *
 * (c) Quynh Xuan Nguyen <seriquynh@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sepiphy\PHPTools\Config;

use ArrayAccess;

interface ConfigInterface extends ArrayAccess
{
    /**
     * @return array
     */
    public function all(): array;

    /**
     * @param mixed $key
     * @return mixed
     */
    public function get($key);

    /**
     * @param string|string[] $paths The dirs or files to load.
     * @return void
     */
    public function load($paths): void;
}
