<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CronController extends Controller
{
    /**
     * @Route("cron/process-song", name="cron.process_song")
     * @throws \InvalidArgumentException
     */
    public function processSongAction()
    {
        $this->get('app.songprovider')->processCurrentSong();

        return new Response('ok');
    }
}
