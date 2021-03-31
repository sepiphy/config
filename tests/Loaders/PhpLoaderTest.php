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

use RuntimeException;
use Sepiphy\Config\Loaders\PhpLoader;
use Sepiphy\Config\LoaderInterface;

/**
 * @author Quynh Xuan Nguyen <seriquynh@gmail.com>
 */
class PhpLoaderTest extends LoaderTest
{
    /**
     * {@inheritdoc}
     */
    protected function createLoader(): LoaderInterface
    {
        return new PhpLoader();
    }

    public function testLoadFile()
    {
        $a = require $this->configDir.'/a.php';

        $items = $this->loader->load($this->configDir.'/a.php');

        $this->assertEquals(compact('a'), $items);
    }

    public function testCouldNotLoadFile()
    {
        $path = __DIR__ . '/../fixtures/invalidconfig/a.php';

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageMatches('/^The file \[(.*)\] must return an array\.$/');

        $this->loader->load($path);
    }
}
