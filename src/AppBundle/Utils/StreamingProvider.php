<?php

namespace AppBundle\Utils;


abstract class StreamingProvider
{
    abstract public function getCurrentSong($albumcover = true);

    abstract public function getCurrentAudience();
}