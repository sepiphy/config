<?php declare(strict_types=1);

/*
 * This file is part of the Sepiphy package.
 *
 * (c) Quynh Xuan Nguyen <seriquynh@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sepiphy\Config;

use ArrayAccess;

/**
 * @author Quynh Xuan Nguyen <seriquynh@gmail.com>
 */
interface ConfigInterface extends ArrayAccess
{
    /**
     * Get all configuration items.
     *
     * @return array
     */
    public function all(): array;

    /**
     * Determine whether the configuration item exists.
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * Get a configuration item.
     *
     * @param string $key
     * @param mixed $fallback
     * @return mixed
     */
    public function get(string $key, $fallback = null);

    /**
     * Set a configuration item.
     *
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function set(string $key, $value);
}
