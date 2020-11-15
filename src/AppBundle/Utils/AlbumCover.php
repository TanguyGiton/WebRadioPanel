<?php

namespace AppBundle\Utils;


use Circle\RestClientBundle\Services\RestClient;

class AlbumCover
{
    private $restClient;

    private $country = 'fr';

    /**
     * AlbumCover constructor.
     * @param RestClient $restClient
     */
    public function __construct(RestClient $restClient)
    {
        $this->restClient = $restClient;
    }

    /**
     * @param $title
     * @param $artist
     * @return mixed|null
     */
    public function getImageOnItunes($title, $artist)
    {
        $result = $this->searchOnItunes($title . ' ' . $artist, 1);

        if (0 !== count($result)) {
            $albumcoverurl = $result[0]['albumcover'];

            $albumcoverurl = str_replace('100x100', '600x600', $albumcoverurl);

            $response = $this->restClient->get($albumcoverurl);

            if ($response->isOk()) {
                return $albumcoverurl;
            }
        }

        return null;
    }

    /**
     * @param $query
     * @param int $limit
     * @return array
     */
    public function searchOnItunes($query, $limit = 5)
    {
        $result = [];

        $query = urlencode($query);
        $response = $this->restClient->get('https://itunes.apple.com/search?term=' . $query . '&media=music&entity=song&limit=' . $limit . '&country=' . $this->country);

        $data = json_decode($response->getContent());

        $data = $data->results;
        foreach ($data as $key => $value) {
            $result[$key]['albumcover'] = $value->artworkUrl100;
            $result[$key]['artist'] = $value->artistName;
            $result[$key]['title'] = $value->trackName;
        }
        return $result;
    }
}