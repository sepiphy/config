<?php declare(strict_types=1);

/*
 * This file is part of the Sepiphy package.
 *
 * (c) Quynh Xuan Nguyen <seriquynh@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sepiphy\PHPTools\Config\Loaders;

use RuntimeException;
use Sepiphy\PHPTools\Contracts\Config\LoaderInterface;
use Symfony\Component\Finder\Finder;

/**
 * @author Quynh Xuan Nguyen <seriquynh@gmail.com>
 */
abstract class Loader implements LoaderInterface
{
    /**
     * {@inheritdoc}
     */
    public function load($resources): array
    {
        $items = [];

        $paths = is_array($resources) ? $resources : func_get_args();

        foreach ($paths as $path) {
            $path = realpath($path);

            if ($path === false) {
                continue;
            }

            if (is_file($path)) {
                $name = pathinfo($path, PATHINFO_FILENAME);
                $items[$name] = $this->parse($path);
                continue;
            }

            foreach ($this->filter($dir = $path) as $extention => $files) {
                foreach ($files as $file) {
                    $name = $file->getBasename($extention);

                    $fileDir = dirname($file->getRealPath());

                    if ($fileDir === $dir) {
                        $items[$name] = $this->parse($file->getRealPath());
                    } else {
                        $items[$file->getRelativePath()][$name] = $this->parse($file->getRealPath());
                    }
                }
            }
        }

        return $items;
    }

    /**
     * Filter files in the given directory.
     *
     * @param string $directory
     * @return Finder[]
     */
    protected function filter(string $directory): array
    {
        $files = [];

        foreach ($this->extensions() as $extention) {
            $pattern = '*.'.ltrim($extention, '.');
            $files[$extention] = Finder::create()->files()->name($pattern)->in($directory);
        }

        return $files;
    }

    /**
     * Get the supported extensions.
     *
     * @return string[]
     */
    abstract protected function extensions(): array;

    /**
     * Parse the given file.
     *
     * @param string $path
     * @return array
     *
     * @throws RuntimeException
     */
    abstract protected function parse(string $path): array;
}
