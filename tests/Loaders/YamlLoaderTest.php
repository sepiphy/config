<?php declare(strict_types=1);

/*
 * This file is part of the Sepiphy package.
 *
 * (c) Quynh Xuan Nguyen <seriquynh@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Sepiphy\Config\Loaders;

use Sepiphy\Config\LoaderInterface;
use Sepiphy\Config\Loaders\YamlLoader;

/**
 * @author Quynh Xuan Nguyen <seriquynh@gmail.com>
 */
class YamlLoaderTest extends LoaderTest
{
    /**
     * {@inheritdoc}
     */
    protected function createLoader(): LoaderInterface
    {
        return new YamlLoader();
    }

    public function testLoadFile()
    {
        $a = require $this->configDir.'/a.php';

        $items = $this->loader->load($this->configDir.'/a.yml');

        $this->assertEquals(compact('a'), $items);
    }
}
