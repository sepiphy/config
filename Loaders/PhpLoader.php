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

/**
 * @author Quynh Xuan Nguyen <seriquynh@gmail.com>
 */
class PhpLoader extends Loader
{
    /**
     * {@inheritdoc}
     */
    protected function extensions(): array
    {
        return ['.php'];
    }

    /**
     * {@inheritdoc}
     */
    protected function parse(string $path): array
    {
        $items = require $path;

        if (is_array($items = require $path)) {
            return $items;
        }

        throw new RuntimeException(
            sprintf('The file [%s] must return an array.', $path)
        );
    }
}
