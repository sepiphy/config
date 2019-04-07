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

use Symfony\Component\Finder\Finder;
use Sepiphy\PHPTools\Contracts\Config\ConfigContract;

class Config implements ConfigContract
{
    /**
     * @var string
     */
    protected $paths = [];

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
     */
    public function __construct(array $items = [])
    {
        $this->items = $items;
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
    public function load($paths): void
    {
        $paths = (array) $paths;

        foreach ($paths as $path) {
            $path = realpath($path);

            if (is_file($path)) {
                $name = pathinfo($path, PATHINFO_FILENAME);
                $this->items[$name] = require $path;
                $this->paths[$name] = $path;
                continue;
            }

            $finder = Finder::create()
                ->files()
                ->name('*.php')
                ->in($dir = $path)
            ;

            foreach ($finder as $file) {
                $configFileDir = dirname($file->getRealPath());

                if ($configFileDir === $dir) {
                    $name = $file->getBasename('.php');
                    $this->items[$name] = require $file->getRealPath();
                    $this->paths[$name] = $file->getRealPath();
                } else {
                    $name = $file->getRelativePath().'/'.$file->getBasename('.php');
                    $this->items[$name] = require $file->getRealPath();
                    $this->paths[$name] = $file->getRealPath();
                }
            }
        }
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
