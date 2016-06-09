<?php

namespace tests\AppBundle\Utils;


use AppBundle\Entity\Song;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SongProviderTest extends WebTestCase
{
    protected $songProvider;

    protected $streamingInfo;

    public function setUp()
    {
        self::bootKernel();
        $container = self::$kernel->getContainer();
        $this->songProvider = $container->get('app.songprovider');
        $this->streamingInfo = $container->get('app.streaminginfo');
    }

    public function testProcessCurrentSong()
    {
        $song = $this->songProvider->processCurrentSong();
        $streaminginfo = $this->streamingInfo->getCurrentSong();

        static::assertInstanceOf(Song::class, $song);

        static::assertNotNull($song->getTitle());
        static::assertNotNull($song->getArtist());
        static::assertNotNull($song->getLifetime());
        static::assertNotNull($song->getUid());

        static::assertEquals($streaminginfo['title'], $song->getTitle());
        static::assertEquals($streaminginfo['artist'], $song->getArtist());
        static::assertEquals($streaminginfo['lifetime'], $song->getLifetime());
        static::assertEquals($streaminginfo['uid'], $song->getUid());

        if (!empty($streaminginfo['albumcover'])) {
            static::assertNotNull($song->getImageName());
        }
    }
}
