<?php declare(strict_types=1);

/*
 * This file is part of the Sepiphy package.
 *
 * (c) Quynh Xuan Nguyen <seriquynh@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sepiphy\Config\Loaders;

use RuntimeException;
use Symfony\Component\Yaml\Yaml;

/**
 * @author Quynh Xuan Nguyen <seriquynh@gmail.com>
 */
class YamlLoader extends Loader
{
    /**
     * {@inheritdoc}
     */
    protected function extensions(): array
    {
        return ['.yml', '.yaml'];
    }

    /**
     * {@inheritdoc}
     */
    protected function parse(string $path): array
    {
        if (class_exists('Symfony\Component\Yaml\Yaml')) {
            return Yaml::parseFile($path);
        }

        throw new RuntimeException(
            'Symfony\Component\Yaml\Yaml class doesn\'t exist. Run "composer require symfony/yaml".'
        );
    }
}
