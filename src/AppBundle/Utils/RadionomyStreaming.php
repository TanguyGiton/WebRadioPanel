<?php

namespace AppBundle\Utils;

use Circle\RestClientBundle\Services\RestClient;
use Doctrine\Common\Cache\PhpFileCache;

class RadionomyStreaming extends StreamingProvider
{
    private $restClient;

    private $cache;

    private $radioUID;

    private $apiKey;

    public function __construct(RestClient $restClient, PhpFileCache $cache, $radioIUD, $apiKey)
    {
        $this->setRadioUID($radioIUD);
        $this->setApiKey($apiKey);

        $this->restClient = $restClient;
        $this->cache = $cache;
    }

    /**
     * @param bool $albumcover
     * @return false|mixed
     */
    public function getCurrentSong($albumcover = true)
    {
        if ($cached = $this->cache->fetch("currentsong")) {
            $currentsong = $cached;
        } else {
            $url = 'http://api.radionomy.com/currentsong.cfm?radiouid=' . $this->getRadioUID() . '&apikey=' . $this->getApiKey() . '&callmeback=yes&type=xml&cover=yes';

            $response = $this->restClient->get($url);

            $xml = new \SimpleXMLElement($response->getContent());

            $currentsong['artist'] = (string)$xml->track->artists;
            $currentsong['title'] = (string)$xml->track->title;
            $currentsong['albumcover'] = (string)$xml->track->cover;

            $callback = (int)((int)$xml->track->callmeback / 1000);

            $currentsong['lifetime'] = time() + $callback;

            $this->cache->save('currentsong', $currentsong, $callback);
        }

        if (!$albumcover) {
            unset($currentsong['albumcover']);
        }

        return $currentsong;
    }

    /**
     * @return mixed
     */
    public function getRadioUID()
    {
        return $this->radioUID;
    }

    /**
     * @param mixed $radioUID
     */
    public function setRadioUID($radioUID)
    {
        $this->radioUID = $radioUID;
    }

    /**
     * @return mixed
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param mixed $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @return false|mixed
     */
    public function getCurrentAudience()
    {
        if ($cached = $this->cache->fetch("currentaudience")) {
            $currentaudience = $cached;
        } else {
            $url = 'http://api.radionomy.com/currentaudience.cfm?radiouid=' . $this->getRadioUID() . '&apikey=' . $this->getApiKey();

            $response = $this->restClient->get($url);

            $currentaudience['currentaudience'] = (int)$response->getContent();

            $callback = 5 * 60;

            $currentaudience['lifetime'] = time() + $callback;

            $this->cache->save('currentaudience', $currentaudience, $callback);
        }

        return $currentaudience;
    }

}