<?php

namespace Tests\AppBundle\Utils;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RadionomyInfoTest extends WebTestCase
{
    public $StreamInfo;

    public $cache;

    public function setUp()
    {
        self::bootKernel();
        $container = self::$kernel->getContainer();
        $this->StreamInfo = $container->get('app.streaminginfo');
        $this->cache = $container->get('phpfilecache');
    }

    public function testGetCurrentSong()
    {
        $this->cache->delete('currentsong');

        $data = $this->StreamInfo->getCurrentSong();

        static::assertNotNull($data['title']);
        static::assertNotNull($data['artist']);
        static::assertNotNull($data['albumcover']);
        static::assertNotNull($data['lifetime']);

        static::assertTrue(is_string($data['title']));
        static::assertTrue(is_string($data['artist']));
        static::assertTrue(is_string($data['albumcover']));
        static::assertTrue(is_int($data['lifetime']));

        $data2 = $this->StreamInfo->getCurrentSong(false);

        static::assertArrayNotHasKey('albumcover', $data2);
    }

    public function testGetCurrentAudience()
    {
        $this->cache->delete('currentaudience');

        $data = $this->StreamInfo->getCurrentAudience();

        static::assertNotNull($data['currentaudience']);
        static::assertNotNull($data['lifetime']);

        static::assertTrue(is_int($data['currentaudience']));
        static::assertTrue(is_int($data['lifetime']));
    }
}
