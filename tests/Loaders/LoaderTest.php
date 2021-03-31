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

use PHPUnit\Framework\TestCase;
use Sepiphy\Config\LoaderInterface;

/**
 * @author Quynh Xuan Nguyen <seriquynh@gmail.com>
 */
abstract class LoaderTest extends TestCase
{
    /**
     * The config directory.
     *
     * @var string
     */
    protected $configDir;

    /**
     * The LoaderInterface implementation.
     *
     * @var LoaderInterface
     */
    protected $loader;

    /**
     * Create a new LoaderInterface implementation.
     *
     * @return LoaderInterface
     */
    abstract protected function createLoader(): LoaderInterface;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->configDir = realpath(__DIR__.'/../fixtures/config');
        $this->loader = $this->createLoader();
    }

    public function testLoadDirectory()
    {
        $a = require $this->configDir.'/a.php';
        $b = require $this->configDir.'/b.php';
        $nested = [
            'a' => require $this->configDir.'/nested/a.php',
            'b' => require $this->configDir.'/nested/b.php',
        ];

        $items = $this->loader->load($this->configDir);

        $this->assertEquals(compact('a', 'b', 'nested'), $items);
    }
}
