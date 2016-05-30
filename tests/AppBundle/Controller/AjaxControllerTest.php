<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AjaxControllerTest extends WebTestCase
{
    public function testCurrentSong()
    {
        $client = static::createClient();

        $client->request('GET', '/ajax/currentsong.json', [], [], [
            'HTTP_X-Requested-With' => 'XMLHttpRequest',
        ]);

        $content = $client->getResponse()->getContent();

        static::assertContains('title', $content);
        static::assertContains('artist', $content);
        static::assertContains('lifetime', $content);
        static::assertContains('callback', $content);
        static::assertContains('albumcover', $content);

        $data = (array)json_decode($content);

        static::assertArrayHasKey('title', $data);
        static::assertArrayHasKey('artist', $data);
        static::assertArrayHasKey('lifetime', $data);
        static::assertArrayHasKey('callback', $data);
        static::assertArrayHasKey('albumcover', $data);

        static::assertTrue(is_string($data['title']));
        static::assertTrue(is_string($data['artist']));
        static::assertTrue(is_string($data['albumcover']));
        static::assertTrue(is_int($data['lifetime']));
        static::assertTrue(is_int($data['callback']));
    }

    public function testCurrentAudience()
    {
        $client = static::createClient();

        $client->request('GET', '/ajax/currentaudience.json', [], [], [
            'HTTP_X-Requested-With' => 'XMLHttpRequest',
        ]);

        $content = $client->getResponse()->getContent();

        static::assertContains('currentaudience', $content);
        static::assertContains('lifetime', $content);
        static::assertContains('callback', $content);

        $data = (array)json_decode($content);

        static::assertArrayHasKey('currentaudience', $data);
        static::assertArrayHasKey('lifetime', $data);
        static::assertArrayHasKey('callback', $data);

        static::assertTrue(is_int($data['currentaudience']));
        static::assertTrue(is_int($data['lifetime']));
        static::assertTrue(is_int($data['callback']));
    }
}
