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

use Sepiphy\PHPTools\Contracts\Config\LoaderInterface;
use Symfony\Component\Finder\Finder;

/**
 * @author Quynh Xuan Nguyen <seriquynh@gmail.com>
 */
abstract class Loader implements LoaderInterface
{
    /**
     * Filter files with a specific extension in the given directory.
     *
     * @param string $directory
     * @param string|array $extentions
     * @return Finder
     */
    protected function filterFiles(string $directory, $extentions = '.php'): Finder
    {
        $patterns = [];
        foreach ((array) $extentions as $extention) {
            $patterns[] = '*.'.ltrim($extention, '.');
        }
        return Finder::create()
            ->files()
            ->name($patterns)
            ->in($directory)
        ;
    }
}
