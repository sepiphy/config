<?php declare(strict_types=1);

/*
 * This file is part of the Sepiphy package.
 *
 * (c) Quynh Xuan Nguyen <seriquynh@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Sepiphy\Config;

use PHPUnit\Framework\TestCase;
use Sepiphy\Config\Config;

/**
 * @author Quynh Xuan Nguyen <seriquynh@gmail.com>
 */
class ConfigTest extends TestCase
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var array
     */
    protected $items = [];

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->config = new Config($this->items = [
            'app' => [
                'name' => 'sepiphy',
                'version' => 'testing',
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        unset($_SERVER['FOO'], $_ENV['BAR'], $_SERVER['FOO_BAR'], $_ENV['FOO_BAR']);
    }

    public function testAll()
    {
        $this->assertEquals($this->items, $this->config->all());
    }

    public function testHas()
    {
        $this->assertTrue($this->config->has('app'));
        $this->assertTrue($this->config->has('app.name'));
        $this->assertTrue($this->config->has('app.version'));
    }

    public function testGet()
    {
        $this->assertSame('sepiphy', $this->config->get('app.name'));
        $this->assertSame('testing', $this->config->get('app.version'));
        $this->assertNull($this->config->get('unknown'));
        $this->assertSame('fallback', $this->config->get('unknown', 'fallback'));
    }

    public function testSet()
    {
        $this->config->set('app', [
            'name' => 'foo',
            'version' => 'bar',
        ]);

        $this->assertSame('foo', $this->config->get('app.name'));
        $this->assertSame('bar', $this->config->get('app.version'));

        $this->config->set('app.name', 'foo1');
        $this->config->set('app.version', 'bar1');

        $this->assertSame('foo1', $this->config->get('app.name'));
        $this->assertSame('bar1', $this->config->get('app.version'));

        $this->config->set('app.key', 'foobar');
        $this->config->set('app.debug', 'on/off');

        $this->assertSame('foobar', $this->config->get('app.key'));
        $this->assertSame('on/off', $this->config->get('app.debug'));
    }

    public function testLoadMethod()
    {
        $config = new Config();

        $this->assertEquals([], $config->all());

        $config->load(__DIR__.'/fixtures/config2');

        $this->assertEquals([
            'app' => [
                'name' => 'Sepiphy',
                'version' => 'v1.0.0',
            ],
            'database' => [
                'default' => 'sqlite',
                'connections' => [
                    'sqlite' => [
                        'driver' => 'sqlite',
                        'database' => ':memory:',
                    ]
                ],
            ],
        ], $config->all());
    }
}
