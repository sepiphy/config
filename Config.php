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

class Config implements ConfigInterface
{
    /**
     * @var string
     */
    protected $paths = [];

    /**
     * @var array
     */
    protected $items = [];

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
    public function get($key)
    {
        return $this->offsetGet($key);
    }

    /**
     * {@inheritdoc}
     */
    public function load($paths): void
    {
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
                ->in($path);

            foreach ($finder as $file) {
                $name = $file->getBasename('.php');
                $this->items[$name] = require $file->getRealPath();
                $this->paths[$name] = $file->getRealPath();
            }
        }
    }

    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->items);
    }

    public function offsetGet($offset)
    {
        return $this->offsetExists($offset) ? $this->items[$offset] : null;
    }

    public function offsetSet($offset, $value): void
    {
        $this->items[$offset] = $value;
    }

    public function offsetUnset($offset): void
    {
        if ($this->offsetExists($offset)) {
            unset($this->items[$offset]);
        }
    }
}
