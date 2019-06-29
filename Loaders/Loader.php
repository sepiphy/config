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

use Symfony\Component\Finder\Finder;
use Sepiphy\PHPTools\Contracts\Config\LoaderInterface;

abstract class Loader implements LoaderInterface
{
    /**
     * @param string $dir
     * @param string $ext
     * @return Finder
     */
    protected function filterFiles(string $dir, string $ext = '.php'): Finder
    {
        return Finder::create()
            ->files()
            ->name('*.'.ltrim($ext, '.'))
            ->in($dir)
        ;
    }
}
