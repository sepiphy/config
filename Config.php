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

use Sepiphy\PHPTools\Config\Loaders\PhpLoader;
use Sepiphy\PHPTools\Contracts\Config\ConfigContract;
use Sepiphy\PHPTools\Contracts\Config\LoaderContract;

class Config implements ConfigContract
{
    /**
     * @var LoaderContract
     */
    protected $loader;

    /**
     * @var array
     */
    protected $items = [];

    /**
     * @param string $key
     * @param mixed $fallback
     * @return mixed
     */
    public static function env(string $key, $fallback = null)
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

    /**
     * @param array $items
     * @param LoaderContract $loader
     */
    public function __construct(array $items = [], LoaderContract $loader = null)
    {
        $this->items = $items;
        $this->loader = $loader ?: new PhpLoader;
    }

    /**
     * {@inheritdoc}
     */
    public function all(): array
    {
        return $this->items;
    }

    /**
     * {@inheritdoc}
     */
    public function has(string $key): bool
    {
        return $this->hasItemsKey($this->items, $key);
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $key, $fallback = null)
    {
        return $this->getItemsKey($this->items, $key, $fallback);
    }

    /**
     * {@inheritdoc}
     */
    public function set(string $key, $value): self
    {
        $this->setItemsKey($this->items, $key, $value);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function load($resources): void
    {
        $this->items = array_merge($this->items, $this->loader->load($resources));
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset): bool
    {
        return $this->has($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value): void
    {
        $this->set($offset, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset): void
    {
        if ($this->offsetExists($offset)) {
            unset($this->items[$offset]);
        }
    }

    /**
     * @return LoaderContract
     */
    public function getLoader(): LoaderContract
    {
        return $this->loader;
    }

    /**
     * @param LoaderContract $loader
     * @return self
     */
    public function setLoader(LoaderContract $loader): self
    {
        $this->loader = $loader;

        return $this;
    }

    /**
     * @param array $items
     * @param string $key
     * @return bool
     */
    protected function hasItemsKey(array $items, string $key): bool
    {
        if (array_key_exists($key, $items)) {
            return true;
        }

        if (strpos($key, '.') > 0) {
            [$left, $right] = explode('.', $key, 2);

            if (array_key_exists($left, $items) && is_array($items[$left])) {
                return $this->hasItemsKey($items[$left], $right);
            }
        }

        return false;
    }

    /**
     * @param array $items
     * @param string $key
     * @param mixed $fallback
     * @return bool
     */
    protected function getItemsKey(array $items, string $key, $fallback = null)
    {
        if (array_key_exists($key, $items)) {
            return $items[$key];
        }

        if (strpos($key, '.') > 0) {
            [$left, $right] = explode('.', $key, 2);

            if (array_key_exists($left, $items) && is_array($items[$left])) {
                return $this->getItemsKey($items[$left], $right, $fallback);
            }
        }

        return $fallback;
    }

    /**
     * @param array $items
     * @param string $key
     * @param mixed $value
     * @return void
     */
    protected function setItemsKey(array &$items, string $key, $value): void
    {
        if (strpos($key, '.') > 0) {
            [$left, $right] = explode('.', $key, 2);

            if (array_key_exists($left, $items) && is_array($items[$left])) {
                $this->setItemsKey($items[$left], $right, $value);
            } else {
                $items[$left] = [];

                $this->setItemsKey($items[$left], $right, $value);
            }
        }

        $items[$key] = $value;
    }
}
