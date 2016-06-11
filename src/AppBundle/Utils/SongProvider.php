<?php

namespace AppBundle\Utils;

use AppBundle\Entity\Song;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class SongProvider
{
    protected $albumCover;

    protected $streamingProvider;

    protected $em;

    protected $vich;

    protected $tempDir;

    /**
     * SongProvider constructor.
     * @param AlbumCover $albumCover
     * @param StreamingProvider $streamingProvider
     * @param EntityManager $entityManager
     * @param string $tempDir
     */
    public function __construct(AlbumCover $albumCover, StreamingProvider $streamingProvider, EntityManager $entityManager, $tempDir)
    {
        $this->albumCover = $albumCover;
        $this->streamingProvider = $streamingProvider;
        $this->em = $entityManager;
        $this->tempDir = $tempDir;
    }

    public function processCurrentSong()
    {
        $streaminginfo = $this->streamingProvider->getCurrentSong();

        $song = $this->em->getRepository('AppBundle:Song')->findOneBy(array(
            'title' => $streaminginfo['title'],
            'artist' => $streaminginfo['artist'],
        ));

        if (!$song) {
            $song = new Song();
            $song->setTitle($streaminginfo['title'])
                ->setArtist($streaminginfo['artist']);

            if (!empty($streaminginfo['albumcover'])) {
                $albumcover = $streaminginfo['albumcover'];
            } else {
                $albumcover = $this->albumCover->getImageOnItunes($song->getTitle(), $song->getArtist());
            }

            if ($albumcover) {

                $infoFile = new \SplFileInfo($albumcover);
                $basename = $infoFile->getBasename();

                $path = $this->tempDir . '/' . $basename;

                file_put_contents($path, file_get_contents($albumcover));

                $albumcoverFile = new UploadedFile($path, $basename, null, null, null, true);
                $song->setImageFile($albumcoverFile);
            }

            $this->em->persist($song);
            $this->em->flush();
        }
        
        $song->setLifetime($streaminginfo['lifetime']);

        return $song;
    }
}