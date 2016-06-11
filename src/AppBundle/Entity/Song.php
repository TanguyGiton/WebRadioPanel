<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Song
 *
 * @ORM\Table(name="song")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SongRepository")
 *
 * @Vich\Uploadable
 */
class Song
{
    /**
     * @Vich\UploadableField(mapping="album_cover", fileNameProperty="imageName")
     *
     * @Assert\Image(
     *     minWidth=300,
     *     minHeight=300,
     *     allowPortrait=false,
     *     allowLandscape=false,
     *     minWidthMessage="user.image_file.min_width",
     *     minHeightMessage="user.image_file.min_height",
     *     maxWidthMessage="user.image_file.max_width",
     *     maxHeightMessage="user.image_file.max_height",
     *     groups={"Registration", "Profile"}
     * )
     *
     * @var File
     */
    protected $imageFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string
     */
    protected $imageName;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=100)
     *
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="artist", type="string", length=100)
     */
    private $artist;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="addtime", type="datetime")
     */
    private $addtime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatetime", type="datetime")
     */
    private $updatetime;

    /**
     * @var int
     */
    private $lifetime;

    public function __construct()
    {
        if ($this->getUpdatetime() === null) {
            $this->setUpdatetime(new \DateTime());
        }
        if ($this->getAddtime() === null) {
            $this->setAddtime(new \DateTime());
        }
    }

    /**
     * Get updatetime
     *
     * @return \DateTime
     */
    public function getUpdatetime()
    {
        return $this->updatetime;
    }

    /**
     * Set updatetime
     *
     * @param \DateTime $updatetime
     *
     * @return Song
     */
    public function setUpdatetime($updatetime)
    {
        $this->updatetime = $updatetime;

        return $this;
    }

    /**
     * Get addtime
     *
     * @return \DateTime
     */
    public function getAddtime()
    {
        return $this->addtime;
    }

    /**
     * Set addtime
     *
     * @param \DateTime $addtime
     *
     * @return Song
     */
    public function setAddtime($addtime)
    {
        $this->addtime = $addtime;

        return $this;
    }

    /**
     * @return File
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @param File|UploadedFile $image
     *
     * @return User
     */
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        if ($image) {
            $this->updateUpdatetime();
        }

        return $this;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function updateUpdatetime()
    {
        $this->setUpdatetime(new \DateTime());
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Song
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get artist
     *
     * @return string
     */
    public function getArtist()
    {
        return $this->artist;
    }

    /**
     * Set artist
     *
     * @param string $artist
     *
     * @return Song
     */
    public function setArtist($artist)
    {
        $this->artist = $artist;

        return $this;
    }

    /**
     * Get imageName
     *
     * @return string
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    /**
     * Set imageName
     *
     * @param string $imageName
     *
     * @return Song
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;

        return $this;
    }

    /**
     * @return int
     */
    public function getLifetime()
    {
        return $this->lifetime;
    }

    /**
     * @param int $lifetime
     */
    public function setLifetime($lifetime)
    {
        $this->lifetime = $lifetime;
    }
}
