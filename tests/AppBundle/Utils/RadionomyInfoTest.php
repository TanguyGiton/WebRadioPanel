<?php

namespace Tests\AppBundle\Utils;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RadionomyInfoTest extends WebTestCase
{
    protected $StreamInfo;

    protected $cache;

    public function setUp()
    {
        self::bootKernel();
        $container = self::$kernel->getContainer();
        $this->StreamInfo = $container->get('app.streaminginfo');
        $this->cache = $container->get('phpfilecache');
    }

    public function testGetCurrentSong()
    {
        $data = $this->StreamInfo->getCurrentSong();

        static::assertNotNull($data['title']);
        static::assertNotNull($data['artist']);
        static::assertNotNull($data['albumcover']);
        static::assertNotNull($data['lifetime']);
        static::assertNotNull($data['uid']);

        static::assertTrue(is_string($data['title']));
        static::assertTrue(is_string($data['artist']));
        static::assertTrue(is_string($data['albumcover']));
        static::assertTrue(is_int($data['lifetime']));
        static::assertTrue(is_string($data['uid']));

        $data2 = $this->StreamInfo->getCurrentSong(false);

        static::assertArrayNotHasKey('albumcover', $data2);
    }

    public function testGetCurrentAudience()
    {
        $data = $this->StreamInfo->getCurrentAudience();

        static::assertNotNull($data['currentaudience']);
        static::assertNotNull($data['lifetime']);

        static::assertTrue(is_int($data['currentaudience']));
        static::assertTrue(is_int($data['lifetime']));
    }

    public function tearDown()
    {
        $this->cache->delete('currentsong');
        $this->cache->delete('currentaudience');
    }
}
