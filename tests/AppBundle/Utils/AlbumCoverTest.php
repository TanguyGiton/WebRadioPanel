<?php
/**
 * Created by PhpStorm.
 * User: tangu
 * Date: 23/05/2016
 * Time: 03:25
 */

namespace tests\AppBundle\Utils;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AlbumCoverTest extends WebTestCase
{
    protected static $LIMIT = 5;
    protected $AlbumCover;

    public function setUp()
    {
        self::bootKernel();
        $this->AlbumCover = self::$kernel->getContainer()->get('app.albumcover');
    }

    public function testSearchOnItunes()
    {
        $result = $this->AlbumCover->searchOnItunes('baby', static::$LIMIT);

        static::assertCount(static::$LIMIT, $result);

        static::assertEquals('Justin Bieber', $result[0]['artist']);
        static::assertEquals('Baby (feat. Ludacris)', $result[0]['title']);
        static::assertEquals('http://is5.mzstatic.com/image/thumb/Music/v4/f5/2d/dc/f52ddcdc-bde2-b281-709a-8d1d1e998a87/source/100x100bb.jpg', $result[0]['albumcover']);
    }

    public function testGetImageOnItunes()
    {
        $result = $this->AlbumCover->getImageOnItunes('baby', 'Justin Bieber');

        static::assertNotNull($result);

        static::assertEquals('http://is5.mzstatic.com/image/thumb/Music/v4/f5/2d/dc/f52ddcdc-bde2-b281-709a-8d1d1e998a87/source/600x600bb.jpg', $result);
    }
}
