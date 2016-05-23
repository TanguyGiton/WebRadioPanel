<?php

namespace AppBundle\Utils;


abstract class StreamingProvider
{
    abstract function getCurrentSong();
}