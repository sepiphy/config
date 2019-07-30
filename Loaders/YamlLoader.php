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

use Symfony\Component\Yaml\Yaml;

/**
 * @author Quynh Xuan Nguyen <seriquynh@gmail.com>
 */
class YamlLoader extends Loader
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
                $items[$name] = Yaml::parseFile($path);
                continue;
            }

            $finder = $this->filterFiles($dir = $path, '.yml');

            foreach ($finder as $file) {
                $name = $file->getBasename('.yml');

                $configFileDir = dirname($file->getRealPath());

                if ($configFileDir === $dir) {
                    $items[$name] = Yaml::parseFile($file->getRealPath());
                } else {
                    $items[$file->getRelativePath()][$name] = Yaml::parseFile($file->getRealPath());
                }
            }

            $finder = $this->filterFiles($dir = $path, '.yaml');

            foreach ($finder as $file) {
                $name = $file->getBasename('.yaml');

                $configFileDir = dirname($file->getRealPath());

                if ($configFileDir === $dir) {
                    $items[$name] = Yaml::parseFile($file->getRealPath());
                } else {
                    $items[$file->getRelativePath()][$name] = Yaml::parseFile($file->getRealPath());
                }
            }
        }

        return $items;
    }
}
