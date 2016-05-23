<?php

namespace Tests\AppBundle\Utils;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RadionomyInfoTest extends WebTestCase
{
    public $StreamInfo;

    public function setUp()
    {

        self::bootKernel();
        $this->StreamInfo = self::$kernel->getContainer()->get('app.streaminginfo');
    }

    public function testGetCurrentSong()
    {
        $data = $this->StreamInfo->getCurrentSong();

        $this->AssertNotNull($data['title']);
        $this->AssertNotNull($data['artist']);
        $this->AssertNotNull($data['lifetime']);

        $this->AssertTrue(is_string($data['title']));
        $this->AssertTrue(is_string($data['artist']));
        $this->AssertTrue(is_int($data['lifetime']));
    }
}
