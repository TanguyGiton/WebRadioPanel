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

    public function getCurrentSong()
    {
        if ($cached = $this->cache->fetch("currentsong")) {
            $currentsong = $cached;
        } else {
            $response = $this->restClient->get('http://api.radionomy.com/currentsong.cfm?radiouid=' . $this->getRadioUID() . '&apikey=' . $this->getApiKey() . '&callmeback=yes&type=xml');

            $xml = new \SimpleXMLElement($response->getContent());

            $currentsong['artist'] = (string)$xml->track->artists;
            $currentsong['title'] = (string)$xml->track->title;

            $callback = intval((intval($xml->track->callmeback)) / 1000);

            $currentsong['lifetime'] = time() + $callback;

            $this->cache->save("currentsong", $currentsong, $callback);
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
}